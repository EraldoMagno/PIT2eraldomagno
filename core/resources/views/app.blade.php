<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{get_company_name()}}</title>
    <link rel="icon" type="image/png" sizes="16x16" href="{{image_url('favicon.png') }}">
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
                                <a class="dropdown-item" rel="alternate" href="{{ route('admin_lang_switch', $language->short_name) }}">
                                    <img src="{{image_url('flags/'.$flag) }}" class="language-img">{{ $language->locale_name }}
                                </a>
                        @endforeach
                    </div>
                </li>
                <li class="nav-item dropdown user user-menu">
                    <a href="#" class="nav-link text-uppercase text-white" data-toggle="dropdown">
                        @if(auth()->guard('admin')->check())
                        <img src="{{ Auth::guard('admin')->user()->photo != '' ? image_url('uploads/'.Auth::guard('admin')->user()->photo) : image_url('uploads/defaultavatar.png') }}" class="user-image" alt="User Image"/>
                        <span class="hidden-xs"> {{  auth()->guard('admin')->user()->name }} </span>
                        @endif
                        <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header text-uppercase text-white">
                            @if(auth()->guard('admin')->check())
                            <img src="{{Auth::guard('admin')->user()->photo != '' ? image_url('uploads/'.Auth::guard('admin')->user()->photo) : image_url('uploads/defaultavatar.png') }}" class="img-circle" alt="User Image" />
                            <p>{{  auth()->guard('admin')->user()->name }} </p>
                            @endif
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="{{ url('profile') }}" class="btn btn-primary btn-sm btn-flat">{{trans('app.edit_profile')}}</a>
                            </div>
                            <div class="pull-right">
                                <a href="{{ route('admin_logout') }}" class="btn btn-danger btn-sm btn-flat">{{trans('app.logout')}}</a>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
</header>
@include('nav')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    @yield('content') 
</div>
<div id="ajax-modal" class="modal fade" id="staticBackdrop" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog" role="document"></div>
</div>
    @if(!is_verified())
    <div id="activation-modal" class="modal fade" role="dialog" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title"><i class="fa fa-lock"></i> Verification of the license</h6>
                </div>
                <div class="modal-body">
                    {!! Form::open(['url'=>'/settings/verify','id'=>'verify_form']) !!}
                    <div class="row">
                        <div class=" col-xs-3 col-sm-3">
                            <img src="{{asset(config('app.images_path').'lock.png')}}" width="100%">
                        </div>
                        <div class="col-xs-9 col-sm-9 ">
                            <div class="form-group">
                                <label for="envato_username">Envato Username</label>
                                <input type="text" class="form-control input-sm" required name="envato_username" id="envato_username" placeholder="Enter your envato username here"/>
                            </div>
                            <div class="form-group">
                                <label for="envato_username">Purchase Code</label>
                                <input type="text" class="form-control input-sm" name="purchase_code" id="purchase_code" placeholder="Enter your purchase code here"/>
                                <span style="font-size:12px;"><a href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code-" target="_blank">Where can I find my purchase code ?</a></span>
                            </div>
                            <div class="form-group">
                                <a href="javascript:" onclick="checkLicense()" class="btn btn-sm btn-success"><span class="glyphicon glyphicon-check"></span>Verify</a>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12">
                            <div class="alert alert-info" style="font-size:12px;  margin-bottom: 0px;" >
                                <span class="glyphicon glyphicon-warning-sign" style="margin-right: 12px;float: left;font-size: 22px;margin-top: 10px;margin-bottom: 10px;"></span>
                                Each website using this plugin needs a legal license (1 license = 1 website).<br/>
                                To read find more information on envato licenses,
                                <a href="https://codecanyon.net/licenses/standard" target="_blank">click here</a>.<br/>
                                If you need to buy a new license of this plugin, <a href="https://codecanyon.net/item/classic-invoicer/6193251?ref=elantsys" target="_blank">click here</a>.
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@include('partials.scripts')
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