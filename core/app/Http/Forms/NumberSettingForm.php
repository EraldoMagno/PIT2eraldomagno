<?php

namespace App\Http\Forms;

use Kris\LaravelFormBuilder\Form;

class NumberSettingForm extends Form
{
    public function buildForm()
    {
        $this->add('client_number', 'text', [
            'label' => trans('app.client_number_prefix'),
            'attr'=>['class'=>'form-control','required'],
            'wrapper' => ['class' => 'form-group col-sm-12']
        ]);
        $this->add('invoice_number', 'text', [
            'label' => trans('app.invoice_number_prefix'),
            'attr'=>['class'=>'form-control','required'],
            'wrapper' => ['class' => 'form-group col-sm-12']
        ]);
        $this->add('estimate_number', 'text', [
            'label' => trans('app.estimate_number_prefix'),
            'attr'=>['class'=>'form-control','required'],
            'wrapper' => ['class' => 'form-group col-sm-12']
        ]);
        $this->add('buttons', 'static', [
            'template' => 'crud.form_button'
        ]);
    }
}
