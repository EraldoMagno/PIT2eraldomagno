<?php namespace App\Models;
use File;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UuidModel;
class Setting extends Model {
    use UuidModel;
    public $incrementing = false;
    protected $primaryKey = 'uuid';

    protected $fillable = ['name', 'email', 'phone', 'address1', 'address2', 'city', 'state', 'postal_code','login_bg',
        'country', 'contact', 'vat', 'website', 'logo', 'favicon','date_format','thousand_separator','decimal_separator','decimals','purchase_code'];

    public static function boot(){
        parent::boot();
        // registering a callback to be executed upon the creation of a equipment
        static::updating(function ($setting) {
            if($setting->original['login_bg'] != $setting->attributes['login_bg']){
                if(File::exists(config('app.images_path').$setting->original['login_bg'])) File::delete(config('app.images_path').$setting->original['login_bg']);
            }            
        });
    }
}
