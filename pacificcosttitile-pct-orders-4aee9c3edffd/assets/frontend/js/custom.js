var counties = {
    'Alameda': '06001',
    'Alpine': '06003',
    'Amador': '06005',
    'Butte': '06007',
    'Calaveras': '06009',
    'Colusa': '06011',
    'Contra Costa': '06013',
    'Del Norte': '06015',
    'El Dorado': '06017',
    'Fresno': '06019',
    'Glenn': '06021',
    'Humboldt': '06023',
    'Imperial': '06025',
    'Inyo': '06027',
    'Kern': '06029',
    'Kings': '06031',
    'Lake': '06033',
    'Lassen': '06035',
    'Los Angeles': '06037',
    'Madera': '06039',
    'Marin': '06041',
    'Mariposa': '06043',
    'Mendocino': '06045',
    'Merced': '06047',
    'Modoc': '06049',
    'Mono': '06051',
    'Monterey': '06053',
    'Napa': '06055',
    'Nevada': '06057',
    'Orange': '06059',
    'Placer': '06061',
    'Plumas': '06063',
    'Riverside': '06065',
    'Sacramento': '06067',
    'San Benito': '06069',
    'San Bernardino': '06071',
    'San Diego': '06073',
    'San Francisco': '06075',
    'San Joaquin': '06077',
    'San Luis': '06079',
    'San Mateo': '06081',
    'Santa Barbara': '06083',
    'Santa Clara': '06085',
    'Santa Cruz': '06087',
    'Shasta': '06089',
    'Sierra': '06091',
    'Siskiyou': '06093',
    'Solano': '06095',
    'Sonoma': '06097',
    'Stanislaus': '06099',
    'Sutter': '06101',
    'Tehama': '06103',
    'Trinity': '06105',
    'Tulare': '06107',
    'Tuolumne': '06109',
    'Ventura': '06111',
    'Yolo': '06113',
    'Yuba': '06115',
};

