@extends('app')
@push('styles')
{{ Html::style(asset('assets/css/morris.min.css')) }}
@endpush
@section('content')
    <div class="col-md-12 content-header" >
        <h5><i class="fa fa-line-chart"></i> {{trans('app.reports')}}</h5>
    </div>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card border-top-primary">
                    <div class="card-header">
                        <div class="row">
                            @include('reports.partials.top_nav')
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row" id="report-body"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    {{ Html::script(asset('assets/plugins/printthis/printThis.js')) }}
    @include('reports.partials.reports_js')
@endpush