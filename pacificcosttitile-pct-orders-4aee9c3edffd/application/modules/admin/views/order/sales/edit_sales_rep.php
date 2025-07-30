<style>
.bootstrap-select:not([class*="col-"]):not([class*="form-control"]):not(.input-group-btn) {
    width: -webkit-fill-available;
}
#accordionEx .card-header a .fa-angle-down {
	display: none;
}
#accordionEx .card-header a.collapsed .fa-angle-up {
	display: none;
}
#accordionEx .card-header a.collapsed .fa-angle-down {
	display: inline-block;
}
.accordion > .card.managerInfoCard {
	overflow: initial;
}
.remove-btn-holder {
	position: absolute;
    right: -20px;
    top: -30px;
}
.remove-btn-holder .threshold-remove-btn {
    border-radius: 50%;
}
.accordion .commission-details .card{
	border-bottom: 1px solid rgba(0,0,0,.125) !important;
	border-bottom-left-radius: 0.25rem !important;
	border-bottom-right-radius: 0.25rem !important;
	margin-bottom:25px;
}

.threshold__amounts .clone-main-div .remove-btn-holder {
	display: none;
}
.commission-details .nav-pills .nav-link.active {
   position: relative;
}

.commission-details .nav-pills .nav-link.active:before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    border-top: 23px solid #fff;
    border-bottom: 23px solid #fff;
    border-left: 29px solid transparent;
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
                <h1 class="h3 text-gray-800">Sales Rep</h1>
            </div>
			<div class="col-sm-6">
                <a href="<?php echo site_url('order/admin/sales-rep'); ?>" class="btn btn-info btn-icon-split float-right mr-2">
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
						<h6 class="m-0 font-weight-bold text-primary">Edit Sales Rep</h6>
					</div>
					<div class="card-body">
						<form id="frm-add-sales-rep" method="POST" enctype="multipart/form-data">

							<div class="accordion md-accordion" id="accordionEx">

								<div class="card mx-auto mt-5 mb-5" style="border-bottom: 1px solid rgba(0, 0, 0, 0.125);">
									<div class="card-header" role="tab" id="salesRepInfoTab">
										<a data-toggle="collapse" style="color: #000000;" data-parent="#accordionEx" href="#salesRepInfo" aria-expanded="true"
										aria-controls="salesRepInfo">
											<h5 class="mb-0 text-primary">
											Sales Rep Info <i class="fas fa-angle-down pull-right"></i><i class="fas fa-angle-up pull-right"></i>
											</h5>
										</a>
									</div>
									<div id="salesRepInfo" class="collapse show" role="tabpanel" aria-labelledby="salesRepInfoTab" data-parent="#accordionEx">
										<div class="card-body">
											<div class="form-group">
												<label for="sales_rep_first_name" class="col-sm-4 col-form-label">First Name<span class="required"> *</span></label>
												<div class="col-sm-6">
													<input type="text" class="form-control" name="sales_rep_first_name" id="sales_rep_first_name" class="form-control" placeholder="Enter Sales Rep. First Name" value="<?php echo isset($sales_rep_info['first_name']) && !empty($sales_rep_info['first_name']) ? $sales_rep_info['first_name'] : '' ?>">
													<?php if (!empty($first_name_error_msg)) {?>
														<span class="error"><?php echo $first_name_error_msg; ?></span>
													<?php }?>
												</div>
											</div>

											<div class="form-group">
												<label for="sales_rep_last_name" class="col-sm-4 col-form-label">Last Name<span class="required"> *</span></label>
												<div class="col-sm-6">
													<input type="text" class="form-control" name="sales_rep_last_name" id="sales_rep_last_name" class="form-control" placeholder="Enter Sales Rep. Last Name" value="<?php echo isset($sales_rep_info['last_name']) && !empty($sales_rep_info['last_name']) ? $sales_rep_info['last_name'] : '' ?>">
													<?php if (!empty($last_name_error_msg)) {?>
														<span class="error"><?php echo $last_name_error_msg; ?></span>
													<?php }?>
												</div>
											</div>

											<div class="form-group">
												<label for="email_address" class="col-sm-4 col-form-label">Email Address<span class="required"> *</span></label>
												<div class="col-sm-6">
													<input type="text" class="form-control" name="email_address" id="email_address" class="form-control" placeholder="Email Address" value="<?php echo isset($sales_rep_info['email_address']) && !empty($sales_rep_info['email_address']) ? $sales_rep_info['email_address'] : '' ?>">
													<?php if (!empty($email_error_msg)) {?>
														<span class="error"><?php echo $email_error_msg; ?></span>
													<?php }?>
												</div>
											</div>

											<div class="form-group">
												<label for="telephone" class="col-sm-4 col-form-label">Phone Number<span class="required"> *</span></label>
												<div class="col-sm-6">
													<input type="text" class="form-control" name="telephone" id="telephone" class="form-control" placeholder="Phone Number" value="<?php echo isset($sales_rep_info['telephone_no']) && !empty($sales_rep_info['telephone_no']) ? $sales_rep_info['telephone_no'] : '' ?>">
													<?php if (!empty($phone_error_msg)) {?>
														<span class="error"><?php echo $phone_error_msg; ?></span>
													<?php }?>
												</div>
											</div>

											<div class="form-group">
												<label for="language" class="col-sm-4 col-form-label">Disable</label>
												<div class="col-sm-1">
													<input <?php echo $sales_rep_info['status'] == 0 ? "checked" : ""; ?>  type="checkbox" class="form-control" name="status" id="status" class="form-control">
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="card mx-auto mt-5 mb-5" style="border-bottom: 1px solid rgba(0, 0, 0, 0.125);">
									<div class="card-header" role="tab" id="reswareInfoTab">
										<a data-toggle="collapse" class="collapsed" style="color: #000000;" data-parent="#accordionEx" href="#reswareInfo"
										aria-controls="reswareInfo">
											<h5 class="mb-0 text-primary">
											Resware <i class="fas fa-angle-down pull-right"></i><i class="fas fa-angle-up pull-right"></i>
											</h5>
										</a>
									</div>
									<div id="reswareInfo" class="collapse" role="tabpanel" aria-labelledby="reswareInfoTab" data-parent="#accordionEx">
										<div class="card-body">
											<div class="form-group">
												<label for="partner_id" class="col-sm-4 col-form-label">Partner Id<span class="required"> *</span></label>
												<div class="col-sm-6">
													<input type="text" class="form-control" name="partner_id" id="partner_id" class="form-control" placeholder="Partner Id" value="<?php echo isset($sales_rep_info['partner_id']) && !empty($sales_rep_info['partner_id']) ? $sales_rep_info['partner_id'] : '' ?>">
													<?php if (!empty($partner_id_error_msg)) {?>
														<span class="error"><?php echo $partner_id_error_msg; ?></span>
													<?php }?>
												</div>
											</div>

											<div class="form-group">
												<label for="partner_type_id" class="col-sm-4 col-form-label">Partner Type Id<span class="required"> *</span></label>
												<div class="col-sm-6">
													<input type="text" class="form-control" name="partner_type_id" id="partner_type_id" class="form-control" placeholder="Partner Type Id" value="<?php echo isset($sales_rep_info['partner_type_id']) && !empty($sales_rep_info['partner_type_id']) ? $sales_rep_info['partner_type_id'] : '' ?>">
													<?php if (!empty($partner_type_id_error_msg)) {?>
														<span class="error"><?php echo $partner_type_id_error_msg; ?></span>
													<?php }?>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div class="card mx-auto mt-5 mb-5" style="border-bottom: 1px solid rgba(0, 0, 0, 0.125);">
									<div class="card-header" role="tab" id="productionTab">
										<a data-toggle="collapse" class="collapsed" style="color: #000000;" data-parent="#accordionEx" href="#production"
										aria-controls="production">
											<h5 class="mb-0 text-primary">
											Production <i class="fas fa-angle-down pull-right"></i><i class="fas fa-angle-up pull-right"></i>
											</h5>
										</a>
									</div>
									<div id="production" class="collapse" role="tabpanel" aria-labelledby="productionTab" data-parent="#accordionEx">
										<div class="card-body">
											<div class="form-group">
												<label for="sales_rep_no_of_open_orders" class="col-sm-4 col-form-label">Number of Open Orders<span class="required"></span></label>
												<div class="col-sm-6">
													<input type="text" class="form-control" name="sales_rep_no_of_open_orders" id="sales_rep_no_of_open_orders" value="<?php echo isset($sales_rep_info['sales_rep_no_of_open_orders']) && !empty($sales_rep_info['sales_rep_no_of_open_orders']) ? $sales_rep_info['sales_rep_no_of_open_orders'] : '' ?>" class="form-control">
													<?php if (!empty($sales_rep_no_of_open_orders_error_msg)) {?>
														<span class="error"><?php echo $sales_rep_no_of_open_orders_error_msg; ?></span>
													<?php }?>
												</div>
											</div>

											<div class="form-group">
												<label for="sales_rep_no_of_close_orders" class="col-sm-4 col-form-label">Number of Closed Orders<span class="required"></span></label>
												<div class="col-sm-6">
													<input type="text" class="form-control" name="sales_rep_no_of_close_orders" id="sales_rep_no_of_close_orders" value="<?php echo isset($sales_rep_info['sales_rep_no_of_close_orders']) && !empty($sales_rep_info['sales_rep_no_of_close_orders']) ? $sales_rep_info['sales_rep_no_of_close_orders'] : '' ?>" class="form-control">
													<?php if (!empty($sales_rep_no_of_close_orders_error_msg)) {?>
														<span class="error"><?php echo $sales_rep_no_of_close_orders_error_msg; ?></span>
													<?php }?>
												</div>
											</div>

											<div class="form-group">
												<label for="sales_rep_premium" class="col-sm-4 col-form-label">Revenue<span class="required"></span></label>
												<div class="col-sm-6">
													<input type="text" class="form-control" name="sales_rep_premium" id="sales_rep_premium" value="<?php echo isset($sales_rep_info['sales_rep_premium']) && !empty($sales_rep_info['sales_rep_premium']) ? $sales_rep_info['sales_rep_premium'] : '' ?>" class="form-control">
													<?php if (!empty($sales_rep_premium_error_msg)) {?>
														<span class="error"><?php echo $sales_rep_premium_error_msg; ?></span>
													<?php }?>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div class="card mx-auto mt-5 mb-5 managerInfoCard" style="border-bottom: 1px solid rgba(0, 0, 0, 0.125);">
									<div class="card-header" role="tab" id="managerTab">
										<a data-toggle="collapse" class="collapsed" style="color: #000000;" data-parent="#accordionEx" href="#managerInfo"
										aria-controls="managerInfo">
											<h5 class="mb-0 text-primary">
											Manager <i class="fas fa-angle-down pull-right"></i><i class="fas fa-angle-up pull-right"></i>
											</h5>
										</a>
									</div>
									<div id="managerInfo" class="collapse" role="tabpanel" aria-labelledby="managerTab" data-parent="#accordionEx">
										<div class="card-body">
											<div class="form-group">
												<label for="language" class="col-sm-4 col-form-label">Sales Manager</label>
												<div class="col-sm-1">
													<input <?php echo $sales_rep_info['is_sales_rep_manager'] == 1 ? "checked" : ""; ?>  type="checkbox" class="form-control" name="is_sales_rep_manager" id="is_sales_rep_manager" class="form-control">
												</div>
											</div>

											<div class="form-group">
												<label for="saled_rep_drop" class="col-sm-4 col-form-label">Select Sales Reps.</label>
												<div class="col-sm-6">
													<select name="sales_rep_users[]" id="saled_rep_drop"  class="selectpicker" multiple data-live-search="true" data-actions-box="true" >
														<?php foreach ($salesUsers as $salesUser) {
    $selected = '';
    if (set_value('sales_rep_users') && in_array($salesUser['id'], set_value('sales_rep_users'))) {
        $selected = 'selected';
    } else {

        $sales_rep_users = explode(',', $sales_rep_info['sales_rep_users']);
        if (in_array($salesUser['id'], $sales_rep_users)) {
            $selected = 'selected';
        }
    }
    ?>
															<option <?php echo $selected; ?> value="<?php echo $salesUser['id']; ?>"><?php echo $salesUser['first_name'] . " " . $salesUser['last_name']; ?></option>
														<?php
}?>
													</select>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div class="card mx-auto mt-5 mb-5" style="border-bottom: 1px solid rgba(0, 0, 0, 0.125);">
									<div class="card-header" role="tab" id="imagesTab">
										<a data-toggle="collapse" class="collapsed" style="color: #000000;" data-parent="#accordionEx" href="#imagesInfo"
										aria-controls="imagesInfo">
											<h5 class="mb-0 text-primary">
											Images <i class="fas fa-angle-down pull-right"></i><i class="fas fa-angle-up pull-right"></i>
											</h5>
										</a>
									</div>
									<div id="imagesInfo" class="collapse" role="tabpanel" aria-labelledby="imagesTab" data-parent="#accordionEx">
										<div class="card-body">
											<div class="form-group">
												<label for="sales_rep_profile_img" class="col-sm-4 col-form-label">Profile Img For Borrower Email</label>
												<div class="col-sm-6">
													<input type="file" class="form-control" name="sales_rep_profile_img" id="sales_rep_profile_img" accept=".png,.jpg" class="form-control">
													<?php if (!empty($sales_rep_profile_img_error_msg)) {?>
														<span class="error"><?php echo $sales_rep_profile_img_error_msg; ?></span>
													<?php }?>
												</div>
												<div class="col-sm-4">
													<?php
