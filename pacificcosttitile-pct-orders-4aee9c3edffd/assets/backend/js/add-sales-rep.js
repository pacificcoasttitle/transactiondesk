$(document).ready(function () {
    if (jQuery('#frm-add-sales-rep').length) {
        jQuery('#frm-add-sales-rep').validate({
            ignore: "",
            rules: {
                sales_rep_first_name: "required",
                sales_rep_last_name: "required",
                email_address: "required",
                telephone: "required",
                partner_id: "required",
                partner_type_id: "required",
                sales_rep_no_of_open_orders: "required",
                sales_rep_no_of_close_orders: "required",
                sales_rep_premium: "required",
            },
            messages: {
                sales_rep_first_name: "Please Enter First Name",
                sales_rep_last_name: "Please Enter Last Name",
                email_address: "Please Enter Email address",
                telephone: "Please Enter Phone Number",
                partner_id: "Please Enter Partner Id",
                partner_type_id: "Please Enter Partner Type Id",
            },
            invalidHandler: function (event, validator) {
                if (validator.numberOfInvalids() > 0) {
                    validator.showErrors();
                    var collapse_class_id = $(":input.error").closest(".collapse").attr('id');
                    $('#' + collapse_class_id).collapse('show');
                }
            },
            submitHandler: function (form) {
                form.submit();
            }
        });
    }

    if (jQuery('#export-sales-rep-user').length) {
        console.log('export-sales-rep-user');
        jQuery('#export-sales-rep-user').validate({
            ignore: "",
            rules: {
                sales_rep: "required",
            },
            messages: {
                sales_rep: "Please Select Sales Reps"
            },
            invalidHandler: function (event, validator) {
                if (validator.numberOfInvalids() > 0) {
                    validator.showErrors();
                    var collapse_class_id = $(":select.error").closest(".collapse").attr('id');
                    $('#' + collapse_class_id).collapse('show');
                }
            },
            submitHandler: function (form) {
                console.log('sibmit handler');
                $.ajax({
                    url: base_url + "order/admin/export-sales-reps",
                    method: "POST",
                    data: {
                        sales_rep: $('#sales_rep').val(),
                    },
                    success: function (data) {
                        if (data.status == 'success') {
                            download('sales_reps.csv', data.data);
                            $('.success-msg').show();
                            setTimeout(function () {
                                $('.success-msg').hide();
                            }, 4000);
                        }
                        else {
                            $('.error-msg').show();
                            setTimeout(function () {
                                $('.error-msg').hide();
                            }, 4000);
                        }
                    },
                    error: function (XMLHttpRequest, textStatus, errorThrown) {
                        $('.error-msg').show();
                        setTimeout(function () {
                            $('.error-msg').hide();
                        }, 4000);
                    }
                });
            }
        });
    }
});