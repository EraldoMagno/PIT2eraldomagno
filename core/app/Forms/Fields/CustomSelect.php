<?php
namespace App\Forms\Fields;

use Kris\LaravelFormBuilder\Fields\FormField;
class CustomSelect extends FormField
{
    protected function getTemplate()
    {
        return 'vendor.laravel-form-builder.custom_select';
    }


    /**
     * @inheritdoc
     */
    public function getDefaults()
    {
        return [
            'attr' => ['class' => 'chosen-select', 'id' => $this->getName()],
        ];
    }
}
