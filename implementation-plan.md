# Implementation Plan

Ordered sequence for building the theme. Each phase should be completed and tested before proceeding.

## Phase 1: Foundation
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
- [ ] Theme appears in Appearance → Themes
- [ ] Theme activates without PHP errors
- [ ] Site loads (even if unstyled)

---

## Phase 2: Custom Fields
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
- [ ] Meta box appears when editing a post
- [ ] Artist field saves and persists
- [ ] Verdict dropdown saves and persists
- [ ] Values survive post update

---

## Phase 3: Header & Footer
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
- [ ] Header displays site title
- [ ] Custom logo displays if set
- [ ] Navigation links work
- [ ] Footer renders
- [ ] Base typography looks correct

---

## Phase 4: Homepage Grid
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
- [ ] Homepage shows grid of CD covers
- [ ] Clicking cover goes to single post
- [ ] 4 columns on desktop (1200px+)
- [ ] 3 columns on tablet (768px-1199px)
- [ ] 2 columns on mobile (<768px)
- [ ] Posts without featured images show placeholder
- [ ] Empty state message displays when no posts

---

## Phase 5: Single CD View
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
- [ ] Single post shows album title as heading
- [ ] Artist displays correctly
- [ ] "Keep It" shows in green
- [ ] "Delete It" shows in red
- [ ] Post content (thoughts) renders
- [ ] Featured image displays
- [ ] Missing fields handled gracefully

---

## Phase 6: Comments
**Goal**: Working comment system on CD reviews

### Tasks
1. Create `comments.php`:
   - Comment list display
   - Comment form
   - Comment count
2. Include comments in `single.php`
3. Style comments in `style.css`

### Verification
- [ ] Comments section appears on single posts
- [ ] Can submit new comment
- [ ] Existing comments display
- [ ] Comment moderation works in admin

---

## Phase 7: Static Pages
**Goal**: About page works correctly

### Tasks
1. Create `page.php`:
   - Simple page template
   - Page title
   - Page content
2. Style page content

### Verification
- [ ] About page renders correctly
- [ ] Page title displays
- [ ] Content displays
- [ ] Navigation link to About works

---

## Phase 8: Polish & Edge Cases
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
- [ ] All README testing checklist items pass
- [ ] No PHP warnings/notices
- [ ] No browser console errors
- [ ] Acceptable Lighthouse scores

---

## Phase 9: Documentation & Deployment
**Goal**: Ready for production

### Tasks
1. Review and finalise README.md
2. Create sample content guide (optional)
3. Final Git commit with clean history
4. Deploy to production following deployment steps
5. Post-deployment verification

### Verification
- [ ] Production site works identically to local
- [ ] Can create new CD review end-to-end
- [ ] RSS feed accessible
- [ ] Comments working
