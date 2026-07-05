<?php

declare(strict_types=1);

namespace CmsOrbit\Popup;

use CmsOrbit\Core\Foundation\Entity\EntityRegistry;
use CmsOrbit\Core\Foundation\OrbitServiceProvider;
use CmsOrbit\Core\Support\Facades\Orbit;
use CmsOrbit\Core\Support\Locale;
use CmsOrbit\Popup\Entities\PopupEntity;
use Illuminate\Support\Facades\Route;

/**
 * Registers the Popup document type with Orbit: loads migrations and
 * translations, wires the public "active popups" endpoint, and submits the
 * entity descriptor (menu / permissions / CRUD routes) to Core.
 */
class PopupServiceProvider extends OrbitServiceProvider
{
    public function register(): void
    {
        // Register the entity as soon as Core's EntityRegistry is resolved. This
        // is order-independent: Core loads the entity CRUD routes during its own
        // boot(), which may run before this package's boot(), so registering in
        // boot() would be too late for the routes to pick the entity up.
        $this->app->afterResolving(EntityRegistry::class, function (EntityRegistry $registry): void {
            $registry->registerClass([PopupEntity::class]);
        });

        if ($this->app->resolved(EntityRegistry::class)) {
            $this->app->make(EntityRegistry::class)->registerClass([PopupEntity::class]);
        }

        $this->loadJsonTranslationsFrom(__DIR__.'/../resources/lang');
        Locale::registerPath(__DIR__.'/../resources/lang');
    }

    public function boot(): void
    {
        Orbit::registerSection('documents', 'bs.file-earmark-text', __('Documents'), 5000);

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        Route::middleware('web')->group(__DIR__.'/../routes/web.php');
    }
}
