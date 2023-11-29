<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header bg-primary">
            <h6 class="modal-title"><i class="fa fa-cogs"></i> @lang('app.assign_permissions')</h6>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
            {!! Form::model($role, ['url' => 'settings/assignPermission', 'class'=>"ajax-submit"]) !!}
                <div class="form-group">
                    {!! Form::label('name', trans('app.role')) !!}
                    {!! Form::hidden('role_id', $role->uuid) !!}
                    <p>{{$role->name}}</p>
                </div>
                <div class="form-group">
                    <table class="table">
                        <tr>
                            <th>{{trans('app.name')}}</th>
                            <th>{{trans('app.description')}}</th>
                            <th>{{trans('app.assign')}}</th>
                        </tr>
                        @foreach($permissions as $permission)
                            <tr>
                                <td>{{$permission->name}}</td>
                                <td>{{$permission->description}}</td>
                                <td>{!! Form::checkbox($permission->name, $permission->uuid, $role->permissions->contains('name', $permission->name) ? true : null ) !!} </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
                @include('crud.modal_form_buttons')
            {!! Form::close() !!}
        </div>
    </div>
</div>