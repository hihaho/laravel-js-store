---
name: bug-fixing
description: "Test-driven bug fixing workflow. Activates when: fixing bugs, debugging issues, resolving defects, investigating errors, or when user mentions: bug, fix, broken, not working, error, issue, defect, regression."
argument-hint: [bug description or issue reference]
---

# Test-Driven Bug Fixing

A disciplined approach to fixing bugs: **reproduce first, fix second**. Write a failing test that captures the bug, then fix the code to make it pass.

## Core Principle

**Never start by trying to fix the bug.** Instead:

1. Understand the bug
2. Write a test that reproduces it (fails)
3. Fix the bug
4. Verify the test passes
5. Document what was wrong

## When to Use This Skill

Use this skill when:
- Fixing reported bugs or defects
- Investigating unexpected behavior
- Resolving regression issues
- Debugging error reports

## Workflow

### Phase 1: Understand the Bug

Before writing any code:

1. **Gather information**
   - Read any linked issues or error reports
   - Ask clarifying questions if reproduction steps are unclear

2. **Identify the scope**
   - Which files/classes are likely involved?
   - Can this be reproduced with a test?

3. **Confirm understanding**
   - Summarize the bug in one sentence
   - State the expected vs actual behavior
   - Get user confirmation before proceeding

### Phase 2: Write the Failing Test

**This is the critical step.** Write a test that:

1. **Reproduces the exact scenario** that triggers the bug
2. **Fails with the current code** (proving the bug exists)
3. **Will pass when the bug is fixed**

```php
public function test_it_handles_edge_case_with_empty_data_provider(): void
{
    // Arrange: Set up the scenario that triggers the bug
    $this->app->make(Repository::class)->set('js-store.test', [ValidEmptyDataProvider::class]);

    // Act: Perform the action that fails
    $providers = DataProviderCollection::fromConfig('js-store.test');
    $providers->store();

    // Assert: What SHOULD happen (currently fails)
    $this->assertFalse($this->app->make('js-store')->data()->has('valid-empty'));
}
```

### Phase 3: Verify Test Fails

Run the test to confirm it fails:

```bash
vendor/bin/phpunit --filter=test_it_handles_edge_case_with_empty_data_provider
```

**If the test passes**: The bug may not be what we thought. Revisit Phase 1.

**If the test fails**: Proceed to fixing.

### Phase 4: Fix the Bug

Use a subagent to investigate and fix:

```
Task: Fix the bug causing [test name] to fail.

Context:
- Test file: tests/RelevantTest.php
- Test method: test_it_handles_edge_case_with_empty_data_provider
- Current error: [paste error message]

The test reproduces the bug. Find and fix the root cause.
Do NOT modify the test - only fix the production code.
Run the test after each change to verify progress.
```

### Phase 5: Verify the Fix

Run quality checks based on which files were changed:

- Use the `backend-quality` skill (Tier 1 only: Pint + related tests). Rector, PHPStan, and the full test suite run at completion — see the `backend-quality` skill for details.

### Phase 6: Document the Fix

When creating/updating a PR, include a **technical** description:

```markdown
### What was the bug?

Description of the root cause with file and line references.

### How was it fixed?

Description of the fix and why it works.

### Test coverage

Added `test_name` to verify the fix and prevent regression.
```

## Test Writing Guidelines

### Test the Specific Scenario

Don't test general functionality - test the exact scenario that was broken:

```php
// Good - tests the specific bug scenario
public function test_it_omits_providers_whose_data_returns_null(): void

// Bad - too generic
public function test_it_builds_store(): void
```

### Name Tests Descriptively

Test names should describe the scenario and expected outcome:

```php
// Good
public function test_it_resolves_provider_key_from_classname(): void
public function test_it_renders_script_block_with_custom_nonce(): void
public function test_it_flushes_shared_store_between_octane_requests(): void

// Bad
public function test_it_works(): void
public function test_it_handles_providers(): void
```

## When Tests Aren't Possible

For bugs that can't be reproduced with tests:

1. **Document why** — explain why automated testing isn't feasible
2. **Provide manual steps** — detailed reproduction steps
3. **Add defensive code** — consider adding validation or error handling
