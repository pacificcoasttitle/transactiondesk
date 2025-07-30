jQuery(document).ready(function ($) {
    $("#company_name").autocomplete({
        source: function (request, response) {
            $.ajax({
                url: base_url + "admin/order/order/getDetailsByName",
                data: {
                    term: request.term
                },
                type: "POST",
                dataType: "json",
                success: function (data) {
                    if (data.length > 0) {
                        response($.map(data, function (item) {
                            return item;
                        }))
                    } else {
                        response([{ label: 'No results found.', val: -1 }]);
                    }
                }
            });
        },
        delay: 0,
        minLength: 3,
        select: function (event, ui) {
            event.preventDefault();
            $("#company_name").val(ui.item.company);
            $("#email_address").val(ui.item.email_address);
            $("#first_name").val(ui.item.fname);
            $("#last_name").val(ui.item.lname);
            $("#client_id").val(ui.item.id);
        },
        change: function (event, ui) {
            if (ui.item == null) {
                $("#company_name").parent().removeClass('state-success').addClass('state-error');
            }
        }
    });

    $("#document_type").autocomplete({
        source: function (request, response) {
            $.ajax({
                url: base_url + "order/admin/search-document-type",
                data: {
                    doc_type: request.term
                },
                type: "POST",
                dataType: "json",
                success: function (result) {
                    if (result.status == 'success') {
                        if (result.data.length > 0) {
                            response($.map(result.data, function (item) {
                                return item;
                            }));
                        } else {
                            response([{ label: 'No results found.', val: -1 }]);
                        }
                    } else {
                        response([{ label: 'No results found.', val: -1 }]);
                    }
                }
            });
        },
        delay: 0,
        minLength: 1,
        select: function (event, ui) {
            event.preventDefault();
            $("#document_type").val(ui.item.doc_type);
            $("#document_name").val(ui.item.doc_type_description);
        },
        change: function (event, ui) {
            if (ui.item == null) {
                $("#document_type").parent().removeClass('state-success').addClass('state-error');
            }
        }
    });

    $("#document_sub_type").autocomplete({
        source: function (request, response) {
            $.ajax({
                url: base_url + "order/admin/search-document-sub-type",
                data: {
                    doc_type: request.term
                },
                type: "POST",
                dataType: "json",
                success: function (result) {
                    console.log(result);
                    if (result.status == 'success') {
                        if (result.data.length > 0) {
                            response($.map(result.data, function (item) {
                                return item.doc_type;
                            }));
                        } else {
                            response([{ label: 'No results found.', val: -1 }]);
                        }
                        console.log(result.data);
                    } else {
                        response([{ label: 'No results found.', val: -1 }]);
                    }
                }
            });
        },
        delay: 0,
        minLength: 1,
        select: function (event, ui) {
            event.preventDefault();
            $("#document_sub_type").val(ui.item.value);
        },
        change: function (event, ui) {
            if (ui.item == null) {
                $("#document_sub_type").parent().removeClass('state-success').addClass('state-error');
            }
        }
    });

    $('#ion_fraud_note_form').on('submit', function (e) {
        e.preventDefault(); // Prevent the default form submission
        $('#ionFraudSubmit').prop('disabled', true);

        var formData = $(this).serialize();
        formData += '&add_notes=true';
        $.ajax({
            type: 'POST',
            url: base_url + "order/admin/send-order-to-resware", // Replace with your form action URL
            data: formData,
            success: function (response) {
                // Handle success (response from server)
                // let file_id = $('#ion_fraud_note_form #file_id').val();
                // sendOrderToResware(file_id);
                $('#ion_fraud_note').modal('hide');
                $("#note").val(null);
                $("#note_subject").val(null);
                syncToReswareRes(response);
            },
            error: function (xhr, status, error) {
                // Handle error
                console.log(error);
            }
        });
    });
});

function getInstrumentData(file_id) {
    $('body').animate({
        opacity: 0.5
    }, "slow");
    $.ajax({
        url: base_url + "order/admin/get-instrument-data",
        method: "POST",
        data: {
            file_id: file_id
        },
        success: function (data) {
            var result = jQuery.parseJSON(data);
            $('body').animate({
                opacity: 1.0
            }, "slow");
            if (result.status == 'success') {
                $('#instrument_number_container').html(result.data);
                $('#instrument_model').modal('show');
            } else {
                $('#instrument_number_container').html(result.data);
                $('#instrument_model').modal('show');
            }
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            $('#lp_order_error_msg').html('Something went wrong. Please try it again.').show();
            $([document.documentElement, document.body]).animate({
                scrollTop: $("#lp_order_success_msg").offset().top
            }, 1000);

            setTimeout(function () {
                $('#lp_order_error_msg').html('').hide();
            }, 5000);
        }
    });
}

function regenerateReport(file_id) {
    $('body').animate({
        opacity: 0.5
    }, "slow");
    $.ajax({
        url: base_url + "order/admin/regenerate-report",
        method: "POST",
        data: {
            file_id: file_id
        },
        success: function (data) {
            var result = jQuery.parseJSON(data);
            $('body').animate({
                opacity: 1.0
            }, "slow");
            $('#lp_order_success_msg').html(result.message).show();
            $([document.documentElement, document.body]).animate({
                scrollTop: $("#lp_order_success_msg").offset().top
            }, 1000);
            lp_order_list.ajax.reload(null, false);
            setTimeout(function () {
                $('#lp_order_success_msg').html('').hide();
            }, 5000);

        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            $('#lp_order_error_msg').html('Something went wrong. Please try it again.').show();
            $([document.documentElement, document.body]).animate({
                scrollTop: $("#lp_order_success_msg").offset().top
            }, 1000);

            setTimeout(function () {
                $('#lp_order_error_msg').html('').hide();
            }, 5000);
        }
    });
}

