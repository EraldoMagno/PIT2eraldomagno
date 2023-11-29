<div class="col-md-12" id="report_content">
    @include('clientarea.reports.partials.report_header',['report_type'=>trans('app.client_statement')])
    <table class="table table-hover table-striped table-bordered datatable">
        <thead>
        <tr class="table_header">
            <th>@lang('app.date')</th>
            <th>@lang('app.activity')</th>
            <th class="text-right">@lang('app.invoices')</th>
            <th class="text-right">@lang('app.payments')</th>
            <th class="text-right">@lang('app.balance')</th>
        </tr>
        </thead>
        <tbody>
        @php ($total = 0)
        @if(!empty($statement))
            @foreach($statement as $record)
                @php ($total = ($record['transaction_type'] == 'payment') ? $total - currency_convert(getCurrencyId($record['currency']),$record['amount']) : $total + currency_convert(getCurrencyId($record['currency']),$record['amount']))
                <tr>
                    <td>{{ format_date($record['date']) }}</td>
                    <td>{{ $record['activity'] }}</td>
                    <td class="text-right text-red">{{ $record['transaction_type'] != 'payment' ? format_amount(currency_convert(getCurrencyId($record['currency']),$record['amount'])) : '' }}</td>
                    <td class="text-right text-green">{{ $record['transaction_type'] == 'payment' ? format_amount(currency_convert(getCurrencyId($record['currency']),$record['amount'])) : '' }}</td>
                    <td class="text-right text-bold">{{ format_amount($total) }}</td>
                </tr>
            @endforeach
            <tr>
                <td class="text-bold">{{ trans('app.total') }}</td>
                <td class="text-right text-bold text-red" colspan="4">{{ defaultCurrency().' '.format_amount($total) }}</td>
            </tr>
        @endif
        </tbody>
    </table>
</div>
