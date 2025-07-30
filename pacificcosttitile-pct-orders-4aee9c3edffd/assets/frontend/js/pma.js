$(document).ready(function () {
    firstModal = true;
    // create dialog for manual comps selection
    // compsDialog = $('#comps-dialog').dialog({
    //     autoOpen: false,
    //     height: 350,
    //     width: 850,
    //     modal: true
    // });

    compsDialog = $('#comps-dialog');

    // IE compatability
    if (!window.console) {
        console = {
            log: function () { }
        };
    }
    $.ajaxSetup({
        cache: false
    })

    // set default values 
    request = '';
    reportNum = '';
    docsSkip = false;
    compsArray = [];
    compsXML = '';
    compsSkip = false;
    reportData = {};
    apnInfo = {};
    $('.js-pma-apn').hide();
    $('#comps-form').hide();
    $('.comps-submit').on('click', validateComps);
    $('#comps-table').hide();
    $('.comps-submit').hide();
    $('.pma-table').hide();
    $('.js-switch-search').on('click', switchSearch);
    $('.js-run-pma-button').hide();
    autoComplete();
    listReps();
    $('.progress-bar').progressbar({
        value: false
    });
    $('.progress-bar').hide();
    $('.recent-reports tbody').hide();

    // create dialog for custom info selection
    // runPMADialog = $('#run-pma-dialog').dialog({
    //     autoOpen: false,
    //     height: 370,
    //     width: 500,
    //     modal: true
    // });

    // when user runs PMA, open custom info dialog and create unique ID for the CPP that will be generated
    $(document).on('click', '.js-run-pma-button', function () {
        $('#run-pma-dialog').modal('show');
    });

    // when user selects "Submit" on custom info dialog, close dialog, show progressbar, and extract info from form 
    $('.pma-modal-submit').on('click', function () {
        $(".pma-alert").hide();
        if ($("#rep-name").val() == '') {
            $(".pma-alert .error_msg").html("Please select Rep");
            $(".pma-alert").show();
            return false;
        }
        else {
            $('#run-pma-dialog').modal('hide');
            $('.progress-bar').show();
            getCustomInfo();
        }
    });
    dataTransfer(''); // fetch recently ran CPPs and tallies for number of reports run and associated costs
    $(document).on('click', '.js-run-apn-button', apnMultiple);
    $(document).on('click', '.js-search-apn', getAPN);
    $(document).on('click', '.js-find-property', getAddress);

    report_table = $('#cpl_listing').DataTable({
        "aaSorting": [],
        "language": {
            // searchPlaceholder: "Search File# or Address",
            paginate: {
                next: '<span class="fa fa-angle-right"></span>',
                previous: '<span class="fa fa-angle-left"></span>',
            },
            "emptyTable": "Record(s) not found.",
            // "search": "",
        },
    });
    autoComplete();
});

function setDefaultValues() {


    // set default values 
    request = '';
    reportNum = '';
    docsSkip = false;
    compsArray = [];
    compsXML = '';
    compsSkip = false;
    reportData = {};
    apnInfo = {};
    $('.js-pma-apn').hide();
    $('#comps-form').hide();

}


// Get archived company and agent dropdown info for customized info form
function textDropdown(dropArray, id) {
    //console.log(dropArray);
    var $input = $(id).autocomplete({
        source: dropArray,
        minLength: 0,
        select: function (e, ui) {
            retrieveFormData(id, e, ui);
        }
    }).addClass("");

    if (firstModal) {
        if ($input.autocomplete("widget").is(":visible")) {
            $input.autocomplete("close");
            return;
        }
    }
}


// retrieve form data to populate customized info form
function retrieveFormData(id, e, ui) {
    var query = 'task=populate';
    var input = ui.item.value
    if (id == '#realtor-name') {
        data = {
            // 'task': 'populate',
            'type': 'agent',
            'agent': input
        }
    } else {
        data = {
            // 'task': 'populate',
            'type': 'company',
            'company': input
        }
    }
    $.ajax({
        url: base_url + 'pmas/task/populate',
        type: 'GET',
        data: data
    })
        .done(function (response) {
            //console.log(response)
            populateData(response);

        })
        .fail(function () { })
}


// populate customized info form based on previously used agent or company
function populateData(response) {
    var formItems = $.parseJSON(response);
    //console.log(formItems);
    var company = formItems.company;
    var address = formItems.address;
    $('#realtor-company').val(company);
    $('#realtor-address').val(address);
}


// get list of all previous agents and companies for modal dropdown
function getDropItems() {
    $.ajax({
        url: base_url + 'pmas/task/fetchItems',
        type: 'GET',
        // data: {
        //     task: 'fetchItems'
        // }
    })
        .done(function (response) {
            var dropData = $.parseJSON(response);
            // console.log(dropData[0]);
            // console.log(dropData[1]);
            var realtors = dropData['realtor_name'];
            var companies = dropData['realtor_company'];
            realtors = realtors.sort();
            companies = companies.sort();
            textDropdown(realtors, '#realtor-name');
            textDropdown(companies, '#realtor-company');
        })
        .fail(function () { })
}

// compiles data from customized info form to send to database for future reports that use same company or agent 
function recordFormData(query) {
    //console.log(query);
    $.ajax({
        url: 'pma/store-form.php',
        type: 'POST',
        data: query
    })
        .done(function (response) {
            //console.log(response);
        })
        .fail(function () { })
}


// controls the switching between address and APN search
function switchSearch() {
    event.preventDefault ? event.preventDefault() : event.returnValue = false;
    var oldClass = $('.js-search-button').hasClass('js-find-property') ? 'js-find-property' : 'js-search-apn';
    var newClass = $('.js-search-button').hasClass('js-find-property') ? 'js-search-apn' : 'js-find-property';
    //console.log(oldClass);
    //console.log(newClass);
    $('.js-search-button').removeClass(oldClass);
    $('.js-search-button').addClass(newClass);
    if (newClass === 'js-find-property') {
        $('.js-pma-apn').hide();
        $('.js-pma-address').show();
        $('.js-switch-search').text('Switch to APN Search');
        $('.js-search-label1').text('Property Address');
        $('.js-search-label2').text('City');
        $('.js-pma-city').attr("placeholder", "City");
        $('.js-search-button').text('Find Property');
    } else {
        $('.js-pma-address').hide();
        $('.js-pma-apn').show();
        $('.js-switch-search').text('Switch to Address Search');
        $('.js-search-label1').text('APN');
        $('.js-search-label2').text('County');
        $('.js-pma-city').attr("placeholder", "County");

        $('.js-search-button').text('Search APN');
    }
}

