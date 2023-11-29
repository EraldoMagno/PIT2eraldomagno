<?php

namespace App\Http\Forms;

use Kris\LaravelFormBuilder\Form;
use App\Invoicer\Repositories\Contracts\CurrencyInterface as Currency;
use App\Models\Client;

class EstimateForm extends Form
{
    protected $currency;
    public function __construct(Currency $currency){
        $this->currency  = $currency;
    }
    public function buildForm()
    {
        $currencies = $this->currency->currencySelect();
        $default_currency = $this->currency->defaultCurrency();
        $this->add('client_id', 'select', [
            'label' => trans('app.customer'),
            'label_attr' => ['for' => $this->name],
            'attr'=>['class'=>'form-control form-control-sm chosen','required'],
            'choices' => Client::all()->pluck('name', 'uuid')->toArray(),
            'wrapper' => ['class' => 'form-group col-sm-6'],
        ]);
        $this->add('estimate_no', 'text', [
            'label' => trans('app.estimate_no'),
            'label_attr' => ['class' => 'required', 'for' => $this->name],
            'attr'=>['class'=>'form-control form-control-sm','required'],
            'wrapper' => ['class' => 'form-group col-sm-6'],
        ]);
        $this->add('currency', 'select', [
            'label' => trans('app.currency'),
            'choices' => $currencies,
            'selected' => $this->model->currency ?? $default_currency,
            'empty_value' => trans('app.none'),
            'attr'=>['class'=>'form-control form-control-sm chosen calc_event','required'],
            'wrapper' => ['class' => 'form-group col-sm-6'],
        ]);
        $this->add('estimate_date', 'text', [
            'label' => trans('app.estimate_date'),
            'label_attr' => ['for' => $this->name],
            'attr'=>['class'=>'form-control form-control-sm datepicker','required'],
            'wrapper' => ['class' => 'form-group col-sm-6'],
        ]);
        $this->add('items', 'collection', [
            'type' => 'form',
            'template' => 'estimates.partials.item-rows',
            'data' => null,
            'options' => [
                'class' => EstimateRowForm::class,
                'label' => false,
            ],
            'removeFromMassUpdate' => true,
        ]);
        $this->add('notes', 'textarea', [
            'label' => trans('app.notes'),
            'label_attr' => ['for' => $this->name],
            'attr'=>['class'=>'form-control form-control-sm text_editor','id'=>'estimate_notes','rows'=>2],
            'wrapper' => ['class' => 'form-group col-sm-12'],
        ]);
        $this->add('terms', 'textarea', [
            'label' => trans('app.terms'),
            'label_attr' => ['for' => $this->name],
            'attr'=>['class'=>'form-control form-control-sm text_editor','id'=>'estimate_terms','rows'=>2],
            'wrapper' => ['class' => 'form-group col-sm-12'],
        ]);
        $this->add('buttons', 'static', [
            'template' => 'crud.form_button'
        ]);
        $this->showFieldErrors = true;
    }
}
