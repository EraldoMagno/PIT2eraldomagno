@extends('clientarea.app')
@section('content')
    <div class="col-md-12 content-header" >
        <h5><i class="fa fa-{{ $headingIcon ?? null }}"></i> {{ $heading ?? null }}</h5>
    </div>
    <section class="content">
        <div class="card border-top-primary">
            <div class="card-header">
                <a href="{{ route('cestimates.index') }}" class="btn btn-sm btn-outline-info"> <i class="fa fa-arrow-circle-left"></i> @lang('app.back')</a>
                <a href="{{ route('cestimate_pdf', $estimate->uuid) }}" class="btn btn-primary btn-sm pull-right" style="margin-left: 5px"> <i class="fa fa-download"></i> @lang('app.download')</a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        @if (Session::has('flash_notification.message'))
                            {!! message() !!}
                        @endif
                    </div>
                </div>
                @include('estimates.partials.address_part')
                <div class="row mt-5">
                    <div class="col-md-12">
                        <table class="table">
                            <thead style="margin-bottom:30px;background: #2e3e4e;color: #fff;">
                            <tr>
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
                                <td><b>{!! $item->item_name !!}</b><br/>{!! $item->item_description !!}</td>
                                <td class="text-center">{{ $item->quantity }}</td>
                                <td class="text-right">{{ format_amount($item->price) }}</td>
                                <td class="text-center">{{ $item->tax ? $item->tax->value.'%' : '' }}</td>
                                <td class="text-right">{{ format_amount($item->itemTotal) }}</td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div><!-- /.col -->
                    <div class="col-md-6"></div><!-- /.col -->
                    <div class="col-md-6">
                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                <tr>
                                    <th style="width:50%">{{trans('app.subtotal')}}</th>
                                    <td class="text-right">
                                        <span id="subTotal">{{ format_amount($estimate->totals['subTotal']) }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{trans('app.tax')}}</th>
                                    <td class="text-right">
                                        <span id="taxTotal">{{ format_amount($estimate->totals['taxTotal']) }}</span>
                                    </td>
                                </tr>

                                <tr class="amount_due">
                                    <th>{{trans('app.total')}}</th>
                                    <td class="text-right">
                                        <span class="currencySymbol" style="display: inline-block;">{{ $estimate->currency }} </span>
                                        <span id="grandTotal">{{format_amount($estimate->totals['grandTotal'])}}</span>
                                    </td>
                                </tr>
                                </tbody></table>
                        </div>
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
            </div>
        </div>
    </section>
@endsection





