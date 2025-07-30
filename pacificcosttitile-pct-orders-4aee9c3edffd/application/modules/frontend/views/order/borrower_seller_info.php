<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo $title;?></title>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link
		href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,200&display=swap"
		rel="stylesheet">
	<link rel="stylesheet" href="<?php echo base_url();?>assets/frontend/css/buyer-seller-package/bootstrap.min.css?v=01">
	<link rel="stylesheet" href="<?php echo base_url();?>assets/frontend/css/buyer-seller-package/style.css?v=01">
</head>

<style>
	.error2 {
		margin-top: 10px;
	}
	.form-control {
        width: 100%;
    }
    .table>:not(caption)>*>* {
        padding: 1rem;
    }
    
</style>

<body class="">


	<!-- header -->

	<header>
		<div class="container">
			<div class="row align-items-center">
				<div class="col-6">
					<a href="#">
						<img src="<?php echo base_url();?>assets/frontend/images/buyer-seller-package/alanna-logo.png"
							alt="" class="img-fluid img_logo">
					</a>
				</div>
			</div>
		</div>
	</header>

	<!-- form content -->

	<section class="form_content">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
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
                    <form action="<?php echo base_url().'seller-info/'.$orderDetails['file_id']; ?>" onsubmit="if (validateForm()) { document.forms['borrower_seller_form'].submit(); }" method="post" name="borrower_seller_form" id="borrower_seller_form">
                        <h2 class="blue_title">Seller Opening Package<br><span style="font-size:16px; padding-top:15px;">Property Address: <?php echo $orderDetails['full_address'];?></span><br><span style="font-size:16px; padding-top:15px;">APN:<?php echo $orderDetails['apn'];?></span></h2>
                        <div class="accordion" id="accordionExample">
                            
							
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingEighteen">
                                    <button style="border-bottom: 1px solid rgba(0,0,0,.125)" class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEighteen" aria-expanded="true" aria-controls="collapseEighteen">
                                      OWNER'S ESCROW INFORMATION SHEET
                                    </button>
                                </h2>
                                <input type="hidden" name="order_id" id="order_id" value="<?php echo $orderDetails['order_id'];?>">
                                <div id="collapseEighteen" class="accordion-collapse collapse show" aria-labelledby="headingEighteen" data-bs-parent="#accordionExample" style="">
                                    <div class="accordion-body">
                                        <h3 class="text-center mt-md-5 mby-5">
                                            <b>
                                                OWNER'S ESCROW INFORMATION SHEET <br>
                                                BORROWER’S AUTHORIZATION
                                            </b>
                                        </h3>

                                        <div class="notice_box mt-4">
                                            NOTE:  Please accept this as authorization for Pacific Coast Title Company associates to obtain payoff demand statements on any below-referenced loans on our behalf.
                                        </div>

                                        <div class="date_escrow_num max-w-100 text_black mt-4">
                                            <span><strong>ESCROW NO.:</strong></span>10257432-GLE-MP<br>
                                            <span><strong>TITLE NO.:</strong></span>10257432-GLT-<br>
                                        </div>

                                        <div class="text-center mt-4">
                                            PLEASE FILL OUT THIS FORM COMPLETELY AND RETURN TO OUR OFFICE AS SOON AS POSSIBLE <br>
                                            AS IT WILL ASSIST US IN THE ADMINISTRATION OF YOUR TRANSACTION.
                                        </div>

                                        <div class="form-group position-relative mb-3 mt-3">
                                            <label for="" class="mb-2"><b></b></label>
                                            <input type="text" class="form-control" id="seller_name" name="seller_name" required="required" data-error="#seller_name-error">
                                            <small class="small_label">Seller(s):</small>
                                        </div>
                                        <label id="seller_name-error" class="error text-danger" for="seller_name"></label>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3 mt-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" class="form-control" id="escrow_home_phone_number" name="escrow_home_phone_number" required="required" data-error="#escrow_home_phone_number-error">
                                                    <small class="small_label">Home Phone Number:</small>
                                                </div>
                                                <label id="escrow_home_phone_number-error" class="error text-danger" for="escrow_home_phone_number"></label>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3 mt-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" class="form-control" id="work_phone_number" name="work_phone_number" required="required" data-error="#work_phone_number-error">
                                                    <small class="small_label">Work Phone Number:</small>
                                                </div>
                                                <label id="work_phone_number-error" class="error text-danger" for="work_phone_number"></label>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3 mt-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" class="form-control" id="fax_number" name="fax_number" required="required" data-error="#fax_number-error">
                                                    <small class="small_label">Fax Number:</small>
                                                    
                                                </div>
                                                <label id="fax_number-error" class="error text-danger" for="fax_number"></label>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3 mt-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" class="form-control" id="cell_phone_number" name="cell_phone_number" required="required" data-error="#cell_phone_number-error">
                                                    <small class="small_label">Cell Phone Number:</small>
                                                    
                                                </div>
                                                <label id="cell_phone_number-error" class="error text-danger" for="cell_phone_number"></label>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3 mt-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" class="form-control" id="email_address" name="email_address" required="required" data-error="#email_address-error">
                                                    <small class="small_label">E-Mail Address:</small>
                                                    
                                                </div>
                                                <label id="email_address-error" class="error text-danger" for="email_address"></label>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3 mt-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" class="form-control" id="cell_phone_number_2" name="cell_phone_number_2" required="required" data-error="#cell_phone_number_2-error">
                                                    <small class="small_label">Cell Phone Number:</small>
                                                    
                                                </div>
                                                <label id="cell_phone_number_2-error" class="error text-danger" for="cell_phone_number_2"></label>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3 mt-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" class="form-control" id="escrow_ssn" name="escrow_ssn" required="required" data-error="#escrow_ssn-error">
                                                    <small class="small_label">Social Security #:</small>
                                                   
                                                </div>
                                                <label id="escrow_ssn-error" class="error text-danger" for="escrow_ssn"></label>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3 mt-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" class="form-control" id="ssn_2" name="ssn_2" required="required" data-error="#ssn_2-error">
                                                    <small class="small_label">Social Security #:</small>
                                                    
                                                </div>
                                                <label id="ssn_2-error" class="error text-danger" for="ssn_2"></label>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group position-relative mb-3 mt-3">
                                                <label for="" class="mb-2"><b></b></label>
                                                <textarea rows="2" class="form-control" style="height:auto;" id="property_address" name="property_address" required="required" data-error="#property_address-error"></textarea>
                                                <small class="small_label">Property Address:</small>
                                                
                                            </div>
                                            <label id="property_address-error" class="error text-danger" for="property_address"></label>
                                        </div>

                                        <div class="row">
                                            <div class="form-group position-relative mb-3 mt-3">
                                                <label for="" class="mb-2"><b></b></label>
                                                <textarea rows="2" class="form-control" style="height:auto;" id="seller_current_mailing_address" name="seller_current_mailing_address" required="required" data-error="#seller_current_mailing_address-error"></textarea>
                                                <small class="small_label">Seller(s) Current Mailing Address: </small>
                                                
                                            </div>
                                            <label id="seller_current_mailing_address-error" class="error text-danger" for="seller_current_mailing_address"></label>
                                        </div>

                                        <div class="row">
                                            <div class="form-group position-relative mb-3 mt-3">
                                                <label for="" class="mb-2"><b></b></label>
                                                <textarea rows="2" class="form-control" style="height:auto;" id="seller_mailing_address_after_close_escrow" name="seller_mailing_address_after_close_escrow" required="required" data-error="#seller_mailing_address_after_close_escrow-error"></textarea>
                                                <small class="small_label">Seller(s) Mailing Address after Close of Escrow: </small>
                                                
                                            </div>
                                            <label id="seller_mailing_address_after_close_escrow-error" class="error text-danger" for="seller_mailing_address_after_close_escrow"></label>
                                        </div>

                                        <div class="row">
                                            <div class="form-group position-relative mb-3 mt-3">
                                                <label for="" class="mb-2"><b></b></label>
                                                <textarea rows="2" class="form-control" style="height:auto;" id="seller_mailing_address_after_close_escrow_2" name="seller_mailing_address_after_close_escrow_2" required="required" data-error="#seller_mailing_address_after_close_escrow_2-error"></textarea>
                                                <small class="small_label">Seller(s) Mailing Address after Close of Escrow: </small>
                                                
                                            </div>
                                            <label id="seller_mailing_address_after_close_escrow_2-error" class="error text-danger" for="seller_mailing_address_after_close_escrow_2"></label>
                                        </div>

                                        <div class="mt-4 mb-2"><b>Existing Loan(s) That Are Currently Recorded Against The Property:</b></div>

                                        <b>
                                            NOTE:  If you have an FHA Loan that is to be paid off at the close of escrow, the lender requires a 30-day notice of your intention to prepay.  It is the owner’s responsibility to notify the lender.
                                        </b>

                                        <div class="form-group position-relative mb-3 mt-3">
                                            <label for="" class="mb-2"><b></b></label>
                                            <input type="text" class="form-control" id="first_trust_deed_lender" name="first_trust_deed_lender" required="required" data-error="#first_trust_deed_lender-error">
                                            <small class="small_label">FIRST TRUST DEED LENDER:</small>
                                        </div>
                                        <label id="first_trust_deed_lender-error" class="error text-danger" for="first_trust_deed_lender"></label>

                                        <div class="row">
                                            <div class="form-group position-relative mb-3 mt-3">
                                                <label for="" class="mb-2"><b></b></label>
                                                <textarea rows="2" class="form-control" style="height:auto;" id="lender_address" name="lender_address" required="required" data-error="#lender_address-error"></textarea>
                                                <small class="small_label">Address:</small>
                                                
                                            </div>
                                        </div>
                                        <label id="lender_address-error" class="error text-danger" for="lender_address"></label>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3 mt-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" class="form-control" id="loan_number" name="loan_number" required="required" data-error="#loan_number-error">
                                                    <small class="small_label">Loan Number:</small>
                                                    
                                                </div>
                                                <label id="loan_number-error" class="error text-danger" for="loan_number"></label>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3 mt-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" class="form-control" id="lender_phone_number" name="lender_phone_number" required="required" data-error="#lender_phone_number-error">
                                                    <small class="small_label">Phone Number:</small>
                                                </div>
                                                <label id="lender_phone_number-error" class="error text-danger" for="lender_phone_number"></label>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3 mt-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" class="form-control" id="unpaid_principal_balance" name="unpaid_principal_balance" required="required" data-error="#unpaid_principal_balance-error">
                                                    <small class="small_label">Unpaid Principal Balance $:</small>
                                                </div>
                                                <label id="unpaid_principal_balance-error" class="error text-danger" for="unpaid_principal_balance"></label>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3 mt-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" class="form-control" id="next_due" name="next_due" required="required" data-error="#next_due-error">
                                                    <small class="small_label">Next Due:</small>
                                                </div>
                                                <label id="next_due-error" class="error text-danger" for="next_due"></label>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="row mt-4">
                                                <div class="col-lg-9 col-md-8">
                                                    <div class="mb-3">
                                                        Type of Loan: <input type="text" class="input_single w-medium" id="type_of_loan" name="type_of_loan"> VA <input type="text" class="input_single w-medium" id="va" name="va">FHA <input type="text" class="input_single w-medium" id="fha" name="fha">Conventional <input type="text" class="input_single w-medium" id="conventional" name="conventional">Equity Line/Line of Credit
                                                    </div>
                                                    <div>
                                                        TAXES: <input type="text" class="input_single w-medium" id="taxes" name="taxes">Paid <input type="text" class="input_single w-medium" id="paid" name="paid"> Unpaid<input type="text" class="input_single w-medium" id="unpaid" name="unpaid"> Taxes are being paid through my impound account
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 col-md-4">
                                                    <div class="d-flex">
                                                        Impound Acct:
                                                        <div class="ms-2">
                                                            <input type="radio" id="impondYes" value="yes" name="is_impound_acc">
                                                            <label for="impondYes">Yes</label>
                                                        </div>
                                                        <div class="ms-2">
                                                            <input type="radio" id="impondNo" value="no" name="is_impound_acc">
                                                            <label for="impondNo">No</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <hr class="mt-4">

                                        <div class="row">
                                            <div class="form-group position-relative mb-3 mt-3">
                                                <label for="" class="mb-2"><b></b></label>
                                                <input type="text" class="form-control" id="second_trust_deed_lender" name="second_trust_deed_lender" required="required" data-error="#second_trust_deed_lender-error">
                                                <small class="small_label">SECOND TRUST DEED LENDER: </small>
                                            </div>
                                            <label id="second_trust_deed_lender-error" class="error text-danger" for="second_trust_deed_lender"></label>
                                        </div>

                                        <div class="row">
                                            <div class="form-group position-relative mb-3 mt-3">
                                                <label for="" class="mb-2"><b></b></label>
                                                <textarea rows="2" class="form-control" style="height:auto;" id="second_lender_address" name="second_lender_address" required="required" data-error="#second_lender_address-error"></textarea>
                                                <small class="small_label">Address:</small>
                                                
                                            </div>
                                            <label id="second_lender_address-error" class="error text-danger" for="second_lender_address"></label>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3 mt-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" class="form-control" id="second_loan_number" name="second_loan_number" required="required" data-error="#second_loan_number-error">
                                                    <small class="small_label">Loan Number:</small>
                                                    
                                                </div>
                                                <label id="second_loan_number-error" class="error text-danger" for="second_loan_number"></label>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3 mt-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" class="form-control" id="second_lender_phone_number" name="second_lender_phone_number" required="required" data-error="#second_lender_phone_number-error">
                                                    <small class="small_label">Phone Number:</small>
                                                    
                                                </div>
                                                <label id="second_lender_phone_number-error" class="error text-danger" for="second_lender_phone_number"></label>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group position-relative mb-3 mt-3 col-md-6">
                                                <label for="" class="mb-2"><b></b></label>
                                                <input type="text" class="form-control" id="second_unpaid_principal_balance" name="second_unpaid_principal_balance" required="required" data-error="#second_unpaid_principal_balance-error">
                                                <small class="small_label">Unpaid Principal Balance $:</small>
                                            </div>
                                            <label id="second_unpaid_principal_balance-error" class="error text-danger" for="second_unpaid_principal_balance"></label>
                                        </div>
                                        
                                        <div class="row mt-4">
                                            <div class="col-lg-12">
                                                <div class="mb-3">
                                                    Type of Loan: <input type="text" class="input_single w-medium" id="second_type_of_loan" name="second_type_of_loan"> VA <input type="text" class="input_single w-medium" id="second_va" name="second_va">FHA <input type="text" class="input_single w-medium" id="second_fha" name="second_fha">Conventional <input type="text" class="input_single w-medium" id="second_conventional" name="second_conventional">Equity Line/Line of Credit
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group position-relative mb-3 mt-3">
                                                <label for="" class="mb-2"><b></b></label>
                                                <input type="text" class="form-control" id="homeowner_association" name="homeowner_association" required="required" data-error="#homeowner_association-error">
                                                <small class="small_label">Homeowner’s Association: </small>
                                                
                                            </div>
                                            <label id="homeowner_association-error" class="error text-danger" for="homeowner_association"></label>
                                        </div>

                                        <div class="row">
                                            <div class="form-group position-relative mb-3 mt-3">
                                                <label for="" class="mb-2"><b></b></label>
                                                <input type="text" class="form-control" id="management_company" name="management_company" required="required" data-error="#management_company-error">
                                                <small class="small_label">Management Company: </small>
                                                
                                            </div>
                                            <label id="management_company-error" class="error text-danger" for="management_company"></label>
                                        </div>

                                        <div class="row">
                                            <div class="form-group position-relative mb-3 mt-3">
                                                <label for="" class="mb-"><b></b></label>
                                                <input type="text" class="form-control" id="management_mailing_address" name="management_mailing_address" required="required" data-error="#management_mailing_address-error">
                                                <small class="small_label">Mailing Address: </small>
                                                
                                            </div>
                                            <label id="management_mailing_address-error" class="error text-danger" for="management_mailing_address"></label>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3 mt-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" class="form-control" id="contact_person" name="contact_person" required="required" data-error="#contact_person-error">
                                                    <small class="small_label">Contact Person:</small>
                                                    
                                                </div>
                                                <label id="contact_person-error" class="error text-danger" for="contact_person"></label>
                                            </div> 
                                            <div class="col-md-6">         
                                                <div class="form-group position-relative mb-3 mt-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" class="form-control" id="management_phone_number" name="management_phone_number" required="required" data-error="#management_phone_number-error">
                                                    <small class="small_label">Phone Number:</small>
                                                    
                                                </div>
                                                <label id="management_phone_number-error" class="error text-danger" for="management_phone_number"></label>
                                            </div>
                                        </div>

                                        <hr>	

                                        <div class="row">
                                            <div class="form-group position-relative mb-3 mt-3">
                                                <label for="" class="mb-2"><b></b></label>
                                                <input type="text" class="form-control" id="second_homeowner_association" name="second_homeowner_association" required="required" data-error="#second_homeowner_association-error">
                                                <small class="small_label">Homeowner’s Association: </small>
                                                
                                            </div>
                                            <label id="second_homeowner_association-error" class="error text-danger" for="second_homeowner_association"></label>
                                        </div>

                                        <div class="row">
                                            <div class="form-group position-relative mb-3 mt-3">
                                                <label for="" class="mb-2"><b></b></label>
                                                <input type="text" class="form-control" id="second_management_company" name="second_management_company" required="required" data-error="#second_management_company-error">
                                                <small class="small_label">Management Company: </small>
                                                
                                            </div>
                                            <label id="second_management_company-error" class="error text-danger" for="second_management_company"></label>
                                        </div>

                                        <div class="row">
                                            <div class="form-group position-relative mb-3 mt-3">
                                                <label for="" class="mb-2"><b></b></label>
                                                <input type="text" class="form-control" id="second_management_mailing_address" name="second_management_mailing_address" required="required" data-error="#second_management_mailing_address-error">
                                                <small class="small_label">Mailing Address: </small>
                                                
                                            </div>
                                            <label id="second_management_mailing_address-error" class="error text-danger" for="second_management_mailing_address"></label>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3 mt-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" class="form-control" id="second_contact_person" name="second_contact_person" required="required" data-error="#second_contact_person-error">
                                                    <small class="small_label">Contact Person:</small>
                                                    
                                                </div>
                                                <label id="second_contact_person-error" class="error text-danger" for="second_contact_person"></label>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3 mt-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" class="form-control" id="second_management_phone_number" name="second_management_phone_number" required="required" data-error="#second_management_phone_number-error">
                                                    <small class="small_label">Phone Number:</small>
                                                    
                                                </div>
                                                <label id="second_management_phone_number-error" class="error text-danger" for="second_management_phone_number"></label>
                                            </div>
                                        </div>

                                        <hr>	

                                        <div class="mt-3">
                                            Water Stock: If so, please attach certificate for transfer
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3 mt-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" class="form-control" id="water_company_name" name="water_company_name" required="required" data-error="#water_company_name-error">
                                                    <small class="small_label">Name of Company: </small>
                                                    
                                                </div>
                                                <label id="water_company_name-error" class="error text-danger" for="water_company_name"></label>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3 mt-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" class="form-control" id="water_contract_name" name="water_contract_name" required="required" data-error="#water_contract_name-error">
                                                    <small class="small_label">Name of Contact: </small>
                                                    
                                                </div>
                                                <label id="water_contract_name-error" class="error text-danger" for="water_contract_name"></label>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3 mt-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" class="form-control" id="water_company_address" name="water_company_address" required="required" data-error="#water_company_address-error">
                                                    <small class="small_label">Address: </small>
                                                    
                                                </div>
                                                <label id="water_company_address-error" class="error text-danger" for="water_company_address"></label>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3 mt-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" class="form-control" id="water_company_phone" name="water_company_phone" required="required" data-error="#water_company_phone-error">
                                                    <small class="small_label">Phone: </small>
                                                    
                                                </div>
                                                <label id="water_company_phone-error" class="error text-danger" for="water_company_phone"></label>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group position-relative mb-3 mt-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" class="form-control" id="amount_of_assessment" name="amount_of_assessment" required="required" data-error="#amount_of_assessment-error">
                                                    <small class="small_label">Amount of assessment $ </small>
                                                    
                                                </div>
                                                <label id="amount_of_assessment-error" class="error text-danger" for="amount_of_assessment"></label>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group position-relative mb-3 mt-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" class="form-control" id="water_next_due" name="water_next_due" required="required" data-error="#water_next_due-error">
                                                    <small class="small_label">Next Due </small>
                                                    
                                                </div>
                                                <label id="water_next_due-error" class="error text-danger" for="water_next_due"></label>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group position-relative mb-3 mt-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" class="form-control" id="no_of_shares" name="no_of_shares" required="required" data-error="#no_of_shares-error">
                                                    <small class="small_label">No. of Shares </small>
                                                    
                                                </div>
                                                <label id="no_of_shares-error" class="error text-danger" for="no_of_shares"></label>
                                            </div>
                                        </div>

                                        <div class="mt-4">
                                            Please place any additional information that you feel we may require on the reverse side of this form.
                                        </div>

                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="mt-5">
                                                    Date :    
                                                    <input type="text" class="input_single" id="date" name="date" required="required" data-error="#date-error">
                                                </div>
                                                <label id="date-error" class="error text-danger" for="date"></label>
                                            </div>
                                            
                                            <div class="col-md-4">
                                                <div class="mt-5">
                                                    <input type="text" class="input_single d-block w-full" id="escrow_signature" name="escrow_signature" required="required" data-error="#escrow_signature-error">
                                                    Luz Amparo Rockey
                                                </div>
                                                <label id="escrow_signature-error" class="error text-danger" for="escrow_signature"></label>
                                            </div>
                                            
                                        </div>

                                    </div>
                                </div>
                            </div>

                            
                        </div>
                        <p class="my-4">
							Signing below indicates that the information included here is correct and complete to the
							best of my knowledge and ackowledges and accepts the information included in this document
						</p>
						<h4 class="text-orange text-center mb-5">
							You must click SUBMIT below to securely send your completed forms to<br> Pacific Coast Title
							Company.
						</h4>
						<div class="text-center"><button type="submit" class="btn btn-primary">Submit</button></div>
                    </form>
                </div>
            </div>
        </div>
    </section>
	<script src="<?php echo base_url();?>assets/frontend/js/order/jquery.min.js"></script>
    <script src="<?php echo base_url();?>assets/frontend/js/jquery.validate.min.js"></script>
	<script src="<?php echo base_url();?>assets/frontend/js/order/bootstrap.bundle.min.js"></script>
	<script src="<?php echo base_url();?>assets/frontend/js/order/script.js?v=01"></script>
</body>

</html>
