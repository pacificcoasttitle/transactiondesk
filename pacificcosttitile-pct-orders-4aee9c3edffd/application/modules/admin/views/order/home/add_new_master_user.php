<style>
.bootstrap-select:not([class*="col-"]):not([class*="form-control"]):not(.input-group-btn) {
    width: -webkit-fill-available;
}
</style>
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
                <h1 class="h3 text-gray-800">New User</h1>
            </div>
            <div class="col-sm-6">
                <a href="<?php echo base_url() . 'order/admin/master-users'; ?>" class="btn btn-info btn-icon-split float-right mr-2">
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
                        <h6 class="m-0 font-weight-bold text-primary">Add New User</h6>
                    </div>
                    <div class="card-body">
                        <form id="add-new-master-user" method="POST">
                            <div class="form-group">
                                <label for="first_name" class="col-sm-2 col-form-label">First Name<span class="required"> *</span></label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="first_name" id="first_name" value="<?php echo set_value('first_name') ?>" class="form-control" placeholder="First Name">
                                    <?php if (!empty($first_name_error_msg)) {?>
                                        <span class="error"><?php echo $first_name_error_msg; ?></span>
                                    <?php }?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="last_name" class="col-sm-2 col-form-label">Last Name<span class="required"> *</span></label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="last_name" id="last_name" value="<?php echo set_value('last_name') ?>" class="form-control" placeholder="Last Name">
                                    <?php if (!empty($last_name_error_msg)) {?>
                                        <span class="error"><?php echo $last_name_error_msg; ?></span>
                                    <?php }?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="email_address" class="col-sm-2 col-form-label">Email Address<span class="required"> *</span></label>
                                <div class="col-sm-6">
                                    <input type="email" value="<?php echo set_value('email_address') ?>" class="form-control" name="email_address" id="email_address" class="form-control" placeholder="Email Address">
                                    <?php if (!empty($email_address_error_msg)) {?>
                                        <span class="error"><?php echo $email_address_error_msg; ?></span>
                                    <?php }?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="telephone_no" class="col-sm-2 col-form-label">Telephone</label>
                                <div class="col-sm-6">
                                    <input type="text" value="<?php echo set_value('telephone_no') ?>" class="form-control" name="telephone_no" id="telephone_no" class="form-control" placeholder="Telephone">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="company" class="col-sm-2 col-form-label">Company<span class="required"> *</span></label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="company" id="company" value="<?php echo set_value('company') ?>" class="form-control" placeholder="Company">
                                    <?php if (!empty($company_error_msg)) {?>
                                        <span class="error"><?php echo $company_error_msg; ?></span>
                                    <?php }?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="address" class="col-sm-2 col-form-label">Address<span class="required"> *</span></label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="address" id="address" value="<?php echo set_value('address') ?>" class="form-control" placeholder="Address">
                                    <?php if (!empty($address_error_msg)) {?>
                                        <span class="error"><?php echo $address_error_msg; ?></span>
                                    <?php }?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="city" class="col-sm-2 col-form-label">City<span class="required"> *</span></label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="city" id="city" value="<?php echo set_value('city') ?>" class="form-control" placeholder="City">
                                    <?php if (!empty($city_error_msg)) {?>
                                        <span class="error"><?php echo $city_error_msg; ?></span>
                                    <?php }?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="state" class="col-sm-2 col-form-label">State<span class="required"> *</span></label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="state" id="state" value="<?php echo set_value('state') ?>" class="form-control" placeholder="State">
                                    <?php if (!empty($state_error_msg)) {?>
                                        <span class="error"><?php echo $state_error_msg; ?></span>
                                    <?php }?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="zipcode" class="col-sm-2 col-form-label">Zipcode<span class="required"> *</span></label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="zipcode" id="zipcode" value="<?php echo set_value('zipcode') ?>" class="form-control" placeholder="Zipcode">
                                    <?php if (!empty($zipcode_error_msg)) {?>
                                        <span class="error"><?php echo $zipcode_error_msg; ?></span>
                                    <?php }?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="zipcode" class="col-sm-2 col-form-label">Select Company</label>
                                <div class="col-sm-6">
                                    <select name="partner_companies[]"  class="selectpicker" multiple data-live-search="true">
                                        <?php foreach ($companys as $company) {?>
                                            <?php $selected = '';
    if (set_value('partner_companies') && in_array($company['partner_id'], set_value('partner_companies'))) {
        $selected = 'selected';
    }
    ?>
                                            <option <?php echo $selected; ?> value="<?php echo $company['partner_id']; ?>"><?php echo $company['partner_name']; ?></option>
                                        <?php }?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-6">
                                    <button type="submit" class="btn btn-info btn-icon-split">
                                        <span class="icon text-white-50">
                                            <i class="fas fa-save"></i>
                                        </span>
                                        <span class="text">Add</span>
                                    </button>
                                    <!-- <button type="submit" class="btn btn-secondary">Update</button> -->
                                    <a href="<?php echo base_url() . 'order/admin/master-users'; ?>"  class="btn btn-secondary btn-icon-split">
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