if (isset($sales_rep_info['sales_rep_profile_img']) && !empty($sales_rep_info['sales_rep_profile_img'])) {
    if (env('AWS_ENABLE_FLAG') == 1) {
        $sales_rep_info['sales_rep_profile_img'] = str_replace('uploads/', '', $sales_rep_info['sales_rep_profile_img']);
        $img = env('AWS_PATH') . $sales_rep_info['sales_rep_profile_img'];
    } else {
        $img = base_url() . $sales_rep_info['sales_rep_profile_img'];
    }

}
?>
													<?php
if (isset($img) && !empty($img)) {
    ?>
															<img src="<?php echo $img; ?>" width="100" height="100">
															<a href="javascript:void(0);" onclick="removeSalesRepProfileImg(<?php echo $sales_rep_info['id']; ?>);">Remove img</a>
													<?php
}
?>

												</div>
											</div>

											<div class="form-group">
												<label for="sales_rep_profile_thank_you_img" class="col-sm-4 col-form-label">Profile Img For Thank you Email</label>
												<div class="col-sm-6">
													<input type="file" class="form-control" name="sales_rep_profile_thank_you_img" id="sales_rep_profile_thank_you_img" accept=".png,.jpg" class="form-control">
													<?php if (!empty($sales_rep_profile_thank_you_img_error_msg)) {?>
														<span class="error"><?php echo $sales_rep_profile_thank_you_img_error_msg; ?></span>
													<?php }?>
												</div>
												<div class="col-sm-4">
													<?php
