<div id="page-preloader"><span class="spinner border-t_second_b border-t_prim_a"></span></div>
<div class="l-theme animated-css" style="height:auto;" data-header="sticky" data-header-top="200" data-canvas="container">
    
    <header class="header header-topbar-hidden header-boxed-width navbar-fixed-top header-background-trans header-color-white header-logo-white header-navibox-1-left header-navibox-2-right header-navibox-3-right header-navibox-4-right">
        <div class="container container-boxed-width">
            <nav class="navbar" id="nav">
                <div class="container">
                    <div class="header-navibox-1">
                        <button class="menu-mobile-button visible-xs-block js-toggle-mobile-slidebar toggle-menu-button"><i class="toggle-menu-button-icon"><span></span><span></span><span></span><span></span><span></span><span></span></i></button>
                        <a class="navbar-brand scroll" style="margin-right: 90px !important;" href="<?php echo base_url(); ?>dashboard"><img class="normal-logo" src="<?php echo base_url(); ?>assets/media/general/logo2.png" alt="logo"><img class="scroll-logo hidden-xs" src="<?php echo base_url(); ?>assets/media/general/logo2-dark.png" alt="logo"></a>
                    </div>
                    
                    <?php if(!isset($mail_dashboard)) {?>
                        <div class="header-navibox-2">
                            <ul class="nav navbar-nav">
                                <li class="user-notifications">
                                    <a style="top: -5px;" class="nav-link dropdown-toggle" href="#" id="notificationDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-bell fa-fw" style="font-size:26px;"></i>
                                        <?php if ($unreadNotificationCount['total_unread_count'] > 0 ) { ?>
                                            <span class="badge badge-danger badge-counter" data-count="<?php echo $unreadNotificationCount['total_unread_count'];?>"><?php echo $unreadNotificationCount['total_unread_count'];?></span>
                                        <?php } else { ?>
                                            <span class="badge badge-danger badge-counter d-none" data-count="<?php echo $unreadNotificationCount['total_unread_count'];?>"><?php echo $unreadNotificationCount['total_unread_count'];?></span>
                                        <?php } ?>
                                    </a>

                                    <div class="dropdown-list dropdown-menu shadow animated--grow-in" aria-labelledby="notificationDropdown">
                                        <h6 class="dropdown-header">
                                            Notification Center
                                        </h6>
                                        <div class="slimscroll notification-item-list" style="overflow-y: auto;overflow-x:hidden; max-height:318px;">
                                        <?Php if(!empty($notifications)) {
                                                foreach($notifications as $notification) {
                                                    $alertClass = '';
                                                    $iconClass = '';
                                                    if ($notification['type'] == 'approved' || $notification['type'] == 'completed') {
                                                        $alertClass = 'bg-success';
                                                        $iconClass = 'fa-check';
                                                    } else if ($notification['type'] == 'denied') {
                                                        $alertClass = 'bg-danger';
                                                        $iconClass = 'fa-ban';
                                                    } else if ($notification['type'] == 'added' || $notification['type'] == 'assigned' || $notification['type'] == 'created') {
                                                        $alertClass = 'bg-warning';
                                                        $iconClass = 'fa-exclamation-triangle';
                                                    }
                                                    ?>
                                                    <a class="dropdown-item d-flex align-items-center" href="#">
                                                        <div class="mr-3">
                                                            <div class="icon-circle <?php echo $alertClass;?>">
                                                                <i class="fa <?php echo $iconClass;?> text-white"></i>
                                                            </div>
                                                        </div>
                                                        <div style="width: max-content;color:#333 !important;">
                                                            <div class="small text-gray-500"><?php echo date('F d, Y', strtotime($notification['created_at']));?></div>
                                                            <?php echo $notification['message'];?>
                                                        </div>
                                                    </a>
                                                <?php } ?>
                                            <?php } else { ?>
                                                <a class="dropdown-item d-flex align-items-center" href="#">
                                                    
                                                    <div style="width: 44rem!important;">
                                                        <span style="color:#333 !important;" class="font-weight-bold">No new notifications found</span>
                                                    </div>
                                                </a>
                                            <?php } ?>	
                                        </div>
                                        <a class="dropdown-item text-center small text-gray-500" href="<?php echo base_url().'hr/notifications'; ?>">Show All Notification</a>
                                    </div>
                                </li> 
                                <?php  $userdata = $this->session->userdata('user');
                                if($userdata['is_escrow_officer'] == 0 && $userdata['is_escrow_assistant'] == 0 && $userdata['is_special_lender'] == 0 && $userdata['is_sales_rep'] == 0 && $userdata['is_title_officer'] == 0 && $userdata['is_payoff_user'] == 0) { ?>
                                    <li><a href="<?php echo base_url(); ?>dashboard">Dashboard Home</a></li>
                                    <li><a href="<?php echo base_url().'order'; ?>">Open Order</a></li>
                                    <li><a href="<?php echo base_url().'cpl-dashboard'; ?>">CPL</a></li>
                                    <li><a href="<?php echo base_url().'proposed-insured'; ?>">Proposed Insured</a></li>
                                    <!-- <li><a href="<?php // echo base_url().'prelim-files'; ?>">Review Prelims</a></li> -->

                                    <?php if (!empty($userdata) && isset($userdata['is_master']) && $userdata['is_master'] == 1) { ?>
                                        <li><a href="<?php echo base_url('reports'); ?>">Reports</a></li>   
                                    <?php } ?>
                                         
                                <?php } else {
                                    if($userdata['is_sales_rep']  == 1) { ?>
                                        <li><a href="<?php echo base_url(); ?>sales-dashboard/<?php echo $userdata['id']; ?>">Dashboard Home</a></li>
                                        <li><a href="<?php echo base_url(); ?>sales-current-month-history">Daily</a></li>
                                        <li><a href="<?php echo base_url(); ?>sales-production-history/<?php echo $userdata['id'];?>">Production History</a></li>
                                        <li><a href="<?php echo base_url(); ?>trends/<?php echo $userdata['id'];?>">Trends</a></li>
                                        <li><a href="<?php echo base_url(); ?>sales-summary/<?php echo $userdata['id'];?>">Summary</a></li>
                                        <!-- <li><a href="<?php echo base_url(); ?>sales-commission/<?php echo $userdata['id'];?>">Commission</a></li> -->
                                    <?php } else if($userdata['is_title_officer'] == 1)  { ?>
                                        <li><a href="<?php echo base_url(); ?>title-officer-dashboard">Dashboard Home</a></li>
                                        <li><a href="<?php echo base_url().'cpl-dashboard'; ?>">CPL</a></li>
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
        