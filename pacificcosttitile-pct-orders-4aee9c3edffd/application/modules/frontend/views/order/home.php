<style>
	.typography-section__inner {
        margin-left: 10%;
    }
	.progress {
		height: auto;
		margin-bottom: 0px;
	}
	.align-display {
		flex-direction: row;
		align-items: center;
		display: flex;
	}
	.w-20 {
		width: 20px;
	}
	.tagline {
		height: 0;
		border-top: 1px solid #D9DDE5;
		text-align: center;
	}
	.tagline span {
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

	.form-grp-title {
		margin-top: 42px;
		margin-bottom: 42px;
	}
	.center-wrapper {
		margin: 0 auto;
	}

	.form-control {
		padding: 1.5rem 0.75rem;
	}
	select.form-control {
		height: 50px;
		padding: 0.375rem 0.75rem;
	}
</style>

<section class="section-type-4a section-defaulta" style="padding-bottom:0px;">
	<div class="container-fluid">
        <div class="row mb-3">
            <div class="col-sm-12">
                <h1 class="h3 text-gray-800 text-center">Helping Get Your Transaction Started.</h1>
            </div>
        </div>
        <div class="row ">
            <div class="col-md-10 center-wrapper">
                <div class="card shadow mb-4 smart-forms">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Add your details below.</h6>
                    </div>
                    <div class="card-body">
                        <form id="smart-form" method="POST"  enctype="multipart/form-data">
							<div class="row form-grp-title">
								<div class="col-sm-12">
									<div class="tagline"><span> YOUR DETAILS (WILL BE AUTOFILLED) </span></div>
								</div>
							</div>
                            <div class="row form-group">
								<div class="col-sm-6">
									<!-- <label for="resware_client_id" class="col-sm-2 col-form-label">Resware Client Id</label> -->
									<input value="<?php echo $customer_data['first_name']; ?>" type="text" name="OpenName" id="OpenName" class="form-control" placeholder=" First Name">
									<input type="hidden" name="id" id="CustomerId" value="<?php echo $customer_data['id']; ?>">
                                </div>
								<div class="col-sm-6">
									<input value="<?php echo $customer_data['last_name']; ?>" type="text" name="OpenLastName" id="OpenLastName" class="form-control" placeholder="Last Name">
                                </div>
                            </div>

							<div class="row form-group">
								<div class="col-sm-6">
									<input value="<?php echo $customer_data['telephone_no']; ?>" type="tel" name="Opentelephone" id="Opentelephone" class="form-control" placeholder="Telephone">
                                </div>
								<div class="col-sm-6">
									<input value="<?php echo $customer_data['email_address']; ?>" type="email" name="OpenEmail" id="OpenEmail" class="form-control" placeholder="Email address">
                                </div>
                            </div>

							<div class="row form-group">
								<div class="col-sm-6">
									<input value="<?php echo $customer_data['company_name']; ?>" type="text"
												name="CompanyName" id="CompanyName" class="form-control"
												placeholder="Company Name">
                                </div>
								<div class="col-sm-6">
									<input value="<?php echo $customer_data['street_address']; ?>"
												type="text" name="StreetAddress" id="StreetAddress"
												class="form-control" placeholder="Street Address">
                                </div>
                            </div>

							<div class="row form-group">
								<div class="col-sm-6">
									<input value="<?php echo $customer_data['city']; ?>" type="text" name="City" id="City" class="form-control" placeholder="City">
                                </div>
								<div class="col-sm-6">
									<input value="<?php echo $customer_data['zip_code']; ?>" type="text" name="Zipcode" id="Zipcode" class="form-control" placeholder="Zipcode">
                                </div>
                            </div>

							<div class="row form-grp-title">
								<div class="col-sm-12">
									<div class="tagline">
										<span>Find Your Property</span>
									</div>
								</div>
							</div>
							<div id="address_container">
								<div class="row form-group">
									<input type="hidden" name="property-state" id="property-state" value="">
									<input type="hidden" name="property-city" id="property-city" value="">
									<input type="hidden" name="neighbourhood" id="neighbourhood" value="">
									<input type="hidden" name="property-fips" id="property-fips" value="">
									<input type="hidden" name="property-full-address" id="property-full-address" value="">
									<input type="hidden" name="property-type" id="property-type" value="">
									<input type="hidden" name="property-zip" id="property-zip" value="">
									<input type="hidden" name="random_number" id="random_number" value="">
									<input type="hidden" name="ion-report-status" id="ion-report-status" value="false">
									<input type="hidden" name="ion-fraud-status" id="ion-fraud-status" value="false">
									<input type="hidden" name="ion-report-flag" id="ion-report-flag" value="<?php echo $ionFraudFlag; ?>">


									<div class="col-sm-12">
										<input type="text" name="Property" id="property-search" class="form-control gui-input pac-target-input" placeholder="Property Address">
									</div>
								</div>

								<div class="row form-group">
									<div class="col-sm-12">
										<a class="button btn btn-primary search-property search-property-button" href="javascript:void(0);" id="search-btn">Property Search</a>
										<a class="button btn btn-secondary switch-apn-button search-property-button" href="javascript:void(0);" id="switch-apn-btn">Switch To APN Search</a>
									</div>
								</div>
							</div>

							<div id="apn_container" style="display:none;">
								<div class="row form-group">
									<div class="col-sm-6">
										<input type="text" name="apn_num" id="apn_num" class="form-control" placeholder="APN">
									</div>
									<div class="col-sm-6">
										<input type="text" name="apn_county" id="apn_county" class="form-control" placeholder="County">
									</div>
								</div>

								<div class="row form-group">
									<div class="col-sm-12">
										<a class="button btn btn-primary search-apn search-apn-button" href="javascript:void(0);" id="search-apn-btn">APN Search</a>
										<a class="button btn btn-secondary switch-property-button search-apn-button" href="javascript:void(0);" id="switch-property-btn">Switch To Property Search</a>
									</div>
								</div>
							</div>
							<div class="row form-group">
								<div class="col-sm-6 pma-error alert alert-danger text-center" style="display:none;"></div>
								<div class="search-loader hidden"></div>
							</div>

							<div class="row form-grp-title">
								<div class="col-sm-12">
									<div class="tagline"><span> Property Details (Will Be AutoFilled) </span></div>
								</div>
							</div>

							<div class="row form-group">
								<div class="col-sm-6">
									<input type="text" name="FullProperty" id="FullProperty" class="form-control" placeholder="Full Street Address">
								</div>
								<div class="col-sm-6">
									<input type="text" name="apn" id="apn" class="form-control" placeholder="APN">
								</div>
							</div>

							<div class="row form-group">
								<div class="col-sm-6">
									<input type="text" name="County" id="County" class="form-control" placeholder="County">
								</div>
								<div class="col-sm-6">
									<input type="text" name="LegalDescription" id="LegalDescription" class="form-control" placeholder="Brief Legal Desription">
								</div>
								<input type="hidden" id="unit_number" name="unit_number" value="">
							</div>

							<div class="row form-grp-title">
								<div class="col-sm-12">
									<div class="tagline"><span>Seller Details (Will Be AutoFilled)</span></div>
								</div>
							</div>

							<div class="row form-group">
								<div class="col-sm-6">
									<input type="text" name="PrimaryOwner" id="PrimaryOwner" class="form-control" placeholder="Primary Owner">
								</div>
								<div class="col-sm-6">
									<input type="text" name="SecondaryOwner" id="SecondaryOwner" class="form-control" placeholder="Secondary Owner">
								</div>
							</div>

							<div class="row form-grp-title">
								<div class="col-sm-12">
									<div class="tagline"><span>Transaction Details</span></div>
								</div>
							</div>

							<div class="row form-group">
								<div class="col-sm-12">
									<select id="SalesRep" name="SalesRep" class="form-control">
										<option value="">Sales Rep...</option>
										<?php
if (isset($salesRep) && !empty($salesRep)) {
    foreach ($salesRep as $k => $v) {
        $name = array($v['first_name'], $v['last_name']);
        $full_name = implode(' ', $name);
        ?>
													<option value="<?php echo $v['id']; ?>"  <?php echo ($v['id'] == $customer_data['sales_rep_id']) ? "selected" : '' ?> ><?php echo $full_name; ?></option>
										<?php
}
}
?>
									</select>
								</div>
							</div>
							<div class="row form-group">
								<div class="col-sm-12">
									<select id="TitleOfficer" name="TitleOfficer" class="form-control">
										<option value="">Title Officer</option>
										<?php

if (isset($titleOfficer) && !empty($titleOfficer)) {
    foreach ($titleOfficer as $key => $value) {
        ?>
													<option value="<?php echo $value['id']; ?>" <?php echo ($value['id'] == $customer_data['title_officer_id']) ? "selected" : '' ?> ><?php echo $value['name']; ?></option>
										<?php
}
}
?>
									</select>
								</div>
							</div>

							<div class="row form-group">
								<div class="col-sm-12">
									<input type="hidden" name="ProductType" id="ProductType">
									<select id="ProductTypeID" name="ProductTypeID" class="form-control">
										<option value="">Select Product</option>
									</select>
								</div>
							</div>


							<div id="sales-loan-amount-fields" style="display:none;">
								<div class="row form-group">
									<div class="col-sm-12">
										<input type="text" data-type="number" class="form-control" name="salesAmount" id="salesAmount" placeholder="Sales Amount">
									</div>
								</div>
								<div class="row form-group">
									<div class="col-sm-12">
										<input type="text" data-type="number" class="form-control" name="loanAmount" id="loanAmount" placeholder="Loan Amount">
									</div>
								</div>

								<div class="row form-group">
									<div class="col-sm-12">
										<input type="text" class="form-control" name="primaryBorrower" id="primaryBorrower" placeholder="Primary Borrower">
									</div>
								</div>
								<div class="row form-group">
									<div class="col-sm-12">
										<input type="text" class="form-control" name="secondaryBorrower" id="secondaryBorrower" placeholder="Secondary Borrower">
									</div>
								</div>
							</div>

							<div class="row form-group">
								<div class="col-sm-12">
									<input type="text" class="form-control" name="escrowNumber" id="escrowNumber" placeholder="Escrow Number">
								</div>
							</div>
							<div class="row form-group">
								<div class="col-sm-12">
									<input type="text" class="form-control" name="loanNumber" id="loanNumber" placeholder="Loan Number">
								</div>
							</div>

							<div class="row form-grp-title">
								<div class="col-sm-12">
									<div class="tagline"><span>Add Deliverables</span></div>
								</div>
							</div>

							<div id="clone-email-address" class="cloneya-wrap" >
								<?php if (!empty($deliverables)) {
    $i = 0;
    foreach ($deliverables as $deliverable) {?>
											<div class="row form-group toclone clone-widget cloneya">
												<div class="col-sm-10">
														<?php if ($i == 0) {?>
															<input type="email" class="form-control" name="AdditionalEmail[]" id="AdditionalEmail" placeholder="Email Address" value="<?php echo $deliverable; ?>">
														<?php } else {?>
															<input type="email" class="form-control" name="AdditionalEmail[]" id="AdditionalEmail<?php echo $i; ?>" placeholder="Email Address" value="<?php echo $deliverable; ?>">
														<?php }?>
												</div>
												<a href="javascript:void(0)" class="clone button btn btn-primary mr-2"><i class="fa fa-plus pt-2"></i></a>
												<a href="javascript:void(0)" class="delete button btn btn-danger mr-2"><i class="fa fa-minus pt-2"></i></a>

											</div>

										<?php $i++;}
} else {?>
									<div class="row form-group toclone clone-widget cloneya">
										<div class="col-sm-10">
											<input type="email" class="form-control" name="AdditionalEmail[]" id="AdditionalEmail" placeholder="Email Address">
										</div>
										<a href="javascript:void(0)" class="clone button  btn btn-primary mr-2"><i class="fa fa-plus pt-2"></i></a>
										<a href="javascript:void(0)" class="delete button btn btn-danger mr-2"><i class="fa fa-minus pt-2"></i></a>
									</div>
								<?php }?>
							</div>

							<div class="row form-grp-title">
								<div class="col-sm-12">
									<div class="tagline"><span> Add Parties </span></div>
								</div>
							</div>


							<div class="row form-group">
								<div class="col-sm-3 align-display">
									<input type="checkbox" class="form-control w-20 mr-5" name="add-agent-details" id="add-agent-details">
									<span>Add Agent Details</span>
								</div>

								<?php

$is_escrow = isset($customer_data['is_escrow']) && !empty($customer_data['is_escrow']) ? $customer_data['is_escrow'] : 0;
$is_primary_mortgage_user = isset($customer_data['is_primary_mortgage_user']) && !empty($customer_data['is_primary_mortgage_user']) ? $customer_data['is_primary_mortgage_user'] : 0;

if ($is_escrow == 1 || $is_primary_mortgage_user == 1) {?>
										<div class="col-sm-3 align-display">
											<input type="checkbox" class="form-control w-20 mr-5" name="add-lender-details" id="add-lender-details">
											<span >Add Lender</span>
										</div>

									<?php }if ($is_escrow == 0 || $is_primary_mortgage_user == 1) {?>
										<div class="col-sm-3 align-display">
											<input type="checkbox" class="form-control w-20 mr-5" name="add-escrow-details" id="add-escrow-details">
											<span >Add Escrow</span>
										</div>
								<?php }?>

								<div class="col-sm-3 align-display" id="add-escrow-officer-section" style="display:none;">
									<input type="checkbox" class="form-control w-20 mr-5" name="add-escrow-officer-details" id="add-escrow-officer-details">
									<span >Add Escrow Officer</span>
								</div>
							</div>

							<div id="agent-details-fields" style="display: none;">
								<div class="row form-group">
									<div class="col-sm-6 text-center">
										<div class="tagline">
											<span>
												Buyers Agent
											</span>
										</div>
									</div>
									<div class="col-sm-6 text-center">
										<div class="tagline">
										<span>
											Listing Agent
										</span>
										</div>
									</div>
								</div>

								<div class="row form-group">
									<div class="col-sm-6">
										<input type="text" name="BuyerAgentName" id="BuyerAgentName" class="form-control" placeholder="Agent Name">
										<input type="hidden" name="BuyerAgentId" id="BuyerAgentId" value="">
										<input type="hidden" name="buyer_agent_partner_id" id="buyer_agent_partner_id" value="">
									</div>

									<div class="col-sm-6">
										<input type="text" name="ListingAgentName" id="ListingAgentName" class="form-control" placeholder="Agent Name">
										<input type="hidden" name="ListingAgentId" id="ListingAgentId" value="">
										<input type="hidden" name="listing_agent_partner_id" id="listing_agent_partner_id" value="">
									</div>
								</div>

								<div class="row form-group">
									<div class="col-sm-6">
										<input type="email" name="BuyerAgentEmailAddress" id="BuyerAgentEmailAddress" class="form-control" placeholder="Agent Email address">
									</div>

									<div class="col-sm-6">
										<input type="email" name="ListingAgentEmailAddress" id="ListingAgentEmailAddress" class="form-control" placeholder="Agent Email address">
									</div>
								</div>

								<div class="row form-group">
									<div class="col-sm-6">
										<input type="tel" name="BuyerAgentTelephone" id="BuyerAgentTelephone" class="form-control" placeholder="Agent Telephone">
									</div>

									<div class="col-sm-6">
										<input type="tel" name="ListingAgentTelephone" id="ListingAgentTelephone" class="form-control" placeholder="Agent Telephone">
									</div>
								</div>

								<div class="row form-group">
									<div class="col-sm-6">
										<input type="text" name="BuyerAgentCompany" id="BuyerAgentCompany" class="form-control" placeholder="Agent Company Name">
									</div>

									<div class="col-sm-6">
										<input type="text" name="ListingAgentCompany" id="ListingAgentCompany" class="form-control" placeholder="Agent Company Name">
									</div>
								</div>

								<div class="row form-group">
									<div class="col-sm-6">
										<div style="display: none;" class="alert notification alert-error" id="required-agent-details">Enter Buyers Agent or Listing Agent details</div>
									</div>
								</div>
							</div>

							<div id="lender-details-fields" style="display: none;">
								<div class="row spacer-b30 spacer-t30">
									<div class="col-sm-12">
										<div class="tagline"><span> Add Lender Details </span></div>
									</div>
								</div>
								<div class="row form-group">
									<div class="col-sm-6">
										<input type="text" name="LenderCompany" id="LenderCompany" class="form-control" placeholder="Lender Company Name">
									</div>
									<div class="col-sm-6">
										<input type="text" name="LenderName" id="LenderName" class="form-control" placeholder="Lender Name">
									</div>
									<input type="hidden" name="LenderId" id="LenderId" value="">
								</div>

								<div class="row form-group">
									<div class="col-sm-6">
										<input type="email" name="LenderEmailAddress" id="LenderEmailAddress" class="form-control" placeholder="Lender Email address">
									</div>

									<div class="col-sm-6">
										<input type="tel" name="LenderTelephone" id="LenderTelephone" class="form-control" placeholder="Lender Telephone">
									</div>
								</div>
							</div>

							<div id="escrow-details-fields" style="display: none;">
								<div class="row mb-5">
									<div class="col-sm-12">
										<div class="tagline"><span> Add Escrow Details </span></div>
									</div>
								</div>
								<div class="row form-group">
									<div class="col-sm-6">
										<input type="text" name="EscrowCompany" id="EscrowCompany" class="form-control" placeholder="Escrow Company Name">
									</div>
									<div class="col-sm-6">
										<input type="text" name="EscrowName" id="EscrowName" class="form-control" placeholder="Escrow Name">
									</div>
									<input type="hidden" name="EscrowId" id="EscrowId" value="">
								</div>

								<div class="row form-group">
									<div class="col-sm-6">
										<input type="email" name="EscrowEmailAddress" id="EscrowEmailAddress" class="form-control" placeholder="Escrow Email address">
									</div>

									<div class="col-sm-6">
										<input type="tel" name="EscrowTelephone" id="EscrowTelephone" class="form-control" placeholder="Escrow Telephone" readonly="readonly">
									</div>
								</div>
							</div>

							<div class="spacer-b30" id="escrow-officer-field" style="display: none;">
								<div class="row spacer-b30 spacer-t30">
									<div class="col-sm-12">
										<div class="tagline"><span> Select Escrow Officer </span></div>
									</div>
								</div>
								<div class="row form-group">
									<div class="col-sm-6">
										<select id="escrow_officer" name="escrow_officer" class="form-control">
											<option value="">----Select Escrow Officer----</option>
											<?php
if (isset($escrowOfficers) && !empty($escrowOfficers)) {
    foreach ($escrowOfficers as $escrowOfficer) {
        ?>
													<option value="<?php echo $escrowOfficer['partner_id']; ?>"><?php echo $escrowOfficer['partner_name']; ?></option>
											<?php
}
}
?>
										</select>
									</div>
								</div>

							</div>

							<?php if ($is_escrow == 0) {?>
							<div class="row form-grp-title">
								<div class="col-sm-12">
									<div class="tagline"><span>Upload Curative Document</span></div>
								</div>
							</div>

							<div class="row form-group">
								<div class="col-sm-6">
									<label class="button btn btn-primary search-file-btn">
										<input name="upload_curative" id="upload_curative" type="file" style="display:None;"> <span>Upload 1003</span>
									</label>
									<span> </span>
								</div>
							</div>

							<?php }?>

							<?php if ($is_escrow == 1) {?>
							<div class="row form-grp-title">
								<div class="col-sm-12">
									<div class="tagline"><span>Upload Curative Document</span></div>
								</div>
							</div>

							<div class="row form-group">
								<div class="col-sm-6">
									<label class="button btn btn-primary search-file-btn">
										<input name="upload_curative" id="upload_curative" type="file" style="display:None;"> <span>Upload RPA</span>
									</label>
									<span> </span>
								</div>
							</div>

							<?php }?>
							<div class="row form-group">
								<div class="col-sm-12">
									<div class="result spacer-b10"></div>
								</div>
							</div>

							<div class="row form-group">
								<div class="col-sm-12" id="progressDivId">
									<div class='' id='progressBar'></div>
									<div class='' id='percent'>0%</div>
								</div>
							</div>
                            <div class="form-group">
                                <div class="col-sm-6">
                                    <button type="submit" class="btn btn-success btn-icon-split home-submit" <?php echo ($submitButtonFlag == 0) ? "disabled" : ""; ?> >
                                        <span class="icon text-white-50">
                                            <i class="fas fa-save"></i>
                                        </span>
                                        <span class="text">Submit</span>
                                    </button>
                                    <button type="reset" class="btn btn-secondary btn-icon-split">
                                        <span class="icon text-white-50">
                                            <i class="fas fa-arrow-left"></i>
                                        </span>
                                        <span class="text">Cancel</span>
									</button>
									<a class="btn btn-info btn-icon-split" href="http://www.pct.com">
										<span class="icon text-white-50">
                                            <i class="fas fa-home"></i>
                                        </span>
                                        <span class="text">Homepage</span>
									</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
	<!-- <div class="container">
		<div class="row">
			<div class="typography-section__inner">
				<h2 class="ui-title-block ui-title-block_light"></h2>
				<div class="ui-decor-1a bg-accent"></div>
				<h3 class="ui-title-block_light">Helping Get Your Transaction Started.</h3>
			</div>
			<div class="col-md-12">
				<div class="smart-wrap">
					<div class="smart-forms smart-container wrap-0">

						<form method="POST" id="smart-form" enctype="multipart/form-data">
							<div class="form-body">
								<div class="spacer-b30 spacer-t30">
									<div class="tagline"><span>Your Details (Will Be AutoFilled) </span></div>
								</div>

								<div class="frm-row">
									<div class="section colm colm6">
										<label class="field prepend-icon">
											<input value="<?php echo $customer_data['first_name']; ?>" type="text" name="OpenName" id="OpenName" class="gui-input" placeholder=" First Name">
											<span class="field-icon"><i class="fa fa-user"></i></span>
											<input type="hidden" name="id" id="CustomerId"
												value="<?php echo $customer_data['id']; ?>">
										</label>
									</div>

									<div class="section colm colm6">
										<label class="field prepend-icon">
											<input value="<?php echo $customer_data['last_name']; ?>" type="text"
												name="OpenLastName" id="OpenLastName" class="gui-input"
												placeholder="Last Name">
											<span class="field-icon"><i class="fa fa-user"></i></span>
										</label>
									</div>
								</div>

								<div class="frm-row">
									<div class="section colm colm6">
										<label class="field prepend-icon">
											<input value="<?php echo $customer_data['telephone_no']; ?>" type="tel"
												name="Opentelephone" id="Opentelephone" class="gui-input"
												placeholder="Telephone">
											<span class="field-icon"><i class="fa fa-phone-square"></i></span>
										</label>
									</div>
									<div class="section colm colm6">
										<label class="field prepend-icon">
											<input value="<?php echo $customer_data['email_address']; ?>"
												type="email" name="OpenEmail" id="OpenEmail" class="gui-input"
												placeholder="Email address">
											<span class="field-icon"><i class="fa fa-envelope"></i></span>
										</label>
									</div>
								</div>

								<div class="frm-row">
									<div class="section colm colm6">
										<label class="field prepend-icon">
											<input value="<?php echo $customer_data['company_name']; ?>" type="text"
												name="CompanyName" id="CompanyName" class="gui-input"
												placeholder="Company Name">
											<span class="field-icon"><i class="fa fa-user"></i></span>
										</label>
									</div>

									<div class="section colm colm6">
										<label class="field prepend-icon">
											<input value="<?php echo $customer_data['street_address']; ?>"
												type="text" name="StreetAddress" id="StreetAddress"
												class="gui-input" placeholder="Street Address">
											<span class="field-icon"><i class="fa fa-envelope"></i></span>
										</label>
									</div>
								</div>

								<div class="frm-row">
									<div class="section colm colm6">
										<label class="field prepend-icon">
											<input value="<?php echo $customer_data['city']; ?>" type="text"
												name="City" id="City" class="gui-input" placeholder="City">
											<span class="field-icon"><i class="fa fa-user"></i></span>
										</label>
									</div>

									<div class="section colm colm6">
										<label class="field prepend-icon">
											<input value="<?php echo $customer_data['zip_code']; ?>" type="text"
												name="Zipcode" id="Zipcode" class="gui-input" placeholder="Zipcode">
											<span class="field-icon"><i class="fa fa-envelope"></i></span>
										</label>
									</div>
								</div>

								<div class="spacer-b30 spacer-t30">
									<div class="tagline">
										<span>Find Your Property</span>
									</div>
								</div>

								<input type="hidden" name="property-state" id="property-state" value="">
								<input type="hidden" name="property-city" id="property-city" value="">
								<input type="hidden" name="neighbourhood" id="neighbourhood" value="">
								<input type="hidden" name="property-fips" id="property-fips" value="">
								<input type="hidden" name="property-full-address"
									id="property-full-address" value="">
								<input type="hidden" name="property-type" id="property-type" value="">
								<input type="hidden" name="property-zip" id="property-zip" value="">
								<input type="hidden" name="random_number" id="random_number" value="">

								<div id="address_container">
									<div class="frm-row">
										<div class="section colm colm12">
											<label class="field prepend-icon">
												<input type="text" name="Property" id="property-search"
													class="gui-input" placeholder="Property Address">
												<span class="field-icon"><i class="fa fa-user"></i></span>
											</label>
										</div>
									</div>

									<div class="frm-row">
										<div class="section colm colm3">
											<a class="button btn-primary search-property search-property-button"
												href="javascript:void(0);" id="search-btn">Property Search</a>
										</div>
										<div class="section colm colm4">
											<a class="button switch-apn-button search-property-button"
												href="javascript:void(0);" id="switch-apn-btn">Switch To APN Search</a>
										</div>
									</div>
								</div>

								<div id="apn_container" style="display:none;">
									<div class="frm-row">
										<div class="section colm colm6">
											<label class="field prepend-icon">
												<input type="text" name="apn_num" id="apn_num"
													class="gui-input" placeholder="APN">
												<span class="field-icon"><i class="fa fa-user"></i></span>

											</label>
										</div>

										<div class="section colm colm6">
											<label class="field prepend-icon">
												<input type="text" name="apn_county" id="apn_county"
													class="gui-input" placeholder="County">
												<span class="field-icon"><i class="fa fa-envelope"></i></span>
											</label>
										</div>
									</div>

									<div class="frm-row">
										<div class="section colm colm3" style="width:auto !important;">
											<a class="button btn-primary search-apn search-apn-button"
												href="javascript:void(0);" id="search-apn-btn">APN Search</a>
										</div>
										<div class="section colm colm5">
											<a class="button switch-property-button search-apn-button"
												href="javascript:void(0);" id="switch-property-btn">Switch To Property Search</a>
										</div>
									</div>
								</div>


								<div class="pma-error alert alert-danger" style="display:none;"></div>
								<div class="search-loader hidden"></div>

								<div class="spacer-b30 spacer-t30">
									<div class="tagline"><span> Property Details (Will Be AutoFilled) </span></div>
								</div>

								<div class="frm-row">
									<div class="section colm colm12">
										<label class="field prepend-icon">
											<input type="text" name="FullProperty" id="FullProperty"
												class="gui-input" placeholder="Full Street Address">
											<span class="field-icon"><i class="fa fa-user"></i></span>
										</label>
									</div>
								</div>

								<div class="frm-row">
									<div class="section colm colm6">
										<label class="field prepend-icon">
											<input type="text" name="apn" id="apn" class="gui-input"
												placeholder="APN">
											<span class="field-icon"><i class="fa fa-user"></i></span>
										</label>
									</div>

									<div class="section colm colm6">
										<label class="field prepend-icon">
											<input type="text" name="County" id="County" class="gui-input"
												placeholder="County">
											<span class="field-icon"><i class="fa fa-envelope"></i></span>
										</label>
									</div>
								</div>

								<input type="hidden" id="unit_number" name="unit_number" value="">
								<div class="frm-row">
									<div class="section colm colm12">
										<label class="field prepend-icon">
											<input type="text" name="LegalDescription" id="LegalDescription"
												class="gui-input" placeholder="Brief Legal Desription">
											<span class="field-icon"><i class="fa fa-user"></i></span>
										</label>
									</div>
								</div>

								<div class="spacer-b30 spacer-t30">
									<div class="tagline"><span>Seller Details (Will Be AutoFilled)</span></div>

								</div>

								<div class="frm-row">
									<div class="section colm colm12">
										<label class="field prepend-icon">
											<input type="text" name="PrimaryOwner" id="PrimaryOwner"
												class="gui-input" placeholder="Primary Owner">
											<span class="field-icon"><i class="fa fa-user"></i></span>
										</label>
									</div>

									<div class="section colm colm12">
										<label class="field prepend-icon">
											<input type="text" name="SecondaryOwner" id="SecondaryOwner"
												class="gui-input" placeholder="Secondary Owner">
											<span class="field-icon"><i class="fa fa-user"></i></span>
										</label>
									</div>
								</div>

								<div class="spacer-b30 spacer-t30">
									<div class="tagline"><span> Transaction Details </span></div>
								</div>

								<div class="frm-row">
									<div class="section colm colm6">
										<label class="field select">
											<select id="SalesRep" name="SalesRep">
												<option value="">Sales Rep...</option>
												<?php
if (isset($salesRep) && !empty($salesRep)) {
    foreach ($salesRep as $k => $v) {
        $name = array($v['first_name'], $v['last_name']);
        $full_name = implode(' ', $name);
        ?>
															<option value="<?php
echo $v['id']; ?>"><?php
echo $full_name; ?></option>
												<?php
}
}
?>
											</select>
											<i class="arrow double"></i>
										</label>
									</div>

									<div class="section colm colm6">
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
									</div>
								</div>

								<div class="frm-row">
									<div class="section colm colm12">
										<label class="field select">
											<input type="hidden" name="ProductType" id="ProductType">
											<select id="ProductTypeID" name="ProductTypeID">
												<option value="">Select Product</option>
											</select>
											<i class="arrow double"></i>
										</label>
									</div>
								</div>

								<div class="frm-row" id="sales-loan-amount-fields" style="display: none;">
									<div class="section colm colm12">
										<label class="field">
											<input type="text" class="gui-input" name="salesAmount" id="salesAmount"
												placeholder="Sales Amount">
										</label>
										<div class="spacer-b10"></div>
										<label class="field">
											<input type="text" class="gui-input" name="loanAmount" id="loanAmount"
												placeholder="Loan Amount">
										</label>
										<div class="spacer-b10"></div>
										<label class="field">
											<input type="text" class="gui-input" name="primaryBorrower" id="primaryBorrower"
												placeholder="Primary Borrower">
										</label>
										<div class="spacer-b10"></div>
										<label class="field">
											<input type="text" class="gui-input" name="secondaryBorrower" id="secondaryBorrower"
												placeholder="Secondary Borrower">
										</label>
									</div>
								</div>
								<label class="field">
									<input type="text" class="gui-input" name="escrowNumber" id="escrowNumber"
										placeholder="Escrow Number">
								</label>
								<div class="spacer-b10"></div>
								<label class="field">
									<input type="text" class="gui-input" name="loanNumber" id="loanNumber"
										placeholder="Loan Number">
								</label>

								<div class="spacer-b30 spacer-t30">
									<div class="tagline"><span>Add Deliverables</span></div>

								</div>

								<div class="frm-row">
									<div class="section colm colm12" id="clone-email-address">
										<?php if (!empty($deliverables)) {
    $i = 0;
    foreach ($deliverables as $deliverable) {?>
													<div class="toclone clone-widget">
														<div class="spacer-b10">
															<label class="field">
																<?php if ($i == 0) {?>
																	<input type="email" class="gui-input" name="AdditionalEmail[]"
																	id="AdditionalEmail" placeholder="Email Address" value="<?php echo $deliverable; ?>">
																<?php } else {?>
																	<input type="email" class="gui-input" name="AdditionalEmail[]"
																	id="AdditionalEmail<?php echo $i; ?>" placeholder="Email Address" value="<?php echo $deliverable; ?>">
																<?php }?>

															</label>
														</div>
														<a href="#" class="clone button btn-primary"><i class="fa fa-plus"></i></a>
														<a href="#" class="delete button"><i class="fa fa-minus"></i></a>
													</div>

												<?php $i++;}
} else {?>
											<div class="toclone clone-widget">
												<div class="spacer-b10">
													<label class="field">
														<input type="email" class="gui-input" name="AdditionalEmail[]"
															id="AdditionalEmail" placeholder="Email Address">
													</label>
												</div>
												<a href="#" class="clone button btn-primary"><i class="fa fa-plus"></i></a>
												<a href="#" class="delete button"><i class="fa fa-minus"></i></a>
											</div>
										<?php }?>
									</div>

								</div>


								<div class="spacer-t30">
									<div class="tagline"><span> Add Parties</span></div>
								</div>
								<div class="frm-row">
									<div class="section colm colm4">
										<div class="option-group field">
											<label class="option block spacer-t10">
												<input type="checkbox" name="add-agent-details"
													id="add-agent-details">
												<span class="checkbox"></span> Add Agent Details
											</label>
										</div>
									</div>
									<?php

$is_escrow = isset($customer_data['is_escrow']) && !empty($customer_data['is_escrow']) ? $customer_data['is_escrow'] : 0;
$is_primary_mortgage_user = isset($customer_data['is_primary_mortgage_user']) && !empty($customer_data['is_primary_mortgage_user']) ? $customer_data['is_primary_mortgage_user'] : 0;

if ($is_escrow == 1 || $is_primary_mortgage_user == 1) {?>
											<div class="section colm colm4" id="add-lender-section">
												<div class="option-group field">
													<label class="option block spacer-t10">
														<input type="checkbox" name="add-lender-details"
															id="add-lender-details">
														<span class="checkbox"></span> Add Lender
													</label>
												</div>
											</div>

										<?php }if ($is_escrow == 0 || $is_primary_mortgage_user == 1) {?>
											<div class="section colm colm4" id="add-escrow-section">
												<div class="option-group field">
													<label class="option block spacer-t10">
														<input type="checkbox" name="add-escrow-details"
															id="add-escrow-details">
														<span class="checkbox"></span> Add Escrow
													</label>
												</div>
											</div>
									<?php }?>

									<div class="section colm colm4" id="add-escrow-officer-section" style="display:none;">
										<div class="option-group field">
											<label class="option block spacer-t10">
												<input type="checkbox" name="add-escrow-officer-details"
													id="add-escrow-officer-details">
												<span class="checkbox"></span> Add Escrow Officer
											</label>
										</div>
									</div>
								</div>

								<div id="agent-details-fields" style="display: none;">
									<div class="frm-row">
										<div class="spacer-b10"></div>
										<div class="section colm colm6 tagline">
											<div class="tagline">
												<span>
													Buyers Agent
												</span>
											</div>
										</div>
										<div class="section colm colm6 tagline">
											<div class="tagline">
												<span>
													Listing Agent
												</span>
											</div>
										</div>
									</div>
									<div class="frm-row">
										<div class="section colm colm6">
											<label class="field prepend-icon">
												<input type="text" name="BuyerAgentName" id="BuyerAgentName"
													class="gui-input" placeholder="Agent Name">
												<span class="field-icon"><i class="fa fa-user"></i></span>
												<input type="hidden" name="BuyerAgentId" id="BuyerAgentId" value="">
												<input type="hidden" name="buyer_agent_partner_id" id="buyer_agent_partner_id" value="">
											</label>
										</div>
										<div class="section colm colm6">
											<label class="field prepend-icon">
												<input type="text" name="ListingAgentName" id="ListingAgentName"
													class="gui-input" placeholder="Agent Name">
												<span class="field-icon"><i class="fa fa-user"></i></span>
												<input type="hidden" name="ListingAgentId" id="ListingAgentId"
													value="">
												<input type="hidden" name="listing_agent_partner_id" id="listing_agent_partner_id" value="">
											</label>
										</div>
									</div>

									<div class="frm-row">
										<div class="section colm colm6">
											<label class="field prepend-icon">
												<input type="email" name="BuyerAgentEmailAddress"
													id="BuyerAgentEmailAddress" class="gui-input"
													placeholder="Agent Email address">
												<span class="field-icon"><i class="fa fa-envelope"></i></span>
											</label>
										</div>

										<div class="section colm colm6">
											<label class="field prepend-icon">
												<input type="email" name="ListingAgentEmailAddress"
													id="ListingAgentEmailAddress" class="gui-input"
													placeholder="Agent Email address">
												<span class="field-icon"><i class="fa fa-envelope"></i></span>
											</label>
										</div>
									</div>

									<div class="frm-row">
										<div class="section colm colm6">
											<label class="field prepend-icon">
												<input type="tel" name="BuyerAgentTelephone"
													id="BuyerAgentTelephone" class="gui-input"
													placeholder="Agent Telephone">
												<span class="field-icon"><i class="fa fa-phone-square"></i></span>
											</label>
										</div>

										<div class="section colm colm6">
											<label class="field prepend-icon">
												<input type="tel" name="ListingAgentTelephone"
													id="ListingAgentTelephone" class="gui-input"
													placeholder="Agent Telephone">
												<span class="field-icon"><i class="fa fa-phone-square"></i></span>
											</label>
										</div>
									</div>
									<div class="frm-row">
										<div class="section colm colm6">
											<label class="field prepend-icon">
												<input type="text" name="BuyerAgentCompany" id="BuyerAgentCompany"
													class="gui-input" placeholder="Agent Company Name">
												<span class="field-icon"><i class="fa fa-user"></i></span>
											</label>
										</div>
										<div class="section colm colm6">
											<label class="field prepend-icon">
												<input type="text" name="ListingAgentCompany"
													id="ListingAgentCompany" class="gui-input"
													placeholder="Agent Company Name">
												<span class="field-icon"><i class="fa fa-user"></i></span>
											</label>
										</div>
									</div>
									<div style="display: none;" class="alert notification alert-error" id="required-agent-details">Enter Buyers Agent or Listing Agent details</div>
								</div>

								<div id="lender-details-fields" style="display: none;">

									<div class="spacer-b30">
										<div class="tagline"><span> Add Lender Details</span></div>
									</div>
									<div class="frm-row">
										<div class="section colm colm12">
											<label class="field prepend-icon">
												<input type="text" name="LenderCompany" id="LenderCompany"
													class="gui-input" placeholder="Lender Company Name">
												<span class="field-icon"><i class="fa fa-user"></i></span>
											</label>
										</div>
									</div>
									<div class="frm-row">
										<div class="section colm colm12">
											<label class="field prepend-icon">
												<input type="text" name="LenderName" id="LenderName"
													class="gui-input" placeholder="Lender Name">
												<span class="field-icon"><i class="fa fa-user"></i></span>
												<input type="hidden" name="LenderId" id="LenderId" value="">
											</label>
										</div>
									</div>

									<div class="frm-row">
										<div class="section colm colm6">
											<label class="field prepend-icon">
												<input type="email" name="LenderEmailAddress"
													id="LenderEmailAddress" class="gui-input"
													placeholder="Lender Email address">
												<span class="field-icon"><i class="fa fa-envelope"></i></span>
											</label>
										</div>

										<div class="section colm colm6">
											<label class="field prepend-icon">
												<input type="tel" name="LenderTelephone" id="LenderTelephone"
													class="gui-input" placeholder="Lender Telephone">
												<span class="field-icon"><i class="fa fa-phone-square"></i></span>
											</label>
										</div>
									</div>



								</div>

								<div id="escrow-details-fields" style="display: none;">

									<div class="spacer-b30">
										<div class="tagline"><span> Add Escrow Details</span></div>
									</div>
									<div class="frm-row">
										<div class="section colm colm12">
											<label class="field prepend-icon">
												<input type="text" name="EscrowCompany" id="EscrowCompany"
													class="gui-input" placeholder="Escrow Company Name">
												<span class="field-icon"><i class="fa fa-user"></i></span>
											</label>
										</div>
									</div>
									<div class="frm-row">
										<div class="section colm colm12">
											<label class="field prepend-icon">
												<input type="text" name="EscrowName" id="EscrowName"
													class="gui-input" placeholder="Escrow Name">
												<span class="field-icon"><i class="fa fa-user"></i></span>
												<input type="hidden" name="EscrowId" id="EscrowId" value="">
											</label>
										</div>
									</div>

									<div class="frm-row">
										<div class="section colm colm6">
											<label class="field prepend-icon">
												<input type="email" name="EscrowEmailAddress"
													id="EscrowEmailAddress" class="gui-input"
													placeholder="Escrow Email address">
												<span class="field-icon"><i class="fa fa-envelope"></i></span>
											</label>
										</div>

										<div class="section colm colm6">
											<label class="field prepend-icon">
												<input type="tel" name="EscrowTelephone" id="EscrowTelephone"
													class="gui-input" placeholder="Escrow Telephone"
													readonly="readonly">
												<span class="field-icon"><i class="fa fa-phone-square"></i></span>
											</label>
										</div>
									</div>

								</div>

								<div class="spacer-b30" id="escrow-officer-field" style="display: none;">
									<div class="spacer-b30">
										<div class="tagline"><span> Select Escrow Officer</span></div>
									</div>

									<div class="frm-row">
										<div class="section colm colm12">
											<label class="field select">
												<select id="escrow_officer" name="escrow_officer">
													<option value="">----Select Escrow Officer----</option>
													<?php
if (isset($escrowOfficers) && !empty($escrowOfficers)) {
    foreach ($escrowOfficers as $escrowOfficer) {
        ?>
															<option value="<?php echo $escrowOfficer['partner_id']; ?>"><?php echo $escrowOfficer['partner_name']; ?></option>
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

								<?php if ($is_escrow == 0) {?>
									<div class="spacer-b20 spacer-t30">
										<div class="tagline"><span> Upload Curative Documenttt</span></div>
									</div>
									<div class="frm-row">
										<div class="section colm colm12" id="upload_1003">
											<div class="option-group field">
												<div class="spacer-t20">
													<label class="button btn-primary search-file-btn">
														<input name="upload_curative" id="upload_curative" type="file" style="display:None;"> <span>Upload 1003</span>
													</label>
													<span></span>
												</div>
											</div>
										</div>
									</div>
								<?php }?>

								<?php if ($is_escrow == 1) {?>
									<div class="spacer-b20 spacer-t30">
										<div class="tagline"><span> Upload Curative Documentoo</span></div>
									</div>
									<div class="frm-row">
										<div class="section colm colm12" id="upload_rpa">
											<div class="option-group field">
												<div class="spacer-t20">
													<label class="button btn-primary search-file-btn">
														<input name="upload_curative" id="upload_curative" type="file" style="display:None;"> <span>Upload RPA</span>
													</label>
													<span></span>
												</div>
											</div>
										</div>
									</div>
								<?php }?>

								<div class="result spacer-b10"></div>


								<div class='' id="progressDivId">
									<div class='' id='progressBar'></div>
									<div class='' id='percent'>0%</div>
								</div>
								<div style="height: 10px;"></div>

							</div>
							<div class="form-footer">
								<button type="submit" data-btntext-sending="Sending..."
									class="button btn-primary" id="btn-place-order">Submit</button>
								<button type="reset" class="button">Cancel</button>
								<a style="border: 0;height: 42px;color: #243140;line-height: 1;font-size: 15px;cursor: pointer;padding: 0 18px;text-align: center;vertical-align: top;background: #bdc3c7;display: inline-block;-webkit-user-drag: none;text-shadow: 0 1px rgba(255, 255, 255, 0.2);margin-right: 10px;margin-bottom: 5px;text-decoration: none;border-radius: 3px;padding-top: 13px;"
									href="http://www.pct.com">Homepage</a>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</section> -->
</section>
<br><br>

<div class="modal fade" id="searchResultModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
	aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="row">
				<div class="col-lg-12">
					<div class="card shadow mb-4">
						<div class="card-header py-3">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
							<h6 class="m-0 font-weight-bold text-primary">Search Results</h6>
						</div>
						<div class="card-body">
							<div class="smart-forms smart-container">
								<div class="modal-body search-result">
									<table class="table table-bordered" width="100%">
										<thead>
											<tr>
												<th width="21%">APN</th>
												<th width="40%">Address</th>
												<!-- <th width="21%">City</th> -->
												<th width="21%">Unit Number</th>
												<th width="15%">Run Listing</th>
											</tr>
										</thead>
										<tbody></tbody>
									</table>
								</div>
								<div class="modal-footer">
									<div class="apn-search-loader hidden"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- <div class="modal-header">
				<h4 class="modal-title">Search Results</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body search-result">
				<table class="table-search-results" width="100%">
					<thead>
						<tr>
							<th width="21%">APN</th>
							<th width="22%">Address</th>
							<th width="21%">City</th>
							<th width="21%">Unit Number</th>
							<th width="15%">Run Listing</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
			<div class="modal-footer">
				<div class="apn-search-loader hidden"></div>
			</div> -->
		</div>
	</div>
</div>

<!-- Start Fraud confirmation popup -->
<div class="modal fade" id="searchFraudResultModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="row">
				<div class="col-lg-12">
					<div class="card shadow mb-4">
						<div class="card-header py-3">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
							<h6 class="m-0 font-weight-bold text-primary">Fraud Report Results For Property: <span class="ion-search-propery" ></span></h6>
						</div>
						<div class="card-body">
							<div class="smart-forms smart-container">
								<div class="modal-body ion-result">
									<table class="table table-bordered" width="100%">
										<thead>
											<tr>
												<th width="40%">Black Knight Owner Name</th>
												<th width="40%">ION Report Owner Name</th>
											</tr>
										</thead>
										<tbody></tbody>
									</table>
								</div>
								<div class="modal-footer" style="justify-content: flex-start">
									<button type="submit" class="button btn-primary ion-proceed">Proceed</button>
									<button type="button" class="button btn-primary ion-review-fraud" >Review Fraud</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- End Fraud confirmation popup -->

<div class="modal fade smart-forms" id="findCustomerModal" tabindex="-1" role="dialog"
	aria-labelledby="customerModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Find Customer Number</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body search-result">
				<form method="POST" action="" id="find-customer-form">
					<div class="form-body">
						<div class="frm-row">
							<div class="section colm colm12">
								<label class="field prepend-icon">
									<input type="email" name="CustomerEmail" id="CustomerEmail" class="gui-input"
										placeholder="Email address">
									<span class="field-icon"><i class="fa fa-envelope"></i></span>
								</label>
							</div>
						</div>
					</div>

					<div class="find-customer-result"></div>
			</div>
			<div class="modal-footer">
				<button type="submit" class="button btn-primary">Submit</button>
				<button type="button" class="button btn-primary" data-dismiss="modal">Cancel</button>
			</div>
			</form>
		</div>
	</div>
</div>
<!-- End Find Customer Number Modal -->

<!-- Start Show Customer Number Modal -->
<div class="modal fade smart-forms" id="showCustomernumberModal" tabindex="-1" role="dialog"
	aria-labelledby="showCustomernumberModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Customer Number</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body search-result">
				<div class="form-body">
					<div id="showCustomerNumber"></div>
				</div>
				<!-- <div class="find-customer-result"></div> -->
			</div>
			<div class="modal-footer">
				<button type="button" class="button btn-primary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<!-- End Show Customer Number Modal -->