if (env('AWS_ENABLE_FLAG') == 1) {
    $sales_rep_info['sales_rep_profile_thank_you_img'] = str_replace('uploads/', '', $sales_rep_info['sales_rep_profile_thank_you_img']);
    $imgThank = env('AWS_PATH') . $sales_rep_info['sales_rep_profile_thank_you_img'];
} else {
    $imgThank = base_url() . $sales_rep_info['sales_rep_profile_thank_you_img'];
}
?>
													<?php
if (isset($imgThank) && !empty($imgThank)) {
    ?>
															<img src="<?php echo $imgThank; ?>" width="100" height="100">
															<a href="javascript:void(0);" onclick="removeSalesRepThankYouProfileImg(<?php echo $sales_rep_info['id']; ?>);">Remove img</a>
													<?php
}
?>

												</div>
											</div>
										</div>
									</div>
								</div>

								<div class="card mx-auto mt-5 mb-5" style="border-bottom: 1px solid rgba(0, 0, 0, 0.125);">
									<div class="card-header" role="tab" id="notificationsTab">
										<a data-toggle="collapse" class="collapsed" style="color: #000000;" data-parent="#accordionEx" href="#notificationsInfo"
										aria-controls="notificationsInfo">
											<h5 class="mb-0 text-primary">
											Notifications<i class="fas fa-angle-down pull-right"></i><i class="fas fa-angle-up pull-right"></i>
											</h5>
										</a>
									</div>
									<div id="notificationsInfo" class="collapse" role="tabpanel" aria-labelledby="notificationsTab" data-parent="#accordionEx">
										<div class="card-body">
											<div class="form-group">
												<label for="telephone" class="col-sm-4 col-form-label">&nbsp;</label>
												<div class="col-sm-6">
													<input type="checkbox" <?php echo ($sales_rep_info['is_mail_notification'] == 1) ? 'checked' : '' ?> class="" style="height:18px;width:18px;margin-right:10px;" name="is_mail_notification" id="is_mail_notification" class="form-control" placeholder="Mail Notification">Mail Notification
												</div>
											</div>
										</div>
									</div>
								</div>
								<?php if ($is_super_admin): ?>
								<div class="card mx-auto mt-5 mb-5" style="border-bottom: 1px solid rgba(0, 0, 0, 0.125);">
									<div class="card-header" role="tab" id="commissionTab">
										<a data-toggle="collapse" class="collapsed" style="color: #000000;" data-parent="#accordionEx" href="#commissionInfo"
										aria-controls="commissionInfo">
											<h5 class="mb-0 text-primary">
											Commissions <i class="fas fa-angle-down pull-right"></i><i class="fas fa-angle-up pull-right"></i>
											</h5>
										</a>
									</div>
									<div id="commissionInfo" class="collapse" role="tabpanel" aria-labelledby="commissionTab" data-parent="#accordionEx">
										<div class="card-body">
											<div class="card">
												<div class="card-body">
													<div class="form-group" >
														<label for="commission-draw" class="col-sm-4 col-form-label">Draw amount</label>
														<div class="col-sm-6">
															<input  step="01" min="0"  type="number" class="form-control" name="commission_draw" id="commission-draw" class="form-control" value="<?php echo set_value('commission_draw', $sales_rep_info['commission_draw_value']); ?>" />
														</div>
													</div>
													<div class="form-group" >
														<label for="commission-first-threshold" class="col-sm-4 col-form-label">First In Threshold</label>
														<div class="col-sm-6">
															<input  step="01" min="0"  type="number" class="form-control" name="commission_first_threshold" id="commission-first-threshold" class="form-control" value="<?php echo set_value('commission_first_threshold', $sales_rep_info['first_in_threshold']); ?>" />
														</div>
													</div>

													<div class="form-group" >
														<label for="commission-bonus" class="col-sm-4 col-form-label">Apply Bonus?</label>
														<div class="col-sm-6">
														<select name="apply_bonus"  id="commission-bonus" class="selectpicker" >

															<option  value="0">No</option>
															<option <?=($sales_rep_info['apply_bonus'] == "1") ? 'selected' : '';?> value="1">Yes</option>

														</select>
														</div>
													</div>

												</div>
											</div>
										<?php
