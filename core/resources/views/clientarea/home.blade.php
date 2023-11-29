@extends('clientarea.app')
@section('content')
    <div class="col-md-12 content-header" >
        <h5><i class="fa fa-home"></i> @lang('app.dashboard')</h5>
    </div>
    <section class="content">
        <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <i class="fa fa-file-pdf-o bg-green"></i>
                    <div class="info-box-content">
                        <span class="info-box-text">@lang('app.invoices')</span>
                        <span class="info-box-number">{{ $invoices }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <i class="fa fa-list-alt bg-yellow"></i>
                    <div class="info-box-content">
                        <span class="info-box-text">@lang('app.estimates')</span>
                        <span class="info-box-number">{{ $estimates }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <i class="fa fa-usd bg-aqua"></i>
                    <div class="info-box-content">
                        <span class="info-box-text">@lang('app.payments')</span>
                        <span class="info-box-number" style="color: #00a65a;">{{ $total_payments }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <i class="fa fa-credit-card bg-red"></i>
                    <div class="info-box-content">
                        <span class="info-box-text">@lang('app.outstanding_amount')</span>
                        <span class="info-box-number" style="color: #dd4b39;">{{ $total_outstanding }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-primary">
                    <i class="fa fa-usd fa-3x"></i>
                    <div class="info-box-content">
                        <span class="info-box-number">{{ $invoice_stats['partiallyPaid'] }}</span>
                        <span class="info-box-text">@lang('app.invoices_partially_paid')</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-yellow">
                    <i class="fa fa-money fa-3x"></i>
                    <div class="info-box-content">
                        <span class="info-box-number">{{ $invoice_stats['unpaid'] }}</span>
                        <span class="info-box-text">@lang('app.unpaid_invoices')</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-red">
                    <i class="fa fa-times fa-3x"></i>
                    <div class="info-box-content">
                        <span class="info-box-number">{{  $invoice_stats['overdue'] }}</span>
                        <span class="info-box-text">@lang('app.invoices_overdue')</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-green">
                    <i class="fa fa-check fa-3x"></i>
                    <div class="info-box-content">
                        <span class="info-box-number">{{  $invoice_stats['paid'] }}</span>
                        <span class="info-box-text">@lang('app.paid_invoices')</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card border-top-primary">
                    <div class="card-header with-border">
                        <h3 class="card-title"> @lang('app.recent_invoices')</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover datatable">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>@lang('app.invoice_number')</th>
                                    <th>@lang('app.invoice_status')</th>
                                    <th>@lang('app.client')</th>
                                    <th>@lang('app.date')</th>
                                    <th>@lang('app.due_date')</th>
                                    <th>@lang('app.amount')</th>
                                    <th width="20%">@lang('app.action')</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($recentInvoices as $count=>$invoice)
                                    <tr>
                                        <td>{{ $count+1 }}</td>
                                        <td><a href="{{ route('cinvoices.show', $invoice->uuid) }}">{{ $invoice->invoice_no }}</a></td>
                                        <td><span class="badge {{ statuses()[$invoice->status]['class'] }}">{{ ucwords(statuses()[$invoice->status]['label']) }}</span></td>
                                        <td>{{ $invoice->client->name }}</td>
                                        <td>{{ format_date($invoice->invoice_date) }}</td>
                                        <td>{{ format_date($invoice->due_date) }} </td>
                                        <td>{!! '<span style="display:inline-block">'.$invoice->currency.'</span><span style="display:inline-block"> '.format_amount($invoice->totals['grandTotal']).'</span>'  !!}</td>
                                        <td>
                                            <a href="{{ route('cinvoices.show',$invoice->uuid) }}" class="btn btn-xs btn-info"><i class="fa fa-eye"></i> @lang('app.view')</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
            </div>
            <div class="col-md-12">
                <div class="card border-top-primary">
                    <div class="card-header with-border">
                        <h3 class="card-title"> @lang('app.recent_estimates')</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover datatable">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>@lang('app.estimate_number')</th>
                                    <th>@lang('app.client')</th>
                                    <th>@lang('app.date')</th>
                                    <th>@lang('app.amount')</th>
                                    <th width="20%">@lang('app.action')</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($recentEstimates as $count=>$estimate)
                                    <tr>
                                        <td>{{ $count+1 }}</td>
                                        <td><a href="{{ route('cestimates.show', $estimate->uuid) }}">{{ $estimate->estimate_no }}</a></td>
                                        <td>{{ $estimate->client->name }}</td>
                                        <td>{{ format_date($estimate->estimate_date) }}</td>
                                        <td>{!! '<span style="display:inline-block">'.$estimate->currency.'</span><span style="display:inline-block"> '.format_amount($estimate->totals['grandTotal']).'</span>' !!}</td>
                                        <td>
                                            <a href="{{ route('cestimates.show',$estimate->uuid) }}" class="btn btn-xs btn-info"><i class="fa fa-eye"></i> @lang('app.view')</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection