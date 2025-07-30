<?php
    if ( isset($_GET['skin']) ) $skin=(int)$_GET['skin'];else $skin=1;
    include('easy-protect.php');
    $options = array(
        'skin'     => $skin,
        #'md5'      => true,
        #'block'   => array('127.0.0.1','95.222.76.152'),
        'attempts' => 3,
        'timeout'  => 60,
        #'bypass'  => array('127.0.0.1','95.222.76.152'),
    );
    session_set_cookie_params(0);session_start();
    protect(array('admin','demo'), $options);
    // WITH MD5 LOOK EXAMPLE test2.php
    #protect('admin', $options); // only ONE password
?>
<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Marketing Center | Pacific Coast Title Company</title>
    <meta content="We specialize in Residential, Commercial Title & Escrow Services" name="description">
    <meta content="" name="keywords">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="telephone=no" name="format-detection">
    <meta name="HandheldFriendly" content="true">
    <link rel="stylesheet" href="assets/css/master.css">
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <!--[if lt IE 9 ]>
<script src="/assets/js/separate-js/html5shiv-3.7.2.min.js" type="text/javascript"></script><meta content="no" http-equiv="imagetoolbar">
<![endif]-->
	<link rel="stylesheet" type="text/css" href="assets/js/lightbox/themes/evolution-dark/jquery.lightbox.css" />
	<!--[if IE 6]>
	<link rel="stylesheet" type="text/css" href="assets/js/lightbox/themes/evolution-dark/jquery.lightbox.ie6.css" />
	<![endif]-->


</head>

