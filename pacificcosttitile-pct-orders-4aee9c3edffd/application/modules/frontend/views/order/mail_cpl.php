<style>

	.ui-autocomplete { position: absolute; cursor: default;z-index:10000 !important;}
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
    .fs-2 {
		font-size: 18px;
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
	<?php
// $this->load->view('layout/header_dashboard');
;?>

	<section class="section-type-4a section-defaulta" style="padding-bottom:100px;">
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="col-sm-6">
                    <h1 class="h3 text-gray-800">Closing Protection Letters </h1>
                </div>
            </div>
            <div class="card shadow mb-4">
                <div class="card-header datatable-header py-3">
                    <div class="datatable-header-titles" >
                        <span>
                            <i class="fas fa-users"></i>
                        </span>
                        <h6 class="m-0 font-weight-bold text-primary pl-10">Generate your CPL</h6>
                    </div>
                </div>

                <div class="card-body">
                    <?php if (!empty($success)) {
    ?>
                    <div id="agent_success_msg" class="w-100 alert alert-success alert-dismissible">
                        <?php foreach ($success as $sucess) {
        echo $sucess . "<br \>";
    }?>
                    </div>
                    <?php

}
if (!empty($errors)) {
    ?>
                    <div id="agent_error_msg" class="w-100 alert alert-danger alert-dismissible">
                        <?php foreach ($errors as $error) {
        echo $error . "<br \>";
    }?>
                    </div>
                    <?php

}?>
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
                                    <tr role="row" class="odd"><td colspan="4" class="text-center">No record found</td></tr>
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
							<h2 class="ui-title-block ui-title-block_light">Closing Protection Letters</h2>
							<div class="ui-decor-1a bg-accent"></div>
							<h3 class="ui-title-block_light">Generate your CPL</h3>
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
												<tr role="row" class="odd"><td colspan="4" class="text-center">No record found</td></tr>
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

    <div class="modal fade" width="500px" id="lender_information" tabindex="-1" role="dialog" aria-labelledby="Lender Infromation" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document" style="width:40%;">
            <div class="modal-content">
                <form method="POST" action="<?php echo base_url(); ?>add-lender-order" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card shadow">
                                <div class="card-body">
                                    <div class="smart-forms smart-container">
                                        <div class="modal-body search-result">
                                            <div class="row form-grp-title mt-0">
                                                <div class="col-sm-12">
                                                    <div class="tagline"><span> LENDER DETAILS </span></div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-sm-8 display-inline fs-2">
                                                        <input class="radio" type="radio" name="new_existing_lender" id="add_lender" value="add_lender">New Lender
                                                        <input class="radio" type="radio" name="new_existing_lender" id="existing_lender" value="existing_lender">Existing Lender
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <label for="LenderCompany" class="col-form-label">Lender Company</label>
                                                        <input type="text" name="LenderCompany" id="LenderCompany" class="form-control gui-input ui-autocomplete-input" placeholder="Lender Company Name" required="required">
                                                        <input type="hidden" name="LenderId" id="LenderId" value="">
                                                        <input type="hidden" name="file_id" id="file_id" value="">
                                                        <input type="hidden" name="partner_id" id="partner_id" value="">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <label for="assignment_clause" class="col-form-label">Assignment Clause</label>
                                                        <input type="text" name="assignment_clause" id="assignment_clause" class="form-control gui-input ui-autocomplete-input" placeholder="Assignment Clause">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <label for="LenderName" class="col-form-label">Lender Name</label>
                                                        <input type="text" name="LenderName" id="LenderName" class="gui-input form-control" placeholder="Attention" autocomplete="off">
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <label for="LenderAddress" class="col-form-label">Lender Address</label>
                                                        <input type="text" name="LenderAddress" id="LenderAddress" class="gui-input form-control" placeholder="Lender Address" required="required">
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <label for="LenderCity" class="col-form-label">Lender City</label>
                                                        <input type="text" name="LenderCity" id="LenderCity" class="gui-input form-control" placeholder="Lender City" required="required">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <label for="LenderState" class="col-form-label">Lender State</label>
                                                        <input type="text" name="LenderState" id="LenderState" class="gui-input form-control" placeholder="Lender State">
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <label for="LenderZipcode" class="col-form-label">Lender Zipcode</label>
                                                        <input type="text" name="LenderZipcode" id="LenderZipcode" class="gui-input form-control" placeholder="Lender Zipcode" required="required">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row form-grp-title">
                                                    <div class="col-sm-12">
                                                        <div class="tagline"><span> PROPERTY ADDRESS </span></div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <label for="property_address" class="col-form-label">Property Address</label>
                                                        <input required="required" type="text" class="gui-input form-control" name="property_address" id="property_address" placeholder="Property Address">
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <label for="property_city" class="col-form-label">Property City</label>
                                                        <input type="text" name="property_city" id="property_city" class="gui-input form-control" placeholder="Property City" required="required">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <label for="property_state" class="col-form-label">Property State</label>
                                                        <input type="text" name="property_state" id="property_state" class="gui-input form-control" placeholder="Property State" required="required">
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <label for="property_zipcode" class="col-form-label">Property Zipcode</label>
                                                        <input type="text" name="property_zipcode" id="property_zipcode" class="gui-input form-control" placeholder="Property Zipcode" required="required">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row form-grp-title">
                                                    <div class="col-sm-12">
                                                        <div class="tagline"><span> LOAN DETAILS </span></div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <label for="loan_number" class="col-form-label">Loan Number</label>
                                                        <input required="required" type="text" class="gui-input form-control" name="loan_number" id="loan_number" placeholder="Loan Number">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row form-grp-title">
                                                    <div class="col-sm-12">
                                                        <div class="tagline"><span> BORROWERS & VESTING </span></div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <label for="borrowers_vesting" class="col-form-label">Primary Borrower Name</label>
                                                        <input type="text" name="borrowers_vesting" id="borrowers_vesting" class="gui-input form-control" placeholder="Primary Borrower Name"  required="required">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row form-grp-title">
                                                    <div class="col-sm-12">
                                                        <div class="tagline"><span> SELECT BRANCH </span></div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <label for="branch" class="col-form-label">Select Branch</label>
                                                        <select id="branch" name="branch" class="form-control">
                                                            <option value="">Select Branch</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <input type="hidden" id="cpl_api" name="cpl_api" value="">
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
                    <!-- <div class="smart-forms smart-container wrap-2" style="margin:30px">
                        <div class="modal-body search-result">
                            <div id="lender-details-fields" style="">
                                <div class="spacer-b20">
                                    <div class="tagline"><span>Lender Details</span></div>
                                </div>

                                <div class="frm-row">
                                    <div class="section colm colm12">
                                        <label class="field prepend-icon">
                                            <input class="radio" type="radio" name="new_existing_lender" id="add_lender" value="add_lender">New Lender
                                            <input class="radio" type="radio" name="new_existing_lender" id="existing_lender" value="existing_lender">Existing Lender
                                        </label>
                                    </div>
                                </div>

                                <div class="frm-row">
                                    <div class="section colm colm12">
                                        <label class="field prepend-icon">
                                            <input type="text" name="LenderCompany" id="LenderCompany" class="gui-input ui-autocomplete-input"
                                                placeholder="Lender Company Name" required="required">
                                            <span class="field-icon"><i class="fa fa-user"></i></span>

                                            <input type="hidden" name="LenderId" id="LenderId" value="">
                                            <input type="hidden" name="file_id" id="file_id" value="">
                                            <input type="hidden" name="partner_id" id="partner_id" value="">
                                        </label>
                                    </div>
                                </div>

                                <div class="frm-row">
                                    <div class="section colm colm12">
                                        <label class="field prepend-icon">
                                            <input type="text" name="assignment_clause" id="assignment_clause" class="gui-input ui-autocomplete-input"
                                                placeholder="Assignment Clause">
                                            <span class="field-icon"><i class="fa fa-user"></i></span>
                                        </label>
                                    </div>
                                </div>

                                <div class="frm-row">
                                    <div class="section colm colm12">
                                        <label class="field prepend-icon">
                                            <input type="text" name="LenderName" id="LenderName"
                                                class="gui-input" placeholder="Attention"
                                                autocomplete="off">
                                            <span class="field-icon"><i class="fa fa-user"></i></span>
                                        </label>
                                    </div>
                                </div>

                                <div class="frm-row">
                                    <div class="section colm colm6">
                                        <label class="field prepend-icon">
                                            <input type="text" name="LenderAddress" id="LenderAddress" class="gui-input"
                                                placeholder="Lender Address" required="required">
                                            <span class="field-icon"><i class="fa fa-envelope"></i></span>
                                        </label>
                                    </div>

                                    <div class="section colm colm6">
                                        <label class="field prepend-icon">
                                            <input type="text" name="LenderCity" id="LenderCity" class="gui-input"
                                                placeholder="Lender City" required="required">
                                            <span class="field-icon"><i class="fa fa-envelope"></i></span>
                                        </label>
                                    </div>
                                </div>

                                <div class="frm-row">
                                    <div class="section colm colm6">
                                        <label class="field prepend-icon">
                                            <input type="text" name="LenderState" id="LenderState" class="gui-input"
                                                placeholder="Lender State">
                                            <span class="field-icon"><i class="fa fa-envelope"></i></span>
                                        </label>
                                    </div>

                                    <div class="section colm colm6">
                                        <label class="field prepend-icon">
                                            <input type="text" name="LenderZipcode" id="LenderZipcode" class="gui-input form-control"
                                                placeholder="Lender Zipcode" required="required">
                                            <span class="field-icon"><i class="fa fa-envelope"></i></span>
                                        </label>
                                    </div>
                                </div>

                                <div class="spacer-b20">
                                    <div class="tagline"><span>Property Address</span></div>
                                </div>

                                <div class="frm-row">
                                    <div class="section colm colm6">
                                        <label class="field prepend-icon">
                                            <input required="required" type="text" class="gui-input form-control" name="property_address" id="property_address" placeholder="Property Address">
                                            <span class="field-icon"><i class="fa fa-envelope"></i></span>
                                        </label>
                                    </div>

                                    <div class="section colm colm6">
                                        <label class="field prepend-icon">
                                            <input type="text" name="property_city" id="property_city" class="gui-input"
                                                placeholder="Property City" required="required">
                                            <span class="field-icon"><i class="fa fa-envelope"></i></span>
                                        </label>
                                    </div>
                                </div>

                                <div class="frm-row">
                                    <div class="section colm colm6">
                                        <label class="field prepend-icon">
                                            <input type="text" name="property_state" id="property_state" class="gui-input"
                                                placeholder="Property State" required="required">
                                            <span class="field-icon"><i class="fa fa-envelope"></i></span>
                                        </label>
                                    </div>

                                    <div class="section colm colm6">
                                        <label class="field prepend-icon">
                                            <input type="text" name="property_zipcode" id="property_zipcode" class="gui-input"
                                                placeholder="Property Zipcode" required="required">
                                            <span class="field-icon"><i class="fa fa-envelope"></i></span>
                                        </label>
                                    </div>
                                </div>

                                <div class="spacer-b20">
                                    <div class="tagline"><span>Loan Details</span></div>
                                </div>

                                <div class="frm-row spacer-b15">
                                    <div class="section colm colm12">
                                        <label class="field">
                                            <input required="required" type="text" class="gui-input" name="loan_number" id="loan_number" placeholder="Loan Number">
                                        </label>
                                    </div>
                                </div>

                                <div class="spacer-b20">
                                    <div class="tagline"><span>Borrowers & Vesting</span></div>
                                </div>

                                <div class="frm-row spacer-b15">
                                    <div class="section colm colm12">
                                        <label class="field prepend-icon">
                                            <input type="text" name="borrowers_vesting" id="borrowers_vesting" class="gui-input"
                                                placeholder="Primary Borrower Name"  required="required">
                                            <span class="field-icon"><i class="fa fa-user"></i></span>
                                        </label>
                                    </div>
                                </div>


                                <div id="fnf">
                                    <div class="spacer-b20">
                                        <div class="tagline"><span>Select Branch</span></div>
                                    </div>

                                    <div class="frm-row">
                                        <div class="section colm colm12">
                                        <label class="field select">
                                                <select id="branch" name="branch">
                                                    <option value="">Select Branch</option>
                                                </select>
                                                <i class="arrow double"></i>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-footer" style="margin: 0px 20px;">
                            <button type="submit" data-btntext-sending="Sending..."
                                class="button btn-primary">Submit</button>
                            <button type="reset" data-dismiss="modal" aria-label="Close" class="button">Cancel</button>
                        </div>
                    </div> -->
                </form>
            </div>
        </div>
    </div>

	<!-- <div class="modal fade" width="500px" id="lender_information" tabindex="-1" role="dialog"
		aria-labelledby="Lender Infromation" aria-hidden="true">
		<div class="modal-dialog modal-lg" role="document" style="width:40%;">
			<div class="modal-content">
				<form method="POST" action="<?php echo base_url(); ?>add-lender-order" enctype="multipart/form-data">
					<div class="smart-forms smart-container wrap-2" style="margin:30px">
						<div class="modal-body search-result">
							<div id="lender-details-fields" style="">
								<div class="spacer-b20">
									<div class="tagline"><span>Lender Details</span></div>
								</div>

								<div class="frm-row">
									<div class="section colm colm12">
										<label class="field prepend-icon">
											<input class="radio" type="radio" name="new_existing_lender" id="add_lender" value="add_lender">New Lender
											<input class="radio" type="radio" name="new_existing_lender" id="existing_lender" value="existing_lender">Existing Lender
										</label>
									</div>
								</div>

								<div class="frm-row">
									<div class="section colm colm12">
										<label class="field prepend-icon">
											<input type="text" name="LenderCompany" id="LenderCompany" class="gui-input ui-autocomplete-input" placeholder="Lender Company Name" required="required">
											<span class="field-icon"><i class="fa fa-user"></i></span>

											<input type="hidden" name="LenderId" id="LenderId" value="">
											<input type="hidden" name="file_id" id="file_id" value="">
											<input type="hidden" name="partner_id" id="partner_id" value="">
										</label>
									</div>
								</div>

								<div class="frm-row">
									<div class="section colm colm12">
										<label class="field prepend-icon">
											<input type="text" name="assignment_clause" id="assignment_clause" class="gui-input ui-autocomplete-input"
												placeholder="Assignment Clause">
											<span class="field-icon"><i class="fa fa-user"></i></span>
										</label>
									</div>
								</div>

								<div class="frm-row">
									<div class="section colm colm12">
										<label class="field prepend-icon">
											<input type="text" name="LenderName" id="LenderName"
												class="gui-input" placeholder="Attention"
												autocomplete="off">
											<span class="field-icon"><i class="fa fa-user"></i></span>
										</label>
									</div>
								</div>

								<div class="frm-row">
									<div class="section colm colm6">
										<label class="field prepend-icon">
											<input type="text" name="LenderAddress" id="LenderAddress" class="gui-input"
												placeholder="Lender Address" required="required">
											<span class="field-icon"><i class="fa fa-envelope"></i></span>
										</label>
									</div>

									<div class="section colm colm6">
										<label class="field prepend-icon">
											<input type="text" name="LenderCity" id="LenderCity" class="gui-input"
												placeholder="Lender City" required="required">
											<span class="field-icon"><i class="fa fa-envelope"></i></span>
										</label>
									</div>
								</div>

								<div class="frm-row">
									<div class="section colm colm6">
										<label class="field prepend-icon">
											<input type="text" name="LenderState" id="LenderState" class="gui-input"
												placeholder="Lender State">
											<span class="field-icon"><i class="fa fa-envelope"></i></span>
										</label>
									</div>

									<div class="section colm colm6">
										<label class="field prepend-icon">
											<input type="text" name="LenderZipcode" id="LenderZipcode" class="gui-input"
												placeholder="Lender Zipcode" required="required">
											<span class="field-icon"><i class="fa fa-envelope"></i></span>
										</label>
									</div>
								</div>

								<div class="spacer-b20">
									<div class="tagline"><span>Property Address</span></div>
								</div>

								<div class="frm-row">
									<div class="section colm colm6">
										<label class="field prepend-icon">
											<input required="required" type="text" class="gui-input" name="property_address" id="property_address" placeholder="Property Address">
											<span class="field-icon"><i class="fa fa-envelope"></i></span>
										</label>
									</div>

									<div class="section colm colm6">
										<label class="field prepend-icon">
											<input type="text" name="property_city" id="property_city" class="gui-input"
												placeholder="Property City" required="required">
											<span class="field-icon"><i class="fa fa-envelope"></i></span>
										</label>
									</div>
								</div>

								<div class="frm-row">
									<div class="section colm colm6">
										<label class="field prepend-icon">
											<input type="text" name="property_state" id="property_state" class="gui-input" placeholder="Property State">
											<span class="field-icon"><i class="fa fa-envelope"></i></span>
										</label>
									</div>

									<div class="section colm colm6">
										<label class="field prepend-icon">
											<input type="text" name="property_zipcode" id="property_zipcode" class="gui-input"
												placeholder="Property Zipcode" required="required">
											<span class="field-icon"><i class="fa fa-envelope"></i></span>
										</label>
									</div>
								</div>

								<div class="spacer-b20">
									<div class="tagline"><span>Loan Details</span></div>
								</div>

								<div class="frm-row spacer-b15">
									<div class="section colm colm12">
										<label class="field">
											<input required="required" type="text" class="gui-input" name="loan_number" id="loan_number" placeholder="Loan Number">
										</label>
									</div>
								</div>

								<div class="spacer-b20">
									<div class="tagline"><span>Borrowers & Vesting</span></div>
								</div>

								<div class="frm-row spacer-b15">
									<div class="section colm colm12">
										<label class="field prepend-icon">
											<input type="text" name="borrowers_vesting" id="borrowers_vesting" class="gui-input"
												placeholder="Borrowers & Vesting"  required="required">
											<span class="field-icon"><i class="fa fa-user"></i></span>
										</label>
									</div>
								</div>

								<input type="hidden" id="cpl_api" name="cpl_api" value="">

								<div id="fnf">
									<div class="spacer-b20">
										<div class="tagline"><span>Select Branch</span></div>
									</div>
									<div class="frm-row">
										<div class="section colm colm12">
										<label class="field select">
												<select id="branch" name="branch">
													<option value="">Select Branch</option>
												</select>
												<i class="arrow double"></i>
											</label>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="form-footer" style="margin: 0px 20px;">
							<button type="submit" data-btntext-sending="Sending..."
								class="button btn-primary">Submit</button>
							<button type="reset" data-dismiss="modal" aria-label="Close" class="button">Cancel</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div> -->
	<?php
// $this->load->view('layout/footer');
;?>
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


<script>
	/* Lender autocomplete */

	$("#LenderCompany" ).focusin(function() {
		if ($('input[name="new_existing_lender"]:checked').val() == 'existing_lender') {
			if($('.ui-widget.ui-autocomplete').length > 0) {
				$('#LenderCompany').autocomplete( "enable" );
			}
			$("#LenderCompany").autocomplete({
				source: function(request, response) {
					$.ajax({
						url: base_url+'getDetailsByName',
						data: {
							term : request.term,//the value of the input is here
							is_escrow : 0
						},
						type: "POST",
						dataType: "json",
						success: function (data) {
							if (data.length > 0) {
								response($.map(data, function (item) {
									return item;
								}))
							} else {
								response([{ label: 'No results found.', val: -1}]);
							}
						}
					});
				},
				delay: 0,
				minLength: 3,
				select: function( event, ui ) {
					event.preventDefault();
					$("#LenderCompany").val(ui.item.company);

					if(ui.item.state) {
						$("#LenderState").val(ui.item.state).parent().addClass('state-success');
					} else {
						$("#LenderState").val('').parent().removeClass('state-success').addClass('state-error');
					}

					if(ui.item.name) {
						$("#LenderName").val(ui.item.name).parent().addClass('state-success');
					} else {
						$("#LenderName").val('').parent().removeClass('state-success').addClass('state-error');
					}

					if(ui.item.address) {
						$("#LenderAddress").val(ui.item.address).parent().addClass('state-success');
					} else {
						$("#LenderAddress").val('').parent().removeClass('state-success').addClass('state-error');
					}

					if(ui.item.city) {
						$("#LenderCity").val(ui.item.city).parent().addClass('state-success');
					} else {
						$("#LenderCity").val('').parent().removeClass('state-success').addClass('state-error');
					}

					if(ui.item.zip_code) {
						$("#LenderZipcode").val(ui.item.zip_code).parent().addClass('state-success');
					} else {
						$("#LenderZipcode").val('').parent().removeClass('state-success').addClass('state-error');
					}

					if(ui.item.assignment_clause) {
						$("#assignment_clause").val(ui.item.assignment_clause);
					} else {
						$("#assignment_clause").val('');
					}
					$("#LenderId").val(ui.item.id);

				},
				change: function( event, ui ) {
					if (ui.item == null)
					{
						$("#LenderState").val('').parent().removeClass('state-success').addClass('state-error');
						$("#LenderCompany").val('').parent().removeClass('state-success').addClass('state-error');
						$("#LenderAddress").val('').parent().removeClass('state-success').addClass('state-error');
						$("#LenderCity").val('').parent().removeClass('state-success').addClass('state-error');
						$("#LenderZipcode").val('').parent().removeClass('state-success').addClass('state-error');
						$("#assignment_clause").val('');
						$("#LenderId").val('');
					}
						}
					});
		} else {
			if($('.ui-widget.ui-autocomplete').length > 0) {
				$('#LenderCompany').autocomplete( "disable" );
			}
			// $("#LenderCompany").autocomplete({
			// 	source: function(request, response) {
			// 		$.ajax({
			// 			url: base_url+"admin/order/home/get_company_list",
			// 			data: {
			// 				term : request.term
			// 			},
			// 			type: "POST",
			// 			dataType: "json",
			// 			success: function (data) {
			// 				if (data.length > 0) {
			// 					response($.map(data, function (item) {
			// 						return item;
			// 					}))
			// 				} else {
			// 					response([{ label: 'No results found.', val: -1}]);
			// 				}
			// 			}
			// 		});
			// 	},
			// 	delay: 0,
			// 	minLength: 3,
			// 	select: function( event, ui ) {
			// 		event.preventDefault();
			// 		$("#LenderCompany").val(ui.item.partner_name);

			// 		if(ui.item.address1) {
			// 			$("#LenderAddress").val(ui.item.address1).parent().addClass('state-success');
			// 		} else {
			// 			$("#LenderAddress").val('').parent().removeClass('state-success').addClass('state-error');
			// 		}

			// 		if(ui.item.city) {
			// 			$("#LenderCity").val(ui.item.city).parent().addClass('state-success');
			// 		} else {
			// 			$("#LenderCity").val('').parent().removeClass('state-success').addClass('state-error');
			// 		}

			// 		if(ui.item.zip) {
			// 			$("#LenderZipcode").val(ui.item.zip).parent().addClass('state-success');
			// 		} else {
			// 			$("#LenderZipcode").val('').parent().removeClass('state-success').addClass('state-error');
			// 		}
			// 		$("#LenderId").val('');
			// 		$("#state").val(ui.item.state);
			// 		$("#partner_id").val(ui.item.partner_id);
			// 	},
			// 	change: function( event, ui ) {
			// 		if (ui.item == null) {
			// 			$("#LenderCompany").parent().removeClass('state-success').addClass('state-error');
			// 		}
			// 	}
			// });
        }
    });
	/* Lender autocomplete */

	/* Agent autocomplete */
    $("#agent_name").autocomplete({
        source: function(request, response) {
            $.ajax({
                url: base_url+'agent/getAgentDetails',
                data: {
                    term : request.term
                },
                type: "POST",
                dataType: "json",
                success: function (data) {
					if (data.length > 0) {
						response($.map(data, function (item) {
							return item;
						}))
					} else {
						response([{ label: 'No results found.', val: -1}]);
					}
				}
            });
		},
		delay: 0,
		minLength: 3,
        select: function( event, ui ) {
            event.preventDefault();
			$("#agent_name").val(ui.item.name);
			$("#agent_id").val(ui.item.id);
        },
        change: function( event, ui ) {

        }
    });
	/* Agent autocomplete */

	$(document).ready(function () {
		$("input[name=new_existing_lender]").change(function(){
			$("#LenderName").val('');
			$("#LenderState").val('');
			$("#LenderCompany").val('');
			$("#LenderAddress").val('');
			$("#LenderCity").val('');
			$("#LenderZipcode").val('');
			$("#assignment_clause").val('');
			$("#LenderId").val('');
		});
	});

    function lender_pop_up(lenderFlag, fileId)
    {
		if (lenderFlag == 1) {
			$(this).form.submit();
		} else {
			$('#page-preloader').css('background-color', 'rgba(0,0,0,.5)');
			$('#page-preloader').css('display', 'block');
			$.ajax({
				url: base_url + "get-order-details-cpl",
				type: "post",
				data: {
					fileId: fileId,
					requestFrom: 'generic-form',
				},
				success: function (response) {
					var res = jQuery.parseJSON(response);
					if(res.status == 'success') {
						var optionsAsString = "";
						for(var i = 0; i < res.orderDetails['agents_data'].length; i++) {
							var selected = '';
							if(res.orderDetails['agents_data'][i]['id'] == res.orderDetails['fnf_agent_id']) {
								selected = 'selected';
							}
							if (res.orderDetails['cpl_api'] == 'westcor' || res.orderDetails['cpl_api'] == 'natic' || res.orderDetails['cpl_api'] == 'doma') {
								optionsAsString += "<option "+ selected +" value='" + res.orderDetails['agents_data'][i]['id'] + "'>" + res.orderDetails['agents_data'][i]['city'] + "</option>";
							} else {
								optionsAsString += "<option "+ selected +" value='" + res.orderDetails['agents_data'][i]['id'] + "'>" + res.orderDetails['agents_data'][i]['location_city'] + "</option>";
							}

						}
						$('select[name="branch"]').children('option:not(:first)').remove();
						$( 'select[name="branch"]' ).append( optionsAsString );
						$("#branch").prop('required',true);

						$('#cpl_api').val(res.orderDetails['cpl_api']);
						$("#LenderName").val(res.orderDetails['lender_name']);
						$("#LenderState").val(res.orderDetails['lender_state']);
						$("#LenderCompany").val(res.orderDetails['lender_company_name']);
						$("#assignment_clause").val(res.orderDetails['lender_assignment_clause']);
						$("#LenderAddress").val(res.orderDetails['lender_address']);
						$("#LenderCity").val(res.orderDetails['lender_city']);
						$("#LenderZipcode").val(res.orderDetails['lender_zipcode']);
						$("#LenderId").val(res.orderDetails['lender_id']);
						$("#borrowers_vesting").val(res.orderDetails['borrowers_vesting']);
						$("#loan_number").val(res.orderDetails['loan_number']);
						if(res.orderDetails['unit_number']) {
							$("#property_address").val(res.orderDetails['unit_number']+", "+res.orderDetails['property_address']);
						} else {
							$("#property_address").val(res.orderDetails['property_address']);
						}
						$("#property_city").val(res.orderDetails['property_city']);
						$("#property_state").val(res.orderDetails['property_state']);
						$("#property_zipcode").val(res.orderDetails['property_zipcode']);
						if (res.orderDetails['lender_id'] != '') {
							$("#existing_lender").prop("checked", true);
						} else {
							$("#add_lender").prop("checked", true);
						}
					}
					$('#page-preloader').css('display', 'none');
					$('#lender_information').modal('show');
					$('#file_id').val(fileId);
				}
			});
			return false;
		}
	}

	$("form").submit(function(){
		$('#page-preloader').css('background-color', 'rgba(0,0,0,.5)');
		$('#page-preloader').css('display', 'block');
	});

	function base64toBlob(base64Data, contentType) {
		contentType = contentType || '';
		var sliceSize = 1024;
		var byteCharacters = atob(base64Data);
		var bytesLength = byteCharacters.length;
		var slicesCount = Math.ceil(bytesLength / sliceSize);
		var byteArrays = new Array(slicesCount);

		for (var sliceIndex = 0; sliceIndex < slicesCount; ++sliceIndex) {
			var begin = sliceIndex * sliceSize;
			var end = Math.min(begin + sliceSize, bytesLength);

			var bytes = new Array(end - begin);
			for (var offset = begin, i = 0; offset < end; ++i, ++offset) {
				bytes[i] = byteCharacters[offset].charCodeAt(0);
			}
			byteArrays[sliceIndex] = new Uint8Array(bytes);
		}
		return new Blob(byteArrays, {
			type: contentType
		});
	}

	function downloadDocumentFromAws(url, documentType)
    {
        $('#page-preloader').css('background-color', 'rgba(0,0,0,.5)');
		$('#page-preloader').css('display', 'block');
        var fileNameIndex = url.lastIndexOf("/") + 1;
        var filename = url.substr(fileNameIndex);
        $.ajax({
			url: base_url + "download-aws-document",
			type: "post",
			data: {
				url : url
			},
            async: false,
			success: function (response) {
				if (response) {
					if (navigator.msSaveBlob) {
						var csvData = base64toBlob(response, 'application/octet-stream');
						var csvURL = navigator.msSaveBlob(csvData, filename);
						var element = document.createElement('a');
						element.setAttribute('href', csvURL);
						element.setAttribute('download', documentType+"_"+filename);
						element.style.display = 'none';
						document.body.appendChild(element);
						document.body.removeChild(element);
					} else {
						console.log(response);
						var csvURL = 'data:application/octet-stream;base64,' + response;
						var element = document.createElement('a');
						element.setAttribute('href', csvURL);
						element.setAttribute('download', documentType+"_"+filename);
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
