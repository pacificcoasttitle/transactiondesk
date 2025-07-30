<html>
<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" cros56rigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;700&family=Tai+Heritage+Pro:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body{
            font-family: 'Montserrat', sans-serif;
            margin: 0;
            padding: 0;
            font-size: 14px;
        }
        *{
            box-sizing: border-box;
        }
        .float-right{
            float: right;
        }
        .mt-10{
            margin-top: 10px;
        }
        .mt-15{
            margin-top: 15px;
        }
        .mt-20{
            margin-top: 20px;
        }
        .mt-50{
            margin-top: 50px;
        }
        .mt-49{
            margin-top: 49px;
        }
        .text-center{
            text-align: center !important;
        }
        .text-left{
            text-align: left !important;
        }
        .size_a4 { width: 8.3in; height: 11.7in; }
        .size_letter { width: 8.5in; height: 11in; }
        .size_executive { width: 7.25in; height: 10.5in; }
        .pdf_page {
            margin: 0 auto;
            box-sizing: border-box;
            background-color: #fff;
            color: #000;
            position: relative;
        }
        .pdf_header {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 50px;
        }
        .pdf_footer {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            font-size: 12px;
        }
        .tax-text{
            position: absolute;
            left: 0;
            top: 50%;
            font-size: 24px;
            font-weight: 700;
            transform: translateY(-50%);
        }
        .title-logo{
            position: absolute;
            right: 0;
            top: 0;
        }
        .address-bar {
            position: absolute;
            top: 51px;
            padding: 20px 0 12px;
            width: 100%;
            border-bottom: 3px solid #000;
        }
        .address {
            line-height: 18px;
        }
        .tax-detail {
            position: absolute;
            right: 30px;
            top: 20px;
            width: 240px;
            display: block;
        }
        .tax-detail span {
            float: right;
            width: 115px;
        }
        .company-name{
            margin-top: 15px;
        }
        .pdf-body{
            position: absolute;
            top: 171px;
            width: 100%;
            font-family: 'Tai Heritage Pro', serif;
        }
        .billing-address {
            border: 1px solid #000;
            margin-top: 40px;
            line-height: 18px;
            margin-bottom: 15px;
        }
        .billing-address span {
            width: 130px;
            display: inline-block;
        }
        .billing-address div{
            padding: 0 5px;
        }
        .billing-address div:last-child,.border-top{
            border-top: 1px solid;
        }
        .tax-table {
            border: 1px solid #000;
            line-height: 18px;
            margin-bottom: 15px;
        }
        .tax-table::after{
            content: '';
            display: table;
            width: 100%;
        }
        .column{
            width: 33.33%;
            float: left;
        }
        .column:not(:last-child){
            border-right: 1px solid #000;
        } 
        .column div{
            padding: 0 5px;
        }
        table {
            width: 100%;
            border: 1px solid #000;
            font-size: 14px;
            border-collapse: collapse;
            font-family: 'Tai Heritage Pro', serif;
        }
        table.table th:first-child,
        table.table td:first-child{
            text-align: left;
            padding-left: 5px;
        }
        table.table th:last-child,
        table.table td:last-child{
            text-align: right;
        }
        table.table th{
            font-weight: 400;
            border-bottom: 1px solid #000;
        }
        table.table th,
        table.table td{
            text-align: center;
            line-height: 18px;
        }
        table.table td{
            font-weight: 700;
        }
        table.table.last-table th:first-child,
        table.table.last-table td:first-child{
            text-align: center;
            width: 200px;
        }
        table.table.last-table th:nth-child(2),
        table.table.last-table td:nth-child(2){
            text-align: left;
        }
        .footer-left{
            position: absolute;
            bottom: 0;
            left: 0;
        }
        .footer-right{    
            position: absolute;
            right: 0;
            text-align: right;
            bottom: 0;
        }
        .page-number {
            margin-top: -25px;
        }
        table.table .no-border th{
            border: 0;
        }
        table.table.text-center th,
        table.table.text-center td{
            text-align: center !important;
        }
        table.table.text-center th.text-left{
            text-align: left !important;
        }
        .contact-box {
            font-family: 'Tai Heritage Pro', serif;
            border: 1px solid #000;
            font-weight: 700;
            line-height: 18px;
            text-align: center;
            padding: 2px 5px;
            margin-top: 15px;
        }
        .report-end{
            font-family: 'Tai Heritage Pro', serif;
            text-align: center;
            margin-top: 20px;
            font-weight: 700;
        }
        .border-t-0{
            border-top: 0;
        }
    </style>
