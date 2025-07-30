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
                <h1 class="h3 text-gray-800">Bonus Range</h1>
            </div>
        </div>
        <div class="row">
			<div class="col-md-12">
				<div class="card shadow mb-4">
					<div class="card-header py-3">
						<h6 class="m-0 font-weight-bold text-primary">Add Bonus Range</h6>
					</div>
					<div class="card-body">        
						<form id="frm-add-bonus-range" method="POST" >


							<div class="form-group">
								<label for="revenue_range" class="col-sm-2 col-form-label">From Revenue</label>
								<div class="col-sm-6">
									<input  step="1" min="0"  type="number" class="form-control" name="revenue_range_min" id="revenue_range_min" class="form-control" placeholder="From Revenue" value="<?php echo set_value('revenue_range_min') ?>">
									<?php if(!empty(form_error('revenue_range_min'))){ ?>                     
										<span class="error"><?php echo form_error('revenue_range_min'); ?></span>
									<?php } ?>
								</div>
								
								
							</div>

							<div class="form-group">
								<label for="bonus_amount" class="col-sm-2 col-form-label">Bonus</label>
								<div class="col-sm-6">
									<input  step="1" min="0"  type="number" class="form-control" name="bonus_amount" id="bonus_amount" class="form-control" placeholder="Enter Bonus Amout" value="<?php echo set_value('bonus_amount') ?>">
									<?php if(!empty(form_error('bonus_amount'))){ ?>                     
										<span class="error"><?php echo form_error('bonus_amount'); ?></span>
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
									<!-- <button type="submit" class="btn btn-secondary">Update</button> -->
									<a href="<?php echo site_url('order/admin/commission-bonus'); ?>" class="btn btn-secondary btn-icon-split">
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
