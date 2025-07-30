<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class Commercial extends MX_Controller {

    function __construct() 
    {
        parent::__construct(); 
    }

    function commercialServices()
    {
        $data['title'] = 'Commercial Title | Pacific Coast Title Company';
		$this->load->view('layout/head',$data);
		$this->load->view('commercial/commercial_services'); 
    }

    function commercialResources()
    {
        $data['title'] = 'Commercial Title | Pacific Coast Title Company';
		$this->load->view('layout/head',$data);
		$this->load->view('commercial/commercial_resources'); 
    }

    function commercialExpertise()
    {
        $data['title'] = 'Commercial Title | Pacific Coast Title Company';
		$this->load->view('layout/head',$data);
		$this->load->view('commercial/commercial_expertise'); 
    }

    
}