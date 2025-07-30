$(document).ready(function () {
    var pay_off_orders_listing = '';
    if ($('#pay_off_orders_listing').length) {
        pay_off_orders_listing = $('#pay_off_orders_listing').DataTable({
            "paging": true,
            "lengthChange": false,
            "language": {
                searchPlaceholder: "Search",
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
                url: base_url + "get-transactees", // json datasource
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
                    $("#pay_off_orders_listing tbody").append(
                        '<tr><td colspan="4" class="text-center">No records found</td></tr>');
                    $("#pay_off_orders_listing_processing").css("display", "none");
                }
            }
        });
    }
});

function downloadPayOffDocument(file_id) {
    $('#page-preloader').css('background-color', 'rgba(0,0,0,.5)');
    $('#page-preloader').css('display', 'block');
    var filename = 'Pay_off_' + file_id + '.pdf';
    $.ajax({
        url: base_url + "download-pay-off-document",
        type: "post",
        data: {
            file_id: file_id
        },
        dataType: "html",
        success: function (response) {
            if (response) {
                if (navigator.msSaveBlob) {
                    var csvData = base64toBlob(response, 'application/octet-stream');
                    var csvURL = navigator.msSaveBlob(csvData, filename);
                    var element = document.createElement('a');
                    element.setAttribute('href', csvURL);
                    element.setAttribute('download', filename);
                    element.style.display = 'none';
                    document.body.appendChild(element);
                    document.body.removeChild(element);
                } else {
                    console.log(response);
                    var csvURL = 'data:application/octet-stream;base64,' + response;
                    var element = document.createElement('a');
                    element.setAttribute('href', csvURL);
                    element.setAttribute('download', filename);
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

function updatePayOffAction(file_id) {
    $('#page-preloader').css('background-color', 'rgba(0,0,0,.5)');
    $('#page-preloader').css('display', 'block');
    var filename = 'Pay_off_' + file_id + '.pdf';
    $.ajax({
        url: base_url + "update-pay-off-action",
        type: "post",
        data: {
            file_id: file_id
        },
        dataType: "html",
        success: function (response) {
            var results = JSON.parse(response);
            $('#page-preloader').css('display', 'none');
            if (results.status == 'success') {
                alert(results.msg);
            }
            else if (results.status == 'error') {
                alert(results.msg);
            }
        }
    });
}