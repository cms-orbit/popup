# CMS Orbit Popup Guidelines

`cms-orbit/popup` provides document-based popup content with scheduling (`started_at`, `ended_at`),
"dismiss for N days" (`ignore_days`), style JSON, and the `OrbitPopups` React overlay.

## Package independence

- Admin CRUD and API route (`popups.active`) ship inside the package.
- The React overlay component lives in `resources/js/components/OrbitPopups.tsx`.
- Vite alias `@cms-orbit/popup` is declared via `resources/orbit/frontend.json` and applied by
  `php artisan orbit:frontend-sync`.

## Internationalization (required)

- PHP entity labels: `__()` + `ko.json`.
- React overlay buttons ("Dismiss", "Don't show again today", etc.): `useT()` + `ko.json`.
- Never hardcode Korean/English in `OrbitPopups.tsx`.

## Host setup

| Step | Required |
| --- | --- |
| `composer require cms-orbit/popup` + `migrate` | Yes |
| `php artisan orbit:frontend-sync` | Yes |
| Render `<OrbitPopups />` once in a host layout | **Yes** (single JSX line) |

The layout inclusion is intentional: hosts choose which public layout shows popups. Document
this in README; do not assume a global layout exists in Core.

## Contributing

Do not embed host layout files in the package. Export `OrbitPopups` from `resources/js/index.ts`
and document the one-line integration. Follow `orbit-i18n` for all visible strings.
