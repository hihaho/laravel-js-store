<package-boost-guidelines>
# Package Boost Guidelines

These guidelines replace Laravel Boost's default foundation for
repositories that are **Laravel packages**, not applications. The
framing, tooling, and trade-offs differ — follow this version when
working inside a package codebase.

## Foundational Context

This codebase is a **Laravel package** distributed via Composer, not a
Laravel application. Key consequences:

- There is no `artisan`, no `app/`, no `bootstrap/`, no `routes/`, no
  `.env`, and no database by default. A Testbench-provided Laravel
  application is spun up only at test time.
- The primary artefact is the package's public API (service provider,
  facades, classes) — everything else is scaffolding.
- Downstream apps consume this package. Every public change is a
  user-facing API change governed by semver.
- `composer.json` is the source of truth for supported PHP and
  Laravel versions. Check `require.php` and `require.illuminate/*`
  before using version-specific features.

## Use `vendor/bin/testbench`, not `php artisan`

Running artisan commands directly against the package fails — there is
no host application. Use Testbench's binary:

| Instead of | Use |
|---|---|
| `php artisan test` | The package's configured test runner (`vendor/bin/pest` or `vendor/bin/phpunit`) |
| `php artisan tinker` | `vendor/bin/testbench tinker` |
| `php artisan make:*` | Create files manually under `src/` |
| `php artisan vendor:publish` | `vendor/bin/testbench vendor:publish` |

### Commands that require `laravel/boost`

These only apply when the package has `laravel/boost` as a dev
dependency. Skip if Boost isn't installed — `package-boost:sync`
prints a warning and moves on.

| Instead of | Use |
|---|---|
| `php artisan boost:install` | `vendor/bin/testbench boost:install` |
| `php artisan boost:mcp` | `vendor/bin/testbench boost:mcp` |

Register the package's service provider in `testbench.yaml` under
`providers:` so Testbench boots it. Published files land in
`workbench/` by default, not `config/` or `resources/` of a host app.

## Source Layout

- `src/` — package source, PSR-4 autoloaded per `composer.json`
- `tests/` — Pest or PHPUnit suite, base case `Orchestra\Testbench\TestCase`
- `config/` — publishable defaults (the file shipped with the package)
- `resources/` — views, translations, Boost skills / guidelines
- `database/migrations`, `database/factories` — only if the package
  ships them
- `workbench/` — developer-only Testbench scaffolding; never shipped

Check sibling files before inventing structure. Do not introduce new
top-level directories without a clear reason.

## Cross-Version Compatibility

Supporting multiple Laravel / PHP majors is routine for packages.
Activate `cross-version-laravel-support` **before** writing the
code; activate `ci-matrix-troubleshooting` **after** a matrix cell
has failed.

## Conventions

- Match existing code style, naming, and structural patterns — check
  sibling files before writing new ones.
- Use descriptive names (`resolvePublishDestination`, not `resolve()`).
- Reuse existing helpers before adding new ones.
- Do not add dependencies without approval; every new `require` is a
  constraint downstream consumers inherit.

## Tests Are the Specification

The package has no running application to click through. Tests are how
behaviour is pinned down.

- Write tests alongside any behavioural change. Feature tests through
  Testbench are preferred over ad-hoc tinker scripts.
- Do not create "verification scripts" when a test can prove the same
  thing.
- Run the project's configured test runner (`vendor/bin/pest` or
  `vendor/bin/phpunit`) before claiming a change is done.

## Public API Discipline

- Every `public`, `protected`, or exported symbol is part of the
  package's surface. Breaking changes require a major version bump.
- Prefer `final` classes and `private`/`@internal` markers for anything
  not intended for extension.
- Keep config keys, published asset paths, and service container
  bindings stable across patch and minor versions.

## Documentation Files

Only create or edit documentation (README, CHANGELOG, docs/) when
explicitly requested or when a behaviour change requires it.

## Replies

Be concise. Focus on what changed and why. Skip restating what the
diff already shows.

---

# Release Automation

## CHANGELOG.md is updated automatically — do NOT edit by hand for releases

`CHANGELOG.md` is kept in sync with GitHub releases by `.github/workflows/update-changelog.yml`. When a release is published (not just drafted), the workflow uses `stefanzweifel/changelog-updater-action` to prepend the release body to `CHANGELOG.md` and commits the update back to the release branch.

This means:

- **Do not** add changelog entries manually when preparing a release. The release body pasted into the GitHub release becomes the changelog entry automatically.
- **Do not** include a changelog diff in the release PR — the post-release commit comes from CI.
- If the changelog needs a fix *after* a release, edit `CHANGELOG.md` directly and commit — but this is unusual and only for typos or formatting issues in the auto-generated entry.

## Release workflow (summary)

1. Merge release-worthy commits to `master`
2. Tag and create the GitHub release; paste the release body (markdown) into the release form
3. CI automatically prepends the release body to `CHANGELOG.md` and commits it back

No manual `CHANGELOG.md` edits are part of the release PR.

## Verification Before Completion

Before claiming any work is complete or successful, run the verification command fresh and confirm the output. Evidence before claims, always.

### Required Before Any Completion Claim

1. **Run** the relevant command (in the current message, not from memory)
2. **Read** the full output
3. **Confirm** it supports the claim
4. **Then** state the result with evidence

### During Development (after each change)

| Claim            | Required verification                              |
|------------------|----------------------------------------------------|
| Code style clean | `vendor/bin/pint --dirty --format agent` output    |
| Tests pass       | Related tests pass via `--filter` or specific file |
| Bug fixed        | Previously failing test now passes                 |

### At Completion Only (feature/phase done, before PR)

These are slower — only run them once at the very end, in this order:

| Claim             | Required verification                                                                  |
|-------------------|----------------------------------------------------------------------------------------|
| Rector clean      | `vendor/bin/rector process` showing 0 files changed                                    |
| PHPStan clean     | `vendor/bin/phpstan analyse --memory-limit=2G` showing 0 errors                        |
| Full suite passes | `vendor/bin/phpunit` output showing 0 failures                                         |
| Matrix sanity     | `composer update --prefer-lowest` + phpunit (if deps or version-specific code changed) |
| Feature complete  | All above checks pass                                                                  |

Shortcut: `composer qa` runs format → rector → phpstan in one go.

### Always Capture Command Output

Append `|| true` to verification commands so the output is always captured, even on failure. Without it, a non-zero exit code can hide the output, forcing an expensive second run just to read the errors.

```bash
# CORRECT — output always visible
vendor/bin/phpunit --filter=testName || true
vendor/bin/pint --dirty --format agent || true
vendor/bin/phpstan analyse --memory-limit=2G || true

# WRONG — output lost on failure, wastes time re-running
vendor/bin/phpunit --filter=testName
```

### Never Use Without Evidence

- "should work now"
- "that should fix it"
- "looks correct"
- "I'm confident this works"

These phrases indicate missing verification. Run the command first, then report what actually happened.
</package-boost-guidelines>
