<script type="text/javascript">
    $(function() {
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
    <div class="form-group">
        {!! Form::label('client_id', trans('app.client')) !!}
        {!! Form::select('client',$clients,$client->uuid ?? null, ['class' => 'form-control input-sm chosen', 'id' => 'client_id', 'required']) !!}
    </div>
</div>
<div class="col-md-3">
    <label>{{ trans('app.from') }} : </label>
    <div class="input-group mb-3">
        <input value="{{ $from_date ?? null }}" class="form-control form-control-sm date" size="16" type="text" name="from_date" readonly id="from_date"/>
        <div class="input-group-append">
            <span class="input-group-text" id="basic-addon2"><i class="fa fa-calendar"></i></span>
        </div>
    </div>
</div>
<div class="col-md-3">
    <label>{{ trans('app.to') }} : </label>
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
        <a href="javascript: void(0);" onclick="javascript: payments_summary();" class="btn btn-large btn-sm btn-success"  style="margin:6px"><i class="fa fa-check"></i> {{ trans('app.generate_report') }}</a>
    </div>
</div>
<div class="col-md-12" id="report_content">
    @include('reports.partials.report_header',['report_type'=>trans('app.payments_summary')])
    <table class="table table-bordered">
        <thead>
        <tr class="table_header">
            <th>{{ trans('app.date') }} </th>
            <th>{{ trans('app.invoice_number') }}</th>
            <th>{{ trans('app.payment_method') }}</th>
            <th>{{ trans('app.client') }}</th>
            <th class="text-right">{{ trans('app.amount') }}</th>
        </tr>
        </thead>
        <tbody>
        @php ($total = 0)
        @foreach($payments as $payment)
            @php ($payment_amount_converted = currency_convert(getCurrencyId($payment->currency),$payment->amount))
            @php ($total += $payment_amount_converted)
            <tr>
                <td>{{ format_date($payment->payment_date) }}</td>
                <td><a href="{{ route('invoices.show', $payment->invoice_id) }}">{{ $payment->invoice_no }}</a></td>
                <td>{{ $payment->method_name }}</td>
                <td><a href="{{ route('clients.show', $payment->client_id) }}">{{ $payment->client_name }}</a></td>
                <td class="text-right text-bold">{{ defaultCurrency().' '.format_amount($payment_amount_converted) }}</td>
            </tr>
        @endforeach
        <tr>
            <td class="text-bold">{{ trans('app.total') }}</td>
            <td class="text-right text-bold text-green" colspan="4">{{ defaultCurrency().' '.format_amount($total) }}</td>
        </tr>
        </tbody>
    </table>
</div>