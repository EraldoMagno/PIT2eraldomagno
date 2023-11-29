@extends('app')
@section('content')
<div class="col-md-12 content-header" >
    <h5><i class="fa fa-{{ $headingIcon ?? null }}"></i> {{ $heading ?? null }}</h5>
</div>
<section class="content">
<div class="row">
    <div class="col-md-12">
        <div class="card border-top-primary">
            <div class="card-body">
                {!! form($invoice_form) !!}
            </div>
        </div>
    </div>
</div>
</section>
@endsection
@push('scripts')
    {{ Html::script('assets/plugins/accounting-js/accounting.min.js') }}
    @include('invoices.partials._invoices_js')
@endpush