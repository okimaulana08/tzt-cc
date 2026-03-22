# Fix Code Style

Fix all PHP code style issues using Laravel Pint, then report what changed.

## Steps

1. **Check first** (dry run):
   ```bash
   ./vendor/bin/pint --test --ansi 2>&1
   ```

2. If issues found, **auto-fix**:
   ```bash
   ./vendor/bin/pint --ansi 2>&1
   ```

3. Show a summary of files changed

4. Optionally check JS/Blade formatting by scanning for obvious issues:
   - Blade: inconsistent indentation (2 vs 4 spaces)
   - Tailwind class ordering (note only — not auto-fixed)

## Pint config (pint.json)

This project uses the **Laravel** preset. If `pint.json` doesn't exist, create it:

```json
{
    "preset": "laravel",
    "rules": {
        "single_quote": true,
        "no_unused_imports": true,
        "ordered_imports": {
            "sort_algorithm": "alpha"
        }
    }
}
```

## Output

List every file modified by Pint. If zero files changed, confirm "Code style is clean ✅".
