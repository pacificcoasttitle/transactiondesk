<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class AgentResources extends MX_Controller {

    function __construct() 
    {
        parent::__construct(); 
    }

    function blankForms()
    {
        $data['title'] = 'Blank Forms | Pacific Coast Title Company';
		$this->load->view('layout/head',$data);
		$this->load->view('agentResources/blank_forms'); 
    }

    function educationalBooklets()
    {
        $data['title'] = 'Educational Booklets | Pacific Coast Title';
		$this->load->view('layout/head',$data);
		$this->load->view('agentResources/educational_booklets'); 
    }

    function flyerCenter()
    {
        $data['title'] = 'Training Center | Pacific Coast Title Company';
		$this->load->view('layout/head',$data);
		$this->load->view('agentResources/flyer_center'); 
    }

    function recordingFees()
    {
        $data['title'] = 'Blank Forms | Pacific Coast Title Company';
		$this->load->view('layout/head',$data);
		$this->load->view('agentResources/recording_fees'); 
    }

    function rateBook()
    {
        $data['title'] = 'Rate Book | Pacific Coast Title';
		$this->load->view('layout/head',$data);
		$this->load->view('agentResources/rate_book'); 
    }

    function trainingCenter()
    {
        $data['title'] = 'Training Center | Pacific Coast Title Company';
		$this->load->view('layout/head',$data);
		$this->load->view('agentResources/training_center'); 
    }
}