<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Classic Invoicer - System Check Requirements</title>
    @include('install.partials.styles')
</head>
<body class="login-page">
<div class="container">
    <div class="login-logo">
        <b>Classic</b> Invoicer Installation
    </div><!-- /.login-logo -->
    <div class="row">
        <div class="col-md-12">
            <!-- TABLE: LATEST ORDERS -->
            <div class="card">
                <div class="card-body">
                    <div id="system_details">
                        <div class="alert alert-warning"> Minimum System Requirements</div>
                        <?php $flag= true; ?>
                        <ul style="padding-left: 0">
                            @foreach(install_minimum_requirements() as $requirement)
                                <li>
                                    @if($requirement['check'])
                                        <i class="fa fa-check text-success"></i> {{ $requirement['success'] }}
                                    @else
                                        <?php $flag = false; ?>
                                        <i class="fa fa-remove text-danger"></i> {{ $requirement['error'] }}
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="card-footer clearfix hide" id="card-footer">
                    @if($flag == false)
                        {!!  Form::submit('Correct errors and try again', ['class' => 'btn-flat btn btn-danger', 'onClick'=>"location.reload(true)"]) !!}
                    @else
                        <a href="{{url('install/database')}}" class="btn btn-sm btn-success next_btn"> Let's Begin ! </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@include('install.partials.scripts')
</body>
</html>

