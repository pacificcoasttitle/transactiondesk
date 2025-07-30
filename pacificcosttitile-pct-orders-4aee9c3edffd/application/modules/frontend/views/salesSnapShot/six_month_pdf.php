<html>
<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;900&family=Poppins:wght@400;500;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url('assets/frontend/css/sales-snap-shot/style_6.css'); ?>">
</head>
<body>
    <div class="page_container">
        <div class="pdf_page size_letter">
            <div class="pdf_header">
                <h1>SALES SNAPSHOT</h1>
                <div class="overview_text"  >6 MONTH OVERVIEW</div>
                <div class="santa_monica" style="margin-top: 0px;"><?php echo $area_name; ?></div>
                <!-- <img src="<?php echo base_url('assets/sales_snap_shot/logo.png') ?>" class="pacific_logo" alt=""> -->
            </div>
            <div class="pdf_body">
                <div class="grid">
                    <div>
                        <img src="<?php echo base_url('assets/sales_snap_shot/report.png') ?>" alt="report">
                        <h4>Total <?=$property_type?> Sales</h4>
                        <div class="count"><?php echo $total_records; ?></div>
                    </div>
                    <div>
                        <img src="<?php echo base_url('assets/sales_snap_shot/Price.png') ?>" alt="report">
                        <h4>Avg. Sales Price</h4>
                        <div class="count">$<?php echo number_format($avg_sales_price); ?></div>
                    </div>
                    <div>
                        <img src="<?php echo base_url('assets/sales_snap_shot/home.png') ?>" alt="report">
                        <h4>Avg. Price Per Sqft</h4>
                        <div class="count">$<?php echo number_format($avg_price_per_sq_ft); ?></div>
                    </div>
                </div>
                <div class="grid">
                    <div>
                        <img src="<?php echo base_url('assets/sales_snap_shot/Bed.png') ?>" alt="report">
                        <h4>Avg. Beds</h4>
                        <div class="count"><?php echo number_format($avg_beds, 1); ?></div>
                    </div>
                    <div>
                        <img src="<?php echo base_url('assets/sales_snap_shot/Bath.png') ?>" alt="report">
                        <h4>Avg. Baths</h4>
                        <div class="count"><?php echo number_format($avg_baths, 1); ?></div>
                    </div>
                    <div>
                        <img src="<?php echo base_url('assets/sales_snap_shot/Rent.png') ?>" alt="report">
                        <h4>Absentee %</h4>
                        <div class="count"><?php echo number_format($absentee, 2); ?>%</div>
                    </div>
                </div>
                <div class="monthly_conatiner" style="margin-top: 25px;">
                    <table>
                        <thead>
                            <tr>
                                <th>MONTH BY MONTH</th>
                                <th>AVG. SALES PRICE</th>
                                <th>AVG. $ SQFT</th>
                                <th>PRICE % CHANGE</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($monthly_data)) {
    foreach ($monthly_data as $month) {?>
                                    <tr>
                                        <td><?php echo $month['month']; ?></td>
                                        <td>$<?php echo number_format($month['avg_sales_price']); ?></td>
                                        <td>$<?php echo number_format($month['avg_price_per_sq_ft']); ?></td>
                                        <td><?php echo number_format($month['price_change'], 3); ?>%</td>
                                    </tr>
                            <?php }
} else {?>
                                    <tr>No Record Found</tr>
                            <?php }?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="pdf_footer monthly_conatiner">
                <div class="media-object">
                    <?php
$image_url = trim(env('AWS_PATH') . $salesRep['sales_rep_report_image']);
if (!empty($salesRep['sales_rep_report_image'])):
?>
                        <img src="<?php echo $image_url; ?>" alt="Profile-Pic" class="profile_img"/>
                    <?php endif;?>
                    <div>
                        <div class="zoe-name"><?php echo $salesRep['first_name'] . ' ' . $salesRep['last_name']; ?></div>
                        <div class="occupation"><?php echo $salesRep['title']; ?></div>
                        <div class="contact-detail">
                            <a href="tel:<?php echo $salesRep['telephone_no']; ?>"><?php echo $salesRep['telephone_no']; ?></a>
                            <a href="mailto:<?php echo $salesRep['email_address']; ?>"><?php echo $salesRep['email_address']; ?></a>
                        </div>
                    </div>
                </div>
                <!-- <div class="sales-price">
                    Report as of <?php echo date('m/d/Y'); ?> <br>
                    995K Max Sales Price <br>
                    SFR's Only
                </div> -->
            </div>
        </div>
    </div>
</body>
