<style>
.state-error {
    display: block!important;
    margin-top: 6px;
    padding: 0 3px;
    font-family: Arial, Helvetica, sans-serif;
    font-style: normal;
    line-height: normal;
    color: #CC0000;
    font-size: 0.85em;
}
#page-preloader {
    background: none;
    display: none;
}

iframe {
    position: absolute;
    top: 20%;
}

a {
    color: white;
}
</style>

<link rel="stylesheet" type="text/css" href="assets/js/lightbox/themes/evolution-dark/jquery.lightbox.css" />


<body>
    <?php
        $this->load->view('layout/header_dashboard');
    ?>
    <div class="smart-wrap" id="borrower_page">
        <div class="smart-forms smart-container wrap-0">
            <div class="form-body smart-steps steps-theme-primary">
                <?php if(!empty($success)) {?>
                    <div id="borrower_success_msg" class="w-100 alert alert-success alert-dismissible">
                        <?php foreach($success as $sucess) {
                                echo $sucess."<br \>";	
                            }?>
                    </div>
                    <?php } 
                        if(!empty($errors)) {?>
                    <div id="borrower_error_msg" class="w-100 alert alert-danger alert-dismissible">
                        <?php foreach($errors as $error) {
                                echo $error."<br \>";	
                            }?>
                    </div>
                <?php } ?>

                <?php if($is_borrower_info_submitted == 0) {?>
                    <form method="post" action="<?php echo base_url();?>borrower-info-submit" enctype="multipart/form-data" name="borrower-form" id="borrower-form">
                        <input type="hidden" id="order_id" name="order_id" value="<?php echo $order_id;?>">
                        <input type="hidden" id="file_id" name="file_id" value="<?php echo $file_id;?>">
                        <input type="hidden" name="is_seller" id="is_seller" value="<?php echo $sellerFlag; ?>">
                        <h2>Personal <br>Information</h2>
                        <fieldset>  
                            <div class="spacer-b40">
                                <p class="medium fine-grey">
                                Please use the form to fill out your information. If there is a spouse or domestic partner on the transaction please select one below and fill in their information.</p>
                            </div>
                                
                            <div class="frm-row">
                                <div class="section colm colm4">
                                <label for="firstname" class="field-label">First Name </label>
                                    <label class="field prepend-icon">
                                        <input type="text" value="<?php echo $borrower_first_name;?>" name="firstname" id="firstname" class="gui-input" placeholder="First name">
                                        <span class="field-icon"><i class="fa fa-user"></i></span>  
                                    </label>
                                </div>
                                
                                <div class="section colm colm4">
                                <label for="middlename" class="field-label">Middle Name </label>
                                    <label class="field prepend-icon">
                                        <input type="text" value="<?php echo $borrower_middle_name;?>" name="middlename" id="middlename" class="gui-input" placeholder="Middle name">
                                        <span class="field-icon"><i class="fa fa-user"></i></span>  
                                    </label>
                                </div>
                                
                                <div class="section colm colm4">
                                <label for="lastname" class="field-label">Last Name </label>
                                    <label class="field prepend-icon">
                                        <input type="text" value="<?php echo $borrower_last_name;?>" name="lastname" id="lastname" class="gui-input" placeholder="Last name">
                                        <span class="field-icon"><i class="fa fa-user"></i></span>  
                                    </label>
                                </div>
                            </div>
                                    
                            <div class="frm-row">
                               
                                        
                                <div class="section colm colm4">
                                    <label for="mobile" class="field-label">Mobile Phone </label>
                                    <label class="field prepend-icon">
                                        <input type="text" name="mobile" value="<?php echo $borrower_mobile_number;?>" id="mobile" class="gui-input" placeholder="(999)-999-9999">
                                        <span class="field-icon">
                                            <i class="fa fa-phone-square"></i>
                                        </span>
                                    </label>
                                </div>
                                        
                                <div class="section colm colm4">
                                    <label for="date_of_birth" class="field-label">Date of Birth </label>
                                    <label class="field prepend-icon">
                                        <input type="text" name="date_of_birth" id="date_of_birth" class="gui-input" placeholder="DD/MM/YYYY" >
                                        <span class="field-icon">
                                            <i class="fa fa-phone-square"></i>
                                        </span> 
                                    </label>
                                </div>
								
								 <div class="section colm colm4">
                                    <label for="ssn" class="field-label">SSN Last 4 Digits  </label>
                                    <label class="field prepend-icon">
                                        <input type="text" name="ssn" id="ssn" class="gui-input" placeholder="1234">
                                        <span class="field-icon">
                                            <i class="fa fa-user"></i>
                                        </span>  
                                    </label>
                                </div>
                            </div>
                                    
                            <div class="frm-row">
                                <div class="section colm colm4">
                                    <label for="email" class="field-label">Email </label>
                                    <label class="field prepend-icon">
                                        <input type="email" name="email" id="email" class="gui-input" placeholder="abc@gmail.com">
                                        <span class="field-icon">
                                            <i class="fa fa-envelope"></i>
                                        </span>  
                                    </label>
                                </div>
                                

                                <div class="section colm colm4">
                                    <label for="dln" class="field-label">Drivers Lic No </label>
                                    <label class="field prepend-icon">
                                        <input type="text" name="dln" id="dln" class="gui-input" placeholder="D000000">
                                        <span class="field-icon">
                                            <i class="fa fa-user"></i>
                                        </span>  
                                    </label>
                                </div>

                                <!-- <div class="section colm colm4">
                                    <div class="section">
                                        <div class="option-group field">
                                            <label class="option">
                                                <input type="radio" name="buyer_seller" class="smartfm-ctrl" value="buyer"  data-show-id="buyer">
                                                <span class="radio"></span> Buyer
                                            </label>
                                            <label class="option">
                                                <input type="radio" name="buyer_seller" class="smartfm-ctrl" value="seller" data-show-id="seller">
                                                <span class="radio"></span>  Seller              
                                            </label>                                                               
                                        </div>
                                    </div>
                                </div> -->
								
                            </div>

                            <div class="frm-row">
                                <div class="section colm colm8">
                                    <div class="section">
                                        <div class="option-group field">
                                            <label class="option">
                                                <input type="radio" name="status" class="smartfm-ctrl" value="married"  data-show-id="married">
                                                <span class="radio"></span> Married
                                            </label>
                                            <label class="option">
                                                <input type="radio" name="status" class="smartfm-ctrl" value="domestic_partner" data-show-id="domestic_partner">
                                                <span class="radio"></span>  Domestic Partner              
                                            </label>
                                            <label class="option">
                                                <input type="radio" name="status" class="smartfm-ctrl" value="single"  data-show-id="single">
                                                <span class="radio"></span> Single           
                                            </label>                                                                   
                                        </div>
                                    </div>
                                    <!--   <label for="ssn" class="field-label">Social Security No. </label>
                                        <label class="field prepend-icon">
                                            <input type="text" name="ssn" id="ssn" class="gui-input" placeholder="000-00-0000">
                                            <span class="field-icon">
                                                <i class="fa fa-user"></i>
                                            </span>  
                                        </label> -->
                                </div>
                            </div>
                                    
                        <!--    <div class="spacer-b40 spacer-t30">
                                <div class="tagline">
                                    <span>Add Marriage or Domestic Partner</span>
                                </div>
                            </div> -->    
                                    
                           
						   
						   
						   
						   
						   

                            <div id="married" class="hiddenbox section smartform-reset">
                                <div class="frm-row">
                                    <div class="section colm colm4">
                                        <label for="spouse_firstname" class="field-label">Spouse First Name </label>
                                        <label class="field prepend-icon">
                                            <input type="text" name="spouse_firstname" id="spouse_firstname" class="gui-input" placeholder="First name">
                                            <span class="field-icon"><i class="fa fa-user"></i></span>  
                                        </label>
                                    </div>
                                
                                    <div class="section colm colm4">
                                        <label for="spouse_middlename" class="field-label">Spouse Middle Name </label>
                                        <label class="field prepend-icon">
                                            <input type="text" name="spouse_middlename" id="spouse_middlename" class="gui-input" placeholder="Middle name">
                                            <span class="field-icon"><i class="fa fa-user"></i></span>  
                                        </label>
                                    </div>
                                
                                    <div class="section colm colm4">
                                        <label for="spouse_lastname" class="field-label">Spouse Last Name </label>
                                        <label class="field prepend-icon">
                                            <input type="text" name="spouse_lastname" id="spouse_lastname" class="gui-input" placeholder="Last name">
                                            <span class="field-icon"><i class="fa fa-user"></i></span>  
                                        </label>
                                    </div>
                                </div>
                                    
                                <div class="frm-row">
                                        
                                    <div class="section colm colm4">
                                        <label for="spouse_mobile" class="field-label">Spouse Mobile Phone </label>
                                        <label class="field prepend-icon">
                                            <input type="text" name="spouse_mobile" id="spouse_mobile" class="gui-input" placeholder="(999)-999-9999">
                                            <span class="field-icon"><i class="fa fa-phone-square"></i></span>
                                        </label>
                                    </div>
                                        
                                   <div class="section colm colm4">
                                        <label for="spouse_ssn" class="field-label">Spouse SSN Last 4 Digits </label>
                                        <label class="field prepend-icon">
                                            <input type="text" name="spouse_ssn" id="spouse_ssn" class="gui-input" placeholder="1234">
                                            <span class="field-icon"><i class="fa fa-user"></i></span>  
                                        </label>
                                    </div>
									 <div class="section colm colm4">
                                       
                                    </div>
                                </div>
                                
								
                              <!--  <div class="frm-row">
                                    <div class="section colm colm4">
                                        <label for="spouse_birthplace" class="field-label">Spouse Birthplace </label>
                                        <label class="field prepend-icon">
                                            <input type="text" name="spouse_birthplace" id="spouse_birthplace" class="gui-input" placeholder="United States">
                                            <span class="field-icon"><i class="fa fa-user"></i></span>  
                                        </label>
                                    </div>
                                        
                                    <div class="section colm colm4">
                                        <label for="spouse_ssn" class="field-label">Spouse Social Security No. </label>
                                        <label class="field prepend-icon">
                                            <input type="text" name="spouse_ssn" id="spouse_ssn" class="gui-input" placeholder="000-00-0000">
                                            <span class="field-icon"><i class="fa fa-user"></i></span>  
                                        </label>
                                    </div>

                                    <div class="section colm colm4">
                                        <label for="spouse_dln" class="field-label">Spouse Drivers Lic No </label>
                                        <label class="field prepend-icon">
                                            <input type="text" name="spouse_dln" id="spouse_dln" class="gui-input" placeholder="D000000">
                                            <span class="field-icon"><i class="fa fa-user"></i></span>  
                                        </label>
                                    </div>
                                </div>  -->                      
                            </div>

                            <div id="domestic_partner" class="hiddenbox section smartform-reset">        
                                <div class="frm-row">
                                    <div class="section colm colm4">
                                        <label for="partner_firstname" class="field-label">Partner First Name </label>
                                        <label class="field prepend-icon">
                                            <input type="text" name="partner_firstname" id="partner_firstname" class="gui-input" placeholder="First name">
                                            <span class="field-icon"><i class="fa fa-user"></i></span>  
                                        </label>
                                    </div>
                                        
                                    <div class="section colm colm4">
                                        <label for="partner_middlename" class="field-label">Partner Middle Name </label>
                                        <label class="field prepend-icon">
                                            <input type="text" name="partner_middlename" id="partner_middlename" class="gui-input" placeholder="Middle name">
                                            <span class="field-icon"><i class="fa fa-user"></i></span>  
                                        </label>
                                    </div>
                                        
                                    <div class="section colm colm4">
                                        <label for="partner_lastname" class="field-label">Partner Last Name </label>
                                        <label class="field prepend-icon">
                                            <input type="text" name="partner_lastname" id="partner_lastname" class="gui-input" placeholder="Last name">
                                            <span class="field-icon"><i class="fa fa-user"></i></span>  
                                        </label>
                                    </div>
                                </div>
                                    
                                <div class="frm-row">
                                        
                                    <div class="section colm colm4">
                                        <label for="partner_mobile" class="field-label">Partner Mobile Phone </label>
                                        <label class="field prepend-icon">
                                            <input type="text" name="partner_mobile" id="partner_mobile" class="gui-input" placeholder="(999)-999-9999">
                                            <span class="field-icon"><i class="fa fa-phone-square"></i></span>
                                        </label>
                                    </div>
                                        
                                   <div class="section colm colm4">
                                        <label for="partner_ssn" class="field-label">Partner SSN. Last 4 </label>
                                        <label class="field prepend-icon">
                                            <input type="text" name="partner_ssn" id="partner_ssn" class="gui-input" placeholder="1234">
                                            <span class="field-icon"><i class="fa fa-user"></i></span>  
                                        </label>
                                    </div>
									
									 <div class="section colm colm4">
                                       
                                    </div>
                                </div>
                                    
                               
                            </div>

                            <div class="spacer-b40 spacer-t40">
                                <div class=""><span></span></div>
                            </div>
                        </fieldset>
                
                       <h2>Property <br>Information</h2>
                        <fieldset>   
                            <div class="spacer-b40">
                                <p class="medium fine-grey">
                                Please list your residences for the last 10 years. If you need add additional residences please click on the orange button to add them.</p>
                            </div>
                                
                            <div class="toclone clone-widget">
                                <div id="clone-group-fields2">
                                    <div class="toclone clone-widget2">
                                        <div class="frm-row">
                                            <div class="spacer-b10 colm colm8">
                                                <label for="residence_address" class="field-label">Residence Address </label>
                                                <label class="prepend-icon">
                                                    <input type="text" name="residence_addresses[]" id="residence_address" class="gui-input" placeholder="1234 Success Ave. Success City, CA">
                                                    <span class="field-icon"><i class="fa fa-user"></i></span> 
                                                </label>
                                            </div>

                                            <div class="spacer-b10 colm colm2">
                                                <label for="residence_from_date" class="field-label">From: </label>
                                                <label class="field prepend-icon">
                                                    <input type="text" name="residence_from_dates[]" id="residence_from_date" class="gui-input" placeholder="MM/YYYY" >
                                                    <span class="field-icon"><i class="fa fa-calendar"></i></span>
                                                </label>                 
                                            </div>

                                            <div class="spacer-b10 colm colm2">
                                                <label for="residence_to_date" class="field-label">To: </label>
                                                <label for="datesf2" class="field prepend-icon">
                                                    <input type="text" name="residence_to_dates[]" id="residence_to_date" class="gui-input" placeholder="MM/YYYY" >
                                                    <span class="field-icon"><i class="fa fa-calendar"></i></span>
                                                </label>                
                                            </div> 								
                                        </div>
                                        <label class="labelwid">Add More </label>
                                        <a href="#" class="clone button btn-primary"><i class="fa fa-plus"></i></a>
                                        <a href="#" class="delete button"><i class="fa fa-minus"></i></a>
                                    </div>  
                                </div>
                            </div>
                            
                            <div class="spacer-b40 spacer-t40">
                                <div class=""><span></span></div>
                            </div>      
                        </fieldset>
                                
                        <h2>About Your <br>Transaction</h2>
                        <fieldset>  
                            <div class="spacer-b40">
                                <p class="medium fine-grey">
                                Please tell us a little more about you and your transaction. This will help us to spot any unknown surprises that might delay your transaction.</p>
                            </div> 
                                
                           <!-- <div class="toclone clone-widget">
                                <div id="clone-group-fields">
                                    <div class="toclone clone-widget2">
                                        <div class="frm-row">
                                            <div class="spacer-b10 colm colm4">
                                                <label for="business_name" class="field-label">Business Name </label>
                                                <label class="prepend-icon">
                                                    <input type="text" name="business_names[]" id="business_name" class="gui-input" placeholder="Abc Company Inc">
                                                    <span class="field-icon"><i class="fa fa-user"></i></span> 
                                                </label>
                                            </div>

                                            <div class="spacer-b10 colm colm4">
                                                <label for="employment_address" class="field-label">Address </label>
                                                <label class="prepend-icon">
                                                    <input type="text" name="employment_addresses[]" id="employment_address" class="gui-input" placeholder="789 Sucess Ave. Success City, CA">
                                                    <span class="field-icon"><i class="fa fa-user"></i></span> 
                                                </label>
                                            </div>

                                            <div class="spacer-b10 colm colm2">
                                                <label for="employment_from_date" class="field-label">From: </label>
                                                <label for="datesf1" class="field prepend-icon">
                                                    <input type="text" name="employment_from_dates[]" id="employment_from_date" class="gui-input" placeholder="MM/YYYY" >
                                                    <span class="field-icon"><i class="fa fa-user"></i></span>
                                                </label>                 
                                            </div>

                                            <div class="spacer-b10 colm colm2">
                                                <label for="employment_to_date" class="field-label">To: </label>
                                                <label for="datesf2" class="field prepend-icon">
                                                    <input type="text" name="employment_to_dates[]" id="employment_to_date" class="gui-input" placeholder="MM/YYYY" >
                                                    <span class="field-icon"><i class="fa fa-user"></i></span>
                                                </label>                
                                            </div> 								
                                        </div>

                                        <label class="labelwid">Add More </label>
                                        <a href="#" class="clone button btn-primary"><i class="fa fa-plus"></i></a>
                                        <a href="#" class="delete button"><i class="fa fa-minus"></i></a>
                                    </div>  
                                </div>
                            </div> -->
                                
                         <!--   <div class="spacer-b40 spacer-t40">
                                <div class="tagline"><span>Add Marriage or Domestic Partner Occupation</span></div>
                            </div> -->
                                
                            
							
							<div class="section">
							 <div class="frm-row">
								<div class="section colm colm10">
                                    <p class="medium fine-grey">Is <?php echo $propertyAddress;?> the property address for this transaction?</p>
								</div>
								<div class="section colm colm2">
									 <label class="modern-switch">
										<span class="switch-label">No</span>  
										<input type="checkbox" id="street_address" name="street_address">
										<span class="switch-toggle"></span>
										<span class="switch-label">Yes</span>                      
									</label>
								</div>
                             </div>
							</div>
							<div class="section">
							 <div class="frm-row">
								<div class="section colm colm10">
								 <p class="medium fine-grey">
                                Do the buyer intend to use this as their primary residence?</p>
								</div>
								<div class="section colm colm2">
									 <label class="modern-switch">
										<span class="switch-label">No</span>  
										<input type="checkbox" id="buyer_intends_to_reside" name="buyer_intends_to_reside">
										<span class="switch-toggle"></span>
										<span class="switch-label">Yes</span>                      
									</label>
								</div>
                             </div>
							</div>
							<div class="section">
							<div class="frm-row">
								<div class="section colm colm10">
								 <p class="medium fine-grey">
                                Is the Land Improved?</p>
								</div>
								<div class="section colm colm2">
									 <label class="modern-switch">
										<span class="switch-label">No</span>  
										<input type="checkbox" id="land_is_unimproved" name="land_is_unimproved">
										<span class="switch-toggle"></span>
										<span class="switch-label">Yes</span>                      
									</label>
								</div>
                             </div>
							</div>
							<div class="section">
							 <div class="frm-row">
								<div class="section colm colm10">
								 <p class="medium fine-grey">
                                Is this property a SFR, 1-4 Units, or Condominium?</p>
								</div>
								<div class="section colm colm2">
									 <label class="modern-switch">
										<span class="switch-label">No</span>  
										<input type="checkbox" id="type_of_property" name="type_of_property">
										<span class="switch-toggle"></span>
										<span class="switch-label">Yes</span>                      
									</label>
								</div>
                             </div>
							</div>
							<div class="section">
							 <div class="frm-row">
								<div class="section colm colm10">
								 <p class="medium fine-grey">
                                 Any work done on the premise on the last 6 months?</p>
								</div>
								<div class="section colm colm2">
									 <label class="modern-switch">
										<span class="switch-label">No</span>  
										<input type="checkbox" id="work_done_last_6_month" name="work_done_last_6_month">
										<span class="switch-toggle"></span>
										<span class="switch-label">Yes</span>                      
									</label>
								</div>
                             </div>
							</div>
							<div class="section">
							 <div class="frm-row">
								<div class="section colm colm10">
								 <p class="medium fine-grey">
                                 Were you previously married?</p>
								</div>
								<div class="section colm colm2">
									 <label class="modern-switch">
										<span class="switch-label">No</span>  
										<input type="checkbox" id="previously_married" name="previously_married">
										<span class="switch-toggle"></span>
										<span class="switch-label">Yes</span>                      
									</label>
								</div>
                             </div>
							</div>
							<div class="section">
							 <div class="frm-row">
								<div class="section colm colm9">
								 <p class="medium fine-grey">
                                Are you currently employed or in the past own a business/corporation/llc/sole proprietorship</p>
								</div>
								<div class="section colm colm3">
									<div class="option-group field">
										<label class="option">
											<input type="radio" name="employment_status" class="smartfm-ctrl" value="add_business" data-show-id="add_business">
											<span class="radio"></span> Yes
										</label>
										<label class="option">
											<input type="radio" name="employment_status" class="smartfm-ctrl" value="dont_add"  data-show-id="dont_add">
											<span class="radio"></span> No          
										</label>         
									</div>
								</div>
                            </div>
							</div>
                                    
                            <div id="add_business" class="hiddenbox section smartform-reset">   
                                <div class="toclone clone-widget">
                                    <div id="clone-group-fields3">
                                        <div class="toclone clone-widget2">
                                            <div class="frm-row">
                                                <div class="spacer-b10 colm colm4">
                                                    <label for="partner_business_name" class="field-label">Business Name </label>
                                                    <label class="prepend-icon">
                                                        <input type="text" name="business_names[]" id="business_names" class="gui-input" placeholder="Abc Company Inc">
                                                        <span class="field-icon"><i class="fa fa-user"></i></span> 
                                                    </label>
                                                </div>

                                                <div class="spacer-b10 colm colm4">
                                                    <label for="partner_address" class="field-label">Address </label>
                                                    <label class="prepend-icon">
                                                        <input type="text" name="employment_addresses[]" id="employment_addresses" class="gui-input" placeholder="789 Sucess Ave. Success City, CA">
                                                        <span class="field-icon"><i class="fa fa-user"></i></span> 
                                                    </label>
                                                </div>

                                                <div class="spacer-b10 colm colm2">
                                                    <label for="partner_from_date" class="field-label">From: </label>
                                                    <label for="datesf1" class="field prepend-icon">
                                                        <input type="text" name="employment_from_dates[]" id="employment_from_dates" class="gui-input" placeholder="MM/YYYY" >
                                                        <span class="field-icon"><i class="fa fa-user"></i></span>
                                                    </label>                 
                                                </div>

                                                <div class="spacer-b10 colm colm2">
                                                    <label for="partner_to_date" class="field-label">To: </label>
                                                    <label for="datesf2" class="field prepend-icon">
                                                        <input type="text" name="employment_to_dates[]" id="employment_to_dates" class="gui-input" placeholder="MM/YYYY" >
                                                        <span class="field-icon"><i class="fa fa-user"></i></span>
                                                    </label>                
                                                </div> 								
                                            </div>
                                            <label class="labelwid">Add More </label>
                                            <a href="#" class="clone button btn-primary"><i class="fa fa-plus"></i></a>
                                            <a href="#" class="delete button"><i class="fa fa-minus"></i></a>
                                        </div>  
                                    </div>
                                </div>
                            </div> 

							
							
                            <div class="spacer-b40 spacer-t40">
                                <div class=""><span></span></div>
                            </div>	
                        </fieldset>
                                
                                                
                                
                        <h2>Sign & <br> Submit</h2>
                        <fieldset>
                            <!-- <div class="spacer-b40">
                                <p class="medium fine-grey">Please complete the information below prior to hitting submit.</p>
                            </div> 
                                                    
                            <div class="spacer-b30 spacer-t30">
                                <div class="tagline"><span>About the Property</span></div>
                            </div>

                            <div class="frm-row">
                                <div class="section colm colm12">
                                    <label for="street_address" class="field-label">Street Address </label>
                                    <label class="field prepend-icon">
                                        <input type="text" name="street_address" id="street_address" class="gui-input" placeholder="Street Address">
                                        <span class="field-icon"><i class="fa fa-user"></i></span>  
                                    </label>
                                </div>
                            </div>
                                    
                            <div class="frm-row">
                                <div class="section colm colm6">
                                    <label for="land_is_unimproved" class="field-label">The land is unimproved </label>
                                    <label class="field select">
                                        <select id="land_is_unimproved" name="land_is_unimproved">
                                            <option value="">Select</option>
                                            <option value="yes">Yes</option>
                                            <option value="no">No</option>
                                        </select>
                                        <i class="arrow double"></i>
                                    </label>
                                </div>

                                <div class="section colm colm6">
                                    <label for="type_of_property" class="field-label">Type of property on land </label>
                                    <label class="field select">
                                        <select id="type_of_property" name="type_of_property">
                                            <option value="">Select</option>
                                            <option value="single_family_1_4">Single Family 1-4</option>
                                            <option value="condo_unit">Condo Unit</option>
                                            <option value="other">Other</option>
                                        </select>
                                        <i class="arrow double"></i>
                                    </label>
                                </div>
                            </div>
                                        
                            <div class="frm-row">     
                                <div class="section colm colm12">
                                    <label for="buyer_intends" class="field-label">Buyer Intends to reside on the property for this transaction? </label>
                                    <div class="option-group field">
                                        <label class="option">
                                            <input type="radio" name="buyer_intends" class="smartfm-ctrl" value="yes">
                                            <span class="radio"></span> Yes
                                        </label>
                                        <label class="option">
                                            <input type="radio" name="buyer_intends" class="smartfm-ctrl" value="no">
                                            <span class="radio"></span> No
                                        </label> 
                                    </div>         
                                </div>
                            </div> -->
                                    
                            <div class="spacer-b30 spacer-t10">
                                <div class="tagline"><span>Disclosure</span></div>
                            </div>   

                            <div class="frm-row">
                                <div class="section colm colm12">
                                    <div class="option-group field">
                                        <label class="option">
                                            <input type="checkbox" id="general_terms" name="general_terms" value="General Terms">
                                            <span class="checkbox"></span> 
                                            The undersigned declare, under penalty of perjury, that the foregoing is true and correct.                
                                        </label>
                                    </div>

                                    <div class="spacer-b20">
                                    
                                    </div>
                                </div>
                            </div>
                                        
                            <div class="frm-row">
                                <div class="section colm colm6">
                                    <label for="signature" class="field-label">Please Type your name in the box below </label>
                                    <label class="field prepend-icon">
                                        <input type="text" name="signature" id="signature" class="gui-input" placeholder="">
                                        <span class="field-icon"><i class="fa fa-user"></i></span>   

                                    </label>
                                </div>

                                <div class="section colm colm6">
                                    <label for="spouse_signature" class="field-label">Please TYPE your spouse's name. (if applicable) </label>
                                    <label class="field prepend-icon">
                                        <input type="text" name="spouse_signature" id="spouse_signature" class="gui-input" placeholder="">
                                        <span class="field-icon"><i class="fa fa-user"></i></span>  
                                    </label>
                                </div>
                            </div>     
                            <div class="result"></div>
                        </fieldset>

							<!--
							
                            <div class="section">
                                <div class="option-group field">
                                    <label class="option">
                                        <input type="radio" name="partnership_status" class="smartfm-ctrl" value="me"  data-show-id="me">
                                        <span class="radio"></span> Me Only
                                    </label>
                                    <label class="option">
                                        <input type="radio" name="partnership_status" class="smartfm-ctrl" value="spouseonly"  data-show-id="spouseonly">
                                        <span class="radio"></span> Spouse Only
                                    </label>
                                    <label class="option">
                                        <input type="radio" name="partnership_status" class="smartfm-ctrl" value="both"  data-show-id="both">
                                        <span class="radio"></span> Both
                                    </label>
                                    <label class="option">
                                        <input type="radio" name="partnership_status" class="smartfm-ctrl" value="notmarried"  data-show-id="notmarried">
                                        <span class="radio"></span> Not Married          
                                    </label>         
                                </div>
                            </div>
                            
                            <div class="spacer-b30 spacer-t30">
                                <div class=""><span></span></div>
                            </div>
                                
                            <div id="me" class="hiddenbox section smartform-reset">
                                <div class="frm-row">
                                    <div class="section colm colm6">
                                        <label for="prior_spouse_name" class="field-label">Prior Spouse Name </label>
                                        <label class="field prepend-icon">
                                            <input type="text" name="prior_spouse_name" id="prior_spouse_name" class="gui-input" placeholder="">
                                            <span class="field-icon"><i class="fa fa-user"></i></span>  
                                        </label>
                                    </div>
                                    
                                    <div class="section colm colm4">
                                        <label for="prior_spouse_reason" class="field-label">Reason For End </label>
                                        <label class="field select">
                                            <select id="prior_spouse_reason" name="prior_spouse_reason">
                                                <option value="">Reason</option>
                                                <option value="Death">Death</option>
                                                <option value="Divorce">Divorce</option>
                                            </select>
                                            <i class="arrow double"></i>
                                        </label>
                                    </div>
                                    
                                    <div class="spacer-b10 colm colm2">
                                        <label for="prior_spouse_end" class="field-label">End: </label>
                                        <label class="field prepend-icon">
                                            <input type="text" name="prior_spouse_end" id="prior_spouse_end" class="gui-input" placeholder="MM/YYYY" >
                                            <span class="field-icon"><i class="fa fa-user"></i></span>
                                        </label>                
                                    </div> 	
                                </div>
                            </div>
                            
                            <div id="spouseonly" class="hiddenbox section smartform-reset">
                                <div class="frm-row">
                                    <div class="section colm colm6">
                                        <label for="current_spouse_prior_spouse_name" class="field-label">Current Spouse's Prior Spouse Name </label>
                                        <label class="field prepend-icon">
                                            <input type="text" name="current_spouse_prior_spouse_name" id="current_spouse_prior_spouse_name" class="gui-input" placeholder="">
                                            <span class="field-icon"><i class="fa fa-user"></i></span>  
                                        </label>
                                    </div>
                                
                                    <div class="section colm colm4">
                                        <label for="current_spouse_prior_spouse_reason" class="field-label">Reason For End </label>
                                        <label class="field select">
                                            <select id="current_spouse_prior_spouse_reason" name="current_spouse_prior_spouse_reason">
                                                <option value="">Reason</option>
                                                <option value="Death">Death</option>
                                                <option value="Divorce">Divorce</option> 
                                            </select>
                                            <i class="arrow double"></i>
                                        </label>
                                    </div>
                                
                                    <div class="spacer-b10 colm colm2">
                                        <label for="current_spouse_prior_spouse_end" class="field-label">End: </label>
                                        <label class="field prepend-icon">
                                            <input type="text" name="current_spouse_prior_spouse_end" id="current_spouse_prior_spouse_end" class="gui-input" placeholder="MM/YYYY" >
                                            <span class="field-icon"><i class="fa fa-user"></i></span>
                                        </label>                
                                    </div> 	
                                </div>
                            </div> 

                            <div id="both" class="hiddenbox section smartform-reset">
                                <div class="frm-row">
                                    <div class="section colm colm6">
                                        <label for="prior_spouse_name_both" class="field-label">Prior Spouse Name </label>
                                        <label class="field prepend-icon">
                                            <input type="text" name="prior_spouse_name_both" id="prior_spouse_name_both" class="gui-input" placeholder="">
                                            <span class="field-icon"><i class="fa fa-user"></i></span>  
                                        </label>
                                    </div>
                                    
                                    <div class="section colm colm4">
                                        <label for="prior_spouse_reason_both" class="field-label">Reason For End </label>
                                        <label class="field select">
                                            <select id="prior_spouse_reason_both" name="prior_spouse_reason_both">
                                                <option value="">Reason</option>
                                                <option value="Death">Death</option>
                                                <option value="Divorce">Divorce</option>
                                            </select>
                                            <i class="arrow double"></i>
                                        </label>
                                    </div>
                                    
                                    <div class="spacer-b10 colm colm2">
                                        <label for="prior_spouse_end_both" class="field-label">End: </label>
                                        <label class="field prepend-icon">
                                            <input type="text" name="prior_spouse_end_both" id="prior_spouse_end_both" class="gui-input" placeholder="MM/YYYY" >
                                            <span class="field-icon"><i class="fa fa-user"></i></span>
                                        </label>                
                                    </div> 	
                                </div>
                                
                                <div class="frm-row">
                                    <div class="section colm colm6">
                                        <label for="current_spouse_prior_spouse_name_both" class="field-label">Current Spouse's Prior Spouse Name </label>
                                        <label class="field prepend-icon">
                                            <input type="text" name="current_spouse_prior_spouse_name_both" id="current_spouse_prior_spouse_name_both" class="gui-input" placeholder="">
                                            <span class="field-icon"><i class="fa fa-user"></i></span>  
                                        </label>
                                    </div>
                                
                                    <div class="section colm colm4">
                                        <label for="current_spouse_prior_spouse_reason_both" class="field-label">Reason For End </label>
                                        <label class="field select">
                                            <select id="current_spouse_prior_spouse_reason_both" name="current_spouse_prior_spouse_reason_both">
                                                <option value="">Reason</option>
                                                <option value="Death">Death</option>
                                                <option value="Divorce">Divorce</option> 
                                            </select>
                                            <i class="arrow double"></i>
                                        </label>
                                    </div>
                                
                                    <div class="spacer-b10 colm colm2">
                                        <label for="current_spouse_prior_spouse_end_both" class="field-label">End: </label>
                                        <label class="field prepend-icon">
                                            <input type="text" name="current_spouse_prior_spouse_end_both" id="current_spouse_prior_spouse_end_both" class="gui-input" placeholder="MM/YYYY" >
                                            <span class="field-icon"><i class="fa fa-user"></i></span>
                                        </label>                
                                    </div> 	
                                </div>
                            </div>
                                
                            <div id="notmarried" class="hiddenbox section smartform-reset">
                        
                            </div>
							
							-->
                    </form>  
                <?php } else { ?>   
                    <div id="borrower_error_msg" class="w-100">
                        <h2 style="margin: 20px 0;">Wire Instructions</h2>
                        <fieldset>
                            <div class="spacer-b40">
                                <div class="section center">
                                    <p class="medium fine-grey">
                                    Please verify that the information below is correct. You will receive an email invitation from us in order to verify your banking information. This is inteneded to prevent any wire fraud on your transaction. If you have questions about wire fraud we have created a quick video <br> the can help you understand what it us. <underline>Video Link</underline></p>
                                </div>
                            </div>
                            
                            <div class="section center">
                                <div class="frm-row">
                                    <div class="section colm colm12">
                                        <button type="" class="button btn-secondary"><a id="wire_instruction" target="_blank" href="">Check Your Email</a></button>
                                    </div>
                                    
                                </div>
                            </div>
                            
                            <div class="section center">
                                <div class="frm-row">
                                    <div class="section colm colm12">
                                        <p class="medium fine-grey" id="borrower_name"><strong>Borrower Name:</strong> <?php echo $borrower_name;?></p>
                                        <p class="medium fine-grey" id="borrower_address"><strong>Borrower Address:</strong> <?php echo $borrower_address;?></p>
                                        <p class="medium fine-grey" id="escrow_partner"><strong>Escrow Partner:</strong> Pacific Coast Title Company - Escrow Officer</p>
                                        <p class="medium fine-grey" id="escrow_officer"><strong>Escrow Officer:</strong> <?php echo $escrow_officer;?></p>
                                    </div>
                                   
                                </div>
                            </div>
                            <div class="spacer-b40 spacer-t40">
                                <div class=""><span></span></div>
                            </div>
							<div class="section center">
                                <div class="frm-row">
                                    <div class="section colm colm12">
                                        <p class="medium fine-grey">  
										<h3>What is Wire Fraud?</h3>
										<a class="lightbox" href="https://youtu.be/_1cVVoJG5x8"/> <img src="http://dev.pacificcoasttitle.com/assets/media/content/bg/Email.jpg"/></a>
										</p>
                                    </div>
                                    
                                </div>
                            </div>
							 <div class="spacer-b40 spacer-t40">
                                <div class=""><span></span></div>
                            </div>
							
							<div class="section">
                                <div class="frm-row">
                                    <div class="section colm colm5">
                                        <p class="medium fine-grey">  
										<h3>What is a wire?</h3>
										<p class="medium fine-grey">A wire, or wire transfer, is an electronic transfer of money across a network from one bank or credit union to another. With a wire, no physical money moves between bank locations, but people or entities are able to “wire” money to another person or entity as long as they have a bank account. Wires are typically used in most real estate transactions because funds are received more quickly (usually the same day), and there are no holds placed on the money once received.</p>
										</p>
                                        <br>
										<p class="medium fine-grey">
										<h3>What is a wire?</h3>										
										<p class="medium fine-grey">Wire instructions are the directions you follow when sending money electronically to another person or entity. In the case of your home purchase, the funds are typically sent to your settlement agent. Wire instructions typically include:
										
										<ul>
										<li class="medium fine-grey">Bank Name and Address</li>
										<li class="medium fine-grey">Bank ABA Number</li>
										<li class="medium fine-grey">Bank Account Number</li>
										<li class="medium fine-grey">Account Holder’s Name</li>
										<li class="medium fine-grey">Reference Information (in real estate transactions, this is usually a file number)</li>
										
										</p>
                                        
                                    </div>
                                    <div class="section colm colm5"> 

									<p class="medium fine-grey">  
										<h3>What are wire instructions?</h3>
										<p class="medium fine-grey">Wire fraud typically involves a hacker gaining access to an email account and posing as a trusted party involved in your real estate transaction. This could be someone pretending to be your real estate agent, loan officer, title agent, or even an attorney. Once the hacker has access to a trusted email account, the hacker sends an email from that account or from a similar account that looks “almost” the same as one of the parties in the transaction – with information related to your transaction, including wire instructions for your closing funds. If you react to that email, your funds are sent to an account controlled by the hacker in some manner. Once receipt of the money is confirmed, the hacker immediately withdrawals your funds from that account using multiple transfers to accounts normally outside the United States. Once these transfers occur, the likelihood of recovery is small, if at all.</p>
									
									
									
									
									</div>
                                </div>
                            </div>

                        </fieldset>  
                    </div>
                <?php }  ?>                                                                           
            </div>
        </div>
    </div>
	<section class="section-type-1si section-sm parallax area-bg area-bg_grad-7 area-bg_op_80"></section>
        <?php
            $this->load->view('layout/footer');
        ?>
