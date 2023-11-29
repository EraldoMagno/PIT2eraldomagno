<?php

namespace App\Http\Forms;

use Kris\LaravelFormBuilder\Form;

class SettingForm extends Form
{
    public function buildForm()
    {
        $this->add('name', 'text', [
            'label' => trans('app.name'),
            'attr'=>['class'=>'form-control form-control-sm','required'],
            'wrapper' => ['class' => 'form-group col-sm-12'],
        ]);
        $this->add('email', 'email', [
            'label' => trans('app.email'),
            'attr'=>['class'=>'form-control form-control-sm','required'],
            'wrapper' => ['class' => 'form-group col-sm-12'],
        ]);
        $this->add('contact', 'text', [
            'label' => trans('app.contact_person'),
            'attr'=>['class'=>'form-control form-control-sm','required'],
            'wrapper' => ['class' => 'form-group col-sm-12'],
        ]);
        $this->add('phone', 'text', [
            'label' => trans('app.phone'),
            'attr'=>['class'=>'form-control form-control-sm','required'],
            'wrapper' => ['class' => 'form-group col-sm-12'],
        ]);
        $this->add('city', 'text', [
            'label' => trans('app.city'),
            'attr'=>['class'=>'form-control form-control-sm','required'],
            'wrapper' => ['class' => 'form-group col-sm-12'],
        ]);
        $this->add('state', 'text', [
            'label' => trans('app.state'),
            'attr'=>['class'=>'form-control form-control-sm','required'],
            'wrapper' => ['class' => 'form-group col-sm-12'],
        ]);
        $this->add('country', 'text', [
            'label' => trans('app.country'),
            'attr'=>['class'=>'form-control form-control-sm','required'],
            'wrapper' => ['class' => 'form-group col-sm-12'],
        ]);
        $this->add('address1', 'text', [
            'label' => trans('app.address_1'),
            'attr'=>['class'=>'form-control form-control-sm','required'],
            'wrapper' => ['class' => 'form-group col-sm-12'],
        ]);
        $this->add('address2', 'text', [
            'label' => trans('app.address_2'),
            'attr'=>['class'=>'form-control form-control-sm'],
            'wrapper' => ['class' => 'form-group col-sm-12'],
        ]);
        $this->add('postal_code', 'text', [
            'label' => trans('app.postal_code'),
            'attr'=>['class'=>'form-control form-control-sm'],
            'wrapper' => ['class' => 'form-group col-sm-12'],
        ]);
        $this->add('vat', 'text', [
            'label' => trans('app.vat_number'),
            'attr'=>['class'=>'form-control form-control-sm'],
            'wrapper' => ['class' => 'form-group col-sm-12'],
        ]);
        $this->add('website', 'text', [
            'label' => trans('app.website'),
            'attr'=>['class'=>'form-control form-control-sm'],
            'wrapper' => ['class' => 'form-group col-sm-12'],
        ]);
        $this->add('image_label', 'static', [
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
            'label' => trans('app.no_file_added')
        ]);
        $this->add('favicon_label', 'static', [
            'label_show' => false,
            'tag' => 'label',
            'value' => trans('app.favicon'),
            'wrapper' => ['class' => 'form-group col-sm-12 mb-1'],
        ]);
        if($this->model && $this->model->favicon != ''){
            $this->add(
                'favicon_preview',
                'static', [
                    'tag' => 'img',
                    'attr' => ['class' => 'form-control-static thumbnail', 'src' => asset(image_url($this->model->favicon))],
                    'label_show' => false,
                ]
            );
        }
        $this->add('favicon', 'file', [
            'label' => 'No file added',
            'label_attr'=>['class'=>'custom-file-label'],
            'attr'=>['class'=>'custom-file-input','accept'=>"image/*",'onchange'=>"$(this).parents('.custom-file').find('.custom-file-label').html($(this).val());"],
            'wrapper' => ['class' => 'custom-file col-sm-12 mb-3'],
            'label' => trans('app.no_file_added')
        ]);
        $this->add('login_background_image_label', 'static', [
            'label_show' => false,
            'tag' => 'label',
            'value' => trans('app.login_background_image'),
            'wrapper' => ['class' => 'form-group mb-1'],
        ]);
        if($this->model->login_bg) {
            $this->add(
                'login_bg_preview',
                'static', [
                    'tag' => 'img',
                    'attr' => ['class' => 'form-control-static img-thumbnail', 'src' => asset(image_url($this->model->login_bg)),'alt'=>$this->model->login_bg,'width'=>'100px'],
                    'label_show' => false,
                ]
            );
        }
        $this->add('login_bg', 'file', [
            'label_attr'=>['class'=>'custom-file-label'],
            'attr'=>['class'=>'custom-file-input','onchange'=>"$(this).parents('.custom-file').find('.custom-file-label').html($(this).val());"],
            'wrapper' => ['class' => 'custom-file mb-3'],
            'label' => trans('app.no_file_added')
        ]);
        $this->add('date_format', 'select', [
            'label' => trans('app.date_format'),
            'attr'=>['class'=>'form-control chosen form-control-sm','required'],
            'choices'=>[
                'd/m/Y' => date('d/m/Y'),
                'm/d/Y' => date('m/d/Y'),
                'Y/m/d' => date('Y/m/d'),
                'F j, Y' => date('F j, Y'),
                'm.d.y' => date('m.d.Y'),
                'd-m-Y' => date('d-m-Y'),
                'D M j Y' => date('D M j Y')
            ],
            'wrapper' => ['class' => 'form-group col-sm-12'],
        ]);
        $this->add('thousand_separator', 'select', [
            'label' => trans('app.amount_thousand_separator'),
            'attr'=>['class'=>'form-control chosen','required'],
            'choices' => [',' => '1,000 - Comma Separator','.' => '1.000 - Dot Separator'],
            'wrapper' => ['class' => 'form-group col-sm-12']
        ]);
        $this->add('decimal_separator', 'select', [
            'label' => trans('app.amount_decimal_separator'),
            'attr'=>['class'=>'form-control chosen'],
            'choices' => ['.' => '1000.00 - Dot',',' => '1000,00 - Comma'],
            'wrapper' => ['class' => 'form-group col-sm-12'],
        ]);
        $this->add('decimals', 'select', [
            'label' => trans('app.amount_decimals'),
            'attr'=>['class'=>'form-control chosen'],
            'choices' => ['1' => '1','2' => '2','3' => '3','4' => '4','5' => '5'],
            'wrapper' => ['class' => 'form-group col-sm-12'],
        ]);
        $this->add('buttons', 'static', [
            'template' => 'crud.form_button'
        ]);
    }
}
