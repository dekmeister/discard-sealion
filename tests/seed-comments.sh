#!/usr/bin/env bash
#
# seed-comments.sh — Import approved comments into a running WordPress instance
#
# Reads a JSON fixture file and creates one WordPress comment per entry,
# linking it to the correct post and optionally threading it as a reply.
#
# Usage:
#   tests/seed-comments.sh COMPOSE_FILE [FIXTURES_FILE]
#
#   COMPOSE_FILE   Path to the docker-compose.yml file for the WordPress instance.
#   FIXTURES_FILE  Path to a JSON array of comment objects.
#                  Defaults to tests/fixtures/comments.json (relative to this script).
#
# Each object in the JSON array must have:
#   post_title          string   Title of the post to comment on (must already exist)
#   author              string   Commenter display name
#   author_email        string   Commenter email
#   content             string   Comment body text
#   parent_author       string|null  Display name of the parent comment's author, or null
#   gmt_offset_minutes  int      Negative = minutes ago; creates a realistic past timestamp
#
# Requirements:
#   docker  — used to invoke WP-CLI via the wordpress:cli image
#   jq      — used to parse the fixture JSON
#   date    — GNU coreutils date (supports -d flag)
#
# Assumptions:
#   - WordPress is running via the given compose file
#   - The Discard Sealion theme is active and posts have been seeded already

set -euo pipefail

if [[ $# -lt 1 ]]; then
  echo "Usage: $(basename "$0") COMPOSE_FILE [FIXTURES_FILE]" >&2
  exit 1
fi

COMPOSE_FILE="$1"
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
FIXTURES="${2:-$SCRIPT_DIR/fixtures/comments.json}"

# ---------------------------------------------------------------------------
# Preflight checks
# ---------------------------------------------------------------------------

for cmd in docker jq date; do
  if ! command -v "$cmd" &>/dev/null; then
    echo "Error: '$cmd' is required but not installed." >&2
    exit 1
  fi
done

if [[ ! -f "$COMPOSE_FILE" ]]; then
  echo "Error: compose file not found: $COMPOSE_FILE" >&2
  exit 1
fi

if [[ ! -f "$FIXTURES" ]]; then
  echo "Error: fixture file not found: $FIXTURES" >&2
  exit 1
fi

# ---------------------------------------------------------------------------
# WP-CLI helper (same pattern as seed-posts.sh)
# ---------------------------------------------------------------------------

WP_CONTAINER=$(docker compose -f "$COMPOSE_FILE" ps -q wordpress)

if [[ -z "$WP_CONTAINER" ]]; then
  echo "Error: WordPress container is not running. Start it before seeding." >&2
  exit 1
fi

wpcli() {
  docker run --rm \
    --volumes-from "$WP_CONTAINER" \
    --network "container:$WP_CONTAINER" \
    -e WORDPRESS_DB_HOST=db \
    -e WORDPRESS_DB_USER=exampleuser \
    -e WORDPRESS_DB_PASSWORD=examplepass \
    -e WORDPRESS_DB_NAME=exampledb \
    wordpress:cli wp "$@" --allow-root
}

echo "Seeding comments from: $FIXTURES"
echo "---"

CREATED=0
FAILED=0

while IFS= read -r entry; do
  post_title=$(jq -r '.post_title' <<< "$entry")
  author=$(jq -r '.author' <<< "$entry")
  author_email=$(jq -r '.author_email' <<< "$entry")
  content=$(jq -r '.content' <<< "$entry")
  parent_author=$(jq -r '.parent_author // empty' <<< "$entry")
  gmt_offset=$(jq -r '.gmt_offset_minutes' <<< "$entry")

  # Resolve post ID by title.
  post_json=$(wpcli post list \
    --post_type=post \
    --post_status=publish \
    --fields=ID,post_title \
    --format=json 2>/dev/null)
  post_id=$(jq -r --arg title "$post_title" '.[] | select(.post_title == $title) | .ID' <<< "$post_json" || true)

  if [[ -z "$post_id" ]]; then
    echo "SKIP: post not found: $post_title" >&2
    FAILED=$((FAILED + 1))
    continue
  fi

  # Compute comment timestamp from offset (negative = minutes ago).
  comment_date_gmt=$(date -u -d "${gmt_offset} minutes" +"%Y-%m-%d %H:%M:%S" 2>/dev/null \
    || date -u -v "${gmt_offset}M" +"%Y-%m-%d %H:%M:%S")

  # Resolve parent comment ID if a parent author is specified.
  parent_id=0
  if [[ -n "$parent_author" ]]; then
    parent_json=$(wpcli comment list \
      --post_id="$post_id" \
      --fields=comment_ID,comment_author \
      --format=json 2>/dev/null)
    parent_id=$(jq -r --arg author "$parent_author" \
      '.[] | select(.comment_author == $author) | .comment_ID' \
      <<< "$parent_json" | head -1 || true)
    parent_id="${parent_id:-0}"
  fi

  COMMENT_ID=$(wpcli comment create \
    --comment_post_ID="$post_id" \
    --comment_author="$author" \
    --comment_author_email="$author_email" \
    --comment_content="$content" \
    --comment_approved=1 \
    --comment_date_gmt="$comment_date_gmt" \
    --comment_parent="$parent_id" \
    --porcelain)

  if [[ -n "$parent_author" ]]; then
    echo "Created comment $COMMENT_ID on \"$post_title\" by $author (reply to $parent_author, parent ID: $parent_id)"
  else
    echo "Created comment $COMMENT_ID on \"$post_title\" by $author"
  fi
  CREATED=$((CREATED + 1))

done < <(jq -c '.[]' "$FIXTURES")

echo "---"
echo "Done. Created $CREATED comments ($FAILED skipped)."
