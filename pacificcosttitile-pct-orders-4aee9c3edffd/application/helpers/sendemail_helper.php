<?php 

if(!function_exists('send_email')){
	function send_email($fromemail, $from_name, $to, $subject, $content,$myPdf=array(),$ccTo=null,$bcc=array()){
		$instance = &get_instance();
		$instance->load->library('email');

		$config['protocol']     = 'smtp';
        $config['smtp_host']    = 'smtp.sendgrid.net';
        $config['smtp_port']    = '587';
        $config['smtp_timeout'] = '120';
        $config['smtp_user']    = 'apikey';
        $config['smtp_pass']    =  getenv('SENDGRID_API_KEY');
        $config['charset']      = 'utf-8';
        $config['newline']      = "\r\n";
        $config['mailtype']     = 'html'; 
        $config['validation']   = TRUE; 
		$instance->email->initialize($config);
		    

        $instance->email->initialize($config);
		
		$instance->email->from($fromemail, $from_name);
        $instance->email->to($to); 
        if(!is_null($ccTo)){
            $instance->email->cc($ccTo);
        }
        $instance->email->subject($subject);
        $instance->email->message($content);  

        if(isset($bcc) && !empty($bcc))
        {
            $instance->email->bcc($bcc);
        }


        foreach($myPdf as $file){
            $instance->email->attach($file);
        }
       
        if($instance->email->send())
        {
         	return true;
        }
        else
        {
          	return false;
        }
        
	}          
}



/*if(!function_exists('send_email')) {

	function send_email($fromemail, $from_name, $to, $subject, $content, $pdfs =array(), $ccs = array(), $bccs = array())
    {
        
        $email = new SendGrid\Mail\Mail();
        $email->setFrom($fromemail, $from_name);
        $email->setSubject($subject);
        $email->addTo($to, "To User");
        $ccEmails = array();
        $bccEmails = array();

        if(isset($ccs) && !empty($ccs)) {
           foreach($ccs as $cc) {
                $ccEmails[$cc] = '';
           }
           $email->addCcs($ccEmails);
        }

        if(isset($bccs) && !empty($bccs)) {
            foreach($bccs as $bcc) {
                $bccEmails[$bcc] = '';
            }
            $email->addBccs($bccEmails);
        }

        $email->addContent(
            "text/html", $content
        );
        if(!empty($pdfs)) {
            foreach($pdfs as $pdf) {
                $documentName = pathinfo($pdf);
                $email->addAttachment(
                    file_get_contents($pdf),
                    "application/pdf",
                    $documentName['basename'],
                    "attachment"
                );
            }
        }
    
        $sendgrid = new SendGrid(getenv('SENDGRID_API_KEY'));
        try {
            $response = $sendgrid->send($email);
            if($response->statusCode() == 202 || $response->statusCode() == 200) {
                return true;
            } else {
                return false;
            }
            
        } catch (Exception $e) {
           return false;
        }
        exit;  
	}          
}

?>*/
