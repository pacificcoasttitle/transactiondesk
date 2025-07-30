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
      <div class="card-header">Edit Title Rate</div>
        <div class="card-body">        
            <form id="edit-title-rates" method="POST">
                <div class="form-group row">
                    <label for="min_price" class="col-sm-2 col-form-label">Min Price<span class="required"> *</span></label>
                    <div class="col-sm-10">
                        <?php
                        // echo "<pre>"; print_r($min_price_error_msg); exit;
                            $min_range = isset($rate_info['min_range']) ? $rate_info['min_range'] : '';
                        ?>
                        <input type="number" class="form-control" name="min_price" id="min_price" value="<?php echo $min_range; ?>" class="form-control" placeholder="Min Price">

                        <?php if(!empty($min_price_error_msg)){ ?>                     
                            <span class="error"><?php echo $min_price_error_msg; ?></span>
                        <?php } ?>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="max_price" class="col-sm-2 col-form-label">Max Price<span class="required"> *</span></label>
                    <div class="col-sm-10">
                        <?php 
                            $max_range = isset($rate_info['max_range']) ? $rate_info['max_range'] : '';
                        ?>
                        <input type="number" value="<?php echo $max_range; ?>" class="form-control" name="max_price" id="max_price" class="form-control" placeholder="Max Price">
                        <?php if(!empty($max_price_error_msg)){ ?>                     
                            <span class="error"><?php echo $max_price_error_msg; ?></span>
                        <?php } ?>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="owner_rate" class="col-sm-2 col-form-label">Owner Rate<span class="required"> *</span></label>
                    <div class="col-sm-10">
                        <?php 
                            $owner_rate = isset($rate_info['owner_rate']) ? $rate_info['owner_rate'] : '';
                        ?>
                        <input type="number" value="<?php echo $owner_rate; ?>" class="form-control" name="owner_rate" id="owner_rate" class="form-control" placeholder="Owner Rate">
                        <?php if(!empty($owner_rate_error_msg)){ ?>                     
                            <span class="error"><?php echo $owner_rate_error_msg; ?></span>
                        <?php } ?>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="home_owner_rate" class="col-sm-2 col-form-label">Home Owner Rate<span class="required"> *</span></label>
                    <div class="col-sm-10">
                        <?php 
                            $home_owner_rate = isset($rate_info['home_owner_rate']) ? $rate_info['home_owner_rate'] : '';
                        ?>
                        <input type="number" value="<?php echo $home_owner_rate; ?>" class="form-control" name="home_owner_rate" id="home_owner_rate" class="form-control" placeholder="Home Owner Rate">
                        <?php if(!empty($home_owner_rate_error_msg)){ ?>                     
                            <span class="error"><?php echo $home_owner_rate_error_msg; ?></span>
                        <?php } ?>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="con_loan_rate" class="col-sm-2 col-form-label">Con Loan Rate<span class="required"> *</span></label>
                    <div class="col-sm-10">
                        <?php 
                            $con_loan_rate = isset($rate_info['con_loan_rate']) ? $rate_info['con_loan_rate'] : '';
                        ?>
                        <input type="number" value="<?php echo $con_loan_rate; ?>" class="form-control" name="con_loan_rate" id="con_loan_rate" class="form-control" placeholder="Con Loan Rate">
                        <?php if(!empty($con_loan_rate_error_msg)){ ?>                     
                            <span class="error"><?php echo $con_loan_rate_error_msg; ?></span>
                        <?php } ?>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="resi_loan_rate" class="col-sm-2 col-form-label">Resi Loan Rate<span class="required"> *</span></label>
                    <div class="col-sm-10">
                        <?php 
                            $resi_loan_rate = isset($rate_info['resi_loan_rate']) ? $rate_info['resi_loan_rate'] : '';
                        ?>
                        <input type="number" value="<?php echo $resi_loan_rate; ?>" class="form-control" name="resi_loan_rate" id="resi_loan_rate" class="form-control" placeholder="Resi Loan Rate">
                        <?php if(!empty($resi_loan_rate_error_msg)){ ?>                     
                            <span class="error"><?php echo $resi_loan_rate_error_msg; ?></span>
                        <?php } ?>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="con_full_loan_rate" class="col-sm-2 col-form-label">Con Full Loan Rate<span class="required"> *</span></label>
                    <div class="col-sm-10">
                        <?php 
                            $con_full_loan_rate = isset($rate_info['con_full_loan_rate']) ? $rate_info['con_full_loan_rate'] : '';
                        ?>
                        <input type="number" value="<?php echo $con_full_loan_rate; ?>" class="form-control" name="con_full_loan_rate" id="con_full_loan_rate" class="form-control" placeholder="Con Full Loan Rate">
                        <?php if(!empty($con_full_loan_rate_error_msg)){ ?>                     
                            <span class="error"><?php echo $con_full_loan_rate_error_msg; ?></span>
                        <?php } ?>
                    </div>
                </div>
                <div class="pull-right">
                    <button type="submit" class="btn btn-secondary">Update</button>
                    <a href="<?php echo site_url('calculator/admin/title_rates'); ?>" id="cancel" name="cancel" class="btn btn-secondary">Cancel</a>
                </div>           
            </form>
        </div>
    </div>
</div>