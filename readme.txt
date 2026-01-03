=== Discard Sealion ===
Contributors: declanwalsh
Tags: blog, custom-background, custom-logo, featured-images, grid-layout, one-column, theme-options, threaded-comments
Requires at least: 6.0
Tested up to: 6.4
Requires PHP: 8.1
Stable tag: 1.0.0
License: Private Use
License URI:

A minimal WordPress theme for cataloguing and reviewing CD collections with a simple "Kept / Deleted" verdict system.

== Description ==

Discard Sealion is a custom WordPress theme designed for music enthusiasts who want to catalogue and review their CD collections. The theme features a clean, Instagram-style grid layout for the homepage and individual review pages with verdict display.

Key Features:

* Responsive grid homepage displaying CD cover artwork
* Individual review pages with album information, verdict, and thoughts
* Verdict display showing "Kept" (green) or "Deleted" (red) status
* Artist field using WordPress excerpt
* Verdict filtering via category pages
* Footer statistics showing total Kept and Deleted counts with links to filtered views
* Standard WordPress comments support on reviews
* About page template support
* Built-in RSS feed via WordPress core
* Mobile-first responsive design
* No external dependencies or plugins required

Design Highlights:

* Clean, minimal aesthetic with light colour scheme
* CSS Grid-based responsive layout (4 columns → 3 → 2)
* System font stack for fast loading
* Modern browser support (CSS Grid compatible)

== Installation ==

1. Upload the theme files to `/wp-content/themes/discard-sealion/`
2. Activate the theme through the 'Appearance > Themes' menu in WordPress
3. Configure your site title in Settings → General
4. Set your site icon in Appearance → Customize
5. Create your first CD review post with a featured image

== Frequently Asked Questions ==

= How do I add a CD review? =

Create a new post with the album title as the post title. Add the artist name in the Excerpt field, assign the post to either the "Keep" or "Delete" category, upload the CD cover as the featured image, and write your review in the content area.

= What image size should I use for CD covers? =

Square images work best for the grid layout. WordPress will automatically create appropriate thumbnail sizes from your uploaded featured images.

= Can I filter by verdict (Keep/Delete)? =

Yes. Click the "X Kept" or "Y Deleted" links in the footer to view filtered lists of CDs by verdict. You can also access these via the category archives.

= Does this theme support custom post types? =

No, CD reviews use standard WordPress posts to leverage built-in features like comments, RSS feeds, and the familiar editing interface.

= Where do I enter the artist name? =

The artist name is stored in the WordPress excerpt field. You can find this field in the post editor sidebar (it may need to be enabled via the three-dot menu → Preferences → Panels → Excerpt).

== Changelog ==

= 1.0.0 =
* Initial release
* Grid homepage with responsive CD cover layout
* Single post template for CD reviews
* Artist field using WordPress excerpt
* Verdict system using WordPress categories (Keep/Delete)
* Category archive pages with grid layout
* Footer statistics with verdict counts and filter links
* About page template
* Comments support
* Mobile, tablet, and desktop responsive design
* Automatic category creation on theme activation

== Upgrade Notice ==

= 1.0.0 =
Initial release.

== Data Storage ==

This theme uses WordPress built-in features for data storage:

* **Artist**: Stored in the WordPress excerpt field. Access via the Excerpt panel in the post editor.
* **Verdict**: Stored as WordPress categories. Two categories are automatically created on theme activation:
  - "Keep" - for CDs to keep in the collection
  - "Delete" - for CDs to discard from the collection
* **Album Title**: Standard WordPress post title
* **Review**: Standard WordPress post content
* **Cover Art**: WordPress featured image
* **Date Reviewed**: WordPress post date

The theme displays verdicts as "Kept" (green) or "Deleted" (red) on individual CD pages and provides category archive pages to view filtered lists.

== Copyright ==

Discard Sealion WordPress Theme, Copyright 2026
Discard Sealion is distributed under the terms of private use only.

== Credits ==

* System font stack for typography
* CSS Grid for layout
* No external libraries or frameworks
