---
name: release
description: Cut a new versioned release of Consentric. Use when the PO asks to release a new version (e.g. "release 1.2.0").
argument-hint: "<version> (e.g. 1.2.0)"
allowed-tools: Bash Read Edit Glob Grep
---

# Release a new version of Consentric

The user has requested a new release. The target version is: **$ARGUMENTS**

Follow every step below in order. Do not skip steps. Show the user what you are doing at each step.

---

## Step 1 — Confirm the version

The version argument should be in `MAJOR.MINOR.PATCH` format (e.g. `1.2.0`), without the `v` prefix.
If the argument is missing or malformed, ask the user to provide it before continuing.

## Step 2 — Create a release branch

```bash
git checkout main && git pull origin main
git checkout -b release/v$ARGUMENTS
```

## Step 3 — Bump the version in exactly three places

Open `consentric.php` and update **both** of these lines:
- The plugin header comment: `* Version: X.X.X`
- The PHP constant:          `define('SCC_VERSION', 'X.X.X')`

Open `readme.txt` and update:
- `Stable tag: X.X.X`

Use the Read tool to find the current values, then use the Edit tool to replace them. Never guess — always read first.

## Step 4 — Commit the version bump

```
chore: bump version to X.X.X
```

Stage only `consentric.php` and `readme.txt`.

## Step 5 — Merge to main

```bash
git checkout main
git merge --no-ff release/vX.X.X -m "chore: release vX.X.X"
git branch -d release/vX.X.X
git push origin main
```

## Step 6 — Tag and push

```bash
git tag vX.X.X
git push origin vX.X.X
```

## Step 7 — Verify the GitHub Actions workflow

After pushing the tag, check that the release workflow starts and passes:

```bash
/opt/homebrew/bin/gh run list --repo sergiorsmaster/consentric --limit 3
```

Wait for it to complete, then confirm the GitHub Release exists with the zip attached:

```bash
/opt/homebrew/bin/gh release view vX.X.X --repo sergiorsmaster/consentric
```

Report the release URL to the user when done.

---

## What the workflow does automatically

The `.github/workflows/release.yml` GitHub Actions workflow:
1. Validates the tag version matches the `Version:` header — fails loudly if not.
2. Builds `consentric-X.X.X.zip` (dev files excluded).
3. Creates the GitHub Release with the zip attached and auto-generated notes.
