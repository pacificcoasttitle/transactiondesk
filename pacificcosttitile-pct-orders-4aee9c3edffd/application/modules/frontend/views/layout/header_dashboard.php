<div id="page-preloader"><span class="spinner border-t_second_b border-t_prim_a"></span></div>
<div class="l-theme animated-css" style="height:auto;" data-header="sticky" data-header-top="200" data-canvas="container">
    
    <header class="header header-topbar-hidden header-boxed-width navbar-fixed-top header-background-trans header-color-white header-logo-white header-navibox-1-left header-navibox-2-right header-navibox-3-right header-navibox-4-right">
        <div class="container container-boxed-width">
            <nav class="navbar" id="nav">
                <div class="container">
                    <div class="header-navibox-1">
                        <button class="menu-mobile-button visible-xs-block js-toggle-mobile-slidebar toggle-menu-button"><i class="toggle-menu-button-icon"><span></span><span></span><span></span><span></span><span></span><span></span></i></button>
                        <a class="navbar-brand scroll" href="<?php echo base_url(); ?>dashboard"><img class="normal-logo" src="<?php echo base_url(); ?>assets/media/general/logo2.png" alt="logo"><img class="scroll-logo hidden-xs" src="<?php echo base_url(); ?>assets/media/general/logo2-dark.png" alt="logo"></a>
                    </div>
                    
                    <?php if(!isset($mail_dashboard)) {?>
                        <div class="header-navibox-2">
                            <ul class="yamm nav navbar-nav">
                                <?php  $userdata = $this->session->userdata('user');
                                if($userdata['is_escrow_officer'] == 0 && $userdata['is_escrow_assistant'] == 0 && $userdata['is_special_lender'] == 0 && $userdata['is_sales_rep'] == 0 && $userdata['is_title_officer'] == 0 && $userdata['is_payoff_user'] == 0) { ?>
                                    <li><a href="<?php echo base_url(); ?>dashboard">Dashboard Home</a></li>
                                    <li><a href="<?php echo base_url().'order'; ?>">Open Order</a></li>
                                    <li><a href="<?php echo base_url().'cpl-dashboard'; ?>">Generate CPL</a></li>
                                    <li><a href="<?php echo base_url().'proposed-insured'; ?>">Proposed Insured</a></li>
                                    <!-- <li><a href="<?php // echo base_url().'prelim-files'; ?>">Review Prelims</a></li> -->

                                    <?php if (!empty($userdata) && isset($userdata['is_master']) && $userdata['is_master'] == 1) { ?>
                                        <li><a href="<?php echo base_url('reports'); ?>">Reports</a></li>   
                                    <?php } ?>
                                         
                                <?php } else {
                                    if($userdata['is_sales_rep']  == 1) { ?>
                                        <li><a href="<?php echo base_url(); ?>sales-dashboard/<?php echo $userdata['id']; ?>">Dashboard Home</a></li>
                                        <li><a href="<?php echo base_url(); ?>sales-production-history/<?php echo $userdata['id'];?>">Production History</a></li>
                                        <li><a href="<?php echo base_url(); ?>trends/<?php echo $userdata['id'];?>">Trends</a></li>
                                        <li><a href="<?php echo base_url(); ?>sales-summary/<?php echo $userdata['id'];?>">Summary</a></li>
                                    <?php } else if($userdata['is_title_officer'] == 1)  { ?>
                                        <li><a href="<?php echo base_url(); ?>title-officer-dashboard">Dashboard Home</a></li>
                                        <li><a href="<?php echo base_url().'cpl-dashboard'; ?>">Generate CPL</a></li>
                                        <li><a href="<?php echo base_url().'proposed-insured'; ?>">Proposed Insured</a></li>
                                        <li><a href="<?php echo base_url().'prelim-files'; ?>">Review Prelims</a></li>
                                    <?php }  else if($userdata['is_payoff_user'] == 1)  { ?>
                                        <li><a href="<?php echo base_url(); ?>pay-off-dashboard">Dashboard Home</a></li>
                                    <?php } else if ($userdata['is_escrow_officer'] == 1 || $userdata['is_escrow_assistant'] == 1) {?>
                                        <li><a href="<?php echo base_url(); ?>escrow-dashboard">Dashboard Home</a></li>
                                    <?php } ?>
                                <?php } ?>
                                <li><a href="<?php echo base_url().'logout'; ?>">Logout</a></li>   
                            </ul>
                        </div>
                    <?php }?>
                </div>
            </nav>
        </div>
    </header>

    <div class="section-title-page7q area-bg area-bg_blue area-bg_op_60 parallax">
        <div class="area-bg__inner">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <h1 class="b-title-page"></h1>
                        <div class="b-title-page__info"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
        