// pulls names of all PCT reps for 1) rep dropdown list on custom info dialog and 2) table that tallies # of reports run for each rep
function listReps() {
    $.ajax({
        url: base_url + 'pmas/rep_list',
        type: 'GET'
    })
        .done(function (response) {
            // var repsResponse = $.parseJSON(response);
            // var repsDropdown = repsResponse[0];
            // var repsTable = repsResponse[1];
            $('#rep-name').html(response);
            // $('.rep-table > tbody').html(repsTable);
        });
}

// extracts data from custom info form. 
function getCustomInfo() {
    event.preventDefault ? event.preventDefault() : event.returnValue = false;

    reportData.rep = $('#rep-name option:selected').text();
    reportData.repId = $('#rep-name option:selected').val();
    var formData = $('#run-pma-form').serialize();
    firstModal = false;
    // recordFormData(formData);
    query = formData;
    var includeComps = $('#include-comps').val();
    var includeDocs = $('#include-docs').val();
    if (includeComps === 'No') {
        compsSkip = true;
    }
    if (includeDocs === 'No' || reportData.report110) {
        docsSkip = true;
    }
    //console.log('includeComps=' + includeComps);
    //console.log('includeDocs=' + includeDocs);
    checkXML();
}


function checkXML() {
    // if user doesn't want to select comps (or 187 has been parsed and there are <6 comps available) and doesn't want docs, then generate reporte
    if (compsSkip && docsSkip) {
        runPMA()
    }
    // otherwise request 187 to parse
    else {
        get187()
    }
}


// gets 187 for client-side parsing
function get187() {
    $('body').addClass('loading-screen');
    $.ajax({
        type: "GET",
        url: base_url + "pmas/proxy",
        data: {
            requrl: reportData.report187
        },
        dataType: "xml",
        success: function (xml) {
            compsXML = xml;
            $('body').removeClass('loading-screen');
            parse187(xml)
        },
        error: function () {
            alert("An error occurred while processing XML file.");
            $('body').removeClass('loading-screen');
        }
    });
}

// extracts comparable section from 187 and determines whether docs report need to be retrieved
function parse187() {
    var comps = $(compsXML).find("ComparableSalesReport").find("ComparableSales");
    compsArray = comps.find("ComparableSale");
    //console.log(compsArray);
    if (!docsSkip) {
        parseDocs();
        docsData();
    } else {
        compsRoute();
    }
}


// walks through all prior transfers; if one of designated doc types is found, it calls parseDocsVals
function parseDocs() {
    var docTypes = ['Grant Deed', 'Open Deed', 'Notice of Sale', 'Notice of Default', 'Lien', 'Intrafamily Transfer or Dissolution']
    $(compsXML).find("TransferHistory").find("TransferWithDefault").each(function (i, element) {
        var docType = $(this).find('DocumentType').text();
        docType = docType.toString();
        // console.log('docType=' + docType);
        if (docTypes.indexOf(docType) > -1) {
            parseDocsVals(element);
            return false;
        }
    })
}


// extracts info from prior transfer needed to request 110 report
function parseDocsVals(element) {
    recordingDate = $(element).find('RecordingDate').text();
    recordingDate = recordingDate.toString();
    //console.log('rec=' + recordingDate);
    if (recordingDate !== '') {
        recordingDate = recordingDate.slice(6, 8) + '/' + recordingDate.slice(4, 6) + '/' + recordingDate.slice(0, 4);
    }
    reportData.recordingDate = recordingDate ? recordingDate : '';
    var docNum = $(element).find("DocumentNumber").text();
    docNum = docNum.toString();
    reportData.docNum = docNum ? docNum : '';
}

// if there are more than 6 comps available in the 187 and user hasn't selected to skip manual selection, parse comps. Otherwise, directly generate CPP.
function compsRoute() {
    //console.log(compsSkip)
    //console.log(compsArray)
    if (!compsSkip && compsArray.length > 6) {
        parseComps();
    } else {
        compsSkip = true;
        runPMA();
    }
}


// Pull info for each comparable sale in 187
function parseComps() {
    var currentDate = new Date().toISOString().slice(0, 10);
    currentDate = currentDate.replace(/-/g, '');
    compsSoldWithinYear = 0;
    compsDataRaw = [];
    compsArray.each(function () {
        var compsAPN = $(this).find("APN").text();
        var compsAddress = $(this).find("SiteAddress").text();
        var compsBuildingArea = $(this).find("BuildingArea").text();
        compsBuildingArea = commasToNumber(compsBuildingArea);
        var compsLotSize = $(this).find("LotSize").text();
        compsLotSize = commasToNumber(compsLotSize);
        var compsProximity = $(this).find("Proximity").text();
        var compsSalesDate = $(this).find("RecordingDate").text();
        if ((currentDate - compsSalesDate) < 10000) {
            compsSoldWithinYear++;
            withinLastYear = true;
        }
        else {
            withinLastYear = false;
        }
        compsSalesDate = compsSalesDate.slice(4, 6) + '/' + compsSalesDate.slice(6, 8) + '/' + compsSalesDate.slice(0, 4);
        if (compsSalesDate.slice(0, 1) === '0') {
            compsSalesDate = compsSalesDate.slice(1);
        }
        if (compsSalesDate.slice(3, 4) == '0') {
            compsSalesDate = compsSalesDate.slice(0, 3) + compsSalesDate.slice(4);
        }
        var compsSalePrice = $(this).find("SalePrice").text();
        compsSalePrice = '$' + commasToNumber(compsSalePrice);
        var compsBeds = $(this).find("Bedrooms").text();
        var compsBaths = $(this).find("Baths").text();
        var compsBedsBaths = compsBeds + '/' + compsBaths;
        compsDataRaw.push([withinLastYear, compsAddress, compsBuildingArea, compsLotSize, compsBedsBaths, compsSalesDate, compsProximity, compsSalePrice, compsAPN]);
    });
    if (compsSoldWithinYear > 8) {
        compsData = [];
        for (var i = 0; i < compsDataRaw.length; i++) {
            if (compsDataRaw[i][0]) {
                compsData.push(compsDataRaw[i]);
            }
        }
    }
    else {
        compsData = compsDataRaw;
    }
    for (var i = 0; i < compsData.length; i++) {
        compsData[i].shift();
    }
    appendComps(compsData);
}

