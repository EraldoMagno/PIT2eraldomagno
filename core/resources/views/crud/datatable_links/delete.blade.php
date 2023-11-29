{!! Form::open(['route' => [$delete_route,$entityId], 'method' => 'DELETE','class'=>'delete-frm']) !!}
{!! Form::button('<i class="fas fa-trash"></i> ', [
    'type' => 'submit',
    'class' => 'btn btn-sm btn-danger delete_btn',
    'title' => trans('app.deleting_record'),
    'data-message'=>trans('app.delete_confirmation_msg'),
    'data-confirm'=>trans('app.yes'),
    'data-cancel'=>trans('app.no')
]) !!}
{!! Form::close() !!}
