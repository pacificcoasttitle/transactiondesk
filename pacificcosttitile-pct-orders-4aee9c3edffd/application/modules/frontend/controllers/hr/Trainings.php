<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class Trainings extends MX_Controller 
{
	function __construct() 
    {
        parent::__construct();
		$this->load->helper(
            array('file', 'url','form')
        );
        $this->load->library('session');
		$this->load->library('form_validation');
		$this->load->library('hr/template');
        $this->load->library('hr/common');
        $this->load->model('hr/hr'); 
        $this->common->is_user();
	}
	
	public function index()
	{
        $userdata = $this->session->userdata('hr_user');
        $data['errors'] = array();
        $data['success'] = array();
        if ($this->session->userdata('errors')) {
            $data['errors'] = $this->session->userdata('errors');
            $this->session->unset_userdata('errors');
        }
        if ($this->session->userdata('success')) {
            $data['success'] = $this->session->userdata('success');
            $this->session->unset_userdata('success');
        }
        $data['name'] = $userdata['name'];
		$data['title'] = 'HR-Center Trainings';
        $this->template->show("hr", "trainings", $data);
	}

    public function getTrainings()
    {
        $params = array();  $data = array();
        $params['is_frontend'] = 1;
		if (isset($_POST['draw']) && !empty($_POST['draw'])) {
			$params['draw'] = isset($_POST['draw']) && !empty($_POST['draw']) ? $_POST['draw'] : 10;
			$params['length'] = isset($_POST['length']) && !empty($_POST['length']) ? $_POST['length'] : 2;
			$params['start'] = isset($_POST['start']) && !empty($_POST['start']) ? $_POST['start'] : 0;
			$params['orderColumn'] = isset($_POST['order'][0]['column']) && !empty($_POST['order'][0]['column']) ? $_POST['order'][0]['column'] : 0;
			$params['orderDir'] = isset($_POST['order'][0]['dir']) && !empty($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 0;
			$params['searchvalue'] = isset($_POST['search']['value']) && !empty($_POST['search']['value']) ? $_POST['search']['value'] : '';
			$pageno = ($params['start'] / $params['length'])+1;
			$trainingsList = $this->common->getTrainings($params);
			$json_data['draw'] = intval( $params['draw'] );
		} else {
			$params['searchvalue'] = isset($_POST['keyword']) && !empty($_POST['keyword']) ? $_POST['keyword'] : '';
			$trainingsList = $this->common->getTrainings($params);
		}
		
		if (isset($trainingsList['data']) && !empty($trainingsList['data'])) {
			$i = $params['start'] + 1;
			foreach ($trainingsList['data'] as $training)  {
				$nestedData = array();
				$nestedData[] = $i;
                $nestedData[] = $training['name'];
                $nestedData[] = $training['description'];
				$update_date = '-';
                if ($training['is_complete'] == 1) {
                    $status = '<span class="badge-new badge-new-success">Completed</span>';
					if(strtotime($training['updated_at'])) {
						$update_date = $this->common->convertTimezone($training['updated_at']);
					}
                } else {
                    $status = '<span class="badge-new badge-new-info">Pending</span>';
                }
                $nestedData[] = $status;
                $nestedData[] = $update_date;
                $nestedData[] = "<div class='smart-forms'>
                                    <form action='".base_url()."hr/view-trainings-docs/".$training['id']."' method='POST'>
                                        <button style='height:29px;color: white;' class='button' type='submit'>View Documents</button>
                                    </form>
                                </div>";
				$data[] = $nestedData; 
				$i++; 
			}
		}

		$json_data['recordsTotal'] = intval( $trainingsList['recordsTotal'] );
		$json_data['recordsFiltered'] = intval( $trainingsList['recordsFiltered'] );
		$json_data['data'] = $data;
		echo json_encode($json_data);
    }

    public function viewTrainingsDocs()
    {
        $id = $this->uri->segment(3);
        $userdata = $this->session->userdata('hr_user');
        $this->load->model('admin/hr/training_model');
        $this->load->model('admin/hr/training_material_model');
        $this->load->model('admin/hr/training_status_model');
        $data['training_status'] = $this->training_status_model->get_many_by("(user_id = {$userdata['id']} and training_id ={$id})");
        $data['trainingMaterials'] = $this->training_model->with('materials')->get($id);
        $this->template->show("hr", "view_training_docs", $data);
    }
}
