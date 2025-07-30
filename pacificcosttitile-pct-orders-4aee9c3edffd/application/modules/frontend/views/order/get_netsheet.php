<style>
	.ui-autocomplete {
		position: absolute;
		cursor: default;
		z-index: 10000 !important;
	}

	.ui-autocomplete {
		max-height: 300px !important;
	}

	.radio {
		top: 5px !important;
		margin: 0px 10px !important;
	}

	.radio:before {
		background: none !important;
	}

	th {
		text-align: center;
	}
	.form-grp-title {
		margin-top: 30px;
    	margin-bottom: 20px;
	}
	.form-grp-title .tagline {
		height: 0;
		border-top: 1px solid #D9DDE5;
	}
	.form-grp-title .tagline span {
		text-transform: uppercase;
		display: inline-block;
		position: relative;
		padding: 0 0px;
		background: #ffffff;
		color: #d35411;
		top: -10px;
		font-size: 16px;
		font-weight: 700;
		letter-spacing: 0.25px;
	}
	.mt-0 {
		margin-top: 0px;
	}
	.display-inline {
		display: inline-block;
    	vertical-align: top;
	}
</style>

<body>
	<?php // $this->load->view('layout/header_dashboard'); ?>

	<section class="section-type-4a section-defaulta" style="padding-bottom:100px;">
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="col-sm-6">
                    <h1 class="h3 text-gray-800">Netsheet </h1>
                </div>
            </div>
            <div class="card shadow mb-4">
                <div class="card-header datatable-header py-3">
                    <div class="datatable-header-titles" >
                        <span>
                            <i class="fas fa-users"></i>
                        </span>
                        <h6 class="m-0 font-weight-bold text-primary pl-10">Generate your Netsheet</h6>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (!empty($success)) {?>
                    <div id="agent_success_msg" class="w-100 alert alert-success alert-dismissible">
                        <?php foreach ($success as $sucess) {echo $sucess . "<br \>";}?>
                    </div>
                    <?php }if (!empty($errors)) {?>
                    <div id="agent_error_msg" class="w-100 alert alert-danger alert-dismissible">
                        <?php foreach ($errors as $error) {echo $error . "<br \>";}?>
                    </div>
                    <?php }?>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="cpl_listing" width="100%" cellspacing="0">
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
                                <?php if (!empty($file_number)) {?>
                                <tr role="row" class="odd">
                                    <td>1</td>
                                    <td><?php echo $file_number; ?></td>
                                    <td><?php echo $full_address; ?></td>
                                    <td><?php echo $created; ?></td>
                                    <td><?php echo $action; ?></td>
                                </tr>
                                <?php } else {?>
                                <tr role="row" class="odd">
                                    <td colspan="4" class="text-center">No record found</td>
                                </tr>
                                <?php }?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
			<!-- <div class="row">
				<div class="row">
					<div class="col-xs-12">
						<div class="typography-section__inner" style="padding: 0px 17px;">
							<h2 class="ui-title-block ui-title-block_light">Netsheet</h2>
							<div class="ui-decor-1a bg-accent"></div>
							<h3 class="ui-title-block_light">Generate your Netsheet</h3>
						</div>
						<?php if (!empty($success)) {?>
						<div id="agent_success_msg" class="w-100 alert alert-success alert-dismissible">
							<?php foreach ($success as $sucess) {echo $sucess . "<br \>";}?>
						</div>
						<?php }if (!empty($errors)) {?>
						<div id="agent_error_msg" class="w-100 alert alert-danger alert-dismissible">
							<?php foreach ($errors as $error) {echo $error . "<br \>";}?>
						</div>
						<?php }?>

						<div class="typography-sectiona">
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
											<?php if (!empty($file_number)) {?>
											<tr role="row" class="odd">
												<td>1</td>
												<td><?php echo $file_number; ?></td>
												<td><?php echo $full_address; ?></td>
												<td><?php echo $created; ?></td>
												<td><?php echo $action; ?></td>
											</tr>
											<?php } else {?>
											<tr role="row" class="odd">
												<td colspan="4" class="text-center">No record found</td>
											</tr>
											<?php }?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div> -->
		</div>
	</section>

	<div class="modal fade" width="500px" id="netsheet_information" tabindex="-1" role="dialog" aria-labelledby="Netsheet Infromation" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document" style="width:40%;">
            <div class="modal-content">
				<form method="POST" action="<?php echo base_url(); ?>create-netsheet/<?php echo $random_number; ?>" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card shadow">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary" >Create Netsheet</h6>
                                </div>
                                <div class="card-body">
									<div class="smart-forms smart-container">
                                        <div class="modal-body search-result">
                                            <div class="form-group">
                                                <div class="row form-grp-title mt-0">
                                                    <div class="col-sm-12">
                                                        <div class="tagline"><span> Select Options </span></div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-8 display-inline align-space">
														<input class="radio" type="radio" name="req_type" id="buyer" value="buyer" required="required">Buyer
														<input class="radio" type="radio" name="req_type" id="seller" value="seller">Seller
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group" style="display:none;">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <label for="origin_charge" class="col-form-label">Origin</label>
                                                        <input type="text" name="origin_charge" id="origin_charge" class="form-control gui-input ui-autocomplete-input" placeholder="Origination Charge Fee">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <label for="appraisal_fee" class="col-form-label">Appraisal Fee</label>
                                                        <input type="text" name="appraisal_fee" id="appraisal_fee" class="form-control gui-input ui-autocomplete-input" placeholder="Appraisal Fee">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group" style="display:none;">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <label for="credit_repot" class="col-form-label">Credit Report</label>
                                                        <input type="text" name="credit_repot" id="credit_repot" class="gui-input form-control" placeholder="Credit Report">
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <label for="prepaid_interest" class="col-form-label">Prepaid Interest</label>
                                                        <input type="text" name="prepaid_interest" id="prepaid_interest" class="gui-input form-control" placeholder="Prepaid Interest">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group" style="display: none;">
                                                <div class="col-sm-6" >
                                                    <label for="prepaid_interest_days" class="col-form-label">Prepaid Interest Days</label>
                                                    <input type="text" name="Prepaid Interest Days" id="prepaid_interest_days" class="gui-input form-control" placeholder="prepaid_interest_days" >
                                                </div>
                                            </div>

                                            <div class="form-group" style="display: none;">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <label for="home_ins" class="col-form-label">Home Ins</label>
                                                        <input type="text" name="home_ins" id="home_ins" class="gui-input form-control" placeholder="Home Ins">
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <label for="process_fee" class="col-form-label">Process Fee</label>
                                                        <input type="text" name="process_fee" id="process_fee" class="gui-input form-control" placeholder="Process Fee">
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="form-footer" style="padding: 0px 1rem !important;">

                                            <button type="submit" data-btntext-sending="Sending..." class="btn btn-success btn-icon-split btn-sm">
                                                <span class="icon text-white-50">
                                                    <i class="fas fa-check"></i>
                                                </span>
                                                <span class="text">Submit</span>
                                            </button>

                                            <button type="reset" data-dismiss="modal" aria-label="Close" class="btn btn-danger btn-icon-split btn-sm">
                                                <span class="icon text-white-50">
                                                    <i class="fas fa-ban"></i>
                                                </span>
                                                <span class="text">Cancel</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

	<!-- <div class="modal fade" width="500px" id="netsheet_information" tabindex="-1" role="dialog"
		aria-labelledby="Netsheet Infromation" aria-hidden="true">
		<div class="modal-dialog modal-lg" role="document" style="width:40%;">
			<div class="modal-content">
				<form method="POST" action="<?php echo base_url(); ?>create-netsheet/<?php echo $random_number; ?>" enctype="multipart/form-data">
					<div class="smart-forms smart-container wrap-2" style="margin:30px">
						<div class="modal-body search-result">
							<div id="lender-details-fields" style="">
								<div class="spacer-b20">
									<div class="tagline"><span>Select Options</span></div>
								</div>

								<div class="frm-row">
									<div class="section colm colm12">
										<label class="field prepend-icon">
											<input class="radio" type="radio" name="req_type" id="buyer"
												value="buyer" required="required">Buyer
											<input class="radio" type="radio" name="req_type" id="seller"
												value="seller">Seller
										</label>
									</div>
								</div>

								<div id="buyer_purchase_option" style="display:none;">
									<div class="frm-row">
										<div class="section colm colm6">
											<label class="field prepend-icon">
												<input type="text" name="origin_charge" id="origin_charge" class="gui-input priceInput"
													placeholder="Origination Charge Fee" >
												<span class="field-icon"><i class="fa fa-envelope"></i></span>
											</label>
										</div>

										<div class="section colm colm6">
											<label class="field prepend-icon">
												<input type="text" name="appraisal_fee" id="appraisal_fee" class="gui-input priceInput"
													placeholder="Appraisal Fee" >
												<span class="field-icon"><i class="fa fa-envelope"></i></span>
											</label>
										</div>
									</div>

									<div class="frm-row">
										<div class="section colm colm12">
											<label class="field prepend-icon">
												<input type="text" name="credit_repot" id="credit_repot" class="gui-input priceInput"
													placeholder="Credit Report Fee" >
												<span class="field-icon"><i class="fa fa-envelope"></i></span>
											</label>
										</div>
									</div>

									<div class="frm-row">
										<div class="section colm colm6">
											<label class="field prepend-icon">
												<input type="text" name="prepaid_interest" id="prepaid_interest"
													class="gui-input priceInput" placeholder="Prepaid Interest Rate%" >
												<span class="field-icon"><i class="fa fa-envelope"></i></span>
											</label>
										</div>

										<div class="section colm colm6">
											<label class="field prepend-icon">
												<input type="text" name="prepaid_interest_days" id="prepaid_interest_days"
													class="gui-input" placeholder="Prepaid Interest Days"
													>
												<span class="field-icon"><i class="fa fa-envelope"></i></span>
											</label>
										</div>
									</div>

									<div class="frm-row">
										<div class="section colm colm6">
											<label class="field prepend-icon">
												<input type="text" name="home_ins" id="home_ins" class="gui-input priceInput"
													placeholder="Homeowner’s Insurance Premium">
												<span class="field-icon"><i class="fa fa-envelope"></i></span>
											</label>
										</div>

										<div class="section colm colm6">
											<label class="field prepend-icon">
												<input type="text" name="process_fee" id="process_fee" class="gui-input priceInput"
													placeholder="Processing Fee" >
												<span class="field-icon"><i class="fa fa-envelope"></i></span>
											</label>
										</div>
									</div>
								</div>
							</div>

							<div class="form-footer" style="padding: 0px !important;">
								<button type="submit" data-btntext-sending="Sending..."
									class="button btn-primary">Submit</button>
								<button type="reset" data-dismiss="modal" aria-label="Close"
									class="button">Cancel</button>
							</div>
						</div>
                    </div>
				</form>
			</div>
		</div>
	</div> -->
	<?php // $this->load->view('layout/footer'); ?>
