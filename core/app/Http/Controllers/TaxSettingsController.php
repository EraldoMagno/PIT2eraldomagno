<?php namespace App\Http\Controllers;

use App\Datatables\TaxDatatable;
use App\Http\Forms\TaxForm;
use App\Http\Requests\TaxSettingFormRequest;
use App\Invoicer\Repositories\Contracts\TaxSettingInterface as Tax;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Response;
use Kris\LaravelFormBuilder\FormBuilderTrait;
use Laracasts\Flash\Flash;
use Illuminate\Support\Facades\View;


class TaxSettingsController extends Controller {
	use FormBuilderTrait;
    protected $formClass = TaxForm::class;
    protected $datatable = TaxDatatable::class;
    private $tax;
    protected $routes = [
        'index' => 'settings.tax.index',
        'create' => 'settings.tax.create',
        'store' => 'settings.tax.store',
        'update' => 'settings.tax.update'
    ];
    public function __construct(Tax $tax){
        $this->tax = $tax;
        $this->middleware('permission:edit_setting');
        View::share('heading', trans('app.tax_settings'));
        View::share('headingIcon', 'th-large');
        View::share('btnCreateText', trans('app.new_tax'));
        View::share('createDisplayMode', 'ajax-modal');
        View::share('routes', $this->routes);
        View::share('iconCreate', 'plus');
        View::share('showBtnCreate', true);
    }
	/**
	 * Display a listing of the resource.
	 *
	 * @return View
	 */
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
        $heading = trans('app.add_tax');
        return view('crud.modal',compact('heading','form'));
    }
    /**
     * Store a newly created resource in storage.
     * @param TaxSettingFormRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(TaxSettingFormRequest $request)
	{
        $data = array('name' => $request->name, 'value' => $request->value);
		if($this->tax->create($data)){
            Flash::success(trans('app.record_updated'));
        }else{
            Flash::error(trans('app.update_failed'));
        }
        return redirect('settings/tax');
	}
	/**
	 * Show the form for editing the specified resource.
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
        $tax = $this->tax->getById($id);
        $form = $this->form($this->formClass, [
            'method' => 'PATCH',
            'url' => route($this->routes['update'],$id),
            'class' => 'needs-validation row ajax-submit',
            'novalidate',
            'model'=> $tax
        ]);
        $heading = trans('app.edit_tax');
        return view('crud.modal',compact('heading','form'));
	}
    /**
     * Update the specified resource in storage.
     * @param TaxSettingFormRequest $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update(TaxSettingFormRequest $request, $id)
	{
		$data   =  ['name'=>$request->name, 'value'=>$request->value, 'selected' => $request->selected];
        if($request->selected) {
            $this->tax->resetDefault();
        }
        if($this->tax->updateById($id, $data)){
            Flash::success(trans('app.record_updated'));
            return Response::json(['success' => true, 'msg' => 'tax updated'], 201);
        }
        return Response::json(['success' => false, 'msg' => 'update failed', 'errors' => $this->errorBag()], 422);
	}
	/**
	 * Remove the specified resource from storage.
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        $entity = $this->tax->getById($id);
        if (empty($entity)) {
            if(request()->ajax()){
                return response()->json([
                    'type' => 'error',
                    'message' => trans('app.delete_failed'),
                    'action' => 'none'
                ]);
            }else{
                Flash::error(trans('app.delete_failed'))->error();
                return redirect(route($this->routes['index']));
            }
        }
        $this->tax->deleteById($id);
        if (request()->ajax()) {
            return response()->json([
                'type' => 'success',
                'message' => trans('app.record_deleted'),
                'action' => 'refresh_datatable'
            ]);
        } else {
            Flash::success(trans('app.record_deleted'));
            return redirect(route($this->routes['index']));
        } 
	}

}
