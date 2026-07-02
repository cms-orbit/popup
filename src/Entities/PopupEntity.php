<?php

declare(strict_types=1);

namespace CmsOrbit\Popup\Entities;

use CmsOrbit\Core\Foundation\Entity\DocumentEntity;
use CmsOrbit\Core\Screen\Field;
use CmsOrbit\Core\Screen\Fields\CheckBox;
use CmsOrbit\Core\Screen\Fields\Code;
use CmsOrbit\Core\Screen\Fields\DateTimer;
use CmsOrbit\Core\Screen\Fields\Input;
use CmsOrbit\Core\Screen\Fields\Quill;
use CmsOrbit\Core\Screen\Fields\Select;
use CmsOrbit\Core\Screen\Sight;
use CmsOrbit\Core\Screen\TD;
use CmsOrbit\Popup\Models\Popup;

/**
 * Admin descriptor for the Popup document type.
 */
class PopupEntity extends DocumentEntity
{
    public function model(): string
    {
        return Popup::class;
    }

    public function icon(): string
    {
        return 'bs.window-stack';
    }

    public function sort(): int
    {
        return 5900;
    }

    /**
     * @return Field[]
     */
    public function fields(): array
    {
        return [
            Input::make('title')->title(__('Title'))->required(),
            Quill::make('content')->title(__('Content')),
            DateTimer::make('started_at')
                ->title(__('Started at'))
                ->enableTime()
                ->format('Y-m-d H:i:S')
                ->required(),
            DateTimer::make('ended_at')
                ->title(__('Ended at'))
                ->enableTime()
                ->format('Y-m-d H:i:S')
                ->allowEmpty(),
            Input::make('ignore_days')
                ->type('number')
                ->title(__('Ignore days'))
                ->help(__('Days to hide the popup after the visitor chooses "do not show again".')),
            CheckBox::make('show_title')->title(__('Show title'))->sendTrueOrFalse(),
            Code::make('styles')
                ->title(__('Styles'))
                ->language('json')
                ->help(__('JSON style overrides (width, position, ...).')),
            Select::make('approved')
                ->title(__('Approval'))
                ->options([0 => __('Rejected'), 10 => __('Waiting'), 30 => __('Approved')]),
        ];
    }

    /**
     * @return TD[]
     */
    public function columns(): array
    {
        return [
            TD::make('document_id', __('ID'))->sort(),
            TD::make('title', __('Title')),
            TD::make('started_at', __('Started at'))->sort(),
            TD::make('ended_at', __('Ended at'))->sort(),
            TD::make('approved', __('Approval')),
        ];
    }

    /**
     * @return Sight[]
     */
    public function legend(): array
    {
        return [
            Sight::make('document_id', __('ID')),
            Sight::make('title', __('Title')),
            Sight::make('started_at', __('Started at')),
            Sight::make('ended_at', __('Ended at')),
            Sight::make('ignore_days', __('Ignore days')),
            Sight::make('approved', __('Approval')),
        ];
    }
}
