<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão de Usuarios - Database Details</title>
    @include('install.partials.styles')
</head>
<body class="login-page">
	<div class="container">
		<div class="login-logo">
			<b>Classic</b> Invoicer Installation
		</div>
		<div class="card">
			<div class="card-body">
				<div class="row">
					<div class="col-md-12">
						<div class="alert alert-warning">Database Details</div>
						@if (count($errors) > 0)
							{!! display_form_errors($errors) !!}
						@endif
						@if (Session::has('flash_notification'))
							{!! message() !!}
						@endif
						{!! Form::open(['url'=>'/install/database']) !!}
						<div class="form-group">
							{!! Form::label('hostname', 'Hostname') !!}
							{!! Form::text('hostname', null, ['class' => 'form-control input-sm','required', 'placeholder' => 'hostname' ]) !!}
						</div>
						<div class="form-group">
							{!! Form::label('database', 'Database') !!}
							{!! Form::text('database', null, ['class' => 'form-control input-sm','required','placeholder' => 'Database' ]) !!}
						</div>
						<div class="form-group">
							{!! Form::label('username', 'Username') !!}
							{!! Form::text('username', null, ['class' => 'form-control input-sm','required','placeholder' => 'Username' ]) !!}
						</div>
						<div class="form-group">
							{!! Form::label('password', 'Password') !!}
							{!! Form::password('password', ['class' => 'form-control input-sm','placeholder' => 'Password' ]) !!}
						</div>
						<div class="form-group">
							{!! Form::submit('Submit', ['class' => 'btn btn-sm btn-success next_btn']) !!}
						</div>
						{!! Form::close() !!}
					</div>
				</div>
			</div>
		</div>
</div>
@include('install.partials.scripts')
</body>
</html>

