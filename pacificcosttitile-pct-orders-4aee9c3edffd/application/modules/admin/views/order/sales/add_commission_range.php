<style>
.bootstrap-select:not([class*="col-"]):not([class*="form-control"]):not(.input-group-btn) {
    width: -webkit-fill-available;
}
</style>
<div class="content">
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
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-sm-6">
                <h1 class="h3 text-gray-800">Commission Range</h1>
            </div>
        </div>
        <div class="row">
			<div class="col-md-12">
				<div class="card shadow mb-4">
					<div class="card-header py-3">
						<h6 class="m-0 font-weight-bold text-primary">Add Commission Range</h6>
					</div>
					<div class="card-body">        
						<form id="frm-add-commission-range" method="POST" >

							<div class="form-group">
								<label for="zipcode" class="col-sm-4 col-form-label">Product Type</label>
								<div class="col-sm-6">
									<select name="product_type"  class="selectpicker show_hide_underwriter_tier_select" data-actions-box="true" required>
										<option value="">Select Product Type</option>
										<?php foreach($product_types as $product_type) {?>
											<?php $selected = '';
												if(set_value('product_type') && ($product_type == set_value('product_type')))  {
													$selected = 'selected';
												} 
											?> 
											<option <?php echo $selected;?> value="<?php echo $product_type;?>"><?php echo ucwords($product_type);?></option>
										<?php }?>
									</select>
									<?php if(!empty(form_error('product_type'))){ ?>                     
										<span class="error"><?php echo form_error('product_type'); ?></span>
									<?php } ?>
								</div>
							</div>

							<div class="form-group">
								<label for="zipcode" class="col-sm-4 col-form-label">Underwriter</label>
								<div class="col-sm-6">
								<div class="show_hide_underwriter_tier show_hide_underwriter_tier-" >
									<select  class="selectpicker" data-actions-box="true">
									<option value="">Select Product Type First</option>
									</select>
								</div>
									<?php foreach($underwriter_tiers as $underwriter_tier_key=>$underwriter_tier_obj) :?>
										<div class="show_hide_underwriter_tier show_hide_underwriter_tier-<?php echo $underwriter_tier_key; ?>" >
											<select name="underwriter_tier[<?php echo $underwriter_tier_key; ?>]"  class="selectpicker" data-actions-box="true">
												<option value="">Select Underwriter Tier</option>
												<?php $last_label = ''; ?>
												<?php foreach($underwriter_tier_obj as $underwriter_tier) {?>
													<?php
													if ($last_label != $underwriter_tier->underwriter) : ?>
														<?php if($last_label != '') : ?>
															</optgroup>
														<?php endif; ?>
														<optgroup label="<?php echo ucwords($underwriter_tier->underwriter); ?>" class="opt_group_<?php echo $underwriter_tier->product_type; ?>">
														<?php 
														endif;
														$last_label = $underwriter_tier->underwriter; 
														$selected = '';
														if(set_value('underwriter_tier['.$underwriter_tier_key.']') && ($underwriter_tier->id == set_value('underwriter_tier['.$underwriter_tier_key.']')))  {
															$selected = 'selected';
														} 
													?> 
													<option <?php echo $selected;?> value="<?php echo $underwriter_tier->id;?>"><?php echo $underwriter_tier->title;?></option>
													
												<?php }?>
												<?php if($last_label != '') : ?>
													</optgroup>
												<?php endif; ?>
											</select>
										</div>
										<?php if(!empty(form_error('underwriter_tier['.$underwriter_tier_key.']'))){ ?>                     
											<span class="error"><?php echo form_error('underwriter_tier['.$underwriter_tier_key.']'); ?></span>
										<?php } ?>
									<?php endforeach; ?>
								</div>
							</div>

							<div class="form-group">
								<label for="revenue_range" class="col-sm-2 col-form-label">Revenue Range </label>
								<div class="row" >
									<div class="col-sm-4">
										<input  step="1" min="0"  type="number" class="form-control" name="revenue_range_min" id="revenue_range_min" class="form-control" placeholder="Minumum Value" value="<?php echo set_value('revenue_range_min') ?>">
										<?php if(!empty(form_error('revenue_range_min'))){ ?>                     
											<span class="error"><?php echo form_error('revenue_range_min'); ?></span>
										<?php } ?>
									</div>
									
									<div class="col-sm-4">
										<input  step="1" min="0"  type="number" class="form-control" name="revenue_range_max" id="revenue_range_max" class="form-control" placeholder="Maximum Value" value="<?php echo set_value('revenue_range_max')?>">
										<?php if(!empty(form_error('revenue_range_max'))){ ?>                     
											<span class="error"><?php echo form_error('revenue_range_max'); ?></span>
										<?php } ?>
									</div>
								</div>
							</div>

							<div class="form-group">
								<label for="premium" class="col-sm-2 col-form-label">Premium</label>
								<div class="col-sm-6">
									<input  step="1" min="0"  type="number" class="form-control" name="premium" id="premium" class="form-control" placeholder="Enter Premium" value="<?php echo set_value('premium') ?>">
									<?php if(!empty(form_error('premium'))){ ?>                     
										<span class="error"><?php echo form_error('premium'); ?></span>
									<?php } ?>
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
									
									<a href="<?php echo site_url('order/admin/commission-range'); ?>" class="btn btn-secondary btn-icon-split">
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
