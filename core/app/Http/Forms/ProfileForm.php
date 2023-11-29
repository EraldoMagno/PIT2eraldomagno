<?php

namespace App\Http\Forms;

use Kris\LaravelFormBuilder\Form;

class ProfileForm extends Form
{
    public function buildForm()
    {
        $this->add('username', 'text', [
            'label' => trans('app.username'),
            'attr'=>['required'],
            'wrapper' => ['class' => 'form-group col-sm-12'],
        ]);
        $this->add('name', 'text', [
            'label' => trans('app.name'),
            'attr'=>['required'],
            'wrapper' => ['class' => 'form-group col-sm-12'],
        ]);
        $this->add('email', 'text', [
            'label' => trans('app.email'),
            'attr'=>['required'],
            'wrapper' => ['class' => 'form-group col-sm-12'],
        ]);
        $this->add('phone', 'text', [
            'label' => trans('app.phone'),
            'wrapper' => ['class' => 'form-group col-sm-12'],
        ]);
        $this->add('image_label', 'static', [
            'label_show' => false,
            'tag' => 'label',
            'value' => trans('app.photo'),
            'wrapper' => ['class' => 'form-group col-sm-12 mb-1'],
        ]);
        $this->add(
            'photo_preview',
            'static', [
                'tag' => 'img',
                'attr' => ['class' => 'form-control-static thumbnail', 'src' => asset($this->model->photo != '' ? image_url('uploads/'.$this->model->photo) : image_url('uploads/no-image.jpg'))],
                'label_show' => false,
            ]
        );
        $this->add('photo', 'file', [
            'label' => 'No file added',
            'label_attr'=>['class'=>'custom-file-label'],
            'attr'=>['class'=>'custom-file-input','accept'=>"image/*",'onchange'=>"$(this).parents('.custom-file').find('.custom-file-label').html($(this).val());"],
            'wrapper' => ['class' => 'custom-file col-sm-12 mb-3'],
        ]);
        $this->add('password', 'repeated', [
            'type' => 'password',
            'first_name' => 'password',
            'second_name' => 'password_confirmation',
            'attr'=>['autocomplete'=>'new-password','class'=>'form-control'],
            'first_options'=>['value'=>null,'label'=>trans('app.password').trans('app.password_leave_blank_notification')],
            'second_options'=>['value'=>null,'label'=>trans('app.confirm_password')],
            'wrapper' => ['class' => 'form-group col-sm-12']
        ]);
        $this->add('buttons', 'static', [
            'template' => 'crud.form_button'
        ]);
    }
}
