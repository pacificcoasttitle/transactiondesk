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

	/* Fix wkhtmltopdf compatibility with BS flex features */
	.title_string {
    
}
.row {
	display: -webkit-box;
	display: flex;
	-webkit-box-pack: center;
	justify-content: center;
}
.row > div {
	-webkit-box-flex: 1;
	/* -webkit-flex: 1; */
	/* flex: 1; */
	/* -webkit-box-flex-group: 1; */
	/* flex-basis: auto; */
	padding: 0 12px 0 12px;
}
small.small_label {
	position: relative;
    bottom: 0px;
    background: transparent;
    top: 5px;
    left: 0;
	font-size: 12px;
	white-space: nowrap;
}
.position-relative input {
	border: 0;
    border-radius: 0;
    border-bottom: 1px solid;
}
label b {
	white-space: nowrap;
}


.row > div:last-child {
	margin-right: 0;
}
.align-items-start input,.align-items-start label {
	display: inline;
}

/* Fix wkhtmltopdf compatibility with BS tables borders */

</style>

<body class="">


	<!-- header -->

	<!-- <header>
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
	</header> -->

	<!-- form content -->

	<section class="form_content">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
					
                    <form action="<?php echo base_url().'borrower-seller-form/'.$orderDetails['file_id']; ?>" method="post" name="borrower_seller_form" id="borrower_seller_form">
                        <h2 class="blue_title">Seller Opening Package<br><span style="font-size:16px; padding-top:15px;">Property Address: <?php echo $orderDetails['full_address'];?></span><br><span style="font-size:16px; padding-top:15px;">APN:<?php echo $orderDetails['apn'];?></span></h2>
                        <div class="accordion" id="accordionExample">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">(1) Escrow Instructions</button>
                                </h2>
								<input type="hidden" name="order_id" id="order_id" value="<?php echo $orderDetails['order_id'];?>">
                                <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <div class="row mb-3">
                                            <div class="col-md-12">
                                                <table class="table table-type-3 typography-last-elem no-footer spacer-t30">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Document Name</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php if (!empty($docsInfo)) { 
                                                            $i = 1;
                                                            foreach($docsInfo as $docs) {
                                                                if($docs['is_escrow_instruction_doc'] == 1) {?>
                                                                    <tr role="row" class="odd">
                                                                        <td><?php echo $i;?></td>
                                                                        <td><?php echo $docs['original_document_name'];?></td>
                                                                        <td>
                                                                            <div class="custom__task_actions smart-forms" style="display: inline-block;">
                                                                                <a target="_blank" href="<?php echo env('AWS_PATH').'instruction_documents/'.$docs['document_name'];?>" class="btn button btn-primary">
                                                                                    <span class="text">View</span>
                                                                                </a>
                                                                                <div class="clearfix"></div>
                                                                            </div>
                                                                        </td> 
                                                                    </tr>   
                                                        <?php $i++; } }
                                                            } else { ?>

                                                                <tr align="center">
                                                                    <td colspan="3">No Document found.</td>
                                                                </tr>
                                                        <?php } ?>
                                                              
                                                    </tbody>
                                                </table>	
                                            </div>
                                        </div>
                                    </div>                                       
                                </div>
                            </div>
							<div class="accordion-item">
                                <h2 class="accordion-header" id="heading2">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2" aria-expanded="false" aria-controls="collapse2">(2) Commission Instructions</button>
                                </h2>
                                <div id="collapse2" class="accordion-collapse collapse" aria-labelledby="heading2" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <div class="row mb-3">
                                            <div class="col-md-12">
                                                <table class="table table-type-3 typography-last-elem no-footer spacer-t30">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Document Name</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php if (!empty($docsInfo)) { 
                                                            $i = 1;
                                                            foreach($docsInfo as $docs) {
                                                                if($docs['is_commission_doc'] == 1) {?>
                                                                    <tr role="row" class="odd">
                                                                        <td><?php echo $i;?></td>
                                                                        <td><?php echo $docs['original_document_name'];?></td>
                                                                        <td>
                                                                            <div class="custom__task_actions smart-forms" style="display: inline-block;">
                                                                                <a target="_blank" href="<?php echo env('AWS_PATH').'instruction_documents/'.$docs['document_name'];?>" class="btn button btn-primary">
                                                                                    <span class="text">View</span>
                                                                                </a>
                                                                                <div class="clearfix"></div>
                                                                            </div>
                                                                        </td> 
                                                            </tr>   
                                                            <?php $i++; } }
                                                            } else { ?>

                                                            <tr align="center">
                                                                <td colspan="3">No Document found.</td>
                                                            </tr>
                                                        <?php } ?>
                                                            
                                                    </tbody>
                                                </table>	
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item d-none">
                                <h2 class="accordion-header" id="headingEighteen">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEighteen" aria-expanded="true" aria-controls="collapseEighteen">
                                      (3) OWNER'S ESCROW INFORMATION SHEET
                                    </button>
                                </h2>
                                <div id="collapseEighteen" class="accordion-collapse collapse" aria-labelledby="headingEighteen" data-bs-parent="#accordionExample" style="">
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
                                            <span><strong>ESCROW NO.:</strong></span><?php echo $orderDetails['escrow_number'];?><br>
                                            <span><strong>TITLE NO.:</strong></span>10257432-GLT-<br>
                                        </div>

                                        <div class="text-center mt-4">
                                            PLEASE FILL OUT THIS FORM COMPLETELY AND RETURN TO OUR OFFICE AS SOON AS POSSIBLE <br>
                                            AS IT WILL ASSIST US IN THE ADMINISTRATION OF YOUR TRANSACTION.
                                        </div>

                                        <div class="form-group position-relative mb-3 mt-3">
                                            <label for="" class="mb-2"><b></b></label>
                                            <input type="text" value="<?php echo $seller_name;?>" class="form-control" id="seller_name" name="seller_name" value="<?php echo $sellerInfo['seller_name'] ? $sellerInfo['seller_name'] : '';?>" required="required" data-error="#seller_name-error">
                                            <small class="small_label">Seller(s):</small>
                                        </div>
                                        <label id="seller_name-error" class="error text-danger" for="seller_name"></label>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3 mt-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" value="<?php echo $escrow_home_phone_number;?>" class="form-control" id="" name="escrow_home_phone_number" value="<?php echo $sellerInfo['escrow_home_phone_number'] ? $sellerInfo['escrow_home_phone_number'] : '';?>" required="required" data-error="#escrow_home_phone_number-error">
                                                    <small class="small_label">Home Phone Number:</small>
                                                </div>
                                                <label id="escrow_home_phone_number-error" class="error text-danger" for="escrow_home_phone_number"></label>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3 mt-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" value="<?php echo $work_phone_number;?>" class="form-control" id="work_phone_number" name="work_phone_number" value="<?php echo $sellerInfo['work_phone_number'] ? $sellerInfo['work_phone_number'] : '';;?>" required="required" data-error="#work_phone_number-error">
                                                    <small class="small_label">Work Phone Number:</small>
                                                </div>
                                                <label id="work_phone_number-error" class="error text-danger" for="work_phone_number"></label>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3 mt-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" value="<?php echo $fax_number;?>" class="form-control" id="fax_number" name="fax_number" value="<?php echo $sellerInfo['fax_number'] ? $sellerInfo['fax_number'] : '';;?>" required="required" data-error="#fax_number-error">
                                                    <small class="small_label">Fax Number:</small>
                                                    
                                                </div>
                                                <label id="fax_number-error" class="error text-danger" for="fax_number"></label>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3 mt-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" value="<?php echo $cell_phone_number;?>" class="form-control" id="cell_phone_number" name="cell_phone_number" value="<?php echo $sellerInfo['cell_phone_number'] ? $sellerInfo['cell_phone_number'] : '';?>" required="required" data-error="#cell_phone_number-error">
                                                    <small class="small_label">Cell Phone Number:</small>
                                                    
                                                </div>
                                                <label id="cell_phone_number-error" class="error text-danger" for="cell_phone_number"></label>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3 mt-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" value="<?php echo $email_address;?>" class="form-control" id="email_address" name="email_address" value="<?php echo $sellerInfo['email_address'] ? $sellerInfo['email_address'] : '';?>" required="required" data-error="#email_address-error">
                                                    <small class="small_label">E-Mail Address:</small>
                                                    
                                                </div>
                                                <label id="email_address-error" class="error text-danger" for="email_address"></label>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3 mt-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" value="<?php echo $cell_phone_number_2;?>" class="form-control" id="cell_phone_number_2" name="cell_phone_number_2" value="<?php echo $sellerInfo['cell_phone_number_2'] ? $sellerInfo['cell_phone_number_2'] : '';?>" required="required" data-error="#cell_phone_number_2-error">
                                                    <small class="small_label">Cell Phone Number:</small>
                                                    
                                                </div>
                                                <label id="cell_phone_number_2-error" class="error text-danger" for="cell_phone_number_2"></label>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3 mt-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" value="<?php echo $escrow_ssn;?>" class="form-control" id="escrow_ssn" name="escrow_ssn" value="<?php echo $sellerInfo['escrow_ssn'] ? $sellerInfo['escrow_ssn'] : '';?>" required="required" data-error="#escrow_ssn-error">
                                                    <small class="small_label">Social Security #:</small>
                                                   
                                                </div>
                                                <label id="escrow_ssn-error" class="error text-danger" for="escrow_ssn"></label>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3 mt-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" value="<?php echo $ssn_2;?>" class="form-control" id="ssn_2" name="ssn_2" value="<?php echo $sellerInfo['ssn_2'] ? $sellerInfo['ssn_2'] : '';?>" required="required" data-error="#ssn_2-error">
                                                    <small class="small_label">Social Security #:</small>
                                                    
                                                </div>
                                                <label id="ssn_2-error" class="error text-danger" for="ssn_2"></label>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group position-relative mb-3 mt-3">
                                                <label for="" class="mb-2"><b></b></label>
                                                <textarea rows="2" class="form-control" style="height:auto;" id="property_address" name="property_address" required="required" data-error="#property_address-error"><?php echo $property_address;?></textarea>
                                                <small class="small_label">Property Address:</small>
                                                
                                            </div>
                                            <label id="property_address-error" class="error text-danger" for="property_address"></label>
                                        </div>

                                        <div class="row">
                                            <div class="form-group position-relative mb-3 mt-3">
                                                <label for="" class="mb-2"><b></b></label>
                                                <textarea rows="2" class="form-control" style="height:auto;" id="seller_current_mailing_address" name="seller_current_mailing_address"  required="required" data-error="#seller_current_mailing_address-error"><?php echo $seller_current_mailing_address;?></textarea>
                                                <small class="small_label">Seller(s) Current Mailing Address: </small>
                                                
                                            </div>
                                            <label id="seller_current_mailing_address-error" class="error text-danger" for="seller_current_mailing_address"></label>
                                        </div>

                                        <div class="row">
                                            <div class="form-group position-relative mb-3 mt-3">
                                                <label for="" class="mb-2"><b></b></label>
                                                <textarea rows="2" class="form-control" style="height:auto;" id="seller_mailing_address_after_close_escrow" name="seller_mailing_address_after_close_escrow"  required="required" data-error="#seller_mailing_address_after_close_escrow-error"><?php echo $seller_mailing_address_after_close_escrow;?></textarea>
                                                <small class="small_label">Seller(s) Mailing Address after Close of Escrow: </small>
                                                
                                            </div>
                                            <label id="seller_mailing_address_after_close_escrow-error" class="error text-danger" for="seller_mailing_address_after_close_escrow"></label>
                                        </div>

                                        <div class="row">
                                            <div class="form-group position-relative mb-3 mt-3">
                                                <label for="" class="mb-2"><b></b></label>
                                                <textarea rows="2" class="form-control" style="height:auto;" id="seller_mailing_address_after_close_escrow_2" name="seller_mailing_address_after_close_escrow_2"  required="required" data-error="#seller_mailing_address_after_close_escrow_2-error"><?php echo $seller_mailing_address_after_close_escrow_2;?></textarea>
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
                                            <input type="text" value="<?php echo $first_trust_deed_lender;?>" class="form-control" id="first_trust_deed_lender" name="first_trust_deed_lender"  required="required" data-error="#first_trust_deed_lender-error">
                                            <small class="small_label">FIRST TRUST DEED LENDER:</small>
                                        </div>
                                        <label id="first_trust_deed_lender-error" class="error text-danger" for="first_trust_deed_lender"></label>

                                        <div class="row">
                                            <div class="form-group position-relative mb-3 mt-3">
                                                <label for="" class="mb-2"><b></b></label>
                                                <textarea rows="2" class="form-control" style="height:auto;" id="lender_address" name="lender_address" required="required" data-error="#lender_address-error"><?php echo $lender_address;?></textarea>
                                                <small class="small_label">Address:</small>
                                                
                                            </div>
                                        </div>
                                        <label id="lender_address-error" class="error text-danger" for="lender_address"></label>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3 mt-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" value="<?php echo $loan_number;?>" class="form-control" id="loan_number" name="loan_number" required="required" data-error="#loan_number-error">
                                                    <small class="small_label">Loan Number:</small>
                                                    
                                                </div>
                                                <label id="loan_number-error" class="error text-danger" for="loan_number"></label>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3 mt-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" value="<?php echo $lender_phone_number;?>" class="form-control" id="lender_phone_number" name="lender_phone_number" required="required" data-error="#lender_phone_number-error">
                                                    <small class="small_label">Phone Number:</small>
                                                </div>
                                                <label id="lender_phone_number-error" class="error text-danger" for="lender_phone_number"></label>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3 mt-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" value="<?php echo $unpaid_principal_balance;?>" class="form-control" id="unpaid_principal_balance" name="unpaid_principal_balance"  required="required" data-error="#unpaid_principal_balance-error">
                                                    <small class="small_label">Unpaid Principal Balance $:</small>
                                                </div>
                                                <label id="unpaid_principal_balance-error" class="error text-danger" for="unpaid_principal_balance"></label>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3 mt-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" value="<?php echo $next_due;?>" class="form-control" id="next_due" name="next_due"  required="required" data-error="#next_due-error">
                                                    <small class="small_label">Next Due:</small>
                                                </div>
                                                <label id="next_due-error" class="error text-danger" for="next_due"></label>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="row mt-4">
                                                <div class="col-lg-9 col-md-8">
                                                    <div class="mb-3">
                                                        Type of Loan: <input type="text" value="<?php echo $type_of_loan;?>" class="input_single w-medium" id="type_of_loan" name="type_of_loan" > VA <input type="text" value="<?php echo $va;?>" class="input_single w-medium" id="va" name="va">FHA <input type="text" value="<?php echo $fha;?>" class="input_single w-medium" id="fha" name="fha" >Conventional <input type="text" value="<?php echo $conventional;?>" class="input_single w-medium" id="conventional" name="conventional">Equity Line/Line of Credit
                                                    </div>
                                                    <div>
                                                        TAXES: <input type="text" value="<?php echo $taxes;?>" class="input_single w-medium" id="taxes" name="taxes" >Paid <input type="text" value="<?php echo $paid;?>" class="input_single w-medium" id="paid" name="paid"> Unpaid<input type="text" value="<?php echo $unpaid;?>" class="input_single w-medium" id="unpaid" name="unpaid" > Taxes are being paid through my impound account
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 col-md-4">
                                                    <div class="d-flex">
                                                        Impound Acct:
                                                        <div class="ms-2">
                                                            <input type="radio" <?php echo ($is_impound_acc == 'yes') ? 'checked="checked"' : '';?> <?php echo ($is_buyer_intends == 'Yes') ? 'checked="checked"' : '';?> id="impondYes" value="yes" name="is_impound_acc" >
                                                            <label for="impondYes">Yes</label>
                                                        </div>
                                                        <div class="ms-2">
                                                            <input type="radio" <?php echo ($is_impound_acc == 'no') ? 'checked="checked"' : '';?> id="impondNo" value="no" name="is_impound_acc">
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
                                                <input type="text" value="<?php echo $second_trust_deed_lender;?>" class="form-control" id="second_trust_deed_lender" name="second_trust_deed_lender" required="required" data-error="#second_trust_deed_lender-error">
                                                <small class="small_label">SECOND TRUST DEED LENDER: </small>
                                            </div>
                                            <label id="second_trust_deed_lender-error" class="error text-danger" for="second_trust_deed_lender"></label>
                                        </div>

                                        <div class="row">
                                            <div class="form-group position-relative mb-3 mt-3">
                                                <label for="" class="mb-2"><b></b></label>
                                                <textarea rows="2" class="form-control" style="height:auto;" id="second_lender_address" name="second_lender_address"  required="required" data-error="#second_lender_address-error"><?php echo $second_lender_address;?></textarea>
                                                <small class="small_label">Address:</small>
                                                
                                            </div>
                                            <label id="second_lender_address-error" class="error text-danger" for="second_lender_address"></label>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3 mt-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" value="<?php echo $second_loan_number;?>" class="form-control" id="second_loan_number" name="second_loan_number" required="required" data-error="#second_loan_number-error">
                                                    <small class="small_label">Loan Number:</small>
                                                    
                                                </div>
                                                <label id="second_loan_number-error" class="error text-danger" for="second_loan_number"></label>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3 mt-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" value="<?php echo $second_lender_phone_number;?>" class="form-control" id="second_lender_phone_number" name="second_lender_phone_number" required="required" data-error="#second_lender_phone_number-error">
                                                    <small class="small_label">Phone Number:</small>
                                                    
                                                </div>
                                                <label id="second_lender_phone_number-error" class="error text-danger" for="second_lender_phone_number"></label>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group position-relative mb-3 mt-3 col-md-6">
                                                <label for="" class="mb-2"><b></b></label>
                                                <input type="text" value="<?php echo $second_unpaid_principal_balance;?>" class="form-control" id="second_unpaid_principal_balance" name="second_unpaid_principal_balance" required="required" data-error="#second_unpaid_principal_balance-error">
                                                <small class="small_label">Unpaid Principal Balance $:</small>
                                            </div>
                                            <label id="second_unpaid_principal_balance-error" class="error text-danger" for="second_unpaid_principal_balance"></label>
                                        </div>
                                        
                                        <div class="row mt-4">
                                            <div class="col-lg-12">
                                                <div class="mb-3">
                                                    Type of Loan: <input type="text" value="<?php echo $second_type_of_loan;?>" class="input_single w-medium" id="second_type_of_loan" name="second_type_of_loan" > VA <input type="text" value="<?php echo $second_va;?>" class="input_single w-medium" id="second_va" name="second_va" >FHA <input type="text" value="<?php echo $second_fha;?>" class="input_single w-medium" id="second_fha" name="second_fha" >Conventional <input type="text" value="<?php echo $second_conventional;?>" class="input_single w-medium" id="second_conventional" name="second_conventional">Equity Line/Line of Credit
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group position-relative mb-3 mt-3">
                                                <label for="" class="mb-2"><b></b></label>
                                                <input type="text" value="<?php echo $homeowner_association;?>" class="form-control" id="homeowner_association" name="homeowner_association" required="required" data-error="#homeowner_association-error">
                                                <small class="small_label">Homeowner’s Association: </small>
                                                
                                            </div>
                                            <label id="homeowner_association-error" class="error text-danger" for="homeowner_association"></label>
                                        </div>

                                        <div class="row">
                                            <div class="form-group position-relative mb-3 mt-3">
                                                <label for="" class="mb-2"><b></b></label>
                                                <input type="text" value="<?php echo $management_company;?>" class="form-control" id="management_company" name="management_company" required="required" data-error="#management_company-error">
                                                <small class="small_label">Management Company: </small>
                                                
                                            </div>
                                            <label id="management_company-error" class="error text-danger" for="management_company"></label>
                                        </div>

                                        <div class="row">
                                            <div class="form-group position-relative mb-3 mt-3">
                                                <label for="" class="mb-"><b></b></label>
                                                <input type="text" value="<?php echo $management_mailing_address;?>" class="form-control" id="management_mailing_address" name="management_mailing_address" required="required" data-error="#management_mailing_address-error">
                                                <small class="small_label">Mailing Address: </small>
                                                
                                            </div>
                                            <label id="management_mailing_address-error" class="error text-danger" for="management_mailing_address"></label>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3 mt-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" value="<?php echo $contact_person;?>" class="form-control" id="contact_person" name="contact_person" required="required" data-error="#contact_person-error">
                                                    <small class="small_label">Contact Person:</small>
                                                    
                                                </div>
                                                <label id="contact_person-error" class="error text-danger" for="contact_person"></label>
                                            </div> 
                                            <div class="col-md-6">         
                                                <div class="form-group position-relative mb-3 mt-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" value="<?php echo $management_phone_number;?>" class="form-control" id="management_phone_number" name="management_phone_number" required="required" data-error="#management_phone_number-error">
                                                    <small class="small_label">Phone Number:</small>
                                                    
                                                </div>
                                                <label id="management_phone_number-error" class="error text-danger" for="management_phone_number"></label>
                                            </div>
                                        </div>

                                        <hr>	

                                        <div class="row">
                                            <div class="form-group position-relative mb-3 mt-3">
                                                <label for="" class="mb-2"><b></b></label>
                                                <input type="text" value="<?php echo $second_homeowner_association;?>" class="form-control" id="second_homeowner_association" name="second_homeowner_association" required="required" data-error="#second_homeowner_association-error">
                                                <small class="small_label">Homeowner’s Association: </small>
                                                
                                            </div>
                                            <label id="second_homeowner_association-error" class="error text-danger" for="second_homeowner_association"></label>
                                        </div>

                                        <div class="row">
                                            <div class="form-group position-relative mb-3 mt-3">
                                                <label for="" class="mb-2"><b></b></label>
                                                <input type="text" value="<?php echo $second_management_company;?>" class="form-control" id="second_management_company" name="second_management_company" required="required" data-error="#second_management_company-error">
                                                <small class="small_label">Management Company: </small>
                                                
                                            </div>
                                            <label id="second_management_company-error" class="error text-danger" for="second_management_company"></label>
                                        </div>

                                        <div class="row">
                                            <div class="form-group position-relative mb-3 mt-3">
                                                <label for="" class="mb-2"><b></b></label>
                                                <input type="text" value="<?php echo $second_management_mailing_address;?>" class="form-control" id="second_management_mailing_address" name="second_management_mailing_address" required="required" data-error="#second_management_mailing_address-error">
                                                <small class="small_label">Mailing Address: </small>
                                                
                                            </div>
                                            <label id="second_management_mailing_address-error" class="error text-danger" for="second_management_mailing_address"></label>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3 mt-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" value="<?php echo $second_contact_person;?>" class="form-control" id="second_contact_person" name="second_contact_person" required="required" data-error="#second_contact_person-error">
                                                    <small class="small_label">Contact Person:</small>
                                                    
                                                </div>
                                                <label id="second_contact_person-error" class="error text-danger" for="second_contact_person"></label>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3 mt-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" value="<?php echo $second_management_phone_number;?>" class="form-control" id="second_management_phone_number" name="second_management_phone_number" required="required" data-error="#second_management_phone_number-error">
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
                                                    <input type="text" value="<?php echo $water_company_name;?>" class="form-control" id="water_company_name" name="water_company_name" required="required" data-error="#water_company_name-error">
                                                    <small class="small_label">Name of Company: </small>
                                                    
                                                </div>
                                                <label id="water_company_name-error" class="error text-danger" for="water_company_name"></label>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3 mt-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" value="<?php echo $water_contract_name;?>" class="form-control" id="water_contract_name" name="water_contract_name" required="required" data-error="#water_contract_name-error">
                                                    <small class="small_label">Name of Contact: </small>
                                                    
                                                </div>
                                                <label id="water_contract_name-error" class="error text-danger" for="water_contract_name"></label>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3 mt-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" value="<?php echo $water_company_address;?>" class="form-control" id="water_company_address" name="water_company_address" required="required" data-error="#water_company_address-error">
                                                    <small class="small_label">Address: </small>
                                                    
                                                </div>
                                                <label id="water_company_address-error" class="error text-danger" for="water_company_address"></label>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3 mt-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" value="<?php echo $water_company_phone;?>" class="form-control" id="water_company_phone" name="water_company_phone" required="required" data-error="#water_company_phone-error">
                                                    <small class="small_label">Phone: </small>
                                                    
                                                </div>
                                                <label id="water_company_phone-error" class="error text-danger" for="water_company_phone"></label>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group position-relative mb-3 mt-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" value="<?php echo $amount_of_assessment;?>" class="form-control" id="amount_of_assessment" name="amount_of_assessment"  required="required" data-error="#amount_of_assessment-error">
                                                    <small class="small_label">Amount of assessment $ </small>
                                                    
                                                </div>
                                                <label id="amount_of_assessment-error" class="error text-danger" for="amount_of_assessment"></label>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group position-relative mb-3 mt-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" value="<?php echo $water_next_due;?>" class="form-control" id="water_next_due" name="water_next_due" required="required" data-error="#water_next_due-error">
                                                    <small class="small_label">Next Due </small>
                                                    
                                                </div>
                                                <label id="water_next_due-error" class="error text-danger" for="water_next_due"></label>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group position-relative mb-3 mt-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" value="<?php echo $no_of_shares;?>" class="form-control" id="no_of_shares" name="no_of_shares"  required="required" data-error="#no_of_shares-error">
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
                                                    <input type="text" value="<?php echo $date;?>" class="input_single" id="date" name="date" required="required" data-error="#date-error">
                                                </div>
                                                <label id="date-error" class="error text-danger" for="date"></label>
                                            </div>
                                            
                                            <div class="col-md-4">
                                                <div class="mt-5">
                                                    <input type="text" value="<?php echo $escrow_signature;?>" class="input_single d-block w-full" id="escrow_signature" name="escrow_signature" required="required" data-error="#escrow_signature-error">
                                                    Luz Amparo Rockey
                                                </div>
                                                <label id="escrow_signature-error" class="error text-danger" for="escrow_signature"></label>
                                            </div>
                                            
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingThree">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">   (3) Statement of Information</button>
                                </h2>
                                <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                    <h4 class="text-center my-4"><strong>CONFIDENTIAL INFORMATION FOR YOUR PROTECTION</strong></h4>

                                    <div class="mb-20">
                                        Completion of this statement expedites your application for title insurance, as it assists in establishing identity, eliminating matters affecting persons with similar names and avoiding the use of fraudulent or forged documents.  Complete all blanks (please print) or indicate "none" or "N/A."  If more space is needed for any item(s), use the reverse side of the form.  Each party (and spouse/domestic partner, if applicable) to the transaction should personally sign this form.
                                    </div>
                                    <div class="row my-5">
                                        <div class="col-md-6">
                                            To: Pacific Coast Title Company <br>
                                            516 Burchett St., Glendale, CA  91203	
                                        </div>
                                        <div class="col-md-6 text-md-end">
                                            ESCROW NO.:  <b>10257432-GLE-MP</b><br>TITLE NO.: <b> 10257432-GLT-</b>	
                                        </div> 
                                    </div>
                                    <h4 class="text-center"><b>NAME AND PERSONAL INFORMATION</b></h4>

                                    <div class="row mt-5">
                                        <div class="col-md-9">	
                                            <div class="row">
                                                <div class="col-lg-3 col-md-6">
                                                    <div class="form-group position-relative mb-3">
                                                        <label for="" class="mb-2"><b></b></label>
                                                        <input type="text" value="<?php echo $first_name;?>" class="form-control" id="first_name" name="first_name" required="required" data-error="#first_name-error">
                                                        <small class="small_label">First Name</small>
                                                    </div>
                                                    <label id="first_name-error" class="error text-danger" for="first_name"></label>
                                                </div>
                                                <div class="col-lg-3 col-md-6">
                                                    <div class="form-group position-relative mb-3">
                                                        <label for="" class="mb-2"><b></b></label>
                                                        <input type="text" value="<?php echo $middle_name;?>" class="form-control" id="middle_name" name="middle_name" required="required" data-error="#middle_name-error">
                                                        <small class="small_label">Middle Name</small>
                                                    </div>
                                                    <label id="middle_name-error" class="error text-danger" for="middle_name"></label>
                                                </div>
                                                <div class="col-lg-3 col-md-6">
                                                    <div class="form-group position-relative mb-3">
                                                        <label for="" class="mb-2"><b></b></label>
                                                        <input type="text" value="<?php echo $last_name;?>" class="form-control" id="last_name" name="last_name" required data-error="#last_name-error">
                                                        <small class="small_label">Last Name</small>
                                                    </div>
                                                    <label id="last_name-error" class="error text-danger" for="last_name"></label>
                                                </div>
                                                <div class="col-lg-3 col-md-6">
                                                    <div class="form-group position-relative mb-3">
                                                        <label for="" class="mb-2"><b></b></label>
                                                        <input type="text" value="<?php echo $maiden_name;?>" class="form-control" id="maiden_name" name="maiden_name" required data-error="#maiden_name-error">
                                                        <small class="small_label">Maiden Name</small>
                                                    </div>
                                                    <label id="maiden_name-error" class="error text-danger" for="maiden_name"></label>
                                                </div>
                                            </div>
                                            <div class="text-center mt-4 f14">(If none, indicate)</div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group position-relative mb-3">
                                                <label for="" class="mb-2"><b></b></label>
                                                <input type="text" value="<?php echo $date_of_birth;?>" class="form-control" id="date_of_birth" name="date_of_birth" required data-error="#date_of_birth-error">
                                                <small class="small_label">Date of Birth</small>
                                            </div>
                                            <label id="date_of_birth-error" class="error text-danger" for="date_of_birth"></label>
                                        </div>
                                    </div>
                                        <div class="row mt-3">
                                            <div class="col-md-4">
                                                <div class="form-group position-relative mb-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" value="<?php echo $home_phone_number;?>" class="form-control" id="home_phone_number" name="home_phone_number" required data-error="#home_phone_number-error">
                                                    <small class="small_label">Home Phone</small>
                                                </div>
                                                <label id="home_phone_number-error" class="error text-danger" for="home_phone_number"></label>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group position-relative mb-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" value="<?php echo $business_phone_number;?>" class="form-control" id="business_phone_number" name="business_phone_number" required data-error="#business_phone_number-error">
                                                    <small class="small_label">Business Phone</small>
                                                </div>
                                                <label id="business_phone_number-error" class="error text-danger" for="business_phone_number"></label>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group position-relative mb-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" value="<?php echo $birthplace;?>" class="form-control" id="birthplace" name="birthplace" required data-error="#birthplace-error">
                                                    <small class="small_label">Birthplace</small>
                                                </div>
                                                <label id="birthplace-error" class="error text-danger" for="birthplace"></label>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" value="<?php echo $ssn;?>" class="form-control" id="ssn" name="ssn" required data-error="#ssn-error">
                                                    <small class="small_label">Social Security No.</small>
                                                </div>
                                                <label id="ssn-error" class="error text-danger" for="ssn"></label>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" value="<?php echo $driver_license_no;?>" class="form-control" id="driver_license_no" name="driver_license_no" required data-error="#driver_license_no-error">
                                                    <small class="small_label">Driver’s License No.</small>
                                                </div>
                                                <label id="driver_license_no-error" class="error text-danger" for="driver_license_no"></label>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-md-12">
                                                <div class="form-group position-relative mb-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" value="<?php echo $another_name_that_used;?>" class="form-control" id="another_name_that_used" name="another_name_that_used" required data-error="#another_name_that_used-error">
                                                    <small class="small_label">List any other name you have used or been known by</small>
                                                </div>
                                                <label id="another_name_that_used-error" class="error text-danger" for="another_name_that_used"></label>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" value="<?php echo $residence_state;?>" class="form-control" id="residence_state" name="residence_state" required data-error="#residence_state-error">
                                                    <small class="small_label">State of residence</small>
                                                </div>
                                                <label id="residence_state-error" class="error text-danger" for="residence_state"></label>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" value="<?php echo $lived_year;?>" class="form-control" id="lived_year" name="lived_year" required data-error="#lived_year-error">
                                                    <small class="small_label">I have lived continuously in the U.S.A. since</small>
                                                </div>
                                                <label id="lived_year-error" class="error text-danger" for="lived_year"></label>
                                            </div>
                                        </div>                                            
                                        <div class="mt-5">
                                            Are you currently married? <input type="checkbox" <?php echo  ($is_married == 'on') ? 'checked="checked"' : '';?> name="is_married" id="is_married"> If yes, complete the following information:
                                        </div>

                                        <div class="form-group position-relative mt-3 mb-3">
                                            <label for="" class="mb-2"><b></b></label>
                                            <input type="text" value="<?php echo $date_and_place_marriage;?>" class="form-control" id="date_and_place_marriage" name="date_and_place_marriage" data-error="#date_and_place_marriage-error">
                                            <small class="small_label">Date and place of marriage</small>
                                        </div>
                                        <label id="date_and_place_marriage-error" class="error text-danger d-flex" for="date_and_place_marriage"></label>  

                                        <div class="row mt-3">
                                            <div class="col-md-9">	
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-6">
                                                        <div class="form-group position-relative mb-3">
                                                            <label for="" class="mb-2"><b>Spouse:</b></label>
                                                            <input type="text" value="<?php echo $spouse_first_name;?>" class="form-control" id="spouse_first_name" name="spouse_first_name" data-error="#spouse_first_name-error">
                                                            <small class="small_label">First Name</small>
                                                        </div>
                                                        <label id="spouse_first_name-error" class="error text-danger" for="spouse_first_name"></label>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6">
                                                        <div class="form-group position-relative mb-3">
                                                            <label for="" class="mb-2"><b>&nbsp;</b></label>
                                                            <input type="text" value="<?php echo $spouse_middle_name;?>" class="form-control" id="spouse_middle_name" name="spouse_middle_name" data-error="#spouse_middle_name-error">
                                                            <small class="small_label">Middle Name</small>
                                                        </div>
                                                        <label id="spouse_middle_name-error" class="error text-danger" for="spouse_middle_name"></label>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6">
                                                        <div class="form-group position-relative mb-3">
                                                            <label for="" class="mb-2"><b>&nbsp;</b></label>
                                                            <input type="text" value="<?php echo $spouse_last_name;?>" class="form-control" id="spouse_last_name" name="spouse_last_name" data-error="#spouse_last_name-error">
                                                            <small class="small_label">Last Name</small>
                                                        </div>
                                                        <label id="spouse_last_name-error" class="error text-danger" for="spouse_last_name"></label>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6">
                                                        <div class="form-group position-relative mb-3">
                                                            <label for="" class="mb-2"><b>&nbsp;</b></label>
                                                            <input type="text" value="<?php echo $spouse_maiden_name;?>" class="form-control" id="spouse_maiden_name" name="spouse_maiden_name" data-error="#spouse_maiden_name-error">
                                                            <small class="small_label">Maiden Name</small>
                                                        </div>
                                                        <label id="spouse_maiden_name-error" class="error text-danger" for="spouse_maiden_name"></label>
                                                    </div>
                                                </div>
                                                <div class="text-center mt-4 f14">(If none, indicate)</div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group position-relative mb-3">
                                                    <label for="" class="mb-2"><b>&nbsp;</b></label>
                                                    <input type="text" value="<?php echo $spouse_date_of_birth;?>" class="form-control" id="spouse_date_of_birth" name="spouse_date_of_birth" data-error="#spouse_date_of_birth-error">
                                                    <small class="small_label">Date of Birth</small>
                                                </div>
                                                <label id="spouse_date_of_birth-error" class="error text-danger" for="spouse_date_of_birth"></label>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-md-4">
                                                <div class="form-group position-relative mb-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" value="<?php echo $spouse_home_phone_number;?>" class="form-control" id="spouse_home_phone_number" name="spouse_home_phone_number" data-error="#spouse_home_phone_number-error">
                                                    <small class="small_label">Home Phone</small>
                                                </div>
                                                <label id="spouse_home_phone_number-error" class="error text-danger" for="spouse_home_phone_number"></label>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group position-relative mb-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" value="<?php echo $spouse_business_phone_number;?>" class="form-control" id="spouse_business_phone_number" name="spouse_business_phone_number" data-error="#spouse_business_phone_number-error">
                                                    <small class="small_label">Business Phone</small>
                                                </div>
                                                <label id="spouse_business_phone_number-error" class="error text-danger" for="spouse_business_phone_number"></label>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group position-relative mb-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" value="<?php echo $spouse_birthplace;?>" class="form-control" id="spouse_birthplace" name="spouse_birthplace" data-error="#spouse_birthplace-error">
                                                    <small class="small_label">Birthplace</small>
                                                </div>
                                                <label id="spouse_birthplace-error" class="error text-danger" for="spouse_birthplace"></label>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" value="<?php echo $spouse_ssn;?>" class="form-control" id="spouse_ssn" name="spouse_ssn" data-error="#spouse_ssn-error">
                                                    <small class="small_label">Social Security No.</small>
                                                </div>
                                                <label id="spouse_ssn-error" class="error text-danger" for="spouse_ssn"></label>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" value="<?php echo $spouse_driver_license_no;?>" class="form-control" id="spouse_driver_license_no" name="spouse_driver_license_no" data-error="#spouse_driver_license_no-error">
                                                    <small class="small_label">Driver’s License No.</small>
                                                </div>
                                                <label id="spouse_driver_license_no-error" class="error text-danger" for="spouse_driver_license_no"></label>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-md-12">
                                                <div class="form-group position-relative mb-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" value="<?php echo $spouse_another_name_that_used;?>" class="form-control" id="spouse_another_name_that_used" name="spouse_another_name_that_used" data-error="#spouse_another_name_that_used-error">
                                                    <small class="small_label">List any other name you have used or been known by</small>
                                                </div>
                                                <label id="spouse_another_name_that_used-error" class="error text-danger" for="spouse_another_name_that_used"></label>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" value="<?php echo $spouse_state_residence;?>" class="form-control" id="spouse_state_residence" name="spouse_state_residence" data-error="#spouse_state_residence-error">
                                                    <small class="small_label">State of residence</small>
                                                </div>
                                                <label id="spouse_state_residence-error" class="error text-danger" for="spouse_state_residence"></label>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" value="<?php echo $spouse_lived_year;?>" class="form-control" id="spouse_lived_year" name="spouse_lived_year" data-error="#spouse_lived_year-error">
                                                    <small class="small_label">I have lived continuously in the U.S.A. since</small>
                                                </div>
                                                <label id="spouse_lived_year-error" class="error text-danger" for="spouse_lived_year"></label>
                                            </div>
                                        </div>                                            
                                        <div class="mt-5">
                                            Are you currently a registered domestic partner? <input type="checkbox" <?php echo  ($is_domestic_partner == 'on') ? 'checked="checked"' : '';?> name="is_domestic_partner" id="is_domestic_partner"> If yes, complete the following information:
                                        </div>

                                        <div class="row mt-3">
                                            <div class="col-md-9">	
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-6">
                                                        <div class="form-group position-relative mb-3">
                                                            <label for="" class="mb-2"><b>Domestic Partner:</b></label>
                                                            <input type="text" value="<?php echo $domestic_first_name;?>" class="form-control" id="domestic_first_name" name="domestic_first_name" data-error="#domestic_first_name-error">
                                                            <small class="small_label">First Name</small>
                                                        </div>
                                                        <label id="domestic_first_name-error" class="error text-danger" for="domestic_first_name"></label>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6">
                                                        <div class="form-group position-relative mb-3">
                                                            <label for="" class="mb-2"><b>&nbsp;</b></label>
                                                            <input type="text" value="<?php echo $domestic_middle_name;?>" class="form-control" id="domestic_middle_name" name="domestic_middle_name" data-error="#domestic_middle_name-error">
                                                            <small class="small_label">Middle Name</small>
                                                        </div>
                                                        <label id="domestic_middle_name-error" class="error text-danger" for="domestic_middle_name"></label>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6">
                                                        <div class="form-group position-relative mb-3">
                                                            <label for="" class="mb-2"><b>&nbsp;</b></label>
                                                            <input type="text" value="<?php echo $domestic_last_name;?>" class="form-control" id="domestic_last_name" name="domestic_last_name" data-error="#domestic_last_name-error">
                                                            <small class="small_label">Last Name</small>
                                                        </div>
                                                        <label id="domestic_last_name-error" class="error text-danger" for="domestic_last_name"></label>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6">
                                                        <div class="form-group position-relative mb-3">
                                                            <label for="" class="mb-2"><b>&nbsp;</b></label>
                                                            <input type="text" value="<?php echo $domestic_maiden_name;?>" class="form-control" id="domestic_maiden_name" name="domestic_maiden_name" data-error="#domestic_maiden_name-error">
                                                            <small class="small_label">Maiden Name</small>
                                                        </div>
                                                        <label id="domestic_maiden_name-error" class="error text-danger" for="domestic_maiden_name"></label>
                                                    </div>
                                                </div>
                                                <div class="text-center mt-4 f14">(If none, indicate)</div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group position-relative mb-3">
                                                    <label for="" class="mb-2"><b>&nbsp;</b></label>
                                                    <input type="text" value="<?php echo $domestic_date_of_birth;?>" class="form-control" id="domestic_date_of_birth" name="domestic_date_of_birth" data-error="#domestic_date_of_birth-error">
                                                    <small class="small_label">Date of Birth</small>
                                                </div>
                                                <label id="domestic_date_of_birth-error" class="error text-danger" for="domestic_date_of_birth"></label>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-md-4">
                                                <div class="form-group position-relative mb-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" value="<?php echo $domestic_home_phone_number;?>" class="form-control" id="domestic_home_phone_number" name="domestic_home_phone_number" data-error="#domestic_home_phone_number-error">
                                                    <small class="small_label">Home Phone</small>
                                                </div>
                                                <label id="domestic_home_phone_number-error" class="error text-danger" for="domestic_home_phone_number"></label>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group position-relative mb-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" value="<?php echo $domestic_business_phone_number;?>" class="form-control" id="domestic_business_phone_number" name="domestic_business_phone_number" data-error="#domestic_business_phone_number-error">
                                                    <small class="small_label">Business Phone</small>
                                                </div>
                                                <label id="domestic_business_phone_number-error" class="error text-danger" for="domestic_business_phone_number"></label>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group position-relative mb-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" value="<?php echo $domestic_birthplace;?>" class="form-control" id="domestic_birthplace" name="domestic_birthplace" data-error="#domestic_birthplace-error">
                                                    <small class="small_label">Birthplace</small>
                                                </div>
                                                <label id="domestic_birthplace-error" class="error text-danger" for="domestic_birthplace"></label>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" value="<?php echo $domestic_ssn;?>" class="form-control" id="domestic_ssn" name="domestic_ssn" data-error="#domestic_ssn-error">
                                                    <small class="small_label">Social Security No.</small>
                                                </div>
                                                <label id="domestic_ssn-error" class="error text-danger" for="domestic_ssn"></label>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" value="<?php echo $domestic_driver_license_no;?>" class="form-control" id="domestic_driver_license_no" name="domestic_driver_license_no" data-error="#domestic_driver_license_no-error">
                                                    <small class="small_label">Driver’s License No.</small>
                                                </div>
                                                <label id="domestic_driver_license_no-error" class="error text-danger" for="domestic_driver_license_no"></label>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-md-12">
                                                <div class="form-group position-relative mb-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" value="<?php echo $domestic_another_name_that_used;?>" class="form-control" id="domestic_another_name_that_used" name="domestic_another_name_that_used" data-error="#domestic_another_name_that_used-error">
                                                    <small class="small_label">List any other name you have used or been known by</small>
                                                </div>
                                                <label id="domestic_another_name_that_used-error" class="error text-danger" for="domestic_another_name_that_used"></label>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" value="<?php echo $domestic_state_residence;?>" class="form-control" id="domestic_state_residence" name="domestic_state_residence" data-error="#domestic_state_residence-error">
                                                    <small class="small_label">State of residence</small>
                                                </div>
                                                <label id="domestic_state_residence-error" class="error text-danger" for="domestic_state_residence"></label>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" value="<?php echo $domestic_lived_year;?>" class="form-control" id="domestic_lived_year" name="domestic_lived_year" data-error="#domestic_lived_year-error">
                                                    <small class="small_label">I have lived continuously in the U.S.A. since</small>
                                                </div>
                                                <label id="domestic_lived_year-error" class="error text-danger" for="domestic_lived_year"></label>
                                            </div>
                                        </div>   

                                        <div class="mt-5 mb-2 text-center title_string"><b>*******************************************************************************************</b></div>

                                        <h5 class="text-center"><strong>RESIDENCES (LAST 10 YEARS)</strong></h5>

                                        <div class="mt-3 mb-3 text-center title_string"><b>*******************************************************************************************</b></div>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group position-relative mb-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" value="<?php echo $residence_number_street;?>" class="form-control" id="residence_number_street" name="residence_number_street" required data-error="#residence_number_street-error">
                                                    <small class="small_label">Number &amp; Street</small>
                                                </div>
                                                <label id="residence_number_street-error" class="error text-danger" for="residence_number_street"></label>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group position-relative mb-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" value="<?php echo $residence_city;?>" class="form-control" id="residence_city" name="residence_city" required data-error="#residence_city-error">
                                                    <small class="small_label">City</small>
                                                </div>
                                                <label id="residence_city-error" class="error text-danger" for="residence_city"></label>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group position-relative mb-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" value="<?php echo $residence_from_date_to_date;?>" class="form-control" id="residence_from_date_to_date" name="residence_from_date_to_date" required data-error="#residence_from_date_to_date-error">
                                                    <small class="small_label">From (date) to (date)</small>
                                                </div>
                                                <label id="residence_from_date_to_date-error" class="error text-danger" for="residence_from_date_to_date"></label>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-md-4">
                                                <div class="form-group position-relative">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" value="<?php echo $second_residence_number_street;?>" class="form-control" id="second_residence_number_street" name="second_residence_number_street">
                                                    <small class="small_label">Number &amp; Street</small>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group position-relative">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" value="<?php echo $second_residence_city;?>" class="form-control" id="second_residence_city" name="second_residence_city">
                                                    <small class="small_label">City</small>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group position-relative">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" value="<?php echo $second_residence_from_date_to_date;?>" class="form-control" id="second_residence_from_date_to_date" name="second_residence_from_date_to_date">
                                                    <small class="small_label">From (date) to (date)</small>
                                                </div>
                                                <label id="second_residence_from_date_to_date-error" class="error text-danger error2" for="second_residence_from_date_to_date"></label>
                                            </div>
                                        </div>
                                        <div class="text-center mt-4 f14">(If more space is required, use reverse side of form)</div>
                                        <div class="mt-4 mb-2 text-center title_string"><b>*******************************************************************************************</b></div>

                                        <h5 class="text-center"><strong>OCCUPATIONS/BUSINESSES (LAST 10 YEARS)</strong></h5>

                                        <div class="mt-3 mb-3 text-center title_string"><b>*******************************************************************************************</b></div>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group position-relative mb-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" value="<?php echo $firm_or_business_name;?>" class="form-control" id="firm_or_business_name" name="firm_or_business_name" required data-error="#firm_or_business_name-error">
                                                    <small class="small_label">Firm or Business name</small>
                                                </div>
                                                <label id="firm_or_business_name-error" class="error text-danger" for="firm_or_business_name"></label>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group position-relative mb-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" value="<?php echo $business_address;?>" class="form-control" id="business_address" name="business_address" required data-error="#business_address-error">
                                                    <small class="small_label">Address</small>
                                                </div>
                                                <label id="business_address-error" class="error text-danger" for="business_address"></label>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group position-relative mb-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" value="<?php echo $business_from_date_to_date;?>" class="form-control" id="business_from_date_to_date" name="business_from_date_to_date" required data-error="#business_from_date_to_date-error">
                                                    <small class="small_label">From (date) to (date)</small>
                                                </div>
                                                <label id="business_from_date_to_date-error" class="error text-danger" for="business_from_date_to_date"></label>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-md-4">
                                                <div class="form-group position-relative">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" value="<?php echo $second_firm_or_business_name;?>" class="form-control" id="second_firm_or_business_name" name="second_firm_or_business_name">
                                                    <small class="small_label">Firm or Business name</small>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group position-relative">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" value="<?php echo $second_business_address;?>" class="form-control" id="second_business_address" name="second_business_address">
                                                    <small class="small_label">Address</small>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group position-relative">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" value="<?php echo $second_business_from_date_to_date;?>" class="form-control" id="second_business_from_date_to_date" name="second_business_from_date_to_date">
                                                    <small class="small_label">From (date) to (date)</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-5 mb-2 text-center title_string"><b>*******************************************************************************************</b></div>

                                        <h5 class="text-center"><strong>INFORMATION ABOUT THE PROPERTY</strong></h5>

                                        <div class="mt-3 mb-3 text-center title_string"><b>*******************************************************************************************</b></div>

                                        <div class="d-flex">
                                            <div class="me-3">
                                                Buyer intends to reside on the property in this transaction:  
                                            </div>
                                            <div class="me-3">
                                                <input type="radio" <?php echo ($is_buyer_intends == 'Yes') ? 'checked="checked"' : '';?> id="yesProperty" value="Yes" name="is_buyer_intends" required data-error="#is_buyer_intends-error">
                                                <label for="yesProperty">Yes</label>
                                            </div>
                                            <div>
                                                <input type="radio" <?php echo ($is_buyer_intends == 'No') ? 'checked="checked"' : '';?> id="noPorperty" value="No" name="is_buyer_intends">
                                                <label for="noPorperty">No</label>
                                            </div>
                                        </div>
                                        <label id="is_buyer_intends-error" class="error text-danger d-flex" for="is_buyer_intends"></label>

                                        <div class="mt-5 mb-2 text-center title_string"><b>*******************************************************************************************</b></div>

                                        <h5 class="text-center"><strong>Owner to complete the following items</strong></h5>

                                        <div class="mt-3 mb-3 text-center title_string"><b>*******************************************************************************************</b></div>

                                        <div class="form-group position-relative mb-3">
                                            <label for="" class="mb-2"><b></b></label>
                                            <input type="text" value="<?php echo $owner_street_address;?>" class="form-control" id="owner_street_address" name="owner_street_address" required data-error="#owner_street_address-error">
                                            <small class="small_label"> Street Address of Property in this transaction: </small>
                                        </div>
                                        <label id="owner_street_address-error" class="error text-danger d-flex" for="owner_street_address"></label>

                                        <div class="mt-4">
                                            The land is unimproved <input type="text" value="<?php echo $unimproved;?>" class="input_single w-small" id="unimproved" name="unimproved">; or improved with a structure of the following type:  A Single or 1-4 Family <input type="text" value="<?php echo $single_family;?>" class="input_single w-small" id="single_family" name="single_family"> Condo Unit <input type="text" value="<?php echo $condo_unit;?>" class="input_single w-small" id="condo_unit" name="condo_unit"> Other <input type="text" value="<?php echo $other;?>" class="input_single w-small" id="other" name="other"> 	
                                        </div>

                                        <div class="d-flex mt-3">
                                            <div class="me-3">
                                                Improvements, remodeling or repairs to this property have been made within the past six months: 
                                            </div>
                                            <div class="me-3">
                                                <input type="radio" <?php echo ($is_improvement == 'Yes') ? 'checked="checked"' : '';?> name="is_improvement" id="yesImprovements" value="Yes" required data-error="#is_improvement-error">
                                                <label for="yesImprovements">Yes</label>
                                            </div>
                                            <div>
                                                <input type="radio" <?php echo ($is_improvement == 'No') ? 'checked="checked"' : '';?> name="is_improvement" id="noImprovements" value="No">
                                                <label for="noImprovements">No</label>
                                            </div>
                                        </div>
                                        <label id="is_improvement-error" class="error text-danger d-flex" for="is_improvement"></label>

                                        <div class="d-flex mt-3">
                                            <div class="me-3">
                                                If yes, have all costs for labor and materials arising in connection therewith been paid in full?
                                            </div>
                                            <div class="me-3">
                                                <input type="radio" <?php echo ($is_materials == 'Yes') ? 'checked="checked"' : '';?> name="is_materials" id="yesmaterials" value="Yes" required data-error="#is_materials-error">
                                                <label for="yesmaterials">Yes</label>
                                            </div>
                                            <div>
                                                <input type="radio" <?php echo ($is_materials == 'No') ? 'checked="checked"' : '';?> name="is_materials" id="nomaterials" value="No">
                                                <label for="nomaterials">No</label>
                                            </div>
                                            
                                        </div>
                                        <label id="is_materials-error" class="error text-danger d-flex" for="is_materials"></label>

                                        <div class="mt-3">
                                            Any current loans on property? <input type="checkbox" <?php echo  ($is_loan == 'on') ? 'checked="checked"' : '';?> name="is_loan" id="is_loan">; If yes, complete the following:
                                        </div>

                                        <div class="mt-3 row">
                                            <div class="col-md-4">
                                                <div class="form-group position-relative mb-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" value="<?php echo $lender;?>" class="form-control" id="lender" name="lender" data-error="#lender-error">
                                                    <small class="small_label">Lender</small>
                                                </div>
                                                <label id="lender-error" class="error text-danger" for="lender"></label>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group position-relative mb-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" value="<?php echo $loan_amount;?>" class="form-control" id="loan_amount" name="loan_amount" data-error="#loan_amount-error">
                                                    <small class="small_label">Loan Amount</small>
                                                </div>
                                                <label id="loan_amount-error" class="error text-danger" for="loan_amount"></label>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group position-relative mb-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" value="<?php echo $loan_account;?>" class="form-control" id="loan_account" name="loan_account" data-error="#loan_account-error">
                                                    <small class="small_label">Loan Account #</small>
                                                </div>
                                                <label id="loan_account-error" class="error text-danger" for="loan_account"></label>
                                            </div>
                                        </div>

                                        <div class="mt-3 row">
                                            <div class="col-md-4">
                                                <div class="form-group position-relative">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" value="<?php echo $second_lender;?>" class="form-control" id="second_lender" name="second_lender">
                                                    <small class="small_label">Lender</small>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group position-relative">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" value="<?php echo $second_loan_amount;?>" class="form-control" id="second_loan_amount" name="second_loan_amount">
                                                    <small class="small_label">Loan Amount</small>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group position-relative">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" value="<?php echo $second_loan_account;?>" class="form-control" id="second_loan_account" name="second_loan_account">
                                                    <small class="small_label">Loan Account #</small>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mt-4">The undersigned declare, under penalty of perjury, that the foregoing is true and correct.</div>

                                        <div class="mt-3 row">
                                            <div class="col-md-6">
                                                Executed on <input type="text" value="<?php echo $executed_date;?>" class="input_single" id="executed_date" name="executed_date" required data-error="#executed_date-error">, <input type="text" value="<?php echo $executed_year;?>" class="input_single w-medium" id="executed_year" name="executed_year">
                                                <label id="executed_date-error" class="error text-danger d-flex" for="executed_date"></label>
                                            </div>
                                            <div class="col-md-6">
                                                at <input type="text" value="<?php echo $executed_time;?>" class="input_single" id="executed_time" name="executed_time" required data-error="#executed_time-error">
                                                <label id="executed_time-error" class="error text-danger d-flex" for="executed_time"></label>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mt-5">
                                                    Signature :    
                                                    <input type="text" value="<?php echo $signature;?>" class="input_single" id="signature" name="signature" required data-error="#signature-error">
                                                </div>
                                                <label id="signature-error" class="error text-danger" for="signature"></label>
                                            </div>
                                            <div class="col-md-6 text-md-end">
                                                <div class="mt-5">
                                                    Signature :    
                                                    <input type="text" value="<?php echo $second_signature;?>" class="input_single" id="second_signature" name="second_signature" required data-error="#second_signature-error">
                                                </div>
                                                <label id="second_signature-error" class="error text-danger" for="second_signature"></label>
                                            </div>
                                        </div>

                                        <p class="mt-4 text-center">
                                            (Note:  If applicable, both spouses/domestic partners must sign.)
                                            <strong class="d-block">THANK YOU</strong>
                                        </p>
                                    </div>
                                </div>
                            </div>

							<div class="accordion-item">
                                <h2 class="accordion-header" id="headingTwenty">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwenty" aria-expanded="true" aria-controls="collapseTwenty">
                                       (4) 593-C Form
                                    </button>
                                </h2>
                                <div id="collapseTwenty" class="accordion-collapse collapse" aria-labelledby="headingTwenty" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <div class="row text-center mb-4">
                                            <div class="col-md-3">
                                                TAXABLE YEAR
                                                <hr class="hr1 mt-0 mb-0">
                                                <h4 class="mt-0 mb-0 real_estate_title"><b><?php echo date('Y')?></b></h4>
                                                <hr class="hr1 mt-0 mb-0">
                                            </div>
                                            <div class="col-md-6">
                                                <h4 class="mt-30"><strong>Real Estate Withholding Statement</strong></h4>
                                            </div>
                                            <div class="col-md-3">
                                                CALIFORNIA FORM
                                                <hr class="hr1 mt-0 mb-0">
                                                <h4 class="mt-0 mb-0 real_estate_title"><b>593</b></h4>
                                                <hr class="hr1 mt-0 mb-0">
                                            </div>
                                        </div>
                                        <div class="row mb-4">
                                            <div class="col-md-3">
                                                <div>
                                                    <label for="amended">AMENDED: </label>
                                                    <input type="checkbox" <?php echo  ($is_amended == 'yes') ? 'checked="checked"' : '';?> id="is_amended" name="is_amended" value="yes">
                                                </div>
                                            </div>
                                            <div class="col-md-9 text-end">
                                                <div>
                                                    <label for="amended">Escrow or Exchange No.  </label>
                                                    <input type="input" class="input_single" readonly value="10257432-GLE-MP">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-4">
                                            <strong>Part I  Remitter Information</strong> &nbsp;
                                            <input type="checkbox" <?php echo (in_array('REEP', $remitter_info)) ? 'checked="checked"' : '';?> id="remitter1" name="remitter_info[]" value="REEP"> &nbsp;
                                            <label for="remitter1">REEP</label> &nbsp;
                                            <input type="checkbox" <?php echo (in_array('qualified_intermediary', $remitter_info)) ? 'checked="checked"' : '';?> id="remitter2" name="remitter_info[]" value="qualified_intermediary"> &nbsp;
                                            <label for="remitter2">Qualified Intermediary</label> &nbsp;
                                            <input type="checkbox" <?php echo (in_array('transferee', $remitter_info)) ? 'checked="checked"' : '';?> id="remitter3" name="remitter_info[]" value="transferee"> &nbsp;
                                            <label for="remitter3">Buyer/Transferee</label> &nbsp;
                                            <input type="checkbox" <?php echo (in_array('other', $remitter_info)) ? 'checked="checked"' : '';?> id="remitter4" name="remitter_info[]" value="other"> &nbsp;
                                            <label for="remitter4">Other</label> &nbsp;
                                            <input type="text" value="<?php echo $other_remitter_info;?>" class="input_single optional-input" id="other_remitter_info" name="other_remitter_info"> &nbsp;
                                        </div>
                                        <hr class="hr1 mb-4">
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3">
                                                    <input type="text" value="<?php echo $business_name;?>" class="form-control" value="Pacific Coast Title Company" id="business_name" name="business_name">
                                                    <small class="small_label">Business name</small>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <input type="checkbox" <?php echo (in_array('fein', $business_num)) ? 'checked="checked"' : '';?> id="fein" name="business_num[]" value="fein"> &nbsp;
                                                <label for="fein">FEIN</label> &nbsp;
                                                <input type="checkbox" <?php echo (in_array('corpNo', $business_num)) ? 'checked="checked"' : '';?> id="corpNo" name="business_num[]" value="corpNo"> &nbsp;
                                                <label for="corpNo">CA Corp no.</label> &nbsp;
                                                <input type="checkbox" <?php echo (in_array('sosNo', $business_num)) ? 'checked="checked"' : '';?> id="sosNo" name="business_num[]" value="sosNo"> &nbsp;
                                                <label for="sosNo">CA SOS file no.</label> &nbsp;<br>
                                                <strong>95-2569776</strong>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-4">
                                                <div class="form-group position-relative mb-3">
                                                    <input type="text" value="<?php echo $remitter_first_name;?>" class="form-control" id="remitter_first_name" name="remitter_first_name">
                                                    <small class="small_label">First name</small>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group position-relative mb-3">
                                                    <input type="text" value="<?php echo $remitter_initial_name;?>" class="form-control" id="remitter_initial_name" name="remitter_initial_name">
                                                    <small class="small_label">Initial</small>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group position-relative mb-3">
                                                    <input type="text" value="<?php echo $remitter_last_name;?>" class="form-control" id="remitter_last_name" name="remitter_last_name">
                                                    <small class="small_label">last name</small>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group position-relative mb-3">
                                                    <input type="text" value="<?php echo $remitter_ssn_or_itin;?>" class="form-control" id="remitter_ssn_or_itin" name="remitter_ssn_or_itin">
                                                    <small class="small_label">SSN or ITIN</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-12">
                                                <div class="form-group position-relative mb-3">
                                                    <input type="text" value="<?php echo $remitter_address;?>" class="form-control" value="516 Burchett St." id="remitter_address" name="remitter_address">
                                                    <small class="small_label">Address (apt./ste., room, PO box, or PMB no.) </small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-4">
                                                <div class="form-group position-relative mb-3">
                                                    <input type="text" value="<?php echo $remitter_city;?>" class="form-control" value="Glendale" id="remitter_city" name="remitter_city">
                                                    <small class="small_label">City (If you have a foreign address, see instructions.)</small>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group position-relative mb-3">
                                                    <input type="text" value="<?php echo $remitter_state;?>" class="form-control" value="CA" id="remitter_state" name="remitter_state">
                                                    <small class="small_label">State</small>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group position-relative mb-3">
                                                    <input type="text" value="<?php echo $remitter_zip_code;?>" class="form-control" value="91203" id="remitter_zip_code" name="remitter_zip_code"> 
                                                    <small class="small_label">ZIP code</small>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group position-relative mb-3">
                                                    <input type="text" value="<?php echo $remitter_telephone_num;?>" class="form-control" value="(818) 662-6700" id="remitter_telephone_num" name="remitter_telephone_num">
                                                    <small class="small_label">Telephone number</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-4">
                                            <strong>Part II   Seller/Transferor Information If a grantor or nongrantor trust, check the box that applies.</strong> &nbsp;
                                            <input type="checkbox" <?php echo (in_array('granter', $trust_types)) ? 'checked="checked"' : '';?> id="granter" name="trust_types[]" value="granter"> &nbsp;
                                            <label for="granter">Grantor</label> &nbsp;
                                            <input type="checkbox" <?php echo (in_array('nongranter', $trust_types)) ? 'checked="checked"' : '';?> id="nongranter" name="trust_types[]" value="nongranter"> &nbsp;
                                            <label for="nongranter">Nongrantor Trust</label>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-4">
                                                <div class="form-group position-relative mb-3">
                                                    <input type="text" value="<?php echo $transferor_first_name;?>" class="form-control" id="transferor_first_name" name="transferor_first_name">
                                                    <small class="small_label">First name</small>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group position-relative mb-3">
                                                    <input type="text" value="<?php echo $transferor_initial_name;?>" class="form-control" id="transferor_initial_name" name="transferor_initial_name">
                                                    <small class="small_label">Initial</small>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group position-relative mb-3">
                                                    <input type="text" value="<?php echo $transferor_last_name;?>" class="form-control" id="transferor_last_name" name="transferor_last_name">
                                                    <small class="small_label">last name</small>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group position-relative mb-3">
                                                    <input type="text" value="<?php echo $transferor_ssn_or_itin;?>" class="form-control" id="transferor_ssn_or_itin" name="transferor_ssn_or_itin">
                                                    <small class="small_label">SSN or ITIN</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-4">
                                                <div class="form-group position-relative mb-3">
                                                    <input type="text" value="<?php echo $transferor_spouse_first_name;?>" class="form-control optional-input" id="transferor_spouse_first_name" name="transferor_spouse_first_name">
                                                    <small class="small_label">Spouse's/RDP's first name (if jointly owned)</small>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group position-relative mb-3">
                                                    <input type="text" value="<?php echo $transferor_spouse_middle_name;?>" class="form-control optional-input" id="transferor_spouse_middle_name" name="transferor_spouse_middle_name">
                                                    <small class="small_label">Initial</small>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group position-relative mb-3">
                                                    <input type="text" value="<?php echo $transferor_spouse_last_name;?>" class="form-control optional-input" id="transferor_spouse_last_name" name="transferor_spouse_last_name">
                                                    <small class="small_label">last name</small>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group position-relative mb-3">
                                                    <input type="text" value="<?php echo $transferor_spouse_ssn_or_itin;?>" class="form-control optional-input" id="transferor_spouse_ssn_or_itin" name="transferor_spouse_ssn_or_itin">
                                                    <small class="small_label">Spouse's/RDP's SSN or ITIN (if jointly owned) </small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-3 align-items-center">
                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3">
                                                    <input type="text" value="<?php echo $nongrantor_trust_name;?>" class="form-control optional-input" id="nongrantor_trust_name" name="nongrantor_trust_name">
                                                    <small class="small_label">Business/Nongrantor Trust name (if applicable)</small>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <input type="checkbox" <?php echo (in_array('fein', $transferor_business_num)) ? 'checked="checked"' : '';?> id="fein1" name="transferor_business_num[]" value="fein"> &nbsp;
                                                <label for="fein1">FEIN</label> &nbsp;
                                                <input type="checkbox" <?php echo (in_array('corpNo', $transferor_business_num)) ? 'checked="checked"' : '';?> id="corpNo1" name="transferor_business_num[]" value="corpNo"> &nbsp;
                                                <label for="corpNo1">CA Corp no.</label> &nbsp;
                                                <input type="checkbox" <?php echo (in_array('sosNo', $transferor_business_num)) ? 'checked="checked"' : '';?> id="sosNo1" name="transferor_business_num[]" value="sosNo"> &nbsp;
                                                <label for="sosNo1">CA SOS file no.</label>
                                            </div>
                                        </div> 
                                        <div class="row mb-3">
                                            <div class="col-md-12">
                                                <div class="form-group position-relative mb-3">
                                                    <input type="text" value="<?php echo $transferor_address;?>" class="form-control" id="transferor_address" name="transferor_address">
                                                    <small class="small_label">Address (apt./ste., room, PO box, or PMB no.) </small>
                                                </div>
                                            </div>
                                        </div> 
                                        <div class="row mb-3">
                                            <div class="col-md-4">
                                                <div class="form-group position-relative mb-3">
                                                    <input type="text" value="<?php echo $transferor_city;?>" class="form-control" id="transferor_city" name="transferor_city">
                                                    <small class="small_label">City (If you have a foreign address, see instructions.)</small>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group position-relative mb-3">
                                                    <input type="text" value="<?php echo $transferor_state;?>" class="form-control" id="transferor_state" name="transferor_state">
                                                    <small class="small_label">State </small>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group position-relative mb-3">
                                                    <input type="text" value="<?php echo $transferor_zip_code;?>" class="form-control" id="transferor_zip_code" name="transferor_zip_code">
                                                    <small class="small_label">ZIP code</small>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group position-relative mb-3">
                                                    <input type="text" value="<?php echo $transferor_telephone_number;?>" class="form-control" id="transferor_telephone_number" name="transferor_telephone_number">
                                                    <small class="small_label">Telephone number</small>
                                                </div>
                                            </div>
                                        </div> 
                                        <div class="row mb-3">
                                            <div class="col-md-8">
                                                <div class="form-group position-relative mb-3">
                                                    <input type="text" id="transferor_property_address" name="transferor_property_address" class="form-control" value="Lots/APN: 210-021-30-00-1 and 210-021-29-00-9, Bakersfield, CA  93301 / APN: 210-021-29-00, 210-021-30-00 / Kern County">
                                                    <small class="small_label">Property address (provide street address, parcel number, and county) </small>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group position-relative mb-3">
                                                    <input type="text" value="<?php echo $ownership_percentage;?>" class="form-control" id="ownership_percentage" name="ownership_percentage">
                                                    <small class="small_label">Ownership percentage (%)</small>
                                                </div>
                                            </div>
                                        </div> 
                                        <div class="mb-4">
                                            <strong>Part III Certifications which fully exempt the sale from withholding</strong> (See instructions) <br>
                                            <small><strong>Determine whether you qualify for a full withholding exemption. Check all boxes that apply to the property being sold or transferred.
                                            </strong></small>
                                        </div>
                                        <ol>
                                            <li>
                                                <div class="d-flex align-items-start">
                                                    <input type="checkbox" <?php echo (in_array('certification_1', $certifications)) ? 'checked="checked"' : '';?> id="checkOne" class="mt-1 me-2" value="certification_1" name="certifications[]">
                                                    <label for="checkOne">The property qualifies as the seller's (or decedent's, if sold by the decedent's estate or trust) principal residence under Internal Revenue Code (IRC) Section 121.</label>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="d-flex align-items-start">
                                                    <input type="checkbox" <?php echo (in_array('certification_2', $certifications)) ? 'checked="checked"' : '';?> id="checkTwo" class="mt-1 me-2" value="certification_2" name="certifications[]">
                                                    <label for="checkTwo">The seller (or decedent, if sold by the decedent's estate or trust) last used the property as the seller's (decedent's) principal residence under IRC 121 without regard to the two-year time period.</label>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="d-flex align-items-start">
                                                    <input type="checkbox" <?php echo (in_array('certification_3', $certifications)) ? 'checked="checked"' : '';?> id="checkThree" class="mt-1 me-2" value="certification_3" name="certifications[]">
                                                    <label for="checkThree">The seller has a loss or zero gain for California income tax purposes on this sale. Complete Part VI, Computation on Side</label>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="d-flex align-items-start">
                                                    <input type="checkbox" <?php echo (in_array('certification_4', $certifications)) ? 'checked="checked"' : '';?> id="checkFour" class="mt-1 me-2" value="certification_4" name="certifications[]">
                                                    <label for="checkFour">The property is compulsorily or involuntarily converted, and the seller intends to acquire property that will qualify for nonrecognition of gain under IRC Section 1033.</label>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="d-flex align-items-start">
                                                    <input type="checkbox" <?php echo (in_array('certification_5', $certifications)) ? 'checked="checked"' : '';?> id="checkFive" class="mt-1 me-2" value="certification_5" name="certifications[]">
                                                    <label for="checkFive">The transfer qualifies for nonrecognition treatment under IRC Section 351 (property transferred to a corporationcontrolled by the transferor) or IRC Section 721 (property contributed to a partnership in exchange for a partnership interest).</label>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="d-flex align-items-start">
                                                    <input type="checkbox" <?php echo (in_array('certification_6', $certifications)) ? 'checked="checked"' : '';?> id="checkSix" class="mt-1 me-2" value="certification_6" name="certifications[]">
                                                    <label for="checkSix">The seller is a corporation (or a limited liability company (LLC) classified as a corporation for federal and California income tax purposes) that is either qualified through the California Secretary of State or has a permanent place of business in California.</label>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="d-flex align-items-start">
                                                    <input type="checkbox" <?php echo (in_array('certification_7', $certifications)) ? 'checked="checked"' : '';?> id="checkSeven" class="mt-1 me-2" value="certification_7" name="certifications[]">
                                                    <label for="checkSeven">The seller is a California partnership or qualified to do business in California (or an LLC that is classified as a partnership for federal and California income tax purposes that is not a single member LLC that is disregarded for federal and California income tax purposes).</label>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="d-flex align-items-start">
                                                    <input type="checkbox" <?php echo (in_array('certification_8', $certifications)) ? 'checked="checked"' : '';?> id="checkEight" class="mt-1 me-2" value="certification_8" name="certifications[]">
                                                    <label for="checkEight">The seller is a tax-exempt entity under California or federal law.</label>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="d-flex align-items-start">
                                                    <input type="checkbox" <?php echo (in_array('certification_9', $certifications)) ? 'checked="checked"' : '';?> id="checkNine" class="mt-1 me-2" value="certification_9" name="certifications[]">
                                                    <label for="checkNine">The seller is an insurance company, individual retirement account, qualified pension/profit sharing plan, or charitable remainder trust. </label>
                                                </div>
                                            </li>
                                        </ol>
                                        <p>
                                            If you checked one or more boxes in line 1 through line 9, withholding is not required. Do not complete Part IV. Go to Side 3, complete the perjury statement and sign. Provide Sides 1-3 to the remitter before the close of escrow or exchange transaction to submit to the Franchise Tax Board.
                                        </p>
                                        <div class="mb-4">
                                            <strong>Part IV Certifications that may partially or fully exempt the sale from withholding or if no exemptions apply
                                            </strong> (See instructions) <br>
                                            <small>Determine whether you qualify for a full, partial, or no withholding exemption. Check all boxes that apply to the property being sold or transferred.</small>
                                        </div>
                                        <ol start="10">
                                            <li>
                                                <div class="d-flex align-items-start">
                                                    <input type="checkbox" <?php echo (in_array('certification_10', $certifications)) ? 'checked="checked"' : '';?> id="checkTen" class="mt-1 me-2" value="certification_10" name="certifications[]">
                                                    <label for="checkTen">The transfer qualifies as either a simultaneous or deferred like-kind exchange under IRC Section 1031. See instructions for Form 593, Part IV.</label>
                                                </div>
                                            </li>                                            
                                            <li>
                                                <div class="d-flex align-items-start">
                                                    <input type="checkbox" <?php echo (in_array('certification_11', $certifications)) ? 'checked="checked"' : '';?> id="checkEleven" class="mt-1 me-2" value="certification_11" name="certifications[]">
                                                    <label for="checkEleven">The transfer of this property is an installment sale where the buyer must withhold on the principal portion of each installment payment. Copy of the promissory note is attached at the close of escrow. Complete Part V, Buyer/Transferee Information on Side 2. Withholding may be required.</label>
                                                </div>
                                            </li>                                            
                                            <li>
                                                <div class="d-flex align-items-start">
                                                    <input type="checkbox" <?php echo (in_array('certification_12', $certifications)) ? 'checked="checked"' : '';?> id="checkTwelve" class="mt-1 me-2" value="certification_12" name="certifications[]">
                                                    <label for="checkTwelve">No exemptions apply. Check this box if the exemptions in Part III or Part IV, line 10 and 11, do not apply. Remitter must complete Part VII, Escrow or Exchange Information, on Side 3 for amounts to withhold. Withholding is required.</label>
                                                </div>
                                            </li>
                                        </ol>
                                        <div class="row mb-3">
                                            <div class="col-md-8">
                                                <div class="form-group position-relative mb-3">
                                                    <input type="text" value="<?php echo $remitter_name;?>" class="form-control" value="Pacific Coast Title Company" id="remitter_name" name="remitter_name">
                                                    <small class="small_label">Remitter name</small>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group position-relative mb-3">
                                                    <input type="text" value="<?php echo $remitter_ssn_fein;?>" class="form-control" value="95-2569776" id="remitter_ssn_fein" name="remitter_ssn_fein">
                                                    <small class="small_label">SSN, ITIN, FEIN, CA corp no., or CA SOS file no.</small>
                                                </div>
                                            </div>
                                        </div>
										<div class="part_5_parent">
											<div class="mb-4">
												<strong>Part V   Buyer/Transferee Information</strong><br>
												<small><strong>Complete this part if you checked box 11 in Part IV for an installment agreement.
												</strong></small>
											</div>

											
											<div class="row mb-3">
												<div class="col-md-4">
													<div class="form-group position-relative mb-3">
														<input type="text" value="<?php echo $transferee_first_name;?>" class="form-control" id="transferee_first_name" name="transferee_first_name">
														<small class="small_label">First name</small>
													</div>
												</div>
												<div class="col-md-2">
													<div class="form-group position-relative mb-3">
														<input type="text" value="<?php echo $transferee_initial_name;?>" class="form-control" id="transferee_initial_name" name="transferee_initial_name">
														<small class="small_label">Initial</small>
													</div>
												</div>
												<div class="col-md-4">
													<div class="form-group position-relative mb-3">
														<input type="text" value="<?php echo $transferee_last_name;?>" class="form-control" id="transferee_last_name" name="transferee_last_name">
														<small class="small_label">last name</small>
													</div>
												</div>
												<div class="col-md-2">
													<div class="form-group position-relative mb-3">
														<input type="text" value="<?php echo $transferee_ssn_or_itin;?>" class="form-control" id="transferee_ssn_or_itin" name="transferee_ssn_or_itin">
														<small class="small_label">SSN or ITIN</small>
													</div>
												</div>
											</div>
											<div class="row mb-3">
												<div class="col-md-4">
													<div class="form-group position-relative mb-3">
														<input type="text" value="<?php echo $transferee_spouse_first_name;?>" class="form-control optional-input" id="transferee_spouse_first_name" name="transferee_spouse_first_name">
														<small class="small_label">Spouse's/RDP's first name (if jointly owned)</small>
													</div>
												</div>
												<div class="col-md-2">
													<div class="form-group position-relative mb-3">
														<input type="text" value="<?php echo $transferee_spouse_initial_name;?>" class="form-control optional-input" id="transferee_spouse_initial_name" name="transferee_spouse_initial_name">
														<small class="small_label">Initial</small>
													</div>
												</div>
												<div class="col-md-2">
													<div class="form-group position-relative mb-3">
														<input type="text" value="<?php echo $transferee_spouse_last_name;?>" class="form-control optional-input" id="transferee_spouse_last_name" name="transferee_spouse_last_name">
														<small class="small_label">last name</small>
													</div>
												</div>
												<div class="col-md-4">
													<div class="form-group position-relative mb-3">
														<input type="text" value="<?php echo $transferee_spouse_ssn_or_itin;?>" class="form-control optional-input" id="transferee_spouse_ssn_or_itin" name="transferee_spouse_ssn_or_itin">
														<small class="small_label">Spouse's/RDP's SSN or ITIN</small>
													</div>
												</div>
											</div>
											<div class="row mb-3 align-items-center">
												<div class="col-md-6">
													<div class="form-group position-relative mb-3">
														<input type="text" value="<?php echo $transferee_nongrantor_trust_name;?>" class="form-control optional-input" id="transferee_nongrantor_trust_name" name="transferee_nongrantor_trust_name">
														<small class="small_label">Business/Nongrantor Trust name (if applicable)</small>
													</div>
												</div>
												<div class="col-md-6">
													<input type="checkbox" <?php echo (in_array('fein', $transferee_business_num)) ? 'checked="checked"' : '';?> id="fein2" name="transferee_business_num[]" value="fein"> &nbsp;
													<label for="fein2">FEIN</label> &nbsp;
													<input type="checkbox" <?php echo (in_array('corpNo2', $transferee_business_num)) ? 'checked="checked"' : '';?> id="corpNo2" name="transferee_business_num[]" value="corpNo2"> &nbsp;
													<label for="corpNo2">CA Corp no.</label> &nbsp;
													<input type="checkbox" <?php echo (in_array('sosNo2', $transferee_business_num)) ? 'checked="checked"' : '';?> id="sosNo2" name="transferee_business_num[]" value="sosNo2"> &nbsp;
													<label for="sosNo2">CA SOS file no.</label>
												</div>
											</div> 
											<div class="row mb-3">
												<div class="col-md-12">
													<div class="form-group position-relative mb-3">
														<input type="text" value="<?php echo $transferee_address;?>" class="form-control" id="transferee_address" name="transferee_address">
														<small class="small_label">Address (apt./ste., room, PO box, or PMB no.) </small>
													</div>
												</div>
											</div> 
											<div class="row mb-3">
												<div class="col-md-4">
													<div class="form-group position-relative mb-3">
														<input type="text" value="<?php echo $transferee_city;?>" class="form-control" id="transferee_city" name="transferee_city">
														<small class="small_label">City (If you have a foreign address, see instructions.)</small>
													</div>
												</div>
												<div class="col-md-2">
													<div class="form-group position-relative mb-3">
														<input type="text" value="<?php echo $transferee_state;?>" class="form-control" id="transferee_state" name="transferee_state">
														<small class="small_label">State </small>
													</div>
												</div>
												<div class="col-md-3">
													<div class="form-group position-relative mb-3">
														<input type="text" value="<?php echo $transferee_zip_code;?>" class="form-control" id="transferee_zip_code" name="transferee_zip_code">
														<small class="small_label">ZIP code</small>
													</div>
												</div>
												<div class="col-md-3">
													<div class="form-group position-relative mb-3">
														<input type="text" value="<?php echo $transferee_telephone_number;?>" class="form-control" id="transferee_telephone_number" name="transferee_telephone_number">
														<small class="small_label">Telephone number</small>
													</div>
												</div>
											</div> 
											<div class="row mb-3">
												<div class="col-md-3">
													<div class="form-group position-relative mb-3">
														<input type="text" value="<?php echo $principal_amount_of_promissory_note;?>" class="form-control" id="principal_amount_of_promissory_note" name="principal_amount_of_promissory_note">
														<small class="small_label">Principal Amount of Promissory Note</small>
													</div>
												</div>
												<div class="col-md-3">
													<div class="form-group position-relative mb-3">
														<input type="text" value="<?php echo $installment_amount;?>" class="form-control" id="installment_amount" name="installment_amount">
														<small class="small_label">Installment Amount</small>
													</div>
												</div>
												<div class="col-md-2">
													<div class="form-group position-relative mb-3">
														<input type="text" value="<?php echo $principal_interrest_rate;?>" class="form-control" id="principal_interrest_rate" name="principal_interrest_rate">
														<small class="small_label">PInterest Rate (%)</small>
													</div>
												</div>
												<div class="col-md-4">
													<div class="form-group position-relative mb-3">
														<input type="text" value="<?php echo $repayment_period;?>" class="form-control" id="repayment_period" name="repayment_period">
														<small class="small_label">Repayment Period (Number of months)</small>
													</div>
												</div>
											</div> 
										</div>
                                        <div class="mb-4">
                                            <strong>Buyer's/Transferee's Acknowledgment to Withhold</strong><br>
                                            <small><strong>Read the "Buyer/Transferee" Information below.</strong></small>
                                        </div>
                                        <div class="notice_box mb-3">
                                            I acknowledge that I am required to withhold on the principal portion of each installment payment to the seller/transferor for the above shown California real property either at the rate of 3 1/3% (.0333) of the sales price or the Alternative Withholding Calculation, as specified by the seller/transferor on Form 593, Real Estate Withholding Statement, of the principal portion of each installment payment. I will complete Form 593 for the principal portion of each installment payment and send one copy of each to the Franchise Tax Board (FTB) along with Form 593-V, Payment Voucher for Real Estate Withholding, the withholding payment, and give one copy of Form 593 to the seller/transferor. I will send each withholding payment to the FTB by the 20th day of the month following the month of the installment payment. If the terms of the installment sale, promissory note, or payment schedule change, I will promptly inform the FTB. I understand that the FTB may review relevant escrow documents to ensure withholding compliance. I also understand that I am subject to withholding penalties if I do not withhold on the principal portion of each installment payment and do not send the withholding along with Form 593 to the FTB by the due date, or if I do not send one copy of Form 593 to the seller/transferor by the due date. Go to Side 3, complete the perjury statement and sign.
                                        </div>
										<div class="part_6_parent">
											<div class="mb-4">
												<strong>Part VI Computation</strong><br>
												<small><strong>Complete this part if you checked and certified box 3 in Part III, or to calculate an alternative withholding calculation amount.</strong></small>
											</div>

											<ol start="13">
												<li>
													<div class="d-flex flex-xs-wrap mb-2">
														<b>Selling price</b>&nbsp;<input type="text" value="<?php echo $selling_price;?>" class="input_single flex1" id="selling_price" name="selling_price">
													</div>
												</li>
												<li>
													<div class="d-flex flex-xs-wrap mb-2">
														Selling expenses&nbsp;<input type="text" value="<?php echo $selling_expenses;?>" class="input_single flex1" id="selling_expenses" name="selling_expenses">
													</div>
												</li>
												<li>
													<div class="d-flex flex-xs-wrap mb-2">
													<span><b>Amount realized.</b> Subtract line 14 from line</span>&nbsp;<input type="text" value="<?php echo $amount_realized;?>" class="input_single flex1" id="amount_realized" name="amount_realized">
													</div>
												</li>
												<li>
													<div class="d-flex flex-xs-wrap mb-2">
														Enter the price you paid to purchase the property (see instructions, How to Figure Your Basis.)&nbsp;<input type="text" value="<?php echo $paid_price_to_purchase;?>" class="input_single flex1" id="paid_price_to_purchase" name="paid_price_to_purchase">
													</div>
												</li>
												<li>
													<div class="d-flex flex-xs-wrap mb-2">
														Seller/Transferor-paid points&nbsp;<input type="text" value="<?php echo $seller_paid_months;?>" class="input_single flex1" id="seller_paid_months" name="seller_paid_months">
													</div>
												</li>
												<li>
													<div class="d-flex flex-xs-wrap mb-2">
														Depreciation&nbsp;<input type="text" value="<?php echo $seller_depreciation;?>" class="input_single flex1" id="seller_depreciation" name="seller_depreciation">
													</div>
												</li>
												<li>
													<div class="d-flex flex-xs-wrap mb-2">
														Other decreases to basis&nbsp;<input type="text" value="<?php echo $other_decreases;?>" class="input_single flex1" id="other_decreases" name="other_decreases">
													</div>
												</li>
												<li>
													<div class="d-flex flex-xs-wrap mb-2">
														Total decreases to basis. Add line 17 through line&nbsp;<input type="text" value="<?php echo $total_decrease_line_17;?>" class="input_single flex1" id="total_decrease_line_17" name="total_decrease_line_17">
													</div>
												</li>
												<li>
													<div class="d-flex flex-xs-wrap mb-2">
														Subtract line 20 from line&nbsp;<input type="text" value="<?php echo $subtract_line_20;?>" class="input_single flex1" id="subtract_line_20" name="subtract_line_20">
													</div>
												</li>
												<li>
													<div class="d-flex flex-xs-wrap mb-2">
														Cost of additions and improvements&nbsp;<input type="text" value="<?php echo $signature_cost_of_additioncorporate_officer_date;?>" class="input_single flex1" id="cost_of_addition" name="cost_of_addition">
													</div>
												</li>
												<li>
													<div class="d-flex flex-xs-wrap mb-2">
														Other increases to basis&nbsp;<input type="text" value="<?php echo $other_increase_to_basis;?>" class="input_single flex1" id="other_increase_to_basis" name="other_increase_to_basis">
													</div>
												</li>
												<li>
													<div class="d-flex flex-xs-wrap mb-2">
														Total increases to basis. Add line 22 and line&nbsp;<input type="text" value="<?php echo $total_decrease_line_22;?>" class="input_single flex1" id="total_decrease_line_22" name="total_decrease_line_22">
													</div>
												</li>
												<li>
													<div class="d-flex flex-xs-wrap mb-2">
														Adjusted basis. Add line 21 and line&nbsp;<input type="text" value="<?php echo $adjusted_basis_line_21;?>" class="input_single flex1" id="adjusted_basis_line_21" name="adjusted_basis_line_21">
													</div>
												</li>
												<li>
													<div class="d-flex flex-xs-wrap mb-2">
														Enter any suspended passive activity losses from this property&nbsp;<input type="text" value="<?php echo $suspended_passive_lossed;?>" class="input_single flex1" id="suspended_passive_lossed" name="suspended_passive_lossed">
													</div>
												</li>
												<li>
													<div class="d-flex flex-xs-wrap mb-2">
														Add line 25 and line&nbsp;<input type="text" value="<?php echo $add_line_25;?>" class="input_single flex1" id="add_line_25" name="add_line_25">
													</div>
												</li>
												<li>
													<div class="d-flex flex-xs-wrap mb-2 align-items-end">
														<span><b>Estimated gain or loss on sale.</b> Subtract line 27 from line 15 and enter the amount here
															If you have a loss or zero gain, skip lines 29 and 30. Certify on Side 3. Withholding is not required.
															If you have a gain, go to line 29 to calculate your withholding</span>&nbsp;<input type="text" value="<?php echo $estimated_gain_or_loss;?>" class="input_single flex1" id="estimated_gain_or_loss" name="estimated_gain_or_loss">
													</div>
												</li>
											</ol>
											<div class="row mb-3">
												<div class="col-md-8">
													<div class="form-group position-relative mb-3">
														<input type="text" value="<?php echo $remitter_name_2;?>" class="form-control" value="Pacific Coast Title Company" id="remitter_name_2" name="remitter_name_2">
														<small class="small_label">Remitter name</small>
													</div>
												</div>
												<div class="col-md-4">
													<div class="form-group position-relative mb-3">
														<input type="text" value="<?php echo $remitter_ssn_itin_fein_2;?>" class="form-control" value="95-2569776" id="remitter_ssn_itin_fein_2" name="remitter_ssn_itin_fein_2">
														<small class="small_label">SSN, ITIN, FEIN, CA corp no., or CA SOS file no.</small>
													</div>
												</div>
											</div>
											<ol start="29">
												<li>
													<div class="d-flex flex-xs-wrap mb-2 align-items-end">
														<span>
														<div><b>Alternative withholding calculation amount.</b> Check the applicable box for the filing type.</div>
														<input type="checkbox" <?php echo (in_array('individual_12.3', $calculation_amount)) ? 'checked="checked"' : '';?> class="me-2" id="cal1" name="calculation_amount[]" value="individual_12.3">
														<label for="cal1">Individual 12.3%</label>&nbsp;
														<input type="checkbox" <?php echo (in_array('corporation_8.84', $calculation_amount)) ? 'checked="checked"' : '';?> class="me-2" id="cal2" name="calculation_amount[]" value="corporation_8.84">
														<label for="cal2">Corporation 8.84%</label>&nbsp;
														<input type="checkbox" <?php echo (in_array('financial_corporation_10.84', $calculation_amount)) ? 'checked="checked"' : '';?> class="me-2" id="cal3" name="calculation_amount[]" value="financial_corporation_10.84">
														<label for="cal3">Bank and Financial Corporation 10.84%</label>&nbsp;
														<input type="checkbox" <?php echo (in_array('trust_12.3', $calculation_amount)) ? 'checked="checked"' : '';?> class="me-2" id="cal4" name="calculation_amount[]" value="trust_12.3">
														<label for="cal4">Trust 12.3%</label>&nbsp;
														<input type="checkbox" <?php echo (in_array('non_california_12.3', $calculation_amount)) ? 'checked="checked"' : '';?> class="me-2" id="cal5" name="calculation_amount[]" value="non_california_12.3">
														<label for="cal5">Non-California Partnership 12.3%</label>&nbsp;
														<input type="checkbox" <?php echo (in_array('s_corporation_13.8', $calculation_amount)) ? 'checked="checked"' : '';?> class="me-2" id="cal6" name="calculation_amount[]" value="s_corporation_13.8">
														<label for="cal6">S Corporation 13.8%</label>&nbsp;
														<input type="checkbox" <?php echo (in_array('financial_s_corporation_15.8', $calculation_amount)) ? 'checked="checked"' : '';?> class="me-2" id="cal7" name="calculation_amount[]" value="financial_s_corporation_15.8">
														<label for="cal7">Financial S Corporation 15.8%</label>
														Multiply the amount on line 28 by the tax rate for the filing type selected above and enter the amount here. This is the alternative withholding calculation amount. If you elect the alternative withholding calculation amount, then check the
														appropriate box on line 36, Boxes B-H, and enter the amount on line 37</span>&nbsp;<input type="text" value="<?php echo $calculation_amount_value;?>" class="input_single flex1 optional-input" id="calculation_amount_value" name="calculation_amount_value">
													</div>
												</li>
												<li>
													<div class="d-flex flex-xs-wrap mb-2 align-items-end">
														<span>
															<b>Sales price withholding amount.</b> Multiply the selling price on line 13 by 3 1/3% (.0333).
															This is the <b>sales price withholding amount,</b> If you select the sales price withholding amount, check box A on
															line 36 below and enter the amount on line 37
														</span>&nbsp;<input type="text" value="<?php echo $sales_price_withholding_amount;?>" class="input_single flex1 optional-input" id="sales_price_withholding_amount" name="sales_price_withholding_amount">
													</div>
												</li>
											</ol>
										</div>
                                        <div class="mb-4"><strong>Part VII Escrow or Exchange Information</strong></div>
                                        <ol start="31">
                                            <li>
                                                <div class="d-flex flex-xs-wrap mb-2">
                                                    <span>Escrow or Exchange Number</span>&nbsp;<input type="text" class="input_single flex1" value="10257432-GLE-MP" id="escrow_exchange_number" name="escrow_exchange_number">
                                                </div>
                                            </li>
                                            <li>
                                                <div class="d-flex flex-xs-wrap mb-2">
                                                    <span>Date of Transfer, Exchange Completion, Failed Exchange, or Installment Payment...........(mm/dd/yyyy)</span>&nbsp;<input type="text" value="<?php echo $date_of_transfer;?>" class="input_single flex1" id="date_of_transfer" name="date_of_transfer">
                                                </div>
                                            </li>
                                            <li>
                                                <div class="d-flex flex-xs-wrap mb-2">
                                                    <span>Sales Price, Failed Exchange, or Boot Amount $ <input type="text" value="<?php echo $boot_amount;?>" class="input_single w-small optional-input" id="boot_amount" name="boot_amount"> x Ownership Percentage  <input type="text" value="<?php echo $exchange_ownership_percentage_from;?>" class="input_single w-small optional-input" id="exchange_ownership_percentage_from" name="exchange_ownership_percentage_from"> , <input type="text" value="<?php echo $exchange_ownership_percentage_to;?>" class="input_single w-small optional-input" id="exchange_ownership_percentage_to" name="exchange_ownership_percentage_to">%</span>&nbsp;<input type="text" value="<?php echo $exchange_ownership_amount;?>" class="input_single flex1 optional-input" id="exchange_ownership_amount" name="exchange_ownership_amount">
                                                </div>
                                            </li>
                                            <li>
                                                <div class="d-flex flex-xs-wrap mb-2">
                                                    <span>Amount that should have been withheld</span>&nbsp;<input type="text" value="<?php echo $amount_withheld_from;?>" class="input_single flex1" id="amount_withheld_from" name="amount_withheld_from">,<input type="text" value="<?php echo $amount_withheld_to;?>" class="input_single flex1" id="amount_withheld_to" name="amount_withheld_to">
                                                </div>
                                            </li>
                                            <li>
                                                <div class="d-flex flex-xs-wrap mb-2">
                                                    <span>
                                                        <div class="mb-2">Type of Transaction (Check One Only): </div>
                                                        <label>
                                                            <b>A</b> <input type="radio" <?php echo ($transaction == 'conventional_sale') ? 'checked="checked"' : '';?> class="square_radio mb-2" name="transaction" value="conventional_sale" id="transaction1">
                                                            <label for="transaction1">Conventional Sale/Transfer</label>&nbsp;
                                                            <b>C</b> <input type="radio" <?php echo ($transaction == 'boot') ? 'checked="checked"' : '';?> class="square_radio" name="transaction" value="boot"  id="transaction2">
                                                            <label for="transaction2">Boot</label>&nbsp;
                                                            <b>E</b> <input type="radio" <?php echo ($transaction == 'cash_poor') ? 'checked="checked"' : '';?> class="square_radio" name="transaction" value="cash_poor"  id="transaction3">
                                                            <label for="transaction3">Cash Poor</label><br>
                                                            <b>B</b> <input type="radio" <?php echo ($transaction == 'installment_sale_payment') ? 'checked="checked"' : '';?> class="square_radio" name="transaction" value="installment_sale_payment"  id="installment_sale_payment">
                                                            <label for="transaction4">Installment Sale Payment</label>&nbsp;
                                                            <b>D</b> <input type="radio" <?php echo ($transaction == 'failed_exchange') ? 'checked="checked"' : '';?> class="square_radio" name="transaction" value="failed_exchange"  id="failed_exchange">
                                                            <label for="transaction5">Failed Exchange</label>&nbsp;
                                                        </label>
                                                    </span>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="d-flex-flex-xs-wrap mb-2">
                                                    <span>
                                                        <div>
                                                            Withholding Calculation (Check One Only):
                                                        </div>
                                                        <label>
                                                            <b>Sales Price Method</b><br>
                                                            <b>A</b> <input type="radio" <?php echo ($with_holding == 'sales_price_boot') ? 'checked="checked"' : '';?> class="square_radio" name="with_holding" id="sales_price_boot" value="sales_price_boot">
                                                            <label for="Withholding1">3 1/3% (.0333) x Sales Price, Boot, or Installment Sale Payment</label><br>
                                                            <b>Alternative Withholding Calculation Election</b><br>
                                                            <b>B</b> <input type="radio" <?php echo ($with_holding == 'individual_12.3') ? 'checked="checked"' : '';?> class="square_radio" name="with_holding" id="individual_12.3" value="individual_12.3">
                                                            <label for="Withholding2">Individual 12.3% x Gain on Sale</label>&nbsp;
                                                            <b>F</b> <input type="radio" <?php echo ($with_holding == 's_corporation_13.8') ? 'checked="checked"' : '';?> class="square_radio" name="with_holding" id="s_corporation_13.8" value="s_corporation_13.8">
                                                            <label for="Withholding3">S Corporation 13.8% x Gain on Sale</label><br>
                                                            <b>C</b> <input type="radio" <?php echo ($with_holding == 'non_california_12.3') ? 'checked="checked"' : '';?> class="square_radio" name="with_holding" id="non_california_12.3" value="non_california_12.3">
                                                            <label for="Withholding4">Non-California Partnership 12.3% x Gain on Sale </label>&nbsp;
                                                            <b>G</b> <input type="radio" <?php echo ($with_holding == 'financial_s_corporation_15.8') ? 'checked="checked"' : '';?> class="square_radio" name="with_holding" id="financial_s_corporation_15.8" value="financial_s_corporation_15.8">
                                                            <label for="transaction5">Financial S Corporation 15.8% x Gain on Sale</label><br>
                                                            <b>D</b> <input type="radio" <?php echo ($with_holding == 'corporation_8.84') ? 'checked="checked"' : '';?> class="square_radio" name="with_holding" id="corporation_8.84" value="corporation_8.84">
                                                            <label for="Withholding6">Corporation 8.84% x Gain on Sale</label>&nbsp;
                                                            <b>H</b> <input type="radio" <?php echo ($with_holding == 'trust_12.3') ? 'checked="checked"' : '';?> class="square_radio" name="with_holding" id="trust_12.3" value="trust_12.3">
                                                            <label for="Withholding7">Trust 12.3% x Gain on Sale</label><br>
                                                            <b>E</b> <input type="radio" <?php echo ($with_holding == 'bank_and_financial_10.84') ? 'checked="checked"' : '';?> class="square_radio" name="with_holding" id="bank_and_financial_10.84" value="bank_and_financial_10.84">
                                                            <label for="Withholding8">Bank and Financial Corp. 10.84% x Gain on Sale</label>
                                                        </label>
                                                    </span>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="d-flex flex-xs-wrap mb-2">
                                                    <span>Amount Withheld from this Seller/Transferor</span>&nbsp;<input type="text" value="<?php echo $amount_withheld;?>" class="input_single flex1" id="amount_withheld" name="amount_withheld">
                                                </div>
                                            </li>
                                        </ol>
                                        <div class="notice_box mb-3">
                                            <strong>Title and escrow persons, and exchange accommodators are not authorized to provide legal or accounting advice for purposes of determining withholding amounts. Transferors are strongly encouraged to consult with a competent tax professional for this purpose.</strong>
                                        </div>
                                        <div class="mb-3">
                                            Our privacy notice can be found in annual tax booklets or online. Go to <strong><a target="_blank" href="https://www.ftb.ca.gov/your-rights/privacy/index.html?WT.mc_id=akvPrivacy">ftb.ca.gov/privacy</a></strong> to learn about our privacy policy statement, or go to <strong><a href="https://www.ftb.ca.gov/forms/">ftb.ca.gov/forms</a></strong> and search for <strong>1131</strong> to locate FTB 1131 EN-SP, Franchise Tax Board Privacy Notice on Collection. To request this notice by mail, call 800.338.0505 and enter form code <strong>948</strong> when instructed.
                                        </div>
                                        <div class="mb-3">
                                            <b>Perjury Statement</b><br>
                                            Under penalties of perjury, I hereby certify that the information provided above is, to the best of my knowledge, true and correct. I further certify that:<br>
                                            <div class="my-2">
                                                Check the applicable box(s):
                                            </div>
                                            <div class="d-flex align-items-start mb-2">
                                                <input type="checkbox" <?php echo (in_array('sale1', $perjury)) ? 'checked="checked"' : '';?> class="mt-1 me-2" id="sale1" name="perjury[]" value="sale1">
                                                <label for="sale1">The sale is fully exempt from withholding as indicated by a check mark(s) in Part III.</label>
                                            </div>
                                            <div class="d-flex align-items-start mb-2">
                                                <input type="checkbox" <?php echo (in_array('sale2', $perjury)) ? 'checked="checked"' : '';?> class="mt-1 me-2" id="sale2" name="perjury[]" value="sale2">
                                                <label for="sale2">The sale is fully or partially exempt from withholding as indicated by a check mark(s) in Part IV, box 10 or 11.</label>
                                            </div>
                                            <div class="d-flex align-items-start mb-2">
                                                <input type="checkbox" <?php echo (in_array('sale3', $perjury)) ? 'checked="checked"' : '';?> class="mt-1 me-2" id="sale3" name="perjury[]" value="sale3">
                                                <label for="sale3">The seller has elected the Alternative Withholding Calculation as indicated by a check mark in Part VII, line 36 (B-H).</label>
                                            </div>
                                            <div class="d-flex align-items-start mb-2">
                                                <input type="checkbox" <?php echo (in_array('sale4', $perjury)) ? 'checked="checked"' : '';?> checked class="mt-1 me-2" id="sale4" name="perjury[]" value="sale4">
                                                <label for="sale4">The buyer/transferee understands and accepts the withholding requirements as stated on the Buyer's/Transferee's Acknowledgment to Withhold
                                                    in Part V. The buyer/transferee should only check this box when involved in an installment sale.</label>
                                            </div>
                                            <div class="d-flex align-items-start mb-2">
                                                <input type="checkbox" <?php echo (in_array('sale5', $perjury)) ? 'checked="checked"' : '';?> class="mt-1 me-2" id="sale5" name="perjury[]" value="sale5">
                                                <label for="sale5">The Remitter (Qualified Intermediary) acknowledges this is a cash poor transaction as indicated by a check mark in Part VII, line 35, box E</label>
                                            </div>
                                        </div>
                                        <hr class="hr1">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="f32 mb-2">
                                                    <b>Sign Here</b>
                                                </div>
                                                <small>
                                                    It is unlawful to forge
                                                    a spouse's/RDP's
                                                    signature.
                                                </small>
                                            </div>
                                            <div class="col-md-9">
                                                <div class="row mb-3 mt-md-0 mt-4">
                                                    <div class="col-md-8">
                                                        <input type="text" value="<?php echo $seller_transferor_signature;?>" class="input_single w-full mb-2" id="seller_transferor_signature" name="seller_transferor_signature">
                                                        Seller's/Transferor's signature 
                                                    </div>
                                                    <div class="col-md-4 mt-md-0 mt-2">
                                                        <input type="text" value="<?php echo $seller_transferor_date;?>" class="input_single w-full mb-2" id="seller_transferor_date" name="seller_transferor_date">
                                                        Date
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-md-8">
                                                        <input type="text" value="<?php echo $seller_transferor_spouse_signature;?>" class="input_single w-full mb-2" id="seller_transferor_spouse_signature" name="seller_transferor_spouse_signature">
                                                        Seller's/Transferor's spouse's/RDP's signature 
                                                    </div>
                                                    <div class="col-md-4 mt-md-0 mt-2">
                                                        <input type="text" value="<?php echo $signature_corporate_officer_date;?>" class="input_single w-full mb-2" id="seller_transferor_spouse_date" name="seller_transferor_spouse_date">
                                                        Date
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-md-8">
                                                        <input type="text" value="<?php echo $buyer_transferor_signature;?>" class="input_single w-full mb-2" id="buyer_transferor_signature" name="buyer_transferor_signature">
                                                        Buyer's/Transferee's signature 
                                                    </div>
                                                    <div class="col-md-4 mt-md-0 mt-2">
                                                        <input type="text" value="<?php echo $buyer_transferor_signature;?>" class="input_single w-full mb-2" id="buyer_transferor_date" name="buyer_transferor_signature">
                                                        Date
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-md-8">
                                                        <input type="text" value="<?php echo $buyer_transferor_spouse_signature;?>" class="input_single w-full mb-2" id="buyer_transferor_spouse_signature" name="buyer_transferor_spouse_signature">
                                                        Buyer's/Transferee's spouse's/RDP's signature
                                                    </div>
                                                    <div class="col-md-4 mt-md-0 mt-2">
                                                        <input type="text" value="<?php echo $buyer_transferor_spouse_date;?>" class="input_single w-full mb-2" id="buyer_transferor_spouse_date" name="buyer_transferor_spouse_date">
                                                        Date
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <b>
                                                            Remitter's name and Title/Escrow business name<br>
                                                            Pacific Coast Title Company
                                                        </b>
                                                    </div>
                                                    <div class="col-md-4 mt-md-0 mt-2">
                                                        <b>
                                                            Telephone Number<br>
                                                            714-516-6700
                                                        </b>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

							<div class="accordion-item">
                                <h2 class="accordion-header" id="headingFourteen">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFourteen" aria-expanded="true" aria-controls="collapseFourteen">
                                       (5) 1099-S
                                    </button>
                                </h2>
                                <div id="collapseFourteen" class="accordion-collapse collapse" aria-labelledby="headingFourteen" data-bs-parent="#accordionExample" style="">
                                    <div class="accordion-body">                                        
                                        <h5 class="text-center mt-md-5">
                                            <strong>Proceeds from Real Estate Transactions as required by the Internal Revenue Service</strong>
                                        </h5>

                                        <div class="text-center">
                                            <em>You are required by law to provide Pacific Coast Title Company with your correct taxpayer identification number. If you do not provide your correct taxpayer identification number, you may be subject to civil or criminal penalties imposed by law.</em>
                                        </div>
                                
                                        <div class="row mt-5">
                                            <div class="col-md-6">
                                                Branch Address:<br>
                                                Pacific Coast Title Company<br>
                                                516 Burchett St.<br>
                                                Glendale, CA  91203<br><br><br>

                                                
                                                Escrow No.:  10257432-GLE-MP	
                                            </div>
                                            <div class="col-md-6">
                                                This is important tax information and is being furnished to the Internal Revenue Service, as required by section 1521 of the Tax Reform Act of 1986.  If you are required to file a return, a negligence penalty or other sanction will be imposed if this income is taxable and the IRS determines that it has not been reported.<br><br>
                                                Date of closing: <input type="text" value="<?php echo $date_of_closing;?>" class="w-half input_single" id="date_of_closing" name="date_of_closing">
                                            </div> 
                                        </div>


                                        <hr>
                                
                                        <h5 class="mt-0"><b>PROPERTY ADDRESS OR LEGAL DESCRIPTION</b></h5>
                                
                                        <div>
                                            Lots/APN: 210-021-30-00-1 and 210-021-29-00-9, Bakersfield, CA  93301<br>
                                            Assessors Parcel Number (APN) - 210-021-29-00, 210-021-30-00
                                        </div>
                                        <hr>
                                
                                        <div>
                                            <b>PROCEEDS FOR THIS SALE WENT TO:  </b> (MULTIPLE SELLERS - Use one form for each seller.  Treat husband and wife as one seller (filing joint tax returns) unless requested otherwise, then separate forms must be used.)
                                        </div>
                                
                                        <div class="row mt-5">
                                            <div class="col-md-7">
                                                <div class="form-group position-relative mb-3 mt-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" value="<?php echo $sellers_name;?>" class="form-control" id="sellers_name" name="sellers_name">
                                                    <small class="small_label">Sellers Name (First, MI, Last or Entity Name)</small>
                                                </div>
                                                <div class="form-group position-relative mb-3 mt-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" value="<?php echo $personal_representative;?>" class="form-control" id="personal_representative" name="personal_representative">
                                                    <small class="small_label">Spouse or Personal Representative</small>
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                
                                                <div class="form-group position-relative mb-3 mt-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" value="<?php echo $federal_tax;?>" class="form-control" id="federal_tax" name="federal_tax">
                                                    <small class="small_label">Federal Tax ID# for this seller</small>
                                                </div>
                                                (List only the Tax ID# for the seller listed on Line 1, spouse Tax ID# not required.  Executor/Trustee should not list their name as the seller unless they are going to report the proceeds on their personal income tax return.)
                                            </div> 
                                        </div>
                                
                                        <hr>
                                
                                        <div>
                                            <b>TOTAL CONSIDERATION</b>
                                        </div>
                                
                                        
                                        <div class="row mt-5">
                                            <div class="col-md-6">
                                                <div>$ <input type="text" value="<?php echo $total_consideration;?>" class="input_single" id="total_consideration" name="total_consideration"> Total Consideration</div>
                                                <div><input type="text" value="<?php echo $percentage_of_ownership;?>" class="input_single" id="percentage_of_ownership" name="percentage_of_ownership">% Percentage of ownership for this seller</div>
                                                <div>$ <input type="text" value="<?php echo $gross_allocated;?>" class="input_single" id="gross_allocated" name="gross_allocated"> GROSS Allocated Proceeds</div>
                                                <div><small> (Total consideration multiplied by percentage of ownership)</small></div>
                                            </div>
                                            <div class="col-md-6">
                                                <div>
                                                    <input type="checkbox" <?php echo  ($is_exchange == 'yes') ? 'checked="checked"' : '';?> name="is_exchange" id="is_exchange" value="yes"> <label for="exchange">Exchange (If checked)</label>
                                                    <div class="mt-5">$ <input type="text" value="<?php echo $tax_credit;?>" class="input_single" id="tax_credit" name="tax_credit"> Tax Credit to Seller (Real property tax credits to seller contained in the 400 series of the HUD-1 or comparable closing statement form.)</div>
                                                </div>
                                            </div> 
                                        </div>
                                
                                        <hr>
                                
                                        <div>
                                            <b>MAILING ADDRESS AFTER CLOSE:</b>
                                        </div>
                                
                                
                                        <div class="row mt-5">
                                            <div class="col-md-6">
												<div>
													<input type="text" value="<?php echo $mailing_address_1099_s_1;?>" class="w-full input_single" id="mailing_address_1099_s_1" name="mailing_address_1099_s_1">
												</div>
												<div>
													<input type="text" value="<?php echo $mailing_address_1099_s_2;?>" class="w-full mt-4 input_single" id="mailing_address_1099_s_2" name="mailing_address_1099_s_2">
												</div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="d-flex">
                                                    <input type="checkbox" <?php echo  ($is_outside == 'yes') ? 'checked="checked"' : '';?> name="is_outside" id="is_outside" value="yes" class="me-3"> <label for="outside"><small>Check here if the address is outside of the U.S.A.</small></label>
                                                </div>
                                                <div class="d-flex">
                                                    <input type="checkbox" <?php echo  ($is_regulations == 'yes') ? 'checked="checked"' : '';?> name="is_regulations" id="is_regulations"  value="yes" class="me-3"> <label for="regulations"><small>Check here if you are a foreign person per IRS regulations (nonresident alien, foreign partnership, foreign estate, or foreign trust.) Do not sign below.</small></label>
                                                </div>
                                            </div> 
                                        </div>
                                
                                        <hr>
                                
                                        <div>
                                            Under penalty of perjury, I certify that I am a U.S. person or U.S. resident alien and the number shown on this statement is my correct taxpayer identification number.
                                        </div>
                                
                                        <div class="row mt-5">
                                            <div class="col-md-6">
                                            <div>
                                                <input type="text" value="<?php echo $tranferor_signature;?>" class="w-full input_single" id="tranferor_signature" name="tranferor_signature">
                                                Transferor’s Signature
                                            </div>
                                            <div>
                                                <input type="text" value="<?php echo $spouse_signature;?>" class="w-full mt-3 input_single" id="spouse_signature" name="spouse_signature">
                                                Spouse
                                            </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div>
                                                    <input type="text" value="<?php echo $tranferor_date;?>" class="w-full input_single" id="tranferor_date" name="tranferor_date">
                                                    Date
                                                </div>
                                                <div>
                                                    <input type="text" value="<?php echo $spouse_date;?>" class="w-full mt-3 input_single" id="spouse_date" name="spouse_date">
                                                    Date
                                                </div>
                                            </div> 
                                        </div>
                                    </div>    
                                </div>
                            </div>

							<div class="accordion-item">
                                <h2 class="accordion-header" id="heading22">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse22" aria-expanded="true" aria-controls="collapse22">
                                       (6) FIRPTA AFFIDAVIT
                                    </button>
                                </h2>
                                <div id="collapse22" class="accordion-collapse collapse" aria-labelledby="heading22" data-bs-parent="#accordionExample" style="">
                                    <div class="accordion-body">
                                        <div class="mt-md-5">
                                            <b>DATE:</b> February 11, 2022<br>
                                            <b>ESCROW NO.:</b> 10257432-GLE-MP
                                        </div>
                                        <h3 class="text-center my-4"><strong>Certification of Non-Foreign Status</strong></h3>
                                        
                                        <div class="mb-3">
                                            Section 1445 of the Internal Revenue Code requires a transferee (buyer) of a U.S. Real Property interest to withhold fifteen percent (15%) tax of the gross sales price if the transferor (seller) is a foreign person or entity unless the transferee receives a certification of non-foreign status from the transferor (seller). The certification must be signed under penalties of perjury stating the transferor is not a foreign person/entity and containing the transferor’s name, address, and U.S. Taxpayers Identification Number.
                                        </div>
                                        <div class="mb-3">
                                            Sellers who provide such a certification are except from withholding and the estimated tax cannot be collected from them unless the buyer or their agent have knowledge the certification is false.
                                        </div>
                                        <div class="mb-3">
                                            Certification of Non-Foreign Status by Individual (a separate statement must be completed by each individual seller)
                                        </div>
                                        <div class="mb-2">
                                            I, the undersigned Seller(s), hereby certify the following:
                                        </div>

                                        <ol>
                                            <li>
                                                I am not a non-resident alien for the purposes of U.S. Income Taxation;
                                            </li>
                                            <li>
                                                <div class="d-flex flex-xs-wrap my-2">
                                                    My U.S. taxpayer identifying no. (Social Security No.) is: <input type="taxpayer_identifying_num" value="<?php echo $signature_corporate_officer_date;?>" class="input_single flex1" id="taxpayer_identifying_num" name="taxpayer_identifying_num">;
                                                </div>
                                            </li>
                                            <li>
                                                <div class="d-flex flex-xs-wrap">
                                                    My home address is:<input type="text" value="<?php echo $home_address;?>" class="input_single flex1 mb-2" id="home_address" name="home_address" data-error="home_address-error">
                                                </div>
                                                <input type="text optional-input" class="input_single w-full" id="home_address_2" name="home_address_2">
												<label id="home_address-error" class="error text-danger" for="work_phone_number"></label>
                                            </li>
                                        </ol>
                                        
                                        <div class="mb-3">
                                            I understand this certification may be disclosed to the Internal Revenue Service by the transferee and any false statement I have made herein could be punished by fine, imprisonment, or both.
                                        </div>

                                        <div class="mb-3">
                                            Under penalty of perjury I declare I have completed this certification and to the best of my knowledge and belief it is true, correct and complete.
                                        </div>

                                        <div class="mb-3">
                                            IN WITNESS WHEREOF, the undersigned have executed this document on the date(s) set forth below.
                                        </div>

                                        <div class="mt-5">
                                            Date :    
                                            <input type="text" value="<?php echo $firpta_date;?>" class="input_single" id="firpta_date" name="firpta_date">
                                        </div>
                                        <div class="mt-5 row">
                                            <div class="col-md-4">
                                                <input type="text" value="<?php echo $firpta_signature;?>" class="input_single d-block w-full" id="firpta_signature" name="firpta_signature">
                                                Luz Amparo Rockey
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

							<div class="accordion-item">
                                <h2 class="accordion-header" id="headingEleven">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEleven" aria-expanded="false" aria-controls="collapseEleven">
                                        (7) NHD Receipt
                                    </button>
                                </h2>
                                <div id="collapseEleven" class="accordion-collapse collapse" aria-labelledby="headingEleven" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <div class="form-group d-flex mb-2">
                                            <input type="checkbox" checked="checked" id="acknowledge" class="me-2 mt-1">
                                            <label for="acknowledge" class="mb-2">By clicking the submit button, I agree to terms & conditions.</label>
                                        </div>
                                        <div class="border text-danger p-3">
                                            IMPORTANT NOTICE: Cyber criminals are preying on those involved in real estate transactions. They will hack email accounts, spoof email addresses, and send emails with fake wiring or fake funds delivery instructions. These emails are convincing and sophisticated. Always independently confirm wiring and funding instructions in person or by telephone to our published office phone number of record. Never wire money without double-checking, in person or by telephone that the wiring instructions are correct. BE SKEPTICAL AND VIGILANT. 
                                        </div>
                                        <hr>
                                        
                                        <div class="form-group mb-4">
                                            <label for="" class="mb-2"><b>Signature</b></label>
                                            <textarea name="" id="" class="form-control h-auto" rows="5"></textarea>
                                            <!-- <a href="javascript:;" class="text-body text-end"> Clear</a> -->
                                        </div>

                                        <div class="row mt-5">
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label for="" class="mb-2"><b>TenantID</b></label>
                                                    <input type="text" value="<?php echo $tenant_id;?>" class="form-control" id="tenant_id" name="tenant_id">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label for="" class="mb-2"><b>DocType</b></label>
                                                    <input type="text" value="<?php echo $doc_type;?>" class="form-control" id="doc_type" name="doc_type">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

							<div class="accordion-item">
                                <h2 class="accordion-header" id="heading23">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTen" aria-expanded="false" aria-controls="collapseTen">
                                       (9) Wire Instructions
                                    </button>
                                </h2>
                                <div id="collapseTen" class="accordion-collapse collapse" aria-labelledby="headingTen" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <div class="page">
                                            <h2 class="text-center my-4"><strong>WIRING INFORMATION</strong></h2>
                                            
                                            <div class="date_escrow_num">
                                                <span>ESCROW NO.:</span>10257432-GLE-MP<br>
                                                <span>TITLE NO.:</span>10257432-GLT-<br><br>
                                                <span>TO:</span>
                                                <b>
                                                    Pacific Coast Title Company<br>
                                                    516 Burchett St.<br>
                                                    Glendale, CA  91203	
                                                </b><br><br>
                                                <span>BANK:</span>
                                                <b class="text_black">
                                                    Nano Banc, 7700 Irvine Center Drive, Suite 700, Irvine, CA  92618
                                                </b><br><br>
                                                <span>ROUTING NO:</span>
                                                <b class="text_black">
                                                    122245251
                                                </b><br><br>
                                                <span>ACCOUNT NO:</span>
                                                <b class="text_black">
                                                    Credit to <b class="text_red">Pacific Coast Title Company</b> in trust for <b class="text_red">MORDECHAI CITRONENBAUM</b><br> account number 6100100846
                                                </b><br><br>
                                               <b class="text_black"> PLEASE REFER TO OUR ESCROW NO. <b class="text_red">10257432-GLE-MP</b> </b>
                                            </div>
                                            <h5 class="text-underline text-center my-5 f600">
                                                WIRED FUNDS are preferred, as the funds are immediately posted and available.
                                            </h5>
                                            <p>
                                                ANY CASHIER CHECKS should be made payable to <span class="text_red">Pacific Coast Title Company</span>, reference the escrow number noted above. Funds received by Cashier’s Checks require overnight clearing prior to any close of escrow.
                                            </p>
                                            <p>
                                                Personal checks require bank clearance and your proof from your bank of your paid check.
                                            </p>
                                    
                                            <p>
                                                Delays in closing are likely if these guidelines are not followed. <span class="text_red">Pacific Coast Title Company</span> does not accept any responsibility for these delays to your closing.
                                            </p>
                                    
                                            <p>
                                                Please Note:  Our office does not accept ACH transfers. These instructions are for the purpose of sending wire transfers only.
                                            </p>
                                            
                                            <div class="notice_box">
                                                <p class="mt-0 text-center f600">
                                                    NOTE THE FOLLOWING IS <span class="text-underline">NOT ACCEPTABLE</span> AND CAN <i class="f600">SIGNIFICANTLY DELAY YOUR CLOSING:</i>
                                                </p>
                                                <p>
                                                    OFFICIAL CHECKS &amp; CERTIFIED CHECKS - are not a Cashier’s Check and are subject to a waiting period of 3-7 days and verification of cleared funds.
                                                </p>
                                                <p>
                                                    ON-LINE TRANSFERS OR ACH CREDITS- these can be recalled by the sender and therefore are not acceptable as they do not meet existing government guidelines of “Good Funds”. Your bank may offer this option at a lower cost, DO NOT ACCEPT! 
                                                </p>
                                                <p class="mb-0">
                                                    DIRECT DEPOSIT- This could cause a significant delay in your closing.
                                                </p>
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
    </section>
	<script src="<?php echo base_url();?>assets/frontend/js/order/jquery.min.js"></script>
    <script src="<?php echo base_url();?>assets/frontend/js/jquery.validate.min.js"></script>
	<script src="<?php echo base_url();?>assets/frontend/js/order/bootstrap.bundle.min.js"></script>
	<script src="<?php echo base_url();?>assets/frontend/js/order/script.js?v=02"></script>
</body>

</html>
