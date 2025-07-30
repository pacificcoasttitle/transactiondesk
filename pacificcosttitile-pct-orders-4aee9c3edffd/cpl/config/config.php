<?php
	if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') 
	    $link = "https"; 
	else
	    $link = "http"; 
	  
	// Here append the common URL characters. 
	$link .= "://"; 
	  
	// Append the host(domain name, ip) to the URL. 
	$link .= $_SERVER['HTTP_HOST']; 
	  
	// Append the requested resource location to the URL 
	$link .= $_SERVER['REQUEST_URI']; 
	define("BASE_URL", $link);
	define('BASE_URL_MAIN','http://pct.com/');
?>