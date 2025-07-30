<?php
    include dirname(__FILE__).'/config/config.php';
?>
<!DOCTYPE html>
<html lang="en">
    <head>
	    <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>CPL | Pacific Coast Title Company</title>
        <meta content="We specialize in Residential, Commercial Title & Escrow Services" name="description">
        <meta content="" name="keywords">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="telephone=no" name="format-detection">
        <meta name="HandheldFriendly" content="true">
        <link rel="stylesheet" href="http://www.pct.com/assets/css/master.css">
        <link rel="icon" href="<?php echo BASE_URL; ?>images/favicon.ico" type="image/x-icon">
  
  <!--[if lt IE 9 ]>
<script src="/assets/js/separate-js/html5shiv-3.7.2.min.js" type="text/javascript"></script><meta content="no" http-equiv="imagetoolbar">
<![endif]-->

        
        
        <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>css/smart-forms.css">
        <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>css/font-awesome.min.css">
        <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>css/jquery-ui.css">

        <!-- JS Files -->
        <script type="text/javascript" src="<?php echo BASE_URL; ?>js/jquery-1.9.1.min.js"></script>        
        <script type="text/javascript" src="<?php echo BASE_URL; ?>js/jquery-ui.min.js"></script>
        <script type="text/javascript" src="<?php echo BASE_URL; ?>js/jquery.form.min.js"></script>
        <script type="text/javascript" src="<?php echo BASE_URL; ?>js/jquery.validate.min.js"></script>
        <script type="text/javascript" src="<?php echo BASE_URL; ?>js/additional-methods.min.js"></script>
        <script type="text/javascript" src="<?php echo BASE_URL; ?>js/smart-form.js"></script>  
        <script type="text/javascript" src="<?php echo BASE_URL; ?>js/en/jquery-cloneya.min.js"></script>
        <!-- JS Files -->
       
        
        <!--[if lte IE 9]>
            <script type="text/javascript" src="http://www.pct.com/order/js/jquery.placeholder.min.js"></script>
        <![endif]-->    
        
        <!--[if lte IE 8]>
            <link type="text/css" rel="stylesheet" href="http://wwwwpct.com/order/css/smart-forms-ie8.css">
        <![endif]-->
		
    </head>
    
    <body class="">
	   <!-- Loader-->
    <div id="page-preloader"><span class="spinner border-t_second_b border-t_prim_a"></span></div>
    <!-- Loader end-->
    <div class="l-theme animated-css" data-header="sticky" data-header-top="200" data-canvas="container">
        <!-- ==========================-->
        <!-- SEARCH MODAL-->
        <!-- ==========================-->
        <!-- <div class="header-search open-search">
            <div class="container">
                <div class="row">
                    <div class="col-sm-8 col-sm-offset-2 col-xs-10 col-xs-offset-1">
                        <div class="navbar-search">
                            <form class="search-global">
                                <input class="search-global__input" type="text" placeholder="Type to search" autocomplete="off" name="s" value="">
                                <button class="search-global__btn"><i class="icon stroke icon-Search"></i></button>
                                <div class="search-global__note">Begin typing your search above and press return to search.</div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <button class="search-close close" type="button"><i class="fa fa-times"></i></button>
        </div>  -->
        <!-- ==========================-->
        <!-- MOBILE MENU-->
        <!-- ==========================-->
        <div data-off-canvas="mobile-slidebar left overlay">
            <ul class="yamm nav navbar-nav">
               <li><a href="<?php echo BASE_URL_MAIN; ?>index.html">Home</a></li>
                               <li class="dropdown"><a class="dropdown-toggle" href="#" data-toggle="dropdown">About Us<b class="caret"></b>
                          <!-- Classic Dropdown--></a>
                                    <ul class="dropdown-menu">
                                         <li><h4>How We Help</h4></li>
										<li><a href="our-role.html">Our Role in Title</a></li>
										<li><a href="protecting-you.html">Protecting You</a></li>
										<li><a href="why-pacific-coast-title.html">Why Pacific Coast Title</a></li>
										<li><h4>About Us</h4></li>
										<li><a href="about-us.html">About Our Company</a></li>
										<li><a href="assets/downloads/PacificCoastTitle-FinancialStrength.pdf">Financial Strength</a></li>
										<li><a href="join-our-team.html">Join our Team</a></li>
                                        
                                    </ul>
                                </li>
                                <li class="dropdown"><a class="dropdown-toggle" href="#" data-toggle="dropdown">Residential<b class="caret"></b>
                          <!-- Classic Dropdown--></a>
                                    <ul class="dropdown-menu">
                                        <li><h4>Our Services</h4></li>
                                        <li><a href="residential-title.html">Residential Title</a></li>
                                        <li><a href="escrow-settlement.html">Escrow Settlement</a></li>
										 <li><h4>About Title</h4></li>
										 <li><a href="what-is-title-insurance.html">What Is Title Insurance</a></li>
										<li><a href="benefits-title-insurance.html">Benefits of Title Insurance</a></li>
										<li><a href="life-of-title-search.html">Life of a Title Search</a></li>
										<li><a href="top-10-title-problems.html">Top 10 Title Concerns</a></li>
										<li><h4>About Escrow</h4></li>
										<li><a href="what-is-escrow.html">What is Escrow</a></li>
										<li><a href="life-of-escrow.html">Life of an Escrow</a></li>
										<li><a href="escrow-terms.html">Escrow Terms</a></li>
                                    </ul>
                                </li>
								 <li class="dropdown"><a class="dropdown-toggle" href="#" data-toggle="dropdown">Commercial<b class="caret"></b>
                          <!-- Classic Dropdown--></a>
                                    <ul class="dropdown-menu">
                                       <li><a href="commercial-services.html">Services</a></li>
                                        <li><a href="commercial-expertise.html">Expertise</a></li>
                                        <li><a href="commercial-resources.html">Resources</a></li>
                                    </ul>
                                </li>
								
                                <li class="dropdown"><a class="dropdown-toggle" href="#" data-toggle="dropdown">Agent Resources<b class="caret"></b>
                          <!-- Classic Dropdown--></a>
                                    <ul class="dropdown-menu">
										 <li><h4>Forms & Flyers</h4></li>
                                        <li><a href="blank-forms.html">Blank Forms</a></li>
										<li><a href="educational-booklets.html">Educational Booklets</a></li>
										<li><a href="flyer-center.html">Flyer Center</a></li>
										 <li class="divider"></li>
										 <li><h4>Rates & Fees</h4></li>
										<li><a href="http://www.pct.com/calculator/">Rate Calculator</a></li>
										<li><a href="http://www.pct.com/calculator/index.php?welcome/signup">Lender Rate Portal</a></li>
										<li><a href="recording-fees.html">Recording Fees</a></li>
										<li><a href="assets/downloads/2019-RecordersCalendar.pdf">Recorders Holidays</a></li>
										<li><a href="assets/downloads/TransferTaxes.pdf">Transfer Tax Info</a></li>
										<li><a href="rate-book.html">Rate Book</a></li>
										 <li class="divider"></li>
										  <li><h4>Tools & Video</h4></li>
										<li class="dropdown"><a class="dropdown-toggle" href="#" data-toggle="dropdown">Agent Tools<b style="color:#d35411;" class="caret"></b>
                          <!-- Classic Dropdown--></a>
										<ul class="dropdown-menu">
										
                                        <li><a href="http://www.pacificcoastagent.com/">Pacific Coast Agent</a></li>
										<li><a href="https://www.pcttitletoolbox.com/#!/">PCT Title Toolbox</a></li>
										<li><a href="#">Instant Profile</a></li>
										<li><a href="http://www.pct247.com/">PCT247.com</a></li>
										
										 </ul>
                                </li>
								<li><a href="training-center.html">Training center</a></li>
                                    </ul>
									
                                </li>
                                <li class="dropdown"><a class="dropdown-toggle" href="contact.html" data-toggle="dropdown">Contact<b class="caret"></b>
                          <!-- Classic Dropdown--></a>
                                    <ul class="dropdown-menu">
									
                                        <li><a href="downey.html">Downey</a></li>
                                        <li><a href="glendale.html">Glendale</a></li>
                                        <li><a href="orange.html">Orange</a></li>
										<li><a href="oxnard.html">Oxnard</a></li>
                                        <li><a href="sandiego.html">San Diego</a></li>
                                        
                                    </ul>
                                </li>
            </ul>
            </ul>
        </div>
        <!-- ==========================-->
        <!-- FULL SCREEN MENU-->
        <!-- ==========================-->
      <!--  <div class="wrap-fixed-menu" id="fixedMenu">
            <nav class="fullscreen-center-menu">

                <div class="menu-main-menu-container">

                    <ul class="nav navbar-nav">

                        <li><a href="index.html" >Home</a></li>
                        <li><a href="about-us.html">About</a></li>
                        <li><a href="blank-forms.html" >Blank Forms</a></li>
                        <li><a href="information-flyers.html">Informational Flyers</a> </li>
                        <li><a href="educational-booklets.html">Educational Booklets</a></li>
                        <li><a href="contact.html" >Branches</a>
                        </li>

                    </ul>
                </div>
            </nav>
            <button type="button" class="fullmenu-close"><i class="fa fa-times"></i></button>
        </div> -->

        <header class="header header-topbar-hidden header-boxed-width navbar-fixed-top header-background-trans header-color-white header-logo-white header-navibox-1-left header-navibox-2-right header-navibox-3-right header-navibox-4-right">
            <div class="container container-boxed-width">
                
                <nav class="navbar" id="nav">
                    <div class="container">
                        <div class="header-navibox-1">
                            <!-- Mobile Trigger Start-->
                            <button class="menu-mobile-button visible-xs-block js-toggle-mobile-slidebar toggle-menu-button"><i class="toggle-menu-button-icon"><span></span><span></span><span></span><span></span><span></span><span></span></i></button>
                            <!-- Mobile Trigger End-->
                            <a class="navbar-brand scroll" href="<?php echo BASE_URL_MAIN; ?>index.html"><img class="normal-logo" src="http://www.pct.com/assets/media/general/logo2.png" alt="logo"><img class="scroll-logo hidden-xs" src="http://www.pct.com/assets/media/general/logo2-dark.png" alt="logo"></a>
                        </div>
                        <div class="header-navibox-2">
                            <ul class="yamm main-menu nav navbar-nav">
                                <li><a href="<?php echo BASE_URL_MAIN; ?>index.html">Home</a></li>
                               <li class="dropdown"><a class="dropdown-toggle" href="#" data-toggle="dropdown">About Us<b class="caret"></b>
                          <!-- Classic Dropdown--></a>
                                    <ul class="dropdown-menu">
                                         <li><h4>How We Help</h4></li>
										<li><a href="our-role.html">Our Role in Title</a></li>
										<li><a href="protecting-you.html">Protecting You</a></li>
										<li><a href="why-pacific-coast-title.html">Why Pacific Coast Title</a></li>
										<li><h4>About Us</h4></li>
										<li><a href="about-us.html">About Our Company</a></li>
										<li><a href="assets/downloads/PacificCoastTitle-FinancialStrength.pdf">Financial Strength</a></li>
										<li><a href="join-our-team.html">Join our Team</a></li>
                                        
                                    </ul>
                                </li>
                                <li class="dropdown"><a class="dropdown-toggle" href="#" data-toggle="dropdown">Residential<b class="caret"></b>
                          <!-- Classic Dropdown--></a>
                                    <ul class="dropdown-menu">
                                        <li><h4>Our Services</h4></li>
                                        <li><a href="residential-title.html">Residential Title</a></li>
                                        <li><a href="escrow-settlement.html">Escrow Settlement</a></li>
										 <li><h4>About Title</h4></li>
										 <li><a href="what-is-title-insurance.html">What Is Title Insurance</a></li>
										<li><a href="benefits-title-insurance.html">Benefits of Title Insurance</a></li>
										<li><a href="life-of-title-search.html">Life of a Title Search</a></li>
										<li><a href="top-10-title-problems.html">Top 10 Title Concerns</a></li>
										<li><h4>About Escrow</h4></li>
										<li><a href="what-is-escrow.html">What is Escrow</a></li>
										<li><a href="life-of-escrow.html">Life of an Escrow</a></li>
										<li><a href="escrow-terms.html">Escrow Terms</a></li>
                                    </ul>
                                </li>
								 <li class="dropdown"><a class="dropdown-toggle" href="#" data-toggle="dropdown">Commercial<b class="caret"></b>
                          <!-- Classic Dropdown--></a>
                                    <ul class="dropdown-menu">
                                       <li><a href="commercial-services.html">Services</a></li>
                                        <li><a href="commercial-expertise.html">Expertise</a></li>
                                        <li><a href="commercial-resources.html">Resources</a></li>
                                    </ul>
                                </li>
								
                                <li class="dropdown"><a class="dropdown-toggle" href="#" data-toggle="dropdown">Agent Resources<b class="caret"></b>
                          <!-- Classic Dropdown--></a>
                                    <ul class="dropdown-menu">
										 <li><h4>Forms & Flyers</h4></li>
                                        <li><a href="blank-forms.html">Blank Forms</a></li>
										<li><a href="educational-booklets.html">Educational Booklets</a></li>
										<li><a href="flyer-center.html">Flyer Center</a></li>
										 <li class="divider"></li>
										 <li><h4>Rates & Fees</h4></li>
										<li><a href="http://www.pct.com/calculator/">Rate Calculator</a></li>
										<li><a href="http://www.pct.com/calculator/index.php?welcome/signup">Lender Rate Portal</a></li>
										<li><a href="recording-fees.html">Recording Fees</a></li>
										<li><a href="assets/downloads/2019-RecordersCalendar.pdf">Recorders Holidays</a></li>
										<li><a href="assets/downloads/TransferTaxes.pdf">Transfer Tax Info</a></li>
										<li><a href="rate-book.html">Rate Book</a></li>
										 <li class="divider"></li>
										  <li><h4>Tools & Video</h4></li>
										<li class="dropdown"><a class="dropdown-toggle" href="#" data-toggle="dropdown">Agent Tools<b style="color:#d35411;" class="caret"></b>
                          <!-- Classic Dropdown--></a>
										<ul class="dropdown-menu">
										
                                        <li><a href="http://www.pacificcoastagent.com/">Pacific Coast Agent</a></li>
										<li><a href="https://www.pcttitletoolbox.com/#!/">PCT Title Toolbox</a></li>
										<li><a href="#">Instant Profile</a></li>
										<li><a href="http://www.pct247.com/">PCT247.com</a></li>
										
										 </ul>
                                </li>
								<li><a href="training-center.html">Training center</a></li>
                                    </ul>
									
                                </li>
                                <li class="dropdown"><a class="dropdown-toggle" href="contact.html" data-toggle="dropdown">Contact<b class="caret"></b>
                          <!-- Classic Dropdown--></a>
                                    <ul class="dropdown-menu">
									
                                        <li><a href="downey.html">Downey</a></li>
                                        <li><a href="glendale.html">Glendale</a></li>
                                        <li><a href="orange.html">Orange</a></li>
										<li><a href="oxnard.html">Oxnard</a></li>
                                        <li><a href="sandiego.html">San Diego</a></li>
                                        
                                    </ul>
                                </li>
            </ul>
                            </ul>
                        </div>
                    <!--   <div class="header-navibox-3">
                            <ul class="nav navbar-nav hidden-xs clearfix vcenter">
                              <!--  <li><a class="btn_header_search" href="#"><i class="fa fa-search"></i></a></li>
                                <li>
                                    <button class="js-toggle-screen toggle-menu-button"><i class="toggle-menu-button-icon"><span></span><span></span><span></span><span></span><span></span><span></span></i></button>
                                </li>
                            </ul>
                        </div> -->
                      <!--  <div class="header-navibox-4">
                            <div class="header-cart"><a href="#"><i class="fa fa-shopping-basket" aria-hidden="true"></i></a><span class="header-cart-count">3</span></div>
                            <div class="header-language-nav dropdown">
                                <button class="dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown">English<span class="caret"></span></button>
                                <ul class="dropdown-menu">
                                    <li><a href="#">English</a></li>
                                    <li><a href="#">Italy</a></li>
                                    <li><a href="#">France</a></li>
                                </ul>
                            </div>
                        </div> -->
                    </div>
                </nav>
            </div>
        </header>
        <!-- end .header-->
        <div class="section-title-page7k area-bg area-bg_blue area-bg_op_60 parallax">
          <div class="area-bg__inner">
            <div class="container">
              <div class="row">
                <div class="col-xs-12">
                  <h1 class="b-title-page">CPL Request Form</h1>
                  <div class="b-title-page__info">Helping to protect you.</div>
                  <!-- end breadcrumb-->
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- end .b-title-page-->
		
		 <section class="section-type-4 section-default" style="padding-bottom:0px; padding-top:40px;">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
       

	   <div class="smart-wrap">
            <div class="smart-forms smart-container wrap-2">
            
                <div class="form-header header-primary">
                    <h4><i class="fa fa-commentsz"></i>Request Your CPL</h4>
              </div><!-- end .form-header section -->
                
                <form method="post" action="<?php echo BASE_URL; ?>php/smartprocess2.php" id="smart-form" enctype="multipart/form-data">
                    <div class="form-body">

						  <div class="spacer-b30 spacer-t30">
                            <div class="tagline"><span>Your Details </span></div><!-- .tagline -->
                        </div>                  
                    
                        <div class="frm-row">
                            <div class="section colm colm6">
                                <label class="field prepend-icon">
                                    <input type="text" name="OrderNumber" id="OrderNumber" class="gui-input" placeholder="Order Number">
                                    <span class="field-icon"><i class="fa fa-user"></i></span>
                                </label>
                            </div><!-- end section --> 
                            
                            <div class="section colm colm6">
                                <label class="field prepend-icon">
                                    <input type="text" name="LoanNumber" id="LoanNumber" class="gui-input" placeholder="Loan Number">
                                    <span class="field-icon"><i class="fa fa-envelope"></i></span>  
                                </label>
                            </div><!-- end section -->
                        </div><!-- end frm-row section -->
						
						<div class="spacer-b30 spacer-t30">
                            <div class="tagline"><span>Lender Details </span></div><!-- .tagline -->
                        </div>
						
						
					<div class="section">
                    	<label class="field prepend-icon">
                        	<input type="text" name="LenderName" id="LenderName" class="gui-input" placeholder="Lender's Name">
                            <span class="field-icon"><i class="fa fa-home"></i></span>
                            <input type="hidden" name="id" id="LenderId" value="">  
                        </label>
                    </div><!-- end section -->
					
					 <div class="frm-row">
                            <div class="section colm colm6">
                                <label class="field prepend-icon">
                                    <input type="text" name="LenderAddress" id="LenderAddress" class="gui-input" placeholder="Lender Address">
                                    <span class="field-icon"><i class="fa fa-user"></i></span>
                                </label>
                            </div><!-- end section --> 
                            
                            <div class="section colm colm6">
                                <label class="field prepend-icon">
                                    <input type="text" name="LenderCity" id="LenderCity" class="gui-input" placeholder="Lender City">
                                    <span class="field-icon"><i class="fa fa-envelope"></i></span>  
                                </label>
                            </div><!-- end section -->
                        </div><!-- end frm-row section -->
						 <div class="frm-row">
                            <div class="section colm colm6">
                                <label class="field prepend-icon">
                                    <input type="text" name="LenderSt" id="LenderSt" class="gui-input" placeholder="Lender State">
                                    <span class="field-icon"><i class="fa fa-user"></i></span>
                                </label>
                            </div><!-- end section --> 
                            
                            <div class="section colm colm6">
                                <label class="field prepend-icon">
                                    <input type="text" name="LenderZip" id="LenderZip" class="gui-input" placeholder="Lender Zip">
                                    <span class="field-icon"><i class="fa fa-envelope"></i></span>  
                                </label>
                            </div><!-- end section -->
                        </div><!-- end frm-row section -->
						<div class="spacer-b30 spacer-t30">
                            <div class="tagline"><span>Borrower Details </span></div><!-- .tagline -->
                        </div>
						
						
					<div class="section">
                    	<label class="field prepend-icon">
                        	<input type="text" name="BorrowerNames" id="BorrowerNames" class="gui-input" placeholder="Borrower Name">
                            <span class="field-icon"><i class="fa fa-home"></i></span>  
                        </label>
                    </div><!-- end section -->
					
					 <div class="frm-row">
                            <div class="section colm colm6">
                                <label class="field prepend-icon">
                                    <input type="text" name="PropertyAddress" id="PropertyAddress" class="gui-input" placeholder="Borrower Address">
                                    <span class="field-icon"><i class="fa fa-user"></i></span>
                                </label>
                            </div><!-- end section --> 
                            
                            <div class="section colm colm6">
                                <label class="field prepend-icon">
                                    <input type="text" name="PropertyCity" id="c" class="gui-input" placeholder="Borrower City">
                                    <span class="field-icon"><i class="fa fa-envelope"></i></span>  
                                </label>
                            </div><!-- end section -->
                        </div><!-- end frm-row section -->
						 <div class="frm-row">
                            <div class="section colm colm6">
                                <label class="field prepend-icon">
                                    <input type="text" name="PropertySt" id="PropertySt" class="gui-input" placeholder="Borrower State">
                                    <span class="field-icon"><i class="fa fa-user"></i></span>
                                </label>
                            </div><!-- end section --> 
                            
                            <div class="section colm colm6">
                                <label class="field prepend-icon">
                                    <input type="text" name="PropertyZip" id="PropertyZip" class="gui-input" placeholder="Borrower Zip">
                                    <span class="field-icon"><i class="fa fa-envelope"></i></span>  
                                </label>
                            </div><!-- end section -->
                        </div><!-- end frm-row section -->
						
						<div class="spacer-b30 spacer-t30">
                            <div class="tagline"><span>Delivery Details </span></div><!-- .tagline -->
                        </div>
						
						
					<div class="section">
                    	<label class="field prepend-icon">
                        	<input type="text" name="EmailTo" id="EmailTo" class="gui-input" placeholder="Email CPL To:">
                            <span class="field-icon"><i class="fa fa-home"></i></span>  
                        </label>
                    </div><!-- end section -->
					
					
					
                        
						
                      
					
                        
						
                        
						
                      
                       
                        
						
                        
					
                        
					
                     
					
                       
                        
                        <div class="result spacer-b10"></div><!-- end .result  section -->                     
                        
                        <div class="section progress-section">
                            <div class="progress-bar progress-animated bar-primary">
                                <div class="bar"></div>
                                <div class="percent">0%</div>
                            </div>
                        </div><!-- end progress section --> 
                                                                                                                    
                    </div><!-- end .form-body section -->
                    <div class="form-footer">
                        <button type="submit" data-btntext-sending="Sending..." class="button btn-primary" id="btnSubmit">Submit</button>
                        <button type="reset" class="button"> Cancel </button>
						<a style="border: 0;
    height: 42px;    color: #243140;    line-height: 1;    font-size: 15px;    cursor: pointer;    padding: 0 18px;
    text-align: center;     vertical-align: top;     background: #bdc3c7;     display: inline-block;     -webkit-user-drag: none;
    text-shadow: 0 1px rgba(255, 255, 255, 0.2);     margin-right: 10px;     margin-bottom: 5px;     text-decoration: none;
    border-radius: 3px;     padding-top: 13px;" href="http://www.pct.com"/>Homepage</a>
                    </div><!-- end .form-footer section -->
                </form>
            </div><!-- end .smart-forms section -->
        </div><!-- end .smart-wrap section -->
		
		
  						
						
						
						
						
						
						
						
						
                    </div>
                  
                </div>
            </div>
        </section>
    
     
      <!-- end .section-type-14-->
	  <br><br>
	  <section class="section-type-1 section-sm parallax area-bg area-bg_grad-2 area-bg_op_80">
        <div class="area-bg__inner">
          <div class="container">
            <div class="row">
              <div class="col-md-7">
                <h2 class="ui-title-block-3">Ready to work with us?</h2>
                <div class="ui-subtitle-block-2">we are ready to help.</div>
              </div>
              <div class="col-md-5"><a class="btn btn-default btn-round pull-right" href="https://clients.pacificcoasttitle.com/login.aspx?ReturnUrl=/&officeid=1">open orders</a><a class="btn btn-default btn-round pull-right" href="rate-book.html">get rates</a></div>
            </div>
          </div>
        </div>
      </section>
	  
       <footer class="footer">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <ul class="footer-social-nets">
                            <li class="footer-social-nets__item"><a class="footer-social-nets__link" href="https://www.facebook.com/PacificCoastTitleCompany/">facebook</a></li>
                            <li class="footer-social-nets__item"><a class="footer-social-nets__link" href="https://twitter.com/mypct?lang=en">twitter</a></li>                      
                            <li class="footer-social-nets__item"><a class="footer-social-nets__link" href="#">instagram</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="footer__main">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="footer-section">
                                <a class="footer__logo" href="<?php echo BASE_URL_MAIN;?>index.html"><img class="img-responsive" src="<?php echo BASE_URL_MAIN;?>assets/media/general/logo-lg2.png" alt="Logo"></a>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <section class="footer-section footer-section_links">
                                <h3 class="footer-section__title">useful links</h3>
                                <ul class="footer-list list-unstyled">
                                    <li class="footer-list__item"><a class="footer-list__link" href="<?php echo BASE_URL_MAIN;?>about-us.html">About Us</a></li>
                                    <li class="footer-list__item"><a class="footer-list__link" href="<?php echo BASE_URL_MAIN;?>contact.html">Contact Us</a></li>
                                    <li class="footer-list__item"><a class="footer-list__link" href="<?php echo BASE_URL_MAIN;?>how-we-protect-you.html">Our Role</a></li>
                                    <li class="footer-list__item"><a class="footer-list__link" href="<?php echo BASE_URL_MAIN;?>blank-forms.html">Blank Forms</a></li>
                                    <li class="footer-list__item"><a class="footer-list__link" href="<?php echo BASE_URL_MAIN;?>what-is-title-insurance.html">What Is Title Ins.</a></li>
                                    <li class="footer-list__item"><a class="footer-list__link" href="<?php echo BASE_URL_MAIN;?>flyer-center.html">Info Flyers</a></li>
									<li class="footer-list__item"><a class="footer-list__link" href="<?php echo BASE_URL_MAIN;?>notices.html">Notices</a></li>
                                   <li class="footer-list__item"><a class="footer-list__link" href="<?php echo BASE_URL_MAIN;?>sb2-forms.html">SB2-Forms</a></li>
                                </ul>
                            </section>
                        </div>
                        <div class="col-sm-4">
                            <section class="footer-section">
                                <h3 class="footer-section__title">Corporate Contact Info</h3>
                                <p>Address: 1111 E. Katella Ave Ste. 120 Orange, CA 92867</p>
                                <p>Phone: (714) 516-6700 / (866) 724-1050</p>
                                <p>Email: info@pct.com</p><a class="footer__link" href="https://goo.gl/maps/Hrjgqrh1imP2">get directions</a>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
            <div class="copyright">
                <div class="container">
                <div class="row">
                    <div class="col-xs-12">©<a class="copyright__link" href="<?php echo BASE_URL_MAIN;?>index.html"> PACIFIC COAST TITLE COMPANY</a> All rights reserved.</div>
                </div>
                    </div>
            </div>
        </footer>
        <!-- .footer-->
    </div>
    <!-- end layout-theme-->
    
    
   
    <!-- ++++++++++++-->
    <!-- MAIN SCRIPTS-->
    <!-- ++++++++++++-->
    <!-- <script src="http://www.pct.com/assets/libs/jquery-1.12.4.min.js"></script> -->
    <script src="http://www.pct.com/assets/libs/jquery-migrate-1.2.1.js"></script>
    <!-- Bootstrap-->
    <script src="http://www.pct.com/assets/libs/bootstrap/bootstrap.min.js"></script>

    <!---->
    <!-- Select customization & Color scheme-->
    <script src="http://www.pct.com/assets/plugins/bootstrap-select/js/bootstrap-select.js"></script>
    <!-- Slider-->
    <script src="http://www.pct.com/assets/plugins/owl-carousel/owl.carousel.min.js"></script>
    <!-- Pop-up window-->
    <script src="http://www.pct.com/assets/plugins/magnific-popup/jquery.magnific-popup.min.js"></script>
    <!-- Headers scripts-->
    <script src="http://www.pct.com/assets/plugins/headers/slidebar.js"></script>
    <script src="http://www.pct.com/assets/plugins/headers/header.js"></script>
    <!-- Mail scripts-->
    <script src="http://www.pct.com/assets/plugins/jqBootstrapValidation.js"></script>
    <script src="http://www.pct.com/assets/plugins/contact_me.js"></script>
    <!-- Video player-->
    <script src="http://www.pct.com/assets/plugins/flowplayer/flowplayer.min.js"></script>
    <!-- Filter and sorting images-->
    <script src="http://www.pct.com/assets/plugins/isotope/isotope.pkgd.min.js"></script>
    <script src="http://www.pct.com/assets/plugins/isotope/imagesLoaded.js"></script>
    <!-- Progress numbers-->
    <script src="http://www.pct.com/assets/plugins/rendro-easy-pie-chart/jquery.easypiechart.min.js"></script>
    <script src="http://www.pct.com/assets/plugins/rendro-easy-pie-chart/waypoints.min.js"></script>
    <!-- Animations-->
    <script src="http://www.pct.com/assets/plugins/scrollreveal/scrollreveal.min.js"></script>
    <script src="http://www.pct.com/assets/plugins/revealer/js/anime.min.js"></script>
    <script src="http://www.pct.com/assets/plugins/revealer/js/scrollMonitor.js"></script>
    <script src="http://www.pct.com/assets/plugins/revealer/js/main.js"></script>
    <script src="http://www.pct.com/assets/plugins/animate/wow.min.js"></script>
    <script src="http://www.pct.com/assets/plugins/animate/jquery.shuffleLetters.js"></script>
    <script src="http://www.pct.com/assets/plugins/animate/jquery.scrollme.min.js"></script>


    <!-- User customization-->
    <script src="http://www.pct.com/assets/js/custom.js"></script>
