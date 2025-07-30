<html>
<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <style>
        body{
            font-family: 'Montserrat', sans-serif;
            margin: 0;
            padding: 0;
        }
        *{
            box-sizing: border-box;
        }
        table{
            border-collapse: collapse;
        }
        .pdf_page {
            margin: 0 auto;
            box-sizing: border-box;
            background-color: #fff;
            color: #333;
            position: relative;
        }
        .h100{
            height: 100;;
        }
        .size_letter {
            width: 8.5in;
            height: 11in;
        }
        .w100{
            width: 100%;
        }
        .w33{
            width: 33%;
        }
        .w67{
            width: 67%;
        }
        .w60{
            width: 60%;
        }
        .w30{
            width: 30%;
        }
        .w70{
            width: 70%;
        }
        .w50{
            width: 50%;
        }
        .w20{
            width: 20%;
        }
        .w40{
            width: 40%;
        }
        .w10{
            width: 10%;
        }
        .home-sales{
            font-family: 'Poppins',sans-serif;
            font-size: 24px;
            font-weight: 700;
            color: #2c2e35;
            margin-top:15px;
        }
        .report-sales{
            font-size: 18px;
            font-weight: 300;
            font-family: 'Open sans' sans-serif;
            margin-bottom:15px;
        }
        .pr-5{
            padding-right: 5px;
        }
        .pr-20{
            padding-right: 20px;
        }
        .pl-25 {
            padding-left: 25px;
        }
        .pr-25 {
            padding-right: 25px;
        }
        .orange-bar {
            background-color: #f16a2a;
            padding: 30px 20px;
            height: calc(100% - 210px);
            position: relative;
            /* padding-top: 140px; */
            min-height: 667px;
        }
        .recentsale b {
            display: block;
            font-weight: 900;
            font-size: 65px;
        }
        .recentsale {
            /* writing-mode: vertical-rl; */
            color: #fff;
            font-size: 55px;
            text-transform: uppercase;
            transform: rotateZ(270deg);
            -webkit-transform: rotate(270deg);
            white-space: nowrap;
            /* transform:scaleX(-1); */

            /* position: absolute; */
            top: auto;
            bottom: 30px;
            left: 5px;




            color: #fff;
            font-size: 55px;
            text-transform: uppercase;
            transform: rotateZ(270deg);
            white-space: nowrap;
            position: absolute;
            top: auto;
            bottom: 160px;
            left: -85;
            line-height: 50px;
        }
        .bg-grey{
            background-color: #9fa0a3;
            color: #fff;
            font-weight: 700;
        }
        .data-table {
            font-family: 'Calibri';
            font-size: 20px;
            color: #2c2e35;
            text-align: center;
        }
        .bg-black{
            background-color: #091932;
            color: #fff;
            font-weight: 300;
        }
        .data-table thead {
            font-size: 20px;
        }
        .data-table tbody {
            font-size: 16px;
        }
        .data-table tbody td {
            border: 1px solid #dfe0e1;
        }
        .text-orange, .text-grey{
            color: #d35400;
            font-family: 'Montserrat', sans-serif;
            font-size: 18px;
            font-weight: 500;
            line-height: 18px;
        }
        .text-grey{
            color: #707176;
        }
        .employee-name{
            font-size: 30px;
            line-height: 30px;
        }
        .sales_rep_profile {
            height: 136px;
            width: 136px;
            border-radius: 50%;
        }
        /* .footer {
            width: 100%;
            height: 145px;
            position: fixed;
            bottom: 0;
            max-width: 8.5in;
        }
        .container {
            height: calc(100% - 145px);;
        } */
        @media print {
            .page-break-class {page-break-after: always;}
        }
    </style>
