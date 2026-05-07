---
name: starter-sync
description:
  Walk through unmerged changes from aniftyco/laravel and laravel/laravel since the last sync, deciding what to pull
  into this derived project.
disable-model-invocation: true
---

# starter-sync

This project is derived from the [aniftyco/laravel](https://github.com/aniftyco/laravel) starter kit, which itself
tracks [laravel/laravel](https://github.com/laravel/laravel). This skill walks the upstream history of both repos since
the last sync (or the project's first commit) and helps you absorb worthwhile changes.

You — the LLM — make the calls autonomously. Only ask the user when you genuinely cannot decide.

## Preflight

Before doing anything, verify the working tree is clean:

```bash
git status --porcelain
```

If output is non-empty, **stop**. Tell the user to commit or stash first, then rerun. Applied changes must not mix with
in-flight work.

## Step 0 — Determine the baseline date

Read `.claude/LAST_UPSTREAM_SYNC` if it exists. The file contains a single ISO date (e.g. `2026-05-07`). Use that as the
baseline.

If the file does not exist, this is a first run. Use the date of the project's first commit:

```bash
git log --reverse --format=%aI | head -1
```

Tell the user which baseline you're using and warn them if it's a first run (the change set may be large).

## Step 1 — Sync from aniftyco/laravel

Clone the upstream into a temp directory:

```bash
git clone --quiet https://github.com/aniftyco/laravel.git /tmp/starter-sync-aniftyco
```

List commits since the baseline:

```bash
git -C /tmp/starter-sync-aniftyco log --since=<baseline> --reverse --format='%H %s'
```

For each commit, in order:

1. Inspect the diff: `git -C /tmp/starter-sync-aniftyco show <sha>`
2. Read the affected files in the user's project to understand local state
3. Apply the heuristics below to decide
4. Apply, skip, or ask — and log the decision to a running summary

## Step 2 — Sync from laravel/laravel

Same flow, against `https://github.com/laravel/laravel.git` cloned to `/tmp/starter-sync-laravel`. Use the **same
baseline date** as Step 1.

Many laravel/laravel changes will already be reflected in this project via aniftyco/laravel's own upstream tracking.
When evaluating a laravel/laravel commit, first check whether the same change already landed via Step 1 — if so, skip it
silently.

## Step 3 — Finalize

Only if both phases completed cleanly:

1. Write today's date (ISO format, `YYYY-MM-DD`) to `.claude/LAST_UPSTREAM_SYNC`
2. Remove temp clones: `rm -rf /tmp/starter-sync-aniftyco /tmp/starter-sync-laravel`
3. Present a summary: applied count, skipped count (with reasons grouped), any deferrals

If a phase failed (network, bad clone, user aborted) — do not update the marker. Clean up temp clones. Tell the user
where it stopped so they can rerun.

## Decision Heuristics

### Apply confidently (no need to ask)

- Security fixes and CVE-driven dependency bumps
- Framework version bumps in `composer.json` / `package.json` that don't conflict with this project's pins
- Bugfixes in files the user has not modified
- New utility files or helpers the user does not have
- Workflow / CI improvements that match this project's existing CI shape

### Skip confidently (no need to ask)

- README, branding, logo, `.github/assets/**` — derived projects own their identity
- Files that no longer exist in the user's project (intentionally removed)
- Files where the user's version has diverged substantially and the upstream change targets code the user replaced
- Commits already absorbed (e.g. a laravel/laravel change already pulled in via aniftyco/laravel during Step 1)

### Always ask the user, per change

- Any change to `CLAUDE.md` (root or `.claude/CLAUDE.md`)
- Any change under `.claude/**` (rules, skills, settings, plugins)

These files are user-customized but upstream improvements may still be valuable. For each upstream change in this
category, present the diff and ask whether to apply.

### Defer to the user when uncertain

- True conflict: change touches a file with overlapping local edits
- Architectural shifts (directory restructures, framework config layout changes)
- Dependency removals where the user might still rely on the package
- Anything you cannot determine intent for after reading the relevant local files

When in doubt, **defer**. The user prefers being asked once over silent wrong calls.

## Output Format

While walking commits, keep your narration tight:

- **Applied:** `<sha> <subject>` — one line, no extra commentary
- **Skipped:** `<sha> <subject>` — one line with brief reason (`already absorbed`, `file removed locally`, etc.)
- **Asked:** present the diff, the reason for uncertainty, and wait

The final summary should be a short report — counts plus grouped skip reasons. Don't restate everything you already
showed.
