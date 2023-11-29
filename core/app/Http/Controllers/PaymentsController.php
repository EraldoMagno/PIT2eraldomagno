<?php namespace App\Http\Controllers;

use App\Datatables\PaymentDatatable;
use App\Http\Forms\PaymentForm;
use App\Http\Requests\PaymentFormRequest;
use App\Invoicer\Repositories\Contracts\PaymentInterface as Payment;
use App\Invoicer\Repositories\Contracts\PaymentMethodInterface as PaymentMethod;
use App\Invoicer\Repositories\Contracts\InvoiceInterface as Invoice;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use Kris\LaravelFormBuilder\FormBuilderTrait;
use Laracasts\Flash\Flash;

class PaymentsController extends Controller {
    use FormBuilderTrait;
    protected $payment, $invoice,$paymentmethod;
    protected $datatable = PaymentDatatable::class;
    protected $formClass = PaymentForm::class;
    protected $routes = [
        'index' => 'payments.index',
        'create' => 'payments.create',
        'show' => 'payments.show',
        'edit' => 'payments.edit',
        'store' => 'payments.store',
        'destroy' => 'payments.destroy',
        'update' => 'payments.update'
    ];
    public function __construct(Payment $payment, PaymentMethod $paymentmethod, Invoice $invoice){
        $this->payment = $payment;
        $this->paymentmethod = $paymentmethod;
        $this->invoice = $invoice;
        View::share('heading', trans('app.payments'));
        View::share('headingIcon', 'money');
        View::share('showBtnCreate', true);
        View::share('btnCreateText', trans('app.record_payment'));
        View::share('createDisplayMode', 'ajax-modal');
        View::share('routes', $this->routes);
        View::share('iconCreate', 'money');
    }
    /*
     * Index function
     */
	public function index()
	{
        $datatable = App::make($this->datatable);
        return $datatable->render('crud.index');
	}
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return View
	 */
	public function create()
	{
        if(!hasPermission('add_payment', true)) return redirect('payments');
        $invoice_id = request('invoice_id');
        if($invoice_id){
            $invoice = $this->invoice->getById($invoice_id);
            $invoice->totals = $this->invoice->invoiceTotals($invoice_id);
        }
        else{
            $invoice = null;
        }
        $form = $this->form($this->formClass, [
            'method' => 'POST',
            'url' => route($this->routes['store']),
            'id' => 'payment_form',
            'class' => 'needs-validation row ajax-submit',
            'novalidate',
            'model'=> [
                'invoice'=>$invoice
            ]
        ]);
        $heading = trans('app.add_payment');
        return view('crud.modal', compact('heading','form','invoice'));
	}
    /**
     * Store a newly created resource in storage.
     * @param PaymentFormRequest $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function store(PaymentFormRequest $request)
	{
		$payment = [
            'invoice_id' => $request->get('invoice_id'),
            'payment_date' => date('Y-m-d', strtotime($request->get('payment_date'))),
            'amount' => $request->get('amount'),
            'method' => $request->get('method'),
            'notes' => $request->get('notes')
        ];
        if($this->payment->create($payment)){
            $this->invoice->changeStatus($request->get('invoice_id'));
            Flash::success(trans('app.record_created'));
            return Response::json(array('success' => true, 'msg' => trans('app.record_created')), 200);
        }
        return Response::json(array('success' => false, 'msg' => trans('app.record_creation_failed')), 400);
	}
	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return View
	 */
	public function edit($id)
	{
        if(!hasPermission('edit_payment', true)) return redirect('payments');
        $payment = $this->payment->getById($id);
        $form = $this->form($this->formClass, [
            'method' => 'PATCH',
            'url' => route($this->routes['update'],$payment->uuid),
            'id' => 'payment_form',
            'class' => 'needs-validation row ajax-submit',
            'novalidate',
            'model'=> $payment
        ]);
        $heading = trans('app.edit_payment');
        return view('crud.modal', compact('heading','form'));
	}
	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return
	 */
	public function update(PaymentFormRequest $request, $id)
	{
        $payment = [
            'payment_date' => date('Y-m-d', strtotime($request->get('payment_date'))),
            'amount' => $request->get('amount'),
            'method' => $request->get('method'),
            'notes' => $request->get('notes')
        ];
        if($request->get('invoice_id') != ''){
            $payment['invoice_id'] = $request->get('invoice_id');
        }
        if($this->payment->updateById($id, $payment)){
            $payment = $this->payment->getById($id);
            $this->invoice->changeStatus($payment->invoice_id);
            Flash::success(trans('app.record_updated'));
            return Response::json(array('success' => true, 'msg' => trans('app.record_updated')), 200);
        }
        return Response::json(array('success' => false, 'msg' => trans('app.record_update_failed')), 400);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return
	 */
	public function destroy($id)
	{
        if(!hasPermission('delete_payment', true)) return redirect('payments');
        $payment = $this->payment->getById($id);
        if($this->payment->deleteById($id)){
            Flash::success(trans('app.record_deleted'));
            $this->invoice->changeStatus($payment->invoice_id);
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
    public function show($uuid){
        $payment = $this->payment->getById($uuid);
        $form = $this->form($this->formClass, [
            'method' => 'POST',
            'id' => 'payment_form',
            'class' => 'needs-validation row ajax-submit',
            'novalidate',
            'model'=> $payment
        ]);
        $form->disableFields();
        $form->remove('buttons');
        $heading = trans('app.view_payment');
        return view('crud.modal', compact('heading','form','payment'));
    }

}
