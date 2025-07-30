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

	table#orders_listing tr td:last-child {
		display: inline-flex;
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
</style>
<body>
	<?php
$this->load->view('layout/header_dashboard');
?>

	<section class="section-type-4a section-defaulta" style="padding-bottom:100px;">
		<div class="container">
			<div class="row">
				<div class="row">
					<div class="col-xs-12">
						<div class="typography-section__inner" style="padding: 0px 17px;">
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
                                            <tr role="row" class="odd">
                                                <td>1</td>
                                                <td><?php echo $file_number; ?></td>
                                                <td><?php echo $full_address; ?></td>
                                                <td><?php echo $created; ?></td>
                                                <td><?php echo $action; ?></td>
                                            </tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<?php
$this->load->view('layout/footer');
?>
    <div class="modal fade" width="500px" id="lender_information" tabindex="-1" role="dialog"
		aria-labelledby="Lender Infromation" aria-hidden="true">
		<div class="modal-dialog modal-lg" role="document" style="width:40%;">
			<div class="modal-content">
				<form method="POST" id="add-order-details" enctype="multipart/form-data">
					<input type="hidden" name="orderId" value="" id="orderId">

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
									<div class="tagline"><span>Lender Details</span></div><!-- .tagline -->
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
								</div><!-- end section -->
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
								</div><!-- end section -->
							</div>

							<!-- <div class="frm-row">
								<div class="section colm colm6">
									<label class="field prepend-icon">
										<input type="email" name="LenderEmailAddress" id="LenderEmailAddress"
											class="gui-input" placeholder="Lender Email address" >
										<span class="field-icon"><i class="fa fa-envelope"></i></span>
									</label>
								</div>
								<div class="section colm colm6">
									<label class="field prepend-icon">
										<input type="text" name="LenderState" id="LenderState" class="gui-input"
											placeholder="Lender State" >
										<span class="field-icon"><i class="fa fa-envelope"></i></span>
									</label>
								</div>
							</div> -->
							<div class="frm-row">
								<!-- <div class="section colm colm6">
									<label class="field prepend-icon">
										<input type="text" name="LenderName" id="LenderName"
											class="gui-input" placeholder="Lender Name"
											autocomplete="off">
										<span class="field-icon"><i class="fa fa-user"></i></span>
									</label>
								</div> -->
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
								<!-- <div class="section colm colm6">
									<label class="field prepend-icon">
										<input type="text" name="LenderCity" id="LenderCity" class="gui-input"
											placeholder="Lender City" >
										<span class="field-icon"><i class="fa fa-user"></i></span>
									</label>
								</div> -->
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
									<div class="tagline"><span>Property Details</span></div><!-- .tagline -->
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
								<div class="tagline"><span>Title Officer Details</span></div><!-- .tagline -->
							</div>
							<div class="frm-row">
								<div class="section colm colm12">
									<label class="field select">
                                        <select id="TitleOfficer" name="TitleOfficer">
                                            <option value="">Title Officer</option>
                                            <?php
