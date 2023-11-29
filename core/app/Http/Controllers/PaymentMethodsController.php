<?php namespace App\Http\Controllers;

use App\Datatables\PaymentMethodDatatable;
use App\Http\Forms\PaymentMethodForm;
use App\Http\Requests\PaymentMethodFromRequest;
use App\Invoicer\Repositories\Contracts\PaymentMethodInterface as Payment;
use Illuminate\Support\Facades\Response;
use Laracasts\Flash\Flash;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\View;
use Kris\LaravelFormBuilder\FormBuilderTrait;

class PaymentMethodsController extends Controller{
    use FormBuilderTrait;
    private $payment;
    protected $formClass = PaymentMethodForm::class;
    protected $datatable = PaymentMethodDatatable::class;
    protected $routes = [
        'index' => 'settings.payment.index',
        'create' => 'settings.payment.create',
        'store' => 'settings.payment.store',
        'update' => 'settings.payment.update'
    ];
    public function __construct(Payment $payment){
        $this->payment = $payment;
        $this->middleware('permission:edit_setting');
        View::share('heading', trans('app.payment_methods'));
        View::share('headingIcon', 'money');
        View::share('btnCreateText', trans('app.new_payment_method'));
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
        $heading = trans('app.new_payment_method');
        return view('crud.modal',compact('heading','form'));
    }
    /**
     * Store a newly created resource in storage.
     * @param PaymentMethodFromRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(PaymentMethodFromRequest $request)
	{
		$data = array('name' => $request->name);
        if($this->payment->create($data))
            Flash::success(trans('app.record_created'));
        else
            Flash::error(trans('app.create_failed'));

        return redirect('settings/payment');
	}
	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$payment = $this->payment->getById($id);
        $form = $this->form($this->formClass, [
            'method' => 'PATCH',
            'url' => route($this->routes['update'],$id),
            'class' => 'needs-validation row ajax-submit',
            'novalidate',
            'model'=> $payment
        ]);
        $heading = trans('app.edit_payment_method');
        return view('crud.modal',compact('heading','form'));
	}
    /**
     * Update the specified resource in storage.
     * @param PaymentMethodFromRequest $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update(PaymentMethodFromRequest $request, $id)
	{
		$data = ['name' => $request->name, 'selected' => $request->selected];
        if($request->selected)
            $this->payment->resetDefault();

        if($this->payment->updateById($id, $data)){
            Flash::success(trans('app.record_updated'));
            return Response::json(['success' => true, 'msg' => 'payment method updated'], 200);
        }
        return Response::json(['success' => false, 'msg' => 'update failed'], 422);
	}
	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		if($this->payment->deleteById($id))
            Flash::success(trans('app.record_deleted'));
        else
            Flash::error(trans('app.delete_failed'));
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
}
