<?php

namespace App\Http\Forms;

use Kris\LaravelFormBuilder\Form;
use App\Invoicer\Repositories\Contracts\ProductCategoryInterface as Category;
class ProductForm extends Form
{
    public function __construct(Category $category){
        $this->category = $category;
    }
    public function buildForm()
    {
        $this->add('name', 'text', [
            'label' => trans('app.name'),
            'attr'=>['class'=>'form-control','required'],
            'wrapper' => ['class' => 'form-group col-sm-12'],
        ]);
        $this->add('code', 'text', [
            'label' => trans('app.code'),
            'attr'=>['class'=>'form-control','required'],
            'wrapper' => ['class' => 'form-group col-sm-12'],
        ]);
        $this->add('category_id', 'select', [
            'label' => trans('app.category'),
            'choices' => $this->category->categorySelect(),
            'attr'=>['class'=>'form-control chosen','required'],
            'wrapper' => ['class' => 'form-group col-sm-12'],
        ]);
        $this->add('price', 'number', [
            'label' => trans('app.unit_price'),
            'attr'=>['class'=>'form-control','min'=>'0','step'=>'any', 'required'],
            'wrapper' => ['class' => 'form-group col-sm-12'],
        ]);
        $this->add('image_label', 'static', [
            'label_show' => false,
            'tag' => 'label',
            'value' => 'Product Image ('.trans('app.width').': 200)',
            'wrapper' => ['class' => 'form-group col-sm-12 mb-1'],
        ]);
        if($this->model && $this->model->image !='') {
            $this->add(
                'photo_preview',
                'static', [
                    'tag' => 'img',
                    'attr' => ['class' => 'form-control-static', 'src' => image_url('uploads/product_images/'.$this->model->image),'width'=>'100px'],
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
        $this->add('description', 'textarea', [
            'label' => trans('app.product_description'),
            'attr'=>['rows'=>3],
            'wrapper' => ['class' => 'form-group col-sm-12'],
        ]);
        $this->add('buttons', 'static', [
            'template' => 'crud.modal_form_buttons'
        ]);
    }
}
