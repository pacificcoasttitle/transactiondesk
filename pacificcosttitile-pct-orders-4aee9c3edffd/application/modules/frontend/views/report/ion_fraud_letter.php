<html>
<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,300;0,400;0,700;0,900;1,700&family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,600&display=swap" rel="stylesheet">
    <style>
        body{
            font-family: 'Montserrat', sans-serif;
            margin: 0;
            padding: 0;
        }
        *{
            box-sizing: border-box;
        }
        .size_a4 { width: 8.3in; height: 11.7in; }
        .size_letter { width: 8.5in; height: 11in; }
        .size_executive { width: 7.25in; height: 10.5in; }
        .pdf_page {
            margin: 0 auto;
            box-sizing: border-box;
            background-color: #fff;
            color: #333;
            position: relative;
        }
        .pdf_header {
            position: absolute;
            top: 0;
            height: .8in;
            left: 0;
            right: 0;
            background:url(img/GenericBG.jpg) no-repeat;
            height: 80px;
            background-size: cover;
        }
        .logo_container{
            float: left;
            padding: 25px 25px 25px 0px;
        }
        .logo_container img{
            width: 240px;
        }
        .header_address {
            float: right;
            text-align: right;
            color: #fff;
            font-size: 14px;
            padding: 23px 25px 14px 14px;
        }
        .pdf_footer {
            position: absolute;
            bottom: 0;
            height: .5in;
            left: 0;
            right: 0;
            padding-top: 10px;
            border-top: 4px solid #333;
            text-align: left;
            font-size: 16px;
            font-weight: 600;
        }
        .pdf_footer p{
            margin: 0;
        }
        .page_text{
            float: right;
        }
        .page_title{
            float: left;
        }
        .pdf_body {
            position: absolute;
            top: 1in;
            bottom: 1.2in;
            left: 0;
            right: 0;
        }
        .mb-10{
            margin-bottom: 15px;
        }
        .title_divider{
            height: 5px;
            width: 120px;
            background-color: #d35626;
            margin: 25px 0;
        }
        .f20{
            font-size: 18px;
            margin-bottom: 20px;
        }
        .body_text {
            font-family: 'Lato', sans-serif;
            line-height: 20px;
            font-size: 14px;
			padding-top:30px;
        }
        .sign_img{
            max-width: 280px;
        }
        .h150{
            height: 100px;
        }
        .red_text{
            color: #d3353e;
        }
		.para {
		padding-bottom:8px;
		}
		.para2 {
		padding-bottom:8px;
		font-weight:bold;
		}
		.para3 {
		padding-bottom:8px;
		}
    </style>
</head>
<body>
    <div class="page_container">
        <div class="pdf_page size_letter">
            <div class="pdf_header">
                <div class="logo_container">
                    <!-- <img src="https://gallery.mailchimp.com/3f123598483b787fa180fff0f/images/8cee9f1d-8c9f-4e75-b5b2-f1356f42fff8.png" alt=""> -->
                    <img src="<?php echo base_url('assets/frontend/images/pacific.png') ?>" alt="">
                </div>
                <div class="header_address">
                    Pacific Coast Title Company<br>
                    (866) 724-1050 || www.pct.com
                </div>
            </div>
            <div class="pdf_body">
                <div class="body_text">
                    <p class="para"><?php echo date('Y-m-d'); ?></p>

                    <p class="para2">Re: <?php echo $Mailaddress; ?></p>
                    <div class="mb-10"></div>
					<p class="para">Dear, <?php echo $Ownername; ?>,</p>
					<div class="mb-10"></div>
                   <div class="mb-10"></div>
					 <p class="para">
						We recently received a title order for the property located at
						<b><?php echo $Mailaddress; ?></b> The title order was
						opened under the name of John Doe, but upon running our PCT
						Protect search, our databases reflect that the registered owner
						of the property is Jane Smith.
					</p>

					<p>
						We are committed to ensuring the safety and security of property
						transactions. As part of our dedication to protecting homeowners
						from potential fraud, we have implemented an enhanced security
						measure known as PCT Protect. This system is designed to verify
						the identity of sellers involved in any real estate transaction.
					</p>

					<p class="para">
						When a title order is opened, PCT Protect uses data from a variety
						of trusted industry sources to verify that the name of the proposed
						seller matches the name listed in public and proprietary databases.
						This process allows us to ensure that the person initiating the sale
						has the legal authority to do so.
					</p>

					<p class="para">
						We found a discrepancy regarding your property located at
						<b><?php echo $Mailaddress; ?></b>, and we are requesting that you,
						Jane Smith, contact us directly to confirm whether you are, in fact,
						attempting to sell this property. If you are not currently selling your
						property, this could indicate an unauthorized attempt to sell or transfer
						ownership of your home, which requires immediate attention to ensure
						your security.
					</p>

					<p class="para">
						Please reach out to our team at [Contact Information] at your earliest
						convenience to verify the situation. We will not proceed with the
						transaction until we have confirmed the details with you directly.
					</p>

					<p class="para">
						Thank you for choosing Pacific Coast Title. We are proud to be your
						partner in protecting your property and ensuring a smooth, secure
						transaction.
					</p>


                    At Pacific Coast Title, your security is our priority. We are committed to upholding the highest standards of accuracy and diligence throughout the title process. Should you have any questions or require further information, please don’t hesitate to contact our team at <b>(866) 724-1050</b>.<div class="mb-10"></div>

                    Thank you for choosing Pacific Coast Title. We are proud to be your partner in protecting your property and ensuring a smooth, secure transaction.<div class="mb-10"></div>

                    Sincerely,<div class="mb-10"></div>

                    [Your Name]  <br>
                    [Your Title]  <br>
                    Pacific Coast Title Company  <br>
                    [Contact Information]
                </div>
            </div>
            <!-- <div class="pdf_footer">
                <p class="page_title">Listing Prelim Report</p>
                <p class="page_text">Welcome</p>
            </div> -->
        </div>
    </div>
</body>
