<?php
session_start();

if(!isset( $_SESSION['username'])) {
    header("Location: pma-login.php");
    exit();
}
?>



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

		<!-- Stylesheets -->
		<link rel="stylesheet" href="pma/stylesheets/pagelayout.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="pma/stylesheets/jquery.colorbox.css" type="text/css" />
		<link rel="stylesheet" type="text/css" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
		<link rel="stylesheet" href="pma/stylesheets/media-queries.css"  media="screen" type="text/css" />
		<link rel="stylesheet" href="pma/stylesheets/tablesorter-blue.css"  media="screen" type="text/css" />


		<!-- Javascripts -->
		<script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.js"></script>
		<script type="text/javascript" src="pma/javascripts/jquery-1.8.2.min.js"></script>
		<script type="text/javascript" src="pma/javascripts/jquery.flexslider.js"></script>
		<script type="text/javascript" src="pma/javascripts/jquery.tweet.js"></script>
		<script type="text/javascript" src="pma/javascripts/jquery.colorbox.js"></script>
		<script type="text/javascript" src="pma/javascripts/jquery.easing-1.3.pack.js"></script>
		<script type="text/javascript" src="pma/javascripts/superfish.js"></script>
		<script type="text/javascript" src="pma/javascripts/hoverIntent.js"></script>
		<script type="text/javascript" src="pma/javascripts/jquery.quicksand.js"></script>
		<script type="text/javascript" src="pma/javascripts/tabs.js"></script>
		<script type="text/javascript" src="pma/javascripts/custom.js"></script>
		<script type="text/javascript" src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
        <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyCoMfJn9Q37LUYQucbUdgWF8JGWRuTZlt4&libraries=places&sensor=false"></script>
		<script type="text/javascript" src="pma/javascripts/repdropdown.js"></script>
		<script type="text/javascript" src="pma/javascripts/jquery.tablesorter.min.js"></script> 
		<script type="text/javascript" src="pma/javascripts/pma.js"></script>
				
		<!--[if lt IE 9]>
			<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
	</head>
	<body style="background:#e4e4e4;">
		<!-- START Header -->
		<div class="headerWrap">
			<header>
				<div class="grid_12 clearfix">
					<!-- START Logo -->
					<div id="Logo">
						<a href="index.html"><img src="pma/images/wl.png" alt="Pacific Coast Title Company" /></a>
					</div>
					<!-- END Logo -->
					
					<!-- START Menu -->
				
					<!-- END Menu -->			
				</div>
				<div class="pma-logout"><a href="pma-logout.php">Logout</a></div>
			</header>
		</div>
		<div class="baseHeader"></div>
		<!-- END Header -->
		
		<!-- START Page title -->
		<div class="grid_12 pageTitle">
			<h1>Concierge Property Profile <span class="info"> //  A Property Profile for our exclusive clients. </span></h1>
		</div>
		<!-- END Page title -->

		<!-- START Main content -->
		<div id="PageWrapper" class="grid_12 clearfix">

			<!-- START Sidebar -->
			<div class="grid_4 alpha clearfix">	
				<h4>Total Ran</h4>
				<div class="pma-total"></div>
				<ul class="events">
					
					<li class="clearfix">
						<div class="info">
							<h5></h5>
						</div>
					</li>
				</ul>
				<br />
				<h4>Accumilated Cost</h4>
				<div class="accrued-cost"></div>
				<ul class="events">
					
					<li class="clearfix">
						<div class="info">
							<h5></h5>
						</div>
					</li>
				</ul>
				<br />
				<table class="light rep-table">
					<colgroup>
						<col span="2" />
					</colgroup>
					<thead>
						<tr>
							<th>Rep</th>
							<th>PMA's</th>
							<th>Cost</th>
						
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
				
			</div>
			<!-- END Sidebar -->
			
			<!-- START Main column -->
			<div class="grid_8 omega">
				
				
			<div class="pma-error"></div>	
			<ul  class="formpma">
				<li class="twoColumnPartPropertypma js-pma-input1">
					<label for="subject" class="js-search-label1">Property Address</label>
					<input id="js-property-search" placeholder=" " class="formpma js-pma-address" type="text" value="" name="subject">
					<input id="" placeholder=" " class="formpma js-pma-apn" type="text" value="" name="subject">
				</li>		
				<li class="twoColumnPartPropertypma1 js-pma-input2">
					<label for="subject" class = "js-search-label2">City</label>
					<input id="subject" class="formpma1 js-pma-city js-pma-fips" type="text" value="" name="subject">
				</li>
			</ul>
			<a class="button redButton largeButton js-find-property js-search-button" href="#">Find Property</a> 
			<div class="switch-search js-switch-search"><a href="#">Switch to APN Search</a></div>
				<div class="clear"></div>
				<h4 style="margin-top:25px;">Search Results</h4>
				<div class="progress-bar"></div>
				<div class="separator7"></div>
				<div class="address-result">
					<table class="dark result-table">
						<colgroup>
							<col span="3" />
						</colgroup>
						<thead>
							<tr>
								<th>APN</th>
								<th>Address</th>
								<th>City</th>
								<th>Run PMA</th>
							
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><span class="result-apn"></span></td>
								<td><span class="result-address"></span></td>
								<td><span class="result-city"></span></td>
								<td><a class="button blueButton js-run-pma-button" href="#">Run PMA</a></td>
							</tr>
							
						</tbody>
					</table>
				</div> <!-- end address-result -->

				<div id="run-pma-dialog" title="Report Info">
				  	<form id="run-pma-form">
				  		<fieldset>
				  		<div class="custom-pma-field">
				  			<label for="rep-name">Rep:</label>
				  			<select name="rep-name" type="text" id="rep-name">
							</select>
						</div>
				  		<div class="custom-pma-field">
						    <label for="realtor-name">Realtor Name:</label>
						    <input type="text" name="realtor-name" id="realtor-name" class="text ui-widget-content ui-corner-all" />
						</div>
						<div class="custom-pma-field">
					   		<label for="realtor-company">Realtor Company:</label>
					   		<input type="text" name="realtor-company" id="realtor-company" value="" class="text ui-widget-content ui-corner-all" />
						</div>
						<div class="custom-pma-field">
						    <label for="realtor-address">Realtor Address:</label>
						    <input type="text" name="realtor-address" id="realtor-address" value="" class="text ui-widget-content ui-corner-all" />
				  		</div>
				  		<div class="custom-pma-field">
				  			<label for="tabs">Include Tabs?</label>
				  			<select id="tabs" name="tabs">
				  				<option value="Yes">Yes</option>
				  				<option value="No">No</option>
				  			</select>
				  		</div>
				  		<div class="custom-pma-field include-docs">
				  			<label for="docs">Include Docs?</label>
				  			<select id="include-docs" name="include-docs">
				  				<option value="Yes">Yes</option>
				  				<option value="No" selected="selected">No</option>
				  			</select>
				  		</div>
				  		<div class="custom-pma-field">
				  			<label for="comps">Manually Select Comparables?</label>
				  			<select id="include-comps" name="include-comps">
				  				<option value="Yes">Yes</option>
				  				<option value="No" selected="selected">No</option>
				  			</select>
				  		</div>
				  		</fieldset>
				  		<div class='modal-submit'>
				  			<a class="button blueButton pma-modal-submit"><span>Submit</span><a/>
				  		</div>
				  </form>
				</div>

				<div id="comps-dialog">
				  	<form id="comps-form">
				  	    <div class="comps-header">
				  	    	<p>Please select up to 8 comparable sales to feature prominently in your property profile. You can also ensure that properties will <em>not</em> be included in the profile by clicking the corresponding red "X" when you hover over a property.</p>
				  		</div>
				  		<div class="comps-error"></div>
				  		<table id="comps-table">
				  		<colgroup>
					        <col span="1" style="width: 20%;">
					        <col span="1" style="width: 13%;">
					        <col span="1" style="width: 12%;">
					        <col span="1" style="width: 11%;">
					        <col span="1" style="width: 12%;">
					        <col span="1" style="width: 12%;">
					        <col span="1" style="width: 12%;">
					        <col span="1" style="width: 8%;">
				        </colgroup>
				  		<thead>
				  		<tr>
							<th>Address</th>
							<th>Living Area</th>
							<th>Lot Size</th>
							<th>BDs/BRs</th>
							<th>Sale Date</th>
							<th>Distance</th>
							<th>Sale Price</th>
						</tr>
				  		</thead>
				  		<tbody>	
				  		</tbody>
				  		</table>
				  		<div class="comps-error"></div>
				  		<div class='modal-submit'>
				  			<a class="button blueButton comps-submit" href="#"><span>Submit</span></a>
				  		</div>
				  </form>
				</div>

				<h4 style="margin-top:25px;">Recent Concierge Property Profiles</h4>
				<div class="separator7"></div>
				
				
				<table class="dark recent-reports">
					<colgroup>
						<col span="4" />
					</colgroup>
					<thead>
						<tr>
							<th>Date</th>
							<th>PCT Rep</th>
							<th>Address</th>
							<th>Download</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
			<!-- END Main column -->
		</div>
		<!-- END Main content  -->
		
		<!-- START Footer -->
		<footer>
			<div class="grid_12 clearfix">
				<div class="grid_3 alpha">
					
				</div>

				<div class="grid_3">
					
				</div>

				<div class="grid_3">
				
				</div>
				
				<div class="grid_3 omega">
					
				</div>
				<div class="clear"></div>
			</div>
			
			<div class="copyright">
				<div class="grid_12">
					
				</div>
			</div>		
		</footer>
		<!-- END Footer -->
	</body>
</html>