if (isset($titleOfficer) && !empty($titleOfficer)) {
    foreach ($titleOfficer as $key => $value) {
        ?>
													<option value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
										<?php
}
}
?>
                                        </select>
                                        <i class="arrow double"></i>
                                    </label>
								</div><!-- end section -->
							</div>
							</div>
							<div id="loan-details-section">
							<div class="spacer-b20">
								<div class="tagline"><span>Loan Details</span></div><!-- .tagline -->
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
							<!-- <div id="primary-borrower-section">
							<div class="spacer-b20">
								<div class="tagline"><span>Primary Borrower Information</span></div>
							</div>
							<div class="frm-row spacer-b15">
								<div class="section colm colm12">
									<label class="field prepend-icon">
										<input type="text" name="primary_first_name" id="primary_first_name" class="gui-input"
											placeholder="Primary Borrower Name" >
										<span class="field-icon"><i class="fa fa-user"></i></span>
									</label>
								</div>
								<div class="section colm colm6">
									<label class="field prepend-icon">
										<input type="text" name="primary_last_name" id="primary_last_name" class="gui-input"
											placeholder="Last Name" >
										<span class="field-icon"><i class="fa fa-user"></i></span>
									</label>
								</div>
							</div>
							</div> -->
							<!-- <div id="secondary-borrower-section">
							<div class="spacer-b20">
								<div class="tagline"><span>Secondary Borrower Information</span></div>
							</div>
							<div class="frm-row spacer-b15">
								<div class="section colm colm12">
									<label class="field prepend-icon">
										<input type="text" name="first_name" id="first_name" class="gui-input"
											placeholder="Secondary Borrower Name">
										<span class="field-icon"><i class="fa fa-user"></i></span>
									</label>
								</div>
								<div class="section colm colm6">
									<label class="field prepend-icon">
										<input type="text" name="last_name" id="last_name" class="gui-input"
											placeholder="Last Name">
										<span class="field-icon"><i class="fa fa-user"></i></span>
									</label>
								</div>
							</div>
							</div> -->
							<!-- <div id="vesting-section">
							<div class="spacer-b20">
								<div class="tagline"><span>Vesting</span></div>
							</div>
							<div class="frm-row spacer-b15">
								<div class="section colm colm12">
									<label class="field prepend-icon">
										<input type="text" name="vesting" id="vesting" class="gui-input"
											placeholder="Vesting">
										<span class="field-icon"><i class="fa fa-user"></i></span>
									</label>
								</div>
							</div>
							</div> -->
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
								<div class="tagline"><span>Report Date Section</span></div><!-- .tagline -->
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
					</div>
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
					<input type="hidden" name="orderId" value="" id="edit_orderId">

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
									<div class="tagline"><span>Lender Details</span></div><!-- .tagline -->
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
								</div><!-- end section -->
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
											class="gui-input" placeholder="Assignment clause" autocomplete="off">
										<span class="field-icon"><i class="fa fa-user"></i></span>
									</label>
								</div>
								<!-- <div class="section colm colm6">
									<label class="field prepend-icon">
										<input type="email" name="LenderEmailAddress" id="edit_LenderEmailAddress"
											class="gui-input" placeholder="Lender Email address" >
										<span class="field-icon"><i class="fa fa-envelope"></i></span>
									</label>
								</div> -->
								<!-- <div class="section colm colm6">
									<label class="field prepend-icon">
										<input type="tel" name="LenderState" id="edit_LenderState" class="gui-input"
											placeholder="Lender State" >
										<span class="field-icon"><i class="fa fa-envelope"></i></span>
									</label>
								</div> -->
							</div>
							<div class="frm-row">
								<!-- <div class="section colm colm6">
									<label class="field prepend-icon">
										<input type="text" name="LenderName" id="edit_LenderName"
											class="gui-input" placeholder="Lender Name"
											autocomplete="off">
										<span class="field-icon"><i class="fa fa-user"></i></span>
									</label>
								</div> -->
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
									<div class="tagline"><span>Property Details</span></div><!-- .tagline -->
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
								<div class="tagline"><span>Title Officer Details</span></div><!-- .tagline -->
							</div>
							<div class="frm-row">
								<div class="section colm colm12">
									<label class="field select">
                                        <select id="edit_TitleOfficer" name="TitleOfficer">
                                            <option value="">Title Officer</option>
                                            <?php
if (isset($titleOfficer) && !empty($titleOfficer)) {
    foreach ($titleOfficer as $key => $value) {
        ?>
													<option value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
										<?php
}
}
?>
                                        </select>
                                        <i class="arrow double"></i>
                                    </label>
								</div><!-- end section -->
							</div>
							</div>
							<div id="loan-details-section">
							<div class="spacer-b20">
								<div class="tagline"><span>Loan Details</span></div><!-- .tagline -->
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
							<!-- <div id="primary-borrower-section">
							<div class="spacer-b20">
								<div class="tagline"><span>Primary Borrower Information</span></div>
							</div>
							<div class="frm-row spacer-b15">
								<div class="section colm colm12">
									<label class="field prepend-icon">
										<input type="text" name="primary_first_name" id="edit_primary_first_name" class="gui-input"
											placeholder="Primary Borrower Name" >
										<span class="field-icon"><i class="fa fa-user"></i></span>
									</label>
								</div>
								<div class="section colm colm6">
									<label class="field prepend-icon">
										<input type="text" name="primary_last_name" id="edit_primary_last_name" class="gui-input"
											placeholder="Last Name" >
										<span class="field-icon"><i class="fa fa-user"></i></span>
									</label>
								</div>
							</div>
							</div> -->
							<!-- <div id="secondary-borrower-section">
							<div class="spacer-b20">
								<div class="tagline"><span>Secondary Borrower Information</span></div>
							</div>
							<div class="frm-row spacer-b15">
								<div class="section colm colm12">
									<label class="field prepend-icon">
										<input type="text" name="first_name" id="edit_first_name" class="gui-input"
											placeholder="Secondary Borrower Name">
										<span class="field-icon"><i class="fa fa-user"></i></span>
									</label>
								</div>
								<div class="section colm colm6">
									<label class="field prepend-icon">
										<input type="text" name="last_name" id="edit_last_name" class="gui-input"
											placeholder="Last Name">
										<span class="field-icon"><i class="fa fa-user"></i></span>
									</label>
								</div>
							</div>
							</div> -->
							<!-- <div id="vesting-section">
							<div class="spacer-b20">
								<div class="tagline"><span>Vesting</span></div>
							</div>
							<div class="frm-row spacer-b15">
								<div class="section colm colm12">
									<label class="field prepend-icon">
										<input type="text" name="vesting" id="edit_vesting" class="gui-input"
											placeholder="Vesting">
										<span class="field-icon"><i class="fa fa-user"></i></span>
									</label>
								</div>
							</div>
							</div> -->
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
								<div class="tagline"><span>Report Date Section</span></div><!-- .tagline -->
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
								</div><!-- end section -->
							</div>
						</div>
						<div class="form-footer" style="margin: 0px 20px;">
							<button type="submit" data-btntext-sending="Sending..." class="button btn-primary">Submit</button>
							<button type="reset" data-dismiss="modal" aria-label="Close" class="button">Cancel</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	<!-- Edit info modal -->
