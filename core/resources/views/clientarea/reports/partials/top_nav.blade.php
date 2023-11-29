<div class="col-sm-12">
    <ul class="nav">
        <li class="nav-item">
            <button class="nav-link btn btn-sm btn-danger mr-2 mb-2" onclick="javascript: general_summary();"><i class="fa fa-line-chart"></i> @lang('app.general_summary')</button>
        </li>
        <li class="nav-item">
            <button class="nav-link btn btn-sm btn-primary mr-2 mb-2" onclick="javascript: payments_summary();"><i class="fa fa-money"></i> @lang('app.payments_summary')</button>
        </li>
        <li class="nav-item">
            <button class="nav-link btn btn-sm btn-info mr-2 mb-2" onclick="javascript: client_statement();"><i class="fa fa-users"></i> @lang('app.client_statement')</button>
        </li>
        <li class="nav-item">
            <button class="nav-link btn btn-sm btn-success mr-2 mb-2" onclick="javascript: invoices_report();"><i class="fa fa-file-pdf-o"></i> @lang('app.invoice_report')</button>
        </li>
    </ul>
</div>