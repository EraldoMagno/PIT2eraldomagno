<script type="text/javascript">
var tmp_item_row = null;
$(function(){
    calcTotals();
    $( document ).on("click change paste keyup", ".calc_event", function() {
        calcTotals();
    });
    $(document).on('click','#estimate-add-row',function(){
        addRow();
    });
    $( document ).on('click', '#btn_convert_to_invoice', function() {
        var $this = $(this);
        bootbox.confirm({
            title: '<i class="fa fa-exclamation-triangle"></i> {{ trans('app.make_invoice') }}',
            message: '{{ trans('app.convert_estimate_to_estimate_msg') }}',
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
                    $.post("{{url('estimates/makeInvoice') }}", { "_token": "{{ csrf_token() }}", id : $this.attr('data-id') } , 'json').done(function(data){
                        if(data.redirectTo){
                            window.location = data.redirectTo;
                        }
                    }).fail(function(jqXHR, json, errorThrown){
                        var response = jqXHR.responseJSON;
                        var errorStr = response.message;
                        showErrorNotification(errorStr, 'error');
                    }).always(function(){
                        bootbox.hideAll();
                    });
                }
            }
        });
    });
    $(document).on('submit','#estimate_form', function (e) {
        var form = $('#estimate_form');
        form.addClass('spinner');
        var data = new FormData(form[0]);
        $.ajax({
            type: "POST",
            url: form.attr('action'),
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            beforeSend : function(){
                form.find('button[type=submit]').button('loading');
                form.find('#estimate_errors').remove();
            },
            success : function(data, textStatus, jqXHR){
                if(data.errors){
                    return;
                }
                if(data.redirectTo){
                    window.location = data.redirectTo;
                }else {
                    window.location.reload();
                }
            },
            error : function(jqXHR, json, errorThrown){
                var response = jqXHR.responseJSON;
                var errors = response.errors;
                var errorStr = '<h6>'+response.message+'</h6>';
                form.find('.form-group').removeClass('has-error');
                $.each( errors, function( key, value ) {
                    $(':input[name="'+key+'"]').parent().addClass("has-error");
                    errorStr += '- ' + value[0] + '<br/>';
                });
                var errorsHtml= '<div class="col-sm-12" id="estimate_errors">'+errorMessage(errorStr)+'</div>';
                form.prepend(errorsHtml);
                scrollToElement($('#error_div'));
            },
            complete : function(){
                form.removeClass('spinner');
                form.find('button[type=submit]').button('reset');
            }
        });
        return false;
    });
    $(document).on('click', '.estimate-remove-row', function (e) {
        e.preventDefault();
        var countRows = $('#estimate-rows').find('.estimate_row').length;
        if (countRows > 1) {
            var $this = $(this);
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
                    var id = $this.parents('tr').find('.row_id').val();
                    if(id !== ''){
                        $.post("{{url('estimates/deleteItem') }}", { "_token": "{{ csrf_token() }}", id : id } , 'json').done(function(data){
                            $this.closest('.estimate_row').remove();
                            recalculateRows();
                            calcTotals();
                        }).fail(function(jqXHR, json, errorThrown){
                            var response = jqXHR.responseJSON;
                            var errorStr = response.message;
                            showErrorNotification(errorStr, 'error');
                        }).always(function(){
                            bootbox.hideAll();
                        });
                    }else{
                        $this.closest('.estimate_row').remove();
                        recalculateRows();
                        calcTotals();
                    }    
                }
            }
        });
    }
    });
    $(document).on('click', '.search-product', function (e) {
        e.preventDefault();
        tmp_item_row = $(this).closest('.estimate_row');
        searchProduct();
    });
    $(document).on('click','#select-products-confirm',function(e){
        e.preventDefault();
        addProduct();
    });
});
function searchProduct() {
    var modal = $('#ajax-modal');
    $('#loader').show();
    var dataUrl = "{{ route('products.modal') }}";
    modal.load(dataUrl, function (result) {
        $('#loader').hide();
        var t = $('.datatable').DataTable({
            "columnDefs": [ {
                "searchable": false,
                "orderable": false,
                "targets": 0
            } ],
            "order": [[ 1, 'asc' ]],
            "bLengthChange": false,
            "bInfo" : false,
            "filter" : true,
            'paging': false,
            "oLanguage": { "sSearch": ""}
        });
        $('div.dataTables_filter input').addClass('form-control input-sm');
        $('[data-toggle="popover"]').popover();
        modal.modal('show');
    });
}
function addProduct(productId){
    var product_lookup_id = $("input[name='product_lookup_id']:checked").val();
    $('#select-products-confirm').button('loading');
    $.post("{{ route('products.process_selection') }}", {
        product_lookup_id : product_lookup_id
    }).done(function(data){
        var product = data.product;
        var row = tmp_item_row;
        row.find('.row_product_id').val(product.uuid);
        row.find('.row_product_name').val(product.name);
        row.find('.row_price').val(product.price);
        row.find('.row_quantity').val(product.quantity).trigger('change');
    }).always(function(){
        $('#select-products-confirm').button('reset');
        $('#ajax-modal').modal('toggle');
    });
}
function addRow() {
    var estimate_rows_table = $('#estimate-rows');
    var clone = estimate_rows_table.find('.estimate_row:first').clone();
    var countRows = estimate_rows_table.find('.estimate_row').length;
    clone.find('.form-line').removeClass('focused');
    clone.find('.row_line_total').html(0);
    clone.find('input,select').each(function () {
        this.name = this.name.replace('[0]', '[' + countRows + ']');
    });
    clone.find('input').val('');
    clone.find('input').attr('value', '');
    clone.insertAfter($('#estimate-rows tbody .estimate_row:last'));
    recalculateRows();
    calcTotals();
}
function recalculateRows () {
    $('#estimate-rows').find('.estimate_row').each(function (counter, element) {
        $(element).find("input[name$='[item_id]']").attr('name', 'items[' + counter + '][item_id]');
        $(element).find("input[name$='[uuid]']").attr('name', 'items[' + counter + '][uuid]');
        $(element).find("input[name$='[item_name]']").attr('name', 'items[' + counter + '][item_name]');
        $(element).find("input[name$='[price]']").attr('name', 'items[' + counter + '][price]');
        $(element).find("input[name$='[quantity]']").attr('name', 'items[' + counter + '][quantity]');
        $(element).find("select[name$='[tax_id]']").attr('name', 'items[' + counter + '][tax_id]');
        $(element).find(".line-total-element").attr('id', 'items[' + counter + '][lineTotal]');
    });
}
function calcTotals(){
    var subTotal    = 0;
    var total       = 0;
    var totalTax    = 0;
    $.each($('tr.estimate_row'), function(){
        var quantity    = parseFloat($(this).find('.row_quantity').val());
        var price       = parseFloat($(this).find('.row_price').val());
        var itemTax     = parseFloat($(this).find(".row_tax option:selected").data('value'));
        var itemTotal   = parseFloat(quantity * price) > 0 ? parseFloat(quantity * price) : 0;
        $(this).find('.row_line_total').html(format_amount(itemTotal));
        totalTax        += (itemTotal * itemTax) > 0 ? itemTotal * itemTax : 0;
        subTotal        += itemTotal;
    });
    var discount_mode = parseInt($("[name='discount_mode']").val());
    var discount    = parseFloat($("[name='discount']").val()) > 0 ? parseFloat($("[name='discount']").val()) : 0;
    var discount_amount = discount_mode === 1 ? subTotal * (discount/100) : discount;
    total    += parseFloat(subTotal+totalTax-discount_amount);
    $('.summary_subtotal').html(format_amount(subTotal));
    $('.summary_tax').html(format_amount(totalTax));
    $('.summary_gross').html(format_amount(total));
}
function format_amount(amount){
    var currency_symbol = $("[name='currency'] option:selected").val();
    return accounting.formatMoney(amount,currency_symbol)
}
</script>

