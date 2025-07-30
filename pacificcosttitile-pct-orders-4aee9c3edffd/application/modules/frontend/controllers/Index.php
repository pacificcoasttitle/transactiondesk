<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class Index extends MX_Controller {

    function __construct() 
    {
        parent::__construct();
    }

    function index() 
    {
        $data['title'] = 'Pacific Coast Title Company | Residential & Commercial Title & Escrow Service';
		$this->load->view('home', $data);
    }

}