$(document).ready(function () {

    reportData = {};
    apnInfo = {};

    var isNewSearch = false;
    var request = '';

    autoComplete();
    $(document).on('click', '.search-property', getAddress);


    //customer no
    $('#getCustomerInfo').click(function (e) {
        var customer_no = $('#CustomerNumber').val();

        if (!customer_no) {
            $('#CustomerNumber-error').html('Enter your customer number');
            $('#CustomerNumber-error').show();
            $("#CustomerNumber").parent().addClass('state-error');
        }
        else {
            $.ajax({
                url: "php/search.php",
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

                        if (res.is_escrow == 1) {
                            $('#add-lender-section').show();
                            $('#add-escrow-section').hide();
                            $('#escrow-details-fields').hide();
                            $('#add-lender-details').trigger('change');
                        }
                        else {
                            $('#add-lender-section').hide();
                            $('#add-escrow-section').show();
                            $('#lender-details-fields').hide();
                            $('#add-escrow-details').trigger('change');
                        }
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
                    url: 'php/checkemail.php',
                    type: "get",
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
            $(form).ajaxSubmit({
                /*target:'#showCustomerNumber',*/
                error: function () {
                    // $('.form-footer').removeClass('progress');
                },
                success: function (data) {
                    var res = jQuery.parseJSON(data);

                    $('#findCustomerModal').modal('hide');
                    if (res.customer_number) {
                        var content = "<h3>Your Customer Number is:" + res.customer_number + "</h3>";

                    }
                    else {
                        var content = '<h3> No data found</h3>';
                    }
                    $("#showCustomerNumber").html(content);
                    $("#showCustomernumberModal").modal('show');
                }
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
            $("#BuyerAgentEmailAddress").val('').removeAttr('readonly').parent().removeClass('state-success');
            $("#BuyerAgentTelephone").val('').removeAttr('readonly').parent().removeClass('state-success');
            $("#BuyerAgentCompany").val('').removeAttr('readonly').parent().removeClass('state-success');
            $("#BuyerAgentId").val('');
            $("#ListingAgentName").val('').parent().removeClass('state-success');
            $("#ListingAgentEmailAddress").val('').removeAttr('readonly').parent().removeClass('state-success');
            $("#ListingAgentTelephone").val('').removeAttr('readonly').parent().removeClass('state-success');
            $("#ListingAgentCompany").val('').removeAttr('readonly').parent().removeClass('state-success');
            $("#ListingAgentId").val('');
            $('#agent-details-fields').hide();
        }
    });

    $('#add-lender-details').change(function () {
        if (this.checked) {
            $('#lender-details-fields').show();
        }
        else {
            $("#LenderName").val('').parent().removeClass('state-success');
            $("#LenderEmailAddress").val('').removeAttr('readonly').parent().removeClass('state-success');
            $("#LenderTelephone").val('').removeAttr('readonly').parent().removeClass('state-success');
            $("#LenderCompany").val('').removeAttr('readonly').parent().removeClass('state-success');
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
            $("#EscrowEmailAddress").val('').removeAttr('readonly').parent().removeClass('state-success');
            $("#EscrowTelephone").val('').removeAttr('readonly').parent().removeClass('state-success');
            $("#EscrowCompany").val('').removeAttr('readonly').parent().removeClass('state-success');
            $("#EscrowId").val('');
            $('#escrow-details-fields').hide();
        }
    });

    $("#BuyerAgentName").autocomplete({
        source: "php/agentsearch.php",
        select: function (event, ui) {
            event.preventDefault();
            $("#BuyerAgentName").val(ui.item.name);
            /*$("#AgentFirstName").val(ui.item.first_name);
            $("#AgentLastName").val(ui.item.last_name).attr('readonly','readonly').parent().addClass('state-success');*/
            $("#BuyerAgentEmailAddress").val(ui.item.email_address).attr('readonly', 'readonly').parent().addClass('state-success');
            $("#BuyerAgentTelephone").val(ui.item.telephone_no).attr('readonly', 'readonly').parent().addClass('state-success');
            $("#BuyerAgentCompany").val(ui.item.company).attr('readonly', 'readonly').parent().addClass('state-success');
            $("#BuyerAgentId").val(ui.item.id);
        },
        change: function (event, ui) {
            if (ui.item == null) {
                // $("#AgentLastName").val('').removeAttr('readonly').parent().removeClass('state-success').addClass('state-error');
                $("#BuyerAgentEmailAddress").val('').removeAttr('readonly').parent().removeClass('state-success').addClass('state-error');
                $("#BuyerAgentTelephone").val('').removeAttr('readonly').parent().removeClass('state-success').addClass('state-error');
                $("#BuyerAgentCompany").val('').removeAttr('readonly').parent().removeClass('state-success').addClass('state-error');
                $("#BuyerAgentId").val('');
            }
        }
    });

    /* Listing Agent autocomplete */
    $("#ListingAgentName").autocomplete({
        source: "php/agentsearch.php",
        select: function (event, ui) {
            event.preventDefault();
            $("#ListingAgentName").val(ui.item.name);
            /*$("#AgentFirstName").val(ui.item.first_name);
            $("#AgentLastName").val(ui.item.last_name).attr('readonly','readonly').parent().addClass('state-success');*/
            $("#ListingAgentEmailAddress").val(ui.item.email_address).attr('readonly', 'readonly').parent().addClass('state-success');
            $("#ListingAgentTelephone").val(ui.item.telephone_no).attr('readonly', 'readonly').parent().addClass('state-success');
            $("#ListingAgentCompany").val(ui.item.company).attr('readonly', 'readonly').parent().addClass('state-success');
            $("#ListingAgentId").val(ui.item.id);
        },
        change: function (event, ui) {
            if (ui.item == null) {
                // $("#AgentLastName").val('').removeAttr('readonly').parent().removeClass('state-success').addClass('state-error');
                $("#ListingAgentEmailAddress").val('').removeAttr('readonly').parent().removeClass('state-success').addClass('state-error');
                $("#ListingAgentTelephone").val('').removeAttr('readonly').parent().removeClass('state-success').addClass('state-error');
                $("#ListingAgentCompany").val('').removeAttr('readonly').parent().removeClass('state-success').addClass('state-error');
                $("#ListingAgentId").val('');
            }
        }
    });
    /* Listing Agent autocomplete */

    /* Lender autocomplete */
    $("#LenderName").autocomplete({
        source: "php/usersearch.php",
        select: function (event, ui) {
            event.preventDefault();
            $("#LenderName").val(ui.item.name);
            $("#LenderEmailAddress").val(ui.item.email_address).attr('readonly', 'readonly').parent().addClass('state-success');
            $("#LenderTelephone").val(ui.item.telephone_no).attr('readonly', 'readonly').parent().addClass('state-success');
            $("#LenderCompany").val(ui.item.company).attr('readonly', 'readonly').parent().addClass('state-success');
            $("#LenderId").val(ui.item.id);
        },
        change: function (event, ui) {
            if (ui.item == null) {
                $("#LenderEmailAddress").val('').removeAttr('readonly').parent().removeClass('state-success').addClass('state-error');
                $("#LenderTelephone").val('').removeAttr('readonly').parent().removeClass('state-success').addClass('state-error');
                $("#LenderCompany").val('').removeAttr('readonly').parent().removeClass('state-success').addClass('state-error');
                $("#LenderId").val('');
            }
        }
    });
    /* Lender autocomplete */

    /* Escrow autocomplete */
    $("#EscrowName").autocomplete({
        source: "php/escrowusersearch.php",
        select: function (event, ui) {
            event.preventDefault();
            $("#EscrowName").val(ui.item.name);
            $("#EscrowEmailAddress").val(ui.item.email_address).attr('readonly', 'readonly').parent().addClass('state-success');
            $("#EscrowTelephone").val(ui.item.telephone_no).attr('readonly', 'readonly').parent().addClass('state-success');
            $("#EscrowCompany").val(ui.item.company).attr('readonly', 'readonly').parent().addClass('state-success');
            $("#EscrowId").val(ui.item.id);
        },
        change: function (event, ui) {
            if (ui.item == null) {
                $("#EscrowEmailAddress").val('').removeAttr('readonly').parent().removeClass('state-success').addClass('state-error');
                $("#EscrowTelephone").val('').removeAttr('readonly').parent().removeClass('state-success').addClass('state-error');
                $("#EscrowCompany").val('').removeAttr('readonly').parent().removeClass('state-success').addClass('state-error');
                $("#EscrowId").val('');
            }
        }
    });
    /* Escrow autocomplete */

    $('#ProductTypeID').change(function () {
        $('#sales-loan-amount-fields').show();
        if ($(this).val() == 19 || $(this).val() == 33) {
            $('#sales-loan-amount-fields #salesAmount').hide();
        }
        else if ($(this).val() == 20 || $(this).val() == 32) {
            $('#sales-loan-amount-fields #salesAmount').show();
        }
        else {
            $('#sales-loan-amount-fields').hide();
        }
    });
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
    data(address, locale);
}

// creates data object for AJAX call to API
function data(address, locale) {
    dataObj = {};
    dataObj.Address = address;
    dataObj.LastLine = locale.toString();
    dataObj.ClientReference = '<CustCompFilter><CompNum>8</CompNum><MonthsBack>12</MonthsBack></CustCompFilter>';
    dataObj.OwnerName = '';
    request = 'http://api.sitexdata.com/sitexapi/sitexapi.asmx/AddressSearch?';
    request += $.param(dataObj);
    fetchReports('187');
}


function fetchReports(repNum) {
    reportNum = repNum;
    $.ajax({
        url: 'php/getsearchresults.php',
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
                console.log(response);
                displayError(responseStatus);
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
}


function get187() {
    $.ajax({
        url: 'php/getsearchresults.php',
        data: {
            requrl: reportData.report187,
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


function parse187() {
    var ownerNamePrimary = $(reportXML).find("PropertyProfile").find("PrimaryOwnerName").text();
    var ownerNameSecondary = $(reportXML).find("PropertyProfile").find("SecondaryOwnerName").text();

    if (ownerNamePrimary.indexOf(';') !== -1) {
        ownerNameSecondary = ownerNamePrimary.substr(ownerNamePrimary.indexOf(";") + 1)
        ownerNamePrimary = ownerNamePrimary.slice(0, ownerNamePrimary.indexOf(";"));
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

    var address = $(reportXML).find("PropertyProfile").find("SiteAddress").text();
    if (address) {
        full_address.push(address);
    }

    var city = $(reportXML).find("PropertyProfile").find("SiteCity").text();

    if (city) {
        full_address.push(city);
    }

    var state = $(reportXML).find("PropertyProfile").find("SiteState").text();

    if (state) {
        full_address.push(state);
    }

    var zip = $(reportXML).find("PropertyProfile").find("SiteZip").text();

    if (zip) {
        full_address.push(zip);
    }

    var apn = $(reportXML).find("PropertyProfile").find("APN").text();
    var county = $(reportXML).find("SubjectValueInfo").find("CountyName").text();
    var legalDescription = $(reportXML).find("PropertyProfile").find("LegalBriefDescription").text();
    legalDescription = legalDescription.replace(/\s\s+/g, ' ');

    $('#FullProperty').val(full_address.join(', ')).prop('readonly', true);
    $('#apn').val(apn).prop('readonly', true);
    $('#County').val(county).prop('readonly', true);
    $('#LegalDescription').val(legalDescription).prop('readonly', true);
    $('#PrimaryOwner').val(ownerNamePrimary).prop('readonly', true);
    $('#SecondaryOwner').val(ownerNameSecondary).prop('readonly', true);
    $("#searchResultModal").find(".apn-search-loader").addClass("hidden");
    $('#searchResultModal').modal('hide');

    /*if (localStorage) 
    {
        localStorage.setItem('address',$('#property-search').val());
        localStorage.setItem('city',$('#property-city').val());
        localStorage.setItem('apn',apn);
        localStorage.setItem('state',$('#property-state').val());
        localStorage.setItem('county',county);
    }*/
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

        $('.search-result table > tbody').append('<tr><td><span class="result-apn"></span></td><td><span class="result-address"></span></td><td><span class="result-city"></span></td><td><a href="javascript:void(0);" class="btn btn-sm btn-default" onclick="apnData(this)">Choose</a></td></tr>');

        $('.search-result table > tbody').find('tr').eq(i).find('.result-apn').text(apn);
        $('.search-result table > tbody').find('tr').eq(i).find('.result-address').text(address);
        $('.search-result table > tbody').find('tr').eq(i).find('.result-city').text(city);
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
            notifyAdmin();
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
    // console.log('fips2= ' + fips);
    if ($('#property-fips').length) {
        $('#property-fips').val(fips);
    }

    dataObj = {};
    dataObj.apn = apn;
    dataObj.FIPS = fips;
    dataObj.ClientReference = '<CustCompFilter><SQFT>0.20</SQFT><Radius>0.75</Radius></CustCompFilter>';

    compileAPNRequest(dataObj);
}


// complie URL for APN search
function compileAPNRequest(dataobj) {
    request = 'http://api.sitexdata.com/sitexapi/sitexapi.asmx/ApnSearch?';
    request += $.param(dataObj);
    fetchReports('187');
}

function notifyAdmin() {
    var customer_no = $("#CustomerNumber").val();
    var first_name = $("#OpenName").val();

    if (customer_no || first_name) {
        $.ajax({
            url: "php/notifyadmin.php",
            type: "POST",//type of posting the data
            data: {
                customer_no: customer_no,
                first_name: first_name,
                last_name: $("#OpenLastName").val(),
                telephone_no: $("#Opentelephone").val(),
                email_address: $("#OpenEmail").val(),
                company_name: $("#CompanyName").val(),
                street_address: $("#StreetAddress").val(),
                city: $("#City").val(),
                zipcode: $("#Zipcode").val(),
                property: $('#property-full-address').val()
            },
            success: function (data) {
                console.log(data);
            },
            error: function (xhr, ajaxOptions, thrownError) {

            },
        });
    }
}