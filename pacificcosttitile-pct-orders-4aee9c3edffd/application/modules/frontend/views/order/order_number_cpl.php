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
    <link rel="stylesheet" href="<?php echo base_url(); ?>/assets/css/theme.css">
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
            font-size: 16px;
        }
        #page-preloader {
            background: none !important;
        }

        .radio {
            border-radius: 20px !important;
            position: relative !important;
            background: white !important;
            display: inline-block !important;
            border: 3px solid #B5C1C7 !important;
            height: 21px !important;
            width: 21px !important;
            top: 5px !important;
            margin: 0px 10px !important;
            background: none !important;
            left: 0 !important;
        }

        .radio:before {
            content:'';
            display:block;
            width:60%;
            height:60%;
            margin: 20% auto;    
            border-radius:50%;    
        }
        .radio:checked:before {
            background: #d35411 !important;
        }
        .form-content {
            background: linear-gradient(20deg, #03374f 0%, #02283a 60%, #03374f 100%) !important;
        }
        .website-logo-inside img {
            width: 250px;
        }

        .title-head {
            color: #fff;
            font-weight: 900;
            letter-spacing: -1px;
        }
    </style>
</head>
<body>
    <div id="page-preloader"><span class="spinner border-t_second_b border-t_prim_a"></span></div>  
    <div class="form-body" id="generic_cpl">
        <div class="row">
            <div class="img-holder">
                <div class="bg borrower-login"></div>
                <div class="info-holder"></div>
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
						<h2 class="title-head">PCT Transaction Desk<!--  for CPL --></h2>
						<p></p>
                        <p>Enter order number below and choose your option. <!-- to generate CPL -->.</p>
                        <div class="page-links">
                            <a href="" class="active">Order #</a>
                        </div>
                        <form method="POST" action="" id="order-number-login-form" enctype="multipart/form-data">
                            <label class="field" style="display:block;">
                                <input class="form-control gui-input" type="text" name="order_number" id="order_number" placeholder="Enter Order Number">
                            </label>
                            <span id="order_number_php_error" class="error" style="display: none;"></span>
                            
                            <div class="frm-row page-links">
                                <div class="section colm colm12">
                                    <label class="field prepend-icon">
                                        <input class="radio" type="radio" name="actions" id="get_fees" value="get_fees" checked="checked">Get Fees  
                                        <input class="radio" type="radio" name="actions" id="get_cpl" value="get_cpl">Get CPL
                                        <input class="radio" type="radio" name="actions" id="get_proposed" value="get_proposed">Get Proposed
                                        <input class="radio" type="radio" name="actions" id="get_policy" value="get_policy">Get Policy
                                        <input class="radio" type="radio" name="actions" id="get_netsheet" value="get_netsheet">Get Netsheet
                                    </label>
                                </div>
                            </div>

                            <div class="form-button">
                                <button id="submit" type="submit" class="ibtn">Submit</button>
                            </div>
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


    <script type="text/javascript">
        $(document).ready(function () {
            var $preloader = $('#page-preloader'),
            $spinner   = $preloader.find('.spinner-loader');
            $spinner.fadeOut();
            $preloader.delay(50).fadeOut('slow');

            $( "#order-number-login-form" ).validate({
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
                    order_number: {
                        required: true
                    },
                    actions: {
                        required: true
                    }
                },
                /* @validation error messages 
                ---------------------------------------------- */
                messages:{
                    order_number: {
                        required: 'Please enter the order number'
                    },
                    actions: {
                        required: 'Please select an action'
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
                        error.insertAfter(element.after());
                    }
                   $('#order_number_php_error').hide();
                },
                /* @ajax form submition 
                ---------------------------------------------------- */
                submitHandler:function(form) {
                    $('#page-preloader').css('display', 'block');
                    $('#generic_cpl').css('opacity', '0.5');
                    $.ajax({
                        url: '<?php echo base_url(); ?>generic-landing-page',
                        type: "POST",
                        data: {
                            order_number: $("#order_number").val()
                        },
                        success: function(result) {
                            var res = jQuery.parseJSON(result);
                            var action= $('input[name="actions"]:checked').val();
                            if (res.status == 'success') {
                                var base_url = "<?php echo base_url(); ?>";
                                if(action == 'get_cpl')
                                {
                                    window.location.replace(base_url+'generate-cpl/'+res.random_number);
                                }
                                else if(action == 'get_proposed')
                                {
                                    window.location.replace(base_url+'proposed-insured/'+res.random_number);
                                }
                                else if(action == 'get_policy')
                                {
                                    window.location.replace(base_url+'policy/'+res.random_number);
                                }
                                else if(action == 'get_netsheet')
                                {
                                    window.location.replace(base_url+'get-netsheet/'+res.random_number);
                                }
                                else
                                {
                                    window.location.replace(base_url+'generate-fees/'+res.random_number);
                                }
                                
                            } else if(res.status == 'error') {
                                $('#page-preloader').css('display', 'none');
                                $('#generic_cpl').css('opacity', '1');
                                $('#order_number_php_error').html(res.order_number_php_error);
                                $('#order_number_php_error').show();
                            }
                        },
                        error:function(){
                            alert('Something went wrong');
                        },
                    });
                }
            });
        });
    </script>
</body>
</html>