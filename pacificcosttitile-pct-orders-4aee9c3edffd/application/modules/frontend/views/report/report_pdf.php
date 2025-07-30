<!DOCTYPE html>
<html lang="en">
<head>
    <title>Document</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800;900&family=Roboto:wght@300;400;500;700;900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url('assets/frontend/css/report/style.css');?>">

</head>
<body>
    <page class="sales_activity">
            <div class="sales_activity_hero">
                <div class="d-flex">
                    <div class="col-60">
                        <h1>FARM AREA ANALYSIS</h1>
                        <div class="orange_line"></div>
                        <h2>EDDM CARRIER ROUTES</h2>
                    </div>
                    <div class="col-40">
                        <div class="media">
                            <img src="<?php echo base_url('assets/media/reports/Large-Flat-Symbol.png') ?>" alt="Large-Flat-Symbol" class="img-fluid" width="50">
                            <div class="media-body">
                                PACIFIC COAST
                                <span>TITLE COMPANY</span>
                            </div>                        
                        </div>
                    </div>
                </div>
            </div>



            <div class="market_update_table">
                <h4 class="table_title">AREA: | <span><?php echo $area_name ?></span></h4>
                <div class="d-flex text-center my-20">
                    <div class="col-30 border-right border-bottom">
                        <span class="number green_number"><?php echo $box_data['turnover_rate']['value'] ?>% </span>
                        <h4 class="table_title"><span>ROUTE: <?php echo $box_data['turnover_rate']['route'] ?> <br>HIGHEST TURNOVER RATIO</span></h4>
                    </div>
                    <div class="col-30  border-right border-bottom">
                        <span class="number purple_number"><?php echo $box_data['NOO_ratio']['value'] ?>%</span>
                        <h4 class="table_title"><span>ROUTE: <?php echo $box_data['turnover_rate']['route'] ?> <br>HIGHEST NON-OWNER</span></h4>
                    </div>
                    <div class="col-30  border-bottom">
                        <span class="number equa_number"><?php echo $box_data['avg_yr_owned']['value'] ?></span>
                        <h4 class="table_title"><span>ROUTE: <?php echo $box_data['avg_yr_owned']['route'] ?> <br>LONG AVG YR OWNED</span></h4>
                    </div>
                    <div class="col-30  border-right">
                        <span class="number red_number"><?php echo $box_data['total_units']['value'] ?></span>
                        <h4 class="table_title"><span>ROUTE: <?php echo $box_data['total_units']['route'] ?> <br>MOST UNITS</span></h4>
                    </div>
                    <div class="col-30  border-right">
                        <span class="number blue_number"><?php echo $box_data['total_sales']['value'] ?></span>
                        <h4 class="table_title"><span>ROUTE: <?php echo $box_data['total_sales']['route'] ?> <br>MOST SALES</span></h4>
                    </div> 
                    <?php
                    $num = $avg_price = $box_data['avg_price']['value'];
                    $units = ['', 'K', 'M', 'B', 'T'];
                    for ($i = 0; $num >= 1000; $i++) {
                        $num /= 1000;
                    }
                    $avg_price = round($num, 1) . $units[$i];
                    ?>
                    <div class="col-30">
                        <span class="number yellow_number"><?php echo $avg_price ?></span>
                        <h4 class="table_title"><span>ROUTE: <?php echo $box_data['avg_price']['route'] ?> <br>AVG. SALES PRICE ALL</span></h4>
                    </div>
                </div>
                 
                <h4 class="table_title">TOP 10 CARRIER ROUTES | <span>BY <?php echo strtoupper($sorting_fields[$sort_by]); ?></span></h4>
                <table>
                    <tr>
                        <th>Route</th>
                        <th>Avg. $</th>
                        <th>#Of Sales</th>
                        <th>NOO %</th>
                        <th>Avg. Y.O.</th>
                        <th># of Units</th>
                        <th>T.O.%</th>
                    </tr>
                    <?php
                    foreach ($records as $key => $record) { ?>
                        <tr>
                            <td><?php echo separateZipRoute($record['carrier_route'],$record["sa_site_zip"]) ?></td>
                            <td>$<?php echo number_format($record['avg_price'])  ?></td>
                            <td><?php echo $record['total_sales'] ?></td>
                            <td><?php echo $record['NOO_ratio'] ?></td>
                            <td><?php echo $record['avg_yr_owned'] ?></td>
                            <td><?php echo $record['total_units'] ?></td>
                            <td><?php echo $record['turnover_rate'] ?></td>
                        </tr>
                        
                    <?php 
                    }
                    ?>
                </table>
            </div>


            <div class="footer">
                <div class="d-flex">
                    <div class="signature horizontal_sign">
                    <?php
                    $image_url = trim(env('AWS_PATH').$salesRep['sales_rep_report_image']);
                    if (!empty($salesRep['sales_rep_report_image']) && checkRemoteFile($image_url)):
                    ?>
                    <img src="<?php echo $image_url;?>" alt="Profile-Pic" class="profile_img"/>
                    <?php endif; ?>

                            <!-- <img src="https://i.ibb.co/z7QknKX/Zoe-Noelle.jpg" alt="Zoe-Noelle" class="profile_img"> -->
                        <div>
                            <div class="profile_name"><?php echo $salesRep['first_name'].' '.$salesRep['last_name']; ?></div>
                            <div class="profile_title"><?php echo $salesRep['title'];?></div>
                            <a class="tel_number phone_no" href="tel:<?php echo $salesRep['telephone_no'];?>"><?php echo $salesRep['telephone_no'];?></a>
                            <a href="mailto:<?php echo $salesRep['email_address'];?>" class="tel_number"><?php echo $salesRep['email_address'];?></a>
                        </div>
                    </div>
                    <div class="logo">
                        CUSTOMER SERVICE<br>
                        <a href="tel:(866) 724-1050">(866) 724-1050</a> | <a href="mailto:cs@pct.com">cs@pct.com</a>
                    </div>
                </div>
            </div>
    </page>
</body>
</html>
