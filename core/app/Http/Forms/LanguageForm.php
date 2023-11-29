<?php

namespace App\Http\Forms;

use Kris\LaravelFormBuilder\Form;

class LanguageForm extends Form
{
    public function buildForm()
    {
        $this->add('locale_name', 'text', [
            'label' => trans('app.name'),
            'attr'=>['class'=>'form-control form-control-sm', 'required'],
            'wrapper' => ['class' => 'form-group col-sm-12']
        ]);
        $this->add('short_name', 'text', [
            'label' => trans('app.short_name'),
            'attr'=>['class'=>'form-control form-control-sm', 'required'],
            'wrapper' => ['class' => 'form-group col-sm-12']
        ]);
        $this->add('status', 'select', [
            'label' => trans('app.status'),
            'attr'=>['class'=>'form-control chosen'],
            'choices' => ['0'=>trans('app.no'),  '1' => trans('app.yes')],
            'wrapper' => ['class' => 'form-group col-sm-12'],
        ]);
        $this->add('default', 'select', [
            'label' => trans('app.default'),
            'attr'=>['class'=>'form-control chosen'],
            'choices' => ['0'=>trans('app.no'),  '1' => trans('app.yes')],
            'wrapper' => ['class' => 'form-group col-sm-12'],
        ]);
        if($this->model && $this->model->flag != ''){
            $this->add(
                'flag_preview',
                'static', [
                    'tag' => 'img',
                    'attr' => ['class' => 'form-control form-control-static thumbnail', 'src' => asset('assets/images/flags/'.$this->model->flag)],
                    'label_show' => false,
                ]
            );
        }
        $this->add('flag_label', 'static', [
            'label_show' => false,
            'tag' => 'label',
            'value' => trans('app.flag'),
            'wrapper' => ['class' => 'form-group col-sm-12 mb-1'],
        ]);
        $this->add('flag', 'file', [
            'label' => 'No file added',
            'label_attr'=>['class'=>'custom-file-label'],
            'attr'=>['class'=>'custom-file-input','accept'=>"image/*",'onchange'=>"$(this).parents('.custom-file').find('.custom-file-label').html($(this).val());"],
            'wrapper' => ['class' => 'custom-file col-sm-12 mb-3'],
        ]);
        $this->add('buttons', 'static', [
            'template' => 'crud.modal_form_buttons'
        ]);
    }
}