foreach ($product_types as $product_type):
?>
											<div class="card">
												<div class="card-header" role="tab" id="commissionTab">
													<?php echo ucwords($product_type); ?>
												</div>
												<div class="card-body row commission-details">
													<div class="col-3">
													<!-- <nav> -->
														<div class="nav flex-column nav-pills"  role="tablist" aria-orientation="vertical">
															<?php
$is_fist_tab = true;
foreach ($underwriter_types as $underwriter_type_key => $underwriter_type):
?>
																<a class="nav-item nav-link <?php if ($is_fist_tab) {
    echo 'active';
}
?>" id="v-pills-<?php echo $product_type . '-' . $underwriter_type_key; ?>-tab" data-toggle="pill" href="#v-pills-<?php echo $product_type . '-' . $underwriter_type_key; ?>" role="tab" aria-controls="v-pills-<?php echo $product_type . '-' . $underwriter_type_key; ?>" aria-selected="<?php echo ($is_fist_tab) ? 'true' : 'false' ?>"><?php echo ucwords($underwriter_type_key) ?></a>
															<?php
$is_fist_tab = false;
endforeach;
?>

														</div>
													</div>
													<!-- </nav> -->
													<div class="col-9">
														<div class="tab-content">
															<?php
$is_fist_tab = true;
foreach ($underwriter_types as $underwriter_type_key => $underwriter_type):
?>
																<div class="tab-pane fade <?php if ($is_fist_tab) {
    echo 'show active';
}
?>" id="v-pills-<?php echo $product_type . '-' . $underwriter_type_key; ?>" role="tabpanel" aria-labelledby="v-pills-<?php echo $underwriter_type_key; ?>-tab">
																<?php if (count($underwriter_tires[$product_type][$underwriter_type_key])): ?>
																	<?php foreach ($underwriter_tires[$product_type][$underwriter_type_key] as $underwriter_tire): ?>
																		<?php
