<html>
<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;900&family=Poppins:wght@400;500;700;900&display=swap" rel="stylesheet">
    <style>
        body{
            font-family: 'Montserrat', sans-serif;
            margin: 0;
            padding: 0;
            font-size: 16px;
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
        .pdf_header{
            padding: 20px 0;
        }
        .ionfraud_logo{
            display: block;
            margin: 0 auto;
            max-width: 70%;
        }
        .pdf_body {
            position: absolute;
            top: 170px;
            bottom: 1.2in;
            left: 0;
            right: 0;
        }
        .report_title{
            font-size: 21px;
            font-weight: bold;
            text-align: center;
            position: relative;
        }
		.report_date{
            font-size: 19px;
            font-weight: bold;
            text-align: center;
            position: relative;
			padding-top:10px;
        }
        .report_title span{
            position: absolute;
            right: 0;
            font-size: 15px;
            top: 50%;
            transform: translateY(-50%);
        }
        .address_bar {
            background: #f26a2b;
            font-size: 17px;
            font-weight: bold;
            color: #fff;
            padding: 20px 16px;
            letter-spacing: 0.5px;
            margin: 35px 0 20px;
        }
        .info_bar{
            background: #03374f;
            font-size: 17px;
            font-weight: bold;
            color: #fff;
            padding: 20px 16px;
            letter-spacing: 0.5px;
        }
        .property_detail_box{
            background: #f7f7f7;
            border: 2px solid #d3d3d3;
            border-radius: 0 0 6px 6px;
            padding: 20px;
        }
        hr{
            border-top: 2px solid #d3d3d3;
            margin-bottom: 20px;
            margin-top: 20px;
        }
        .properties{
            font-weight: bold;
            margin-bottom: 10px;
        }
        .properties span{
            font-weight: 400;
            display: block;
            margin-top: 5px;
            margin-left: 70px;
        }
        .properties_value{
            margin-bottom: 10px;
        }
        .grid::after{
            display: table;
            content: '';
            width: 100%;
        }
        .grid > .col-6 {
            float: left;
            width: 50%;
        }
        .text-center{
            text-align: center;
        }
        .my-20{
            margin-top: 20px;
            margin-bottom: 20px;
        }
        .f14{
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="page_container">
        <div class="pdf_page size_letter">
            <div class="pdf_header">
                <img src="<?php echo base_url('assets/frontend/images/ionfraud.png') ?>" class="ionfraud_logo" alt="ionfraud">
            </div>
            <div class="pdf_body">
                <div class="report_title">Property Ownership Report</div>
				  <div class="report_date">Report Date:  <?php echo date("mm-dd-Y"); ?></div>
                <div class="address_bar"><?php echo $Mailaddress; ?></div>
                <div class="info_bar">Property Information</div>
                <div class="property_detail_box">
                    <div class="properties">Owner Name: <br><span> <?php echo $Ownername; ?></span></div>
                    <div class="properties">Mailing Address: <br><span> <?php echo $Mailaddress; ?></span></div>
                    <hr>
                    <div class="grid">
                        <div class="col-6">
                            <div class="grid">
                                <div class="col-6">
                                    <div class="properties">Country:</div>
                                    <div class="properties">Parcel Num:</div>
                                    <div class="properties">Property Type:</div>
                                    <div class="properties">Owner Occupied:</div>
                                    <div class="properties">Foreign Seller:</div>
                                </div>
                                <div class="col-6">
                                    <div class="properties_value"><?php echo $Countyname; ?></div>
                                    <div class="properties_value"><?php echo $Parcelid; ?></div>
                                    <div class="properties_value">SINGLE FAMILY</div>
                                    <div class="properties_value">Yes</div>
                                    <div class="properties_value">No</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="grid">
                                <div class="col-6">
                                    <div class="properties">FIPS:</div>
                                    <div class="properties">Account Num:</div>
                                    <div class="properties">Low Value Threshold:</div>
                                    <div class="properties">Est Mortgage Bal:</div>
                                    <div class="properties">Assessed Value:</div>
                                </div>
                                <div class="col-6">
                                    <div class="properties_value"><?php echo $Fips; ?></div>
                                    <div class="properties_value">R0367491</div>
                                    <div class="properties_value">$ 533,000</div>
                                    <div class="properties_value">$ 82,349</div>
                                    <div class="properties_value">$ 628,307</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="properties">Legal Description: <br><span><?php echo $Legal; ?></span></div>
                </div>
                <div class="text-center my-20 f14">
                    <em>All information provided is deemed reliable, but not guaranteed.<br>
                    Accuracy of the information may vary by county.</em>
                </div>
                <div class="text-center f14">
                    <b>
                        Copyright © 2024 , Pacific Coast Title Company. All rights reserved.
                    </b>
                </div>
            </div>
        </div>
    </div>
</body>
