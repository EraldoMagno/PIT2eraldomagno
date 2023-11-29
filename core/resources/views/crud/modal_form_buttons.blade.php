<div class="modal-footer justify-content-between col-sm-12 px-0">
    {!! Form::button('<i class="fa fa-times"></i> '.trans('app.close'),['class' => 'btn btn-sm btn-danger float-left','data-dismiss'=>'modal']); !!}
    {!! Form::button('<i class="fa fa-save"></i> '.trans('app.save'),['class' => 'btn btn-sm btn-success float-right mr-2','data-loading-text'=>"<i class='fa fa-spin fa-spinner'></i> ".trans('app.processing'), 'type'=>'submit']); !!}
</div>
