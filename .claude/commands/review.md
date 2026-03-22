# Code Review

Run a thorough code review on the most recently changed files (or on $ARGUMENTS if specified).

## What to check

1. **Correctness** — does the logic do what the function/method name promises?
2. **Security**
   - No SQL injection (use query builder / Eloquent — no raw user input in queries)
   - No XSS (Blade `{{ }}` escapes; only use `{!! !!}` for trusted HTML)
   - Authorization gates present on all create/update/delete actions
   - No secrets or credentials hardcoded in source files
3. **Laravel conventions**
   - Thin controllers — complex logic moved to Action classes or Models
   - Validation via Form Request, not inline `$request->validate()` in controllers with >3 rules
   - Eager loading used where relationships are accessed in loops (`->with(...)`)
   - Migration `down()` method is correct and safe
4. **Code style** (based on CLAUDE.md)
   - Single quotes for strings
   - Early returns over nested if/else
   - No `dd()`, `dump()`, `var_dump()`, or `ray()` left in code
5. **Tests** — is there a Feature or Unit test covering the changed logic?
6. **UI/Blade** (if views changed)
   - No inline styles — Tailwind only
   - All form inputs have a `<label>`
   - Destructive actions have a confirmation

## Output format

For each file reviewed, output:

```
### path/to/file.php
✅ OK — brief note
⚠️  WARN — description of issue (line X)
❌ BUG/SECURITY — description of critical issue (line X)
```

End with a **Summary** section: overall quality score (1–10), top 3 action items.

Use the **Haiku** model for this review unless the diff is >300 lines.
