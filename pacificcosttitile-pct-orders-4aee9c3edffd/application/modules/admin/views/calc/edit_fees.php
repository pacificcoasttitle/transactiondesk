<div class="container">
<?php if(!empty($success_msg)){ ?>
    <div class="col-xs-12">
        <div class="alert alert-success"><?php echo $success_msg; ?></div>
    </div>
<?php } ?>
<?php if(!empty($error_msg)){ ?>
    <div class="col-xs-12">
        <div class="alert alert-danger"><?php echo $error_msg; ?></div>
    </div>
<?php } ?>
    <div class="card mx-auto mt-5">
      <div class="card-header">Edit Fee</div>
        <div class="card-body">        
            <form id="frm-edit-fee" method="POST">
                <div class="form-group row">
                    <label for="txn_type" class="col-sm-2 col-form-label">Transaction Type<span class="required"> *</span></label>
                    <div class="col-sm-10">
                        <select name="txn_type" id="txn_type" class="form-control">
	                        <option value="">Select</option>
	                        <option value="resale" <?php if($fees_info['transaction_type'] == 'resale') { echo "selected"; } ?>>Purchase</option>
	                        <option value="refinance" <?php if($fees_info['transaction_type'] == 'refinance') { echo "selected"; } ?>>Refinance</option>
	                     </select>                      
                      <?php if(!empty($txn_type_error_msg)){ ?>                     
                        <span class="error"><?php echo $txn_type_error_msg; ?></span>
                      <?php } ?>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="section" class="col-sm-2 col-form-label">Fee Type<span class="required"> *</span></label>
                    <div class="col-sm-10">
                        <select name="section" id="section" class="form-control">
	                        <option value="">Select</option>
	                        <option value="escrow" <?php if($fees_info['parent_name'] == 'escrow') { echo "selected"; } ?> >Escrow Fees</option>
	                        <option value="recording" <?php if($fees_info['parent_name'] == 'recording') { echo "selected"; } ?> >Recording Fees</option>
	                        <option value="other" <?php if($fees_info['parent_name'] == 'other') { echo "selected"; } ?>>Other</option>
	                     </select>                      
                      <?php if(!empty($section_error_msg)){ ?>                     
                        <span class="error"><?php echo $section_error_msg; ?></span>
                      <?php } ?>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="fee_name" class="col-sm-2 col-form-label">Fee Name<span class="required"> *</span></label>
                    <div class="col-sm-10">
                        <?php 
                            $fee_name = isset($fees_info['name']) && !empty($fees_info['name']) ? $fees_info['name'] : '';
                        ?>
                        <input type="text" class="form-control" name="fee_name" id="fee_name" class="form-control" value="<?php echo $fee_name; ?>" placeholder="Fee Name">

                        <?php if(!empty($name_error_msg)){ ?>                     
                            <span class="error"><?php echo $name_error_msg; ?></span>
                        <?php } ?>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="fee_value" class="col-sm-2 col-form-label">Fee Value<span class="required"> *</span></label>
                    <div class="col-sm-10">
                        <?php 
                            $fee_value = isset($fees_info['value']) && !empty($fees_info['value']) ? $fees_info['value'] : '';
                        ?>

                        <input type="text" class="form-control" name="fee_value" id="fee_value" value="<?php echo $fee_value; ?>" class="form-control" placeholder="Fee Value">
                        <?php if(!empty($value_error_msg)){ ?>                     
                            <span class="error"><?php echo $value_error_msg; ?></span>
                        <?php } ?>
                    </div>
                </div>
                
                <div class="pull-right">
                    <button type="submit" id="addFee" name="addFee" class="btn btn-secondary">Update</button>
                    <a href="<?php echo site_url('calculator/admin/fees'); ?>" id="cancel" name="cancel" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>