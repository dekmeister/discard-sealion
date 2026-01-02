# CLAUDE.md - Claude Code Project Instructions

This file provides context and guidance for Claude Code when working on this WordPress theme.

## Project Context

This is a personal WordPress theme for cataloguing CD reviews. The owner has WordPress admin experience but limited theme development experience. Prioritise clarity and simplicity over clever abstractions.

## Key Technical Constraints

### PHP
- Target PHP 8.1+ compatibility (production runs 8.4, dev runs 8.5)
- Avoid PHP 8.4+ features (property hooks, new array functions)
- Follow WordPress PHP Coding Standards: 
  - Tabs for indentation
  - Spaces inside parentheses: `if ( $condition )`
  - Yoda conditions: `if ( 'value' === $variable )`
  - Snake_case for functions and variables

### WordPress
- Use WordPress core functions, avoid reinventing
- Escape all output: `esc_html()`, `esc_attr()`, `esc_url()`
- Sanitise all input: `sanitize_text_field()`, `sanitize_key()`
- Use `wp_enqueue_style()` and `wp_enqueue_script()`, never hardcode
- Prefix all functions/hooks with `cd_collection_`

### CSS
- No preprocessors - write vanilla CSS directly in style.css
- Use CSS custom properties for colours
- Use CSS Grid for the homepage layout
- Mobile-first responsive approach
- System font stack only

### JavaScript
- Vanilla JS only - no jQuery, no frameworks
- Minimal use - only if genuinely needed
- ES6+ syntax is acceptable (modern browser target)

## File Purposes

| File | Purpose |
|------|---------|
| `style.css` | Theme header + ALL styles (single stylesheet) |
| `functions.php` | Theme setup, includes, enqueues |
| `index.php` | Fallback template (required by WordPress) |
| `header.php` | Site header with navigation |
| `footer.php` | Site footer |
| `front-page.php` | Homepage with CSS Grid of CD covers |
| `single.php` | Individual CD review display |
| `page.php` | Static pages (About, etc.) |
| `comments.php` | Comment list and form |
| `inc/custom-fields.php` | Register `cd_artist` and `cd_verdict` meta |
| `inc/meta-boxes.php` | Admin UI for custom fields |
| `inc/template-tags.php` | Reusable template functions |
| `template-parts/content-grid-item.php` | Grid item for homepage |
| `template-parts/content-single-cd.php` | Single CD review content |
| `template-parts/content-none.php` | Empty state message |

## Custom Fields Reference

### cd_artist
- Type: string
- Sanitisation: `sanitize_text_field()`
- Display: Below album title on single view

### cd_verdict  
- Type: string
- Values: "keep" or "delete" (stored lowercase)
- Sanitisation: `sanitize_key()` with whitelist check
- Display: "Keep It" (green) or "Delete It" (red)

## Template Hierarchy

```
Homepage:     front-page.php → index.php
CD Review:    single.php → index.php  
About Page:   page.php → index.php
```

## Common Tasks

### Adding a new custom field
1. Register meta in `inc/custom-fields.php` using `register_post_meta()`
2. Add UI element in `inc/meta-boxes.php`
3. Add save handler in `inc/meta-boxes.php`
4. Create template tag in `inc/template-tags.php`
5. Use template tag in relevant template

### Modifying grid layout
- Column counts: CSS custom properties in `style.css` (`:root` section)
- Breakpoints: Media queries in `style.css`
- Grid gap/spacing: CSS custom properties

### Changing colours
- All colours defined as CSS custom properties in `:root`
- Verdict colours: `--color-keep`, `--color-delete`

## Testing Commands

Local development testing:
```bash
# Clear existing staging directory
rm -r /mnt/server/Server/wordpress/backup/

# Copy all theme files to staging directory
cp -r /mnt/server/Projects/2026/CD-sealion/{*.php,*.css,screenshot.png,inc,template-parts,assets} /mnt/server/Server/wordpress/backup/wordpress-files/wp-content/themes/cd-collection/

# Start Docker WordPress instance
docker compose -f /mnt/server/Server/wordpress/docker-compose.yml up -d

# Install theme to Docker container
cd /mnt/server/Server/wordpress && ./wordpress_theme_install.sh

# Access site at http://localhost:8080
# WordPress admin at http://localhost:8080/wp-admin

# Stop Docker when done
docker compose -f /mnt/server/Server/wordpress/docker-compose.yml down
```

## Code Review Checklist

Before marking any task complete, verify:

- [ ] All PHP files have `<?php` opening (no closing tag)
- [ ] All output is escaped appropriately
- [ ] All user input is sanitised
- [ ] Functions are prefixed with `cd_collection_`
- [ ] No hardcoded URLs or paths
- [ ] Responsive behaviour works at all breakpoints
- [ ] Code follows WordPress coding standards
- [ ] No PHP warnings or notices

## Error Handling

- Use `wp_debug` mode during development
- Check for existence before using: `if ( has_post_thumbnail() )`
- Provide fallbacks for missing data
- Never expose raw errors to users

## Don't Do These Things

- Don't add build tooling (webpack, npm scripts)
- Don't add external CSS frameworks
- Don't create custom post types (use standard Posts)
- Don't add plugin dependencies
- Don't add JavaScript unless genuinely necessary
- Don't add features not in README scope
- Don't use short PHP tags (`<?=`)
- Don't close PHP files with `?>`
