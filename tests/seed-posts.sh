#!/usr/bin/env bash
#
# seed-posts.sh — Import CD review posts into a running WordPress instance
#
# Reads a JSON fixture file and creates one WordPress post per entry, assigning
# the correct category and optionally attaching a featured image.
#
# Usage:
#   tests/seed-posts.sh COMPOSE_FILE [FIXTURES_FILE]
#
#   COMPOSE_FILE   Path to the docker-compose.yml file for the WordPress instance.
#   FIXTURES_FILE  Path to a JSON array of post objects.
#                  Defaults to tests/fixtures/posts.json (relative to this script).
#
# Each object in the JSON array must have:
#   title      string   Post title (album name)
#   excerpt    string   Post excerpt (artist name)
#   content    string   Post body (review text)
#   category   string   "keep" or "delete"
#   has_image  bool     If true, imports placeholder.png as the featured image
#
# Requirements:
#   docker  — used to invoke WP-CLI via the wordpress:cli image
#   jq      — used to parse the fixture JSON
#
# Assumptions:
#   - WordPress is running via the given compose file
#   - The Discard Sealion theme is active and the Keep/Delete categories exist
#     (created by the after_switch_theme hook in functions.php)
#   - placeholder.png exists at:
#     /var/www/html/wp-content/themes/discard-sealion/assets/images/placeholder.png
#     inside the WordPress container

set -euo pipefail

if [[ $# -lt 1 ]]; then
  echo "Usage: $(basename "$0") COMPOSE_FILE [FIXTURES_FILE]" >&2
  exit 1
fi

COMPOSE_FILE="$1"
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
FIXTURES="${2:-$SCRIPT_DIR/fixtures/posts.json}"

# ---------------------------------------------------------------------------
# Preflight checks
# ---------------------------------------------------------------------------

for cmd in docker jq; do
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
# WP-CLI helper
#
# Every WP-CLI invocation runs in a disposable wordpress:cli container that
# shares the WordPress container's filesystem and network. DB credentials must
# be passed explicitly because wp-config.php reads them via getenv_docker()
# at runtime and the CLI container has no environment of its own.
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

# ---------------------------------------------------------------------------
# Resolve category IDs
# ---------------------------------------------------------------------------

KEEP_ID=$(wpcli term list category --slug=keep --field=term_id)
DELETE_ID=$(wpcli term list category --slug=delete --field=term_id)

if [[ -z "$KEEP_ID" || -z "$DELETE_ID" ]]; then
  echo "Error: Keep or Delete category not found." >&2
  echo "Ensure the Discard Sealion theme is active so its setup hook has run." >&2
  exit 1
fi

echo "Category IDs — keep: $KEEP_ID, delete: $DELETE_ID"
echo "Seeding posts from: $FIXTURES"
echo "---"

# ---------------------------------------------------------------------------
# Create posts
# ---------------------------------------------------------------------------

CREATED=0
WITH_IMAGE=0

while IFS= read -r post; do
  title=$(jq -r '.title' <<< "$post")
  excerpt=$(jq -r '.excerpt' <<< "$post")
  content=$(jq -r '.content' <<< "$post")
  category=$(jq -r '.category' <<< "$post")
  has_image=$(jq -r '.has_image' <<< "$post")

  if [[ "$category" == "keep" ]]; then
    cat_id="$KEEP_ID"
  else
    cat_id="$DELETE_ID"
  fi

  POST_ID=$(wpcli post create \
    --post_title="$title" \
    --post_excerpt="$excerpt" \
    --post_content="$content" \
    --post_status=publish \
    --post_category="$cat_id" \
    --porcelain)

  echo "Created post $POST_ID: $title [$category]"
  CREATED=$((CREATED + 1))

  if [[ "$has_image" == "true" ]]; then
    ATTACHMENT_ID=$(wpcli media import \
      /var/www/html/wp-content/themes/discard-sealion/assets/images/placeholder.png \
      --post_id="$POST_ID" \
      --title="$title cover" \
      --porcelain)
    wpcli post meta set "$POST_ID" _thumbnail_id "$ATTACHMENT_ID"
    echo "  -> Featured image set (attachment $ATTACHMENT_ID)"
    WITH_IMAGE=$((WITH_IMAGE + 1))
  fi

done < <(jq -c '.[]' "$FIXTURES")

# ---------------------------------------------------------------------------
# Summary
# ---------------------------------------------------------------------------

echo "---"
echo "Done. Created $CREATED posts ($WITH_IMAGE with featured images)."
