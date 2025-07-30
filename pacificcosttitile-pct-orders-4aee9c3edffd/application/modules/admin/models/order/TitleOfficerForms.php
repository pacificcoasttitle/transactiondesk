<?php
class TitleOfficerForms extends CI_Model 
{
	function __construct() 
    {
        $this->table = 'pct_order_title_officers_forms';
    }

    public function getTitleOfficersForForm($formId) 
    {
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->where('form_id', $formId);
        $query = $this->db->get();
        return $query->result_array();
    }
}
