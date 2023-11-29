<div class="row">
    <div class="col-sm-6">
        @if($estimate_settings && $estimate_settings->logo != '')
            <img src="{{image_url($estimate_settings->logo)}}" alt="logo" width="100%"/>
        @endif
    </div>
    <div class="col-sm-6 text-right font-weight-bold form-group">
        <h4 class="text-uppercase">@lang('app.estimate')</h4>
        <h5>#{{$estimate->estimate_no}}</h5>
        @lang('app.date') : {{format_date($estimate->estimate_date)}}<br>
        @if($settings && $settings->vat != '')
            <div class="col-xs-9 text-right invoice_title">@lang('app.vat_number') : {{ $settings ? $settings->vat : '' }}</div>
        @endif
    </div>
    <div style="clear: both"></div>
</div>
<div class="row">
    <div class="col-sm-6">
        <div class="panel-body">
            <h6 class="invoice_title text-uppercase">@lang('app.our_information')</h6><hr class="separator"/>
            @if($settings)
            <h6 class="text-uppercase">{{ $settings->name }}</h6>
            {{ $settings->address1 ? $settings->address1.',' : '' }} {{ $settings->state ? $settings->state : '' }}<br/>
            {{ $settings->city ? $settings->city.',' : '' }} {{ $settings->postal_code ? $settings->postal_code.','  : ''  }}<br/>
            {{ $settings->country }}<br/>
                @if($settings->phone != '')
                    @lang('app.phone') : {{ $settings->phone }}<br/>
                @endif
                @if($settings->email != '')
                    @lang('app.email') : {{ $settings->email }}.
                @endif
            @endif
        </div>
    </div>
    <div class="col-sm-6">
            <div class="panel-body">
                <h6 class="invoice_title text-uppercase">@lang('app.billing_to')</h6><hr class="separator"/>
                <h6 class="text-uppercase">{{ $estimate->client->name }}</h6>
                {{ $estimate->client->address1 ? $estimate->client->address1.',' : '' }} {{ $estimate->client->state ? $estimate->client->state : '' }}<br/>
                {{ $estimate->client->city ? $estimate->client->city.',' : '' }} {{ $estimate->client->postal_code ? $estimate->client->postal_code.','  : ''  }}<br/>
                {{ $estimate->client->country }}<br/>
                @if($estimate->client->phone != '')
                    @lang('app.phone') : {{ $estimate->client->phone }}<br/>
                @endif
                @if($estimate->client->email != '')
                    @lang('app.email') : {{ $estimate->client->email }}.
                @endif
            </div>
    </div>
</div>