function addIonFraudNotes(file_id) {
    $('#ion_fraud_note').modal('show');
    $('#ion_fraud_note_form #file_id').val(file_id);
}

function sendOrderToResware(file_id) {
    $('body').animate({
        opacity: 0.5
    }, "slow");
    $.ajax({
        url: base_url + "order/admin/send-order-to-resware",
        method: "POST",
        data: {
            file_id: file_id
        },
        success: function (data) {
            syncToReswareRes(data);
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            $('#lp_order_error_msg').html('Something went wrong. Please try it again.').show();
            $([document.documentElement, document.body]).animate({
                scrollTop: $("#lp_order_success_msg").offset().top
            }, 1000);

            setTimeout(function () {
                $('#lp_order_error_msg').html('').hide();
            }, 5000);
        }
    });
}

function syncToReswareRes(data) {
    if (data) {
        var result = jQuery.parseJSON(data);
        if (result.status == 'success') {
            $('#lp_order_success_msg').html(result.message).show();
            $([document.documentElement, document.body]).animate({
                scrollTop: $("#lp_order_success_msg").offset().top
            }, 1000);
            lp_order_list.ajax.reload(null, false);
            setTimeout(function () {
                $('#lp_order_success_msg').html('').hide();
            }, 5000);
        } else {

            $('#lp_order_error_msg').html(result.message).show();
            $([document.documentElement, document.body]).animate({
                scrollTop: $("#lp_order_error_msg").offset().top
            }, 1000);
            setTimeout(function () {
                $('#lp_order_error_msg').html('').hide();
            }, 5000);
        }
    }
    $('body').animate({
        opacity: 1.0
    }, "slow");
}

function updateLpReportStatus(file_id, status) {
    $('body').animate({
        opacity: 0.5
    }, "slow");
    $.ajax({
        url: base_url + "order/admin/update-lp-report-status",
        method: "POST",
        data: {
            file_id: file_id,
            status: status
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
                lp_order_list.ajax.reload(null, false);
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

function downloadDocumentFromAws(url, documentType) {
    $('#page-preloader').css('background-color', 'rgba(0,0,0,.5)');
    $('#page-preloader').css('display', 'block');
    var fileNameIndex = url.lastIndexOf("/") + 1;
    var filename = url.substr(fileNameIndex);
    $.ajax({
        url: base_url + "download-aws-document-admin",
        type: "post",
        data: {
            url: url
        },
        async: false,
        success: function (response) {
            if (response) {
                if (navigator.msSaveBlob) {
                    var csvData = base64toBlob(response, 'application/octet-stream');
                    var csvURL = navigator.msSaveBlob(csvData, filename);
                    var element = document.createElement('a');
                    element.setAttribute('href', csvURL);
                    element.setAttribute('download', documentType + "_" + filename);
                    element.style.display = 'none';
                    document.body.appendChild(element);
                    document.body.removeChild(element);
                } else {
                    console.log(response);
                    var csvURL = 'data:application/octet-stream;base64,' + response;
                    var element = document.createElement('a');
                    element.setAttribute('href', csvURL);
                    element.setAttribute('download', documentType + "_" + filename);
                    element.style.display = 'none';
                    document.body.appendChild(element);
                    element.click();
                    document.body.removeChild(element);
                }
            }
            $('#page-preloader').css('display', 'none');
        }
    });
}

function addVesting(file_id) {
    $('#file_id').val(file_id);
    $('body').animate({
        opacity: 0.5
    }, "slow");
    $.ajax({
        url: base_url + "order/admin/get-vesting-info",
        method: "POST",
        data: {
            file_id: file_id
        },
        success: function (data) {
            var result = jQuery.parseJSON(data);
            $('body').animate({
                opacity: 1.0
            }, "slow");
            if (result.status == 'success') {
                $("textarea#vesting_info").val(result.vesting_information);
                $('#vesting_model').modal('show');
            }
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            $('#lp_order_error_msg').html('Something went wrong. Please try it again.').show();
            $([document.documentElement, document.body]).animate({
                scrollTop: $("#lp_order_success_msg").offset().top
            }, 1000);

            setTimeout(function () {
                $('#lp_order_error_msg').html('').hide();
            }, 5000);
        }
    });
}

function fileUpload(file_id) {
    $('#upload_file_id').val(file_id);
    $('#fileUploadModel').modal('show');
}

function changeClient(file_id) {
    $('#client_file_id').val(file_id);
    $('#changeClientModel').modal('show');
}

function avoidDuplication() {
    $('input[type="checkbox"]').on('change', function () {
        $('body').animate({ opacity: 0.5 }, "slow");
        var property_id = $(this).attr('id');
        if ($(this).is(":checked")) {
            var avoidFlag = 1;
        } else {
            var avoidFlag = 0;
        }
        $.ajax({
            url: base_url + "update-avoid-duplication-flag",
            method: "POST",
            data: {
                property_id: property_id,
                avoidFlag: avoidFlag
            },
            success: function (data) {
                var result = jQuery.parseJSON(data);
                if (result.status == 'success') {
                    $('body').animate({ opacity: 1.0 }, "slow");
                    $('#lp_order_success_msg').html(result.msg).show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#lp_order_success_msg").offset().top
                    }, 1000);
                    lp_order_list.ajax.reload(null, false);
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
                    scrollTop: $("#lp_order_success_msg").offset().top
                }, 1000);

                setTimeout(function () {
                    $('#lp_order_error_msg').html('').hide();
                }, 4000);
            }
        });
    });
}
