$(document).ready(function () {
    $("input[name=new_existing_lender]").change(function () {
        $("#LenderName").val('');
        $("#LenderState").val('');
        $("#LenderCompany").val('');
        $("#LenderAddress").val('');
        $("#LenderCity").val('');
        $("#LenderZipcode").val('');
        $("#assignment_clause").val('');
        $("#LenderId").val('');
    });
    if ($('#cpl_listing').length) {
        customer_list = $('#cpl_listing').DataTable({
            "paging": true,
            "lengthChange": false,
            "language": {
                searchPlaceholder: "Search #File or Address",
                paginate: {
                    next: '<span class="fa fa-angle-right"></span>',
                    previous: '<span class="fa fa-angle-left"></span>',
                },
                "emptyTable": "Record(s) not found.",
                "search": "",
            },
            /*"searching": false,*/
            "bStateSave": true,
            "fnStateSave": function (oSettings, oData) {
                localStorage.setItem('offersDataTables', JSON.stringify(oData));
            },
            "fnStateLoad": function (oSettings) {
                return JSON.parse(localStorage.getItem('offersDataTables'));
            },
            initComplete: function () {


            },
            dom: 'Bfrtip',
            buttons: [],
            "drawCallback": function () {

            },
            "ordering": false,
            "serverSide": true,
            "ajax": {
                url: base_url + "get-orders-cpl", // json datasource
                type: "post", // method  , by default get
                beforeSend: function () {
                    $('#page-list-loader').css('background-color', 'rgba(0,0,0,.5)');
                    $('#page-list-loader').css('display', 'block');
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    if (parseInt(XMLHttpRequest.status) == 419) {
                        alert("You are logged out. Please login.");
                    }
                    if (parseInt(XMLHttpRequest.status) == 419) {
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                    }
                    $("#cpl_listing tbody").append(
                        '<tr><td colspan="4" class="text-center">No records found</td></tr>');
                    $("#cpl_listing_processing").css("display", "none");
                },
                complete: function () {
                    $("#page-list-loader").hide();
                    $('#page-list-loader').css('display', 'none');
                }
            }
        });
    }
});

