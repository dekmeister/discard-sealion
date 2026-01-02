# Implementation Plan

Ordered sequence for building the theme. Each phase should be completed and tested before proceeding.

## Phase 1: Foundation ✓ COMPLETE
**Goal**: Minimal viable theme that activates without errors

### Tasks
1. Create directory structure
2. Create `style.css` with theme header metadata
3. Create minimal `index.php` (required fallback)
4. Create `functions.php` with basic theme setup:
   - `add_theme_support( 'title-tag' )`
   - `add_theme_support( 'post-thumbnails' )`
   - `add_theme_support( 'custom-logo' )`
   - `add_theme_support( 'automatic-feed-links' )`

### Verification
- [x] Theme appears in Appearance → Themes
- [x] Theme activates without PHP errors
- [x] Site loads (even if unstyled)

---

## Phase 2: Custom Fields ✓ COMPLETE
**Goal**: Artist and Verdict fields available in post editor

### Tasks
1. Create `inc/custom-fields.php`:
   - Register `cd_artist` meta (string)
   - Register `cd_verdict` meta (string)
2. Create `inc/meta-boxes.php`:
   - Add meta box to post editor
   - Artist text input field
   - Verdict dropdown (Keep/Delete)
   - Save handlers with sanitisation
3. Include files in `functions.php`

### Verification
- [x] Meta box appears when editing a post
- [x] Artist field saves and persists
- [x] Verdict dropdown saves and persists
- [x] Values survive post update

---

## Phase 3: Header & Footer ✓ COMPLETE
**Goal**: Consistent site chrome across all pages

### Tasks
1. Create `header.php`:
   - DOCTYPE and `<head>` with `wp_head()`
   - Site title (linked to home)
   - Site icon/logo support
   - Simple navigation (Home | About)
2. Create `footer.php`:
   - Minimal footer content
   - `wp_footer()` call
3. Add CSS custom properties to `style.css`:
   - Colour palette
   - Typography scale
   - Spacing variables
4. Add base styles:
   - Reset/normalise basics
   - Body typography
   - Header layout
   - Footer layout

### Verification
- [x] Header displays site title
- [x] Custom logo displays if set
- [x] Navigation links work
- [x] Footer renders
- [x] Base typography looks correct

---

## Phase 4: Homepage Grid ✓ COMPLETE
**Goal**: CD covers display in responsive grid

### Tasks
1. Create `front-page.php`:
   - Custom query for posts
   - Grid container markup
   - Loop through posts
2. Create `template-parts/content-grid-item.php`:
   - Featured image display
   - Link to single post
   - Aspect ratio handling (square crop)
3. Create `template-parts/content-none.php`:
   - Message for no posts state
4. Add grid CSS to `style.css`:
   - CSS Grid layout
   - Responsive breakpoints (4→3→2 columns)
   - Hover effects (subtle)
   - Image aspect ratio

### Verification
- [x] Homepage shows grid of CD covers
- [x] Clicking cover goes to single post
- [x] 4 columns on desktop (1200px+)
- [x] 3 columns on tablet (768px-1199px)
- [x] 2 columns on mobile (<768px)
- [x] Posts without featured images show placeholder
- [x] Empty state message displays when no posts

---

## Phase 5: Single CD View ✓ COMPLETE
**Goal**: Full CD review page with all fields

### Tasks
1. Create `single.php`:
   - Include header/footer
   - Load single post template part
2. Create `template-parts/content-single-cd.php`:
   - Album title (post title)
   - Artist name (from meta)
   - Verdict with colour styling
   - Thoughts (post content)
   - Featured image (full size)
3. Create `inc/template-tags.php`:
   - `cd_collection_get_artist()` - returns artist
   - `cd_collection_get_verdict()` - returns verdict
   - `cd_collection_verdict_display()` - returns formatted verdict HTML
4. Add single post CSS:
   - Layout structure
   - Verdict styling (green/red)
   - Image presentation
   - Typography for content

### Verification
- [x] Single post shows album title as heading
- [x] Artist displays correctly
- [x] "Keep It" shows in green
- [x] "Delete It" shows in red
- [x] Post content (thoughts) renders
- [x] Featured image displays
- [x] Missing fields handled gracefully

---

## Phase 6: Comments ✓ COMPLETE
**Goal**: Working comment system on CD reviews

### Tasks
1. Create `comments.php`:
   - Comment list display
   - Comment form
   - Comment count
2. Include comments in `single.php`
3. Style comments in `style.css`

### Verification
- [x] Comments section appears on single posts
- [x] Can submit new comment
- [x] Existing comments display
- [x] Comment moderation works in admin

---

## Phase 7: Static Pages ✓ COMPLETE
**Goal**: About page works correctly

### Tasks
1. Create `page.php`:
   - Simple page template
   - Page title
   - Page content
2. Style page content

### Verification
- [x] About page renders correctly
- [x] Page title displays
- [x] Content displays
- [x] Navigation link to About works

---

## Phase 8: Polish & Edge Cases ✓ COMPLETE
**Goal**: Production-ready theme

### Tasks
1. Add `assets/images/placeholder.png` for missing images
2. Add `screenshot.png` for theme preview
3. Test all edge cases from README checklist
4. Verify RSS feed works
5. Test with WordPress debug mode
6. Verify no console errors
7. Accessibility check:
   - Alt text on images
   - Colour contrast
   - Keyboard navigation
8. Performance check:
   - No unused CSS
   - Images appropriately sized

### Verification
- [x] All README testing checklist items pass
- [x] No PHP warnings/notices
- [x] No browser console errors
- [x] Acceptable Lighthouse scores

---

## Phase 9: Documentation & Deployment ✓ COMPLETE
**Goal**: Ready for production

### Tasks
1. Review and finalise README.md
2. Review and finalise CLAUDE.md
3. Review and finalise implementation-plan.md
4. Verify all theme files are complete

### Status
All implementation phases complete! Theme is ready for deployment.

### Deployment Notes
- All theme files are in place and tested locally
- Production deployment to be done separately by user
- Follow deployment steps in README.md when ready

### Local Verification (Completed)
- [x] Theme activates without errors
- [x] All custom fields work correctly
- [x] Homepage grid displays properly
- [x] Single post view works with all fields
- [x] Comments system functional
- [x] Static pages render correctly
- [x] Navigation dynamically includes all pages
- [x] RSS feed accessible via WordPress core
- [x] All edge cases handled gracefully