// create sortable table with info for all comps
function appendComps(compsData) {
    $('#comps-table').find("tbody").html('');
    for (i = 0; i < compsData.length; i++) {
        $('#comps-table').find("tbody").append('<tr><td class="comp-address"></td><td></td><td></td><td></td><td></td><td></td><td></td><td><input type="checkbox" class="apn-checkbox" name="apn" value="' + compsData[i][7] + '"/><span class="x-checkbox" unchecked></span><input type="checkbox" class="invisible-checkbox hide" name="apndelete" value="' + compsData[i][7] + '"/></td></tr>');
        for (var j = 0; j < 7; j++) {
            $('#comps-table').find("tbody").find('tr').eq(i).find('td').eq(j).text(compsData[i][j]);
        }
    }
    $("#comps-table tbody tr").slice(compsData.length).remove();
    $('#comps-table').addClass('tablesorter');
    jQuery.tablesorter.addParser({
        id: 'thousands',
        is: function (s) {
            // return false so this parser is not auto detected 
            return false;
        },
        format: function (s) {
            // format your data for normalization 
            return s.replace('$', '').replace(/,/g, '');
        },
        // set type, either numeric or text 
        type: 'numeric'
    });
    $('#comps-table').tablesorter({
        sortList: [
            [1, 0]
        ],
        headers: {
            1: { //zero-based column index
                sorter: 'thousands'
            },
            2: { //zero-based column index
                sorter: 'thousands'
            },
            6: { //zero-based column index
                sorter: 'thousands'
            }
        }
    });
    displayComps();
}


// display comps selection dialog
function displayComps() {
    //$("#comps-table").find("tr:odd").addClass("table-odd");
    $('.progress-bar').hide();
    $('.x-checkbox').hide();
    compsDialog.modal('show');
    $('#comps-form').show();
    $('#comps-table').show();
    $('.comps-submit').show();
    $('#comps-table tbody tr').hover(function () {
        $(this).find($('.x-checkbox')).show();
    }, function () {
        $(this).find($('.x-checkbox')).hide();
    }),
        $('.x-checkbox').on('click', deleteBox)

}

// makes sure user did not select or remove to many comps
function validateComps() {
    event.preventDefault ? event.preventDefault() : event.returnValue = false;
    //console.log('validate');
    var fields = $('input[name="apn"]').serializeArray();
    var fieldsDeleted = $('input[name="apndelete').serializeArray();
    if (fields.length > 8) {
        $('.comps-error').text('Please select no more than 8 comps');
        //console.log('>8');

    } else if (fieldsDeleted.length > 12) {
        //console.log(fieldsDeleted)
        $('.comps-error').text('You can only select 12 comps to not include');
        //console.log('>12');
    } else {
        $('#comps-form').hide();
        $('.comps-submit').hide();
        $('.comps-error').text('');
        compsDialog.modal('hide');
        var j = 1;
        $('input[name="apn"]').each(function (i, el) {
            if ($(el).is(':checked')) {
                query += '&apn' + j + '=' + el.value;
                j++;
            }
        });
        var j = 1;
        $('input[name="apndelete"]').each(function (i, el) {
            if ($(el).is(':checked')) {
                query += '&apndelete' + j + '=' + el.value;
                j++;
            }
        });
        runPMA();
    }
}

// handles click event on removal of comp
function deleteBox() {
    if ($(this).attr('checked') == undefined) {
        $(this).attr('checked', "");
        $(this).removeAttr('unchecked');
        $(this).closest('tr').find('input[name="apndelete"]').prop('checked', true);
        $(this).closest('tr').find('input[name="apn"]').prop('checked', false);
        $(this).closest('tr').find('.apn-checkbox').prop('checked', false)
        $(this).prop('checked', true);
        $(this).closest('tr').addClass('removed-comp');
        $(this).closest('tr').find('.apn-checkbox').hide();
    } else {
        $(this).removeAttr('checked');
        $(this).attr('unchecked', "");
        $(this).prop('checked', false);
        $(this).closest('tr').find('input[name="apndelete"]').prop('checked', false);
        $(this).closest('tr').removeClass('removed-comp');
        $(this).closest('tr').find('.apn-checkbox').show();
    }
}

// Sends data to backend script that generates PDF 
function runPMA() {
    docsSkip = false;
    compsSkip = false;
    query += '&' + $.param(reportData);
    $('.progress-bar').show();
    $('body').addClass('loading-screen');
    $.ajax({
        crossDomain: true,
        url: base_url + 'pmas/pma',
        type: 'POST',
        data: query
    })
        .done(function (response) {
            //console.log('success');
            returnReport(response);
            $('body').removeClass('loading-screen');
        })
        .fail(function (response) {
            $('.pma-error').text('Unsuccessful PDF Generation');
            $('body').removeClass('loading-screen');
        })
}


// handles response from script that generates PDF
function returnReport(response) {
    // var pdfID = parseID(response);
    // //console.log(pdfID);
    // event.preventDefault ? event.preventDefault() : event.returnValue = false;
    // $('.js-pma-address').val('');
    // $('.js-pma-city').val('');
    // var res = reportData.address;
    // res = res.replace(" ", "_")
    // var pdfLink = 'http://pct.com/pma/profiles/profileTemps/' + res + ' ' + pdfID + '.pdf';
    response = $.parseJSON(response);
    var pdfLink = response.pdfLink;
    reportData.link = pdfLink;
    $('.progress-bar').hide();
    dataTransfer('yes');
}

