<?php

namespace App\Http\Forms;

use Kris\LaravelFormBuilder\Form;

class RoleForm extends Form
{
    public function buildForm()
    {
        $this->add('name', 'text', [
            'label' => trans('app.name'),
            'attr'=>['class'=>'form-control','required'],
            'wrapper' => ['class' => 'form-group col-sm-12'],
        ]);
        $this->add('description', 'textarea', [
            'label' => trans('app.description'),
            'attr'=>['rows'=>3],
            'wrapper' => ['class' => 'form-group col-sm-12'],
        ]);
        $this->add('buttons', 'static', [
            'template' => 'crud.modal_form_buttons'
        ]);
    }
}
