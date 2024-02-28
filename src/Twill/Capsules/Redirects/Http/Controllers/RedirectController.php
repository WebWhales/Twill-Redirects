<?php

namespace TwillRedirects\Twill\Capsules\Redirects\Http\Controllers;

use A17\Twill\Http\Controllers\Admin\ModuleController as BaseModuleController;
use A17\Twill\Services\Listings\Columns\Text;
use A17\Twill\Services\Listings\TableColumns;
use TwillRedirects\Enums\RedirectTypes;
use TwillRedirects\Twill\Capsules\Redirects\Models\Redirect;
use function ltrim;

class RedirectController extends BaseModuleController
{
    protected $moduleName = 'redirects';

    protected $indexOptions = [
        'permalink' => false,
        'editInModal' => false,
        'skipCreateModal' => false,
        'includeScheduledInList' => true,
    ];

    /**
     * Similar to @see getIndexTableColumns but these will be added on top of the default columns.
     */
    protected function additionalIndexTableColumns(): TableColumns
    {
        $table = parent::additionalIndexTableColumns();

        $table->add(
            Text::make()->field('description')->title('Description')
        );

        $table->add(
            Text::make()
                ->field('from')
                ->title('From')
                ->customRender(fn(Redirect $redirect) => '/'.ltrim($redirect->from, '/'))
        );

        return $table;
    }

    protected function formData($request)
    {
        $typeOptions = [
            [
                'value' => RedirectTypes::INTERNAL,
                'label' => __('An internal path'),
            ],
            [
                'value' => RedirectTypes::EXTERNAL,
                'label' => __('An external url'),
            ],
        ];

        $browserModules = config('twill_redirects.browser_modules');

        if (! empty($browserModules)) {
            $typeOptions[] = [
                'value' => RedirectTypes::ENTITY,
                'label' => __('An internal entity'),
            ];
        }

        return ['type_options' => $typeOptions, 'browser_modules' => $browserModules];
    }
}