// finds ID for PDF in 
function parseID(response) {
    var jsonIndex = response.indexOf('{"id');
    var start = jsonIndex + 7;
    var pdfID = response.slice(start, start + 6);
    return pdfID;
}

// sends data to script that updates database
function dataTransfer(status) {
    var dataQuery = $.param(reportData);
    dataQuery += '&dataUpdate=' + status;
    $('body').addClass('loading-screen');
    //console.log(dataQuery);
    $.ajax({
        url: base_url + 'pmas/pma_data',
        type: 'POST',
        data: dataQuery
    })
        .done(function (response) {
            //console.log(response)
            updateTally(response);
        })
        .fail(function () {
            $('.pma-error').text('Problem updating database');
        })
        .always(function () {
            if (status == 'yes') {
                // location.reload();
                $("#smart-form").trigger("reset");
                $("#run-pma-form").trigger("reset");
                $('.search-result-div').addClass('hide');
                setDefaultValues();

            }
            reportData.cost110 = 0;
            reportData.cost111 = 0;
            reportData.cost187 = 0;
            getDropItems();
            $('body').removeClass('loading-screen');
        })
}


// updates tallies of total reports run per rep (and aggregate) and corresponding price
function updateTally(tallies) {
    tallyData = $.parseJSON(tallies);
    //console.log(tallyData);
    $('.pma-total').text(tallyData.total);
    $('.accrued-cost').text(tallyData.cost);
    var rep_data = tallyData.sales_reps;
    list_emement = '';
    if ($('#rep-list-data li').length > 1) {
        $.each(rep_data, function (key, value) {
            var dynamic_li_class = '.rep_list_' + value.rep_id;
            $(dynamic_li_class + ' .report_total').html(value.report_total);
            $(dynamic_li_class + ' .report_cost').html('$' + value.report_cost);
        });
    }
    else {

        $.each(rep_data, function (key, value) {
            var img_div = '';
            if (value.image == '') {
                img_div = '<div class="no-report-image"><span>' + value.image_alt + '</span></div>';
            }
            else {
                img_div = '<img src="' + value.image + '" alt="' + value.image_alt + '" class="retina">';
            }

            list_emement += '<li class="rep_list_' + value.rep_id + '"><div class="u-pic">' + img_div + '</div>';
            list_emement += '<div class="u-info">';
            list_emement += '<div class="u-name">' + value.name + '</div>';
            list_emement += '<div>' + value.email + '</div>';
            list_emement += '<div>' + value.phone + '</div></div>';
            list_emement += '<div class="u-count">';
            list_emement += '<div class="report_total pma_val">' + value.report_total + '</div>';
            list_emement += '<div class="report_cost pma_val">$' + value.report_cost + '</div></div></li>';
        });
        $('#rep-list-data').html(list_emement);
        $("#show_all_rep").removeClass('hide');

    }

    //console.log(tallyData);


    $('.rep-table tr').each(function () {
        var pctRep = $(this).find('td:nth-child(1)').text();
        if (tallyData[pctRep]) {
            var repTotal = tallyData[pctRep][0];
            $(this).find('td:nth-child(2)').text(repTotal);
            var repCost = '$' + tallyData[pctRep][1];
            $(this).find('td:nth-child(3)').text(repCost);
        }
    });
    updateRecents(tallyData)
}


// updates list of recently run reports 
function updateRecents(tallyData) {
    $('.recent-reports tbody').html('');
    var recentReports = tallyData.reports;
    report_table.clear().draw();
    $.each(recentReports, function (key, value) {
        var address = value.address;
        var city = value.city;
        var rep = value.sales_rep;
        var link = value.link;
        var date = value.runDate;
        // $('.recent-reports tbody').append('<tr><td>' + date + '</td><td>' + rep + '</td><td></br>' + address + '<p>' + city + '</p></td><td><a class="button blueButton" href="' + link + '" target="_blank">Download</a></td></tr>')
        //         .show();
        report_table.row.add([
            date,
            rep,
            address,
            '<a href="' + link + '" class="btn btn-success btn-icon-split" target="_blank"><span class="icon text-white-50"><i class="fas fa-download"></i></span><span class="text">Download</span></a>'
        ]).draw(false);
    });
    // report_table.draw();
}


// processes and validates inputted apn and county 
function getAPN() {
    event.preventDefault ? event.preventDefault() : event.returnValue = false;
    searchReset()
    var apn = $('.js-pma-apn').val();
    apn = $.trim(apn);
    var county = $('.js-pma-fips').val();
    county = $.trim(county);
    county = county.toUpperCase();
    county = county.replace('COUNTY', '');
    county = county.trim();
    county = county.replace(/\w\S*/g, function (txt) {
        return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
    });
    //console.log(county)
    //console.log(county.length)
    fips = counties[county];
    if (fips) {
        apnInfo['fips'] = fips;
        apnInfo['apn'] = apn;
        apnData();
    } else {
        $('.progress-bar').hide();
        $('.pma-error').text('Invalid County Name')
        $('.pma-error').show();
    }
}


// processes inputted address
function getAddress() {
    event.preventDefault ? event.preventDefault() : event.returnValue = false;
    searchReset();
    address = $('.js-pma-address').val();
    address = $.trim(address);
    address = removeDiacritics(address);
    var locale = $('.js-pma-city').val();
    locale = $.trim(locale);
    locale = removeDiacritics(locale);
    if (isNaN(locale[0])) {
        locale += ', CA' // if locale is city rather than zip, add in state
    }
    addressData(address, locale);
}


// returns to inital search settings 
function searchReset() {
    $('.progress-bar').show();
    $('.result-apn').text('');
    $('.result-address').text('');
    $('.result-city').text('');
    $('.js-run-pma-button').hide();
    $('.pma-error').hide();
}


// creates data object for AJAX call to API
function addressData(address, locale) {
    dataObj = {};
    dataObj.Address = address;
    dataObj.LastLine = locale.toString();
    dataObj.ClientReference = '<CustCompFilter><CompNum>8</CompNum><MonthsBack>12</MonthsBack></CustCompFilter>';
    dataObj.OwnerName = '';
    request = 'http://api.sitexdata.com/sitexapi/sitexapi.asmx/AddressSearch?';
    request += $.param(dataObj)
    fetchReports('187');
}

