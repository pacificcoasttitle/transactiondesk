<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class M_pdf {
 
    public $param;
    public $pdf;
 
    public function __construct($param = '"en-GB-x","A4","","",10,10,10,10,6,3')
    {
    	require_once(APPPATH."third_party/mpdf/mpdf.php");
        $this->param =$param;
        $this->pdf = new mPDF('',array(216, 279),'','',10,10,10,10);
        // $mpdf = new mPDF('',    // mode - default ''
        //             '',    // format - A4, for example, default ''
        //             0,     // font size - default 0
        //             '',    // default font family
        //             10,    // margin_left
        //             10,    // margin right
        //             30,     // margin top
        //             30,    // margin bottom
        //             9,     // margin header
        //             9,     // margin footer
        //             'L'  // L - landscape, P - portrait
        //         );  
    }
}