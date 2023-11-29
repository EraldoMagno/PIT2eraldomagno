<script type="text/javascript">
    $(function() {
        $('.date').pikaday({ firstDay: 1, format:'YYYY-MM-DD', autoclose:true });
        $(".date").pikaday({ firstDay: 1, format:'YYYY-MM-DD', autoclose:true });
        $('.datatable').DataTable({
            "columnDefs": [ {
                "searchable": false,
                "orderable": false,
                "targets": 0
            } ],
            "order": [[ 1, 'asc' ]],
            "bLengthChange": false,
            "bInfo" : false,
            "filter" : true,
            "oLanguage": { "sSearch": ""}
        });
        $('div.dataTables_filter input').addClass('form-control input-sm');
    });
</script>
<div class="col-md-3">
    <label>@lang('app.from') }} : </label>
    <div class="input-group mb-3">
        <input value="{{ $from_date ?? null }}" class="form-control form-control-sm date" size="16" type="text" name="from_date" readonly id="from_date"/>
        <div class="input-group-append">
            <span class="input-group-text" id="basic-addon2"><i class="fa fa-calendar"></i></span>
        </div>
    </div>
</div>
<div class="col-md-3">
    <label>@lang('app.to') }} : </label>
    <div class="input-group mb-3">
        <input value="{{ $to_date ?? null }}" class="form-control form-control-sm date" size="16" type="text" name="to_date" readonly id="to_date"/>
        <div class="input-group-append">
            <span class="input-group-text" id="basic-addon2"><i class="fa fa-calendar"></i></span>
        </div>
    </div>
</div>
<div class="col-md-3">
    <label> </label>
    <div class="form-group">
        <a href="javascript: void(0);" onclick="javascript: payments_summary();" class="btn btn-large btn-sm btn-success"  style="margin:6px"><i class="fa fa-check"></i> @lang('app.generate_report') }}</a>
    </div>
</div>
<div class="col-md-12" id="report_content">
    @include('clientarea.reports.partials.report_header',['report_type'=>trans('app.payments_summary')])
    <table class="table table-hover table-striped table-bordered">
        <thead>
        <tr class="table_header">
            <th>@lang('app.date')</th>
            <th>@lang('app.invoice_number')</th>
            <th>@lang('app.payment_method')</th>
            <th>@lang('app.client')</th>
            <th class="text-right">@lang('app.amount')</th>
        </tr>
        </thead>
        <tbody>
            @php ($total = 0)
            @foreach($payments as $payment)
                @php ($payment_amount_converted = currency_convert(getCurrencyId($payment->currency),$payment->amount))
                @php ($total += $payment_amount_converted)
                <tr>
                    <td>{{ format_date($payment->payment_date) }}</td>
                    <td><a href="{{ route('cinvoices.show', $payment->invoice_id) }}">{{ $payment->invoice_no }}</a></td>
                    <td>{{ $payment->method_name }}</td>
                    <td>{{ $payment->client_name }}</td>
                    <td class="text-right">{{ defaultCurrency().' '.format_amount(currency_convert(getCurrencyId($payment->currency),$payment->amount)) }}</td>
                </tr>
            @endforeach
            <tr>
                <td class="text-bold">@lang('app.total')</td>
                <td class="text-right text-bold text-green" colspan="4">{{ defaultCurrency().' '.format_amount($total) }}</td>
            </tr>
        </tbody>
    </table>
</div>