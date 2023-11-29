<?php

namespace App\Http\Forms;

use Kris\LaravelFormBuilder\Form;

class CurrencyForm extends Form
{
    public function buildForm()
    {
        $this->add('name', 'text', [
            'label' => trans('app.name'),
            'attr'=>['class'=>'form-control', 'required'],
            'wrapper' => ['class' => 'form-group col-sm-12']
        ]);
        $this->add('code', 'text', [
            'label' => trans('app.code'),
            'attr'=>['class'=>'form-control', 'required'],
            'wrapper' => ['class' => 'form-group col-sm-12']
        ]);
        $this->add('symbol', 'text', [
            'label' => trans('app.symbol'),
            'attr'=>['class'=>'form-control', 'required'],
            'wrapper' => ['class' => 'form-group col-sm-12']
        ]);
        $this->add('exchange_rate', 'text', [
            'label' => trans('app.due_after'),
            'template' => 'settings.partials.exchange_rate'
        ]);
        $this->add('active', 'select', [
            'label' => trans('app.active'),
            'attr'=>['class'=>'form-control chosen'],
            'choices' => ['0'=>'No',  '1' => 'Yes'],
            'wrapper' => ['class' => 'form-group col-sm-12'],
        ]);
        $this->add('default_currency', 'select', [
            'label' => trans('app.default_currency'),
            'attr'=>['class'=>'form-control chosen'],
            'choices' => ['0'=>'No',  '1' => 'Yes'],
            'wrapper' => ['class' => 'form-group col-sm-12'],
        ]);
        $this->add('buttons', 'static', [
            'template' => 'crud.modal_form_buttons'
        ]);
    }
}
