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
      <div class="card-header">Edit Refinance Rate</div>
        <div class="card-body">        
            <form id="edit-refinance-rates" method="POST">
                <div class="form-group row">
                    <label for="county" class="col-sm-2 col-form-label">County<span class="required"> *</span></label>
                    <div class="col-sm-10">
                        <select name="county" id="county" class="form-control">
                            <option value="">Select County</option>
                            <?php 
                                if(isset($county_list) && !empty($county_list))
                                {
                                    foreach ($county_list as $key => $value) 
                                    {
                                        if($rate_info['county'] == $value->zone_name.'__'.$value->transaction_type)
                                        {
                                            $selected = "selected=selected";
                                        }
                                        else
                                        {
                                            $selected = '';
                                        }
                            ?>
                                        <option value="<?php echo $value->zone_name.'__'.$value->transaction_type; ?>" <?php echo $selected; ?>><?php echo $value->zone_name; ?></option>
                            <?php
                                    }
                                }
                            ?>
                      </select>                      
                      <?php if(!empty($county_error_msg)){ ?>                     
                        <span class="error"><?php echo $county_error_msg; ?></span>
                      <?php } ?>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="min_price" class="col-sm-2 col-form-label">Min Price<span class="required"> *</span></label>
                    <div class="col-sm-10">
                        <?php
                            $min_range = isset($rate_info['min_range']) ? $rate_info['min_range'] : '';
                        ?>
                        <input type="number" class="form-control" name="min_price" id="min_price" value="<?php echo $min_range; ?>" class="form-control" placeholder="Min Price">

                        <?php if(!empty($min_price_error_msg)){ ?>                     
                            <span class="error"><?php echo $min_price_error_msg; ?></span>
                        <?php } ?>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="max_price" class="col-sm-2 col-form-label">Max Price<!-- <span class="required"> *</span> --></label>
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
                    <label for="rate" class="col-sm-2 col-form-label">Rate<span class="required"> *</span></label>
                    <div class="col-sm-10">
                        <?php 
                            $escrow_rate = isset($rate_info['escrow_rate']) ? $rate_info['escrow_rate'] : '';
                        ?>
                        <input type="number" value="<?php echo $escrow_rate; ?>" class="form-control" name="rate" id="rate" class="form-control" placeholder="Rate">
                        <?php if(!empty($rate_error_msg)){ ?>                     
                            <span class="error"><?php echo $rate_error_msg; ?></span>
                        <?php } ?>
                    </div>
                </div>                
                <div class="pull-right">
                    <button type="submit" class="btn btn-secondary">Update</button>
                    <a href="<?php echo site_url('calculator/admin/refinance_rates'); ?>" id="cancel" name="cancel" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>