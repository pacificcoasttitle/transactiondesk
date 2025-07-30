
<!DOCTYPE html>
<html>
<head>
	<title>Prosed Insured</title>
	<style>
	*{-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;}

	@page { sheet-size: A4; }
	@page bigger { sheet-size: 215.9mm 279.4mm; }
	@page toc { sheet-size: A4; }
	body{
		font-family:Roboto, 'Segoe UI', Tahoma, sans-serif; 
		font-size:12px; 
		color:#000000; 
		max-width:100%;
		-webkit-print-color-adjust:exact;
	}
	@page .pdf-wrapper{margin:auto; color:#000000;}

	.header-section, .title-officer-info {
		width:100%;
	}
	.header-section .logo, 
	.title-officer-info .title-officer-basic-info, 
	.customer-info .company-details, 
	.customer-info .order-number, 
	.customer-info .property-info {
		float:left;
		width:50%;
	}
	.title-officer-info .title-officer-contact-info {
		float: right;
	}
	.customer-info .order-number,
	.customer-info .property-info { 
		width: 100%; 
	}

    .text-center {
    	text-align: center;
    }

    .text-right {
    	text-align: right;
    }

    .header-section .company-details p, 
    .title-officer-basic-info p, 
    .title-officer-contact-info p, 
    .customer-info .company-details p,
    .customer-info .order-number p,
	.customer-info .property-info p,
	.content-info .basic-details p { 
    	margin-bottom: 0px;
    	margin-top: 0px; 
    }
    .customer-info .company-details p.heading-info,
    .customer-info .order-number p.heading-info,
	.customer-info .property-info p.heading-info {
    	margin-top: 20px !important;
    }
    .heading {
    	text-transform: uppercase;
    }
    .spacer-t30 {
	    margin-top: 30px;
	}

	.order-details {
		line-height: 2px;
	}
	</style>
</head>
<body>
	<div class="pdf-wrapper">
		<div class="header-section">
			<div class="logo">
				<img src="<?php echo base_url(); ?>assets/frontend/images/pi_logo.jpg" alt=""/>
			</div>
			<div class="company-details text-right">
				<?php if(!empty($proposed_branch_id)) { ?>
					<p><?php echo $branch_address;?></p>
					<p><?php echo $branch_city;?>, <?php echo $branch_state;?> <?php echo $branch_zip;?></p>
				<?php } else {?>
					<p>200 W. Glenoaks Blvd, Suite 100</p>
					<p>Glendale, CA 91202</p>
					<p>(818)662-6700</p>
				<?php } ?>
			</div>
			<h5 class="text-right"> <?php echo isset($underwriter) && !empty($underwriter) ? 'Issuing Agent for '.$underwriter : ''; ?></h5>			
		</div>
		<hr>
		<div class="title-officer-info">
			<div class="title-officer-basic-info">
				<p><span class="heading">Title Officer:</span> <?php echo isset($title_officer) && !empty($title_officer) ? $title_officer : ''; ?></p>
				<p><span class="heading">Title Officer Email:</span>  <?php echo isset($title_officer_email) && !empty($title_officer_email) ? $title_officer_email : ''; ?></p>
			</div>
			<div class="title-officer-contact-info text-right">
				<p><span class="heading">Title Officer Phone:</span> <?php echo isset($title_officer_phone) && !empty($title_officer_phone) ? $title_officer_phone : ''; ?></p>
				<!-- <p><span class="heading">Title Officer Fax:</span>  (818)484-2540</p> -->
			</div>
			<div style="clear: both;"></div>
			<div class= "customer-info">
				<div class="company-details">
					<?php
						if(isset($company) && !empty($company))
						{
					?>
							<p class="heading-info"><span class="heading">To:</span> <?php echo isset($company) && !empty($company) ? $company : ''; ?></p>
							<p><?php echo isset($address) && !empty($address) ? $address : ''; ?></p>
					<?php
						}
					?>
					
				</div>
				<div class="order-number">
					<p class="heading-info"><span class="heading">Order No.:</span> <?php echo isset($order_number) && !empty($order_number) ? $order_number : ''; ?></p>
				</div>
				<div class="property-info">
					<p class="heading-info"><span class="heading">Property Address:</span> <?php echo isset($property_address) && !empty($property_address) ? $property_address : ''; ?></p>
				</div>
			</div>
			<hr>
		</div>
		<div class="content-info">
			<div class="basic-details">
				<p class="text-center"><span class="heading">
					<?php
						$s_date = date('M d, Y');
						if(isset($supplemental_report_date) && !empty($supplemental_report_date))
						{
							$s_date = date('M d, Y', strtotime($supplemental_report_date));
						}
					?>
				Supplemental report dated as of: </span><?php echo $s_date; ?></p>
				<p class="text-center" style="display: none;">
					<?php
						$p_date = date('M d, Y');
						if(isset($preliminary_report_date) && !empty($preliminary_report_date))
						{
							$p_date = date('M d, Y', strtotime($preliminary_report_date));
						}
					?>
					<span class="heading">Original preliminary report dated: </span><?php echo $p_date; ?>
				</p>
			</div>
			<div class="note">
				<h2 class="heading text-center">Supplemental Report</h2>
				<p style="text-align: justify;">The above numbered report (including any Supplements or Amendments thereto) is hereby modified and/or supplemented in order to reflect the following additional items relating to the issuance of a Policy of Title Insurance as follows:</p>
				<p style="text-align: justify;">UPON THE CLOSE OF ESCROW AND CONFIRMATION OF RECORDING PACIFIC COAST TITLE WILL BE IN A POSITION TO ISSUE A TITLE POLICY IN FAVOR OF:</p>
			</div>
			<div class="order-details">
				<!-- <p>Borrower: <?php // echo isset($primary_owner) && !empty($primary_owner) ? $primary_owner : '-'; ?></p>
				<p>Secondary Borrower: <?php // echo isset($secondary_owner) && !empty($secondary_owner) ? $secondary_owner : '-'; ?></p> -->
				
				
				<p>Lender: <?php echo isset($lender['company_name']) && !empty($lender['company_name']) ? $lender['company_name'] : '-'; ?></p>

				<p><?php echo isset($lender['assignment_clause']) && !empty($lender['assignment_clause']) ? $lender['assignment_clause'] : '-'; ?></p>
				<p>Address: <?php echo isset($lender['address']) && !empty($lender['address']) ? $lender['address'] : '-'; ?></p>
				
				
			</div>
			<div class="order-details">
				<p style="line-height: 20px;">Borrower: <?php echo isset($vesting) && !empty($vesting) ? $vesting : '-'; ?></p>
				<p>Loan #: <?php echo isset($loan_number) && !empty($loan_number) ? $loan_number : '-'; ?></p>
				<?php
                	if(isset($loan_amount) && !empty($loan_amount))
                	{
                    	$loan_amount = str_replace(",", "", $loan_amount);
              	?>
                  <p>Loan Amount: $<?php echo number_format($loan_amount); ?></p>
                  	<!-- <p>Loan Amount: <?php // echo isset($loan_amount) && !empty($loan_amount) ? '$'.$loan_amount : ''; ?></p> -->
              	<?php
                	}
                ?>
				
			</div>
			<div class="spacer-t30"></div>
			
			
			<div style="line-height: 4px;"><p>Sincerely,</p></div>	
			<div class="order-details">
				<p><?php echo isset($title_officer) && !empty($title_officer) ? $title_officer : ''; ?></p>
				<p>Title Officer</p>
			</div>
		</div>
	</div>
</body>
</html>