<div class="d-flex">
    @if($email_route)
        <span class="btn btn-link btn-primary btn-lg" data-toggle="{{ $edit_display_mode == 'modal' ? 'ajax-modal' : 'normal' }}" data-url="{{ route($email_route,$entityId) }}"><i class="fa fa-paper-plane"></i></span>
    @endif
    @if($permissionRoute)
        <a href="{{route($permissionRoute,$entityId)}}" data-toggle="{{ $show_display_mode == 'modal' ? 'ajax-modal' : 'normal' }}" class="btn btn-warning btn-xs mr-2"><i class="fa fa-cog"></i> @lang('app.permissions')</a>
    @endif
    @if($download_route)
        <a href="{{route($download_route,$entityId)}}" class="btn btn-xs btn-warning mr-2" target="_blank"><i class="fa fa-download"></i> Download</a>
    @endif
    @if($payment_route && $record->totals['amountDue'] > 0) 
        <a href="{{route($payment_route,['invoice_id'=>$entityId])}}" data-toggle="ajax-modal" class="btn btn-xs btn-success mr-2"><i class="fa fa-usd"></i> @lang('app.add_payment')</a>
    @endif
    @if($show_route)
    <a href="{{route($show_route,$entityId)}}" data-toggle="{{ $show_display_mode == 'modal' ? 'ajax-modal' : 'normal' }}" class="btn btn-xs btn-info mr-2"><i class="fa fa-eye"></i> @lang('app.view')</a>
    @endif
    @if($edit_permission) 
        <a href="{{route($edit_route,$entityId)}}" data-toggle="{{ $edit_display_mode == 'modal' ? 'ajax-modal' : 'normal' }}" class="btn btn-xs btn-primary mr-2"><i class="fa fa-edit"></i> @lang('app.edit')</a>
    @endif
    @if($translation_route) 
        <a href="{{route($translation_route,['groupKey'=>'app','locale'=>$record->short_name])}}" data-toggle="normal" class="btn btn-xs btn-warning mr-2"><i class="fa fa-eye"></i> @lang('app.view_translations')</a>
    @endif
    @if($delete_permission) 
        {!! Form::open(['route' => [$delete_route,$entityId], 'method' => 'DELETE','class'=>'delete-frm form-inline']) !!}
            {!! Form::button('<i class="fa fa-times"></i> Delete', [
                'type' => 'submit',
                'class' => 'btn btn-danger btn-xs delete_btn',
                'title' => trans('app.deleting_record'),
                'data-message'=>trans('app.delete_confirmation_msg'),
                'data-confirm'=>trans('app.yes'),
                'data-cancel'=>trans('app.no'),
                'data-toggle'=>'tooltip',
                'data-original-title'=>trans('app.remove'),
            ]) !!}
        {!! Form::close() !!}
    @endif
</div>