</head>
<body>
    <div class="page_container">
        <div class="pdf_page size_letter">
            <div class="pdf_header">
                <div class="tax-text">Tax Search</div>
                <img src="<?php echo base_url('assets/front/images/title_point_logo.png') ?>" class="title-logo" alt="">
            </div> 
            <div class="address-bar">
                <div class="address">
                    <?php echo $city;?>, California<br>
                    <b>Searched: <?php echo $apn;?></b><br>
                    Order: 
                </div>
                <div class="tax-detail">
                    Tax Year: <span><?php echo $tax_year;?></span><br>
                    Tax Cover: <span>06/23/2023</span><br>
                    Searched By: <span>PCTXML01</span><br>
                    Searched On: <span><?php echo date('m/d/Y h:i A');?></span>
                </div>
                <div class="company-name">Company: PACIFIC COAST TITLE | GLENDALE - (FNFSTR) | 01 | CRN: 00012-00021</div>
            </div>     
            <div class="pdf-body">
                <div class="billing-address">
                    <div><span>APN:</span><b><?php echo $apn;?></b></div>
                    <div><span>Described As:</span><b><?php echo $description;?></b></div>
                    <div><span>Address:</span><b><?php echo $property_address;?></b></div>
                    <div><span>City:</span><b><?php echo $city;?></b></div>
                    <div><span>Biliing Address:</span><b><?php echo $billing_address;?></b></div>
                    <div><span>Assessed Owner(s):</span><b><?php echo $assessed_owners;?></b></div>
                    <div><span>Search As:</span><b><?php echo $SearchAsPointers;?></b></div>
                </div>
                <div class="tax-table">
                    <div class="column">
                        <div>Tax Rate Area: <b class="float-right"><?php echo $TaxRateArea;?></b></div>
                        <div class="mt-10">Use Code: <b class="float-right"><?php echo $UseCode;?></b></div>
                        <div class="text-center"><b><?php echo $UseDescription;?></b></div>
                        <div>Region Code: <b class="float-right"><?php echo $RegionCode;?></b></div>
                        <div>Flood Zone: </div>
                        <div>Zoning Code: <b class="float-right"><?php echo $ZoningCode;?></b></div>
                        <div>Taxability Code: <b class="float-right"><?php echo $TaxabilityCode;?></b></div>
                        <div class="mt-10">Tax Rate: <b class="float-right"><?php echo $TaxRate;?></b></div>
                        <div class="mt-50">Bill#:</div>
                        <div>Issue Date: <b class="float-right"><?php echo $IssueDate;?></b></div>
                    </div>
                    <div class="column">
                        <div class="text-center"><b>Value</b></div>
                        <div>Land: <b class="float-right"><?php echo $LandValuation;?></b></div>
                        <div>Improvements: <b class="float-right"><?php echo $ImprovementsValuation;?></b></div>
                        <div>Personal Property:</div>
                        <div>Fixture:</div>
                        <div>Inventory:</div>
                        <div class="text-center mt-10"><b>Exemptions</b></div>
                        <div>Homeowner: <b class="float-right"><?php echo $HomeOwnerExemption;?></b></div>
                        <div>Inventory:</div>
                        <div>Personal Property:</div>
                        <div>Religious:</div>
                        <div>All Other:</div>
                        <div class="border-top">Net Taxabie Value:<b class="float-right"><?php echo $NetTaxableValue;?></b></div>
                    </div>
                    <div class="column">
                        <div>Conveyance Date: <b class="float-right"><?php echo $ConveyanceDate;?></b></div>
                        <div>Conveying Instrument: <b class="float-right"><?php echo $ConveyingInstrument;?></b></div>
                        <div>Date Transfer Acquired:</div>
                        <div>Vesting:</div>
                        <div>Year Buiit: <b class="float-right"><?php echo $YearBuilt;?></b></div>
                        <div>Year Last Modified: <b class="float-right"><?php echo $YearLastModified;?></b></div>
                        <div class="text-center mt-49"><b>Square Footage</b></div>
                        <div>Land:</div>
                        <div>Improvements: <b class="float-right"><?php echo $ImprovementsSqFootage;?></b></div>
                        <div class="border-top">Tax Defaulted: <b class="float-right">2019</b></div>
                        <div class="border-top">Total Tax: <b class="float-right"><?php echo $TotalTax;?></b></div>                        
                    </div>
                </div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Installment</th>
                            <th>Amount</th>
                            <th>Penalty</th>
                            <th>Due Date</th>
                            <th>Status</th>
                            <th>Payment Date</th>
                            <th>Balance</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo $first_number;?></td>
                            <td><?php echo $first_amount;?></td>
                            <td><?php echo $first_penalty;?></td>
                            <td><?php echo $first_due_date;?></td>
                            <td><?php echo $first_status;?></td>
                            <td></td>
                            <td><?php echo $first_balance;?></td>
                        </tr>
                        <tr>
                            <td><?php echo $second_number;?></td>
                            <td><?php echo $second_amount;?></td>
                            <td><?php echo $second_penalty;?></td>
                            <td><?php echo $second_due_date;?></td>
                            <td><?php echo $second_status;?></td>
                            <td></td>
                            <td><?php echo $second_balance;?></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="border-top">Total Balance:</td>
                            <td class="border-top"><?php echo $TotalBalanceTaxInstallment;?></td>
                        </tr>
                    </tbody>
                </table>
                <div class="mt-15"></div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Bonds: <b>0</b></th>
                            <th>Parcel Changed: <b></b></th>
                            <th>56ld to State: <b>1</b></th>
                            <th>Mello-Roos: </th>
                            <th>NSF: <b>N</b></th>
                        </tr>
                    </thead>
                </table>
                <div class="mt-15"></div>
                <table class="table last-table">
                    <thead>
                        <tr>
                            <th>Account</th>
                            <th>Special Lien Description</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($Liens)) { 
                                foreach($Liens as $liens) { ?>
                                    <tr>
                                        <td><?php echo $liens['Account'];?></td>
                                        <td><?php echo $liens['Description'];?></td>
                                        <td><?php echo $liens['Amount'];?></td>
                                    </tr>
                                <?php } ?>
                        <?php } ?>
                    </tbody>
                </table>
            </div>  
            <div class="pdf_footer">
                <div class="footer-left">
                    <b><?php echo $apn;?></b><br>
                    <?php echo $city;?>, California
                </div>
                <div class="text-center page-number">
                    Page 1 of 1
                </div>
                <div class="footer-right">
                    Order: <br>Printed by PCTXML01 on <?php echo date('m/d/Y h:i:s A');?>
                </div>
            </div>
        </div>
            <!-- <div class="page-break" style="page-break-after: always;"></div> -->
            <!-- <div class="pdf_page size_letter" style="margin-top: 50px !important;">
                <table class="table">
                    <thead>
                        <tr>
                            <th colspan="10" class="text-center">*** DELINQUENCY INFORMATION FOR PRIOR YEARS ***</th>
                        </tr>
                        <tr class="no-border">
                            <th>Year</th>
                            <th>Parcel/Bill #</th>
                            <th>Bill Type</th>
                            <th>Bill#</th>
                            <th>Delinq Inst</th>
                            <th>Amount</th>
                            <th>Penalty</th>
                            <th>Cost/Fee</th>
                            <th>Accum lnt</th>
                            <th>Pmt Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>2018</td>
                            <td>6150-021-014-001</td>
                            <td>REG</td>
                            <td></td>
                            <td></td>
                            <td>7723.91</td>
                            <td>56.74</td>
                            <td></td>
                            <td>4634.35</td>
                            <td>UNPAID</td>
                        </tr>
                        <tr>
                            <td>2019</td>
                            <td>6150-021-014-001</td>
                            <td>REG</td>
                            <td></td>
                            <td></td>
                            <td>6762.74</td>
                            <td></td>
                            <td></td>
                            <td>3651.88</td>
                            <td>UNPAID</td>
                        </tr>
                        <tr>
                            <td>2020</td>
                            <td>6150-021-014-001</td>
                            <td>REG</td>
                            <td></td>
                            <td></td>
                            <td>5990.91</td>
                            <td></td>
                            <td></td>
                            <td>2156.73</td>
                            <td>UNPAID</td>
                        </tr>
                        <tr>
                            <td>2021</td>
                            <td>6150-021-014-001</td>
                            <td>REG</td>
                            <td></td>
                            <td></td>
                            <td>8016.96</td>
                            <td></td>
                            <td></td>
                            <td>1443.05</td>
                            <td>UNPAID</td>
                        </tr>
                    </tbody>
                </table> -->
                <!-- <table class="table text-center border-t-0">
                    <thead>
                        <tr>
                            <th colspan="10" class="text-left">Payment History</th>
                        </tr>
                        <tr class="no-border">
                            <th>Payment Date</th>
                            <th>Amount</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>02/04/2020</td>
                            <td>3574.06</td>
                            <td>APPLIED PAYMENT</td>
                        </tr>
                    </tbody>
                </table> -->
                <!-- <div class="mt-15"></div>
                <table class="table text-center">
                    <thead>
                        <tr>
                            <th colspan="10" class="text-center">*** 2023/2024 EXTENDED MONTHLY REDEMPTION SCHEDULE ***</th>
                        </tr>
                        <tr class="no-border">
                            <th>Month</th>
                            <th>Interest</th>
                            <th>Amount</th>
                            <th>Month</th>
                            <th>Interest</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Jul 2023</td>
                            <td>547.24</td>
                            <td>49,781.59</td>
                            <td>Jan 2024</td>
                            <td>3,830.69</td>
                            <td>53,065.04</td>
                        </tr>
                        <tr>
                            <td>Aug 2023</td>
                            <td>1,094.48</td>
                            <td>50,328.83</td>
                            <td>Feb 2024</td>
                            <td>4,377.93</td>
                            <td>53,612.28</td>
                        </tr>
                        <tr>
                            <td>Sep 2023</td>
                            <td>1,641.73</td>
                            <td>50,876.08</td>
                            <td>Mar 2024</td>
                            <td>4,925.18</td>
                            <td>54,159.53</td>
                        </tr>
                        <tr>
                            <td>Oct 2023</td>
                            <td>2,188.97</td>
                            <td>51,423.32</td>
                            <td>Apr 2024</td>
                            <td>5,472.42</td>
                            <td>54,706.77</td>
                        </tr>
                        <tr>
                            <td>Nov 2023</td>
                            <td>2,736.21</td>
                            <td>51,970.56</td>
                            <td>May 2024</td>
                            <td>6,019.66</td>
                            <td>55,254.01</td>
                        </tr>
                        <tr>
                            <td>Dec 2023</td>
                            <td>3,283.45</td>
                            <td>52,517.80</td>
                            <td>Jun 2024</td>
                            <td>6,566.90</td>
                            <td>55,801.25</td>
                        </tr>
                    </tbody>
                </table>
                <div class="contact-box">
                    CONTACT TITLE TAX FOR ADDITIONAL POWER TO SELL FEES<br>
                    OPEN TAX ORDER NUMBER REQUIRED FOR ADDITIONAL INFORMATION INTERIM PROCESSING ON<br>
                    PARTIAL PAYMENITS APPLIED TO DELINQUENT TAXES. CONTACT TITLE TAX FOR UPDATED REDEMPTION AMOUNTS.<br>
                    DELINQUENT CURRENT YEAR TAXES ARE INCLUDED IN THE REDEMPTION SCHEDULE
                </div>
                <div class="report-end">*** END OF REPORT ***</div>
                <div class="pdf_footer">
                    <div class="footer-left">
                        <b>6150-021-014</b><br>
                        Los Angeles, California
                    </div>
                    <div class="text-center page-number">
                        Page 2 of 2
                    </div>
                    <div class="footer-right">
                        Order: 567467456743213<br>Printed by PCTXML01 on 7/5/2023 7:06:26 PM
                    </div>
                </div> -->
        
    </div>
</body>
</html>
