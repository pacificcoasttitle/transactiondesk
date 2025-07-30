<style>
	.ui-autocomplete { position: absolute; cursor: default;z-index:10000 !important;}
	.ui-autocomplete {
		max-height: 300px !important;
		overflow: hidden !important;
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
</style>

<section class="section-type-4a section-defaulta pd-3" style="padding-bottom:0px;">
	<div class="container-fluid">
		<div class="row mb-3">
			<div class="col-sm-6">
				<h1 class="h3 text-gray-800">Closing Protection Letters </h1>
			</div>
			<!-- <div class="col-sm-6">
				<a href="<?php echo base_url(); ?>order/admin/add-title-officer"  class="btn btn-success btn-icon-split float-right mr-2">
					<span class="icon text-white-50">
						<i class="fas fa-plus"></i>
					</span>
					<span class="text"> Add Title Officer </span>
				</a>
				<?php if (!in_array($roleName, ['CS Admin'])): ?>
					<a href="javascript:void(0);" data-export-type="csv" id="export-title-officer-data" class="btn btn-success btn-icon-split float-right mr-2">
						<span class="icon text-white-50">
							<i class="fas fa-file-export"></i>
						</span>
						<span class="text"> Export </span>
					</a>
				<?php endif;?>
			</div> -->
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
						<tbody></tbody>
					</table>
				</div>
			</div>
		</div>
		<!-- <div class="row">
			<div class="row">
				<div class="col-xs-12">
					<div class="typography-section__inner">
						<h2 class="ui-title-block ui-title-block_light">Closing Protection Letters</h2>
						<div class="ui-decor-1a bg-accent"></div>
						<h3 class="ui-title-block_light">Generate your CPL</h3>
					</div>
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
					<div class="typography-sectiona">
						<div class="col-md-12">
							<div class="table-container">
								<table class="table table-type-3 typography-last-elem no-footer" id="cpl_listing">
									<thead>
										<tr style="text-align: center;">
											<th>#</th>
											<th>File Number</th>
											<th>Property Address</th>
											<th>Created</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>

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
							<!-- <div class="card-header py-3">
								<h6 class="m-0 font-weight-bold text-primary" >Lender Details</h6>
							</div> -->
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
												<div class="col-sm-8 d-flex fs-2">
													<!-- <label for="email_id" class="col-form-label">Email</label> -->
													<input class="radio" type="radio" name="new_existing_lender" id="add_lender" value="add_lender">New Lender
													<input class="radio" type="radio" name="new_existing_lender" id="existing_lender" value="existing_lender">Existing Lender
													<!-- <input name="email_id" required="" type="email" class="form-control" id="email_id"> -->
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
													<!-- <input required="" name="first_name" type="text" id="first-name" class="form-control"> -->
												</div>
											</div>
											<div class="row">
												<div class="col-sm-12">
													<label for="assignment_clause" class="col-form-label">Assignment Clause</label>
													<input type="text" name="assignment_clause" id="assignment_clause" class="form-control gui-input ui-autocomplete-input" placeholder="Assignment Clause">
													<!-- <input required="" name="last_name" type="text" id="last-name" class="form-control"> -->
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