<?php

namespace App\Http\Controllers;

use App\Datatables\RoleDatatable;
use App\Http\Forms\RoleForm;
use App\Http\Requests\RoleFormRequest;
use App\Invoicer\Repositories\Contracts\RoleInterface as Role;
use App\Invoicer\Repositories\Contracts\PermissionInterface as Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use Kris\LaravelFormBuilder\FormBuilderTrait;
use Laracasts\Flash\Flash;

class RolesController extends Controller{
    private $role, $permission;
    use FormBuilderTrait;
    protected $formClass = RoleForm::class;
    protected $datatable = RoleDatatable::class;
    protected $routes = [
        'index' => 'settings.role.index',
        'create' => 'settings.role.create',
        'store' => 'settings.role.store',
        'update' => 'settings.role.update'
    ];
    public function __construct(Role $role, Permission $permission){
        $this->middleware('permission:edit_setting');
        $this->role = $role;
        $this->permission = $permission;
        View::share('heading', trans('app.roles'));
        View::share('headingIcon', 'users');
        View::share('btnCreateText', trans('app.new_role'));
        View::share('createDisplayMode', 'ajax-modal');
        View::share('routes', $this->routes);
        View::share('iconCreate', 'plus');
        View::share('showBtnCreate', true);
    }

    public function index(){
        $datatable = App::make($this->datatable);
        return $datatable->render('settings.index');
    }
    public function create(){
        $form = $this->form($this->formClass, [
            'method' => 'POST',
            'url' => route($this->routes['store']),
            'class' => 'needs-validation row ajax-submit',
            'novalidate'
        ]);
        $heading = trans('app.new_role');
        return view('crud.modal',compact('heading','form'));
    }

    public function store(RoleFormRequest $request){
        $role_details = ['name'=>$request->get('name'), 'description'=>$request->get('description')];
        if($this->role->create($role_details))
            Flash::success(trans('app.record_created'));
        else
            Flash::error(trans('app.create_failed'));

        return redirect('settings/roles');
    }

    public function edit($id){
        $role = $this->role->getById($id);
        $form = $this->form($this->formClass, [
            'method' => 'PATCH',
            'url' => route($this->routes['update'],$id),
            'class' => 'needs-validation row ajax-submit',
            'novalidate',
            'model'=> $role
        ]);
        $heading = trans('app.edit_role');
        return view('crud.modal',compact('heading','form'));
    }

    public function update(RoleFormRequest $request, $id)
    {
        $role = ['name' => $request->get('name'), 'description' => $request->get('description')];
        if($this->role->updateById($id, $role)){
            Flash::success(trans('app.record_updated'));
            return Response::json(array('success' => true, 'msg' => trans('app.record_updated')), 200);
        }
        return Response::json(array('success' => false, 'msg' => trans('app.record_update_failed')), 400);
    }

    public function show($id){
        $role = $this->role->getById($id);
        $permissions = $this->permission->all();
        return view('settings.roles.permissions', compact('role','permissions'));
    }

    public function assignPermission(Request $request){
        $role = $this->role->getById($request->input('role_id'));
        $permissions = $this->permission->all();
        $selected_permissions = array();
        foreach($permissions as $permission){
            if($request->has($permission->name)){
                $selected_permissions[] = $permission->uuid;
            }
        }
        if($role->assign($selected_permissions)){
            Flash::success(trans('app.record_updated'));
            return Response::json(array('success' => true, 'msg' => trans('app.record_updated')), 200);
        }
    }

    public function destroy($id)
    {
        if($this->role->deleteById($id)){
            Flash::success(trans('app.record_deleted'));
        }
        else {
            Flash::error(trans('app.record_deletion_failed'));
        }
        return redirect('settings/roles');
    }
}
