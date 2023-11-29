
{{ Html::script('assets/plugins/bootstrap-editable/bootstrap-editable.min.js') }}
<script>
    (function(e,t){if(e.rails!==t){e.error("jquery-ujs has already been loaded!")}var n;var r=e(document);e.rails=n={linkClickSelector:"a[data-confirm], a[data-method], a[data-remote], a[data-disable-with]",buttonClickSelector:"button[data-remote], button[data-confirm]",inputChangeSelector:"select[data-remote], input[data-remote], textarea[data-remote]",formSubmitSelector:"form",formInputClickSelector:"form input[type=submit], form input[type=image], form button[type=submit], form button:not([type])",disableSelector:"input[data-disable-with], button[data-disable-with], textarea[data-disable-with]",enableSelector:"input[data-disable-with]:disabled, button[data-disable-with]:disabled, textarea[data-disable-with]:disabled",requiredInputSelector:"input[name][required]:not([disabled]),textarea[name][required]:not([disabled])",fileInputSelector:"input[type=file]",linkDisableSelector:"a[data-disable-with]",buttonDisableSelector:"button[data-remote][data-disable-with]",CSRFProtection:function(t){var n=e('meta[name="csrf-token"]').attr("content");if(n)t.setRequestHeader("X-CSRF-Token",n)},refreshCSRFTokens:function(){var t=e("meta[name=csrf-token]").attr("content");var n=e("meta[name=csrf-param]").attr("content");e('form input[name="'+n+'"]').val(t)},fire:function(t,n,r){var i=e.Event(n);t.trigger(i,r);return i.result!==false},confirm:function(e){return confirm(e)},ajax:function(t){return e.ajax(t)},href:function(e){return e.attr("href")},handleRemote:function(r){var i,s,o,u,a,f,l,c;if(n.fire(r,"ajax:before")){u=r.data("cross-domain");a=u===t?null:u;f=r.data("with-credentials")||null;l=r.data("type")||e.ajaxSettings&&e.ajaxSettings.dataType;if(r.is("form")){i=r.attr("method");s=r.attr("action");o=r.serializeArray();var h=r.data("ujs:submit-button");if(h){o.push(h);r.data("ujs:submit-button",null)}}else if(r.is(n.inputChangeSelector)){i=r.data("method");s=r.data("url");o=r.serialize();if(r.data("params"))o=o+"&"+r.data("params")}else if(r.is(n.buttonClickSelector)){i=r.data("method")||"get";s=r.data("url");o=r.serialize();if(r.data("params"))o=o+"&"+r.data("params")}else{i=r.data("method");s=n.href(r);o=r.data("params")||null}c={type:i||"GET",data:o,dataType:l,beforeSend:function(e,i){if(i.dataType===t){e.setRequestHeader("accept","*/*;q=0.5, "+i.accepts.script)}if(n.fire(r,"ajax:beforeSend",[e,i])){r.trigger("ajax:send",e)}else{return false}},success:function(e,t,n){r.trigger("ajax:success",[e,t,n])},complete:function(e,t){r.trigger("ajax:complete",[e,t])},error:function(e,t,n){r.trigger("ajax:error",[e,t,n])},crossDomain:a};if(f){c.xhrFields={withCredentials:f}}if(s){c.url=s}return n.ajax(c)}else{return false}},handleMethod:function(r){var i=n.href(r),s=r.data("method"),o=r.attr("target"),u=e("meta[name=csrf-token]").attr("content"),a=e("meta[name=csrf-param]").attr("content"),f=e('<form method="post" action="'+i+'"></form>'),l='<input name="_method" value="'+s+'" type="hidden" />';if(a!==t&&u!==t){l+='<input name="'+a+'" value="'+u+'" type="hidden" />'}if(o){f.attr("target",o)}f.hide().append(l).appendTo("body");f.submit()},formElements:function(t,n){return t.is("form")?e(t[0].elements).filter(n):t.find(n)},disableFormElements:function(t){n.formElements(t,n.disableSelector).each(function(){n.disableFormElement(e(this))})},disableFormElement:function(e){var t=e.is("button")?"html":"val";e.data("ujs:enable-with",e[t]());e[t](e.data("disable-with"));e.prop("disabled",true)},enableFormElements:function(t){n.formElements(t,n.enableSelector).each(function(){n.enableFormElement(e(this))})},enableFormElement:function(e){var t=e.is("button")?"html":"val";if(e.data("ujs:enable-with"))e[t](e.data("ujs:enable-with"));e.prop("disabled",false)},allowAction:function(e){var t=e.data("confirm"),r=false,i;if(!t){return true}if(n.fire(e,"confirm")){r=n.confirm(t);i=n.fire(e,"confirm:complete",[r])}return r&&i},blankInputs:function(t,n,r){var i=e(),s,o,u=n||"input,textarea",a=t.find(u);a.each(function(){s=e(this);o=s.is("input[type=checkbox],input[type=radio]")?s.is(":checked"):s.val();if(!o===!r){if(s.is("input[type=radio]")&&a.filter('input[type=radio]:checked[name="'+s.attr("name")+'"]').length){return true}i=i.add(s)}});return i.length?i:false},nonBlankInputs:function(e,t){return n.blankInputs(e,t,true)},stopEverything:function(t){e(t.target).trigger("ujs:everythingStopped");t.stopImmediatePropagation();return false},disableElement:function(e){e.data("ujs:enable-with",e.html());e.html(e.data("disable-with"));e.bind("click.railsDisable",function(e){return n.stopEverything(e)})},enableElement:function(e){if(e.data("ujs:enable-with")!==t){e.html(e.data("ujs:enable-with"));e.removeData("ujs:enable-with")}e.unbind("click.railsDisable")}};if(n.fire(r,"rails:attachBindings")){e.ajaxPrefilter(function(e,t,r){if(!e.crossDomain){n.CSRFProtection(r)}});r.delegate(n.linkDisableSelector,"ajax:complete",function(){n.enableElement(e(this))});r.delegate(n.buttonDisableSelector,"ajax:complete",function(){n.enableFormElement(e(this))});r.delegate(n.linkClickSelector,"click.rails",function(r){var i=e(this),s=i.data("method"),o=i.data("params"),u=r.metaKey||r.ctrlKey;if(!n.allowAction(i))return n.stopEverything(r);if(!u&&i.is(n.linkDisableSelector))n.disableElement(i);if(i.data("remote")!==t){if(u&&(!s||s==="GET")&&!o){return true}var a=n.handleRemote(i);if(a===false){n.enableElement(i)}else{a.error(function(){n.enableElement(i)})}return false}else if(i.data("method")){n.handleMethod(i);return false}});r.delegate(n.buttonClickSelector,"click.rails",function(t){var r=e(this);if(!n.allowAction(r))return n.stopEverything(t);if(r.is(n.buttonDisableSelector))n.disableFormElement(r);var i=n.handleRemote(r);if(i===false){n.enableFormElement(r)}else{i.error(function(){n.enableFormElement(r)})}return false});r.delegate(n.inputChangeSelector,"change.rails",function(t){var r=e(this);if(!n.allowAction(r))return n.stopEverything(t);n.handleRemote(r);return false});r.delegate(n.formSubmitSelector,"submit.rails",function(r){var i=e(this),s=i.data("remote")!==t,o,u;if(!n.allowAction(i))return n.stopEverything(r);if(i.attr("novalidate")==t){o=n.blankInputs(i,n.requiredInputSelector);if(o&&n.fire(i,"ajax:aborted:required",[o])){return n.stopEverything(r)}}if(s){u=n.nonBlankInputs(i,n.fileInputSelector);if(u){setTimeout(function(){n.disableFormElements(i)},13);var a=n.fire(i,"ajax:aborted:file",[u]);if(!a){setTimeout(function(){n.enableFormElements(i)},13)}return a}n.handleRemote(i);return false}else{setTimeout(function(){n.disableFormElements(i)},13)}});r.delegate(n.formInputClickSelector,"click.rails",function(t){var r=e(this);if(!n.allowAction(r))return n.stopEverything(t);var i=r.attr("name"),s=i?{name:i,value:r.val()}:null;r.closest("form").data("ujs:submit-button",s)});r.delegate(n.formSubmitSelector,"ajax:send.rails",function(t){if(this==t.target)n.disableFormElements(e(this))});r.delegate(n.formSubmitSelector,"ajax:complete.rails",function(t){if(this==t.target)n.enableFormElements(e(this))});e(function(){n.refreshCSRFTokens()})}})(jQuery)
