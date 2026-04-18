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
