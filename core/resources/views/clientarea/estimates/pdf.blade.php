<div class="container">
    <div style="width:300px;height:150px;float:left;">
        @if($estimate->estimate_logo != '')
            <img src="{{ $estimate->estimate_logo }}" alt="logo" width="50%"/>
        @endif
    </div>
    <div class="text-right" style="width:300px;height:150px;float:right;">
        <div class="text-right"> <h2>{{trans('app.estimate')}}</h2></div>
        <table style="width: 100%">
            <tr>
                <td class="text-right" style="width: 40%">{{trans('app.reference')}}</td>
                <td class="text-right">{{ $estimate->estimate_no }}</td>
            </tr>
            <tr>
                <td class="text-right">{{trans('app.date')}}</td>
                <td class="text-right">{{ format_date($estimate->estimate_date) }}</td>
            </tr>
        </table>
    </div>
    <div style="clear: both"></div>
    <div class="col-md-12">
        <div class="from_address">
            <h4 class="invoice_title">{{trans('app.our_information')}}</h4><hr class="separator"/>
            @if($settings)
                <h4 class="text-uppercase">{{ $settings->name }}</h4>
                {{ $settings->address1 ? $settings->address1.',' : '' }} {{ $settings->state ? $settings->state : '' }}<br/>
                {{ $settings->city ? $settings->city.',' : '' }} {{ $settings->postal_code ? $settings->postal_code.','  : ''  }}<br/>
                {{ $settings->country }}<br/>
                @if($settings->phone != '')
                    {{trans('app.phone')}}: {{ $settings->phone }}<br/>
                @endif
                @if($settings->email != '')
                    {{trans('app.email')}}: {{ $settings->email }}.
                @endif
            @endif
        </div>
        <div class="to_address">
            <h4 class="invoice_title">{{trans('app.estimate_to')}} </h4><hr class="separator"/>
            <h4 class="text-uppercase">{{ $estimate->client->name }}</h4>
            {{ $estimate->client->address1 ? $estimate->client->address1.',' : '' }} {{ $estimate->client->state ? $estimate->client->state : '' }}<br/>
            {{ $estimate->client->city ? $estimate->client->city.',' : '' }} {{ $estimate->client->postal_code ? $estimate->client->postal_code.','  : ''  }}<br/>
            {{ $estimate->client->country }}<br/>
            @if($estimate->client->phone != '')
                {{trans('app.phone')}}: {{ $estimate->client->phone }}<br/>
            @endif
            @if($estimate->client->email != '')
                {{trans('app.email')}}: {{ $estimate->client->email }}.
            @endif
        </div>
    </div>
    <div style="clear: both"></div>
    <div class="col-md-12">
        <table class="table">
            <thead style="margin-bottom:30px;background: #2e3e4e;color: #fff;">
            <tr style="margin-bottom:30px;background: #2e3e4e;color: #fff;" class="item_table_header">
                <th style="width:50%">{{trans('app.product')}}</th>
                <th style="width:10%" class="text-center">{{trans('app.quantity')}}</th>
                <th style="width:15%" class="text-right">{{trans('app.price')}}</th>
                <th style="width:10%" class="text-center">{{trans('app.tax')}}</th>
                <th style="width:15%" class="text-right">{{trans('app.total')}}</th>
            </tr>
            </thead>
            <tbody id="items">
            @foreach($estimate->items->sortBy('item_order') as $item)
            <tr>
                <td><b>{{ $item->item_name }}</b><br/>{!! htmlspecialchars_decode(nl2br(e($item->item_description)),ENT_QUOTES) !!}</td>
                <td class="text-center">{{ $item->quantity }}</td>
                <td class="text-right">{{ format_amount($item->price) }}</td>
                <td class="text-center">{{ $item->tax ? $item->tax->value.'%' : '' }}</td>
                <td class="text-right">{{ format_amount($item->itemTotal) }}</td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="col-md-12">
        <table class="table">
            <tbody>
            <tr>
                <th style="width:75%" class="text-right">{{trans('app.subtotal')}}</th>
                <td class="text-right">
                    <span id="subTotal">{{ format_amount($estimate->totals['subTotal']) }}</span>
                </td>
            </tr>
            <tr>
                <th class="text-right">{{trans('app.tax')}}</th>
                <td class="text-right">
                    <span id="taxTotal">{{ format_amount($estimate->totals['taxTotal']) }}</span>
                </td>
            </tr>
            <tr class="amount_due">
                <th class="text-right">{{trans('app.total')}}:</th>
                <td class="text-right">
                    <span id="grandTotal">{{ $estimate->currency.' '.format_amount($estimate->totals['grandTotal']) }}</span>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="col-md-12">
        @if($estimate->notes)
            <h4 class="invoice_title">{{trans('app.notes')}}</h4><hr class="separator"/>
            {!! htmlspecialchars_decode($estimate->notes, ENT_QUOTES) !!}<br/><br/>
        @endif
        @if($estimate->terms)
            <h4 class="invoice_title">{{trans('app.terms')}}</h4><hr class="separator"/>
            {!! htmlspecialchars_decode($estimate->terms, ENT_QUOTES) !!}
        @endif
    </div>
</div>
<style>
    body {font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;overflow-x: hidden;overflow-y: auto;font-size: 13px;}
    .amount_due {font-size: 16px;font-weight: 500;}
    .invoice_title{color: #2e3e4e;font-weight: bold;}
    .invoice_title,.text-uppercase{text-transform: uppercase !important;}
    .text-right{text-align: right;}
    .text-center{text-align: center;}
    .col-sm-12{width: 100%;}
    .col-sm-6{width: 50%;float: left;}
    table {border-spacing: 0;border-collapse: collapse;}
    .table {width: 100%;max-width: 100%;margin-bottom: 20px;}
    .item_table_header>th{padding: 8px;line-height: 1.42857143;vertical-align: top;}
    .table>tr>td, .table>tr>th{padding: 8px;line-height: 1.42857143;vertical-align: top;}
    hr.separator{border-color:  #2e3e4e;margin-top: 10px;margin-bottom: 10px;}
    tbody#items>tr>td{border: 3px solid #fff !important;vertical-align: middle;padding: 8px;}
    #items{background-color: #f1f1f1;}
    .form-group {margin-bottom: 1rem;}
    .from_address{width: 330px;height:200px;margin-bottom:1rem;float: left;}
    .to_address{width: 330px;height:200px;float: right;}
    .capitalize{text-transform: uppercase}
</style>