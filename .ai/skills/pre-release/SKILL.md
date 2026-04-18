---
name: pre-release
description: "Pre-push / pre-release checklist. Runs Rector, Pint, PHPStan, the full PHPUnit suite, and audits README + `.ai/` docs for staleness. Activate before: pushing to remote, tagging a release, writing release notes, or when user mentions: pre-release, pre-push, release checklist, ship, cut release, release notes."
---

# Pre-Release Checklist

Run this gauntlet before pushing commits that may be tagged as a release or before drafting release notes. It catches regressions the `backend-quality` skill skips — Rector drift, stale docs shipped to downstream projects, and drift in `.ai/` source vs generated files.

## When to Use This Skill

Activate when:
- About to push commits that will land in a release
- About to tag a release (`gh release create`)
- About to write or update release notes
- User says "ship it", "cut a release", "pre-push", "release checklist"
- A feature/fix is fully implemented and quality-gated

Do NOT use mid-development — this is a completion-level skill.

## Workflow

Run the checks **in this order**. Each must pass before moving to the next. Fix issues as they surface; do not batch.

Always append `|| true` to verification commands so output is captured even on failure. Pass/fail is determined from the captured output, not the exit status alone.

### 1. Rector

```bash
vendor/bin/rector process || true
```

Must report **0 files changed**. If Rector modifies files, review the diff, keep the changes, and re-run until clean.

### 2. Pint

```bash
vendor/bin/pint --dirty --format agent || true
```

Must be clean. Re-run after Rector — Rector fixes can introduce style drift.

### 3. PHPStan

```bash
vendor/bin/phpstan analyse --memory-limit=2G || true
```

Must show 0 errors. Fix real issues at the source — do not grow `phpstan-baseline.neon`. See `backend-quality` for baseline rules.

### 4. Full Test Suite

```bash
vendor/bin/phpunit || true
```

Must show 0 failures. The CI matrix runs PHP 8.2/8.3/8.4 × Laravel 12/13 × prefer-lowest/prefer-stable — a local green run against the default composer.lock is necessary but not sufficient. If changes touch anything version-sensitive, also run:

```bash
composer update --prefer-lowest --prefer-dist --no-interaction || true
vendor/bin/phpunit || true
composer update --prefer-stable --prefer-dist --no-interaction || true
vendor/bin/phpunit || true
```

### 5. Documentation freshness audit

Release-worthy features change user-visible behavior, so `README.md` and the `.ai/` files shipped to downstream projects (via `package-boost:sync`) can drift silently.

**Rule:** add or edit docs only where they reflect a real change. Delete stale content aggressively.

#### 5a. README

Scan `README.md` against the commits in this release (`git log <last-tag>..HEAD`). Update when:

- Public API signatures changed (method added, parameter added, behavior changed)
- Installation or usage instructions no longer match the supported Laravel/PHP matrix
- Examples reference removed or renamed classes / facades / directives

If unsure whether a change warrants a README update: would a user reading the README after the release see outdated advice? If yes, update.

#### 5b. `.ai/` skills + guidelines

The `.ai/skills/` and `.ai/guidelines/` directories are synced by `package-boost` (`vendor/bin/testbench package-boost:sync`) to `CLAUDE.md`, `AGENTS.md`, `.claude/skills/`, and `.github/skills/`. Those generated files ship with the package and are read by downstream projects' AI tooling.

Check each edited-or-eligible doc:

- **Accuracy** — every command, path, rule name, and API example must still work against current `master`.
- **Scope** — skills describe *when* to activate and *what steps* to run. Guidelines describe *conventions that persist*. Don't mix.
- **Non-bloat** — prefer tables and bullets over prose. One skill = one clear workflow.
- **Trigger words in frontmatter `description`** — if a new workflow exists, make sure someone typing the natural-language ask can discover the skill.

If any `.ai/` file changed, sync and verify:

```bash
vendor/bin/testbench package-boost:sync || true
git status --short .claude/ .github/ CLAUDE.md AGENTS.md
```

All generated files must be committed together with their `.ai/` sources (per the `ai-guidelines` skill).

## Quick Reference

| Step           | Command                                                         | Pass criteria                    |
|----------------|-----------------------------------------------------------------|----------------------------------|
| 1. Rector      | `vendor/bin/rector process \|\| true`                           | 0 files changed                  |
| 2. Pint        | `vendor/bin/pint --dirty --format agent \|\| true`              | clean                            |
| 3. PHPStan     | `vendor/bin/phpstan analyse --memory-limit=2G \|\| true`        | 0 errors                         |
| 4. Tests       | `vendor/bin/phpunit \|\| true`                                  | 0 failures                       |
| 5a. README     | manual scan vs `git log <last-tag>..HEAD`                       | no stale claims                  |
| 5b. Boost docs | `vendor/bin/testbench package-boost:sync \|\| true`             | `.ai/` ↔ generated files in sync |

## Release Notes

Only draft release notes **after** all steps pass. The GitHub release body is prepended to `CHANGELOG.md` automatically by `.github/workflows/update-changelog.yml` on publish. See the `release-automation` guideline.

Do not edit `CHANGELOG.md` manually as part of the release PR.

## Important

- Run every step, in order.
- Do not push if any step fails. Fix, then restart the checklist from step 1.
- Step 5a and 5b are the most common source of silent drift — the README and shipped skills are read by downstream users. Delete stale content before adding new.
