<?php

namespace App\Http\Forms\ClientArea;

use Kris\LaravelFormBuilder\Form;

class ProfileForm extends Form
{
    public function buildForm()
    {
        $this->add('client_no', 'text', [
            'label' => trans('app.client_no'),
            'attr'=>['required','readonly'],
            'wrapper' => ['class' => 'form-group col-sm-6'],
        ]);
        $this->add('name', 'text', [
            'label' => trans('app.name'),
            'attr'=>['required'],
            'wrapper' => ['class' => 'form-group col-sm-6'],
        ]);
        $this->add('email', 'text', [
            'label' => trans('app.email'),
            'attr'=>['required'],
            'wrapper' => ['class' => 'form-group col-sm-6'],
        ]);
        $this->add('phone', 'text', [
            'label' => trans('app.phone'),
            'wrapper' => ['class' => 'form-group col-sm-6'],
        ]);
        $this->add('mobile', 'text', [
            'label' => trans('app.mobile'),
            'wrapper' => ['class' => 'form-group col-sm-6'],
        ]);
        $this->add('address1', 'text', [
            'label' => trans('app.address_1'),
            'wrapper' => ['class' => 'form-group col-sm-6'],
        ]);
        $this->add('address2', 'text', [
            'label' => trans('app.address_2'),
            'wrapper' => ['class' => 'form-group col-sm-6'],
        ]);
        $this->add('city', 'text', [
            'label' => trans('app.city'),
            'wrapper' => ['class' => 'form-group col-sm-6'],
        ]);
        $this->add('state', 'text', [
            'label' => trans('app.state'),
            'wrapper' => ['class' => 'form-group col-sm-6'],
        ]);
        $this->add('country', 'text', [
            'label' => trans('app.country'),
            'wrapper' => ['class' => 'form-group col-sm-6'],
        ]);
        $this->add('postal_code', 'text', [
            'label' => trans('app.postal_code'),
            'wrapper' => ['class' => 'form-group col-sm-6'],
        ]);
        $this->add('website', 'text', [
            'label' => trans('app.website'),
            'wrapper' => ['class' => 'form-group col-sm-6'],
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
                'attr' => ['class' => 'form-control-static thumbnail', 'src' => asset($this->model->photo != '' ? image_url('uploads/client_images/'.$this->model->photo) : image_url('uploads/no-image.jpg'))],
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
            'attr'=>['autocomplete'=>'off','class'=>'form-control'],
            'first_options'=>['value'=>null,'label'=>trans('app.password').trans('app.password_leave_blank_notification')],
            'second_options'=>['value'=>null,'label'=>trans('app.confirm_password')],
            'wrapper' => ['class' => 'form-group col-sm-12']
        ]);
        $this->add('buttons', 'static', [
            'template' => 'crud.form_button'
        ]);
    }
}
