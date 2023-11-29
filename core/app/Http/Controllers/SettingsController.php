<?php namespace App\Http\Controllers;

use App\Http\Forms\SettingForm;
use App\Http\Requests\SettingsFormRequest;
use App\Invoicer\Repositories\Contracts\SettingInterface as Setting;
use Illuminate\Support\Str;
use Laracasts\Flash\Flash;
use Illuminate\Support\Facades\View;
use Kris\LaravelFormBuilder\FormBuilderTrait;

class SettingsController extends Controller {
    use FormBuilderTrait;
    private $setting;
    protected $formClass = SettingForm::class;
    protected $routes = [
        'index' => 'settings.company.index',
        'store' => 'settings.company.store',
        'update' => 'settings.company.update'
    ];
    public function __construct(Setting $setting){
        $this->setting = $setting;
        $this->middleware('permission:edit_setting');
        View::share('heading', trans('app.system_settings'));
        View::share('headingIcon', 'cogs');
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
     * @param SettingsFormRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(SettingsFormRequest $request){
        $data =  array(
            'name'      =>$request->name,
            'email'     =>$request->email,
            'contact'   =>$request->contact,
            'phone'     => $request->phone,
            'address1'  => $request->address1,
            'address2'  => $request->address2,
            'city'      => $request->city,
            'state'     => $request->state,
            'country'   => $request->country,
            'postal_code'=> $request->postal_code,
            'vat'       => $request->vat,
            'website'   => $request->website,
            'date_format'=> $request->date_format,
            'thousand_separator'=> $request->thousand_separator,
            'decimal_separator'=> $request->decimal_separator,
            'decimals'=> $request->decimals,
        );
        if ($request->hasFile('logo')){
            $file = $request->file('logo');
            $filename = strtolower(Str::random(50) . '.' . $file->getClientOriginalExtension());
            $file->move(config('app.assets_absolute_path'), $filename);
            $data['logo']= $filename;
        }
        if ($request->hasFile('favicon')){
            $file = $request->file('favicon');
            $filename = 'favicon.' . $file->getClientOriginalExtension();
            $file->move(config('app.images_path'), $filename);
            \Image::make(sprintf(config('app.images_path').'%s', $filename))->resize(16, 16)->save();
            $data['favicon']= $filename;
        }
        if ($request->hasFile('login_bg')){
            $file = $request->file('login_bg');
            $filename = 'login_bg'.time().'.' . $file->getClientOriginalExtension();
            $file->move(config('app.images_path'), $filename);
            $data['login_bg']= $filename;
        }
        if($this->setting->create($data)){
            saveConfiguration(['APP_NAME'=>$request->name,'APP_URL'=>url('/')]);
            Flash::success(trans('app.settings_updated'));
        }
        else{
            Flash::error(trans('app.update_failed'));
        }
        return redirect('settings/company');
	}
    /**
     * Update the specified resource in storage.
     * @param SettingsFormRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(SettingsFormRequest $request, $id){
        $setting = $this->setting->getById($id);
        $data =  array(
            'name'      => $request->name,
            'email'     => $request->email,
            'contact'   => $request->contact,
            'phone'     => $request->phone,
            'address1'  => $request->address1,
            'address2'  => $request->address2,
            'city'      => $request->city,
            'state'     => $request->state,
            'country'   => $request->country,
            'postal_code'=> $request->postal_code,
            'vat'       => $request->vat,
            'website'   => $request->website,
            'date_format'=> $request->date_format,
            'thousand_separator'=> $request->thousand_separator,
            'decimal_separator'=> $request->decimal_separator,
            'decimals'=> $request->decimals,
        );
        if ($request->hasFile('logo')){
            $file = $request->file('logo');
            $filename = strtolower(Str::random(50) . '.' . $file->getClientOriginalExtension());
            $file->move(config('app.images_path'), $filename);
            \File::delete(config('app.images_path').$setting->logo);
            $data['logo']= $filename;
        }
        if ($request->hasFile('favicon')){
            $file = $request->file('favicon');
            $filename = 'favicon.'.$file->getClientOriginalExtension();
            $file->move(config('app.images_path'), $filename);
            \Image::make(sprintf(config('app.images_path').'%s', $filename))->resize(16, 16)->save();
            $data['favicon']= $filename;
        }
        if ($request->hasFile('login_bg')){
            $file = $request->file('login_bg');
            $filename = 'login_bg'.time().'.' . $file->getClientOriginalExtension();
            $file->move(config('app.images_path'), $filename);
            $data['login_bg']= $filename;
        }
        if($this->setting->updateById($id, $data)){
            saveConfiguration(['APP_NAME'=>$request->name,'APP_URL'=>url('/')]);
            Flash::success(trans('app.settings_updated'));
        }
        else{
            Flash::error(trans('app.update_failed'));
        }
        return redirect('settings/company');
	}
}
