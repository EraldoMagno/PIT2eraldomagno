<?php

namespace App\Http\Forms;

use Kris\LaravelFormBuilder\Form;

class TemplateForm extends Form
{
    public function buildForm()
    {
        $this->add('name', 'select', [
            'label' => trans('app.template'),
            'attr'=>['class'=>'form-control chosen', 'id'=>'template_select'],
            'choices' => ['invoice' => 'Invoice Template','estimate' => 'Estimate Template'],
            'wrapper' => ['class' => 'form-group col-sm-12'],
        ]);
        $this->add('subject', 'text', [
            'label' => trans('app.subject'),
            'attr'=>['class'=>'form-control','required'],
            'wrapper' => ['class' => 'form-group col-sm-12'],
        ]);
        $this->add('body', 'textarea', [
            'label' => trans('app.email_body'),
            'attr'=>['class'=>'form-control form-control-sm text_editor','rows'=>20, 'id'=>'email_template'],
            'wrapper' => ['class' => 'form-group col-sm-12']
        ]);
        $this->add('tags', 'static', [
            'template' => 'settings.partials.tags'
        ]);
        $this->add('buttons', 'static', [
            'template' => 'crud.form_button'
        ]);
    }
}
