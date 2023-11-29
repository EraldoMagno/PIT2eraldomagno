<?php namespace App\Http\Controllers;

use App\Http\Forms\TemplateForm;
use App\Http\Requests\TemplateFormRequest;
use App\Invoicer\Repositories\Contracts\TemplateInterface as Template;
use Illuminate\Support\Facades\View;
use Kris\LaravelFormBuilder\FormBuilderTrait;
use Laracasts\Flash\Flash;

class TemplatesController extends Controller {
    use FormBuilderTrait;
    private $template;
    protected $formClass = TemplateForm::class;
	protected $routes = [
        'index' => 'settings.template.index',
        'store' => 'settings.template.store',
        'update' => 'settings.template.update'
    ];
    public function __construct(Template $template)
    {
        $this->template = $template;
        $this->middleware('permission:edit_setting');
        View::share('heading', trans('app.email_templates'));
        View::share('headingIcon', 'envelope');
    }
    /**
     * Store a newly created resource in storage.
     * @param TemplateFormRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(TemplateFormRequest $request)
	{
		$data = [
            'name' => $request->name,
            'subject' => $request->subject,
            'body' => $request->body,
        ];
        if($this->template->create($data))
            Flash::success(trans('app.record_updated'));
        else
            Flash::error(trans('app.update_failed'));

        return redirect('settings/templates/'.$request->name);
	}

	/**
	 * Display the specified resource.
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
        $template = $this->template->getTemplate($id);
        $route = $template ? route($this->routes['update'],$template->uuid) : route($this->routes['store']);
        $method = $template ? 'PATCH' : 'POST';
        if(!$template){
            $template = new \stdClass();
            $template->name = $id;
        }
        //$template = $template ? $template-> : $id;
        $form = $this->form($this->formClass, [
            'method' => $method,
            'url' => $route,
            'class' => 'needs-validation',
            'novalidate',
            'model'=>$template
        ]); 
		return view('settings.index', compact('form'));
       // return view('settings.template', compact('template', 'select'));
	}

    /**
     * Update the specified resource in storage.
     * @param TemplateFormRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(TemplateFormRequest $request, $id)
	{
        $data = array(
            'subject' => $request->subject,
            'body' => $request->body,
        );

        if($this->template->updateById($id, $data))
            Flash::success(trans('app.record_updated'));
        else
            Flash::error(trans('app.update_failed'));

        return redirect('settings/templates/'.$request->name);
	}


}
