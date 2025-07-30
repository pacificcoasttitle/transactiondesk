<style>
.bootstrap-select:not([class*="col-"]):not([class*="form-control"]):not(.input-group-btn) {
    width: -webkit-fill-available;
}
#accordionEx .card-header a .fa-angle-down {
	display: none;
}
#accordionEx .card-header a.collapsed .fa-angle-up {
	display: none;
}
#accordionEx .card-header a.collapsed .fa-angle-down {
	display: inline-block;
}
.accordion > .card.managerInfoCard {
	overflow: initial;
}
.remove-btn-holder {
	position: absolute;
    right: -20px;
    top: -30px;
}
.remove-btn-holder .threshold-remove-btn {
    border-radius: 50%;
}
.accordion .commission-details .card{
	border-bottom: 1px solid rgba(0,0,0,.125) !important;
	border-bottom-left-radius: 0.25rem !important;
	border-bottom-right-radius: 0.25rem !important;
	margin-bottom:25px;
}

.threshold__amounts .clone-main-div .remove-btn-holder {
	display: none;
}
.commission-details .nav-pills .nav-link.active {
   position: relative;
}

.commission-details .nav-pills .nav-link.active:before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    border-top: 23px solid #fff;
    border-bottom: 23px solid #fff;
    border-left: 29px solid transparent;
}
</style>
<div class="content">
<?php if(!empty($success_msg)){ ?>
    <div class="col-xs-12">
        <div class="alert alert-success"><?php echo $success_msg; ?></div>
    </div>
<?php } ?>
<?php if(!empty($error_msg)){ ?>
    <div class="col-xs-12">
        <div class="alert alert-danger"><?php echo $error_msg; ?></div>
    </div>
<?php } ?>
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-sm-6">
                <h1 class="h3 text-gray-800">Order Details</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Edit Order Details</h6>
                    </div>
                    <div class="card-body"> 
                        <div class="accordion md-accordion" id="accordionEx">
                            <div class="card mx-auto mt-5 mb-5" style="border-bottom: 1px solid rgba(0, 0, 0, 0.125);">
                                <div class="card-header" role="tab" id="orderDetailsTab">
                                    <a data-toggle="collapse" style="color: #000000;" data-parent="#accordionEx" href="#orderDetails" aria-expanded="true"
                                    aria-controls="orderDetails">
                                        <h5 class="mb-0 text-primary">
                                        Order Details <i class="fas fa-angle-down pull-right"></i><i class="fas fa-angle-up pull-right"></i>
                                        </h5>
                                    </a>
                                </div>
                                <div id="orderDetails" class="collapse show" role="tabpanel" aria-labelledby="orderDetailsTab" data-parent="#accordionEx">
                                    <div class="card-body">        
                                        <?php
                                            if(isset($order_details['file_number']) && !empty($order_details['file_number']))
                                            {
                                        ?>
                                                <div class="form-group row">
                                                    <label for="name" class="col-sm-3 col-form-label">Order Number:</label>
                                                    <div class="col-sm-9 col-form-label">
                                                        <?php echo $order_details['file_number']; ?>
                                                    </div>
                                                </div>
                                        <?php
                                            } else if(isset($order_details['lp_file_number']) && !empty($order_details['lp_file_number'])) {
                                        ?>
                                                <div class="form-group row">
                                                    <label for="name" class="col-sm-3 col-form-label">Order Number:</label>
                                                    <div class="col-sm-9 col-form-label">
                                                        <?php echo $order_details['lp_file_number']; ?>
                                                    </div>
                                                </div>
                                        <?php
                                            } 
                                        ?>
                                        <?php
                                            if(isset($order_details['file_id']) && !empty($order_details['file_id']))
                                            {
                                        ?>
                                                <div class="form-group row">
                                                    <label for="name" class="col-sm-3 col-form-label">File ID:</label>
                                                    <div class="col-sm-9 col-form-label">
                                                        <?php echo $order_details['file_id']; ?>
                                                    </div>
                                                </div>
                                        <?php
                                            }
                                        ?>
                                        <?php
                                            if(isset($order_details['opened_date']) && !empty($order_details['opened_date']))
                                            {
                                        ?>
                                                <div class="form-group row">
                                                    <label for="name" class="col-sm-3 col-form-label">Order Open At:</label>
                                                    <div class="col-sm-9 col-form-label">
                                                        <?php echo date("m/d/Y h:i:s A", strtotime($order_details['opened_date']));
                                                        ?>
                                                    </div>
                                                </div>
                                        <?php
                                            }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="card mx-auto mt-5 mb-5" style="border-bottom: 1px solid rgba(0, 0, 0, 0.125);">
                                <div class="card-header" id="customerDetailsTab">
                                    <a data-toggle="collapse" style="color: #000000;" data-parent="#accordionEx" href="#customerDetails" aria-expanded="false"
                                    aria-controls="customerDetails">
                                        <h5 class="mb-0 text-primary">
                                        Customer Details <i class="fas fa-angle-down pull-right"></i><i class="fas fa-angle-up pull-right"></i>
                                        </h5>
                                    </a>
                                </div>
                                <div id="customerDetails" class="collapse" role="tabpanel" aria-labelledby="customerDetailsTab" data-parent="#accordionEx">
                                    <div class="card-body">
                                    <?php
                                        if(isset($customer_details['company_name']) && !empty($customer_details['company_name']))
                                        {
                                    ?>
                                                <div class="form-group row">
                                                    <label for="name" class="col-sm-3 col-form-label">Company Name:</label>
                                                    <div class="col-sm-9 col-form-label">
                                                    <?php echo $customer_details['company_name'];
                                                    ?>
                                                    </div>
                                                </div>
                                    <?php
                                        }
                                    ?>
                                    <?php
                                        if(isset($customer_details['email_address']) && !empty($customer_details['email_address']))
                                        {
                                    ?>
                                            <div class="form-group row">
                                                <label for="name" class="col-sm-3 col-form-label">Email Address:</label>
                                                <div class="col-sm-9 col-form-label">
                                                <?php echo $customer_details['email_address']; ?>
                                                </div>
                                            </div>
                                    <?php
                                        }
                                    ?>
                                    <?php
                                        if(isset($customer_details['first_name']) && !empty($customer_details['first_name']))
                                        {
                                    ?>
                                            <div class="form-group row">
                                                <label for="name" class="col-sm-3 col-form-label">First Name:</label>
                                                <div class="col-sm-9 col-form-label">
                                                <?php echo $customer_details['first_name']; ?>
                                                </div>
                                            </div>
                                    <?php
                                        }
                                    ?>
                                        
                                    <?php
                                        if(isset($customer_details['last_name']) && !empty($customer_details['last_name']))
                                        {
                                    ?>
                                            <div class="form-group row">
                                                <label for="name" class="col-sm-3 col-form-label">Last Name:</label>
                                                <div class="col-sm-9 col-form-label">
                                                <?php echo $customer_details['last_name']; ?>
                                                </div>
                                            </div>
                                    <?php
                                        }
                                    ?>    
                                        
                                    <?php
                                        if(isset($customer_details['telephone_no']) && !empty($customer_details['telephone_no']))
                                        {
                                    ?>
                                            <div class="form-group row">
                                                <label for="name" class="col-sm-3 col-form-label">Telephone:</label>
                                                <div class="col-sm-9 col-form-label">
                                                <?php echo $customer_details['telephone_no']; ?>
                                                </div>
                                            </div>
                                    <?php
                                        }
                                    ?>
                                        
                                    <?php
                                        if(isset($customer_details['street_address']) && !empty($customer_details['street_address']))
                                        {
                                    ?>
                                            <div class="form-group row">
                                                <label for="name" class="col-sm-3 col-form-label">Street Address:</label>
                                                <div class="col-sm-9 col-form-label">
                                                <?php echo $customer_details['street_address']; ?>
                                                </div>
                                            </div>
                                    <?php
                                        }
                                    ?>
                                        
                                    <?php
                                        if(isset($customer_details['city']) && !empty($customer_details['city']))
                                        {
                                    ?>
                                            <div class="form-group row">
                                                <label for="name" class="col-sm-3 col-form-label">City:</label>
                                                <div class="col-sm-9 col-form-label">
                                                <?php echo $customer_details['city']; ?>
                                                </div>
                                            </div>
                                    <?php
                                        }
                                    ?>
                                        
                                    <?php
                                        if(isset($customer_details['zip_code']) && !empty($customer_details['zip_code']))
                                        {
                                    ?>
                                            <div class="form-group row">
                                                <label for="name" class="col-sm-3 col-form-label">Zipcode:</label>
                                                <div class="col-sm-9 col-form-label">
                                                <?php echo $customer_details['zip_code']; ?>
                                                </div>
                                            </div>
                                    <?php
                                        }
                                    ?>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="card mx-auto mt-5 mb-5" style="border-bottom: 1px solid rgba(0, 0, 0, 0.125);">
                                <div class="card-header" role="tab" id="propertyDetailsTab">
                                    <a data-toggle="collapse" class="collapsed" style="color: #000000;" data-parent="#accordionEx" href="#propertyDetails" aria-expanded="false"
                                    aria-controls="propertyDetails">
                                        <h5 class="mb-0 text-primary">
                                            Property Details 
                                            <i class="fas fa-angle-down pull-right"></i><i class="fas fa-angle-up pull-right"></i>
                                        </h5>
                                    </a>
                                </div>
                                <div id="propertyDetails" class="collapse" role="tabpanel" aria-labelledby="propertyDetailsTab" data-parent="#accordionEx">
                                    <div class="card-body">        
                                        <?php
                                            if(isset($order_details['full_address']) && !empty($order_details['full_address']))
                                            {
                                        ?>
                                                <div class="form-group row">
                                                    <label for="name" class="col-sm-3 col-form-label">Property Address:</label>
                                                    <div class="col-sm-9 col-form-label">
                                                        <?php echo $order_details['full_address']; ?>
                                                    </div>
                                                </div>
                                        <?php
                                            }
                                        ?>
                                        
                                        <?php
                                            if(isset($order_details['apn']) && !empty($order_details['apn']))
                                            {
                                        ?>
                                                <div class="form-group row">
                                                    <label for="name" class="col-sm-3 col-form-label">APN:</label>
                                                    <div class="col-sm-9 col-form-label">
                                                        <?php echo $order_details['apn']; ?>
                                                    </div>
                                                </div>
                                        <?php
                                            }
                                        ?>
                                        
                                        <?php
                                            if(isset($order_details['county']) && !empty($order_details['county']))
                                            {
                                        ?>
                                                <div class="form-group row">
                                                    <label for="name" class="col-sm-3 col-form-label">County:</label>
                                                    <div class="col-sm-9 col-form-label">
                                                    <?php echo $order_details['county']; ?>
                                                    </div>
                                                </div>
                                        <?php
                                            }
                                        ?>
                                        
                                        <?php
                                            if(isset($order_details['legal_description']) && !empty($order_details['legal_description']))
                                            {
                                        ?>
                                                <div class="form-group row">
                                                    <label for="name" class="col-sm-3 col-form-label">Brief Legal Description:</label>
                                                    <div class="col-sm-9 col-form-label">
                                                    <?php echo $order_details['legal_description']; ?>
                                                    </div>
                                                </div>
                                        <?php
                                            }
                                        ?>
                                        
                                        <?php
                                            if(isset($order_details['primary_owner']) && !empty($order_details['primary_owner']))
                                            {
                                        ?>
                                                <div class="form-group row">
                                                    <label for="name" class="col-sm-3 col-form-label">Primary Owner:</label>
                                                    <div class="col-sm-9 col-form-label">
                                                    <?php echo $order_details['primary_owner']; ?>
                                                    </div>
                                                </div>
                                        <?php
                                            }
                                        ?>
                                        
                                        <?php
                                            if(isset($order_details['secondary_owner']) && !empty($order_details['secondary_owner']))
                                            {
                                        ?>
                                                <div class="form-group row">
                                                    <label for="name" class="col-sm-3 col-form-label">Secondary Owner:</label>
                                                    <div class="col-sm-9 col-form-label">
                                                    <?php echo $order_details['secondary_owner']; ?>
                                                    </div>
                                                </div>      
                                        <?php
                                            }
                                        ?>
                                        
                                        <?php
                                            if(isset($order_details['borrower']) && !empty($order_details['borrower']))
                                            {
                                        ?>
                                                <div class="form-group row">
                                                    <label for="name" class="col-sm-3 col-form-label">Primary Borrower:</label>
                                                    <div class="col-sm-9 col-form-label">
                                                    <?php echo $order_details['borrower']; ?>
                                                    </div>
                                                </div>       
                                        <?php
                                            }
                                        ?>
                                        
                                        <?php
                                            if(isset($order_details['secondary_borrower']) && !empty($order_details['secondary_borrower']))
                                            {   
                                        ?>
                                                <div class="form-group row">
                                                    <label for="name" class="col-sm-3 col-form-label">Secondary Borrower:</label>
                                                    <div class="col-sm-9 col-form-label">
                                                    <?php echo $order_details['secondary_borrower']; ?>
                                                    </div>
                                                </div>
                                        <?php
                                            }
                                        ?>
                                        
                                    </div>
                                </div>
                            </div>

                            <div class="card mx-auto mt-5 mb-5" style="border-bottom: 1px solid rgba(0, 0, 0, 0.125);">
                                <div class="card-header" role="tab" id="transactionDetailsTab">
                                    <a data-toggle="collapse" class="collapsed" style="color: #000000;" data-parent="#accordionEx" href="#transactionDetails" aria-expanded="false"
                                    aria-controls="transactionDetails">
                                        <h5 class="mb-0 text-primary">
                                        Transaction Details <i class="fas fa-angle-down pull-right"></i><i class="fas fa-angle-up pull-right"></i>
                                        </h5>
                                    </a>
                                </div>
                                <div id="transactionDetails" class="collapse" role="tabpanel" aria-labelledby="transactionDetailsTab" data-parent="#accordionEx">
                                    <div class="card-body">        
                                        <?php
                                            if(isset($order_details['sales_rep_name']) && !empty($order_details['sales_rep_name']))
                                            {
                                        ?>
                                                <div class="form-group row">
                                                    <label for="name" class="col-sm-3 col-form-label">Sales Rep:</label>
                                                    <div class="col-sm-9 col-form-label">
                                                        <?php echo $order_details['sales_rep_name']; ?>
                                                    </div>
                                                </div>
                                        <?php
                                            }
                                        ?>
                                        
                                        <?php
                                            if(isset($order_details['title_officer_name']) && !empty($order_details['title_officer_name']))
                                            {
                                        ?>
                                                <div class="form-group row">
                                                    <label for="name" class="col-sm-3 col-form-label">Title Officer:</label>
                                                    <div class="col-sm-9 col-form-label">
                                                        <?php echo $order_details['title_officer_name']; ?>
                                                    </div>
                                                </div>
                                        <?php
                                            }
                                        ?>
                                        
                                        <?php
                                            if(isset($order_details['product_type']) && !empty($order_details['product_type']))
                                            {
                                        ?>
                                                <div class="form-group row">
                                                    <label for="name" class="col-sm-3 col-form-label">Product:</label>
                                                    <div class="col-sm-9 col-form-label">
                                                    <?php echo $order_details['product_type']; ?>
                                                    </div>
                                                </div>
                                        <?php
                                            }
                                        ?>
                                        <?php
                                            if(isset($order_details['loan_amount']) && !empty($order_details['loan_amount']))
                                            {
                                        ?>
                                                <div class="form-group row">
                                                    <label for="name" class="col-sm-3 col-form-label">Loan Amount:</label>
                                                    <div class="col-sm-9 col-form-label">
                                                    <?php echo $order_details['loan_amount']; ?>
                                                    </div>
                                                </div>
                                        <?php
                                            }
                                        ?><?php
                                            if(isset($order_details['sales_amount']) && !empty($order_details['sales_amount']))
                                            {
                                        ?>
                                                <div class="form-group row">
                                                    <label for="name" class="col-sm-3 col-form-label">Sales Amount:</label>
                                                    <div class="col-sm-9 col-form-label">
                                                    <?php echo $order_details['sales_amount']; ?>
                                                    </div>
                                                </div>
                                        <?php
                                            }
                                        ?>
                                        
                                        <?php
                                            if(isset($order_details['loan_number']) && !empty($order_details['loan_number']))
                                            {
                                        ?>
                                                <div class="form-group row">
                                                    <label for="name" class="col-sm-3 col-form-label">Loan Number:</label>
                                                    <div class="col-sm-9 col-form-label">
                                                    <?php echo $order_details['loan_number']; ?>
                                                    </div>
                                                </div>
                                        <?php
                                            }
                                        ?>

                                        <?php
                                            if(isset($order_details['escrow_number']) && !empty($order_details['escrow_number']))
                                            {
                                        ?>
                                                <div class="form-group row">
                                                    <label for="name" class="col-sm-3 col-form-label">Escrow Number:</label>
                                                    <div class="col-sm-9 col-form-label">
                                                    <?php echo $order_details['escrow_number']; ?>
                                                    </div>
                                                </div>
                                        <?php
                                            }
                                        ?>
                                        
                                        <?php
                                            if(isset($order_details['notes']) && !empty($order_details['notes']))
                                            {
                                        ?>
                                                <div class="form-group row">
                                                    <label for="name" class="col-sm-3 col-form-label">Additional Details:</label>
                                                    <div class="col-sm-9 col-form-label">
                                                    <?php echo $order_details['notes']; ?>
                                                    </div>
                                                </div>
                                        <?php
                                            }
                                        ?>
                                        
                                        
                                    </div>
                                </div>
                            </div> 
                            <?php
                                if(isset($order_details['additional_emails']) && !empty($order_details['additional_emails']))
                                {
                            ?>
                                <div class="card mx-auto mt-5 mb-5" style="border-bottom: 1px solid rgba(0, 0, 0, 0.125);">
                                    <div class="card-header" role="tab" id="deliverablesDetailsTab">
                                        <a data-toggle="collapse" class="collapsed" style="color: #000000;" data-parent="#accordionEx" href="#deliverablesDetails" aria-expanded="false"
                                        aria-controls="deliverablesDetails">
                                            <h5 class="mb-0 text-primary">
                                            Deliverables Details <i class="fas fa-angle-down pull-right"></i><i class="fas fa-angle-up pull-right"></i>
                                            </h5>
                                        </a>
                                    </div>
                                
                                    <div id="deliverablesDetails" class="collapse" role="tabpanel" aria-labelledby="deliverablesDetailsTab" data-parent="#accordionEx">
                                        <div class="card-body">
                                            <div class="form-group row">
                                                <label for="name" class="col-sm-3 col-form-label">Email Address:</label>
                                                <div class="col-sm-9 col-form-label">
                                                    <?php echo $order_details['additional_emails']; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                    }
                                ?>
                            
                            <?php 
                                if(isset($order_details['buyer_agent_id']) && !empty($order_details['buyer_agent_id']))
                                {
                            ?>
                                <div class="card mx-auto mt-5 mb-5" style="border-bottom: 1px solid rgba(0, 0, 0, 0.125);">
                                    <div class="card-header" role="tab" id="buyerAgentDetailsTab">
                                        <a data-toggle="collapse" class="collapsed" style="color: #000000;" data-parent="#accordionEx" href="#buyerAgentDetails" aria-expanded="false"
                                        aria-controls="buyerAgentDetails">
                                            <h5 class="mb-0 text-primary">
                                            Buyer Agent Details <i class="fas fa-angle-down pull-right"></i><i class="fas fa-angle-up pull-right"></i>
                                            </h5>
                                        </a>
                                    </div>
                                
                                    <div id="buyerAgentDetails" class="collapse" role="tabpanel" aria-labelledby="buyerAgentDetailsTab" data-parent="#accordionEx">
                                        <div class="card-body">        
                                            <?php
                                                if(isset($order_details['buyer_agent_name']) && !empty($order_details['buyer_agent_name']))
                                                {
                                            ?>
                                                    <div class="form-group row">
                                                        <label for="name" class="col-sm-3 col-form-label">Buyer Agent Name:</label>
                                                        <div class="col-sm-9 col-form-label">
                                                            <?php echo $order_details['buyer_agent_name']; ?>
                                                        </div>
                                                    </div>
                                            <?php
                                                }
                                            ?>
                                            <?php
                                                if(isset($order_details['buyer_agent_email_address']) && !empty($order_details['buyer_agent_email_address']))
                                                {
                                            ?>
                                                    <div class="form-group row">
                                                        <label for="name" class="col-sm-3 col-form-label">Buyer Agent Email Address:</label>
                                                        <div class="col-sm-9 col-form-label">
                                                            <?php echo $order_details['buyer_agent_email_address']; ?>
                                                        </div>
                                                    </div>
                                            <?php
                                                }
                                            ?>
                                            <?php
                                                if(isset($order_details['buyer_agent_company']) && !empty($order_details['buyer_agent_company']))
                                                {
                                            ?>
                                                    <div class="form-group row">
                                                        <label for="name" class="col-sm-3 col-form-label">Buyer Agent Company:</label>
                                                        <div class="col-sm-9 col-form-label">
                                                            <?php echo $order_details['buyer_agent_company']; ?>
                                                        </div>
                                                    </div>
                                            <?php
                                                }
                                            ?>
                                            <?php
                                                if(isset($order_details['buyer_agent_telephone_no']) && !empty($order_details['buyer_agent_telephone_no']))
                                                {
                                            ?>
                                                    <div class="form-group row">
                                                        <label for="name" class="col-sm-3 col-form-label">Buyer Agent Telephone:</label>
                                                        <div class="col-sm-9 col-form-label">
                                                            <?php echo $order_details['buyer_agent_telephone_no']; ?>
                                                        </div>
                                                    </div>
                                            <?php
                                                }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            <?php
                                }
                            ?>
                            <?php 
                                if(isset($order_details['listing_agent_id']) && !empty($order_details['listing_agent_id']))
                                {
                            ?>
                                <div class="card mx-auto mt-5 mb-5" style="border-bottom: 1px solid rgba(0, 0, 0, 0.125);">
                                    <div class="card-header" role="tab" id="listingAgentDetailsTab">
                                        <a data-toggle="collapse" class="collapsed" style="color: #000000;" data-parent="#accordionEx" href="#listingAgentDetails" aria-expanded="false"
                                        aria-controls="listingAgentDetails">
                                            <h5 class="mb-0 text-primary">
                                            Listing Agent Details <i class="fas fa-angle-down pull-right"></i><i class="fas fa-angle-up pull-right"></i>
                                            </h5>
                                        </a>
                                    </div>
                                
                                    <div id="listingAgentDetails" class="collapse" role="tabpanel" aria-labelledby="listingAgentDetailsTab" data-parent="#accordionEx">
                                        <div class="card-body">        
                                            <?php
                                                if(isset($order_details['listing_agent_name']) && !empty($order_details['listing_agent_name']))
                                                {
                                            ?>
                                                    <div class="form-group row">
                                                        <label for="name" class="col-sm-3 col-form-label">Listing Agent Name:</label>
                                                        <div class="col-sm-9 col-form-label">
                                                            <?php echo $order_details['listing_agent_name']; ?>
                                                        </div>
                                                    </div>
                                            <?php
                                                }
                                            ?>
                                            <?php
                                                if(isset($order_details['listing_agent_email_address']) && !empty($order_details['listing_agent_email_address']))
                                                {
                                            ?>
                                                    <div class="form-group row">
                                                        <label for="name" class="col-sm-3 col-form-label">Listing Agent Email Address:</label>
                                                        <div class="col-sm-9 col-form-label">
                                                            <?php echo $order_details['listing_agent_email_address']; ?>
                                                        </div>
                                                    </div>
                                            <?php
                                                }
                                            ?>
                                            <?php
                                                if(isset($order_details['listing_agent_company']) && !empty($order_details['listing_agent_company']))
                                                {
                                            ?>
                                                    <div class="form-group row">
                                                        <label for="name" class="col-sm-3 col-form-label">Listing Agent Company:</label>
                                                        <div class="col-sm-9 col-form-label">
                                                            <?php echo $order_details['listing_agent_company']; ?>
                                                        </div>
                                                    </div>
                                            <?php
                                                }
                                            ?>
                                            <?php
                                                if(isset($order_details['listing_agent_telephone_no']) && !empty($order_details['listing_agent_telephone_no']))
                                                {
                                            ?>
                                                    <div class="form-group row">
                                                        <label for="name" class="col-sm-3 col-form-label">Listing Agent Telephone:</label>
                                                        <div class="col-sm-9 col-form-label">
                                                            <?php echo $order_details['listing_agent_telephone_no']; ?>
                                                        </div>
                                                    </div>
                                            <?php
                                                }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            <?php
                                }
                            ?> 
                            <?php 
                                if(isset($order_details['escrow_lender_id']) && !empty($order_details['escrow_lender_id']))
                                {
                            ?>
                                <div class="card mx-auto mt-5 mb-5" style="border-bottom: 1px solid rgba(0, 0, 0, 0.125);">
                                    <div class="card-header" role="tab" id="lenderDetailsTab">
                                        <a data-toggle="collapse" class="collapsed" style="color: #000000;" data-parent="#accordionEx" href="#lenderDetails" aria-expanded="false"
                                        aria-controls="lenderDetails">
                                            <h5 class="mb-0 text-primary">
                                            Lender/Escrow Details <i class="fas fa-angle-down pull-right"></i><i class="fas fa-angle-up pull-right"></i>
                                            </h5>
                                        </a>
                                    </div>
                                
                                    <div id="lenderDetails" class="collapse" role="tabpanel" aria-labelledby="lenderDetailsTab" data-parent="#accordionEx">
                                        <div class="card-body">        
                                            <?php
                                                if(isset($order_details['escrow_lender_first_name']) && !empty($order_details['escrow_lender_first_name']))
                                                {
                                            ?>
                                                    <div class="form-group row">
                                                        <label for="name" class="col-sm-3 col-form-label">First Name:</label>
                                                        <div class="col-sm-9 col-form-label">
                                                            <?php echo $order_details['escrow_lender_first_name']; ?>
                                                        </div>
                                                    </div>
                                            <?php
                                                }
                                            ?>
                                            <?php
                                                if(isset($order_details['escrow_lender_last_name']) && !empty($order_details['escrow_lender_last_name']))
                                                {
                                            ?>
                                                    <div class="form-group row">
                                                        <label for="name" class="col-sm-3 col-form-label">Last Name:</label>
                                                        <div class="col-sm-9 col-form-label">
                                                            <?php echo $order_details['escrow_lender_last_name']; ?>
                                                        </div>
                                                    </div>
                                            <?php
                                                }
                                            ?>
                                            <?php
                                                if(isset($order_details['escrow_lender_email']) && !empty($order_details['escrow_lender_email']))
                                                {
                                            ?>
                                                    <div class="form-group row">
                                                        <label for="name" class="col-sm-3 col-form-label">Email Address:</label>
                                                        <div class="col-sm-9 col-form-label">
                                                            <?php echo $order_details['escrow_lender_email']; ?>
                                                        </div>
                                                    </div>
                                            <?php
                                                }
                                            ?>
                                            <?php
                                                if(isset($order_details['escrow_lender_company_name']) && !empty($order_details['escrow_lender_company_name']))
                                                {
                                            ?>
                                                    <div class="form-group row">
                                                        <label for="name" class="col-sm-3 col-form-label">Company:</label>
                                                        <div class="col-sm-9 col-form-label">
                                                            <?php echo $order_details['escrow_lender_company_name']; ?>
                                                        </div>
                                                    </div>
                                            <?php
                                                }
                                            ?>
                                            <?php
                                                if(isset($order_details['escrow_lender_telephone_no']) && !empty($order_details['escrow_lender_telephone_no']))
                                                {
                                            ?>
                                                    <div class="form-group row">
                                                        <label for="name" class="col-sm-3 col-form-label">Telephone:</label>
                                                        <div class="col-sm-9 col-form-label">
                                                            <?php echo $order_details['escrow_lender_telephone_no']; ?>
                                                        </div>
                                                    </div>
                                            <?php
                                                }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            <?php
                                }
                            ?>   
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style type="text/css">
    #accordionEx .fas.fa-angle-down.rotate-icon {
        float: right;
    }
</style>