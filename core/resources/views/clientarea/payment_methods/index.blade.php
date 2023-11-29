@extends('modal')
@section('content')
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header bg-primary">
            <h6 class="modal-title"><i class="fa fa-money"></i> @lang('app.pay_invoice')</h6>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
            <div class="col-sm-12 px-3 py-3 border border-primary border-2 rounded my-3 d-flex justify-content-between" style="background-color: rgba(18, 101, 241, 0.07);">
                <div class="d-flex flex-row align-items-center">
                  <div class="d-flex flex-column ms-4">
                    <span class="h5 mb-1">@lang('app.invoice') {{ $invoice->invoice_no ?? null }}</span>
                    <span class="small text-muted">@lang('app.balance')</span>
                  </div>
                </div>
                <div class="d-flex flex-row align-items-center">
                  <sup class="dollar font-weight-bold text-muted">{{ $invoice->currency }}</sup>
                  <span class="h2 mx-1 mb-0">{{ format_amount($invoice->totals['amountDue']) }}</span>
                </div>
            </div>
            @if($paypal_details['status'] || $stripe_details['status'])
                {!! Form::open(['route' => ['getCheckout'],'class'=>'needs-validation']) !!}
                <input type="hidden" name="selected_method" id="selected_method"/>
                <input type="hidden" name="invoice_id" value="{{$invoice->uuid}}"/>
                <div class="row">
                    @if($paypal_details['status'])
                        <div class="col-sm-6">
                            <input type="radio" class="btn-check d-none" name="selected_method" id="paypal" value="paypal"/>
                            <label class="btn btn-outline-primary btn-lg w-100 text-center" for="paypal">
                                <i class="fa fa-cc-paypal fa-3x"></i>
                            </label>
                        </div>
                    @endif
                    @if($stripe_details['status'])
                        <div class="col-sm-6">
                            <input type="radio" class="btn-check d-none" name="selected_method" id="stripe" value="stripe"/>
                            <label class="btn btn-outline-primary btn-lg w-100 text-center" for="stripe">
                                <i class="fa fa-cc-stripe fa-3x"></i>
                            </label>
                        </div>
                    @endif
                    <div class="col-sm-12 mt-4">
                        <button class="btn btn-success btn-lg btn-block text-uppercase btn_submit" data-loading-text="<i class='fa fa-spin fa-spinner'></i> @lang('app.processing')" disabled id="method_btn">@lang('app.complete_payment')</button>
                    </div>
                </div>
            {!! Form::close() !!}
            @else
                <div class="alert alert-warning">@lang('app.no_gateway_available')</div>
            @endif
        </div>
    </div>
</div>
@endsection