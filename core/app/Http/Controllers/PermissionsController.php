<?php

namespace App\Http\Controllers;

use App\Datatables\PermissionDatatable;
use App\Http\Forms\PermissionForm;
use App\Http\Requests\PermissionFormRequest;
use App\Invoicer\Repositories\Contracts\PermissionInterface as Permission;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Response;
use Laracasts\Flash\Flash;
use Illuminate\Support\Facades\View;
use Kris\LaravelFormBuilder\FormBuilderTrait;

class PermissionsController extends Controller
{
    private $role, $permission;
    use FormBuilderTrait;
    protected $formClass = PermissionForm::class;
    protected $datatable = PermissionDatatable::class;
    protected $routes = [
        'index' => 'settings.permission.index',
        'create' => 'settings.permission.create',
        'store' => 'settings.permission.store',
        'update' => 'settings.permission.update'
    ];
    public function __construct(Permission $permission){
        $this->middleware('permission:edit_setting');
        $this->permission = $permission;
        View::share('heading', trans('app.permissions'));
        View::share('headingIcon', 'cog');
        View::share('createDisplayMode', 'ajax-modal');
        View::share('routes', $this->routes);
        View::share('showBtnCreate', false);
    }

    public function index(){
        $datatable = App::make($this->datatable);
        return $datatable->render('settings.index');
    }

    public function store(PermissionFormRequest $request){
        $permission_details = ['name'=>$request->get('name'), 'description'=>$request->get('description')];
        if($this->permission->create($permission_details))
            Flash::success(trans('app.record_created'));
        else
            Flash::error(trans('app.create_failed'));
        return redirect('settings/permissions');
    }

    public function edit($id){
        $permission = $this->permission->getById($id);
        $form = $this->form($this->formClass, [
            'method' => 'PATCH',
            'url' => route($this->routes['update'],$id),
            'class' => 'needs-validation row ajax-submit',
            'novalidate',
            'model'=> $permission
        ]);
        $heading = trans('app.edit_permission');
        return view('crud.modal',compact('heading','form'));
    }

    public function update(PermissionFormRequest $request, $id)
    {
        $permission = ['description' => $request->get('description')];
        if($this->permission->updateById($id, $permission)){
            Flash::success(trans('app.record_updated'));
            return Response::json(array('success' => true, 'msg' => trans('app.record_updated')), 200);
        }
        return Response::json(array('success' => false, 'msg' => trans('app.record_update_failed')), 400);
    }
    public function destroy($id)
    {
        if($this->permission->deleteById($id)){
            flash()->success('permission Record Deleted  ');
            return redirect('settings/permissions');
        }

    }
}
