<?php
$file = fopen("rep-list.txt", "r") or exit("Unable to open file!");
$reps = array();
$repDropdown = '<option value=""></option>'; //array();
$repTable = '';
//Output a line of the file until the end is reached
while(!feof($file))
  {
  $rep = fgets($file);
  $rep = rtrim($rep);
  $repDropdown .= '<option value="' . $rep . '">' . $rep . '</option>';
  $repTable .=  '<tr><td>' . $rep . '</td><td></td><td></td></tr>';
  }
  $reps[0] = $repDropdown;
  $reps[1] = $repTable;
fclose($file);

echo json_encode($reps);
?>