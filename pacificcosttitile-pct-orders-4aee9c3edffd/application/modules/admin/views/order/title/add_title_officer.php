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
                <h1 class="h3 text-gray-800">Title Officer</h1>
            </div>
            <div class="col-sm-6">
                <a href="<?php echo site_url('order/admin/title-officers'); ?>" class="btn btn-info btn-icon-split float-right mr-2">
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
                            <h6 class="m-0 font-weight-bold text-primary">Add Title Officer</h6>
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
                                    <label for="telephone" class="col-sm-2 col-form-label">Phone Number<span class="required"> *</span></label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" name="telephone" id="telephone" class="form-control" placeholder="Phone Number">
                                        <?php if (!empty($phone_error_msg)) {?>
                                            <span class="error"><?php echo $phone_error_msg; ?></span>
                                        <?php }?>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="partner_id" class="col-sm-2 col-form-label">Partner Id<span class="required"> *</span></label>
                                    <div class="col-sm-6">
                                        <input type="number" class="form-control" name="partner_id" id="partner_id" class="form-control" placeholder="Partner Id">
                                        <?php if (!empty($partner_id_error_msg)) {?>
                                            <span class="error"><?php echo $partner_id_error_msg; ?></span>
                                        <?php }?>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="partner_type_id" class="col-sm-2 col-form-label">Partner Type Id<span class="required"> *</span></label>
                                    <div class="col-sm-6">
                                        <input type="number" class="form-control" name="partner_type_id" id="partner_type_id" class="form-control" placeholder="Partner Type Id">
                                        <?php if (!empty($partner_type_id_error_msg)) {?>
                                            <span class="error"><?php echo $partner_type_id_error_msg; ?></span>
                                        <?php }?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-6">
                                        <button type="submit" id="add-title-officer" name="add-title-officer" class="btn btn-info btn-icon-split">
                                            <span class="icon text-white-50">
                                                <i class="fas fa-save"></i>
                                            </span>
                                            <span class="text">Add</span>
                                        </button>
                                        <!-- <button type="submit" class="btn btn-secondary">Update</button> -->
                                        <a href="<?php echo site_url('order/admin/title-officers'); ?>" class="btn btn-secondary btn-icon-split">
                                            <span class="icon text-white-50">
                                                <i class="fas fa-arrow-left"></i>
                                            </span>
                                            <span class="text">Cancel</span>
                                        </a>
                                    </div>
                                </div>

                                <!-- <div class="pull-right">
                                    <button type="submit" id="add-title-officer" name="add-title-officer" class="btn btn-secondary">Add</button>
                                    <a href="<?php echo site_url('order/admin/title-officers'); ?>" id="cancel" name="cancel" class="btn btn-secondary">Cancel</a>
                                </div> -->
                            </form>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>