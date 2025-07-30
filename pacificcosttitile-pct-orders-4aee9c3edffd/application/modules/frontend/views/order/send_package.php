<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="author" content="colorlib.com">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Sign Up Form - Buyer</title>
	<link rel="stylesheet" href="<?=base_url('assets/buyer-seller-packets/fonts/material-icon/css/material-design-iconic-font.min.css');?>">
	<link rel="stylesheet" href="<?=base_url('assets/buyer-seller-packets/css/style.css?buyer_v='.time());?>">
	<link rel="stylesheet" href="<?php echo base_url();?>assets/frontend/css/jquery-ui.css">
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/frontend/js/jquery-1.9.1.min.js"></script>	
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/frontend/css/smart-forms.css">
</head>

<style>
	.d-none {
		display: none;
	}

	.preloaderjs .spinner {display: none !important;}.preloaderjs#page-preloader {background: rgba(46, 46, 46, 0.99) !important;}#page-preloader {position: fixed;top: 0;right: 0;bottom: 0;left: 0;width: 100%;height: 100%;background: #2e2e2e;z-index: 100500;}#page-preloader .spinner {position: absolute;top: 50%;left: 50%;display: block;width: 100px;height: 100px;margin-top: -50px;margin-left: -50px;border: 3px solid transparent;border-top-color: #e7e4d7;border-radius: 50%;z-index: 1001;-webkit-animation: spin 2.5s infinite linear;animation: spin 2.5s infinite linear;}#page-preloader .spinner:before, #page-preloader .spinner:after {position: absolute;border-radius: 50%;content: '';}#page-preloader .spinner:before {top: 5px;right: 5px;bottom: 5px;left: 5px;border: 3px solid transparent;border-top-color: #71383e;-webkit-animation: spin 2s infinite linear;animation: spin 2s infinite linear;}#page-preloader .spinner:after {top: 15px;right: 15px;bottom: 15px;left: 15px;border: 3px solid transparent;border-top-color: #efa96b;-webkit-animation: spin 1s infinite linear;animation: spin 1s infinite linear;}@keyframes spin {0% {-webkit-transform: rotate(0);transform: rotate(0);}100% {-webkit-transform: rotate(360deg);transform: rotate(360deg);}}
	body {
		background-position: revert !important;
	}

	.radio {
    top: 5px !important;
    margin: 0px 10px !important;
}

.btn-success {
    color: #fff !important;
    background-color: #28a745 !important;
    border-color: #28a745 !important;
}

.btn-success:hover {
    color: #fff !important;
    background-color: #218838 !important;
    border-color: #169b6b !important;
}

.table-type-3 > thead > tr > th {
	color: #000000;
}

.table-type-3 {
	color: #000000;
}

</style>

