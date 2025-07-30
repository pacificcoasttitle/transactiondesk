<style>
.ui-menu .ui-menu-item-wrapper {
    font-size : 13px;
}

.ui-autocomplete {
    max-height: 300px !important;
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
                <h1 class="h3 text-gray-800">CLP/Proposed User</h1>
            </div>
            <div class="col-sm-6">
                <a href="<?php echo base_url() . 'order/admin/cpl-proposed-users'; ?>" class="btn btn-info btn-icon-split float-right mr-2">
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
                        <h6 class="m-0 font-weight-bold text-primary">Edit CLP/Proposed User</h6>
                    </div>
                    <div class="card-body">
                        <form id="add-new-master-user" method="POST">
                            <div class="form-group">
                                <label for="first_name" class="col-sm-2 col-form-label">First Name<span class="required"> *</span></label>
                                <div class="col-sm-6">
                                    <?php $first_name = set_value('first_name') ? set_value('first_name') : $cpl_proposed_user_info['first_name'];?>
                                    <input type="text" class="form-control" name="first_name" id="first_name" value="<?php echo $first_name; ?>" class="form-control" placeholder="First Name">
                                    <?php if (!empty($first_name_error_msg)) {?>
                                        <span class="error"><?php echo $first_name_error_msg; ?></span>
                                    <?php }?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="last_name" class="col-sm-2 col-form-label">Last Name<span class="required"> *</span></label>
                                <div class="col-sm-6">
                                    <?php $last_name = set_value('last_name') ? set_value('last_name') : $cpl_proposed_user_info['last_name'];?>
                                    <input type="text" class="form-control" name="last_name" id="last_name" value="<?php echo $last_name; ?>" class="form-control" placeholder="Last Name">
                                    <?php if (!empty($last_name_error_msg)) {?>
                                        <span class="error"><?php echo $last_name_error_msg; ?></span>
                                    <?php }?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="email_address" class="col-sm-2 col-form-label">Email Address<span class="required"> *</span></label>
                                <div class="col-sm-6">
                                    <?php $email_address = set_value('email_address') ? set_value('email_address') : $cpl_proposed_user_info['email_address'];?>
                                    <input type="email" value="<?php echo $email_address; ?>" class="form-control" name="email_address" id="email_address" class="form-control" placeholder="Email Address">
                                    <?php if (!empty($email_address_error_msg)) {?>
                                        <span class="error"><?php echo $email_address_error_msg; ?></span>
                                    <?php }?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="telephone_no" class="col-sm-2 col-form-label">Telephone</label>
                                <div class="col-sm-6">
                                    <?php $telephone_no = set_value('telephone_no') ? set_value('telephone_no') : $cpl_proposed_user_info['telephone_no'];?>
                                    <input type="text" value="<?php echo $telephone_no; ?>" class="form-control" name="telephone_no" id="telephone_no" class="form-control" placeholder="Telephone">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="company" class="col-sm-2 col-form-label">Company<span class="required"> *</span></label>
                                <div class="col-sm-6">
                                    <?php $company = set_value('company') ? set_value('company') : $cpl_proposed_user_info['company_name'];?>
                                    <input type="text" class="form-control" name="company" id="company" value="<?php echo $company; ?>" class="form-control" placeholder="Company">
                                    <?php if (!empty($company_error_msg)) {?>
                                        <span class="error"><?php echo $company_error_msg; ?></span>
                                    <?php }?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="address" class="col-sm-2 col-form-label">Address<span class="required"> *</span></label>
                                <div class="col-sm-6">
                                    <?php $address = set_value('address') ? set_value('address') : $cpl_proposed_user_info['street_address'];?>
                                    <input type="text" class="form-control" name="address" id="address" value="<?php echo $address; ?>" class="form-control" placeholder="Address">
                                    <?php if (!empty($address_error_msg)) {?>
                                        <span class="error"><?php echo $address_error_msg; ?></span>
                                    <?php }?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="city" class="col-sm-2 col-form-label">City<span class="required"> *</span></label>
                                <div class="col-sm-6">
                                    <?php $city = set_value('city') ? set_value('city') : $cpl_proposed_user_info['city'];?>
                                    <input type="text" class="form-control" name="city" id="city" value="<?php echo $city; ?>" class="form-control" placeholder="City">
                                    <?php if (!empty($city_error_msg)) {?>
                                        <span class="error"><?php echo $city_error_msg; ?></span>
                                    <?php }?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="state" class="col-sm-2 col-form-label">State<span class="required"> *</span></label>
                                <div class="col-sm-6">
                                    <?php $state = set_value('state') ? set_value('state') : $cpl_proposed_user_info['state'];?>
                                    <input type="text" class="form-control" name="state" id="state" value="<?php echo $state; ?>" class="form-control" placeholder="State">
                                    <?php if (!empty($state_error_msg)) {?>
                                        <span class="error"><?php echo $state_error_msg; ?></span>
                                    <?php }?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="zipcode" class="col-sm-2 col-form-label">Zipcode<span class="required"> *</span></label>
                                <div class="col-sm-6">
                                    <?php $zipcode = set_value('zipcode') ? set_value('zipcode') : $cpl_proposed_user_info['zip_code'];?>
                                    <input type="text" class="form-control" name="zipcode" id="zipcode" value="<?php echo $zipcode; ?>" class="form-control" placeholder="Zipcode">
                                    <?php if (!empty($zipcode_error_msg)) {?>
                                        <span class="error"><?php echo $zipcode_error_msg; ?></span>
                                    <?php }?>
                                </div>
                            </div>

                            <?php $partner_id = set_value('partner_id') ? set_value('partner_id') : $cpl_proposed_user_info['partner_id'];?>
                            <input type="hidden" name="partner_id" id="partner_id" value="<?php echo $partner_id; ?>">

                            <div class="form-group">
                                <div class="col-sm-6">
                                    <button type="submit" class="btn btn-info btn-icon-split">
                                        <span class="icon text-white-50">
                                            <i class="fas fa-save"></i>
                                        </span>
                                        <span class="text">Approve</span>
                                    </button>

                                    <a href="<?php echo base_url() . 'order/admin/reject-cpl-proposed-user/' . $cpl_proposed_user_info['id']; ?>" id="reject" name="reject" class="btn btn-danger btn-icon-split">
                                        <span class="icon text-white-50">
                                            <i class="fa fa-ban"></i>
                                        </span>
                                        <span class="text">Reject</span>
                                    </a>

                                    <a href="<?php echo base_url() . 'order/admin/cpl-proposed-users'; ?>" class="btn btn-secondary btn-icon-split">
                                        <span class="icon text-white-50">
                                            <i class="fas fa-arrow-left"></i>
                                        </span>
                                        <span class="text">Cancel</span>
                                    </a>
                                </div>
                            </div>

                            <!-- <div class="pull-right">
                                <button type="submit" class="btn btn-secondary">Approve</button>
                                <a href="<?php echo base_url() . 'order/admin/reject-cpl-proposed-user/' . $cpl_proposed_user_info['id']; ?>" id="reject" name="reject" class="btn btn-secondary">Reject</a>
                                <a href="<?php echo base_url() . 'order/admin/cpl-proposed-users'; ?>" id="cancel" name="cancel" class="btn btn-secondary">Cancel</a>
                            </div> -->
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/frontend/css/smart-forms.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/frontend/css/jquery-ui.css">


<script src="<?php echo base_url(); ?>assets/libs/jquery-1.12.4.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/frontend/js/jquery-ui.min.js"></script>

<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $("#company").autocomplete({
	        source: function(request, response) {
	            $.ajax({
	                url: base_url+"admin/order/home/get_company_list",
	                data: {
						term : request.term
	                },
	                type: "POST",
	                dataType: "json",
	                success: function (data) {
						if (data.length > 0) {
                            response($.map(data, function (item) {
                                return item;
                            }))
                        } else {
                            response([{ label: 'No results found.', val: -1}]);
                        }
					}
	            });
			},
			delay: 0,
			minLength: 3,
	        select: function( event, ui ) {
	            event.preventDefault();
	            $("#company").val(ui.item.partner_name);
                $("#partner_id").val(ui.item.partner_id);
                $("#address").val(ui.item.address1);
                $("#city").val(ui.item.city);
                $("#state").val(ui.item.state);
                $("#zipcode").val(ui.item.zip);
	        },
	        change: function( event, ui ) {
	            if (ui.item == null) {
	            	$("#company").parent().removeClass('state-success').addClass('state-error');
	            }
	        }
	    });
    });
</script>

