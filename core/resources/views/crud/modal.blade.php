<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header bg-primary">
            <h6 class="modal-title"><i class="fa fa-{{ $headingIcon ?? null }}"></i> {{$heading ?? null}}</h6>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
            {!! form($form) !!}
        </div>
    </div>
</div>