<!-- Facebook Pixel Code -->
<script>
!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
document,'script','https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '307916942954456', {
em: 'insert_email_variable'
});
fbq('track', 'PageView');

//Autofill lender info
jQuery(function () {
    jQuery("#LenderName").autocomplete({
        source: "search.php",
        select: function( event, ui ) {
            event.preventDefault();
            jQuery("#LenderName").val(ui.item.value);
            jQuery("#LenderAddress").val(ui.item.address).attr('readonly','readonly').parent().addClass('state-success');
            jQuery("#LenderCity").val(ui.item.city).attr('readonly','readonly').parent().addClass('state-success');
            jQuery("#LenderSt").val(ui.item.state).attr('readonly','readonly').parent().addClass('state-success');
            jQuery("#LenderZip").val(ui.item.zip).attr('readonly','readonly').parent().addClass('state-success');
            jQuery("#LenderId").val(ui.item.id);
        },
        change: function( event, ui ) {
            if (ui.item == null)
            {
                jQuery("#LenderAddress").val('').removeAttr('readonly').parent().removeClass('state-success').addClass('state-error');
                jQuery("#LenderCity").val('').removeAttr('readonly').parent().removeClass('state-success').addClass('state-error');
                jQuery("#LenderSt").val('').removeAttr('readonly').parent().removeClass('state-success').addClass('state-error');
                jQuery("#LenderZip").val('').removeAttr('readonly').parent().removeClass('state-success').addClass('state-error');
                jQuery("#LenderId").val('');
            }
        }
    });

    jQuery('#LenderName').blur(function(){
        if( !jQuery(this).val() ) {
            jQuery("#LenderAddress").val('').removeAttr('readonly').parent().removeClass('state-success').addClass('state-error');
            jQuery("#LenderCity").val('').removeAttr('readonly').parent().removeClass('state-success').addClass('state-error');
            jQuery("#LenderSt").val('').removeAttr('readonly').parent().removeClass('state-success').addClass('state-error');
            jQuery("#LenderZip").val('').removeAttr('readonly').parent().removeClass('state-success').addClass('state-error');
            jQuery("#LenderId").val('');
        }
    });
});

//Autofill lender info



</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=307916942954456&ev=PageView&noscript=1"
/></noscript>
<!-- DO NOT MODIFY -->
<!-- End Facebook Pixel Code -->


</body>

</html>