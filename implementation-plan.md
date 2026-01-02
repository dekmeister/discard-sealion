# Migration Plan: Custom Fields → Excerpt & Categories

## Current State

- **cd_artist**: Post meta field (text input in sidebar meta box)
- **cd_verdict**: Post meta field (dropdown select: keep/delete)

## Target State

- **Artist**: WordPress excerpt field (built-in)
- **Verdict**: WordPress categories ("Keep" and "Delete" as separate categories)

---

## Files That Need Changes

| File | Changes Required |
|------|------------------|
| `inc/custom-fields.php` | **Remove entirely** - no longer needed |
| `inc/meta-boxes.php` | **Remove entirely** - WordPress provides built-in excerpt & category UI |
| `inc/template-tags.php` | **Update functions** to use excerpt and categories instead of post meta |
| `template-parts/content-single-cd.php` | Minor updates (function signatures unchanged) |
| `functions.php` | Remove includes for deleted files, add excerpt support |

---

## Implementation Steps

### Step 1: Enable Excerpt Support

In `functions.php`, ensure excerpt support is enabled for posts. Add to theme setup:

```php
add_post_type_support('post', 'excerpt');
```

### Step 2: Delete Custom Fields Registration

Delete `inc/custom-fields.php` - field registration no longer needed since we're using built-in WordPress features.

### Step 3: Delete Meta Boxes

Delete `inc/meta-boxes.php` - custom meta box UI no longer needed. WordPress provides:
- Built-in excerpt meta box in the editor
- Built-in category meta box for selecting Keep/Delete

### Step 4: Update Template Tags

Update `inc/template-tags.php` to use excerpt and categories:

**cd_sealion_get_artist():**
```php
function cd_sealion_get_artist($post_id = 0) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    return get_the_excerpt($post_id);
}
```

**cd_sealion_get_verdict():**
```php
function cd_sealion_get_verdict($post_id = 0) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    if (has_category('keep', $post_id)) {
        return 'keep';
    }
    if (has_category('delete', $post_id)) {
        return 'delete';
    }
    return '';
}
```

**cd_sealion_verdict_display()** - logic remains the same, just uses updated `cd_sealion_get_verdict()`.

### Step 5: Update functions.php

Remove the `require` statements for deleted files:

```php
// Remove these lines:
require get_template_directory() . '/inc/custom-fields.php';
require get_template_directory() . '/inc/meta-boxes.php';
```

### Step 6: Create Categories

Create two categories in WordPress (manually via admin or programmatically):

- **Keep** (slug: `keep`)
- **Delete** (slug: `delete`)

---

## Benefits of This Approach

- **Simpler architecture** - uses WordPress built-in features
- **Better UX** - excerpt field has rich editor, categories have standard UI
- **Query-friendly** - can easily query posts by category (e.g., show all "Keep" posts)
- **Template tags preserved** - existing templates continue to work with updated function internals
- **No database migration needed** - new posts use new system; old data remains (harmless)

---

## Notes

- The template file `template-parts/content-single-cd.php` should continue to work without changes since it calls the helper functions which maintain the same interface.
- CSS styling for `.verdict-keep`, `.verdict-delete`, and `.verdict-pending` classes remains unchanged.
- Existing posts with old meta data will show "Verdict Pending" until recategorized.
