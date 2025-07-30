<style>
	.ui-title-block + .ui-decor-1a {
		margin-top: 22px;
		margin-bottom: 34px;
	}

	.ui-decor-1a {
		display: inline-block;
		width: 100px;
		height: 2px;
	}

	.bg-accent {
		background-color: #d35411;
	}
	.prepend-icon.file {
		display: inline-block;
		vertical-align: top;
		position: relative;
		width: 100%;
	}

	.prepend-icon.file .button {
		border: 0;
		color: #243140;
		font-size: 15px;
		cursor: pointer;
		text-align: center;
		vertical-align: top;
		background: #bdc3c7;
		display: inline-block;
		-webkit-user-drag: none;
		text-shadow: 0 1px rgba(255, 255, 255, 0.2);
		position: absolute;
		top: 2px;
		right: 2px;
		float: none;
		height: 34px;
		line-height: 34px;
		padding: 0 16px;
		z-index: 10;
		border-radius: 9px;
	}

	.prepend-icon.file .gui-file {
		width: 100%;
		height: 100%;
		cursor: pointer;
		padding: 8px 10px;
		position: absolute;
		-moz-opacity: 0;
		opacity: 0;
		z-index: 11;
		bottom: 0;
		right: 0;
	}

	.form-control {
		padding: .375rem 2.75rem;
	}

	.field-icon {
		top: 0;
		left: 10px;
		width: 42px;
		height: 42px;
		color: inherit;
		line-height: 42px;
		position: absolute;
		text-align: center;
		-webkit-transition: all 0.5s ease-out;
		-moz-transition: all 0.5s ease-out;
		-ms-transition: all 0.5s ease-out;
		-o-transition: all 0.5s ease-out;
		transition: all 0.5s ease-out;
		pointer-events: none;
		z-index: 99;
	}
	.prepend-icon.file .field-icon {
		left: 0;
	}
</style>

<section class="section-type-4a section-defaulta" style="padding-bottom:0px;">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<h2 class="ui-title-block ui-title-block_light">Sales Representative
					<a href="<?php echo base_url('reports/sales_rep'); ?>" class="btn btn-info btn-icon-split pull-right">
						<span class="icon text-white-50">
							<i class="fas fa-arrow-left"></i>
						</span>
						<span class="text">Back</span>
					</a>
				</h2>
				<div class="ui-decor-1a bg-accent"></div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-11 center-wrapper text-center">
				<div class="card shadow mb-4">
					<div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary"> Edit Record </h6>
                    </div>
					<div class="card-body">
						<form method="POST" id="smart-form" enctype="multipart/form-data" novalidate="novalidate" action="<?php echo base_url('reports/sales_rep').'/'.$salesRep['id'] ?>">
							<div class="form-body">
								<?php
								
								if($this->session->flashdata('error')) :
								?>
								<div class="alert alert-danger" role="alert"><?php echo $this->session->flashdata('error');?></div>
								<?php
								elseif($this->session->flashdata('success')):
								?>
								<div class="alert alert-success" role="alert"><?php echo $this->session->flashdata('success');?></div>
								<?php
								endif;
								?>
								<div class="frm-row">
									<?php
									$image_found = false;
									$image_url = trim(env('AWS_PATH').$salesRep['sales_rep_report_image']);
									if (!empty($salesRep['sales_rep_report_image']) && checkRemoteFile($image_url)):
										$image_found = true;
									?>
									<div class="row form-group">
										<div class="section colm text-center">
											<div style="margin: 20px">
												<img src="<?php echo $image_url;?>" height="200" width="200" style="border-radius: 50%">
											</div>
										</div>
									</div>
									<?php endif; ?>
									<div class="row form-group">
										<div class="section colm col-sm-6">
											<input type="text" class="form-control" name="first_name" value="<?=$salesRep['first_name'];?>" placeholder="First Name">
											<span class="field-icon"><i class="fa fa-user"></i></span>
										</div>
		
										<div class="section colm col-sm-6">
											<input type="text" class="form-control" name="last_name" value="<?=$salesRep['last_name'];?>" placeholder="Last Name">
											<span class="field-icon"><i class="fa fa-user"></i></span>
										</div>
									</div>
									<div class="row form-group">
										<div class="section colm col-sm-6">
											<input type="text" class="form-control" name="title" value="<?=$salesRep['title'];?>" placeholder="Title"> 
											<span class="field-icon"><i class="fa fa-user"></i></span>
										</div>
		
										<div class="section colm col-sm-6">
											<input type="tel" class="form-control" name="telephone_no" value="<?=$salesRep['telephone_no'];?>">
											<span class="field-icon"><i class="fa fa-phone-square"></i></span>
										</div>
									</div>
									
									<div class="row form-group">
										<div class="section colm col-sm-6">
											<input type="email" class="form-control" name="email_address" value="<?=$salesRep['email_address'];?>">
											<span class="field-icon"><i class="fa fa fa-envelope"></i></span>
										</div>
										<div class="section colm col-sm-6 text-center">
												<label class="field prepend-icon file">
												<?php
													$image_url = trim(env('AWS_PATH').$salesRep['sales_rep_report_image']);
													if (!empty($salesRep['sales_rep_report_image']) && $image_found):
												?>
												<span class="button"> Change Image </span>
												<?php
													else:
												?>
												
												<span class="button"> Upload Image </span>
												<?php
													endif;
												?>
												<input type="file" class="gui-file form-control" name="sales_rep_report_image" id="report_image" 
												onChange="document.getElementById('uploader1').value = this.value;">
												<input type="text" class="gui-input form-control" id="uploader1" placeholder="no file selected" readonly>
												<span class="field-icon"><i class="fa fa-upload"></i></span>
											</label>
										</div>
									</div>
									<div class="section colm col-sm-12 text-center">
										<button type="reset" class="btn btn-danger btn-icon-split">
											<span class="icon text-white-50">
												<i class="fas fa-refresh"></i>
											</span>
											<span class="text">Reset</span>
										</button>
										<button type="submit" class="btn btn-success btn-icon-split">
											<span class="icon text-white-50">
												<i class="fas fa-save"></i>
											</span>
											<span class="text">Submit</span>
										</button>
									</div>
											
										
								</div>
							</div>
						</form>
					</div>
				</div>
				
			</div>
		</div>
	</div>
</section>

	
