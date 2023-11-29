<div class="row">
    <div class="col-sm-6 mb-5">
        @if($invoiceSettings && $invoiceSettings->logo != '')
            <img src="{{image_url($invoiceSettings->logo)}}" alt="logo" width="50%"/>
        @endif
    </div>
    <div class="col-sm-6 text-right font-weight-bold form-group">
        <h4 class="text-uppercase">@lang('app.invoice')</h4>
        <h5>#{{$invoice->invoice_no}}</h5>
        @lang('app.date') : {{format_date($invoice->invoice_date)}}<br>
        @lang('app.due_date') : {{format_date($invoice->due_date)}}<br>
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
                <h6 class="text-uppercase">{{ $invoice->client->name }}</h6>
                {{ $invoice->client->address1 ? $invoice->client->address1.',' : '' }} {{ $invoice->client->state ? $invoice->client->state : '' }}<br/>
                {{ $invoice->client->city ? $invoice->client->city.',' : '' }} {{ $invoice->client->postal_code ? $invoice->client->postal_code.','  : ''  }}<br/>
                {{ $invoice->client->country }}<br/>
                @if($invoice->client->phone != '')
                    @lang('app.phone') : {{ $invoice->client->phone }}<br/>
                @endif
                @if($invoice->client->email != '')
                    @lang('app.email') : {{ $invoice->client->email }}.
                @endif
            </div>
    </div>
</div>