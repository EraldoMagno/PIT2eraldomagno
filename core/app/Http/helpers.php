<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use App\Jobs\InvoicerMailerJob;
if (!function_exists('sendmail')) {
    function sendmail(array $data) {
        try {
            dispatch(new InvoicerMailerJob($data));
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
function message(){
    $notification = session()->pull('flash_notification')[0];
    $message_type = $notification->level;
    $msghtml = '<div class="alert alert-'.$message_type .'">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <strong>Message: </strong>'.$notification->message.'</div>';
    return $msghtml;
}
function display_form_errors($errors){
    $error_list = '';
    foreach($errors->all() as $error){
        $error_list .= '- '.$error.'<br/>';
    }
    $errorsHtml = '<div class="alert alert-danger">
                   <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                   '.$error_list.'</div>';
    return $errorsHtml;
}
function show_btn($route, $id){
    $btn = '<a class="btn btn-info btn-xs" href="'.route($route, $id).'" data-rel="tooltip" data-placement="top" title="'.trans("app.view").'"><i class="fa fa-eye"></i></a>';
    return $btn;
}
function edit_btn($route, $id){
    $btn = '<a class="btn btn-success btn-xs" data-toggle="ajax-modal" data-rel="tooltip" data-placement="top" href="'.route($route, $id).'" title="'.trans("app.edit").'"><i class="fa fa-pencil"></i></a>';
    return $btn;
}
function delete_btn($route, $id){
    $btn = Form::open(array("method"=>"DELETE", "route" => array($route, $id), 'class' => 'form-inline', 'style'=>'display:inline')).'
           <a class="btn btn-danger btn-xs btn-delete" data-rel="tooltip" data-placement="top" title="'.trans('app.delete').'"><i class="fa fa-trash"></i></a>'.Form::close();
    return $btn;
}
function format_amount($amount,$symbol=null,$show_symbol=true){
    $settings = App\Models\Setting::first();
    $thousand_separator = $settings && $settings->thousand_separator != '' ? $settings->thousand_separator : ',' ;
    $decimal_point = $settings && $settings->decimal_separator != '' ? $settings->decimal_separator : '.' ;
    $decimals = $settings && $settings->decimals != '' ? $settings->decimals : 2;
    $amount = number_format(round($amount,$decimals),$decimals,$decimal_point,$thousand_separator);
    $formatted_amount = $symbol && $show_symbol ? $symbol.' '.$amount : $amount;
    return $formatted_amount;
}
function format_date($date){
    $settings = App\Models\Setting::first();
    $date_format = $settings && $settings->date_format != '' ? $settings->date_format : 'd-m-Y';
    return date($date_format, strtotime($date));
}
function mask_input($number){
    if($number) {
        $mask_number = str_repeat("*", strlen($number) - 5) . substr($number, -5);
        return $mask_number;
    }
}
function get_company_name(){
    $settings = App\Models\Setting::first();
    $company_name = $settings && $settings->name != '' ? Str::limit($settings->name, 20, '')  : 'Gestao de Usuarios';
    return $company_name;
}
function get_setting_value($key){
    $settings = App\Models\Setting::first();
    $value = $settings && $settings->{$key} != '' ? $settings->{$key}  : '';
    return $value;
}
function get_languages(){
    $languages = \DB::table('locales')->where('status', 1)->get();
    return $languages;
}
function get_current_language($lang){
    $language = \DB::table('locales')->where('short_name', $lang)->first();
    return $language;
}
function get_default_language(){
    $language = \DB::table('locales')->where('default', 1)->where('status', 1)->first();
    return $language;
}
function current_language(){
    if(Session::has('applocale')){
        $current_lang = get_current_language(Session::get('applocale'));
        if(!$current_lang){
            $current_lang = get_default_language();
            if(!$current_lang){
                $current_lang = get_current_language(App::getLocale());
            }
        }
    }
    else{
        $current_lang = get_default_language();
        if(!$current_lang){
            $current_lang = get_current_language(App::getLocale());
        }
    }
    return [
        'lang'=>$current_lang,
        'flag'=> $current_lang && $current_lang->flag != '' ? $current_lang->flag : 'placeholder_Flag.jpg'
    ];
}
function is_verified(){
    $settings = App\Models\Setting::first();
    $purchase_code = $settings ? $settings->purchase_code : '';
    if($purchase_code != '' && config('services.license.is_verified')){
        return true;
    }
    return false;
}
function form_buttons(){
    $buttons = '<button type="submit" data-rel="tooltip" data-placement="top" title="'.trans('app.save').'" class="btn btn-sm btn-success"><i class="fa fa-save"></i> '.trans("app.save").'</button>
                <button type="button" data-rel="tooltip" data-placement="top" title="'.trans('app.close').'" data-dismiss="modal" class="btn btn-sm btn-danger"> <i class="fa fa-times"></i> '.trans("app.close").'</button>';
    return $buttons;
}
function statuses(){
    return array(
        '0' => array(
            'status' => 'unpaid',
            'label' => trans('app.unpaid'),
            'class' => 'badge-warning'
        ),
        '1' => array(
            'status' => 'partially_paid',
            'label' => trans('app.partially_paid'),
            'class' => 'badge-primary'
        ),
        '2' => array(
            'status' => 'paid',
            'label' => trans('app.paid'),
            'class' => 'badge-success'
        ),
        '3' => array(
            'status' => 'overdue',
            'label' => trans('app.overdue'),
            'class' => 'badge-danger'
        )
    );
}
function getStatus($field, $value){
    $statuses = statuses();
   foreach($statuses as $key => $status){
       if ( $status[$field] === $value )
           return $key;
   }
   return false;
}
function hasPermission($permission, $show_msg = false){
    if(auth()->guard('admin')->user()->hasPermission($permission) || auth()->guard('admin')->user()->HasRole('admin')){
        return true;
    }else{
        if($show_msg)\Flash::error(trans('app.dont_have_permission'));
        return false;
    }
}
function parse_template($object, $body){
    if (preg_match_all('/\{(.*?)\}/', $body, $template_vars)){
        $replace ='';
        foreach ($template_vars[1] as $var){
            switch (trim($var)){
                case 'invoice_number':
                    if(isset($object->invoice->number)){
                        $replace = $object->invoice->number;
                    }
                    break;
                case 'invoice_amount':
                    if(isset($object->invoice->totals['grandTotal'])){
                        $replace = $object->invoice->currency.$object->invoice->totals['grandTotal'];
                    }
                    break;
                case 'client_name':
                    if(isset($object->client->name)){
                        $replace = $object->client->name;
                    }
                    break;
                case 'client_email':
                    if(isset($object->client->email)){
                        $replace = $object->client->email;
                    }
                    break;
                case 'client_number':
                    if(isset($object->client->client_no)){
                        $replace = $object->client->client_no;
                    }
                    break;
                case 'company_name':
                    if(isset($object->settings->name)){
                        $replace = $object->settings->name;
                    }
                    break;
                case 'company_email':
                    if(isset($object->settings->email)){
                        $replace = $object->settings->email;
                    }
                    break;
                case 'company_website':
                    if(isset($object->settings->website)){
                        $replace = $object->settings->website;
                    }
                    break;
                case 'contact_person':
                    if(isset($object->settings->contact)){
                        $replace = $object->settings->contact;
                    }
                    break;
                case 'username':
                    if(isset($object->user->email)){
                        $replace = $object->user->email;
                    }
                    break;
                case 'password':
                    if(isset($object->user->password)){
                        $replace = $object->user->password;
                    }
                    break;
                case 'login_link':
                    if(isset($object->user->login_link)){
                        $replace = $object->user->login_link;
                    }
                    break;
                default:
                    $replace = '';
            }
            $body = str_replace('{' . $var . '}', $replace, $body);
        }
    }
    return $body;
}
function array_multi_subsort($array, $subkey){
    $b = array(); $c = array();
    foreach ($array as $k => $v) {
        $b[$k] = strtolower($v[$subkey]);
    }
    asort($b);
    foreach ($b as $key => $val) {
        $c[] = $array[$key];
    }
    return $c;
}
function currency_convert($from_id,$amount){
    $default_currency = App\Models\Currency::where('default_currency',1)->first();
    $from_currency = App\Models\Currency::find($from_id);
    if($default_currency){
        $default_currency_value = $amount / floatval($from_currency->exchange_rate) * floatval($default_currency->exchange_rate);
        return $default_currency_value;

    }else{
        return $amount;
    }
}
function defaultCurrency($symbol = false){
    $currency = App\Models\Currency::where('default_currency',1)->first();
    if($symbol){
        return $currency ? $currency->symbol : '$';
    }
    return $currency ? $currency->code.'('.$currency->symbol.')' : 'USD($)';
}
function defaultCurrencyCode(){
    $currency = App\Models\Currency::where('default_currency',1)->first();
    return $currency ? $currency->code : 'USD';
}
function getCurrencyId($symbol){
    $currency_code = explode("(", $symbol, 2)[0];
    $currency = App\Models\Currency::where('code',$currency_code)->first();
    return $currency->uuid;
}
function saveConfiguration($values, $configFilename = '.env') {
    if (empty($values) || !is_array($values)) {
        return false;
    }
    $envFile = base_path($configFilename);
    if (!File::exists($envFile)) {
        $existingConfig = [];
    } else {
        $existingConfig = file($envFile);
    }
    $configs = [];
    foreach ($existingConfig as $config) {
        if (!empty(str_replace(' ', '', $config))) {
            $config = str_replace([
                "\r",
                "\n"
            ], ['', ''], $config);
            $configParts = explode('=', $config, 2);
            if (!empty($configParts[1])) {
                if (!array_key_exists($configParts[0], $values)) {
                    $configs[] = $configParts[0].'='.$configParts[1];
                }
            }
        }
    }
    foreach ($values as $key => $value) {
        $value = str_replace('"', '\"', $value);
        if (strpos($values[$key], ' ') !== false) {
            $configs[] = $key.'="'.$value.'"';
        } else {
            $configs[] = $key.'='.$value;
        }
    }
    File::put($envFile, implode("\n", $configs));
    Artisan::call('config:clear');
    return true;
}

function image_url($image){
    return asset(config('app.images_path').$image);
}
function base64_img($image){
    $image_path = public_path().'/'.$image;
    $type = pathinfo($image_path, PATHINFO_EXTENSION);
    $data = file_get_contents($image_path);
    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
    return $base64;
}
function float_subtraction($num_1, $num_2){
    return ((floor($num_1 * 100) - floor($num_2 * 100)) / 100);
}
function btnCreate($route = '', $mode = 'ajax-modal',$text = 'Create new record', $iconCreate='plus'){
    return '<a href="'.route($route).'" class="btn btn-primary btn-sm" data-toggle="'.$mode.'">
        <i class="fa fa-'.$iconCreate.'"></i> '.$text.'
    </a>';
}
function active_dropdown_menu($prefix){
    if(Request()->route()->getPrefix() == $prefix){
        return true;
    }else{
        return false;
    }
}
function recur_cycles(){
    return [
      '1' => trans('app.monthly'),
      '2' => trans('app.quarterly'),
      '3' => trans('app.semi_annually'),
      '4' => trans('app.annually'),
    ];
}
function status_select_array(){
    $status_array = [];
    foreach (statuses() as $key=>$status){
        $status_array[$key] = $status['label'];
    }
    return $status_array;
}
function install_minimum_requirements(){
    return [
        'php' => [
            'check' => version_compare(phpversion(),"8.0.0",">="),
            'success' => 'PHP Version Compatible',
            'error' => 'PHP Version Not Compatible'
        ], 
        'BCMath' => [
            'check' => extension_loaded('BCMath'),
            'success' => 'BCMath Extension Enabled',
            'error' => 'BCMath Extension Disabled'
        ], 
        'Ctype' => [
            'check' => extension_loaded('Ctype'),
            'success' => 'Ctype Extension Enabled',
            'error' => 'Ctype Extension Disabled'
        ], 
        'openssl' => [
            'check' => extension_loaded('openssl'),
            'success' => 'OpenSSL Extension Enabled',
            'error' => 'OpenSSL Extension Disabled'
        ], 
        'mbstring' => [
            'check' => extension_loaded('mbstring'),
            'success' => 'Mbstring Extension Enabled',
            'error' => 'Mbstring Extension Disabled'
        ], 
        'tokenizer' => [
            'check' => extension_loaded('tokenizer'),
            'success' => 'Tokenizer Extension Enabled',
            'error' => 'Tokenizer Extension Disabled'
        ], 
        'XML' => [
            'check' => extension_loaded('XML'),
            'success' => 'XML Extension Enabled',
            'error' => 'XML Extension Disabled'
        ], 
        'images_folder' => [
            'check' => is_writable('assets/images'),
            'success' => 'ASSETS/IMAGES folder is Writable',
            'error' => 'ASSETS/IMAGES folder is not Writable'
        ], 
    ];
}