function changeLenderUserType(id, selectValue) 
{
    $.ajax({
        url: base_url+'admin/order/home/changeLenderUserType',
        type: "POST",
        data: {
            user_id: id,
            selectValue: selectValue
        },
        async: false,
        success: function (data) {
            var res = jQuery.parseJSON(data);
            if (res.success === true) {
                $('body').animate({ opacity: 1.0 }, "slow");
                $('#customer_success_msg').html('Lender user type updated successfully.').show();
                $([document.documentElement, document.body]).animate({
                    scrollTop: $("#customer_success_msg").offset().top
                }, 1000);
                
                setTimeout(function () {
                    $('#customer_success_msg').html('').hide();
                }, 4000);
            } else {
                $('#customer_error_msg').html('Lender User Type failed due to some error. Please try again.').show();
                $([document.documentElement, document.body]).animate({
                    scrollTop: $("#customer_error_msg").offset().top
                }, 1000);

                setTimeout(function () {
                    $('#customer_error_msg').html('').hide();
                }, 4000);
            }
        }
    });
}

function isMortgageUser()
{    
    $('input[type="checkbox"]').on('change', function() {
        $('body').animate({ opacity: 0.5 }, "slow");
        var user_id = $(this).attr('id');
        if ($(this).is(":checked")) {
            var mortgageUserFlag = 1;
        } else {
            var mortgageUserFlag = 0;
        }
        $.ajax({
            url: base_url+"update-mortgage-user",
            method: "POST",
            data : {
                user_id: user_id,
                mortgageUserFlag: mortgageUserFlag
            },
            success: function(data){
                var result = jQuery.parseJSON(data);
                if (result.status == 'success') {
                    $('body').animate({ opacity: 1.0 }, "slow");
                    $('#customer_success_msg').html(result.msg).show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#customer_success_msg").offset().top
                    }, 1000);
                    customer_list.ajax.reload( null, false );
                    setTimeout(function () {
                        $('#customer_success_msg').html('').hide();
                    }, 4000);
                } else {
                    $('#customer_error_msg').html(result.message).show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#customer_error_msg").offset().top
                    }, 1000);

                    setTimeout(function () {
                        $('#customer_error_msg').html('').hide();
                    }, 4000);
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                $('#customer_error_msg').html('Something went wrong. Please try it again.').show();
                $([document.documentElement, document.body]).animate({
                    scrollTop: $("#customer_success_msg").offset().top
                }, 1000);

                setTimeout(function () {
                    $('#customer_error_msg').html('').hide();
                }, 4000);
            }
        });
    });
}