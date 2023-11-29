<?php
namespace App\Http\Controllers;

use App\Datatables\ProductCategoryDatatable;
use App\Http\Forms\ProductCategoryForm;
use App\Http\Requests\ProductCategoryRequest;
use App\Invoicer\Repositories\Contracts\ProductCategoryInterface as Category;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use Kris\LaravelFormBuilder\FormBuilderTrait;
use Laracasts\Flash\Flash;

class ProductCategoryController extends Controller
{
    use FormBuilderTrait;
    private $category;
    protected $formClass = ProductCategoryForm::class;
    protected $routes = [
        'index'     => 'products.category.index',
        'create'    => 'products.category.create',
        'show'      => 'products.category.show',
        'edit'      => 'products.category.edit',
        'store'     => 'products.category.store',
        'destroy'   => 'products.category.destroy',
        'update'    => 'products.category.update'
    ];
    protected $datatable = ProductCategoryDatatable::class;
    public function __construct(Category $category){
        $this->category = $category;
        View::share('heading', trans('app.categories'));
        View::share('headingIcon', 'th-large');
        View::share('showBtnCreate', true);
        View::share('btnCreateText', trans('app.new_category'));
        View::share('createDisplayMode', 'ajax-modal');
        View::share('routes', $this->routes);
        View::share('iconCreate', 'th-large');
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
    public function store(ProductCategoryRequest $request)
    {
        $data = array(
            'name' => $request->get('name')
        );
        if($this->category->create($data)){
            Flash::success(trans('app.record_created'));
            return Response::json(array('success'=>true, 'msg' => trans('app.record_created')), 200);
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
    public function update(ProductCategoryRequest $request, $id)
    {
        $data = array(
            'name' => $request->get('name')
        );
        if($this->category->updateById($id,$data)){
            Flash::success(trans('app.record_updated'));
            return Response::json(array('success'=>true, 'msg' => trans('app.record_updated')), 200);
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
