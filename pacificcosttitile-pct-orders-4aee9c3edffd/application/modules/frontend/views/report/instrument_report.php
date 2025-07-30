<html>
<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="<?php echo base_url(); ?>assets/frontend/fonts/fontawesome/fonts.css"  rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,300;0,400;0,700;0,900;1,700&family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,600&display=swap" rel="stylesheet">
    <style>
        .hide {
            display: none;
        }
        body{
            font-family: 'Montserrat', sans-serif;
            margin: 0;
            padding: 0;
        }
        *{
            box-sizing: border-box;
        }
        .size_a4 { width: 8.3in; height: 11.7in; }
        .size_letter { width: 11.5in; height: 18in; }
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
        .main_title{
            font-size: 40px;
        }
        .title_divider{
            height: 5px;
            width: 120px;
            background-color: #d35626;
            margin: 25px 0;
        }
        .f26{
            font-size: 24px;
            margin-bottom: 30px;
        }
        .body_text {
            font-family: 'Lato', sans-serif;
            line-height: 28px;
            font-size: 17px;
        }
        .sign_img{
            max-width: 280px;
        }
        .h150{
            height: 50px;
        }
        .red_text{
            color: #d3353e;
        }
    </style>
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
        .size_letter { width: 11.5in; height: 18in; }
        .size_executive { width: 7.25in; height: 10.5in; }
        .pdf_page {
            margin: 0 auto;
            box-sizing: border-box;
            background-color: #fff;
            color: #333;
            position: relative;
            font-family: 'Lato', sans-serif;
        }
        .pdf_header {
            position: absolute;
            top: 0;
            height: .8in;
            left: 0;
            right: 0;
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
        .logo_container{
            float: left;
        }
        .logo_container img{
            width: 240px;
        }
        .header_address {
            float: right;
            text-align: right; 
            color: #121212;
        }
        .main_title{
            font-size: 40px;
        }
        .title_divider{
            height: 5px;
            width: 120px;
            background-color: #d35626;
            margin: 25px 0;
        }
        .body_text {
            line-height: 28px;
            font-size: 17px;
            color: #121212;
        }
        .red_text{
            color: #d3353e;
        }
        .listing_report{
            text-align: center;
            border-bottom: 2px solid;
            padding-bottom: 8px;
            margin-bottom: 8px;
        }
        .report_info {
            font-size: 16px;
            font-weight: 700;
            line-height: 20px;
        }
        .billing_info{
            margin: 20px 0;
        }
        .float_left{
            float: left;
        }
        .float_right{
            float: right;
        }
        .text_right{
            text-align: right;
        }
        .text_left{
            text-align: left;
        }
        .clearfix{
            clear: both;
        }
        .mt-30{
            margin-top: 30px;
        }
        .mb-80{
            margin-bottom: 80px;
        }
        .py-10{
            padding-top: 10px;
            padding-bottom: 10px;
        }
        .table_title {
            font-weight: 900;
            background-color: #f2f3f4;
            border: 1px solid #e8e9ea;
            padding: 5px 8px;
            font-size: 18px;
            margin-top: 40px;
        }
        .table{
            border: 1px solid #e8e9ea;
            border-top:0;
            width: 100%;
            border-collapse: collapse;
        }
        .table td{
            padding: 5px 8px;
        }
        .table td:not(:last-child){
            border-right: 1px solid #c2d1d7;
        }
        .table tr:not(:last-child) td{
            border-bottom: 1px solid #c2d1d7;
        }
        .table_a td:first-child {
            width: 20%;
        }
        .table_b td:nth-of-type(odd){
            width: 20%;
        }
        .table_b td:nth-of-type(even){
            width: 30%;
        }
    </style>
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
        .size_letter { width: 11.5in; height: 18in; }
        .size_executive { width: 7.25in; height: 10.5in; }
        .pdf_page {
            margin: 0 auto;
            box-sizing: border-box;
            background-color: #fff;
            color: #333;
            position: relative;
            font-family: 'Lato', sans-serif;
        }
        .pdf_header {
            position: absolute;
            top: 0;
            height: .8in;
            left: 0;
            right: 0;
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
        .logo_container{
            float: left;
        }
        .logo_container img{
            width: 240px;
        }
        .header_address {
            float: right;
            text-align: right; 
            color: #121212;
        }
        .main_title{
            font-size: 40px;
        }
        .title_divider{
            height: 5px;
            width: 120px;
            background-color: #d35626;
            margin: 25px 0;
        }
        .body_text {
            line-height: 28px;
            font-size: 17px;
            color: #121212;
        }
        .red_text{
            color: #d3353e;
        }
        .listing_report{
            text-align: center;
            border-bottom: 2px solid;
            padding-bottom: 8px;
            margin-bottom: 8px;
            font-size: 21px;
            line-height: 30px;
        }
        .report_info {
            font-size: 21px;
            font-weight: 700;
            line-height: 30px;
        }
        .billing_info{
            margin: 20px 0;
        }
        .float_left{
            float: left;
        }
        .float_right{
            float: right;
        }
        .text_right{
            text-align: right;
        }
        .text_left{
            text-align: left;
        }
        .text_center{
            text-align: center;
        }
        .clearfix{
            clear: both;
        }
        .mt-30{
            margin-top: 30px;
        }
        .mb-80{
            margin-bottom: 80px;
        }
        .py-10{
            padding-top: 10px;
            padding-bottom: 10px;
        }
        .f900{
            font-weight: 900;
        }
        .table_title {
            font-weight: 900;
            background-color: #f2f3f4;
            border: 1px solid #e8e9ea;
            padding: 5px 8px;
            font-size: 18px;
            margin-top: 30px;
        }
        .table{
            border: 1px solid #e8e9ea;
            border-top:0;
            width: 100%;
            border-collapse: collapse;
        }
        .table td{
            padding: 5px 8px;
        }
        .table td:not(:last-child){
            border-right: 1px solid #c2d1d7;
        }
        .table tr:not(:last-child) td{
            border-bottom: 1px solid #c2d1d7;
        }
        .table_a td:first-child {
            width: 20%;
        }
        .table_b td:nth-of-type(odd){
            width: 20%;
        }
        .table_b td:nth-of-type(even){
            width: 30%;
        }
        .legal_desc{
            padding: 5px 8px;
            line-height: 24px;
        }
    </style>
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
            font-family: 'Lato', sans-serif;
        }
        .pdf_header {
            position: absolute;
            top: 0;
            height: .8in;
            left: 0;
            right: 0;
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
            width: 170px;
        }
        .pdf_body {
            position: absolute;
            top: 1in;
            bottom: 1.2in;
            left: 0;
            right: 0;
        }
        .logo_container{
            float: left;
        }
        .logo_container img{
            width: 320px;
        }
        .header_address {
            float: right;
            text-align: right; 
            color: #121212;
            font-size: 16px;
            line-height: 19px
        }
        .main_title{
            font-size: 36px;
        }
        .title_divider{
            height: 5px;
            width: 120px;
            background-color: #d35626;
            margin: 25px 0;
        }
        .body_text {
            line-height: 30px;
            font-size: 16px;
            color: #121212;
        }
        .red_text{
            color: #d3353e;
        }
        .listing_report{
            text-align: center;
            border-bottom: 2px solid;
            padding-bottom: 8px;
            margin-bottom: 8px;
            font-size: 16px;
            line-height: 19px;
        }
        .listing_report-alt {
            text-align: center;
            padding-bottom: 8px;
            margin-bottom: 0px;
        }
        .listing_report-alt-1 {
            text-align: center;
            border-bottom: 2px solid;
            padding-bottom: 50px;
            margin-bottom: 8px;
        }
        .report_info {
            font-size: 16px;
            font-weight: 700;
            line-height: 20px;
        }
        .billing_info{
            margin: 20px 0;
            font-size: 16px;
            line-height: 20px;
        }

        .billing_info-m-40{
            margin: 40px 0;
        }
        .float_left{
            float: left;
        }
        .float_right{
            float: right;
        }
        .text_right{
            text-align: right;
        }
        .text_left{
            text-align: left;
        }
        .text_center{
            text-align: center;
        }
        .clearfix{
            clear: both;
        }
        .mt-30{
            margin-top: 30px;
        }
        .mt-50 {
            margin-top: 50px;
        }
        .mb-80{
            margin-bottom: 80px;
        }
        .mb-50 {
            margin-bottom: 50px;
        }
        .py-10 {
            padding-top: 10px;
            padding-bottom: 10px;
        }
        .f900 {
            font-weight: 900;
        }
        .table_title {
            font-weight: 900;
            background-color: #f2f3f4;
            border: 1px solid #e8e9ea;
            padding: 5px 8px;
            font-size: 16px;
            margin-top: 21px;
        }
        .table_a, .table_b, .table_g  {
            font-size: 16px;
        }
        .table{
            border: 1px solid #e8e9ea;
            border-top:0;
            width: 100%;
            border-collapse: collapse;
        }
        .table td{
            padding: 5px 8px;
        }
        .table td:not(:last-child){
            border-right: 1px solid #c2d1d7;
        }
        .table tr:not(:last-child) td{
            border-bottom: 1px solid #c2d1d7;
        }
        .table_a td:first-child {
            width: 20%;
        }
        .table_b td:nth-of-type(odd){
            width: 20%;
        }
        .table_b td:nth-of-type(even){
            width: 30%;
        }
        .legal_desc{
            padding: 5px 8px;
            font-size: 16px;
            line-height: 21px;
        }
        .orange_text{
            color: #f2692c;
            font-weight: 900;
        }
        /* .table_g td{
            width: 20%;
        } */
        .table_g td:first-child{
            width: 5%;
        }
        .table_g td:nth-child(2){
            width: 20%;
        }
        .table_g td:nth-child(3){
            width: 12%;
        }
        .table_g td:nth-child(4) {
            width: 39%;
        }
        .table_g td:nth-child(5) {
            width: 14%;
        }
        .table_g td:nth-child(6) {
            width: 10%;
        }
        .w-250 {
            width: 250px;
        }
        
    </style>
</head>
<body>
    <?php 
        $awsPath = env('AWS_PATH');
    ?>
    <!-- <div class="page_container">
        <div class="pdf_page size_letter">
            <div class="pdf_body">
                <table cellpadding="0" cellspacing="0" border="0" style="width:100%;margin: 0 auto;height:90vh;background-position: center;background-size: cover;background-image: url('<?php echo base_url('assets/frontend/images/bg.jpg') ?>'); background-repeat: no-repeat;">
                    <tbody>
                        <tr>
                            <td style="padding-left: 70px; height: 320px;"></td>
                        </tr>
                        <tr>
                            <td style="padding-left: 70px; vertical-align: middle;">
                                    <span style="font-size: 60px;color: #d35627; font-weight: 400;font-family: 'LushScript';"><i>Concierge</i></span>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-left: 70px; vertical-align: middle;">
                                <span style="font-size: 30px;color: #FFF;text-transform: uppercase;font-weight: 800;font-family: 'Montserrat';">LISTING PRELIM</span> 
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-left: 70px; vertical-align: middle;padding-top:15px">
                                <span style="font-size: 21px;color: #d35627; font-weight:bold;text-transform: uppercase;font-family: 'Montserrat';">Report</span>   
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-left: 70px; vertical-align: middle;">
                                <span style="font-size: 14px;color: #fff; font-weight:400;text-transform: uppercase;font-family: 'Montserrat';"><?php echo $orderDetails['full_address']; ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <table cellpadding="0" cellspacing="0" border="0" style="width:100%">
                                    <tbody>
                                        <tr>
                                            <td style="height: 100px;"></td>
                                        </tr>
                                        <tr>
                                            <td style="font-size: 22px; font-weight: 500;color: #fff;text-transform: uppercase;padding-left: 70px;font-family: 'Montserrat';">
                                                Prepared For: <br/><span style="font-size: 18px;font-weight: 300;"><?php echo $orderDetails['cust_first_name'] . ' ' . $orderDetails['cust_last_name']; ?></span>
                                            </td>
                                            <td align="right" style="padding-right: 70px;">
                                                <img src="<?php echo base_url('assets/frontend/images/pacific.png') ?>" style="width: 250px;" alt=""/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="height: 170px;"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </tbody>        
                </table>
            </div>    
        </div>
    </div>
    <div class="page-break" style="page-break-after: always;"></div> -->
    <div class="page_container">
        <div style="height:50px"></div>
        <div class="pdf_page size_letter">
            <div class="pdf_header">
                <div class="logo_container">
                    <img src="<?php echo base_url('assets/frontend/images/pacific.png') ?>" alt="">
                </div>
                <div class="header_address">
                    Pacific Coast Title Company <br>
                    1111 E. Katella Ave Ste. 120<br>
                    Orange, CA 92867
                </div>
            </div>
            <div class="pdf_body">
                <div class="listing_report mb-50"><b>Listing Prelim Report</b></div>
                <div class="report_info">
                    <div class="float_left w-250">
                        <?php echo date('M jS g:i a', strtotime($orderDetails['opened_date'])); ?><br>
                        Escrow No: <?php echo $orderDetails['escrow_number']; ?>
                    </div>
                    <div class="float_right text_right w-250">
                        Title Order #:<?php echo $orderDetails['lp_file_number']; ?><br>
                        Title Officer: <?php echo $orderDetails['titleofficer_first_name'] . ' ' . $orderDetails['titleofficer_last_name']; ?>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="billing_info billing_info-m-40">
                    <div class="float_left">
                        <?php echo $orderDetails['cust_company_name']; ?><br>
                        <?php echo $orderDetails['cust_first_name'] . ' ' . $orderDetails['cust_last_name']; ?><br>
                        <?php echo $orderDetails['cust_address']; ?><br>
                        <?php echo $orderDetails['cust_city']; ?>, <?php echo $orderDetails['cust_state']; ?> <?php echo $orderDetails['cust_zipcode']; ?>
                    </div>
                    <div class="float_right text_right">
                        Transaction: <?php echo ($orderDetails['prod_type'] == 'loan') ? 'Loan' : 'Sale' ?><br>
                        Price: $<?php echo ($orderDetails['prod_type'] == 'loan') ? $orderDetails['loan_amount'] : $orderDetails['sales_amount']; ?>
                    </div>
                </div>
                <div class="clearfix"></div>
				 <div class="listing_report-alt text_left mt-50"><b>Subject Property: <?php echo $orderDetails['full_address']; ?></b></div>
                <div class="listing_report-alt-1 text_left mb-50"><b>On behalf of: <?php echo $orderDetails['salerep_first_name'] . ' ' . $orderDetails['salerep_last_name']; ?></b></div>
                
            </div>           
            <div class="pdf_footer">
                <p class="page_title">Listing Prelim Report</p>
                <p class="page_text">Cover</p>
            </div>
        </div>
    </div>
    <div class="page-break" style="page-break-after: always;"></div>
    <div class="page_container">
        <div class="pdf_page size_letter">
            <div class="pdf_body">
                <div class="main_title">Table of Contents</div>
                <div class="title_divider"></div>
                <div class="f26">What this report includes</div>
                <div class="body_text">
                    This report contains important information that gives you the insight needed in regards to the property and potentional obstacles that the seller might face when selling their property. Here are the sections:<br><br>
					<strong>Section:&nbsp A </strong>- <span style="color:red;">Property Information</span><br>
					<strong>Section:&nbsp B</strong> - <span style="color:red;">Beds, Baths, Zoning</span><br>
					<strong>Section:&nbsp C</strong> - <span style="color:red;">Current Ownership</span><br>
					<strong>Section:&nbsp D</strong> - <span style="color:red;">Taxes - 1st & 2nd Installment</span><br>
					<strong>Section:&nbsp E</strong> - <span style="color:red;">Legal Description</span><br>
					<strong>Section:&nbsp F</strong> - <span style="color:red;">Property Vesting</span><br>
                    <strong>Section:&nbsp G</strong> - <span style="color:red;">Open Deeds of Trust</span><br>
					<strong>Section:&nbsp H</strong> - <span style="color:red;">Foreclosure Activity</span><br>
					<strong>Section:&nbsp I</strong> - <span style="color:red;">Liens & Items for Review</span><br>
                </div>
               
                <div class="h150"></div>
                <div class="main_title">About This Report</div>
                <div class="title_divider"></div>
                <div class="f26">And its contents</div>
                <div class="body_text">
                    The information is available through public sources as of <b><span class="red_text"><?php echo date('m/d/Y', strtotime($orderDetails['opened_date'])); ?></span></b>. Information provided by the
                    vendor and public records may not always match exactly depending on how often the information is
                    updated by each source. The items presented or those found that are directly tied to the property in
                    question. Items that are directly associated with the owner will require a statement of
                    information form to be fill out in order for us to conduct a more
                    thorough search. This docment is not a preliminary title report.
                </div>
            </div>           
            <div class="pdf_footer">
                <p class="page_title">Listing Prelim Report</p>
                <p class="page_text">Welcome</p>
            </div>
        </div>
    </div>
    <!-- <div class="page_container">
        <div class="pdf_page size_letter">
            <div class="pdf_body">
                <div class="main_title">Congratulations</div>
                <div class="title_divider"></div>
                <div class="f26">On your journey to selling</div>
                <div class="body_text">
                    The purchase of a home is often the largest single financial investment many people may make in
                    their lifetime. The importance of fully protecting such an investment cannot be overly stressed. A
                    basic home ownership protection essential to the security of the home is safe, sound, reliable
                    title insurance.<br><br>
                    Sincerely<br>
                </div>
                <div style="font-size: 50px; font-weight: 400; color: #276fa8;font-family: 'LushScript';font-style: italic;">
                    <?php echo $orderDetails['salerep_first_name'] . ' ' . $orderDetails['salerep_last_name']; ?>
                </div>
                <div class="h150"></div>
                <div class="main_title">About This Report</div>
                <div class="title_divider"></div>
                <div class="f26">And its contents</div>
                <div class="body_text">
                    The information is available through public sources as of <b><span class="red_text"><?php echo date('m/d/Y', strtotime($orderDetails['opened_date'])); ?></span></b>. Information provided by the
                    vendor and public records may not always match exactly depending on how often the information is
                    updated by each source. The items presented or those found that are directly tied to the property in
                    question. Items that are directly associated with the owner will require a statement of
                    information form to be fill out in order for us to conduct a more
                    thorough search. This docment is not a preliminary title report. <br>
                    
                </div>
            </div>           
            <div class="pdf_footer">
                <p class="page_title">Listing Prelim Report</p>
                <p class="page_text">Welcome</p>
            </div>
        </div>
    </div> -->
    <div class="page-break" style="page-break-after: always;"></div>
    <div class="page_container">
        <div style="height:50px"></div>
        <div class="pdf_page size_letter">
            <div class="pdf_header">
                <div class="logo_container">
                    <img src="<?php echo base_url('assets/frontend/images/pacific.png') ?>" alt="">
                </div>
                <div class="header_address">
                    Pacific Coast Title Company <br>
                    1111 E. Katella Ave Ste. 120<br>
                    Orange, CA 92867
                </div>
            </div>
            <div class="pdf_body">
                <div class="listing_report"><b>Listing Prelim Report</b></div>
                <div class="report_info">
                    <div class="float_left">
                        <?php echo date('M jS g:i a', strtotime($orderDetails['opened_date'])); ?><br>
                        Escrow No: <?php echo $orderDetails['escrow_number']; ?>
                    </div>
                    <div class="float_right text_right">
                        Title Order #:<?php echo $orderDetails['lp_file_number']; ?><br>
                        Title Officer: <?php echo $orderDetails['titleofficer_first_name'] . ' ' . $orderDetails['titleofficer_last_name']; ?>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="billing_info">
                    <div class="float_left">
                        <?php echo $orderDetails['cust_company_name']; ?><br>
                        <?php echo $orderDetails['cust_first_name'] . ' ' . $orderDetails['cust_last_name']; ?><br>
                        <?php echo $orderDetails['cust_address']; ?><br>
                        <?php echo $orderDetails['cust_city']; ?>, <?php echo $orderDetails['cust_state']; ?> <?php echo $orderDetails['cust_zipcode']; ?>
                    </div>
                    <div class="float_right text_right">
                        Transaction: <?php echo ($orderDetails['prod_type'] == 'loan') ? 'Loan' : 'Sale' ?><br>
                        Price: $<?php echo ($orderDetails['prod_type'] == 'loan') ? $orderDetails['loan_amount'] : $orderDetails['sales_amount']; ?>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="listing_report text_left mt-30"><b>Subject Property: <?php echo $orderDetails['full_address']; ?></b></div>
                <div class="mb-80"></div>
                <div class="table_title"><em>Section A:</em> Property</div>
                <table class="table_a table">
                    <tr>
                        <td>Property Address</td>
                        <td><?php echo $orderDetails['full_address']; ?></td>
                    </tr>
                    <tr>
                        <td>APN</td>
                        <td><?php echo $orderDetails['apn']; ?></td>
                    </tr>
                    <tr>
                        <td>County</td>
                        <td><?php echo $orderDetails['county']; ?></td>
                    </tr>
                    <tr>
                        <td colspan="2"><p class="py-10">Brief Legal: <?php echo $orderDetails['legal_description']; ?></p></td>
                    </tr>
                </table>
                <div class="table_title"><em>Section B:</em> Beds, Baths, & Zoning</div>
                <table class="table_b table">
                    <tr>
                        <td>Bedrooms</td>
                        <td><?php echo $titlePointDetails[0]['property_bedroom']; ?></td>
                        <td>Property Type:</td>
                        <td><?php echo $orderDetails['transaction_type']; ?></td>
                    </tr>
                    <tr>
                        <td>Bathrooms</td>
                        <td><?php echo $titlePointDetails[0]['property_bathroom']; ?></td>
                        <td>Zoning: </td>
                        <td><?php echo $titlePointDetails[0]['property_zoning']; ?></td>
                    </tr>
                    <tr>
                        <td>Square Feet</td>
                        <td><?php echo $titlePointDetails[0]['property_squarefeet']; ?> </td>
                        <td>ADU:</td>
                        <td>Eligible</td>
                    </tr>
                    <tr>
                        <td>Lot Size</td>
                        <td><?php echo $titlePointDetails[0]['property_lotsize']; ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td># of Units</td>
                        <td>1</td>
                        <td></td>
                        <td></td>
                    </tr>
                </table>
            </div>           
            <div class="pdf_footer">
                <p class="page_title">Listing Prelim Report</p>
                <p class="page_text">2</p>
            </div>
        </div>
    </div>
    
    <div class="page-break" style="page-break-after: always;"></div>
    <?php  
        $firstInstallment = json_decode($titlePointDetails[0]['first_installment'], true);
        $secondInstallment = json_decode($titlePointDetails[0]['second_installment'], true);
    ?>
    <div class="page_container">
        <div style="height:50px"></div>
        <div class="pdf_page size_letter">
            <div class="pdf_header">
                <div class="logo_container">
                    <img src="<?php echo base_url('assets/frontend/images/pacific.png') ?>" alt="">
                </div>
                <div class="report_info float_right text_right">
                    Title Order #:<?php echo $orderDetails['lp_file_number']; ?><br>
                    Title Officer: <?php echo $orderDetails['titleofficer_first_name'] . ' ' . $orderDetails['titleofficer_last_name']; ?><br>
                    <?php echo date('M jS g:i a', strtotime($orderDetails['opened_date'])); ?><br>
                    Escrow No: <?php echo $orderDetails['escrow_number']; ?>
                </div>
            </div>
            <div class="pdf_body">
                <div class="table_title"><em>Section C:</em> Owners</div>
                <table class="table_a table">
                    <tr>
                        <td>Primary</td>
                        <td><?php echo $orderDetails['primary_owner'] ?></td>
                    </tr>
                    <tr>
                        <td>Mailing Address:</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Secondary</td>
                        <td><?php echo $orderDetails['secondary_owner'] ?></td>
                    </tr>
                    <tr>
                        <td>Mailing Address</td>
                        <td></td>
                    </tr>
                </table>
                <div class="table_title"><em>Section D:</em> Property Taxes  <a href="<?php echo $taxFileUrl?>" target="_blank"><?php echo $orderDetails['apn']; ?></a></div>
                <table class="table_b table">
                    <tr>
                        <td colspan="2" class="text_center f900">1st Installment </td>
                        <td colspan="2" class="text_center f900">2nd Installment </td>
                    </tr>
                    <tr>
                        <td>Balance:</td>
                        <td>$<?php echo $firstInstallment['Balance']; ?></td>
                        <td>Balance:</td>
                        <td>$<?php echo $secondInstallment['Balance']; ?></td>
                    </tr>
                    <tr>
                        <td>Amount:</td>
                        <td>$<?php echo $firstInstallment['Amount']; ?></td>
                        <td>Amount:</td>
                        <td>$<?php echo $secondInstallment['Amount']; ?></td>
                    </tr>
                    <tr>
                        <td>Due Date:</td>
                        <td><?php echo $firstInstallment['DueDate']; ?></td>
                        <td>Due Date:</td>
                        <td><?php echo $secondInstallment['DueDate']; ?></td>
                    </tr>
                    <tr>
                        <td>Number:</td>
                        <td><?php echo $firstInstallment['Number']; ?></td>
                        <td>Number:</td>
                        <td><?php echo $secondInstallment['Number']; ?></td>
                    </tr>
                    <tr>
                        <td>Penalty Date:</td>
                        <td></td>
                        <td>Penalty Date:</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Penalty Amount</td>
                        <td>$<?php echo $firstInstallment['Penalty']; ?></td>
                        <td>Penalty Amount</td>
                        <td>$<?php echo $secondInstallment['Penalty']; ?></td>
                    </tr>
                    <tr>
                        <td>Status: </td>
                        <td><?php echo $firstInstallment['Status']; ?></td>
                        <td>Status:</td>
                        <td><?php echo $secondInstallment['Status']; ?></td>
                    </tr>
                    <tr>
                        <td>Amount Paid:</td>
                        <td>$<?php echo $firstInstallment['AmountPaid']; ?></td>
                        <td>Amount Paid:</td>
                        <td><?php echo $secondInstallment['AmountPaid']; ?></td>
                    </tr>
                    <tr>
                        <td>Tax Year</td>
                        <td><?php echo $firstInstallment['TaxYear']; ?></td>
                        <td>Tax Year</td>
                        <td><?php echo $secondInstallment['TaxYear']; ?></td>
                    </tr>
                </table>
                <div class="table_title"><em>Section E:</em> Legal Description:</div>
                <div class="legal_desc">
                    <?php echo $titlePointDetails[0]['legal_description']; ?><br>
                    For the most up to date and accurate legal description please see grant deed below. 
                </div>
                <div class="table_title"><em>Section F:</em> Property Vesting:</div>
                <div class="legal_desc">
                    <?php echo $titlePointDetails[0]['vesting_information'] ? $titlePointDetails[0]['vesting_information'] : 'Refer to grant deed below' ?> <br><a target="_blank" href="<?php echo $awsPath . 'grant-deed/' . $titlePointDetails[0]['file_number'] ?>.pdf">
                        <?php echo $titlePointDetails[0]['cs4_instrument_no'] ?>  
                    </a> <br>
                    <?php  
                        foreach ($vestingInstrumentDetails as $key => $vesting) { 
                            $count = substr_count($titlePointDetails[0]['cs4_instrument_no'], $vesting['instrument']);
                            if(isset($count) && !empty($count)) {
                                continue; 
                            }
                            // $arr = explode('-', $titlePointDetails[0]['cs4_instrument_no']);
		                    // if (count($arr) > 1 && in_array($vesting['instrument'], $arr)) {
                            // }
                            ?>

                            <a style="color: inherit;" target="_blank" href="<?php echo $awsPath . 'title-point/' .$vesting['id'] ?>.pdf">
                            <?php echo  $vesting['instrument']; ?>
                            </a> <br>
                    <?php }
                    ?>
                </div>
            </div>           
            <div class="pdf_footer">
                <p class="page_title">Listing Prelim Report</p>
                <p class="page_text">3</p>
            </div>
        </div>
    </div>
    <div class="page-break" style="page-break-after: always;"></div>
    <?php
        $i = 0; 
        $page = 4;
        $allSectionRecordChunk = array_chunk($allSectionRecord, 7);

        $countG = count($sectionGRecord);
        $countH = count($sectionHRecord);
        $countI = count($sectionIRecord);
        $countJ = count($sectionJRecord);
        $noRecordG = $noRecordH = $noRecordI = $noRecordJ = false;
        $sectionGRecordNumber = $sectionHRecordNumber = $sectionIRecordNumber = 0;
        
        foreach($allSectionRecordChunk as $recordKey => $chunk) {
            $displaySectionG = ((array_search('G', array_column($chunk, 'section')) !== FALSE)) ? '' : 'hide';
            $displaySectionH = (array_search('H', array_column($chunk, 'section')) !== FALSE) ? '' : 'hide';
            $displaySectionI = (array_search('I', array_column($chunk, 'section')) !== FALSE) ? '' : 'hide';
            // $displaySectionJ = (array_search('J', array_column($chunk, 'section')) !== FALSE) ? '' : 'hide';
            $displayInG = array_filter($chunk, function($v) { return ($v['section'] == 'G');});
            $displayInH = array_filter($chunk, function($v) { return ($v['section'] == 'H');});
            $displayInI = array_filter($chunk, function($v) { return ($v['section'] == 'I');});
            // $displayInJ = array_filter($chunk, function($v) { return ($v['section'] == 'J');});
   ?>
    <div class="page_container">
        <div style="height:50px"></div>
        <div class="pdf_page size_letter">
            <div class="pdf_header">
                <div class="logo_container">
                    <img src="<?php echo base_url('assets/frontend/images/pacific.png') ?>" alt="">
                </div>
                <div class="report_info float_right text_right">
                    Title Order #:<?php echo $orderDetails['lp_file_number'] ?><br>
                    Title Officer: <?php echo $orderDetails['titleofficer_first_name'] . ' ' . $orderDetails['titleofficer_last_name']; ?><br>
                    <?php echo date('M jS g:i a', strtotime($orderDetails['opened_date'])); ?><br>
                    Escrow No: <?php echo $orderDetails['escrow_number'] ?>
                </div>
            </div>
            <div class="pdf_body">
                <div class="table_title <?php echo (strpos(json_encode($displayInG), 'message') && !$noRecordG) ? '' : $displaySectionG; ?>"><em>Section G:</em> Opens Deeds of Trust</div>
                <table class="table table_g <?php echo (strpos(json_encode($displayInG), 'message') && !$noRecordG) ? '' : $displaySectionG; ?>">
                    <tr>
                        <td></td>
                        <td>Lender</td>
                        <td>Loan Amount</td>
                        <td>Party</td>
                        <td>Recorded</td>
                        <td class="text_center">Instrument #</td>
                    </tr>
                    <?php 
                        $i = 0;
                        if(!empty($displayInG) && !strpos(json_encode($displayInG), 'message') > 0) { 
                            foreach($displayInG as $k => $val) {
                        ?>
                    <tr>
                        <td><?php echo $sectionGRecordNumber + 1 . (($sectionGRecordNumber == 0) ? 'st' : (($sectionGRecordNumber == 1) ? 'nd' : (($sectionGRecordNumber == 2) ? 'rd' : 'th'))); ?></td>
                        <td><?php echo  $val['document_name']; ?></td>
                        <td><?php echo  number_format($val['loan_amount']); ?></td>
                        <td><?php echo  ucwords(strtolower($val['parties'])); ?></td>
                        <td><?php echo  $val['recorded_date']; ?></td>
                        <td class="text_center"><b class="orange_text"><a style="color: inherit;" target="_blank" href="<?php echo $awsPath . 'title-point/'.$val['id'] ?>.pdf"><?php echo  $val['instrument']; ?></a></b></td>
                    </tr>
                    <?php $sectionGRecordNumber++;} } else { $noRecordG = true;?>
                    <tr>
                        <td></td>
                        <td colspan="3">There is No Opens Deeds of Trust found.</td>
                    </tr>
                    <?php } ?>
                </table>
                <div class="table_title <?php echo (strpos(json_encode($displayInH), 'message') && !$noRecordH) ? '' : $displaySectionH; ?>"><em>Section H:</em> Foreclosure Activity</div>
                <table class="table_g table <?php echo (strpos(json_encode($displayInH), 'message') && !$noRecordH) ? '' :  $displaySectionH; ?>">
                    <tr>
                        <td></td>
                        <td>Document Name</td>
                        <td>Loan Amount</td>
                        <td>Party</td>
                        <td>Recorded</td>
                        <td class="text_center">Instrument #</td>
                    </tr>
                    <?php
                    if (!empty($displayInH) && !strpos(json_encode($displayInH), 'message') > 0) { 
                    foreach ($displayInH as $key => $val) {  ?>
                    <tr>
                        <td><?php echo $sectionHRecordNumber + 1 . (($sectionHRecordNumber == 0) ? 'st' : (($sectionHRecordNumber == 1) ? 'nd' : (($sectionHRecordNumber == 2) ? 'rd' : 'th'))); ?></td>
                        <td><?php echo  $val['document_name']; ?></td>
                        <td><?php echo  number_format($val['loan_amount']); ?></td>
                        <td><?php echo  ucwords(strtolower($val['parties'])); ?></td>
                        <td><?php echo  $val['recorded_date']; ?></td>                        
                        <td class="text_center"><b class="orange_text"><a style="color: inherit;" target="_blank" href="<?php echo $awsPath . 'title-point/'.$val['id'] ?>.pdf"><?php echo  $val['instrument']; ?></a></b></td>
                    </tr>
                    <?php $sectionHRecordNumber++; } } else {
                        $noRecordH = true;
                        ?>
                        <tr style="text-align: center;" >
                            <td > </td>
                            <td colspan="3" > There are No Foreclosure activity found.</td>
                        </tr>
                    <?php } ?>
                </table>
                <div class="table_title <?php echo (strpos(json_encode($displayInI), 'message') && !$noRecordI) ? '' : $displaySectionI; ?>"><em>Section I:</em> Liens, Notices, and Violations</div>
                <table class="table_g table <?php echo (strpos(json_encode($displayInI), 'message') && !$noRecordI) ? '' :  $displaySectionI; ?>">
                    <tr>
                        <td></td>
                        <td>Document Name</td>
                        <td>Loan Amount</td>
                        <td>Party</td>
                        <td>Recorded</td>
                        <td class="text_center">Instrument #</td>
                    </tr>
                    <?php  
                    
                    if (!empty($displayInI && !strpos(json_encode($displayInI), 'message') > 0)) { 
                    
                    foreach ($displayInI as $key => $val) {  ?>
                    <tr>
                        <td ><?php echo $sectionIRecordNumber + 1 . (($sectionIRecordNumber == 0) ? 'st' : (($sectionIRecordNumber == 1) ? 'nd' : (($sectionIRecordNumber == 2) ? 'rd' : 'th'))); ?></td>
                        <td ><?php echo  $val['document_name']; ?></td>
                        <td><?php echo  number_format($val['loan_amount']); ?></td>
                        <td><?php echo  ucwords(strtolower($val['parties'])); ?></td>
                        <td ><?php echo  $val['recorded_date']; ?></td>
                        <td class="text_center"><b class="orange_text"><a style="color: inherit;" target="_blank" href="<?php echo $awsPath . 'title-point/'.$val['id'] ?>.pdf"><?php echo  $val['instrument']; ?></a></b></td>
                    </tr>
                    <?php $sectionIRecordNumber++; } } else {
                        $noRecordI = true;?>
                        <tr style="text-align: center;" >
                            <td ></td>
                            <td colspan="3" > There are No Liens, Notices, and Violations found.</td>
                        </tr>
                    <?php } ?>
                    
                </table>
                <?php//}?>
            </div>           
            <div class="pdf_footer">
                <p class="page_title">Listing Prelim Report</p>
                <p class="page_text"><?php echo $page; ?></p>
            </div>
        </div>
    </div>
    <div class="page-break" style="page-break-after: always;"></div>
    <?php $page++;} ?>
    <div class="page-break" style="page-break-after: always;"></div>
    
    <?php if($is_plat_map_exist == 1) { ?>
    <div class="page-break" style="page-break-after: always;"></div>
    <div class="page_container">
        <div style="height:50px"></div>
        <div class="pdf_page size_letter">
            <div class="pdf_header">
                <div class="logo_container">
                    <img src="<?php echo base_url('assets/frontend/images/pacific.png') ?>" alt="">
                </div>
                <div class="report_info float_right text_right">
                    Title Order #:<?php echo $orderDetails['lp_file_number'] ?><br>
                    Title Officer: <?php echo $orderDetails['titleofficer_first_name'] . ' ' . $orderDetails['titleofficer_last_name']; ?><br>
                    <?php echo date('M jS g:i a', strtotime($orderDetails['opened_date'])); ?><br>
                    Escrow No: <?php echo $orderDetails['escrow_number'] ?>
                </div>
            </div>
            <div class="pdf_body">
                <!-- <div class="table_title"><em>Section G:</em> Open Loans:</div> -->
                <div style="height:20px"></div>
                <img src="<?php echo $awsPath . 'plat-map/'.$orderDetails['lp_file_number'] ?>.png" style="max-width:800px;margin:0 auto;text-align:center; max-height: 9in;" alt=""/>
            </div>           
            <div class="pdf_footer">
                <p class="page_title">Listing Prelim Report</p>
                <p class="page_text"><?php echo $page; ?></p>
            </div>
        </div>
    </div>
    <?php } ?>
    <div class="page-break" style="page-break-after: always;"></div>

    <div class="page_container">
        <div style="height:50px"></div>
        <div class="pdf_page size_letter">
            <div class="pdf_header">
                <div class="logo_container">
                    <img src="<?php echo base_url('assets/frontend/images/pacific.png') ?>" alt="">
                </div>
                <div class="report_info float_right text_right">
                    Title Order #:<?php echo $orderDetails['lp_file_number'] ?><br>
                    Title Officer: <?php echo $orderDetails['titleofficer_first_name'] . ' ' . $orderDetails['titleofficer_last_name']; ?><br>
                    <?php echo date('M jS g:i a', strtotime($orderDetails['opened_date'])); ?><br>
                    Escrow No: <?php echo $orderDetails['escrow_number'] ?>
                </div>
            </div>
            <div class="pdf_body" >
            <div class="logo" style="position:relative;height:10in">
                <img src="<?php echo base_url('assets/frontend/images/pacific.png') ?>" style="max-width:530px;position:absolute;top:35%;left:15%; " alt=""/>
            </div>
            </div>           
            <div class="pdf_footer">
                <p class="page_title">Listing Prelim Report</p>
                <p class="page_text"><?php echo $page; ?></p>
            </div>
        </div>
    </div>
</body>
