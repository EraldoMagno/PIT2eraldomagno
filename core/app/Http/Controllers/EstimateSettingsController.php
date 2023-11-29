<?php namespace App\Http\Controllers;

use App\Http\Forms\EstimateSettingForm;
use App\Http\Requests\EstimateSettingsFormRequest;
use App\Invoicer\Repositories\Contracts\EstimateSettingInterface as Setting;
use Illuminate\Support\Str;
use Kris\LaravelFormBuilder\FormBuilderTrait;
use Laracasts\Flash\Flash;
use Illuminate\Support\Facades\View;

class EstimateSettingsController extends Controller {
	use FormBuilderTrait;
	private $setting;
	protected $formClass = EstimateSettingForm::class;
	protected $routes = [
        'index' => 'settings.estimate.index',
        'store' => 'settings.estimate.store',
        'update' => 'settings.estimate.update'
    ];
	public function __construct(Setting $setting){
		$this->setting = $setting;
        $this->middleware('permission:edit_setting');
		View::share('heading', trans('app.estimate_settings'));
        View::share('headingIcon', 'file-pdf-o');
	}
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
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
	 *
	 * @return Response
	 */
	public function store(EstimateSettingsFormRequest $request){
		$data =  array(
			'start_number'	=>$request->start_number,
			'terms'    	 	=>$request->terms,
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
		return redirect('settings/estimate');
	}
	/**
	 * Update the specified resource in storage.
	 * @param EstimateSettingsFormRequest $request
	 * @param $uuid
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function update(EstimateSettingsFormRequest $request, $uuid){
		$setting = $this->setting->getById($uuid);
		$data =  array(
			'start_number'  =>$request->start_number,
			'terms'         =>$request->terms,
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
			if($this->setting->updateById($uuid, $data)){
				Flash::success(trans('app.record_updated'));
			}
			else{
				Flash::error(trans('app.update_failed'));
			}
		}
		return redirect('settings/estimate');
	}
}
