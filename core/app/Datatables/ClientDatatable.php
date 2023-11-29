<?php
namespace App\Datatables;
use Html;
use App\Models\Client;
use App\Datatables\CoreDatatable;
use Yajra\DataTables\EloquentDataTable;
class ClientDatatable extends CoreDatatable
{
    protected $editRoute = 'clients.edit';
    protected $deleteRoute = 'clients.destroy';
    protected $editDisplayMode = 'modal';
    protected $showDisplayMode = 'normal';
    protected $show_route = 'clients.show';
    protected $edit_permission;
    protected $delete_permission;
    const SHOW_URL_ROUTE = '';
    public function __construct() 
    {
        $this->edit_permission = hasPermission('edit_client');
        $this->delete_permission = hasPermission('delete_client');
    }
    public function dataTable($query){
        $dataTable = new EloquentDataTable($query);
        $this->action_links($dataTable, self::SHOW_URL_ROUTE);
        $dataTable->editColumn('photo',function($data){
            $photo = $data->photo != '' ? 'uploads/client_images/'.$data->photo : 'uploads/no-image.jpg';
            return Html::image(image_url($photo),'Image',['class'=>'img-circle','width'=>'36px']);
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
    public function query(Client $model)
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
                'photo' => [
                    'data' => 'photo',
                    'data_type' => 'text',
                    'title' => trans('app.photo')
                ],
                'email' => [
                    'data' => 'email',
                    'data_type' => 'text',
                    'title' => trans('app.email')
                ],
                'phone' => [
                    'data' => 'phone',
                    'data_type' => 'text',
                    'title' => trans('app.phone')
                ],
                'country' => [
                    'data' => 'country',
                    'data_type' => 'text',
                    'title' => trans('app.country')
                ]
            ]+$this->btnAction;
    }
}
