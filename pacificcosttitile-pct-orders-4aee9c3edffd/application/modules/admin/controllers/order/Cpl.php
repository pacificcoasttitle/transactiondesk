<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cpl extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(
            array('file', 'url', 'form')
        );
        $this->load->library('order/adminTemplate');
        $this->load->library('form_validation');
        $this->load->library('order/common');

        $this->common->is_admin();
    }

    public function northAmericanBranches()
    {
        $data = array();
        $data['title'] = 'North American Branches';
        $this->load->library('order/natic');
        $data['branchesData'] = $this->natic->getBranches();
        $this->admintemplate->addJS(base_url('assets/vendor/jquery/jquery.min.js'));
        $this->admintemplate->addJS(base_url('assets/backend/js/branches.js'));
        $this->admintemplate->show("order/cpl", "north_american_branches", $data);
        // $this->load->view('order/layout/header', $data);
        // $this->load->view('order/cpl/north_american_branches', $data);
        // $this->load->view('order/layout/footer', $data);
    }

    public function domaBranches()
    {
        $data = array();
        $data['title'] = 'North American Branches';
        $this->load->library('order/natic');
        $data['branchesData'] = $this->natic->getDomaBranches();
        $this->admintemplate->addJS(base_url('assets/vendor/jquery/jquery.min.js'));
        $this->admintemplate->addJS(base_url('assets/backend/js/branches.js'));
        $this->admintemplate->show("order/cpl", "doma_branches", $data);
    }

    public function getNorthAmericanBranches()
    {
        $this->load->library('order/natic');
        $branchesData = $this->natic->getBranchesFromApi();
        if (!empty($branchesData)) {
            /** Save user Activity */
            $activity = 'North American Branches Refreshed.';
            $this->common->logAdminActivity($activity);
            /** End save user activity */
            $data = array('status' => 'success', 'msg' => '');
        } else {
            $data = array('status' => 'error', 'msg' => 'Somethine went wrong.Please try again.');
        }
        echo json_encode($data);
    }

    public function getDomaBranches()
    {
        $this->load->library('order/natic');
        $branchesData = $this->natic->getBranchesFromApi('doma');
        if (!empty($branchesData)) {
            /** Save user Activity */
            $activity = 'North American Branches Refreshed. (Doma)';
            $this->common->logAdminActivity($activity);
            /** End save user activity */
            $data = array('status' => 'success', 'msg' => '');
        } else {
            $data = array('status' => 'error', 'msg' => 'Somethine went wrong.Please try again.');
        }
        echo json_encode($data);
    }

    public function westcorBranches()
    {
        $data = array();
        $data['title'] = 'Westcor Branches';
        $this->load->library('order/westcor');
        $data['branchesData'] = $this->westcor->getBranches();
        $this->admintemplate->addJS(base_url('assets/vendor/jquery/jquery.min.js'));
        $this->admintemplate->addJS(base_url('assets/backend/js/branches.js'));
        $this->admintemplate->show("order/cpl", "westcor_branches", $data);
        // $this->load->view('order/layout/header', $data);
        // $this->load->view('order/cpl/westcor_branches', $data);
        // $this->load->view('order/layout/footer', $data);
    }

    public function getWestcorBranches()
    {
        $this->load->library('order/westcor');
        $branchesData = $this->westcor->getBranchesFromApi();
        if (!empty($branchesData)) {
            /** Save user Activity */
            $activity = 'Westcor Branches Refreshed.';
            $this->common->logAdminActivity($activity);
            /** End save user activity */
            $data = array('status' => 'success', 'msg' => '');
        } else {
            $data = array('status' => 'error', 'msg' => 'Somethine went wrong.Please try again.');
        }
        echo json_encode($data);
    }

    public function commonwealthBranches()
    {
        $data = array();
        $data['title'] = 'Commonwealth Branches';
        $this->load->library('order/fnf');
        $data['branchesData'] = $this->fnf->getAgents();
        $this->admintemplate->addJS(base_url('assets/vendor/jquery/jquery.min.js'));
        $this->admintemplate->addJS(base_url('assets/backend/js/branches.js'));
        $this->admintemplate->show("order/cpl", "commonwealth_branches", $data);
        // $this->load->view('order/layout/header', $data);
        // $this->load->view('order/cpl/commonwealth_branches', $data);
        // $this->load->view('order/layout/footer', $data);
    }

    public function getCommonwealthBranches()
    {
        $this->load->library('order/fnf');
        $branchesData = $this->fnf->getAgentsFromApi(array('order_id' => 0));
        if (!empty($branchesData)) {
            /** Save user Activity */
            $activity = 'Common wealth Branches Refreshed.';
            $this->common->logAdminActivity($activity);
            /** End save user activity */
            $data = array('status' => 'success', 'msg' => '');
        } else {
            $data = array('status' => 'error', 'msg' => 'Somethine went wrong.Please try again.');
        }
        echo json_encode($data);
    }
}
