<?php 

	if (!isset($_SESSION)) session_start(); 
	if(!$_POST) exit;
	
	include dirname(__FILE__).'/settings/settings2.php';
	include dirname(__FILE__).'/functions/emailValidation.php';
	
	
	/* Current Date Year
	------------------------------- */		
	$currYear = date("Y");	
	
/*	---------------------------------------------------------------------------
	: Register all form field variables here
	--------------------------------------------------------------------------- */	
	$OrderNumber = strip_tags(trim($_POST["OrderNumber"]));	
	$LenderName = strip_tags(trim($_POST["LenderName"]));
	$LenderAddress = strip_tags(trim($_POST["LenderAddress"]));
	$LenderCity = strip_tags(trim($_POST["LenderCity"]));
	$LenderState = strip_tags(trim($_POST["LenderSt"]));
	$LenderZip = strip_tags(trim($_POST["LenderZip"]));
	$LoanNumber = strip_tags(trim($_POST["LoanNumber"]));
	$LenderPhone = strip_tags(trim($_POST["LenderPhone"]));
	$EmailTo = strip_tags(trim($_POST["EmailTo"]));
	$BorrowerNames = strip_tags(trim($_POST["BorrowerNames"]));
	$PropertyAddress = strip_tags(trim($_POST["PropertyAddress"]));
	$PropertyCity = strip_tags(trim($_POST["PropertyCity"]));
	$PropertyZip = strip_tags(trim($_POST["PropertyZip"]));
	$PropertySt = strip_tags(trim($_POST["PropertySt"]));


	
	$order_file = uniqid();
	$order_upload = $order_file.$_FILES['orderfiles']['name'];	
	
/*	----------------------------------------------------------------------
	: Prepare form field variables for CSV export
	----------------------------------------------------------------------- */	
	if($generateCSV == true){
		$csvFile = $csvFileName;	
		$csvData = array(
			"$sendername",
			"$emailaddress",
			"$telephone",
			"$senderwebsite",
			"$orderservices",
			"$orderbudget",
			"$ordertimeframe"			
		);
	}

