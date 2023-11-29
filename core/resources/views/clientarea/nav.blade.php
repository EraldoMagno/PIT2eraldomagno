<aside class="main-sidebar sidebar-dark-primary elevation-4">
    @if(get_setting_value('logo') != '')
        <a href="index3.html" class="brand-link">
            <img src="{{ image_url(get_setting_value('logo')) }}" alt="Logo" class="brand-image elevation-3">
        </a>
    @endif
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel mt-3">
            @if(auth()->guard('user')->check())
            <div class="pull-left image">
                <img src="{{Auth::guard('user')->user()->photo != '' ? image_url('uploads/client_images/'.Auth::guard('user')->user()->photo) : image_url('uploads/defaultavatar.png')  }}" class="img-circle" alt="User Image" />
            </div>
            <div class="pull-left info">
                <p> {{  auth()->guard('user')->user()->name }} </p>
                <a href="#"><i class="fa fa-circle text-success"></i> {{trans('app.online')}}</a>
            </div>
            @endif
        </div>
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column sidebar-menu" data-widget="treeview" role="menu" data-accordion="false">
                <li class="header p-2">{{trans('app.main_menu')}}</li>
                <li class="{{ Form::menu_active('clientarea/home') }}">
                    <a href="{{ route('client_dashboard') }}" class="nav-link">
                        <i class="fa fa-home"></i>
                        <span>{{trans('app.dashboard')}}</span>
                    </a>
                </li>
                <li class="{{ Form::menu_active('clientarea/cinvoices') }}">
                    <a href="{{ route('cinvoices.index') }}" class="nav-link">
                        <i class="fa fa-file-pdf-o"></i>
                        <span>{{trans('app.invoices')}}</span>
                    </a>
                </li>
                <li class="{{ Form::menu_active('clientarea/cestimates') }}">
                    <a href="{{ route('cestimates.index') }}" class="nav-link">
                        <i class="fa fa-list-alt"></i>
                        <span>{{trans('app.estimates')}}</span>
                    </a>
                </li>
                <li class="{{ Form::menu_active('clientarea/cpayments') }}">
                    <a href="{{ route('cpayments.index') }}" class="nav-link">
                        <i class="fa fa-money"></i>
                        <span>{{trans('app.payments')}}</span>
                    </a>
                </li>
                <li class="{{ Form::menu_active('clientarea/reports') }}">
                    <a href="{{ url('clientarea/reports') }}" class="nav-link">
                        <i class="fa fa-line-chart"></i>
                        <span>{{trans('app.reports')}}</span>
                    </a>
                </li>
                <li class="header">{{trans('app.account_menu')}}</li>
                <li class="{{ Form::menu_active('clientarea/cprofile') }}">
                    <a href="{{ url('clientarea/cprofile') }}" class="nav-link">
                        <i class="fa fa-user-md "></i>
                        <span>{{trans('app.profile')}}</span>
                    </a>
                </li>
                <li class="{{ Form::menu_active('clientarea/logout') }}">
                    <a href="{{ route('client_logout') }}" class="nav-link">
                        <i class="fa fa-power-off"></i> 
                        <span>{{trans('app.logout')}}</span>
                    </a>
                </li>
            </ul>
    </section>
    <!-- /.sidebar -->
</aside>