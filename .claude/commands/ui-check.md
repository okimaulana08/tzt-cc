# UI/UX Review

Review all Blade views (or the views in $ARGUMENTS) against the project's UI/UX standards.

## Checklist per view

### Accessibility
- [ ] Every `<input>`, `<select>`, `<textarea>` has a corresponding `<label for="...">` or `aria-label`
- [ ] Buttons have descriptive text (not just icons without `aria-label`)
- [ ] Links are descriptive — no bare "click here" or "read more"
- [ ] Color is not the only way to convey information (status badges also use text)

### Layout & Spacing
- [ ] Page uses `py-8` outer padding and `max-w-7xl mx-auto` container
- [ ] Cards follow: `bg-white rounded-xl shadow-sm border border-gray-100 p-6`
- [ ] Sections separated with `space-y-6`
- [ ] Responsive: content readable on mobile (`sm:` breakpoints where needed)

### Forms
- [ ] Required fields marked with `<span class="text-red-500">*</span>`
- [ ] Validation errors displayed via `@error('field')` beneath each input
- [ ] Submit button disabled or shows loading state to prevent double-submit
- [ ] Cancel/back link always present next to submit button

### Feedback & States
- [ ] Flash success messages shown with green styling
- [ ] Flash error messages shown with red styling
- [ ] Empty list states show a helpful message + call-to-action
- [ ] Destructive actions (delete) require confirmation dialog

### Blade conventions
- [ ] No inline `style=""` attributes — Tailwind only
- [ ] `{!! !!}` used only for intentionally trusted HTML (e.g. post content from DB)
- [ ] `@can` / `@auth` guards wrap all role-restricted UI elements
- [ ] Dynamic page `<title>` set via `<x-slot name="title">`

## Output

For each view file:
```
### resources/views/posts/index.blade.php
✅ Accessibility — all inputs labelled
⚠️  WARN — delete button missing confirm dialog (line 42)
❌ BUG — inline style found on line 17, replace with Tailwind class
```

End with overall UI quality score (1–10) and top improvements list.
