<?php
class Pma_data_model extends MY_Model 
{
    public $_table = 'pct_pma_data';
    public $belongs_to = array( 'sales_rep' => array( 'model' => 'pma/customer_basic_detail_model','primary_key' => 'sales_rep' ));
}
