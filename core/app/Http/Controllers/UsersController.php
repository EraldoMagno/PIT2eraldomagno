<?php namespace App\Http\Controllers;

use App\Datatables\UserDatatable;
use App\Http\Forms\UserForm;
use App\Http\Requests\UserFormRequest;
use App\Invoicer\Repositories\Contracts\UserInterface as User;
use App\Invoicer\Repositories\Contracts\RoleInterface as Role;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use Kris\LaravelFormBuilder\FormBuilderTrait;
use Laracasts\Flash\Flash;
class UsersController extends Controller {
    use FormBuilderTrait;
    protected $datatable = UserDatatable::class;
    protected $formClass = UserForm::class;
    private $user, $role;
    protected $routes = [
        'index'     => 'users.index',
        'create'    => 'users.create',
        'show'      => 'users.show',
        'edit'      => 'users.edit',
        'store'     => 'users.store',
        'destroy'   => 'users.destroy',
        'update'    => 'users.update'
    ];
    public function __construct(User $user, Role $role){
        $this->user = $user;
        $this->role = $role;
        View::share('heading', trans('app.users'));
        View::share('headingIcon', 'user');
        View::share('showBtnCreate', true);
        View::share('btnCreateText', trans('app.new_user'));
        View::share('createDisplayMode', 'ajax-modal');
        View::share('routes', $this->routes);
        View::share('iconCreate', 'user');
    }
	public function index(){
        $datatable = App::make($this->datatable);
        return $datatable->render('crud.index');
	}
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create(){
        if(!hasPermission('add_user', true)) return redirect('users');
        $form = $this->form($this->formClass, [
            'method' => 'POST',
            'url' => route($this->routes['store']),
            'id' => 'payment_form',
            'class' => 'needs-validation row ajax-submit',
            'novalidate'
        ]);
        $heading = trans('app.add_user');
        return view('crud.modal', compact('heading','form'));
	}
    /**
     * Store a newly created resource in storage.
     * @param UserFormRequest $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function store(UserFormRequest $request)
	{
        $data = array('username' => $request->username,
                      'name' => $request->name,
                      'email' => $request->email,
                      'phone' => $request->phone,
                      'role_id' => $request->role_id,
                      'password' => bcrypt($request->password)
        );
        $user = $this->user->create($data);
        if($user){
            Flash::success(trans('app.record_created'));
            return Response::json(array('success' => true, 'msg' => trans('app.record_created')), 201);
        }
        return Response::json(array('success' => false, 'msg' => trans('app.create_failed')), 400);
	}
	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
        if(!hasPermission('edit_user', true)) return redirect('users');
		$user = $this->user->getById($id);
        unset($user->password);
        $form = $this->form($this->formClass, [
            'method' => 'PATCH',
            'url' => route($this->routes['update'],$user->uuid),
            'class' => 'needs-validation row ajax-submit',
            'novalidate',
            'model'=> $user
        ]);
        $heading = trans('app.edit_user');
        return view('crud.modal', compact('heading','form'));
	}
    /**
     * Update the specified resource in storage.
     * @param UserFormRequest $request
     * @param $uuid
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update(UserFormRequest $request, $uuid){
        $data = array('username' => $request->username,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role_id' => $request->role_id
        );
        if($request->password != ''){
            $data['password'] = bcrypt($request->password);
        }
        if($this->user->updateById($uuid, $data)){
            Flash::success('User details updated ');
            return Response::json(array('success' => true, 'msg' => trans('app.record_updated')), 200);
        }
        return Response::json(array('success' => false, 'msg' => trans('app.record_update_failed')), 411);
	}
	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id){
        if(!hasPermission('delete_user', true)) return redirect('users');
        $user = $this->user->getById($id);
        if($this->user->deleteById($id)){
            \File::delete(public_path().'/assets/img/uploads/'.$user->photo);
        }
        if (request()->ajax()) {
            return response()->json([
                'type' => 'success',
                'message' => trans('app.record_deletion_success'),
                'action' => 'refresh_datatable'
            ]);
        } else {
            return redirect(route($this->routes['index']));
        }
	}
}
