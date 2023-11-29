<?php namespace App\Http\Controllers;

use App\Datatables\LanguageDatatable;
use App\Http\Forms\LanguageForm;
use App\Http\Requests\TranslationFormRequest;
use App\Invoicer\Repositories\Contracts\TranslationInterface as Translation;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Kris\LaravelFormBuilder\FormBuilderTrait;
use Laracasts\Flash\Flash;

class TranslationsController extends Controller {
	use FormBuilderTrait;
	protected $translation;
    protected $formClass = LanguageForm::class;
    protected $datatable = LanguageDatatable::class;
    protected $routes = [
        'index' => 'settings.translation.index',
        'create' => 'settings.translation.create',
        'store' => 'settings.translation.store',
        'update' => 'settings.translation.update'
    ];
    public function __construct(Translation $translation){
        $this->middleware('permission:edit_setting');
        $this->translation = $translation;
        View::share('heading', trans('app.translations'));
        View::share('headingIcon', 'globe');
        View::share('createDisplayMode', 'ajax-modal');
        View::share('routes', $this->routes);
        View::share('showBtnCreate', true);
        View::share('btnCreateText', trans('app.create_locale'));
        View::share('iconCreate', 'plus');
    }

    public function index(){
        $datatable = App::make($this->datatable);
        return $datatable->render('settings.index');
    }
	// /**
	//  * Display a listing of the resource.
	//  *
	//  * @return Response
	//  */
	// public function index(){
	// 	$locales = $this->translation->all();
	// 	return view('translations.index', compact('locales'));
	// }
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create(){
		$form = $this->form($this->formClass, [
            'method' => 'POST',
            'url' => route($this->routes['store']),
            'class' => 'needs-validation row ajax-submit',
            'novalidate'
        ]);
        $heading = trans('app.add_locale');
        return view('crud.modal',compact('heading','form'));
	}
	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(TranslationFormRequest $request)
	{
		$data =  array(
			'locale_name'    =>ucfirst($request->locale_name),
			'short_name'     =>$request->short_name,
			'status'   		 =>$request->status,
            'default'        => $request->default
		);
		if ($request->hasFile('flag')){
			$file = $request->file('flag');
			$filename = strtolower(Str::random(50) . '.' . $file->getClientOriginalExtension());
			$file->move(config('app.images_path').'flags/', $filename);
			\Image::make(sprintf(config('app.images_path').'flags/%s', $filename))->resize(16,11)->save();
			$data['flag']= $filename;
		}
        if($request->default){
            $this->translation->resetDefault();
        }
		if($this->translation->create($data)){
			$locale_path = base_path().'/resources/lang/'.$request->short_name;
			if(!\File::exists($locale_path)) {
				\File::makeDirectory($locale_path, 0775);
			}
			Flash::success(trans('record_created'));
			return Response::json(array('success' => true, 'msg' => trans('app.record_created')), 200);
		}

		return Response::json(array('success' => false, 'msg' => trans('app.record_failed')), 422);
	}
	/**
	 * Show the form for editing the specified resource.
	 * @param $uuid
	 * @return \Illuminate\View\View
	 */
	public function edit($uuid)
	{
		$locale = $this->translation->getById($uuid);
        $form = $this->form($this->formClass, [
            'method' => 'PATCH',
            'url' => route($this->routes['update'],$uuid),
            'class' => 'needs-validation row ajax-submit',
            'novalidate',
            'model'=> $locale
        ]);
        $heading = trans('app.edit_locale');
        return view('crud.modal',compact('heading','form'));
	}
	/**
	 * Update the specified resource in storage.
	 * @param TranslationFormRequest $request
	 * @param $uuid
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function update(TranslationFormRequest $request, $uuid)
	{
		$locale = $this->translation->getById($uuid);
		$data =  array(
			'locale_name'    =>ucfirst($request->locale_name),
			'status'   		 =>$request->status,
            'default'        =>$request->default
		);
		if($locale->short_name != 'en'){
            $data['short_name'] = $request->short_name;
        }
		if ($request->hasFile('flag')){
			$file = $request->file('flag');
			$filename = strtolower(Str::random(50) . '.' . $file->getClientOriginalExtension());
			$file->move(config('app.images_path').'flags/', $filename);
			\Image::make(sprintf(config('app.images_path').'flags/%s', $filename))->resize(16,11)->save();
			if(is_file(config('app.images_path').'flags/'.$locale->flag)){
				\File::delete(config('app.images_path').'flags/'.$locale->flag);
			}
			$data['flag']= $filename;
		}
        if($request->default){
            $this->translation->resetDefault();
        }
		if($this->translation->updateById($uuid,$data)){
            if($locale->short_name != $request->short_name && $locale->short_name != 'en') {
                $this->translation->updateLocaleKey($locale->short_name, $request->short_name);
            }
            if($locale->short_name != 'en') {
                $old_path = base_path() . '/resources/lang/' . $locale->short_name;
                $new_path = base_path() . '/resources/lang/' . $request->short_name;
                if (!\File::exists($new_path)) {
                    \File::move($old_path, $new_path);
                }
            }
			Flash::success(trans('app.record_updated'));
			return Response::json(array('success' => true, 'msg' => trans('app.record_updated')), 200);
		}
		return Response::json(array('success' => false, 'msg' => trans('app.update_failed')), 422);
	}
	/**
	 * Remove the specified resource from storage.
	 * @param $uuid
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function destroy($uuid)
	{
		$locale = $this->translation->getById($uuid);
		if($this->translation->deleteById($uuid)){
			if(is_file(config('app.images_path').'flags/'.$locale->flag)){
				\File::delete(config('app.images_path').'flags/'.$locale->flag);
			}
			Flash::success(trans('app.record_deleted'));
		}
		else {
            Flash::error(trans('app.delete_failed'));
        }
		return redirect('settings/translations');
	}
}
