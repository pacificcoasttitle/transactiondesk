<?php
class Pct_hr_employee_time_tracking_model extends MY_Model 
{
    public $_table = 'pct_hr_employee_time_tracking';

	public $belongs_to = array('user' => array( 'model' => 'hr/users_model','primary_key' => 'employee_id' ));

	public function track_time($user_id,$clock_event,$is_break = 0)
	{
		$track_data = array();
		$track_data['employee_id'] = $user_id;
		$this->load->library('hr/common');
		$converted_time = $this->common->convertTimezone(date('Y-m-d H:i:s'),'Y-m-d H:i:s','America/Los_Angeles');
		if($clock_event == 'IN') {
			$track_data['time_in'] = $converted_time;
			$this->insert($track_data);
			return true;
		}
		else {
			$record = $this->order_by('id','desc')->get_by($track_data);
			if($record && empty($record->time_out)) {
				$update_data = array('time_out'=>$converted_time,'is_break'=>$is_break);
				$this->update($record->id,$update_data);
				return true;
			}
		}
		return false;
		
	}

	public function get_clock_event($user_id)
	{
		$track_data = array();
		$track_data['employee_id'] = $user_id;
		$record = $this->order_by('id','desc')->get_by($track_data);
		if($record && empty($record->time_out)) {
			return 'OUT';
		}
		else {
			return 'IN';
		}
	}

	public function get_today_working($user_id)
	{
		//SELECT employee_id,DATE(time_in),SUM(TIMESTAMPDIFF(SECOND,time_in,time_out)) AS time_diff FROM pct_hr_employee_time_tracking GROUP BY employee_id,DATE(time_in);
		$converted_date = $this->common->convertTimezone(date('Y-m-d H:i:s'),'Y-m-d','America/Los_Angeles');
		$this->db->select('SUM(TIMESTAMPDIFF(SECOND,time_in,time_out)) AS time_diff');
		$this->db->where('DATE(time_in)',$converted_date);
		$this->db->where('employee_id',$user_id);
		$query = $this->db->get($this->_table);
		$result = $query->row();
		if($result && !empty($result->time_diff)) {
			return $result->time_diff;
		}
		else {
			return 0;
		}
	}

	public function get_last_time($user_id)
	{
		$track_data = array();
		$track_data['employee_id'] = $user_id;
		// $track_data['DATE(time_in)'] = date('Y-m-d');
		$record = $this->order_by('id','desc')->get_by($track_data);
		
		if($record && empty($record->time_out)) {
			$converted_date = $this->common->convertTimezone(date('Y-m-d H:i:s'),'Y-m-d H:i:s','America/Los_Angeles');
			return (strtotime($converted_date) - strtotime($record->time_in));
		}
		else {
			return 0;
		}
	}

	public function get_time_sheet($start_date,$end_date,$employee_id)
	{
		$track_data = array();
		$track_data['employee_id'] = $employee_id;
		$track_data["DATE(time_in) >= "] = date('Y-m-d',strtotime($start_date));
		$track_data["DATE(time_in) <= "] = date('Y-m-d',strtotime($end_date));
		$track_data['employee_id'] = $employee_id;
		// $track_data['DATE(time_in)'] = date('Y-m-d');
		$data = $this->order_by('time_in','asc')->get_many_by($track_data);
		
		return $data;
	}

	public function get_ot_hours()
	{
		
		
		// $this->db->select('employee_id,DATE(time_in) AS record_date,SUM(TIMESTAMPDIFF(SECOND,time_in,time_out)) AS time_diff');
		// $this->db->group_by('employee_id,DATE(time_in)');
		// $this->db->having('time_diff > 28800');
		// $this->db->order_by('time_in','DESC');
		// $query = $this->db->get($this->_table);
		// $result = $query->result();
		// var_dump($result);die;
		$this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
		$sub_query = 'CONCAT(`employee_id`,"__",DATE(`time_in`)) IN (SELECT CONCAT(`employee_id`,"__",DATE(`time_in`)) FROM `pct_hr_employee_time_tracking` GROUP BY `employee_id`,DATE(`time_in`) HAVING SUM(TIMESTAMPDIFF(SECOND,`time_in`,`time_out`))>'.(8*60*60).')';
		
		$this->db->where($sub_query,NULL,FALSE);

		$data = $this->order_by('time_in','asc')->with('user')->get_all();
		return $data;
		
	}
}
