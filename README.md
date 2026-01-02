# CD Collection WordPress Theme

A minimal WordPress theme for cataloguing and reviewing CD collections with a simple "Keep It / Delete It" verdict system.

## Project Overview

**Purpose**: Personal music collection catalogue displaying CD reviews in an Instagram-style grid layout.

**Audience**: Friends and family (< 100 daily visits)

**Key Features**:
- Responsive grid homepage showing CD cover artwork
- Individual review pages with album info, verdict, and thoughts
- Custom "Keep It / Delete It" verdict field with colour coding
- Standard WordPress comments on reviews
- About page support
- Built-in RSS via WordPress core

## Design Decisions

### Architecture Principles

| Decision | Rationale |
|----------|-----------|
| Custom theme (not child theme) | No parent theme dependency, full control, simpler maintenance |
| No external plugins required | Reduces attack surface, update burden, and complexity |
| Custom fields via `register_post_meta()` | Native WordPress API, no ACF dependency |
| Standard post type for CDs | Leverages existing WordPress UI, RSS, comments without custom post type complexity |
| CSS Grid for layout | Native browser support, no framework needed |
| Vanilla CSS (no preprocessors) | Simpler build process, direct editing, adequate for project scope |
| No build tooling | Theme works directly without npm/webpack complexity |

### Data Model

Each CD review is a standard WordPress **Post** with:

| Field | Storage | Purpose |
|-------|---------|---------|
| Title | Post title | Album title |
| Artist | Custom meta: `cd_artist` | Artist/band name |
| Verdict | Custom meta: `cd_verdict` | "keep" or "delete" |
| Thoughts | Post content | Your review/thoughts |
| Cover art | Featured image | CD cover or alternative image |
| Date | Post date | When you listened/reviewed |

### Visual Design

- **Aesthetic**: Light, minimal, clean
- **Grid**: Responsive columns (4 desktop → 3 tablet → 2 mobile)
- **Typography**: System font stack (no external font loading)
- **Colours**: 
  - Background: White/off-white
  - Text: Dark grey
  - Keep verdict: Green (#2e7d32)
  - Delete verdict: Red (#c62828)
  - Accents: Subtle grey borders/shadows

### Browser Support

Modern browsers with CSS Grid support (all browsers from 2017+). No IE11 support.

## Scope Boundaries

### In Scope
- Grid homepage with responsive CD cover layout
- Single post template for CD reviews
- Custom meta box for Artist and Verdict fields
- About page template
- Comments on individual posts
- Configurable site title and icon (via Customizer)
- Mobile, tablet, and desktop responsive design
- WordPress RSS feed (native)

### Out of Scope
- Search functionality
- Filtering/categorisation
- User accounts/authentication beyond WordPress admin
- External API integrations
- Image optimisation beyond WordPress defaults
- Internationalisation/translation

## Technical Requirements

- **WordPress**: 6.0+
- **PHP**: 8.1+ (compatible with 8.4 production, developed on 8.5)
- **No JavaScript frameworks**: Vanilla JS only where needed
- **No CSS frameworks**: Custom CSS using modern features

## Development Environment

- **IDE**: Visual Studio Code with Claude Code
- **Local WordPress**: Docker-based (`wordpress:php8.5` image)
- **Version Control**: Git
- **Deployment**: Manual file transfer to production server

## File Naming Conventions

- PHP files: lowercase with hyphens (`single-post.php`)
- CSS files: lowercase with hyphens (`theme-styles.css`)
- Template parts: prefixed with purpose (`part-cd-grid-item.php`)
- Asset folders: lowercase (`assets/`, `css/`, `images/`)

## WordPress Coding Standards

This theme follows WordPress coding standards with pragmatic simplifications:

- PHP: WordPress PHP Coding Standards (spaces, braces, naming)
- CSS: WordPress CSS Coding Standards
- HTML: Semantic markup, accessibility basics (alt text, ARIA where beneficial)
- Escaping: All output escaped (`esc_html()`, `esc_attr()`, `esc_url()`)
- Sanitisation: All input sanitised on save

## Testing Checklist

### Functional Testing
- [ ] Homepage displays posts in reverse chronological grid
- [ ] Grid responds correctly at mobile/tablet/desktop breakpoints
- [ ] Clicking grid item navigates to single post
- [ ] Single post displays: title, artist, verdict (coloured), content, featured image
- [ ] Verdict displays green "Keep It" or red "Delete It" correctly
- [ ] Comments form displays and accepts submissions
- [ ] About page renders correctly
- [ ] Site title and icon display in header
- [ ] RSS feed includes new posts

### Admin Testing
- [ ] Artist custom field appears in post editor
- [ ] Verdict dropdown appears in post editor
- [ ] Featured image selector works
- [ ] Site title configurable in Settings → General
- [ ] Site icon configurable in Appearance → Customize

### Cross-Device Testing
- [ ] Desktop (1200px+): 4-column grid
- [ ] Tablet (768px-1199px): 3-column grid
- [ ] Mobile (<768px): 2-column grid
- [ ] Touch interactions work on mobile

### Edge Cases
- [ ] Post without featured image displays gracefully
- [ ] Very long album titles don't break layout
- [ ] Very long artist names don't break layout
- [ ] Empty homepage (no posts) shows appropriate message

## Deployment Steps

1. Ensure all testing checklist items pass locally
2. Create theme ZIP or prepare files for transfer
3. Backup production site
4. Upload theme to `/wp-content/themes/cd-collection/`
5. Activate theme in Appearance → Themes
6. Configure site title and icon
7. Verify front-end displays correctly
8. Test one new post creation end-to-end

## Future Considerations

Items explicitly out of scope but could be added later:

- Search functionality
- Filter by verdict (show only Keeps / only Deletes)
- Sort options (alphabetical by artist/album)
- Statistics page (total kept vs deleted)
- Bulk import from spreadsheet
- Integration with MusicBrainz for metadata lookup

## Licence

Private use. Not distributed.
