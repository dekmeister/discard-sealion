=== Discard Sealion ===
Contributors: declanwalsh (dekmeister)
Tags: blog, custom-logo, featured-images, grid-layout, one-column, theme-options, threaded-comments
Requires at least: 6.0
Tested up to: 6.4
Requires PHP: 8.1
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

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
* Related Sites section in the footer with configurable links to other sites
* Automatic header navigation generated from published WordPress pages
* Standard WordPress comments support on reviews
* WordPress page template support (About, etc.)
* Enhanced RSS feed with verdict, featured image, and formatted titles
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
4. Set your site logo in Appearance → Customize
5. Create your first CD review post with a featured image
6. Optionally, add related site links under Appearance → Related Sites

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

= How do I add links to related sites in the footer? =

Go to Appearance → Related Sites in the WordPress admin. You can add site names and URLs, set the section heading, and control how many links appear. Only sites with both a name and URL are displayed.

= What does the RSS feed include? =

Each RSS item includes the album title and artist formatted as "Album - Artist", a description of the format "Album - Artist - Kept/Deleted", the verdict and featured image injected into the content, and category labels displayed as "Kept" or "Deleted".

== Changelog ==

= 1.0.0 =
* Initial release
* Grid homepage with responsive CD cover layout
* Single post template for CD reviews
* Artist field using WordPress excerpt
* Verdict system using WordPress categories (Keep/Delete)
* Category archive pages with grid layout showing all posts
* Footer statistics with verdict counts and filter links
* Related Sites section in footer with admin configuration page
* WordPress page template for static pages
* Comments support
* Enhanced RSS feed: formatted title (Album - Artist), verdict summary in description, featured image in content, category labels mapped to Kept/Deleted
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
* **Related Sites**: Stored in WordPress options via the theme settings page (Appearance → Related Sites).

The theme displays verdicts as "Kept" (green) or "Deleted" (red) on individual CD pages and provides category archive pages to view filtered lists.

== Copyright ==

Discard Sealion WordPress Theme, Copyright 2026 Declan Walsh
Discard Sealion is distributed under the terms of the GNU GPL v2 or later.

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

== Credits ==

* System font stack for typography
* CSS Grid for layout
* No external libraries or frameworks
