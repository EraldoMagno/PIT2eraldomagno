@extends('clientarea.app')
@section('content')
    <div class="col-md-12 content-header" >
        <h5><i class="fa fa-{{ $headingIcon ?? null }}"></i> {{ $heading ?? null }}</h5>
    </div>
    <section class="content">
        <div class="form-group text-right">
            <a href="{{ route('cinvoices.index') }}" class="btn btn-sm btn-outline-info float-left">
                <i class="fa fa-arrow-circle-left"></i> @lang('app.back')
            </a>
            <a href="{{route('cinvoice_pdf',$invoice->uuid)}}" class="btn btn-primary btn-sm mb-1" target="_blank">
                <span class="btn-label"><i class="fa fa-download"></i> @lang('app.download')</span>
            </a>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card border-top-primary">
                    <div class="card-body">
                        @if (Session::has('flash_notification.message'))
                            {!! message() !!}
                        @endif
                        @include('invoices.partials.address_part')
                        <div class="row">
                            <div class="col-md-12 mt-3">
                                <table class="table">
                                    <thead style="margin-bottom:30px;background: #2e3e4e;color: #fff;">
                                    <tr class="item_table_header">
                                        <th style="width:30%">@lang('app.product')</th>
                                        <th style="width:10%" class="text-center">@lang('app.quantity')</th>
                                        <th style="width:15%" class="text-center">@lang('app.price')</th>
                                        <th style="width:10%" class="text-center">@lang('app.tax')</th>
                                        <th style="width:15%" class="text-right">@lang('app.total')</th>
                                    </tr>
                                    </thead>
                                    <tbody id="items">
                                    @foreach($invoice->items->sortBy('item_order') as $item)
                                        <tr>
                                            <td><b>{{ $item->item_name }}</b><br/>{!! htmlspecialchars_decode(nl2br(e($item->item_description)),ENT_QUOTES) !!}</td>
                                            <td class="text-center">{{$item->quantity}}</td>
                                            <td class="text-right">{{$item->price}}</td>
                                            <td class="text-center">{{$item->tax ? $item->tax->value.'%' : '' }}</td>
                                            <td class="text-right">{{format_amount($item->itemTotal)}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6"></div>
                            <div class="col-sm-6">
                                <div class="table-responsive">
                                    <table class="table font-weight-bold">
                                        <tbody>
                                        <tr>
                                            <th style="width:50%">@lang('app.subtotal')</th>
                                            <td class="text-right">{{format_amount($invoice->totals['subTotal'],$invoice->currency ?? null) }}</td>
                                        </tr>
                                        <tr>
                                            <th>@lang('app.tax')</th>
                                            <td class="text-right">{{format_amount($invoice->totals['taxTotal'],$invoice->currency ?? null)}}</td>
                                        </tr>
                                        @if($invoice->discount > 0)
                                            <tr>
                                                <th>
                                                    @lang('app.discount')
                                                    @if($invoice->discount_mode == 1)
                                                        <span class="text-small"> - {{$invoice->discount}}%</span>
                                                    @endif
                                                </th>
                                                <td class="text-right">{{format_amount($invoice->totals['discount'],$invoice->currency ?? null)}}</td>
                                            </tr>
                                        @endif
                                        <tr>
                                            <th>@lang('app.total')</th>
                                            <td class="text-right">{{format_amount($invoice->totals['grandTotal'],$invoice->currency ?? null)}}</td>
                                        </tr>
                                        <tr>
                                            <th>@lang('app.paid')</th>
                                            <td class="text-right">{{format_amount($invoice->totals['paid'],$invoice->currency ?? null)}}</td>
                                        </tr>
                                        <tr class="amount_due">
                                            <th>@lang('app.amount_due')</th>
                                            <td class="text-right">{{format_amount($invoice->totals['amountDue'],$invoice->currency ?? null)}}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                @if($invoice->notes)
                                    <h4 class="invoice_title">@lang('app.notes')</h4><hr class="separator"/>
                                    {!! htmlspecialchars_decode($invoice->notes, ENT_QUOTES) !!}<br/><br/>
                                @endif
                                @if($invoice->terms)
                                    <h4 class="invoice_title">@lang('app.terms')</h4><hr class="separator"/>
                                    {!! htmlspecialchars_decode($invoice->terms, ENT_QUOTES) !!}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection


