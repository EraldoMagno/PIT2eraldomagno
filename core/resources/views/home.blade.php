@extends('app')
@section('content')
    <div class="col-md-12 content-header" >
        <h5><i class="fa fa-home"></i> @lang('app.dashboard')</h5>
    </div>
    <section class="content">
        <div class="row">
            <div class="col-lg-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <i class="fa fa-users bg-aqua"></i>
                    <div class="info-box-content">
                        <span class="info-box-text">@lang('app.clients')</span>
                        <span class="info-box-number">{{ $clients }}</span>
                    </div>
                </div>
            </div>
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
                    <i class="fa fa-puzzle-piece bg-red"></i>
                    <div class="info-box-content">
                        <span class="info-box-text">@lang('app.products')</span>
                        <span class="info-box-number">{{ $products }}</span>
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
                <div class="info-box bg-warning">
                    <i class="fa fa-money fa-3x"></i>
                    <div class="info-box-content">
                        <span class="info-box-number text-white">{{ $invoice_stats['unpaid'] }}</span>
                        <span class="info-box-text text-white">@lang('app.unpaid_invoices')</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-danger">
                    <i class="fa fa-times fa-3x"></i>
                    <div class="info-box-content">
                        <span class="info-box-number">{{ $invoice_stats['overdue'] }}</span>
                        <span class="info-box-text">@lang('app.invoices_overdue')</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-success">
                    <i class="fa fa-check fa-3x"></i>
                    <div class="info-box-content">
                        <span class="info-box-number">{{ $invoice_stats['paid'] }}</span>
                        <span class="info-box-text">@lang('app.paid_invoices')</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <section class="col-md-6">
                <div class="card border-top-primary">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fa fa-pie-chart mr-1"></i> @lang('app.yearly_overview')
                        </h3>
                    </div>
                    <div class="card-body">
                        <div id="yearly_overview">
                            <canvas id="yearly_overview_inner"></canvas>
                        </div>
                    </div>
                </div>
            </section>
            <section class="col-md-6">
                <div class="card border-top-primary">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fa fa-usd mr-1"></i> @lang('app.payment_overview')
                        </h3>
                    </div>
                    <div class="card-body">
                        <div id="payment_overview">
                            <canvas id="payment_overview_inner"></canvas>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card border-top-primary">
                    <div class="card-header with-border">
                        <h3 class="card-title"> @lang('app.recent_invoices')</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>@lang('app.invoice_number')</th>
                                        <th>@lang('app.invoice_status')</th>
                                        <th>@lang('app.client')</th>
                                        <th class="text-right">@lang('app.date')</th>
                                        <th class="text-right">@lang('app.due_date')</th>
                                        <th class="text-right">@lang('app.amount')</th>
                                        <th class="text-right" width="20%">@lang('app.action')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($recentInvoices as $count=>$invoice)
                                    <tr>
                                        <td>{{ $count+1 }}</td>
                                        <td><a href="{{ route('invoices.show', $invoice->uuid) }}">{{ $invoice->number }}</a> </td>
                                        <td><span class="badge {{ statuses()[$invoice->status]['class'] }}">{{ ucwords(statuses()[$invoice->status]['label']) }} </span></td>
                                        <td><a href="{{route('clients.show', $invoice->client_id) }}">{{ $invoice->client->name ?? '' }}</a> </td>
                                        <td class="text-right">{{ format_date($invoice->invoice_date) }} </td>
                                        <td class="text-right">{{ format_date($invoice->due_date) }} </td>
                                        <td class="text-right">{!! '<span style="display:inline-block">'.$invoice->currency.'</span><span style="display:inline-block"> '.format_amount($invoice->totals['grandTotal']).'</span>' !!} </td>
                                        <td class="text-right">
                                            <a href="{{ route('invoices.show',$invoice->uuid) }}" class="btn btn-xs btn-info"><i class="fa fa-eye"></i> @lang('app.view')</a>
                                            @if(hasPermission('edit_invoice'))
                                                <a href="{{ route('invoices.edit',$invoice->uuid) }}" class="btn btn-xs btn-success"><i class="fa fa-pencil"></i> @lang('app.edit')</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
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
                            <table class="table table-bordered table-striped table-hover">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>@lang('app.estimate_number')</th>
                                    <th>@lang('app.client')</th>
                                    <th class="text-right">@lang('app.date')</th>
                                    <th class="text-right">@lang('app.amount')</th>
                                    <th width="20%" class="text-right">@lang('app.action')</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($recentEstimates as $count=>$estimate)
                                    <tr>
                                        <td>{{ $count+1 }}</td>
                                        <td><a href="{{ route('estimates.show', $estimate->uuid) }}">{{ $estimate->estimate_no }} </a></td>
                                        <td><a href="{{ route('clients.show', $estimate->client_id) }}">{{ $estimate->client->name ?? '' }}</a> </td>
                                        <td class="text-right">{{ format_date($estimate->estimate_date) }} </td>
                                        <td class="text-right">{!! '<span style="display:inline-block">'.$estimate->currency.'</span><span style="display:inline-block"> '.format_amount($estimate->totals['grandTotal']).'</span>' !!} </td>
                                        <td class="text-right">
                                            <a href="{{ route('estimates.show',$estimate->uuid) }}" class="btn btn-xs btn-info"><i class="fa fa-eye"></i> @lang('app.view')</a>
                                            @if(hasPermission('edit_estimate'))
                                                <a href="{{ route('estimates.edit',$estimate->uuid) }}" class="btn btn-xs btn-success"><i class="fa fa-pencil"></i> @lang('app.edit')</a>
                                            @endif
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
@push('scripts')
    <script src="{{ asset('assets/js/chart.js') }}"></script>
    <script>
        var income_data     = JSON.parse('<?php echo $yearly_income; ?>');
        var expense_data    = JSON.parse('<?php echo $yearly_expense; ?>');
        var lineChartData   = {
            labels : ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],
            datasets : [{
                label               : "@trans('app.income')",
                fillColor           : "rgba(14,172,147,0.1)",
                strokeColor         : "rgba(14,172,147,1)",
                pointColor          : "rgba(14,172,147,1)",
                pointStrokeColor    : "#fff",
                pointHighlightFill  : "rgba(54,73,92,0.8)",
                pointHighlightStroke: "rgba(54,73,92,1)",
                data                : income_data
            },
                {
                    label               : "{{ trans('app.expenditure') }}",
                    fillColor           : "rgba(244,167,47,0)",
                    strokeColor         : "rgba(244,167,47,1)",
                    pointColor          : "rgba(217,95,6,1)",
                    pointStrokeColor    : "#fff",
                    pointHighlightFill  : "rgba(54,73,92,0.8)",
                    pointHighlightStroke: "rgba(54,73,92,1)",
                    data                : expense_data
                }
            ]
        };
        var pieData = [{
                value: '<?php echo $total_payments; ?>',
                color:"#2FB972",
                highlight: "#37D484",
                label: "{{ trans('app.amount_received') }}"
            },
            {
                value: '<?php echo $total_outstanding; ?>',
                color:"#C84135",
                highlight: "#EA5548",
                label: "{{ trans('app.outstanding_amount') }}"
            }
        ];

        $(function(){
            Chart.defaults.global.scaleFontSize = 12;
            var chartDiv = document.getElementById("yearly_overview_inner").getContext("2d");
            lineChart = new Chart(chartDiv).Line(lineChartData, {
                responsive: true
            });
            $('#yearly_overview').append(lineChart.generateLegend());
            var chartDiv = document.getElementById("payment_overview_inner").getContext("2d");
            pieChart = new Chart(chartDiv).Pie(pieData, {
                responsive : true
            });
            $('#payment_overview').append(pieChart.generateLegend());
        });
    </script>
@endpush