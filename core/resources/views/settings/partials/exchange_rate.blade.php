<div class="form-group col-sm-12">
    {!! Form::label('exchange_rate',trans('app.exchange_rate')) !!}
    <div class="input-group mb-3">
        <div class="input-group-append">
            <span class="input-group-text" id="basic-addon2">1 USD = </span>
        </div>
        {!! Form::text('exchange_rate',$options['value'],['class'=>'form-control','step' => 'any', 'min' => '0']) !!}
    </div>
</div>

  