$default_selected = 'global';
$fix_commission = '';
$underwrter_id = '';
$threshold_arr = array();
if (isset($existing_underwriter[$underwriter_tire->id])) {
    $check_obj = $existing_underwriter[$underwriter_tire->id];
    $underwrter_id = $underwriter_tire->id;
    if (!empty($check_obj->fix_commission) && (float) $check_obj->fix_commission > 0) {
        $default_selected = 'fix';
        $fix_commission = $check_obj->fix_commission;

    } elseif ($check_obj->allow_threshold == 1) {
        $default_selected = 'override';
        $threshold_arr = $check_obj->underwriter_user_threshold_obj;
    }

}
?>
																		<div class="card">
																			<div class="card-header">
																				<?php echo ucwords($underwriter_tire->title); ?>
																			</div>
																			<div class="card-body underwriters-div">
																				<div class="form-group">
																					<label  class="col-sm-4 col-form-label"> Commission Type</label>
																					<div class="col-sm-6">
																						<select name="commission[<?php echo $product_type; ?>][<?php echo $underwriter_type_key; ?>][<?php echo $underwriter_tire->id; ?>][type]"  class="selectpicker show_hide_commissiontypes_select"  data-actions-box="true">

																							<!-- <option value="">Select Commission Type</option> -->
																							<?php foreach ($commission_types as $commission_type) {?>
																								<option <?php echo set_select('commission[' . $product_type . '][' . $underwriter_type_key . '][' . $underwriter_tire->id . '][type]', $commission_type, ($default_selected == $commission_type)); ?>  value="<?php echo $commission_type; ?>"><?php echo ucwords($commission_type); ?></option>
																							<?php }?>
																						</select>
																					</div>
																				</div>
																				<div class="show_hide_commissiontypes show_hide_commissiontypes-override">
																					<div class='threshold__amounts '>
																						<div class='clone-this-threshold card clone-main-div' >
																							<div class="card-body">
																							<?php
