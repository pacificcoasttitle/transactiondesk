$(document).ready(function () {
    if ($('#order_listing').length) {
        order_listing = $('#order_listing').DataTable({
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
                url: base_url + "get-orders-dashboard", // json datasource
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
                    $("#order_listing tbody").append(
                        '<tr><td colspan="4" class="text-center">No records found</td></tr>');
                    $("#order_listing_processing").css("display", "none");
                }
            }
        });
    }
});

$(document).on('click', '.sendInvite', function () {
    var orderId = $(this).data('order');
    var owner = $(this).data('owner');
    var address = $(this).data('address');
    $("#borrower_name").val(owner);
    $("#invite_order_id").val(orderId);
    $("#property_address").val(address);
    $("#sendInviteModal").modal('show');
});

$(document).on('click', '#sendInviteBtn', function () {
    $(this).attr('disabled', true);
    var form_data = $('#inviteForm').serialize();
    var url = base_url + "send_invite";
    $('.error-cotent').html('');
    $.ajax({
        type: "POST",
        url: url,
        data: form_data,
        dataType: 'json',
        success: function (data) {
            if (data.status == true) {
                location.reload();
            } else {
                $('.error-cotent').html(`<div class="alert alert-danger" role="alert">` + data
                    .message + `</div>`);
            }
        },
        complete: function () {
            $('#sendInviteBtn').removeAttr('disabled');
        }
    });
});