@extends('app')
@section('content')
<div class="col-md-12 content-header" >
    <h5><i class="fa fa-{{ $headingIcon ?? null }}"></i> {{ $heading ?? null }}</h5>
</div>
<section class="content">
    <div class="row">
        <div class="col-md-3">
            @include('settings.partials._menu')
        </div>
        <div class="col-md-9">
            <div class="card border-top-primary">
                <div class="card-body">
                    @if(isset($dataTable))
                        @if(hasPermission('add_client') && $showBtnCreate)
                        <div class="form-group text-right">
                            {!! btnCreate($routes['create'], $createDisplayMode, $btnCreateText,$iconCreate) !!}
                        </div>
                        @endif
                        <div class="table-responsive">
                            {!! $dataTable->table(['width' => '100%']) !!}
                        </div>
                    @else
                        {!! form($form) !!}
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@push('scripts')
    @if(isset($dataTable))
        {!! $dataTable->scripts() !!}
    @endif
@endpush