$(document).ready(function () {
    reportData = {};
    apnInfo = {};
    var isNewSearch = false;
    var request = '';

    autoComplete();
    $(document).on('click', '.search-property', getAddress);
    $(document).on('click', '.search-apn', getAPN);
    $(document).on('click', '.switch-apn-button', switchAPN);
    $(document).on('click', '.switch-property-button', switchProperty);

    //customer no
    $('#btn-place-order').click(function (e) {

        if ($("input[name=add-agent-details]").is(":checked")) {
            if (!$('#BuyerAgentEmailAddress').val() && !$('#ListingAgentEmailAddress').val()) {
                $('#required-agent-details').show();
            }

        }
        else {
            $('#required-agent-details').hide();
        }

    });

    $('#getCustomerInfo').click(function (e) {
        var customer_no = $('#CustomerNumber').val();

        if (!customer_no) {
            $('#CustomerNumber-error').html('Enter your customer number');
            $('#CustomerNumber-error').show();
            $("#CustomerNumber").parent().addClass('state-error');
        }
        else {
            $.ajax({
                // url: "php/search.php",
                url: base_url + 'home/getCustomerDetails',
                type: "POST",//type of posting the data
                data: {
                    customer_no: customer_no
                },
                success: function (data) {

                    var res = jQuery.parseJSON(data);
                    if (jQuery.isEmptyObject(res)) {
                        $("#CustomerNumber").removeAttr('readonly').parent().removeClass('state-success').addClass('state-error');
                        $("#CustomerId").val('');
                        $('#CustomerNumber-error').html('Invalid customer number');
                        $('#CustomerNumber-error').show();
                    }
                    else {
                        $('#CustomerNumber-error').html('');
                        $('#CustomerNumber-error').hide();
                        $("#OpenName").val(res.first_name).attr('readonly', 'readonly').parent().addClass('state-success');
                        $("#OpenLastName").val(res.last_name).attr('readonly', 'readonly').parent().addClass('state-success');
                        $("#Opentelephone").val(res.telephone_no).attr('readonly', 'readonly').parent().addClass('state-success');
                        $("#OpenEmail").val(res.email_address).attr('readonly', 'readonly').parent().addClass('state-success');
                        $("#CompanyName").val(res.company_name).attr('readonly', 'readonly').parent().addClass('state-success');
                        $("#StreetAddress").val(res.street_address).attr('readonly', 'readonly').parent().addClass('state-success');
                        $("#City").val(res.city).attr('readonly', 'readonly').parent().addClass('state-success');
                        $("#Zipcode").val(res.zip_code).attr('readonly', 'readonly').parent().addClass('state-success');
                        $("#CustomerId").val(res.id);

                        /*if(res.is_escrow == 1)
                        {
                            $('#add-lender-section').show();
                            $('#add-escrow-section').hide();
                            $('#escrow-details-fields').hide();
                            $('#add-lender-details').trigger('change');
                        }
                        else
                        {
                            $('#add-lender-section').hide();
                            $('#add-escrow-section').show();
                            $('#lender-details-fields').hide();
                            $('#add-escrow-details').trigger('change');
                        }*/
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {

                },
            });
        }
    });

    jQuery('#CustomerNumber').change(function () {
        $('#getCustomerInfo').trigger('click');
    });

    $("#find-customer-form").validate({

        /* @validation states + elements 
        ------------------------------------------- */
        errorClass: "state-error",
        validClass: "state-success",
        errorElement: "em",
        onkeyup: false,
        onclick: false,

        /* @validation rules 
        ------------------------------------------ */
        rules: {
            CustomerEmail: {
                required: true,
                email: true,
                remote: {
                    // url: 'php/checkemail.php',
                    url: base_url + 'home/checkEmail',
                    type: "POST",
                    data: {
                        title: function () {
                            return $("#CustomerEmail").val();
                        }
                    },
                },
            }
        },

        /* @validation error messages 
        ---------------------------------------------- */
        messages: {
            CustomerEmail: {
                required: 'Enter your email address',
                email: 'Enter a valid email address',
                remote: "Email address not exist"
            }
        },

        /* @validation highlighting + error placement  
        ---------------------------------------------------- */
        highlight: function (element, errorClass, validClass) {
            $(element).closest('.field').addClass(errorClass).removeClass(validClass);
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).closest('.field').removeClass(errorClass).addClass(validClass);
        },
        errorPlacement: function (error, element) {
            if (element.is(":radio") || element.is(":checkbox")) {
                element.closest('.option-group').after(error);
            } else {
                error.insertAfter(element.parent());
            }
        },

        /* @ajax form submition 
        ---------------------------------------------------- */
        submitHandler: function (form) {
            /*$(form).ajaxSubmit({
                    
                error:function(){
                    // $('.form-footer').removeClass('progress');
                },
                success:function(data){
                    var res = jQuery.parseJSON(data);
                    
                    $('#findCustomerModal').modal('hide');
                    if(res.customer_number)
                    {
                        var content = "<h3>Your Customer Number is:"+res.customer_number+"</h3>";

                    }
                    else
                    {
                        var content = '<h3> No data found</h3>';
                    }
                    $("#showCustomerNumber").html(content);
                    $("#showCustomernumberModal").modal('show');
                }
            });*/

            $.ajax({
                url: base_url + 'home/getCustomerNumber',
                type: "POST",
                data: {
                    email_address: $("#CustomerEmail").val(),
                },
                success: function (result) {
                    var res = jQuery.parseJSON(result);

                    $('#findCustomerModal').modal('hide');
                    if (res.customer_number) {
                        var content = "<h3>Your Customer Number is:" + res.customer_number + "</h3>";

                    }
                    else {
                        var content = '<h3> No data found</h3>';
                    }
                    $("#showCustomerNumber").html(content);
                    $("#showCustomernumberModal").modal('show');
                },
                error: function () {
                    alert('Something went wrong');
                },
            });
        }
    });

    $('#findCustomerModal').on('hidden.bs.modal', function () {
        $(this).find('form#find-customer-form').trigger('reset');
    });

    $('#findCustomerNumber').click(function (e) {
        $('#findCustomerModal').modal('show');
    });

    $('#add-agent-details').change(function () {
        if (this.checked) {
            $('#agent-details-fields').show();
        }
        else {
            $("#BuyerAgentName").val('').parent().removeClass('state-success');
            $("#BuyerAgentEmailAddress").val('').parent().removeClass('state-success');
            $("#BuyerAgentTelephone").val('').parent().removeClass('state-success');
            $("#BuyerAgentCompany").val('').parent().removeClass('state-success');
            $("#BuyerAgentId").val('');
            $("#ListingAgentName").val('').parent().removeClass('state-success');
            $("#ListingAgentEmailAddress").val('').parent().removeClass('state-success');
            $("#ListingAgentTelephone").val('').parent().removeClass('state-success');
            $("#ListingAgentCompany").val('').parent().removeClass('state-success');
            $("#ListingAgentId").val('');
            $('#agent-details-fields').hide();
            $('#required-agent-details').hide();
        }
    });

    $('#add-lender-details').change(function () {
        if (this.checked) {
            $('#lender-details-fields').show();
        }
        else {
            $("#LenderName").val('').parent().removeClass('state-success');
            $("#LenderEmailAddress").val('').parent().removeClass('state-success');
            $("#LenderTelephone").val('').parent().removeClass('state-success');
            $("#LenderCompany").val('').parent().removeClass('state-success');
            $("#LenderId").val('');
            $('#lender-details-fields').hide();
        }
    });

    $('#add-escrow-details').change(function () {
        if (this.checked) {
            $('#escrow-details-fields').show();
        }
        else {
            $("#EscrowName").val('').parent().removeClass('state-success');
            $("#EscrowEmailAddress").val('').parent().removeClass('state-success');
            $("#EscrowTelephone").val('').parent().removeClass('state-success');
            $("#EscrowCompany").val('').parent().removeClass('state-success');
            $("#EscrowId").val('');
            $('#escrow-details-fields').hide();
        }
    });

    $('#add-escrow-officer-details').change(function () {
        if (this.checked) {
            $('#escrow-officer-field').show();
        } else {
            $('#escrow-officer-field').hide();
        }
    });

    $("#BuyerAgentName").autocomplete({
        source: function (request, response) {
            $.ajax({
                url: base_url + 'agent/getAgentDetails',
                // dataType: "json",
                data: {
                    term: request.term,//the value of the input is here

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
            $('#required-agent-details').hide();
            $("#BuyerAgentName").val(ui.item.name);
            /*$("#AgentFirstName").val(ui.item.first_name);
            $("#AgentLastName").val(ui.item.last_name).attr('readonly','readonly').parent().addClass('state-success');*/
            $("#BuyerAgentEmailAddress").val(ui.item.email_address).parent().addClass('state-success');
            $("#BuyerAgentTelephone").val(ui.item.telephone_no).parent().addClass('state-success');
            $("#BuyerAgentCompany").val(ui.item.company).parent().addClass('state-success');
            $("#BuyerAgentId").val(ui.item.id);
            $("#buyer_agent_partner_id").val(ui.item.partner_id);
        },
        change: function (event, ui) {
            if (ui.item == null) {
                /*$("#BuyerAgentEmailAddress").val('').parent().removeClass('state-success').addClass('state-error');
                $("#BuyerAgentTelephone").val('').parent().removeClass('state-success').addClass('state-error');
                $("#BuyerAgentCompany").val('').parent().removeClass('state-success').addClass('state-error');
                $("#BuyerAgentId").val('');*/
            }
        }
    });

    /* Listing Agent autocomplete */
    $("#ListingAgentName").autocomplete({
        source: function (request, response) {
            $.ajax({
                url: base_url + 'agent/getAgentDetails',
                data: {
                    term: request.term,//the value of the input is here

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
            $('#required-agent-details').hide();
            event.preventDefault();
            $("#ListingAgentName").val(ui.item.name);

            $("#ListingAgentEmailAddress").val(ui.item.email_address).parent().addClass('state-success');
            $("#ListingAgentTelephone").val(ui.item.telephone_no).parent().addClass('state-success');
            $("#ListingAgentCompany").val(ui.item.company).parent().addClass('state-success');
            $("#ListingAgentId").val(ui.item.id);
            $("#listing_agent_partner_id").val(ui.item.partner_id);
        },
        change: function (event, ui) {
            if (ui.item == null) {
                // $("#AgentLastName").val('').removeAttr('readonly').parent().removeClass('state-success').addClass('state-error');
                /*$("#ListingAgentEmailAddress").val('').parent().removeClass('state-success').addClass('state-error');
                $("#ListingAgentTelephone").val('').parent().removeClass('state-success').addClass('state-error');
                $("#ListingAgentCompany").val('').parent().removeClass('state-success').addClass('state-error');
                $("#ListingAgentId").val('');*/
            }
        }
    });
    /* Listing Agent autocomplete */

    /* Lender autocomplete */
    $("#LenderCompany").autocomplete({
        // source: "php/usersearch.php",
        source: function (request, response) {
            $.ajax({
                url: base_url + 'home/getDetailsByName',
                data: {
                    term: request.term,//the value of the input is here
                    is_escrow: 0,
                    is_from_order_form: 1
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
            $("#LenderName").val(ui.item.name);
            $("#LenderEmailAddress").val(ui.item.email_address).parent().addClass('state-success');
            $("#LenderTelephone").val(ui.item.telephone_no).parent().addClass('state-success');
            $("#LenderCompany").val(ui.item.company).parent().addClass('state-success');
            $("#LenderId").val(ui.item.id);
        },
        change: function (event, ui) {
            if (ui.item == null) {
                /* $("#LenderEmailAddress").val('').parent().removeClass('state-success').addClass('state-error');
                 $("#LenderTelephone").val('').parent().removeClass('state-success').addClass('state-error');
                 $("#LenderCompany").val('').parent().removeClass('state-success').addClass('state-error');
                 $("#LenderId").val('');*/
            }
        }
    });
    /* Lender autocomplete */

    /* Escrow autocomplete */
    $("#EscrowCompany, #EscrowName").autocomplete({
        source: function (request, response) {
            $.ajax({
                url: base_url + 'home/getDetailsByName',
                data: {
                    term: request.term,//the value of the input is here
                    is_escrow: 1,
                    is_from_order_form: 1
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
            $("#EscrowName").val(ui.item.name);
            $("#EscrowEmailAddress").val(ui.item.email_address).parent().addClass('state-success');
            $("#EscrowTelephone").val(ui.item.telephone_no).parent().addClass('state-success');
            $("#EscrowCompany").val(ui.item.company).parent().addClass('state-success');
            $("#EscrowId").val(ui.item.id);
        },
        change: function (event, ui) {
            if (ui.item == null) {
                /*$("#EscrowEmailAddress").val('').parent().removeClass('state-success').addClass('state-error');
                $("#EscrowTelephone").val('').parent().removeClass('state-success').addClass('state-error');
                $("#EscrowCompany").val('').parent().removeClass('state-success').addClass('state-error');
                $("#EscrowId").val('');*/
            }
        }
    });
    /* Escrow autocomplete */

    $('#ProductTypeID').change(function () {
        var selectedText = $(this).find('option:selected').text();
        $('#sales-loan-amount-fields').show();

        if ($(this).val() == '4' || $(this).val() == '5' || $(this).val() == '36' || $(this).val() == '22' || $(this).val() == '26' || $(this).val() == '24' || $(this).val() == '27' || $(this).val() == '40') {
            $('#add-escrow-officer-section').show();
        } else {
            $('#add-escrow-officer-section').hide();
            $('#escrow-officer-field').hide();
            $('#add-escrow-officer-details').prop('checked', false);
        }

        if (selectedText.includes("Loan")) {
            $('#sales-loan-amount-fields #salesAmount').hide();
            $('#sales-loan-amount-fields #primaryBorrower').hide();
            $('#sales-loan-amount-fields #secondaryBorrower').hide();
        } else if (selectedText.includes("Sale")) {
            $('#sales-loan-amount-fields #salesAmount').show();
            $('#sales-loan-amount-fields #primaryBorrower').show();
            $('#sales-loan-amount-fields #secondaryBorrower').show();
        } else {
            $('#sales-loan-amount-fields').hide();
        }
        $('#ProductType').val(selectedText);
    });

    /** ION Report requirement status */
    $('.ion-proceed').click(function () {
        $('#ion-report-status').val(false);
        $('.ion-result table > tbody').html('');
        $('#searchFraudResultModal').modal('hide');
    });

    $('.ion-review-fraud').click(function () {
        $('#ion-report-status').val(true); // ION Fraud report 
        $('.ion-result table > tbody').html('');
        $('#searchFraudResultModal').modal('hide');
    });
    /** ION Report requirement status */
});

function autoComplete() {
    // use Google Places API to autocomplete address searches and bias suggestions to California
    var input = document.getElementById('property-search');
    var defaultBounds = new google.maps.LatLngBounds(
        new google.maps.LatLng(-32.30, 114.8),
        new google.maps.LatLng(-42, 124.24)); // latitude and longitude ranges of California
    var options = {
        componentRestrictions: {
            country: 'us'
        },
        bounds: defaultBounds
    };
    autocomplete = new google.maps.places.Autocomplete(input, options);
    google.maps.event.addListener(autocomplete, 'place_changed', function () {
        var place = autocomplete.getPlace(); // get address, without city and state
        $('#property-full-address').val(place.formatted_address);
        setTimeout(function () {
            $('#property-search').val(place.name);
        }, 25); // just display street address
        for (var i = 0; i < place.address_components.length; i++) {
            for (var j = 0; j < place.address_components[i].types.length; j++) {
                if (place.address_components[i].types[j] === ("locality" || "political")) {
                    var city = place.address_components[i].long_name;
                    $('#property-city').val(city);
                }
                else if (place.address_components[i].types[0] === ("administrative_area_level_1") && place.address_components[i].types.length > 1 && place.address_components[i].types[1] === ("political")) { //administrative_area_level_1
                    var state = place.address_components[i].short_name;
                    $('#property-state').val(state);
                }
                else if (place.address_components[i].types[0] === ("postal_code")) {
                    var state = place.address_components[i].short_name;
                    $('#property-zip').val(state);
                }
                else if (place.address_components[i].types[0] === "neighborhood" && place.address_components[i].types.length > 1 && place.address_components[i].types[1] === ("political")) {
                    var neighborhood = place.address_components[i].long_name;
                    $('#neighbourhood').val(neighborhood);
                }
            }
        }
    });
}

// processes inputted address
function getAddress() {

    $('.pma-error').hide();
    isNewSearch = true;
    event.preventDefault ? event.preventDefault() : event.returnValue = false;

    address = $('#property-search').val();
    address = $.trim(address);

    if (address == '') {
        $('.pma-error').html('Please search any address.');
        $('.pma-error').show();
        $('#property-search').parent().addClass('state-error');
        return;
    } else {
        $('.pma-error').html('');
        $('.pma-error').hide();
    }

    $("#search-btn").parents("form").find(".search-loader").removeClass("hidden");
    var locale = $('#property-city').val();
    locale = $.trim(locale);

    state = $('#property-state').val();
    state = $.trim(state);

    if (isNaN(locale[0])) {
        // locale += ', CA' // if locale is city rather than zip, add in state
        if (state !== '') {
            locale += ', ' + state;
        } else {
            locale += ', CA' // if locale is city rather than zip, add in state
        }
    }
    neighbourhood = $('#neighbourhood').val();
    neighbourhood = $.trim(neighbourhood);
    if (isNaN(neighbourhood[0])) {
        if (state !== '') {
            neighbourhood += ', ' + state;
        } else {
            neighbourhood += ', CA' // if neighbourhood is city rather than zip, add in state
        }
    }
    // data(address, locale);
    data(address, locale, neighbourhood, false);
}

// creates data object for AJAX call to API
function data(address, locale, neighbourhood, retry) {
    dataObj = {};
    dataObj.Address = address;
    dataObj.LastLine = locale.toString();
    dataObj.ClientReference = '<CustCompFilter><CompNum>8</CompNum><MonthsBack>12</MonthsBack></CustCompFilter>';
    dataObj.OwnerName = '';
    request = 'http://api.sitexdata.com/sitexapi/sitexapi.asmx/AddressSearch?';
    request += $.param(dataObj);
    compileRequest(dataObj, neighbourhood, retry);
}

// create url for API request
function compileRequest(dataObj, neighbourhood, retry) {
    // var request = 'http://api.sitexdata.com/sitexapi/sitexapi.asmx/AddressSearch?';
    var request = 'http://api.sitexdata.com/sitexapi/sitexapi.asmx/AddressSearch?'
    if (retry) {
        dataObj.LastLine = neighbourhood.toString();
    }
    request += $.param(dataObj);
    // runQueries(request,dataObj,neighbourhood,retry);
    fetchReports('187', request, dataObj, neighbourhood, retry);
}

function fetchReports(repNum, request, dataObj, neighbourhood, retry) {
    reportNum = repNum;
    $.ajax({
        url: base_url + 'home/getSearchResults?',
        data: {
            requrl: request + '&reportType=' + reportNum
        },
        dataType: 'xml'
    })
        .done(function (response, textStatus, jqXHR) {
            var responseStatus = $(response).find('StatusCode').text();
            $("#search-btn").parents("form").find(".search-loader").addClass("hidden");

            if (responseStatus == 'MM') {
                multipleResults(response);
                $("#search-btn").parents("form").find("table").removeClass("hidden");
                $(".buttonNext").removeClass("buttonDisabled");
            }
            else if (responseStatus != 'OK') {
                if (!retry) {
                    $("#search-btn").parents("form").find(".search-loader").removeClass("hidden");
                    data(dataObj.Address, dataObj.LastLine, neighbourhood, true);
                } else {
                    displayError(responseStatus);

                    if (base_url == 'http://localhost-pct.com/') {
                        var fipCode = $('#property-fips').val();
                        var city = $('#property-city').val();
                        var apn = $('#apn').val();
                        var state = $('#property-state').val();
                        var county = $('#County').val();
                        var property_full_add = $('#property-full-address').val();
                        // getProductTypes(county,state);
                        var random_number = Date.now() + (Math.floor(Math.random() * (10000 - 1 + 1)) + 1);
                        if ($('#random_number').length) {
                            $('#random_number').val(random_number);
                        }
                        var unit_no = 1;
                        createService4(fipCode, address, city, unit_no, apn, random_number);
                        createService3(apn, state, county, random_number);
                    }
                }
            }
            else {
                compileXmlUrls(response, '187');

                if (isNewSearch) {
                    multipleResults(response);
                }
                else {
                    get187();
                }
                /*$("#search-btn").parents("form").find("table").removeClass("hidden");
                $(".buttonNext").removeClass("buttonDisabled");*/
            }
        })
        .fail(function (err) {
            $('.pma-error').text('Unsuccessful Request');
            $(".buttonNext").addClass("buttonDisabled");
        });
}

// extracts url for report from API response and adds to reportData object
function compileXmlUrls(response, report) {
    // get the url for each report

    reportUrl = $(response).find('ReportURL').text();
    reportData.report187 = reportUrl;
    // reportData.report187 = "https://api.sitexdata.com/187/1E0F8F50-6300-4d9f-BA0F-180ADAEDF187.asmx/GetXML?reportInfo=dKkqbOJCdWKhyaFj6Y1iSlrrt7qlKMPG7DnIfoL_3BRS2Xh0YN_O4Jv3DrD-3mpXtVRlphtwkaLM7COOoWLTY2P_pLWxtG_goKDG0-Sr_RLj29EYBmnnGByXR7q8FXUFsMlIXFZ3vu0fLzr8tP73h5nGVZQijmIYoX01&filter=<CustCompFilter><SQFT>0.20</SQFT><Radius>0.75</Radius></CustCompFilter>";//reportUrl;
}


function get187() {
    address = $('#property-search').val();
    address = $.trim(address);
    $.ajax({
        url: base_url + 'home/getSearchResults?',
        data: {
            requrl: reportData.report187,
            address: address
        },
        dataType: "xml",
        success: function (xml) {
            reportXML = xml;
            parse187();
        },
        error: function () {
            console.log("An error occurred while processing XML file.");
        }
    });
}
var ionReportData = null;
function getIonReport(address, state) {
    ionReportData = null;
    address = $('#property-search').val();
    address = $.trim(address);
    $.ajax({
        url: base_url + 'getIonReport',
        data: {
            address: address,
            state: state
        },
        type: "POST",
        async: false,
        success: function (data) {
            let ionData = jQuery.parseJSON(data);
            if (ionData.status) {
                ionReportData = ionData.data;
                displayIonReport();
            }
            // parse187();
        },
        error: function () {
            console.log("An error occurred while processing XML file.");
        }
    });
}

function parse187() {
    let propertyCharacteristics = $(reportXML).find("PropertyProfile").find("PropertyCharacteristics");
    let bedrooms = baths = lotSize = zoning = buildingArea = '';
    let properyData = [];
    if (propertyCharacteristics) {
        properyData['bedrooms'] = $(reportXML).find("PropertyProfile").find("PropertyCharacteristics").find("Bedrooms").text();
        properyData['baths'] = $(reportXML).find("PropertyProfile").find("PropertyCharacteristics").find("Baths").text();
        properyData['lotSize'] = $(reportXML).find("PropertyProfile").find("PropertyCharacteristics").find("LotSize").text();
        properyData['zoning'] = $(reportXML).find("PropertyProfile").find("PropertyCharacteristics").find("Zoning").text();
        properyData['buildingArea'] = $(reportXML).find("PropertyProfile").find("PropertyCharacteristics").find("BuildingArea").text();
    }

    var ownerNamePrimary = $(reportXML).find("PropertyProfile").find("PrimaryOwnerName").text();
    var ownerNameSecondary = $(reportXML).find("PropertyProfile").find("SecondaryOwnerName").text();

    if (ownerNamePrimary.indexOf(';') !== -1) {
        ownerNameSecondary = ownerNamePrimary.substr(ownerNamePrimary.indexOf(";") + 1)
        ownerNamePrimary = ownerNamePrimary.slice(0, ownerNamePrimary.indexOf(";"));
    } else if (ownerNamePrimary.indexOf('&') !== -1) {
        ownerNameSecondary = ownerNamePrimary.substr(ownerNamePrimary.indexOf("&") + 1)
        ownerNamePrimary = ownerNamePrimary.slice(0, ownerNamePrimary.indexOf("&"));
    }
    ownerNamePrimary = $.trim(ownerNamePrimary);
    ownerNameSecondary = $.trim(ownerNameSecondary);
    ownerNamePrimary = toTitleCase(ownerNamePrimary);
    ownerNameSecondary = toTitleCase(ownerNameSecondary);
    ownerNamePrimary = ownerNamePrimary.replace(',', '');
    ownerNameSecondary = ownerNameSecondary.replace(',', '');
    ownerNamePrimaryLast = ownerNamePrimary.split(' ')[0];
    ownerNameSecondaryLast = ownerNameSecondary.split(' ')[0];
    ownerNamePrimary = ownerNamePrimary.substr(ownerNamePrimary.indexOf(" ") + 1) + ' ' + ownerNamePrimaryLast;
    if (ownerNameSecondary) {
        ownerNameSecondary = ownerNameSecondary.substr(ownerNameSecondary.indexOf(" ") + 1) + ' ' + ownerNameSecondaryLast;
    }

    var full_address = [];

    var unit_no = $(reportXML).find("PropertyProfile").find("SiteUnit").text();
    if (unit_no) {
        full_address.push(unit_no);
    }

    var address = toTitleCase($(reportXML).find("PropertyProfile").find("SiteAddress").text());
    if (address) {
        full_address.push(address);
    }

    var city = toTitleCase($(reportXML).find("PropertyProfile").find("SiteCity").text());

    if (city) {
        full_address.push(city);
    }
    var property_full_add = full_address.join(', ');
    var state = $(reportXML).find("PropertyProfile").find("SiteState").text();

    if (state) {
        full_address.push(state);
    }

    var zip = $(reportXML).find("PropertyProfile").find("SiteZip").text();

    if (zip) {
        full_address.push(zip);
    }

    var apn = $(reportXML).find("PropertyProfile").find("APN").text();
    var county = toTitleCase($(reportXML).find("SubjectValueInfo").find("CountyName").text());
    /*if(county)
    {
        getProductTypes(county,state);
    }*/
    var legalDescription = toTitleCase($(reportXML).find("PropertyProfile").find("LegalBriefDescription").text());
    legalDescription = legalDescription.replace(/\s\s+/g, ' ');
    var usecode = toTitleCase($(reportXML).find("PropertyProfile").find("UseCode").text());
    $('#property-type').val(usecode);
    $('#property-zip').val(zip);
    $('#property-state').val(state);
    $('#property-city').val(city);
    $('#property-search').val(address);
    $('#property-full-address').val(full_address.join(', ')).prop('readonly', true);
    $('#FullProperty').val(full_address.join(', ')).prop('readonly', true);
    $('#unit_number').val(unit_no);
    $('#apn').val(apn).prop('readonly', true);
    $('#County').val(county).prop('readonly', true);
    $('#LegalDescription').val(legalDescription).prop('readonly', true);
    $('#PrimaryOwner').val(ownerNamePrimary).prop('readonly', true);
    $('#SecondaryOwner').val(ownerNameSecondary).prop('readonly', true);
    $("#searchResultModal").find(".apn-search-loader").addClass("hidden");
    $('#searchResultModal').modal('hide');
    var fipCode = $('#property-fips').val();
    var ionReportFlag = $('#ion-report-flag').val();
    if (ionReportFlag) {
        getIonReport(address, state);
    }
    $.ajax({
        url: base_url + 'home/checkDuplicateOrder',
        type: "POST",
        data: {
            apn: apn
        },
        async: false,
        success: function (data) {
            var res = jQuery.parseJSON(data);
            if (res.success === true) {
                $('.pma-error').text('Order is already exist for this property.');
                $('.pma-error').show();
                return false;
            }
            else {
                var random_number = Date.now() + (Math.floor(Math.random() * (10000 - 1 + 1)) + 1);
                if ($('#random_number').length) {
                    $('#random_number').val(random_number);
                }
                createService4(fipCode, address, city, unit_no, apn, random_number, properyData);
                createService3(apn, state, county, random_number);
            }
        }
    });

}

function displayIonReport() {
    $('.ion-result table > tbody').html('');
    var ownerNamePrimary = $(reportXML).find("PropertyProfile").find("PrimaryOwnerName").text();
    var ownerNameSecondary = $(reportXML).find("PropertyProfile").find("SecondaryOwnerName").text();

    if (ownerNamePrimary.indexOf(';') !== -1) {
        ownerNameSecondary = ownerNamePrimary.substr(ownerNamePrimary.indexOf(";") + 1)
        ownerNamePrimary = ownerNamePrimary.slice(0, ownerNamePrimary.indexOf(";"));
    } else if (ownerNamePrimary.indexOf('&') !== -1) {
        ownerNameSecondary = ownerNamePrimary.substr(ownerNamePrimary.indexOf("&") + 1)
        ownerNamePrimary = ownerNamePrimary.slice(0, ownerNamePrimary.indexOf("&"));
    }
    ownerNamePrimary = $.trim(ownerNamePrimary);
    ownerNameSecondary = $.trim(ownerNameSecondary);

    if (ownerNamePrimary.toLowerCase() === ionReportData.Ownername.toLowerCase()) {
        $('#ion-report-status').val(false); // button proceed
        $('#ion-fraud-status').val(false);
    } else {
        $('#ion-fraud-status').val(true); // ION Fraud found
        $('.ion-search-propery').text(address);
        $('#searchFraudResultModal').modal('show');
        // $(response).find('Locations').children('Location').each(function (i) {

        $('.ion-result table > tbody').append('<tr><td><span class="black-primary-owner"></span></td><td><span class="ion-primary-owner"></span></td></tr>');

        $('.ion-result table > tbody').find('tr').find('.black-primary-owner').text(ownerNamePrimary);
        $('.ion-result table > tbody').find('tr').find('.ion-primary-owner').text(ionReportData.Ownername);

        $('.ion-result table > tbody').append('<tr><td><span class="black-secondary-owner"></span></td><td><span class="ion-secondary-owner"></span></td></tr>');

        $('.ion-result table > tbody').find('tr').find('.black-secondary-owner').text(ownerNameSecondary);
        $('.ion-result table > tbody').find('tr').find('.ion-secondary-owner').text(ionReportData.Ownername2);
        // $('.ion-result table > tbody').find('tr').eq(i).find('.result-address').text(address + ', ' + city);
    }
}

function multipleResults(response) {
    $('#searchResultModal').modal('show');
    $('.search-result table > tbody').html('');
    $(response).find('Locations').children('Location').each(function (i) {

        var address = $(this).find('Address').text();
        apn = $(this).find('APN').text();
        apnInfo[apn] = {}
        var city = $(this).find('City').text();

        apnInfo[apn]['fips'] = $(this).find('FIPS').text();
        var unit_number = $(this).find('UnitNumber').text();

        $('.search-result table > tbody').append('<tr><td><span class="result-apn"></span></td><td><span class="result-address"></span></td><td><span class="result-unit-number"></span></td><td><button type="button" data-btntext-sending="Sending..." class="btn btn-success btn-icon-split btn-sm" onclick="apnData(this)"><span class="icon text-white-50"><i class="fas fa-check"></i></span><span class="text">Choose</span></button></td></tr>');



        $('.search-result table > tbody').find('tr').eq(i).find('.result-apn').text(apn);
        $('.search-result table > tbody').find('tr').eq(i).find('.result-address').text(address + ', ' + city);
        // $('.search-result table > tbody').find('tr').eq(i).find('.result-city').text(city);
        $('.search-result table > tbody').find('tr').eq(i).find('.result-unit-number').text(unit_number);
    });
}

function toTitleCase(str) {
    return str.replace(/\w\S*/g, function (txt) { return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase(); });
}

// display error returned in API query
function displayError(responseStatus) {
    // determine and display specific error
    var errorDisplay = "";
    switch (responseStatus) {
        case 'NM':
            error = 'No exact match';
            break;
        case 'NC':
            error = 'Out of coverage area';
            break;
        case 'IP':
            error = 'Invalid IP';
            break;
        case 'IK':
            error = 'Invalid key';
            break;
        case 'IR':
            error = 'Invalid report type';
            break;
        case 'IN':
            error = 'Invalid property address. Please try once with Zip Code instead of City.';
            break;
        case 'CR':
            error = 'No credits';
            break;
        case 'NH':
            error = 'Valid address, but no hit';
            notifyAdmin('No Hit on property search');
            break;
        default:
            error = "Error"
    }
    $('.pma-error').text(error);
    $('.pma-error').show();
}

function apnData(e) {

    $("#searchResultModal").find(".apn-search-loader").removeClass("hidden");

    isNewSearch = false;

    var apn = $(e).closest('tr').find('.result-apn').text();
    var fips = apnInfo[apn]['fips'];
    // var apn = "2350-013-020";
    // var fips = "06037";
    if ($('#property-fips').length) {
        $('#property-fips').val(fips);
    }

    dataObj = {};
    dataObj.apn = apn;
    dataObj.FIPS = fips;
    dataObj.ClientReference = '<CustCompFilter><SQFT>0.20</SQFT><Radius>0.75</Radius></CustCompFilter>';
    dataObj.random_number = $('#random_number').val();
    compileAPNRequest(dataObj);
}


// complie URL for APN search
function compileAPNRequest(dataobj) {
    request = 'http://api.sitexdata.com/sitexapi/sitexapi.asmx/ApnSearch?';
    request += $.param(dataObj);
    fetchReports('187', request, dataObj);
}

function notifyAdminPlat(subject) {
    var customer_id = $("#CustomerId").val();

    if (customer_id) {
        $.ajax({
            url: base_url + 'notifyAdmin',
            type: "POST",//type of posting the data
            data: {
                customer_id: customer_id,
                property: $('#property-full-address').val(),
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

function switchAPN() {
    $('.pma-error').html('');
    $('.pma-error').hide();
    $('#apn_num').parent().removeClass('state-error');
    $('#apn_county').parent().removeClass('state-error');
    $('#address_container').hide();
    $('#apn_container').show();
}

function switchProperty() {
    $('.pma-error').html('');
    $('.pma-error').hide();
    $('#property-search').parent().removeClass('state-error');
    $('#address_container').show();
    $('#apn_container').hide();
}

function getAPN() {
    isNewSearch = true;
    event.preventDefault ? event.preventDefault() : event.returnValue = false;
    var apn = $.trim($('#apn_num').val());
    var county = $.trim($('#apn_county').val());
    if (apn == '') {
        $('.pma-error').html('Please enter APN.');
        $('.pma-error').show();
        $('#apn_num').parent().addClass('state-error');
        return;
    }
    if (county == '') {
        $('.pma-error').html('Please enter County Name.');
        $('.pma-error').show();
        $('#apn_county').parent().addClass('state-error');
        return;
    }
    county = county.toUpperCase();
    county = county.replace('COUNTY', '');
    county = county.trim();
    county = county.replace(/\w\S*/g, function (txt) {
        return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
    });
    fips = counties[county];
    if (fips) {
        dataObj = {};
        dataObj.apn = apn;
        dataObj.FIPS = fips;
        dataObj.ClientReference = '<CustCompFilter><CompNum>8</CompNum><MonthsBack>12</MonthsBack></CustCompFilter>';
        request = 'http://api.sitexdata.com/sitexapi/sitexapi.asmx/ApnSearch?';
        request += $.param(dataObj)
        //  fetchReports('187');
        fetchReports('187', request, dataObj, '', true);
    } else {
        $('.pma-error').html('Invalid County Name.');
        $('.pma-error').show();
        $('#apn_county').parent().addClass('state-error');
    }
}

/*function getProductTypes()
{
    $.ajax({
       url: base_url+'get-product-types',
       type: "POST",//type of posting the data
       data: {
            county: county,
            state: state,
       },
       success: function (data) {
            var res = jQuery.parseJSON(data);
            
            if(res)
            {
                var output = [];
                output.push('<option value="">Select Product</option>')
                $.each(res, function(key, value) {
                    output.push('<option value="'+ value.product_type_id +'">'+ value.product_type +'</option>');
                });
                $('#ProductTypeID').html(output.join(''));
            }
       },
       error: function(xhr, ajaxOptions, thrownError){
          
       },
  });
}*/