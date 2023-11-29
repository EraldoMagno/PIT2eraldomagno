<?php namespace App\Http\Controllers;

use App\Datatables\CurrencyDatatable;
use App\Http\Forms\CurrencyForm;
use App\Http\Requests\CurrencyFormRequest;
use App\Invoicer\Repositories\Contracts\CurrencyInterface as Currency;
use Illuminate\Support\Facades\App;
use Laracasts\Flash\Flash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\View;
use Kris\LaravelFormBuilder\FormBuilderTrait;

class CurrencyController extends Controller {
    use FormBuilderTrait;
    private $currency;
    protected $formClass = CurrencyForm::class;
    protected $datatable = CurrencyDatatable::class;
	protected $routes = [
        'index' => 'settings.currency.index',
        'create' => 'settings.currency.create',
        'store' => 'settings.currency.store',
        'update' => 'settings.currency.update'
    ];
    public function __construct(Currency $currency){
        $this->middleware('permission:edit_setting');
        $this->currency = $currency;
        View::share('heading', trans('app.currencies'));
        View::share('headingIcon', 'money');
        View::share('btnCreateText', trans('app.new_currency'));
        View::share('createDisplayMode', 'ajax-modal');
        View::share('routes', $this->routes);
        View::share('iconCreate', 'plus');
        View::share('showBtnCreate', true);
    }
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
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
        $heading = trans('app.new_currency');
        return view('crud.modal',compact('heading','form'));
    }
    /**
     * Store a newly created resource in storage.
     * @param CurrencyFormRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(CurrencyFormRequest $request)
	{
		$data = array('name' => $request->name,'exchange_rate'=>$request->exchange_rate, 'symbol' => $request->symbol, 'exchange_rate'=>$request->exchange_rate);
        if($this->currency->create($data))
            Flash::success(trans('app.record_created'));
        else
            Flash::error(trans('app.create_failed'));
        return redirect('settings/currency');
	}
	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$currency = $this->currency->getById($id);
        $form = $this->form($this->formClass, [
            'method' => 'PATCH',
            'url' => route($this->routes['update'],$id),
            'class' => 'needs-validation row ajax-submit',
            'novalidate',
            'model'=> $currency
        ]);
        $heading = trans('app.edit_currency');
        return view('crud.modal',compact('heading','form'));
	}
    /**
     * Update the specified resource in storage.
     * @param CurrencyFormRequest $request
     * @param $id
     * @return Response
     */
    public function update(CurrencyFormRequest $request, $id)
	{
        $data = array('active' => $request->active,'exchange_rate'=>$request->exchange_rate, 'default_currency' => $request->default_currency);
        if($request->default_currency){
            $this->currency->resetDefault();
            $data['active'] = 1;
        }
        if($this->currency->updateById($id, $data)){
            Flash::success(trans('app.record_updated'));
            return Response::json(array('success' => true, 'msg' => trans('app.record_updated')), 201);
        }
        return Response::json(array('success' => false, 'msg' => trans('app.update_failed')), 422);
	}
	public function updateCurrencyRates(){
        Artisan::call('currency:update -o');
        echo nl2br(e(Artisan::output()));
    }
	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$this->currency->deleteById($id);
        if (request()->ajax()) {
            return response()->json([
                'type' => 'success',
                'message' => trans('app.record_deleted'),
                'action' => 'refresh_datatable'
            ]);
        } else {
            return redirect(route($this->routes['index']));
        }   
	}
	public function save_api_key(){
	    $key = request('key');
	    if($key){
	        saveConfiguration(['OPENEXCHANGE_RATES_KEY'=>$key]);
	        return Response::json(['success'=>true,'message'=>trans('app.record_created')],200);
        }
    }
}
