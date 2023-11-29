<?php
namespace App\Datatables;
use App\Datatables\CoreDatatable;
use App\Models\TaxSetting;
use Yajra\DataTables\EloquentDataTable;
class TaxDatatable extends CoreDatatable
{
    protected $editRoute = 'settings.tax.edit';
    protected $deleteRoute = 'settings.tax.destroy';
    protected $editDisplayMode = 'modal';
    protected $showDisplayMode = 'modal';
    protected $edit_permission;
    protected $delete_permission;
    const SHOW_URL_ROUTE = '';
    public function __construct() 
    {
        $this->edit_permission = hasPermission('edit_tax');
        $this->delete_permission = hasPermission('delete_tax');
    }
    public function dataTable($query){
        $dataTable = new EloquentDataTable($query);
        $this->action_links($dataTable, self::SHOW_URL_ROUTE);  
        return $dataTable;
    }
    public function builder(){
        $builder = parent::builder();
        $builder->hasQueryFilters = false;
        $builder->columns($this->getColumns())
            ->setTableAttribute('class', 'table table-hover table-striped table-bordered')
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
    public function query(TaxSetting $model)
    {
        return $model->newQuery()->select();
    }
    protected function getColumns(){
        return [
            'name' => [
                'data' => 'name',
                'data_type' => 'text',
                'title' => trans('app.name')
            ],
            'value' => [
                'data' => 'value',
                'data_type' => 'text',
                'title' => trans('app.value')
            ],
            'selected' => [
                'data' => 'selected',
                'data_type' => 'boolean',
                'title' => trans('app.default')
            ]
        ]+$this->btnAction;
    }
}
