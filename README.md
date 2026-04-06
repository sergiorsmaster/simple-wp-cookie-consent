# Simple Cookie Consent

A free, open-source WordPress cookie consent banner plugin. No Pro tier, no subscription, no tracking.

Built for small businesses and developers who need solid GDPR/LGPD/CCPA compliance without paying for expensive commercial solutions. Minimal and fully transparent.

> WordPress.org listing: coming soon

---

## Features

- Cookie consent banner with 5 position options (bottom bar, top bar, corner boxes, center modal)
- Accept All / Deny All / Preferences modal with per-category toggles
- Google Tag Manager Consent Mode v2 (Basic and Advanced)
- GDPR, LGPD, and CCPA jurisdiction modes
- Cookie scanner with [Open Cookie Database](https://github.com/jkwakman/Open-Cookie-Database) lookup
- Floating preferences icon and `[scc_preferences]` shortcode
- `[scc_cookie_list]` shortcode for displaying scanned cookies
- WP Consent Level API integration
- Polylang compatible
- Accessible (WCAG 2.1 AA): focus trap, `role="dialog"`, `role="switch"`, keyboard navigation

---

## Quick Start (Docker)

The `docker-compose.yml` lives **one level above** the plugin folder. The recommended layout is:

```
my-project/                          ← docker-compose.yml lives here
└── simple-wp-cookie-consent/        ← git repo root (this folder)
```

1. Clone the repo inside your project folder:

```bash
mkdir my-project && cd my-project
git clone https://github.com/sergiorsmaster/simple-wp-cookie-consent.git
```

2. Create a `docker-compose.yml` in `my-project/` (see the example below) and a `.env` file with your credentials:

```ini
MYSQL_ROOT_PASSWORD=rootpassword
MYSQL_DATABASE=wordpress
MYSQL_USER=wpuser
MYSQL_PASSWORD=wppassword
```

Then start the stack:

```bash
docker compose up -d
```

3. Visit [http://localhost:8080](http://localhost:8080), complete the WordPress setup, then activate **Simple Cookie Consent** from the Plugins screen.

4. The plugin folder is bind-mounted directly into the container — any file you edit is live immediately with no rebuild needed.

   The key volume mapping is:
   ```
   ./simple-wp-cookie-consent:/var/www/html/wp-content/plugins/simple-cookie-consent
   ```
   The left side is the repo folder (relative to `docker-compose.yml`); the right side is the plugin slug WordPress expects inside the container.

### docker-compose.yml example

```yaml
services:
  db:
    image: mysql:8.0
    restart: unless-stopped
    environment:
      MYSQL_ROOT_PASSWORD: ${MYSQL_ROOT_PASSWORD}
      MYSQL_DATABASE: ${MYSQL_DATABASE}
      MYSQL_USER: ${MYSQL_USER}
      MYSQL_PASSWORD: ${MYSQL_PASSWORD}
    volumes:
      - db_data:/var/lib/mysql

  wordpress:
    image: wordpress:latest
    restart: unless-stopped
    depends_on: [db]
    ports:
      - "8080:80"
    environment:
      WORDPRESS_DB_HOST: db:3306
      WORDPRESS_DB_NAME: ${MYSQL_DATABASE}
      WORDPRESS_DB_USER: ${MYSQL_USER}
      WORDPRESS_DB_PASSWORD: ${MYSQL_PASSWORD}
      WORDPRESS_DEBUG: 1
      WORDPRESS_CONFIG_EXTRA: |
        define('WP_DEBUG_LOG', true);
        define('WP_DEBUG_DISPLAY', false);
        define('SCRIPT_DEBUG', true);
        define('SAVEQUERIES', true);
    volumes:
      - ./simple-wp-cookie-consent:/var/www/html/wp-content/plugins/simple-cookie-consent

  adminer:
    image: adminer:latest
    ports:
      - "8081:8080"

volumes:
  db_data:
```

An Adminer database UI is available at [http://localhost:8081](http://localhost:8081).

---

## Repository Structure

```
simple-wp-cookie-consent/
├── simple-cookie-consent.php       Main plugin file (header + bootstrap)
├── uninstall.php                   Cleanup on uninstall
├── readme.txt                      WordPress.org listing copy
├── CLAUDE.md                       AI developer working agreement + task list
│
├── includes/
│   ├── class-scc-activator.php     Activation: create DB table, seed defaults
│   ├── class-scc-deactivator.php   Deactivation hook
│   ├── class-scc-consent-store.php PHP consent cookie reader
│   ├── class-scc-cookie-scanner.php Cookie detection + Open Cookie Database lookup
│   ├── class-scc-polylang.php      Polylang string registration/retrieval
│   ├── class-scc-shortcodes.php    [scc_cookie_list] and [scc_preferences]
│   ├── class-scc-wp-consent-api.php WP Consent Level API bridge
│   └── data/
│       └── open-cookie-database.csv Bundled cookie definitions (2 000+ entries)
│
├── admin/
│   ├── class-scc-admin.php         Admin menu, settings registration, AJAX handlers
│   ├── assets/
│   │   ├── admin.css
│   │   └── admin.js
│   └── views/
│       ├── tab-general.php         Banner text, button labels, legal pages
│       ├── tab-appearance.php      Position, colors, border, logo, custom CSS, preview
│       ├── tab-jurisdiction.php    GDPR / LGPD / CCPA selector
│       ├── tab-integrations.php    GTM toggle, mode, Site Kit info
│       ├── tab-cookies.php         Cookie scanner + list management
│       └── tab-help.php            CSS reference, shortcode docs, JS API docs
│
└── public/
    ├── class-scc-public.php        Frontend hooks, banner/modal rendering
    ├── assets/
    │   ├── scc-consent.js          Core consent storage (cookie read/write)
    │   ├── scc-banner.js           Banner show/hide, button wiring, focus management
    │   ├── scc-modal.js            Preferences modal open/close, focus trap
    │   ├── scc-gtm.js              GTM Consent Mode v2 bridge
    │   └── scc-banner.css          All frontend styles (banner + modal + icon)
    └── views/
        ├── banner.php              Banner HTML template
        ├── modal.php               Preferences modal HTML template
        └── preferences-icon.php    Floating cookie icon button
```

---

## JavaScript API

All methods are available on `window.SimpleCookieConsent`:

| Method | Description |
|--------|-------------|
| `acceptAll()` | Grant consent for all categories |
| `denyAll()` | Deny all non-necessary categories |
| `saveConsent( categories )` | Save partial consent — e.g. `{ analytics: true, marketing: false }` |
| `openPreferences()` | Programmatically open the preferences modal |
| `hasConsent( category )` | Returns `true` if consent granted for the given category |
| `hasInteracted()` | Returns `true` if the visitor has made a consent choice |

**`scc:consentUpdated`** — `CustomEvent` fired on `document` after any consent save:

```js
document.addEventListener('scc:consentUpdated', function () {
    if ( SimpleCookieConsent.hasConsent('analytics') ) {
        // initialise analytics
    }
});
```

---

## Hooks & Filters

Custom hooks for extensibility (all prefixed `scc_`):

```php
// Actions
do_action( 'scc_before_banner' );
do_action( 'scc_after_consent_saved', $consent_data );
do_action( 'scc_after_scan_complete', $cookies );

// Filters
apply_filters( 'scc_banner_html', $html );
apply_filters( 'scc_default_consent', $defaults );      // modify GTM defaults
apply_filters( 'scc_cookie_categories', $categories );  // add/remove categories
apply_filters( 'scc_consent_cookie_expiry', 365 );      // days
```

> These hooks are defined in the architecture plan but not yet implemented in the codebase. PRs welcome.

---

## Contributing

1. Fork the repository and create a branch from `main`:
   ```
   git checkout -b feature/feat-XX-short-description
   ```
2. Make your changes, following the code conventions in [CLAUDE.md](CLAUDE.md).
3. Open a pull request against `main` with a clear description of what and why.

**Branch naming:** `feature/feat-XX-slug` for new features, `fix/short-description` for bug fixes.

**Commit style:** imperative mood, short subject line, blank line before body if needed.

---

## AI-Assisted Development

This plugin is built using a **Human = Product Owner / AI = Developer** workflow documented in [CLAUDE.md](CLAUDE.md). Development uses [Claude Code](https://claude.ai/claude-code) (Anthropic's CLI coding agent) with a strict approval gate: no code is committed without PO review.

The task list in `CLAUDE.md` is the source of truth for what has been built and what is planned.

---

## Requirements

- WordPress 6.0+
- PHP 7.4+

---

## License

GPL v2 or later — see [LICENSE](LICENSE).
