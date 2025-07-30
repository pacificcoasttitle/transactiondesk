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
                <h1 class="h3 text-gray-800"> Escrow Officer </h1>
            </div>
            <div class="col-sm-6">
                <a href="<?php echo base_url() . 'order/admin/escrow-officers'; ?>" class="btn btn-info btn-icon-split float-right mr-2">
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
                        <h6 class="m-0 font-weight-bold text-primary">Add Escrow Officer</h6>
                    </div>
                    <div class="card-body">
                        <form id="frm-add-escrow-officer" method="POST" enctype="multipart/form-data">

                            <div class="form-group">
                                <label for="partner_id" class="col-sm-2 col-form-label">Partner ID<span class="required"> *</span></label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="partner_id" id="partner_id" class="form-control" placeholder="Enter Partner ID" value="">
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
                                <label for="sales_rep_last_name" class="col-sm-2 col-form-label">Partner Name<span class="required"> *</span></label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="partner_name" id="partner_name" class="form-control" placeholder="Enter Partner Name" value="">
                                    <?php if (!empty($partner_name_error_msg)) {?>
                                        <span class="error"><?php echo $partner_name_error_msg; ?></span>
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
                                <label for="zip" class="col-sm-2 col-form-label">Zip<span class="required"> *</span></label>
                                <div class="col-sm-6">
                                    <input type="number" class="form-control" name="zip" id="zip" class="form-control" placeholder="Enter Zip">
                                    <?php if (!empty($zip_error_msg)) {?>
                                        <span class="error"><?php echo $zip_error_msg; ?></span>
                                    <?php }?>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-6">
                                    <button type="submit" id="add-escrow-officer" name="add-escrow-officer" class="btn btn-info btn-icon-split">
                                        <span class="icon text-white-50">
                                            <i class="fas fa-save"></i>
                                        </span>
                                        <span class="text">Add</span>
                                    </button>
                                    <a href="<?php echo site_url('order/admin/escrow-officers'); ?>" class="btn btn-secondary btn-icon-split">
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