<?php


include("../mpdf.php");

$mpdf=new mPDF('','Letter','','',0,-5,0,-5); 

//==============================================================
$html = '


<img src="tux.svg" width="100%" />

';

$mpdf->WriteHTML($html);

$mpdf->Output(); exit;



?>