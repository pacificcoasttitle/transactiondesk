<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class Adobe extends MX_Controller {

	public function __construct() {
        parent::__construct();
        $this->load->model('order/apiLogs');
        $this->load->library('order/order');
        $this->load->model('order/document');
        $this->load->helper('sendemail');
    }

    
    public function getDataFromAdobe()
    {
    	ini_set('max_execution_time', 0); 
		ini_set('memory_limit','2048M');
		$json = file_get_contents('php://input');
		if ($json) {
			$logId = $this->apiLogs->syncLogs(0,'adobe', 'webhook_data','https://api.na3.adobesign.com/', array('ReceiveSearchDataService'=>true), array());
			$this->apiLogs->syncLogs(0, 'adobe', 'webhook_data', 'https://api.na3.adobesign.com/', array(), $json, 0, $logId);
			$dir_name = APPPATH.'logs/adobe';

			if (!is_dir($dir_name)) {
				mkdir($dir_name);
			}

			$file = APPPATH.'logs/adobe/response.log';

			if (file_exists($file)) {
				$fh = fopen($file, 'a');
			} else {
				$fh = fopen($file, 'w');
			}

			fwrite($fh, $json."\n");
			fclose($fh);
			$data = json_decode($json,TRUE);
		} else {
			$result['message'] = "Empty response received.";
		}
    	echo json_encode($result);
    }
}



