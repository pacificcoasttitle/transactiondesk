function isDisplayDocumentType()
{    
    $('input[type="checkbox"]').on('change', function() {
        $('body').animate({ opacity: 0.5 }, "slow");
        var lp_document_type_id = $(this).attr('id');
        if ($(this).is(":checked")) {
            var displayFlag = 1;
        } else {
            var displayFlag = 0;
        }
        $.ajax({
            url: base_url+"update-lp-document-type-flag",
            method: "POST",
            data : {
                lp_document_type_id: lp_document_type_id,
                displayFlag: displayFlag
            },
            success: function(data){
                var result = jQuery.parseJSON(data);
                if (result.status == 'success') {
                    $('body').animate({ opacity: 1.0 }, "slow");
                    $('#lp_document_types_success_msg').html(result.msg).show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#lp_document_types_success_msg").offset().top
                    }, 1000);
                    lp_document_list.ajax.reload( null, false );
                    setTimeout(function () {
                        $('#lp_document_types_success_msg').html('').hide();
                    }, 4000);
                } else {
                    $('#lp_document_types_error_msg').html(result.message).show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#lp_document_types_error_msg").offset().top
                    }, 1000);

                    setTimeout(function () {
                        $('#lp_document_types_error_msg').html('').hide();
                    }, 4000);
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                $('#lp_document_types_error_msg').html('Something went wrong. Please try it again.').show();
                $([document.documentElement, document.body]).animate({
                    scrollTop: $("#lp_document_types_success_msg").offset().top
                }, 1000);

                setTimeout(function () {
                    $('#lp_document_types_error_msg').html('').hide();
                }, 4000);
            }
        });
    });
}

function isVesDocumentType()
{    
    $('input[type="checkbox"]').on('change', function() {
        $('body').animate({ opacity: 0.5 }, "slow");
        var lp_document_type_id = $(this).attr('id');
        if ($(this).is(":checked")) {
            var isVesFlag = 1;
        } else {
            var isVesFlag = 0;
        }
        $.ajax({
            url: base_url+"update-lp-document-is-ves-type-flag",
            method: "POST",
            data : {
                lp_document_type_id: lp_document_type_id,
                isVesFlag: isVesFlag
            },
            success: function(data){
                var result = jQuery.parseJSON(data);
                if (result.status == 'success') {
                    $('body').animate({ opacity: 1.0 }, "slow");
                    $('#lp_document_types_success_msg').html(result.msg).show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#lp_document_types_success_msg").offset().top
                    }, 1000);
                    lp_document_list.ajax.reload( null, false );
                    setTimeout(function () {
                        $('#lp_document_types_success_msg').html('').hide();
                    }, 4000);
                } else {
                    $('#lp_document_types_error_msg').html(result.message).show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#lp_document_types_error_msg").offset().top
                    }, 1000);

                    setTimeout(function () {
                        $('#lp_document_types_error_msg').html('').hide();
                    }, 4000);
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                $('#lp_document_types_error_msg').html('Something went wrong. Please try it again.').show();
                $([document.documentElement, document.body]).animate({
                    scrollTop: $("#lp_document_types_success_msg").offset().top
                }, 1000);

                setTimeout(function () {
                    $('#lp_document_types_error_msg').html('').hide();
                }, 4000);
            }
        });
    });
}

function updateDocumentSection(id, section) {
    $('body').animate({
        opacity: 0.5
    }, "slow");
    $.ajax({
        url: base_url + "order/admin/update-doc-section",
        method: "POST",
        data: {
            id: id,
            section: section
        },
        success: function (data) {
            var result = jQuery.parseJSON(data);
            if (result.status == 'success') {
                $('body').animate({
                    opacity: 1.0
                }, "slow");
                $('#lp_order_success_msg').html(result.msg).show();
                $([document.documentElement, document.body]).animate({
                    scrollTop: $("#lp_order_success_msg").offset().top
                }, 1000);
                companies_list.ajax.reload(null, false);
                setTimeout(function () {
                    $('#lp_order_success_msg').html('').hide();
                }, 4000);
            } else {
                $('#lp_order_error_msg').html(result.message).show();
                $([document.documentElement, document.body]).animate({
                    scrollTop: $("#lp_order_error_msg").offset().top
                }, 1000);

                setTimeout(function () {
                    $('#lp_order_error_msg').html('').hide();
                }, 4000);
            }
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            $('#lp_order_error_msg').html('Something went wrong. Please try it again.').show();
            $([document.documentElement, document.body]).animate({
                scrollTop: $("#lp_order_error_msg").offset().top
            }, 1000);

            setTimeout(function () {
                $('#lp_order_error_msg').html('').hide();
            }, 4000);
        }
    });
}