<?php namespace App\Http\Controllers;

use App;
use App\Datatables\ClientDatatable;
use App\Http\Forms\ClientForm;
use App\Http\Requests\ClientFormRequest;
use App\Invoicer\Repositories\Contracts\ClientInterface as Client;
use App\Invoicer\Repositories\Contracts\InvoiceInterface as Invoice;
use App\Invoicer\Repositories\Contracts\EstimateInterface as Estimate;
use App\Invoicer\Repositories\Contracts\NumberSettingInterface as Number;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use Kris\LaravelFormBuilder\FormBuilderTrait;
use Laracasts\Flash\Flash;
class ClientsController extends Controller {
    use FormBuilderTrait;
    private $client, $invoice, $estimate, $number;
    protected $datatable = ClientDatatable::class;
    protected $formClass = ClientForm::class;
    protected $routes = [
        'index' => 'clients.index',
        'create' => 'clients.create',
        'show' => 'clients.show',
        'edit' => 'clients.edit',
        'store' => 'clients.store',
        'destroy' => 'clients.destroy',
        'update' => 'clients.update'
    ];
    public function __construct(Client $client, Invoice $invoice, Estimate $estimate, Number $number){
        $this->client = $client;
        $this->invoice = $invoice;
        $this->estimate = $estimate;
        $this->number = $number;
        View::share('heading', trans('app.clients'));
        View::share('headingIcon', 'users');
        View::share('showBtnCreate', true);
        View::share('btnCreateText', trans('app.new_client'));
        View::share('createDisplayMode', 'ajax-modal');
        View::share('routes', $this->routes);
        View::share('iconCreate', 'user-plus');
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
        if(!hasPermission('add_client', true)) return redirect('clients');
        $client_num = $this->number->prefix('client_number', $this->client->generateClientNum());
        $form = $this->form($this->formClass, [
            'method' => 'POST',
            'url' => route($this->routes['store']),
            'id' => 'create_form',
            'class' => 'needs-validation row ajax-submit',
            'novalidate',
            'model'=>[
                'client_no' => $client_num
            ]
        ]);
        $heading = trans('app.new_client');
        return view('crud.modal', compact('heading','form'));
	}
    /**
     * Store a newly created resource in storage.
     * @param ClientFormRequest $request
     * @return Response
     */
    public function store(ClientFormRequest $request)
	{
        $data = $this->form($this->formClass)->getFieldValues(true);
        $data['password'] = Hash::make($request->password);
        $client = $this->client->create($data);
        if($client){
            if($request->ajaxNonReload){
                return response()->json(['value' => $client->uuid->string, 'text' => $client->name],200);
            }else {
                Flash::success(trans('app.record_created'));
                return response()->json(array('success' => true, 'msg' => trans('app.record_created')), 200);
            }
        }
        return response()->json(array('success' => false, 'msg' => trans('app.record_creation_failed')), 422);
	}
    /**
     * Show the form for editing the specified resource.
     * @param $uuid
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function edit($uuid)
	{
        if(!hasPermission('edit_client', true)) return redirect('clients');
		$client = $this->client->getById($uuid);
        if($client){
            unset($client->password);
            $form = $this->form($this->formClass, [
                'method' => 'PATCH',
                'url' => route($this->routes['update'],$client),
                'id' => 'create_form',
                'class' => 'needs-validation row ajax-submit',
                'novalidate',
                'model'=>$client
            ]);
            $heading = trans('app.edit_client');
            return view('crud.modal', compact('heading','form'));
        }
        else{
            return redirect('clients');
        }
	}
    /**
     * @param $uuid
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function show($uuid){
        $client = $this->client->getById($uuid);
        if($client){
            foreach($client->invoices as $count => $invoice){
                $client->invoices[$count]['totals'] = $this->invoice->invoiceTotals($invoice->uuid);
            }
            foreach($client->estimates as $count => $estimate){
                $client->estimates[$count]['totals'] = $this->estimate->estimateTotals($estimate->uuid);
            }
            return view('clients.show', compact('client'));
        }
        return redirect('clients');
    }
    /**
     * Update the specified resource in storage.
     * @param ClientFormRequest $request
     * @param $uuid
     * @return Response
     *
     */
    public function update(ClientFormRequest $request, $uuid)
	{
        $data = $this->form($this->formClass)->getFieldValues(true);
        if($request->password != ''){
            $data['password'] = Hash::make($request->password);
        }else{
            unset($data['password']);
        }
        if($this->client->updateById($uuid,$data)){
            Flash::success(trans('app.record_updated'));
            return response()->json(array('success' => true, 'msg' => trans('app.record_updated')), 200);
        }
        return response()->json(array('success' => false, 'msg' => trans('app.update_failed')), 422);
	}
    /**
     * Remove the specified resource from storage.
     * @param $uuid
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($uuid)
	{
        if(!hasPermission('delete_client', true)) return redirect('clients');
		$this->client->deleteById($uuid);
        if (request()->ajax()) {
            return response()->json([
                'type' => 'success',
                'message' => 'Record deleted successfully!',
                'action' => 'refresh_datatable'
            ]);
        } else {
            Flash::success(trans('app.record_deleted'));
            return redirect(route('clients.index'));
        }
	}
}
