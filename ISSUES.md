# Open Issues

Findings from WordPress code review and Theme Check — 2026-05-10.

---

## Medium

### [M1] All comment objects fetched to count distinct posts
**File:** `inc/recent-comments.php:54–64`

The `$recent_objs` query uses `'number' => 0` (unlimited) to load every approved comment object from the last 30 days, then counts distinct post IDs in PHP via `wp_list_pluck` + `array_unique`. Benign at current scale; scales poorly.

**Fix:** Replace with a `$wpdb->get_var( "SELECT COUNT(DISTINCT comment_post_ID) FROM ..." )` query, or add `'fields' => 'ids'` and do the distinct-post count via a targeted query rather than loading full objects.

### [M2] `comment-reply` script not enqueued
**File:** `functions.php` (missing enqueue)  
**Source:** Theme Check INFO

Threaded comment replies won't animate into place without the `comment-reply` script. The checker found a reference to `comment-reply` but it is not enqueued. Without it, clicking "Reply" on a comment reloads the page or does nothing, depending on the browser.

**Fix:** Add to `discard_sealion_scripts()`:
```php
if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
    wp_enqueue_script( 'comment-reply' );
}
```

---

## Low

### [L1] `the_title()` echoed without escaping
**Files:** `template-parts/content-grid-item.php:37`, `template-parts/content-single-cd.php:33`

Both use `the_title()` directly inside HTML. WordPress sanitises titles at save time so exploitation is negligible, but "escape on output" still applies.

**Fix:** Replace with `echo esc_html( get_the_title() );`.

### [L2] `bloginfo( 'name' )` echoed unescaped
**Files:** `header.php:28`, `footer.php:36`

`bloginfo()` passes through the `bloginfo` filter but does not guarantee HTML escaping at the call site.

**Fix:** Use `echo esc_html( get_bloginfo( 'name' ) );` in both places.

### [L3] `inc/template-tags.php` missing direct-access guard

Every other `inc/` file starts with `defined( 'ABSPATH' ) || exit;`. `template-tags.php` omits it.

**Fix:** Add `if ( ! defined( 'ABSPATH' ) ) { exit; }` at the top of the file.

### [L4] Inline `<script>` in admin options page
**File:** `inc/theme-options.php:184–210`

The "Add Site" row builder is a bare `<script>` block emitted inside the render callback rather than enqueued via `wp_enqueue_script`. No security risk (the `siteIndex` seed is a PHP integer), but it bypasses the asset pipeline.

**Fix:** Extract to `assets/js/admin-related-sites.js`, enqueue conditionally on the `discard-sealion-related-sites` screen via `admin_enqueue_scripts`, and pass `siteIndex` via `wp_localize_script`.

### [L5] `posts_per_page => -1` on user-reachable pages
**Files:** `front-page.php:19`, `functions.php:137`

The homepage `WP_Query` and the `pre_get_posts` hook for Keep/Delete archives both use `-1`. Fine at current collection size; worth revisiting if the collection grows large.

**Fix:** No action needed now. If page load degrades, add a cap or introduce pagination.

### [L6] Standard WordPress CSS classes not defined
**File:** `style.css`  
**Source:** Theme Check RECOMMENDED

The following WordPress-generated classes have no styles defined. Missing them means core-generated markup (captions, sticky posts, alignment blocks, comment author highlighting) may render unstyled:

- `.wp-caption`, `.wp-caption-text`
- `.sticky`
- `.gallery-caption`
- `.bypostauthor`
- `.alignleft`, `.alignright`, `.aligncenter`
- `.screen-reader-text` (also an accessibility requirement — hides content visually while keeping it available to screen readers)

**Fix:** Add stub rules for each class. The `.screen-reader-text` implementation should follow the [WordPress Codex pattern](https://make.wordpress.org/accessibility/handbook/markup/the-css-class-screen-reader-text/) (position absolute, clip, 1px size). The align classes are a one-liner each.

### [L7] `Tags:` line missing from `style.css` header
**Source:** Theme Check INFO

The theme header in `style.css` has no `Tags:` line. No functional impact, but required for WordPress.org submission and useful for searchability.

**Fix:** Add a `Tags:` line to the style.css header, e.g. `Tags: white, one-column, custom-logo, custom-background, featured-images, blog, personal`.

### [L8] `ISSUES.md` not excluded from dist
**File:** `.distignore`

`ISSUES.md` is checked into the repo but not listed in `.distignore`, so it would be bundled into the production zip built by `bin/build-dist.sh`.

**Fix:** Add `/ISSUES.md` to `.distignore`.
