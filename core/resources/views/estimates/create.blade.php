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