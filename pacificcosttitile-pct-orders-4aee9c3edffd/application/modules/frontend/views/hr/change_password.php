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
                    <div class="form-items">
                        <h3>PCT HR Center</h3>
                        <p>Change Password</p>
                        <form data-parsley-validate="" name="change_password_form" id="change_password_form" method="post"> 
                            <input class="form-control" type="password" name="password" id="password" placeholder="New Password"  data-parsley-required-message="Please enter new password" required>
                            <?php if(!empty($pwd_err_msg)){ ?>         
                                <ul class="parsley-errors-list filled" id="parsley-id-5" aria-hidden="false">
                                    <li class="parsley-required"><?php echo $pwd_err_msg;?></li>
                                </ul>
                            <?php } ?>
                            <input class="form-control" type="password" name="confirm_password" id="confirm_password" data-parsley-trigger="change" placeholder="Confirm Password" data-parsley-equalto="#password" data-parsley-required-message="Please enter confirm password" data-parsley-equalto-message="Confirm password should be same with new password." required>
                            <?php if(!empty($confirm_pwd_err_msg)){ ?>     
                                <ul class="parsley-errors-list filled" id="parsley-id-7" aria-hidden="false">
                                    <li class="parsley-required"><?php echo $confirm_pwd_err_msg;?></li>
                                </ul>
                            <?php } ?>
                            <div class="form-button">
                                <button id="submit" type="submit" class="ibtn">Update</button> 
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