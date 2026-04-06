# Simple Cookie Consent — CLAUDE.md

## Working Agreement

**Human = Product Owner. AI = Developer.**

- All requirements are broken into discrete features/tasks.
- Before implementing any task, the AI presents it and waits for PO approval.
- Each approved feature gets its own git branch (`feature/<slug>`), merged to `main` when complete.
- No code is written speculatively — only what has been approved.

## Workflow per task

1. AI proposes the task scope and acceptance criteria.
2. PO approves (or adjusts).
3. AI creates a `feature/<task-slug>` branch.
4. AI implements, commits, and summarizes changes.
5. PO reviews; AI merges to `main` on approval.

## Feature / Task List

Tasks are tracked in order below. Status: [ ] pending | [~] in progress | [x] done.

### Phase 0 — Project setup
- [x] CLAUDE.md: Working agreement and task list
- [ ] FEAT-01: Docker dev environment (docker-compose.yml + .env.example)
- [ ] FEAT-02: Plugin scaffold (main file, activator, deactivator, uninstall)

### Phase 1 — Core consent engine
- [ ] FEAT-03: Cookie consent storage (JS cookie read/write + PHP reader)
- [ ] FEAT-04: GTM Consent Mode v2 — default + update signals (Basic mode)
- [ ] FEAT-05: GTM Advanced mode support

### Phase 2 — Banner UI
- [ ] FEAT-06: Banner HTML template + CSS (position, colors, responsive)
- [ ] FEAT-07: Accept All / Deny All / Preferences modal
- [ ] FEAT-08: Re-open preferences via footer link

### Phase 3 — Admin settings
- [ ] FEAT-09: Admin menu + General settings tab (texts, logo, legal page links)
- [ ] FEAT-10: Appearance tab (position, colors, custom CSS)
- [ ] FEAT-11: Jurisdiction tab (GDPR / LGPD / CCPA selector)
- [ ] FEAT-12: Integrations tab (GTM toggle + mode, Site Kit info)

### Phase 4 — Cookie scanner
- [ ] FEAT-13: DB table for cookies + admin cookie list UI
- [ ] FEAT-14: Cookie scanner (PHP header scan + JS client scan via admin-ajax)
- [ ] FEAT-15: cookiedatabase.org API lookup + internal fallback list

### Phase 5 — Integrations & shortcode
- [ ] FEAT-16: WP Consent Level API integration
- [ ] FEAT-17: Polylang compatibility (pll_register_string)
- [ ] FEAT-18: [scc_cookie_list] shortcode

### Phase 6 — Polish & release
- [ ] FEAT-19: Uninstall cleanup (drop table, delete options)
- [ ] FEAT-20: readme.txt for WordPress.org

## Code conventions

- PHP prefix: `scc_` on all functions, classes, hooks, options.
- JS namespace: `window.SimpleCookieConsent`
- Text domain: `simple-cookie-consent`
- Min WP: 6.0 | Min PHP: 7.4
- Use WordPress Settings API for all admin forms.
- Enqueue scripts/styles via `wp_enqueue_scripts` / `admin_enqueue_scripts`.
- No inline styles in PHP templates (use CSS files or `wp_add_inline_style`).

## Plugin identity

- Slug: `simple-cookie-consent`
- Main file: `simple-cookie-consent.php`
- GitHub: https://github.com/sergiorsmaster/simple-wp-cookie-consent