</script>
<script>
    jQuery(document).ready(function($){

        $.ajaxSetup({
            beforeSend: function(xhr, settings) {
                console.log('beforesend');
                settings.data += "&_token=<?= csrf_token() ?>";
            }
        });
        $('.editable').editable().on('hidden', function(e, reason){
            var locale = $(this).data('locale');
            if(reason === 'save'){
                $(this).removeClass('status-0').addClass('status-1');
            }
            if(reason === 'save' || reason === 'nochange') {
                var $next = $(this).closest('tr').next().find('.editable.locale-'+locale);
                setTimeout(function() {
                    $next.editable('show');
                }, 300);
            }
        });
        $('.group-select').on('change', function(){
            var group = $(this).val();
            var locale = $('#locale').val();
            if (group) {
                window.location.href = '{{ url('language_translations') }}/'+group+'/'+locale;
            } else {
                window.location.href = '{{ url('language_translations') }}/application/'+locale;
            }
        });
        $("a.delete-key").click(function(e){
            e.preventDefault();
            var row = $(this).closest('tr');
            var url = $(this).attr('href');
            var id = row.attr('id');
            var msg = $(this).attr('data-msg');

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
                        $.post( url, {id: id}, function(){
                            row.remove();
                        });
                    }
                    
                }
            });
        });


        $('.form-import').on('ajax:success', function (e, data) {
            $('div.success-import strong.counter').text(data.counter);
            $('div.success-import').slideDown();
        });
        $('.form-find').on('ajax:success', function (e, data) {
            $('div.success-find strong.counter').text(data.counter);
            $('div.success-find').slideDown();
        });
        $('.form-publish').on('ajax:success', function (e, data) {
            $('div.success-publish').slideDown();
        });
        $(document).on('click', '#btn_update_exchange_rates', function(e){
            e.preventDefault();
            var parent_panel = $(this).parents('.box');
            $.ajax({
                type: "GET",
                url: '{{route('update_exchange_rates')}}',
                beforeSend : function(){
                    parent_panel.find('.alert').remove();
                    parent_panel.addClass('spinner');
                },
                success : function(data){
                    var succesHtml= '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' + data + '</div>';
                    parent_panel.find('.box-body').prepend(succesHtml);
                },
                complete : function(){
                    parent_panel.removeClass('spinner');
                }
            });
        });
        $(document).on('click', '#btn_save_currency_key', function(e){
            var key = $('input#currency_api_key').val();
            if(key.indexOf("*")) {
                $.ajax({
                    type: "POST",
                    url: '{{route('post_currency_key')}}',
                    data: {key: $('input#currency_api_key').val()},
                    beforeSend: function () {
                        $('#btn_save_currency_key').button('loading', 'Processing');
                        $('#api_div').find('.alert').remove();
                    },
                    success: function (data) {
                        var succesHtml = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' + data.message + '</div>';
                        $('#api_div').prepend(succesHtml);
                    },
                    complete: function () {
                        $('#btn_save_currency_key').button('reset');
                    }
                });
            }
        });
    })
</script>