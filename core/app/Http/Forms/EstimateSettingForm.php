<?php

namespace App\Http\Forms;

use Kris\LaravelFormBuilder\Form;

class EstimateSettingForm extends Form
{
    public function buildForm()
    {
        $this->add('start_number', 'text', [
            'label' => trans('app.number_invoice_starting'),
            'attr'=>['class'=>'form-control form-control-sm','required'],
            'wrapper' => ['class' => 'form-group col-sm-12']
        ]);
        $this->add('terms', 'textarea', [
            'label' => trans('app.invoice_terms'),
            'attr'=>['class'=>'form-control form-control-sm text_editor','rows'=>7, 'id'=>'invoice_terms'],
            'wrapper' => ['class' => 'form-group col-sm-12']
        ]);
        $this->add('logo_label', 'static', [
            'label_show' => false,
            'tag' => 'label',
            'value' => trans('app.logo'),
            'wrapper' => ['class' => 'form-group col-sm-12 mb-1'],
        ]);
        if($this->model && $this->model->logo != ''){
            $this->add(
                'logo_preview',
                'static', [
                    'tag' => 'img',
                    'attr' => ['class' => 'form-control-static thumbnail', 'src' => asset(image_url($this->model->logo))],
                    'label_show' => false,
                ]
            );
        }
        $this->add('logo', 'file', [
            'label' => 'No file added',
            'label_attr'=>['class'=>'custom-file-label'],
            'attr'=>['class'=>'custom-file-input','accept'=>"image/*",'onchange'=>"$(this).parents('.custom-file').find('.custom-file-label').html($(this).val());"],
            'wrapper' => ['class' => 'custom-file col-sm-12 mb-3'],
        ]);
        $this->add('buttons', 'static', [
            'template' => 'crud.form_button'
        ]);
    }
}
