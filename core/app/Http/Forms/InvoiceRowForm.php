<?php

namespace App\Http\Forms;

use Kris\LaravelFormBuilder\Form;
use App\Invoicer\Repositories\Contracts\TaxSettingInterface as Tax;
class InvoiceRowForm extends Form
{
    protected $tax;
    public function __construct(Tax $tax){
        $this->tax       = $tax;
   }
    public function buildForm()
    {
        $taxes = $this->tax->taxSelect();
        $this->add('uuid', 'hidden', [
            'wrapper' => ['class' => 'form-group'],
            'label_show' => false,
            'attr' => ['class' => 'row_id']
        ]);
        $this->add('item_id', 'hidden', [
            'wrapper' => ['class' => 'form-group'],
            'label_show' => false,
            'attr' => ['class' => 'form-control row_product_id']
        ]);
        $this->add('item_name', 'text', [
            'wrapper' => ['class'=>'form-group'],
            'label_show' => false,
            'attr' => ['class' => 'form-control form-control-sm row_product_name','required']
        ]);
        $this->add('price', 'number', [
            'wrapper' => ['class'=>'form-group'],
            'label_show' => false,
            'attr' => ['class' => 'form-control form-control-sm row_price calc_event','required','step'=>".1",'min'=>"0.1"]
        ]);
        $this->add('quantity', 'number', [
            'wrapper' => ['class'=>'form-group'],
            'label_show' => false,
            'attr' => ['class' => 'form-control form-control-sm row_quantity calc_event','required','step'=>".1",'min'=>"0.1"]
        ]);
        $this->add('tax_id', 'custom_select', [
            'choices' => $taxes['options'],
            'selected' => $this->model->tax_id ?? $taxes['default'],
            'label_show' => false,
            'empty_value' => trans('core.form.none'),
            'wrapper' => ['class' => 'form-group'],
            'attr'=>['class'=>'form-control form-control-sm row_tax calc_event'],
        ]);
        $this->add('itemTotal', 'static', [
            'wrapper' => ['class' => 'form-group line-total-element'],
            'label_show' => false,
            'attr' => ['class' => 'row_line_total']
        ]);
    }
}
