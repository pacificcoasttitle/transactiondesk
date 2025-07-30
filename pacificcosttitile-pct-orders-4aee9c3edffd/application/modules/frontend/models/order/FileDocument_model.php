<?php
class FileDocument_model extends MY_Model 
{
    public $_table = 'pct_file_documents';

    public function get_forms()
    {
        $userdata = $this->session->userdata('user');
        $this->db->select('*')
            ->from('pct_file_documents')
            ->join('pct_order_title_officers_forms', 'pct_file_documents.id = pct_order_title_officers_forms.form_id');
        $this->db->where('pct_order_title_officers_forms.user_id', $userdata['id']); 
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }
}
