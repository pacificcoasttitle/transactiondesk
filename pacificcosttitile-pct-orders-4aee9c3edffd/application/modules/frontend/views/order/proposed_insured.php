<style>
	.smart-forms .prepend-icon .field-icon {
		top: 14px !important;
	}
	.ui-autocomplete { position: absolute; cursor: default;z-index:10000 !important;} 
	.error {
		color: #FF2F0F !important;
	}
	.ui-helper-clearfix:before, .ui-helper-clearfix:after {
		border: none !important;
	}
	.ui-datepicker {
		margin-top: 0px !important;
	}

	/* table#orders_listing tr td:last-child {
		display: inline-flex;
	} */

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
	td.dataTables_empty {
		display: table-cell !important;
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
<section class="section-type-4a section-defaulta pd-3">
	<div class="container-fluid">
		<div class="row mb-3">
			<div class="col-sm-6">
				<h1 class="h3 text-gray-800">Generate Proposed Insured </h1>
			</div>
		</div>
		<div class="card shadow mb-4">
			<div class="card-header datatable-header py-3">
				<div class="datatable-header-titles" > 
					<span>
						<i class="fas fa-file"></i>
					</span>
					<h6 class="m-0 font-weight-bold text-primary pl-10">Below are all files</h6> 
				</div>
			</div>
		
			<div class="card-body">
				<div class="table-responsive">
					<table class="table table-bordered" id="orders_listing" width="100%" cellspacing="0">
						<thead>
							<tr>
								<th>#</th>
								<th>File Number</th>
								<th>Property Address</th>
								<th>Created</th>
								<th style="text-align: center;">Action</th>
							</tr>
						</thead>                
						<tbody></tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</section>
<!-- <section class="section-type-4a section-defaulta" style="padding-bottom:0px;">
	<div class="container">
		<div class="row">
			<div class="row">
				<div class="col-xs-12">
					<div class="typography-section__inner">
						<h2 class="ui-title-block ui-title-block_light">Generate Proposed Insured</h2>
						<div class="ui-decor-1a bg-accent"></div>
						<h3 class="ui-title-block_light">Below are all files</h3>
					</div>
					<div class="typography-sectiona">
						<div class="col-md-12">
							<div class="table-container">
								<table class="table table-type-3 typography-last-elem no-footer" id="orders_listing">
									<thead>
										<tr>
											<th>#</th>
											<th>File Number</th>
											<th>Property Address</th>
											<th>Created</th>
											<th style="text-align: center;">Action</th>
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
		</div>
	</div>
</section> -->
	
<div class="modal fade" width="500px" id="lender_information" tabindex="-1" role="dialog"
	aria-labelledby="Lender Infromation" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document" style="width:40%;">
		<div class="modal-content">
			<form method="POST" id="add-order-details" enctype="multipart/form-data">
				<div class="row">
					<div class="col-lg-12">
						<div class="card shadow">
							<div class="card-header py-3">
								<h6 class="m-0 font-weight-bold text-primary" >Lender Details</h6>
							</div>
							<div class="card-body"> 
								<div class="smart-forms smart-container">
									<div class="modal-body search-result">

										<input type="hidden" name="orderId" value="" id="orderId">
										<input type="hidden" name="property_id" value="" id="property_id">
										<input type="hidden" name="transaction_id" value="" id="transaction_id">
										<input type="hidden" name="fileId" value="" id="fileId">
										<input type="hidden" name="LenderId" value="" id="LenderId">
										<input type="hidden" name="partner_id" id="partner_id" value="">
										<input type="hidden" name="state" id="state" value="">
										
										<div class="form-group">
											<div class="row form-grp-title mt-0">
												<div class="col-sm-12">
													<div class="tagline"><span> LENDER DETAILS </span></div>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-8 d-flex align-space">
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
											<div class="col-sm-6" style="display: none;">
												<label for="LenderName" class="col-form-label">Lender Name</label>
												<input type="text" name="LenderName" id="LenderName" class="gui-input form-control" placeholder="Attention" autocomplete="off">
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
													<div class="tagline"><span> PROPERTY DETAILS </span></div>
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
													<div class="tagline"><span> TITLE OFFICER DETAILS </span></div>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-12">
													<label for="TitleOfficer" class="col-form-label">Title Officer Details</label>
													<select id="TitleOfficer" name="TitleOfficer" class="gui-input form-control" >
														<option value="">Title Officer</option>
														<?php 
														if(isset($titleOfficer) && !empty($titleOfficer))
														{
															foreach ($titleOfficer as $key => $value) 
															{
														?>
																<option value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
														<?php
															}
														}
														?>
													</select>
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
												<div class="col-sm-6">
													<label for="loan_amount" class="col-form-label">Loan Amount</label>
													<input required="required" type="text" class="gui-input form-control" name="loan_amount" id="loan_amount" placeholder="Loan Number">
												</div>
												<div class="col-sm-6">
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
													<div class="tagline"><span> REPORT DATE SECTION </span></div>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-12">
													<label for="Supplemental_report_date" class="col-form-label">Supplemental Report Date</label>
													<input required="required" type="text" class="gui-input form-control" name="supplemental_report_date" id="Supplemental_report_date" placeholder="Supplemental Report Date" value="<?php echo date('m/d/Y'); ?>">
												</div>
											</div>
											<div class="row">
												<div class="col-sm-6" style="display:none;">
													<label for="preliminary_report_date" class="col-form-label">Preliminary Report Date</label>
													<input required="required" type="text" class="gui-input form-control" name="preliminary_report_date" id="preliminary_report_date" placeholder="Preliminary Report Date" value="<?php echo date('m/d/Y'); ?>">
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
														<?php 
															if (isset($proposedBranches) && !empty($proposedBranches)) {
																foreach ($proposedBranches as $proposedBranch) {
														?>
															<option value="<?php echo $proposedBranch['id']; ?>"><?php echo $proposedBranch['city']; ?></option>
														<?php
																}
															}
														?>
													</select>
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
				<!-- <input type="hidden" name="orderId" value="" id="orderId">

				<input type="hidden" name="property_id" value="" id="property_id">

				<input type="hidden" name="transaction_id" value="" id="transaction_id">

				<input type="hidden" name="fileId" value="" id="fileId">

				<input type="hidden" name="LenderId" value="" id="LenderId">
				<input type="hidden" name="partner_id" id="partner_id" value="">
				<input type="hidden" name="state" id="state" value="">
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
									<input type="text" name="LenderCompany" id="LenderCompany" class="gui-input ui-autocomplete-input"
										placeholder="Lender Company Name">
									<span class="field-icon"><i class="fa fa-user"></i></span>
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
						<div class="frm-row" style="display: none;">
							<div class="section colm colm12">
								<label class="field prepend-icon">
									<input type="text" name="LenderName" id="LenderName" class="gui-input ui-autocomplete-input" placeholder="Loan Officer">
									<span class="field-icon"><i class="fa fa-user"></i></span>
									
									
								</label>
							</div>
						</div>
						<div class="frm-row">
							<div class="section colm colm6">
								<label class="field prepend-icon">
									<input type="text" name="LenderAddress" id="LenderAddress" class="gui-input"
										placeholder="Lender Address" >
									<span class="field-icon"><i class="fa fa-envelope"></i></span>
								</label>
							</div>
							<div class="section colm colm6">
								<label class="field prepend-icon">
									<input type="text" name="LenderCity" id="LenderCity" class="gui-input"
										placeholder="Lender City" >
									<span class="field-icon"><i class="fa fa-user"></i></span>
								</label>
							</div>
						</div>
						<div class="frm-row spacer-b15">
							<div class="section colm colm6">
								<label class="field prepend-icon">
									<input type="text" name="LenderState" id="LenderState" class="gui-input"
										placeholder="Lender State" >
									<span class="field-icon"><i class="fa fa-envelope"></i></span>
								</label>
							</div>
							<div class="section colm colm6">
								<label class="field prepend-icon">
									<input type="text" name="LenderZipcode" id="LenderZipcode" class="gui-input"
										placeholder="Lender Zipcode" >
									<span class="field-icon"><i class="fa fa-envelope"></i></span>
								</label>
							</div>
						</div>
						</div>
						<div id="property-details-section">
							<div class="spacer-b20">
								<div class="tagline"><span>Property Details</span></div>
							</div>

							<div class="frm-row">
								<div class="section colm colm6">
									<label class="field">
										<input required="required" type="text" class="gui-input" name="property_address" id="property_address" placeholder="Street Address">
									</label>
								</div>
								<div class="section colm colm6">
									<label class="field">
										<input required="required" type="text" class="gui-input" name="property_city" id="property_city" placeholder="City">
									</label>
								</div>
							</div>

							<div class="frm-row">
								<div class="section colm colm6">
									<label class="field">
										<input required="required" type="text" class="gui-input" name="property_state" id="property_state" placeholder="State">
									</label>
								</div>
								<div class="section colm colm6">
									<label class="field">
										<input required="required" type="text" class="gui-input" name="property_zipcode" id="property_zipcode" placeholder="Zipcode">
									</label>
								</div>
							</div>
						</div>
						<div id="title-officer-section">
						<div class="spacer-b20">
							<div class="tagline"><span>Title Officer Details</span></div>
						</div>
						<div class="frm-row">
							<div class="section colm colm12">
								<label class="field select">
									<select id="TitleOfficer" name="TitleOfficer">
										<option value="">Title Officer</option>
										<?php 
										if(isset($titleOfficer) && !empty($titleOfficer))
										{
											foreach ($titleOfficer as $key => $value) 
											{
									?>
												<option value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
									<?php
											}
										}
									?>
									</select>
									<i class="arrow double"></i>                    
								</label> 
							</div>
						</div>
						</div>
						<div id="loan-details-section">
						<div class="spacer-b20">
							<div class="tagline"><span>Loan Details</span></div>
						</div>
						<div class="frm-row spacer-b15">
								<div class="section colm colm6">
									<label class="field">
										<input type="text" class="gui-input" name="loan_amount" id="loan_amount" placeholder="Loan Amount">
									</label>
								</div>
								<div class="section colm colm6">
									<label class="field">
										<input type="text" class="gui-input" name="loan_number" id="loan_number" placeholder="Loan Number">
									</label>
								</div>
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
						<div id="report-date-section">
						<div class="spacer-b20">
							<div class="tagline"><span>Report Date Section</span></div>
						</div>
						<div class="frm-row">
							<div class="section colm colm12" id="s-date-section">
								<label class="field prepend-icon">
									<input type="text" name="supplemental_report_date" id="supplemental_report_date" class="gui-input" placeholder="Supplemental Report Date" value="<?php echo date('m/d/Y'); ?>">
									<span class="field-icon"><i class="fa fa-calendar"></i></span>
								</label>
							</div>
							<div class="section colm colm6" id="p-date-section" style="display: none;">
								<label class="field prepend-icon">
									<input type="text" name="preliminary_report_date" id="preliminary_report_date" class="gui-input" placeholder="Preliminary Report Date" value="<?php echo date('m/d/Y'); ?>">
									<span class="field-icon"><i class="fa fa-calendar"></i></span>
								</label>
							</div>
						</div>
						
						</div>
						<div class="spacer-b20">
							<div class="tagline"><span>Select Branch</span></div>
						</div>
						<div class="frm-row">
							<div class="section colm colm12">
								<label class="field select">
									<select id="branch" name="branch">
										<option value="">Select Branch</option>
										<?php 
											if (isset($proposedBranches) && !empty($proposedBranches)) {
												foreach ($proposedBranches as $proposedBranch) {
										?>
											<option value="<?php echo $proposedBranch['id']; ?>"><?php echo $proposedBranch['city']; ?></option>
										<?php
												}
											}
										?>
									</select>
									<i class="arrow double"></i>                    
								</label> 
							</div>
						</div>
					</div>
					<div class="form-footer" style="margin: 0px 20px;">
						<button type="submit" data-btntext-sending="Sending..." class="button btn-primary">Submit</button>
						<button type="reset" data-dismiss="modal" aria-label="Close" class="button">Cancel</button>
					</div>
				</div> -->
			</form>
		</div>
	</div>
</div>

<!-- Edit info modal -->
<div class="modal fade" width="500px" id="edit_information" tabindex="-1" role="dialog"
	aria-labelledby="Lender Infromation" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document" style="width:40%;">
		<div class="modal-content">
			<form method="POST" id="edit-order-details" enctype="multipart/form-data">
				<div class="row">
					<div class="col-lg-12">
						<div class="card shadow">
							<!-- <div class="card-header py-3">
								<h6 class="m-0 font-weight-bold text-primary" >Lender Details</h6>
							</div> -->
							<div class="card-body"> 
								<div class="smart-forms smart-container">
									<div class="modal-body search-result">

										<input type="hidden" name="orderId" value="" id="edit_orderId">
										<input type="hidden" name="property_id" value="" id="edit_property_id">
										<input type="hidden" id="edit_transaction_id" value="" name="transaction_id">
										<input type="hidden" name="fileId" value="" id="edit_fileId">
										<input type="hidden" name="LenderId" value="" id="edit_LenderId">
										<input type="hidden" name="partner_id" id="edit_partner_id" value="">	
										<input type="hidden" name="state" id="edit_state" value="">
										
										<div class="form-group">
											<div class="row form-grp-title mt-0">
												<div class="col-sm-12">
													<div class="tagline"><span> LENDER DETAILS </span></div>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-8 d-flex align-space">
													<input class="radio" type="radio" name="edit_new_existing_lender" id="edit_add_lender" value="add_lender">New Lender		
													<input class="radio" type="radio" name="edit_new_existing_lender" id="edit_existing_lender" value="existing_lender">Existing Lender	
												</div>
											</div>
										</div>

										<div class="form-group">
											<div class="row">
												<div class="col-sm-12">
													<label for="edit_LenderCompany" class="col-form-label">Lender Company</label>
													<input type="text" name="LenderCompany" id="edit_LenderCompany" class="form-control gui-input ui-autocomplete-input" placeholder="Lender Company Name" required="required">
												</div>
											</div>
											<div class="row">
												<div class="col-sm-12">
													<label for="edit_assignment_clause" class="col-form-label">Assignment Clause</label>
													<input type="text" name="assignment_clause" id="edit_assignment_clause" class="form-control gui-input ui-autocomplete-input" placeholder="Assignment Clause">
												</div>
											</div>
										</div>

										<div class="form-group">
											<div class="row">
												<div class="col-sm-6">
													<label for="edit_LenderAddress" class="col-form-label">Lender Address</label>
													<input type="text" name="LenderAddress" id="edit_LenderAddress" class="gui-input form-control" placeholder="Lender Address" required="required">
												</div>
												<div class="col-sm-6">
													<label for="edit_LenderCity" class="col-form-label">Lender City</label>
													<input type="text" name="LenderCity" id="edit_LenderCity" class="gui-input form-control" placeholder="Lender City" required="required">
												</div>
											</div>
										</div>
										
										<div class="form-group">
											<div class="col-sm-6" style="display: none;">
												<label for="edit_LenderName" class="col-form-label">Lender Name</label>
												<input type="text" name="LenderName" id="edit_LenderName" class="gui-input form-control" placeholder="Attention" autocomplete="off">
											</div>
										</div>

										<div class="form-group">
											<div class="row">
												<div class="col-sm-6">
													<label for="edit_LenderState" class="col-form-label">Lender State</label>
													<input type="text" name="LenderState" id="edit_LenderState" class="gui-input form-control" placeholder="Lender State">
												</div>
												<div class="col-sm-6">
													<label for="edit_LenderZipcode" class="col-form-label">Lender Zipcode</label>
													<input type="text" name="LenderZipcode" id="edit_LenderZipcode" class="gui-input form-control" placeholder="Lender Zipcode" required="required">
												</div>
											</div>
										</div>

										<div class="form-group">
											<div class="row form-grp-title">
												<div class="col-sm-12">
													<div class="tagline"><span> PROPERTY DETAILS </span></div>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-6">
													<label for="edit_property_address" class="col-form-label">Property Address</label>
													<input required="required" type="text" class="gui-input form-control" name="property_address" id="edit_property_address" placeholder="Property Address">
												</div>
												<div class="col-sm-6">
													<label for="edit_property_city" class="col-form-label">Property City</label>
													<input type="text" name="property_city" id="edit_property_city" class="gui-input form-control" placeholder="Property City" required="required">
												</div>
											</div>
										</div>

										<div class="form-group">
											<div class="row">
												<div class="col-sm-6">
													<label for="edit_property_state" class="col-form-label">Property State</label>
													<input type="text" name="property_state" id="edit_property_state" class="gui-input form-control" placeholder="Property State" required="required">
												</div>
												<div class="col-sm-6">
													<label for="edit_property_zipcode" class="col-form-label">Property Zipcode</label>
													<input type="text" name="property_zipcode" id="edit_property_zipcode" class="gui-input form-control" placeholder="Property Zipcode" required="required">
												</div>
											</div>
										</div>

										<div class="form-group">
											<div class="row form-grp-title">
												<div class="col-sm-12">
													<div class="tagline"><span> TITLE OFFICER DETAILS </span></div>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-12">
													<label for="edit_TitleOfficer" class="col-form-label">Title Officer Details</label>
													<select id="edit_TitleOfficer" name="TitleOfficer" class="gui-input form-control" >
														<option value="">Title Officer</option>
														<?php 
														if(isset($titleOfficer) && !empty($titleOfficer))
														{
															foreach ($titleOfficer as $key => $value) 
															{
														?>
																<option value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
														<?php
															}
														}
														?>
													</select>
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
												<div class="col-sm-6">
													<label for="edit_loan_amount" class="col-form-label">Loan Amount</label>
													<input required="required" type="text" class="gui-input form-control" name="loan_amount" id="edit_loan_amount" placeholder="Loan Number">
												</div>
												<div class="col-sm-6">
													<label for="edit_loan_number" class="col-form-label">Loan Number</label>
													<input required="required" type="text" class="gui-input form-control" name="loan_number" id="edit_loan_number" placeholder="Loan Number">
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
													<label for="edit_borrowers_vesting" class="col-form-label">Primary Borrower Name</label>
													<input type="text" name="borrowers_vesting" id="edit_borrowers_vesting" class="gui-input form-control" placeholder="Primary Borrower Name"  required="required">
												</div>
											</div>
										</div>
										
										<div class="form-group">
											<div class="row form-grp-title">
												<div class="col-sm-12">
													<div class="tagline"><span> REPORT DATE SECTION </span></div>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-12">
													<label for="edit_supplemental_report_date" class="col-form-label">Supplemental Report Date</label>
													<input required="required" type="text" class="gui-input form-control" name="supplemental_report_date" id="edit_supplemental_report_date" placeholder="Supplemental Report Date" value="<?php echo date('m/d/Y'); ?>">
												</div>
											</div>
											<div class="row">
												<div class="col-sm-12" style="display:none;">
													<label for="edit_preliminary_report_date" class="col-form-label">Preliminary Report Date</label>
													<input required="required" type="text" class="gui-input form-control" name="preliminary_report_date" id="edit_preliminary_report_date" placeholder="Preliminary Report Date" value="<?php echo date('m/d/Y'); ?>">
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
													<label for="edit_branch" class="col-form-label">Select Branch</label>
													<select id="edit_branch" name="edit_branch" class="form-control">
														<option value="">Select Branch</option>
														<?php 
															if (isset($proposedBranches) && !empty($proposedBranches)) {
																foreach ($proposedBranches as $proposedBranch) {
														?>
															<option value="<?php echo $proposedBranch['id']; ?>"><?php echo $proposedBranch['city']; ?></option>
														<?php
																}
															}
														?>
													</select>
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
				
				<!-- <input type="hidden" name="orderId" value="" id="edit_orderId">

				<input type="hidden" name="property_id" value="" id="edit_property_id">

				<input type="hidden" id="edit_transaction_id" value="" name="transaction_id">

				<input type="hidden" name="fileId" value="" id="edit_fileId">

				<input type="hidden" name="LenderId" value="" id="edit_LenderId">
				<input type="hidden" name="partner_id" id="edit_partner_id" value="">	
				<input type="hidden" name="state" id="edit_state" value="">
				<div class="smart-forms smart-container wrap-2" style="margin:30px">
					<div class="modal-body search-result">
						<div id="edit-data-result" class="spacer-b20"></div>
						<div id="lender-details-fields" style="">
							<div class="spacer-b20">
								<div class="tagline"><span>Lender Details</span></div>
							</div>
							<div class="frm-row">	
								<div class="section colm colm12">	
									<label class="field prepend-icon">	
										<input class="radio" type="radio" name="edit_new_existing_lender" id="edit_add_lender" value="add_lender">New Lender		
										<input class="radio" type="radio" name="edit_new_existing_lender" id="edit_existing_lender" value="existing_lender">Existing Lender	
									</label>	
								</div>	
							</div>
							<div class="frm-row">
							<div class="section colm colm12">
								<label class="field prepend-icon">
									<input type="text" name="LenderCompany" id="edit_LenderCompany" class="gui-input ui-autocomplete-input"
										placeholder="Lender Company Name">
									<span class="field-icon"><i class="fa fa-user"></i></span>
									
									
								</label>
							</div>
						</div>
						<div class="frm-row">
							<div class="section colm colm12">
								<label class="field prepend-icon">
									<input type="text" name="assignment_clause" id="edit_assignment_clause" class="gui-input ui-autocomplete-input"
										placeholder="Assignment Clause">
									<span class="field-icon"><i class="fa fa-user"></i></span>
								</label>
							</div>
						</div>

						<div class="frm-row" style="display: none;">
							<div class="section colm colm12">
								<label class="field prepend-icon">
									<input type="text" name="LenderName" id="edit_LenderName"
										class="gui-input" placeholder="Loan Officer" autocomplete="off">
									<span class="field-icon"><i class="fa fa-user"></i></span>
								</label>
							</div>
						</div>
						<div class="frm-row">
							<div class="section colm colm6">
								<label class="field prepend-icon">
									<input type="text" name="LenderAddress" id="edit_LenderAddress" class="gui-input"
										placeholder="Lender Address" >
									<span class="field-icon"><i class="fa fa-envelope"></i></span>
								</label>
							</div>
							<div class="section colm colm6">
								<label class="field prepend-icon">
									<input type="text" name="LenderCity" id="edit_LenderCity" class="gui-input"
										placeholder="Lender City" >
									<span class="field-icon"><i class="fa fa-user"></i></span>
								</label>
							</div>
						</div>
						<div class="frm-row spacer-b15">
							<div class="section colm colm6">
								<label class="field prepend-icon">
									<input type="tel" name="LenderState" id="edit_LenderState" class="gui-input"
										placeholder="Lender State" >
									<span class="field-icon"><i class="fa fa-envelope"></i></span>
								</label>
							</div>
							<div class="section colm colm6">
								<label class="field prepend-icon">
									<input type="text" name="LenderZipcode" id="edit_LenderZipcode" class="gui-input"
										placeholder="Lender Zipcode" >
									<span class="field-icon"><i class="fa fa-envelope"></i></span>
								</label>
							</div>
						</div>
						</div>
						<div id="property-details-section">
							<div class="spacer-b20">
								<div class="tagline"><span>Property Details</span></div>
							</div>

							<div class="frm-row">
								<div class="section colm colm6">
									<label class="field">
										<input required="required" type="text" class="gui-input" name="property_address" id="edit_property_address" placeholder="Street Address">
									</label>
								</div>
								<div class="section colm colm6">
									<label class="field">
										<input required="required" type="text" class="gui-input" name="property_city" id="edit_property_city" placeholder="City">
									</label>
								</div>
							</div>

							<div class="frm-row">
								<div class="section colm colm6">
									<label class="field">
										<input required="required" type="text" class="gui-input" name="property_state" id="edit_property_state" placeholder="State">
									</label>
								</div>
								<div class="section colm colm6">
									<label class="field">
										<input required="required" type="text" class="gui-input" name="property_zipcode" id="edit_property_zipcode" placeholder="Zipcode">
									</label>
								</div>
							</div>
						</div>
						<div id="title-officer-section">
						<div class="spacer-b20">
							<div class="tagline"><span>Title Officer Details</span></div>
						</div>
						<div class="frm-row">
							<div class="section colm colm12">
								<label class="field select">
									<select id="edit_TitleOfficer" name="TitleOfficer">
										<option value="">Title Officer</option>
										<?php 
										if(isset($titleOfficer) && !empty($titleOfficer))
										{
											foreach ($titleOfficer as $key => $value) 
											{
									?>
												<option value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
									<?php
											}
										}
									?>
									</select>
									<i class="arrow double"></i>                    
								</label> 
							</div>
						</div>
						</div>
						<div id="loan-details-section">
						<div class="spacer-b20">
							<div class="tagline"><span>Loan Details</span></div>
						</div>
						<div class="frm-row spacer-b15">
								<div class="section colm colm6">
									<label class="field">
										<input type="text" class="gui-input" name="loan_amount" id="edit_loan_amount" placeholder="Loan Amount">
									</label>
								</div>
								<div class="section colm colm6">
									<label class="field">
										<input type="text" class="gui-input" name="loan_number" id="edit_loan_number" placeholder="Loan Number">
									</label>
								</div>
							</div>
						</div>
						<div class="spacer-b20">
							<div class="tagline"><span>Borrowers & Vesting</span></div>
						</div>
						<div class="frm-row spacer-b15">
							<div class="section colm colm12">
								<label class="field prepend-icon">
									<input type="text" name="borrowers_vesting" id="edit_borrowers_vesting" class="gui-input"
										placeholder="Borrowers & Vesting"  required="required">
									<span class="field-icon"><i class="fa fa-user"></i></span>
								</label>
							</div>
						</div>
						<div id="report-date-section">
						<div class="spacer-b20">
							<div class="tagline"><span>Report Date Section</span></div>
						</div>
						<div class="frm-row">
							<div class="section colm colm12" id="s-date-section">
								<label class="field prepend-icon">
									<input type="text" name="supplemental_report_date" id="edit_supplemental_report_date" class="gui-input" placeholder="Supplemental Report Date" value="<?php echo date('m/d/Y'); ?>">
									<span class="field-icon"><i class="fa fa-calendar"></i></span>
								</label>
							</div>
							<div class="section colm colm6" id="p-date-section" style="display: none;">
								<label class="field prepend-icon">
									<input type="text" name="preliminary_report_date" id="edit_preliminary_report_date" class="gui-input" placeholder="Preliminary Report Date" value="<?php echo date('m/d/Y'); ?>">
									<span class="field-icon"><i class="fa fa-calendar"></i></span>
								</label>
							</div>
						</div>
						
						</div>
						<div class="spacer-b20">
							<div class="tagline"><span>Select Branch</span></div>
						</div>
						<div class="frm-row">
							<div class="section colm colm12">
								<label class="field select">
									<select id="edit_branch" name="edit_branch">
										<option value="">Select Branch</option>
										<?php 
											if (isset($proposedBranches) && !empty($proposedBranches)) {
												foreach ($proposedBranches as $proposedBranch) {
										?>
											<option value="<?php echo $proposedBranch['id']; ?>"><?php echo $proposedBranch['city']; ?></option>
										<?php
												}
											}
										?>
									</select>
									<i class="arrow double"></i>                    
								</label> 
							</div>
						</div>
					</div>
					<div class="form-footer" style="margin: 0px 20px;">
						<button type="submit" data-btntext-sending="Sending..." class="button btn-primary">Submit</button>
						<button type="reset" data-dismiss="modal" aria-label="Close" class="button">Cancel</button>
					</div>
				</div> -->
			</form>
		</div>
	</div>
</div>

