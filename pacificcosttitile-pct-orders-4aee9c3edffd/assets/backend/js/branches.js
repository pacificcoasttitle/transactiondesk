$(document).ready(function () {
    $('#refresh_north_american_branches').click(function (e) {
        $('body').animate({ opacity: 0.5 }, "slow");
        $.ajax({
            url: base_url + "get-north-american-branches",
            method: "POST",
            success: function (data) {
                var result = jQuery.parseJSON(data);
                if (result.status == 'success') {
                    location.reload();
                } else {
                    $('#north_american_error_msg').html(result.message).show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#north_american_error_msg").offset().top
                    }, 1000);

                    setTimeout(function () {
                        $('#north_american_error_msg').html('').hide();
                    }, 4000);
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                $('#north_american_error_msg').html('Something went wrong. Please try it again.').show();
                $([document.documentElement, document.body]).animate({
                    scrollTop: $("#north_american_error_msg").offset().top
                }, 1000);

                setTimeout(function () {
                    $('#north_american_error_msg').html('').hide();
                }, 4000);
            }
        })
    });

    $('#refresh_north_american_doma_branches').click(function (e) {
        $('body').animate({ opacity: 0.5 }, "slow");
        $.ajax({
            url: base_url + "get-doma-branches",
            method: "POST",
            success: function (data) {
                var result = jQuery.parseJSON(data);
                if (result.status == 'success') {
                    location.reload();
                } else {
                    $('#north_american_doma_error_msg').html(result.message).show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#north_american_doma_error_msg").offset().top
                    }, 1000);

                    setTimeout(function () {
                        $('#north_american_doma_error_msg').html('').hide();
                    }, 4000);
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                $('#north_american_doma_error_msg').html('Something went wrong. Please try it again.').show();
                $([document.documentElement, document.body]).animate({
                    scrollTop: $("#north_american_doma_error_msg").offset().top
                }, 1000);

                setTimeout(function () {
                    $('#north_american_doma_error_msg').html('').hide();
                }, 4000);
            }
        })
    });

    $('#refresh_westcor_branches').click(function (e) {
        $('body').animate({ opacity: 0.5 }, "slow");
        $.ajax({
            url: base_url + "get-westcor-branches",
            method: "POST",
            success: function (data) {
                var result = jQuery.parseJSON(data);
                if (result.status == 'success') {
                    location.reload();
                } else {
                    $('#westcor_error_msg').html(result.message).show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#westcor_error_msg").offset().top
                    }, 1000);

                    setTimeout(function () {
                        $('#westcor_error_msg').html('').hide();
                    }, 4000);
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                $('#westcor_error_msg').html('Something went wrong. Please try it again.').show();
                $([document.documentElement, document.body]).animate({
                    scrollTop: $("#westcor_error_msg").offset().top
                }, 1000);

                setTimeout(function () {
                    $('#westcor_error_msg').html('').hide();
                }, 4000);
            }
        })
    });

    $('#refresh_commonwealth_branches').click(function (e) {
        $('body').animate({ opacity: 0.5 }, "slow");
        $.ajax({
            url: base_url + "get-commonwealth-branches",
            method: "POST",
            success: function (data) {
                var result = jQuery.parseJSON(data);
                if (result.status == 'success') {
                    location.reload();
                } else {
                    $('#commonwealth_error_msg').html(result.message).show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#commonwealth_error_msg").offset().top
                    }, 1000);

                    setTimeout(function () {
                        $('#commonwealth_error_msg').html('').hide();
                    }, 4000);
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                $('#commonwealth_error_msg').html('Something went wrong. Please try it again.').show();
                $([document.documentElement, document.body]).animate({
                    scrollTop: $("#commonwealth_error_msg").offset().top
                }, 1000);

                setTimeout(function () {
                    $('#commonwealth_error_msg').html('').hide();
                }, 4000);
            }
        })
    });
});