<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RulesManager extends MX_Controller {

	public function __construct()
    {
        parent::__construct();
        $this->load->helper(
            array('file', 'url','form')
        );
        $this->load->library('order/adminTemplate');
        $this->load->library('form_validation');
        $this->load->model('order/rulesManager_model');
        $this->load->model('order/counties_model');
        $this->load->library('order/common');
        $this->common->is_admin();
    }

    public function index()
	{
		$data = array();
        $data['title'] = 'PCT Order: Rules Manager';
        
        $this->admintemplate->show("order/home", "rules_manager", $data);
        // $this->load->view('order/layout/header', $data);
        // $this->load->view('order/home/rules_manager', $data);
        // $this->load->view('order/layout/footer', $data);
	}

    public function get_rules()
    {
        $params = array();

        if(isset($_POST['draw']) && !empty($_POST['draw']))
        {
            $params['draw'] = isset($_POST['draw']) && !empty($_POST['draw']) ? $_POST['draw'] : 10;
            $params['length'] = isset($_POST['length']) && !empty($_POST['length']) ? $_POST['length'] : 10;
            $params['start'] = isset($_POST['start']) && !empty($_POST['start']) ? $_POST['start'] : 0;
            $params['orderColumn'] = isset($_POST['order'][0]['column']) && !empty($_POST['order'][0]['column']) ? $_POST['order'][0]['column'] : 0;
            $params['orderDir'] = isset($_POST['order'][0]['dir']) && !empty($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 0;

            $params['searchvalue'] = isset($_POST['search']['value']) && !empty($_POST['search']['value']) ? $_POST['search']['value'] : '';
            

            $pageno = ($params['start'] / $params['length'])+1;

            $rules_list = $this->rulesManager_model->getRules($params);

            $json_data['draw'] = intval( $params['draw'] );
        }
        else
        {
            $params['searchvalue'] = isset($_POST['keyword']) && !empty($_POST['keyword']) ? $_POST['keyword'] : '';
            $rules_list = $this->rulesManager_model->getRules($params);          
        }
        $data = array();

        if(isset($rules_list['data']) && !empty($rules_list['data']))
        {
            $con =  array(
                        'where' => array(
                            'status' => 1,
                        )
                    );

            $counties = $this->counties_model->get_counties($con);
            
            $count = $params['start'] + 1;
            foreach ($rules_list['data'] as $key => $value) 
            {
                $nestedData=array();
                
                $nestedData[] = $count;
                $nestedData[] = $value['title'];
                $counties_list = '';

                if(isset($counties) && !empty($counties))
                {
                    foreach ($counties as $k => $v) 
                    {
                        $selected = '';
                        if(set_value('county') && in_array($v['id'], set_value('county')))  
                        {
                            $selected = 'selected';
                        } 
                        else 
                        {
                            $selectedCounties = explode(',', $value['value']);
                            if(in_array($v['id'], $selectedCounties))  {
                                $selected = 'selected';
                            }
                        }

                        $counties_list .= '<option '.$selected.' value='.$v['id'].'>'.$v['county'].'</option>';
                    }
                }
                $countiesList ='<select id="rules_county" data-id = '.$value['id'].' name="county[]" class="selectpicker" multiple data-live-search="true">'.$counties_list.'</select>';

                $nestedData[] = $countiesList;
               
                $data[] = $nestedData;
                $count++;
                
            }
        }
        $json_data['recordsTotal'] = intval( $rules_list['recordsTotal'] );
        $json_data['recordsFiltered'] = intval( $rules_list['recordsFiltered'] );
        $json_data['data'] = $data;
        echo json_encode($json_data);
    }

    public function updateCounties()
    {
        $counties = $this->input->post('counties');
        $ruleId = $this->input->post('rule_id');

        if(isset($ruleId) && !empty($ruleId))
        {
           $list = implode(",",$counties);
           $rulesData = array(
            'value' => $list
           );
           $updateCondition = array(
                'id' => $ruleId,
            );
            $update = $this->rulesManager_model->update($rulesData, $updateCondition);

            if ($update) {
                /** Save user activity */
                $rules = $this->db->select('title')->from('pct_order_rules_manager')->where('id', $ruleId)->get()->row_array(); 
                $activity = 'Rules value for title:  "' . $rules['title'] . '"  updated';
                $this->common->logAdminActivity($activity);
                /** End save user activity */
                $data = array('status' => 'success','message'=> 'Rule updated successfully.');
            }
            else
            {
                $data = array('status' => 'error','message'=> 'Rule not updated.');
            }
        }
        else
        {
            $data = array('status' => 'error','message'=> 'Rule not updated.');
        }

        echo json_encode($data);
    }
}