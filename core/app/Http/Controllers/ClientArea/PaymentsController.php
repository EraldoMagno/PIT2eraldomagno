<?php 

namespace App\Http\Controllers\ClientArea;

use App\Datatables\ClientArea\PaymentDatatable;
use App\Http\Forms\PaymentForm;
use Illuminate\Support\Facades\App;
use App\Http\Requests\PaymentFormRequest;
use App\Invoicer\Repositories\Contracts\PaymentInterface as Payment;
use App\Invoicer\Repositories\Contracts\PaymentMethodInterface as PaymentMethod;
use App\Invoicer\Repositories\Contracts\InvoiceInterface as Invoice;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use Kris\LaravelFormBuilder\FormBuilderTrait;
use Laracasts\Flash\Flash;

class PaymentsController extends Controller {
    use FormBuilderTrait;
    protected $datatable = PaymentDatatable::class;
    protected $formClass = PaymentForm::class;
    protected $payment, $invoice,$paymentmethod,$logged_user;
    public function __construct(Payment $payment, PaymentMethod $paymentmethod, Invoice $invoice){
        $this->payment = $payment;
        $this->paymentmethod = $paymentmethod;
        $this->invoice = $invoice;
        View::share('heading', trans('app.payments'));
        View::share('headingIcon', 'money');
        $this->middleware(function ($request, $next) {
            $this->logged_user = auth()->guard('user')->user()->uuid;
            return $next($request);
        });
    }
	/**
	 * Display a listing of the resource.
	 *
	 * @return View
	 */
	public function index()
	{
        $datatable = App::make($this->datatable);
        return $datatable->render('clientarea.crud.index');
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
        else
            $invoice = null;
        $methods = $this->paymentmethod->paymentMethodSelect();
		return view('payments.create', compact('methods','invoice'));
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
