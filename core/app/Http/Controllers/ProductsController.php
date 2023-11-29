<?php namespace App\Http\Controllers;

use App\Datatables\ProductDatatable;
use App\Http\Forms\ProductForm;
use App\Http\Requests\ProductFormRequest;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Request;
use App\Invoicer\Repositories\Contracts\ProductInterface as Product;
use App\Invoicer\Repositories\Contracts\ProductCategoryInterface as Category;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Kris\LaravelFormBuilder\FormBuilderTrait;
use Laracasts\Flash\Flash;

class ProductsController extends Controller {
    use FormBuilderTrait;
    private $product,$category;
    protected $formClass = ProductForm::class;
    protected $datatable = ProductDatatable::class;
    protected $routes = [
        'index'     => 'products.index',
        'create'    => 'products.create',
        'show'      => 'products.show',
        'edit'      => 'products.edit',
        'store'     => 'products.store',
        'destroy'   => 'products.destroy',
        'update'    => 'products.update'
    ];
    public function __construct(Product $product,Category $category){
        $this->product = $product;
        $this->category = $category;
        View::share('heading', trans('app.products'));
        View::share('headingIcon', 'puzzle-piece');
        View::share('showBtnCreate', true);
        View::share('btnCreateText', trans('app.new_product'));
        View::share('createDisplayMode', 'ajax-modal');
        View::share('routes', $this->routes);
        View::share('iconCreate', 'puzzle-piece');
    }
	public function index()
	{
        $datatable = App::make($this->datatable);
        return $datatable->render('crud.index');
	}
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        if(!hasPermission('add_product', true)) return redirect('products');
        $form = $this->form($this->formClass, [
            'method' => 'POST',
            'url' => route($this->routes['store']),
            'class' => 'needs-validation row ajax-submit',
            'novalidate'
        ]);
        $heading = trans('app.new_product');
        return view('crud.modal',compact('heading','form'));
	}
    /**
     * Store a newly created resource in storage.
     * @param ProductFormRequest $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function store(ProductFormRequest $request)
	{
        $data = array(
                    'code'      => Request::get('code'),
                    'name'      => Request::get('name'),
                    'category_id'  => Request::get('category_id'),
                    'description'=> Request::get('description'),
                    'price'      => Request::get('price'),
        );
        if ($request->hasFile('product_image')){
            $file = $request->file('product_image');
            $filename = strtolower(Str::random(50) . '.' . $file->getClientOriginalExtension());
            $file->move(config('app.images_path').'uploads/product_images', $filename);
            $canvas = Image::canvas(245, 245);
            $image = Image::make(sprintf(config('app.images_path').'uploads/product_images/%s', $filename))->resize(245, 245,
                function($constraint) {
                    $constraint->aspectRatio();
                });
            $canvas->insert($image, 'center');
            $canvas->save(sprintf(config('app.images_path').'uploads/product_images/%s', $filename));
            $data['image']= $filename;
        }

		if($this->product->create($data)){
            Flash::success(trans('app.record_created'));
            return Response::json(array('success'=>true, 'msg' => trans('app.record_created')), 201);
        }
        return Response::json(array('success'=>false, 'msg' => trans('app.record_creation_failed')), 422);
	}
	/**
	 * Show the form for editing the specified resource.
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
        if(!hasPermission('edit_product', true)) return redirect('products');
        $product = $this->product->getById($id);
        $form = $this->form($this->formClass, [
            'method' => 'PATCH',
            'url' => route($this->routes['update'],$product->uuid),
            'class' => 'needs-validation row ajax-submit',
            'novalidate',
            'model'=> $product
        ]);
        $heading = trans('app.edit_product');
        return view('crud.modal', compact('heading','form'));
	}
    /**
     * Update the specified resource in storage.
     * @param ProductFormRequest $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update(ProductFormRequest $request, $id)
	{
        $product = $this->product->getById($id);
        $data = array(
            'code'      => Request::get('code'),
            'name'      => Request::get('name'),
            'category_id'  => Request::get('category_id'),
            'description'=> Request::get('description'),
            'price'      => Request::get('price'),
        );
        if ($request->hasFile('product_image')){
            $file = $request->file('product_image');
            $filename = strtolower(Str::random(50) . '.' . $file->getClientOriginalExtension());
            $file->move(config('app.images_path').'uploads/product_images', $filename);
            $canvas = Image::canvas(245, 245);
            $image = Image::make(sprintf(config('app.images_path').'uploads/product_images/%s', $filename))->resize(245, 245,
                function($constraint) {
                    $constraint->aspectRatio();
                });
            $canvas->insert($image, 'center');
            $canvas->save(sprintf(config('app.images_path').'uploads/product_images/%s', $filename));
            $data['image']= $filename;
        }
		if($this->product->updateById($id,$data)){
		    if(isset($data['image'])) {
                File::delete(config('app.images_path') . 'uploads/product_images/' . $product->image);
            }
            Flash::success(trans('app.record_updated'));
            return Response::json(array('success'=>true, 'msg' => trans('app.record_updated')), 201);
        }
        return Response::json(array('success'=>false, 'msg' =>  trans('app.record_update_failed')), 422);
	}
	/**
	 * Remove the specified resource from storage.
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        if(!hasPermission('delete_product', true)) return redirect('products');
		if($this->product->deleteById($id))
            Flash::success(trans('app.record_deleted'));
        else
            Flash::error(trans('app.record_deletion_failed'));
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
    /**
     * @return \Illuminate\View\View
     */
    public function products_modal(){
        $products = $this->product->all();
        return view('products.products_modal', compact('products'));
    }
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function process_products_selections(){
        $selected = request('product_lookup_id');
        $product = $this->product->getById($selected);
        $product->quantity = 1;
        return response()->json(['success'=>true, 'product' => $product], 200);
    }
}
