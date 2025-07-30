function avoidDuplication()
{    
    $('input[type="checkbox"]').on('change', function() {
        $('body').animate({ opacity: 0.5 }, "slow");
        var property_id = $(this).attr('id');
        if ($(this).is(":checked")) {
            var avoidFlag = 1;
        } else {
            var avoidFlag = 0;
        }
        $.ajax({
            url: base_url+"update-avoid-duplication-flag",
            method: "POST",
            data : {
                property_id: property_id,
                avoidFlag: avoidFlag
            },
            success: function(data){
                var result = jQuery.parseJSON(data);
                if (result.status == 'success') {
                    $('body').animate({ opacity: 1.0 }, "slow");
                    $('#order_success_msg').html(result.msg).show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#order_success_msg").offset().top
                    }, 1000);
                    order_list.ajax.reload( null, false );
                    setTimeout(function () {
                        $('#order_success_msg').html('').hide();
                    }, 4000);
                } else {
                    $('#order_error_msg').html(result.message).show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#order_error_msg").offset().top
                    }, 1000);

                    setTimeout(function () {
                        $('#order_error_msg').html('').hide();
                    }, 4000);
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                $('#order_error_msg').html('Something went wrong. Please try it again.').show();
                $([document.documentElement, document.body]).animate({
                    scrollTop: $("#order_success_msg").offset().top
                }, 1000);

                setTimeout(function () {
                    $('#order_error_msg').html('').hide();
                }, 4000);
            }
        });
    });
}

function preview_email(notificationId)
{
    $('#page-preloader').css('background-color', 'rgba(0,0,0,.5)');
    $('#page-preloader').css('display', 'block');
    $.ajax({
        url:base_url+"admin/order/home/email_preview",
        type: "post",
        data: {
            notificationId: notificationId
        },
        dataType: "html",
        success: function (response) {
            var results = JSON.parse(response);
            $('#mail_preview').html(results);
            $('#email_preview').modal('show');
            $('#page-preloader').css('display', 'none');
        }
    });
}