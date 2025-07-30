<?php
include dirname(__FILE__).'/config/database.php';

// Get search term 
$searchTerm = $_GET['term']; 
 
// Fetch matched data from the database 
$query = $conn->query("SELECT * FROM tbl_lenders WHERE name LIKE '%".$searchTerm."%' AND status = 1 ORDER BY name ASC"); 
 
// Generate array with skills data 
$lenderInfo = array(); 
if($query->num_rows > 0){ 
    while($row = $query->fetch_assoc()){ 
        $data['id'] = isset($row['id']) && !empty($row['id']) ? $row['id'] : ''; 

        // $data['value'] = isset($row['name']) && !empty($row['name']) ? $row['name'].' - '.$row['city'] : ''; ;
        
        $value = '';
        if(isset($row['name']) && !empty($row['name']))
        {
        	$value = $row['name'];

        	if(isset($row['city']) && !empty($row['city']))
        	{
        		$value .= ' - '.$row['city'];
        	}
        }
        $data['value'] = $value;

        $data['address'] = isset($row['address']) && !empty($row['address']) ? $row['address'] : '';
        $data['city'] = isset($row['city']) && !empty($row['city']) ? $row['city'] : '';
        $data['state'] = isset($row['state']) && !empty($row['state']) ? $row['state'] : '';
        $data['zip'] = isset($row['zip']) && !empty($row['zip']) ? $row['zip'] : '';
        array_push($lenderInfo, $data); 
    } 
} 

// Return results as json encoded array 
echo json_encode($lenderInfo); 
?>