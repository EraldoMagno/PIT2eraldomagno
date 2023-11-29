<?php

namespace App\Http\Forms;

use App\Models\Client;
use Kris\LaravelFormBuilder\Form;
use App\Invoicer\Repositories\Contracts\CurrencyInterface as Currency;
class InvoiceForm extends Form
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
            'label' => trans('app.client'),
            'label_attr' => ['for' => $this->name],
            'attr'=>['class'=>'form-control form-control-sm chosen','required'],
            'choices' => Client::all()->pluck('name', 'uuid')->toArray(),
            'wrapper' => ['class' => 'form-group col-sm-6'],
        ]);
        $this->add('invoice_date', 'text', [
            'label' => trans('app.invoice_date'),
            'label_attr' => ['for' => $this->name,'class' => 'required'],
            'attr'=>['class'=>'form-control form-control-sm datepicker','required','readonly'],
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
        $this->add('due_date', 'text', [
            'label' => trans('app.due_date'),
            'label_attr' => ['class' => 'required'],
            'attr'=>['class'=>'form-control form-control-sm datepicker','required','readonly'],
            'wrapper' => ['class' => 'form-group col-sm-6'],
        ]);
        $this->add('invoice_no', 'text', [
            'label' => trans('app.invoice_number'),
            'label_attr' => ['class' => 'required'],
            'attr'=>['class'=>'form-control form-control-sm','required'],
            'wrapper' => ['class' => 'form-group col-sm-6'],
        ]);
        $this->add('status', 'select', [
            'label' => trans('app.status'),
            'label_attr' => ['class' => 'required'],
            'attr'=>['class'=>'form-control form-control-sm chosen','required'],
            'choices' => status_select_array(),
            'wrapper' => ['class' => 'form-group col-sm-6'],
        ]);
        $this->add('recurring', 'select', [
            'label' => trans('app.recurring'),
            'attr'=>['class'=>'form-control form-control-sm chosen','required'],
            'choices' => ['0'=>trans('app.no'),'1'=>trans('app.yes')],
            'wrapper' => ['class' => 'form-group col-sm-6'],
        ]);
        $this->add('recurring_cycle', 'select', [
            'label' => trans('app.recurring_cycle'),
            'attr'=>['class'=>'form-control form-control-sm chosen','required'],
            'choices' => recur_cycles(),
            'wrapper' => ['class' => 'form-group col-sm-6'],
        ]);
        $this->add('items', 'collection', [
            'type' => 'form',
            'template' => 'invoices.partials.item-rows',
            'discount'=>$this->model->discount ?? 0,
            'discount_mode'=>$this->model->discount_mode ?? 1,
            'data' => null,
            'options' => [
                'class' => InvoiceRowForm::class,
                'label' => false,
            ],
            'removeFromMassUpdate' => true,
        ]);
        $this->add('notes', 'textarea', [
            'label' => trans('app.notes'),
            'attr'=>['class'=>'form-control form-control-sm text_editor','id'=>'estimate_notes','rows'=>2],
            'wrapper' => ['class' => 'form-group col-sm-12'],
        ]);
        $this->add('terms', 'textarea', [
            'label' => trans('app.terms'),
            'attr'=>['class'=>'form-control form-control-sm text_editor','id'=>'invoice_terms','rows'=>2],
            'wrapper' => ['class' => 'form-group col-sm-12'],
        ]);
        $this->add('buttons', 'static', [
            'template' => 'crud.form_button'
        ]);
        $this->showFieldErrors = true;
    }
}
