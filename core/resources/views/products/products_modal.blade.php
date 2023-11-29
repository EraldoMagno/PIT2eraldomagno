<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header bg-gray">
            <h6 class="modal-title"><i class="fa fa-plus"></i> @lang('app.select_product')</h6>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-sm-12 table-responsive">
                    <table class="table table-bordered table-striped table-hover datatable">
                        <thead class="item-table-header">
                        <tr>
                            <th></th>
                            <th>@lang('app.image')</th>
                            <th>@lang('app.name')</th>
                            <th>@lang('app.code')</th>
                            <th>@lang('app.category')</th>
                            <th>@lang('app.price')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($products as $count=>$product)
                            <tr>
                                <td> {!! Form::radio('product_lookup_id',$product->uuid,$count==0 ?: false) !!}</td>
                                <td>
                                    @if($product->image != '')
                                        <a href="#" data-toggle="popover" data-trigger="hover" title="{{ $product->name }}" data-html="true" data-content="{!! htmlentities(Html::image(asset(config('app.uploads_path').'product_images/'.$product->image), 'image')) !!}">{!! Html::image(asset(config('app.uploads_path').'product_images/'.$product->image), 'image', array('style'=>'width:50px')) !!}</a>
                                    @else
                                        {!! Html::image(config('app.uploads_path').'product_images/no-product-image.png', 'image', array('style'=>'width:50px')) !!}
                                    @endif
                                </td>
                                <td>{{ $product->name }} </td>
                                <td>{{ $product->code }} </td>
                                <td>{{ $product->category ? $product->category->name : '' }} </td>
                                <td>{{ $product->price }} </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                {!! Form::button(trans('app.add_product'),['class'=>'btn btn-sm btn-success pull-left', 'id'=>'select-products-confirm','data-loading-text'=>"<i class='fa fa-spin fa-spinner'></i> ".trans('core.form.processing')] ) !!}
                {!! Form::button(trans('app.cancel'),['class'=>'btn btn-sm btn-default','data-dismiss'=>'modal'] ) !!}
            </div>
        </div>
    </div>
</div>
