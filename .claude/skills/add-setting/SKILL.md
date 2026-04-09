---
name: add-setting
description: Add a new WordPress admin settings field following the plugin's conventions. Covers registration, sanitization, default value, tab view HTML, frontend usage, and uninstall cleanup.
argument-hint: "<option_name> <tab> <field_type> (e.g. scc_my_option general text)"
allowed-tools: Bash Read Edit Write Glob Grep
---

# Add a new WordPress settings field

The user wants to add a new admin settings field. Details: **$ARGUMENTS**

Clarify with the user if any of the following are not clear from the arguments:
- **Option name** (e.g. `scc_my_option`) — must use `scc_` prefix
- **Tab** — which settings tab: `general`, `appearance`, `jurisdiction`, `integrations`, `cookies`, `help`
- **Field type** — text, textarea, checkbox/toggle, select, number, color, page selector
- **Default value**
- **Where it is used** — PHP template, JS (`sccSettings`), or both

---

## Step 1 — Register and sanitize the option

**File:** `admin/class-scc-admin.php`

In `register_settings()`, add inside the correct settings group (tab):

```php
register_setting( 'scc_TAB', 'scc_my_option', array(
    'sanitize_callback' => 'sanitize_text_field', // or sanitize_textarea_field, absint, etc.
) );
```

Sanitization functions by field type:
- Text input → `sanitize_text_field`
- Textarea → `sanitize_textarea_field`
- Toggle/checkbox → return `isset( $input ) ? '1' : '0'`
- Number → `absint`
- Color → `sanitize_hex_color`
- URL → `esc_url_raw`
- Page ID → `absint`

## Step 2 — Add a default value on activation

**File:** `includes/class-scc-activator.php`

In `set_defaults()`, add:

```php
add_option( 'scc_my_option', 'default_value' );
```

Use `add_option` (not `update_option`) — it only sets the value if the option does not already exist.

## Step 3 — Add the field in the admin tab view

**File:** `admin/views/tab-TABNAME.php`

Read the option at the top of the file:
```php
$my_option = get_option( 'scc_my_option', 'default_value' );
```

Add the field HTML following the existing `.scc-field` pattern:

```php
<div class="scc-field">
    <label class="scc-field__label" for="scc_my_option">
        <?php esc_html_e( 'Field Label', 'simple-cookie-consent' ); ?>
    </label>
    <div class="scc-field__control">
        <input type="text" id="scc_my_option" name="scc_my_option"
            value="<?php echo esc_attr( $my_option ); ?>">
        <p class="description">
            <?php esc_html_e( 'Helper text describing this field.', 'simple-cookie-consent' ); ?>
        </p>
    </div>
</div>
```

For a toggle, use the `.scc-admin-toggle` pattern (see existing toggles in any tab view).
For a select, use `<select name="scc_my_option">` with `selected()` helper on each `<option>`.

**Security reminder:** All output must be escaped (`esc_html()`, `esc_attr()`, `esc_url()`). For page selector dropdowns, wrap `selected` with `absint()` and `show_option_none` with `esc_html__()`. See full rules in `CLAUDE.md` under "WordPress Security Rules".

## Step 4 — Use the value in the frontend (if needed)

**If used in PHP templates** (`public/views/`):
```php
$my_option = get_option( 'scc_my_option', 'default_value' );
```

**If needed in JavaScript**, pass it via `wp_localize_script` in `public/class-scc-public.php`:
```php
'myOption' => get_option( 'scc_my_option', 'default_value' ),
```
Then access in JS as `window.sccSettings.myOption`.

## Step 5 — Add to uninstall cleanup

**File:** `uninstall.php`

Add to the `delete_option` list:
```php
delete_option( 'scc_my_option' );
```

## Step 6 — Update translations

If the field label or description are new strings, run `/translate` after this change
to regenerate the POT and compile updated MO files.

---

## Settings groups by tab

| Tab | Settings group | PHP file |
|-----|---------------|----------|
| General | `scc_general` | `tab-general.php` |
| Appearance | `scc_appearance` | `tab-appearance.php` |
| Jurisdiction | `scc_jurisdiction` | `tab-jurisdiction.php` |
| Integrations | `scc_integrations` | `tab-integrations.php` |
| Cookies | *(no Settings API — custom form)* | `tab-cookies.php` |
| Help | *(read-only — no settings)* | `tab-help.php` |
