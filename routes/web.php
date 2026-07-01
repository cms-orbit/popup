<?php

declare(strict_types=1);

use CmsOrbit\Popup\Http\Controllers\PopupController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Popup public routes
|--------------------------------------------------------------------------
|
| Endpoint consumed by the OrbitPopups overlay component to fetch the popups
| that should currently be displayed. Loaded by the PopupServiceProvider
| inside the "web" middleware group.
*/
Route::get('popups/active', [PopupController::class, 'active'])->name('popups.active');
