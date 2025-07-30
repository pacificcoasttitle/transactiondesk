$(document).ready(function () {
    var title_officer_order_list = '';
    var title_officer_notes = '';
    var title_officer_forms_listing = '';
    if ($('#title_officer_orders_listing').length) {
        title_officer_order_list = $('#title_officer_orders_listing').DataTable({
            "paging": true,
            "lengthChange": false,
            "language": {
                searchPlaceholder: "Search File# or Address",
                paginate: {
                    next: '<span class="fa fa-angle-right"></span>',
                    previous: '<span class="fa fa-angle-left"></span>',
                },
                "emptyTable": "Record(s) not found.",
                "search": "",
            },
            initComplete: function () {


            },
            "dom": 'lf<"title_officer_orders_listing_filter">rtip',
            buttons: [],
            "drawCallback": function () {

            },
            "fnInitComplete": function (oSettings, json) {               
                $(".fa-info-circle").mouseenter(function() {
                    $(this).closest('td').find('span.tooltiptext').css("visibility", "visible").css("border-radius", "3px");
                }).mouseleave(function() {
                    $(this).closest('td').find('span.tooltiptext').css("visibility", "hidden").css("border-radius", "0px");
                });
            },
            "ordering": false,
            "serverSide": true,
            "ajax": {
                url: base_url + "get-title-officer-orders",
                type: "post",
                data: function (d) {
                    d.status = $('#orders_filter').val();
                    d.month = $('#month_filter').val();
                    d.month = $('#month_filter').val();
                    d.order_type = $('#order_type_filter').val();
                },
                dataFilter: function (data) {
                    var json = jQuery.parseJSON(data);
                    var countingData = json.count_data;
                    json.recordsTotal = json.recordsTotal;
                    json.recordsFiltered = json.recordsFiltered;
                    json.data = json.data;
                    return JSON.stringify(json);
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
                    $("#title_officer_orders_listing tbody").append(
                        '<tr><td colspan="4" class="text-center">No records found</td></tr>');
                    $("#title_officer_orders_listing_processing").css("display", "none");
                }
            }
        });

        $("div#title_officer_orders_listing_filter").append(
            '<label><select style="width:auto;" name="month_filter" id="month_filter" class="custom-select custom-select-sm form-control form-control-sm"> <option value="01"> January </option><option value="02">February</option><option value="03">March</option><option value="04">April</option><option value="05">May</option><option value="06">June</option><option value="07">July</option><option value="08">August</option><option value="09">September</option><option value="10">October</option><option value="11">November</option><option value="12">December</option></select></label><label><select style="width:auto;" name="orders_filter" id="orders_filter" class="custom-select custom-select-sm form-control form-control-sm"> <option value="open"> Open </option><option value="closed">Closed</option><option value="cancelled">Cancelled</option></select></label><label><select style="width:auto;margin-left:10px;" name="order_type_filter" id="order_type_filter" class="custom-select custom-select-sm form-control form-control-sm"><option value="open"> Select Order Type </option> <option value="resware_orders"> Resware Orders </option><option value="lp_orders">LP Orders</option></select></label>'
            );

        var d = new Date(),

            m = d.getMonth(),

            y = d.getFullYear();

        $('#month_filter option:eq(' + m + ')').prop('selected', true);
    }

    if ($('#title_officer_notes').length) {
        title_officer_notes = $('#title_officer_notes').DataTable({
            // "pageLength": 2,
            "paging": true,
            "lengthChange": false,
            "language": {
                searchPlaceholder: "Search File# or Address",
                paginate: {
                    next: '<span class="fa fa-angle-right"></span>',
                    previous: '<span class="fa fa-angle-left"></span>',
                },
                "emptyTable": "Record(s) not found.",
                "search": "",
            },
            /*"searching": false,*/
            initComplete: function () {


            },
            dom: 'Bfrtip',
            buttons: [],
            "drawCallback": function () {

            },
            "ordering": false,
            "serverSide": true,
            "ajax": {
                url: base_url + "get-notes-orders", // json datasource
                type: "post", // method  , by default get
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    if (parseInt(XMLHttpRequest.status) == 419) {
                        alert("You are logged out. Please login.");
                    }
                    if (parseInt(XMLHttpRequest.status) == 419) {
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                    }
                    $("#title_officer_notes tbody").append(
                        '<tr><td colspan="4" class="text-center">No records found</td></tr>');
                    $("#title_officer_notes_processing").css("display", "none");
                }
            }
        });
    }

    if ($('#title_officer_forms_listing').length) {
        title_officer_forms_listing = $('#title_officer_forms_listing').DataTable({
            // "pageLength": 2,
            "paging": true,
            "lengthChange": false,
            "language": {
                paginate: {
                    next: '<span class="fa fa-angle-right"></span>',
                    previous: '<span class="fa fa-angle-left"></span>',
                },
                "emptyTable": "Record(s) not found.",
                "search": "",
            },
            /*"searching": false,*/
            initComplete: function () {

            },
            dom: 'Bfrtip',
            buttons: [],
            "drawCallback": function () {

            },
            "ordering": false,
            "serverSide": true,
            "ajax": {
                url: base_url + "get-file-document", // json datasource
                type: "post", // method  , by default get
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    if (parseInt(XMLHttpRequest.status) == 419) {
                        alert("You are logged out. Please login.");
                    }
                    if (parseInt(XMLHttpRequest.status) == 419) {
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                    }
                    $("#title_officer_forms_listing tbody").append(
                        '<tr><td colspan="4" class="text-center">No records found</td></tr>');
                    $("#title_officer_forms_listing_processing").css("display", "none");

                }
            }
        });
    }

    $("#orders_filter").on("change", function () {
        title_officer_order_list.ajax.reload();
    });

    $("#month_filter").on("change", function () {
        title_officer_order_list.ajax.reload();
    });

    $("#order_type_filter").on("change", function(){
        title_officer_order_list.ajax.reload();
    });


});

