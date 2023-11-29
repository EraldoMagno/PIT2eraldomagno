<?php

namespace App\Http\Forms;

use Kris\LaravelFormBuilder\Form;

class PaymentGatewayForm extends Form
{
    public function buildForm()
    {
        $this->add('paypal_settings_heading', 'static', [
            'label_show' => false,
            'tag' => 'h2',
            'attr' => ['class' => 'form-control-static card-title text-white'],
            'wrapper' => ['class' => 'form-group mb-1 card-header bg-info text-white p-2 mb-3'],
            'value' => '1. Paypal',
        ]);
        if($this->model['paypal_id']){
            $this->add('paypal_id','hidden',['value'=>$this->model['paypal_id']]);
        }
        $this->add('paypal_status', 'select', [
            'label' => trans('app.paypal_status'),
            'attr'=>['class'=>'form-control chosen'],
            'choices' => ['0' => trans('app.no'),  '1' => trans('app.yes')],
            'selected' => $this->model['paypal_details']['status'] ?? 0,
            'wrapper' => ['class' => 'form-group col-sm-12'],
        ]);
        $this->add('paypal_mode', 'select', [
            'label' => trans('app.mode'),
            'attr'=>['class'=>'form-control chosen'],
            'choices' => ['sandbox'=>'Sandbox','live'=>'Production'],
            'selected' => $this->model['paypal_details']['mode'] ?? null,
            'wrapper' => ['class' => 'form-group col-sm-12'],
        ]);
        $this->add('paypal_account', 'text', [
            'label' => trans('app.account'),
            'attr'=>['class'=>'form-control'],
            'value' => $this->model['paypal_details']['account'] ?? null,
            'wrapper' => ['class' => 'form-group col-sm-12']
        ]);
        $this->add('client_id', 'text', [
            'label' => trans('app.client_id'),
            'attr'=>['class'=>'form-control'],
            'value' => $this->model['paypal_details']['client_id'] ?? null,
            'wrapper' => ['class' => 'form-group col-sm-12']
        ]);
        $this->add('secret_key', 'text', [
            'label' => trans('app.secret_key'),
            'attr'=>['class'=>'form-control'],
            'value' => $this->model['paypal_details']['secret'] ?? null,
            'wrapper' => ['class' => 'form-group col-sm-12']
        ]);
        $this->add('stripe_settings_heading', 'static', [
            'label_show' => false,
            'tag' => 'h5',
            'attr' => ['class' => 'form-control-static card-title text-white'],
            'value' => '2. Stripe',
            'wrapper' => ['class' => 'form-group mb-1 card-header bg-secondary text-white p-2 mb-3'],
        ]);
        if($this->model['stripe_id']){
            $this->add('stripe_id','hidden',['value'=>$this->model['stripe_id']] );
        }
        $this->add('stripe_status', 'select', [
            'label' => trans('app.stripe_status'),
            'attr'=>['class'=>'form-control chosen'],
            'choices' => ['0' => trans('app.no'),  '1' => trans('app.yes')],
            'selected' => $this->model['stripe_details']['status'] ?? null,
            'wrapper' => ['class' => 'form-group col-sm-12'],
        ]);
        $this->add('stripe_key', 'text', [
            'label' => trans('app.stripe_key'),
            'attr'=>['class'=>'form-control'],
            'value' => $this->model['stripe_details']['key'] ?? null,
            'wrapper' => ['class' => 'form-group col-sm-12']
        ]);
        $this->add('stripe_secret', 'text', [
            'label' => trans('app.stripe_secret'),
            'attr'=>['class'=>'form-control'],
            'value' => $this->model['stripe_details']['secret'] ?? null,
            'wrapper' => ['class' => 'form-group col-sm-12']
        ]);
        $this->add('buttons', 'static', [
            'template' => 'crud.form_button'
        ]);
    }
}
