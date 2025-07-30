<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class Aboutus extends MX_Controller {

    function __construct() 
    {
        parent::__construct(); 
    }

    function role()
    {
        $data['title'] = 'Our Role | Pacific Coast Title Company';
		$this->load->view('layout/head',$data);
		$this->load->view('about/role'); 
    }

    function protect()
    {
        $data['title'] = 'Protecting You | Pacific Coast Title Company';
		$this->load->view('layout/head',$data);
		$this->load->view('about/protect'); 
    }

    function pacific()
    {
        $data['title'] = 'Why Pacific Coast Title | Pacific Coast Title Company';
		$this->load->view('layout/head',$data);
		$this->load->view('about/pacific'); 
    }

    function about()
    {
        $data['title'] = 'About Us | Pacific Coast Title Company';
		$this->load->view('layout/head',$data);
		$this->load->view('about/about'); 
    }

    function joinOurTeam()
    {
        $data['title'] = 'Contact Us | Pacific Coast Title Company';
		$this->load->view('layout/head',$data);
		$this->load->view('about/join_our_team'); 
    }
}