<body>
	<div class="main">
		<div id="page-preloader" style="background-color: rgba(0, 0, 0, 0.5);display: none;"><span class="spinner border-t_second_b border-t_prim_a"></span></div>
		<div>
			<div class="container2">
				<img src="<?php echo base_url();?>assets/buyer-seller-packets/images/logo.png" style="width:300px;">
				<h1 style="font-weight:bold;color: #fff;">Buyer & Seller Package </h1>
				<h3 style="margin-top:10px;color: #fff;"><?php echo $orderDetails['full_address'];?></h3>
				<h4 style="margin-bottom:40px;margin-top:10px;color: #fff;">APN:<?php echo $orderDetails['apn'];?> | File# <?php echo $orderDetails['file_number'];?> </h4>

			</div>
		</div>

		<div class="container">
			
			<div>
				<input type="hidden" name="order_id" id="order_id" value="<?php echo $orderDetails['order_id'];?>">
				<div class="row">
					<div class="row" style="background: #f8f8f8;border:1px solid #e0e0e0">
						<div class="col-xs-12" style="height:250px;">
							
							<?php if(!empty($success)) {?>
							<div id="agent_success_msg" class="w-100 alert alert-success alert-dismissible">
								<?php foreach($success as $sucess) {
										echo $sucess."<br \>";	
									}?>
							</div>
							<?php } 
								if(!empty($errors)) {?>
							<div id="agent_error_msg" class="w-100 alert alert-danger alert-dismissible">
								<?php foreach($errors as $error) {
										echo $error."<br \>";	
									}?>
							</div>
							<?php } ?>
							<div class="typography-sectiona" style="color:#000000;">
								<div class="col-md-12">
									<div class="table-container">
										<table class="table table-type-3 typography-last-elem no-footer" id="cpl_listing">
											<thead>
												<tr>
													<th>#</th>
													<th>File Number</th>
													<th>Property Address</th>
													<th>Created</th>
													<th>Action</th>
												</tr>
											</thead>
											<tbody>
												<?php if(!empty($file_number)) {?>
													<tr role="row" class="odd">
														<td>1</td>
														<td><?php echo $file_number;?></td>
														<td><?php echo $full_address;?></td>
														<td><?php echo $created;?></td>
														<td><?php echo $action;?></td> 
													</tr>
												<?php } else { ?>
													<tr role="row" class="odd"><td colspan="4" class="text-center">No record found</td></tr>
												<?php }  ?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" width="500px" id="buyer_welcome" tabindex="-1" role="dialog"
		aria-labelledby="Buyer Infromation" aria-hidden="true">
			<div class="modal-dialog modal-lg" role="document" style="width:40%;">
				<div class="modal-content">
					<form method="POST" action="<?php echo base_url();?>add-buyer-on-order-mail" enctype="multipart/form-data">
						<div class="smart-forms smart-container wrap-2" style="margin:30px">
							<div class="modal-body search-result">
								
									<div id="lender-details-fields" >
										
										<div class="spacer-b25">
											<div class="tagline"><span>Buyer Info</span></div>
										</div>

										<div id="buyer-info-clone-group-fields">
											<div class="toclone clone-widget">
												<div class="frm-row">
													<div class="section colm colm12">
														<label class="field prepend-icon">
															<input type="text" name="buyer_emails[]" id="buyer_email" class="gui-input ui-autocomplete-input"
																placeholder="Email Address" required="required">
															<span class="field-icon"><i class="fa fa-user"></i></span>
														</label>
													</div>

													<div class="section colm colm6">
														<label class="field prepend-icon">
															<input type="text" name="buyer_first_names[]" id="buyer_first_name" class="gui-input ui-autocomplete-input"
																placeholder="First Name" required="required">
															<span class="field-icon"><i class="fa fa-user"></i></span>
														</label>
													</div>
													<div class="section colm colm6">
														<label class="field prepend-icon">
															<input type="text" name="buyer_last_names[]" id="buyer_last_name" class="gui-input ui-autocomplete-input"
																placeholder="Last Name" required="required">
															<span class="field-icon"><i class="fa fa-user"></i></span>
														</label>
													</div>
													<div class="section colm colm12">	
														<label class="field prepend-icon">	
															<input class="radio" type="radio" name="is_main_buyer" id="is_main_buyer" value="is_main_buyer0" required="required">Primary Buyer		
														</label>	
													</div>
												</div>
												<a href="#" class="mr-5 clone button btn btn-success"><i class="fa fa-plus"></i></a>
												<a href="#" class="delete button"><i class="fa fa-minus"></i></a>
											</div>
										</div>							
									</div>
								

								<input type="hidden" name="order_id" id="order_id" value="<?php echo $orderDetails['order_id'];?>">
								<input type="hidden" name="file_id" id="file_id" value="<?php echo $orderDetails['file_id'];?>">
							</div>

							<div class="form-footer" style="padding-top:0px;">
								<button type="submit" data-btntext-sending="Sending..."
									class="button btn btn-success">Submit</button>
								<button type="button" data-dismiss="modal" aria-label="Close" class="button">Cancel</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>

		<div class="modal fade" width="500px" id="seller_welcome" tabindex="-1" role="dialog"
			aria-labelledby="Seller Infromation" aria-hidden="true">
			<div class="modal-dialog modal-lg" role="document" style="width:40%;">
				<div class="modal-content">
					<form method="POST" action="<?php echo base_url();?>add-seller-on-order-mail" enctype="multipart/form-data">
						<div class="smart-forms smart-container wrap-2" style="margin:30px">
							<div class="modal-body search-result">
								
									<div id="lender-details-fields" >
										
										<div class="spacer-b25">
											<div class="tagline"><span>Seller Info</span></div>
										</div>

										<div id="seller-info-clone-group-fields">
											<div class="toclone clone-widget">
												<div class="frm-row">
													<div class="section colm colm12">
														<label class="field prepend-icon">
															<input type="text" name="seller_emails[]" id="seller_email" class="gui-input ui-autocomplete-input"
																placeholder="Email Address" required="required">
															<span class="field-icon"><i class="fa fa-user"></i></span>
														</label>
													</div>

													<div class="section colm colm6">
														<label class="field prepend-icon">
															<input type="text" name="seller_first_names[]" id="seller_first_name" class="gui-input ui-autocomplete-input"
																placeholder="First Name" required="required">
															<span class="field-icon"><i class="fa fa-user"></i></span>
														</label>
													</div>
													<div class="section colm colm6">
														<label class="field prepend-icon">
															<input type="text" name="seller_last_names[]" id="seller_last_name" class="gui-input ui-autocomplete-input"
																placeholder="Last Name" required="required">
															<span class="field-icon"><i class="fa fa-user"></i></span>
														</label>
													</div>
													<div class="section colm colm12">	
														<label class="field prepend-icon">	
															<input class="radio" type="radio" name="is_main_seller" id="is_main_seller" value="is_main_seller0" required="required">Primary Seller		
														</label>	
													</div>
												</div>
												<a href="#" class="mr-5 clone button btn btn-success"><i class="fa fa-plus"></i></a>
												<a href="#" class="delete button"><i class="fa fa-minus"></i></a>
											</div>
										</div>							
									</div>
								

								<input type="hidden" name="order_id" id="order_id" value="<?php echo $orderDetails['order_id'];?>">
								<input type="hidden" name="file_id" id="file_id" value="<?php echo $orderDetails['file_id'];?>">
							</div>

							<div class="form-footer" style="padding-top:0px;">
								<button type="submit" data-btntext-sending="Sending..."
									class="button btn btn-success">Submit</button>
								<button type="button" data-dismiss="modal" aria-label="Close" class="button">Cancel</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>

	<!-- JS -->

	
	<?php
    $this->load->view('layout/footer_script');