</body>

</html>

<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/frontend/css/smart-forms.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/frontend/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/frontend/css/jquery-ui.css">

<script  type="text/javascript" src="<?php echo base_url(); ?>assets/libs/jquery-1.12.4.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/frontend/js/jquery.form.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/frontend/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/frontend/js/additional-methods.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/frontend/js/smart-form.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/frontend/js/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/buyer-seller-packets/vendor/input-mask/jquery.mask.min.js"></script>


<script>
	$(document).ready(function() {
        $(".priceInput").mask("000,000,000,000,000.00", {reverse: true});
        $(".daysInput").mask("000", {reverse: true});

		$('input[type=radio][name=req_type]').change(function(){
            var radio_val = $(this).val();
            if (radio_val == 'buyer') {
                $('#buyer_purchase_option').show();
				$('#origin_charge').prop('required',true);
				$('#appraisal_fee').prop('required',true);
				$('#credit_repot').prop('required',true);
				$('#prepaid_interest').prop('required',true);
				$('#prepaid_interest_days').prop('required',true);
				$('#home_ins').prop('required',true);
				$('#process_fee').prop('required',true);
            } else {
                $('#buyer_purchase_option').hide();
				$('#origin_charge').prop('required',false);
				$('#appraisal_fee').prop('required',false);
				$('#credit_repot').prop('required',false);
				$('#prepaid_interest').prop('required',false);
				$('#prepaid_interest_days').prop('required',false);
				$('#home_ins').prop('required',false);
				$('#process_fee').prop('required',false);
            }
        });
	});

	function generate_netsheet(formFlag)
    {
        if (formFlag == 1) {
            console.log($(this));
            console.log($(this).form);
			$(this).form.submit();
		} else {
            $('#page-preloader').css('display', 'none');
            $('#netsheet_information').modal('show');
        }
	}

	$("form").submit(function () {
		$('#page-preloader').css('background-color', 'rgba(0,0,0,.5)');
		$('#page-preloader').css('display', 'block');
	});

	function downloadDocumentFromAws(url, documentType) {
		$('#page-preloader').css('background-color', 'rgba(0,0,0,.5)');
		$('#page-preloader').css('display', 'block');
		var fileNameIndex = url.lastIndexOf("/") + 1;
		var filename = url.substr(fileNameIndex);
		$.ajax({
			url: base_url + "download-aws-document",
			type: "post",
			data: {
				url: url
			},
			async: false,
			success: function (response) {
				if (response) {
					if (navigator.msSaveBlob) {
						var csvData = base64toBlob(response, 'application/octet-stream');
						var csvURL = navigator.msSaveBlob(csvData, filename);
						var element = document.createElement('a');
						element.setAttribute('href', csvURL);
						element.setAttribute('download', documentType + "_" + filename);
						element.style.display = 'none';
						document.body.appendChild(element);
						document.body.removeChild(element);
					} else {
						console.log(response);
						var csvURL = 'data:application/octet-stream;base64,' + response;
						var element = document.createElement('a');
						element.setAttribute('href', csvURL);
						element.setAttribute('download', documentType + "_" + filename);
						element.style.display = 'none';
						document.body.appendChild(element);
						element.click();
						document.body.removeChild(element);
					}
				}
				$('#page-preloader').css('display', 'none');
			}
		});
	}
</script>
