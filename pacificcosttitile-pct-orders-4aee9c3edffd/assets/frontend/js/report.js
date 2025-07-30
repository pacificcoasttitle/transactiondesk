$(document).ready(function(){
    $('#report_listing').DataTable({
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
});