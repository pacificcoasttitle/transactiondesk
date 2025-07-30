<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Pacific Coast Title Company</title> 
		
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<meta name="google-site-verification" content="5vF_hADNjtdZIFBJ-jeLi-SQGFR6jKEYw0w-EjtFJfs" />
		<meta name="Description" content="Pacific Coast Title Company Specializes in Residential & Commercial Title Insurance. We take pride in the success of our clients.">
		<meta name="robots" content="NOODP">

		<!-- Favicons -->
		<link rel="shortcut icon" href="favicon.ico">
		<link rel="apple-touch-icon-precomposed" sizes="144x144" href="apple-touch-icon-144x144-precomposed.png">
		<link rel="apple-touch-icon-precomposed" sizes="114x114" href="apple-touch-icon-114x114-precomposed.png">
		<link rel="apple-touch-icon-precomposed" sizes="72x72" href="apple-touch-icon-72x72-precomposed.png">
		<link rel="apple-touch-icon-precomposed" href="apple-touch-icon-precomposed.png">
		
		<!-- Google fonts -->
		<link href='http://fonts.googleapis.com/css?family=Droid+Sans:400,700' rel='stylesheet' type='text/css'>
		<link href='http://fonts.googleapis.com/css?family=Droid+Serif:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
		<link href='https://fonts.googleapis.com/css?family=Montserrat&display=swap' rel='stylesheet'>

		<!-- Stylesheets -->
		<link rel="stylesheet" href="pma/stylesheets/pagelayout.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="pma/stylesheets/jquery.colorbox.css" type="text/css" />
		<link rel="stylesheet" href="pma/stylesheets/media-queries.css"  media="screen" type="text/css" />
				
		<!--[if lt IE 9]>
			<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
	</head>
	<body>
	<div class="bg">
		<!-- START Header -->
		<div class="headerWrap">
			<header>
				<div class="grid_12 clearfix">
					<!-- START Logo -->
					<div id="Logo">
						 <!-- <a href="index.html"><img src="http://www.pct.com/assets/media/general/logo2.png" alt="Pacific Coast Title Company" /></a> -->
					</div>
					<!-- END Logo -->
					
					<!-- START Menu -->
					<nav id="MenuBar" class="grid_9 omega floatRight">
						<ul class="navList clearfix">
							
							
							
							
							
						</ul>
					</nav>
					<!-- END Menu -->			
				</div>
			</header>
		</div>
		<div class="baseHeader"></div>
		<!-- END Header -->

		<!-- START Main content -->
		<div id="PageWrapper" class="grid_12 clearfix">
			<div class="errorNotFound clearfix">
				<?php if(isset($_GET['msg'])): ?>
				<div class="error_message"><?php echo $_GET['msg']; ?></div>
				<?php endif; ?>
				<div class="errorImg"></div>
				<div class="info">
					<h2>Concierge Profile</h2>
					<div class="separator2"></div>
					<div id="contact">
					<div id="message"></div>
					<br />
					<form method="post" action="checklogin.php" name="login" id="login">
						<div align="center" class="sep">
							<label for="username" class="txt" accesskey="U"></label>
							<input class="login" name="username" placeholder="Name" type="text" id="username" size="30" value="" />
						</div>
			
						<div align="center" class="sep">
							<label for="password" class="txt" accesskey="E"></label>
							<input class="login" name="password" placeholder="Password" type="password" id="password" size="30" value="" />
						</div>
								
						<div style="text-align:center; margin-top:25px;">
							<button type="submit" class="button mediumButtonL" id="submit" name="submit">Login</button>
						</div>
					</form>
				</div>
				</div>
			</div>	
							
		</div>
		<!-- END Main content -->
		
		<!-- START Footer -->
		<footer>
			<div class="grid_12 clearfix">
				<div class="grid_3 alpha">
				<!-- 	<h5>About Us</h5>
					<blockquote>
						<p>We specialize Residential & Commercial Title Insurance and we work hard behind the scenes to make sure your experience with us is a satisfying one. Your success is our success.</p>
					</blockquote> -->
				</div>

				<div class="grid_3">
				<!-- 	<h5>Technology Tools</h5>
					<p>Here are some quick links</p>
					<ul class="squareList">
						<li><a href="Pacific-Coast-Agent.html">PCT Netsheet App</a></li>
						<li><a href="Mobile-Title.html">Mobile Title App</a></li>
						<li><a href="Toolbox.html">Marketing Toolbox</a></li>
						<li><a href="Video-Center.html">Learning Center</a></li>
					</ul> -->
				</div>

				<div class="grid_3">
					
				</div>
				
				<div class="grid_3 omega">
				<!-- 	<h5>We're social!</h5>
					<p>Connect with us on our different social media sites.</p>
				<ul class="socialLinks clearfix">
					<li><a href="https://twitter.com/mypct" title="Folllow us on Twitter" class="twitter"></a></li>
					<li><a href="https://www.facebook.com/PacificCoastTitleCompany" title="Join us on Facebook" class="facebook"></a></li>
					<li><a href="http://www.linkedin.com/company/pacific-coast-title-company?trk=top_nav_home" title="Join us on LinkedIn" class="linkedin"></a></li>
				</ul>	-->		
				</div>
				<div class="clear"></div>
			</div>
			
			<div class="copyright">
				<div class="grid_12">
					
				</div>
			</div>		
		</footer>
		<!-- END Footer -->

		<!-- Javascripts -->
		<script type="text/javascript" src="pma/javascripts/jquery-1.8.2.min.js"></script>
		<script type="text/javascript" src="pma/javascripts/jquery.flexslider.js"></script>
		<script type="text/javascript" src="pma/javascripts/jquery.tweet.js"></script>
		<script type="text/javascript" src="pma/javascripts/jquery.colorbox.js"></script>
		<script type="text/javascript" src="pma/javascripts/jquery.easing-1.3.pack.js"></script>
		<script type="text/javascript" src="pma/javascripts/superfish.js"></script>
		<script type="text/javascript" src="pma/javascripts/hoverIntent.js"></script>
		<script type="text/javascript" src="pma/javascripts/jquery.quicksand.js"></script>
		<script type="text/javascript" src="pma/javascripts/tabs.js"></script>
		<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
		<script type="text/javascript" src="pma/javascripts/custom.js"></script>		
	</div>
	</body>
</html>