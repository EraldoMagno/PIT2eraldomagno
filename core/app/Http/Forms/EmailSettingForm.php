<?php

namespace App\Http\Forms;

use Kris\LaravelFormBuilder\Form;

class EmailSettingForm extends Form
{
    public function buildForm()
    {
        $this->add('protocol', 'select', [
            'label' => trans('app.protocol'),
            'attr'=>['class'=>'form-control chosen form-control-sm'],
            'choices'=>['smtp'=>'SMTP','mailgun'=>'Mailgun','mandrill'=>'Mandrill'],
            'wrapper' => ['class' => 'form-group col-sm-12'],
        ]);
        $this->add('smtp_host', 'text', [
            'label' => trans('app.smtp_host'),
            'attr'=>['class'=>'form-control form-control-sm'],
            'wrapper' => ['class' => 'form-group col-sm-12']
        ]);
        $this->add('smtp_username', 'email', [
            'label' => trans('app.smtp_username'),
            'attr'=>['class'=>'form-control form-control-sm'],
            'wrapper' => ['class' => 'form-group col-sm-12']
        ]);
        $this->add('smtp_password', 'text', [
            'label' => trans('app.smtp_password'),
            'attr'=>['class'=>'form-control form-control-sm'],
            'wrapper' => ['class' => 'form-group col-sm-12']
        ]);
        $this->add('smtp_port', 'text', [
            'label' => trans('app.smtp_port'),
            'attr'=>['class'=>'form-control form-control-sm'],
            'wrapper' => ['class' => 'form-group col-sm-12']
        ]);
        $this->add('encryption', 'select', [
            'label' => trans('app.encryption'),
            'attr'=>['class'=>'form-control chosen form-control-sm'],
            'choices'=>['null'=>'None','SSL'=>'SSL','TLS'=>'TLS'],
            'wrapper' => ['class' => 'form-group col-sm-12'],
        ]);
        $this->add('mailgun_domain', 'text', [
            'label' => trans('app.mailgun_domain'),
            'attr'=>['class'=>'form-control form-control-sm'],
            'wrapper' => ['class' => 'form-group col-sm-12']
        ]);
        $this->add('mailgun_secret', 'text', [
            'label' => trans('app.mailgun_secret'),
            'attr'=>['class'=>'form-control form-control-sm'],
            'wrapper' => ['class' => 'form-group col-sm-12']
        ]);
        $this->add('mandrill_secret', 'text', [
            'label' => trans('app.mandrill_secret'),
            'attr'=>['class'=>'form-control form-control-sm'],
            'wrapper' => ['class' => 'form-group col-sm-12']
        ]);
        $this->add('from_name', 'text', [
            'label' => trans('app.from_name'),
            'attr'=>['class'=>'form-control form-control-sm','required'],
            'wrapper' => ['class' => 'form-group col-sm-12']
        ]);
        $this->add('from_email', 'email', [
            'label' => trans('app.from_email'),
            'attr'=>['class'=>'form-control form-control-sm','required'],
            'wrapper' => ['class' => 'form-group col-sm-12']
        ]);
        $this->add('buttons', 'static', [
            'template' => 'crud.form_button'
        ]);
        
    
    }
}
