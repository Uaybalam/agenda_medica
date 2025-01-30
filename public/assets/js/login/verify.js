$('#verify').click(function () {
    var code = $('#code').val();

    $.ajax({
        url: '/login/verify2fa',
        type:'post',
        data: {'code': code} ,
        dataType:'json',
        success:function( response ){
            if(response.status)
            {
                location.href = '/appointment/book';
            } 
            else
            {
            	Notify.error("Invalid Code");
            }
        }
    });
 });