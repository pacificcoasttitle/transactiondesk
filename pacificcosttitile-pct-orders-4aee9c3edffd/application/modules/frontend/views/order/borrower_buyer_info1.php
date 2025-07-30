<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="author" content="colorlib.com">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Sign Up Form - Buyer</title>

	<!-- Font Icon -->
	<link rel="stylesheet"
		href="<?=base_url('assets/buyer-seller-packets/fonts/material-icon/css/material-design-iconic-font.min.css');?>">
	<!-- <link rel="stylesheet" href="<?=base_url('assets/buyer-seller-packets/vendor/nouislider/nouislider.min.css');?>"> -->

	<!-- Main css -->
	<link rel="stylesheet" href="<?=base_url('assets/buyer-seller-packets/css/style.css?buyer_v='.time());?>">
	<link rel="stylesheet" href="<?php echo base_url();?>assets/frontend/css/jquery-ui.css">
</head>

<style>
	.d-none {
		display: none;
	}

	.preloaderjs .spinner {display: none !important;}.preloaderjs#page-preloader {background: rgba(46, 46, 46, 0.99) !important;}#page-preloader {position: fixed;top: 0;right: 0;bottom: 0;left: 0;width: 100%;height: 100%;background: #2e2e2e;z-index: 100500;}#page-preloader .spinner {position: absolute;top: 50%;left: 50%;display: block;width: 100px;height: 100px;margin-top: -50px;margin-left: -50px;border: 3px solid transparent;border-top-color: #e7e4d7;border-radius: 50%;z-index: 1001;-webkit-animation: spin 2.5s infinite linear;animation: spin 2.5s infinite linear;}#page-preloader .spinner:before, #page-preloader .spinner:after {position: absolute;border-radius: 50%;content: '';}#page-preloader .spinner:before {top: 5px;right: 5px;bottom: 5px;left: 5px;border: 3px solid transparent;border-top-color: #71383e;-webkit-animation: spin 2s infinite linear;animation: spin 2s infinite linear;}#page-preloader .spinner:after {top: 15px;right: 15px;bottom: 15px;left: 15px;border: 3px solid transparent;border-top-color: #efa96b;-webkit-animation: spin 1s infinite linear;animation: spin 1s infinite linear;}@keyframes spin {0% {-webkit-transform: rotate(0);transform: rotate(0);}100% {-webkit-transform: rotate(360deg);transform: rotate(360deg);}}


</style>

<body>

	<div class="main">
		<div id="page-preloader" style="background-color: rgba(0, 0, 0, 0.5);display: none;"><span class="spinner border-t_second_b border-t_prim_a"></span></div>
		<div>
			<div class="container2">
				<img src="<?php echo base_url();?>assets/buyer-seller-packets/images/logo.png" style="width:300px;">
				<h1>Buyer Welcome Interview </h1>
				<h3><?php echo $orderDetails['full_address'];?></h3>
				<h4 style="margin-bottom:40px;">APN:<?php echo $orderDetails['apn'];?> | File# <?php echo $orderDetails['file_number'];?> </h4>

			</div>
		</div>


		<div class="container">
			<?php if(!empty($success)) {?>
			<div id="agent_success_msg" class="w-100 alert alert-success alert-dismissible">
				<?php foreach($success as $sucess) {
							echo $sucess."<br \>";	
						}?>

			</div>
			<?php  } 
			if(!empty($errors)) {?>
			<div id="agent_error_msg" class="w-100 alert alert-danger alert-dismissible">
				<?php foreach($errors as $error) {
						echo $error."<br \>";	
					}?>
			</div>
			<?php } ?>

			<form method="POST" id="signup-form" class="signup-form">
				<div>
					<h3>About You</h3>
					<input type="hidden" name="order_id" id="order_id" value="<?php echo $orderDetails['order_id'];?>">
					<fieldset>
						<h2>Personal information</h2>
						<p class="desc">Please enter your infomation and proceed to next step so we can build your
							account</p>
						<div class="fieldset-content">
							<?php if(count($buyers)) :?>
							<?php foreach($buyers as $key_buyer=>$buyer) :?>
							<div class="form-group">
								<p class="buyer_desc">
									<span class="desc_title">Buyer <?=($key_buyer+1)?></span>
									<span class="desc_border"></span>
								</p>
							</div>
							<div class="form-row">
								<div class="form-flex">
									<div class="form-group">
										<label class="form-label">First Name</label>
										<input type="text" name="buyer[<?=$buyer['id']?>][first_name]"
											required="required" value="<?=$buyer['first_name']?>" />
										<span class="text-input">example: John </span>
									</div>
									<div class="form-group">
										<label class="form-label">Last Name</label>
										<input type="text" name="buyer[<?=$buyer['id']?>][last_name]"
											required="required" value="<?=$buyer['last_name']?>" />
										<span class="text-input">example: Smith </span>
									</div>
								</div>
							</div>
							<div class="form-row">
								<div class="form-flex">
									<div class="form-group">
										<label for="email" class="form-label">Email</label>
										<input type="email" name="buyer[<?=$buyer['id']?>][email]" required="required"
											value="<?=$buyer['email']?>" />
										<span class="text-input">example: johnsmith@gmail.com </span>
									</div>
									<div class="form-group">
										<label for="phone" class="form-label">Mobile Phone #</label>
										<input class="phone_mask" type="text" name="buyer[<?=$buyer['id']?>][phone]"
											required="required" value="<?=$buyer['phone']?>"
											pattern="\(\d{3}\)[ ]?\d{3}[-]?\d{4}" />
										<span class="text-input">example: (000) 000-0000 </span>
									</div>
								</div>
							</div>

							<div class="form-row">
								<div class="form-flex">
									<div class="form-group form-date dob_date_picker_div">
										<label class="form-label">Birth Date</label>
										<div class="form-date-group">
											<div class="form-date-item">
												<select class="dob_birth_date" id="birth_date<?=$buyer['id']?>"
													name="buyer[<?=$buyer['id']?>][birth_date]" required="required"
													data-val="<?=$buyer['birth_date']?>"></select>
												<span class="text-input">DD</span>
											</div>
											<div class="form-date-item">
												<select class="dob_birth_month" id="birth_month<?=$buyer['id']?>"
													name="buyer[<?=$buyer['id']?>][birth_month]" required="required"
													data-val="<?=$buyer['birth_month']?>"></select>
												<span class="text-input">MM</span>
											</div>
											<div class="form-date-item">
												<select class="dob_birth_year" id="birth_year<?=$buyer['id']?>"
													name="buyer[<?=$buyer['id']?>][birth_year]" required="required"
													data-val="<?=$buyer['birth_year']?>"></select>
												<span class="text-input">YYYY</span>
											</div>
										</div>
									</div>

									<div class="form-group">
										<label class="form-label">Social Security No.</label>
										<input class="ssn" type="text" name="buyer[<?=$buyer['id']?>][ssn]"
											required="required" pattern="\d{3}-?\d{2}-?\d{4}"
											value="<?=$buyer['ssn']?>" />
										<span class="text-input">example: XXX-XX-XXXX </span>
									</div>

								</div>
							</div>
							<div class="form-group">
								<label class="form-label">Current Mailing Address</label>
								<input type="text" name="buyer[<?=$buyer['id']?>][current_mailing_address]"
									required="required" value="<?=$buyer['current_mailing_address']?>" />
								<span class="text-input">456 Main St. Los Angeles, CA </span>

							</div>
							<div class="form-group">
								<label class="form-label">Mailing Address Post Closing</label>
								<input type="text" name="buyer[<?=$buyer['id']?>][mailing_address_port_closing]"
									required="required"
									value="<?php echo ($buyer['mailing_address_port_closing'])?$buyer['mailing_address_port_closing']:$orderDetails['full_address'];?>" />
								<span class="text-input">456 Main St. Los Angeles, CA </span>

							</div>



							<?php  endforeach; ?>

							<?php endif; ?>

							<div class="form-row">
								<div class="form-group">
									<label class="form-label">Would You like to add another buyer?</label>
									<select name="is_another_buyer" class="buyer__show_hide_action"
										data-action="another_buyer" id="is_another_buyer" required="required">
										<option value="">Select</option>
										<option value="1">Yes</option>
										<option value="0">No</option>
									</select>
									<span class="text-input">example: yes or no. </span>
								</div>
							</div>

						</div>


						<div class="another_buyer buyer__show_hide_div" style="display: none;">
							<div class="fieldset-content">
								<div class="form-row">
									<div class="form-flex">
										<div class="form-group">
											<label class="form-label">First Name</label>
											<input type="text" name="buyer[new][first_name]" id="buyer_new_first_name"
												required="required" />
											<span class="text-input">example: John </span>
										</div>
										<div class="form-group">
											<label class="form-label">Last Name</label>
											<input type="text" name="buyer[new][last_name]" id="buyer_new_last_name"
												required="required" />
											<span class="text-input">example: Smith </span>
										</div>
									</div>
								</div>
								<div class="form-row">
									<div class="form-flex">
										<div class="form-group">
											<label for="email" class="form-label">Email</label>
											<input type="email" name="buyer[new][email]" required="required" />
											<span class="text-input">example: johnsmith@gmail.com </span>
										</div>
										<div class="form-group">
											<label for="phone" class="form-label">Mobile Phone #</label>
											<input class="phone_mask" type="text" name="buyer[new][phone]"
												required="required" pattern="\(\d{3}\)[ ]?\d{3}[-]?\d{4}" />
											<span class="text-input">example: (000) 000-0000 </span>
										</div>
									</div>
								</div>

								<div class="form-row">
									<div class="form-flex">
										<div class="form-group form-date dob_date_picker_div">
											<label class="form-label">Birth Date</label>
											<div class="form-date-group">
												<div class="form-date-item">
													<select class="dob_birth_date" id="birth_datenew"
														name="buyer[new][birth_date]" required="required"></select>
													<span class="text-input">DD</span>
												</div>
												<div class="form-date-item">
													<select class="dob_birth_month" id="birth_monthnew"
														name="buyer[new][birth_month]" required="required"></select>
													<span class="text-input">MM</span>
												</div>
												<div class="form-date-item">
													<select class="dob_birth_year" id="birth_yearnew"
														name="buyer[new][birth_year]" required="required"></select>
													<span class="text-input">YYYY</span>
												</div>
											</div>
										</div>
										<div class="form-group">
											<label class="form-label">Social Security No.</label>
											<input class="ssn" type="text" name="buyer[new][ssn]" required="required"
												pattern="\d{3}-?\d{2}-?\d{4}" />
											<span class="text-input">example: XXX-XX-XXXX </span>
										</div>

									</div>
								</div>
								<div class="form-group">
									<label class="form-label">Current Mailing Address</label>
									<input type="text" name="buyer[new][current_mailing_address]" required="required" />
									<span class="text-input">456 Main St. Los Angeles, CA </span>

								</div>
								<div class="form-group">
									<label class="form-label">Mailing Address Post Closing</label>
									<input type="text" name="buyer[new][mailing_address_port_closing]"
										required="required" value="<?php echo $orderDetails['full_address'];?>" />
									<span class="text-input">456 Main St. Los Angeles, CA </span>

								</div>

							</div>
						</div>
					</fieldset>

					<h3>Your History</h3>
					<fieldset>
						<h2>Personal History</h2>
						<p class="desc">Please enter your infomation below in regards to your aliases and marriage history.
						</p>
						<div class="fieldset-content">
							<div class="form-group">
								<p class="buyer_desc">
									<span class="desc_title">Buyer 1</span>
									<span class="desc_border"></span>
								</p>
							</div>
							<div class="form-row">
								<div class="form-group">
									<label class="form-label">Have you ever used another last name?</label>
									<select id="is_used_another_last_name" name="is_used_another_last_name" required="required">
										<option value="">Select</option>
										<option value="Yes">Yes</option>
										<option value="No">No</option>
									</select>
									<span class="text-input">example: Jones, Johnson, etc. </span>
								</div>
							</div>
							<div class="d-none" id="another_last_name_container">
								<div class="form-group">
									<label class="form-label">Enter Last Names Here Below</label>
									<input type="text" name="another_last_name" id="another_last_name" value="" />
									<span class="text-input">example: Jones, Johnson, etc. </span>
								</div>
							</div>

							<div class="form-row">
								<div class="form-group">
									<label class="form-label">Have you ever been married or have a domestic partner?</label>
									<select id="is_married_or_domestic_partner" name="is_married_or_domestic_partner" required="required">
										<option value="">Select</option>
										<option value="Yes">Yes</option>
										<option value="No">No</option>
									</select>
									<span class="text-input">example: Yes, No, etc. </span>
								</div>
							</div>

							<div class="d-none" id="married_or_domestic_partner">
								<div class="form-group">
									<div class="form-group form-date dob_date_picker_div">
										<label class="form-label">Enter Date of Marriage/Domestic Parntership</label>
										<div class="form-date-group">
											<div class="form-date-item">
												<select class="dob_birth_date" id="marriage_or_domestic_day"
													name="marriage_or_domestic_day"></select>
												<span class="text-input">DD</span>
											</div>
											<div class="form-date-item">
												<select class="dob_birth_month" id="marriage_or_domestic_month"
													name="marriage_or_domestic_month"></select>
												<span class="text-input">MM</span>
											</div>
											<div class="form-date-item">
												<select class="dob_birth_year" id="marriage_or_domestic_year"
													name="marriage_or_domestic_year"></select>
												<span class="text-input">YYYY</span>
											</div>
										</div>
									</div>
								</div>
								<div class="form-group">
									<p class="buyer_desc">
										<span class="desc_title">Spouse Domestic Partner</span>
										<span class="desc_border"></span>
									</p>
								</div>
								<div class="form-row">
									<div class="form-flex">
										<div class="form-group">
											<label class="form-label">First Name</label>
											<input type="text" name="spouse_first_name" id="spouse_first_name" value="" />
											<span class="text-input">example: John </span>
										</div>
										<div class="form-group">
											<label class="form-label">Last Name</label>
											<input type="text" name="spouse_last_name" id="spouse_last_name" value=""/>
											<span class="text-input">example: Smith </span>
										</div>
									</div>
								</div>
								<div class="form-row">
									<div class="form-flex">
										<div class="form-group">
											<label for="email" class="form-label">Email</label>
											<input type="email" name="spouse_email" id="spouse_email" value=""/>
											<span class="text-input">example: johnsmith@gmail.com </span>
										</div>
										<div class="form-group">
											<label for="phone" class="form-label">Mobile Phone #</label>
											<input class="phone_mask" type="text" name="spouse_phone" id="spouse_phone"
												value="" pattern="\(\d{3}\)[ ]?\d{3}[-]?\d{4}" />
											<span class="text-input">example: (000) 000-0000 </span>
										</div>
									</div>
								</div>

								<div class="form-row">
									<div class="form-flex">
										<div class="form-group form-date dob_date_picker_div">
											<label class="form-label">Birth Date</label>
											<div class="form-date-group">
												<div class="form-date-item">
													<select class="dob_birth_date" id="spouse_birth_day" name="spouse_birth_day"></select>
													<span class="text-input">DD</span>
												</div>
												<div class="form-date-item">
													<select class="dob_birth_month" id="spouse_birth_month" name="spouse_birth_month"></select>
													<span class="text-input">MM</span>
												</div>
												<div class="form-date-item">
													<select class="dob_birth_year" id="spouse_birth_year" name="spouse_birth_year"></select>
													<span class="text-input">YYYY</span>
												</div>
											</div>
										</div>

										<div class="form-group">
											<label class="form-label">Social Security No.</label>
											<input class="ssn" type="text" name="spouse_ssn" id="spouse_ssn" pattern="\d{3}-?\d{2}-?\d{4}" value="" />
											<span class="text-input">example: XXX-XX-XXXX </span>
										</div>

									</div>
								</div>
							</div>
							
							<div class="form-group">
								<p class="buyer_desc">
									<span class="desc_title">Residence History (Last 10 Years)</span>
									<span class="desc_border"></span>
								</p>
							</div>
							
							<div class="form-row">
								<div class="form-group">
									<label class="form-label">Is the property you are selling:
										<?php echo $orderDetails['full_address'];?></label>
									<select id="is_property_sell_2" name="is_property_sell_2" required="required">
										<option value="">Select</option>
										<option value="Yes">Yes</option>
										<option value="No">No</option>
									</select>
								</div>
							</div>

							<div class="d-none" id="is_another_property_sell">
								<div class="form-group">
									<label class="form-label">Enter the property name that is selling</label>
									<input type="text" name="another_property_sell" id="another_property_sell" value="" />
									<span class="text-input">example: Jones, Johnson, etc. </span>
								</div>
							</div>

							<div class="form-row">
								<div class="form-flex">
									<div class="form-group">
										<label class="form-label">From</label>
										<input type="text" class="datepicker" name="from_date" id="from_date" required="required" value="" />
									</div>
									<div class="form-group">
										<label class="form-label">To</label>
										<input type="text" class="datepicker" name="from_to" id="from_to" required="required" value="" />
									</div>
								</div>
							</div>
							<div class="form-row">
								<div class="form-group">
									<label class="form-label">Would you like to add another residence?</label>
									<select id="is_another_residence" name="is_another_residence" required="required">
										<option value="">Select</option>
										<option value="Yes">Yes</option>
										<option value="No">No</option>
									</select>
								</div>
							</div>

							<div class="d-none" id="another_residence_container">
								<div class="form-group">
									<label class="form-label">Enter the another residence name</label>
									<input type="text" name="another_residence" id="another_residence" value="" />
									<span class="text-input">example: Jones, Johnson, etc. </span>
								</div>
							
								<div class="form-row">
									<div class="form-flex">
										<div class="form-group">
											<label class="form-label">From</label>
											<input type="text" class="datepicker" name="another_from_date" id="another_from_date" value="" />
										</div>
										<div class="form-group">
											<label class="form-label">To</label>
											<input type="text" class="datepicker" name="another_to_date" id="another_to_date" value="" />
										</div>
									</div>
								</div>
							</div>

							<div class="form-row">
								<div class="form-group">
									<label class="form-label">Buyer intends to reside on the property?</label>
									<select id="is_reside_property" name="is_reside_property" required="required">
										<option value="">Select</option>
										<option value="Yes">Yes</option>
										<option value="No">No</option>
									</select>
								</div>
							</div>

							<div class="form-row">
								<div class="form-group">
									<label class="form-label">Property Address: <?php echo $orderDetails['full_address'];?></label>
									<select id="is_property_address" name="is_property_address" required="required">
										<option value="">Select</option>
										<option value="Yes">Yes</option>
										<option value="No">No</option>
									</select>
								</div>
							</div>

							<div class="form-row">
								<div class="form-group">
									<label class="form-label">The land is unimproved/improved with a structure type of the followng:</label>
									<select id="is_unimproved_improved" name="is_unimproved_improved" required="required">
										<option value="">Select</option>
										<option value="Yes">Yes</option>
										<option value="No">No</option>
									</select>
								</div>
							</div>

							<div class="form-row">
								<div class="form-group">
									<label class="form-label">Improvements/Remodel/Repairs made in the last 6 months?</label>
									<select id="is_improvement" name="is_improvement" required="required">
										<option value="">Select</option>
										<option value="Yes">Yes</option>
										<option value="No">No</option>
									</select>
								</div>
							</div>

							<div class="d-none" id="is_improvement_container">
								<div class="form-row">
									<div class="form-group">
										<label class="form-label">If Yes, have all cost and labor been paid in full?</label>
										<select id="is_full_paid" name="is_full_paid" required="required">
											<option value="">Select</option>
											<option value="Yes">Yes</option>
											<option value="No">No</option>
										</select>
									</div>
								</div>
							</div>
							
							<div class="form-row">
								<div class="form-group">
									<label class="form-label">Are there any current loans against the property?</label>
									<select id="is_loan" name="is_loan" required="required">
										<option value="">Select</option>
										<option value="Yes">Yes</option>
										<option value="No">No</option>
									</select>
								</div>
							</div>

							<div class="d-none" id="is_loan_container">
								<div class="form-row">
									<div class="form-group">
										<label class="form-label">Lender Name</label>
										<input type="text" name="lender_name_2" id="lender_name_2" value="" />
									</div>
								</div>
								<div class="form-row">
									<div class="form-flex">
										<div class="form-group form-date dob_date_picker_div">
											<label class="form-label">Loan Amount</label>
											<input type="text" name="lender_loan_amount" id="lender_loan_amount" value="" />
										</div>

										<div class="form-group form-date dob_date_picker_div">
											<label class="form-label">Acct No</label>
											<input type="text" name="lender_acct_no" id="lender_acct_no" value="" />
										</div>
									</div>
								</div>
							</div>
						</div>
					</fieldset>

					<h3>Occupation History</h3>
					<fieldset>
						<h2>Occupation History</h2>
						<p class="desc">Please enter your infomation below in regards to your employment history</p>
						<div class="fieldset-content">
							<div class="form-group">
								<p class="buyer_desc">
									<span class="desc_title">Buyer 1 Employment History (Last 10 Years)</span>
									<span class="desc_border"></span>
								</p>
							</div>
							<div class="form-row">
								<div class="form-group">
									<label class="form-label">Are You Currently Employed?</label>
									<select id="is_currently_employed" name="is_currently_employed" required="required">
										<option value="">Select</option>
										<option value="Yes">Yes</option>
										<option value="No">No</option>
									</select>
									<span class="text-input"></span>
								</div>
							</div>
							
							<div class="d-none" id="currently_employed_container">
								<div class="form-row">
									<div class="form-group">
										<label class="form-label">Please Enter Company Name</label>
										<input type="text" name="employee_company_name" id="employee_company_name" value="" />
									</div>
								</div>
								<div class="form-row">
									<div class="form-flex">
										<div class="form-group form-date dob_date_picker_div">
											<label class="form-label">From:</label>
											<input type="text" class="datepicker" name="from_employee_date" id="from_employee_date" value="" />
										</div>

										<div class="form-group form-date dob_date_picker_div">
											<label class="form-label">To:</label>
											<input type="text" class="datepicker" name="to_employee_date" id="to_employee_date" value="" />
										</div>
									</div>
								</div>
							</div>
						
							<div class="form-row">
								<div class="form-group">
									<label class="form-label">Would you like to add another occupation?</label>
									<select id="is_add_another_occupation" name="is_add_another_occupation" required="required">
										<option value="">Select</option>
										<option value="Yes">Yes</option>
										<option value="No">No</option>
									</select>
								</div>
							</div>

							<div class="d-none" id="another_occupation_container">
								<div class="form-row">
									<div class="form-group">
										<label class="form-label">Please Enter Company Name</label>
										<input type="text" name="employee_another_company_name" id="employee_another_company_name" value="" />
									</div>
								</div>
								<div class="form-row">
									<div class="form-flex">
										<div class="form-group form-date dob_date_picker_div">
											<label class="form-label">From:</label>
											<input type="text" class="datepicker" name="another_from_employee_date" id="another_from_employee_date" value="" />
										</div>

										<div class="form-group form-date dob_date_picker_div">
											<label class="form-label">To:</label>
											<input type="text" class="datepicker" name="another_to_employee_date" id="another_to_employee_date" value="" />
										</div>
									</div>
								</div>
							</div>
							
							<div class="form-row d-none" id="married_employed_container">
								<div class="form-group">
									<label class="form-label">Is Your Spouse/Domestic Partner employed?</label>
									<select id="is_spouse_domestic_partner_employed" name="is_spouse_domestic_partner_employed">
										<option value="">Select</option>
										<option value="Yes">Yes</option>
										<option value="No">No</option>
									</select>
									<span class="text-input">example: Yes, No, etc. </span>
								</div>
							</div>
							
							<div class="d-none" id="spouse_domestic_partner_employed_container">
								<div class="form-group">
									<p class="buyer_desc">
										<span class="desc_title">Spouse/Domestic Partner Employment</span>
										<span class="desc_border"></span>
									</p>
								</div>
								
								<div class="form-row">
									<div class="form-group">
										<label class="form-label">Please Enter Company Name</label>
										<input type="text" name="spouse_company_name" id="spouse_company_name" value="" />
									</div>
								</div>
								<div class="form-row">
									<div class="form-flex">
										<div class="form-group form-date dob_date_picker_div">
											<label class="form-label">From:</label>
											<input type="text" class="datepicker" name="from_spouse_date" id="from_spouse_date" value="" />
										</div>

										<div class="form-group form-date dob_date_picker_div">
											<label class="form-label">To:</label>
											<input type="text" class="datepicker" name="to_spouse_date" id="to_spouse_date" value="" />
										</div>
									</div>
								</div>
							</div>
							<div class="form-row d-none" id="married_occupation_container">
								<div class="form-group">
									<label class="form-label">Would you like to add another occupation for your spouse/domestic partner?</label>
									<select id="is_another_occupation_spouse_domestic" name="is_another_occupation_spouse_domestic">
										<option value="">Select</option>
										<option value="Yes">Yes</option>
										<option value="No">No</option>
									</select>
								</div>
							</div>

							<div class="d-none" id="another_spouse_domestic_partner_employed_container">
								<div class="form-group">
									<p class="buyer_desc">
										<span class="desc_title">Spouse/Domestic Partner Employment</span>
										<span class="desc_border"></span>
									</p>
								</div>
								
								<div class="form-row">
									<div class="form-group">
										<label class="form-label">Please Enter Company Name</label>
										<input type="text" name="another_spouse_company_name" id="another_spouse_company_name" value="" />
									</div>
								</div>
								<div class="form-row">
									<div class="form-flex">
										<div class="form-group form-date dob_date_picker_div">
											<label class="form-label">From:</label>
											<input type="text" class="datepicker" name="another_from_spouse_date" id="another_from_spouse_date" value="" />
										</div>

										<div class="form-group form-date dob_date_picker_div">
											<label class="form-label">To:</label>
											<input type="text" class="datepicker" name="another_to_spouse_date" id="another_to_spouse_date" value="" />
										</div>
									</div>
								</div>
							</div>
						</div>
					</fieldset>

					<h3>Property & Loan</h3>

					<fieldset>

						<h2>About Your Property</h2>
						<p class="desc">Please enter your infomation and proceed to next step so we can build your
							account</p>
						<div class="form-row">
							<div class="form-group">
								<label class="form-label">Is this the property that you are buying:
									<?php echo $orderDetails['full_address'];?> </label>
								<select name="is_same_property" required="required">
									<option value="">Select</option>
									<option value="1">Yes</option>
									<option value="0">No</option>
								</select>
								<span class="text-input">example: yes or no. </span>
							</div>
						</div>
						<div class="form-group">
							<label class="form-label">Enter the Loan Amount that you applied For:</label>
							<input class="amount_mask" type="text" name="loan_amount" id="appliedloan"
								required="required" />
							<span class="text-input">example: $674,950 </span>

						</div>
						<div class="form-group">
							<label class="form-label">What is the Name of Your Lender?</label>
							<input type="text" name="lender_name" required="required" />
							<span class="text-input">Wells Fargo, Bank of America, etc. </span>

						</div>
						<div class="form-group">
							<label for="loanofficer" class="form-label">Enter You Loan Officers Name ( if applicable
								)</label>
							<input type="text" name="loan_officer_name" id="loanofficer" />
							<span class="text-input">John Smith </span>

						</div>

						<div class="form-row">
							<div class="form-flex">
								<div class="form-group">
									<label class="form-label">Enter Loan Officer Email ( if applicable )</label>
									<input type="email" name="loan_officer_email" id="loemail" />
									<span class="text-input">example: Johnsmith@abcloancompany.com </span>
								</div>
								<div class="form-group">
									<label class="form-label">Enter Loan Officer Phone Number ( if applicable )</label>
									<input class="phone_mask" type="text" name="loan_officer_phone" id="lophone"
										pattern="\(\d{3}\)[ ]?\d{3}[-]?\d{4}" />
									<span class="text-input">example: (800) 000-0000 </span>
								</div>
							</div>
						</div>


						<div class="form-row">
							<div class="form-group">
								<label class="form-label">Are you working with a loan processor?</label>
								<select name="is_loan_processor" class="buyer__show_hide_action"
									data-action="loan_processor_div" required="required">
									<option value="">Select</option>
									<option value="1">Yes</option>
									<option value="0">No</option>
								</select>
								<span class="text-input">example: yes or no. </span>
							</div>
						</div>

						<div class="loan_processor_div" style="display: none;">
							<div class="form-group">
								<label for="lpanofficer" class="form-label">Enter You Loan Processor Name </label>
								<input type="text" name="loan_processor_name" id="lpanofficer" required="required" />
								<span class="text-input">John Smith </span>

							</div>

							<div class="form-row">
								<div class="form-flex">
									<div class="form-group">
										<label class="form-label">Enter Loan Processor Email </label>
										<input type="email" name="loan_processor_email" id="lpemail"
											required="required" />
										<span class="text-input">example: Johnsmith@abcloancompany.com </span>
									</div>
									<div class="form-group">
										<label class="form-label">Enter Loan Processor Phone Number</label>
										<input class="phone_mask" type="text" name="loan_processor_phone" id="lpphone"
											required="required" pattern="\(\d{3}\)[ ]?\d{3}[-]?\d{4}" />
										<span class="text-input">example: (800) 000-0000 </span>
									</div>
								</div>
							</div>
						</div>



					</fieldset>

					<h3>About Your Insurance</h3>
					<fieldset>
						<h2>Home Insurance</h2>
						<p class="desc">Tell us a little bit about it. </p>
						<div class="fieldset-content">


							<div class="form-row">
								<div class="form-group">
									<label class="form-label">Insurance</label>
									<select name="is_home_ins" class="buyer__show_hide_action"
										data-action="home_ins_div" required="required">
										<option value="">Select</option>
										<option value="1">Yes</option>
										<option value="0">No</option>
									</select>
									<span class="text-input">example: yes or no.</span>
								</div>
							</div>
							<div class="home_ins_div" style="display: none;">

								<div class="form-row">
									<div class="form-group">
										<label class="form-label">Insuance Agency Name</label>
										<input type="text" name="ins_agency_name" required="required" />
										<span class="text-input">example: Statefarm, Allstate, etc. </span>
									</div>
								</div>

								<div class="form-row">
									<div class="form-group">
										<label class="form-label">Insurance Agent Name</label>
										<input type="text" name="ins_agent_name" required="required" />
										<span class="text-input">example: Statefarm, Allstate, etc. </span>
									</div>
								</div>


								<div class="form-row">
									<div class="form-flex">
										<div class="form-group">
											<label class="form-label">Insurance Agent's Email</label>
											<input type="email" name="ins_agent_email" required="required" />
											<span class="text-input">example: johnsmith@abcinsurance.com </span>
										</div>
										<div class="form-group">
											<label class="form-label">Insurance Agent's Phone Number</label>
											<input class="phone_mask" type="text" name="ins_agent_phone"
												required="required" pattern="\(\d{3}\)[ ]?\d{3}[-]?\d{4}" />
											<span class="text-input">example: (800) 000-0000 </span>
										</div>
									</div>
								</div>

								<div class="form-row">
									<div class="form-group">
										<label class="form-label">What Annual Premium were you quoted?</label>
										<input class="amount_mask" type="text" name="annual_premium"
											required="required" />
										<span class="text-input">example:$3600 annual premium </span>
									</div>
								</div>
							</div>



						</div>
					</fieldset>

					<h3>Vesting & Ownership</h3>
					<fieldset>
						<h2>Vesting & Ownership</h2>
						<p class="desc">Please review the relationship and martial status of each of the buyers </p>

						<?php if(count($buyers)) :?>
						<?php foreach($buyers as $key_buyer=>$buyer) :?>
						<div class="form-row vesting-buyer-div" data-id="<?=$buyer['id']?>">
							<div class="form-flex">
								<div class="form-group">
									<label for="email" class="form-label">Buyer <?=($key_buyer+1)?></label>
									<input type="text" name="" id=""
										value="<?=$buyer['first_name']." ".$buyer['last_name']?>" />
								</div>
								<div class="form-group">
									<label for="email" class="form-label">Marital Status</label>
									<select id="buyer_<?=$buyer['id']?>_marital_status"
										name="buyer[<?=$buyer['id']?>][marital_status]" required="required" class="buyer__show_hide_action has__data_val marital_status_select" data-action="married-to-option<?=$buyer['id']?>">
										<option value="" data-val="0">Select</option>
										<?php foreach($marital_status as $marital_status_key=>$marital_status_val) : ?>
											<option value="<?=$marital_status_key?>" data-val="<?=$marital_status_val['show_married_option'];?>"><?=$marital_status_val['text'];?></option>
										<?php endforeach; ?>
									</select>
								</div>
								<div class="form-group married-to-option<?=$buyer['id']?>" style="display: none;" >
									<label for="email" class="form-label">Married To:</label>
									<select class="married married_option_change" id="buyer_<?=$buyer['id']?>_married_to"
										name="buyer[<?=$buyer['id']?>][married_to]" data-related="">
										<option value="">Select</option>
										<?php foreach($buyers as $key_buyer_married_to=>$buyer_married_to) :?>
										<?php if($buyer_married_to['id'] != $buyer['id']) :?>
										<option value="<?=$buyer_married_to['id']?>">
											<?=$buyer_married_to['first_name']." ".$buyer_married_to['last_name']?>
										</option>
										<?php endif; ?>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
						</div>
						<?php $new_buyer_index = $key_buyer+1;?>
						<?php endforeach; ?>
						<?php endif; ?>

						<div class="form-row d-none" id="new_buyer_vesting_container" class="vesting-buyer-div" data-id="new">
							<div class="form-flex">
								<div class="form-group">
									<label for="email" class="form-label">Buyer <?=($new_buyer_index+1)?></label>
									<input type="text" name="new_buyer_name" id="new_buyer_name" value="" />
								</div>
								<div class="form-group">
									<label for="email" class="form-label">Marital Status</label>
									<select id="buyer_new_marital_status" name="buyer[new][marital_status]"
										required="required" class="buyer__show_hide_action has__data_val" data-action="married-to-optionnew">
										<option value="">Select</option>
										<?php foreach($marital_status as $marital_status_key=>$marital_status_val) : ?>
											<option value="<?=$marital_status_key?>" data-val="<?=$marital_status_val['show_married_option'];?>"><?=$marital_status_val['text'];?></option>
										<?php endforeach; ?>
									</select>
								</div>
								<div class="form-group married-to-optionnew" style="display: none;" >
									<label for="email" class="form-label">Married To:</label>
									<select class="married married_option_change"  id="buyer_new_married_to" name="buyer[new][married_to]" data-related="">
										<option value="">Select</option>
										<?php foreach($buyers as $key_buyer_married_to=>$buyer_married_to) :?>

										<option value="<?=$buyer_married_to['id']?>">
											<?=$buyer_married_to['first_name']." ".$buyer_married_to['last_name']?>
										</option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
						</div>

						<div class="form-row">
							<div class="form-group">
								<label class="form-label">Please tell us how the property will be vested:</label>
								<select id="property_vested" name="property_vested" required="required">
									<option value="">Select</option>
									<?php foreach($vesting_choice as $vesting_choice_key=>$vesting_choice_val) : ?>
											<option value="<?=$vesting_choice_key?>" ><?=$vesting_choice_val['text'];?></option>
										<?php endforeach; ?>
								</select>
								<!-- <span class="text-input">example: yes or no. </span> -->
							</div>
						</div>
					</fieldset>

					<h3>Proceeds/Refund</h3>
					<fieldset>
						<h2>Proceeds/Refund Disbursement Instructions</h2>
						<p class="desc">Tell us a little bit about it.</p>

						<div class="form-row">
							<div class="form-group">
								<label class="form-label">The Undersigned hereby authorizes and directs Pacific Coast Title Company to disburse proceeds as follows:</label>
								<select id="proceeds_refund" name="proceeds_refund" required="required">
									<option value="">Select</option>
									<option value="transfer_all_proceeds" >Transfer All Proceeds</option>
									<option value="transfer_portion" >Transfer Portion</option>
									<option value="issue_physical_check_for_pickup">Issue physical Check For PickUp</option>
									<option value="fed_Ex_check_address">Fed Ex Check Addres</option>
									<option value="wire_proceeds">Wire Proceeds</option>
								</select>
							</div>
						</div>

						<div class="d-none" id="transfer_all_proceeds_cont">
							<div class="form-row">
								<div class="form-group">
									<label class="form-label">Att:</label>
									<input type="text" name="transfer_all_proceeds_att" id="transfer_all_proceeds_att" />
								</div>
							</div>

							<div class="form-row">
								<div class="form-group">
									<label class="form-label">Esc#:</label>
									<input type="text" name="transfer_all_proceeds_esc" id="transfer_all_proceeds_esc" />
								</div>
							</div>
						</div>

						<div class="d-none" id="transfer_portion_cont">
							<div class="form-row">
								<div class="form-group">
									<label class="form-label">Att:</label>
									<input type="text" name="transfer_portion_att" id="transfer_portion_att" />
								</div>
							</div>

							<div class="form-row">
								<div class="form-group">
									<label class="form-label">Esc#:</label>
									<input type="text" name="transfer_portion_att_esc" id="transfer_portion_att_esc" />
								</div>
							</div>
						</div>

						<div class="d-none" id="fed_Ex_check_address_cont">
							<div class="form-row">
								<div class="form-group">
									<label class="form-label">Address</label>
									<input type="text" name="fed_Ex_check_address" id="fed_Ex_check_address" />
								</div>
							</div>
						</div>

						<div class="d-none" id="wire_proceeds_cont">
							<div class="form-row">
								<div class="form-group">
									<label class="form-label">Bank Name</label>
									<input type="text" name="bank_name" id="bank_name"/>
								</div>
							</div>
							<div class="form-row">
								<div class="form-flex">
									<div class="form-group">
										<label class="form-label">Account Name</label>
										<input type="text" name="account_name" id="account_name" />
									</div>
									<div class="form-group">
										<label class="form-label">Phone Number</label>
										<input class="phone_mask" type="text" name="wire_proceed_phone"
											id="wire_proceed_phone" pattern="\(\d{3}\)[ ]?\d{3}[-]?\d{4}" />
										<span class="text-input">example: (800) 000-0000 </span>
									</div>
								</div>
							</div>
							<div class="form-row">
								<div class="form-flex">
									<div class="form-group">
										<label class="form-label">Routing Number</label>
										<input type="text" name="routing_number" id="routing_number" />
									</div>
									<div class="form-group">
										<label class="form-label">Account Number</label>
										<input type="text" name="account_number" id="account_number" />
									</div>
								</div>
							</div>
						</div>
					</fieldset>

					<h3>Confirmation</h3>
					<fieldset>
						<h2>Confirmation</h2>
						<p>&nbsp;</p>
						<p class="desc">Signing below indicates that the information included here is correct and
							complete to the best of my knowledge and ackowledges and accepts the information included in
							this document.</p>
						<p class="desc">You must click below Finish button to securely send your completed forms to
							Pacific Coast Title Company.</p>
					</fieldset>
				</div>
			</form>
		</div>
	</div>

	<!-- JS -->
	<script src="<?=base_url('assets/buyer-seller-packets/vendor/jquery/jquery.min.js');?>"></script>
	<script src="<?php echo base_url();?>assets/buyer-seller-packets/vendor/jquery/jquery-ui.min.js"></script>
	<script src="<?=base_url('assets/buyer-seller-packets/vendor/jquery-validation/dist/jquery.validate.min.js');?>">
	</script>
	<script src="<?=base_url('assets/buyer-seller-packets/vendor/jquery-validation/dist/additional-methods.min.js');?>">
	</script>
	<script src="<?=base_url('assets/buyer-seller-packets/vendor/jquery-steps/jquery.steps.min.js');?>"></script>
	<script src="<?=base_url('assets/buyer-seller-packets/vendor/minimalist-picker/dobpicker.js');?>"></script>
	<script src="<?=base_url('assets/buyer-seller-packets/vendor/nouislider/nouislider.min.js');?>"></script>
	<script src="<?=base_url('assets/buyer-seller-packets/vendor/wnumb/wNumb.js');?>"></script>
	<script src="<?=base_url('assets/buyer-seller-packets/vendor/input-mask/jquery.mask.min.js');?>"></script>
	<script src="<?=base_url('assets/buyer-seller-packets/js/buyer-main.js?buyer_v='.time());?>"></script>
	<script src="<?php echo base_url();?>assets/js/custom.js?seller_v=<?=time()?>"></script>
</body>

</html>
