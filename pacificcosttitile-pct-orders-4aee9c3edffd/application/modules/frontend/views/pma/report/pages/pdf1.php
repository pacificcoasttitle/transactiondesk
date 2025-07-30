
    <page class="pdf1 text-center">
        <img src="<?php echo base_url('assets/pma/report/img/logo.png');?>" alt="line">
        <img src="<?php echo base_url('assets/pma/report/img/hero.png');?>" alt="hero" class="hero_img">
        <h1 class="concierge_title">Concierge Property Profile</h1>
        <div class="gray_title">Prepared For:</div>
        <div class="address_box">
            <div class="address_heading"> <?php echo $realtorName; ?></div><span><?php echo $realtorCompany; ?></span> <?php echo $realtorAddress; ?>
        </div>
        <div class="gray_title">PROPERTY ADDRESS:</div>
        <div class="address2"><?php echo $main_report->PropertyProfile->SiteAddress; ?> <?php echo $main_report->PropertyProfile->SiteUnitType; ?> <?php echo $main_report->PropertyProfile->SiteUnit; ?><br> <?php echo (!empty($main_report->PropertyProfile->SiteCity)) ? $main_report->PropertyProfile->SiteCity .', ':'' ?><?php echo $main_report->PropertyProfile->SiteState; ?> <?php echo $main_report->PropertyProfile->SiteZip; ?> </div>
    </page>