function getPartners(fileId) 
{
    $('#page-preloader').css('background-color', 'rgba(0,0,0,.5)');
    $('#page-preloader').css('display', 'block');
    $.ajax({
        url: base_url + "get-partners",
        type: "post",
        data: {
            fileId: fileId
        },
        dataType: "html",
        success: function (response) {

            var results = JSON.parse(response);
            
            var table_data = '';
            if(results.status == 'success')
            {
                if(!jQuery.isEmptyObject(results.partners))
                {
                    $.each(results.partners, function( key, value ) {
                          table_data += '<tr><td>'+value.PartnerID+'</td><td>'+value.PartnerTypeID+'</td><td>'+value.PartnerType.PartnerTypeName+'</td><td>'+value.PartnerName+'</td></tr>';
                    });
                }
                else
                {
                    table_data += '<tr><td colspan="4" style="text-align: center;">No records found.</td></tr>';
                }
                $('#tbl-partners-data tbody').html(table_data);
                $('#partnersModal').modal('show');
            }
            else if(results.status == 'error')
            {
                alert(results.msg);
            }
            $('#page-preloader').css('display', 'none');
        }
    });
}

function downloadDocumentFromAws(url, documentType)
{
    $('#page-preloader').css('background-color', 'rgba(0,0,0,.5)');
    $('#page-preloader').css('display', 'block');
    var fileNameIndex = url.lastIndexOf("/") + 1;
    var filename = url.substr(fileNameIndex);
    $.ajax({
        url: base_url + "download-aws-file",
        type: "post",
        data: {
            url : url
        },
        async: false,
        success: function (response) {
            if (response) {
                if (navigator.msSaveBlob) {
                    var csvData = base64toBlob(response, 'application/octet-stream');
                    var csvURL = navigator.msSaveBlob(csvData, filename);
                    var element = document.createElement('a');
                    element.setAttribute('href', csvURL);
                    element.setAttribute('download', documentType+"_"+filename);
                    element.style.display = 'none';
                    document.body.appendChild(element);
                    document.body.removeChild(element);
                } else {
                    console.log(response);
                    var csvURL = 'data:application/octet-stream;base64,' + response;
                    var element = document.createElement('a');
                    element.setAttribute('href', csvURL);
                    element.setAttribute('download', documentType+"_"+filename);
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