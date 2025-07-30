<!DOCTYPE html>
<html>
<head>
    <title>Document</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link
        href="https://fonts.googleapis.com/css?family=Crimson+Text:wght@400;600;700&family=Open+Sans:wght@400;600;700&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="<?php echo base_url('assets/pma/report/style.css'); ?>">


</head>
<body>
	<?php

	for ($i=1; $i <=8 ; $i++) { 
		if($i==7) {
			if(count($comparableSales) > 3) {
				$this->load->view('pma/report/pages/pdf'.$i);
			}
		}
		else {
			$this->load->view('pma/report/pages/pdf'.$i);
		}
	}
	?>
</body>
</html>