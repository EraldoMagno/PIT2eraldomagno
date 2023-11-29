<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{get_company_name()}}</title>
    <link rel="icon" type="image/png" sizes="16x16" href="{{image_url('favicon.png')}}">
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('partials.styles')
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
</head>
<body class="skin-blue layout-navbar-fixed control-sidebar-slide-open layout-fixed">
<div class="wrapper animsition">
<header class="main-header">
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link sidebar-toggle" data-widget="pushmenu" href="#" role="button">
                    <i class="fa fa-bars"></i>
                </a>
            </li>
        </ul>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
                <a href="#" class="nav-link text-uppercase text-white" data-toggle="dropdown">
                    <img src="{{image_url('flags/'.current_language()['flag']) }}" class="language-img">
                    {{ current_language()['lang']->locale_name ?? null }} <b class="caret"></b>
                </a>
                <div class="dropdown-menu">
                    <?php $languages = get_languages(); ?>
                    @foreach($languages as $language)
                            <?php $flag = $language->flag != '' ? $language->flag : 'placeholder_Flag.jpg'; ?>
                            <a class="dropdown-item" rel="alternate" href="{{ route('client_lang_switch', $language->short_name) }}">
                                <img src="{{image_url('flags/'.$flag) }}" class="language-img">{{ $language->locale_name }}
                            </a>
                    @endforeach
                </div>
            </li>
            <li class="nav-item dropdown user user-menu">
                <a href="#" class="nav-link text-uppercase text-white" data-toggle="dropdown">
                    @if(auth()->guard('user')->check())
                    <img src="{{ Auth::guard('user')->user()->photo != '' ? image_url('uploads/client_images/'.Auth::guard('user')->user()->photo) : image_url('uploads/defaultavatar.png') }}" class="user-image" alt="User Image"/>
                    <span class="hidden-xs"> {{  auth()->guard('user')->user()->name }} </span>
                    @endif
                    <b class="caret"></b>
                </a>
                <ul class="dropdown-menu">
                    <!-- User image -->
                    <li class="user-header text-uppercase text-white">
                        @if(auth()->guard('user')->check())
                        <img src="{{Auth::guard('user')->user()->photo != '' ? image_url('uploads/client_images/'.Auth::guard('user')->user()->photo) : image_url('uploads/defaultavatar.png') }}" class="img-circle" alt="User Image" />
                        <p>{{  auth()->guard('user')->user()->name }} </p>
                        @endif
                    </li>
                    <!-- Menu Footer-->
                    <li class="user-footer">
                        <div class="pull-left">
                            <a href="{{ url('clientarea/cprofile') }}" class="btn btn-primary btn-sm btn-flat">{{trans('app.edit_profile')}}</a>
                        </div>
                        <div class="pull-right">
                            <a href="{{ route('client_logout') }}" class="btn btn-danger btn-sm btn-flat">{{trans('app.logout')}}</a>
                        </div>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
</header>
<!-- Left side column. contains the logo and sidebar -->
@include('clientarea.nav')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
<!-- Content Header (Page header) -->
@yield('content')
</div><!-- /.content-wrapper -->
</div><!-- ./wrapper -->
<div id="ajax-modal" class="modal fade" id="staticBackdrop" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog" role="document"></div>
</div>
@include('partials.scripts')
@yield('scripts')
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