</body>

</html>

<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/frontend/css/smart-forms-borrower.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/frontend/css/smart-addons-borrower.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/frontend/css/font-awesome-borrower.min.css">




<script type="text/javascript" src="<?php echo base_url(); ?>assets/frontend/js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/frontend/js/jquery.steps.min.js"></script> 
<script type="text/javascript" src="<?php echo base_url(); ?>assets/frontend/js/jquery-ui-custom.min.js"></script> 
<script type="text/javascript" src="<?php echo base_url(); ?>assets/frontend/js/jquery.validate.min.js"></script> 

<script type="text/javascript" src="<?php echo base_url(); ?>assets/frontend/js/additional-methods.min.js"></script> 
<script type="text/javascript" src="<?php echo base_url(); ?>assets/frontend/js/jquery-ui-slider-pips.min.js"></script> 
<script type="text/javascript" src="<?php echo base_url(); ?>assets/frontend/js/jquery-ui-touch-punch.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/frontend/js/jquery.formShowHide.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/frontend/js/jquery.maskedinput.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/frontend/js/jquery-cloneya.min.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/rendro-easy-pie-chart/waypoints.min.js"></script>

	
	<!-- ligthbox-->
    <script type="text/javascript" src="http://www.pct.com/assets/js/lightbox/jquery.lightbox.min.js"></script>
	
	
	<script type="text/javascript">
  jQuery(document).ready(function($){
    $('.lightbox').lightbox();
  });
