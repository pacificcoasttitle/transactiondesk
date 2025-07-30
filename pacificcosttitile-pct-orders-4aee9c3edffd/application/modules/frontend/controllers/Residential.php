<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class Residential extends MX_Controller {

    function __construct() 
    {
        parent::__construct(); 
    }

    function title()
    {
        $data['title'] = 'Residential Title | Pacific Coast Title Company';
		$this->load->view('layout/head',$data);
		$this->load->view('residential/title'); 
    }

    function escrowSettlement()
    {
        $data['title'] = 'Escrow Settlement | Pacific Coast Title Company';
		$this->load->view('layout/head',$data);
		$this->load->view('residential/escrow_settlement'); 
    }

    function titleInsurance()
    {
        $data['title'] = 'What is Title Insurance | Pacific Coast Title Company';
		$this->load->view('layout/head',$data);
		$this->load->view('residential/title_insurance'); 
    }

    function benefitsTitleInsurance()
    {
        $data['title'] = 'Benefits of Title Insurance| Pacific Coast Title Company';
		$this->load->view('layout/head',$data);
		$this->load->view('residential/benefits_title_insurance'); 
    }

    function lifeOfTitleSearch()
    {
        $data['title'] = 'Life of a Title Search | Pacific Coast Title Company';
		$this->load->view('layout/head',$data);
		$this->load->view('residential/life_of_title_search'); 
    }

    function topTitleProblems()
    {
        $data['title'] = 'Top 10 Title Problems| Pacific Coast Title Company';
		$this->load->view('layout/head',$data);
		$this->load->view('residential/top_title_problems'); 
    }

    function whatIsEscrow()
    {
        $data['title'] = 'Life of a Title Search | Pacific Coast Title Company';
		$this->load->view('layout/head',$data);
		$this->load->view('residential/what_is_escrow'); 
    }

    function lifeOfEscrow()
    {
        $data['title'] = 'Life of a Title Search | Pacific Coast Title Company';
		$this->load->view('layout/head',$data);
		$this->load->view('residential/life_of_escrow'); 
    }

    function escrowTerms()
    {
        $data['title'] = 'Our Role | Pacific Coast Title Company';
		$this->load->view('layout/head',$data);
		$this->load->view('residential/escrow_terms'); 
    }
}