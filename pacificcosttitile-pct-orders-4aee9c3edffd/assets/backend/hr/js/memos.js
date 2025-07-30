CKEDITOR.replace( 'memo_description' );

$(document).ready(function () {
    $('#memo_date').datepicker().datepicker("setDate", new Date());
    if ($("#memo_date_val").length != 0) {
        $('#memo_date').val($("#memo_date_val").val());
    }
});