<?php

namespace App\Http\Forms;

use Kris\LaravelFormBuilder\Form;
use App\Invoicer\Repositories\Contracts\ExpenseInterface as Expense;
use App\Invoicer\Repositories\Contracts\ExpenseCategoryInterface as Category;
use App\Invoicer\Repositories\Contracts\CurrencyInterface as Currency;
class ExpenseForm extends Form
{
    public function __construct(Expense $expense,Category $category,Currency $currency){
        $this->expense = $expense;
        $this->category = $category;
        $this->currency  = $currency;
    }
    public function buildForm()
    {
        $this->add('name', 'text', [
            'label' => trans('app.expense_name'),
            'attr'=>['class'=>'form-control','required'],
            'wrapper' => ['class' => 'form-group col-sm-12'],
        ]);
        $this->add('vendor', 'text', [
            'label' => trans('app.vendor'),
            'attr'=>['class'=>'form-control'],
            'wrapper' => ['class' => 'form-group col-sm-12'],
        ]);
        $this->add('expense_date', 'text', [
            'label' => trans('app.expense_date'),
            'attr'=>['class'=>'form-control datepicker','required'],
            'wrapper' => ['class' => 'form-group col-sm-12'],
        ]);
        $this->add('category_id', 'select', [
            'label' => trans('app.category'),
            'choices' => $this->category->categorySelect(),
            'attr'=>['class'=>'form-control chosen','required'],
            'wrapper' => ['class' => 'form-group col-sm-12'],
        ]);
        $this->add('currency', 'select', [
            'label' => trans('app.currency'),
            'choices' => $this->currency->currencySelect(),
            'attr'=>['class'=>'form-control chosen','required'],
            'wrapper' => ['class' => 'form-group col-sm-12'],
        ]);
        $this->add('amount', 'number', [
            'label' => trans('app.amount'),
            'attr'=>['class'=>'form-control','min'=>'0','step'=>'any', 'required'],
            'wrapper' => ['class' => 'form-group col-sm-12'],
        ]);
        $this->add('notes', 'textarea', [
            'label' => trans('app.notes'),
            'attr'=>['rows'=>3],
            'wrapper' => ['class' => 'form-group col-sm-12'],
        ]);
        $this->add('buttons', 'static', [
            'template' => 'crud.modal_form_buttons'
        ]);
    }
}
