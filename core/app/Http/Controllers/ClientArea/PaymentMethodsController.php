<?php
namespace App\Http\Controllers\ClientArea;
use App\Invoicer\Repositories\Contracts\InvoiceInterface as Invoice;
class PaymentMethodsController extends Controller{
    protected $invoice;
    public function __construct(Invoice $invoice){
       $this->invoice   = $invoice;
    }
    public function index($invoice_id){
        $paypal_details = config('services.paypal');
        $stripe_details = config('services.stripe');
        $invoice = $this->invoice->getById($invoice_id);
        return view('clientarea.payment_methods.index', compact('paypal_details','stripe_details','invoice'));
    }
}
