<div class="content">
    <?php if (!empty($success_msg)) {?>
        <div class="col-xs-12">
            <div class="alert alert-success"><?php echo $success_msg; ?></div>
        </div>
    <?php }?>
    <?php if (!empty($error_msg)) {?>
        <div class="col-xs-12">
            <div class="alert alert-danger"><?php echo $error_msg; ?></div>
        </div>
    <?php }?>
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-sm-6">
                <h1 class="h3 text-gray-800"><?=$pageTitle?></h1>
            </div>
            <div class="col-sm-6">
                <a href="<?php echo site_url('order/admin/payoff-users'); ?>" class="btn btn-info btn-icon-split float-right mr-2">
                    <span class="icon text-white-50">
                        <i class="fas fa-arrow-left"></i>
                    </span>
                    <span class="text"> Back </span>
                </a>
            </div>
        </div>
        <div class="row">
                <div class="col-md-12">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Add <?=$pageTitle?></h6>
                        </div>
                        <div class="card-body">
                            <form id="frm-add-title-officer-rep" method="POST">

                                <div class="form-group">
                                    <label for="title_officer_name" class="col-sm-2 col-form-label">First Name<span class="required"> *</span></label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" name="first_name" id="first_name" class="form-control" placeholder="First Name">
                                        <?php if (!empty($first_name_error_msg)) {?>
                                            <span class="error"><?php echo $first_name_error_msg; ?></span>
                                        <?php }?>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="title_officer_name" class="col-sm-2 col-form-label">Last Name<span class="required"> *</span></label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" name="last_name" id="last_name" class="form-control" placeholder="Last Name">
                                        <?php if (!empty($last_name_error_msg)) {?>
                                            <span class="error"><?php echo $last_name_error_msg; ?></span>
                                        <?php }?>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="email_address" class="col-sm-2 col-form-label">Email Address<span class="required"> *</span></label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" name="email_address" id="email_address" class="form-control" placeholder="Email Address">
                                        <?php if (!empty($email_error_msg)) {?>
                                            <span class="error"><?php echo $email_error_msg; ?></span>
                                        <?php }?>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="company_name" class="col-sm-2 col-form-label">Company Name<span class="required"> *</span></label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" name="company_name" id="company_name" class="form-control" placeholder="Enter Company Name">
                                        <?php if (!empty($company_name_error_msg)) {?>
                                            <span class="error"><?php echo $company_name_error_msg; ?></span>
                                        <?php }?>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="address" class="col-sm-2 col-form-label">Address<span class="required"> *</span></label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" name="address" id="address" class="form-control" placeholder="Enter Address">
                                        <?php if (!empty($address_error_msg)) {?>
                                            <span class="error"><?php echo $address_error_msg; ?></span>
                                        <?php }?>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="city" class="col-sm-2 col-form-label">City<span class="required"> *</span></label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" name="city" id="city" class="form-control" placeholder="Enter City">
                                        <?php if (!empty($city_error_msg)) {?>
                                            <span class="error"><?php echo $city_error_msg; ?></span>
                                        <?php }?>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="state" class="col-sm-2 col-form-label">State<span class="required"> *</span></label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" name="state" id="state" class="form-control" placeholder="Enter State">
                                        <?php if (!empty($state_error_msg)) {?>
                                            <span class="error"><?php echo $state_error_msg; ?></span>
                                        <?php }?>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="zip" class="col-sm-2 col-form-label">Zip Code<span class="required"> *</span></label>
                                    <div class="col-sm-6">
                                        <input type="number" class="form-control" name="zip" id="zip" class="form-control" placeholder="Enter Zip">
                                        <?php if (!empty($zip_error_msg)) {?>
                                            <span class="error"><?php echo $zip_error_msg; ?></span>
                                        <?php }?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-6">
                                        <button type="submit" id="update-payoff-users" name="update-payoff-users" class="btn btn-info btn-icon-split">
                                            <span class="icon text-white-50">
                                                <i class="fas fa-save"></i>
                                            </span>
                                            <span class="text">Add</span>
                                        </button>
                                        <a href="<?php echo site_url('order/admin/payoff-users'); ?>" class="btn btn-secondary btn-icon-split">
                                            <span class="icon text-white-50">
                                                <i class="fas fa-arrow-left"></i>
                                            </span>
                                            <span class="text">Cancel</span>
                                        </a>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>