# Changelog

All notable changes to Discard Sealion are recorded here. The format is based
on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/), and the project
follows [Semantic Versioning](https://semver.org/).

## 1.1.0 - 2026-11-08

### Added

- Comments that have reached the maximum reply depth (Settings → Discussion)
  now show a "Reply limit reached for this thread" note where the Reply link
  would be, instead of silently omitting it.

### Changed

- Nested comments use far less width on phones: replies are marked by a thin
  vertical line at every level with a small, constant indent, replacing the
  deep indentation (which also carried unintended default list padding).
- Comments are more compact: the avatar sits beside the author name and date
  (name above date) instead of above the text, at 40px, with tighter spacing
  between comments.
- Comment times are shown in the visitor's local timezone, labelled with the
  zone (e.g. "3:45 pm EST"). Without JavaScript they fall back to site time.

### Fixed

- The Recent Comments "Unread" filter now compares against a true UTC
  timestamp instead of one derived from the site-local time.

## 1.0.0

- Initial release
