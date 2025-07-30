<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta content="We specialize in Residential, Commercial Title & Escrow Services" name="description">
    <meta content="" name="keywords">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="telephone=no" name="format-detection">
    <meta name="HandheldFriendly" content="true">
    <title>Authentication | Pacific Coast Title Company</title>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/frontend/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/frontend/css/fontawesome-all.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/frontend/css/iofrm-style.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/frontend/css/iofrm-theme2.css">
    <link rel="stylesheet" type="text/css"  href="<?php echo base_url(); ?>assets/frontend/css/smart-forms.css">
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/frontend/js/jquery-1.9.1.min.js"></script>
    <style>
        #email_address_php_error {
            display: none;
            margin-top: 6px;
            padding: 0 3px;
            font-family: Arial, Helvetica, sans-serif;
            font-style: normal;
            line-height: normal;
            color: red;
            font-size: 0.85em;
        }
        .state-error,
        .required,
        .error {
            color: #ff0000;
        }
    </style>
</head>
<body>
    <div class="form-body">
       
        <div class="row">
            <div class="img-holder">
                <div class="bg borrower-login"></div>
                <div class="info-holder">

                </div>
            </div>
            <div class="form-holder">
                <div class="form-content">
                    <div class="form-items borrower-login-from-items">
					
					<div class="website-logo-inside">
                            <a href="index.html">
                                <div class="l">
                                    <img class="logo-size" src="<?php echo base_url(); ?>assets/media/general/logo2.png" alt="">
                                </div>
                            </a>
                        </div>
						<h2>Borrower Verification Page</h21>
                        <h3>Let us know who you are...</h3>
						<p></p>
						<p>Title Order#:<?php echo $orderNumber; ?></p>
						<p>Property Address: <?php echo $propertyAddress; ?></p>
                        <p>Enter your cell phone number below. You will receive a code via text message. Please enter that code below to gain access to our secure form.</p>
                        <div class="page-links">
                            <a href="" class="active">Log in</a>
                            <!-- <a href="register2.html">Register</a> -->
                        </div>
                        <form method="POST" action="" id="borrower-login-form" enctype="multipart/form-data">
                            <label class="field" style="display:block;" id="get_code_label">
                                <input class="form-control gui-input" value="<?php echo $borrower_mobile_number; ?>" type="text" name="phone_number" id="phone_number" placeholder="Enter Mobile Number">
                            </label>

                            <label class="field" style="display:none;" id="verification_code_label">
                                <input class="form-control gui-input" type="text" name="verification_code" id="verification_code" placeholder="Verification Code">
                            </label>
                            <span id="code_error_msg" class="error" style="display: none;"></span>
                            <div class="form-button">
                                <!-- <button id="btn-verify-code" type="submit" class="ibtn" style="display:none;" name="verify_code">Verify Code</button>

                                <button id="btn-get-code" type="submit" class="ibtn" name="get_code">Get Code</button> -->

                                <input type="button" id="btn-verify-code" name="verify_code" value="Verify Code" class="ibtn" style="display:none;width:auto;" onclick="verifyCode();">

                                <input type="button" id="btn-get-code" name="get_code" value="Get Code" class="ibtn" onclick="getCode();" style="width:auto;">


                            </div>
                            <input type="hidden" name="random_number" id="random_number" value="<?php echo $randomNumber; ?>">
                            <input type="hidden" name="fileId" id="fileId" value="<?php echo $fileId; ?>">
                            <input type="hidden" name="is_seller" id="is_seller" value="<?php echo $sellerFlag; ?>">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="<?php echo base_url(); ?>assets/frontend/js/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/frontend/js/jquery.form.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/frontend/js/popper.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/frontend/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/frontend/js/main.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/frontend/js/jquery.validate.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/frontend/js/jquery-input-mask-phone-number.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/frontend/js/smart-form.js"></script>   
    <!-- <script type="text/javascript">
        var base_url = 'http://dev.pacificcoasttitle.com/';
    </script> -->

    <script type="text/javascript">
        $(document).ready(function () {

            $('#phone_number').usPhoneFormat();

            $( "#borrower-login-form" ).validate({
                
                /* @validation states + elements 
                ------------------------------------------- */
                errorClass: "state-error",
                validClass: "state-success",
                errorElement: "span",
                onkeyup: false,
                onclick: false,
                ignore: ":hidden",                     
                
                /* @validation rules 
                ------------------------------------------ */
                rules: {
                    phone_number: {
                        required: true
                    },
                    verification_code: {
                        required: true
                    }
                },
                
                /* @validation error messages 
                ---------------------------------------------- */
                messages:{
                    phone_number: {
                        required: 'Enter your phone number'
                    },
                    verification_code: {
                        required: 'Enter verification code'
                    }
                },

                /* @validation highlighting + error placement  
                ---------------------------------------------------- */ 
                highlight: function(element, errorClass, validClass) {
                        $(element).closest('.field').addClass(errorClass).removeClass(validClass);
                },
                unhighlight: function(element, errorClass, validClass) {
                        $(element).closest('.field').removeClass(errorClass).addClass(validClass);
                },
                errorPlacement: function(error, element) {
                   if (element.is(":radio") || element.is(":checkbox")) {
                            element.closest('.option-group').after(error);
                   } else {
                            error.insertAfter(element.parent());
                   }
                   /*$('#email_address_php_error').hide();
                   $('#password_php_error').hide();*/
                },
                
                /* @ajax form submition 
                ---------------------------------------------------- */
                /*submitHandler:function(form) {
                    
                    $.ajax({
                        url: '<?php // echo base_url(); ?>generate-verification-code',
                        type: "POST",
                        data: {
                            phone_number: $("#phone_number").val(),
                            random_number: $("#random_number").val(),
                        },
                        success: function(result)
                        {
                            console.log(result);
                            var res = jQuery.parseJSON(result);
                            
                            $('#findCustomerModal').modal('hide');
                            if(res.msg_status == 'success')
                            {
                                $('#btn-get-code').html('Resend Code');
                                $('#get_code_label').hide();
                                $('#btn-verify-code').show();
                                $('#verification_code_label').show();

                            }
                            else if(res.msg_status == 'error')
                            {
                                $('#btn-get-code').html('Get Code');
                                $('#get_code_label').show();
                                $('#btn-verify-code').hide();
                                $('#verification_code_label').hide();
                                alert(res.error_message);
                            }
                            
                        },
                        error:function(){
                            alert('Something went wrong');
                        },
                    });
                }*/
            });
        });

        function getCode() 
        {
            var btn_val = $('#btn-get-code').val();

            if(btn_val == 'Resend Code')
            {
                $('#verification_code_label').hide();
                $('#btn-verify-code').hide();
                $('#btn-get-code').val('Get Code');
                $('#get_code_label').show();
                $('#verification_code').val('');
            }
            else
            {
                if ($("#borrower-login-form" ).valid())
                {
                    $.ajax({
                        url: '<?php echo base_url(); ?>generate-verification-code',
                        type: "POST",
                        data: {
                            phone_number: $("#phone_number").val(),
                            random_number: $("#random_number").val(),
                            is_seller: $("#is_seller").val(),
                        },
                        success: function(result)
                        {
                            var res = jQuery.parseJSON(result); 
                            if(res.msg_status == 'success')
                            {
                                $('#btn-get-code').val('Resend Code');
                                $('#get_code_label').hide();
                                $('#btn-verify-code').show();
                                $('#verification_code_label').show();
                                $('#verification_code_label').css('display','block');

                            }
                            else if(res.msg_status == 'error')
                            {
                                $('#btn-get-code').val('Get Code');
                                $('#get_code_label').show();
                                $('#btn-verify-code').hide();
                                $('#verification_code_label').hide();
                                $('#code_error_msg').html(res.error_message);
                                $('#code_error_msg').show().delay(5000).fadeOut();
                            }
                            
                        },
                        error:function(){
                            alert('Something went wrong');
                        },
                    });
                } 
            }
            
        }

        function verifyCode() 
        {
            if ($("#borrower-login-form" ).valid())
            {
                $.ajax({
                    url: '<?php echo base_url(); ?>code-verification',
                    type: "POST",
                    data: {
                        code: $("#verification_code").val(),
                        fileId: $("#fileId").val(),
                        is_seller: $("#is_seller").val(),
                    },
                    success: function(result)
                    {
                        var res = jQuery.parseJSON(result);
                        if(res.status == 'success')
                        {
                            var base_url = "<?php echo base_url(); ?>";
                            var random_number = $("#random_number").val();
                            if($("#is_seller").val() == 1) {
                                window.location.replace(base_url+'borrower-information/seller/'+random_number);
                            } else {
                                window.location.replace(base_url+'borrower-information/'+random_number);
                            }
                            
                        }
                        else if(res.status == 'error')
                        {
                            $('#code_error_msg').html(res.message);
                            $('#code_error_msg').show().delay(5000).fadeOut();
                        }
                        
                    },
                    error:function(){
                        alert('Something went wrong');
                    },
                });
            }
        }
    </script>
</body>
</html>