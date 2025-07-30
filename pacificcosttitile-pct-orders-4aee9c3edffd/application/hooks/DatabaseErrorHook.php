<?php
defined('BASEPATH') or exit('No direct script access allowed');

class DatabaseErrorHook
{

    public function handle_db_error()
    {
        $CI = &get_instance();
        if ($CI->db->conn_id === false) {
            // Database connection error
            show_error('Database connection error', 500);
        } else {
            // Database query error
            // print_r($CI->db->error());die;
            if ($CI->db->error()['code'] != 0) {
                $CI->load->view('errors/hooks/custom_db_error');
                echo $CI->output->get_output();
                exit;
            }
        }
    }
}
