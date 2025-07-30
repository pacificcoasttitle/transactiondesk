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
                <h1 class="h3 text-gray-800">Agent</h1>
            </div>
            <div class="col-sm-6">
                <a href="<?php echo base_url() . 'order/admin/agents'; ?>" class="btn btn-info btn-icon-split float-right mr-2">
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
                        <h6 class="m-0 font-weight-bold text-primary">Edit Agent</h6>
                    </div>
                    <div class="card-body">
                        <form id="edit-agent" method="POST">
                            <div class="form-group">
                                <label for="name" class="col-sm-2 col-form-label">Name<span class="required"> *</span></label>
                                <div class="col-sm-6">
                                    <?php
$name = isset($agent_info['name']) ? $agent_info['name'] : '';
?>
                                    <input type="text" class="form-control" name="name" id="name" value="<?php echo $name; ?>" class="form-control" placeholder="Name">

                                    <?php if (!empty($name_error_msg)) {?>
                                        <span class="error"><?php echo $name_error_msg; ?></span>
                                    <?php }?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="email_address" class="col-sm-2 col-form-label">Email Address<span class="required"> *</span></label>
                                <div class="col-sm-6">
                                    <?php
$email_address = isset($agent_info['email_address']) ? $agent_info['email_address'] : '';
?>
                                    <input type="email" value="<?php echo $email_address; ?>" class="form-control" name="email_address" id="email_address" class="form-control" placeholder="Email Address">
                                    <?php if (!empty($email_address_error_msg)) {?>
                                        <span class="error"><?php echo $email_address_error_msg; ?></span>
                                    <?php }?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="telephone_no" class="col-sm-2 col-form-label">Telephone<span class="required"> *</span></label>
                                <div class="col-sm-6">
                                    <?php
$telephone_no = isset($agent_info['telephone_no']) ? $agent_info['telephone_no'] : '';
?>
                                    <input type="text" value="<?php echo $telephone_no; ?>" class="form-control" name="telephone_no" id="telephone_no" class="form-control" placeholder="Telephone">
                                    <?php if (!empty($telephone_no_error_msg)) {?>
                                        <span class="error"><?php echo $telephone_no_error_msg; ?></span>
                                    <?php }?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="company" class="col-sm-2 col-form-label">Company<span class="required"> *</span></label>
                                <div class="col-sm-6">
                                    <?php
// echo "<pre>"; print_r($company_error_msg); exit;
$company = isset($agent_info['company']) ? $agent_info['company'] : '';
?>
                                    <input type="text" class="form-control" name="company" id="company" value="<?php echo $company; ?>" class="form-control" placeholder="Company">

                                    <?php if (!empty($company_error_msg)) {?>
                                        <span class="error"><?php echo $company_error_msg; ?></span>
                                    <?php }?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="address" class="col-sm-2 col-form-label">Address<span class="required"> *</span></label>
                                <div class="col-sm-6">
                                    <?php
// echo "<pre>"; print_r($company_error_msg); exit;
$address = isset($agent_info['address']) ? $agent_info['address'] : '';
?>
                                    <input type="text" class="form-control" name="address" id="address" value="<?php echo $address; ?>" class="form-control" placeholder="Address">

                                    <?php if (!empty($address_error_msg)) {?>
                                        <span class="error"><?php echo $address_error_msg; ?></span>
                                    <?php }?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="city" class="col-sm-2 col-form-label">City<span class="required"> *</span></label>
                                <div class="col-sm-6">
                                    <?php
// echo "<pre>"; print_r($company_error_msg); exit;
$city = isset($agent_info['city']) ? $agent_info['city'] : '';
?>
                                    <input type="text" class="form-control" name="city" id="city" value="<?php echo $city; ?>" class="form-control" placeholder="City">

                                    <?php if (!empty($city_error_msg)) {?>
                                        <span class="error"><?php echo $city_error_msg; ?></span>
                                    <?php }?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="zipcode" class="col-sm-2 col-form-label">Zipcode<span class="required"> *</span></label>
                                <div class="col-sm-6">
                                    <?php
// echo "<pre>"; print_r($company_error_msg); exit;
$zipcode = isset($agent_info['zipcode']) ? $agent_info['zipcode'] : '';
?>
                                    <input type="text" class="form-control" name="zipcode" id="zipcode" value="<?php echo $zipcode; ?>" class="form-control" placeholder="Zipcode">

                                    <?php if (!empty($zipcode_error_msg)) {?>
                                        <span class="error"><?php echo $zipcode_error_msg; ?></span>
                                    <?php }?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="list_unit" class="col-sm-2 col-form-label">List Unit<span class="required"> *</span></label>
                                <div class="col-sm-6">
                                    <?php
// echo "<pre>"; print_r($company_error_msg); exit;
$list_unit = isset($agent_info['list_unit']) ? $agent_info['list_unit'] : '';
?>
                                    <input type="text" class="form-control" name="list_unit" id="list_unit" value="<?php echo $list_unit; ?>" class="form-control" placeholder="List Unit">

                                    <?php if (!empty($list_unit_error_msg)) {?>
                                        <span class="error"><?php echo $list_unit_error_msg; ?></span>
                                    <?php }?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="list_volume" class="col-sm-2 col-form-label">List Volume<span class="required"> *</span></label>
                                <div class="col-sm-6">
                                    <?php
// echo "<pre>"; print_r($company_error_msg); exit;
$list_volume = isset($agent_info['list_volume']) ? $agent_info['list_volume'] : '';
?>
                                    <input type="text" class="form-control" name="list_volume" id="list_volume" value="<?php echo $list_volume; ?>" class="form-control" placeholder="List Volume">

                                    <?php if (!empty($list_volume_error_msg)) {?>
                                        <span class="error"><?php echo $list_volume_error_msg; ?></span>
                                    <?php }?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="selected_revenue" class="col-sm-2 col-form-label">Selected Revenue<span class="required"> *</span></label>
                                <div class="col-sm-6">
                                    <?php
// echo "<pre>"; print_r($company_error_msg); exit;
$selected_revenue = isset($agent_info['selected_revenue']) ? $agent_info['selected_revenue'] : '';
?>
                                    <input type="text" class="form-control" name="selected_revenue" id="selected_revenue" value="<?php echo $selected_revenue; ?>" class="form-control" placeholder="Selected Revenue">

                                    <?php if (!empty($selected_revenue_error_msg)) {?>
                                        <span class="error"><?php echo $selected_revenue_error_msg; ?></span>
                                    <?php }?>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-6">
                                    <button type="submit" class="btn btn-info btn-icon-split">
                                        <span class="icon text-white-50">
                                            <i class="fas fa-save"></i>
                                        </span>
                                        <span class="text">Update</span>
                                    </button>
                                    <!-- <button type="submit" class="btn btn-secondary">Update</button> -->
                                    <a href="<?php echo base_url() . 'order/admin/agents'; ?>" class="btn btn-secondary btn-icon-split">
                                        <span class="icon text-white-50">
                                            <i class="fas fa-arrow-left"></i>
                                        </span>
                                        <span class="text">Cancel</span>
                                    </a>
                                </div>
                                <!-- <a href="<?php echo base_url() . 'order/admin/agents'; ?>" id="cancel" name="cancel" class="btn btn-secondary">Cancel</a> -->
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>