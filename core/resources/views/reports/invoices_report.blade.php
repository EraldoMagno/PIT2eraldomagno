<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('client_id', trans('app.client')) !!}
        {!! Form::select('client',$clients,$client, ['class' => 'form-control input-sm chosen', 'id' => 'client_id', 'required']) !!}
    </div>
</div>
<div class="col-md-3">
    <label> </label>
    <div class="form-group">
        <a href="javascript: void(0);" onclick="javascript: invoices_report();" class="btn btn-large btn-sm btn-success"  style="margin:6px"><i class="fa fa-check"></i> {{ trans('app.generate_report') }} </a>
    </div>
</div>
<div class="col-md-12 table-responsive" id="report_content">
    @include('reports.partials.report_header',['report_type'=>trans('app.invoice_report')])
    <table class="table table-hover table-bordered ">
        <thead>
        <tr class="table_header">
            <th>{{ trans('app.status') }}</th>
            <th>{{ trans('app.invoice_number') }}</th>
            <th>{{ trans('app.date') }} </th>
            <th>{{ trans('app.client') }}</th>
            <th class="text-right">{{trans('app.amount')}}</th>
            <th class="text-right">{{trans('app.paid')}}</th>
            <th class="text-right">{{trans('app.amount_due')}}</th>
        </tr>
        </thead>
        <tbody>
        @php($total_invoiced = 0)
        @php($total_paid = 0)
        @php($total_due = 0)
        @foreach($invoices as $invoice)
            @php ($total_invoiced += currency_convert(getCurrencyId($invoice->currency),$invoice->totals['grandTotal']))
            @php ($total_paid += currency_convert(getCurrencyId($invoice->currency),$invoice->totals['paidFormatted']))
            @php ($total_due += currency_convert(getCurrencyId($invoice->currency),$invoice->totals['amountDue']))
            <tr>
                <td><span class="badge {{ statuses()[$invoice->status]['class'] }}">{{ strtoupper(statuses()[$invoice->status]['label']) }}</span></td>
                <td><a href="{{ route('invoices.show', $invoice->uuid) }}">{{ $invoice->invoice_no }}</a></td>
                <td>{{ format_date($invoice->invoice_date) }}</td>
                <td><a href="{{ route('clients.show', $invoice->client_id ) }}">{{ $invoice->client->name }}</a></td>
                <td class="text-right">{{  defaultCurrency().' '.format_amount(currency_convert(getCurrencyId($invoice->currency),$invoice->totals['grandTotal'])) }} </td>
                <td class="text-right">{{  defaultCurrency().' '.format_amount(currency_convert(getCurrencyId($invoice->currency),$invoice->totals['paidFormatted'])) }} </td>
                <td class="text-right">{{  defaultCurrency().' '.format_amount(currency_convert(getCurrencyId($invoice->currency),$invoice->totals['amountDue'])) }} </td>
            </tr>
        @endforeach
        <tr>
            <td class="text-bold" colspan="4">{{ trans('app.total') }}</td>
            <td class="text-bold text-right">{{ defaultCurrency().' '.format_amount($total_invoiced) }}</td>
            <td class="text-bold text-right">{{ defaultCurrency().' '.format_amount($total_paid) }}</td>
            <td class="text-right text-bold text-red" colspan="4">{{ defaultCurrency().' '.format_amount($total_due) }}</td>
        </tr>
        </tbody>
    </table>
</div>