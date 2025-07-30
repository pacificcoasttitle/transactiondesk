function isMortgagePrimaryUser()
{    
    $('input[type="checkbox"]').on('change', function() {
        $('body').animate({ opacity: 0.5 }, "slow");
        var user_id = $(this).attr('id');
        if ($(this).is(":checked")) {
            var primaryMortgageUserFlag = 1;
        } else {
            var primaryMortgageUserFlag = 0;
        }
        $.ajax({
            url: base_url+"is-mortgage-primary-user",
            method: "POST",
            data : {
                user_id: user_id,
                primaryMortgageUserFlag: primaryMortgageUserFlag
            },
            success: function(data){
                var result = jQuery.parseJSON(data);
                if (result.status == 'success') {
                    $('body').animate({ opacity: 1.0 }, "slow");
                    $('#mortgage_success_msg').html(result.msg).show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#mortgage_success_msg").offset().top
                    }, 1000);
                    customer_list.ajax.reload( null, false );
                    setTimeout(function () {
                        $('#mortgage_success_msg').html('').hide();
                    }, 4000);
                } else {
                    $('#mortgage_error_msg').html(result.message).show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#mortgage_error_msg").offset().top
                    }, 1000);

                    setTimeout(function () {
                        $('#mortgage_error_msg').html('').hide();
                    }, 4000);
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                $('#mortgage_error_msg').html('Something went wrong. Please try it again.').show();
                $([document.documentElement, document.body]).animate({
                    scrollTop: $("#mortgage_success_msg").offset().top
                }, 1000);

                setTimeout(function () {
                    $('#mortgage_error_msg').html('').hide();
                }, 4000);
            }
        });
    });
}