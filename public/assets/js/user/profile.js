$('#g2fa').change(function () {
    var checked = $('#g2fa').is(':checked') ? 1 : 0;

    $.ajax({
        url: '/user/update2fa',
        type:'post',
        data: {'active2fa': checked} ,
        dataType:'json',
        success:function( response ){
            if(response.status)
            {
                $("#content-qr").css("display","block");
                $("#content-qr img")[0].src = response.qr;
            }
            else
            {
                $("#content-qr").css("display","none");
                $("#content-qr img")[0].src = "";
            }
        }
    });
 });