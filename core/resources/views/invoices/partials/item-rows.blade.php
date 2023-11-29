<div class="col-lg-12 col-md-12 col-xs-12 panel-content">
    <div class="form-group">
        <span id="invoice-add-row" class="btn btn-sm btn-info pointer">
            <i class="fa fa-plus"></i> @lang('app.add_row')
        </span>
    </div>
    <div class="table-responsive">
        <table id="invoice-rows" class="table table-condensed">
            <thead class="item-table-header">
            <tr>
                <th></th>
                <th>@lang('app.product')</th>
                <th>@lang('app.quantity')</th>
                <th>@lang('app.price')</th>
                <th>@lang('app.tax')</th>
                <th class="text-right">@lang('app.total')</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($options['children'] as $row)
                <tr class="invoice_row">
                    <td>
                        <div class="form-group">
                            <span class="bg-white pl-0 border-0">
                                <i title="@lang('app.search_product')" role="button" class="fa fa-search search-product"></i>
                            </span>
                        </div>
                    </td>
                    <td>
                        {!! form_row($row->uuid) !!}
                        {!! form_row($row->item_id) !!}
                        {!! form_row($row->item_name) !!}
                    </td>
                    <td>{!! form_row($row->quantity) !!}</td>
                    <td>{!! form_row($row->price) !!}</td>
                    <td>{!! form_row($row->tax_id) !!}</td>
                    <td class="text-right font-weight-bold">{!! form_row($row->itemTotal) !!}</td>
                    <td><div class="form-group"><i class="fa fa-times invoice-remove-row" role="button"></i></div></td>
                </tr>
            @endforeach
            <tr>
                <td colspan="4" rowspan="7"></td>
                <th>@lang('app.subtotal')</th>
                <td class="summary_subtotal text-right font-weight-bold"></td>
                <td></td>
            </tr>
            <tr>
                <th>@lang('app.tax')</th>
                <td class="summary_tax text-right font-weight-bold"></td>
            </tr>
            <tr>
                <th style="vertical-align: middle">@lang('app.discount')</th>
                <td class="text-right">
                    <div class="input-group">
                        {!! Form::select('discount_mode',array('1'=>'%','0'=>'Flat'), $options['discount_mode'],['class' => 'form-control-sm calc_event', 'id' => 'discount_mode']) !!}
                        {!! Form::input('number','discount', $options['discount'],['class' => 'form-control form-control-sm text-right calc_event', 'id' => 'discount', 'step'=>'any', 'min'=>'0']) !!}
                    </div>
                </td>
            </tr>
            <tr>
                <th>@lang('app.total')</th>
                <td class="summary_gross text-right font-weight-bold"></td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
