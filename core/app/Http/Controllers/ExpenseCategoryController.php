<?php

namespace App\Http\Controllers;

use App\Datatables\ExpenseCategoryDatatable;
use App\Http\Forms\ExpenseCategoryForm;
use App\Invoicer\Repositories\Contracts\ExpenseCategoryInterface as Category;
use App\Http\Requests\ExpenseCategoryRequest;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use Kris\LaravelFormBuilder\FormBuilderTrait;
use Laracasts\Flash\Flash;

class ExpenseCategoryController extends Controller
{
    use FormBuilderTrait;
    private $category;
    protected $formClass = ExpenseCategoryForm::class;
    protected $datatable = ExpenseCategoryDatatable::class;
    protected $routes = [
        'index'     => 'expenses.category.index',
        'create'    => 'expenses.category.create',
        'show'      => 'expenses.category.show',
        'edit'      => 'expenses.category.edit',
        'store'     => 'expenses.category.store',
        'destroy'   => 'expenses.category.destroy',
        'update'    => 'expenses.category.update'
    ];
    public function __construct(Category $category){
        $this->category = $category;
        View::share('heading', trans('app.categories'));
        View::share('headingIcon', 'credit-card');
        View::share('showBtnCreate', true);
        View::share('btnCreateText', trans('app.new_category'));
        View::share('createDisplayMode', 'ajax-modal');
        View::share('routes', $this->routes);
        View::share('iconCreate', 'credit-card');
    }
    public function index()
    {
        $datatable = App::make($this->datatable);
        return $datatable->render('crud.index');
    }
    public function create()
    {
        $form = $this->form($this->formClass, [
            'method' => 'POST',
            'url' => route($this->routes['store']),
            'class' => 'needs-validation row ajax-submit',
            'novalidate'
        ]);
        $heading = trans('app.add_category');
        return view('crud.modal',compact('heading','form'));
    }
    public function store(ExpenseCategoryRequest $request)
    {
        $data = array(
            'name' => $request->get('name')
        );
        if($this->category->create($data)){
            Flash::success(trans('app.record_created'));
            return Response::json(array('success'=>true, 'msg' => trans('app.record_created')), 201);
        }
        return Response::json(array('success'=>false, 'msg' => trans('app.record_creation_failed')), 422);
    }
    public function edit($id)
    {
        $category = $this->category->getById($id);
        $form = $this->form($this->formClass, [
            'method' => 'PATCH',
            'url' => route($this->routes['update'],$category->uuid),
            'class' => 'needs-validation row ajax-submit',
            'novalidate',
            'model'=> $category
        ]);
        $heading = trans('app.edit_category');
        return view('crud.modal', compact('heading','form'));
    }
    public function update(ExpenseCategoryRequest $request, $id)
    {
        $data = array(
            'name' => $request->get('name')
        );
        if($this->category->updateById($id,$data)){
            Flash::success(trans('app.record_updated'));
            return Response::json(array('success'=>true, 'msg' => trans('app.record_updated')), 201);
        }
        return Response::json(array('success'=>false, 'msg' =>  trans('app.record_update_failed')), 422);
    }
    public function destroy($id)
    {
        if($this->category->deleteById($id)){
            Flash::success(trans('app.record_deleted'));
        }
        else {
            Flash::error(trans('app.record_deletion_failed'));
        }
        if (request()->ajax()) {
            return response()->json([
                'type' => 'success',
                'message' => trans('app.record_deletion_failed'),
                'action' => 'refresh_datatable'
            ]);
        } else {
            return redirect(route($this->routes['index']));
        }    
    }
}
