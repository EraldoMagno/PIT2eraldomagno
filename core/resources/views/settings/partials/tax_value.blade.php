<div class="form-group col-sm-12">
    {!! Form::label('value',trans('app.tax_value')) !!}
    <div class="input-group mb-3">
        {!! Form::number('value',$options['value'],['class'=>'form-control','step'=>'any', 'min'=>'0', 'required']) !!}
        <div class="input-group-append">
            <span class="input-group-text" id="basic-addon2">%</span>
        </div>
    </div>
</div>

  