$min_range_val = $max_range_val = $threshold_commission = '';
if (!empty($threshold_arr) && isset($threshold_arr[0])) {
    $min_range_val = $threshold_arr[0]->threshold_amount_min;
    $max_range_val = $threshold_arr[0]->threshold_amount_max;
    $threshold_commission = $threshold_arr[0]->threshold_commission;
    unset($threshold_arr[0]);
}
?>
																							<div class = "form-group"><div class="col-sm-12"><div class="remove-btn-holder"><button type="button" class="btn btn-danger threshold-remove-btn"><i class="fa fa-times"></i></button></div></div></div>
																								<div class="form-group ">
																									<label  class="col-sm-4 col-form-label">Amount Range</label>
																									<div class="col-sm-4">
																										<input  step="01" min="0"  type="number" class="form-control" name="commission[<?php echo $product_type; ?>][<?php echo $underwriter_type_key; ?>][<?php echo $underwriter_tire->id; ?>][threshold_amount_min][]"  class="form-control" placeholder="Minimum Amount" value="<?php echo $min_range_val; ?>">
																									</div>
																									<div class="col-sm-4">
																										<input  step="01" min="0"  type="number" class="form-control" name="commission[<?php echo $product_type; ?>][<?php echo $underwriter_type_key; ?>][<?php echo $underwriter_tire->id; ?>][threshold_amount_max][]"  class="form-control" placeholder="Maximum Amount" value="<?php echo $max_range_val; ?>">
																									</div>
																								</div>
																								<div class="form-group ">
																									<label  class="col-sm-4 col-form-label">Commission %</label>
																									<div class="col-sm-6">
																										<input  step="0.1" min="0"  type="number" class="form-control" name="commission[<?php echo $product_type; ?>][<?php echo $underwriter_type_key; ?>][<?php echo $underwriter_tire->id; ?>][threshold_commission][]"  class="form-control" placeholder="Commision %" value="<?php echo $threshold_commission; ?>">
																									</div>
																								</div>


																							</div>
																						</div>
																						<div class='clone-to-threshold'>
																						<?php
foreach ($threshold_arr as $underwriter_user_threshold_obj):
    $min_range_val = "";
    $max_range_val = "";
    $commission_val = "";
    if (count($underwriter_user_threshold_obj)) {
        $min_range_val = $underwriter_user_threshold_obj->threshold_amount_min;
        $max_range_val = $underwriter_user_threshold_obj->threshold_amount_max;
        $threshold_commission = $underwriter_user_threshold_obj->threshold_commission;
    }
    ?>
																									<div class='clone-this-threshold card' >
																										<div class="card-body">
																										<div class = "form-group"><div class="col-sm-12"><div class="remove-btn-holder"><button type="button" class="btn btn-danger threshold-remove-btn"><i class="fa fa-times"></i></button></div></div></div>
																									<div class="form-group show_hide_threshold">
																										<label  class="col-sm-4 col-form-label">Amount Range</label>
																										<div class="col-sm-4">
																											<input  step="01" min="0"  type="number" class="form-control" name="commission[<?php echo $product_type; ?>][<?php echo $underwriter_type_key; ?>][<?php echo $underwriter_tire->id; ?>][threshold_amount_min][]"  class="form-control" placeholder="Minimum Amount" value="<?php echo $min_range_val; ?>">
																										</div>
																										<div class="col-sm-4">
																											<input  step="01" min="0"  type="number" class="form-control" name="commission[<?php echo $product_type; ?>][<?php echo $underwriter_type_key; ?>][<?php echo $underwriter_tire->id; ?>][threshold_amount_max][]"  class="form-control" placeholder="Maximum Amount" value="<?php echo $max_range_val; ?>">
																										</div>
																									</div>
																									<div class="form-group show_hide_threshold">
																										<label class="col-sm-4 col-form-label">Commission %</label>
																										<div class="col-sm-6">
																											<input  step="0.1" min="0"  type="number" class="form-control" name="commission[<?php echo $product_type; ?>][<?php echo $underwriter_type_key; ?>][<?php echo $underwriter_tire->id; ?>][threshold_commission][]"  class="form-control" placeholder="Commision %" value="<?php echo $threshold_commission; ?>">
																										</div>
																									</div>


																										</div>
																									</div>
																								<?php
