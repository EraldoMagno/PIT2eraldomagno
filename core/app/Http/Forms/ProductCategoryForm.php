<?php

namespace App\Http\Forms;

use Kris\LaravelFormBuilder\Form;

class ProductCategoryForm extends Form
{
    public function buildForm()
    {
        $this->add('name', 'text', [
            'label' => trans('app.name'),
            'attr'=>['class'=>'form-control','required'],
            'wrapper' => ['class' => 'form-group col-sm-12'],
        ]);
        $this->add('buttons', 'static', [
            'template' => 'crud.modal_form_buttons'
        ]);
    }
}
