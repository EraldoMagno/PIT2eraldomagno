<?php

namespace App\Http\Forms;

use Kris\LaravelFormBuilder\Form;
use App\Invoicer\Repositories\Contracts\RoleInterface as Role;
class UserForm extends Form
{
    public function __construct(Role $role){
        $this->role = $role;
    }
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
        $this->add('role_id', 'select', [
            'label' => trans('app.role'),
            'choices' => $this->role->all()->pluck('name','uuid')->toArray(),
            'attr'=>['class'=>'form-control chosen','required'],
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