endforeach;
?>
																						</div>

																						<div class="clearfix">
																							<div class="form-group">
																								<button type="button" class="btn btn-success pull-right threshold-add-btn">
																									<i class="fa fa-plus"></i>
																								</button>

																							</div>
																						</div>
																					</div>
																				</div>
																				<div class="show_hide_commissiontypes show_hide_commissiontypes-fix">
																					<div class="form-group">
																						<label  class="col-sm-4 col-form-label"> Commission %</label>
																						<div class="col-sm-6">
																							<input  step="0.1" min="0"  type="number" class="form-control" name="commission[<?php echo $product_type; ?>][<?php echo $underwriter_type_key; ?>][<?php echo $underwriter_tire->id; ?>][fix_commission]"  class="form-control" placeholder="Commision %" value="<?php echo set_value('commission[' . $product_type . '][' . $underwriter_type_key . '][' . $underwriter_tire->id . '][fix_commission]', $fix_commission) ?>">
																						</div>
																					</div>
																				</div>
																			</div>
																		</div>
																	<?php endforeach;?>
																	<?php else: ?>
																		<div>No Tiers Found</div>
																	<?php endif;?>
																</div>
															<?php
$is_fist_tab = false;
endforeach;
?>
														</div>
													</div>

												</div>
											</div>

											<?php
endforeach;
?>
											<?php
$default_selected = 'global';
$fix_commission = '';
$threshold_arr = array();
if (!empty($escrow_commissions)) {
    $check_obj = $escrow_commissions;
    if (!empty($check_obj->fix_commission) && (float) $check_obj->fix_commission > 0) {
        $default_selected = 'fix';
        $fix_commission = $check_obj->fix_commission;

    } elseif ($check_obj->allow_threshold == 1) {
        $default_selected = 'override';
        $threshold_arr = $check_obj->underwriter_user_threshold_obj;
    }

}
?>
											<div class="card">
												<div class="card-header" role="tab" >
													Escrow
												</div>
												<div class="card-body commission-details underwriters-div">

													<div class="form-group">
														<label  class="col-sm-4 col-form-label"> Commission Type</label>
														<div class="col-sm-6">
															<select name="escrow_commission[type]"  class="selectpicker show_hide_commissiontypes_select"  data-actions-box="true">

																<?php foreach ($commission_types as $commission_type) {?>
																	<option <?php echo set_select('escrow_commission[type]', $commission_type, $default_selected == $commission_type); ?>  value="<?php echo $commission_type; ?>"><?php echo ucwords($commission_type); ?></option>
																<?php }?>
															</select>
														</div>
													</div>
													<div class="show_hide_commissiontypes show_hide_commissiontypes-override">
														<div class='threshold__amounts '>
															<div class='clone-this-threshold card clone-main-div' >
																<div class="card-body">
																<?php
$min_range_val = $max_range_val = $threshold_commission = '';
if (!empty($threshold_arr) && isset($threshold_arr[0])) {
    $min_range_val = $threshold_arr[0]->threshold_amount_min;
    $max_range_val = $threshold_arr[0]->threshold_amount_max;
    $threshold_commission = $threshold_arr[0]->threshold_commission;
    unset($threshold_arr[0]);
}
?>
																<div class = "form-group"><div class="col-sm-12"><div class="remove-btn-holder"><button type="button" class="btn btn-danger threshold-remove-btn"><i class="fa fa-times"></i></button></div></div></div>
																	<div class="form-group ">
																		<label  class="col-sm-4 col-form-label">Commission Range</label>
																		<div class="col-sm-4">
																			<input  step="01" min="0"  type="number" class="form-control" name="escrow_commission[threshold_amount_min][]"  class="form-control" placeholder="Minimum Amount" value="<?php echo $min_range_val; ?>">
																		</div>
																		<div class="col-sm-4">
																			<input  step="01" min="0"  type="number" class="form-control" name="escrow_commission[threshold_amount_max][]"  class="form-control" placeholder="Maximum Amount" value="<?php echo $max_range_val; ?>">
																		</div>
																	</div>
																	<div class="form-group ">
																		<label  class="col-sm-4 col-form-label">Commission %</label>
																		<div class="col-sm-6">
																			<input  step="0.1" min="0"  type="number" class="form-control" name="escrow_commission[threshold_commission][]"  class="form-control" placeholder="Commision %" value="<?php echo $threshold_commission; ?>">
																		</div>
																	</div>


																</div>
															</div>
															<div class='clone-to-threshold'>
															<?php
