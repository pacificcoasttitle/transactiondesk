<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class Contact extends MX_Controller {

    function __construct() 
    {
        parent::__construct(); 
    }

    function downey()
    {
        $data['title'] = 'Downey Branch | Pacific Coast Title Company';
		$this->load->view('layout/head',$data);
		$this->load->view('contact/downey'); 
    }

    function orange()
    {
        $data['title'] = 'Orange Branch | Pacific Coast Title Company';
		$this->load->view('layout/head',$data);
		$this->load->view('contact/orange'); 
    }

    function oxnard()
    {
        $data['title'] = 'Oxnard Branch | Pacific Coast Title Company';
		$this->load->view('layout/head',$data);
		$this->load->view('contact/oxnard'); 
    }

    function sandiego()
    {
        $data['title'] = 'San Diego Branch | Pacific Coast Title Company';
		$this->load->view('layout/head',$data);
		$this->load->view('contact/sandiego'); 
    }
    function glendale()
    {
        $data['title'] = 'Glendale Branch | Pacific Coast Title Company';
		$this->load->view('layout/head',$data);
		$this->load->view('contact/glendale'); 
    }

}