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
                <h1 class="h3 text-gray-800">Edit Fee</h1>
            </div>
            <div class="col-sm-6">
                <a href="<?php echo base_url() . 'order/admin/fees'; ?>" class="btn btn-info btn-icon-split float-right mr-2">
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
                        <h6 class="m-0 font-weight-bold text-primary">Edit Fee</h6>
                    </div>
                    <div class="card-body">
                        <form id="frm-edit-fee" method="POST">
                            <div class="form-group">
                                <label for="txn_type" class="col-sm-2 col-form-label">Transaction Type<span class="required"> *</span></label>
                                <div class="col-sm-6">
                                    <select name="txn_type" id="txn_type" class="form-control">
                                        <option value="">Select</option>
                                        <option value="sale" <?php if ($fees_info['transaction_type'] == 'sale') {echo "selected";}?>>Sale</option>
                                        <option value="loan" <?php if ($fees_info['transaction_type'] == 'loan') {echo "selected";}?>>Loan</option>
                                    </select>
                                <?php if (!empty($txn_type_error_msg)) {?>
                                    <span class="error"><?php echo $txn_type_error_msg; ?></span>
                                <?php }?>
                                </div>
                            </div>

                            <?php if (isset($fee_types) && !empty($fee_types)) {?>
                                    <div class="form-group">
                                        <label for="fee_type" class="col-sm-2 col-form-label">Fee Type<span class="required"> *</span></label>
                                        <div class="col-sm-6">
                                            <select name="fee_type" id="fee_type" class="form-control">
                                                <option value="">Select</option>
                                                <?php foreach ($fee_types as $key => $value) {?>
                                                        <option value="<?php echo $value['id']; ?>" <?php if ($value['id'] == $fees_info['fee_type_id']) {echo "selected";}?>><?php echo $value['name']; ?></option>
                                                <?php }?>
                                            </select>
                                        <?php if (!empty($fee_type_id_error_msg)) {?>
                                            <span class="error"><?php echo $fee_type_id_error_msg; ?></span>
                                        <?php }?>
                                        </div>
                                    </div>
                            <?php }?>

                            <?php if (isset($titleOfficer) && !empty($titleOfficer)) {?>
                            <div class="form-group">
                                <label for="fee_type" class="col-sm-2 col-form-label">Title Officer<span class="required"> *</span></label>
								<div class="col-sm-6">
                                    <select id="title_officer" name="title_officer" class="form-control">
                                        <option value="0">All</option>
                                        <?php foreach ($titleOfficer as $key => $value) {?>
                                            <option value="<?php echo $value['id']; ?>" <?php echo ($value['id'] == $fees_info['title_officer']) ? "selected" : '' ?>><?php echo $value['name']; ?></option>
                                        <?php }?>
									</select>
								</div>
							</div>
                            <?php }?>

                            <div class="form-group">
                                <label for="fee_name" class="col-sm-2 col-form-label">Fee Name<span class="required"> *</span></label>
                                <div class="col-sm-6">
                                    <?php $fee_name = isset($fees_info['name']) && !empty($fees_info['name']) ? $fees_info['name'] : '';?>
                                    <input type="text" class="form-control" name="fee_name" id="fee_name" class="form-control" value="<?php echo $fee_name; ?>" placeholder="Fee Name">

                                    <?php if (!empty($name_error_msg)) {?>
                                        <span class="error"><?php echo $name_error_msg; ?></span>
                                    <?php }?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="fee_value" class="col-sm-2 col-form-label">Fee Value<span class="required"> *</span></label>
                                <div class="col-sm-6">
                                    <?php $fee_value = isset($fees_info['value']) && !empty($fees_info['value']) ? $fees_info['value'] : '';?>

                                    <input type="text" class="form-control" name="fee_value" id="fee_value" value="<?php echo $fee_value; ?>" class="form-control" placeholder="Fee Value">
                                    <?php if (!empty($value_error_msg)) {?>
                                        <span class="error"><?php echo $value_error_msg; ?></span>
                                    <?php }?>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-6">
                                    <button type="submit" id="addFee" name="addFee" class="btn btn-info btn-icon-split">
                                        <span class="icon text-white-50">
                                            <i class="fas fa-save"></i>
                                        </span>
                                        <span class="text">Update</span>
                                    </button>
                                    <a href="<?php echo base_url() . 'order/admin/fees'; ?>" class="btn btn-secondary btn-icon-split">
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