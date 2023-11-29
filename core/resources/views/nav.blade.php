<aside class="main-sidebar sidebar-dark-primary elevation-4">
    @if(get_setting_value('logo') != '')
        <a href="index3.html" class="brand-link">
            <img src="{{ image_url(get_setting_value('logo')) }}" alt="Logo" class="brand-image elevation-3">
        </a>
    @endif
    <section class="sidebar">
        <div class="user-panel mt-3">
            @if(auth()->guard('admin')->check())
            <div class="pull-left image">
                <img src="{{Auth::guard('admin')->user()->photo != '' ? image_url('uploads/'.Auth::guard('admin')->user()->photo) : image_url('uploads/defaultavatar.png')  }}" class="img-circle" alt="User Image" />
            </div>
            <div class="pull-left info">
                <p> {{  auth()->guard('admin')->user()->name }} </p>
                <a href="#"><i class="fa fa-circle text-success"></i> {{trans('app.online')}}</a>
            </div>
            @endif
        </div>
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column sidebar-menu" data-widget="treeview" role="menu" data-accordion="false">
                <li class="header p-2">{{trans('app.main_menu')}}</li>
                <li class="nav-item {{ Form::menu_active('/') }}">
                    <a href="{{ url('/') }}" class="nav-link">
                        <i class="fa fa-home"></i>
                        <span>{{trans('app.dashboard')}}</span>
                    </a>
                </li>
                <li class="nav-item {{ Form::menu_active('clients') }}">
                    <a href="{{ url('clients') }}" class="nav-link">
                        <i class="fa fa-users"></i>
                        <span>{{trans('app.clients')}}</span>
                    </a>
                </li>
                <li class="nav-item {{ Form::menu_active('invoices') }}">
                    <a href="{{ url('invoices') }}" class="nav-link">
                        <i class="fa fa-file-pdf-o"></i>
                        <span>{{trans('app.invoices')}}</span>
                    </a>
                </li>
                <li class="nav-item {{ Form::menu_active('estimates') }}">
                    <a href="{{ url('estimates') }}" class="nav-link">
                        <i class="fa fa-list-alt"></i>
                        <span>{{trans('app.estimates')}}</span>
                    </a>
                </li>
                <li class="nav-item {{ Form::menu_active('payments') }}">
                    <a href="{{ url('payments') }}" class="nav-link">
                        <i class="fa fa-money"></i>
                        <span>{{trans('app.payments')}}</span>
                    </a>
                </li>
                <li class="nav-item {{ active_dropdown_menu('/expenses') ? 'menu-is-opening menu-open' : '' }}">
                    <a href="#" class="nav-link {{ Form::menu_active('expenses') }} {{ Form::menu_active('expenses/category') }}">
                        <i class="nav-icon fa fa-credit-card"></i>
                        <p>
                            @lang('app.expenses') <i class="right fa fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview" style="display: {{ active_dropdown_menu('/expenses') ? 'block' : 'none' }};">
                        <li class="nav-item">
                            <a href="{{ route('expenses.index') }}" class="nav-link {{ Request::is('expenses/list') ? 'active' : null }}">
                                <i class="fa fa-circle nav-icon"></i>
                                <p>@lang('app.expenses')</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('expenses.category.index') }}" class="nav-link {{ Request::is('expenses/category') ? 'active' : null }}">
                                <i class="fa fa-circle nav-icon"></i>
                                <p>@lang('app.categories')</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item {{ active_dropdown_menu('/products') ? 'menu-is-opening menu-open' : '' }}">
                    <a href="#" class="nav-link {{ Form::menu_active('products') }} {{ Form::menu_active('products/category') }}">
                        <i class="nav-icon fa fa-puzzle-piece"></i>
                        <p>
                            @lang('app.products') <i class="right fa fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview" style="display: {{ active_dropdown_menu('/products') ? 'block' : 'none' }};">
                        <li class="nav-item">
                            <a href="{{ route('products.index') }}" class="nav-link {{ Request::is('products/list') ? 'active' : null }}">
                                <i class="fa fa-circle nav-icon"></i>
                                <p>@lang('app.products')</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('products.category.index') }}" class="nav-link {{ Request::is('products/category') ? 'active' : null }}">
                                <i class="fa fa-circle nav-icon"></i>
                                <p>@lang('app.categories')</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item {{ Form::menu_active('reports') }}">
                    <a href="{{ url('reports') }}" class="nav-link">
                        <i class="fa fa-line-chart"></i> 
                        <span>{{trans('app.reports')}}</span>
                    </a>
                </li>
                <li class="nav-item {{ Form::menu_active('users') }}">
                    <a href="{{ route('users.index') }}" class="nav-link">
                        <i class="fa fa-user "></i> 
                        <span>{{trans('app.users')}}</span>
                    </a>
                </li>
                @if(auth()->guard('admin')->check() && (auth()->guard('admin')->user()->can('edit_setting') || auth()->guard('admin')->user()->HasRole('admin')))
                    <li class="nav-item {{ Form::menu_active('settings') }}">
                        <a href="{{ url('settings/company') }}" class="nav-link">
                            <i class="fa fa-cogs"></i> 
                            <span>{{trans('app.settings')}}</span>
                        </a>
                    </li>
                @endif
                <li class="header p-2">{{trans('app.account_menu')}}</li>
                <li class="nav-item {{ Form::menu_active('profile') }}">
                    <a href="{{ url('profile') }}" class="nav-link">
                        <i class="fa fa-user-md "></i> <span>{{trans('app.profile')}}</span>
                    </a>
                </li>
                <li class="nav-item {{ Form::menu_active('logout') }}">
                    <a href="{{ route('admin_logout') }}" class="nav-link">
                        <i class="fa fa-power-off"></i> <span>{{trans('app.logout')}}</span>
                    </a>
                </li>
            </ul>
        </nav>
    </section>
</aside>