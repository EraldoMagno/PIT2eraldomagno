@extends('default')
@section('content')
    @if (isset($errors) && count($errors) > 0)
        {!! display_form_errors($errors) !!}
    @endif
    
    <section class="login-form">
        {!! Form::open(['url' => '/login']) !!}
        <div class="form-group">
            {!! Form::label('email', trans('app.email_or_username')) !!}
            {!! Form::input('text','login', null, ['class'  =>"form-control", 'required'=>'required', 'placeholder'=>"Email or username"]) !!}
        </div>
        <div class="form-group">
            {!! Form::label('password', trans('app.password')) !!}
            {!! Form::password('password', ['class'=>"form-control", 'placeholder'=>"password", 'required']) !!}
        </div>
        <div class="form-group">
            {!! Form::Submit('Login', ['class'=>"btn btn-primary login-button btn-sm form-control"]) !!}
        </div>
        <a href="{{ url('clientarea/login') }}" class="btn btn-success btn-sm pull-right" target="_blank">@lang('app.access_client_area')</a>
        <div class="clearfix"></div>
        {!! Form::close() !!}
    </section>
@endsection