<div class="content">
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-sm-6">
                <h1 class="h3 text-gray-800">Users -> Admin Users</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Edit Admin User</h6>
                    </div>
                    <div class="card-body">
                        <form method="post" name="admin_user_form" >
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Email address<span class="required"> *</span></label>
                                        <input type="email" class="form-control" placeholder="Email" name="email" id="email" value="<?php echo $adminUserInfo['email'];?>" disabled="disabled" required="required">
                                    </div>
                                    <?php if(!empty($email_error_msg)){ ?>       
                                        <div class="typography-line text-danger">
                                            <?php echo $email_error_msg;?>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="first_name">First Name<span class="required"> *</span></label>
                                        <input type="text" class="form-control" placeholder="First Name" name="first_name" id="first_name" value="<?php echo $adminUserInfo['first_name'];?>" required="required">
                                    </div>
                                    <?php if(!empty($first_name_error_msg)){ ?>  
                                        <div class="typography-line text-danger">
                                           <?php echo $first_name_error_msg;?>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Last Name<span class="required"> *</span></label>
                                        <input type="text" class="form-control" placeholder="Last Name" name="last_name" id="last_name" value="<?php echo $adminUserInfo['last_name'];?>" required="required">
                                    </div>
                                    <?php if(!empty($last_name_error_msg)){ ?>  
                                        <div class="typography-line text-danger">
                                            <?php echo $last_name_error_msg;?>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Password<span class="required"> </span></label>
                                        <input type="password" class="form-control" placeholder="Password" name="password" id="password" value="">
                                    </div>
                                    <?php if(!empty($password_error_msg)){ ?>  
                                        <div class="typography-line text-danger">
                                            <?php echo $password_error_msg;?>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label style="width:100%;">User Type<span class="required"> *</span></label>
                                        <?php foreach($userTypes as $userType) { 
                                                if ($userType['id'] == 1 || $userType['id'] ==2 ) { ?>
                                                    <input style="width:15px;height:15px;" <?php echo $adminUserInfo['user_type_id'] == $userType['id'] ? 'checked' : '';?> class="" type="radio" name="user_type" value="<?php echo $userType['id'];?>" required>&nbsp;<?php echo $userType['name'];?>&nbsp;
                                                <?php }
                                        } ?>
                                    </div>
                                    <?php if(!empty($user_type_error_msg)){ ?>  
                                        <div class="typography-line text-danger">
                                            <?php echo $user_type_error_msg;?>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
									<div class="form-check form-group">
										<input type="checkbox" class="form-check-input" id="status" name="status" value="<?php echo $adminUserInfo['status'];?>" <?php echo $adminUserInfo['status'] == 1 ? 'checked' : '';?>>
										<label class="form-check-label" for="status">Active</label>
									</div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-info btn-icon-split">
                                <span class="icon text-white-50">
                                    <i class="fas fa-save"></i>
                                </span>
                                <span class="text">Update</span>
                                
                            </button>
                            <a href="<?php echo base_url().'hr/admin/admin-users'; ?>" class="btn btn-secondary btn-icon-split">
                                <span class="icon text-white-50">
                                    <i class="fas fa-arrow-right"></i>
                                </span>
                                <span class="text">Cancel</span>
                            </a>
                           
                            <div class="clearfix"></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
