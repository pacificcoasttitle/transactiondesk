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
    <title>Open Order Form | Pacific Coast Title Company</title>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/frontend/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/frontend/css/fontawesome-all.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/frontend/css/iofrm-style.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/frontend/css/iofrm-theme2.css">
    <link rel="stylesheet" type="text/css"  href="<?php echo base_url(); ?>assets/frontend/css/smart-forms.css">
    <style>
        #email_address_php_error, #password_php_error {
            display: none;
            margin-top: 6px;
            padding: 0 3px;
            font-family: Arial, Helvetica, sans-serif;
            font-style: normal;
            line-height: normal;
            color: red;
            font-size: 0.85em;
        }

        #change_pwd_success p {
            margin: 0px 0 12px 0 !important;
            padding: 0px !important;
            font-family: Arial, Helvetica, sans-serif;
            font-style: normal;
            line-height: normal;
            color: #0eef0e;
            text-transform: capitalize;
            font-size: 1em;
        }
       
        .website-logo-inside img {
            width: 250px;
        }
        .form-content .form-button .ibtn:hover {opacity: .6;}
        .form-content .form-button .ibtn {
            border-radius: 6px;
            border: 0;
            padding: 10px 45px;
            background-color: #F26A41;
            color: #fff;
            font-size: 14px;
            font-weight: 700;
            font-family: 'Manrope', sans-serif;
            text-decoration: none;
            cursor: pointer;
            text-transform: uppercase;
            margin-right: 10px;
            outline: none;
            -webkit-transition: all 0.3s ease;
            transition: all 0.3s ease;
            -webkit-box-shadow: 0 0 0 rgba(0, 0, 0, 0.16);
            box-shadow: 0 0 0 rgba(0, 0, 0, 0.16);
        }
        .form-content {
            background: linear-gradient(20deg, #03374f 0%, #02283a 60%, #03374f 100%) !important;
        }
        .title-head {
            color: #fff;
            font-weight: 900;
            letter-spacing: -1px;
        }
        .form-content .form-items {
            max-width: 565px;
        }
    </style>
</head>
<body>
    <div class="form-body">
       <!--<div class="website-logo">
            <a href="index.html">
                <div class="logo">
                    <img class="logo-size" src="<?php echo base_url(); ?>assets/media/general/logo_login.png" alt="">
                </div>
            </a>
        </div> -->
        <div class="row">
            <div class="img-holder">
                <div class="bg"></div>
                <div class="info-holder">

                </div>
            </div>
            <div class="form-holder">
                <div class="form-content">
                    <div class="form-items">
					
					<div class="website-logo-inside">
                            <a href="index.html">
                                <div class="l">
                                    <img class="logo-size" src="<?php echo base_url(); ?>assets/media/general/logo2.png" alt="">
                                </div>
                            </a>
                        </div>
					
                        <p style="margin-bottom: 0px !important;">Welcome To</p>
                        <h1 class="title-head">Transaction Desk</h1>
                        <p>Enter your email below to log in.</p>
                        <div class="page-links">
                            <a href="login2.html" class="active">Log in</a>
                            <!-- <a href="register2.html">Register</a> -->
                        </div>
                        <form method="POST" action="<?php echo base_url();?>do_login" id="login-form" enctype="multipart/form-data">

                            <?php if(isset($change_password_error_msg) && !empty($change_password_error_msg)) {?>
                                <span id="change_password_error_msg" class="">
                                    <p><?php echo $change_password_error_msg;?></p>
                                </span>
                            <?php } ?>

                            <?php if(isset($change_pwd_success) && !empty($change_pwd_success)) {?>
                                <span id="change_pwd_success" class="">
                                    <p><?php echo $change_pwd_success;?></p>
                                </span>
                            <?php } ?>

                            <label class="field state-error" style="display:block;">
                                <input class="form-control gui-input" type="email" name="email_address" id="email_address" placeholder="E-mail Address">
                            </label>
                            
                            <span id="email_address_php_error" class=""></span>

                            <label class="field state-error" style="display:none;" id="password_container">
                                <input class="form-control gui-input" type="password" name="pwd" id="pwd" placeholder="Password">
                            </label>
                            
                            <span id="password_php_error" class=""></span>
                            
                            <input type="hidden" id="is_password_field_show" name="is_password_field_show" value="0">
                            
                            <div class="form-button">
                                <button id="submit" type="submit" class="ibtn">Log in</button>
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
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/frontend/js/smart-form.js?random=<?php echo uniqid(); ?>"></script>   
    <script type="text/javascript">
        var base_url = '<?php echo base_url(); ?>';
    </script>
</body>
</html>