// creates data object for AJAX call to API
function apnData() {
    var apn = apnInfo['apn'];
    var fips = apnInfo['fips'];
    dataObj = {};
    dataObj.apn = apn;
    dataObj.FIPS = fips;
    dataObj.ClientReference = '<CustCompFilter><CompNum>8</CompNum><MonthsBack>12</MonthsBack></CustCompFilter>';
    request = 'http://api.sitexdata.com/sitexapi/sitexapi.asmx/ApnSearch?';
    request += $.param(dataObj)
    fetchReports('187');
}

// creates data object for AJAX call to API
function docsData() {
    dataObj = {};
    dataObj.ClientReference = '';
    dataObj.APN = reportData.apn;
    dataObj.FIPS = reportData.fips;
    dataObj.recordingDate = reportData.recordingDate;
    dataObj.documentNumber = reportData.docNum;
    dataObj.BookNumber = '';
    dataObj.PageNumber = '';
    request = 'http://api.sitexdata.com/sitexapi/sitexapi.asmx/GetDocument?';
    request += $.param(dataObj);
    fetchReports('110');
}


function addReportCost(reportNum, amount, responseStatus) {
    if (responseStatus === 'OK') {
        var reportName = 'cost' + reportNum;
        reportData[reportName] = amount;
    }
}

// queries API 
function fetchReports(repNum) {
    reportNum = repNum;
    //request = decodeURIComponent(request);
    request = request.replace(/</g, '^');
    $('body').addClass('loading-screen');
    //console.log(request);
    $.ajax({
        url: base_url + 'pmas/proxy',
        data: {
            requrl: request + '&reportType=' + reportNum
        },
        dataType: 'xml'
    })
        .done(function (response, textStatus, jqXHR) {
            //console.log(response)
            var responseStatus = $(response).find('StatusCode').text();
            //console.log(responseStatus);
            if (reportNum === '110') {
                var responseStatus = $(response).find('Status').text();
                addReportCost('110', .45, responseStatus);
                compileXmlUrls(response, 'report110');
                compsRoute();
            } else if (reportNum === '111') {
                addReportCost('111', .05, responseStatus);
                compileXmlUrls(response, 'report111');
            } else if (responseStatus === 'MM') {
                multipleResults(response);
            } else if (responseStatus !== 'OK') {
                displayError(responseStatus);
            } else if (reportNum === '187') {
                addReportCost('187', .50, responseStatus);
                compileXmlUrls(response, 'report187');
                listResults(response);
                fetchReports('111')
            }
        })
        .fail(function (err) {
            if (reportNum === '187') {
                $('.pma-error').text('Unsuccessful Request');
            }
            if (reportNum === '110') {
                compsRoute();
            }
        })
        .always(function () {
            $('.search-result-div').removeClass('hide');
            $('body').removeClass('loading-screen');
            if (reportNum !== '110') {
                $('.progress-bar').hide();
            }
        })
}

// lists result(s) from API call
function listResults(response) {
    // display the returned address(es) under search results
    $('.result-table > tbody').show();
    var address = $(response).find('Locations').find('Location').find('Address').text();
    var apn = $(response).find('Locations').find('Location').find('APN').text();
    var city = $(response).find('Locations').find('Location').find('City').text();
    var state = $(response).find('Locations').find('Location').find('State').text();
    var zip = $(response).find('Locations').find('Location').find('ZIP').text();
    var fips = $(response).find('Locations').find('Location').find('FIPS').text();
    addResultToRepData(address, city, state, zip, apn, fips)
    $('.result-apn').text(apn);
    $('.result-address').text(address);
    $('.result-city').text(city);
    $('.js-run-pma-button').show();
    compsXML = '';
    compsArray = [];
    compsSkip = false;
    docsSkip = false;
    $('#comps-table')
        .unbind('appendCache applyWidgetId applyWidgets sorton update updateCell')
        .removeClass('tablesorter')
        .find('thead th')
        .unbind('click mousedown')
        .removeClass('header headerSortDown headerSortUp');
}

// extracts url for report from API response and adds to reportData object
function compileXmlUrls(response, report) {
    // get the url for each report
    reportUrl = $(response).find('ReportURL').text();
    if (report === 'report111') {
        // console.log(reportUrl);
        var reportUrl = reportUrl.split("<CustCompFilter>")[0];
        //console.log(reportUrl);
    }
    reportData[report] = reportUrl;
}


// if API call is unsuccessful, displays error 
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
            error = 'Invalid property address';
            break;
        case 'CR':
            error = 'No credits';
            break;
        case 'NH':
            error = 'Valid address, but no hit';
            break;
        default:
            error = "Error"
    }
    $('.pma-error').text(error);
    $('.pma-error').show();
}



// handles API response that has multiple addresses 
function multipleResults(response) {
    $('.result-table > tbody').html('');
    $(response).find('Locations').children('Location').each(function (i) {
        var address = $(this).find('Address').text();
        apn = $(this).find('APN').text();
        apnInfo[apn] = {}
        var city = $(this).find('City').text();
        var state = $(this).find('State').text();
        var zip = $(this).find('ZIP').text();
        apnInfo[apn]['fips'] = $(this).find('FIPS').text();
        //console.log('fips1= ' + apnInfo[apn]['fips']);
        $('.result-table > tbody').append('<tr><td><span class="result-apn"></span></td><td><span class="result-address"></span></td><td><span class="result-city"></span></td><td><button type="button" class="btn btn-info js-run-apn-button" >Create</button></td></tr>');
        $('.result-table > tbody').find('tr').eq(i).find('.result-apn').text(apn);
        $('.result-table > tbody').find('tr').eq(i).find('.result-address').text(address);
        $('.result-table > tbody').find('tr').eq(i).find('.result-city').text(city);
        $('.js-run-apn-button').show();
    });
}


// adds address data to reportData object
function addResultToRepData(address, city, state, zip, apn, fips) {
    //address = address.replace('.', '');
    //address = address.replace(',', '');
    address = address.replace('#', '');
    address = address.replace('/', ' ');
    reportData.address = address;
    reportData.city = city;
    reportData.state = state;
    reportData.zip = zip;
    reportData.apn = apn;
    reportData.fips = fips;
}


