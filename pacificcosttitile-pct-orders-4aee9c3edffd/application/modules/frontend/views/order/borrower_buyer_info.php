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
    input[type="radio"] { margin: 10px !important; }
    .form-control {
        width: 100%;
    }
</style>

<body class="">

    <header>
        <div class="container">
            <div class="row align-items-center">
                <div class="col-6">
                    <a href="#"><img src="<?php echo base_url();?>assets/frontend/images/buyer-seller-package/alanna-logo.png" alt="..." class="img-fluid img_logo"></a>
                </div>
                <div class="col-6 text-end">
                </div>
            </div>
        </div>
    </header>

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
                    <form action="<?php echo base_url().'buyer-info/'.$orderDetails['file_id']; ?>" method="post" name="borrower_buyer_form" id="borrower_buyer_form">
                        <h2 class="blue_title">Buyer Opening Package<br><span style="font-size:16px; padding-top:15px;">Property Address: <?php echo $orderDetails['full_address'];?></span><br><span style="font-size:16px; padding-top:15px;">APN:<?php echo $orderDetails['apn'];?></span></h2>
                        <div class="accordion" id="accordionExample">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingThirteen">
                                    <button style="border-bottom: 1px solid rgba(0,0,0,.125)" class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThirteen" aria-expanded="false" aria-controls="collapseThirteen">Buyer Information</button>
                                </h2>
                                <input type="hidden" name="order_id" id="order_id" value="<?php echo $orderDetails['order_id'];?>">
                                <div id="collapseThirteen" class="accordion-collapse collapse show" aria-labelledby="headingThirteen" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <div class="date_escrow_num mt-md-5">
                                            <span>Escrow No.:</span> 10257432-GLE-MP<br><span>Title No.:</span> 10257432-GLT-
                                        </div> 

                                        <h4 class="text-center my-4"><strong>PLEASE FILL OUT THIS FORM COMPLETELY AND RETURN TO OUR OFFICE AS SOON AS POSSIBLE <br> AS IT WILL ASSIST US IN THE ADMINISTRATION OF YOUR TRANSACTION.</strong></h4>

                                        <div class="form-group position-relative mb-3 mt-3">
                                            <label for="" class="mb-2"><b></b></label>
                                            <input type="text" class="form-control" id="buyer_full_name" name="buyer_full_name" required data-error="#buyer_full_name-error">
                                            <small class="small_label">Buyer(s):</small>
                                        </div>
                                        <label id="buyer_full_name-error" class="error text-danger" for="buyer_full_name"></label>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3 mt-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" class="form-control" id="buyer_home_number" name="buyer_home_number" required data-error="#buyer_home_number-error">
                                                    <small class="small_label">Home Phone Number:</small>
                                                </div>
                                                <label id="buyer_home_number-error" class="error text-danger" for="buyer_home_number"></label>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3 mt-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" class="form-control" id="buyer_work_number" name="buyer_work_number" required data-error="#buyer_work_number-error">
                                                    <small class="small_label">Work Phone Number:</small>
                                                </div>
                                                <label id="buyer_work_number-error" class="error text-danger" for="buyer_work_number"></label>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3 mt-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" class="form-control" id="buyer_email_address" name="buyer_email_address" required data-error="#buyer_email_address-error">
                                                    <small class="small_label">E-Mail Address:</small>
                                                </div>
                                                <label id="buyer_email_address-error" class="error text-danger" for="buyer_email_address"></label>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3 mt-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" class="form-control" id="buyer_fax_number" name="buyer_fax_number" required data-error="#buyer_fax_number-error">
                                                    <small class="small_label">Fax Number:</small>
                                                </div>
                                                <label id="buyer_fax_number-error" class="error text-danger" for="buyer_fax_number"></label>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3 mt-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" class="form-control" id="buyer_ssn" name="buyer_ssn" required data-error="#buyer_ssn-error">
                                                    <small class="small_label">Social Security #:</small>
                                                </div>
                                                <label id="buyer_ssn-error" class="error text-danger" for="buyer_ssn"></label>
                                            </div>
                                        </div>

                                        <div class="form-group position-relative mb-3 mt-3">
                                            <label for="" class="mb-2"><b></b></label>
                                            <textarea rows="2" class="form-control" style="height:auto;" id="buyer_current_mailing_address" name="buyer_current_mailing_address" required data-error="#buyer_current_mailing_address-error"></textarea>
                                            <small class="small_label">Buyer(s) Current Mailing Address:</small>
                                        </div>
                                        <label id="buyer_current_mailing_address-error" class="error text-danger" for="buyer_current_mailing_address"></label>

                                        <div class="form-group position-relative mb-3 mt-3">
                                            <label for="" class="mb-2"><b></b></label>
                                            <textarea rows="2" class="form-control" style="height:auto;" id="buyer_mailing_address_after_close" name="buyer_mailing_address_after_close" required data-error="#buyer_mailing_address_after_close-error"></textarea>
                                            <small class="small_label">Buyer(s) Mailing Address After Close Of Escrow:</small>
                                        </div>
                                        <label id="buyer_mailing_address_after_close-error" class="error text-danger" for="buyer_mailing_address_after_close"></label>

                                        <div class="form-group position-relative mb-3 mt-5">
                                            <label for="" class="mb-2"><b>New Loan(s) Buyer(s) Are Applying For:</b></label>
                                            <input type="text" class="form-control" id="lender_name" name="lender_name" required data-error="#lender_name-error">
                                            <small class="small_label">Name Of Lender:</small>
                                        </div>
                                        <label id="lender_name-error" class="error text-danger" for="lender_name"></label>
                                        
                                        <div class="form-group position-relative mb-3 mt-3">
                                            <label for="" class="mb-2"><b></b></label>
                                            <textarea rows="2" class="form-control" style="height:auto;" id="lender_address" name="lender_address" required data-error="#lender_address-error"></textarea>
                                            <small class="small_label">Address:</small>
                                        </div>
                                        <label id="lender_address-error" class="error text-danger" for="lender_address"></label>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3 mt-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" class="form-control" id="agent_name" name="agent_name" required data-error="#agent_name-error">
                                                    <small class="small_label">Agent's Name:</small>
                                                </div>
                                                <label id="agent_name-error" class="error text-danger error2" for="agent_name"></label>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3 mt-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" class="form-control" id="agent_phone_number" name="agent_phone_number" required data-error="#agent_phone_number-error">
                                                    <small class="small_label">Phone Number:</small>
                                                </div>
                                                <label id="agent_phone_number-error" class="error text-danger error2" for="agent_phone_number"></label>
                                            </div>
                                        </div>

                                        <div class="form-group position-relative mb-3">
                                            <label for="" class="mb-2"><b></b></label>
                                            <input type="text" class="form-control" id="second_lender_name" name="second_lender_name">
                                            <small class="small_label">Name Of Seond Lender:</small>
                                        </div>
                                        
                                        
                                        <div class="form-group position-relative mb-3 mt-3">
                                            <label for="" class="mb-2"><b></b></label>
                                            <textarea rows="2" class="form-control" style="height:auto;" id="seond_lender_address" name="seond_lender_address"></textarea>
                                            <small class="small_label">Address:</small>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3 mt-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" class="form-control" id="second_agent_name" name="second_agent_name">
                                                    <small class="small_label">Second Agent's Name:</small>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3 mt-3">
                                                    <label for="" class="mb-2"><b></b></label>
                                                    <input type="text" class="form-control" id="seond_agent_phone_number" name="seond_agent_phone_number">
                                                    <small class="small_label">Phone Number:</small>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3 mt-3">
                                                    <label for="" class="mb-2"><b>New Insurance:</b></label>
                                                    <input type="text" class="form-control" id="insurance_name" name="insurance_name" required data-error="#insurance_name-error">
                                                    <small class="small_label">Insurance's Name:</small>
                                                </div>
                                                <label id="insurance_name-error" class="error text-danger error2" for="insurance_name"></label>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group position-relative mb-3 mt-3">
                                                    <label for="" class="mb-2"><b>&nbsp;</b></label>
                                                    <input type="text" class="form-control" id="insurance_phone_number" name="insurance_phone_number" required data-error="#insurance_phone_number-error">
                                                    <small class="small_label">Phone Number:</small>
                                                </div>
                                                <label id="insurance_phone_number-error" class="error text-danger error2" for="insurance_phone_number"></label>
                                            </div>
                                        </div>

                                        <div class="form-group position-relative mb-3 mt-3">
                                            <label for="" class="mb-2"><b></b></label>
                                            <textarea rows="2" class="form-control" style="height:auto;" id="insurance_address" name="insurance_address" required data-error="#insurance_address-error"></textarea>
                                            <small class="small_label">Insurance's Address:</small>
                                        </div>
                                        <label id="insurance_address-error" class="error text-danger" for="insurance_address"></label>
                                        
                                        <div class="form-group position-relative mb-3 mt-3">
                                            <label for="" class="mb-2"><b></b></label>
                                            <input type="text" class="form-control" id="insurance_company" name="insurance_company" required data-error="#insurance_company-error">
                                            <small class="small_label">Insurance Company:</small>
                                        </div>
                                        <label id="insurance_company-error" class="error text-danger" for="insurance_company"></label>

                                        <p class="mt-5">Please place any additional information that you feel we may require on the reverse side of this form.</p>
                                                
                                        <div class="mb-80 mt-5">
                                            Dated:    
                                            <input type="text" class="w30 input_single" id="buyer_date" name="buyer_date" required data-error="#buyer_date-error">
                                            <label id="buyer_date-error" class="error text-danger d-flex" for="buyer_date"></label>
                                        </div>
                                       

                                        <input type="text" class="signature" value="" placeholder="signature" id="buyer_signature" name="buyer_signature" required data-error="#buyer_signature-error">  
                                        <label id="buyer_signature-error" class="error text-danger d-flex" for="buyer_signature"></label>
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
    <script src="<?php echo base_url();?>assets/frontend/js/order/script.js?v=02"></script>
    <script src="<?php echo base_url();?>assets/frontend/js/order/signature_pad.min.js"></script>
</body>
</html>