<body>
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
                        <li><a href="informational-flyers.html">Informational Flyers</a> </li>
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
                            <a class="navbar-brand scroll" href="home.html"><img class="normal-logo" src="assets/media/general/logo2.png" alt="logo"><img class="scroll-logo hidden-xs" src="assets/media/general/logo2-dark.png" alt="logo"></a>
                        </div>
                        <div class="header-navibox-2">
                           
										 <a href="easy-protect.php?logout=true">LOGOUT</a>
                           
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
        <div class="section-title-page3 area-bg area-bg_blue area-bg_op_90 parallax">
          <div class="area-bg__inner">
            <div class="container">
              <div class="row">
                <div class="col-xs-12">
                  <h1 class="b-title-page">Marketing Center</h1>
                  <div class="b-title-page__info">Marketing on demand</div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- end .b-title-page-->
      <div class="section-default">
          <div class="b-isotope b-isotope-1 b-isotope-1_mod-a">
            <ul class="b-isotope-filter list-inline">
              <li><a class="current" href="" data-filter="*">all works</a></li>
              <li><a href="" data-filter=".design">Pacific Coast Agent</a></li>
              <li><a href="" data-filter="">Pacific Coast Instant Profile</a></li>
              <li><a href="" data-filter="">PCT247.COM</a></li>
              <li><a href="" data-filter="">How to Read A Prelim</a></li>
              <li><a href="" data-filter="">Title Tips</a></li>
            </ul>
            <div class="container">
              <div class="row">
                <div class="col-xs-12">
                  <ul class="b-isotope-grid grid list-unstyled">
                    <li class="grid-sizer"></li>
                    <li class="b-isotope-grid__item grid-item design"><a class="b-isotope-grid__inner lightbox" href=""><img src="assets/media/content/gallery/360x340/7.jpg" alt="foto"><span class="b-isotope-grid__wrap-info"><span class="b-isotope-grid__info"><span class="b-isotope-grid__title">Download & Signup</span><span class="b-isotope-grid__categorie">This video shows you how to download our app and create an account.</span></span></span></a></li>
                    <li class="b-isotope-grid__item grid-item design"><a class="b-isotope- lightbox" href="http://youtu.be/CF21CiydS3M?hd=1"><img src="assets/media/content/gallery/360x340/8.jpg" alt="foto"><span class="b-isotope-grid__wrap-info"><span class="b-isotope-grid__info"><span class="b-isotope-grid__title">Buyers Estimate</span><span class="b-isotope-grid__categorie">This video will show you how to create a buyers estimate.</span></span></span></a></li>
                    <li class="b-isotope-grid__item grid-item design"><a class="b-isotope-grid__inner lightbox" href="http://youtu.be/gVQIh2lsQTo?hd=1"><img src="assets/media/content/gallery/360x340/9.jpg" alt="foto"><span class="b-isotope-grid__wrap-info"><span class="b-isotope-grid__info"><span class="b-isotope-grid__title">Extra Payment</span><span class="b-isotope-grid__categorie">This video will show you how to use the extra payment calculator.</span></span></span></a></li>
                    <li class="b-isotope-grid__item grid-item design"><a class="b-isotope-grid__inner lightbox" href="http://youtu.be/8ilaiD4hHys?hd=1"><img src="assets/media/content/gallery/360x340/10.jpg" alt="foto"><span class="b-isotope-grid__wrap-info"><span class="b-isotope-grid__info"><span class="b-isotope-grid__title">Sellers Netsheet</span><span class="b-isotope-grid__categorie">This video will show you how to create a sellers net sheet.</span></span></span></a></li>
                    <li class="b-isotope-grid__item grid-item design"><a class="b-isotope-grid__inner lightbox" href="http://youtu.be/Q3n0vB8lIBY?hd=1"><img src="assets/media/content/gallery/360x340/11.jpg" alt="foto"><span class="b-isotope-grid__wrap-info"><span class="b-isotope-grid__info"><span class="b-isotope-grid__title">Sell-to-Net</span><span class="b-isotope-grid__categorie">This video will show you how to use the Sell-to-Net feature in our app.</span></span></span></a></li>
                    <li class="b-isotope-grid__item grid-item design"><a class="b-isotope-grid__inner lightbox" href="http://youtu.be/YTlnk_RolLk?hd=1"><img src="assets/media/content/gallery/360x340/12.jpg" alt="foto"><span class="b-isotope-grid__wrap-info"><span class="b-isotope-grid__info"><span class="b-isotope-grid__title">CD Timelines</span><span class="b-isotope-grid__categorie">This video will show you how view CD timelines for your transaction.</span></span></span></a></li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
          <!-- end b-isotope-->
      </div>
      <!-- end .section-default-->
	   <section class="section-type-1 section-sm parallax area-bg area-bg_grad-2 area-bg_op_90">
            <div class="area-bg__inner">
                <div class="container">
                    <div class="row">
                        <div class="col-md-7">
                            <h2 class="ui-title-block-3">we provide higher quality services</h2>
                            <div class="ui-subtitle-block-2">and you’ll get solutions for everything</div>
                        </div>
                        <div class="col-md-5"><a class="btn btn-default btn-round pull-right" href="https://clients.pacificcoasttitle.com/login.aspx?ReturnUrl=/&officeid=1">open orders</a><a class="btn btn-default btn-round pull-right" href="rate-book.html">get rates</a></div>
                    </div>
                </div>
            </div>
        </section>
        <!-- end .section-type-1-->
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
                                <a class="footer__logo" href="home.html"><img class="img-responsive" src="assets/media/general/logo-lg2.png" alt="Logo"></a>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <section class="footer-section footer-section_links">
                                <h3 class="footer-section__title">useful links</h3>
                                <ul class="footer-list list-unstyled">
                                    <li class="footer-list__item"><a class="footer-list__link" href="about.html">About Us</a></li>
                                    <li class="footer-list__item"><a class="footer-list__link" href="contact.html">Contact Us</a></li>
                                    <li class="footer-list__item"><a class="footer-list__link" href="how-we-protect-you.html">Our Role</a></li>
                                    <li class="footer-list__item"><a class="footer-list__link" href="blank-forms.html">Blank Forms</a></li>
                                    <li class="footer-list__item"><a class="footer-list__link" href="what-is-title-insurance.html">What Is Title Ins.</a></li>
                                    <li class="footer-list__item"><a class="footer-list__link" href="informational-flyers.html">Info Flyers</a></li>
									<li class="footer-list__item"><a class="footer-list__link" href="notices.html">Notices</a></li>
									<li class="footer-list__item"><a class="footer-list__link" href="sb2-forms.html">SB2-Forms</a></li>
                                   
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
                    <div class="col-xs-12">©<a class="copyright__link" href="index.html"> PACIFIC COAST TITLE COMPANY</a> All rights reserved.</div>
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
    <script src="assets/libs/jquery-1.12.4.min.js"></script>
    <script src="assets/libs/jquery-migrate-1.2.1.js"></script>
    <!-- Bootstrap-->
    <script src="assets/libs/bootstrap/bootstrap.min.js"></script>

    <!---->
    <!-- Select customization & Color scheme-->
    <script src="assets/plugins/bootstrap-select/js/bootstrap-select.js"></script>
    <!-- Slider-->
    <script src="assets/plugins/owl-carousel/owl.carousel.min.js"></script>
    <!-- Pop-up window-->
    <script src="assets/plugins/magnific-popup/jquery.magnific-popup.min.js"></script>
    <!-- Headers scripts-->
    <script src="assets/plugins/headers/slidebar.js"></script>
    <script src="assets/plugins/headers/header.js"></script>
    <!-- Mail scripts-->
    <script src="assets/plugins/jqBootstrapValidation.js"></script>
    <script src="assets/plugins/contact_me.js"></script>
    <!-- Video player-->
    <script src="assets/plugins/flowplayer/flowplayer.min.js"></script>
    <!-- Filter and sorting images-->
    <script src="assets/plugins/isotope/isotope.pkgd.min.js"></script>
    <script src="assets/plugins/isotope/imagesLoaded.js"></script>
    <!-- Progress numbers-->
    <script src="assets/plugins/rendro-easy-pie-chart/jquery.easypiechart.min.js"></script>
    <script src="assets/plugins/rendro-easy-pie-chart/waypoints.min.js"></script>
    <!-- Animations-->
    <script src="assets/plugins/scrollreveal/scrollreveal.min.js"></script>
    <script src="assets/plugins/revealer/js/anime.min.js"></script>
    <script src="assets/plugins/revealer/js/scrollMonitor.js"></script>
    <script src="assets/plugins/revealer/js/main.js"></script>
    <script src="assets/plugins/animate/wow.min.js"></script>
    <script src="assets/plugins/animate/jquery.shuffleLetters.js"></script>
    <script src="assets/plugins/animate/jquery.scrollme.min.js"></script>
	<!-- ligthbox-->
    <script type="text/javascript" src="assets/js/lightbox/jquery.lightbox.min.js"></script>

    <!-- User customization-->
    <script src="assets/js/custom.js"></script>
	
	<script type="text/javascript">
  jQuery(document).ready(function($){
    $('.lightbox').lightbox();
  });
</script>

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
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=307916942954456&ev=PageView&noscript=1"
/></noscript>
<!-- DO NOT MODIFY -->
<!-- End Facebook Pixel Code -->
	

</body>

</html>
