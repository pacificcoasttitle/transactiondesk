<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HR Center - Pacific Coast Title Company</title>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/frontend/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/frontend/hr/css/iofrm-style.css">
    <style>
        ul {
            list-style: none;
            color: red;
            padding: 0px;
        }
		.form-body.without-side .form-content .form-items.form-items-login {
			background: none;
    		box-shadow: none;
			margin-top: 70px;
		}
    </style>
</head>

<body>
    <div class="form-body without-side">
        <div class="website-logo">
            <a href="index.html">
                <div class="logo">
                    <img class="logo-size" src="<?php echo base_url(); ?>assets/media/hr/logo2.png" alt="">
                </div>
            </a>
        </div>
        <div class="row">
            <div class="img-holder">
                <div class="bg"></div>
                <div class="info-holder">
                    <img src="images/graphic3.svg" alt="">
                </div>
            </div>
            <div class="form-holder">
                <div class="form-content">
                    <div class="form-items form-items-login">
						<div class="text-center">
							<h3 class="text-center">HR DESK</h3>
							<p class="text-center">Login to Access Your Account</p>
						</div>
						<?php if($this->session->flashdata('success')): ?>
						<div class="alert alert-success" role="alert"><?php echo $this->session->flashdata('success');?></div>
						<?php
						endif;
						?>
                        <form data-parsley-validate="" name="login_form" id="login_form" method="post"> 
                            <input class="form-control" type="email" name="email" id="email" placeholder="E-mail Address" data-parsley-trigger="change" data-parsley-required-message="Please enter email address" required>
                            <?php if(!empty($email_error_msg)){ ?>         
                                <ul class="parsley-errors-list filled" id="parsley-id-5" aria-hidden="false">
                                    <li class="parsley-required"><?php echo $email_error_msg;?></li>
                                </ul>
                            <?php } ?>
                            <input class="form-control" type="password" name="password" id="password" placeholder="Password" data-parsley-required-message="Please enter password" required>
                            <?php if(!empty($password_error_msg)){ ?>     
                                <ul class="parsley-errors-list filled" id="parsley-id-7" aria-hidden="false">
                                    <li class="parsley-required"><?php echo $password_error_msg;?></li>
                                </ul>
                            <?php } ?>
                            <div class="form-button">
                                <button id="submit" type="submit" class="ibtn">Login</button> 
                                <span style="color:#fff">&nbsp | &nbsp</span> <a href="<?php echo base_url('hr/forgot-password'); ?>">Forgot password?</a>
                            </div>
                        </form> 
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="<?php echo base_url(); ?>assets/frontend/js/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/frontend/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/frontend/js/parsley.min.js"></script>
    
</body>

</html>
