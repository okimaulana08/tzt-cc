# TZT App — Claude Code Project Guide

## Stack
- **Laravel 13** · PHP 8.4 · MySQL
- **Blade** templates · **Tailwind CSS v3** · Alpine.js (via Breeze)
- **Spatie Laravel Permission** for roles/permissions
- **Laravel Pint** for code style · **PHPUnit** for testing

---

## Coding Style

### PHP / Laravel
- Follow **PSR-12** enforced by Laravel Pint (`./vendor/bin/pint`)
- Use **single quotes** for strings unless interpolation is needed
- Prefer **early returns** over deep nesting
- Controllers stay thin — business logic belongs in dedicated Action classes or the Model
- Use **Form Request** classes for all validation (`php artisan make:request`)
- Use **Resource** classes for JSON responses if API endpoints are added
- Model relationships: always define both sides (e.g. `hasMany` + `belongsTo`)
- Always use `$fillable` (never `$guarded = []`)
- Write **PHPDoc** only for non-obvious return types on public methods

### Blade / HTML
- One Blade component per responsibility — avoid large monolithic templates
- Use `<x-component>` syntax for all components (not `@component`)
- No inline `style=""` attributes — use Tailwind utility classes only
- Semantic HTML: `<nav>`, `<main>`, `<header>`, `<section>`, `<article>`, `<aside>`

### Tailwind CSS
- Mobile-first responsive: `sm:`, `md:`, `lg:` breakpoints
- Colour palette: indigo-600 (primary), green-600 (success), red-600 (danger), yellow-500 (warning), gray-* (neutrals)
- Card pattern: `bg-white rounded-xl shadow-sm border border-gray-100 p-6`
- Button pattern: `px-4 py-2 rounded-md text-sm font-medium transition`

---

## UI/UX Design Principles
- **Consistent spacing**: use `py-8` for page padding, `space-y-6` for sections
- **Feedback always visible**: flash success/error messages at top of content area
- **Destructive actions**: require `onsubmit="return confirm(...)"` or a modal
- **Empty states**: always show a helpful message + CTA when lists are empty
- **Loading states**: disable submit buttons on form submit to prevent double-submit
- **Accessibility**: all inputs must have a `<label>`, buttons must have accessible text

---

## Testing

### Rules
- Every new feature needs at least one **Feature test** (`tests/Feature/`)
- Every Model method / helper needs a **Unit test** (`tests/Unit/`)
- Use **database transactions** (`RefreshDatabase`) — never leave test data in DB
- Use **factories** for test data; never hardcode IDs
- Tests must be **fast**: mock external HTTP calls, queues, and mail

### Conventions
```php
// Feature test naming
public function test_authenticated_user_can_create_post(): void {}
public function test_guest_cannot_access_dashboard(): void {}
public function test_editor_cannot_delete_others_post(): void {}
```

### Run tests
```bash
php artisan test                    # all tests
php artisan test --filter PostTest  # single class
php artisan test --coverage         # with coverage
```

---

## Code Review Checklist
Before merging any change, verify:
- [ ] `./vendor/bin/pint` passes with zero changes
- [ ] `php artisan test` passes with zero failures
- [ ] No `dd()`, `dump()`, `var_dump()`, or `ray()` left in code
- [ ] No hardcoded credentials, secrets, or environment values in source
- [ ] Authorization checked (`$this->authorize()` or `@can`) on all write routes
- [ ] Migrations are reversible (`down()` method works)
- [ ] N+1 queries avoided — use `with()` eager loading
- [ ] New routes follow RESTful naming (`posts.index`, `posts.store`, etc.)

---

## Deploy Mechanism

### Pre-deploy checklist
1. All tests pass locally
2. `.env.example` updated if new env vars added
3. Migrations are non-destructive (no column drops on large tables)

### Deploy sequence (production)
```bash
git pull origin main
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
npm ci && npm run build
php artisan queue:restart
```

### Rollback
```bash
php artisan migrate:rollback   # revert last migration batch
# restore previous build artifacts if needed
php artisan cache:clear
php artisan config:clear
```

---

## Cost-Friendly AI Usage

Use **Haiku** model for:
- Generating boilerplate (migrations, factories, seeders)
- Simple refactors (renaming, reformatting)
- Writing tests for already-written code

Use **Sonnet** (default) for:
- Feature implementation
- Code review and security analysis
- Architecture decisions

Avoid re-generating large files — prefer targeted `Edit` operations.

---

## Skills (Slash Commands)

Skills are **invoked manually** by typing `/skill-name`. They do NOT auto-run. `CLAUDE.md` is auto-loaded every session; code style (Pint) auto-runs via hook on every PHP file edit.

| Command | When to use |
|---|---|
| `/review` | After writing a feature or bug fix — before committing |
| `/test` | Run & fix failing tests; detect coverage gaps |
| `/style` | Full project-wide Pint check (per-file auto-runs via hook) |
| `/ui-check` | After changing Blade views — audit accessibility & UX |
| `/deploy` | Deploy locally; `/deploy prod` for production |

**Recommended workflow per task:**
```
Build feature/fix bug
  → /review           (catch issues early)
  → /test             (ensure nothing breaks)
  → /style            (final style sweep)
  → commit & push
```

---

## Common Commands

```bash
# Dev server
php artisan serve

# Watch assets
npm run dev

# Lint PHP
./vendor/bin/pint

# Run tests
php artisan test

# Create common files
php artisan make:model Foo -mfsc    # model + migration + factory + seeder + controller
php artisan make:request StoreFooRequest
php artisan make:policy FooPolicy --model=Foo

# Database
php artisan migrate:fresh --seed    # reset + re-seed (dev only)
php artisan db:seed --class=FooSeeder
```
