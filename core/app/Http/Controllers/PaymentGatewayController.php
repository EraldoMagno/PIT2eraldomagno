<?php

namespace App\Http\Controllers;

use App\Http\Forms\PaymentGatewayForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Kris\LaravelFormBuilder\FormBuilderTrait;
use Laracasts\Flash\Flash;
use App\Invoicer\Repositories\Contracts\PaymentMethodInterface as Payment;

class PaymentGatewayController extends Controller
{
    use FormBuilderTrait;
    private $payment;
    protected $formClass = PaymentGatewayForm::class;
    protected $routes = [
        'index' => 'settings.payment.index',
        'create' => 'settings.payment.create',
        'store' => 'settings.payment.store',
        'update' => 'settings.payment.update'
    ];
    public function __construct(Payment $payment){
        $this->middleware('permission:edit_setting');
        $this->payment = $payment;
        View::share('heading', trans('app.payment_gateways'));
        View::share('headingIcon', 'money');
        View::share('routes', $this->routes);
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $paypal_details = config('services.paypal');
        $stripe_details = config('services.stripe');
        $paypal = $this->payment->where('name','Paypal')->first();
        $stripe = $this->payment->where('name','Stripe')->first();
        $form = $this->form(PaymentGatewayForm::class, [
            'method' => 'POST',
            'url' => route('settings.gateways.store'),
            'class' => 'needs-validation', 'novalidate',
            'model' => [
                'paypal_id' => $paypal->uuid ?? null,
                'stripe_id' => $stripe->uuid ?? null,
                'paypal_details' => $paypal_details,
                'stripe_details' => $stripe_details
            ]
        ]);
        return view('settings.index', compact('form'));
    }
    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        if(!isset($request->paypal_id)){
            $this->payment->create(array('name' => 'Paypal'));
        }
        if(!isset($request->stripe_id)){
            $this->payment->create(array('name' => 'Stripe'));
        }
        saveConfiguration([
            'PAYPAL_CLIENT_ID' => $request->client_id,
            'PAYPAL_CLIENT_SECRET' => $request->secret_key,
            'PAYPAL_STATUS' => $request->paypal_status,
            'PAYPAL_ACCOUNT' => $request->paypal_account,
            'PAYPAL_MODE' => $request->paypal_mode,
        ]);
        saveConfiguration([
            'STRIPE_SECRET' => $request->stripe_secret,
            'STRIPE_STATUS' => $request->stripe_status,
            'STRIPE_KEY' => $request->stripe_key,
        ]);
        Flash::success(trans('app.record_updated'));
        return redirect()->route('settings.gateways.index');
    }
}
