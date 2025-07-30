$(document).ready(function () {
    if ($('#create-note').length) {
        $('#create-note').validate({
            rules: {
                subject: "required",
                body: "required"
            },
            messages: {
                subject: "Please enter subject",
                body: "Please enter body",
            }
        });
    }
});



