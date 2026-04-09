# Simple Cookie Consent — CLAUDE.md

## Working Agreement

**Human = Product Owner. AI = Developer.**

- All requirements are broken into discrete features/tasks.
- Before implementing any task, the AI presents scope + acceptance criteria and waits for PO approval.
- Each approved feature gets its own git branch (`feature/feat-XX-slug`), merged to `main` when complete.
- No code is written speculatively — only what has been approved.
- Never include FEAT-XX IDs or branch names in admin/frontend UI text.
- Always show the implementation and wait for PO approval before committing.

---

## Slash Commands (Skills)

Use these when the PO makes the corresponding request:

| Command | When to use |
|---------|-------------|
| `/new-feature FEAT-XX description` | PO asks to implement a new feature |
| `/release X.X.X` | PO asks to release a new version |
| `/translate` | New strings were added; translations need updating |
| `/add-setting option_name tab field_type` | PO asks to add a new admin settings field |

Skills are defined in `.claude/skills/` — read the relevant `SKILL.md` for the full step-by-step procedure.

---

## Plugin Identity

- **Slug:** `simple-cookie-consent`
- **Main file:** `simple-cookie-consent.php`
- **GitHub:** https://github.com/sergiorsmaster/simple-wp-cookie-consent
- **Min WP:** 6.0 | **Min PHP:** 7.4
- **Text domain:** `simple-cookie-consent`

---

## Code Conventions

- PHP prefix `scc_` on all functions, classes, hooks, and options.
- JS namespace `window.SimpleCookieConsent`.
- Wrap all user-facing strings with `__()` / `esc_html__()` / `esc_html_e()`.
- WordPress Settings API for all admin forms.
- Enqueue scripts/styles via `wp_enqueue_scripts` / `admin_enqueue_scripts`.
- No inline styles in PHP templates — use CSS files or `wp_add_inline_style`.
- Do NOT use `load_plugin_textdomain()` — WordPress.org handles translations automatically since WP 4.6.

### WordPress Security Rules (Plugin Check compliance)

These rules are **mandatory** — Plugin Check will flag violations as errors or warnings.

**Output escaping** — every `echo` must use an escaping function:
- Text: `esc_html()`, `esc_html_e()`, `esc_html__()`
- Attributes: `esc_attr()`, `esc_attr_e()`
- URLs: `esc_url()`
- HTML fragments built in PHP: `wp_kses_post()`
- Numbers in inline JS: cast with `(int)` or `intval()`

**Input handling** — always `wp_unslash()` before sanitizing superglobals:
- `sanitize_text_field( wp_unslash( $_POST['field'] ) )`
- `sanitize_key( wp_unslash( $_GET['key'] ) )`
- `absint( $_GET['id'] )` (absint is safe without unslash)
- Nonces: `wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'action' )`

**Nonce verification** — required before processing any `$_GET` / `$_POST` data:
- For form submissions: use `wp_nonce_field()` + `wp_verify_nonce()`
- For read-only display params (e.g. `$_GET['tab']`): add `// phpcs:ignore WordPress.Security.NonceVerification.Recommended` with a comment explaining why

**Redirects** — always use `wp_safe_redirect()` instead of `wp_redirect()`.

**Direct database queries** — our custom table (`scc_cookies`) requires `$wpdb` calls. Add a phpcs ignore comment:
```php
// phpcs:ignore WordPress.DB.DirectDatabaseQuery, WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- $table uses trusted prefix
$results = $wpdb->get_results( "SELECT * FROM {$table} WHERE ..." );
```

**No inline JS event handlers** — use `data-scc-action` attributes + delegated JS listeners instead of `onclick`.

---

## Repository Structure

```
simple-cookie-consent/
├── simple-cookie-consent.php   ← Main file (plugin header + bootstrap + SCC_VERSION constant)
├── uninstall.php               ← Drops table + deletes all scc_* options
├── readme.txt                  ← WordPress.org listing
├── .github/workflows/
│   └── release.yml             ← Auto-build zip + GitHub Release on vX.X.X tag push
├── .claude/skills/             ← AI slash commands (skills)
├── includes/
│   ├── class-scc-activator.php     ← create_tables(), set_defaults(), seed_own_cookie()
│   ├── class-scc-deactivator.php
│   ├── class-scc-consent-store.php ← PHP cookie reader
│   ├── class-scc-cookie-scanner.php
│   ├── class-scc-wp-consent-api.php
│   ├── class-scc-polylang.php
│   └── class-scc-shortcodes.php    ← [scc_cookie_list], [scc_preferences]
├── admin/
│   ├── class-scc-admin.php         ← Menu, tabs, register_settings(), handle_cookie_actions()
│   ├── views/
│   │   ├── tab-general.php         ← scc_general settings group
│   │   ├── tab-appearance.php      ← scc_appearance settings group
│   │   ├── tab-jurisdiction.php    ← scc_jurisdiction settings group
│   │   ├── tab-integrations.php    ← scc_integrations settings group + Debug
│   │   ├── tab-cookies.php         ← Cookie list + scanner (custom form, no Settings API)
│   │   └── tab-help.php            ← Read-only reference (CSS, shortcodes, JS API)
│   └── assets/
│       ├── admin.css
│       └── admin.js
├── public/
│   ├── class-scc-public.php        ← enqueue_scripts(), render_banner(), render_modal()
│   ├── views/
│   │   ├── banner.php
│   │   ├── modal.php
│   │   └── preferences-icon.php    ← Cookie SVG icon
│   └── assets/
│       ├── scc-banner.css
│       ├── scc-banner.js           ← SimpleCookieConsent JS API + GTM integration
│       └── scc-modal.js            ← Focus trap, aria, toggle state
└── languages/
    ├── simple-cookie-consent.pot   ← Master template (source of truth)
    ├── simple-cookie-consent-pt_PT.po/.mo
    ├── simple-cookie-consent-pt_BR.po/.mo
    └── simple-cookie-consent-de_DE.po/.mo
```

