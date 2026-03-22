# Run Tests

Run the Laravel test suite and report results. If $ARGUMENTS is provided, filter tests to that class or method name.

## Steps

1. Run the test suite:
   ```bash
   php artisan test $ARGUMENTS --ansi 2>&1
   ```

2. If tests fail:
   - Read the failing test file and the relevant source file
   - Identify whether the failure is in the **test** (wrong assertion) or the **source code** (actual bug)
   - Fix the source code bug if it's a real bug; update the test only if the test expectation is wrong
   - Re-run until green

3. If all tests pass, also check for missing coverage:
   - Are there Feature tests for every Controller action?
   - Are there Unit tests for non-trivial Model methods?
   - If obvious gaps exist, create the missing tests

## Test creation conventions

```php
// tests/Feature/PostTest.php
use RefreshDatabase;

public function test_authenticated_user_can_create_post(): void
{
    $user = User::factory()->create()->assignRole('editor');
    $this->actingAs($user)
         ->post(route('posts.store'), [...])
         ->assertRedirect();
    $this->assertDatabaseHas('posts', ['title' => '...']);
}

public function test_viewer_cannot_create_post(): void
{
    $user = User::factory()->create()->assignRole('viewer');
    $this->actingAs($user)
         ->post(route('posts.store'), [...])
         ->assertForbidden();
}
```

## Output

Report:
- Total tests / passed / failed / skipped
- List of failing tests with root cause
- List of any newly created test files
