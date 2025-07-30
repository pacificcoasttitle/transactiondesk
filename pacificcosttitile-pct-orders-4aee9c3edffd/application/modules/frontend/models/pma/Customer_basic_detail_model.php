<?php
class Customer_basic_detail_model extends MY_Model 
{
    public $has_many = array( 'pma' => array( 'model' => 'pma/pma_data_model','primary_key' => 'sales_rep' ) );
}
