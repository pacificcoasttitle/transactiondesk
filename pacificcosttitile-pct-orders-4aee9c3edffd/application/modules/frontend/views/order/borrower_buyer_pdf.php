<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title;?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,200&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/frontend/css/buyer-seller-package/bootstrap.min.css?v=01">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/frontend/css/buyer-seller-package/style.css?v=01">
</head>

<style>
	.error2 {
		margin: 5px 0;
	}
    /* input[type="radio"] { margin: 10px !important; } */
    .form-control {
        width: 100%;
    }
	.table>:not(caption)>*>* {
        padding: 1rem;
    }
		/* Fix wkhtmltopdf compatibility with BS flex features */
		.title_string {
	padding: 2px;
    /* overflow: hidden;
    white-space: nowrap; */
	
	
    
}
.row {
	display: -webkit-box;
	display: flex;
	-webkit-box-pack: center;
	justify-content: center;
}
.row > div {
	-webkit-box-flex: 1;
	-webkit-flex: 1;
	flex: 1;
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
.position-relative input,input.form-control {
	border: 0;
    border-radius: 0;
    border-bottom: 1px solid;
}


.row > div:last-child {
	margin-right: 0;
}
.align-items-start input,.align-items-start label,.d-flex > * {
	display: inline;
}

</style>

<body class="">

    <!-- <header>
        <div class="container">
            <div class="row align-items-center">
                <div class="col-6">
                    <a href="#"><img src="<?php echo base_url();?>assets/frontend/images/buyer-seller-package/alanna-logo.png" alt="..." class="img-fluid img_logo"></a>
                </div>
                <div class="col-6 text-end">
                </div>
            </div>
        </div>
    </header> -->

    <section class="form_content">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    
                    <form action="<?php echo base_url().'borrower-buyer-form/'.$orderDetails['file_id']; ?>" method="post" name="borrower_buyer_form" id="borrower_buyer_form">
                        <h2 class="blue_title">Buyer Opening Package<br><span style="font-size:16px; padding-top:15px;">Property Address: <?php echo $orderDetails['full_address'];?></span><br><span style="font-size:16px; padding-top:15px;">APN:<?php echo $orderDetails['apn'];?></span></h2>
                        <div class="accordion" id="accordionExample">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">(1) Property Information</button>
                                </h2>
                                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <div class="row mb-3">
                                            <div class="col-md-12">
                                                <div class="form-group position-relative mb-3 mt-3">
                                                    <label for="" class="mb-2"><b>Property Address Being Purchased</b></label>
                                                    <input type="text" class="form-control" id="property_address" value="<?php echo $orderDetails['address'];?>" name="property_address" required data-error="#property_address-error">
                                                    <small class="small_label">Street Address</small>
                                                </div>
                                                <input type="hidden" name="order_id" id="order_id" value="<?php echo $orderDetails['order_id'];?>">
                                                <label id="property_address-error" class="error text-danger error2" for="property_address"></label>

                                                <div class="form-group position-relative mb-3">
                                                    <input type="text" class="form-control" id="property_address2" value="" name="property_address2" required data-error="#property_address2-error">
                                                    <small  class="small_label">Street Address Line 2</small>
                                                </div>
                                                <label id="property_address2-error" class="error text-danger error2" for="property_address2"></label>

												<div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group position-relative mb-3">
                                                            <input type="text" class="form-control" id="property_city" value="<?php echo $orderDetails['property_city'];?>" name="property_city" id="property_city" name="property_city" required data-error="#property_city-error">
                                                            <small  class="small_label">City</small>
                                                        </div>
                                                        <label id="property_city-error" class="error text-danger error2" for="property_city"></label>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group position-relative mb-3 ">
                                                            <input type="text" class="form-control" id="property_zipcode" value="<?php echo $orderDetails['property_zip'];?>" name="property_zipcode" required data-error="#property_zipcode-error">
                                                            <small  class="small_label">Zip Code</small>
                                                        </div>
                                                        <label id="property_zipcode-error" class="error text-danger error2" for="property_zipcode"></label>
                                                    </div>
												</div>	
                                            </div>
                                        </div>
                                    </div>    
                                </div>
                            </div>
                            <div class="accordion-item d-none">
                                <h2 class="accordion-header" id="headingThirteen">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThirteen" aria-expanded="false" aria-controls="collapseThirteen">(2) Buyer Information</button>
                                </h2>
                                <div id="collapseThirteen" class="accordion-collapse collapse" aria-labelledby="headingThirteen" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <div class="date_escrow_num mt-md-5">
                                            <span>Escrow No.:</span> 10257432-GLE-MP<br><span>Title No.:</span> 10257432-GLT-
                                        </div> 

                                        <h4 class="text-center my-4"><strong>PLEASE FILL OUT THIS FORM COMPLETELY AND RETURN TO OUR OFFICE AS SOON AS POSSIBLE <br> AS IT WILL ASSIST US IN THE ADMINISTRATION OF YOUR TRANSACTION.</strong></h4>

                                        <div class="form-group position-relative mb-3 mt-3">
                                            <label for="" class="mb-2"><b></b></label>
                                            <input type="text" class="form-control" id="buyer_full_name" name="buyer_full_name" value="<?php echo $buyer_full_name;?>" required data-error="#buyer_full_name-error">
                                            <small class="small_label">Buyer(s):</small>
                                        </div>
                                        <label id="buyer_full_name-error" class="error text-danger" for="buyer_full_name"></label>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3 mt-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" class="form-control" id="buyer_home_number" name="buyer_home_number" value="<?php echo $buyer_home_number;?>" required data-error="#buyer_home_number-error">
                                                    <small class="small_label">Home Phone Number:</small>
                                                </div>
                                                <label id="buyer_home_number-error" class="error text-danger" for="buyer_home_number"></label>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3 mt-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" class="form-control" id="buyer_work_number" name="buyer_work_number" value="<?php echo $buyer_work_number;?>" required data-error="#buyer_work_number-error">
                                                    <small class="small_label">Work Phone Number:</small>
                                                </div>
                                                <label id="buyer_work_number-error" class="error text-danger" for="buyer_work_number"></label>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3 mt-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" class="form-control" id="buyer_email_address" name="buyer_email_address" value="<?php echo $buyer_email_address;?>" required data-error="#buyer_email_address-error">
                                                    <small class="small_label">E-Mail Address:</small>
                                                </div>
                                                <label id="buyer_email_address-error" class="error text-danger" for="buyer_email_address"></label>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3 mt-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" class="form-control" id="buyer_fax_number" name="buyer_fax_number" value="<?php echo $buyer_fax_number;?>" required data-error="#buyer_fax_number-error">
                                                    <small class="small_label">Fax Number:</small>
                                                </div>
                                                <label id="buyer_fax_number-error" class="error text-danger" for="buyer_fax_number"></label>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3 mt-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" class="form-control" id="buyer_ssn" name="buyer_ssn" value="<?php echo $buyer_ssn;?>" required data-error="#buyer_ssn-error">
                                                    <small class="small_label">Social Security #:</small>
                                                </div>
                                                <label id="buyer_ssn-error" class="error text-danger" for="buyer_ssn"></label>
                                            </div>
                                        </div>

                                        <div class="form-group position-relative mb-3 mt-3">
                                            <label for="" class="mb-2"><b></b></label>
                                            <textarea rows="2" class="form-control" style="height:auto;" id="buyer_current_mailing_address" name="buyer_current_mailing_address" required data-error="#buyer_current_mailing_address-error"><?php echo $buyer_current_mailing_address;?></textarea>
                                            <small class="small_label">Buyer(s) Current Mailing Address:</small>
                                        </div>
                                        <label id="buyer_current_mailing_address-error" class="error text-danger" for="buyer_current_mailing_address"></label>

                                        <div class="form-group position-relative mb-3 mt-3">
                                            <label for="" class="mb-2"><b></b></label>
                                            <textarea rows="2" class="form-control" style="height:auto;" id="buyer_mailing_address_after_close" name="buyer_mailing_address_after_close" required data-error="#buyer_mailing_address_after_close-error"><?php echo $buyer_mailing_address_after_close;?></textarea>
                                            <small class="small_label">Buyer(s) Mailing Address After Close Of Escrow:</small>
                                        </div>
                                        <label id="buyer_mailing_address_after_close-error" class="error text-danger" for="buyer_mailing_address_after_close"></label>

                                        <div class="form-group position-relative mb-3 mt-5">
                                            <label for="" class="mb-2"><b>New Loan(s) Buyer(s) Are Applying For:</b></label>
                                            <input type="text" class="form-control" id="lender_name" name="lender_name" value="<?php echo $lender_name;?>" required data-error="#lender_name-error">
                                            <small class="small_label">Name Of Lender:</small>
                                        </div>
                                        <label id="lender_name-error" class="error text-danger" for="lender_name"></label>
                                        
                                        <div class="form-group position-relative mb-3 mt-3">
                                            <label for="" class="mb-2"><b></b></label>
                                            <textarea rows="2" class="form-control" style="height:auto;" id="lender_address" name="lender_address" required data-error="#property_address-error"><?php echo $lender_address;?></textarea>
                                            <small class="small_label">Address:</small>
                                        </div>
                                        <label id="lender_address-error" class="error text-danger" for="lender_address"></label>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3 mt-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" class="form-control" id="agent_name" name="agent_name" value="<?php echo $agent_name;?>" required data-error="#agent_name-error">
                                                    <small class="small_label">Agent's Name:</small>
                                                </div>
                                                <label id="agent_name-error" class="error text-danger error2" for="agent_name"></label>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3 mt-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" class="form-control" id="agent_phone_number" name="agent_phone_number" value="<?php echo $agent_phone_number;?>" required data-error="#agent_phone_number-error">
                                                    <small class="small_label">Phone Number:</small>
                                                </div>
                                                <label id="agent_phone_number-error" class="error text-danger error2" for="agent_phone_number"></label>
                                            </div>
                                        </div>

                                        <div class="form-group position-relative mb-3">
                                            <label for="" class="mb-2"><b></b></label>
                                            <input type="text" class="form-control" id="second_lender_name" name="second_lender_name" value="<?php echo $second_lender_name;?>">
                                            <small class="small_label">Name Of Seond Lender:</small>
                                        </div>
                                        
                                        
                                        <div class="form-group position-relative mb-3 mt-3">
                                            <label for="" class="mb-2"><b></b></label>
                                            <textarea rows="2" class="form-control" style="height:auto;" id="seond_lender_address" name="seond_lender_address" ><?php echo $seond_lender_address;?></textarea>
                                            <small class="small_label">Address:</small>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3 mt-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" class="form-control" id="second_agent_name" name="second_agent_name" value="<?php echo $second_agent_name;?>">
                                                    <small class="small_label">Second Agent's Name:</small>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3 mt-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" class="form-control" id="seond_agent_phone_number" name="seond_agent_phone_number" value="<?php echo $seond_agent_phone_number;?>">
                                                    <small class="small_label">Phone Number:</small>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3 mt-3">
                                                    <label for="" class="mb-2"><b>New Insurance:</b></label>
                                                    <input type="text" class="form-control" id="insurance_name" name="insurance_name" value="<?php echo $insurance_name;?>" required data-error="#insurance_name-error">
                                                    <small class="small_label">Insurance's Name:</small>
                                                </div>
                                                <label id="insurance_name-error" class="error text-danger error2" for="insurance_name"></label>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3 mt-3">
                                                    <label for="" class="mb-2"><b>&nbsp;</b></label>
                                                    <input type="text" class="form-control" id="insurance_phone_number" name="insurance_phone_number" value="<?php echo $insurance_phone_number;?>" required data-error="#insurance_phone_number-error">
                                                    <small class="small_label">Phone Number:</small>
                                                </div>
                                                <label id="insurance_phone_number-error" class="error text-danger error2" for="insurance_phone_number"></label>
                                            </div>
                                        </div>

                                        <div class="form-group position-relative mb-3 mt-3">
                                            <label for="" class="mb-2"><b></b></label>
                                            <textarea rows="2" class="form-control" style="height:auto;" id="insurance_address" name="insurance_address" required data-error="#insurance_address-error"><?php echo $insurance_address;?></textarea>
                                            <small class="small_label">Insurance's Address:</small>
                                        </div>
                                        <label id="insurance_address-error" class="error text-danger" for="insurance_address"></label>
                                        
                                        <div class="form-group position-relative mb-3 mt-3">
                                            <label for="" class="mb-2"><b></b></label>
                                            <input type="text" class="form-control" id="insurance_company" name="insurance_company" value="<?php echo $insurance_company;?>" required data-error="#insurance_company-error">
                                            <small class="small_label">Insurance Company:</small>
                                        </div>
                                        <label id="insurance_company-error" class="error text-danger" for="insurance_company"></label>

                                        <p class="mt-5">Please place any additional information that you feel we may require on the reverse side of this form.</p>
                                                
                                        <div class="mb-80 mt-5">
                                            Dated:    
                                            <input type="text" class="w30 input_single" id="buyer_date" name="buyer_date" value="<?php echo $buyer_date;?>" required data-error="#buyer_date-error">
                                            <label id="buyer_date-error" class="error text-danger d-flex" for="buyer_date"></label>
                                        </div>
                                       

                                        <input type="text" class="signature" placeholder="signature" id="buyer_signature" name="buyer_signature" value="<?php echo $buyer_signature;?>" required data-error="#buyer_signature-error">  
                                        <label id="buyer_signature-error" class="error text-danger d-flex" for="buyer_signature"></label>
                                    </div>   
                                </div>
                            </div>
                        
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingTwo">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">(2) Escrow Instructions</button>
                                </h2>
                                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <div class="form-group mb-4">
                                            <label for="" class="mb-2"><b>Is there a Mortgage or equity line on the Property?</b></label>
                                            <ul class="list-inline mb-0">
                                                <li class="list-inline-item me-md-5">
                                                    <input type="radio" id="yesMortgage" value="yes" name="is_mortgage" required data-error="#is_mortgage-error" <?php echo ($is_mortgage == 'yes') ? 'checked="checked"' : '';?>> 
                                                    <label for="yesMortgage">Yes</label>
                                                </li>
                                                <li class="list-inline-item">
                                                    <input type="radio" id="noMortgage" value="no" name="is_mortgage" <?php echo ($is_mortgage == 'no') ? 'checked="checked"' : '';?>> 
                                                    <label for="noMortgage">No</label>
                                                </li>
                                            </ul>
                                            <label id="is_mortgage-error" class="error text-danger" for="is_mortgage"></label>
                                        </div> 
                                        <div class="form-group mb-4">
                                            <label for="" class="mb-2"><b>Are there any other Liens on the Property?</b></label>
                                            <ul class="list-inline mb-0">
                                                <li class="list-inline-item me-md-5">
                                                    <input type="radio" id="yesLiens" value="yes" name="is_liens" required data-error="#is_liens-error" <?php echo ($is_liens == 'yes') ? 'checked="checked"' : '';?>> 
                                                    <label for="yesLiens">Yes</label>
                                                </li>
                                                <li class="list-inline-item">
                                                    <input type="radio" id="noLiens" value="no" name="is_liens" <?php echo ($is_liens == 'no') ? 'checked="checked"' : '';?>> 
                                                    <label for="noLiens">No</label>
                                                </li>
                                            </ul>
                                            <label id="is_liens-error" class="error text-danger" for="is_liens"></label>
                                        </div>
                                        <div class="form-group mb-4">
                                            <label for="" class="mb-2"><b>Are there mandatory Homeowners or Condominium Associations?</b></label>
                                            <ul class="list-inline mb-0">
                                                <li class="list-inline-item me-md-5">
                                                    <input type="radio" id="yesCondominium" value="yes" name="is_condominium" required data-error="#is_condominium-error" <?php echo ($is_condominium == 'yes') ? 'checked="checked"' : '';?>> 
                                                    <label for="yesCondominium">Yes</label>
                                                </li>
                                                <li class="list-inline-item">
                                                    <input type="radio" id="noCondominium" value="no" name="is_condominium" <?php echo ($is_condominium == 'no') ? 'checked="checked"' : '';?>> 
                                                    <label for="noCondominium">No</label>
                                                </li>
                                            </ul>
                                            <label id="is_condominium-error" class="error text-danger" for="is_condominium"></label>
                                        </div>
                                        <div class="form-group mb-4">
                                            <label for="" class="mb-2"><b>Is this your primary residence / homestead property for tax purposes?</b></label>
                                            <ul class="list-inline mb-0">
                                                <li class="list-inline-item me-md-5">
                                                    <input type="radio" id="yesresidence" value="yes" name="is_residence" <?php echo ($is_residence == 'yes') ? 'checked="checked"' : '';?> required data-error="#is_residence-error"> 
                                                    <label for="yesresidence">Yes</label>
                                                </li>
                                                <li class="list-inline-item">
                                                    <input type="radio" id="noresidence" value="no" <?php echo ($is_residence == 'no') ? 'checked="checked"' : '';?> name="is_residence"> 
                                                    <label for="noresidence">No</label>
                                                </li>
                                            </ul>
                                            <label id="is_residence-error" class="error text-danger" for="is_residence"></label>
                                        </div>
                                        <div class="form-group mb-4">
                                            <label for="" class="mb-2"><b>Has there been a divorce since the purchase of the property or are you currently in the process of getting divorced?</b></label>
                                            <ul class="list-inline mb-0">
                                                <li class="list-inline-item me-md-5">
                                                    <input type="radio" id="yesdivorced" value="yes" name="is_divorced" <?php echo ($is_divorced == 'yes') ? 'checked="checked"' : '';?> required data-error="#is_divorced-error"> 
                                                    <label for="yesdivorced">Yes</label>
                                                </li>
                                                <li class="list-inline-item">
                                                    <input type="radio" id="nodivorced" value="no" name="is_divorced" <?php echo ($is_divorced == 'no') ? 'checked="checked"' : '';?>> 
                                                    <label for="nodivorced">No</label>
                                                </li>
                                            </ul>
                                            <label id="is_divorced-error" class="error text-danger" for="is_divorced"></label>
                                            <div class="errorMsg text-danger d-none">Please provide a copy of the divorce decree or marriage settlement agreement.</div>
                                        </div>
                                        <div class="form-group mb-4">
                                            <label for="" class="mb-2"><b>Has your name changed since purchasing the Property?
                                            </b></label>
                                            <ul class="list-inline mb-0">
                                                <li class="list-inline-item me-md-5">
                                                    <input type="radio" id="yeschanged" value="yes" name="is_changed" required data-error="#is_changed-error" <?php echo ($is_changed == 'yes') ? 'checked="checked"' : '';?>> 
                                                    <label for="yeschanged">Yes</label>
                                                </li>
                                                <li class="list-inline-item">
                                                    <input type="radio" id="nochanged" value="no" name="is_changed" <?php echo ($is_changed == 'no') ? 'checked="checked"' : '';?>> 
                                                    <label for="nochanged">No</label>
                                                </li>
                                            </ul>
                                            <label id="is_changed-error" class="error text-danger" for="is_changed"></label>
                                            <div class="errorMsg text-danger d-none">Please provide a copy of the marriage certificate or other legal document showing name change. </div>
                                        </div>
                                        <div class="form-group mb-4">
                                            <label for="" class="mb-2"><b>Has there been a death of an owner since taking title to the Property?
                                            </b></label>
                                            <ul class="list-inline mb-0">
                                                <li class="list-inline-item me-md-5">
                                                    <input type="radio" id="yesdeath" value="yes" name="is_death" required data-error="#is_death-error" <?php echo ($is_death == 'yes') ? 'checked="checked"' : '';?>> 
                                                    <label for="yesdeath">Yes</label>
                                                </li>
                                                <li class="list-inline-item">
                                                    <input type="radio" id="nodeath" value="no" name="is_death" <?php echo ($is_death == 'no') ? 'checked="checked"' : '';?>> 
                                                    <label for="nodeath">No</label>
                                                </li>
                                            </ul>
                                            <label id="is_death-error" class="error text-danger" for="is_death"></label>
                                            <div class="errorMsg text-danger d-none">We will need a certified copy of the original death certificate for recordation, The original death certificate will be returned after closing. 
                                            </div>
                                        </div>
                                        <div class="form-group mb-4">
                                            <label for="" class="mb-2"><b>Do you have an existing survey for the Property?</b></label>
                                            <ul class="list-inline mb-0">
                                                <li class="list-inline-item me-md-5">
                                                    <input type="radio" id="yessurvey" value="yes" name="is_survey" required data-error="#is_survey-error" <?php echo ($is_survey == 'yes') ? 'checked="checked"' : '';?>> 
                                                    <label for="yessurvey">Yes</label>
                                                </li>
                                                <li class="list-inline-item me-md-5">
                                                    <input type="radio" id="nosurvey" value="no" name="is_survey" <?php echo ($is_survey == 'no') ? 'checked="checked"' : '';?>> 
                                                    <label for="nosurvey">No</label>
                                                </li>
                                                <li class="list-inline-item">
                                                    <input type="radio" id="na" value="n/a" name="is_survey" <?php echo ($is_survey == 'n/a') ? 'checked="checked"' : '';?>> 
                                                    <label for="na">N/A - Property is a Condominium</label>
                                                </li>
                                            </ul>
                                            <label id="is_survey-error" class="error text-danger" for="is_survey"></label>
                                            <div class="errorMsg d-none">
                                                <div class="text-danger mb-3"> Please provide a copy of the existing survey to our office.</div>
                                                <label for="" class="mb-2"><b>Has there been any structural changes or improvements to the Property since the date of the Survey (such as new construction, fences, pools, driveways, etc.)?</b></label>
                                                <ul class="list-inline mb-0">
                                                    <li class="list-inline-item me-md-5">
                                                        <input type="radio" id="yesstructural" value="yes" name="is_structural" data-error="#is_structural-error" <?php echo ($is_structural == 'yes') ? 'checked="checked"' : '';?>> 
                                                        <label for="yesstructural">Yes</label>
                                                    </li>
                                                    <li class="list-inline-item">
                                                        <input type="radio" id="nostructural" value="no" name="is_structural" <?php echo ($is_structural == 'no') ? 'checked="checked"' : '';?>> 
                                                        <label for="nostructural">No</label>
                                                    </li>
                                                </ul>
                                                <label id="is_structural-error" class="error text-danger" for="is_structural"></label>
                                            </div>
                                        </div>
                                        <div class="form-group mb-4">
                                            <label for="" class="mb-2"><b>Do you have an existing Owner's Title Insurance Policy for the Property? (Note: Depending on your Sales Contract, the Seller could be penalized $150.00 for not delivering their current owner's title insurance policy to the Buyer within 10 days of the effective date of the Sales Contract.)</b></label>
                                            <ul class="list-inline mb-0">
                                                <li class="list-inline-item me-md-5">
                                                    <input type="radio" id="yesInsurance" value="yes" name="is_insurance" required data-error="#is_same_property_address_as_forwarding_address-error" <?php echo ($is_insurance == 'yes') ? 'checked="checked"' : '';?> > 
                                                    <label for="yesInsurance">Yes</label>
                                                </li>
                                                <li class="list-inline-item">
                                                    <input type="radio" id="noInsurance" value="no" name="is_insurance" <?php echo ($is_insurance == 'no') ? 'checked="checked"' : '';?>> 
                                                    <label for="noInsurance">No</label>
                                                </li>
                                            </ul>
                                            <label id="is_insurance-error" class="error text-danger" for="is_insurance"></label>
                                            <div class="text-danger d-none errorMsg">Please provide a copy of the existing Owner'sTitle Insurance Policy to our office.</div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group mb-4">
                                                    <label for="" class="mb-2"><b>Water Service (Please Check One): </b></label>
                                                    <ul class="list-unstyled">
                                                        <li>
                                                            <input type="radio" id="City" value="City" name="water_service" required data-error="#water_service-error" <?php echo ($water_service == 'City') ? 'checked="checked"' : '';?>> 
                                                            <label for="City">City</label>
                                                        </li>
                                                        <li>
                                                            <input type="radio" id="County" value="County" name="water_service" <?php echo ($water_service == 'County') ? 'checked="checked"' : '';?>> 
                                                            <label for="County">County</label>
                                                        </li>
                                                        <li>
                                                            <input type="radio" id="FGUA" value="FGUA" name="water_service" <?php echo ($water_service == 'FGUA') ? 'checked="checked"' : '';?>> 
                                                            <label for="FGUA">FGUA</label>
                                                        </li>
                                                        <li>
                                                            <input type="radio" id="Septic" value="Septic" name="water_service" <?php echo ($water_service == 'Septic') ? 'checked="checked"' : '';?>> 
                                                            <label for="Septic">Well / Septic</label>
                                                        </li>
                                                        <li>
                                                            <input type="radio" id="other" value="other" name="water_service" <?php echo ($water_service == 'other') ? 'checked="checked"' : '';?>> 
                                                            <label for="County"><input type="text" class="form-control" placeholder="Other" id="other_water_service_name" name="other_water_service_name" value="<?php echo $other_water_service_name;?>"></label>
                                                        </li>
                                                        <label id="water_service-error" class="error text-danger" for="water_service"></label>
                                                    </ul>
                                                   
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-4">
                                                    <label for="" class="mb-2">Water Service Provider Name</label>
                                                    <input type="text" class="form-control" id="water_service_provider_name" name="water_service_provider_name" required data-error="#water_service_provider_name-error" value="<?php echo $water_service_provider_name;?>">
                                                    <label id="water_service_provider_name-error" class="error text-danger" for="water_service_provider_name"></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item d-none" id="mortgage">
                                <h2 class="accordion-header" id="headingFive">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                        Mortgage Information
                                    </button>
                                </h2>
                                <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <h5><b>Please provide for all loans/mortgages against the property, including lines of credit, even if it has a zero balance.</b></h5>
                                        <div class="text-danger 
                                        mb-4">Note that you should continue to make mortgage payments that are due and payable prior to closing. Do not close (freeze) any lines of credit prior to closing
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group mb-4">
                                                    <label for="" class="mb-2">1st Bank / Mortgage Company</label>
                                                    <input type="text" class="form-control" id="first_mortgage_company" name="first_mortgage_company" data-error="#first_mortgage_company-error" value="<?php echo $first_mortgage_company;?>">
                                                    <label id="first_mortgage_company-error" class="error text-danger" for="first_mortgage_company"></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group mb-4">
                                                    <label for="" class="mb-2">Loan Number</label>
                                                    <input type="text" class="form-control" id="first_mortgage_loan_number" name="first_mortgage_loan_number" data-error="#first_mortgage_loan_number-error" value="<?php echo $first_mortgage_loan_number;?>">
                                                    <label id="first_mortgage_loan_number-error" class="error text-danger" for="first_mortgage_loan_number"></label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label for="" class="mb-2">Phone Number</label>
                                                    <div class="row">
                                                        <div class="col-4 position-relative mb-3">
                                                            <input type="text" class="form-control" id="first_mortgage_area_code" name="first_mortgage_area_code" data-error="#first_mortgage_area_code-error" value="<?php echo $first_mortgage_area_code;?>">
                                                            <small class="small_label">Area Code</small>
                                                        </div>
                                                        <label id="first_mortgage_area_code-error" class="error text-danger error2" for="first_mortgage_area_code"></label>
                                                        <div class="col-8 position-relative mb-3">
                                                            <input type="text" class="form-control" id="first_mortgage_phone_number" name="first_mortgage_phone_number" data-error="#first_mortgage_phone_number-error" value="<?php echo $first_mortgage_phone_number;?>">
                                                            <small class="small_label">Phone Number</small>
                                                        </div>
                                                        <label id="first_mortgage_phone_number-error" class="error text-danger error2" for="first_mortgage_phone_number"></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <hr>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group mb-4">
                                                    <label for="" class="mb-2">2nd Bank / Mortgage Company</label>
                                                    <input type="text" class="form-control" id="second_mortgage_company" name="second_mortgage_company" value="<?php echo $second_mortgage_company;?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group mb-4">
                                                    <label for="" class="mb-2">Loan Number</label>
                                                    <input type="text" class="form-control" id="second_mortgage_loan_number" name="second_mortgage_loan_number" value="<?php echo $second_mortgage_loan_number;?>">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label for="" class="mb-2">Phone Number</label>
                                                    <div class="row">
                                                        <div class="col-4 position-relative">
                                                            <input type="text" class="form-control" id="second_mortgage_area_code" name="second_mortgage_area_code" value="<?php echo $second_mortgage_area_code;?>">
                                                            <small class="small_label">Area Code</small>
                                                        </div>
                                                        <div class="col-8 position-relative">
                                                            <input type="text" class="form-control" id="second_mortgage_phone_number" name="second_mortgage_phone_number" value="<?php echo $second_mortgage_phone_number;?>">
                                                            <small class="small_label">Phone Number</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <hr>
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group mb-4">
                                                    <label for="" class="mb-2">3rd Bank / Mortgage Company</label>
                                                    <input type="text" class="form-control" id="third_mortgage_company" name="third_mortgage_company" value="<?php echo $third_mortgage_company;?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group mb-4">
                                                    <label for="" class="mb-2">Loan Number</label>
                                                    <input type="text" class="form-control" id="third_mortgage_loan_number" name="third_mortgage_loan_number" value="<?php echo $third_mortgage_loan_number;?>">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label for="" class="mb-2">Phone Number</label>
                                                    <div class="row">
                                                        <div class="col-4 position-relative">
                                                            <input type="text" class="form-control" id="third_mortgage_area_code" name="third_mortgage_area_code" value="<?php echo $third_mortgage_area_code;?>">
                                                            <small class="small_label">Area Code</small>
                                                        </div>
                                                        <div class="col-8 position-relative">
                                                            <input type="text" class="form-control" id="third_mortgage_phone_number" name="third_mortgage_phone_number" value="<?php echo $third_mortgage_phone_number;?>">
                                                            <small class="small_label">Phone Number</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item d-none" id="lien">
                                <h2 class="accordion-header" id="headingSix">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                                  Other Lien Information
                                    </button>
                                </h2>
                                <div id="collapseSix" class="accordion-collapse collapse" aria-labelledby="headingSix" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group mb-4">
                                                    <label for="" class="mb-2">Lienholder Name 1</label>
                                                    <input type="text" class="form-control" id="first_lien_holder_name" name="first_lien_holder_name" data-error="#first_lien_holder_name-error" value="<?php echo $first_lien_holder_name;?>">
                                                    <label id="first_lien_holder_name-error" class="error text-danger" for="first_lien_holder_name"></label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-4">
                                                    <label for="" class="mb-2">Amount Owed 1</label>
                                                    <input type="text" class="form-control" id="first_amount_owed" name="first_amount_owed" data-error="#first_amount_owed-error" value="<?php echo $first_amount_owed;?>">
                                                    <label id="first_amount_owed-error" class="error text-danger" for="first_amount_owed"></label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-4">
                                                    <label for="" class="mb-2">Lienholder Name 2</label>
                                                    <input type="text" class="form-control" id="second_lien_holder_name" name="second_lien_holder_name" value="<?php echo $second_lien_holder_name;?>">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-4">
                                                    <label for="" class="mb-2">Amount Owed 2</label>
                                                    <input type="text" class="form-control" id="second_amount_owed" name="second_amount_owed" value="<?php echo $second_amount_owed;?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item d-none" id="association">
                                <h2 class="accordion-header" id="headingSeven">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
                                      Association Information
                                    </button>
                                </h2>
                                <div id="collapseSeven" class="accordion-collapse collapse" aria-labelledby="headingSeven" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group mb-4">
                                                    <label for="" class="mb-2">Condominium / Homeowners Association 1</label>
                                                    <input type="text" class="form-control" id="first_homeowners_association" name="first_homeowners_association" data-error="#first_homeowners_association-error" value="<?php echo $first_homeowners_association;?>">
                                                    <label id="first_homeowners_association-error" class="error text-danger" for="first_homeowners_association"></label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-4">
                                                    <label for="" class="mb-2">Property Management Company</label>
                                                    <input type="text" class="form-control" id="first_property_management_company" name="first_property_management_company" data-error="#first_property_management_company-error" value="<?php echo $first_property_management_company;?>">
                                                    <label id="first_property_management_company-error" class="error text-danger" for="first_property_management_company"  ></label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-4">
                                                    <label for="" class="mb-2">Property Management Number</label>
                                                    <input type="text" class="form-control" id="first_property_management_number" name="first_property_management_number" data-error="#first_property_management_number-error" value="<?php echo $first_property_management_number;?>">
                                                    <label id="first_property_management_number-error" class="error text-danger" for="first_property_management_number" ></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group mb-4">
                                                    <label for="" class="mb-2">Condominium / Homeowners Association 2</label>
                                                    <input type="text" class="form-control" id="second_homeowners_association" name="second_homeowners_association" value="<?php echo $second_homeowners_association;?>">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-4">
                                                    <label for="" class="mb-2">Property Management Company</label>
                                                    <input type="text" class="form-control" id="second_property_management_company" name="second_property_management_company" value="<?php echo $second_property_management_company;?>">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-4">
                                                    <label for="" class="mb-2">Property Management Number</label>
                                                    <input type="text" class="form-control" id="second_property_management_number" name="second_property_management_number" value="<?php echo $second_property_management_number;?>">
                                                </div>
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
                                                        <input type="text" class="form-control" id="first_name" name="first_name" required data-error="#first_name-error" value="<?php echo $first_name;?>">
                                                        <small class="small_label">First Name</small>
                                                    </div>
                                                    <label id="first_name-error" class="error text-danger" for="first_name"></label>
                                                </div>
                                                <div class="col-lg-3 col-md-6">
                                                    <div class="form-group position-relative mb-3">
                                                        <label for="" class="mb-2"><b></b></label>
                                                        <input type="text" class="form-control" id="middle_name" name="middle_name" required data-error="#middle_name-error" value="<?php echo $middle_name;?>">
                                                        <small class="small_label">Middle Name</small>
                                                    </div>
                                                    <label id="middle_name-error" class="error text-danger" for="middle_name"></label>
                                                </div>
                                                <div class="col-lg-3 col-md-6">
                                                    <div class="form-group position-relative mb-3">
                                                        <label for="" class="mb-2"><b></b></label>
                                                        <input type="text" class="form-control" id="last_name" name="last_name" required data-error="#last_name-error" value="<?php echo $last_name;?>">
                                                        <small class="small_label">Last Name</small>
                                                    </div>
                                                    <label id="last_name-error" class="error text-danger" for="last_name"></label>
                                                </div>
                                                <div class="col-lg-3 col-md-6">
                                                    <div class="form-group position-relative mb-3">
                                                        <label for="" class="mb-2"><b></b></label>
                                                        <input type="text" class="form-control" id="maiden_name" name="maiden_name" required data-error="#maiden_name-error" value="<?php echo $maiden_name;?>">
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
                                                <input type="text" class="form-control" id="date_of_birth" name="date_of_birth" required data-error="#date_of_birth-error" value="<?php echo $date_of_birth;?>">
                                                <small class="small_label">Date of Birth</small>
                                            </div>
                                            <label id="date_of_birth-error" class="error text-danger" for="date_of_birth"></label>
                                        </div>
                                    </div>
                                        <div class="row mt-3">
                                            <div class="col-md-4">
                                                <div class="form-group position-relative mb-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" class="form-control" id="home_phone_number" name="home_phone_number" required data-error="#home_phone_number-error" value="<?php echo $home_phone_number;?>">
                                                    <small class="small_label">Home Phone</small>
                                                </div>
                                                <label id="home_phone_number-error" class="error text-danger" for="home_phone_number"></label>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group position-relative mb-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" class="form-control" id="business_phone_number" name="business_phone_number" required data-error="#business_phone_number-error" value="<?php echo $business_phone_number;?>">
                                                    <small class="small_label">Business Phone</small>
                                                </div>
                                                <label id="business_phone_number-error" class="error text-danger" for="business_phone_number"></label>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group position-relative mb-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" class="form-control" id="birthplace" name="birthplace" required data-error="#birthplace-error" value="<?php echo $birthplace;?>">
                                                    <small class="small_label">Birthplace</small>
                                                </div>
                                                <label id="birthplace-error" class="error text-danger" for="birthplace"></label>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" class="form-control" id="ssn" name="ssn" required data-error="#ssn-error" value="<?php echo $ssn;?>">
                                                    <small class="small_label">Social Security No.</small>
                                                </div>
                                                <label id="ssn-error" class="error text-danger" for="ssn"></label>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" class="form-control" id="driver_license_no" name="driver_license_no" required data-error="#driver_license_no-error" value="<?php echo $driver_license_no;?>">
                                                    <small class="small_label">Driver’s License No.</small>
                                                </div>
                                                <label id="driver_license_no-error" class="error text-danger" for="driver_license_no"></label>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-md-12">
                                                <div class="form-group position-relative mb-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" class="form-control" id="another_name_that_used" name="another_name_that_used" required data-error="#another_name_that_used-error" value="<?php echo $another_name_that_used;?>">
                                                    <small class="small_label">List any other name you have used or been known by</small>
                                                </div>
                                                <label id="another_name_that_used-error" class="error text-danger" for="another_name_that_used"></label>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" class="form-control" id="residence_state" name="residence_state" required data-error="#residence_state-error" value="<?php echo $residence_state;?>">
                                                    <small class="small_label">State of residence</small>
                                                </div>
                                                <label id="residence_state-error" class="error text-danger" for="residence_state"></label>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" class="form-control" id="lived_year" name="lived_year" required data-error="#lived_year-error" value="<?php echo $lived_year;?>">
                                                    <small class="small_label">I have lived continuously in the U.S.A. since</small>
                                                </div>
                                                <label id="lived_year-error" class="error text-danger" for="lived_year"></label>
                                            </div>
                                        </div>                                            
                                        <div class="mt-5">
                                            Are you currently married? <input type="checkbox" name="is_married" id="is_married" <?php echo  ($is_married == 'on') ? 'checked="checked"' : '';?>> If yes, complete the following information:
                                        </div>

                                        <div class="form-group position-relative mt-3 mb-3">
                                            <label for="" class="mb-2"><b></b></label>
                                            <input type="text" class="form-control" id="date_and_place_marriage" name="date_and_place_marriage" data-error="#date_and_place_marriage-error" value="<?php echo $date_and_place_marriage;?>">
                                            <small class="small_label">Date and place of marriage</small>
                                        </div>
                                        <label id="date_and_place_marriage-error" class="error text-danger d-flex" for="date_and_place_marriage"></label>  

                                        <div class="row mt-3">
                                            <div class="col-md-9">	
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-6">
                                                        <div class="form-group position-relative mb-3">
                                                            <label for="" class="mb-2"><b>Spouse:</b></label>
                                                            <input type="text" class="form-control" id="spouse_first_name" name="spouse_first_name" data-error="#spouse_first_name-error" value="<?php echo $spouse_first_name;?>">
                                                            <small class="small_label">First Name</small>
                                                        </div>
                                                        <label id="spouse_first_name-error" class="error text-danger" for="spouse_first_name"></label>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6">
                                                        <div class="form-group position-relative mb-3">
                                                            <label for="" class="mb-2"><b>&nbsp;</b></label>
                                                            <input type="text" class="form-control" id="spouse_middle_name" name="spouse_middle_name" data-error="#spouse_middle_name-error" value="<?php echo $spouse_middle_name;?>">
                                                            <small class="small_label">Middle Name</small>
                                                        </div>
                                                        <label id="spouse_middle_name-error" class="error text-danger" for="spouse_middle_name"></label>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6">
                                                        <div class="form-group position-relative mb-3">
                                                            <label for="" class="mb-2"><b>&nbsp;</b></label>
                                                            <input type="text" class="form-control" id="spouse_last_name" name="spouse_last_name" data-error="#spouse_last_name-error" value="<?php echo $spouse_last_name;?>">
                                                            <small class="small_label">Last Name</small>
                                                        </div>
                                                        <label id="spouse_last_name-error" class="error text-danger" for="spouse_last_name"></label>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6">
                                                        <div class="form-group position-relative mb-3">
                                                            <label for="" class="mb-2"><b>&nbsp;</b></label>
                                                            <input type="text" class="form-control" id="spouse_maiden_name" name="spouse_maiden_name" data-error="#spouse_maiden_name-error" value="<?php echo $spouse_maiden_name;?>">
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
                                                    <input type="text" class="form-control" id="spouse_date_of_birth" name="spouse_date_of_birth" data-error="#spouse_date_of_birth-error" value="<?php echo $spouse_date_of_birth;?>">
                                                    <small class="small_label">Date of Birth</small>
                                                </div>
                                                <label id="spouse_date_of_birth-error" class="error text-danger" for="spouse_date_of_birth"></label>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-md-4">
                                                <div class="form-group position-relative mb-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" class="form-control" id="spouse_home_phone_number" name="spouse_home_phone_number" data-error="#spouse_home_phone_number-error" value="<?php echo $spouse_home_phone_number;?>">
                                                    <small class="small_label">Home Phone</small>
                                                </div>
                                                <label id="spouse_home_phone_number-error" class="error text-danger" for="spouse_home_phone_number"></label>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group position-relative mb-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" class="form-control" id="spouse_business_phone_number" name="spouse_business_phone_number" data-error="#spouse_business_phone_number-error" value="<?php echo $spouse_business_phone_number;?>">
                                                    <small class="small_label">Business Phone</small>
                                                </div>
                                                <label id="spouse_business_phone_number-error" class="error text-danger" for="spouse_business_phone_number"></label>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group position-relative mb-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" class="form-control" id="spouse_birthplace" name="spouse_birthplace" data-error="#spouse_birthplace-error" value="<?php echo $spouse_birthplace;?>">
                                                    <small class="small_label">Birthplace</small>
                                                </div>
                                                <label id="spouse_birthplace-error" class="error text-danger" for="spouse_birthplace"></label>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" class="form-control" id="spouse_ssn" name="spouse_ssn" data-error="#spouse_ssn-error" value="<?php echo $spouse_ssn;?>">
                                                    <small class="small_label">Social Security No.</small>
                                                </div>
                                                <label id="spouse_ssn-error" class="error text-danger" for="spouse_ssn"></label>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" class="form-control" id="spouse_driver_license_no" name="spouse_driver_license_no" data-error="#spouse_driver_license_no-error" value="<?php echo $spouse_driver_license_no;?>">
                                                    <small class="small_label">Driver’s License No.</small>
                                                </div>
                                                <label id="spouse_driver_license_no-error" class="error text-danger" for="spouse_driver_license_no"></label>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-md-12">
                                                <div class="form-group position-relative mb-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" class="form-control" id="spouse_another_name_that_used" name="spouse_another_name_that_used" data-error="#spouse_another_name_that_used-error" value="<?php echo $spouse_another_name_that_used;?>">
                                                    <small class="small_label">List any other name you have used or been known by</small>
                                                </div>
                                                <label id="spouse_another_name_that_used-error" class="error text-danger" for="spouse_another_name_that_used"></label>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" class="form-control" id="spouse_state_residence" name="spouse_state_residence" data-error="#spouse_state_residence-error" value="<?php echo $spouse_state_residence;?>">
                                                    <small class="small_label">State of residence</small>
                                                </div>
                                                <label id="spouse_state_residence-error" class="error text-danger" for="spouse_state_residence"></label>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" class="form-control" id="spouse_lived_year" name="spouse_lived_year" data-error="#spouse_lived_year-error" value="<?php echo $spouse_lived_year;?>">
                                                    <small class="small_label">I have lived continuously in the U.S.A. since</small>
                                                </div>
                                                <label id="spouse_lived_year-error" class="error text-danger" for="spouse_lived_year"></label>
                                            </div>
                                        </div>                                            
                                        <div class="mt-5">
                                            Are you currently a registered domestic partner? <input type="checkbox" name="is_domestic_partner" id="is_domestic_partner" <?php echo  ($is_domestic_partner == 'on') ? 'checked="checked"' : '';?>> If yes, complete the following information:
                                        </div>

                                        <div class="row mt-3">
											<div class="col-md-12">
												<label for="" ><b>Domestic Partner:</b></label>
												<div class="row">
												<div class="col-md-9">	
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-6">
														
                                                        <div class="form-group position-relative mb-3">
                                                            <input type="text" class="form-control" id="domestic_first_name" name="domestic_first_name" data-error="#domestic_first_name-error" value="<?php echo $domestic_first_name;?>">
                                                            <small class="small_label">First Name</small>
                                                        </div>
                                                        <label id="domestic_first_name-error" class="error text-danger" for="domestic_first_name"></label>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6">
                                                        <div class="form-group position-relative mb-3">
                                                            
                                                            <input type="text" class="form-control" id="domestic_middle_name" name="domestic_middle_name" data-error="#domestic_middle_name-error" value="<?php echo $domestic_middle_name;?>">
                                                            <small class="small_label">Middle Name</small>
                                                        </div>
                                                        <label id="domestic_middle_name-error" class="error text-danger" for="domestic_middle_name"></label>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6">
                                                        <div class="form-group position-relative mb-3">
                                                            
                                                            <input type="text" class="form-control" id="domestic_last_name" name="domestic_last_name" data-error="#domestic_last_name-error" value="<?php echo $domestic_last_name;?>">
                                                            <small class="small_label">Last Name</small>
                                                        </div>
                                                        <label id="domestic_last_name-error" class="error text-danger" for="domestic_last_name"></label>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6">
                                                        <div class="form-group position-relative mb-3">
                                                            
                                                            <input type="text" class="form-control" id="domestic_maiden_name" name="domestic_maiden_name" data-error="#domestic_maiden_name-error" value="<?php echo $domestic_maiden_name;?>">
                                                            <small class="small_label">Maiden Name</small>
                                                        </div>
                                                        <label id="domestic_maiden_name-error" class="error text-danger" for="domestic_maiden_name"></label>
                                                    </div>
                                                </div>
                                                <div class="text-center mt-4 f14">(If none, indicate)</div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group position-relative mb-3">
                                                    
                                                    <input type="text" class="form-control" id="domestic_date_of_birth" name="domestic_date_of_birth" data-error="#domestic_date_of_birth-error" value="<?php echo $domestic_date_of_birth;?>">
                                                    <small class="small_label">Date of Birth</small>
                                                </div>
                                                <label id="domestic_date_of_birth-error" class="error text-danger" for="domestic_date_of_birth"></label>
                                            </div>
												</div>
											</div>
                                            
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-md-4">
                                                <div class="form-group position-relative mb-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" class="form-control" id="domestic_home_phone_number" name="domestic_home_phone_number" data-error="#domestic_home_phone_number-error" value="<?php echo $domestic_home_phone_number;?>">
                                                    <small class="small_label">Home Phone</small>
                                                </div>
                                                <label id="domestic_home_phone_number-error" class="error text-danger" for="domestic_home_phone_number"></label>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group position-relative mb-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" class="form-control" id="domestic_business_phone_number" name="domestic_business_phone_number" data-error="#domestic_business_phone_number-error" value="<?php echo $domestic_business_phone_number;?>">
                                                    <small class="small_label">Business Phone</small>
                                                </div>
                                                <label id="domestic_business_phone_number-error" class="error text-danger" for="domestic_business_phone_number"></label>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group position-relative mb-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" class="form-control" id="domestic_birthplace" name="domestic_birthplace" data-error="#domestic_birthplace-error" value="<?php echo $domestic_birthplace;?>">
                                                    <small class="small_label">Birthplace</small>
                                                </div>
                                                <label id="domestic_birthplace-error" class="error text-danger" for="domestic_birthplace"></label>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" class="form-control" id="domestic_ssn" name="domestic_ssn" data-error="#domestic_ssn-error" value="<?php echo $domestic_ssn;?>">
                                                    <small class="small_label">Social Security No.</small>
                                                </div>
                                                <label id="domestic_ssn-error" class="error text-danger" for="domestic_ssn"></label>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" class="form-control" id="domestic_driver_license_no" name="domestic_driver_license_no" data-error="#domestic_driver_license_no-error" value="<?php echo $domestic_driver_license_no;?>">
                                                    <small class="small_label">Driver’s License No.</small>
                                                </div>
                                                <label id="domestic_driver_license_no-error" class="error text-danger" for="domestic_driver_license_no"></label>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-md-12">
                                                <div class="form-group position-relative mb-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" class="form-control" id="domestic_another_name_that_used" name="domestic_another_name_that_used" data-error="#domestic_another_name_that_used-error value="<?php echo $domestic_another_name_that_used;?>"
                                                    <small class="small_label">List any other name you have used or been known by</small>
                                                </div>
                                                <label id="domestic_another_name_that_used-error" class="error text-danger" for="domestic_another_name_that_used"></label>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" class="form-control" id="domestic_state_residence" name="domestic_state_residence" data-error="#domestic_state_residence-error" value="<?php echo $domestic_state_residence;?>">
                                                    <small class="small_label">State of residence</small>
                                                </div>
                                                <label id="domestic_state_residence-error" class="error text-danger" for="domestic_state_residence"></label>
                                            </div>
                                            <div class="col-md-6"> 
                                                <div class="form-group position-relative mb-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" class="form-control" id="domestic_lived_year" name="domestic_lived_year" data-error="#domestic_lived_year-error" value="<?php echo $domestic_lived_year;?>">
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
                                                    <input type="text" class="form-control" id="residence_number_street" name="residence_number_street" required data-error="#residence_number_street-error" value="<?php echo $residence_number_street;?>">
                                                    <small class="small_label">Number &amp; Street</small>
                                                </div>
                                                <label id="residence_number_street-error" class="error text-danger" for="residence_number_street"></label>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group position-relative mb-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" class="form-control" id="residence_city" name="residence_city" required data-error="#residence_city-error" value="<?php echo $residence_city;?>">
                                                    <small class="small_label">City</small>
                                                </div>
                                                <label id="residence_city-error" class="error text-danger" for="residence_city"></label>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group position-relative mb-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" class="form-control" id="residence_from_date_to_date" name="residence_from_date_to_date" required data-error="#residence_from_date_to_date-error" value="<?php echo $residence_from_date_to_date;?>">
                                                    <small class="small_label">From (date) to (date)</small>
                                                </div>
                                                <label id="residence_from_date_to_date-error" class="error text-danger" for="residence_from_date_to_date"></label>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-md-4">
                                                <div class="form-group position-relative">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" class="form-control" id="second_residence_number_street" name="second_residence_number_street" value="<?php echo $second_residence_number_street;?>">
                                                    <small class="small_label">Number &amp; Street</small>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group position-relative">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" class="form-control" id="second_residence_city" name="second_residence_city" value="<?php echo $second_residence_city;?>">
                                                    <small class="small_label">City</small>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group position-relative">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" class="form-control" id="second_residence_from_date_to_date" name="second_residence_from_date_to_date" value="<?php echo $second_residence_from_date_to_date;?>">
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
                                                    <input type="text" class="form-control" id="firm_or_business_name" name="firm_or_business_name" required data-error="#firm_or_business_name-error" value="<?php echo $firm_or_business_name;?>">
                                                    <small class="small_label">Firm or Business name</small>
                                                </div>
                                                <label id="firm_or_business_name-error" class="error text-danger" for="firm_or_business_name"></label>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group position-relative mb-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" class="form-control" id="business_address" name="business_address" required data-error="#business_address-error" value="<?php echo $business_address;?>">
                                                    <small class="small_label">Address</small>
                                                </div>
                                                <label id="business_address-error" class="error text-danger" for="business_address"></label>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group position-relative mb-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" class="form-control" id="business_from_date_to_date" name="business_from_date_to_date" required data-error="#business_from_date_to_date-error" value="<?php echo $business_from_date_to_date;?>">
                                                    <small class="small_label">From (date) to (date)</small>
                                                </div>
                                                <label id="business_from_date_to_date-error" class="error text-danger" for="business_from_date_to_date"></label>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-md-4">
                                                <div class="form-group position-relative">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" class="form-control" id="second_firm_or_business_name" name="second_firm_or_business_name" value="<?php echo $second_firm_or_business_name;?>">
                                                    <small class="small_label">Firm or Business name</small>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group position-relative">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" class="form-control" id="second_business_address" name="second_business_address" value="<?php echo $second_business_address;?>">
                                                    <small class="small_label">Address</small>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group position-relative">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" class="form-control" id="second_business_from_date_to_date" name="second_business_from_date_to_date" value="<?php echo $second_business_from_date_to_date;?>">
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
                                                <input type="radio" id="yesProperty" value="Yes" <?php echo ($is_buyer_intends == 'Yes') ? 'checked="checked"' : '';?> name="is_buyer_intends" required data-error="#is_buyer_intends-error">
                                                <label for="yesProperty">Yes</label>
                                            </div>
                                            <div>
                                                <input type="radio" id="noPorperty" value="No" name="is_buyer_intends" <?php echo ($is_buyer_intends == 'No') ? 'checked="checked"' : '';?>>
                                                <label for="noPorperty">No</label>
                                            </div>
                                        </div>
                                        <label id="is_buyer_intends-error" class="error text-danger d-flex" for="is_buyer_intends"></label>

                                        <div class="mt-5 mb-2 text-center title_string"><b>*******************************************************************************************</b></div>

                                        <h5 class="text-center"><strong>Owner to complete the following items</strong></h5>

                                        <div class="mt-3 mb-3 text-center title_string"><b>*******************************************************************************************</b></div>

                                        <div class="form-group position-relative mb-3">
                                            <label for="" class="mb-2"><b></b></label>
                                            <input type="text" class="form-control" id="owner_street_address" name="owner_street_address" required data-error="#owner_street_address-error" value="<?php echo $owner_street_address;?>">
                                            <small class="small_label"> Street Address of Property in this transaction: </small>
                                        </div>
                                        <label id="owner_street_address-error" class="error text-danger d-flex" for="owner_street_address"></label>

                                        <div class="mt-4">
                                            The land is unimproved <input type="text" class="input_single w-small" id="unimproved" name="unimproved" value="<?php echo $unimproved;?>">; or improved with a structure of the following type:  A Single or 1-4 Family <input type="text" class="input_single w-small" id="single_family" name="single_family" value="<?php echo $single_family;?>"> Condo Unit <input type="text" class="input_single w-small" id="condo_unit" name="condo_unit" value="<?php echo $condo_unit;?>"> Other <input type="text" class="input_single w-small" id="other" name="other" value="<?php echo $other;?>"> 	
                                        </div>

                                        <div class="d-flex mt-3">
                                            <div class="me-3">
                                                Improvements, remodeling or repairs to this property have been made within the past six months: 
                                            </div>
                                            <div class="me-3">
                                                <input type="radio" name="is_improvement" id="yesImprovements" value="Yes" <?php echo ($is_improvement == 'Yes') ? 'checked="checked"' : '';?> required data-error="#is_improvement-error">
                                                <label for="yesImprovements">Yes</label>
                                            </div>
                                            <div>
                                                <input type="radio" name="is_improvement" id="noImprovements" value="No" <?php echo ($is_improvement == 'No') ? 'checked="checked"' : '';?>>
                                                <label for="noImprovements">No</label>
                                            </div>
                                        </div>
                                        <label id="is_improvement-error" class="error text-danger d-flex" for="is_improvement"></label>

                                        <div class="d-flex mt-3">
                                            <div class="me-3">
                                                If yes, have all costs for labor and materials arising in connection therewith been paid in full?
                                            </div>
                                            <div class="me-3">
                                                <input type="radio" name="is_materials" id="yesmaterials" value="Yes" required data-error="#is_materials-error" <?php echo ($is_materials == 'yes') ? 'checked="checked"' : '';?>>
                                                <label for="yesmaterials">Yes</label>
                                            </div>
                                            <div>
                                                <input type="radio" name="is_materials" id="nomaterials" value="No" <?php echo ($is_materials == 'no') ? 'checked="checked"' : '';?>>
                                                <label for="nomaterials">No</label>
                                            </div>
                                            
                                        </div>
                                        <label id="is_materials-error" class="error text-danger d-flex" for="is_materials"></label>

                                        <div class="mt-3">
                                            Any current loans on property? <input type="checkbox" name="is_loan" id="is_loan" <?php echo  ($is_loan == 'on') ? 'checked="checked"' : '';?>>; If yes, complete the following:
                                        </div>

                                        <div class="mt-3 row">
                                            <div class="col-md-4">
                                                <div class="form-group position-relative mb-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" class="form-control" id="lender" name="lender" data-error="#lender-error" value="<?php echo $lender;?>">
                                                    <small class="small_label">Lender</small>
                                                </div>
                                                <label id="lender-error" class="error text-danger" for="lender"></label>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group position-relative mb-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" class="form-control" id="loan_amount" name="loan_amount" data-error="#loan_amount-error" value="<?php echo $loan_amount;?>">
                                                    <small class="small_label">Loan Amount</small>
                                                </div>
                                                <label id="loan_amount-error" class="error text-danger" for="loan_amount"></label>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group position-relative mb-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" class="form-control" id="loan_account" name="loan_account" data-error="#loan_account-error" value="<?php echo $loan_account;?>">
                                                    <small class="small_label">Loan Account #</small>
                                                </div>
                                                <label id="loan_account-error" class="error text-danger" for="loan_account"></label>
                                            </div>
                                        </div>

                                        <div class="mt-3 row">
                                            <div class="col-md-4">
                                                <div class="form-group position-relative">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" class="form-control" id="second_lender" name="second_lender" value="<?php echo $second_lender;?>">
                                                    <small class="small_label">Lender</small>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group position-relative">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" class="form-control" id="second_loan_amount" name="second_loan_amount" value="<?php echo $second_loan_amount;?>">
                                                    <small class="small_label">Loan Amount</small>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group position-relative">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" class="form-control" id="second_loan_account" name="second_loan_account" value="<?php echo $second_loan_account;?>">
                                                    <small class="small_label">Loan Account #</small>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mt-4">The undersigned declare, under penalty of perjury, that the foregoing is true and correct.</div>

                                        <div class="mt-3 row">
                                            <div class="col-md-6">
                                                Executed on <input type="text" class="input_single" id="executed_date" name="executed_date" required data-error="#executed_date-error" value="<?php echo $executed_date;?>">, <input type="text" class="input_single w-medium" id="executed_year" name="executed_year" value="<?php echo $executed_year;?>">
                                                <label id="executed_date-error" class="error text-danger d-flex" for="executed_date"></label>
                                            </div>
                                            <div class="col-md-6">
                                                at <input type="text" class="input_single" id="executed_time" name="executed_time" required data-error="#executed_time-error" value="<?php echo $executed_time;?>">
                                                <label id="executed_time-error" class="error text-danger d-flex" for="executed_time"></label>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mt-5">
                                                    Signature :    
                                                    <input type="text" class="input_single" id="signature" name="signature" required data-error="#signature-error"  value="<?php echo $signature;?>">
                                                </div>
                                                <label id="signature-error" class="error text-danger" for="signature"></label>
                                            </div>
                                            <div class="col-md-6 text-md-end">
                                                <div class="mt-5">
                                                    Signature :    
                                                    <input type="text" class="input_single" id="second_signature" name="second_signature" required data-error="#second_signature-error" value="<?php echo $second_signature;?>">
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
                                <h2 class="accordion-header" id="headingEight">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEight" aria-expanded="false" aria-controls="collapseEight">
                                       (4) Vesting Form
                                    </button>
                                </h2>
                                <div id="collapseEight" class="accordion-collapse collapse" aria-labelledby="headingEight" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <div class="page">
                                            <div class="d-float">
                                                <div class="date_escrow_num mt-md-5">
                                                    <span>Date:</span>February 11, 2022<br><br>
                                                    <span>ESCROW NO.:</span>10257432-GLE-MP<br>
                                                    <span>TITLE NO.:</span>10257432-GLT-
                                                </div>
                                            </div>
                                            <p class="mt-3">
                                                YOU AS ESCROW HOLDER ARE AUTHORIZED TO SHOW VESTING ON THE GRANT DEED TO RECORD AS FOLLOWS:
                                            </p>
                                            
                                            <div class="form-group position-relative mb-4 mt-3">
                                                <label for="" class="mb-2"><b></b></label>
                                                <input type="text" class="form-control" id="names" name="names" required data-error="#names-error" value="<?php echo $names;?>">
                                                <small class="small_label">Names:</small>
                                            </div>
                                            <label id="names-error" class="error text-danger" for="names"></label>

                                            <div class="mb-4">PLEASE MARK APPROPRIATE CHOICE FOR STATUS: check for PICK-UP </div>
                                            <div class="d-flex align-items-center mb-2">
                                                <input type="checkbox" id="husbandWife" name="pick_ups[]" value="husbandWife" class="me-2" <?php echo (in_array('husbandWife', $pick_ups)) ? 'checked="checked"' : '';?>>
                                                <label for="husbandWife">Husband and Wife</label>                                                 
                                            </div>
                                            <div class="d-flex align-items-center mb-2">
                                                <input type="checkbox" id="wifeHusband" name="pick_ups[]" value="wifeHusband" class="me-2" <?php echo (in_array('wifeHusband', $pick_ups)) ? 'checked="checked"' : '';?>>
                                                <label for="wifeHusband">Wife and Husband</label>                                                 
                                            </div>
                                            <div class="d-flex align-items-center mb-2">
                                                <input type="checkbox" id="couple" name="pick_ups[]" value="couple" class="me-2" <?php echo (in_array('couple', $pick_ups)) ? 'checked="checked"' : '';?>>
                                                <label for="couple">A Married Couple</label>                                                 
                                            </div>
                                            <div class="d-flex align-items-center mb-2">
                                                <input type="checkbox" id="singleMan" name="pick_ups[]" value="singleMan" class="me-2" <?php echo (in_array('singleMan', $pick_ups)) ? 'checked="checked"' : '';?>>
                                                <label for="singleMan">A Single Man (never married)</label>                                                 
                                            </div>
                                            <div class="d-flex align-items-center mb-2">
                                                <input type="checkbox" id="singleWoman" name="pick_ups[]" value="singleWoman" class="me-2" <?php echo (in_array('singleWoman', $pick_ups)) ? 'checked="checked"' : '';?>>
                                                <label for="singleWoman">A Single Woman (never married)</label>                                                 
                                            </div>
                                            <div class="d-flex align-items-center mb-2">
                                                <input type="checkbox" id="singlePerson" name="pick_ups[]" value="singlePerson" class="me-2" <?php echo (in_array('singlePerson', $pick_ups)) ? 'checked="checked"' : '';?>>
                                                <label for="singlePerson">A Single Person (never married)</label>                                                 
                                            </div>
                                            <div class="d-flex align-items-center mb-2">
                                                <input type="checkbox" id="marriedMan" name="pick_ups[]" value="marriedMan" class="me-2" <?php echo (in_array('marriedMan', $pick_ups)) ? 'checked="checked"' : '';?>>
                                                <label for="marriedMan">A Married Man (as his sole and separate property)*</label>                                                 
                                            </div>
                                            <div class="d-flex align-items-center mb-2">
                                                <input type="checkbox" id="marriedWoman" name="pick_ups[]" value="marriedWoman" class="me-2 <?php echo (in_array('marriedWoman', $pick_ups)) ? 'checked="checked"' : '';?>
                                                <label for="marriedWoman">A Married Woman (as her sole and separate property)*</label>                                                 
                                            </div>
                                            <div class="d-flex align-items-center mb-2">
                                                <input type="checkbox" id="marriedPerson" name="pick_ups[]" value="marriedPerson" class="me-2" <?php echo (in_array('marriedPerson', $pick_ups)) ? 'checked="checked"' : '';?>>
                                                <label for="marriedPerson">A Married Person (as his/her sole and separate property)*</label>                                                 
                                            </div>
                                            <div class="mb-2">
                                                <label for="interspousal">* Please indicate name of spouse so interspousal deed may be drawn:</label>  
                                                <input type="text" class="form-control" id="names_of_spouse" name="names_of_spouse" required data-error="#names_of_spouse-error" value="<?php echo $names_of_spouse;?>">                                               
                                            </div>
                                            <label id="names_of_spouse-error" class="error text-danger" for="names_of_spouse"></label>
                                            <div class="d-flex align-items-center mb-2">
                                                <input type="checkbox" id="unmarriedMan" name="pick_ups[]" value="unmarriedMan" class="me-2" <?php echo (in_array('unmarriedMan', $pick_ups)) ? 'checked="checked"' : '';?>>
                                                <label for="unmarriedMan">An Unmarried Man (divorced)</label>                                                 
                                            </div>
                                            <div class="d-flex align-items-center mb-2">
                                                <input type="checkbox" id="unmarriedWoman" name="pick_ups[]" value="unmarriedWoman" class="me-2" <?php echo (in_array('unmarriedWoman', $pick_ups)) ? 'checked="checked"' : '';?>>
                                                <label for="unmarriedWoman">An Unmarried Woman (divorced)</label>                                                 
                                            </div>
                                            <div class="d-flex align-items-center mb-2">
                                                <input type="checkbox" id="unmarriedPerson" name="pick_ups[]" value="unmarriedPerson" class="me-2" <?php echo (in_array('unmarriedPerson', $pick_ups)) ? 'checked="checked"' : '';?>>
                                                <label for="unmarriedPerson">An Unmarried Person (divorced)</label>                                                 
                                            </div>
                                            <div class="d-flex align-items-center mb-2">
                                                <input type="checkbox" id="widow" name="pick_ups[]" value="widow" class="me-2" <?php echo (in_array('widow', $pick_ups)) ? 'checked="checked"' : '';?>>
                                                <label for="widow">A Widow (spouse deceased)</label>                                                 
                                            </div>
                                            <div class="d-flex align-items-center mb-2">
                                                <input type="checkbox" id="widower" name="pick_ups[]" value="widower" class="me-2" <?php echo (in_array('widower', $pick_ups)) ? 'checked="checked"' : '';?>>
                                                <label for="widower">A Widower (spouse deceased)</label>                                                 
                                            </div>
                                            <div class="d-flex align-items-center mb-2">
                                                <input type="checkbox" id="domestic" name="pick_ups[]" value="domestic" class="me-2" <?php echo (in_array('domestic', $pick_ups)) ? 'checked="checked"' : '';?>>
                                                <label for="domestic">Registered Domestic Partners</label>                                                 
                                            </div>
                                            <div class="mb-3 mt-3"><b>PLEASE MARK APPROPRIATE CHOICE FOR VESTING:</b></div>
                                            <div class="d-flex align-items-center mb-2">
                                                <input type="checkbox" id="community" name="appropriate_choice[]" value="community" class="me-2" <?php echo (in_array('community', $appropriate_choice)) ? 'checked="checked"' : '';?>>
                                                <label for="community">Community Property</label>                                                 
                                            </div>
                                            <div class="d-flex align-items-center mb-2">
                                                <input type="checkbox" id="survivorship" name="appropriate_choice[]" value="survivorship" class="me-2" <?php echo (in_array('survivorship', $appropriate_choice)) ? 'checked="checked"' : '';?>>
                                                <label for="survivorship">Community Property with Right of Survivorship</label>                                                 
                                            </div>
                                            <div class="d-flex align-items-center mb-2">
                                                <input type="checkbox" id="tenants" name="appropriate_choice[]" value="tenants" class="me-2" <?php echo (in_array('tenants', $appropriate_choice)) ? 'checked="checked"' : '';?>>
                                                <label for="tenants">Joint Tenants</label>                                                 
                                            </div>
                                            <div class="d-flex align-items-center mb-2">
                                                <input type="checkbox" id="internetAmount" name="appropriate_choice[]" value="internetAmount" class="me-2" <?php echo (in_array('internetAmount', $appropriate_choice)) ? 'checked="checked"' : '';?>>
                                                <label for="internetAmount">Tenants In Common (Please Give Interest Amounts)</label>                                                 
                                            </div>
                                            <div class="d-flex align-items-center mb-2">
                                                <input type="checkbox" id="partnership" name="appropriate_choice[]" value="" class="me-2" <?php echo (in_array('partnership', $appropriate_choice)) ? 'checked="checked"' : '';?>>
                                                <label for="partnership">Sole and Separate Property (If Married or Domestic Partnership, an Interspousal Grant Deed, A Quitclaim Deed, Statement Of Information and Appropriate Instructions Will Need To Be Submitted.)</label>                                                 
                                            </div>
                                            <div class="d-flex align-items-center mb-2">
                                                <input type="checkbox" id="general" name="appropriate_choice[]" value="general" class="me-2" <?php echo (in_array('general', $appropriate_choice)) ? 'checked="checked"' : '';?>>
                                                <label for="general">Partnership (Limited Or General)</label>     
                                                <input type="text" class="form-control w-50 ms-3" id="partnership_name" name="partnership_name" value="<?php echo $partnership_name;?>">                                            
                                            </div>
                                            <div class="d-flex align-items-center mb-2">
                                                <input type="checkbox" id="corporation" name="appropriate_choice[]" value="corporation" class="me-2" <?php echo (in_array('corporation', $appropriate_choice)) ? 'checked="checked"' : '';?>>
                                                <label for="corporation">Corporation (California Or Other State)</label>     
                                                <input type="text" class="form-control w-50 ms-3" id="corporation_name" name="corporation_name" value="<?php echo $corporation_name;?>">                                            
                                            </div>
                                            <div class="d-flex align-items-center mb-2">
                                                <input type="checkbox" id="trust" name="appropriate_choice[]" value="trust" class="me-2" <?php echo (in_array('trust', $appropriate_choice)) ? 'checked="checked"' : '';?>>
                                                <label for="trust">A Trust (attach copy of Trust Agreement)</label>                                     
                                            </div>
                                            <div class="d-flex align-items-center mb-2">
                                                <input type="checkbox" id="other" name="appropriate_choice[]" value="other" class="me-2" <?php echo (in_array('other', $appropriate_choice)) ? 'checked="checked"' : '';?>>
                                                <label for="other">Other</label>                                     
                                            </div>

                                            <div class="mt-5 mb-5">
                                                Escrow Holder advises the parties hereto to seek legal counsel with their attorney and/or accountant as to how they should hold title.
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mt-5">
                                                        Signature :    
                                                        <input type="text" class="input_single" id="vesting_form_signature" name="vesting_form_signature" required data-error="#vesting_form_signature-error" value="<?php echo $vesting_form_signature;?>">
                                                    </div>
                                                    <label id="vesting_form_signature-error" class="error text-danger" for="vesting_form_signature"></label>
                                                </div>
                                                <div class="col-md-6 text-md-end">
                                                    <div class="mt-5">
                                                        Date :    
                                                        <input type="text" class="input_single" id="vesting_form_date" name="vesting_form_date" required data-error="#vesting_form_date-error" value="<?php echo $vesting_form_date;?>">
                                                    </div>
                                                    <label id="vesting_form_date-error" class="error text-danger" for="vesting_form_date"></label>
                                                </div> 
                                            </div>                                
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingNine">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseNine" aria-expanded="false" aria-controls="collapseNine">
                                       (5) Preliminary Change of Ownership
                                    </button>
                                </h2>
                                <div id="collapseNine" class="accordion-collapse collapse" aria-labelledby="headingNine" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <h2 class="text-center my-4"><strong>PRELIMINARY CHANGE OF OWNERSHIP REPORT</strong></h2>
                                        <p>
                                            To be completed by the transferee (buyer) prior to a transfer of subject property, in accordance with section 480.3 of the Revenue and Taxation Code. A Preliminary Change of Ownership Report must <b>be filed with each conveyance in the County Recorder’s office for the county where the property is located.</b>
                                        </p>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <small>
                                                    NAME AND MAILING ADDRESS OF BUYER/TRANSFEREE <br> (Make necessary corrections to the printed name and mailing address)
                                                </small>
                                                <textarea name="address" rows="6" id="address" class="form-control mt-3" style="height:auto !important" readonly>
                                                        Mordechai Citronenbaum  
                                                        48 Hauser Blvd. #1-110 
                                                        Los Angeles, CA 90036
                                                </textarea>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3">
                                                    <input type="text" class="form-control f700 f12" value="" id="assessors_parcel_number" name="assessors_parcel_number" required data-error="#assessors_parcel_number-error" value="<?php echo $assessors_parcel_number;?>">
                                                    <small class="small_label">ASSESSOR'S PARCEL NUMBER</small>
                                                </div>
                                                <label id="assessors_parcel_number-error" class="error text-danger error2" for="assessors_parcel_number"></label>
                                                <div class="form-group position-relative mb-3">
                                                    <input type="text" class="form-control f700 f12" id="transferor" name="transferor" required data-error="#transferor-error" value="<?php echo $transferor;?>">
                                                    <small class="small_label">SELLER/TRANSFEROR</small>
                                                </div>
                                                <label id="transferor-error" class="error text-danger error2" for="transferor"></label>
                                                <div class="form-group position-relative mb-3">
                                                    <input type="text" class="form-control f700 f12" id="buyer_daytime_phone_number" name="buyer_daytime_phone_number" required data-error="#buyer_daytime_phone_number-error" value="<?php echo $buyer_daytime_phone_number;?>">
                                                    <small class="small_label">BUYER'S DAYTIME TELEPHONE NUMBER</small>
                                                </div>
                                                <label id="buyer_daytime_phone_number-error" class="error text-danger error2" for="buyer_daytime_phone_number"></label>
                                                <div class="form-group position-relative mb-3">
                                                    <input type="text" class="form-control f700 f12" id="buyer_email_address" name="buyer_email_address" required data-error="#buyer_email_address-error" value="<?php echo $buyer_email_address;?>">
                                                    <small class="small_label">BUYER'S EMAIL ADDRESS</small>
                                                </div>
                                                <label id="buyer_email_address-error" class="error text-danger error2" for="buyer_email_address"></label>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group position-relative mb-3">
                                            <input type="text" class="form-control f700 f12" value="" id="real_property_addres" name="real_property_addres" required data-error="#real_property_addres-error" value="<?php echo $real_property_addres;?>">
                                            <small class="small_label">STREET ADDRESS OR PHYSICAL LOCATION OF REAL PROPERTY </small>
                                        </div>
                                        <label id="real_property_addres-error" class="error text-danger error2" for="real_property_addres"></label>

                                        <table class="table yesno_table">
                                            <tr>
                                                <th><b>YES</b></th>
                                                <th><b>NO</b></th>
                                                <th></th>
                                            </tr>
                                            <tr>
                                                <td><input type="radio" name="is_principal_residence" id="checkYes0" value="yes" required data-error="#is_principal_residence-error" <?php echo ($is_principal_residence == 'yes') ? 'checked="checked"' : '';?>></td>
                                                <td><input type="radio" name="is_principal_residence" id="checkNo0" value="no" <?php echo ($is_principal_residence == 'no') ? 'checked="checked"' : '';?>></td>
                                                <td>
                                                    This property is intended as my principal residence. If YES, please indicate the date of occupancy or intended occupancy.
                                                    <div class="d-flex date_group">
                                                        <input type="text" class="form-control" placeholder="MO" id="intended_occupancy_month" name="intended_occupancy_month" value="<?php echo $intended_occupancy_month;?>">
                                                        <input type="text" class="form-control" placeholder="DAY" id="intended_occupancy_day" name="intended_occupancy_day" value="<?php echo $intended_occupancy_day;?>">
                                                        <input type="text" class="form-control" placeholder="YEAR" id="intended_occupancy_year" name="intended_occupancy_year" value="<?php echo $intended_occupancy_year;?>">
                                                    </div>
                                                    <label id="is_principal_residence-error" class="error text-danger" for="is_principal_residence"></label>
                                                </td>
                                            </tr>
                                            
                                            <tr>
                                                <td><input type="radio" name="is_disabled_veteran" id="checkYes1" value="yes" required data-error="#is_disabled_veteran-error" <?php echo ($is_disabled_veteran == 'yes') ? 'checked="checked"' : '';?>></td>
                                                <td><input type="radio" name="is_disabled_veteran" id="checkNo1" value="no" <?php echo ($is_disabled_veteran == 'no') ? 'checked="checked"' : '';?>></td>
                                                <td>
                                                    Are you a disabled veteran or a unmarried surviving spouse of a disabled veteran who was compensated at 100% by the Department of Veterans Affairs?
                                                    <label id="is_disabled_veteran-error" class="error text-danger d-flex" for="is_disabled_veteran"></label>
                                                </td>
                                            </tr>
                                        </table>

                                        <div class="form-group position-relative mb-3">
                                            <input type="text" class="form-control f700 f12" value="" id="mail_property_tax_name" name="mail_property_tax_name" required data-error="#mail_property_tax_name-error" value="<?php echo $mail_property_tax_name;?>">
                                            <small class="small_label">MAIL PROPERTY TAX INFORMATION TO (NAME)</small>
                                        </div>
                                        <label id="mail_property_tax_name-error" class="error text-danger error2" for="mail_property_tax_name"></label>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3">
                                                    <input type="text" class="form-control f700 f12" value="" id="mail_property_tax_address" name="mail_property_tax_address" required data-error="#mail_property_tax_address-error" value="<?php echo $mail_property_tax_address;?>">
                                                    <small class="small_label">MAIL PROPERTY TAX INFORMATION TO (ADDRESS)</small>
                                                </div>
                                                <label id="mail_property_tax_address-error" class="error text-danger error2" for="mail_property_tax_address"></label>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3">
                                                    <input type="text" class="form-control f700 f12" value="" id="mail_property_tax_city" name="mail_property_tax_city" required data-error="#mail_property_tax_city-error" value="<?php echo $mail_property_tax_city;?>">
                                                    <small class="small_label">CITY </small>
                                                </div>
                                                <label id="mail_property_tax_city-error" class="error text-danger error2" for="mail_property_tax_city"></label>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3">
                                                    <input type="text" class="form-control f700 f12" value="" id="mail_property_tax_state" name="mail_property_tax_state" required data-error="#mail_property_tax_state-error" value="<?php echo $mail_property_tax_state;?>">
                                                    <small class="small_label">STATE </small>
                                                </div>
                                                <label id="mail_property_tax_state-error" class="error text-danger error2" for="mail_property_tax_state"></label>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3">
                                                    <input type="text" class="form-control f700 f12" value="" id="mail_property_tax_zipcode" name="mail_property_tax_zipcode" required data-error="#mail_property_tax_zipcode-error" value="<?php echo $mail_property_tax_zipcode;?>">
                                                    <small class="small_label">ZIP CODE</small>
                                                </div>
                                                <label id="mail_property_tax_zipcode-error" class="error text-danger error2" for="mail_property_tax_zipcode"></label>
                                            </div>
                                        </div>

                                        <div class="text-center mt-5">
                                            <h5 class="f600">
                                                PART 1. TRANSFER INFORMATION
                                            </h5>
                                            <em> Please complete all statements.</em>
                                            <p>This section contains possible exclusions from reassessment for certain types of transfers.</p>
                                        </div>

                                        <table class="table yesno_table">
                                            <tr>
                                                <th></th>
                                                <th><b>YES</b></th>
                                                <th><b>NO</b></th>
                                                <th></th>
                                            </tr>
                                            <tr>
                                                <td>A.</td>
                                                <td><input type="radio" name="is_transfer_between_spouses" id="checkYes2" value="yes" required data-error="#is_transfer_between_spouses-error" <?php echo ($is_transfer_between_spouses == 'yes') ? 'checked="checked"' : '';?>></td>
                                                <td><input type="radio" name="is_transfer_between_spouses" id="checkNo2" value="no" <?php echo ($is_transfer_between_spouses == 'no') ? 'checked="checked"' : '';?>></td>
                                                <td>
                                                    This transfer is solely between spouses (addition or removal of a spouse, death of a spouse, divorce settlement, etc.).
                                                    <label id="is_transfer_between_spouses-error" class="error text-danger d-flex" for="is_transfer_between_spouses"></label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>B.</td>
                                                <td><input type="radio" name="is_transfer_between_domestic_partners" id="checkYes3" value="yes" required data-error="#is_transfer_between_domestic_partners-error" <?php echo ($is_transfer_between_domestic_partners == 'yes') ? 'checked="checked"' : '';?>></td>
                                                <td><input type="radio" name="is_transfer_between_domestic_partners" id="checkNo3" value="no" id="second_buyer_middle_name" <?php echo ($is_transfer_between_domestic_partners == 'no') ? 'checked="checked"' : '';?>></td>
                                                <td>
                                                    This transfer is solely between domestic partners currently registered with the California Secretary of State <em>
                                                        (addition or removal of a partner, death of a partner, termination settlement, etc.). 
                                                    </em>
                                                    <label id="is_transfer_between_domestic_partners-error" class="error text-danger d-flex" for="is_transfer_between_domestic_partners"></label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>C.</td>
                                                <td><input type="radio" name="is_transfer" id="checkYes4" value="yes" required data-error="#is_transfer-error" <?php echo ($is_transfer == 'yes') ? 'checked="checked"' : '';?>></td>
                                                <td><input type="radio" name="is_transfer" id="checkNo4" value="no" <?php echo ($is_transfer == 'no') ? 'checked="checked"' : '';?>></td>
                                                <td>
                                                    This is a transfer:
                                                    <label id="is_transfer-error" class="error text-danger d-flex" for="is_transfer"></label>
                                                    <div class="d-flex">
                                                        <input type="radio" name="is_parent_child_transfer" id="parentchild1" value="" class="me-2" required data-error="#is_parent_child_transfer-error" <?php echo ($is_parent_child_transfer == 'yes') ? 'checked="checked"' : '';?>> <label for="parentchild1">between parent(s) and child(ren)</label>
                                                    </div>
                                                    <div class="d-flex">
                                                        <input type="radio" name="is_parent_child_transfer" id="parentchild2" value="no" class="me-2" <?php echo ($is_parent_child_transfer == 'no') ? 'checked="checked"' : '';?>> <label for="parentchild2">between grandparent(s) and grandchild(ren).</label>
                                                    </div>
                                                    <label id="is_parent_child_transfer-error" class="error text-danger d-flex" for="is_parent_child_transfer"></label>
                                                    <div class="mt-2">
                                                        Was this the transferor/grantor's principal residence? &nbsp;
                                                        <input type="radio" name="is_principal_residence" id="principal1" value="yes" class="me-2"  required data-error="#is_principal_residence-error" <?php echo ($is_principal_residence == 'yes') ? 'checked="checked"' : '';?>> <label for="principal1">YES</label>
                                                        <input type="radio" name="is_principal_residence" id="principal2" value="no" class="me-2" <?php echo ($is_principal_residence == 'no') ? 'checked="checked"' : '';?>>NO</label>
                                                    </div>
                                                    <label id="is_principal_residence-error" class="error text-danger d-flex" for="is_principal_residence"></label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>D.</td>
                                                <td><input type="radio" name="is_cotenant_death" id="checkYes5" value="yes" required data-error="#is_cotenant_death-error" <?php echo ($is_cotenant_death == 'yes') ? 'checked="checked"' : '';?>></td>
                                                <td><input type="radio" name="is_cotenant_death" id="checkNo5" value="no" <?php echo ($is_cotenant_death == 'no') ? 'checked="checked"' : '';?>></td>
                                                <td>
                                                    This transfer is the result of a cotenant’s death. Date of death <input type="text" class="input_single" name="date_of_death" id="date_of_death" value="<?php echo $date_of_death;?>">
                                                    <label id="is_cotenant_death-error" class="error text-danger d-flex" for="is_cotenant_death"></label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>E.</td>
                                                <td><input type="radio" name="is_replace_principal_residence_own" id="checkYes6" value="yes" required data-error="#is_replace_principal_residence_own-error" <?php echo ($is_replace_principal_residence_own == 'yes') ? 'checked="checked"' : '';?>></td>
                                                <td><input type="radio" name="is_replace_principal_residence_own" id="checkNo6" value="no" <?php echo ($is_replace_principal_residence_own == 'no') ? 'checked="checked"' : '';?>></td>
                                                <td>
                                                    This transaction is to replace a principal residence owned by a person 55 years of age or older.<br>
                                                    <label id="is_replace_principal_residence_own-error" class="error text-danger d-flex" for="is_replace_principal_residence_own"></label>
                                                    Within the same county?  &nbsp;
                                                    <input type="radio" name="is_replace_principal_residence_own_in_same_county" id="sameCountry1" class="me-2" value="yes" required data-error="#is_replace_principal_residence_own_in_same_county-error" <?php echo ($is_replace_principal_residence_own_in_same_county == 'yes') ? 'checked="checked"' : '';?>> <label for="sameCountry1">YES</label>
                                                    <input type="radio" name="is_replace_principal_residence_own_in_same_county" id="sameCountry2" class="me-2" value="no" <?php echo ($is_replace_principal_residence_own_in_same_county == 'no') ? 'checked="checked"' : '';?>> <label for="sameCountry2">NO</label>
                                                    <label id="is_replace_principal_residence_own_in_same_county-error" class="error text-danger d-flex" for="is_replace_principal_residence_own_in_same_county"></label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>F.</td>
                                                <td><input type="radio" name="is_replace_principal_residence_person_disabled" id="checkYes7" value="yes" required data-error="#is_replace_principal_residence_person_disabled-error" <?php echo ($is_replace_principal_residence_person_disabled == 'yes') ? 'checked="checked"' : '';?>></td>
                                                <td><input type="radio" name="is_replace_principal_residence_person_disabled" id="checkNo7" value="no" <?php echo ($is_replace_principal_residence_person_disabled == 'no') ? 'checked="checked"' : '';?>></td>
                                                <td>
                                                    This transaction is to replace a principal residence by a person who is severely disabled.<br>
                                                    <label id="is_replace_principal_residence_person_disabled-error" class="error text-danger d-flex" for="is_replace_principal_residence_person_disabled"></label>
                                                    Within the same county?  &nbsp;
                                                    <input type="radio" name="is_replace_principal_residence_person_disabled_in_same_county" id="severeDisabled1" class="me-2" value="yes" required data-error="#is_replace_principal_residence_person_disabled_in_same_county-error" <?php echo ($is_replace_principal_residence_person_disabled_in_same_county == 'yes') ? 'checked="checked"' : '';?>> <label for="severeDisabled1">YES</label>
                                                    <input type="radio" name="is_replace_principal_residence_person_disabled_in_same_county" id="severeDisabled2" class="me-2" value="no" <?php echo ($is_replace_principal_residence_person_disabled_in_same_county == 'no') ? 'checked="checked"' : '';?>> <label for="severeDisabled2">NO</label>
                                                    <label id="is_replace_principal_residence_person_disabled_in_same_county-error" class="error text-danger d-flex" for="is_replace_principal_residence_person_disabled_in_same_county"></label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>G.</td>
                                                <td><input type="radio" name="is_replace_principal_residence_damaged" id="checkYes8" value="yes" required data-error="#is_replace_principal_residence_damaged-error" <?php echo ($is_replace_principal_residence_damaged == 'yes') ? 'checked="checked"' : '';?>></td>
                                                <td><input type="radio" name="is_replace_principal_residence_damaged" id="checkNo8" value="no" <?php echo ($is_replace_principal_residence_damaged == 'no') ? 'checked="checked"' : '';?>></td>
                                                <td>
                                                    This transaction is to replace a principal residence substantially damaged or destroyed by a wildfire or natural disaster for which the Governor proclaimed a state of emergency. <br>
                                                    <label id="is_replace_principal_residence_damaged-error" class="error text-danger d-flex" for="is_replace_principal_residence_damaged"></label>
                                                    Within the same county?  &nbsp;
                                                    <input type="radio" name="is_replace_principal_residence_damaged_in_same_county" id="damage1" class="me-2" value="yes" required data-error="#is_replace_principal_residence_damaged_in_same_county-error" <?php echo ($is_replace_principal_residence_damaged_in_same_county == 'yes') ? 'checked="checked"' : '';?>> <label for="damage1">YES</label>
                                                    <input type="radio" name="is_replace_principal_residence_damaged_in_same_county" id="damage2" class="me-2" value="no" <?php echo ($is_replace_principal_residence_damaged_in_same_county == 'no') ? 'checked="checked"' : '';?>> <label for="damage2">NO</label>
                                                    <label id="is_replace_principal_residence_damaged_in_same_county-error" class="error text-danger d-flex" for="is_replace_principal_residence_damaged_in_same_county"></label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>H.</td>
                                                <td><input type="radio" name="is_name_change" id="checkYes9" value="yes" required data-error="#is_name_change-error" <?php echo ($is_name_change == 'yes') ? 'checked="checked"' : '';?>></td>
                                                <td><input type="radio" name="is_name_change" id="checkNo9" value="no" <?php echo ($is_name_change == 'no') ? 'checked="checked"' : '';?>></td>
                                                <td>
                                                    This transaction is only a correction of the name(s) of the person(s) holding title to the property (e.g., a name change
                                                    upon marriage). If YES, please explain:  &nbsp;
                                                    <input type="text" class="input_single" name="name_change_reason" id="name_change_reason" value="<?php echo $name_change_reason;?>">
                                                    <label id="is_name_change-error" class="error text-danger d-flex" for="is_name_change"></label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>I.</td>
                                                <td><input type="radio" name="is_lender_interest" id="checkYes10" value="yes" required data-error="#is_lender_interest-error" <?php echo ($is_lender_interest == 'yes') ? 'checked="checked"' : '';?>></td>
                                                <td><input type="radio" name="is_lender_interest" id="checkNo10" value="no" <?php echo ($is_lender_interest == 'no') ? 'checked="checked"' : '';?>></td>
                                                <td>
                                                    The recorded document creates, terminates, or reconveys a lender's interest in the property. &nbsp;
                                                    <input type="text" class="input_single" name="lender_interest_reason" id="lender_interest_reason" value="<?php echo $lender_interest_reason;?>">
                                                    <label id="is_lender_interest-error" class="error text-danger d-flex" for="is_lender_interest"></label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>J.</td>
                                                <td><input type="radio" name="is_financing_purpose" id="checkYes11" value="yes" required data-error="#is_financing_purpose-error" <?php echo ($is_financing_purpose == 'yes') ? 'checked="checked"' : '';?>></td>
                                                <td><input type="radio" name="is_financing_purpose" id="checkNo11" value="no" <?php echo ($is_financing_purpose == 'no') ? 'checked="checked"' : '';?>></td>
                                                <td>
                                                    This transaction is recorded only as a requirement for financing purposes or to create, terminate, or reconvey a security
                                                    interest (e.g., cosigner). If YES, please explain:  &nbsp;
                                                    <input type="text" class="input_single" name="financing_purpose_reason" id="financing_purpose_reason" value="<?php echo $financing_purpose_reason;?>">
                                                    <label id="is_financing_purpose-error" class="error text-danger d-flex" for="is_financing_purpose"></label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>K.</td>
                                                <td><input type="radio" name="is_trustee_of_trust" id="checkYes12" value="yes" required data-error="#is_trustee_of_trust-error" <?php echo ($is_trustee_of_trust == 'yes') ? 'checked="checked"' : '';?>></td>
                                                <td><input type="radio" name="is_trustee_of_trust" id="checkNo12" value="no" <?php echo ($is_trustee_of_trust == 'no') ? 'checked="checked"' : '';?>></td>
                                                <td>
                                                    The recorded document substitutes a trustee of a trust, mortgage, or other similar document.   &nbsp;
                                                    <label id="is_trustee_of_trust-error" class="error text-danger d-flex" for="is_trustee_of_trust"></label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>L.</td>
                                                <td><input type="radio" name="is_transfer_property" id="checkYes13" value="yes" required data-error="#is_transfer_property-error" <?php echo ($is_transfer_property == 'yes') ? 'checked="checked"' : '';?>></td>
                                                <td><input type="radio" name="is_transfer_property" id="checkNo13" value="no" <?php echo ($is_transfer_property == 'no') ? 'checked="checked"' : '';?>></td>
                                                <td>
                                                    This is a transfer of property:
                                                    <label id="is_transfer_property-error" class="error text-danger d-flex" for="is_transfer_property"></label>
                                                    <div class="pl-3 mb-3">
                                                        1. to/from a revocable trust that may be revoked by the transferor and is for the benefit of <br>
                                                        <input type="radio" name="benefit" id="benefit1" class="me-2" value="transferor" required data-error="#benefit-error" <?php echo ($benefit == 'transferor') ? 'checked="checked"' : '';?>> <label for="benefit1">the transferor, and/or</label>
                                                        <input type="radio" name="benefit" id="benefit2" class="me-2" value="transferor_spouse" <?php echo ($benefit == 'transferor_spouse') ? 'checked="checked"' : '';?>> <label for="benefit2">the transferor's spouse</label>
                                                        <input type="radio" name="benefit" id="benefit3" class="me-2" value="registered_domestic_partner" <?php echo ($benefit == 'registered_domestic_partner') ? 'checked="checked"' : '';?>> <label for="benefit3"> registered domestic partner.</label>
                                                    </div>
                                                    <label id="benefit-error" class="error text-danger d-flex" for="benefit"></label>
                                                    <div class="pl-3">
                                                        2. to/from an irrevocable trust for the benefit of the <br>
                                                        <input type="radio" name="trustor" id="trustor1" class="me-2" value="transferor" required data-error="#trustor-error" <?php echo ($trustor == 'transferor') ? 'checked="checked"' : '';?>> <label for="trustor1">the transferor, and/orreator/grantor/trustor and/or         </label>
                                                        <input type="radio" name="trustor" id="trustor2" class="me-2" value="trustor_spouse" <?php echo ($trustor == 'trustor_spouse') ? 'checked="checked"' : '';?>> <label for="trustor2">grantor's/trustor's spouse</label>
                                                        <input type="radio" name="trustor" id="trustor3" class="me-2" value="trustor_registered_domestic_partner" <?php echo ($trustor == 'trustor_registered_domestic_partner') ? 'checked="checked"' : '';?>> <label for="trustor3"> grantor's/trustor's registered domestic partner</label>
                                                    </div>
                                                    <label id="trustor-error" class="error text-danger d-flex" for="trustor"></label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>M.</td>
                                                <td><input type="radio" name="is_subject_to_lease" id="checkYes14" value="yes" required data-error="#is_subject_to_lease-error" <?php echo ($is_subject_to_lease == 'yes') ? 'checked="checked"' : '';?>></td>
                                                <td><input type="radio" name="is_subject_to_lease" id="checkNo14" value="no" <?php echo ($is_subject_to_lease == 'no') ? 'checked="checked"' : '';?>></td>
                                                <td>
                                                    This property is subject to a lease with a remaining lease term of 35 years or more including written options.
                                                    <label id="is_subject_to_lease-error" class="error text-danger d-flex" for="is_subject_to_lease"></label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>N.</td>
                                                <td><input type="radio" name="is_transfer_between_parties" id="checkYes15" value="yes" required data-error="#is_transfer_between_parties-error" <?php echo ($is_transfer_between_parties == 'yes') ? 'checked="checked"' : '';?>></td>
                                                <td><input type="radio" name="is_transfer_between_parties" id="checkNo15" value="no" <?php echo ($is_transfer_between_parties == 'no') ? 'checked="checked"' : '';?>></td>
                                                <td>
                                                    This is a transfer between parties in which proportional interests of the transferor(s) and transferee(s) in each and every parcel being transferred remain exactly the same after the transfer
                                                    <label id="is_transfer_between_parties-error" class="error text-danger d-flex" for="is_transfer_between_parties"></label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>O.</td>
                                                <td><input type="radio" name="is_subsidized_low_income" id="checkYes16" value="yes" required data-error="#is_subsidized_low_income-error" <?php echo ($is_subsidized_low_income == 'yes') ? 'checked="checked"' : '';?>></td>
                                                <td><input type="radio" name="is_subsidized_low_income" id="checkNo16" value="no" <?php echo ($is_subsidized_low_income == 'no') ? 'checked="checked"' : '';?>></td>
                                                <td>
                                                    This is a transfer subject to subsidized low-income housing requirements with governmentally imposed restrictions, or restrictions imposed by specified nonprofit corporations.
                                                    <label id="is_subsidized_low_income-error" class="error text-danger d-flex" for="is_subsidized_low_income"></label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>P.</td>
                                                <td><input type="radio" name="is_solar_energy_system" id="checkYes17" value="yes" required data-error="#is_solar_energy_system-error" <?php echo ($is_solar_energy_system == 'yes') ? 'checked="checked"' : '';?>></td>
                                                <td><input type="radio" name="is_solar_energy_system" id="checkNo17" value="no" <?php echo ($is_solar_energy_system == 'no') ? 'checked="checked"' : '';?>></td>
                                                <td>
                                                    This transfer is to the first purchaser of a new building containing an active solar energy system.
                                                    <label id="is_solar_energy_system-error" class="error text-danger d-flex" for="is_solar_energy_system"></label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Q.</td>
                                                <td><input type="radio" name="is_transfer_other" id="checkYes18" value="yes" required data-error="#is_transfer_other-error" <?php echo ($is_transfer_other == 'yes') ? 'checked="checked"' : '';?>></td>
                                                <td><input type="radio" name="is_transfer_other" id="checkNo18" value="no" <?php echo ($is_transfer_other == 'no') ? 'checked="checked"' : '';?>></td>
                                                <td>
                                                    Other. This transfer is to 
                                                    <input type="text" class="input_single" name="other_transfer" id="other_transfer" value="<?php echo $other_transfer;?>">
                                                    <label id="is_transfer_other-error" class="error text-danger d-flex" for="is_transfer_other"></label>
                                                </td>
                                            </tr>
                                        </table>

                                        <small>* Please refer to the instructions for Part 1.</small>
                                        <p class="text-center f600 mt-3">Please provide any other information that will help the Assessor understand the nature of the transfer.</p>

                                        <div class="text-center my-5">
                                            <h5 class="f600">
                                                PART 2. OTHER TRANSFER INFORMATION 
                                            </h5>
                                            <em>Check and complete as applicable.</em>
                                        </div>

                                        <table class="table yesno_table">
                                            <tr>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                            <tr>
                                                <td>A.</td>
                                                <td>
                                                    Date of transfer, if other than recording date: 
                                                    <input type="date" class="input_single" id="recording_date" value="" name="recording_date" required data-error="#recording_date-error" value="<?php echo $recording_date;?>">
                                                    <label id="recording_date-error" class="error text-danger d-flex" for="recording_date"></label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>B.</td>
                                                <td>
                                                    Type of transfer: 
                                                    <div class="row">
                                                        <div class="col-lg-2 col-md-6">
                                                            <div class="d-flex">
                                                                <input type="checkbox" class="mt-2 me-2" id="purchase" value="purchase" name="types_of_transfer[]" required data-error="#types_of_transfer-error" <?php echo (in_array('purchase', $types_of_transfer)) ? 'checked="checked"' : '';?> >
                                                                <label for="purchase">Purchase</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-2 col-md-6">
                                                            <div class="d-flex">
                                                                <input type="checkbox" class="mt-2 me-2" id="foreclosure" value="foreclosure" name="types_of_transfer[]" <?php echo (in_array('foreclosure', $types_of_transfer)) ? 'checked="checked"' : '';?>>
                                                                <label for="foreclosure">Foreclosure</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-6">
                                                            <div class="d-flex">
                                                                <input type="checkbox" class="mt-2 me-2" id="trade" value="trade_of_exchange" name="types_of_transfer[]" <?php echo (in_array('trade_of_exchange', $types_of_transfer)) ? 'checked="checked"' : '';?>>
                                                                <label for="trade">Trade or exchange </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-5 col-md-6">
                                                            <div class="d-flex">
                                                                <input type="checkbox" class="mt-2 me-2" id="acquisition" value="acquisition" name="types_of_transfer[] " <?php echo (in_array('acquisition', $types_of_transfer)) ? 'checked="checked"' : '';?>>
                                                                <label for="acquisition"> Merger, stock, or partnership acquisition (Form BOE-100-B) </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row mt-3">
                                                        <div class="col-md-6">
                                                            Contract of sale. Date of contract <input type="date" class="input_single" id="date_of_contract" name="date_of_contract" required data-error="#date_of_contract-error" value="<?php echo $date_of_contract;?>">
                                                            <label id="date_of_contract-error" class="error text-danger d-flex" for="date_of_contract"></label>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <input type="checkbox" id="inheritance" value="inheritance" name="types_of_transfer[]" <?php echo (in_array('inheritance', $types_of_transfer)) ? 'checked="checked"' : '';?>> 
                                                            <label for="inheritance">Inheritance. Date of death: 
                                                            <input type="date" class="input_single" id="date_of_death_transfer" name="date_of_death_transfer" required data-error="#date_of_death_transfer-error" value="<?php echo $date_of_death_transfer;?>"></label>
                                                            <label id="date_of_death_transfer-error" class="error text-danger d-flex" for="date_of_death_transfer"></label>
                                                        </div>
                                                    </div>
                                                    <div class="row mt-3">
                                                        <div class="col-lg-2 col-md-6">
                                                            <div class="d-flex">
                                                                <input type="checkbox" class="mt-2 me-2" id="leaseback" value="leaseback" name="types_of_transfer[]" <?php echo (in_array('leaseback', $types_of_transfer)) ? 'checked="checked"' : '';?>>
                                                                <label for="leaseback"> Sale/leaseback</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-2 col-md-6">
                                                            <div class="d-flex">
                                                                <input type="checkbox" class="mt-2 me-2" id="lease" value="lease" name="types_of_transfer[]" <?php echo (in_array('lease', $types_of_transfer)) ? 'checked="checked"' : '';?>>
                                                                <label for="lease">Creation of a lease</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-6">
                                                            <div class="d-flex">
                                                                <input type="checkbox" class="mt-2 me-2" id="assignment" value="assignment" name="types_of_transfer[]" <?php echo (in_array('assignment', $types_of_transfer)) ? 'checked="checked"' : '';?>>
                                                                <label for="assignment">Assignment of a lease </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-5 col-md-6">
                                                            <div class="d-flex">
                                                                <input type="checkbox" class="mt-2 me-2" id="termination" value="termination" name="types_of_transfer[]" <?php echo (in_array('termination', $types_of_transfer)) ? 'checked="checked"' : '';?>>
                                                                <label for="termination"> Termination of a lease. Date lease began  
                                                                    <input type="date" class="input_single" id="date_of_lease_began" name="date_of_lease_began" required data-error="#date_of_lease_began-error" value="<?php echo $date_of_lease_began;?>"></label>
                                                                <label id="date_of_lease_began-error" class="error text-danger d-flex" for="date_of_lease_began"></label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="mt-3">
                                                        Original term in years (including written options): 
                                                        <input type="text" class="input_single w-small" id="original_terms_in_year" name="original_terms_in_year" value="<?php echo $original_terms_in_year;?>"> 
                                                         Remaining term in years (including written options):   
                                                         <input type="text"  class="input_single w-small" id="remaining_terms_in_year" name="remaining_terms_in_year" value="<?php echo $remaining_terms_in_year;?>">
                                                    </div>
                                                    <label id="types_of_transfer-error" class="error text-danger d-flex" for="types_of_transfer"></label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>C.</td>
                                                <td>
                                                    Only a partial interest in the property was transferred.
                                                    <input type="radio" name="is_partial_interest" id="propertYes" required data-error="#is_partial_interest-error" value="yes" <?php echo ($is_partial_interest == 'yes') ? 'checked="checked"' : '';?>>&nbsp;<label for="propertYes">YES</label> &nbsp;
                                                    <input type="radio" name="is_partial_interest" id="propertNo" value="no" <?php echo ($is_partial_interest == 'no') ? 'checked="checked"' : '';?>>&nbsp;<label for="propertNo">NO</label>&nbsp;
                                                    If YES, indicate the percentage transferred: 
                                                    <input type="text" class="input_single w-medium" id="start_percentage_range" name="start_percentage_range" value="<?php echo $start_percentage_range;?>"> % 
                                                    <input type="text" class="input_single w-medium" id="end_percentage_range" name="end_percentage_range" value="<?php echo $end_percentage_range;?>">
                                                    <label id="is_partial_interest-error" class="error text-danger d-flex" for="is_partial_interest"></label>
                                                </td>
                                            </tr>
                                        </table>

                                        <div class="text-center my-5">
                                            <h5 class="f600">
                                                PART 3. PURCHASE PRICE AND TERMS OF SALE 
                                            </h5>
                                            <em>Check and complete as applicable.</em>
                                        </div>

                                        <table class="table yesno_table">
                                            <tr>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                            <tr>
                                                <td>A.</td>
                                                <td>
                                                    <div class="d-flex justify-content-between">
                                                        Total purchase price   <div>$ 
                                                            <input type="text" class="input_single" id="total_purchase_price" name="total_purchase_price" required data-error="#total_purchase_price-error" value="<?php echo $total_purchase_price;?>"></div>
                                                            
                                                    </div>
                                                    <label id="total_purchase_price-error" class="error text-danger d-flex" for="total_purchase_price"></label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>B.</td>
                                                <td>
                                                    <div class="d-flex justify-content-between">
                                                        Cash down payment or value of trade or exchange excluding closing costs  <div>Amount $ 
                                                            <input type="text" class="input_single" id="cash_down_payment" name="cash_down_payment" required data-error="#cash_down_payment-error" value="<?php echo $cash_down_payment;?>"></div>
                                                            
                                                    </div>
                                                    <label id="cash_down_payment-error" class="error text-danger d-flex" for="cash_down_payment"></label>
                                                </td>
                                            </tr>       
                                            <tr>
                                                <td>C.</td>
                                                <td>
                                                    First deed of trust @ &nbsp;
                                                   <input type="text" class="input_single w-small" id="first_deed_of_trust_interest" name="first_deed_of_trust_interest" value="<?php echo $first_deed_of_trust_interest;?>"> % interest for 
                                                   <input type="text" class="input_single w-small" id="first_deed_of_trust_years" name="first_deed_of_trust_years" value="<?php echo $first_deed_of_trust_years;?>">years. 
                                                   Monthly payment $ 
                                                   <input type="text" class="input_single" id="first_deed_of_trust_monthly_payment" name="first_deed_of_trust_monthly_payment" required data-error="#first_deed_of_trust_monthly_payment-error" value="<?php echo $first_deed_of_trust_monthly_payment;?>">
                                                   <label id="first_deed_of_trust_monthly_payment-error" class="error text-danger d-flex" for="first_deed_of_trust_monthly_payment"></label>                    
                                                    <div class="row mt-3">
                                                        <div class="col-lg-3 col-md-6">
                                                            <div class="d-flex">
                                                                <input type="checkbox" class="mt-2 me-2" id="fha" value="fha" name="first_deed_payment_types[]" <?php echo (in_array('fha', $first_deed_payment_types)) ? 'checked="checked"' : '';?>
                                                                <label for="discount">FHA (____Discount Points)</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-6">
                                                            <div class="d-flex">
                                                                <input type="checkbox" class="mt-2 me-2" id="cal_vet" value="cal_vet" name="first_deed_payment_types[]" <?php echo (in_array('cal_vet', $first_deed_payment_types)) ? 'checked="checked"' : '';?>
                                                                <label for="vet">Cal-Vet </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-6">
                                                            <div class="d-flex">
                                                                <input type="checkbox" class="mt-2 me-2" id="va_point" value="va_point" name="first_deed_payment_types[]" <?php echo (in_array('va_point', $first_deed_payment_types)) ? 'checked="checked"' : '';?>
                                                                <label for="va_point">VA (____Discount Points) </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-6">
                                                            <div class="d-flex">
                                                                <input type="checkbox" class="mt-2 me-2" id="fix_rate" value="fix_rate" name="first_deed_payment_types[]" <?php echo (in_array('fix_rate', $first_deed_payment_types)) ? 'checked="checked"' : '';?>
                                                                <label for="fix_rate"> Fixed rate </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row mt-3">
                                                        <div class="col-md-4">
                                                            <div class="d-flex">
                                                                <input type="checkbox" class="mt-2 me-2" id="var_rate" value="var_rate" name="first_deed_payment_types[]" <?php echo (in_array('var_rate', $first_deed_payment_types)) ? 'checked="checked"' : '';?>
                                                                <label for="var_rate"> Variable rate</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="d-flex">
                                                                <input type="checkbox" class="mt-2 me-2" id="loan" value="loan" name="first_deed_payment_types[]" <?php echo (in_array('loan', $first_deed_payment_types)) ? 'checked="checked"' : '';?>
                                                                <label for="loan"> Bank/Savings & Loan/Credit Union </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="d-flex">
                                                                <input type="checkbox" class="mt-2 me-2" id="loan_carried_by_seller" value="loan_carried_by_seller" name="first_deed_payment_types[]" <?php echo (in_array('loan_carried_by_seller', $first_deed_payment_types)) ? 'checked="checked"' : '';?>
                                                                <label for="carried">Loan carried by seller</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row mt-3">
                                                        <div class="col-md-6">
                                                            <div class="d-flex">
                                                                <input type="checkbox" class="mt-2 me-2" id="balloon_payment" value="balloon_payment" name="first_deed_payment_types[]" <?php echo (in_array('balloon_payment', $first_deed_payment_types)) ? 'checked="checked"' : '';?>
                                                                <label for="balloon">Balloon payment $</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="d-flex">
                                                                <input type="checkbox" class="mt-2 me-2" id="due_date" value="due_date" name="first_deed_payment_types[]" <?php echo (in_array('due_date', $first_deed_payment_types)) ? 'checked="checked"' : '';?>
                                                                <label for="due_date">Due date: <input type="date" class="input_single" id="first_deed_due_date" name="first_deed_due_date" value="<?php echo $first_deed_due_date;?>"></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>D.</td>
                                                <td>
                                                    <div>
                                                        Second deed of trust @  <input type="text" class="input_single w-small" id="second_deed_of_trust_interest" name="second_deed_of_trust_interest" value="<?php echo $second_deed_of_trust_interest;?>"> % interest for 
                                                        <input type="text" class="input_single w-small" id="second_deed_of_trust_years" name="second_deed_of_trust_years" value="<?php echo $second_deed_of_trust_years;?>"> years.   
                                                        Monthly payment $ <input type="text" class="input_single" id="second_deed_of_trust_monthly_payment" name="second_deed_of_trust_monthly_payment" value="<?php echo $second_deed_of_trust_monthly_payment;?>">  
                                                        Amount $ <input type="text" class="input_single" id="second_deed_of_trust_amount" name="second_deed_of_trust_amount" value="<?php echo $second_deed_of_trust_amount;?>">
                                                    </div>
                                                    <div class="mt-3 row">
                                                        <div class="col-md-4">
                                                            <div class="d-flex">
                                                                <input type="checkbox" class="mt-2 me-2" id="fixed_rate" value="fixed_rate" name="second_deed_payment_types[]" <?php echo (in_array('fixed_rate', $second_deed_payment_types)) ? 'checked="checked"' : '';?>>
                                                                <label for="fixed_rate"> Fixed rate</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="d-flex">
                                                                <input type="checkbox" class="mt-2 me-2" id="second_var_rate" value="second_var_rate" name="second_deed_payment_types[]" <?php echo (in_array('second_var_rate', $second_deed_payment_types)) ? 'checked="checked"' : '';?>>
                                                                <label for="variable_rate"> Variable rate</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="d-flex">
                                                                <input type="checkbox" class="mt-2 me-2" id="second_loan" value="second_loan" name="second_deed_payment_types[]" <?php echo (in_array('second_loan', $second_deed_payment_types)) ? 'checked="checked"' : '';?>>
                                                                <label for="union">Bank/Savings & Loan/Credit Union </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mt-3 row">
                                                        <div class="col-md-4">
                                                            <div class="d-flex">
                                                                <input type="checkbox" class="mt-2 me-2" id="second_loan_carried_by_seller" value="second_loan_carried_by_seller" name="second_deed_payment_types[]" <?php echo (in_array('second_loan_carried_by_seller', $second_deed_payment_types)) ? 'checked="checked"' : '';?>>
                                                                <label for="loan_seller">Loan carried by seller</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="d-flex">
                                                                <input type="checkbox" class="mt-2 me-2" id="second_ballon_payment" value="second_ballon_payment" name="second_deed_payment_types[]" <?php echo (in_array('second_ballon_payment', $second_deed_payment_types)) ? 'checked="checked"' : '';?>>
                                                                <label for="ballon_pay">Balloon payment $ <input type="text" class="input_single w-small" id="ballon_payment" name="ballon_payment" value="<?php echo $ballon_payment;?>"></label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="d-flex">
                                                                <input type="checkbox" class="mt-2 me-2" id="second_due_date" value="second_due_date" name="second_deed_payment_types[]" <?php echo (in_array('second_due_date', $second_deed_payment_types)) ? 'checked="checked"' : '';?>>
                                                                <label for="date_due"> Due date: <input type="date" class="input_single" id="second_deed_due_date" name="second_deed_due_date" value="<?php echo $second_deed_due_date;?>"></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>E.</td>
                                                <td>
                                                    Was an Improvement Bond or other public financing assumed by the buyer?    &nbsp;
                                                    <input type="radio" name="is_financing" id="financingYes" required data-error="#is_financing-error" value="yes" <?php echo ($is_financing == 'yes') ? 'checked="checked"' : '';?>>&nbsp;<label for="financingYes">YES</label> &nbsp;
                                                    <input type="radio" name="is_financing" id="financingNo" value="no" <?php echo ($is_financing == 'no') ? 'checked="checked"' : '';?>>&nbsp;<label for="financingNo">NO</label>&nbsp;
                                                    Outstanding balance $ <input type="text" class="input_single w-medium" id="outstanding_balance" name="outstanding_balance" value="<?php echo $outstanding_balance;?>">
                                                    <label id="is_financing-error" class="error text-danger d-flex" for="is_financing"></label>                    
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>F.</td>
                                                <td>
                                                    Amount, if any, of real estate commission fees paid by the buyer which are not included in the purchase price $ 
                                                    <input type="text" class="input_single w-medium" id="real_estate_commission" name="real_estate_commission" required data-error="#real_estate_commission-error" value="<?php echo $real_estate_commission;?>">
                                                    <label id="real_estate_commission-error" class="error text-danger d-flex" for="real_estate_commission"></label>    
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>G.</td>
                                                <td>
                                                    he property was purchased: 
                                                    <div class="mt-3">
                                                        <input type="radio" name="property_purchase_via" id="real_estate" required data-error="#property_purchase_via-error" value="real_estate" <?php echo ($property_purchase_via == 'real_estate') ? 'checked="checked"' : '';?>> 
                                                        <label for="broker_name"> Through real estate broker.</label> 
                                                        Broker name: <input type="text" class="input_single" id="broker_name" name="broker_name" value="<?php echo $broker_name;?>"> 
                                                        Phone number: <input type="text" class="input_single" id="broker_phone_number" name="broker_phone_number" value="<?php echo $broker_phone_number;?>">
                                                    </div> 
                                                    <div class="mt-3">
                                                        <input type="radio" name="property_purchase_via" id="direct" value="direct_from_seller" <?php echo ($property_purchase_via == 'direct_from_seller') ? 'checked="checked"' : '';?>> <label for="direct">Direct from seller</label>  &nbsp;
                                                        <input type="radio" name="property_purchase_via" id="direct" value="family_member_relationship" <?php echo ($property_purchase_via == 'family_member_relationship') ? 'checked="checked"' : '';?>> <label for="family">From a family member-Relationship  &nbsp;
                                                        <input type="text" class="input_single" id="property_purchase_via_name" name="property_purchase_via_name" value="<?php echo $property_purchase_via_name;?>"></label>  
                                                    </div>
                                                    <div class="mt-3">
                                                        <input type="radio" name="property_purchase_via" id="other" value="other" <?php echo ($property_purchase_via == 'other') ? 'checked="checked"' : '';?>> 
                                                        <label for="Other">Other. Please explain: 
                                                            <input type="text" class="input_single" id="other_through" name="other_through" value="<?php echo $other_through;?>">
                                                        </label>  
                                                    </div>
                                                    <label id="property_purchase_via-error" class="error text-danger d-flex" for="property_purchase_via"></label> 
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>H.</td>
                                                <td>
                                                    Please explain any special terms, seller concessions, broker/agent fees waived, financing, and any other information (e.g., buyer assumed the existing loan balance) that would assist the Assessor in the valuation of your property.
                                                </td>
                                            </tr>

                                            
                                        </table>

                                        <div class="text-center my-5">
                                            <h5 class="f600">
                                                PART 4. PROPERTY INFORMATION 
                                            </h5>
                                            <em>Check and complete as applicable.</em>
                                        </div>

                                        <table class="table yesno_table">
                                            <tr>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                            <tr>
                                                <td>A.</td>
                                                <td>
                                                    Type of property transferred
                                                    <div class="row mt-3">
                                                        <div class="col-md-4">
                                                            <div class="d-flex">
                                                                <input type="checkbox" class="mt-2 me-2" id="single" name="types_of_property_transferred[]" value="single" <?php echo (in_array('single', $types_of_property_transferred)) ? 'checked="checked"' : '';?>>
                                                                <label for="single"> Single-family residence</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="d-flex">
                                                                <input type="checkbox" class="mt-2 me-2" id="own" name="types_of_property_transferred[]" value="own" <?php echo (in_array('own', $types_of_property_transferred)) ? 'checked="checked"' : '';?>>
                                                                <label for="own">Co-op/Own-your-own</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="d-flex">
                                                                <input type="checkbox" class="mt-2 me-2" id="manufactured" name="types_of_property_transferred[]" value="manufactured" <?php echo (in_array('manufactured', $types_of_property_transferred)) ? 'checked="checked"' : '';?>>
                                                                <label for="manufactured">Manufactured home</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row mt-3">
                                                        <div class="col-md-4">
                                                            <div class="d-flex">
                                                                <input type="checkbox" class="mt-2 me-2" id="multiple" name="types_of_property_transferred[]" value="multiple" <?php echo (in_array('multiple', $types_of_property_transferred)) ? 'checked="checked"' : '';?>>
                                                                <label for="multiple"> Multiple-family residence. Number of units: 
                                                                <input type="text" class="input_single" id="num_of_units" name="num_of_units" value="<?php echo $num_of_units;?>"></label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="d-flex">
                                                                <input type="checkbox" class="mt-2 me-2" id="condo" name="types_of_property_transferred[]" value="condo" <?php echo (in_array('condo', $types_of_property_transferred)) ? 'checked="checked"' : '';?>>
                                                                <label for="condo">Condominium</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="d-flex">
                                                                <input type="checkbox" class="mt-2 me-2" id="unimproved" name="types_of_property_transferred[]" value="unimproved" <?php echo (in_array('unimproved', $types_of_property_transferred)) ? 'checked="checked"' : '';?>>
                                                                <label for="unimproved">Unimproved lot</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row mt-3">
                                                        <div class="col-md-4">
                                                            <div class="d-flex">
                                                                <input type="checkbox" class="mt-2 me-2" id="timber" name="types_of_property_transferred[]" value="timber" <?php echo (in_array('timber', $types_of_property_transferred)) ? 'checked="checked"' : '';?>>
                                                                <label for="timber"> Other. Description: (i.e., timber, mineral, water rights, etc.)</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="d-flex">
                                                                <input type="checkbox" class="mt-2 me-2" id="timshare" name="types_of_property_transferred[]" value="timshare" <?php echo (in_array('timshare', $types_of_property_transferred)) ? 'checked="checked"' : '';?>>
                                                                <label for="timshare">Timeshare </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="d-flex">
                                                                <input type="checkbox" class="mt-2 me-2" id="commercial" name="types_of_property_transferred[]" value="commercial" <?php echo (in_array('commercial', $types_of_property_transferred)) ? 'checked="checked"' : '';?>>
                                                                <label for="commercial"> Commercial/Industrial</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>B.</td>
                                                <td>
                                                    <div class="d-flex">
                                                        <div class="me-3">
                                                            <input type="radio" name="is_personal_property" id="bpropertyYes" value="yes" required data-error="#is_personal_property-error" <?php echo ($is_personal_property == 'yes') ? 'checked="checked"' : '';?>>&nbsp; <label for="bpropertyYes">YES</label>&nbsp;
                                                            <input type="radio" name="is_personal_property" id="bpropertyNo" value="no">&nbsp; <label for="bpropertyNo" <?php echo ($is_personal_property == 'no') ? 'checked="checked"' : '';?>>NO</label> &nbsp;
                                                        </div>
                                                        <div>
                                                            Personal/business property, or incentives, provided by seller to buyer are included in the purchase price. Examples of personal property are furniture, farm equipment, machinery, etc. Examples of incentives are club memberships, etc. Attach list if available.
                                                        </div>
                                                    </div>
                                                    <label id="is_personal_property-error" class="error text-danger d-flex" for="is_personal_property"></label> 

                                                    If YES, enter the value of the personal/business property: $ <input type="text" class="input_single w-medium" id="peronal_property_value" name="peronal_property_value" value="<?php echo $peronal_property_value;?>"> 
                                                    Incentives $ <input type="text" class="input_single w-medium" id="incentives" name="incentives" value="<?php echo $incentives;?>">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>C.</td>
                                                <td>
                                                    <div class="d-flex">
                                                        <div class="me-3">
                                                            <input type="radio" name="is_manufacture_home_included_in_purchase_price" id="purchasePriceYes" value="yes" required data-error="#is_manufacture_home_included_in_purchase_price-error" <?php echo ($is_manufacture_home_included_in_purchase_price == 'yes') ? 'checked="checked"' : '';?>>&nbsp; <label for="purchasePriceYes">YES</label>&nbsp;
                                                            <input type="radio" name="is_manufacture_home_included_in_purchase_price" id="purchasePriceNo" value="no" <?php echo ($is_manufacture_home_included_in_purchase_price == 'no') ? 'checked="checked"' : '';?>>&nbsp; <label for="purchasePriceNo">NO</label> &nbsp;
                                                        </div>
                                                        <div>
                                                            A manufactured home is included in the purchase price.
                                                        </div>
                                                        <label id="is_manufacture_home_included_in_purchase_price-error" class="error text-danger d-flex" for="is_manufacture_home_included_in_purchase_price"></label> 
                                                    </div>
                                                    <div class=" mt-3">
                                                        If YES, enter the value attributed to the manufactured home: $  
                                                        <input type="text" class="input_single w-medium" id="value_manufacture_home" name="value_manufacture_home" value="<?php echo $value_manufacture_home;?>">
                                                    </div>
                                                    <div class="d-flex mt-3">
                                                        <div class="me-3">
                                                            <input type="radio" name="is_manufacture_home_tax" id="manufacturedPriceYes" value="yes" required data-error="#is_manufacture_home_tax-error" <?php echo ($is_manufacture_home_tax == 'yes') ? 'checked="checked"' : '';?>>&nbsp; <label for="manufacturedPriceYes">YES</label>&nbsp;
                                                            <input type="radio" name="is_manufacture_home_tax" id="manufacturedPriceNo" value="no">&nbsp; <label for="manufacturedPriceNo" <?php echo ($is_manufacture_home_tax == 'no') ? 'checked="checked"' : '';?>>NO</label> &nbsp;
                                                        </div>
                                                        <div>
                                                            The manufactured home is subject to local property tax. If NO, enter decal number  
                                                            <input type="text" class="input_single w-medium" id="deal_number" name="deal_number" value="<?php echo $deal_number;?>">
                                                        </div>
                                                        <label id="is_manufacture_home_tax-error" class="error text-danger d-flex" for="is_manufacture_home_tax"></label> 
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>D.</td>
                                                <td>
                                                    <div class="d-flex">
                                                        <div class="me-3">
                                                            <input type="radio" name="is_property_produce_income" id="purchasePriceYes" value="yes" required data-error="#is_property_produce_income-error" <?php echo ($is_property_produce_income == 'yes') ? 'checked="checked"' : '';?>>&nbsp; <label for="purchasePriceYes">YES</label>&nbsp;
                                                            <input type="radio" name="is_property_produce_income" id="purchasePriceNo" value="no" <?php echo ($is_property_produce_income == 'no') ? 'checked="checked"' : '';?>>&nbsp; <label for="purchasePriceNo">NO</label> &nbsp;
                                                        </div>
                                                        <div>
                                                            The property produces rental or other income.
                                                        </div>
                                                        <label id="is_property_produce_income-error" class="error text-danger d-flex" for="is_property_produce_income"></label> 
                                                    </div>
                                                    <div class="mt-3">
                                                        If YES, the income is from: &nbsp;
                                                        <input type="radio" name="income_type" id="rent" value="rent" <?php echo ($income_type == 'rent') ? 'checked="checked"' : '';?>> &nbsp; <label for="rent">Lease/rent</label> &nbsp;
                                                        <input type="radio" name="income_type" id="contract" value="contract" <?php echo ($income_type == 'contract') ? 'checked="checked"' : '';?>> &nbsp; <label for="contract">Contract</label> &nbsp;
                                                        <input type="radio" name="income_type" id="mineral" value="mineral" <?php echo ($income_type == 'mineral') ? 'checked="checked"' : '';?>> &nbsp; <label for="mineral">Mineral rights</label> &nbsp;
                                                        <input type="radio" name="income_type" id="other_income" value="other_income" <?php echo ($income_type == 'other_income') ? 'checked="checked"' : '';?>> &nbsp; <label for="other_income">Other: &nbsp;
                                                        <input type="text" class="input_single" id="other_income_type" name="other_income_type" value="<?php echo $other_income_type;?>"></label> &nbsp;
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>E.</td>
                                                <td>
                                                    <div>
                                                        The condition of the property at the time of sale was:  &nbsp;
                                                        <input type="radio" name="property_condition" id="good" value="good" required data-error="#property_condition-error" <?php echo ($property_condition == 'good') ? 'checked="checked"' : '';?>> &nbsp; <label for="good">Good   </label> &nbsp;
                                                        <input type="radio" name="property_condition" id="average" value="average"> &nbsp; <label for="average" <?php echo ($is_buyer_intends == 'average') ? 'checked="checked"' : '';?>>Average   </label> &nbsp;
                                                        <input type="radio" name="property_condition" id="fair" value="fair" <?php echo ($property_condition == 'fair') ? 'checked="checked"' : '';?>> &nbsp; <label for="fair">Fair   </label> &nbsp;
                                                        <input type="radio" name="property_condition" id="poor" value="poor" <?php echo ($property_condition == 'poor') ? 'checked="checked"' : '';?>> &nbsp; <label for="poor">Poor</label> &nbsp;
                                                    </div>
                                                    <div class="mt-2"> Please describe: <input type="text" class="input_single" id="property_condition_describe" name="property_condition_describe" value="<?php echo $property_condition_describe;?>"></div>
                                                    <label id="property_condition-error" class="error text-danger d-flex" for="property_condition"></label>                    
                                                </td>
                                            </tr>
                                        </table>

                                        <div class="text-center mt-5">
                                            <h5 class="f600">
                                                CERTIFICATION
                                            </h5>
                                            <em>
                                                I certify (or declare) that the foregoing and all information hereon, including any accompanying statements or documents, is true and correct to the best of my knowledge and belief.
                                            </em>
                                        </div>
                                        <hr class="my-3">
                                        <div>SIGNATURE OF BUYER/TRANSFEREE OR CORPORATE OFFICER</div>
                                        <div class="row mt-2">
                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3">
                                                    <input type="date" class="form-control" id="signature_corporate_officer_date" name="signature_corporate_officer_date" required data-error="#signature_corporate_officer_date-error" value="<?php echo $signature_corporate_officer_date;?>">
                                                    <small class="small_label">DATE </small>
                                                         
                                                </div>
                                                <label id="signature_corporate_officer_date-error" class="error text-danger error2" for="signature_corporate_officer_date"></label>  
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3">
                                                    <input type="text" class="form-control" id="corporate_officer_telephone" name="corporate_officer_telephone" required data-error="#corporate_officer_telephone-error" value="<?php echo $corporate_officer_telephone;?>">
                                                    <small class="small_label">TELEPHONE</small>
                                                    
                                                </div>
                                                <label id="corporate_officer_telephone-error" class="error text-danger error2" for="corporate_officer_telephone"></label>       
                                            </div>
                                        </div>
                                        <div>NAME OF BUYER/TRANSFEREE/PERSONAL REPRESENTATIVE/CORPORATE OFFICER (PLEASE PRINT)</div>
                                        <div class="row mt-2">
                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3">
                                                    <input type="text" class="form-control" id="corporate_officer_name" name="corporate_officer_name" required data-error="#corporate_officer_name-error" value="<?php echo $corporate_officer_name;?>">
                                                    <small class="small_label">TITLE  </small>
                                                   
                                                </div>
                                                <label id="corporate_officer_name-error" class="error text-danger error2" for="corporate_officer_name"></label>       
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3">
                                                    <input type="email" class="form-control" id="corporate_officer_email" name="corporate_officer_email" required data-error="#corporate_officer_email-error">
                                                    <small class="small_label">EMAIL ADDRESS</small>
                                                    
                                                </div>
                                                <label id="corporate_officer_email-error" class="error text-danger error2" for="corporate_officer_email"></label>     
                                            </div>
                                        </div>
                                        <div class="f500 text-center my-3">The Assessor's office may contact you for additional information regarding this transaction.    </div>
                                        

                                    </div>
                                        
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingTen">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTen" aria-expanded="false" aria-controls="collapseTen">
                                       (6) Preliminary Report Approval
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
							<div class="accordion-item">
                                <h2 class="accordion-header" id="headingEleven">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEleven" aria-expanded="false" aria-controls="collapseEleven">
                                        (7) NHD Receipt
                                    </button>
                                </h2>
                                <div id="collapseEleven" class="accordion-collapse collapse" aria-labelledby="headingEleven" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <div class="form-group d-flex mb-2">
                                            <input type="checkbox" id="acknowledge" name="acknowledge"  class="me-2 mt-1" checked="checked" required data-error="#acknowledge-error">
                                            <label for="acknowledge" class="mb-2">By clicking the submit button, I agree to terms & conditions.</label>
                                            <label id="acknowledge-error" class="error text-danger d-flex" for="acknowledge"></label>    
                                        </div>
                                        <div class="border text-danger p-3">
                                            IMPORTANT NOTICE: Cyber criminals are preying on those involved in real estate transactions. They will hack email accounts, spoof email addresses, and send emails with fake wiring or fake funds delivery instructions. These emails are convincing and sophisticated. Always independently confirm wiring and funding instructions in person or by telephone to our published office phone number of record. Never wire money without double-checking, in person or by telephone that the wiring instructions are correct. BE SKEPTICAL AND VIGILANT. 
                                        </div>
                                        <hr>

                                        <div class="form-group mb-4">
                                            <label for="" class="mb-2"><b>Signature</b></label>
                                            <div class="sign_pad">
                                                <canvas id="canvas1" width="500" height="100" style="touch-action: none;"></canvas>
                                            </div>
                                           
                                            <a onclick="clear1()" href="javascript:void()" class="text-body text-end"> Clear</a>
                                        </div>

                                        <div class="row mt-5">
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label for="" class="mb-2"><b>TenantID</b></label>
                                                    <input type="text" class="form-control" id="tenant_id" name="tenant_id" required data-error="#tenant_id-error" value="<?php echo $tenant_id;?>">
                                                </div>
                                                <label id="tenant_id-error" class="error text-danger" for="tenant_id"></label>     
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label for="" class="mb-2"><b>DocType</b></label> 
                                                    <input type="text" class="form-control" id="doc_type" name="doc_type" required data-error="#doc_type-error" value="<?php echo $doc_type;?>">
                                                </div>
                                                <label id="doc_type-error" class="error text-danger" for="doc_type"></label>     
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
    <script src="<?php echo base_url();?>assets/frontend/js/order/signature_pad.min.js"></script>
</body>
</html>
