<?php

namespace Webkul\WebForm\DataGrids;

use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class WebFormDataGrid extends DataGrid
{
    /**
     * Prepare query builder.
     */
    public function prepareQueryBuilder(): Builder
    {
        $queryBuilder = DB::table('web_forms')
            ->addSelect(
                'web_forms.id',
                'web_forms.title',
            );

        $this->addFilter('id', 'web_forms.id');

        return $queryBuilder;
    }

    /**
     * Prepare columns.
     */
    public function prepareColumns(): void
    {
        $this->addColumn([
            'index'    => 'id',
            'label'    => trans('admin::app.settings.webforms.index.datagrid.id'),
            'type'     => 'string',
            'sortable' => true,
        ]);

        $this->addColumn([
            'index'    => 'title',
            'label'    => trans('admin::app.settings.webforms.index.datagrid.title'),
            'type'     => 'string',
            'sortable' => true,
        ]);
    }

    /**
     * Prepare actions.
     */
    public function prepareActions(): void
    {
        $this->addAction([
            'index'  => 'view',
            'icon'   => 'icon-eye',
            'title'  => trans('admin::app.settings.webforms.index.datagrid.view'),
            'method' => 'GET',
            'url'    => fn ($row) => route('admin.settings.web_forms.view', $row->id),
        ]);

        $this->addAction([
            'index'  => 'edit',
            'icon'   => 'icon-edit',
            'title'  => trans('admin::app.settings.webforms.index.datagrid.edit'),
            'method' => 'GET',
            'url'    => fn ($row) => route('admin.settings.web_forms.edit', $row->id),
        ]);

        $this->addAction([
            'index'  => 'delete',
            'icon'   => 'icon-delete',
            'title'  => trans('admin::app.settings.webforms.index.datagrid.delete'),
            'method' => 'DELETE',
            'url'    => fn ($row) => route('admin.settings.web_forms.delete', $row->id),
        ]);
    }
}
