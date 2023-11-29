<?php namespace App\Http\Controllers;

use App\Datatables\EstimateDatatable;
use App\Http\Forms\EstimateForm;
use App\Http\Requests\EstimateFormRequest;
use App\Http\Requests\SendEmailFrmRequest;
use App\Invoicer\Repositories\Contracts\EstimateInterface as Estimate;
use App\Invoicer\Repositories\Contracts\EstimateItemInterface as EstimateItem;
use App\Invoicer\Repositories\Contracts\ProductInterface as Product;
use App\Invoicer\Repositories\Contracts\TaxSettingInterface as Tax;
use App\Invoicer\Repositories\Contracts\ClientInterface as Client;
use App\Invoicer\Repositories\Contracts\CurrencyInterface as Currency;
use App\Invoicer\Repositories\Contracts\SettingInterface as Setting;
use App\Invoicer\Repositories\Contracts\NumberSettingInterface as Number;
use App\Invoicer\Repositories\Contracts\TemplateInterface as Template;
use App\Invoicer\Repositories\Contracts\EstimateSettingInterface as EstimateSetting;
use App\Invoicer\Repositories\Contracts\EmailSettingInterface as MailSetting;
use App\Invoicer\Repositories\Contracts\InvoiceInterface as Invoice;
use App\Invoicer\Repositories\Contracts\InvoiceSettingInterface as InvoiceSetting;
use App\Invoicer\Repositories\Contracts\InvoiceItemInterface as InvoiceItem;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use Kris\LaravelFormBuilder\FormBuilderTrait;
use Laracasts\Flash\Flash;
use PDF;

