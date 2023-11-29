<div class="form-group">
    {!! Form::label('due_days',trans('app.days')) !!}
    <div class="input-group mb-3">
        {!! Form::text('due_days',$options['value'],['class'=>'form-control']) !!}
        <div class="input-group-append">
            <span class="input-group-text" id="basic-addon2">@lang('app.days')</span>
        </div>
    </div>
</div>

  