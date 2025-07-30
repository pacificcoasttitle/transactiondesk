function enablePayoffUser() {
    $('input[type="checkbox"]').on('change', function () {
        $('body').animate({ opacity: 0.5 }, "slow");
        var user_id = $(this).attr('id');
        if ($(this).is(":checked")) {
            var status = 1;
        } else {
            var status = 0;
        }
        $.ajax({
            url: base_url + "order/admin/update-user-status",
            method: "POST",
            data: {
                user_id: user_id,
                status: status
            },
            success: function (data) {
                var result = jQuery.parseJSON(data);
                if (result.status == 'success') {
                    $('body').animate({ opacity: 1.0 }, "slow");
                    $('#payoff_user_success_msg').html(result.msg).show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#payoff_user_success_msg").offset().top
                    }, 1000);
                    payoff_user_list.ajax.reload(null, false);
                    setTimeout(function () {
                        $('#payoff_user_success_msg').html('').hide();
                    }, 4000);
                } else {
                    $('#payoff_user_error_msg').html(result.message).show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#payoff_user_error_msg").offset().top
                    }, 1000);

                    setTimeout(function () {
                        $('#payoff_user_error_msg').html('').hide();
                    }, 4000);
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                $('#payoff_user_error_msg').html('Something went wrong. Please try it again.').show();
                $([document.documentElement, document.body]).animate({
                    scrollTop: $("#payoff_user_success_msg").offset().top
                }, 1000);

                setTimeout(function () {
                    $('#payoff_user_error_msg').html('').hide();
                }, 4000);
            }
        });
    });
}