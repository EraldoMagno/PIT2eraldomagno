@extends('clientarea.default')
@section('content')
@if (session('status'))
<div class="alert alert-success">
    {{ session('status') }}
</div>
@endif
@if (count($errors) > 0)
    {!! display_form_errors($errors) !!}
@endif
<section class="login-form">
    {!! Form::open(['url' => '/clientarea/password/email']) !!}
    <div class="form-group">
        {!! Form::label('email', trans('app.email')) !!}
        {!! Form::input('email','email', null, ['class'=>"form-control",'required', 'placeholder'=>"email"]) !!}
    </div>
    <div class="form-group">
        {!! Form::Submit(trans('app.reset_password'), ['class'=>"btn btn-primary form-control"]) !!}
    </div>
    {!! Form::close() !!}

    <div class="form-group">
        <a href="{{ route('client_login') }}" class="pull-right">{{trans('app.go_to_login')}}</a>
        <div class="clearfix"></div>
    </div>
</section>
@endsection
