var orders ='';
$(document).ready(function () {
    if ($('#orders').length > 0)  {
        orders = $('#orders').DataTable({
           "paging": true,
            "lengthMenu": [10, 20, 50, 100, 200, 500, 1000],
            "lengthChange": true,
            "language": {
                paginate: {
                  next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                  previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                },
                "emptyTable": "Record(s) not found.",
            },
            initComplete: function() {
            },
            "dom": 'Blfrtip',
            "drawCallback": function () {               
                $('.dataTables_paginate > .pagination li').addClass('page-item');
                $('.dataTables_paginate > .pagination a').addClass('page-link');
                $('.dataTables_paginate > .pagination li.previous a, .dataTables_paginate > .pagination li.next a').addClass('rounded');
            },
            "ordering": true,            
            "serverSide": true,
            "columnDefs": [ {
                'targets': [0, 5, 7], 
                'orderable': false, 
             }],
            "ajax": {                
                url: base_url+"hr/admin/get-orders", 
                type: "post", 
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    if (parseInt(XMLHttpRequest.status) == 419) {
                        alert("You are logged out. Please login.");
                    }
                    if (parseInt(XMLHttpRequest.status) == 419) {
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                    }
                    $("#orders tbody").append('<tr><td colspan="6" class="text-center">No records found</td></tr>');
                    $("#orders_processing").css("display", "none");
                }
            }            
        });
    }
});



