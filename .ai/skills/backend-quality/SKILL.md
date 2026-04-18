---
name: backend-quality
description: "Runs backend code quality checks in two tiers: Pint + related tests (every change), Pint + PHPStan + Rector + full suite (completion only). Activate after making changes to PHP files, or when user mentions: pint, phpstan, rector, code quality, static analysis, code style, run checks."
---

# Backend Code Quality

Run backend quality checks after making changes to PHP files. Which checks to run depends on where you are in the workflow — see the two tiers below.

## When to Use This Skill

Activate this skill when:
- PHP files have been created or modified
- Finalizing a feature, bug fix, or refactor that touched PHP code
- The user asks to run backend checks, Pint, or tests
- Before creating a PR with PHP changes

## Two Tiers of Checks

### Tier 1: During Development (after each change)

Run these checks every time you modify PHP files — they are fast:

**1. Pint (Code Style)**

```bash
vendor/bin/pint --dirty --format agent
```

Fix any formatting issues. Re-run until clean.

**2. Related Tests Only**

Run the minimum scope needed:

```bash
# Specific test file
vendor/bin/phpunit tests/RelevantTest.php

# Filter by test name
vendor/bin/phpunit --filter=test_method_name
```

All related tests must pass.

### Tier 2: At Completion (once, at the very end)

Run these checks **only when the feature, bug fix, or spec is fully implemented** — right before creating a PR or marking work as done.

Order matters: Rector → Pint → PHPStan → tests. Each step can surface work the next one relies on.

**1. Rector**

```bash
vendor/bin/rector process
```

Must report **0 files changed**. If Rector modifies files, review the diff, keep the changes, and re-run until clean. Pint may need a follow-up pass after Rector.

**2. Pint** (after any Rector fixes)

```bash
vendor/bin/pint --dirty --format agent
```

**3. PHPStan**

```bash
vendor/bin/phpstan analyse --memory-limit=2G
```

Must show 0 errors. Fix real issues — do not pad `phpstan-baseline.neon`. Only regenerate the baseline after a deliberate strictness ratchet, and have the user confirm.

**4. Full Test Suite**

```bash
vendor/bin/phpunit
```

Must show 0 failures. This catches cross-cutting regressions.

**5. Cross-matrix sanity (when dependencies or version-specific code changed)**

The CI matrix runs PHP 8.2/8.3/8.4 × Laravel 12/13 × prefer-lowest/prefer-stable. If your change touched `composer.json`, version-sensitive API usage, or framework-compat shims, verify both ends of the matrix locally:

```bash
composer update --prefer-lowest --prefer-dist --no-interaction
vendor/bin/phpunit

composer update --prefer-stable --prefer-dist --no-interaction
vendor/bin/phpunit
```

## Quick Reference

| Check           | Command                                     | When to run              | Pass criteria   |
|-----------------|---------------------------------------------|--------------------------|-----------------|
| Code style      | `vendor/bin/pint --dirty --format agent`    | Every change             | No changes      |
| Related tests   | `vendor/bin/phpunit [--filter]`             | Every change             | 0 failures      |
| Rector          | `vendor/bin/rector process`                 | Completion only          | 0 files changed |
| PHPStan         | `vendor/bin/phpstan analyse --memory-limit=2G` | Completion only       | 0 errors        |
| Full test suite | `vendor/bin/phpunit`                        | Completion only          | 0 failures      |
| Lowest matrix   | `composer update --prefer-lowest` + phpunit | Completion + dep change  | 0 failures      |

Shortcut: `composer qa` chains format → rector → phpstan.

## Important

- **Do NOT run PHPStan, Rector, or the full test suite mid-feature** — they are slow and waste time when the code is still in flux.
- When the user explicitly asks to run any of these, always obey regardless of tier.
- **Fix PHPStan errors at the source.** Do not add `@phpstan-ignore` comments, grow `phpstan-baseline.neon`, or relax `phpstan.neon.dist` (level, `strictRules`, `type_coverage`, `cognitive_complexity`) to make errors disappear. The only legitimate exception is an upstream bug — confirm with the user before suppressing.
