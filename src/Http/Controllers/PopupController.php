<?php

declare(strict_types=1);

namespace CmsOrbit\Popup\Http\Controllers;

use CmsOrbit\Popup\Models\Popup;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

/**
 * Public (front-facing) controller exposing the currently active popups so the
 * OrbitPopups overlay component can render them on any page.
 */
class PopupController extends Controller
{
    /**
     * Return the popups whose display window currently includes now().
     */
    public function active(): JsonResponse
    {
        $popups = Popup::query()
            ->active()
            ->orderByDesc('popups.started_at')
            ->get()
            ->map(fn (Popup $popup): array => [
                'id' => $popup->getKey(),
                'title' => $popup->getAttribute('title'),
                'content' => $popup->getAttribute('content'),
                'show_title' => (bool) $popup->getAttribute('show_title'),
                'ignore_days' => $popup->getAttribute('ignore_days'),
                'styles' => $popup->getAttribute('styles') ?? [],
            ])
            ->all();

        return response()->json(['popups' => $popups]);
    }
}
