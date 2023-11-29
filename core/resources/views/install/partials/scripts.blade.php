{!! Html::script(asset('assets/js/jquery.min.js')) !!}
{!! Html::script(asset('assets/js/bootstrap.min.js')) !!}
{!! Html::script(asset('assets/js/validator.min.js')) !!}
{!! Html::script(asset('assets/plugins/backstretch-js/jquery.backstretch.min.js')) !!}
<script>
    $(function(){
        $.backstretch("{{asset('assets/images/bg.jpg')}}");
        showListItems();
        $('form').validator().on('submit', function (e) {
            if (e.isDefaultPrevented()) {
                $(this).removeClass('spinner');
            } else {
                $(this).addClass('spinner');
            }
        });
    });
    var i = 0;
    function showListItems() {
        $("ul li:hidden:first").fadeIn("slow", function() {
            i=i+1;
            var result = setTimeout(showListItems, 500);
            if(i==8){
                $('#box-footer').removeClass('hide');
            }
        });
    }
</script>