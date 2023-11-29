<span class="btn btn-xs btn-info float-right mb-4 no-print" onClick="$('#report_content').printThis()">
    <i class="fa fa-print"></i>
    @lang('app.print')
</span>
<div class="mb-1 font-weight-bold text-uppercase">@lang('app.date') : {{ format_date(date('Y-m-d')) }}</div>
@if(isset($client))
    <div class="mb-1 font-weight-bold text-uppercase">@lang('app.client') : {{ $client->name ?? 'All' }}</div>
@endif    
@if(isset($category))
    <div class="mb-1 font-weight-bold text-uppercase">@lang('app.category') : {{ $category->name ?? 'All' }}</div>
@endif 
@if(isset($from_date) && $from_date != '' && isset($to_date) && $to_date != '')
    <div class="mb-3 font-weight-bold text-uppercase">@lang('app.from') : {{ format_date($from_date) }} @lang('app.to') : {{ format_date($to_date) }}</div>
@endif
<div class="text-center mb-1 font-weight-bold text-uppercase"><u>{{ $report_type ?? null }}</u></div>