=== CD Sealion ===
Contributors: declanwalsh
Tags: blog, custom-background, custom-logo, featured-images, grid-layout, one-column, theme-options, threaded-comments
Requires at least: 6.0
Tested up to: 6.4
Requires PHP: 8.1
Stable tag: 1.0.0
License: Private Use
License URI:

A minimal WordPress theme for cataloguing and reviewing CD collections with a simple "Keep It / Delete It" verdict system.

== Description ==

CD Sealion is a custom WordPress theme designed for music enthusiasts who want to catalogue and review their CD collections. The theme features a clean, Instagram-style grid layout for the homepage and individual review pages with custom verdict fields.

Key Features:

* Responsive grid homepage displaying CD cover artwork
* Individual review pages with album information, verdict, and thoughts
* Custom "Keep It / Delete It" verdict field with colour coding (green for keep, red for delete)
* Artist custom field for easy metadata management
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

1. Upload the theme files to `/wp-content/themes/cd-sealion/`
2. Activate the theme through the 'Appearance > Themes' menu in WordPress
3. Configure your site title in Settings → General
4. Set your site icon in Appearance → Customize
5. Create your first CD review post with a featured image

== Frequently Asked Questions ==

= How do I add a CD review? =

Create a new post, add the album title as the post title, fill in the Artist and Verdict custom fields in the editor, upload the CD cover as the featured image, and write your review in the content area.

= What image size should I use for CD covers? =

Square images work best for the grid layout. WordPress will automatically create appropriate thumbnail sizes from your uploaded featured images.

= Can I filter by verdict (Keep/Delete)? =

Not in version 1.0. This feature may be added in future releases.

= Does this theme support custom post types? =

No, CD reviews use standard WordPress posts to leverage built-in features like comments, RSS feeds, and the familiar editing interface.

== Changelog ==

= 1.0.0 =
* Initial release
* Grid homepage with responsive CD cover layout
* Single post template for CD reviews
* Custom meta boxes for Artist and Verdict fields
* About page template
* Comments support
* Mobile, tablet, and desktop responsive design

== Upgrade Notice ==

= 1.0.0 =
Initial release.

== Custom Fields ==

This theme adds two custom fields to posts:

* Artist (cd_artist): Text field for the artist or band name
* Verdict (cd_verdict): Dropdown with "Keep It" or "Delete It" options

These fields appear in the post editor sidebar when creating or editing posts.

== Copyright ==

CD Sealion WordPress Theme, Copyright 2026
CD Sealion is distributed under the terms of private use only.

== Credits ==

* System font stack for typography
* CSS Grid for layout
* No external libraries or frameworks
