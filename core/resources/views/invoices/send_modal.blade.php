@extends('modal')
@section('content')
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header bg-primary">
            <h6 class="modal-title"><i class="fa fa-paper-plane"></i> {{trans('app.send_invoice')}}</h6>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-sm-12">
                    {!! Form::open(['route' => ['email_invoice'], 'class' => 'ajax-submit']) !!}
                    <div class="form-group">
                        {{Form::hidden('invoice_id',$invoice->uuid)}}
                        {!! Form::label('email', trans('app.email').'*') !!}
                        {!! Form::text('email', $invoice->client ? $invoice->client->email : null, ['class' => 'form-control input-sm', 'required']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('subject', trans('app.subject').'*') !!}
                        {!! Form::text('subject', $template->subject ?? '', ['class' => 'form-control input-sm', 'required']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('message', trans('app.message').'*') !!}
                        {!! Form::textarea('message', $template->body ?? '', ['class' => 'form-control text_editor', 'required']) !!}
                    </div>
                    <div class="modal-footer justify-content-between px-0">
                        {{ Form::button('<i class="fa fa-paper-plane"></i> '.trans('app.send'), ['type' => 'submit', 'class' => 'btn btn-success btn-sm'] )  }}
                        {{ Form::button('<i class="fa fa-times"></i> '.trans('app.close'), ['class' => 'btn btn-danger btn-sm','data-dismiss'=>'modal'] )  }}
                    </div>
                {!! Form::close() !!}
                </div>
                <div class="col-sm-12">
                    @include('common.tags')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection