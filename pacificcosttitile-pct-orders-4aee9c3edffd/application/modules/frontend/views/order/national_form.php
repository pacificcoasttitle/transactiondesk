<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="author" content="colorlib.com">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title><?=$title;?></title>

	<!-- Font Icon -->
	<link rel="stylesheet"
		href="<?=base_url('assets/buyer-seller-packets/fonts/material-icon/css/material-design-iconic-font.min.css');?>">
	<!-- <link rel="stylesheet" href="<?=base_url('assets/buyer-seller-packets/vendor/nouislider/nouislider.min.css');?>"> -->

	<!-- Main css -->
	<link rel="stylesheet" href="<?=base_url('assets/buyer-seller-packets/css/style.css?buyer_v=' . time());?>">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/frontend/css/jquery-ui.css">
</head>

<style>
	.d-none {
		display: none;
	}

	.preloaderjs .spinner {display: none !important;}.preloaderjs#page-preloader {background: rgba(46, 46, 46, 0.99) !important;}#page-preloader {position: fixed;top: 0;right: 0;bottom: 0;left: 0;width: 100%;height: 100%;background: #2e2e2e;z-index: 100500;}#page-preloader .spinner {position: absolute;top: 50%;left: 50%;display: block;width: 100px;height: 100px;margin-top: -50px;margin-left: -50px;border: 3px solid transparent;border-top-color: #e7e4d7;border-radius: 50%;z-index: 1001;-webkit-animation: spin 2.5s infinite linear;animation: spin 2.5s infinite linear;}#page-preloader .spinner:before, #page-preloader .spinner:after {position: absolute;border-radius: 50%;content: '';}#page-preloader .spinner:before {top: 5px;right: 5px;bottom: 5px;left: 5px;border: 3px solid transparent;border-top-color: #71383e;-webkit-animation: spin 2s infinite linear;animation: spin 2s infinite linear;}#page-preloader .spinner:after {top: 15px;right: 15px;bottom: 15px;left: 15px;border: 3px solid transparent;border-top-color: #efa96b;-webkit-animation: spin 1s infinite linear;animation: spin 1s infinite linear;}@keyframes spin {0% {-webkit-transform: rotate(0);transform: rotate(0);}100% {-webkit-transform: rotate(360deg);transform: rotate(360deg);}}

    input {
        font-weight: normal;
    }

    .submit-btn {
        width: 140px;
        height: 50px;
        color: #fff;
        background: #f96414;
        align-items: center;
        -moz-align-items: center;
        -webkit-align-items: center;
        -o-align-items: center;
        -ms-align-items: center;
        justify-content: center;
        -moz-justify-content: center;
        -webkit-justify-content: center;
        -o-justify-content: center;
        -ms-justify-content: center;
        text-decoration: none;
    }

    .error {
        color: #f63726;
    }
    .form-row select {
        color: #797575
    }

</style>

<body>

	<div class="main">
		<div id="page-preloader" style="background-color: rgba(0, 0, 0, 0.5);display: none;"><span class="spinner border-t_second_b border-t_prim_a"></span></div>
		<div>
			<div class="container2">
				<img src="<?php echo base_url(); ?>assets/buyer-seller-packets/images/logo.png" style="width:300px;">
				<h1>NATIONAL TITLE ORDER FORM </h1>
				<h3><?php echo $orderDetails['full_address']; ?></h3>
				<h4 >How to order: complete the form & Email: national@pct.com </h4>
				<h4 style="margin-bottom:40px;">For assistance call 877.536.3390 </h4>

			</div>
		</div>


		<div class="container">
			<?php if (!empty($success)) {?>
			<div id="agent_success_msg" class="w-100 alert alert-success alert-dismissible">
				<?php echo $success; ?>

			</div>
			<?php }if (!empty($errors)) {?>
			<div id="agent_error_msg" class="w-100 alert alert-danger alert-dismissible">
				<?php echo $errors; ?>
			</div>
			<?php }?>

			<form method="POST" id="national-form" class="national-form">
				<div>
					<fieldset>
						<h2>NATIONAL TITLE ORDER FORM</h2>
						<p class="desc">Please enter your infomation </p>
						<div class="fieldset-content">

							<div class="form-row">
								<div class="form-flex">
									<div class="form-group">
										<label for="buyer_name" class="form-label">Buyer/borrower name(s)</label>
										<input type="text" name="buyer_name" required="required" value="" placeholder="Buyer/borrower name(s)"/>
                                        <?php if (!empty($buyer_name_error_msg)) {?>
                                            <span class="error"><?php echo $buyer_name_error_msg; ?></span>
                                        <?php }?>
									</div>
									<div class="form-group">
										<label for="buyer_current_address" class="form-label">Buyer/borrower current address</label>
										<input type="text" name="buyer_current_address" required="required" value="" placeholder="Buyer/borrower current address" />
                                        <?php if (!empty($buyer_current_address_error_msg)) {?>
                                            <span class="error"><?php echo $buyer_current_address_error_msg; ?></span>
                                        <?php }?>
									</div>
								</div>
							</div>
							<div class="form-row">
								<div class="form-flex">
									<div class="form-group">
										<label for="buyer_email" class="form-label">Borrower Email</label>
										<input type="email" name="buyer_email" required="required" value="" placeholder="Borrower Email" />
                                        <?php if (!empty($buyer_email_error_msg)) {?>
                                            <span class="error"><?php echo $buyer_email_error_msg; ?></span>
                                        <?php }?>
									</div>
									<div class="form-group">
										<label for="buyer_mobile" class="form-label">Borrower Mobile Phone #</label>
										<input class="" type="text" name="buyer_mobile" required="required" placeholder="Borrower Mobile Phone #" />
                                        <?php if (!empty($buyer_mobile_error_msg)) {?>
                                            <span class="error"><?php echo $buyer_mobile_error_msg; ?></span>
                                        <?php }?>
									</div>
								</div>
							</div>

							<div class="form-row">
								<div class="form-flex">
                                    <div class="form-group">
										<label for="buyer_property_address"  class="form-label">Subject property address</label>
										<input type="text" name="buyer_property_address" required="required" value="" placeholder="Subject property address"/>
                                        <?php if (!empty($buyer_property_address_error_msg)) {?>
                                            <span class="error"><?php echo $buyer_property_address_error_msg; ?></span>
                                        <?php }?>
									</div>

									<div class="form-group">
										<label for="title_hold_reason" class="form-label">How will buyer/borrower(s) hold Title?</label>
										<input type="text" name="title_hold_reason" required="required" value="" placeholder="How will buyer/borrower(s) hold Title?" />
                                        <?php if (!empty($title_hold_reason_error_msg)) {?>
                                            <span class="error"><?php echo $title_hold_reason_error_msg; ?></span>
                                        <?php }?>
									</div>

								</div>
							</div>

                            <div class="form-row">
								<div class="form-flex">
                                    <div class="form-group">
										<label for="ssn" class="form-label">Last 4 digits of buyer/borrower(s) SSN</label>
										<input type="text" name="ssn" required="required" value="" placeholder="Last 4 digits of buyer/borrower(s) SSN" />
                                        <?php if (!empty($ssn_error_msg)) {?>
                                            <span class="error"><?php echo $ssn_error_msg; ?></span>
                                        <?php }?>
									</div>

									<div class="form-group">
										<label for="estimated_closing_date" class="form-label">Estimated closing date</label>
										<input type="date" name="estimated_closing_date" required="required" value="" placeholder="Estimated closing date" />
                                        <?php if (!empty($estimated_closing_date_error_msg)) {?>
                                            <span class="error"><?php echo $estimated_closing_date_error_msg; ?></span>
                                        <?php }?>
									</div>

								</div>
							</div>

                            <div class="form-row">
								<div class="form-flex">
                                    <div class="form-group">
										<label for="lender" class="form-label">Lender</label>
										<input type="text" name="lender" required="required"  value="" placeholder="Lender"/>
                                        <?php if (!empty($lender_error_msg)) {?>
                                            <span class="error"><?php echo $lender_error_msg; ?></span>
                                        <?php }?>
									</div>
                                    <div class="form-group"></div>
								</div>
							</div>

                            <div class="form-row">
								<div class="form-flex">
                                    <div class="form-group">
										<label for="marital_status" class="form-label">Marital Status</label>
                                        <select name="marital_status" id="marital_status">
                                            <option value="">Select Marrital Status</option>
                                            <option value="married">Married</option>
                                            <option value="unmarried">Unmarried</option>
                                        </select>
										<!-- <input type="number" name="marital_status" required="required" value="" placeholder="Loan amount" /> -->
                                        <?php if (!empty($marital_status_error_msg)) {?>
                                            <span class="error"><?php echo $marital_status_error_msg; ?></span>
                                        <?php }?>
									</div>

                                    <div class="form-group">
										<label for="sales_rep" class="form-label">Sales Rep</label>
										<select name="sales_rep" id="sales_rep">
                                        <option value="">Select Sales rep</option>
                                            <?php
if (isset($salesRep) && !empty($salesRep)) {
    foreach ($salesRep as $k => $v) {
        $name = array($v['first_name'], $v['last_name']);
        $full_name = implode(' ', $name);
        ?>
        <option value="<?php echo $v['id']; ?>"><?php echo $full_name; ?></option>
<?php
}
}
?>
                                        </select>
                                        <?php if (!empty($sales_rep_error_msg)) {?>
                                            <span class="error"><?php echo $sales_rep_error_msg; ?></span>
                                        <?php }?>
									</div>
								</div>
							</div>

                            <div class="form-row">
								<div class="form-flex">
                                    <div class="form-group">
										<label for="loan_amount" class="form-label">Loan amount</label>
										<input type="number" name="loan_amount" required="required" value="" placeholder="Loan amount" />
                                        <?php if (!empty($loan_amount_error_msg)) {?>
                                            <span class="error"><?php echo $loan_amount_error_msg; ?></span>
                                        <?php }?>
									</div>

                                    <div class="form-group">
										<label for="loan_number" class="form-label">Loan number</label>
										<input type="text" name="loan_number" required="required" value="" placeholder="Loan number"/>
                                        <?php if (!empty($loan_number_error_msg)) {?>
                                            <span class="error"><?php echo $loan_number_error_msg; ?></span>
                                        <?php }?>
									</div>
								</div>
							</div>

                            <div class="form-row">
                                <div class="form-flex">
                                    <div class="form-group">
                                        <label for="type_of_loan" class="form-label">Type of loan</label>
                                        <input type="text" name="type_of_loan" required="required" value="" placeholder="Type of loan" />
                                        <?php if (!empty($type_of_loan_error_msg)) {?>
                                            <span class="error"><?php echo $type_of_loan_error_msg; ?></span>
                                        <?php }?>
                                    </div>
                                    <div class="form-group">
										<label for="loan_officer" class="form-label">Loan officer</label>
										<input type="text" name="loan_officer" required="required" value="" placeholder="Loan officer"/>
                                        <?php if (!empty($loan_officer_error_msg)) {?>
                                            <span class="error"><?php echo $loan_officer_error_msg; ?></span>
                                        <?php }?>
									</div>
								</div>
							</div>
                            <div class="form-row">
								<div class="form-flex">
                                    <div class="form-group">
										<label for="title_items_required_by" class="form-label">Title items required by Lender</label>
										<input type="text" name="title_items_required_by" required="required" value="" placeholder="Title items required by Lender"/>
                                        <?php if (!empty($title_items_required_by_error_msg)) {?>
                                            <span class="error"><?php echo $title_items_required_by_error_msg; ?></span>
                                        <?php }?>
									</div>

									<div class="form-group">
										<label for="lender_clause" class="form-label">Lender/Mortgagee clause</label>
										<input type="text" name="lender_clause" required="required" value="" placeholder="Lender/Mortgagee clause" />
                                        <?php if (!empty($lender_clause_error_msg)) {?>
                                            <span class="error"><?php echo $lender_clause_error_msg; ?></span>
                                        <?php }?>
									</div>
								</div>
							</div>

                            <div class="form-row">
								<div class="form-flex">
                                    <div class="form-group">
										<label for="return_document_to" class="form-label">Return documents to</label>
										<input type="text" name="return_document_to" required="required" value="" placeholder="Return documents to"/>
                                        <?php if (!empty($return_document_to_error_msg)) {?>
                                            <span class="error"><?php echo $return_document_to_error_msg; ?></span>
                                        <?php }?>
									</div>

									<div class="form-group">
										<label for="main_lender_contact" class="form-label">Main Lender contact</label>
										<input type="text" name="main_lender_contact" required="required" value="" placeholder="Main Lender contact" />
                                        <?php if (!empty($main_lender_contact_error_msg)) {?>
                                            <span class="error"><?php echo $main_lender_contact_error_msg; ?></span>
                                        <?php }?>
									</div>
								</div>
							</div>

						</div>
					</fieldset>

                    <div class="actions clearfix">
                        <ul role="menu" aria-label="Pagination">
                            <li class="" aria-disabled="true">
                                <button type="submit" class="btn btn-success submit-btn"  >Save</button>
                            </li>
                        </ul>
                    </div>
				</div>
			</form>
		</div>
	</div>

	<!-- JS -->
	<script src="<?=base_url('assets/buyer-seller-packets/vendor/jquery/jquery.min.js');?>"></script>
	<script src="<?php echo base_url(); ?>assets/buyer-seller-packets/vendor/jquery/jquery-ui.min.js"></script>
	<script src="<?=base_url('assets/buyer-seller-packets/vendor/jquery-validation/dist/jquery.validate.min.js');?>"></script>
	<script src="<?=base_url('assets/buyer-seller-packets/vendor/jquery-validation/dist/additional-methods.min.js');?>"></script>
	<!-- <script src="<?=base_url('assets/buyer-seller-packets/vendor/jquery-steps/jquery.steps.min.js');?>"></script> -->
	<!-- <script src="<?=base_url('assets/buyer-seller-packets/vendor/minimalist-picker/dobpicker.js');?>"></script> -->
	<!-- <script src="<?=base_url('assets/buyer-seller-packets/vendor/nouislider/nouislider.min.js');?>"></script> -->
	<!-- <script src="<?=base_url('assets/buyer-seller-packets/vendor/wnumb/wNumb.js');?>"></script> -->
	<script src="<?=base_url('assets/buyer-seller-packets/vendor/input-mask/jquery.mask.min.js');?>"></script>
	<!-- <script src="<?=base_url('assets/buyer-seller-packets/js/buyer-main.js?buyer_v=' . time());?>"></script> -->
	<!-- <script src="<?php echo base_url(); ?>assets/js/custom.js?seller_v=<?=time()?>"></script> -->

    <script>
        // Wait for the DOM to be ready
        $(function() {
            $("form.national-form").validate({
                rules: {
                    buyer_name: "required",
                    buyer_current_address: "required",
                    sales_rep: "required",
                    marital_status: "required",
                    buyer_email: {
                        required: true,
                        email: true
                    },
                    ssn: {
                        required: true,
                        maxlength: 4
                    }
                },
                // Specify validation error messages
                messages: {
                    buyer_name: "Please enter buyer/borrower name",
                    buyer_current_address: "Please enter buyer/borrower current address",
                    buyer_email: "Please enter a valid email address",
                    buyer_mobile: "Please enter Borrower mobile phone",
                    buyer_property_address: "Please enter subject property address",
                    title_hold_reason: "Please enter hold title",
                    ssn: {
                        required: "Please enter SSN",
                        maxlength: "Maximum length of the input is 4"
                    },
                    estimated_closing_date: "Please enter estimated closing date",
                    lender: "Please enter lender",
                    loan_amount: "Please enter loan amount",
                    loan_number: "Please enter loan number",
                    type_of_loan: "Please enter type of loan",
                    title_items_required_by: "Please enter title items required by Lender",
                    lender_clause: "Please enter lender/mortgagee clause",
                    return_document_to: "Please enter return documents to",
                    main_lender_contact: "Please enter main lender contact",
                    loan_officer: "Please enter Loan officer",
                    marital_status: "Please Select Marital Status",
                    sales_rep: "Please Select Sales Rep",
                },
                submitHandler: function(form) {
                    form.submit();
                }
            });
        });
    </script>
</body>

</html>
