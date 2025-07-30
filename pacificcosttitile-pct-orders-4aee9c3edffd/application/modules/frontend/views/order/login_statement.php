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
					
                        <h3>Let us know who you are...</h3>
                        <p>Enter your email below to log in. <br> No password is needed.</p>
                        <div class="page-links">
                            <a href="login2.html" class="active">Log in</a>
                            <!-- <a href="register2.html">Register</a> -->
                        </div>
                        <form method="POST" action="" id="login-form" enctype="multipart/form-data">
                            <label class="field state-error" style="display:block;">
                                <input class="form-control gui-input" type="email" name="email_address" id="email_address" placeholder="E-mail Address">
                            </label>
                            
                            <span id="email_address_php_error" class=""></span>
                            
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
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/frontend/js/smart-form.js"></script>   
    <script type="text/javascript">
        var base_url = '<?php echo base_url(); ?>';
    </script>
</body>
</html>