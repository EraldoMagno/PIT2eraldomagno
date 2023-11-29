<?php namespace App\Http\Controllers;

use App\Http\Forms\NumberSettingForm;
use App\Invoicer\Repositories\Contracts\NumberSettingInterface as Setting;
use Illuminate\Support\Facades\View;
use Kris\LaravelFormBuilder\FormBuilderTrait;
use Laracasts\Flash\Flash;

class NumberSettingsController extends Controller {
    use FormBuilderTrait;
    private $setting;
    protected $formClass = NumberSettingForm::class;
	protected $routes = [
        'index' => 'settings.number.index',
        'store' => 'settings.number.store',
        'update' => 'settings.number.update'
    ];
    public function __construct(Setting $setting){
        $this->setting = $setting;
        $this->middleware('permission:edit_setting');
        View::share('heading', trans('app.number_prefix'));
        View::share('headingIcon', 'file-text');
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $setting = $this->setting->first();
        $route = $setting ? route($this->routes['update'],$setting->uuid) : route($this->routes['store']);
        $method = $setting ? 'PATCH' : 'POST';
        $form = $this->form($this->formClass, [
            'method' => $method,
            'url' => $route,
            'class' => 'needs-validation',
            'novalidate',
            'model'=>$setting
        ]); 
		return view('settings.index', compact('form'));
	}
	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
        $data =  [
            'invoice_number'  => request('invoice_number'),
            'client_number'   => request('client_number'),
            'estimate_number' => request('estimate_number'),
        ];

        if($this->setting->create($data)){
            Flash::success(trans('app.record_updated'));
        }
        else{
            Flash::error(trans('app.update_failed'));
        }
        return redirect('settings/number');
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
        $data =  [
            'invoice_number'  => request('invoice_number'),
            'client_number'   => request('client_number'),
            'estimate_number' => request('estimate_number'),
        ];
        if($this->setting->updateById($id, $data)){
            Flash::success(trans('app.record_updated'));
        }
        else{
            Flash::error(trans('app.update_failed'));
        }
        return redirect('settings/number');
	}


}
