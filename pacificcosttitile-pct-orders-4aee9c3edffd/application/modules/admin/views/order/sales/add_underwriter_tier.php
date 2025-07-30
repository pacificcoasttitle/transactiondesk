<style>
.bootstrap-select:not([class*="col-"]):not([class*="form-control"]):not(.input-group-btn) {
    width: -webkit-fill-available;
}
</style>
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
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-sm-6">
                <h1 class="h3 text-gray-800">Add Underwriter Tier</h1>
            </div>
        </div>
        <div class="row">
			<div class="col-md-12">
				<div class="card shadow mb-4">
					<div class="card-header py-3">
						<h6 class="m-0 font-weight-bold text-primary">Add Underwriter Tier</h6>
					</div>
					<div class="card-body">        
						<form id="frm-add-commission-range" method="POST" >
							<div class="form-group">
								<label for="zipcode" class="col-sm-2 col-form-label">Product Type<span class="required"> *</span></label>
								<div class="col-sm-6">
									<select name="product_type"  class="selectpicker" data-actions-box="true" required>
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
								<label for="zipcode" class="col-sm-2 col-form-label">Underwriter<span class="required"> *</span></label>
								<div class="col-sm-6">
									<select name="underwriter_type"  class="selectpicker" data-actions-box="true" required>
										<option value="">Select Underwriter</option>
										<?php foreach($underwriter_types as $key=>$underwriter_type) {?>
											<?php $selected = '';
												if(set_value('underwriter_type') && ($key== set_value('underwriter_type')))  {
													$selected = 'selected';
												} 
											?> 
											<option <?php echo $selected;?> value="<?php echo $key;?>"><?php echo ucwords($key); ?></option>
										<?php }?>
									</select>
									<?php if(!empty(form_error('underwriter_type'))){ ?>                     
										<span class="error"><?php echo form_error('underwriter_type'); ?></span>
									<?php } ?>
								</div>
							</div>

							<div class="form-group">
								<label for="title" class="col-sm-2 col-form-label">Title<span class="required"> *</span></label>
								<div class="col-sm-6">
									<input type="text"  name="title" id="title" class="form-control" placeholder="Enter Tier title" value="<?php echo set_value('title');?>" required>
									<?php if(!empty(form_error('title'))){ ?>                     
										<span class="error"><?php echo form_error('title'); ?></span>
									<?php } ?>
								</div>
							</div>

							<div class="form-group">
								<label for="commission" class="col-sm-2 col-form-label">Total Commission %</label>
								<div class="col-sm-6">
								<input  step=".01" min="0"  type="number" class="form-control" name="commission" id="commission" class="form-control" placeholder="Enter Total Commission %" value="<?php echo set_value('commission')?>">
								<?php if(!empty(form_error('commission'))){ ?>                     
									<span class="error"><?php echo form_error('commission'); ?></span>
								<?php } ?>
								</div>
							</div>

							<div class="form-group">
								<label for="description" class="col-sm-2 col-form-label">Description</label>
								<div class="col-sm-6">
									<textarea class="form-control" name="description" id="description" placeholder="Enter Description"><?php echo set_value('description');?></textarea>
								</div>
							</div>
							
							<div class="form-group">
								<div class="col-sm-6">
									<button type="submit" id="add-title-officer" name="add-title-officer" class="btn btn-info btn-icon-split">
										<span class="icon text-white-50">
											<i class="fas fa-save"></i>
										</span>
										<span class="text">Add</span>
									</button>
									
									<a href="<?php echo site_url('order/admin/underwriter-tier'); ?>" class="btn btn-secondary btn-icon-split">
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
