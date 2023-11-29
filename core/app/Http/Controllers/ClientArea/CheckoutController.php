<?php
namespace App\Http\Controllers\ClientArea;
use App\Http\Requests\ClientAreaCheckoutRequest;
use App\Invoicer\Repositories\Contracts\InvoiceInterface as Invoice;
use App\Invoicer\Repositories\Contracts\InvoiceSettingInterface as InvoiceSetting;
use App\Invoicer\Repositories\Contracts\SettingInterface as Setting;
use App\Invoicer\Repositories\Contracts\PaymentInterface as Payment;
use App\Invoicer\Repositories\Contracts\PaymentMethodInterface as PaymentMethod;
use Flash;
use Redirect;
use Illuminate\Http\Request;
use Omnipay\Omnipay;
class CheckoutController extends Controller{
    protected $invoice,$invoiceSetting,$setting,$payment,$paymentMethod,$gateway;
    public function __construct(Invoice $invoice, Setting $setting, InvoiceSetting $invoiceSetting,Payment $payment,PaymentMethod $paymentMethod){
        $this->invoiceSetting = $invoiceSetting;
        $this->payment = $payment;
        $this->paymentMethod = $paymentMethod;
        $this->invoice = $invoice;
        $this->setting   = $setting;
        $this->gateway = Omnipay::create('PayPal_Rest');
        $this->gateway->setClientId(env('PAYPAL_CLIENT_ID'));
        $this->gateway->setSecret(env('PAYPAL_CLIENT_SECRET'));
        $this->gateway->setTestMode(env('PAYPAL_MODE') == 'sandbox' ? true : false); //set it to 'false' when go live
    }
    public function getCheckout(ClientAreaCheckoutRequest $request){
        if (auth()->guard('user')->user()){
            $invoice_id = $request->invoice_id;
            $invoice = $this->invoice->getById($invoice_id);
            $invoice_totals = $this->invoice->invoiceTotals($invoice_id);
            $selected_method = $request->selected_method;
            if($selected_method == 'paypal' && config('services.paypal.status') == 1){
                try {
                        $response = $this->gateway->purchase(array(
                            'amount' => $invoice_totals['amountDue'],
                            'currency' => defaultCurrencyCode(),
                            'returnUrl' => route('getDone'),
                            'cancelUrl' => route('getCancel',$invoice_id),
                            'transactionId' =>$invoice->uuid,
                            'description'=> trans('app.invoice').' '.trans('app.payment')
                        ))->send();
                        if ($response->isRedirect()) {
                            return redirect($response->getRedirectUrl());
                        } 
                    } catch(Exception $e) {
                        Flash::error($e->getMessage());
                        return redirect()->route('cinvoices.show', $invoice_id);
                    }
            }else{
                return redirect()->route('stripecheckout',$invoice_id);
            }
        }
    }
 
    public function stripeCheckout($invoice_id){
        $invoice = $this->invoice->getById($invoice_id);
        $invoiceSettings = $this->invoiceSetting->first();
        $invoice->totals = $this->invoice->invoiceTotals($invoice_id);
        $stripe_key = config('services.stripe.key');
        $settings = $this->setting->first();
        return view('clientarea.payment_methods.stripe', compact('invoice','invoiceSettings','settings','stripe_key'));
    }
    public function stripeSuccess(Request $request){
        $payment_method_model = $this->paymentMethod->model();
        $payment_method = $payment_method_model::where('name','Stripe')->first();
        if(!$payment_method){
            $payment_method = $payment_method_model::create(['name'=>'Stripe']);
        }
        $payment_data = [
            'invoice_id' => $request->get('invoice_id'),
            'payment_date' => date('Y-m-d'),
            'amount' => $request->get('amount'),
            'method' => $payment_method->uuid,
            'notes' => 'Transaction Id : '.$request->get('stripeToken')
        ];
        if($this->payment->create($payment_data)) {
            $this->invoice->changeStatus($request->get('invoice_id'));
        }
        Flash::success(trans('app.payment_successful'));
        return redirect()->route('cinvoices.show', $request->invoice_id);
    }
    public function paypalNotify(Request $request){
        $txn_id = $request->txn_id;
        $invoice_id = $request->item_number;
        $payment_method_model = $this->paymentMethod->model();
        $payment_method = $payment_method_model::where('name','Paypal')->first();
        if(!$payment_method){
            $payment_method = $payment_method_model::create(['name'=>'Paypal']);
        }
        $payment_data = [
            'invoice_id' => $invoice_id,
            'payment_date' => date('Y-m-d'),
            'amount' => $request->payment_gross,
            'method' => $payment_method->uuid,
            'notes' => 'Transaction id : '.$txn_id
        ];
        if($this->payment->create($payment_data)) {
            $this->invoice->changeStatus($invoice_id);
        }
    }
    public function getDone(Request $request){
        if ($request->input('paymentId') && $request->input('PayerID')){
            $transaction = $this->gateway->completePurchase(array(
                'payer_id'             => $request->input('PayerID'),
                'transactionReference' => $request->input('paymentId'),
            ));
            $response = $transaction->send();
            if ($response->isSuccessful()){
                // The customer has successfully paid.
                $arr_body = $response->getData();
                $invoice_id = $arr_body['transactions'][0]['invoice_number'];
                $amount = $arr_body['transactions'][0]['amount']['total'];
                $payment_model = $this->payment->model();
                $notes = 'Transaction id : '.$arr_body['id'];
                $payment_record = $payment_model::where('notes',$notes)->where('invoice_id',$invoice_id)->first();
                if(!$payment_record){
                    $payment_method_model = $this->paymentMethod->model();
                    $payment_method = $payment_method_model::where('name','Paypal')->first();
                    if(!$payment_method){
                        $payment_method = $payment_method_model::create(['name'=>'Paypal']);
                    }
                    $payment_data = [
                        'invoice_id' => $invoice_id,
                        'payment_date' => date('Y-m-d'),
                        'amount' => $amount,
                        'method' => $payment_method->uuid,
                        'notes' => $notes
                    ];
                    if($this->payment->create($payment_data)) {
                        $this->invoice->changeStatus($invoice_id);
                    }
                }
                Flash::success(trans('app.payment_successful'));
                return redirect()->route('cinvoices.show', $invoice_id);
            } else {
                Flash::error(trans('app.payment_failed'));
            }
        } else {
            Flash::error(trans('app.payment_failed'));
        }
        return redirect()->route('cinvoices.index');
    }
    public function getCancel($invoice_id){
        Flash::error(trans('app.payment_cancelled'));
        return redirect()->route('cinvoices.show', $invoice_id);
    }
}
