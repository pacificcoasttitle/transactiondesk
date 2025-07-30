$(document).ready(function () {
    if ($('#prelim_files').length) {
        customer_list = $('#prelim_files').DataTable({
            "paging": true,
            "lengthChange": false,
            "language": {
                searchPlaceholder: "Search #File or Address",
                paginate: {
                    next: '<span class="fa fa-angle-right"></span>',
                    previous: '<span class="fa fa-angle-left"></span>',
                },
                "emptyTable": "Record(s) not found.",
                "search": ""
            },
            // "searching": false,
            "bStateSave": true,
            "fnStateSave": function (oSettings, oData) {
                localStorage.setItem('offersDataTables', JSON.stringify(oData));
            },
            "fnStateLoad": function (oSettings) {
                return JSON.parse(localStorage.getItem('offersDataTables'));
            },
            initComplete: function () { },
            dom: 'Bfrtip',
            buttons: [],
            "drawCallback": function () { },
            "ordering": false,
            "serverSide": true,
            "ajax": {
                url: base_url + "get-orders-prelim", // json datasource
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