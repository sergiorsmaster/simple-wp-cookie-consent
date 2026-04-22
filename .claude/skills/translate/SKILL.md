---
name: translate
description: Regenerate the POT template and compile all MO translation files. Use whenever new translatable strings are added to the plugin.
allowed-tools: Bash Read Edit Glob Grep
---

# Update translations for Consentric

Regenerate the POT template and compile all MO files.
Run this skill whenever new translatable strings are added to the plugin.

The Docker dev environment must be running. If it is not, start it first:
```bash
docker compose up -d
```

---

## Step 1 — Regenerate the POT file

Run WP-CLI inside the WordPress container to scan all PHP files and regenerate the master template:

```bash
docker compose exec wordpress wp i18n make-pot \
  /var/www/html/wp-content/plugins/simple-cookie-consent \
  /var/www/html/wp-content/plugins/simple-cookie-consent/languages/simple-cookie-consent.pot \
  --domain=simple-cookie-consent \
  --allow-root
```

## Step 2 — Check for new/changed strings

Compare the new POT with the existing PO files to identify:
- Strings added (need translation)
- Strings removed or changed (fuzzy matches or obsolete)

## Step 3 — Update PO files

For each locale (`pt_PT`, `pt_BR`, `de_DE`), update the PO file to include new strings.
If the user has provided translations for new strings, apply them.
If not, mark new strings as untranslated (`msgstr ""`).

**Never delete existing translations** — only add missing entries for new strings.

When translating, follow locale conventions:
- **pt_PT** (European Portuguese): "Guardar", "Eliminar", "Separador", "Ecrã"
- **pt_BR** (Brazilian Portuguese): "Salvar", "Excluir", "Aba", "Tela"
- **de_DE** (German): formal "Sie" register, WordPress DE conventions

## Step 4 — Compile MO files

For each updated PO file, compile the binary MO:

```bash
docker compose exec wordpress wp i18n make-mo \
  /var/www/html/wp-content/plugins/simple-cookie-consent/languages \
  --allow-root
```

Verify that a matching `.mo` file exists for every `.po` file.

## Step 5 — Commit

Stage all files in `languages/`:

```bash
git add languages/
git commit -m "i18n: update translations"
```

Report to the user which locales were updated and how many new strings were added.

---

## Locale files managed

| File | Locale | Notes |
|------|--------|-------|
| `simple-cookie-consent.pot` | Template (all strings) | Source of truth |
| `simple-cookie-consent-pt_PT.po/.mo` | European Portuguese | Guardar/Ecrã/Eliminar |
| `simple-cookie-consent-pt_BR.po/.mo` | Brazilian Portuguese | Salvar/Tela/Excluir |
| `simple-cookie-consent-de_DE.po/.mo` | German | Formal register |
