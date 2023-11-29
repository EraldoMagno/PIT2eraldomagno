<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>{{get_company_name()}} | {{trans('app.login')}}</title>
    <link rel="icon" type="image/png" sizes="16x16" href="{{image_url('favicon.png')}}">
    {!! Html::style(asset('assets/css/bootstrap.min.css')) !!}
    {!! Html::style(asset('assets/css/theme.min.css')) !!}
    {!! Html::style(asset('assets/plugins/amaranjs/css/amaran.min.css')) !!}
    {!! Html::style(asset('assets/css/style.css')) !!}
	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]--> 
</head>
<body class="login-page">
    <div class="login-box">
        <div class="login-logo">
            {{get_company_name()}}
        </div>
        <div class="login-box-body bg-white p-4">
            @yield('content')
        </div>
        <section class="panel-footer">
            <a href="{{ url('password/reset') }}">@lang('app.lost_password')</a>
        </section>
    </div>
{!! Html::script(asset('assets/js/jquery.min.js')) !!}
{!! Html::script(asset('assets/js/bootstrap.min.js')) !!}
{!! Html::script(asset('assets/js/validator.min.js')) !!}
{!! Html::script(asset('assets/plugins/amaranjs/js/jquery.amaran.min.js')) !!}
{!! Html::script(asset('assets/plugins/backstretch-js/jquery.backstretch.min.js')) !!}
@php
$bg = get_setting_value('login_bg') != '' ? image_url(get_setting_value('login_bg')) : image_url('bg.jpg');
@endphp
<script>
    $(function(){
        $('form').validator();
        $.backstretch("{{$bg}}");
    });
</script>
@if (session()->has('flash_notification'))
    <?php
        $notification = session()->pull('flash_notification')[0];
        $message_type = $notification->level;
    ?>
    @if($message_type == 'success')
        <script>
            $.amaran({
                'theme'     :'awesome ok',
                'content'   :{
                    title:'Success !',
                    message:'{{$notification->message}}!',
                    info:'',
                    icon:'fa fa-check-square-o'
                },
                'position'  :'bottom right',
                'outEffect' :'slideBottom'
            });
        </script>
    @elseif($message_type == 'danger')
        <script>
            $.amaran({
                'theme'     :'awesome error',
                'content'   :{
                    title:'Error !',
                    message:'{{$notification->message}}!',
                    info:'',
                    icon:'fa fa-times-circle-o'
                },
                'position'  :'bottom right',
                'outEffect' :'slideBottom'
            });
        </script>
    @endif
@endif
</body>
</html>