// handles click event on run APN
function apnMultiple() {
    event.preventDefault ? event.preventDefault() : event.returnValue = false;
    var apn = $(this).closest('tr').find('.result-apn').text();
    var fips = apnInfo[apn]['fips'];
    apnInfo['apn'] = apn;
    apnInfo['fips'] = fips;
    $('.progress-bar').show();
    $('.result-table > tbody').html('');
    $('.result-table > tbody').hide();
    $('.result-table > tbody').append('<tr><td><span class="result-apn"></span></td><td><span class="result-address"></span></td><td><span class="result-city"></span></td><td><button type="button" class="btn btn-info js-run-pma-button">Create</button></td></tr>');
    apnData();
}


function autoComplete() {
    // use Google Places API to autocomplete address searches and bias suggestions to California
    var input = document.getElementById('js-property-search');
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
        setTimeout(function () {
            $('.js-pma-address').val(place.name);
        }, 25); // just display street address
        for (var i = 0; i < place.address_components.length; i++) {
            for (var j = 0; j < place.address_components[i].types.length; j++) {
                if (place.address_components[i].types[j] === ("locality" || "political")) {
                    var city = place.address_components[i].long_name;
                    $('.js-pma-city').val(city);
                }
            }
        }
    });
}


// ***helper and test functions***

// helper function that adds commas to numbers
function commasToNumber(val) {
    while (/(\d+)(\d{3})/.test(val.toString())) {
        val = val.toString().replace(/(\d+)(\d{3})/, '$1' + ',' + '$2');
    }
    return val;
}


// removes accent marks from inputted address
function removeDiacritics(str) {
    var changes;
    if (!changes) {
        changes = defaultDiacriticsRemovalMap;
    }
    for (var i = 0; i < changes.length; i++) {
        str = str.replace(changes[i].letters, changes[i].base);
    }
    return str;
}

// purely for testing
function testComps() {
    //console.log('compsSkip= ' + compsSkip);
    if (compsSkip === true) {
        runPMA()
        //console.log('compsXML ' + compsXML);
    } else if (compsXML === '') {
        testGet187();
    } else {
        displayComps();
    }
}

// purely for testing
function testGet187() {
    reportData.address = 'testaddress';
    query = $.param(reportData);
    $.ajax({
        type: "GET",
        url: base_url + "pmas/xmlproxy",
        data: {
            requrl: 'test187.xml'
        },
        dataType: "xml",
        success: function (xml) {
            compsXML = xml;
            parse187();
        },
        error: function () {
            alert("An error occurred while processing XML file.");
        }
    });
}


// data objects 


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
}