</head>
<body>
    <div class="page_container">
    <?php foreach ($records as $k => $record) {?>
        <div class="pdf_page size_letter">
            <table class="w100">
                <tr>
                    <td class="w33 pr-20 h100">
                        <img src="<?php echo base_url() . 'assets/frontend/images/sales_activity_building.png' ?>" alt="..." class="w100">
                        <div class="orange-bar">
                            <div class="recentsale">
                                Recent Sales
                                <b><?=$monthName;?></b>
                                <?=date('Y');?>
                            </div>
                        </div>
                    </td>
                    <td class="w67" valign="top">
                        <table class="w100">
                            <tr>
                                <td align="right">
                                    <a href="#"><img src="<?php echo base_url() . 'assets/frontend/images/sales_activity_logo.png' ?>" alt=""></a>
                                </td>
                            </tr>
                            <tr>
                                <td align="left">
                                    <div class="home-sales"><?=$country;?> County Home Sale Activity</div>
                                    <div class="report-sales">This report includes resale of single family residences, <br> condos, and new homes.</div>
                                </td>
                            </tr>
                        </table>
                        <table class="w100 data-table">
                            <thead>
                                <tr>
                                    <td></td>
                                    <td colspan="2" class="bg-grey">SFR’s</td>
                                    <td colspan="2" class="bg-grey">Condos</td>
                                </tr>
                                <tr>
                                    <td class="bg-black">City</td>
                                    <td class="bg-black"># Sold</td>
                                    <td class="bg-black">Median $</td>
                                    <td class="bg-black"># Sold</td>
                                    <td class="bg-black">Median $</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($record as $key => $val) {?>
                                <tr>
                                    <td align="left" ><?=$key;?></td>
                                    <td><?=$val['SFR']['count'];?></td>
                                    <td>$<?=number_format($val['SFR']['avgSalePrice']);?></td>
                                    <td><?=$val['Condos']['count'];?></td>
                                    <td>$<?=number_format($val['Condos']['avgSalePrice']);?></td>
                                </tr>
                                <?php }?>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </table>
            <table class="w100">
                <tr><td style="height: 20px;"></td></tr>
            </table>
            <table class="w100">
                <tr>
                    <td class="w50">
                        <table class="w100">
                            <tr>
                            <?php if (isset($salesRep['sales_rep_report_image']) && !empty($salesRep['sales_rep_report_image'])) {
    if (env('AWS_ENABLE_FLAG') == 1) {
        $img = env('AWS_PATH') . (!empty($salesRep['sales_rep_report_image']) ? $salesRep['sales_rep_report_image'] : str_replace('uploads/', '', $salesRep['sales_rep_report_image']));
        // $salesRep['sales_rep_report_image'] = str_replace('uploads/', '', $salesRep['sales_rep_report_image']);
        // $img = env('AWS_PATH') . $salesRep['sales_rep_report_image'];
    } else {
        $img = base_url() . $salesRep['sales_rep_report_image'];
    }
}?>
<?php
$imageExistFlag = false;
    if (isset($img) && !empty($img)) {
        $imageExistFlag = true;?>
    <td class="w30 pl-25 pr-5">
        <img class="sales_rep_profile" src="<?php echo $img; ?>" class="w100" alt="">
    </td>
<?php
}?>
                                <td class="w70 <?php (!$imageExistFlag) ? 'pl-25' : '';?>">
                                    <div class="employee-name text-grey"><b><?php echo $salesRep['first_name'] . ' ' . $salesRep['last_name']; ?></b></div>
                                    <div class="text-orange"><?php echo $salesRep['title']; ?></div>
                                    <div class="text-grey"><b><?php echo $salesRep['telephone_no']; ?></b></div>
                                    <div class="text-grey"><?php echo $salesRep['email_address']; ?></div>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <!-- <td class="w10"></td> -->
                    <td class="w40 pr-25" align="right">
                        <div class="text-orange">CUSTOMER SERVICE</div>
                        <div class="text-grey">(866) 724-1050 | cs@pct.com</div>
                        <div class="text-orange">OPEN ORDERS</div>
                        <div class="text-grey">openorders@pct.com</div>
                    </td>
                </tr>
            </table>
        </div>
        <?php if ($k < (count($records) - 1)) {?>
        <div class="page-break-class" style="page-break-after: always;" ></div>
        <?php }?>
    <?php }?>
    </div>
</body>
