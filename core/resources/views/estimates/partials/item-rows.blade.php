<div class="col-lg-12 col-md-12 col-xs-12 panel-content">
    <div class="form-group">
        <span id="estimate-add-row" class="btn btn-sm btn-info pointer"><i class="fa fa-plus"></i> @lang('app.add_row')</span>
    </div>
    <div class="table-responsive  col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <table id="estimate-rows" class="table table-condensed">
            <thead class="item-table-header">
                <tr>
                    <th></th>
                    <th>@lang('app.product')</th>
                    <th>@lang('app.quantity')</th>
                    <th>@lang('app.price')</th>
                    <th>@lang('app.tax')</th>
                    <th>@lang('app.total')</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            @foreach($options['children'] as $row)
                <tr class="estimate_row">
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
                    <td class="text-right font-weight-bold">{!! form_row($row->lineTotal) !!}</td>
                    <td><div class="form-group"><i class="fa fa-times estimate-remove-row" role="button"></i></div></td>
                </tr>
            @endforeach
            <tr>
                <td colspan="4" rowspan="7"></td>
                <td>
                    <label class="show-control-label text-right">
                        @lang('app.subtotal')
                    </label>
                </td>
                <td class="summary_subtotal text-right font-weight-bold"></td>
                <td></td>
            </tr>
            <tr>
                <td>
                    <label class="show-control-label text-right">
                        @lang('app.tax')
                    </label>
                </td>
                <td class="summary_tax text-right font-weight-bold"></td>
            </tr>
            <tr>
                <td>
                    <label class="show-control-label text-right">
                        @lang('app.total')
                    </label>
                </td>
                <td class="summary_gross text-right font-weight-bold"></td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
