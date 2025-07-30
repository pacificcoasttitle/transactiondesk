$(document).ready(function () {
    if(jQuery('#frm-add-escrow-officer').length)
    {
       jQuery('#frm-add-escrow-officer').validate({
            ignore:":not(:visible)",
            rules: {
                partner_name:"required",
                address:"required",
                email_address:"required",
                city:"required",
                partner_id:"required",
                state:"required",
                zip:"required",
                partner_type_id:"required"
            },
            messages: {
                partner_name:"Please Enter Partner Name",
                address:"Please Enter Address",
                email_address:"Please Enter Email address",
                city:"Please Enter City",
                partner_id:"Please Enter Partner Id",
                partner_type_id:"Please Enter Partner Type Id",
                state:"Please Enter State",
                zip: "Please Enter Zip",
            },
            submitHandler: function(form) {
                form.submit();  
            }
        }); 
    }

    if(jQuery('#frm-edit-escrow-officer').length)
        {
           jQuery('#frm-edit-escrow-officer').validate({
                ignore:":not(:visible)",
                rules: {
                    partner_name:"required",
                    address:"required",
                    email_address:"required",
                    city:"required",
                    partner_id:"required",
                    state:"required",
                    zip:"required",
                    partner_type_id:"required"
                },
                messages: {
                    partner_name:"Please Enter Partner Name",
                    address:"Please Enter Address",
                    email_address:"Please Enter Email address",
                    city:"Please Enter City",
                    partner_id:"Please Enter Partner Id",
                    partner_type_id:"Please Enter Partner Type Id",
                    state:"Please Enter State",
                    zip: "Please Enter Zip",
                },
                submitHandler: function(form) {
                    form.submit();  
                }
            }); 
        }
});