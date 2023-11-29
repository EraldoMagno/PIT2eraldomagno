<script type="text/javascript">
    $(function(){
        if($('.table-responsive').length > 0){
            //Perfect Scrollbar
            new PerfectScrollbar('.table-responsive');
        }
        /*-----------------------------------------------------------
         Delete Button clicked
         --------------------------------------------------------------*/
        $(document).on('click', '.delete_btn', function(e) {
            e.preventDefault();
            var btn_delete = $(this);
            var delete_frm = btn_delete.parents('form');
            bootbox.confirm({
                title: '<i class="fa fa-exclamation-triangle"></i> {{ trans('app.deleting_record') }}',
                message: '{{ trans('app.delete_confirmation_msg') }}',
                buttons: {
                    cancel: {
                        label: '<i class="fa fa-times"></i> {{ trans('app.no') }}',
                        className: 'btn-danger btn-sm mr-auto'
                    },
                    confirm: {
                        label: '<i class="fa fa-check"></i> {{ trans('app.yes') }}',
                        className: 'btn-success btn-sm'
                    }
                },
                callback: function (result) {
                    if(result){
                        btn_delete.button('loading');
                        $.post(delete_frm.attr('action'), delete_frm.serialize(), function(response) {
                            if (response.type === 'success') {
                                if (response.action === 'refresh_datatable') {
                                    if (typeof window.LaravelDataTables !== "undefined") {
                                        $("#dataTableBuilder").DataTable().ajax.reload();
                                    } else {
                                        setTimeout(function() { window.location.reload(); }, 1000);
                                    }
                                } else if (response.action === 'reload') {
                                    setTimeout(function() { window.location.reload(); }, 1000);
                                } else if (response.action === 'redirect') {
                                    window.location.href = response.redirect_url;
                                } else if (response.action === 'show_msg') {
                                    showSuccessNotification(response.message, 'success');
                                    setTimeout(function() { window.location.reload(); }, 1000);
                                }
                                showSuccessNotification(response.message, 'success');
                            } else {
                                showErrorNotification(response.message, 'error');
                            }
                        }).fail(function(jqXHR, textStatus) {
                            var response = jqXHR.responseJSON;
                            var errorStr = response.message;
                            showErrorNotification(errorStr, 'error');
                        }).always(function() {
                            btn_delete.button('reset');
                        });
                    }
                    
                }
            });

    });
});
function showErrorNotification(text, icon) {
    Swal.fire({
        title: 'Error!',
        html: text,
        icon: icon
    });
}

function showSuccessNotification(text, icon) {
    Swal.fire({
        title: 'Success!',
        html: text,
        confirmButtonText: '<i class="fa fa-thumbs-up"></i> Great!',
        icon: icon
    });
}

function errorMessage(errorStr) {
    return '<div class="col-sm-12 alert alert-danger bg-danger text-white" id="error_div"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' + errorStr + '</div>';
}
</script>