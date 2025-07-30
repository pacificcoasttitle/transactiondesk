<div class="content">
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-sm-6">
                <h1 class="h3 text-gray-800">Users -> Employees</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Edit Employee</h6>
                    </div>
                    <div class="card-body">
                        <form method="post" name="user_form" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Email address<span class="required"> *</span></label>
                                        <input type="email" value="<?php echo $userInfo['email'];?>" class="form-control" placeholder="Email" name="email" id="email" required="required" disabled>
                                    </div>
                                    <?php if(!empty($email_error_msg)){ ?>       
                                        <div class="typography-line text-danger">
                                            <?php echo $email_error_msg;?>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <input type="hidden" name="email" id="email" value="<?php echo $userInfo['email'];?>">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">PCT Order Email address</label>
                                        <input type="text" value="<?php echo $userInfo['pct_order_email'];?>" class="form-control" placeholder="Email" name="pct_order_email" id="pct_order_email">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Password</label>
                                        <input type="password" value="" class="form-control" placeholder="Password" name="password" id="password">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="first_name">First Name<span class="required"> *</span></label>
                                        <input type="text" value="<?php echo $userInfo['first_name'];?>" class="form-control" placeholder="First Name" name="first_name" id="first_name" value="" required="required">
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
                                        <input type="text" value="<?php echo $userInfo['last_name'];?>" class="form-control" placeholder="Last Name" name="last_name" id="last_name" value="" required="required">
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
                                        <label>Mobile Number<span class="required"> *</span></label>
                                        <input type="text" value="<?php echo $userInfo['cell_phone'];?>" class="form-control" placeholder="Mobile Number" name="cell_phone" id="cell_phone" value="" required="required">
                                    </div>
                                    <?php if(!empty($cell_phone_error_msg)){ ?>  
                                        <div class="typography-line text-danger">
                                            <?php echo $cell_phone_error_msg;?>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
							<div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Birth Date<span class="required"> *</span></label>
                                        <input type="text" class="form-control" value="<?php if(strtotime($userInfo['birth_date'])) {echo date('m/d/Y',strtotime($userInfo['birth_date']));}?>" placeholder="Birth Date" name="birth_date" id="birth_date" value="" required="required">
                                    </div>
                                    <?php if(!empty($birth_date_error_msg)){ ?>  
                                        <div class="typography-line text-danger">
                                            <?php echo $birth_date_error_msg;?>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Employee Id<span class="required"> *</span></label>
                                        <input type="text" class="form-control" placeholder="Employee Id" name="employee_id" id="employee_id" value="<?php echo !empty($userInfo['employee_id']) ? $userInfo['employee_id'] : '';?>" required="required">
                                    </div>
                                    <?php if(!empty($employee_id_error_msg)){ ?>  
                                        <div class="typography-line text-danger">
                                            <?php echo $employee_id_error_msg;?>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Position<span class="required"> *</span></label>
                                        <select name="position" id="position" class="form-control" required>
                                            <option value="">Select Position</option>
                                            <?php foreach($hrPositions as $hrPosition) {?>
                                                <option value="<?php echo $hrPosition['id'];?>" <?php echo $userInfo['position_id'] == $hrPosition['id'] ? 'selected' : '';?>><?php echo $hrPosition['name'];?></option>
                                            <?php } ?>
                                        </select> 
                                    </div>
                                    <?php if(!empty($position_error_msg)){ ?>  
                                        <div class="typography-line text-danger">
                                            <?php echo $position_error_msg;?>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Hire Date<span class="required"> *</span></label>
                                        <input type="text" class="form-control" value="<?php echo date("m/d/Y", strtotime($userInfo['hire_date']));?>" placeholder="Hire Date" name="hire_date" id="hire_date" value="" required="required">
                                    </div>
                                    <input type="hidden" id="hire_date_val" name="hire_date_val" value="<?php echo date("m/d/Y", strtotime($userInfo['hire_date']));?>">
                                    <?php if(!empty($hire_date_error_msg)){ ?>  
                                        <div class="typography-line text-danger">
                                            <?php echo $hire_date_error_msg;?>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label style="width:100%;">User Type<span class="required"> *</span></label>
                                        <?php foreach($userTypes as $userType) { 
                                                if ($userType['id'] != 1 && $userType['id'] !=2 ) { ?>
                                                    <input style="width:15px;height:15px;" <?php echo $userInfo['user_type_id'] == $userType['id'] ? 'checked' : '';?> class="" type="radio" name="user_type" value="<?php echo $userType['id'];?>" required>&nbsp;<?php echo $userType['name'];?>&nbsp;
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
                                    <div class="form-group">
                                        <label>Department</label>
                                        <select name="department" id="department" class="form-control">
                                            <option value="">Select Department</option>
                                            <?php foreach($departments as $department) {?>
                                                <option value="<?php echo $department['id'];?>" <?php echo $userInfo['department_id'] == $department['id'] ? 'selected' : '';?>><?php echo $department['name'];?></option>
                                            <?php } ?>
                                        </select> 
                                    </div>
                                    <?php if(!empty($department_error_msg)){ ?>  
                                        <div class="typography-line text-danger">
                                            <?php echo $department_error_msg;?>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Branch<span class="required"> *</span></label>
                                        <select name="branch" id="branch" class="form-control" required>
                                            <option value="">Select Branch</option>
                                            <?php foreach($branches as $branches) {?>
                                                <option value="<?php echo $branches->id;?>" <?php echo $userInfo['branch_id'] == $branches->id ? 'selected' : '';?>><?php echo $branches->name;?></option>
                                            <?php } ?>
                                        </select> 
                                    </div>
                                    <?php if(!empty($branch_error_msg)){ ?>  
                                        <div class="typography-line text-danger">
                                            <?php echo $branch_error_msg;?>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Profile Img<span class="required"></span></label>
                                        <input type="file" class="form-control" name="profile_img" id="profile_img" accept="image/*" class="form-control">
                                    </div>
                                    <?php if(!empty($profile_img_error_msg)) { ?>  
                                        <div class="typography-line text-danger">
                                            <?php echo $profile_img_error_msg;?>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="col-md-6">
                                    <?php
                                        if (isset($userInfo['profile_img']) && !empty($userInfo['profile_img'])) {
                                            $img = env('AWS_PATH').'hr/user/'.$userInfo['profile_img'];
                                            
                                        }
                                        if (isset($img) && !empty($img)) { ?>
                                            <img src="<?php echo $img; ?>" width="100" height="100">
                                    <?php } ?>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-info btn-icon-split">
                                <span class="icon text-white-50">
                                    <i class="fas fa-save"></i>
                                </span>
                                <span class="text">Update</span>
                            </button>
                            <a href="<?php echo base_url().'hr/admin/users'; ?>" class="btn btn-secondary btn-icon-split">
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



