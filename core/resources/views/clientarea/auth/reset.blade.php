@extends('clientarea.default')
@section('content')
    @if (count($errors) > 0)
        {!! display_form_errors($errors) !!}
    @endif
    {!! Form::open(['url' => 'clientarea/password/reset']) !!}
        {!! Form::hidden('token', $token) !!}
        <div class="form-group">
            {!! Form::label('email', trans('app.email')) !!}
            {!! Form::input('email','email', old('email'), ['class'=>"form-control",'required','placeholder'=>"email"]) !!}
        </div>
        <div class="form-group">
            {!! Form::label('password', trans('app.password')) !!}
            {!! Form::password('password', ['class'=>"form-control",'required','placeholder'=>"password"]) !!}
        </div>
        <div class="form-group">
            {!! Form::label('password_confirmation', trans('app.confirm_password')) !!}
            {!! Form::password('password_confirmation', ['class'=>"form-control", 'placeholder'=>"Confirm Password"]) !!}
        </div>
        <div class="form-group">
            {!! Form::Submit(trans('app.reset_password'), ['class'=>"btn btn-primary form-control"]) !!}
        </div>
    {!! Form::close() !!}
@endsection
