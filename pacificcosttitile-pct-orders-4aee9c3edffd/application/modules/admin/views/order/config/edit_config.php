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
                <h1 class="h3 text-gray-800">Edit Commission configuartio</h1>
            </div>
        </div>
        <div class="row">
			<div class="col-md-12">
				<div class="card shadow mb-4">
					<div class="card-header py-3">
						<h6 class="m-0 font-weight-bold text-primary">Edit Commission configuartio</h6>
					</div>
					<div class="card-body">        
						<form  method="POST" >
							<div class="form-group">
								<label for="title" class="col-sm-2 col-form-label">Title<span class="required"> *</span></label>
								<div class="col-sm-6">
									<input type="text"  name="title" id="title" class="form-control" placeholder="Enter title" value="<?php echo set_value('title',$record->title);?>" required>
									<?php if(!empty(form_error('title'))){ ?>                     
										<span class="error"><?php echo form_error('title'); ?></span>
									<?php } ?>
								</div>
							</div>

							<div class="form-group">
								<label for="value" class="col-sm-2 col-form-label">Commission %</label>
								<div class="col-sm-6">
								<input  step=".01" min="0"  type="number" class="form-control" name="commission" id="commission" class="form-control" placeholder="Enter Commission %" value="<?php echo set_value('commission',$record->value)?>">
								<?php if(!empty(form_error('commission'))){ ?>                     
									<span class="error"><?php echo form_error('commission'); ?></span>
								<?php } ?>
								</div>
							</div>
							
							<div class="form-group">
								<div class="col-sm-6">
									<button type="submit" class="btn btn-info btn-icon-split">
										<span class="icon text-white-50">
											<i class="fas fa-save"></i>
										</span>
										<span class="text">Update</span>
									</button>
									
									<a href="<?php echo site_url('order/admin/commission-config'); ?>" class="btn btn-secondary btn-icon-split">
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