var defaultDiacriticsRemovalMap = [{
    'base': 'A',
    'letters': /[\u0041\u24B6\uFF21\u00C0\u00C1\u00C2\u1EA6\u1EA4\u1EAA\u1EA8\u00C3\u0100\u0102\u1EB0\u1EAE\u1EB4\u1EB2\u0226\u01E0\u00C4\u01DE\u1EA2\u00C5\u01FA\u01CD\u0200\u0202\u1EA0\u1EAC\u1EB6\u1E00\u0104\u023A\u2C6F]/g
}, {
    'base': 'AA',
    'letters': /[\uA732]/g
}, {
    'base': 'AE',
    'letters': /[\u00C6\u01FC\u01E2]/g
}, {
    'base': 'AO',
    'letters': /[\uA734]/g
}, {
    'base': 'AU',
    'letters': /[\uA736]/g
}, {
    'base': 'AV',
    'letters': /[\uA738\uA73A]/g
}, {
    'base': 'AY',
    'letters': /[\uA73C]/g
}, {
    'base': 'B',
    'letters': /[\u0042\u24B7\uFF22\u1E02\u1E04\u1E06\u0243\u0182\u0181]/g
}, {
    'base': 'C',
    'letters': /[\u0043\u24B8\uFF23\u0106\u0108\u010A\u010C\u00C7\u1E08\u0187\u023B\uA73E]/g
}, {
    'base': 'D',
    'letters': /[\u0044\u24B9\uFF24\u1E0A\u010E\u1E0C\u1E10\u1E12\u1E0E\u0110\u018B\u018A\u0189\uA779]/g
}, {
    'base': 'DZ',
    'letters': /[\u01F1\u01C4]/g
}, {
    'base': 'Dz',
    'letters': /[\u01F2\u01C5]/g
}, {
    'base': 'E',
    'letters': /[\u0045\u24BA\uFF25\u00C8\u00C9\u00CA\u1EC0\u1EBE\u1EC4\u1EC2\u1EBC\u0112\u1E14\u1E16\u0114\u0116\u00CB\u1EBA\u011A\u0204\u0206\u1EB8\u1EC6\u0228\u1E1C\u0118\u1E18\u1E1A\u0190\u018E]/g
}, {
    'base': 'F',
    'letters': /[\u0046\u24BB\uFF26\u1E1E\u0191\uA77B]/g
}, {
    'base': 'G',
    'letters': /[\u0047\u24BC\uFF27\u01F4\u011C\u1E20\u011E\u0120\u01E6\u0122\u01E4\u0193\uA7A0\uA77D\uA77E]/g
}, {
    'base': 'H',
    'letters': /[\u0048\u24BD\uFF28\u0124\u1E22\u1E26\u021E\u1E24\u1E28\u1E2A\u0126\u2C67\u2C75\uA78D]/g
}, {
    'base': 'I',
    'letters': /[\u0049\u24BE\uFF29\u00CC\u00CD\u00CE\u0128\u012A\u012C\u0130\u00CF\u1E2E\u1EC8\u01CF\u0208\u020A\u1ECA\u012E\u1E2C\u0197]/g
}, {
    'base': 'J',
    'letters': /[\u004A\u24BF\uFF2A\u0134\u0248]/g
}, {
    'base': 'K',
    'letters': /[\u004B\u24C0\uFF2B\u1E30\u01E8\u1E32\u0136\u1E34\u0198\u2C69\uA740\uA742\uA744\uA7A2]/g
}, {
    'base': 'L',
    'letters': /[\u004C\u24C1\uFF2C\u013F\u0139\u013D\u1E36\u1E38\u013B\u1E3C\u1E3A\u0141\u023D\u2C62\u2C60\uA748\uA746\uA780]/g
}, {
    'base': 'LJ',
    'letters': /[\u01C7]/g
}, {
    'base': 'Lj',
    'letters': /[\u01C8]/g
}, {
    'base': 'M',
    'letters': /[\u004D\u24C2\uFF2D\u1E3E\u1E40\u1E42\u2C6E\u019C]/g
}, {
    'base': 'N',
    'letters': /[\u004E\u24C3\uFF2E\u01F8\u0143\u00D1\u1E44\u0147\u1E46\u0145\u1E4A\u1E48\u0220\u019D\uA790\uA7A4]/g
}, {
    'base': 'NJ',
    'letters': /[\u01CA]/g
}, {
    'base': 'Nj',
    'letters': /[\u01CB]/g
}, {
    'base': 'O',
    'letters': /[\u004F\u24C4\uFF2F\u00D2\u00D3\u00D4\u1ED2\u1ED0\u1ED6\u1ED4\u00D5\u1E4C\u022C\u1E4E\u014C\u1E50\u1E52\u014E\u022E\u0230\u00D6\u022A\u1ECE\u0150\u01D1\u020C\u020E\u01A0\u1EDC\u1EDA\u1EE0\u1EDE\u1EE2\u1ECC\u1ED8\u01EA\u01EC\u00D8\u01FE\u0186\u019F\uA74A\uA74C]/g
}, {
    'base': 'OI',
    'letters': /[\u01A2]/g
}, {
    'base': 'OO',
    'letters': /[\uA74E]/g
}, {
    'base': 'OU',
    'letters': /[\u0222]/g
}, {
    'base': 'P',
    'letters': /[\u0050\u24C5\uFF30\u1E54\u1E56\u01A4\u2C63\uA750\uA752\uA754]/g
}, {
    'base': 'Q',
    'letters': /[\u0051\u24C6\uFF31\uA756\uA758\u024A]/g
}, {
    'base': 'R',
    'letters': /[\u0052\u24C7\uFF32\u0154\u1E58\u0158\u0210\u0212\u1E5A\u1E5C\u0156\u1E5E\u024C\u2C64\uA75A\uA7A6\uA782]/g
}, {
    'base': 'S',
    'letters': /[\u0053\u24C8\uFF33\u1E9E\u015A\u1E64\u015C\u1E60\u0160\u1E66\u1E62\u1E68\u0218\u015E\u2C7E\uA7A8\uA784]/g
}, {
    'base': 'T',
    'letters': /[\u0054\u24C9\uFF34\u1E6A\u0164\u1E6C\u021A\u0162\u1E70\u1E6E\u0166\u01AC\u01AE\u023E\uA786]/g
}, {
    'base': 'TZ',
    'letters': /[\uA728]/g
}, {
    'base': 'U',
    'letters': /[\u0055\u24CA\uFF35\u00D9\u00DA\u00DB\u0168\u1E78\u016A\u1E7A\u016C\u00DC\u01DB\u01D7\u01D5\u01D9\u1EE6\u016E\u0170\u01D3\u0214\u0216\u01AF\u1EEA\u1EE8\u1EEE\u1EEC\u1EF0\u1EE4\u1E72\u0172\u1E76\u1E74\u0244]/g
}, {
    'base': 'V',
    'letters': /[\u0056\u24CB\uFF36\u1E7C\u1E7E\u01B2\uA75E\u0245]/g
}, {
    'base': 'VY',
    'letters': /[\uA760]/g
}, {
    'base': 'W',
    'letters': /[\u0057\u24CC\uFF37\u1E80\u1E82\u0174\u1E86\u1E84\u1E88\u2C72]/g
}, {
    'base': 'X',
    'letters': /[\u0058\u24CD\uFF38\u1E8A\u1E8C]/g
}, {
    'base': 'Y',
    'letters': /[\u0059\u24CE\uFF39\u1EF2\u00DD\u0176\u1EF8\u0232\u1E8E\u0178\u1EF6\u1EF4\u01B3\u024E\u1EFE]/g
}, {
    'base': 'Z',
    'letters': /[\u005A\u24CF\uFF3A\u0179\u1E90\u017B\u017D\u1E92\u1E94\u01B5\u0224\u2C7F\u2C6B\uA762]/g
}, {
    'base': 'a',
    'letters': /[\u0061\u24D0\uFF41\u1E9A\u00E0\u00E1\u00E2\u1EA7\u1EA5\u1EAB\u1EA9\u00E3\u0101\u0103\u1EB1\u1EAF\u1EB5\u1EB3\u0227\u01E1\u00E4\u01DF\u1EA3\u00E5\u01FB\u01CE\u0201\u0203\u1EA1\u1EAD\u1EB7\u1E01\u0105\u2C65\u0250]/g
}, {
    'base': 'aa',
    'letters': /[\uA733]/g
}, {
    'base': 'ae',
    'letters': /[\u00E6\u01FD\u01E3]/g
}, {
    'base': 'ao',
    'letters': /[\uA735]/g
}, {
    'base': 'au',
    'letters': /[\uA737]/g
}, {
    'base': 'av',
    'letters': /[\uA739\uA73B]/g
}, {
    'base': 'ay',
    'letters': /[\uA73D]/g
}, {
    'base': 'b',
    'letters': /[\u0062\u24D1\uFF42\u1E03\u1E05\u1E07\u0180\u0183\u0253]/g
}, {
    'base': 'c',
    'letters': /[\u0063\u24D2\uFF43\u0107\u0109\u010B\u010D\u00E7\u1E09\u0188\u023C\uA73F\u2184]/g
}, {
    'base': 'd',
    'letters': /[\u0064\u24D3\uFF44\u1E0B\u010F\u1E0D\u1E11\u1E13\u1E0F\u0111\u018C\u0256\u0257\uA77A]/g
}, {
    'base': 'dz',
    'letters': /[\u01F3\u01C6]/g
}, {
    'base': 'e',
    'letters': /[\u0065\u24D4\uFF45\u00E8\u00E9\u00EA\u1EC1\u1EBF\u1EC5\u1EC3\u1EBD\u0113\u1E15\u1E17\u0115\u0117\u00EB\u1EBB\u011B\u0205\u0207\u1EB9\u1EC7\u0229\u1E1D\u0119\u1E19\u1E1B\u0247\u025B\u01DD]/g
}, {
    'base': 'f',
    'letters': /[\u0066\u24D5\uFF46\u1E1F\u0192\uA77C]/g
}, {
    'base': 'g',
    'letters': /[\u0067\u24D6\uFF47\u01F5\u011D\u1E21\u011F\u0121\u01E7\u0123\u01E5\u0260\uA7A1\u1D79\uA77F]/g
}, {
    'base': 'h',
    'letters': /[\u0068\u24D7\uFF48\u0125\u1E23\u1E27\u021F\u1E25\u1E29\u1E2B\u1E96\u0127\u2C68\u2C76\u0265]/g
}, {
    'base': 'hv',
    'letters': /[\u0195]/g
}, {
    'base': 'i',
    'letters': /[\u0069\u24D8\uFF49\u00EC\u00ED\u00EE\u0129\u012B\u012D\u00EF\u1E2F\u1EC9\u01D0\u0209\u020B\u1ECB\u012F\u1E2D\u0268\u0131]/g
}, {
    'base': 'j',
    'letters': /[\u006A\u24D9\uFF4A\u0135\u01F0\u0249]/g
}, {
    'base': 'k',
    'letters': /[\u006B\u24DA\uFF4B\u1E31\u01E9\u1E33\u0137\u1E35\u0199\u2C6A\uA741\uA743\uA745\uA7A3]/g
}, {
    'base': 'l',
    'letters': /[\u006C\u24DB\uFF4C\u0140\u013A\u013E\u1E37\u1E39\u013C\u1E3D\u1E3B\u017F\u0142\u019A\u026B\u2C61\uA749\uA781\uA747]/g
}, {
    'base': 'lj',
    'letters': /[\u01C9]/g
}, {
    'base': 'm',
    'letters': /[\u006D\u24DC\uFF4D\u1E3F\u1E41\u1E43\u0271\u026F]/g
}, {
    'base': 'n',
    'letters': /[\u006E\u24DD\uFF4E\u01F9\u0144\u00F1\u1E45\u0148\u1E47\u0146\u1E4B\u1E49\u019E\u0272\u0149\uA791\uA7A5]/g
}, {
    'base': 'nj',
    'letters': /[\u01CC]/g
}, {
    'base': 'o',
    'letters': /[\u006F\u24DE\uFF4F\u00F2\u00F3\u00F4\u1ED3\u1ED1\u1ED7\u1ED5\u00F5\u1E4D\u022D\u1E4F\u014D\u1E51\u1E53\u014F\u022F\u0231\u00F6\u022B\u1ECF\u0151\u01D2\u020D\u020F\u01A1\u1EDD\u1EDB\u1EE1\u1EDF\u1EE3\u1ECD\u1ED9\u01EB\u01ED\u00F8\u01FF\u0254\uA74B\uA74D\u0275]/g
}, {
    'base': 'oi',
    'letters': /[\u01A3]/g
}, {
    'base': 'ou',
    'letters': /[\u0223]/g
}, {
    'base': 'oo',
    'letters': /[\uA74F]/g
}, {
    'base': 'p',
    'letters': /[\u0070\u24DF\uFF50\u1E55\u1E57\u01A5\u1D7D\uA751\uA753\uA755]/g
}, {
    'base': 'q',
    'letters': /[\u0071\u24E0\uFF51\u024B\uA757\uA759]/g
}, {
    'base': 'r',
    'letters': /[\u0072\u24E1\uFF52\u0155\u1E59\u0159\u0211\u0213\u1E5B\u1E5D\u0157\u1E5F\u024D\u027D\uA75B\uA7A7\uA783]/g
}, {
    'base': 's',
    'letters': /[\u0073\u24E2\uFF53\u00DF\u015B\u1E65\u015D\u1E61\u0161\u1E67\u1E63\u1E69\u0219\u015F\u023F\uA7A9\uA785\u1E9B]/g
}, {
    'base': 't',
    'letters': /[\u0074\u24E3\uFF54\u1E6B\u1E97\u0165\u1E6D\u021B\u0163\u1E71\u1E6F\u0167\u01AD\u0288\u2C66\uA787]/g
}, {
    'base': 'tz',
    'letters': /[\uA729]/g
}, {
    'base': 'u',
    'letters': /[\u0075\u24E4\uFF55\u00F9\u00FA\u00FB\u0169\u1E79\u016B\u1E7B\u016D\u00FC\u01DC\u01D8\u01D6\u01DA\u1EE7\u016F\u0171\u01D4\u0215\u0217\u01B0\u1EEB\u1EE9\u1EEF\u1EED\u1EF1\u1EE5\u1E73\u0173\u1E77\u1E75\u0289]/g
}, {
    'base': 'v',
    'letters': /[\u0076\u24E5\uFF56\u1E7D\u1E7F\u028B\uA75F\u028C]/g
}, {
    'base': 'vy',
    'letters': /[\uA761]/g
}, {
    'base': 'w',
    'letters': /[\u0077\u24E6\uFF57\u1E81\u1E83\u0175\u1E87\u1E85\u1E98\u1E89\u2C73]/g
}, {
    'base': 'x',
    'letters': /[\u0078\u24E7\uFF58\u1E8B\u1E8D]/g
}, {
    'base': 'y',
    'letters': /[\u0079\u24E8\uFF59\u1EF3\u00FD\u0177\u1EF9\u0233\u1E8F\u00FF\u1EF7\u1E99\u1EF5\u01B4\u024F\u1EFF]/g
}, {
    'base': 'z',
    'letters': /[\u007A\u24E9\uFF5A\u017A\u1E91\u017C\u017E\u1E93\u1E95\u01B6\u0225\u0240\u2C6C\uA763]/g
}];