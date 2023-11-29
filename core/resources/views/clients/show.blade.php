@extends('app')
@section('content')
<div class="col-md-12 content-header" >
    <h5><i class="fa fa-user"></i> {{ trans('app.client_details') }}</h5>
</div>
<section class="content">
    <div class="row">
            <div class="col-md-6">
                <div class="card border-top-primary">
                    <div class="card-header with-border">
                        <h3 class="card-title">{{ $client->name }}</h3>
                    </div>

                    <div class="card-body">
                        <table class="table table-striped">
                            <tr>
                                <td style="width:30%"><dt>{{ trans('app.client_number') }}</dt></td>
                                <td>{{ $client->client_no }}</td>
                            </tr>

                            <tr>
                                <td><dt>{{ trans('app.email') }}</dt></td>
                                <td>{{ $client->email }}</td>
                            </tr>

                            <tr>
                                <td><dt>{{ trans('app.phone') }}</dt></td>
                                <td>{{ $client->phone }}</td>
                            </tr>

                            <tr>
                                <td><dt>{{ trans('app.mobile') }}</dt></td>
                                <td>{{ $client->mobile }}</td>
                            </tr>

                            <tr>
                                <td><dt>{{ trans('app.address_1') }}</dt></td>
                                <td>{{ $client->address1 }}</td>
                            </tr>

                            <tr>
                                <td><dt>{{ trans('app.address_2') }}</dt></td>
                                <td>{{ $client->address2 }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-top-primary">
                    <div class="card-body">
                        <table class="table table-striped">
                            <tr>
                                <td style="width:30%"><dt>{{ trans('app.city') }}</dt></td>
                                <td>{{ $client->city }}</td>
                            </tr>
                            <tr>
                                <td><dt>{{ trans('app.state_province') }}</dt></td>
                                <td>{{ $client->state }}</td>
                            </tr>
                            <tr>
                                <td><dt>{{ trans('app.postal_zip') }}</dt></td>
                                <td>{{ $client->postal_code }}</td>
                            </tr>
                            <tr>
                                <td><dt>{{ trans('app.country') }}</dt></td>
                                <td>{{ $client->Country }}</td>
                            </tr>
                            <tr>
                                <td><dt>{{ trans('app.website') }}</dt></td>
                                <td>{{ $client->website }}</td>
                            </tr>
                            <tr>
                                <td><dt>{{ trans('app.notes') }}</dt></td>
                                <td>{{ $client->notes }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
     </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card border-top-primary">
                <div class="card-header with-border">
                    <h3 class="card-title">{{ trans('app.invoices') }}</h3>
                </div>
                <div class="card-body">
                        <table class="table table-bordered table-striped table-hover datatable">
                            <thead>
                            <tr>
                                <th width="10%"></th>
                                <th>{{ trans('app.invoice_number') }}</th>
                                <th>{{ trans('app.status') }}</th>
                                <th>{{ trans('app.date') }}</th>
                                <th>{{ trans('app.due_date') }}</th>
                                <th>{{ trans('app.amount') }}</th>
                                <th width="20%">{{ trans('app.action') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($client->invoices as $invoice)
                            <tr>
                                <td></td>
                                <td><a href="{{ route('invoices.show', $invoice->uuid ) }}">{{ $invoice->invoice_no }}</a> </td>
                                <td><span class="badge {{ statuses()[$invoice->status]['class'] }}">{{ ucwords(statuses()[$invoice->status]['label']) }} </span></td>
                                <td>{{ $invoice->invoice_date }} </td>
                                <td>{{ $invoice->due_date }} </td>
                                <td>{{ $invoice->currency.''.$invoice->totals['grandTotal'] }} </td>
                                <td>
                                    <a href="{{ route('invoices.show',$invoice->uuid) }}" class="btn btn-xs btn-info"><i class="fa fa-eye"></i> {{ trans('app.view') }} </a>
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
                    <h3 class="card-title">{{ trans('app.estimates') }}</h3>
                </div>

                <div class="card-body">
                    <table class="table table-bordered table-striped table-hover datatable">
                        <thead>
                        <tr>
                            <th width="10%"></th>
                            <th>{{ trans('app.estimate_number') }}</th>
                            <th>{{ trans('app.date') }}</th>
                            <th>{{ trans('app.amount') }}</th>
                            <th width="20%">{{ trans('app.action') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($client->estimates as $count => $estimate)
                        <tr>
                            <td>{{ $count+1 }}</td>
                            <td><a href="{{ route('estimates.show', $estimate->uuid ) }}">{{ $estimate->estimate_no }}</a> </td>
                            <td>{{ $estimate->estimate_date }} </td>
                            <td>{{ $estimate->currency.''.$estimate->totals['grandTotal'] }} </td>
                            <td>
                                <a href="{{route('estimates.show',$estimate->uuid)}}" class="btn btn-xs btn-info"><i class="fa fa-eye"></i> {{ trans('app.view') }} </a>
                            </td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection



