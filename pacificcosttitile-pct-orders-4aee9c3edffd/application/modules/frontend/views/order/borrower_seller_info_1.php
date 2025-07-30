<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="author" content="colorlib.com">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Sign Up Form</title>

	<!-- Font Icon -->
	<link rel="stylesheet"
		href="<?php echo base_url();?>assets/buyer-seller-packets/fonts/material-icon/css/material-design-iconic-font.min.css">
	<link rel="stylesheet"
		href="<?php echo base_url();?>assets/buyer-seller-packets/vendor/nouislider/nouislider.min.css">

	<!-- Main css -->
	<link rel="stylesheet" href="<?php echo base_url();?>assets/buyer-seller-packets/css/style.css?seller_v=<?=time()?>">
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
		<div class"File">
			<div class="container2">
				<img src="<?php echo base_url();?>assets/buyer-seller-packets/images/logo.png" style="width:300px;">
				<h1>Seller Welcome Interview </h1>
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
			<form method="POST" id="signup-form" class="signup-form"
				action="<?php echo base_url().'seller-info/'.$orderDetails['file_id']; ?>">
				<div>

					<h3>About You</h3>
					<input type="hidden" name="order_id" id="order_id" value="<?php echo $orderDetails['order_id'];?>">
					<fieldset>
						<h2>Personal information</h2>
						<p class="desc">Please enter your infomation and proceed to next step so we can build your
							account</p>
					<div class="fieldset-content">
							<?php foreach($sellers as $key_seller=>$seller) :?>
							<div class="form-group">
								<p class="buyer_desc">
									<span class="desc_title">Seller <?=($key_seller+1)?></span>
									<span class="desc_border"></span>
								</p>
							</div>
							<div class="form-row">
								<div class="form-flex">
									<div class="form-group">
										<label class="form-label">First Name</label>
										<input type="text" name="seller[<?=$seller['id']?>][first_name]"
											required="required" value="<?=$seller['first_name']?>" />
										<span class="text-input">example: John </span>
									</div>
									<div class="form-group">
										<label class="form-label">Last Name</label>
										<input type="text" name="seller[<?=$seller['id']?>][last_name]"
											required="required" value="<?=$seller['last_name']?>" />
										<span class="text-input">example: Smith </span>
									</div>
								</div>
							</div>
							<div class="form-row">
								<div class="form-flex">
									<div class="form-group">
										<label for="email" class="form-label">Email</label>
										<input type="email" name="seller[<?=$seller['id']?>][email]" required="required"
											value="<?=$seller['email']?>" />
										<span class="text-input">example: johnsmith@gmail.com </span>
									</div>
									<div class="form-group">
										<label for="phone" class="form-label">Mobile Phone #</label>
										<input class="phone_mask" type="text" name="seller[<?=$seller['id']?>][phone]"
											required="required" value="<?=$seller['phone']?>"
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
												<select class="dob_birth_date" id="birth_date<?=$seller['id']?>"
													name="seller[<?=$seller['id']?>][birth_date]" required="required"
													data-val="<?=$seller['birth_date']?>"></select>
												<span class="text-input">DD</span>
											</div>
											<div class="form-date-item">
												<select class="dob_birth_month" id="birth_month<?=$seller['id']?>"
													name="seller[<?=$seller['id']?>][birth_month]" required="required"
													data-val="<?=$seller['birth_month']?>"></select>
												<span class="text-input">MM</span>
											</div>
											<div class="form-date-item">
												<select class="dob_birth_year" id="birth_year<?=$seller['id']?>"
													name="seller[<?=$seller['id']?>][birth_year]" required="required"
													data-val="<?=$seller['birth_year']?>"></select>
												<span class="text-input">YYYY</span>
											</div>
										</div>
									</div>

									<div class="form-group">
										<label class="form-label">Social Security No.</label>
										<input class="ssn" type="text" name="seller[<?=$seller['id']?>][ssn]"
											required="required" pattern="\d{3}-?\d{2}-?\d{4}"
											value="<?=$seller['ssn']?>" />
										<span class="text-input">example: XXX-XX-XXXX </span>
									</div>

								</div>
							</div>
							<div class="form-group">
								<label class="form-label">Current Mailing Address</label>
								<input type="text" name="seller[<?=$seller['id']?>][current_mailing_address]"
									required="required" value="<?php echo ($seller['current_mailing_address'])?$seller['current_mailing_address']:$orderDetails['full_address'];?>" />
								<span class="text-input">456 Main St. Los Angeles, CA </span>

							</div>
							<div class="form-group">
								<label class="form-label">Mailing Address Post Closing</label>
								<input type="text" name="seller[<?=$seller['id']?>][mailing_address_port_closing]"
									required="required"
									value="<?=$seller['mailing_address_port_closing']?>" />
								<span class="text-input">456 Main St. Los Angeles, CA </span>

							</div>



							<?php  endforeach; ?>

							<div class="form-row">
								<div class="form-group">
									<label class="form-label">Would You Like to Add Another Seller?</label>
									<select id="is_another_seller" name="is_another_seller" required="required">
										<option value="">Select</option>
										<option value="Yes">Yes</option>
										<option value="No">No</option>
									</select>
									<span class="text-input">example: Statefarm, Allstate, etc. </span>
								</div>
							</div>

							<div class="d-none" id="second_seller">
							<div class="fieldset-content">
								<div class="form-row">
									<div class="form-flex">
										<div class="form-group">
											<label class="form-label">First Name</label>
											<input type="text" name="seller[new][first_name]" id="buyer_new_first_name"
												required="required" />
											<span class="text-input">example: John </span>
										</div>
										<div class="form-group">
											<label class="form-label">Last Name</label>
											<input type="text" name="seller[new][last_name]" id="buyer_new_last_name"
												required="required" />
											<span class="text-input">example: Smith </span>
										</div>
									</div>
								</div>
								<div class="form-row">
									<div class="form-flex">
										<div class="form-group">
											<label for="email" class="form-label">Email</label>
											<input type="email" name="seller[new][email]" required="required" />
											<span class="text-input">example: johnsmith@gmail.com </span>
										</div>
										<div class="form-group">
											<label for="phone" class="form-label">Mobile Phone #</label>
											<input class="phone_mask" type="text" name="seller[new][phone]"
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
														name="seller[new][birth_date]" required="required"></select>
													<span class="text-input">DD</span>
												</div>
												<div class="form-date-item">
													<select class="dob_birth_month" id="birth_monthnew"
														name="seller[new][birth_month]" required="required"></select>
													<span class="text-input">MM</span>
												</div>
												<div class="form-date-item">
													<select class="dob_birth_year" id="birth_yearnew"
														name="seller[new][birth_year]" required="required"></select>
													<span class="text-input">YYYY</span>
												</div>
											</div>
										</div>
										<div class="form-group">
											<label class="form-label">Social Security No.</label>
											<input class="ssn" type="text" name="seller[new][ssn]" required="required"
												pattern="\d{3}-?\d{2}-?\d{4}" />
											<span class="text-input">example: XXX-XX-XXXX </span>
										</div>

									</div>
								</div>
								<div class="form-group">
									<label class="form-label">Current Mailing Address</label>
									<input type="text" name="seller[new][current_mailing_address]" required="required" />
									<span class="text-input">456 Main St. Los Angeles, CA </span>

								</div>
								<div class="form-group">
									<label class="form-label">Mailing Address Post Closing</label>
									<input type="text" name="seller[new][mailing_address_port_closing]"
										required="required" value="<?php echo $orderDetails['full_address'];?>" />
									<span class="text-input">456 Main St. Los Angeles, CA </span>

								</div>

								</div>
							</div>
							<div class="form-row">
								<div class="form-group">
									<label class="form-label">Is the Seller a Trustee or Trust?</label>
									<select id="is_trustee" name="is_trustee" required="required">
										<option value="">Select</option>
										<option value="Yes">Yes</option>
										<option value="No">No</option>
									</select>
									<span class="text-input">example: yes, no </span>
								</div>

								<div class="d-none" id="trustee_container">
									<div class="form-group">
										<label for="ssn" class="form-label">Who is/are the Current Acting
											Trustee(s)?</label>
										<input type="text" name="current_trustees" id="current_trustees" />
										<span class="text-input">example: John,Jane </span>
									</div>

									<div class="form-group">
										<label class="form-label">Are they the Original Trustees Or Successor
											Trustee(s)?</label>
										<select id="is_original_trustees" name="is_original_trustees"
											required="required">
											<option value="">Select</option>
											<option value="Yes">Yes</option>
											<option value="No">No</option>
										</select>
										<span class="text-input">example: yes, no </span>
									</div>
								</div>
							</div>

							<div class="form-row">
								<div class="form-group">
									<label class="form-label">Is the Seller a as Limited Liability Company, Corporation,
										Partnership?</label>
									<select id="is_limited_company" name="is_limited_company" required="required">
										<option value="">Select</option>
										<option value="Yes">Yes</option>
										<option value="No">No</option>
									</select>
									<span class="text-input">example: yes, no </span>
								</div>
							</div>
							<div class="form-row">
								<div class="form-group">
									<label class="form-label">What is the Seller(s) current marital status?</label>
									<select id="is_married" name="is_married" required="required">
										<option value="">Select</option>
										<option value="single">Single</option>
										<option value="married">Married</option>
										<option value="widowed">Widowed</option>
										<option value="divorced">Divorced</option>
										<option value="separated">Separated</option>
									</select>
									<span class="text-input">example: single, married, divorced. </span>
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
									<span class="desc_title">Seller 1</span>
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

							<div class="form-row is_married_or_domestic_partner_wrapper">
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
						</div>
					</fieldset>

					<h3>Occupation History</h3>
					<fieldset>
						<h2>Occupation History</h2>
						<p class="desc">Please enter your infomation below in regards to your employment history</p>
						<div class="fieldset-content">
							<div class="form-group">
								<p class="buyer_desc">
									<span class="desc_title">Seller 1 Employment History (Last 10 Years)</span>
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
						<h2>About Your Loan</h2>
						<p class="desc">Tell us a little bit about the finances.</p>
						<div class="fieldset-content">

							<div class="form-row">
								<div class="form-group">
									<label class="form-label">Is the property you are selling:
										<?php echo $orderDetails['full_address'];?></label>
									<select id="is_property_sell" name="is_property_sell" required="required">
										<option value="">Select</option>
										<option value="Yes">Yes</option>
										<option value="No">No</option>
									</select>
									<span class="text-input">example: yes or no. </span>
								</div>
							</div>

							<div class="form-row">
								<div class="form-group">
									<label class="form-label">Is the property owned Free and Clear?</label>
									<select id="is_property_owned_free_clear" name="is_property_owned_free_clear"
										required="required">
										<option value="">Select</option>
										<option value="Yes">Yes</option>
										<option value="No">No</option>
									</select>
									<span class="text-input">example: yes or no. </span>
								</div>
							</div>

							<div class="d-none" id="property_owned_free_clear_no">
								<div class="form-group">
									<label for="ssn" class="form-label">Lender Name</label>
									<input type="text" name="lender_name" id="lender_name" />
									<span class="text-input">example: Wells Fargo, Bank of America </span>
								</div>
								<div class="form-group">
									<label for="ssn" class="form-label">Lender Address</label>
									<input type="text" name="lender_address" id="lender_address" />
									<span class="text-input">456 Main St. Los Angeles, CA </span>
								</div>

								<div class="form-row">
									<div class="form-flex">
										<div class="form-group">
											<label class="form-label">Loan Number</label>
											<input type="text" name="loan_number" id="loan_number" />
											<span class="text-input">example: 8845648974 </span>
										</div>
										<div class="form-group">
											<label class="form-label">Phone Number</label>
											<input type="text" class="phone_mask" name="lender_phone_number"
												id="lender_phone_number" />
											<span class="text-input">example: (800) 000-0000 </span>
										</div>
									</div>
								</div>

								<div class="form-row">
									<div class="form-flex">
										<div class="form-group">
											<label class="form-label">Unpaid Balance</label>
											<input type="text" class="amount_mask" name="unpaid_balance"
												id="unpaid_balance" />
											<span class="text-input">example: $585,452.00 </span>
										</div>
										<div class="form-group">
											<label class="form-label">Payment Due Date</label>
											<input type="text" class="datepicker" name="payment_due_date" id="payment_due_date" />
											<span class="text-input">example: 10/15/2022 </span>
										</div>
									</div>
								</div>

								<div class="form-row">
									<div class="form-flex">
										<div class="form-group">
											<label class="form-label">Loan Type</label>
											<select id="loan_type" name="loan_type">
												<option value="">Select</option>
												<option value="VA">VA</option>
												<option value="FHA">FHA</option>
												<option value="Conventional">Conventional</option>
												<option value="Equity">Equity Line</option>
											</select>
											<span class="text-input">example: Conventional, FHA </span>
										</div>
										<div class="form-group d-none">
											<label class="form-label">Impound Accouunt?</label>
											<select id="is_impound_account" name="is_impound_account">
												<option value="">Select</option>
												<option value="Yes">Yes</option>
												<option value="No">No</option>
											</select>
											<span class="text-input">example: Yes or No </span>
										</div>
									</div>
								</div>
								<div class="form-row d-none">
									<div class="form-flex">
										<div class="form-group">
											<label class="form-label">Taxes Status</label>
											<select id="tax_status" name="tax_status">
												<option value="">Select</option>
												<option value="Paid">Paid</option>
												<option value="Unpaid">Unpaid</option>
											</select>
											<span class="text-input">example: Paid or Unpaid </span>
										</div>
										<div class="form-group">
											<label class="form-label">Paid via Impound</label>
											<select id="is_paid_impound" name="is_paid_impound">
												<option value="">Select</option>
												<option value="Yes">Yes</option>
												<option value="No">No</option>
											</select>
											<span class="text-input">example: Yes or No </span>
										</div>
									</div>
								</div>
							</div>

							<div class="form-row">
								<div class="form-group">
									<label class="form-label">Add Another Loan?</label>
									<select id="is_another_loan" name="is_another_loan" required="required">
										<option value="">Select</option>
										<option value="Yes">Yes</option>
										<option value="No">No</option>
									</select>
									<span class="text-input">example: yes or no. </span>
								</div>
							</div>

							<div class="d-none" id="another_loan_option">
								<div class="form-group">
									<label for="ssn" class="form-label">Lender Name</label>
									<input type="text" name="second_lender_name" id="second_lender_name" />
									<span class="text-input">example: Wells Fargo, Bank of America </span>
								</div>
								<div class="form-group">
									<label for="ssn" class="form-label">Lender Address</label>
									<input type="text" name="second_lender_address" id="second_lender_address" />
									<span class="text-input">456 Main St. Los Angeles, CA </span>
								</div>

								<div class="form-row">
									<div class="form-flex">
										<div class="form-group">
											<label class="form-label">Loan Number</label>
											<input type="text" name="second_loan_number" id="second_loan_number" />
											<span class="text-input">example: 8845648974 </span>
										</div>
										<div class="form-group">
											<label class="form-label">Phone Number</label>
											<input type="text" class="phone_mask" name="second_lender_phone_number"
												id="second_lender_phone_number" />
											<span class="text-input">example: (800) 000-0000 </span>
										</div>
									</div>
								</div>

								<div class="form-row">
									<div class="form-flex">
										<div class="form-group">
											<label class="form-label">Unpaid Balance</label>
											<input type="text" class="amount_mask" name="second_unpaid_balance"
												id="second_unpaid_balance" />
											<span class="text-input">example: $585,452.00 </span>
										</div>
										<div class="form-group">
											<label class="form-label">Payment Due Date</label>
											<input type="text" class="datepicker" name="second_payment_due_date"
												id="second_payment_due_date" />
											<span class="text-input">example: 10/15/2022 </span>
										</div>
									</div>
								</div>

								<div class="form-row">
									<div class="form-flex">
										<div class="form-group">
											<label class="form-label">Loan Type</label>
											<select id="second_loan_type" name="second_loan_type">
												<option value="">Select</option>
												<option value="VA">VA</option>
												<option value="FHA">FHA</option>
												<option value="Conventional">Conventional</option>
												<option value="Equity">Equity Line</option>
											</select>
											<span class="text-input">example: Conventional, FHA </span>
										</div>
										<div class="form-group d-none">
											<label class="form-label">Impound Accouunt?</label>
											<select id="second_is_impound_account" name="second_is_impound_account">
												<option value="">Select</option>
												<option value="Yes">Yes</option>
												<option value="No">No</option>
											</select>
											<span class="text-input">example: Yes or No </span>
										</div>
									</div>
								</div>
								<div class="form-row d-none">
									<div class="form-flex">
										<div class="form-group">
											<label class="form-label">Taxes Status</label>
											<select id="second_tax_status" name="second_tax_status">
												<option value="">Select</option>
												<option value="Paid">Paid</option>
												<option value="Unpaid">Unpaid</option>
											</select>
											<span class="text-input">example: Paid or Unpaid </span>
										</div>
										<div class="form-group">
											<label class="form-label">Paid via Impound</label>
											<select id="second_is_paid_impound" name="second_is_paid_impound">
												<option value="">Select</option>
												<option value="Yes">Yes</option>
												<option value="No">No</option>
											</select>
											<span class="text-input">example: Yes or No </span>
										</div>
									</div>
								</div>
							</div>
						</div>
					</fieldset>
					<h3>Utilities & HOA</h3>
					<fieldset>
						<h2>Utilities</h2>
						<p class="desc">Let us know who services your home.</p>
						<div class="fieldset-content">
							<div class="form-row">
								<div class="form-group">
									<label class="form-label">Is there a Private Water Company with Water Stock
										Affecting the Property?</label>
									<select id="is_private_water_company" name="is_private_water_company"
										required="required">
										<option value="">Select</option>
										<option value="Yes">Yes</option>
										<option value="No">No</option>
									</select>
									<span class="text-input">example: yes or no. </span>
								</div>
							</div>

							<div class="d-none" id="water_company_container">
								<div class="form-group">
									<label for="ssn" class="form-label">Water Company Name</label>
									<input type="text" name="water_company" id="water_company" />
									<span class="text-input">example: abc water company </span>
								</div>
								<div class="form-group">
									<label for="ssn" class="form-label">Company Address</label>
									<input type="text" name="water_company_address" id="water_company_address" />
									<span class="text-input">456 Main St. Los Angeles, CA </span>
								</div>

								<div class="form-row">
									<div class="form-flex">
										<div class="form-group">
											<label class="form-label">Account Number</label>
											<input type="number" name="water_account_number"
												id="water_account_number" />
											<span class="text-input">example: 8582985455 </span>
										</div>
										<div class="form-group">
											<label class="form-label">Phone Number</label>
											<input type="text" class="phone_mask" name="water_phone_number"
												id="water_phone_number" />
											<span class="text-input">example: (000) 000-0000 </span>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="fieldset-content">
							<div class="form-row">
								<div class="form-group">
									<label class="form-label">Are there any Homeowners Associations affecting the
										Property?</label>
									<select id="is_hoa" name="is_hoa" required="required">
										<option value="">Select</option>
										<option value="Yes">Yes</option>
										<option value="No">No</option>
									</select>
									<span class="text-input">example: yes or no. </span>
								</div>
							</div>

							<div class="d-none" id="hoa_container">
								<div class="form-group">
									<label for="ssn" class="form-label">HOA Management Company Name</label>
									<input type="text" name="hoa_company" id="hoa_company" />
									<span class="text-input">example: Ranch Hills Home Owners Association </span>

								</div>
								<div class="form-group">
									<label for="ssn" class="form-label">HOA Management Company Address</label>
									<input type="text" name="hoa_company_address" id="hoa_company_address" />
									<span class="text-input">456 Main St. Los Angeles, CA </span>

								</div>

								<div class="form-row">
									<div class="form-flex">
										<div class="form-group">
											<label class="form-label">HOA Contact Person</label>
											<input type="text" name="hoa_contact_person" id="hoa_contact_person" />
											<span class="text-input">example: HOA customer service </span>
										</div>
										<div class="form-group">
											<label class="form-label">HOA Contact Phone</label>
											<input type="text" class="phone_mask" name="hoa_contact_number"
												id="hoa_contact_number" />
											<span class="text-input">example: (000) 000-0000</span>
										</div>
									</div>
								</div>
							</div>

							<div class="form-row">
								<div class="form-group">
									<label class="form-label">Would you like to add another HOA?</label>
									<select id="is_another_hoa" name="is_another_hoa" required="required">
										<option value="">Select</option>
										<option value="Yes">Yes</option>
										<option value="No">No</option>
									</select>
								</div>
							</div>

							<div class="d-none" id="another_hoa_container">
								<div class="form-group">
									<label for="ssn" class="form-label">HOA Management Company Name</label>
									<input type="text" name="second_hoa_company" id="second_hoa_company" />
									<span class="text-input">example: Ranch Hills Home Owners Association </span>

								</div>
								<div class="form-group">
									<label for="ssn" class="form-label">HOA Management Company Address</label>
									<input type="text" name="second_hoa_company_address" id="second_hoa_company_address" />
									<span class="text-input">456 Main St. Los Angeles, CA </span>

								</div>

								<div class="form-row">
									<div class="form-flex">
										<div class="form-group">
											<label class="form-label">HOA Contact Person</label>
											<input type="text" name="second_hoa_contact_person" id="second_hoa_contact_person" />
											<span class="text-input">example: HOA customer service </span>
										</div>
										<div class="form-group">
											<label class="form-label">HOA Contact Phone</label>
											<input type="text" class="phone_mask" name="second_hoa_contact_number"
												id="second_hoa_contact_number" />
											<span class="text-input">example: (000) 000-0000</span>
										</div>
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


					<!--	<h3>Policy Affidavit</h3>
                
                <fieldset>                   
                    
                     <h2>About Your Property</h2>
                            <p class="desc">Please enter your infomation and proceed to next step so we can build your account</p>
                    <div class="fieldset-content">
                        <div class="form-row">
                         <div class="form-group">
                                 <label class="form-label">(1.) Is This Your Property?[Insert Property Address Here]</label>
                                  <select id="loan" name="loantype">
                                  <option value="Select">Select</option>
                                  <option value="Yes">Yes</option>
                                  <option value="No">No</option>
                                  <option value="Not">Not Sure</option>
                                 </select>
                                   <span class="text-input">example: yes or no. </span>
                        </div>
                        </div>
                        <div class="form-row">
                         <div class="form-group">
                                 <label class="form-label">(2.) The land is either a one-to-four family residence or a condominium and does not have a separate structure, garage or apartment used as a second residence.</label>
                                  <select id="loan" name="loantype">
                                  <option value="Select">Select</option>
                                  <option value="Yes">Yes</option>
                                  <option value="No">No</option>
                                   <option value="Not">Not Sure</option>
                                 </select>
                                  <span class="text-input">example: yes or no. </span>
                        </div>
                        </div>
                        <div class="form-row">
                         <div class="form-group">
                                 <label class="form-label">(3.) There are no liens against the land and no judgments or tax liens against us, except those liens described in the Preliminary Report/Commitment issued by Pacific Coast Title Company.</label>
                                  <select id="loan" name="loantype">
                                  <option value="Select">Select</option>
                                  <option value="Yes">Yes</option>
                                  <option value="No">No</option>
                                   <option value="Not">Not Sure</option>
                                 </select>
                                   <span class="text-input">example: yes or no. </span>
                        </div>
                        </div>
                        <div class="form-row">
                         <div class="form-group">
                                 <label class="form-label">(4.) All taxes and assessments by a taxing authority are paid through the Kern Tax Collector and there have been no special tax assessments granted on the land or tax exemptions that were not lawful.</label>
                                  <select id="loan" name="loantype">
                                  <option value="Select">Select</option>
                                  <option value="Yes">Yes</option>
                                  <option value="No">No</option>
                                   <option value="Not">Not Sure</option>
                                 </select>
                                   <span class="text-input">example: yes or no. </span>
                        </div>
                        </div>
                        <div class="form-row">
                         <div class="form-group">
                                 <label class="form-label">(5.) If applicable, all assessments by the homeowner’s association for the subdivision/condominium are paid current and outstanding assessments are not yet payable.</label>
                                  <select id="loan" name="loantype">
                                  <option value="Select">Select</option>
                                  <option value="Yes">Yes</option>
                                  <option value="No">No</option>
                                   <option value="Not">Not Sure</option>
                                 </select>
                                  <span class="text-input">example: yes or no. </span>
                        </div>
                        </div>
                        <div class="form-row">
                         <div class="form-group">
                                 <label class="form-label">(6.) There have been no improvements added to the land or construction on the land within the last year.</label>
                                  <select id="loan" name="loantype">
                                  <option value="Select">Select</option>
                                  <option value="Yes">Yes</option>
                                  <option value="No">No</option>
                                   <option value="Not">Not Sure</option>
                                 </select>
                                  <span class="text-input">example: yes or no. </span>
                        </div>
                        </div>
                        <div class="form-row">
                         <div class="form-group">
                                 <label class="form-label">(7.) There are no pending repairs or improvements to the street(s) adjacent to the land.</label>
                                  <select id="loan" name="loantype">
                                  <option value="Select">Select</option>
                                  <option value="Yes">Yes</option>
                                  <option value="No">No</option>
                                   <option value="Not">Not Sure</option>
                                 </select>
                                   <span class="text-input">example: yes or no. </span>
                        </div>
                        </div>
                        <div class="form-row">
                         <div class="form-group">
                                 <label class="form-label">(8.) If applicable, a building permit from the proper government office authorized all improvements that we made to the land.</label>
                                  <select id="loan" name="loantype">
                                  <option value="Select">Select</option>
                                  <option value="Yes">Yes</option>
                                  <option value="No">No</option>
                                   <option value="Not">Not Sure</option>
                                 </select>
                                  <span class="text-input">example: yes or no. </span>
                        </div>
                        </div>
                        <div class="form-row">
                         <div class="form-group">
                                 <label class="form-label">(9.) We are not aware of and have not been told that the improvements on the land violate any building ordinances/regulations, zoning ordinances/regulations, restrictions or covenants.</label>
                                  <select id="loan" name="loantype">
                                  <option value="Select">Select</option>
                                  <option value="Yes">Yes</option>
                                  <option value="No">No</option>
                                   <option value="Not">Not Sure</option>
                                 </select>
                                   <span class="text-input">example: yes or no. </span>
                        </div>
                        </div>
                        <div class="form-row">
                         <div class="form-group">
                                 <label class="form-label">(10.) We are not aware of and have not been told that the improvements on the land described in Exhibit "A" encroach over any easement, property or building setback lines.</label>
                                  <select id="loan" name="loantype">
                                  <option value="Select">Select</option>
                                  <option value="Yes">Yes</option>
                                  <option value="No">No</option>
                                   <option value="Not">Not Sure</option>
                                 </select>
                                   <span class="text-input">example: yes or no. </span>
                        </div>
                        </div>
                        <div class="form-row">
                         <div class="form-group">
                                 <label class="form-label">(11.) We are not aware of and have not been told that the improvements by our neighbors encroach over our property or building setback lines.</label>
                                  <select id="loan" name="loantype">
                                  <option value="Select">Select</option>
                                  <option value="Yes">Yes</option>
                                  <option value="No">No</option>
                                   <option value="Not">Not Sure</option>
                                 </select>
                                   <span class="text-input">example: yes or no. </span>
                        </div>
                        </div>
                        <div class="form-row">
                         <div class="form-group">
                                 <label class="form-label">(12.) The land has actual pedestrian and vehicular access based on a legal right of access to the land.</label>
                                  <select id="loan" name="loantype">
                                  <option value="Select">Select</option>
                                  <option value="Yes">Yes</option>
                                  <option value="No">No</option>
                                   <option value="Not">Not Sure</option>
                                 </select>
                                   <span class="text-input">example: yes or no. </span>
                        </div>
                        </div>
                        <div class="form-row">
                         <div class="form-group">
                                 <label class="form-label">(13.) The affiants indemnify and hold Pacific Coast Title Company harmless from any loss, liability, costs, expenses, including attorneys’ fees, that Pacific Coast Title Company may suffer from errors on incorrect statements in these representations, actually known to the affiant(s), upon which Pacific Coast Title Company relies to issue the buyers an ALTA Homeowners Policy of Title Insurance for a one-to-four family residence.</label>
                                  <select id="loan" name="loantype">
                                  <option value="Select">Select</option>
                                  <option value="Yes">Yes</option>
                                  <option value="No">No</option>
                                   <option value="Not">Not Sure</option>
                                 </select>
                                   <span class="text-input">example: yes or no. </span>
                        </div>
                        </div>
                    </div>
                </fieldset>  -->
				</div>
			</form>
		</div>

	</div>

	<!-- JS -->
	<script src="<?php echo base_url();?>assets/buyer-seller-packets/vendor/jquery/jquery.min.js"></script>
	<script src="<?php echo base_url();?>assets/buyer-seller-packets/vendor/jquery/jquery-ui.min.js"></script>
	<script
		src="<?php echo base_url();?>assets/buyer-seller-packets/vendor/jquery-validation/dist/jquery.validate.min.js">
	</script>
	
	<script
		src="<?php echo base_url();?>assets/buyer-seller-packets/vendor/jquery-validation/dist/additional-methods.min.js">
	</script>
	<script src="<?php echo base_url();?>assets/buyer-seller-packets/vendor/jquery-steps/jquery.steps.min.js"></script>
	<script src="<?php echo base_url();?>assets/buyer-seller-packets/vendor/minimalist-picker/dobpicker.js"></script>
	<script src="<?php echo base_url();?>assets/buyer-seller-packets/vendor/nouislider/nouislider.min.js"></script>
	<script src="<?php echo base_url();?>assets/buyer-seller-packets/vendor/input-mask/jquery.mask.min.js"></script>
	<script src="<?php echo base_url();?>assets/buyer-seller-packets/vendor/wnumb/wNumb.js"></script>
	<script src="<?php echo base_url();?>assets/buyer-seller-packets/js/main.js?seller_v=<?=time()?>"></script>
	<script src="<?php echo base_url();?>assets/js/custom.js?seller_v=<?=time()?>"></script>

</body>

</html>
