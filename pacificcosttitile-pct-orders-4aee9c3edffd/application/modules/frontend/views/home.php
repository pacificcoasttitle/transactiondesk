<!DOCTYPE html>
<html>
<head>
	<title><?php echo $title; ?></title>
	<meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta content="We specialize in Residential, Commercial Title & Escrow Services" name="description">
    <meta content="" name="keywords">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="telephone=no" name="format-detection">
    <meta name="HandheldFriendly" content="true">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/frontend/css/custom.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/master.css">
    <link rel="icon" href="<?php echo base_url(); ?>assets/frontend/images/favicon.ico" type="image/x-icon">
</head>
<body>
	<?php
        $this->load->view('layout/header');
    ?>
	<div class="main-slider slider-pro text-center" id="main-slider" data-slider-width="100%" data-slider-height="920px"
		data-slider-arrows="false" data-slider-buttons="true">
		<div class="sp-slides">
			<!-- Slide 1-->
			<div class="sp-slide"><img class="sp-image"
					src="<?php echo base_url(); ?>assets/media/components/b-main-slider/springhome.jpg" alt="slider">
				<div class="container">
					<div class="row">
						<div class="col-sm-12">
							<div class="main-slider__info sp-layer" data-width="100%" data-show-transition="left"
								data-hide-transition="left" data-show-duration="2000" data-show-delay="1200"
								data-hide-delay="400">Residential / Commercial</div>
							<h2 class="main-slider__title sp-layer" data-width="100%" data-show-transition="left"
								data-hide-transition="left" data-show-duration="800" data-show-delay="400"
								data-hide-delay="400">Title Settlement Services</h2>
							<div class="sp-layer" data-width="100%" data-show-transition="left"
								data-hide-transition="left" data-show-duration="1200" data-show-delay="2000"
								data-hide-delay="400">
								<!--<a class="main-slider__btn btn btn-default btn-lg " href="<?php echo base_url(); ?>order">Open Order</a>--><a
									class="main-slider__btn btn btn-default btn-lg "
									href="<?php echo base_url(); ?>calculator/">Get Quote</a><a
									class="main-slider__btn btn btn-default btn-lg "
									href="https://www.pcttitletoolbox.com/#!/">Property Info</a>
								<!--<a class="main-slider__btn btn btn-default btn-lg " href="<?php echo base_url(); ?>farm">Order Farm</a>-->
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- Slide 2-->
			<!--   <div class="sp-slide"><img class="sp-image" src="<?php echo base_url(); ?>assets/media/components/b-main-slider/bg-2.jpg" alt="slider">
                    <div class="container">
                        <div class="row">
                            <div class="col-sm-10 col-sm-offset-1">
                                <div class="main-slider__info sp-layer" data-width="100%" data-show-transition="left" data-hide-transition="left" data-show-duration="2000" data-show-delay="1200" data-hide-delay="400">Creative / Multipurpose / Colorful</div>
                                <h2 class="main-slider__title sp-layer" data-width="100%" data-show-transition="left" data-hide-transition="left" data-show-duration="800" data-show-delay="400" data-hide-delay="400">your project hassle-free</h2>
                                <div class="sp-layer" data-width="100%" data-show-transition="left" data-hide-transition="left" data-show-duration="1200" data-show-delay="2000" data-hide-delay="400"><a class="main-slider__btn btn btn-default btn-round" href="services.html">Open Order</a><a class="main-slider__btn main-slider__btn_white btn btn-default btn-round" href="services.html">Property Info</a></div>
                            </div>
                        </div>
                    </div>
                </div> -->
			<!-- Slide 3-->
			<!--    <div class="sp-slide"><img class="sp-image" src="<?php echo base_url(); ?>assets/media/components/b-main-slider/bg-1.jpg" alt="slider">
                    <div class="container">
                        <div class="row">
                            <div class="col-sm-10 col-sm-offset-1">
                                <div class="main-slider__info sp-layer" data-width="100%" data-show-transition="left" data-hide-transition="left" data-show-duration="2000" data-show-delay="1200" data-hide-delay="400">Creative / Multipurpose / Colorful</div>
                                <h2 class="main-slider__title sp-layer" data-width="100%" data-show-transition="left" data-hide-transition="left" data-show-duration="800" data-show-delay="400" data-hide-delay="400">your project hassle-free</h2>
                                <div class="sp-layer" data-width="100%" data-show-transition="left" data-hide-transition="left" data-show-duration="1200" data-show-delay="2000" data-hide-delay="400"><a class="main-slider__btn btn btn-default btn-round" href="services.html">read more</a><a class="main-slider__btn main-slider__btn_white btn btn-default btn-round" href="services.html">what we offer</a></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Slide 4-->
			<!--    <div class="sp-slide"><img class="sp-image" src="<?php echo base_url(); ?>assets/media/components/b-main-slider/bg-2.jpg" alt="slider">
                    <div class="container">
                        <div class="row">
                            <div class="col-sm-10 col-sm-offset-1">
                                <div class="main-slider__info sp-layer" data-width="100%" data-show-transition="left" data-hide-transition="left" data-show-duration="2000" data-show-delay="1200" data-hide-delay="400">Creative / Multipurpose / Colorful</div>
                                <h2 class="main-slider__title sp-layer" data-width="100%" data-show-transition="left" data-hide-transition="left" data-show-duration="800" data-show-delay="400" data-hide-delay="400">your project hassle-free</h2>
                                <div class="sp-layer" data-width="100%" data-show-transition="left" data-hide-transition="left" data-show-duration="1200" data-show-delay="2000" data-hide-delay="400"><a class="main-slider__btn btn btn-default btn-round" href="services.html">read more</a><a class="main-slider__btn main-slider__btn_white btn btn-default btn-round" href="services.html">what we offer</a></div>
                            </div>
                        </div>
                    </div>
                </div> -->
		</div>
	</div>
	<!-- end .main-slider-->
	<section class="section-default">
		<div class="container">
			<div class="row">
				<div class="col-xs-12">
					<div class="text-center">
						<div class="ui-subtitle-block">We would like to welcome you to Pacific Coast Title </div>
						<h2 class="ui-title-block-2">What We Do</h2>
						<div class="ui-decor-1 bg-primary" style="background:#d35410;"></div>

					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-3">
					<section class="b-advantages b-advantages-1  wow  fadeIn" data-wow-duration="1s"
						data-wow-delay="0.15s"> <i class="b-advantages__icon stroke flaticon-printed-paper "></i>
						<div class="b-advantages__inner">
							<h3 class="b-advantages__title ui-title-inner"><a href="index.html">Residential Title</a>
							</h3>
							<div class="b-advantages__info">Our thorough title searches, title clearance, and title
								policies help to produce a clear property title and enable an efficient closing of your
								transactions.</div>
						</div>
					</section>
					<!-- end .b-advantages-->
				</div>
				<div class="col-sm-3">
					<section class="b-advantages b-advantages-1  wow  fadeIn" data-wow-duration="1s"
						data-wow-delay="0.35s"> <i class="b-advantages__icon stroke flaticon-layers "></i>
						<div class="b-advantages__inner">
							<h3 class="b-advantages__title ui-title-inner"><a href="index.html">Commercial Title</a>
							</h3>
							<div class="b-advantages__info">Our team of top industry professionals have the expertise
								and knowledge
								needed to close your commercial real estate transactions.</div>
						</div>
					</section>
					<!-- end .b-advantages-->
				</div>
				<div class="col-sm-3">
					<section class="b-advantages b-advantages-1 wow  fadeIn" data-wow-duration="1s"
						data-wow-delay="0.45s"><i class="b-advantages__icon stroke flaticon-presentation"></i>
						<div class="b-advantages__inner">
							<h3 class="b-advantages__title ui-title-inner"><a href="index.html">Escrow Settlement</a>
							</h3>
							<div class="b-advantages__info">Our escrow division is comprised of seasoned settlement
								agents who can
								facilitate the closing of the most demanding residential & commercial transactions</div>
						</div>
					</section>
					<!-- end .b-advantages-->
				</div>
				<div class="col-sm-3">
					<section class="b-advantages b-advantages-1 wow  fadeIn" data-wow-duration="1s"
						data-wow-delay="0.65s"><i class="b-advantages__icon stroke flaticon-bar-chart"></i>
						<div class="b-advantages__inner">
							<h3 class="b-advantages__title ui-title-inner"><a href="index.html">Lender Solutions</a>
							</h3>
							<div class="b-advantages__info">Our title operations and technology infrastructure allow us
								to partner with some of the nations largest lenders requiring title & escrow services
							</div>
						</div>
					</section>
					<!-- end .b-advantages-->
				</div>
			</div>
		</div>
	</section>
	<!-- end .section-default-->
	<div class="block-table block-table_lg">
		<div class="block-table__cell col-lg-6">
			<div class="section-type-9 area-bg area-bg_op_25 area-bg_grad-5 block-table__inner">
				<div class="area-bg__inner">
					<!--   <div class="b-brands owl-carousel owl-theme enable-owl-carousel" data-min480="2" data-min768="3" data-min992="3" data-min1200="3" data-pagination="false" data-navigation="false" data-auto-play="40000" data-stop-on-hover="true">
                            <a class="b-brands__item" href="index.html"><img class="img-responsive center-block" src="<?php echo base_url(); ?>assets/media/components/b-brands/res.png" alt="foto"></a>
                            <a class="b-brands__item" href="index.html"><img class="img-responsive center-block" src="<?php echo base_url(); ?>assets/media/components/b-brands/com.png" alt="foto"></a>
                            <a class="b-brands__item" href="index.html"><img class="img-responsive center-block" src="<?php echo base_url(); ?>assets/media/components/b-brands/hou.png" alt="foto"></a>
                        </div> -->
					<!-- end b-brands-->
				</div>
				<div class="helper-2"></div>
			</div>
		</div>
		<div class="block-table__cell col-lg-6">
			<div class="section-type-10 area-bg area-bg_blue area-bg_op_90 parallax">
				<div class="area-bg__inner">
					<ul class="b-tabs-nav nav nav-tabs">
						<li class="active"><a href="#who" data-toggle="tab">about us</a></li>
						<li><a href="#statement" data-toggle="tab">mission statement</a></li>
						<li><a href="#awards" data-toggle="tab">our values</a></li>
						<li><a href="#strategy" data-toggle="tab">the strategy</a></li>
					</ul>
					<div class="b-tabs-content tab-content">
						<div class="tab-pane active" id="who">
							<section class="section-area">
								<h2 class="ui-title-block-3">who we are</h2>
								<div class="ui-subtitle-block-2">solutions for everyone</div>
								<div class="ui-decor-2 bg-white"></div>
								<p>We specialize in Residential & Commercial Title Insurance and we work hard behind the
									scenes to make sure your experience with us is a satisfying one. Your success is our
									success.</p>
							</section>
						</div>
						<div class="tab-pane" id="statement">
							<section class="section-area">
								<h2 class="ui-title-block-3">mission statement</h2>
								<div class="ui-subtitle-block-2">solutions for everyone</div>
								<div class="ui-decor-2 bg-white"></div>
								<p>To empower our clients through superior customer service, industry innovation, and
									our commitment to delivering on our promises.<br><br></p>
							</section>
						</div>
						<div class="tab-pane" id="awards">
							<section class="section-area">
								<h2 class="ui-title-block-3">our values</h2>
								<div class="ui-subtitle-block-2">solutions for everyone</div>
								<div class="ui-decor-2 bg-white"></div>
								<p>it's our goal to establish a long-term relationship with all of our clients. By
									working together, we can deliver our services faster which will help you close deals
									quickly and more efficiently. </p>
							</section>
						</div>
						<div class="tab-pane" id="strategy">
							<section class="section-area">
								<h2 class="ui-title-block-3">our strategy</h2>
								<div class="ui-subtitle-block-2">solutions for everyone</div>
								<div class="ui-decor-2 bg-white"></div>
								<p>to provide cutting edge tools that agents can use in their business generation
									efforts and combine them with the best customer service in the industry.<br><br></p>
							</section>
						</div>
					</div>
					<!-- end .b-tabs-->
				</div>
			</div>
		</div>
	</div>
	<!-- end .block-table-->
	<section class="section-default bg-grey">
		<div class="container">
			<div class="row">
				<div class="col-xs-12">
					<div class="text-center">
						<div class="ui-subtitle-block">want to know a little more about us</div>
						<h2 class="ui-title-block-2"><span class="shuffle">Service, Tools, Commitment</span></h2>
						<div class="ui-decor-1 bg-primary" style="background:#d35410;"></div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12">
					<div class="b-advantages-group">
						<section class="b-advantages b-advantages-2 b-advantages_3-col"><i
								class="b-advantages__icon stroke flaticon-screen"></i>
							<div class="b-advantages__inner effect-border">
								<h3 class="b-advantages__title ui-title-inner">residential</h3>
								<div class="b-advantages__info">Our experience in the Residential sector allows us to
									provide Title Insurance policies promptly.</div>
							</div>
						</section>
						<!-- end .b-advantages-->
						<section class="b-advantages b-advantages-2  b-advantages_3-col"><i
								class="b-advantages__icon stroke flaticon-layers"></i>
							<div class="b-advantages__inner effect-border effect-active ">
								<h3 class="b-advantages__title ui-title-inner">commercial</h3>
								<div class="b-advantages__info">Our commercial sector is ready to meet all the demands
									brought by these large transactions.</div>
							</div>
						</section>
						<!-- end .b-advantages-->
						<section class="b-advantages b-advantages-2 b-advantages_3-col"><i
								class="b-advantages__icon stroke flaticon-presentation	"></i>
							<div class="b-advantages__inner effect-border">
								<h3 class="b-advantages__title ui-title-inner">escrow</h3>
								<div class="b-advantages__info">Our escrow division is ready to help ensure all
									conditions are met and all funds are dispersed.</div>
							</div>
						</section>
						<!-- end .b-advantages-->
						<section class="b-advantages b-advantages-2 b-advantages_3-col"><i
								class="b-advantages__icon stroke flaticon-worldwide"></i>
							<div class="b-advantages__inner effect-border">
								<h3 class="b-advantages__title ui-title-inner">nationwide</h3>
								<div class="b-advantages__info">Our nationwide network helps us provide our services on
									residential & commercial transactions within the United States.

								</div>
							</div>
						</section>
						<!-- end .b-advantages-->
						<section class="b-advantages b-advantages-2 b-advantages_3-col"><i
								class="b-advantages__icon stroke flaticon-analytics"></i>
							<div class="b-advantages__inner effect-border">
								<h3 class="b-advantages__title ui-title-inner">technology</h3>
								<div class="b-advantages__info">Our dedication to technology keeps us at the forefont of
									innovation which helps us provide the serives you need.</div>
							</div>
						</section>
						<!-- end .b-advantages-->
						<section class="b-advantages b-advantages-2 b-advantages_3-col"><i
								class="b-advantages__icon stroke flaticon-big-handbag"></i>
							<div class="b-advantages__inner effect-border">
								<h3 class="b-advantages__title ui-title-inner">people</h3>
								<div class="b-advantages__info">Our team is what makes it all come together. The company
									culture at Pacific Coast Title Company is unlike any other.</div>
							</div>
						</section>
						<!-- end .b-advantages-->
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- end .section-default-->

	<!-- end .section-area-->
	<!-- <section class="section-default">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="text-center">
                            <div class="ui-subtitle-block">Tempor incididunt labore dolore veniam</div>
                            <h2 class="ui-title-block-2"><span class="shuffle">projects we do</span></h2>
                            <div class="ui-decor-1 bg-primary"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="b-isotope b-isotope-1">
                            <ul class="b-isotope-filter list-inline">
                                <li><a class="current" href="" data-filter="*">all works</a></li>
                                <li><a href="" data-filter=".design">web design</a></li>
                                <li><a href="" data-filter=".wordpress">wordpress</a></li>
                                <li><a href="" data-filter=".mockups">mockups</a></li>
                                <li><a href="" data-filter=".animation">animation</a></li>
                                <li><a href="" data-filter=".print">print design</a></li>
                            </ul>
                            <div class="container">
                                <div class="row">
                                    <div class="col-xs-12">
                                        <ul class="b-isotope-grid grid list-unstyled">
                                            <li class="grid-sizer"></li>
                                            <li class="b-isotope-grid__item grid-item wordpress print ">
                                                <a class="b-isotope-grid__inner" href="portfolio-1.html"><img src="<?php echo base_url(); ?>assets/media/content/gallery/360x360/1.jpg" alt="foto"><span class="b-isotope-grid__wrap-info"><span class="b-isotope-grid__info"><span class="b-isotope-grid__title">the glass bottle</span><span class="b-isotope-grid__categorie">branding / print</span></span>
                                                    </span>
                                                </a>
                                            </li>
                                            <li class="b-isotope-grid__item grid-item design animation">
                                                <a class="b-isotope-grid__inner" href="portfolio-1.html"><img src="<?php echo base_url(); ?>assets/media/content/gallery/360x260/1.jpg" alt="foto"><span class="b-isotope-grid__wrap-info"><span class="b-isotope-grid__info"><span class="b-isotope-grid__title">the glass bottle</span><span class="b-isotope-grid__categorie">branding / print</span></span>
                                                    </span>
                                                </a>
                                            </li>
                                            <li class="b-isotope-grid__item grid-item wordpress mockups animation">
                                                <a class="b-isotope-grid__inner" href="portfolio-1.html"><img src="<?php echo base_url(); ?>assets/media/content/gallery/360x450/1.jpg" alt="foto"><span class="b-isotope-grid__wrap-info"><span class="b-isotope-grid__info"><span class="b-isotope-grid__title">the glass bottle</span><span class="b-isotope-grid__categorie">branding / print</span></span>
                                                    </span>
                                                </a>
                                            </li>
                                            <li class="b-isotope-grid__item grid-item wordpress animation print effect-active">
                                                <a class="b-isotope-grid__inner" href="portfolio-1.html"><img src="<?php echo base_url(); ?>assets/media/content/gallery/360x260/2.jpg" alt="foto"><span class="b-isotope-grid__wrap-info"><span class="b-isotope-grid__info"><span class="b-isotope-grid__title">the glass bottle</span><span class="b-isotope-grid__categorie">branding / print</span></span>
                                                    </span>
                                                </a>
                                            </li>
                                            <li class="b-isotope-grid__item grid-item design">
                                                <a class="b-isotope-grid__inner" href="portfolio-1.html"><img src="<?php echo base_url(); ?>assets/media/content/gallery/360x450/2.jpg" alt="foto"><span class="b-isotope-grid__wrap-info"><span class="b-isotope-grid__info"><span class="b-isotope-grid__title">the glass bottle</span><span class="b-isotope-grid__categorie">branding / print</span></span>
                                                    </span>
                                                </a>
                                            </li>
                                            <li class="b-isotope-grid__item grid-item wordpress mockups animation">
                                                <a class="b-isotope-grid__inner" href="portfolio-1.html"><img src="<?php echo base_url(); ?>assets/media/content/gallery/360x360/2.jpg" alt="foto"><span class="b-isotope-grid__wrap-info"><span class="b-isotope-grid__info"><span class="b-isotope-grid__title">the glass bottle</span><span class="b-isotope-grid__categorie">branding / print</span></span>
                                                    </span>
                                                </a>
                                            </li>
                                            <li class="b-isotope-grid__item grid-item design print">
                                                <a class="b-isotope-grid__inner" href="portfolio-1.html"><img src="<?php echo base_url(); ?>assets/media/content/gallery/360x260/3.jpg" alt="foto"><span class="b-isotope-grid__wrap-info"><span class="b-isotope-grid__info"><span class="b-isotope-grid__title">the glass bottle</span><span class="b-isotope-grid__categorie">branding / print</span></span>
                                                    </span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end .b-isotope
                    </div>
                </div>
            </div>
        </section> -->
	<!-- end .section-default-->
	<section class="section-type-1 section-sm parallax area-bg area-bg_grad-2 area-bg_op_80">
		<div class="area-bg__inner">
			<div class="container">
				<div class="row">
					<div class="col-md-8">
						<h2 class="ui-title-block-3">Want to Know More about Us?</h2>
						<div class="ui-subtitle-block-2">we would love to tell you!</div>
					</div>
					<div class="col-md-4"><a class="btn btn-default btn-lg pull-right"
							href="industry-documents/PCTInfoSheet.pdf">View Company Overview</a></div>
				</div>
			</div>
		</div>
	</section>
	<!-- end .section-type-1-->

	<section class="section-type-4 section-default">
		<div class="container">
			<div class="row">
				<div class="col-md-6">
					<div class="ui-subtitle-block">in the real estate transaction</div>
					<h2 class="ui-title-block ui-title-block_light">How We Protect<br> Your Clients</h2>
					<div class="ui-decor-1 bg-primary"></div>

					<p>After your sales contract has been accepted, Pacific Coast Title Company will search all related
						public records to look for any problems with the home’s title. This search typically involves a
						review of land records going back many years. More than 1/3 of all title searches reveal a
						problem with the title that we will make sure is fixed before you go to closing. For instance, a
						previous owner may have had minor construction done on the property, but never fully paid the
						contractor. Or the previous owner may have failed to pay local or state taxes. Pacific Coast
						Title Company seeks to resolve problems like these before you go to closing. Once we issue a
						title policy, if for some reason any claim which is covered under your title policy is ever
						filed against your property, Contact Pacific Coast Title Company and we will work diligently to
						process and help resolve your claim.</p>

				</div>
				<div class="col-md-6">
					<div class="owl-carousel owl-theme owl-theme_mod-b enable-owl-carousel" data-pagination="true"
						data-navigation="false" data-single-item="true" data-auto-play="7000"
						data-transition-style="fade" data-main-text-animation="true" data-after-init-delay="3000"
						data-after-move-delay="1000" data-stop-on-hover="true"><img class="img-responsive"
							src="<?php echo base_url(); ?>assets/media/content/carousel-1/4.jpg" alt="foto">
						<!--<img class="img-responsive" src="<?php echo base_url(); ?>assets/media/content/carousel-1/1.png" alt="foto"><img class="img-responsive" src="<?php echo base_url(); ?>assets/media/content/carousel-1/1.png" alt="foto">-->
					</div>
					<!-- end carousel-->
				</div>
			</div>
		</div>
	</section>
	<!-- end .section-default-->
	<!-- b-progress width parallax-->

	<section class="section-type-8 parallax area-bg area-bg_grad-2">
		<div class="area-bg__inner">
			<div class="container">
				<div class="row">
					<div class="col-md-6">
						<div class="b-video player fixed-controls play-button">
							<video poster="<?php echo base_url(); ?>assets/media/components/b-video/poster-3.jpg">
								<source src="<?php echo base_url(); ?>assets/media/content/video/4.mp4"
									type="video/mp4">
							</video>
						</div>
						<!-- end .b-video-->
					</div>
					<div class="col-md-6">
						<div class="section-type-8__inner">
							<div class="ui-subtitle-block">Want to know...</div>
							<h2 class="ui-title-block ui-title-block_light">Why Real Estate Agents Trust Pacific Coast
								Title Company</h2>
							<div class="ui-decor-1 bg-white"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section><br><br><br><br>
	<!-- end .section-type-8-->


	<section class="section-type-4 scrollme-section">
		<div class="container">
			<div class="row">
				<div class="col-md-6">
					<div class="ui-subtitle-block">In real estate</div>
					<h2 class="ui-title-block ui-title-block_light"><span class="shuffle">Title Insurance is Your Best
							Bet.</span></h2>
					<div class="ui-decor-1 bg-primary"></div>

					<p>Dollar for dollar, title insurance is the best investment you can make to protect your
						interest in one of the most valuable assets you own: your home. Title insurance insures
						a real estate investment, unlocking its potential as a financial asset for the owner. As
						such, title insurance plays a major role in the confidence that lies at the heart of our
						nation’s real estate market and economy.</p><a class="btn btn-default btn-round btn_mrg-top_45"
						href="residential-title.html">learn more about title</a>
				</div>
				<div class="col-md-6">
					<div class="section-type-4__img">
						<div class="scrollme">
							<div class="animateme" data-when="enter" data-from="1" data-to="0" data-opacity="0"
								data-translatex="300" data-rotatez="0">
								<img src="<?php echo base_url(); ?>assets/media/content/657x498/2.jpg" alt="foto">
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<?php
        $this->load->view('layout/footer_above_section');
	?>
	
</body>

</html>
<?php
    $this->load->view('layout/footer');
?>

<script src="<?php echo base_url(); ?>assets/plugins/slider-pro/jquery.sliderPro.min.js"></script>