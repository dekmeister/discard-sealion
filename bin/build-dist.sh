#!/usr/bin/env bash
#
# Build a distributable zip of the theme, honouring .distignore.
# Output: dist/discard-sealion.zip
#
# Usage: ./bin/build-dist.sh

set -euo pipefail

SLUG="discard-sealion"
ROOT="$(cd "$(dirname "$0")/.." && pwd)"
DIST="$ROOT/dist"
STAGE="$DIST/$SLUG"
ZIP="$DIST/$SLUG.zip"

if ! command -v rsync >/dev/null 2>&1; then
	echo "error: rsync is required" >&2
	exit 1
fi
if ! command -v zip >/dev/null 2>&1; then
	echo "error: zip is required" >&2
	exit 1
fi

cd "$ROOT"

rm -rf "$STAGE" "$ZIP"
mkdir -p "$STAGE"

# .distignore uses the same anchored-path syntax as rsync's --exclude-from
# (a leading "/" anchors to the transfer root). Also exclude the dist dir
# itself so repeat builds don't recurse into prior output.
rsync -a \
	--exclude-from="$ROOT/.distignore" \
	--exclude='/dist' \
	./ "$STAGE/"

cd "$DIST"
zip -rq "$ZIP" "$SLUG"
rm -rf "$STAGE"

echo "Built: $ZIP"
