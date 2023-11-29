<?php namespace App\Http\Controllers\ClientArea;

use App\Datatables\ClientArea\EstimateDatatable;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\View;
use App\Invoicer\Repositories\Contracts\EstimateInterface as Estimate;
use App\Invoicer\Repositories\Contracts\EstimateItemInterface as EstimateItem;
use App\Invoicer\Repositories\Contracts\EstimateSettingInterface as EstimateSetting;
use App\Invoicer\Repositories\Contracts\ProductInterface as Product;
use App\Invoicer\Repositories\Contracts\TaxSettingInterface as Tax;
use App\Invoicer\Repositories\Contracts\ClientInterface as Client;
use App\Invoicer\Repositories\Contracts\CurrencyInterface as Currency;
use App\Invoicer\Repositories\Contracts\SettingInterface as Setting;
use Illuminate\Support\Facades\Redirect;

class EstimatesController extends Controller {
    protected $datatable = EstimateDatatable::class;
    protected $product,$tax,$client,$currency,$estimate,$estimateItem,$setting,$logged_user,$estimateSetting;
    public function __construct(Product $product,Tax $tax, Client $client, Currency $currency, Estimate $estimate, EstimateItem $estimateItem, Setting $setting,EstimateSetting $estimateSetting){
        $this->product = $product;
        $this->client = $client;
        $this->currency = $currency;
        $this->tax = $tax;
        $this->estimate = $estimate;
        $this->estimateItem = $estimateItem;
        $this->setting = $setting;
        $this->estimateSetting = $estimateSetting;
        View::share('heading', trans('app.estimates'));
        View::share('headingIcon', 'list-alt');
        $this->middleware(function ($request, $next) {
            $this->logged_user = auth()->guard('user')->user()->uuid;
            return $next($request);
        });
    }
	/**
	 * Display a listing of the resource.
	 *
	 */
	public function index(){
        $datatable = App::make($this->datatable);
        return $datatable->render('clientarea.crud.index');
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
            return view('clientarea.estimates.show', compact('estimate', 'settings','estimate_settings'));
        }
        return Redirect::route('cestimates');
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
            $pdf = \PDF::loadView('clientarea.estimates.pdf', compact('settings', 'estimate','estimate_settings'));
            return $pdf->download(trans('app.estimate').'_'.$estimate->estimate_no.'_'.date('Y-m-d').'.pdf');
        }
        return Redirect::route('cestimates');
    }
}