</script>

<!-- Facebook Pixel Code -->



<script type="text/javascript">
    $(document).ready(function(){
        
        var safeWireFlag = false;
        $("#borrower-form").steps({
            bodyTag: "fieldset",
            headerTag: "h2",
            bodyTag: "fieldset",
            transitionEffect: "slideLeft",
            labels: {
                next: "Continue",
                previous: "Go Back",
                loading: "Loading..." 
            },
            onStepChanging: function (event, currentIndex, newIndex){
                if (currentIndex > newIndex){return true; }
                var form = $(this);
                if (currentIndex < newIndex){}
                if(currentIndex == 0) {
                    if(form.valid() === true) {
                        $.ajax({
                            url: '<?php  echo base_url(); ?>create-order-safewire',
                            type: "POST",
                            data: {
                                file_id: $("#file_id").val(),
                                order_id: $("#order_id").val(),
                                firstname: $("#firstname").val(),
                                lastname: $("#lastname").val(),
                                email: $("#email").val(),
                                mobile:  $("#mobile").val()
                            },
                            async: true,
                            beforeSend: function() {
                                $('#page-preloader').css('display', 'block');
                                $('#borrower_page').css('opacity', '0.5');
                            },
                            success: function(result) {
                                $('#page-preloader').css('display', 'none');
                                $('#borrower_page').css('opacity', '1');
                                var res = jQuery.parseJSON(result);
                                if (res.success === true) {
                                    //$('#wire_instruction').attr("href", res.action_link);
                                    safeWireFlag = true;
                                } else {
                                    alert(res.message);
                                    $("a[href$='previous']").click();
                                }
                            },
                            error:function(){
                                $('#page-preloader').css('display', 'none');
                                $('#borrower_page').css('opacity', '1');
                                alert('Something went wrong');
                                safeWireFlag = false;
                            },
                        });
                    } else {
                        return form.valid();
                        
                    }
                    return true;
                } else {
                    
                    return form.valid();
                }
            },
            onStepChanged: function (event, currentIndex, priorIndex){
            },
            onFinishing: function (event, currentIndex){
                $('#page-preloader').css('display', 'block');
                $('#borrower_page').css('opacity', '0.5');
                var form = $(this);
                form.validate().settings.ignore = ":disabled";
                if(form.valid() === true) {
                    $("#borrower-form")[0].submit();
                } else {
                    return form.valid();
                }
               
            },
            onFinished: function (event, currentIndex){
                var form = $(this);
            }
        }).validate({
            errorClass: "state-error",
            validClass: "state-success",
            errorElement: "em",
            onkeyup: false,
            onclick: false,
            rules: {
                firstname: {
                    required: true
                },
                /*middlename: {
                    required: true
                },*/
                lastname: {
                     required: true
                },
                email: {
                    required: true,
                    email: true    
                },
                mobile: {
                    required: true
                },			
                // date_of_birth: {
                //     required: true
                // },
                /*birthplace:{
                    required: true
                },*/
                // ssn:{
                //     required: true
                // },
                /*dln:{
                    required: true
                },*/
                buyer_seller :{
                     required: true
                 },		
                // status:{
                //     required: true
                // },	
                /*street_address:{
                    required: true
                },
                land_is_unimproved:{
                    required: true
                },
                type_of_property:{
                    required: true
                },
                buyer_intends:{
                    required: true
                },*/
                // general_terms:{
                //     required: true
                // },
                // signature:{
                //     required: true
                // },
                // spouse_firstname:{
                //     required: {
                //         depends: function(element) {
                //             return ($("input[name=status]:checked").val() == "married");
                //         },
                //     },
                // },
                /*spouse_middlename:{
                    required: {
                        depends: function(element) {
                            return ($("input[name=status]:checked").val() == "married");
                        },
                    },
                },*/
                // spouse_lastname:{
                //     required: {
                //         depends: function(element) {
                //             return ($("input[name=status]:checked").val() == "married");
                //         },
                //     },
                // },	
                /*spouse_telephone:{
                    required: {
                        depends: function(element) {
                            return ($("input[name=status]:checked").val() == "married");
                        },
                    },
                },*/	
                // spouse_mobile:{
                //     required: {
                //         depends: function(element) {
                //             return ($("input[name=status]:checked").val() == "married");
                //         },
                //     },
                // },
                /*spouse_date_of_birth:{
                    required: {
                        depends: function(element) {
                            return ($("input[name=status]:checked").val() == "married");
                        },
                    },
                },	
                spouse_birthplace:{
                    required: {
                        depends: function(element) {
                            return ($("input[name=status]:checked").val() == "married");
                        },
                    },
                },*/
                // spouse_ssn:{
                //     required: {
                //         depends: function(element) {
                //             return ($("input[name=status]:checked").val() == "married");
                //         },
                //     },
                // },
                /*spouse_dln:{
                    required: {
                        depends: function(element) {
                            return ($("input[name=status]:checked").val() == "married");
                        },
                    },
                },*/
                // partner_firstname:{
                //     required: {
                //         depends: function(element) {
                //             return ($("input[name=status]:checked").val() == "domestic_partner");
                //         },
                //     },
                // },
                /*partner_middlename:{
                    required: {
                        depends: function(element) {
                            return ($("input[name=status]:checked").val() == "domestic_partner");
                        },
                    },
                },*/
                // partner_lastname:{
                //     required: {
                //         depends: function(element) {
                //             return ($("input[name=status]:checked").val() == "domestic_partner");
                //         },
                //     },
                // },	
                /*partner_telephone:{
                    required: {
                        depends: function(element) {
                            return ($("input[name=status]:checked").val() == "domestic_partner");
                        },
                    },
                },*/
                // partner_mobile:{
                //     required: {
                //         depends: function(element) {
                //             return ($("input[name=status]:checked").val() == "domestic_partner");
                //         },
                //     },
                // },
                /*partner_date_of_birth:{
                    required: {
                        depends: function(element) {
                            return ($("input[name=status]:checked").val() == "domestic_partner");
                        },
                    },
                },	
                partner_birthplace:{
                    required: {
                        depends: function(element) {
                            return ($("input[name=status]:checked").val() == "domestic_partner");
                        },
                    },
                },*/
                // partner_ssn:{
                //     required: {
                //         depends: function(element) {
                //             return ($("input[name=status]:checked").val() == "domestic_partner");
                //         },
                //     },
                // },
                /*partner_dln:{
                    required: {
                        depends: function(element) {
                            return ($("input[name=status]:checked").val() == "domestic_partner");
                        },
                    },
                },*/
                // employment_status:{
                //     required: true
                // },	
                /*partnership_status:{
                    required: true
                },	
                prior_spouse_name:{
                    required: {
                        depends: function(element) {
                            return ($("input[name=partnership_status]:checked").val() == "me");
                        },
                    },
                },
                prior_spouse_reason:{
                    required: {
                        depends: function(element) {
                            return ($("input[name=partnership_status]:checked").val() == "me");
                        },
                    },
                },
                prior_spouse_end:{
                    required: {
                        depends: function(element) {
                            return ($("input[name=partnership_status]:checked").val() == "me");
                        },
                    },
                },
                current_spouse_prior_spouse_name:{
                    required: {
                        depends: function(element) {
                            return ($("input[name=partnership_status]:checked").val() == "spouseonly");
                        },
                    },
                },
                current_spouse_prior_spouse_reason:{
                    required: {
                        depends: function(element) {
                            return ($("input[name=partnership_status]:checked").val() == "spouseonly");
                        },
                    },
                },
                current_spouse_prior_spouse_end:{
                    required: {
                        depends: function(element) {
                            return ($("input[name=partnership_status]:checked").val() == "spouseonly");
                        },
                    },
                },
                prior_spouse_name_both:{
                    required: {
                        depends: function(element) {
                            return ($("input[name=partnership_status]:checked").val() == "both");
                        },
                    },
                },
                prior_spouse_reason_both:{
                    required: {
                        depends: function(element) {
                            return ($("input[name=partnership_status]:checked").val() == "both");
                        },
                    },
                },
                prior_spouse_end_both:{
                    required: {
                        depends: function(element) {
                            return ($("input[name=partnership_status]:checked").val() == "both");
                        },
                    },
                },
                current_spouse_prior_spouse_name_both:{
                    required: {
                        depends: function(element) {
                            return ($("input[name=partnership_status]:checked").val() == "both");
                        },
                    },
                },
                current_spouse_prior_spouse_reason_both:{
                    required: {
                        depends: function(element) {
                            return ($("input[name=partnership_status]:checked").val() == "both");
                        },
                    },
                },
                current_spouse_prior_spouse_end_both:{
                    required: {
                        depends: function(element) {
                            return ($("input[name=partnership_status]:checked").val() == "both");
                        },
                    },
                },*/
                // "residence_addresses[]": "required",	
                // "residence_from_dates[]": "required",
                // "residence_to_dates[]": "required",	
                // "business_names[]": "required",	
                // "employment_addresses[]": "required",	
                // "employment_from_dates[]": "required",
                // "employment_to_dates[]": "required",
                /*"partner_business_names[]": "required",	
                "partner_addresses[]": "required",	
                "partner_from_dates[]": "required",
                "partner_to_dates[]": "required",*/	
            },
            messages: {
                firstname: {
                    required: "Please enter first name"
                },
                middlename: {
                    required: "Please enter middle name"
                },
                lastname: {
                    required: "Please enter last name"
                },
                mobile: {
                    required: "Please enter mobile number"
                },
                telephone: {
                    required: 'Please enter telephone',
                },					
                date_of_birth: {
                    required: "Please enter date of birth"
                },
                email: {
                    required: "Please enter email"
                },
                ssn:{
                    required: 'Please enter social security no'
                },
                dln:{
                    required: 'Please enter drivers lic no'
                },	
                status:{
                    required: 'Please select one of option'
                },	
                buyer_seller:{
                    required: 'Please select one of option'
                },
                spouse_firstname: {
                    required: "Please enter spouse first name"
                },
                spouse_middlename: {
                    required: "Please enter spouse middle name"
                },
                spouse_lastname: {
                    required: "Please enter spouse last name"
                },
                spouse_mobile: {
                    required: "Please enter spouse mobile number"
                },
                spouse_telephone: {
                    required: 'Please enter spouse telephone',
                },					
                spouse_date_of_birth: {
                    required: "Please enter spouse date of birth"
                },
                spouse_birthplace: {
                    required: "Please enter spouse birth place"
                },
                spouse_ssn:{
                    required: 'Please enter spouse social security no'
                },
                spouse_dln:{
                    required: 'Please enter spouse drivers lic no'
                },
                partner_firstname: {
                    required: "Please enter partner first name"
                },
                partner_middlename: {
                    required: "Please enter partner middle name"
                },
                partner_lastname: {
                    required: "Please enter partner last name"
                },
                partner_mobile: {
                    required: "Please enter partner mobile number"
                },
                partner_telephone: {
                    required: 'Please enter partner telephone',
                },					
                partner_date_of_birth: {
                    required: "Please enter partner date of birth"
                },
                partner_birthplace: {
                    required: "Please enter partner birth place"
                },
                partner_ssn:{
                    required: 'Please enter partner social security no'
                },
                partner_dln:{
                    required: 'Please enter partner drivers lic no'
                },
                employment_status:{
                    required: 'Please select one of option'
                },
                partnership_status:{
                    required: 'Please select one of option'
                },
                prior_spouse_name: {
                    required: "Please enter prior spouse name"
                },
                prior_spouse_reason: {
                    required: "Please select reason for end"
                },
                prior_spouse_end: {
                    required: "Please enter prior spouse end date"
                },
                current_spouse_prior_spouse_name: {
                    required: "Please enter current prior spouse name"
                },
                current_spouse_prior_spouse_reason: {
                    required: "Please select reason for end"
                },
                current_spouse_prior_spouse_end: {
                    required: "Please enter current prior spouse end date"
                },
                prior_spouse_name_both: {
                    required: "Please enter prior spouse name"
                },
                prior_spouse_reason_both: {
                    required: "Please select reason for end"
                },
                prior_spouse_end_both: {
                    required: "Please enter prior spouse end date"
                },
                current_spouse_prior_spouse_name_both: {
                    required: "Please enter current prior spouse name"
                },
                current_spouse_prior_spouse_reason_both: {
                    required: "Please select reason for end"
                },
                current_spouse_prior_spouse_end_both: {
                    required: "Please enter current prior spouse end date"
                },
                street_address: {
                    required: "Please enter street address"
                },
                type_of_property: {
                    required: "Please select one option"
                },
                land_is_unimproved: {
                    required: "Please select one option"
                },
                buyer_intends: {
                    required: "Please select one option"
                },
                general_terms: {
                    required: "Please check the checkbox"
                },
                signature: {
                    required: "Please enter the name"
                },
                "residence_addresses[]": "Please enter address",
                "residence_from_dates[]": "Please enter from date",
                "residence_to_dates[]": "Please enter to date",
                "business_names[]": "Please enter business names",	
                "employment_addresses[]": "Please enter address",	
                "employment_from_dates[]": "Please enter from date",
                "employment_to_dates[]": "Please enter to date",
                "partner_business_names[]": "Please enter business names",	
                "partner_addresses[]": "Please enter address",	
                "partner_from_dates[]": "Please enter from date",
                "partner_to_dates[]": "Please enter to date",			
            },
            highlight: function(element, errorClass, validClass) {
                $(element).closest('.field').addClass(errorClass).removeClass(validClass);
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).closest('.field').removeClass(errorClass).addClass(validClass);
            },
            errorPlacement: function(error, element) {
                if (element.is(":radio") || element.is(":checkbox")) {
                    element.closest('.option-group').after(error);
                } else {
                    error.insertAfter(element.parent());
                }
            }
        
        });

        

        /* Show hide payment options
        ------------------------------------------------------- */
        $('.smartfm-ctrl').formShowHide();

        /* @normal masking rules 
			---------------------------------------------------------- */
				
        $.mask.definitions['f'] = "[A-Fa-f0-9]"; 	
        $("#telephone").mask('(999) 999-9999', {placeholder:'X'});
        $("#spouse_telephone").mask('(999) 999-9999', {placeholder:'X'});
        $("#partner_telephone").mask('(999) 999-9999', {placeholder:'X'});
        $("#mobile").mask('(999) 999-9999', {placeholder:'X'});
        $("#spouse_mobile").mask('(999) 999-9999', {placeholder:'X'});
        $("#partner_mobile").mask('(999) 999-9999', {placeholder:'X'});
        $("#date_of_birth").mask('99/99/9999', {placeholder:'_'});
        $("#spouse_date_of_birth").mask('99/99/9999', {placeholder:'_'});
        $("#partner_date_of_birth").mask('99/99/9999', {placeholder:'_'});
        $("#ssn").mask('9999', {placeholder:'_'});
        $("#spouse_ssn").mask('9999', {placeholder:'_'});
        $("#partner_ssn").mask('9999', {placeholder:'_'});
        $("#residence_from_date").mask('99/9999', {placeholder:'_'});
        $("#residence_to_date").mask('99/9999', {placeholder:'_'});
        $("#employment_from_date").mask('99/9999', {placeholder:'_'});
        $("#employment_to_date").mask('99/9999', {placeholder:'_'});
        $("#partner_from_date").mask('99/9999', {placeholder:'_'});
        $("#partner_to_date").mask('99/9999', {placeholder:'_'});
        $("#prior_spouse_end").mask('99/9999', {placeholder:'_'});
        $("#prior_spouse_end_both").mask('99/9999', {placeholder:'_'});
        $("#current_spouse_prior_spouse_end_both").mask('99/9999', {placeholder:'_'});
        $("#current_spouse_prior_spouse_end").mask('99/9999', {placeholder:'_'});

        $('.smartfm-ctrl').formShowHide({
            resetClass: 'smartform-reset'
        });

        /* Group Cloning
        ------------------------------------------------- */			
        $('#clone-group-fields').cloneya({
            maximum: 5
        }).on('after_append.cloneya', function (event, toclone, newclone) {
            $(newclone).find("em").remove();
            $(newclone).find("input").attr('aria-describedby', '');
            $("#employment_from_date1").mask('99/9999', {placeholder:'_'});
            $("#employment_from_date2").mask('99/9999', {placeholder:'_'});
            $("#employment_from_date3").mask('99/9999', {placeholder:'_'});
            $("#employment_from_date4").mask('99/9999', {placeholder:'_'});
            $("#employment_to_date1").mask('99/9999', {placeholder:'_'});
            $("#employment_to_date2").mask('99/9999', {placeholder:'_'});
            $("#employment_to_date3").mask('99/9999', {placeholder:'_'});
            $("#employment_to_date4").mask('99/9999', {placeholder:'_'});
		});

        $('#clone-group-fields2').cloneya({
            maximum: 5
        }).on('after_append.cloneya', function (event, toclone, newclone) {
            $(newclone).find("em").remove();
            $(newclone).find("input").attr('aria-describedby', '');
            $("#residence_from_date1").mask('99/9999', {placeholder:'_'});
            $("#residence_from_date2").mask('99/9999', {placeholder:'_'});
            $("#residence_from_date3").mask('99/9999', {placeholder:'_'});
            $("#residence_from_date4").mask('99/9999', {placeholder:'_'});
            $("#residence_to_date1").mask('99/9999', {placeholder:'_'});
            $("#residence_to_date2").mask('99/9999', {placeholder:'_'});
            $("#residence_to_date3").mask('99/9999', {placeholder:'_'});
            $("#residence_to_date4").mask('99/9999', {placeholder:'_'});
		});

        $('#clone-group-fields3').cloneya({
            maximum: 5
        }).on('after_append.cloneya', function (event, toclone, newclone) {
            $(newclone).find("em").remove();
            $(newclone).find("input").attr('aria-describedby', '');
            $("#partner_from_date1").mask('99/9999', {placeholder:'_'});
            $("#partner_from_date2").mask('99/9999', {placeholder:'_'});
            $("#partner_from_date3").mask('99/9999', {placeholder:'_'});
            $("#partner_from_date4").mask('99/9999', {placeholder:'_'});
            $("#partner_to_date1").mask('99/9999', {placeholder:'_'});
            $("#partner_to_date2").mask('99/9999', {placeholder:'_'});
            $("#partner_to_date3").mask('99/9999', {placeholder:'_'});
            $("#partner_to_date4").mask('99/9999', {placeholder:'_'});
        });	
    }); 
    
    
</script>