</body>

</html>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/frontend/css/smart-forms.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/frontend/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/frontend/css/jquery-ui.css">


<script type="text/javascript" src="<?php echo base_url(); ?>assets/frontend/js/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/frontend/js/jquery.validate.min.js"></script>
<script>
$(document).ready(function () {

		$('#supplemental_report_date').datepicker().datepicker("setDate", new Date());
		// $('#preliminary_report_date').datepicker();
		$('#edit_supplemental_report_date').datepicker().datepicker("setDate", new Date());
		// $('#edit_preliminary_report_date').datepicker();

		if(jQuery('#add-order-details').length)
	    {
	       jQuery('#add-order-details').validate({
	       		ignore:":not(:visible)",
	            rules: {
	                LenderCompany:"required",
	                LenderEmailAddress:"required",
	                // LenderName:"required",
	                TitleOfficer:"required",
	                // loan_amount:"required",
	                loan_number:"required",
	                primary_first_name:"required",
	               // primary_last_name:"required",
	                supplemental_report_date:"required",
				   // preliminary_report_date:"required",
				   branch:"required",
	            },
	            messages: {
	                TitleOfficer:"Please select title officer",
	                loan_number:"Please enter loan number",
	                borrower:"Please enter borrower",
	                lender:"Please enter lender",
	                supplemental_report_date:"Please select date",
					preliminary_report_date:"Please select date",
					branch:"Please select branch",
	            },
	            submitHandler: function(form) {
	            	$('#page-preloader').css('background-color', 'rgba(0,0,0,.5)');
					$('#page-preloader').css('display', 'block');
	            	var LenderCompany = $('#LenderCompany').val();
	            	var assignment_clause = $('#assignment_clause').val();
	            	var LenderEmailAddress = $('#LenderEmailAddress').val();
	            	var LenderState = $('#LenderState').val();
	            	var LenderName = $('#LenderName').val();
	            	var LenderAddress = $('#LenderAddress').val();
	            	var LenderCity = $('#LenderCity').val();
	            	var LenderZipcode = $('#LenderZipcode').val();
	            	var TitleOfficer = $('#TitleOfficer').val();
	            	var loan_amount = $('#loan_amount').val();
	            	var loan_number = $('#loan_number').val();
	            	// var primary_first_name = $('#primary_first_name').val();
	            	// var primary_last_name = $('#primary_last_name').val();
	            	// var secondary_first_name = $('#first_name').val();
	            	// var secondary_last_name = $('#last_name').val();
	            	var borrowers_vesting = $('#borrowers_vesting').val();
	            	// var vesting = $('#vesting').val();
	            	var LenderId = $('#LenderId').val();
	            	var orderId = $('#orderId').val();
	            	var transaction_id = $('#transaction_id').val();
	            	var property_id = $('#property_id').val();
	            	var property_address = $('#property_address').val();
	            	var property_city = $('#property_city').val();
	            	var property_state = $('#property_state').val();
	            	var property_zipcode = $('#property_zipcode').val();
	            	var fileId = $('#fileId').val();
	            	var supplemental_report_date = $('#supplemental_report_date').val();
	            	var preliminary_report_date = $('#preliminary_report_date').val();
					var new_existing_lender = $('input[name="new_existing_lender"]:checked').val();
					var branch = $('#branch').val();
	                $.ajax({
	                url: base_url + "add-order-details",
	                type: "post",
	                data:{
	                    TitleOfficer: TitleOfficer,
	                    loan_amount: loan_amount,
	                    loan_number: loan_number,
	                    borrowers_vesting: borrowers_vesting,
	                    property_address: property_address,
	                    property_city: property_city,
	                    property_state: property_state,
	                    property_zipcode: property_zipcode,
	                    // primary_first_name: primary_first_name,
	                    // primary_last_name: primary_last_name,
	                    // secondary_first_name: secondary_first_name,
	                    // secondary_last_name: secondary_last_name,
	                    // vesting: vesting,
	                    LenderId: LenderId,
	                    LenderCompany:LenderCompany,
	                    assignment_clause:assignment_clause,
	            		LenderEmailAddress:LenderEmailAddress,
	            		LenderState:LenderState,
	            		LenderName:LenderName,
	            		LenderAddress:LenderAddress,
	            		LenderCity:LenderCity,
	            		LenderZipcode:LenderZipcode,
	                    orderId: orderId,
	                    transaction_id: transaction_id,
	                    property_id: property_id,
	                    fileId: fileId,
	                    s_report_date: supplemental_report_date,
	                    p_report_date: preliminary_report_date,
						new_existing_lender: new_existing_lender,
						branch: branch
	                },
	                success: function(response) {
	                	$('#page-preloader').css('display', 'none');
	                	var res = JSON.parse(response);
						if(res.status == 'success')
						{
							$('#lender_information').modal('hide');
							if(res.data)
							{
								var binaryData = res.data;
								downloadFile(binaryData);
							}
							location.reload(true);
						}
						else if(res.status == 'error')
						{
							$('.modal-body.search-result').append('<div class="error">Something went wrong. Please try again.</div>');
							$('#lender_information').modal('hide');
						}
	                }
	            });
	            }
	        });
	    }

	    /* Lender autocomplete */
	    $("#LenderCompany" ).focusin(function() {
	    	if ($('input[name="new_existing_lender"]:checked').val() == 'existing_lender')
	    	{
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
			            $("#LenderCompany").val(ui.item.company).parent().addClass('state-success');

			            if(ui.item.email_address)
			            {
							$("#LenderEmailAddress").val(ui.item.email_address).parent().addClass('state-success');

						} else {
							$("#LenderEmailAddress").val('').removeAttr('readonly').parent().removeClass('state-success').addClass('state-error');
						}

						if(ui.item.state) {
							$("#LenderState").val(ui.item.state).parent().addClass('state-success');
						} else {
							$("#LenderState").val('').removeAttr('readonly').parent().removeClass('state-success').addClass('state-error');
						}

						if(ui.item.name)
						{
							$("#LenderName").val(ui.item.name).parent().addClass('state-success');
						} else {
							$("#LenderName").val('').removeAttr('readonly').parent().removeClass('state-success').addClass('state-error');
						}

			            if(ui.item.address) {
							$("#LenderAddress").val(ui.item.address).parent().addClass('state-success');
						} else {
							$("#LenderAddress").val('').removeAttr('readonly').parent().removeClass('state-success').addClass('state-error');
						}

						if(ui.item.city) {
							$("#LenderCity").val(ui.item.city).parent().addClass('state-success');
						} else {
							$("#LenderCity").val('').removeAttr('readonly').parent().removeClass('state-success').addClass('state-error');
						}

						if(ui.item.zip_code) {
							$("#LenderZipcode").val(ui.item.zip_code).parent().addClass('state-success');
						} else {
							$("#LenderZipcode").val('').removeAttr('readonly').parent().removeClass('state-success').addClass('state-error');
						}

						if (ui.item.assignment_clause) {
							$("#assignment_clause").val(ui.item.assignment_clause);
						} else {
							$("#assignment_clause").val('');
						}

			            $("#LenderId").val(ui.item.id);
			        },
			        change: function( event, ui ) {
			            if (ui.item == null)
			            {
			            	$("#LenderEmailAddress").val('').removeAttr('readonly').parent().removeClass('state-success').addClass('state-error');
			                $("#LenderState").val('').removeAttr('readonly').parent().removeClass('state-success').addClass('state-error');
							$("#LenderCompany").val('').removeAttr('readonly').parent().removeClass('state-success').addClass('state-error');
							$("#LenderAddress").val('').removeAttr('readonly').parent().removeClass('state-success').addClass('state-error');
			                $("#LenderCity").val('').removeAttr('readonly').parent().removeClass('state-success').addClass('state-error');
							$("#LenderZipcode").val('').removeAttr('readonly').parent().removeClass('state-success').addClass('state-error');
							$("#assignment_clause").val('');
							$("#LenderId").val('');
			            }
			        }
			    });
			}
			else
			{
				if($('.ui-widget.ui-autocomplete').length > 0) {
					$('#LenderCompany').autocomplete( "disable" );
				}
			}
		});

		$("#edit_LenderCompany" ).focusin(function() {
	    	if ($('input[name="edit_new_existing_lender"]:checked').val() == 'existing_lender')
	    	{
	    		if($('.ui-widget.ui-autocomplete').length > 0) {
					$('#edit_LenderCompany').autocomplete( "enable" );
				}
				$("#edit_LenderCompany").autocomplete({
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
			            $("#edit_LenderCompany").val(ui.item.company).parent().addClass('state-success');

			            if(ui.item.email_address)
			            {
							$("#edit_LenderEmailAddress").val(ui.item.email_address).parent().addClass('state-success');

						} else {
							$("#edit_LenderEmailAddress").val('').removeAttr('readonly').parent().removeClass('state-success').addClass('state-error');
						}

						if(ui.item.state) {
							$("#edit_LenderState").val(ui.item.state).parent().addClass('state-success');
						} else {
							$("#edit_LenderState").val('').removeAttr('readonly').parent().removeClass('state-success').addClass('state-error');
						}

						if(ui.item.name)
						{
							$("#edit_LenderName").val(ui.item.name).parent().addClass('state-success');
						} else {
							$("#edit_LenderName").val('').removeAttr('readonly').parent().removeClass('state-success').addClass('state-error');
						}

			            if(ui.item.address) {
							$("#edit_LenderAddress").val(ui.item.address).parent().addClass('state-success');
						} else {
							$("#edit_LenderAddress").val('').removeAttr('readonly').parent().removeClass('state-success').addClass('state-error');
						}

						if(ui.item.city) {
							$("#edit_LenderCity").val(ui.item.city).parent().addClass('state-success');
						} else {
							$("#edit_LenderCity").val('').removeAttr('readonly').parent().removeClass('state-success').addClass('state-error');
						}

						if(ui.item.zip_code) {
							$("#edit_LenderZipcode").val(ui.item.zip_code).parent().addClass('state-success');
						} else {
							$("#edit_LenderZipcode").val('').removeAttr('readonly').parent().removeClass('state-success').addClass('state-error');
						}
						if (ui.item.assignment_clause) {
							$("#edit_assignment_clause").val(ui.item.assignment_clause);
						} else {
							$("#edit_assignment_clause").val('');
						}
			            $("#edit_LenderId").val(ui.item.id);
			        },
			        change: function( event, ui ) {
			            if (ui.item == null)
			            {
			            	$("#edit_LenderEmailAddress").val('').removeAttr('readonly').parent().removeClass('state-success').addClass('state-error');
			                $("#edit_LenderState").val('').removeAttr('readonly').parent().removeClass('state-success').addClass('state-error');
							$("#edit_LenderCompany").val('').removeAttr('readonly').parent().removeClass('state-success').addClass('state-error');
							$("#edit_LenderAddress").val('').removeAttr('readonly').parent().removeClass('state-success').addClass('state-error');
			                $("#edit_LenderCity").val('').removeAttr('readonly').parent().removeClass('state-success').addClass('state-error');
							$("#edit_LenderZipcode").val('').removeAttr('readonly').parent().removeClass('state-success').addClass('state-error');
							$("#edit_assignment_clause").val('');
							$("#edit_LenderId").val('');
			            }
			        }
			    });
			}
			else
			{

			}
		});

		/* Lender autocomplete */

		/*$('#lender_information,#edit_information').on('hidden.bs.modal', function (e) {
		  $(this)
		    .find("input,textarea,select")
		       .val('')
		       .end()
		    .find("input[type=checkbox], input[type=radio]")
		       .prop("checked", "")
		       .end();
		});*/

		/* Edit modal validations */
		if(jQuery('#edit-order-details').length)
		{
		   jQuery('#edit-order-details').validate({
		   		ignore:":not(:visible)",
		        rules: {
		            LenderCompany:"required",
	                LenderEmailAddress:"required",
	                // LenderName:"required",
	                TitleOfficer:"required",
	                // loan_amount:"required",
	                loan_number:"required",
	                primary_first_name:"required",
	                // primary_last_name:"required",
	                supplemental_report_date:"required",
				   // preliminary_report_date:"required",
				    edit_branch:"required",
		        },
		        messages: {
		            TitleOfficer:"Please select title officer",
	                loan_number:"Please enter loan number",
	                borrower:"Please enter borrower",
	                lender:"Please enter lender",
	                supplemental_report_date:"Please select date",
					preliminary_report_date:"Please select date",
					edit_branch:"Please select branch",
		        },
		        submitHandler: function(form) {
		        	$('#page-preloader').css('background-color', 'rgba(0,0,0,.5)');
					$('#page-preloader').css('display', 'block');
	            	var LenderCompany = $('#edit_LenderCompany').val();
	            	var LenderEmailAddress = $('#edit_LenderEmailAddress').val();
	            	var LenderState = $('#edit_LenderState').val();
	            	var LenderName = $('#edit_LenderName').val();
	            	var LenderAddress = $('#edit_LenderAddress').val();
	            	var assignment_clause = $('#edit_assignment_clause').val();
	            	var LenderCity = $('#edit_LenderCity').val();
	            	var LenderZipcode = $('#edit_LenderZipcode').val();
	            	var TitleOfficer = $('#edit_TitleOfficer').val();
	            	var loan_amount = $('#edit_loan_amount').val();
	            	var loan_number = $('#edit_loan_number').val();
	            	// var primary_first_name = $('#edit_primary_first_name').val();
	            	// var primary_last_name = $('#edit_primary_last_name').val();
	            	// var secondary_first_name = $('#edit_first_name').val();
	            	// var secondary_last_name = $('#edit_last_name').val();
	            	// var vesting = $('#edit_vesting').val();

	            	var LenderId = $('#edit_LenderId').val();
	            	var orderId = $('#edit_orderId').val();
	            	var transaction_id = $('#edit_transaction_id').val();
	            	var property_id = $('#edit_property_id').val();
	            	var borrowers_vesting = $('#edit_borrowers_vesting').val();
					var property_address = $('#edit_property_address').val();
	            	var property_city = $('#edit_property_city').val();
	            	var property_state = $('#edit_property_state').val();
	            	var property_zipcode = $('#edit_property_zipcode').val();
	            	var fileId = $('#edit_fileId').val();
	            	var supplemental_report_date = $('#edit_supplemental_report_date').val();
	            	var preliminary_report_date = $('#edit_preliminary_report_date').val();
					var new_existing_lender = $('input[name="edit_new_existing_lender"]:checked').val();
					var branch = $('#edit_branch').val();
		            $.ajax({
		            url: base_url + "add-order-details",
		            type: "post",
		            data:{
		                TitleOfficer: TitleOfficer,
	                    loan_amount: loan_amount,
	                    loan_number: loan_number,
	                    // primary_first_name: primary_first_name,
	                    // primary_last_name: primary_last_name,
	                    // secondary_first_name: secondary_first_name,
	                    // secondary_last_name: secondary_last_name,
	                    // vesting: vesting,
	                    LenderId: LenderId,
	                    LenderCompany:LenderCompany,
	                    assignment_clause:assignment_clause,
	            		LenderEmailAddress:LenderEmailAddress,
	            		LenderState:LenderState,
	            		LenderName:LenderName,
	            		LenderAddress:LenderAddress,
	            		LenderCity:LenderCity,
	            		LenderZipcode:LenderZipcode,
	                    orderId: orderId,
	                    transaction_id: transaction_id,
	                    property_id: property_id,
	                    borrowers_vesting: borrowers_vesting,
	                    property_address: property_address,
	                    property_city: property_city,
	                    property_state: property_state,
	                    property_zipcode: property_zipcode,
	                    fileId: fileId,
	                    s_report_date: supplemental_report_date,
	                    p_report_date: preliminary_report_date,
						new_existing_lender: new_existing_lender,
						branch: branch
		            },
		            success: function(response) {
		            	$('#page-preloader').css('display', 'none');
		            	var res = JSON.parse(response);
						if(res.status == 'success')
						{
							$('#edit-data-result').html('<div class="alert alert-success">Data updated successfully</div>');
							if(res.data)
							{
								var binaryData = res.data;
								downloadFile(binaryData);
							}

							location.reload(true);
						}
						else if(res.status == 'error')
						{
							$('#edit-data-result').html('<div class="alert alert-error">Something went wrong. Please try again.</div>');
						}
						$('#edit-data-result').fadeOut( 5000, function() {
						    $('#edit_information').modal('hide');
						});
		            }
		        });
		        }
		    });
		}
		/* Edit modal validations */

		$("input[name=new_existing_lender]").change(function(){
			$("#LenderEmailAddress").val('');
			$("#LenderName").val('');
			$("#LenderState").val('');
			$("#LenderCompany").val('');
			$("#LenderAddress").val('');
			$("#LenderCity").val('');
			$("#LenderZipcode").val('');
			$("#assignment_clause").val('');
			$("#LenderId").val('');
		});
		$("input[name=edit_new_existing_lender]").change(function(){
			$("#edit_LenderEmailAddress").val('');
			$("#edit_LenderName").val('');
			$("#edit_LenderState").val('');
			$("#edit_LenderCompany").val('');
			$("#edit_LenderAddress").val('');
			$("#edit_LenderCity").val('');
			$("#edit_LenderZipcode").val('');
			$("#edit_assignment_clause").val('');
			$("#edit_LenderId").val('');
		});
	});