/*	-------------------------------------------------------------------------
	: Prepare serverside validation 
	------------------------------------------------------------------------- */
	$errors = array();

	if(empty($OrderNumber))
	{
		$errors[] = "Enter order number";
	}

	if(empty($LoanNumber))
	{
		$errors[] = "Enter loan number";
	}

	if(empty($LenderName))
	{
		$errors[] = "Enter lender's name";
	}

	if(empty($LenderAddress))
	{
		$errors[] = "Enter lender address";
	}	

	if(empty($LenderCity))
	{
		$errors[] = "Enter lender city";
	}

	if(empty($LenderState))
	{
		$errors[] = "Enter lender state";
	}

	if(empty($LenderZip))
	{
		$errors[] = "Enter lender zip";
	}

	if(empty($BorrowerNames))
	{
		$errors[] = "Enter borrower name";
	}

	if(empty($PropertyAddress))
	{
		$errors[] = "Enter borrower address";
	}

	if(empty($PropertyCity))
	{
		$errors[] = "Enter borrower city";
	}

	if(empty($PropertySt))
	{
		$errors[] = "Enter borrower state";
	}

	if(empty($PropertyZip))
	{
		$errors[] = "Enter borrower zip";
	}
	
	//validate email address
	if(isset($_POST["EmailTo"])){
		if (!$EmailTo) {
			$errors[] = "Enter email cpl to";
		} else if (!validEmail($EmailTo)) {
			$errors[] = "Please enter valid email cpl to";
		}
	}	
	
	/*//validate file uploads
	if(isset($_FILES['orderfiles'])) {
		// maximum file size :: 2MB
		$maxsize    =  2097152; 
		// File must be attached
		if (empty($_FILES['orderfiles']['name'])) {
			$errors[] = "You must browse or attach a file.";
		}
		// File size must be 2MB or less
		if ($_FILES['orderfiles']['size'] > $maxsize) {
			$errors[] = "File uploaded is too large. Try 2MB or less.";
		}
		// Detect allowed file extentions
		$valid_file_extensions = array(".jpg", ".jpeg", ".png");
		$file_extension = strrchr($_FILES["orderfiles"]["name"], ".");
		// Check that the uploaded file is actually an image
		if (!in_array($file_extension, $valid_file_extensions)) {
			$errors[] = "Please upload a jpg or png image file.";
		}		
	}	
	
	//validate message / comment
	if(isset($_POST["sendermessage"])){
		if (strlen($sendermessage) < 10) {
			if (!$sendermessage) {
				$errors[] = "You must enter a message.";
			} else {
				$errors[] = "Message must be at least 10 characters.";
			}
		}
	}
	
	// validate security captcha 
	if(isset($_POST["captcha"])){
		if (!$captcha) {
			$errors[] = "You must enter the captcha code";
		} else if (($captcha) != $_SESSION['gfm_captcha']) {
			$errors[] = "Captcha code is incorrect";
		}
	}*/
	
	//In case there are errors, output them in a list
	if ($errors) {
		$errortext = "";
		foreach ($errors as $error) {
			$errortext .= '<li>'. $error . "</li>";
		}
		echo '<div class="alert notification alert-error">The following errors occured:<br><ul>'. $errortext .'</ul></div>';
	
	} else{		
		
			// Store data to DB
			include dirname(__FILE__).'/../config/database.php';

			if($conn)
			{
				if(empty($_POST['id']))
				{
					$time = date("Y-m-d H:i:s");
					$sql = "INSERT INTO `tbl_lenders` (`name`,`address`,`city`,`state`,`zip`,`status`,`created_at`) VALUES ('".$LenderName."','".$LenderAddress."','".$LenderCity."','".$LenderState."','".$LenderZip."',1, '".$time."');";

					if ($conn->query($sql) === TRUE) 
					{
						$id = $conn->insert_id;

						if($id)
						{

							$query = "INSERT INTO `tbl_lenders_details` (lender_id,order_no,loan_no,owner_name,borrower_address, borrower_city,borrower_state,borrower_zip,email_cpl_to, status,created_at) VALUES ('".$id."','".$OrderNumber."','".$LoanNumber."','".$BorrowerNames."','".$PropertyAddress."','".$PropertyCity."','".$PropertySt."','".$PropertyZip."','".$EmailTo."',1,'".$time."');";

							$conn->query($query);
						}					    
					} 
					else 
					{
					    echo "Error: " . $sql . "<br>" . $conn->error;
					}
				}
				else
				{
					//update details table
					$lender_id = isset($_POST['id']) && !empty($_POST['id']) ? $_POST['id'] : '';

					$time = date("Y-m-d H:i:s");

					$query = "INSERT INTO `tbl_lenders_details` (lender_id,order_no,loan_no,owner_name,borrower_address, borrower_city,borrower_state,borrower_zip,email_cpl_to, status,created_at) VALUES ('".$lender_id."','".$OrderNumber."','".$LoanNumber."','".$BorrowerNames."','".$PropertyAddress."','".$PropertyCity."','".$PropertySt."','".$PropertyZip."','".$EmailTo."',1,'".$time."');";

					$conn->query($query);
					
					//update details table
				}
				
			}
			// Store data to DB	

		if ($_FILES['orderfiles']['error'] == 0) {
			move_uploaded_file($_FILES['orderfiles']['tmp_name'], '../smuploads/' .$order_upload);	
		
			include dirname(__FILE__).'/phpmailer/PHPMailerAutoload.php';
			include dirname(__FILE__).'/templates/smartmessage2.php';
				
			$mail = new PHPMailer();
			$mail->isSendmail();
			$mail->IsHTML(true);
			$mail->setFrom($emailaddress,$sendername);
			$mail->CharSet = "UTF-8";
			$mail->Encoding = "base64";
			$mail->Timeout = 200;
			$mail->ContentType = "text/html";
			$mail->addAddress($receiver_email, $receiver_name);
			$mail->Subject = $receiver_subject;
			$mail->AddAttachment('../smuploads/'.$order_upload);	
			$mail->Body = $message;
			$mail->AltBody = "Use an HTML compatible email client";
					
			// For multiple email recepients from the form 
			// Simply change recepients from false to true
			// Then enter the recipients email addresses
			// echo $message;
			$recipients = false;
			if($recipients == true){
				$recipients = array(
					"ghernandez@pct.com" => "Jerry",
					
				);
				
				foreach($recipients as $email => $name){
					$mail->AddBCC($email, $name);
				}	
			}
			
			if($mail->Send()) {
			/*	-----------------------------------------------------------------
				: Generate the CSV file and post values if its true
				----------------------------------------------------------------- */		
				if($generateCSV == true){	
					if (file_exists($csvFile)) {
						$csvFileData = fopen($csvFile, 'a');
						fputcsv($csvFileData, $csvData );
					} else {
						$csvFileData = fopen($csvFile, 'a'); 
						$headerRowFields = array(
							"Sender Name",
							"Email Address",
							"Telephone",
							"Website",
							"Services",
							"Budget",
							"Time Frame"										
						);
						fputcsv($csvFileData,$headerRowFields);
						fputcsv($csvFileData, $csvData );
					}
					fclose($csvFileData);
				}
				
			/*	---------------------------------------------------------------------
				: Send the auto responder message if its true
				--------------------------------------------------------------------- */
				if($autoResponder == true){
				
					include dirname(__FILE__).'/templates/autoresponder.php';
					
					$automail = new PHPMailer();
					$automail->isSendmail();
					$automail->setFrom($receiver_email,$receiver_name);
					$automail->isHTML(true);                                 
					$automail->CharSet = "UTF-8";
					$automail->Encoding = "base64";
					$automail->Timeout = 200;
					$automail->ContentType = "text/html";
					$automail->AddAddress($emailaddress, $EmailTo, $sendername);
					$automail->Subject = "Thank you for contacting us";
					$automail->Body = $automessage;
					$automail->AltBody = "Use an HTML compatible email client";
					$automail->Send();	 
				}
				
				if($redirectForm == true){
					echo '<script>setTimeout(function () { window.location.replace("'.$redirectForm_url.'") }, 8000); </script>';
				}
							
			  	echo '<div class="alert notification alert-success">Your CPL request is being submitted. We will begin processing it immediately.</div>'; 
			  
				// Start delete function 
				// Automatically deletes files from the smuploads folder after successful sending
				// You can remove this function if you want to keep uploads on your server
				$files = glob('../smuploads/*'); 
				foreach($files as $file){ 
				  if(is_file($file))
					unlink($file); 
				}	  
			  
				} 
				else {
					echo '<div class="alert notification alert-error">Message not sent - server error occured!</div>';	
				}
		}
	}
?>