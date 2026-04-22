---
name: new-feature
description: Start a new feature following the FEAT-XX branch workflow. Use when the PO asks to implement a new feature. Presents scope and waits for approval before writing any code.
argument-hint: "<FEAT-XX> <short description> (e.g. FEAT-32 Script Manager)"
allowed-tools: Bash Read Edit Write Glob Grep
---

# Start a new feature for Consentric

The user wants to implement a new feature. Arguments: **$ARGUMENTS**

The argument format is: `FEAT-XX short description` (e.g. `FEAT-32 Script Manager`).
If the format is unclear, ask the user to clarify before proceeding.

---

## Step 1 — Present scope and acceptance criteria

Before writing any code:
1. Parse the feature ID (e.g. `FEAT-32`) and a short slug (e.g. `script-manager`) from the arguments.
2. Research the codebase to understand what files will be affected.
3. Present to the user:
   - **What** will be implemented (clear description)
   - **Acceptance criteria** (bullet list of observable outcomes)
   - **Files to create/modify** (table)
4. **Wait for explicit PO approval** before proceeding to Step 2.

## Step 2 — Create the feature branch

```bash
git checkout main && git pull origin main
git checkout -b feature/feat-XX-slug
```

Use the exact FEAT-XX ID and a lowercase hyphenated slug derived from the description.

## Step 3 — Update CLAUDE.md status to in-progress

In `CLAUDE.md`, find the task line for this feature and change `[ ]` to `[~]`.
Commit this change on the feature branch:

```
chore: mark FEAT-XX as in progress
```

## Step 4 — Implement

Follow the acceptance criteria from Step 1. Adhere to all code conventions and **WordPress Security Rules** documented in `CLAUDE.md`. Key rules:
- PHP prefix `scc_` on all functions, classes, hooks, and options
- JS namespace `window.SimpleCookieConsent`
- Text domain `simple-cookie-consent` — wrap all user-facing strings with `__()` / `esc_html__()`
- No inline styles in PHP templates — use CSS files or `wp_add_inline_style`
- WordPress Settings API for all admin forms
- Enqueue via `wp_enqueue_scripts` / `admin_enqueue_scripts`
- Min WP 6.0 / Min PHP 7.4 — no syntax or functions newer than these
- **Escape all output** — `esc_html()`, `esc_attr()`, `esc_url()`, `wp_kses_post()`, `(int)` for inline JS
- **Sanitize all input** — `wp_unslash()` before sanitizing `$_GET`/`$_POST`/`$_COOKIE`
- **Nonce verification** on all form processing; `// phpcs:ignore` with reason for read-only params
- **`wp_safe_redirect()`** instead of `wp_redirect()`
- **No inline JS event handlers** — use `data-scc-action` + delegated listeners
- **`$wpdb` direct queries** must have `// phpcs:ignore` comments

**Show the user each significant piece of code before committing.**

## Step 5 — Commit

Use a descriptive commit message. Do not include FEAT-XX IDs in user-visible UI strings.
Only include feature IDs in commit messages and branch names.

## Step 6 — Request PO review and approval

Summarise what was implemented. Wait for explicit approval before merging.

## Step 7 — Merge to main and clean up

```bash
git checkout main
git merge --no-ff feature/feat-XX-slug -m "feat: merge FEAT-XX short description"
git branch -d feature/feat-XX-slug
git push origin main
```

Update `CLAUDE.md`: change `[~]` to `[x]` for this feature. Commit on main:

```
chore: mark FEAT-XX as complete
```
