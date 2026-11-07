# Changelog

All notable changes to Discard Sealion are recorded here. The format is based
on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/), and the project
follows [Semantic Versioning](https://semver.org/).

## Unreleased

### Changed

- Comment times are shown in the visitor's local timezone, labelled with the
  zone (e.g. "3:45 pm EST"). Without JavaScript they fall back to site time.

### Fixed

- The Recent Comments "Unread" filter now compares against a true UTC
  timestamp instead of one derived from the site-local time.

## 1.0.0

- Initial release
