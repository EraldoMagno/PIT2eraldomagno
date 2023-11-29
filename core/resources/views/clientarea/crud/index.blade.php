@extends('clientarea.app')
@section('content')
<div class="col-md-12 content-header" >
    <h5><i class="fa fa-{{ $headingIcon ?? null }}"></i> {{ $heading ?? null }}</h5>
</div>
<section class="content">
<div class="row">
    <div class="col-md-12">
        <div class="card border-top-primary">
            <div class="card-body">
                <div class="table-responsive">
                    {!! $dataTable->table(['width' => '100%']) !!}
                </div>
            </div>
        </div>
    </div>
</div>
</section>
@endsection
@push('scripts')
{!! $dataTable->scripts() !!}
@endpush