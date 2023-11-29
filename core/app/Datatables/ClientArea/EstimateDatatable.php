<?php
namespace App\Datatables\ClientArea;
use Html;
use App\Datatables\CoreDatatable;
use App\Models\Estimate;
use Yajra\DataTables\EloquentDataTable;
class EstimateDatatable extends CoreDatatable
{
    protected $editDisplayMode = 'normal';
    protected $showDisplayMode = 'normal';
    protected $downloadRoute = 'cestimate_pdf';
    protected $show_route = 'cestimates.show';
    protected $edit_permission;
    protected $delete_permission;
    const SHOW_URL_ROUTE = '';
    public function dataTable($query){
        $dataTable = new EloquentDataTable($query);
        $this->action_links($dataTable, self::SHOW_URL_ROUTE);    
        $dataTable->editColumn('estimate_date',function($row){
            return format_date($row->estimate_date);
        });  
        $dataTable->editColumn('amount',function($row){
            return '<span style="display:inline-block">'.$row->currency.'</span> <span style="display:inline-block"> '.format_amount($row->totals['grandTotal']).'</span>';
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
    public function query(Estimate $model)
    {
        return $model->newQuery()->where('client_id',auth()->guard('user')->id())->select();
    }
    protected function getColumns(){
        return [
                'estimate_no' => [
                    'data' => 'estimate_no',
                    'data_type' => 'text',
                    'title' => trans('app.estimate_number')
                ],
                'estimate_date' => [
                    'data' => 'estimate_date',
                    'data_type' => 'text',
                    'title' => trans('app.estimate_date')
                ],
                'amount' => [
                    'data' => 'amount',
                    'data_type' => 'text',
                    'title' => trans('app.amount'),
                    'searchable'=>false,
                    'orderable'=>false
                ]
            ]+$this->btnAction;
    }
}
