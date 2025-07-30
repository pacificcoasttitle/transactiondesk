<!DOCTYPE html>
<html lang="en">

<head>
	
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="preconnect" href="https://fonts.gstatic.com">
	
	<link rel="stylesheet" href="<?php echo base_url('assets/frontend/css/label/style.css');?>">
    
</head>

<body>
    <table class="bordered" cellspacing="0" cellpadding="0">
        <?php foreach(array_chunk($pdfInfos,3) as $pdfInfo_details) {?>
            <tr>
                <?php $i = 0;?>
                <?php foreach($pdfInfo_details as $pdfInfo) { ?>
                    <?php if($i != 0) { ?>
                        <td class="separator" />
                    <?php } ?>
                    <td>
                        <?php echo $pdfInfo['line_1'];?>
                        <br/>
                        <?php if($or_current_resident == '1') { ?>
                            Or Current Resident
                            <br/>
                        <?php } ?>
                        <?php echo $pdfInfo['line_2'];?>
                        <br/>
                        <?php echo $pdfInfo['line_3'];?>
                    </td>

                <?php $i++; } ?>	
            </tr>
        <?php } ?>
    </table>
</body>

</html>


 
	