?>
	


	<script type="text/javascript" src="<?php echo base_url(); ?>assets/frontend/js/jquery.form.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/frontend/js/jquery.validate.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/frontend/js/additional-methods.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/frontend/js/smart-form.js"></script> 
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/frontend/js/jquery-ui.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/frontend/js/jquery-cloneya.min.js"></script>
	<script>
		/* Lender autocomplete */
		$(document).ready(function () {
			$('#seller-info-clone-group-fields').cloneya({
				maximum: 5
			}).on('after_append.cloneya', function (event, toclone, newclone) {
				var id = $(newclone).find("input[name='is_main_seller']").attr('id');
				$('#'+id).val(id);
			}).off('remove.cloneya').on('remove.cloneya', function (event, clone) {
				$(clone).slideToggle('slow', function () {
					$(clone).remove();
				})
			});
			$('#buyer-info-clone-group-fields').cloneya({
				maximum: 5
			}).on('after_append.cloneya', function (event, toclone, newclone) {
				var id = $(newclone).find("input[name='is_main_seller']").attr('id');
				$('#'+id).val(id);
			}).off('remove.cloneya').on('remove.cloneya', function (event, clone) {
				$(clone).slideToggle('slow', function () {
					$(clone).remove();
				})
			});
		});
	</script>
</body>

</html>
