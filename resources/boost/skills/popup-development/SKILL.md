---
name: popup-development
description: Implement CMS Orbit popup overlays — DocumentEntity CRUD, active popup API, OrbitPopups React component, styles JSON, and i18n. Activate when editing cms-orbit/popup entities, API routes, or overlay UI.
---

# Popup package development

## When to use

- Changing popup fields, scheduling logic, or the active popups endpoint
- Updating `OrbitPopups` styling/behavior
- Adding translation keys for overlay actions

## Workflow

1. Keep `PopupEntity` field labels wrapped in `__()`.
2. Active popup query must respect `started_at`, `ended_at`, and publish state.
3. `OrbitPopups` fetches `/popups/active` by default; allow `endpoint` prop override.
4. All button/copy strings via `useT()`; update `resources/lang/ko.json`.
5. Register Vite alias only through `resources/orbit/frontend.json` — no host paths in package code.

## Host integration snippet

```tsx
import { OrbitPopups } from '@cms-orbit/popup';

export function SiteLayout({ children }: { children: React.ReactNode }) {
    return (
        <>
            {children}
            <OrbitPopups />
        </>
    );
}
```

Run `php artisan orbit:frontend-sync` after install so `@cms-orbit/popup` resolves.

## Testing

- Feature test the JSON/active endpoint response shape and date filtering.
- Do not require a full browser layout test inside the package unless using Pest browser plugin.