class EstimatesController extends Controller {
    use FormBuilderTrait;
    protected $formClass = EstimateForm::class;
    protected $product,$tax,$client,$currency,$estimate,$estimateItem,$setting, $number,$template,$estimateSetting,$mail_setting,$invoiceSetting,$invoiceItem,$invoice;
    protected $datatable = EstimateDatatable::class;
   protected $routes = [
        'index' => 'estimates.index',
        'create' => 'estimates.create',
        'show' => 'estimates.show',
        'edit' => 'estimates.edit',
        'store' => 'estimates.store',
        'destroy' => 'estimates.destroy',
        'update' => 'estimates.update'
    ];
    public function __construct(Product $product,Tax $tax, Client $client, Currency $currency, Estimate $estimate, EstimateItem $estimateItem, Setting $setting, Number $number,Template $template, EstimateSetting $estimateSetting, MailSetting $mail_setting,InvoiceSetting $invoiceSetting,InvoiceItem $invoiceItem,Invoice $invoice ){
        $this->product = $product;
        $this->client = $client;
        $this->currency = $currency;
        $this->tax = $tax;
        $this->estimate = $estimate;
        $this->estimateItem = $estimateItem;
        $this->setting = $setting;
        $this->number = $number;
        $this->template = $template;
        $this->estimateSetting = $estimateSetting;
        $this->mail_setting = $mail_setting;
        $this->invoiceSetting = $invoiceSetting;
        $this->invoiceItem = $invoiceItem;
        $this->invoice = $invoice;
        View::share('heading', trans('app.estimates'));
        View::share('headingIcon', 'list-alt');
        View::share('showBtnCreate', true);
        View::share('btnCreateText', trans('app.new_estimate'));
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
        if(!hasPermission('add_estimate', true)) return redirect('estimates');
        $settings     = $this->estimateSetting->first();
        $start        = $settings ? $settings->start_number : 0;
        $estimate_form = $this->form($this->formClass, [
            'method' => 'POST',
            'url' => route($this->routes['store']),
            'id' => 'estimate_form',
            'class' => 'needs-validation row', 'novalidate',
            'model'=>[
                'estimate_no'=>$this->number->prefix('estimate_number', $this->estimate->generateEstimateNum($start)),
                'terms' => $settings ? $settings->terms : ''
            ]
        ]);
        return view('estimates.create',compact('estimate_form'));
	}
    /**
     * Store a newly created resource in storage.
     * @param EstimateFormRequest $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function store(EstimateFormRequest $request)
	{
        $estimateData = [
            'client_id'     => $request->get('client_id'),
            'estimate_no'   => $request->get('estimate_no'),
            'estimate_date' => date('Y-m-d', strtotime($request->get('estimate_date'))),
            'notes'         => $request->get('notes'),
            'terms'         => $request->get('terms'),
            'currency'      => $request->get('currency')
        ];
        $estimate = $this->estimate->create($estimateData);
        if($estimate){
            $this->saveItems($estimate, $request->items);
            $settings     = $this->estimateSetting->first();
            if($settings){
                $start = $settings->start_number+1;
                $this->estimateSetting->updateById($settings->uuid, array('start_number'=>$start));
            }
            return Response::json(array('success' => true,'redirectTo'=>route('estimates.show', $estimate->uuid), 'msg' => trans('app.record_created')), 200);
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
        $estimate = $this->estimate->getById($uuid);
        if($estimate){
            $settings = $this->setting->first();
            $estimate_settings = $this->estimateSetting->first();
            return view('estimates.show', compact('estimate', 'settings','estimate_settings'));
        }
        return Redirect::route('estimates.index');
	}
    /**
     * Show the form for editing the specified resource.
     * @param $uuid
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
	public function edit($uuid)
	{
        if(!hasPermission('edit_estimate', true)) return redirect('estimates');
        $estimate = $this->estimate->getById($uuid);
        if($estimate){
            $estimate_form = $this->form($this->formClass, [
                'method' => 'PATCH',
                'url' => route($this->routes['update'],$estimate),
                'id' => 'estimate_form',
                'class' => 'needs-validation row', 'novalidate',
                'model'=> $estimate
            ]);
            return view('estimates.edit',compact('estimate_form','estimate'));
        }
        return Redirect::route('estimates.index');
	}
    /**
     * Update the specified resource in storage.
     * @param EstimateFormRequest $request
     * @param $uuid
     * @return \Symfony\Component\HttpFoundation\Response
     */
	public function update(EstimateFormRequest $request, $uuid)
	{
        $estimateData = [
            'client_id'     => $request->get('client_id'),
            'estimate_no'   => $request->get('estimate_no'),
            'estimate_date' => date('Y-m-d', strtotime($request->get('estimate_date'))),
            'notes'         => $request->get('notes'),
            'terms'         => $request->get('terms'),
            'currency'      => $request->get('currency')
        ];
        $estimate = $this->estimate->updateById($uuid, $estimateData);
        if($estimate){
            $this->saveItems($estimate, $request->items);
            return Response::json(array('success' => true,'redirectTo'=>route('estimates.show', $estimate->uuid), 'msg' => trans('app.record_updated')), 200);
        }
        return Response::json(array('success' => false, 'msg' => trans('app.record_update_failed')), 400);
	}
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteItem(){
        $uuid = request('id');
        if($this->estimateItem->deleteById($uuid))
            return Response::json(array('success' => true, 'msg' => trans('app.record_deleted')), 200);

        return Response::json(array('success' => false, 'msg' => trans('app.record_deletion_failed')), 400);
    }
    /**
     * @param $uuid
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function estimatePdf($uuid){
        $estimate = $this->estimate->getById($uuid);
        if($estimate){
            $settings = $this->setting->first();
            $estimate_settings = $this->estimateSetting->first();
            $estimate->estimate_logo = $estimate_settings && $estimate_settings->logo ? base64_img(config('app.images_path').$estimate_settings->logo) : '';
            $pdf = PDF::loadView('estimates.pdf', compact('settings', 'estimate','estimate_settings'));
            return $pdf->download(trans('app.estimate').'_'.$estimate->estimate_no.'_'.date('Y-m-d').'.pdf');
        }
        return Redirect::route('estimates.index');
    }
    public function send_modal($uuid){
        $estimate = $this->estimate->getById($uuid);
        $template = $this->template->where('name', 'estimate')->first();
        return view('estimates.send_modal',compact('estimate','template'));
    }
    public function send(SendEmailFrmRequest $request){
        try {
        $uuid = $request->get('estimate_id');
        $estimate = $this->estimate->getById($uuid);
        $settings = $this->setting->first();
        $estimate_settings = $this->estimateSetting->first();
        $data_object = new \stdClass();
        $data_object->settings  = $settings;
        $data_object->client    = $estimate->client;
        $data_object->user = $estimate->client;
        $estimate->estimate_logo = $estimate_settings && $estimate_settings->logo ? base64_img(config('app.images_path').$estimate_settings->logo) : '';
        $pdf_name = trans('app.estimate').'_'. $estimate->estimate_no . '_' . date('Y-m-d') . '.pdf';
        PDF::loadView('estimates.pdf', compact('settings', 'estimate', 'estimate_settings'))->save(config('app.assets_path').'attachments/'.$pdf_name);
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
            sendmail($params);
            Flash::success(trans('app.email_sent'));
            return response()->json(['type' => 'success','message' => trans('app.email_sent')]);
        }catch (\Exception $exception){
            $error = $exception->getMessage();
            Flash::error($error);
            return response()->json(['type' => 'fail', 'message' => $error],422);
        }
    }
    public function makeInvoice(){
        $uuid = request()->get('id');
        $estimate = $this->estimate->getById($uuid);
        $settings     = $this->invoiceSetting->first();
        $start        = $settings ? $settings->start_number : 0;
        $invoice_num  = $this->number->prefix('invoice_number', $this->invoice->generateInvoiceNum($start));
        $invoiceData = array(
            'client_id'     => $estimate->client_id,
            'invoice_no'    => $invoice_num,
            'invoice_date'  => date('Y-m-d'),
            'notes'         => $estimate->notes,
            'terms'         => $estimate->terms,
            'currency'      => $estimate->currency,
            'status'        => '0',
            'discount'      => 0,
            'recurring'     => 0,
            'recurring_cycle' => 1,
            'due_date' => date('Y-m-d')
        );
        $invoice = $this->invoice->create($invoiceData);
        if($invoice) {
            $items = $estimate->items;
            foreach ($items as $item) {
                $itemsData = array(
                    'invoice_id' => $invoice->uuid,
                    'item_name' => $item->item_name,
                    'item_description' => $item->item_description,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'tax_id' => $item->tax != '' ? $item->tax->uuid : null,
                );
                $this->invoiceItem->create($itemsData);
            }
            $settings = $this->invoiceSetting->first();
            if ($settings) {
                $start = $settings->start_number + 1;
                $this->invoiceSetting->updateById($settings->uuid, array('start_number' => $start));
            }
            return Response::json(array('success' => true, 'redirectTo'=>route('invoices.show',$invoice->uuid), 'msg' => trans('app.record_created')), 200);
        }else{
            return Response::json(array('success' => false, 'msg' => trans('app.record_creation_failed')), 400);
        }
    }
	public function destroy($uuid)
	{
        if(!hasPermission('delete_estimate', true)) return redirect('estimates');
        if($this->estimate->deleteById($uuid)){
            Flash::success(trans('app.record_deleted'));
            return Redirect::route('estimates.index');
        }
        Flash::error(trans('app.record_deletion_failed'));
        return Redirect::route('estimates.index');
	}
    public function saveItems($entity, $rows){
        $ids = [];
        foreach ($rows as $row) {
            if($row['item_id'] > 0){
                $product = $this->product->getById($row['item_id']);
                $row['item_description'] = $product->description;
            }
            $row['estimate_id'] = $entity->uuid;
            $row['tax_id'] = $row['tax_id'] != '' ? $row['tax_id'] : null;
            if ($row['uuid'] > 0) {
                $record = $entity->items()->find($row['uuid']);
                $record->fill($row);
                $record->save();
            } else {
                $record = $this->estimateItem->create($row);
            }
            $ids[] = $record->uuid;
        }
        foreach ($entity->items as $row) {
            if (!in_array($row->uuid, $ids)) {
                $row->delete();
            }
        }
    }
}
