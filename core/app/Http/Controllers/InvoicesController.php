<?php namespace App\Http\Controllers;

use App\Datatables\InvoiceDatatable;
use App\Http\Forms\InvoiceForm;
use App\Http\Requests\InvoiceFromRequest;
use App\Http\Requests\SendEmailFrmRequest;
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
use App\Invoicer\Repositories\Contracts\SubscriptionInterface as Subscription;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use Kris\LaravelFormBuilder\FormBuilderTrait;
use Laracasts\Flash\Flash;
use PDF;
use Mail;
class InvoicesController extends Controller {
   use FormBuilderTrait;
   protected $formClass = InvoiceForm::class;
   protected $product,$client,$tax,$currency,$invoice,$items,$setting,$number,$invoiceSetting, $template, $mail_setting,$subscription;
   protected $datatable = InvoiceDatatable::class;
   protected $routes = [
        'index' => 'invoices.index',
        'create' => 'invoices.create',
        'show' => 'invoices.show',
        'edit' => 'invoices.edit',
        'store' => 'invoices.store',
        'destroy' => 'invoices.destroy',
        'update' => 'invoices.update'
    ];
   public function __construct(Invoice $invoice, Product $product, Client $client,  Tax $tax, Currency $currency, InvoiceItem $items, Setting $setting, Number $number, InvoiceSetting $invoiceSetting, Template $template, MailSetting $mail_setting, Subscription $subscription){
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
        $this->subscription = $subscription;
        View::share('heading', trans('app.invoices'));
        View::share('headingIcon', 'file-pdf-o');
        View::share('showBtnCreate', true);
        View::share('btnCreateText', trans('app.new_invoice'));
        View::share('createDisplayMode', 'normal');
        View::share('routes', $this->routes);
        View::share('iconCreate', 'plus');
   }

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
        if(!hasPermission('add_invoice', true)) return redirect('invoices');
        $settings     = $this->invoiceSetting->first();
        $start        = $settings ? $settings->start_number : 0;
        $invoice_form = $this->form($this->formClass, [
            'method' => 'POST',
            'url' => route($this->routes['store']),
            'id' => 'invoice_form',
            'class' => 'needs-validation row', 'novalidate',
            'model'=>[
                'invoice_date'=>date('Y-m-d'),
                'due_date'=>$settings ? date('Y-m-d',strtotime("+".$settings->due_days." days")) : date('Y-m-d'),
                'invoice_no'=>$this->number->prefix('invoice_number', $this->invoice->generateInvoiceNum($start)),
                'terms' => $settings ? $settings->terms : ''
            ]
        ]);
        return view('invoices.create',compact('invoice_form'));
	}

    /**
     * Store a newly created resource in storage.
     * @param InvoiceFromRequest $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function store(InvoiceFromRequest $request)
	{
	    $due_date = $request->get('due_date');
        $invoiceData = array(
            'client_id'     => $request->get('client_id'),
            'invoice_no'    => $request->get('invoice_no'),
            'invoice_date'  => date('Y-m-d', strtotime($request->get('invoice_date'))),
            'notes'         => $request->get('notes'),
            'terms'         => $request->get('terms'),
            'currency'      => $request->get('currency'),
            'status'        => $request->get('status'),
            'discount'      => $request->get('discount') != '' ? $request->get('discount') : 0,
            'discount_mode' => $request->get('discount_mode'),
            'recurring'     => $request->get('recurring'),
            'recurring_cycle' => $request->get('recurring_cycle')
        );
        if($due_date != ''){
            $invoiceData['due_date'] = date('Y-m-d', strtotime($request->get('due_date')));
        }
        $invoice = $this->invoice->create($invoiceData);
        if($invoice){
            $this->saveItems($invoice, $request->items);
            $settings     = $this->invoiceSetting->first();
            if($settings){
                $start = $settings->start_number+1;
                $this->invoiceSetting->updateById($settings->uuid, array('start_number'=>$start));
            }
            if($request->get('recurring') == 1){
                $cycle = $request->get('recurring_cycle');
                $invoice_date = strtotime($invoice->invoice_date);
                switch ($cycle) {
                    case 1:
                        $next_due_date = date("Y-m-d", strtotime("+1 month", $invoice_date));
                        break;
                    case 2:
                        $next_due_date = date("Y-m-d", strtotime("+3 month", $invoice_date));
                        break;
                    case 3:
                        $next_due_date = date("Y-m-d", strtotime("+6 month", $invoice_date));
                        break;
                    case 4:
                        $next_due_date = date("Y-m-d", strtotime("+12 month", $invoice_date));
                        break;
                    default:
                        $next_due_date = date("Y-m-d", strtotime("+12 month", $invoice_date));
                }
                $subscriptionData = array(
                    'invoice_id' => $invoice->uuid,
                    'billingcycle' => $cycle,
                    'nextduedate' => $next_due_date,
                    'status' => '1'
                );
                $this->subscription->create($subscriptionData);
            }
            return Response::json(array('success' => true,'redirectTo'=>route('invoices.show', $invoice->uuid), 'msg' =>  trans('app.record_created')), 200);
        }
        return Response::json(array('success' => false, 'msg' => trans('app.record_creation_failed')), 400);
	}
    /**
     * Display the specified resource.
     * @param $uuid
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
	public function show($uuid)
	{
        if(!hasPermission('view_invoice', true)) return redirect('invoices');
        $invoice = $this->invoice->getById($uuid);
        if ($invoice) {
            $settings = $this->setting->first();
            $invoiceSettings = $this->invoiceSetting->first();
            return view('invoices.show', compact('invoice', 'settings', 'invoiceSettings'));
        }
        return Redirect::route('invoices.index');
	}
    /**
     * Show the form for editing the specified resource.
     * @param $uuid
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
	public function edit($uuid)
	{
        if(!hasPermission('edit_invoice', true)) return redirect('invoices');
        $invoice = $this->invoice->getById($uuid);
        if ($invoice) {
            $invoice_form = $this->form($this->formClass, [
                'method' => 'PATCH',
                'url' => route($this->routes['update'],$invoice),
                'id' => 'invoice_form',
                'class' => 'needs-validation row', 'novalidate',
                'model'=> $invoice
            ]);
            return view('invoices.edit',compact('invoice_form'));
        }
        return Redirect::route('invoices.index');
	}
    /**
     * Update the specified resource in storage.
     * @param InvoiceFromRequest $request
     * @param $uuid
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update(InvoiceFromRequest $request, $uuid)
	{
        $due_date = $request->get('due_date');
        $invoiceData = array(
            'client_id'     => $request->get('client_id'),
            'invoice_no'    => $request->get('invoice_no'),
            'invoice_date'  => date('Y-m-d', strtotime($request->get('invoice_date'))),
            'notes'         => $request->get('notes'),
            'terms'         => $request->get('terms'),
            'currency'      => $request->get('currency'),
            'status'        => $request->get('status'),
            'discount'      => $request->get('discount') != '' ? $request->get('discount') : 0,
            'discount_mode' => $request->get('discount_mode'),
            'recurring'     => $request->get('recurring'),
            'recurring_cycle' => $request->get('recurring_cycle')
        );
        if($due_date != ''){
            $invoiceData['due_date'] = date('Y-m-d', strtotime($request->get('due_date')));
        }
        $invoice = $this->invoice->updateById($uuid, $invoiceData);
        if($invoice){
            $this->saveItems($invoice, $request->items);
            $this->invoice->changeStatus($uuid);
            $cycle = $request->get('recurring_cycle');
            $model = $this->subscription->model();
            $subscription = $model::where('invoice_id',$invoice->uuid)->first();
            if($subscription){
                if($request->get('recurring') == 1) {
                    $today = date('Y-m-d');
                    if(strtotime($subscription->nextduedate) <= strtotime($today)) {
                        switch ($cycle) {
                            case 1:
                                $next_due_date = date("Y-m-d", strtotime("+1 month", strtotime($today)));
                                break;
                            case 2:
                                $next_due_date = date("Y-m-d", strtotime("+3 month", strtotime($today)));
                                break;
                            case 3:
                                $next_due_date = date("Y-m-d", strtotime("+6 month", strtotime($today)));
                                break;
                            case 4:
                                $next_due_date = date("Y-m-d", strtotime("+12 month", strtotime($today)));
                                break;
                            default:
                                $next_due_date = date("Y-m-d", strtotime("+12 month", strtotime($today)));
                        }
                    }
                    else{
                        $next_due_date = $subscription->nextduedate;
                    }
                    $subscriptionData = array(
                        'invoice_id' => $invoice->uuid,
                        'billingcycle' => $cycle,
                        'nextduedate' => $next_due_date,
                        'status' => '1'
                    );
                    $this->subscription->updateById($subscription->uuid,$subscriptionData);
                }else{
                    $subscriptionData = array(
                        'status' => '0'
                    );
                    $this->subscription->updateById($subscription->uuid,$subscriptionData);
                }
            }else {
                if ($request->get('recurring') == 1) {
                    $invoice_date = strtotime($invoice->invoice_date);
                    switch ($cycle) {
                        case 1:
                            $next_due_date = date("Y-m-d", strtotime("+1 month", $invoice_date));
                            break;
                        case 2:
                            $next_due_date = date("Y-m-d", strtotime("+3 month", $invoice_date));
                            break;
                        case 3:
                            $next_due_date = date("Y-m-d", strtotime("+6 month", $invoice_date));
                            break;
                        case 4:
                            $next_due_date = date("Y-m-d", strtotime("+12 month", $invoice_date));
                            break;
                        default:
                            $next_due_date = date("Y-m-d", strtotime("+12 month", $invoice_date));
                    }
                    $subscriptionData = array(
                        'invoice_id' => $invoice->uuid,
                        'billingcycle' => $cycle,
                        'nextduedate' => $next_due_date,
                        'status' => '1'
                    );
                    $this->subscription->create($subscriptionData);
                }
            }
            return Response::json(array('success' => true,'redirectTo'=>route('invoices.show', $invoice->uuid), 'msg' => trans('app.record_updated')), 200);
        }
        return Response::json(array('success' => false, 'msg' => trans('app.record_update_failed')), 400);
	}
    /**
     * @return mixed
     */
    public function ajaxSearch(){
        return $this->invoice->ajaxSearch();
    }
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteItem(){
        $id = request('id');
        if($this->items->deleteById($id)) {
            return Response::json(array('success' => true, 'msg' => trans('app.record_deleted')), 201);
        }
        return Response::json(array('success' => false, 'msg' => trans('app.record_deletion_failed')), 400);
    }
    /**
     * @param $uuid
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function invoicePdf($uuid){
        $invoice = $this->invoice->getById($uuid);
        if($invoice){
            $settings = $this->setting->first();
            $invoiceSettings = $this->invoiceSetting->first();
            $invoice->pdf_logo = $invoiceSettings && $invoiceSettings->logo ? base64_img(config('app.images_path').$invoiceSettings->logo) : '';
            $pdf = PDF::loadView('invoices.pdf', compact('settings', 'invoice', 'invoiceSettings'));
            return $pdf->download(trans('app.invoice').'_'.$invoice->number.'_'.date('Y-m-d').'.pdf');
        }
        return Redirect::route('invoices');
    }
    public function send_modal($uuid){
        $invoice = $this->invoice->getById($uuid);
        $template = $this->template->where('name', 'invoice')->first();
        return view('invoices.send_modal',compact('invoice','template'));
    }
    public function send(SendEmailFrmRequest $request){
        $uuid = $request->get('invoice_id');
        $invoice = $this->invoice->getById($uuid);
        $settings = $this->setting->first();
        $invoiceSettings = $this->invoiceSetting->first();
        $data_object = new \stdClass();
        $data_object->invoice = $invoice;
        $data_object->settings = $settings;
        $data_object->client = $invoice->client;
        $data_object->user = $invoice->client;
        $invoice->pdf_logo = $invoiceSettings && $invoiceSettings->logo ? base64_img(config('app.images_path').$invoiceSettings->logo) : '';
        $pdf_name = trans('app.invoice').'_'. $invoice->number . '_' . date('Y-m-d') . '.pdf';
        PDF::loadView('invoices.pdf', compact('settings', 'invoice', 'invoiceSettings'))->save(config('app.assets_path').'attachments/'.$pdf_name);
        $params = [
            'data' => [
                'emailBody'=>parse_template($data_object, $request->get('message')),
                'emailTitle'=>parse_template($data_object,$request->get('subject')),
                'attachment' => config('app.assets_path').'attachments/'.$pdf_name
            ],
            'to' => $request->get('email'),
            'template_type' => 'markdown',
            'template' => 'emails.invoicer-mailer',
            'subject' => parse_template($data_object,$request->get('subject'))
        ];
        try {
            sendmail($params);
            Flash::success(trans('app.email_sent'));
            return response()->json(['type' => 'success', 'message' => trans('app.email_sent')]);
        }catch (\Exception $exception){
            $error = $exception->getMessage();
            Flash::error($error);
            return response()->json(['type' => 'fail','message' => $error],422);
        }
    }
    /**
     * Remove the specified resource from storage.
     * @param $uuid
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	public function destroy($uuid)
	{
        if(!hasPermission('send_invoice', true)) return redirect('invoices');
        if ($this->invoice->deleteById($uuid)) {
            Flash::success(trans('app.record_deleted'));
            return redirect('invoices');
        }
        Flash::error(trans('app.record_deletion_failed'));
        return redirect('invoices');
	}
    public function saveItems($entity, $rows){
        $ids = [];
        foreach ($rows as $row) {
            if($row['item_id'] > 0){
                $product = $this->product->getById($row['item_id']);
                $row['item_description'] = $product->description;
            }
            $row['invoice_id'] = $entity->uuid;
            $row['tax_id'] = $row['tax_id'] != '' ? $row['tax_id'] : null;
            if ($row['uuid'] > 0) {
                $record = $entity->items()->find($row['uuid']);
                $record->fill($row);
                $record->save();
            } else {
                $record = $this->items->create($row);
            }
            $ids[] = $record->uuid;
        }
        foreach ($entity->items as $row) {
            if (!in_array($row->uuid, $ids)) {
                $row->delete();
            }
        }
    }
    public function set_subscription(){

    }
}
