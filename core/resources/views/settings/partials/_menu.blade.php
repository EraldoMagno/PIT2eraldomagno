<div class="list-group border-top-primary">
    <a class="list-group-item {{ Request::is('settings/company') ? 'active' : '' }} " href="{{ route('settings.company.index') }}"> {{trans('app.company')}}</a>
    <a class="list-group-item {{ Request::is('settings/invoice') ? 'active' : '' }}" href="{{ route('settings.invoice.index') }}"> {{trans('app.invoice')}}</a>
    <a class="list-group-item {{ Request::is('settings/email') ? 'active' : '' }}" href="{{ route('settings.email.index') }}"> {{trans('app.email')}}</a>
    <a class="list-group-item {{ Request::is('settings/estimate') ? 'active' : '' }}" href="{{ route('settings.estimate.index') }}"> {{trans('app.estimate')}}</a>
    <a class="list-group-item {{ Request::is('settings/tax') ? 'active' : '' }}" href="{{ route('settings.tax.index') }}"> {{trans('app.tax')}}</a>
    <a class="list-group-item {{ Request::is('settings/templates/*') ? 'active' : '' }}" href="{{ route('settings.template.show', 'invoice') }}"> {{trans('app.templates')}}</a>
    <a class="list-group-item {{ Request::is('settings/number') ? 'active' : '' }}" href="{{ route('settings.number.index') }}"> {{trans('app.numbering')}}</a>
    <a class="list-group-item {{ Request::is('settings/payment') ? 'active' : '' }}" href="{{ route('settings.payment.index') }}"> {{trans('app.payment_methods')}}</a>
    <a class="list-group-item {{ Request::is('settings/gateways') ? 'active' : '' }}" href="{{ route('settings.gateways.index') }}"> {{trans('app.payment_gateway')}}</a>
    <a class="list-group-item {{ Request::is('settings/currency') ? 'active' : '' }}" href="{{ route('settings.currency.index') }}"> {{trans('app.currency')}}</a>
    <a class="list-group-item {{ Request::is('settings/roles') ? 'active' : '' }}" href="{{ route('settings.role.index') }}"> {{trans('app.roles')}}</a>
    <a class="list-group-item {{ Request::is('settings/permissions') ? 'active' : '' }}" href="{{ route('settings.permission.index') }}"> {{trans('app.permissions')}}</a>
    <a class="list-group-item {{ Request::is('settings/translations') || Request::is('translations') || Request::is('settings/translations/*') || Request::is('translations/*') ? 'active' : '' }}" href="{{ route('settings.translation.index') }}"> {{trans('app.language_manager')}}</a>
</div>