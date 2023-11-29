@extends('clientarea.app')
@section('content')
<div class="col-md-12 content-header" >
    <h6><i class="fa fa-{{ $headingIcon ?? null }}"></i> {{ $heading ?? null }}</h6>
</div>
<section class="content">
<div class="row">
    <div class="col-md-12">
        <div class="card border-top-primary">
            <div class="card-body">
                {!! form($form) !!}
            </div>
        </div>
    </div>
</div>
</section>
@endsection

