<?php
namespace App\Datatables;
use Html;
use App\Models\Client;
use App\Datatables\CoreDatatable;
use App\Models\Expense;
use Yajra\DataTables\EloquentDataTable;
class ExpenseDatatable extends CoreDatatable
{
    protected $editRoute = 'expenses.edit';
    protected $deleteRoute = 'expenses.destroy';
    protected $editDisplayMode = 'modal';
    protected $showDisplayMode = 'modal';
    protected $edit_permission;
    protected $delete_permission;
    const SHOW_URL_ROUTE = '';
    public function __construct() 
    {
        $this->edit_permission = hasPermission('edit_expense');
        $this->delete_permission = hasPermission('delete_expense');
    }
    public function dataTable($query){
        $dataTable = new EloquentDataTable($query);
        $this->action_links($dataTable, self::SHOW_URL_ROUTE);
        $dataTable->editColumn('category_id',function($row){
            return $row->category->name ?? '';
        });  
        $dataTable->editColumn('expense_date',function($row){
            return format_date($row->expense_date);
        });   
        $dataTable->editColumn('amount',function($row){
            return '<span style="display:inline-block">'.$row->currency.'</span> <span style="display:inline-block"> '.format_amount($row->amount).'</span>';
        });   
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
    public function query(Expense $model)
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
            'expense_date' => [
                'data' => 'expense_date',
                'data_type' => 'text',
                'title' => trans('app.date')
            ],
            'category_id' => [
                'data' => 'category_id',
                'data_type' => 'text',
                'title' => trans('app.category')
            ],
            'amount' => [
                'data' => 'amount',
                'data_type' => 'text',
                'title' => trans('app.amount')
            ]
        ]+$this->btnAction;
    }
}
