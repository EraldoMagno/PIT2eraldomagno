<?php

namespace App\Datatables;
use App\Datatables\CoreDatatable;
use App\Models\Currency;
use Yajra\DataTables\EloquentDataTable;
class CurrencyDatatable extends CoreDatatable
{
    protected $editRoute = 'settings.currency.edit';
    protected $deleteRoute = 'settings.currency.destroy';
    const SHOW_URL_ROUTE = '';
    public function __construct() 
    {
        $this->edit_permission = hasPermission('edit_currency');
        $this->delete_permission = hasPermission('delete_currency');
    }
    public function dataTable($query)
    {
        $dataTable = new EloquentDataTable($query);
        $this->action_links($dataTable, self::SHOW_URL_ROUTE);
        return $dataTable;
    }
    public function builder()
    {
        $builder = parent::builder();
        $builder->hasQueryFilters = false;
        $builder->columns($this->getColumns())
            ->setTableAttribute('class', 'table table-hover table-striped')
            ->parameters([
                'dom' => 'Bfrtip',
                'responsive' => true,
                'stateSave' => true,
                "oLanguage" => [
                    'sLengthMenu' => "_MENU_",
                    'buttons'=> [
                        'pageLength' => " %d ".trans('app.records'),
                    ],
                    'sSearch'=>"",
                ],
                "bInfo"  => false,
                'buttons' => [
                    ['extend' => 'print', 'className' => 'btn-sm btn-info', 'text' => '<i class="fa fa-print"></i> '.trans('app.print')],
                    ['extend' => 'csv', 'className' => 'btn-sm btn-warning', 'text' => '<i class="fa fa-file-excel-o"> </i> '. trans('app.csv')],
                    ['extend' => 'pageLength', 'className' => 'btn-sm btn-success'],
                ],
                'regexp'  => true
            ]);
        if (!empty($this->filterDefinition)) {
            $builder->hasQueryFilters = true;
        }
        return $builder;
    }
    public function query(Currency $model)
    {
        return $model->newQuery()->select();
    }
    protected function getColumns()
    {
        return [
                'name' => [
                    'data' => 'name',
                    'data_type' => 'text',
                    'title' => trans('app.name'),
                ],
                'code' => [
                    'data' => 'code',
                    'data_type' => 'text',
                    'title' => trans('app.code'),
                ],
                'symbol' => [
                    'data' => 'symbol',
                    'data_type' => 'text',
                    'title' => trans('app.symbol'),
                ],
                'exchange_rate' => [
                    'data' => 'exchange_rate',
                    'data_type' => 'text',
                    'title' => trans('app.exchange_rate'),
                ],
                'active' => [
                    'data' => 'active',
                    'data_type' => 'boolean',
                    'title' => trans('app.active'),
                ],
                'default_currency' => [
                    'data' => 'default_currency',
                    'data_type' => 'boolean',
                    'title' => trans('app.default'),
                ]
            ]+$this->btnAction;
    }
}

