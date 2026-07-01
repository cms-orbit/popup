<?php

declare(strict_types=1);

namespace CmsOrbit\Popup\Models;

use CmsOrbit\Core\Document\DocumentModel;
use CmsOrbit\Popup\Database\Factories\PopupFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * Popup document type.
 *
 * Document meta (title, content, approval, counters) lives on the central
 * documents / document_contents tables managed by DocumentModel. The scheduling
 * and presentation columns (started_at, ended_at, ignore_days, show_title,
 * styles) live on the popups table.
 *
 * @property string|null $title
 * @property string|null $content
 * @property int|null $ignore_days
 * @property bool $show_title
 * @property array<string, mixed>|null $styles
 * @property Carbon|null $started_at
 * @property Carbon|null $ended_at
 */
class Popup extends DocumentModel
{
    use SoftDeletes;

    protected $table = 'popups';

    protected $guarded = [];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'read_count' => 'integer',
        'comment_count' => 'integer',
        'assent_count' => 'integer',
        'dissent_count' => 'integer',
        'ignore_days' => 'integer',
        'show_title' => 'boolean',
        'styles' => 'array',
        'is_notice' => 'boolean',
        'is_secret' => 'boolean',
        'approved' => 'integer',
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    public function documentType(): string
    {
        return 'popup';
    }

    protected static function newFactory(): PopupFactory
    {
        return PopupFactory::new();
    }

    /**
     * Approved popups whose display window currently includes now().
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query
            ->where('documents.approved', 30)
            ->where('popups.started_at', '<=', now())
            ->where(function (Builder $q): void {
                $q->whereNull('popups.ended_at')
                    ->orWhere('popups.ended_at', '>=', now());
            });
    }
}
