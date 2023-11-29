
@extends('app')
@section('content')
<div class="col-md-12 content-header" >
    <h5><i class="fa fa-{{ $headingIcon ?? null }}"></i> {{ $heading ?? null }}</h5>
</div>
<section class="content">
<div class="row">
    <div class="col-md-12">
        <div class="card border-top-primary">
            <div class="card-header">
                <a href="{{ route('estimates.index') }}" class="btn btn-info btn-sm"> <i class="fa fa-chevron-left"></i> @lang('app.back')</a>
                <a href="{{ route('estimates.show', $estimate->uuid) }}" class="btn btn-primary btn-sm float-right"> <i class="fa fa-search"></i> @lang('app.preview')</a>
                <span id="btn_convert_to_invoice" data-id="{{$estimate->uuid}}" class="btn btn-success btn-sm float-right mr-3"> <i class="fa fa-mail-forward"></i> @lang('app.make_invoice')</span>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    {!! display_form_errors($errors) !!}
                @endif
                {!! form($estimate_form) !!}
            </div>
        </div>
    </div>
</div>
</section>
@endsection
@push('scripts')
    {{ Html::script('assets/plugins/accounting-js/accounting.min.js') }}
    @include('estimates.partials._estimatesjs')
@endpush