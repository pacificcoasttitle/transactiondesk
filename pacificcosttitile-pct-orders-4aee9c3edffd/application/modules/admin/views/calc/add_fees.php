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
      <div class="card-header">Add Fee</div>
        <div class="card-body">        
            <form id="frm-add-fee" method="POST">
                <div class="form-group row">
                    <label for="txn_type" class="col-sm-2 col-form-label">Transaction Type<span class="required"> *</span></label>
                    <div class="col-sm-10">
                        <select name="txn_type" id="txn_type" class="form-control">
	                        <option value="">Select</option>
	                        <option value="resale">Purchase</option>
	                        <option value="refinance">Refinance</option>
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
	                        <option value="escrow">Escrow Fees</option>
	                        <option value="recording">Recording Fees</option>
	                        <option value="other">Other</option>
	                     </select>                      
                      <?php if(!empty($section_error_msg)){ ?>                     
                        <span class="error"><?php echo $section_error_msg; ?></span>
                      <?php } ?>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="fee_name" class="col-sm-2 col-form-label">Fee Name<span class="required"> *</span></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="fee_name" id="fee_name" class="form-control" placeholder="Fee Name">

                        <?php if(!empty($name_error_msg)){ ?>                     
                            <span class="error"><?php echo $name_error_msg; ?></span>
                        <?php } ?>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="fee_value" class="col-sm-2 col-form-label">Fee Value<span class="required"> *</span></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="fee_value" id="fee_value" class="form-control" placeholder="Fee Value">
                        <?php if(!empty($value_error_msg)){ ?>                     
                            <span class="error"><?php echo $value_error_msg; ?></span>
                        <?php } ?>
                    </div>
                </div>
                
                <div class="pull-right">
                    <button type="submit" id="addFee" name="addFee" class="btn btn-secondary">Add</button>
                    <a href="<?php echo site_url('calculator/admin/fees'); ?>" id="cancel" name="cancel" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>