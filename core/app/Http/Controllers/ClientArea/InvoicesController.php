<?php namespace App\Http\Controllers\ClientArea;

use Illuminate\Support\Facades\App;
use App\Datatables\ClientArea\InvoiceDatatable;
use App\Invoicer\Repositories\Contracts\InvoiceInterface as Invoice;
use App\Invoicer\Repositories\Contracts\ProductInterface as Product;
use App\Invoicer\Repositories\Contracts\ClientInterface as Client;
use App\Invoicer\Repositories\Contracts\TaxSettingInterface as Tax;
use App\Invoicer\Repositories\Contracts\CurrencyInterface as Currency;
use App\Invoicer\Repositories\Contracts\InvoiceItemInterface as InvoiceItem;
use App\Invoicer\Repositories\Contracts\SettingInterface as Setting;
use App\Invoicer\Repositories\Contracts\NumberSettingInterface as Number;
use App\Invoicer\Repositories\Contracts\InvoiceSettingInterface as InvoiceSetting;
use App\Invoicer\Repositories\Contracts\TemplateInterface as Template;
use App\Invoicer\Repositories\Contracts\EmailSettingInterface as MailSetting;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;

class InvoicesController extends Controller {
   protected $datatable = InvoiceDatatable::class;
   protected $product,$client,$tax,$currency,$invoice,$items,$setting,$number,$invoiceSetting, $template, $mail_setting,$logged_user;
   public function __construct(Invoice $invoice, Product $product, Client $client,  Tax $tax, Currency $currency, InvoiceItem $items, Setting $setting, Number $number, InvoiceSetting $invoiceSetting, Template $template, MailSetting $mail_setting){
       $this->invoice   = $invoice;
       $this->product   = $product;
       $this->client    = $client;
       $this->tax       = $tax;
       $this->currency  = $currency;
       $this->items     = $items;
       $this->setting   = $setting;
       $this->number    = $number;
       $this->invoiceSetting = $invoiceSetting;
       $this->template  = $template;
       $this->mail_setting = $mail_setting;
       View::share('heading', trans('app.invoices'));
       View::share('headingIcon', 'file-pdf-o');
       $this->middleware(function ($request, $next) {
           $this->logged_user = auth()->guard('user')->user();
           return $next($request);
       });
   }
	public function index(){
        $datatable = App::make($this->datatable);
        return $datatable->render('clientarea.crud.index');
	}
    /**
     * Display the specified resource.
     * @param $uuid
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
	public function show($uuid)
	{
        $invoice = $this->invoice->getById($uuid);
        if ($invoice) {
            $settings = $this->setting->first();
            $invoiceSettings = $this->invoiceSetting->first();
            return view('clientarea.invoices.show', compact('invoice', 'settings', 'invoiceSettings'));
        }
	}
    /**
     * @param $uuid
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function invoicePdf($uuid){
        $invoice = $this->invoice->with('items')->getById($uuid);
        if($invoice){
            $settings = $this->setting->first();
            $invoiceSettings = $this->invoiceSetting->first();
            $invoice->pdf_logo = $invoiceSettings && $invoiceSettings->logo ? base64_img(config('app.images_path').$invoiceSettings->logo) : '';
            $pdf = \PDF::loadView('clientarea.invoices.pdf', compact('settings', 'invoice', 'invoiceSettings'));
            return $pdf->download(trans('app.invoice').'_'.$invoice->number.'_'.date('Y-m-d').'.pdf');
        }
        return Redirect::route('cinvoices');
    }
}