function generateProposedInsured(fileId)
{
	if(fileId)
	{
		$('#page-preloader').css('background-color', 'rgba(0,0,0,.5)');
		$('#page-preloader').css('display', 'block');
		$.ajax({
            url: base_url + "generate-proposed-insured",
            type: "post",
            data:{
                fileId: fileId,
            },
            success: function(response) {
            	var res = JSON.parse(response);
            	var dataRequired = 0;
                if(res.status == 'success')
                {
                	/*if(res.orderDetails['is_escrow'] == 1 && (res.orderDetails['escrow_lender_id'] == null || res.orderDetails['escrow_lender_id'] == undefined || res.orderDetails['escrow_lender_id'].length == 0))
                	{
                		dataRequired = 1;
                		$('#lender-details-fields').show();
                	}
                	else
                	{
                		$('#lender-details-fields').hide();
                	}*/
                	$("#LenderName").val(res.orderDetails['lender_name']);
					$("#LenderEmailAddress").val(res.orderDetails['lender_email']);
					$("#LenderState").val(res.orderDetails['lender_state']);
					$("#LenderCompany").val(res.orderDetails['lender_company_name']);
					$("#assignment_clause").val(res.orderDetails['lender_assignment_clause']);
					$("#LenderAddress").val(res.orderDetails['lender_address']);
					$("#LenderCity").val(res.orderDetails['lender_city']);
					$("#LenderZipcode").val(res.orderDetails['lender_zipcode']);
					$("#LenderId").val(res.orderDetails['lender_id']);
					$("#property_address").val(res.orderDetails['street_address']);
					$("#property_city").val(res.orderDetails['property_city']);
					$("#property_state").val(res.orderDetails['property_state']);
					$("#property_zipcode").val(res.orderDetails['property_zip']);

					$("#loan_amount").val(res.orderDetails['loan_amount']);
					$("#loan_number").val(res.orderDetails['loan_number']);
					$("#TitleOfficer").val(res.orderDetails['title_officer']);

                	$("#borrowers_vesting").val(res.orderDetails['borrowers_vesting']);

                	if(res.orderDetails['supplemental_report_date'] == null || res.orderDetails['supplemental_report_date'] == undefined || res.orderDetails['supplemental_report_date'].length == 0)
					{

                		// $('#edit_preliminary_report_date').val(res.orderDetails['preliminary_report_date']);
					}
					else
					{
						$('#supplemental_report_date').val(res.orderDetails['supplemental_report_date']);
					}

                	/*$('#supplemental_report_date').val(res.orderDetails['supplemental_report_date']);
                	$('#preliminary_report_date').val(res.orderDetails['preliminary_report_date']);*/

                	if (res.orderDetails['lender_id'] != '')
                	{
						$("#existing_lender").prop("checked", true);
						// $('input[name=new_existing_lender]').attr("disabled",true);
					} else {
						// $('input[name=new_existing_lender]').attr("disabled",false);
						$("#add_lender").prop("checked", true);
					}

                }
                $('#page-preloader').css('display', 'none');
               	$('#LenderId').val(res.orderDetails.lender_id);
				$('#orderId').val(res.orderDetails.orderId);
            	$('#transaction_id').val(res.orderDetails.transaction_id);
            	$('#property_id').val(res.orderDetails.property_id);
            	$('#fileId').val(res.orderDetails.fileId);
				/*if(dataRequired == 0)
				{
					$( "#add-order-details" ).submit();
				}
				else
				{*/
					$('#lender_information').modal('show');
				/*}*/
            }
        });
	}
	else
	{
		alert("File ID required.");
	}
}