---

## Key Options Reference

| Option | Tab | Type | Default |
|--------|-----|------|---------|
| `scc_enabled` | General | toggle | `'1'` |
| `scc_banner_title` | General | text | `'We use cookies'` |
| `scc_banner_text` | General | textarea | *(long default)* |
| `scc_accept_label` | General | text | `'Accept All'` |
| `scc_deny_label` | General | text | `'Deny All'` |
| `scc_preferences_label` | General | text | `'Preferences'` |
| `scc_modal_title` | General | text | `'Cookie Preferences'` |
| `scc_modal_intro` | General | textarea | *(long default)* |
| `scc_modal_save_label` | General | text | `'Save Preferences'` |
| `scc_modal_deny_label` | General | text | `'Deny All'` |
| `scc_privacy_policy_page` | General | page selector | `0` |
| `scc_cookie_policy_page` | General | page selector | `0` |
| `scc_imprint_page` | General | page selector | `0` |
| `scc_show_preferences_icon` | General | toggle | `'1'` |
| `scc_position` | Appearance | select | `'bottom-bar'` |
| `scc_color_bg` | Appearance | color | `'#ffffff'` |
| `scc_color_text` | Appearance | color | `'#111111'` |
| `scc_color_accent` | Appearance | color | `'#0073aa'` |
| `scc_logo_source` | Appearance | select | `'custom'` |
| `scc_logo_url` | Appearance | URL | `''` |
| `scc_border_radius` | Appearance | number | `'6'` |
| `scc_banner_max_width` | Appearance | number | `'200'` |
| `scc_banner_border_width` | Appearance | number | `'0'` |
| `scc_banner_border_color` | Appearance | color | `'#dddddd'` |
| `scc_button_style` | Appearance | select | `'outline'` |
| `scc_custom_css` | Appearance | textarea | `''` |
| `scc_jurisdiction` | Jurisdiction | select | `'gdpr'` |
| `scc_ccpa_opt_out_text` | Jurisdiction | text | *(fallback in render)* |
| `scc_gtm_enabled` | Integrations | toggle | `'0'` |
| `scc_gtm_mode` | Integrations | select | `'basic'` |
| `scc_gtm_wait_for_update` | Integrations | number | `'500'` |
| `scc_debug` | Integrations | toggle | `'0'` |

---

## JavaScript API (`window.SimpleCookieConsent`)

| Method | Description |
|--------|-------------|
| `acceptAll()` | Accept all categories, update GTM, hide banner |
| `denyAll()` | Deny all non-necessary, update GTM, hide banner |
| `saveConsent(obj)` | Save partial consent `{analytics, marketing, functional}` |
| `openPreferences()` | Open the preferences modal |
| `openBanner()` | Re-show the consent banner |
| `hasConsent(category)` | Returns `true/false` for a category |
| `hasInteracted()` | Returns `true` if visitor has made a choice |

Event: `document.addEventListener('scc:consentUpdated', e => { e.detail /* consent object */ })`

---

## Feature / Task List

Status: `[ ]` pending | `[~]` in progress | `[x]` done

### Phase 0 — Project setup
- [x] FEAT-01: Docker dev environment
- [x] FEAT-02: Plugin scaffold

### Phase 1 — Core consent engine
- [x] FEAT-03: Cookie consent storage (JS cookie + PHP reader)
- [x] FEAT-04: GTM Consent Mode v2 — default + update signals (Basic mode)
- [x] FEAT-05: GTM Advanced mode support

### Phase 2 — Banner UI
- [x] FEAT-06: Banner HTML template + CSS
- [x] FEAT-07: Accept All / Deny All / Preferences modal
- [x] FEAT-08: Re-open preferences via footer link

### Phase 3 — Admin settings
- [x] FEAT-09: Admin menu + General settings tab
- [x] FEAT-10: Appearance tab
- [x] FEAT-11: Jurisdiction tab
- [x] FEAT-12: Integrations tab

### Phase 4 — Cookie scanner
- [x] FEAT-13: DB table + admin cookie list UI
- [x] FEAT-14: Cookie scanner (PHP header scan + JS client scan)
- [x] FEAT-15: Open Cookie Database lookup + built-in fallback list

### Phase 5 — Integrations & shortcode
- [x] FEAT-16: WP Consent Level API integration
- [x] FEAT-17: Polylang compatibility
- [x] FEAT-18: [scc_cookie_list] shortcode

### Phase 6 — Polish & release
- [x] FEAT-19: Uninstall cleanup
- [x] FEAT-20: readme.txt for WordPress.org
- [x] FEAT-21: Banner & modal style review + expanded appearance settings

### Phase 7 — Developer experience & repository health
- [x] FEAT-22: Register scc_consent cookie in DB on activation
- [x] FEAT-23: Replace preferences icon with cookie SVG
- [x] FEAT-24: Banner preview via ?scc_preview=1 + admin link
- [x] FEAT-25: Admin Help tab
- [x] FEAT-26: Accessibility review (focus trap, tabindex, aria-checked)
- [x] FEAT-27: README.md for developers
- [x] FEAT-28: GitHub Actions release workflow
- [x] FEAT-29: License fix (GPL v2) + CODE_OF_CONDUCT.md
- [x] FEAT-30: UX improvements — button order, modal text, openBanner()
- [x] FEAT-31: i18n — PT-PT, PT-BR, DE translations + POT file

### Backlog
- [ ] FEAT-32: Script Manager — admin UI to manage custom script snippets per consent category
