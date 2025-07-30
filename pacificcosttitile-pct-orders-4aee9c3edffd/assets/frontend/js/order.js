var taxServiceExecuted = false;
var lvServiceExecuted = false;

$(document).ready(function () {
    let notifyAdminFlag = false;
    let notificationSubject = '';

    if ($(".grant-deed-no-data").length) {
        notifyAdminFlag = true;
        notificationSubject += 'Grant Deed';
        // notifyAdmin('Grant Deed Not Found');
    }

    if ($(".tax-no-data").length) {
        if (notifyAdminFlag) {
            notificationSubject += ',';
        }
        notificationSubject += ' Tax Document';
        notifyAdminFlag = true;
        // notifyAdmin('Tax Document Not Found');
    }

    if ($(".legal-vesting-no-data").length) {
        if (notifyAdminFlag) {
            notificationSubject += ',';
        }
        notificationSubject += ' Legal Vesting';
        notifyAdminFlag = true;
        // notifyAdmin('Legal Vesting Document Not Found');
    }

    if (notifyAdminFlag) {
        notificationSubject = notificationSubject + ' Documents Not Found';
        notifyAdmin(notificationSubject);
    }

    if ($('#clone-email-address').length) {
        $('#clone-email-address').cloneya({
            maximum: 5
        }).on('after_append.cloneya', function (event, toclone, newclone) {
            var name = $(newclone).find("input[type='email']").attr('id');
        }).off('remove.cloneya').on('remove.cloneya', function (event, clone) {
            $(clone).slideToggle('slow', function () {
                $(clone).remove();

            })
        });
    }

    getProductTypes();

    $('.search-file-btn').children("input").bind('change', function () {
        var fileName = '';
        fileName = $(this).val().split("\\").slice(-1)[0];
        $(this).parent().parent().children("span").html(fileName);
    });

    if ($('#CompanyName').length) {
        $("#CompanyName, #OpenEmail").autocomplete({
            source: function (request, response) {
                $.ajax({
                    url: base_url + 'getDetailsByName',
                    data: {
                        term: request.term,
                        is_master_search: 1
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
                $("#CompanyName").val(ui.item.company);
                $("#OpenEmail").val(ui.item.email_address).parent().addClass('state-success');
                $("#Opentelephone").val(ui.item.telephone_no).parent().addClass('state-success');
                $("#OpenName").val(ui.item.fname).parent().addClass('state-success');
                $("#OpenLastName").val(ui.item.lname).parent().addClass('state-success');
                $("#StreetAddress").val(ui.item.address).parent().addClass('state-success');
                $("#City").val(ui.item.city).parent().addClass('state-success');
                $("#Zipcode").val(ui.item.zip_code).parent().addClass('state-success');
                $("#CustomerId").val(ui.item.id);
                if (ui.item.sales_rep_id) {
                    $("#SalesRep").val(ui.item.sales_rep_id)
                }

                if (ui.item.title_officer_id) {
                    $("#TitleOfficer").val(ui.item.title_officer_id)
                }


                var is_escrow = ui.item.is_escrow;
                var is_mortgage_broker = ui.item.is_primary_mortgage_user;

                if (is_mortgage_broker == 1) {
                    $('#add-lender-section').show();
                    $('#add-escrow-section').show();
                    $('#email-notification-section').show();
                    $('#upload_lender').show();
                    $('#upload_escrow').show();
                } else {
                    if (is_escrow == 1) {
                        $('#add-lender-section').show();
                        $('#add-escrow-section').hide();
                        $('#escrow-details-fields').hide();
                        $("#add-escrow-details").prop("checked", false);
                        $('#upload_lender').hide();
                        $('#upload_escrow').show();
                        $('#email-notification-section').hide();
                    } else {
                        $('#add-lender-section').hide();
                        $('#lender-details-fields').hide();
                        $("#add-lender-details").prop("checked", false);
                        $('#add-escrow-section').show();
                        $('#email-notification-section').show();
                        $('#upload_lender').show();
                        $('#upload_escrow').hide();
                    }
                }

                getProductTypes();
                getDeliverables(ui.item.partner_id);
            },
            change: function (event, ui) {
                if (ui.item == null) {
                    $("#CompanyName").parent().removeClass('state-success').addClass('state-error');
                    $("#OpenEmail").val('').parent().removeClass('state-success').addClass('state-error');
                    $("#Opentelephone").val('').parent().removeClass('state-success').addClass('state-error');
                    $("#OpenName").val('').parent().removeClass('state-success').addClass('state-error');
                    $("#OpenLastName").val('').parent().removeClass('state-success').addClass('state-error');
                    $("#StreetAddress").val('').parent().removeClass('state-success').addClass('state-error');
                    $("#City").val('').parent().removeClass('state-success').addClass('state-error');
                    $("#Zipcode").val('').parent().removeClass('state-success').addClass('state-error');
                    $("#CustomerId").val('');
                }
            }
        });
    }

    $('#email-notification').on('click', function () {
        if ($(this).is(":checked")) {
            $(this).val(1);
        } else {
            $(this).val(0);
        }
    });

    $("input[data-type='number']").keyup(function (event) {
        if (event.which >= 37 && event.which <= 40) {
            event.preventDefault();
        }
        var $this = $(this);
        var num = $this.val().replace(/[^0-9 \,]/, '');
        num = num.replace(/,/gi, "");
        var num2 = num.split(/(?=(?:\d{3})+$)/).join(",");
        $this.val(num2);
    });
});


function createService4(fipCode, address, city, unit_no, apn, random_number, properyData = '') {

    let bedrooms = baths = lotSize = zoning = buildingArea = '';
    if (!$.isEmptyObject(properyData)) {
        bedrooms = $(reportXML).find("PropertyProfile").find("PropertyCharacteristics").find("Bedrooms").text();
        baths = $(reportXML).find("PropertyProfile").find("PropertyCharacteristics").find("Baths").text();
        lotSize = $(reportXML).find("PropertyProfile").find("PropertyCharacteristics").find("LotSize").text();
        zoning = $(reportXML).find("PropertyProfile").find("PropertyCharacteristics").find("Zoning").text();
        buildingArea = $(reportXML).find("PropertyProfile").find("PropertyCharacteristics").find("BuildingArea").text();
    }
    $.ajax({
        // url: 'php/createservice.php',
        url: base_url + 'createService',
        data: {
            fipCode: fipCode,
            address: address,
            city: city,
            unit_no: unit_no,
            apn: apn,
            methodId: 4,
            random_number: random_number,
            bedRooms: bedrooms,
            baths: baths,
            lotSize: lotSize,
            zoning: zoning,
            buildingArea: buildingArea
        },
        type: "POST",
        dataType: "xml"
    })
        .done(function (response, textStatus, jqXHR) {
            var responseStatus = $(response).find('ReturnStatus').text();

            if (responseStatus == 'Failed') {

                $('#legalDescription, #vestingInformation').prev('.loader').hide();
                $('#legalDescription').html('No data found.');
                $('#vestingInformation').html('No data found.');
                $('#grantDeedInfoFile').prev('.loader').hide();
                $('#grantDeedInfoFile').css('border', '1px solid #000000');
                $('#grantDeedInfoFile').css('padding', '15px');
                $('#grantDeedInfoFile').html('<span class="orderinfo1">No data found.</span>');
                lvServiceExecuted = true;
            }
            else if (responseStatus == 'Success') {
                $requestId = $(response).find('RequestID').text();
                getRequestSummaries($requestId, '4', random_number);
            }
        })
        .fail(function (err) {
            lvServiceExecuted = true;
            $('#legalDescription, #vestingInformation').prev('.loader').hide();
            $('#legalDescription').html('No data found.');
            $('#vestingInformation').html('No data found.');
            $('#grantDeedInfoFile').prev('.loader').hide();
            $('#grantDeedInfoFile').css('border', '1px solid #000000');
            $('#grantDeedInfoFile').css('padding', '15px');
            $('#grantDeedInfoFile').html('<span class="orderinfo1">No data found.</span>');
            alert('Something went wrong.Please hard refresh your page.');
        });
}

function createService3(apn, state, county, random_number) {
    $.ajax({
        // url: 'php/createservice.php',
        url: base_url + 'createService',
        data: {
            apn: apn,
            state: state,
            county: county,
            methodId: 3,
            random_number: random_number,
        },
        dataType: "xml",
        type: "POST"
    })
        .done(function (response, textStatus, jqXHR) {

            var responseStatus = $(response).find('ReturnStatus').text();

            if (responseStatus == 'Failed') {
                $('#firstInstallment, #secondInstallment').prev('.loader').hide();
                $('#firstInstallment').css('border', '1px solid #000000');
                $('#firstInstallment').css('padding', '15px');
                $('#firstInstallment').html('<span class="orderinfo1">No data found.</span>');
                $('#secondInstallment').css('border', '1px solid #000000');
                $('#secondInstallment').css('padding', '15px');
                $('#secondInstallment').html('<span class="orderinfo1">No data found.</span>');
                taxServiceExecuted = true;
            }
            else if (responseStatus == 'Success') {
                $requestId = $(response).find('RequestID').text();
                getRequestSummaries($requestId, '3', random_number);
            }
        })
        .fail(function (err) {
            /*$('#firstInstallment, #secondInstallment').prev('.loader').hide();
            $('#firstInstallment').css('border','1px solid #000000');
            $('#firstInstallment').css('padding','15px');
            $('#firstInstallment').html('<span class="orderinfo1">No data found.</span>');
            $('#secondInstallment').css('border','1px solid #000000');
            $('#secondInstallment').css('padding','15px');
            $('#secondInstallment').html('<span class="orderinfo1">No data found.</span>');*/
            alert('Something went wrong.Please hard refresh(Ctrl+F5) your page.');
        });
}

function getRequestSummaries(requestId, methodId, random_number) {
    var apn = $("#apn").val();
    $.ajax({
        url: base_url + 'getRequestSummaries',
        data: {
            requestId: requestId,
            methodId: methodId,
            apn: apn,
            random_number: random_number,
        },
        dataType: "xml",
        type: "POST"
    })
        .done(function (response, textStatus, jqXHR) {

            var responseStatus = $(response).find('ReturnStatus').text();

            if (responseStatus == 'Failed') {
                $('#legalDescription, #vestingInformation').prev('.loader').hide();
                $('#legalDescription').html('No data found.');
                $('#vestingInformation').html('No data found.');

                $('#grantDeedInfoFile').prev('.loader').hide();
                $('#grantDeedInfoFile').css('border', '1px solid #000000');
                $('#grantDeedInfoFile').css('padding', '15px');
                $('#grantDeedInfoFile').html('<span class="orderinfo1">No data found.</span>');

                $('#firstInstallment, #secondInstallment').prev('.loader').hide();
                $('#firstInstallment').css('border', '1px solid #000000');
                $('#firstInstallment').css('padding', '15px');
                $('#firstInstallment').html('<span class="orderinfo1">No data found.</span>');
                $('#secondInstallment').css('border', '1px solid #000000');
                $('#secondInstallment').css('padding', '15px');
                $('#secondInstallment').html('<span class="orderinfo1">No data found.</span>');
                if (methodId == '3') {
                    taxServiceExecuted = true;
                } else {
                    lvServiceExecuted = true;
                }
            }
            else if (responseStatus == 'Success') {
                $resultId = $(response).find("ResultThumbNail:first").find("ID").text();
                if ($resultId) {
                    getResultById($resultId, methodId, random_number);
                }
                else {
                    getRequestSummaries($requestId, methodId, random_number);
                }

            }
        })
        .fail(function (err) {
            /*$('#legalDescription, #vestingInformation').prev('.loader').hide();
            $('#legalDescription').html('No data found.');
            $('#vestingInformation').html('No data found.');
            
            $('#grantDeedInfoFile').prev('.loader').hide();
            $('#grantDeedInfoFile').css('border','1px solid #000000');
            $('#grantDeedInfoFile').css('padding','15px');
            $('#grantDeedInfoFile').html('<span class="orderinfo1">No data found.</span>');
        
            $('#firstInstallment, #secondInstallment').prev('.loader').hide();
            $('#firstInstallment').css('border','1px solid #000000');
            $('#firstInstallment').css('padding','15px');
            $('#firstInstallment').html('<span class="orderinfo1">No data found.</span>');
            $('#secondInstallment').css('border','1px solid #000000');
            $('#secondInstallment').css('padding','15px');
            $('#secondInstallment').html('<span class="orderinfo1">No data found.</span>');*/
            alert('Something went wrong.Please hard refresh(Ctrl+F5) your page.');
        });
}

function getResultById(resultId, methodId, random_number) {
    var apn = $("#apn").val();
    $.ajax({
        url: base_url + 'getResultById',
        data: {
            resultId: resultId,
            apn: apn,
            methodId: methodId,
            random_number: random_number
        },
        dataType: "xml",
        type: "POST"
    })
        .done(function (response, textStatus, jqXHR) {
            if (methodId == '3') {
                taxServiceExecuted = true;
            } else {
                lvServiceExecuted = true;
            }
            setTimeout(function () {
                if (lvServiceExecuted && taxServiceExecuted) {
                    $('.home-submit').prop('disabled', false);
                }
            }, 1000);
            var responseStatus = $(response).find('ReturnStatus').text();

            if (responseStatus == 'Failed') {
                $('#legalDescription, #vestingInformation').prev('.loader').hide();
                $('#legalDescription').html('No data found.');
                $('#vestingInformation').html('No data found.');


                $('#firstInstallment, #secondInstallment').prev('.loader').hide();
                $('#firstInstallment').css('border', '1px solid #000000');
                $('#firstInstallment').css('padding', '15px');
                $('#firstInstallment').html('<span class="orderinfo1">No data found.</span>');
                $('#secondInstallment').css('border', '1px solid #000000');
                $('#secondInstallment').css('padding', '15px');
                $('#secondInstallment').html('<span class="orderinfo1">No data found.</span>');

            }
            else if (responseStatus == 'Success') {
                /*if(L_V_CreateService == '' || L_V_GetRequestSummary == '' || L_V_GetResultById == '')
                {
                    if(methodId == 4)
                    {
                        var briefLegal = $(response).find("Result:first").find("BriefLegal").text();
                        var vesting = $(response).find("Result:first").find("Vesting").text();
                        var instrumentNumber = $(response).find("Result:first").find("LvDeeds").find("LegalAndVesting2DeedInfo:first").find("InstrumentNumber").text();
                        

                        $('#legalDescription, #vestingInformation').prev('.loader').hide();
                       
                        if(briefLegal)
                        {
                            $('#legalDescription').html(briefLegal);
                        }
                        else
                        {
                            $('#legalDescription').html('No data found.');
                        }

                        if(vesting)
                        {
                            $('#vestingInformation').html(vesting);
                        }
                        else
                        {
                            $('#vestingInformation').html('No data found.');
                        }

                        if(instrumentNumber)
                        {
                            var recordedDate = $(response).find("Result:first").find("LvDeeds").find("LegalAndVesting2DeedInfo:first").find("RecordedDate").text();         
                            var dateParts =recordedDate.split('/');
                            if(dateParts[2])
                            {
                                var docId = instrumentNumber.replace(dateParts[2], "");
                                var recDate = dateParts[2];
                                instrumentSearch(docId,recDate,state,county);
                            }
                            
                        }

                    }
                }
                if(Tax_CreateService == '' || Tax_GetRequestSummary == '' || Tax_GetResultById == '')
                {
                    if(methodId == 3)
                    {      
                        $('#firstInstallment, #secondInstallment').prev('.loader').hide();
                        if($(response).find("Result:first").find("TaxReport").find("Installments").children('item').length)
                        {
                            $(response).find("Result:first").find("TaxReport").find("Installments").children('item').each(function(i) {
                                var balance = $(this).find('Balance').text() ? $(this).find('Balance').text() : ' - ';
                                var amount  = $(this).find('Amount').text()? $(this).find('Amount').text() : ' - ';
                                var duedate = $(this).find('DueDate').text() ? $(this).find('DueDate').text() : ' - ';
                                var number  = $(this).find('Number').text() ? $(this).find('Number').text() : ' - ';
                                var paymentdate = $(this).find('PaymentDate').text() ? $(this).find('PaymentDate').text() : ' - ';
                                var penalty = $(this).find('Penalty').text() ? $(this).find('Penalty').text() : ' - ';
                                var status = $(this).find('Status').text() ? $(this).find('Status').text() : ' - ';
                                var amountpaid = $(this).find('AmountPaid').text() ? $(this).find('AmountPaid').text() : ' - ';
                                var taxyear = $(this).find('TaxYear').text() ? $(this).find('TaxYear').text() : ' - ';
                                if(i == 0)
                                {
                                    var firstIntdata = '<p>Balance: '+balance+'</p><p>Amount: '+amount+'</p><p>DueDate: '+duedate+'</p><p>Number: '+number+'</p><p>PaymentDate: '+paymentdate+'</p><p>Penalty: '+penalty+'</p><p>Status: '+status+'</p><p>AmountPaid: '+amountpaid+'</p><p>TaxYear: '+taxyear+'</p>';
                                    
                                    $('#firstInstallment').css('border','1px solid #000000');
                                    $('#firstInstallment').css('padding','15px');
                                    $('#firstInstallment').html(firstIntdata);
                                }
                                else if(i == 1)
                                {
                                    var secondIntdata = '<p>Balance: '+balance+'</p><p>Amount: '+amount+'</p><p>DueDate: '+duedate+'</p><p>Number: '+number+'</p><p>PaymentDate: '+paymentdate+'</p><p>Penalty: '+penalty+'</p><p>Status: '+status+'</p><p>AmountPaid: '+amountpaid+'</p><p>TaxYear: '+taxyear+'</p>';
                                    
                                    $('#secondInstallment').css('border','1px solid #000000');
                                    $('#secondInstallment').css('padding','15px');
                                    $('#secondInstallment').html(secondIntdata);
                                }
                            });
                        }
                        else
                        {
                            $('#firstInstallment').css('border','1px solid #000000');
                            $('#firstInstallment').css('padding','15px');
                            $('#firstInstallment').html('<span class="orderinfo1">No data found.</span>');
                            $('#secondInstallment').css('border','1px solid #000000');
                            $('#secondInstallment').css('padding','15px');
                            $('#secondInstallment').html('<span class="orderinfo1">No data found.</span>');
                        }
                    }
                }*/
            }
        })
        .fail(function (err) {
            if (methodId == '3') {
                taxServiceExecuted = true;
            } else {
                lvServiceExecuted = true;
            }
            setTimeout(function () {
                if (lvServiceExecuted && taxServiceExecuted) {
                    $('.home-submit').prop('disabled', false);
                }
            }, 1000);
            /*$('#legalDescription, #vestingInformation').prev('.loader').hide();
            $('#legalDescription').html('No data found.');
            $('#vestingInformation').html('No data found.');
            $('#firstInstallment, #secondInstallment').prev('.loader').hide();
            $('#firstInstallment').css('border','1px solid #000000');
            $('#firstInstallment').css('padding','15px');
            $('#firstInstallment').html('<span class="orderinfo1">No data found.</span>');
            $('#secondInstallment').css('border','1px solid #000000');
            $('#secondInstallment').css('padding','15px');
            $('#secondInstallment').html('<span class="orderinfo1">No data found.</span>');*/
            alert('Something went wrong.Please hard refresh(Ctrl+F5) your page.');
        });
}

function imageCreateRequest(serviceId, methodId, fileNumber) {
    if (methodId == 4) {
        $('#grantDeedInfoFile').next('.loader').show();
    }

    $.ajax({
        url: base_url + 'imageCreateRequest',
        data: {
            serviceId: serviceId,
        },
        dataType: "xml",
        type: "POST"
    })
        .done(function (response, textStatus, jqXHR) {

            var responseStatus = $(response).find('ReturnStatus').text();

            if (responseStatus == 'Failed') {
                if (methodId == 4) {
                    $('#grantDeedInfoFile').next('.loader').hide();
                    $('#grantDeedInfoFile').css('border', '1px solid #000000');
                    $('#grantDeedInfoFile').css('padding', '15px');
                    $('#grantDeedInfoFile').html('<span class="orderinfo1">No data found.</span>');
                }
                else if (methodId == 3) {
                    $('#taxDocumentInfo').next('.loader').hide();
                    $('#taxDocumentInfo').css('border', '1px solid #000000');
                    $('#taxDocumentInfo').css('padding', '15px');
                    $('#taxDocumentInfo').html('<span class="orderinfo1">No data found.</span>');
                }
            }
            else if (responseStatus == 'Success') {
                $requestId = $(response).find('RequestID').text();
                getRequestStatus($requestId, methodId, fileNumber);
            }
        })
        .fail(function (err) {
            if (methodId == 4) {
                $('#grantDeedInfoFile').next('.loader').hide();
                $('#grantDeedInfoFile').css('border', '1px solid #000000');
                $('#grantDeedInfoFile').css('padding', '15px');
                $('#grantDeedInfoFile').html('<span class="orderinfo1">No data found.</span>');

            }
            else if (methodId == 3) {
                $('#instrumentInfoFile').next('.loader').hide();
                $('#instrumentInfoFile').css('border', '1px solid #000000');
                $('#instrumentInfoFile').css('padding', '15px');
                $('#instrumentInfoFile').html('<span class="orderinfo1">No data found.</span>');
            }
        });
}

function getRequestStatus(requestId, methodId, fileNumber) {
    $.ajax({
        url: base_url + 'getRequestStatus',
        data: {
            requestId: requestId
        },
        dataType: "xml",
        type: "POST"
    })
        .done(function (response, textStatus, jqXHR) {

            var responseStatus = $(response).find('ReturnStatus').text();

            if (responseStatus == 'Failed') {
                if (methodId == 4) {

                    $('#grantDeedInfoFile').next('.loader').hide();
                    $('#grantDeedInfoFile').css('border', '1px solid #000000');
                    $('#grantDeedInfoFile').css('padding', '15px');
                    $('#grantDeedInfoFile').html('<span class="orderinfo1">No data found.</span>');

                }
                else if (methodId == 3) {
                    $('#taxDocumentInfo').next('.loader').hide();
                    $('#taxDocumentInfo').css('border', '1px solid #000000');
                    $('#taxDocumentInfo').css('padding', '15px');
                    $('#taxDocumentInfo').html('<span class="orderinfo1">No data found.</span>');
                }
            }
            else if (responseStatus == 'Success') {
                $resultId = $(response).find("RequestId:first").text();
                generateImage($resultId, methodId, fileNumber);
            }
        })
        .fail(function (err) {
            if (methodId == 4) {
                $('#grantDeedInfoFile').next('.loader').hide();
                $('#grantDeedInfoFile').css('border', '1px solid #000000');
                $('#grantDeedInfoFile').css('padding', '15px');
                $('#grantDeedInfoFile').html('<span class="orderinfo1">No data found.</span>');

            }
            else if (methodId == 3) {
                $('#taxDocumentInfo').next('.loader').hide();
                $('#taxDocumentInfo').css('border', '1px solid #000000');
                $('#taxDocumentInfo').css('padding', '15px');
                $('#taxDocumentInfo').html('<span class="orderinfo1">No data found.</span>');
            }
        });
}

function generateImage(requestId, methodId, fileNumber) {
    $.ajax({
        url: base_url + 'generateImage',
        data: {
            requestId: requestId,
            methodId: methodId,
            fileNumber: fileNumber,
        },
        dataType: "xml",
        type: "POST"
    })
        .done(function (response, textStatus, jqXHR) {

            var responseStatus = $(response).find('ReturnStatus').text();

            if (responseStatus == 'Failed') {
                if (methodId == 4) {
                    $('#grantDeedInfoFile').prev('.loader').hide();
                    $('#grantDeedInfoFile').css('border', '1px solid #000000');
                    $('#grantDeedInfoFile').css('padding', '15px');
                    $('#grantDeedInfoFile').html('<span class="orderinfo1">No data found.</span>');

                }
                else if (methodId == 3) {
                    $('#taxDocumentInfo').prev('.loader').hide();
                    $('#taxDocumentInfo').css('border', '1px solid #000000');
                    $('#taxDocumentInfo').css('padding', '15px');
                    $('#taxDocumentInfo').html('<span class="orderinfo1">No data found.</span>');
                }

            }
            else if (responseStatus == 'Success') {
                var base64_data = $(response).find("Data:first").text();
                var bin = atob(base64_data);

                if (methodId == 3) {
                    if (navigator.msSaveBlob) {
                        var filename = "Tax.pdf";
                        download(filename, base64_data);
                    }
                    else {
                        download('GrantDeed.pdf', base64_data);
                    }
                    $('#taxDocumentInfo').next('.loader').hide();
                }
                else if (methodId == 4) {
                    if (navigator.msSaveBlob) {
                        var filename = "L&V.pdf";
                        download(filename, base64_data);
                    }
                    else {
                        download('L&V.pdf', base64_data);
                    }
                    $('#grantDeedInfoFile').next('.loader').hide();
                }
            }
        })
        .fail(function (err) {
            if (methodId == 4) {
                if (methodId == 4) {
                    $('#grantDeedInfoFile').prev('.loader').hide();
                    $('#grantDeedInfoFile').css('border', '1px solid #000000');
                    $('#grantDeedInfoFile').css('padding', '15px');
                    $('#grantDeedInfoFile').html('<span class="orderinfo1">No data found.</span>');
                }
                else if (methodId == 3) {
                    $('#taxDocumentInfo').prev('.loader').hide();
                    $('#taxDocumentInfo').css('border', '1px solid #000000');
                    $('#taxDocumentInfo').css('padding', '15px');
                    $('#taxDocumentInfo').html('<span class="orderinfo1">No data found.</span>');
                }
            }
            else if (methodId == 3) {
                $('#taxDocumentInfo').prev('.loader').hide();
                $('#taxDocumentInfo').css('border', '1px solid #000000');
                $('#taxDocumentInfo').css('padding', '15px');
                $('#taxDocumentInfo').html('<span class="orderinfo1">No data found.</span>');
            }

        });
}

function instrumentSearch(docId, recDate, state, county, fileNumber) {
    $('#instrumentInfoFile').next('.loader').show();
    $.ajax({
        // url: 'php/createservice.php',
        url: base_url + 'instrumentService',
        data: {
            state: state,
            county: county,
            docId: docId,
            recDate: recDate,
            methodId: 3,
        },
        dataType: "xml",
        type: "POST"
    })
        .done(function (response, textStatus, jqXHR) {

            var responseStatus = $(response).find('ReturnStatus').text();

            if (responseStatus == 'Failed') {

                $('#instrumentInfoFile').prev('.loader').hide();
                $('#instrumentInfoFile').css('border', '1px solid #000000');
                $('#instrumentInfoFile').css('padding', '15px');
                $('#instrumentInfoFile').html('<span class="orderinfo1">No data found.</span>');

            }
            else if (responseStatus == 'Success') {
                $requestId = $(response).find('RequestID').text();
                getInstrumentRequestSummaries($requestId, '3', fileNumber);
            }
        })
        .fail(function (err) {

            $('#instrumentInfoFile').prev('.loader').hide();
            $('#instrumentInfoFile').css('border', '1px solid #000000');
            $('#instrumentInfoFile').css('padding', '15px');
            $('#instrumentInfoFile').html('<span class="orderinfo1">No data found.</span>');

        });
}

function getInstrumentRequestSummaries(requestId, methodId, fileNumber) {
    var apn = $("#apn").val();
    $.ajax({
        url: base_url + 'getRequestSummaries',
        data: {
            requestId: requestId,
            methodId: methodId,
            apn: apn,
        },
        dataType: "xml",
        type: "POST"
    })
        .done(function (response, textStatus, jqXHR) {

            var responseStatus = $(response).find('ReturnStatus').text();

            if (responseStatus == 'Failed') {
                $('#instrumentInfoFile').prev('.loader').hide();
                $('#instrumentInfoFile').css('border', '1px solid #000000');
                $('#instrumentInfoFile').css('padding', '15px');
                $('#instrumentInfoFile').html('<span class="orderinfo1">No data found.</span>');
            }
            else if (responseStatus == 'Success') {
                serviceId = '';
                serviceId = $(response).find("RequestSummaries:first").find("RequestSummary:first").find("Order:first").find("Services:first").find("Service:first").find("ID:first").text();
                imageCreateRequest(serviceId, methodId, fileNumber);
            }
        })
        .fail(function (err) {
            $('#instrumentInfoFile').prev('.loader').hide();
            $('#instrumentInfoFile').css('border', '1px solid #000000');
            $('#instrumentInfoFile').css('padding', '15px');
            $('#instrumentInfoFile').html('<span class="orderinfo1">No data found.</span>');
        });
}

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
    return new Blob(byteArrays, { type: contentType });
}

function download(filename, text) {

    /*if(L_V_CreateService == '' || L_V_GetRequestSummary == '' || L_V_GetResultById == '')
    {
        var csvData = base64toBlob(text,'application/octet-stream');
        var csvURL = navigator.msSaveBlob(csvData, filename);

        var element = document.createElement('a');
        element.setAttribute('href', csvURL);
        element.setAttribute('download', filename);

        element.style.display = 'none';
        document.body.appendChild(element);

        document.body.removeChild(element);
    }
    else
    {*/
    if (navigator.msSaveBlob) {
        var csvData = base64toBlob(text, 'application/octet-stream');
        var csvURL = navigator.msSaveBlob(csvData, filename);
        var element = document.createElement('a');
        element.setAttribute('href', csvURL);
        element.setAttribute('download', filename);

        element.style.display = 'none';
        document.body.appendChild(element);

        document.body.removeChild(element);
    }
    else {
        var csvURL = 'data:application/octet-stream;base64,' + text;
        var element = document.createElement('a');
        element.setAttribute('href', csvURL);
        element.setAttribute('download', filename);

        element.style.display = 'none';
        document.body.appendChild(element);
        element.click();
        document.body.removeChild(element);
    }
    /*}*/


}

function generateGrantDeed(fips, year, docId, fileNumber) {
    $('#instrumentInfoFile').next('.loader').show();
    $.ajax({
        url: base_url + 'generate-grant-deed',
        data: {
            fips: fips,
            year: year,
            docId: docId,
            fileNumber: fileNumber,
        },
        dataType: "xml",
        type: "POST"
    })
        .done(function (response, textStatus, jqXHR) {

            var responseStatus = $(response).find('Documents:first').find('DocumentResponse:first').find('DocStatus:first').find('Msg:first').text();

            if (responseStatus == 'OK') {
                var base64_data = $(response).find("Documents:first").find("DocumentResponse:first").find("Document:first").find("Body:first").find("Body:first").text();
                var bin = atob(base64_data);
                if (navigator.msSaveBlob) {
                    var filename = "GrantDeed.pdf";
                    download(filename, base64_data);
                }
                else {
                    download('GrantDeed.pdf', base64_data);
                }
                $('#instrumentInfoFile').next('.loader').hide();
            }
            else {
                $('#instrumentInfoFile').prev('.loader').hide();
                $('#instrumentInfoFile').css('border', '1px solid #000000');
                $('#instrumentInfoFile').css('padding', '15px');
                $('#instrumentInfoFile').html('<span class="orderinfo1">No data found.</span>');
            }
        })
        .fail(function (err) {
            $('#instrumentInfoFile').prev('.loader').hide();
            $('#instrumentInfoFile').css('border', '1px solid #000000');
            $('#instrumentInfoFile').css('padding', '15px');
            $('#instrumentInfoFile').html('<span class="orderinfo1">No data found.</span>');
        });
}

function generateTaxDoc(apn, serviceId, fileNumber) {
    $('#taxDocumentInfo').next('.loader').show();
    $.ajax({
        url: base_url + 'generate-tax-doc',
        data: {
            apn: apn,
            serviceId: serviceId,
            fileNumber: fileNumber
        },
        dataType: "xml",
        type: "POST"
    })
        .done(function (response, textStatus, jqXHR) {

            var responseStatus = $(response).find('Documents:first').find('DocumentResponse:first').find('DocStatus:first').find('Msg:first').text();

            if (responseStatus == 'OK') {
                var base64_data = $(response).find("Documents:first").find("DocumentResponse:first").find("Document:first").find("Body:first").find("Body:first").text();
                var bin = atob(base64_data);
                if (navigator.msSaveBlob) {
                    var filename = "Tax.pdf";
                    download(filename, base64_data);
                }
                else {
                    download('Tax.pdf', base64_data);
                }
                $('#taxDocumentInfo').next('.loader').hide();
            }
            else {
                $('#taxDocumentInfo').prev('.loader').hide();
                $('#taxDocumentInfo').css('border', '1px solid #000000');
                $('#taxDocumentInfo').css('padding', '15px');
                $('#taxDocumentInfo').html('<span class="orderinfo1">No data found.</span>');
            }
        })
        .fail(function (err) {
            $('#taxDocumentInfo').prev('.loader').hide();
            $('#taxDocumentInfo').css('border', '1px solid #000000');
            $('#taxDocumentInfo').css('padding', '15px');
            $('#taxDocumentInfo').html('<span class="orderinfo1">No data found.</span>');
        });
}

function notifyAdmin(subject) {
    var customer_id = $("#CustomerId").val();
    var property_full_address = $("#property-full-address").val();
    if (customer_id) {
        $.ajax({
            url: base_url + 'notifyAdmin',
            type: "POST",//type of posting the data
            data: {
                customer_id: customer_id,
                property: property_full_address,
                subject: subject,
            },
            success: function (data) {
                console.log(data);
            },
            error: function (xhr, ajaxOptions, thrownError) {

            },
        });
    }
}

function getProductTypes() {
    var email = $('#OpenEmail').val();
    var customerId = $('#CustomerId').val();
    if (email) {
        $.ajax({
            url: base_url + 'get-product-types',
            type: "POST",//type of posting the data
            data: {
                email: email,
                customerId: customerId
            },
            success: function (data) {
                var res = jQuery.parseJSON(data);

                if (res) {
                    var output = [];
                    output.push('<option value="">Select Product</option>')
                    $.each(res, function (key, value) {
                        output.push('<option value="' + key + '">' + value + '</option>');
                    });
                    $('#ProductTypeID').html(output.join(''));
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {

            },
        });
    }
}

function getDeliverables(partner_id) {
    $.ajax({
        url: base_url + "frontend/order/home/getDeliverables",
        type: "POST",
        data: {
            partner_id: partner_id,
        },
        async: true,
        success: function (result) {
            if (result) {
                var res = jQuery.parseJSON(result);
                var preDeliverables = $("input[name^='AdditionalEmail']").length;
                for (j = 1; j < preDeliverables; j++) {
                    $("#cloner" + j)[0].click();
                }
                $('#AdditionalEmail').val('');

                if (res.deliverables.length > 0) {

                    for (i = 0; i < res.deliverables.length; i++) {
                        if (i == 0) {
                            $('#AdditionalEmail').val(res.deliverables[i]);
                        } else {
                            if ($("#clonea").length > 0) {
                                $("#clonea")[0].click();
                            }
                        }
                    }
                    for (i = 0; i < res.deliverables.length; i++) {
                        if (i != 0) {
                            var emailVal = res.deliverables[i];
                            $('#AdditionalEmail' + i).val(emailVal);
                        }
                    }
                }
            }
        },
        error: function () {

        },
    });
}