function base64toBlob(base64Data, contentType)
{
    contentType = contentType || '';
    var sliceSize = 1024;
    var byteCharacters = (base64Data);
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
    return new Blob(byteArrays, { type: contentType });
}

function editInformation(fileId)
{
	if(fileId)
	{
		$('#page-preloader').css('background-color', 'rgba(0,0,0,.5)');
		$('#page-preloader').css('display', 'block');

		$.ajax({
            // url: base_url + "get-order-details",
            url: base_url + "generate-proposed-insured",
            type: "post",
            data:{
                fileId: fileId,
            },
            success: function(response) {
            	$('#page-preloader').css('display', 'none');

            	var res = JSON.parse(response);
            	if(res.status == 'success')
                {
                	if(res.status == 'success')
	                {
	                	/*if(res.orderDetails['is_escrow'] == 1)
	                	{
	                		$('#edit-order-details #lender-details-fields').show();
	                	}
	                	else
	                	{
	                		$('#edit-order-details #lender-details-fields').hide();
	                	}*/
	                	$("#edit_LenderName").val(res.orderDetails['lender_name']);
						// $("#edit_LenderEmailAddress").val(res.orderDetails['lender_email']);
						$("#edit_LenderState").val(res.orderDetails['lender_state']);
						$("#edit_LenderCompany").val(res.orderDetails['lender_company_name']);
						$("#edit_assignment_clause").val(res.orderDetails['lender_assignment_clause']);
						$("#edit_LenderAddress").val(res.orderDetails['lender_address']);
						$("#edit_LenderCity").val(res.orderDetails['lender_city']);
						$("#edit_LenderZipcode").val(res.orderDetails['lender_zipcode']);
						$("#edit_LenderId").val(res.orderDetails['lender_id']);


						$("#edit_property_address").val(res.orderDetails['street_address']);
						$("#edit_property_city").val(res.orderDetails['property_city']);
						$("#edit_property_state").val(res.orderDetails['property_state']);
						$("#edit_property_zipcode").val(res.orderDetails['property_zip']);
						/*$("#edit_primary_first_name").val(res.orderDetails['primary_owner_first_name']);
						$("#edit_primary_last_name").val(res.orderDetails['primary_owner_last_name']);
						$("#edit_vesting").val(res.orderDetails['vesting']);


						$("#edit_first_name").val(res.orderDetails['secondary_owner_first_name']);
						$("#edit_last_name").val(res.orderDetails['secondary_owner_last_name']);*/

						$("#edit_borrowers_vesting").val(res.orderDetails['borrowers_vesting']);
						$("#edit_loan_amount").val(res.orderDetails['loan_amount']);
						$("#edit_loan_number").val(res.orderDetails['loan_number']);


						$("#edit_TitleOfficer").val(res.orderDetails['title_officer']);
						$("#edit_branch").val(res.orderDetails['proposed_branch_id']);


						if(res.orderDetails['supplemental_report_date'] == null || res.orderDetails['supplemental_report_date'] == undefined || res.orderDetails['supplemental_report_date'].length == 0)
						{

	                		// $('#edit_preliminary_report_date').val(res.orderDetails['preliminary_report_date']);
						}
						else
						{
							$('#edit_supplemental_report_date').val(res.orderDetails['supplemental_report_date']);
						}

	                	/*$('#edit_supplemental_report_date').val(res.orderDetails['supplemental_report_date']);
	                	$('#edit_preliminary_report_date').val(res.orderDetails['preliminary_report_date']);*/

	                }
	                $('#edit_LenderId').val(res.orderDetails.lender_id);
	                if (res.orderDetails['lender_id'] != '')
                	{
						$("#edit_existing_lender").prop("checked", true);
						// $('input[name=new_existing_lender]').attr("disabled",true);
					} else {
						// $('input[name=new_existing_lender]').attr("disabled",false);
						$("#edit_add_lender").prop("checked", true);
					}
					$('#edit_orderId').val(res.orderDetails.orderId);
	            	$('#edit_transaction_id').val(res.orderDetails.transaction_id);
	            	$('#edit_property_id').val(res.orderDetails.property_id);
	            	$('#edit_fileId').val(res.orderDetails.fileId);
                	$('#edit_information').modal('show');
                }
                else
                {
                	alert("Something went wrong. Please try again.");
                }
            }
        });
	}
	else
	{
		alert("File ID required.");
	}
}

function downloadFile(binaryData)
{
	if (navigator.msSaveBlob)
    {
        var csvData = base64toBlob(binaryData,'application/octet-stream');
        var csvURL = navigator.msSaveBlob(csvData, 'ProposedInsured.pdf');
        var element = document.createElement('a');
        element.setAttribute('href', csvURL);
        element.setAttribute('download', 'ProposedInsured.pdf');
        element.style.display = 'none';
        document.body.appendChild(element);
        document.body.removeChild(element);
    }
    else
    {

        var csvURL = 'data:application/octet-stream;base64,'+binaryData;
        var element = document.createElement('a');
        element.setAttribute('href', csvURL);
        element.setAttribute('download', 'ProposedInsured.pdf');
        element.style.display = 'none';
        document.body.appendChild(element);
        element.click();
        document.body.removeChild(element);
    }
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