var policy_orders = '';
$(document).ready(function () {
    if ($('#policy_orders').length) {
        policy_orders = $('#policy_orders').DataTable({
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
            initComplete: function () { },
            dom: 'Bfrtip',
            buttons: [],
            "drawCallback": function () { },
            "ordering": false,
            "serverSide": true,
            "ajax": {
                url: base_url + "get-orders-policy",
                type: "post",
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
                    $("#upload_doc_orders tbody").append(
                        '<tr><td colspan="4" class="text-center">No records found</td></tr>');
                    $("#upload_doc_orders_processing").css("display", "none");
                },
                complete: function () {
                    $("#page-list-loader").hide();
                    $('#page-list-loader').css('display', 'none');
                }
            }
        });
    }
});