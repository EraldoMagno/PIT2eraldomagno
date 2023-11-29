<?php

namespace App\Http\Forms;

use Kris\LaravelFormBuilder\Form;

class ClientForm extends Form
{
    public function buildForm()
    {
        $this->add('client_no', 'text', [
            'label' => trans('app.client_no'),
            'attr'=>['required'],
            'wrapper' => ['class' => 'form-group col-sm-6'],
        ]);
        $this->add('name', 'text', [
            'label' => trans('app.name'),
            'attr'=>['required'],
            'wrapper' => ['class' => 'form-group col-sm-6'],
        ]);
        $this->add('email', 'email', [
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
            'attr'=>['required'],
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
        $this->add('postal_code', 'text', [
            'label' => trans('app.postal_code'),
            'wrapper' => ['class' => 'form-group col-sm-6'],
        ]);
        $this->add('country', 'text', [
            'label' => trans('app.country'),
            'wrapper' => ['class' => 'form-group col-sm-6'],
        ]);
        $this->add('website', 'text', [
            'label' => trans('app.website'),
            'wrapper' => ['class' => 'form-group col-sm-6'],
        ]);
        $this->add('notes', 'textarea', [
            'label' => trans('app.notes'),
            'attr'=>['rows'=>3],
            'wrapper' => ['class' => 'form-group col-sm-12'],
        ]);
        $this->add('password', 'repeated', [
            'type' => 'password',
            'first_name' => 'password',
            'second_name' => 'password_confirmation',
            'attr'=>['autocomplete'=>'new-password','class'=>'form-control'],
            'first_options'=>['value'=>null,'label'=>trans('app.password')],
            'second_options'=>['value'=>null,'label'=>trans('app.confirm_password')],
            'wrapper' => ['class' => 'form-group col-sm-12']
        ]);
        $this->add('buttons', 'static', [
            'template' => 'crud.modal_form_buttons'
        ]);
    }
}
