<?php namespace App\Http\Controllers;

use App\Http\Forms\InvoiceSettingForm;
use App\Http\Requests\InvoiceSettingsFormRequest;
use App\Invoicer\Repositories\Contracts\InvoiceSettingInterface as Setting;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\View;
use Kris\LaravelFormBuilder\FormBuilderTrait;
use Laracasts\Flash\Flash;

class InvoiceSettingsController extends Controller {
    use FormBuilderTrait;
    private $setting;
    protected $formClass = InvoiceSettingForm::class;
    protected $routes = [
        'index' => 'settings.invoice.index',
        'store' => 'settings.invoice.store',
        'update' => 'settings.invoice.update'
    ];
    public function __construct(Setting $setting){
        $this->setting = $setting;
        $this->middleware('permission:edit_setting');
        View::share('heading', trans('app.invoice_settings'));
        View::share('headingIcon', 'file-pdf-o');
    }
	/**
	 * Display a listing of the resource.
	 */
	public function index(){
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
     * @param InvoiceSettingsFormRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(InvoiceSettingsFormRequest $request){
        $data =  array(
            'start_number'    =>$request->start_number,
            'terms'           =>$request->terms,
            'due_days'        =>$request->due_days,
            'show_status'     =>$request->show_status,
            'show_pay_button' =>$request->show_pay_button
        );
        if ($request->hasFile('logo')){
            $file = $request->file('logo');
            $filename = strtolower(Str::random(50) . '.' . $file->getClientOriginalExtension());
            $file->move(config('app.images_path'), $filename);
            \Image::make(sprintf(config('app.images_path').'%s', $filename))->resize(200,null,function($constraint){
                $constraint->aspectRatio();
                $constraint->upsize();
            })->save();
            $data['logo']= $filename;
        }
        if($this->setting->create($data)){
            Flash::success(trans('app.record_updated'));
        }
        else{
            Flash::error(trans('app.update_failed'));
        }
        return redirect('settings/invoice');
	}
    /**
     * Update the specified resource in storage.
     * @param InvoiceSettingsFormRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(InvoiceSettingsFormRequest $request, $id){
        $setting = $this->setting->getById($id);
        $data =  array(
            'start_number'    =>$request->start_number,
            'terms'           =>$request->terms,
            'due_days'        =>$request->due_days,
            'show_status'     =>$request->show_status,
            'show_pay_button' =>$request->show_pay_button
        );
        if ($request->hasFile('logo')){
            $file = $request->file('logo');
            $filename = strtolower(Str::random(50) . '.' . $file->getClientOriginalExtension());
            $file->move(config('app.images_path'), $filename);
            \Image::make(sprintf(config('app.images_path').'%s', $filename))->resize(200,null,function($constraint){
                $constraint->aspectRatio();
                $constraint->upsize();
            })->save();
            $data['logo']= $filename;
            \File::delete(config('app.images_path').$setting->logo);
        }
        if($request->start_number < $setting->start_number){
                Flash::error('Error occurred, start number should be > '.$setting->start_number);
        }else{
            if($this->setting->updateById($id, $data)){
                Flash::success(trans('app.record_updated'));
            }
            else{
                Flash::error(trans('app.update_failed'));
            }
        }
        return redirect('settings/invoice');
	}
}