$("#LenderCompany").focusin(function () {
    if ($('input[name="new_existing_lender"]:checked').val() == 'existing_lender') {
        if ($('.ui-widget.ui-autocomplete').length > 0) {
            $('#LenderCompany').autocomplete("enable");
        }
        $("#LenderCompany").autocomplete({
            source: function (request, response) {
                $.ajax({
                    url: base_url + 'getDetailsByName',
                    data: {
                        term: request.term,//the value of the input is here
                        is_escrow: 0
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
                $("#LenderCompany").val(ui.item.company);

                if (ui.item.state) {
                    $("#LenderState").val(ui.item.state).parent().addClass('state-success');
                } else {
                    $("#LenderState").val('').parent().removeClass('state-success').addClass('state-error');
                }

                if (ui.item.name) {
                    $("#LenderName").val(ui.item.name).parent().addClass('state-success');
                } else {
                    $("#LenderName").val('').parent().removeClass('state-success').addClass('state-error');
                }

                if (ui.item.address) {
                    $("#LenderAddress").val(ui.item.address).parent().addClass('state-success');
                } else {
                    $("#LenderAddress").val('').parent().removeClass('state-success').addClass('state-error');
                }

                if (ui.item.city) {
                    $("#LenderCity").val(ui.item.city).parent().addClass('state-success');
                } else {
                    $("#LenderCity").val('').parent().removeClass('state-success').addClass('state-error');
                }

                if (ui.item.zip_code) {
                    $("#LenderZipcode").val(ui.item.zip_code).parent().addClass('state-success');
                } else {
                    $("#LenderZipcode").val('').parent().removeClass('state-success').addClass('state-error');
                }

                if (ui.item.assignment_clause) {
                    $("#assignment_clause").val(ui.item.assignment_clause);
                } else {
                    $("#assignment_clause").val('');
                }
                $("#LenderId").val(ui.item.id);

            },
            change: function (event, ui) {
                if (ui.item == null) {
                    $("#LenderState").val('').parent().removeClass('state-success').addClass('state-error');
                    $("#LenderCompany").val('').parent().removeClass('state-success').addClass('state-error');
                    $("#LenderAddress").val('').parent().removeClass('state-success').addClass('state-error');
                    $("#LenderCity").val('').parent().removeClass('state-success').addClass('state-error');
                    $("#LenderZipcode").val('').parent().removeClass('state-success').addClass('state-error');
                    $("#assignment_clause").val('');
                    $("#LenderId").val('');
                }
            }
        });
    } else {
        if ($('.ui-widget.ui-autocomplete').length > 0) {
            $('#LenderCompany').autocomplete("disable");
        }
    }
});
/* Lender autocomplete */

/* Agent autocomplete */
$("#agent_name").autocomplete({
    source: function (request, response) {
        $.ajax({
            url: base_url + 'agent/getAgentDetails',
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
        $("#agent_name").val(ui.item.name);
        $("#agent_id").val(ui.item.id);
    },
    change: function (event, ui) {

    }
});
/* Agent autocomplete */

function downloadDocumentFromAws(url, documentType) {
    $('#page-preloader').css('background-color', 'rgba(0,0,0,.5)');
    $('#page-preloader').css('display', 'block');
    var fileNameIndex = url.lastIndexOf("/") + 1;
    var filename = url.substr(fileNameIndex);
    $.ajax({
        url: base_url + "download-aws-document",
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

function lender_pop_up(lenderFlag, fileId) {
    if (lenderFlag == 1) {
        $(this).form.submit();
    } else {
        $('#page-preloader').css('background-color', 'rgba(0,0,0,.5)');
        $('#page-preloader').css('display', 'block');
        $.ajax({
            url: base_url + "get-order-details-cpl",
            type: "post",
            data: {
                fileId: fileId
            },
            success: function (response) {
                var res = jQuery.parseJSON(response);
                console.log('res ==', res);
                if (res.status == 'success') {
                    var optionsAsString = "";
                    if ((res.orderDetails.hasOwnProperty("agents_data")) && (res.orderDetails['agents_data'].length > 0)) {
                        for (var i = 0; i < res.orderDetails['agents_data'].length; i++) {
                            var selected = '';
                            if (res.orderDetails['agents_data'][i]['id'] == res.orderDetails['fnf_agent_id']) {
                                selected = 'selected';
                            }
                            if (res.orderDetails['cpl_api'] == 'westcor' || res.orderDetails['cpl_api'] == 'natic' || res.orderDetails['cpl_api'] == 'doma') {
                                optionsAsString += "<option " + selected + " value='" + res.orderDetails['agents_data'][i]['id'] + "'>" + res.orderDetails['agents_data'][i]['city'] + "</option>";
                            } else if (res.orderDetails['cpl_api'] == 'natic') {
                                optionsAsString += "<option " + selected + " value='" + res.orderDetails['agents_data'][i]['id'] + "'>" + res.orderDetails['agents_data'][i]['city'] + "</option>";
                            } else {
                                optionsAsString += "<option " + selected + " value='" + res.orderDetails['agents_data'][i]['id'] + "'>" + res.orderDetails['agents_data'][i]['location_city'] + "</option>";
                            }
                        }
                    }
                    $('select[name="branch"]').children('option:not(:first)').remove();
                    $('select[name="branch"]').append(optionsAsString);
                    $("#branch").prop('required', true);

                    $('#cpl_api').val(res.orderDetails['cpl_api']);
                    $("#LenderName").val(res.orderDetails['lender_name']);
                    $("#LenderState").val(res.orderDetails['lender_state']);
                    $("#LenderCompany").val(res.orderDetails['lender_company_name']);
                    $("#assignment_clause").val(res.orderDetails['lender_assignment_clause']);
                    $("#LenderAddress").val(res.orderDetails['lender_address']);
                    $("#LenderCity").val(res.orderDetails['lender_city']);
                    $("#LenderZipcode").val(res.orderDetails['lender_zipcode']);
                    $("#LenderId").val(res.orderDetails['lender_id']);
                    $("#borrowers_vesting").val(res.orderDetails['borrowers_vesting']);
                    $("#loan_number").val(res.orderDetails['loan_number']);
                    if (res.orderDetails['unit_number']) {
                        $("#property_address").val(res.orderDetails['unit_number'] + ", " + res.orderDetails['property_address']);
                    } else {
                        $("#property_address").val(res.orderDetails['property_address']);
                    }
                    $("#property_city").val(res.orderDetails['property_city']);
                    $("#property_state").val(res.orderDetails['property_state']);
                    $("#property_zipcode").val(res.orderDetails['property_zipcode']);
                    if (res.orderDetails['lender_id'] != '') {
                        $("#existing_lender").prop("checked", true);
                    } else {
                        $("#add_lender").prop("checked", true);
                    }
                }
                $('#page-preloader').css('display', 'none');
                $('#lender_information').modal('show');
                $('#file_id').val(fileId);
            }
        });
        return false;
    }
}

$("form").submit(function () {
    $('#page-preloader').css('background-color', 'rgba(0,0,0,.5)');
    $('#page-preloader').css('display', 'block');
});

function base64toBlob(base64Data, contentType) {
    contentType = contentType || '';
    var sliceSize = 1024;
    var byteCharacters = atob(base64Data);
    var bytesLength = byteCharacters.length;
    var slicesCount = Math.ceil(bytesLength / sliceSize);
    var byteArrays = new Array(slicesCount);

    for (var sliceIndex = 0; sliceIndex < slicesCount; ++sliceIndex) {
        var begin = sliceIndex * sliceSize;
        var end = Math.min(begin + sliceSize, bytesLength);

        var bytes = new Array(end - begin);
        for (var offset = begin, i = 0; offset < end; ++i, ++offset) {
            bytes[i] = byteCharacters[offset].charCodeAt(0);
        }
        byteArrays[sliceIndex] = new Uint8Array(bytes);
    }
    return new Blob(byteArrays, {
        type: contentType
    });
}