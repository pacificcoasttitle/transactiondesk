<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
use Knp\Snappy\Pdf;
class Snappy_pdf {
 
    public $pdf;
 
    public function __construct($options = null)
    {
    	$dir_name = FCPATH;
        $dir_name = str_replace('\\', '/', $dir_name);
    	if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $this->pdf = new Pdf($dir_name.'vendor/bin/wkhtmltopdf.exe.bat');
        } else {
            // $this->pdf = new Pdf($dir_name.'vendor/h4cc/wkhtmltopdf-amd64/bin/wkhtmltopdf-amd64');
            $this->pdf = new Pdf('/usr/local/bin/wkhtmltopdf');
        }

        if(!(is_array($options) && count($options))) {

	        $options = [
	                'margin-top'    => 0,
	                'margin-right'  => 0,
	                'margin-bottom' => 0,
	                'margin-left'   => 0,
	                'page-size' => 'Letter', 
	                'zoom'          => 1.285,
	                'load-error-handling'=>'ignore',
	                'load-media-error-handling'=>'ignore',
	                // 'disable-javascript'=> false
	            ];
        }

        foreach ($options as $key => $value) {
        	$this->pdf->setOption($key,$value);
        }

    }
}