foreach ($threshold_arr as $underwriter_user_threshold_obj):
    $min_range_val = "";
    $max_range_val = "";
    $commission_val = "";
    if (count($underwriter_user_threshold_obj)) {
        $min_range_val = $underwriter_user_threshold_obj->threshold_amount_min;
        $max_range_val = $underwriter_user_threshold_obj->threshold_amount_max;
        $threshold_commission = $underwriter_user_threshold_obj->threshold_commission;
    }
    ?>
																		<div class='clone-this-threshold card' >
																			<div class="card-body">
																			<div class = "form-group"><div class="col-sm-12"><div class="remove-btn-holder"><button type="button" class="btn btn-danger threshold-remove-btn"><i class="fa fa-times"></i></button></div></div></div>
																		<div class="form-group show_hide_threshold">
																			<label  class="col-sm-4 col-form-label">Commission Range</label>
																			<div class="col-sm-4">
																				<input  step="01" min="0"  type="number" class="form-control" name="escrow_commission[threshold_amount_min][]"  class="form-control" placeholder="Minimum Amount" value="<?php echo $min_range_val; ?>">
																			</div>
																			<div class="col-sm-4">
																				<input  step="01" min="0"  type="number" class="form-control" name="escrow_commission[threshold_amount_max][]"  class="form-control" placeholder="Maximum Amount" value="<?php echo $max_range_val; ?>">
																			</div>
																		</div>
																		<div class="form-group show_hide_threshold">
																			<label  class="col-sm-4 col-form-label">Commission %</label>
																			<div class="col-sm-6">
																				<input  step="0.1" min="0"  type="number" class="form-control" name="escrow_commission[threshold_commission][]"  class="form-control" placeholder="Commision %" value="<?php echo $threshold_commission; ?>">
																			</div>
																		</div>


																			</div>
																		</div>
																	<?php
endforeach;
?>
															</div>

															<div class="clearfix">
																<div class="form-group">
																	<button type="button" class="btn btn-success pull-right threshold-add-btn">
																		<i class="fa fa-plus"></i>
																	</button>

																</div>
															</div>
														</div>
													</div>
													<div class="show_hide_commissiontypes show_hide_commissiontypes-fix">
														<div class="form-group">
															<label  class="col-sm-4 col-form-label"> Commission %</label>
															<div class="col-sm-6">
																<input  step="0.1" min="0"  type="number" class="form-control" name="escrow_commission[fix_commission]"  class="form-control" placeholder="Commision %" value="<?php echo set_value('commission[' . $product_type . '][' . $underwriter_type_key . '][' . $underwriter_tire->id . '][fix_commission]', $fix_commission) ?>">
															</div>
														</div>
													</div>
												</div>
											</div>

											<div class="card">
												<div class="card-header" role="tab" >
													Sales Rep Override
												</div>
												<div class="card-body commission-details underwriters-div">
													<div class="form-group" >
														<label class="col-sm-4 col-form-label">Sales Rep</label>
														<div class="col-sm-6">
															<select name="commission_sales_rep_override_id"  class="selectpicker"  data-actions-box="true">
																<option value="">Select Sales Rep</option>
																<?php foreach ($salesUsers as $salesUser): ?>
																	<option <?=($commission_sales_rep_override_id == $salesUser['id']) ? 'selected' : '';?> value="<?=$salesUser['id']?>"><?=$salesUser['first_name'] . ' ' . $salesUser['last_name']?></option>
																<?php endforeach;?>
															</select>

														</div>
													</div>

													<?php
$product_types[] = 'escrow';
foreach ($product_types as $product_type): ?>
														<?php
$override_commission_val = 0.00;
if (isset($commission_sales_rep_override_val[$product_type])) {
    $override_commission_val = $commission_sales_rep_override_val[$product_type];
}
?>
														<div class="form-group" >
															<label class="col-sm-4 col-form-label"><?=ucfirst($product_type);?></label>
															<div class="col-sm-6">
																<input  step="0.1" min="0"  type="number" class="form-control" name="commission_sales_rep_override_val[<?=$product_type?>]"  class="form-control" value="<?=$override_commission_val?>" />
															</div>
														</div>
													<?php endforeach;?>

												</div>
											</div>
										</div>
									</div>
								</div>

								<?php endif;?>

								<div class="form-group">
									<div class="col-sm-6">
										<button type="submit" id="edit-sales-rep" name="edit-sales-rep" class="btn btn-info btn-icon-split">
											<span class="icon text-white-50">
												<i class="fas fa-save"></i>
											</span>
											<span class="text">Update</span>
										</button>
										<a href="<?php echo site_url('order/admin/sales-rep'); ?>" class="btn btn-secondary btn-icon-split">
											<span class="icon text-white-50">
												<i class="fas fa-arrow-left"></i>
											</span>
											<span class="text">Cancel</span>
										</a>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
    </div>
</div>
