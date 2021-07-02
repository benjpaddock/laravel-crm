<?php

namespace Webkul\Admin\DataGrids\Setting;

use Webkul\UI\DataGrid\DataGrid;
use Illuminate\Support\Facades\DB;

class SourceDataGrid extends DataGrid
{
    protected $redirectRow = [
        "id"    => "id",
        "route" => "admin.settings.sources.edit",
    ];

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('lead_sources')
            ->addSelect(
                'lead_sources.id',
                'lead_sources.name',
            );

        $this->setQueryBuilder($queryBuilder);
    }

    public function addColumns()
    {
        $this->addColumn([
            'index'             => 'id',
            'label'             => trans('admin::app.datagrid.id'),
            'type'              => 'string',
            'searchable'        => true,
            'sortable'          => true,
            'filterable_type'   => 'add'
        ]);

        $this->addColumn([
            'index'             => 'name',
            'label'             => trans('admin::app.datagrid.name'),
            'type'              => 'string',
            'searchable'        => true,
            'sortable'          => true,
            'filterable_type'   => 'add'
        ]);
    }

    public function prepareActions()
    {
        $this->addAction([
            'title'  => trans('ui::app.datagrid.edit'),
            'method' => 'GET',
            'route'  => 'admin.settings.sources.edit',
            'icon'   => 'pencil-icon',
        ]);

        $this->addAction([
            'title'        => trans('ui::app.datagrid.delete'),
            'method'       => 'DELETE',
            'route'        => 'admin.settings.sources.delete',
            'confirm_text' => trans('ui::app.datagrid.massaction.delete', ['resource' => 'source']),
            'icon'         => 'trash-icon',
        ]);
    }

    public function prepareMassActions()
    {
    }
}
