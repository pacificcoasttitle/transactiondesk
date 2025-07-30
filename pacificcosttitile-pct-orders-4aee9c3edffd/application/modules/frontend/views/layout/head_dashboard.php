<!DOCTYPE html>
<html lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title><?php echo $title; ?></title>
    <meta content="We specialize in Residential, Commercial Title & Escrow Services" name="description">
    <meta content="" name="keywords">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="telephone=no" name="format-detection">
    <meta name="HandheldFriendly" content="true">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/master.css">
    <link href="<?php echo base_url(); ?>assets/backend/vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="<?php echo base_url(); ?>favicon.ico">
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/frontend/js/jquery-1.9.1.min.js"></script>
</head>
<style>
	.pagination {
		overflow: hidden;
	}
	.pagination > li > a {
		width: 42px;
		height: 42px;
		margin-right: 8px;
		padding-top: 14px;
		border: 1px solid rgba(221, 221, 221, 0.5);
	}
	.pagination > .active > a, .pagination > .active > span, .pagination > .active > a:hover, .pagination > .active > span:hover, .pagination > .active > a:focus, .pagination > .active > span:focus {
		background-color: #6533d7;
		background-image: -webkit-linear-gradient(305deg, #6533d7 0%, #339bd7 100%);
	}
	.pagination > li > a:hover, .pagination > li > span:hover, .pagination > li > a:focus, .pagination > li > span:focus {
		background-color: #6533d7;
		background-image: -webkit-linear-gradient(305deg, #6533d7 0%, #339bd7 100%);
	}
	.dataTables_paginate {
		padding-top: 50px;
		padding-bottom: 100px;
		text-align: right;
	}
	.typography-section {
		padding-bottom: 0px;
	}

	.dataTables_filter input {
	    height: calc(1.5em + 0.5rem + 2px); 
	    background: #fff;
	    position: relative;
	    vertical-align: top;
	    border: 1px solid #cbd2d6;
	    display: -moz-inline-stack;
	    display: inline-block;
	    color: #34495E;
	    outline: none;
	    height: 42px;
	    width: 96%;
	    zoom: 1;
	    border-radius: 3px;
	    margin: 0;
	    font-size: 14px;
	    font-family: "Roboto", Arial, Helvetica, sans-serif;
	    font-weight: 400;
	}

	.button-color {
	  color: #888888;
	}
	
	.button-color-green {
	  background: rgb(0, 102, 68);
	}
</style>