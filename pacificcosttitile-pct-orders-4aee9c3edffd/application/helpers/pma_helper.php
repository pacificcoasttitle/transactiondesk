<?php
if(!function_exists('dollars')){
	function dollars($dollarAmount) {
	    // Prepend '$' symbol to figures that represent price
	    if ((string)$dollarAmount == '') {
	        return '';
	    }
	    if ($dollarAmount == '0') {
	        return '0';
	    }

	    else {
	        return '$' . $dollarAmount;
	    }
	}
}
if(!function_exists('toPercent')){
	function toPercent($num) {
        // Convert .197 to 19.7%
        if ($num == '') {
            return $num;
        }
        $percent = (float)$num * 100;
        $percent .= '%';
        return $percent; 
    }
}
if(!function_exists('formatName')){
	function formatName($name) {
        // Order names, e.g., change 'Smith, John' to 'John Smith'
        if (empty($name)) {
            return '';
        }
        if (strpos($name, ';') !== false) {
            return $name;
        }
        $names = explode(", ", $name);
        
        $name ='';
        if ( isset($names[1]) && !empty($names[1]) ) 
        {
            $name = isset($names[0]) && !empty($names[0]) ? $names[1] . ' ' . $names[0] : $names[1];
        }
        else if(isset($names[0]) && !empty($names[0]))
        {
            $name = $names[0];
        }

        // $name = $names[1] . " " . $names[0];
        return $name;
    }
}
if(!function_exists('properCase')){

    function properCase($pronoun) {
        // Assign proper case to pronouns, e.g., change JOHN SMITH to John Smith
        // PHP's typical solution, ucfirst(strtolower($last_name)), lowercases letters after apostraphes (e.g., Patrick O'connell)
        $pronoun = str_replace('\' ', '\'', ucwords(str_replace('\'', '\' ', strtolower($pronoun))));
        $pronoun = str_replace('Ca,', 'CA,', $pronoun); // State in addresses should be capitalized
        return $pronoun; 
    }
}
if(!function_exists('formatDate')){
	function formatDate($date) {
		// var_dump($date);
        // Make dates readable, e.g., change '20131225' to '12/25/2013'
        if (empty($date)) {
            return '';
        }
        $year = substr($date, 0, 4);
        $month = substr($date, 4, 2);
        if ($month[0] == '0') {
            $month = $month[1];
        }
        $day = substr($date, 6, 2); 
        if ($day[0] == '0') {
            $day = $day[1];
        }
        $cleanDate = $month . '/' . $day . '/' . $year;
        return $cleanDate;
    }
}
if(!function_exists('minMax')){
	function minMax($housingTrait, $query, $report187, $compIndexes = null) {
		// Find min, median and max values for area property characteristics
		$areaRange = array();
		$lowestHighest = array();
		if(isset($compIndexes) && !empty($compIndexes))
		{
			for ($k = 0; $k < 9; $k ++) {
				if(isset($compIndexes[$k]) && !empty($compIndexes[$k]))
				{
					$i = intval($compIndexes[$k]) ? intval($compIndexes[$k]) : $k;
					$quantity = $report187->ComparableSalesReport->ComparableSales->ComparableSale[$i]->$housingTrait;
					$areaRange[] = (float)($quantity);
				}
				  
			}
		}
		else {

			foreach ($report187->ComparableSalesReport->ComparableSales->ComparableSale as $comparableSale) {

				$quantity = $comparableSale->$housingTrait;
				$areaRange[] = (float)($quantity);
			}

		}
		
		$return_array = [
			'min'=>0,
			'max'=>0,
			'median'=>0,
		];
		 
		$areaRange = array_filter($areaRange);
		if (empty($areaRange)) {
			if($query == 'all') {
				return $return_array;
			}
			else {
				return 0;
			}
		}
		else {
			switch ($query) {
				case 'all':
					$return_array['min'] = min($areaRange);
					$return_array['max'] = max($areaRange);

					rsort($areaRange); 
	            	$middle = round(count($areaRange) / 2); 
	            	$return_array['median'] = $areaRange[$middle-1];
	            	$value = $return_array;
					break;
				case 'min':
					$value = min($areaRange);
					break;
				case 'max':
					$value = max($areaRange);
					break;
				case 'median':
					rsort($areaRange); 
	            	$middle = round(count($areaRange) / 2); 
	            	$value = $areaRange[$middle-1];
	            	break;
	            }
			return $value;
		}
	}
}
if(!function_exists('createMap')){
	function createMap($report187, $compIndexes=null) 
	{
		$mapRequest = 'http://maps.googleapis.com/maps/api/staticmap?key=AIzaSyCHjPxFsA0PjR0uE6N0dpDxsjD6Gr3DtuI&zoom=15&size=571x396&maptype=hybrid&sensor=false&markers=color:red|';
		$propertyLat = (string)$report187->PropertyProfile->PropertyCharacteristics->Latitude[0];
		$propertyLong = (string)$report187->PropertyProfile->PropertyCharacteristics->Longitude[0];
		$mapRequest .= $propertyLat . ',' . $propertyLong . '&';
		if(isset($compIndexes) && !empty($compIndexes))
		{
			for ($k = 0; $k < 9; $k ++) 
			{
				if(isset($compIndexes[$k]) && !empty($compIndexes[$k]))
				{
					$i = intval($compIndexes[$k]) ? intval($compIndexes[$k]) : $k;
					$j = (string)($k + 1); 
					$mapRequest .= 'markers=color:blue|label:' . $j . '|';
					$lat = (string)$report187->ComparableSalesReport->ComparableSales->ComparableSale[$i]->Latitude;
					$long = (string)$report187->ComparableSalesReport->ComparableSales->ComparableSale[$i]->Longitude;
					$mapRequest .= $lat . ',' .$long . '&';
				}
				
			}
		}
		else {
			$k = 0;
			foreach ($report187->ComparableSalesReport->ComparableSales->ComparableSale as $comparableSale) {
					$k++;
					$j = (string)($k); 
					$mapRequest .= 'markers=color:blue|label:' . $j . '|';
					$lat = (string)$comparableSale->Latitude;
					$long = (string)$comparableSale->Longitude;
					$mapRequest .= $lat . ',' .$long . '&';
					if($k>=8) {
						break;
					}
			}

		}
		
		return $mapRequest;
	}
}
if(!function_exists('cleanLegal')){
	function cleanLegal($legal) {
		// Make the legal descriptions more readable
		$legal = str_replace(':', ': ', $legal);
		return $legal;
	}
}