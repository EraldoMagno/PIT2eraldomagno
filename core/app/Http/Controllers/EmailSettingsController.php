<?php namespace App\Http\Controllers;

use App\Http\Forms\EmailSettingForm;
use App\Http\Requests\EmailSettingsRequest;
use App\Invoicer\Repositories\Contracts\EmailSettingInterface as Setting;
use Laracasts\Flash\Flash;
use Illuminate\Support\Facades\View;
use Kris\LaravelFormBuilder\FormBuilderTrait;

class EmailSettingsController extends Controller {
	use FormBuilderTrait;
	private $setting;
	protected $formClass = EmailSettingForm::class;
	protected $routes = [
        'index' => 'settings.email.index',
        'store' => 'settings.email.store',
        'update' => 'settings.email.update'
    ];

	public function __construct(Setting $setting){
		$this->setting = $setting;
        $this->middleware('permission:edit_setting');
		View::share('heading', trans('app.email_settings'));
        View::share('headingIcon', 'paper-plane');
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
	public function store(EmailSettingsRequest $request)
	{
		$data =  array(
			'protocol'		    =>$request->protocol,
			'smtp_host' 	    =>$request->smtp_host,
			'smtp_username'     =>$request->smtp_username,
			'smtp_password'     =>$request->smtp_password,
			'smtp_port' 	    =>$request->smtp_port,
			'from_email' 	    =>$request->from_email,
			'mailgun_domain' 	=>$request->mailgun_domain,
			'mailgun_secret' 	=>$request->mailgun_secret,
			'mandrill_secret' 	=>$request->mandrill_secret,
			'from_name' 	    =>$request->from_name,
			'encryption' 	    =>$request->encryption
		);
		if($this->setting->create($data)){
		    saveConfiguration([
		        'MAIL_DRIVER'       =>$request->protocol,
		        'MAILGUN_DOMAIN'    =>$request->mailgun_domain,
                'MAILGUN_SECRET'    =>$request->mailgun_secret,
                'MANDRILL_SECRET'   =>$request->mandrill_secret,
                'MAIL_FROM_ADDRESS' =>$request->from_email,
                'MAIL_FROM_NAME'    =>$request->from_name,
                'MAIL_USERNAME'     =>$request->smtp_username,
                'MAIL_PASSWORD'     =>"'$request->smtp_password'",
                'MAIL_HOST'         =>$request->smtp_host,
                'MAIL_PORT'         =>$request->smtp_port,
                'MAIL_ENCRYPTION'   =>$request->encryption
            ]);
			Flash::success(trans('app.record_updated'));
		}
		else{
			Flash::error(trans('app.update_failed'));
		}
		return redirect('settings/email');
	}
	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(EmailSettingsRequest $request, $uuid)
	{
		$data =  array(
			'protocol'		    =>$request->protocol,
			'smtp_host' 	    =>$request->smtp_host,
			'smtp_username'     =>$request->smtp_username,
			'smtp_password'     =>$request->smtp_password,
			'smtp_port' 	    =>$request->smtp_port,
			'from_email' 	    =>$request->from_email,
            'mailgun_domain' 	=>$request->mailgun_domain,
            'mailgun_secret' 	=>$request->mailgun_secret,
            'mandrill_secret' 	=>$request->mandrill_secret,
            'from_name' 	    =>$request->from_name,
            'encryption' 	    =>$request->encryption
		);

		if($this->setting->updateById($uuid, $data)){
            saveConfiguration([
                'MAIL_DRIVER'       =>$request->protocol,
                'MAILGUN_DOMAIN'    =>$request->mailgun_domain,
                'MAILGUN_SECRET'    =>$request->mailgun_secret,
                'MANDRILL_SECRET'   =>$request->mandrill_secret,
                'MAIL_FROM_ADDRESS' =>$request->from_email,
                'MAIL_FROM_NAME'    =>$request->from_name,
                'MAIL_USERNAME'     =>$request->smtp_username,
                'MAIL_PASSWORD'     =>"'$request->smtp_password'",
                'MAIL_HOST'         =>$request->smtp_host,
                'MAIL_PORT'         =>$request->smtp_port,
                'MAIL_ENCRYPTION'   =>$request->encryption
            ]);
			Flash::success(trans('app.record_updated'));
		}
		else{
			Flash::error(trans('app.update_failed'));
		}
		return redirect('settings/email');
	}
}
