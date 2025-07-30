<?php
	$file_url = isset($file_url) && !empty($file_url) ? $file_url : '';
?>
<div class="l-main-content">
	<article class="b-post b-post-full clearfix">
		<div class="">
			<iframe id="legal-vesting-doc" src="<?php echo $file_url; ?>" width="825px" height="800px">
				This browser does not support PDFs. Please download the PDF to view it: Download PDF
			</iframe>
		</div>
	</article>
</div>
<script type="text/javascript">
$(document).ready(function() {
	var file_url = "<?php echo isset($file_url) && !empty($file_url) ? $file_url : ''; ?>";
	if(file_url == '')
	{
		var serviceId = "<?php echo isset($serviceId) && !empty($serviceId) ? $serviceId : ''; ?>";
		var fileNumber = "<?php echo isset($file_number) && !empty($file_number) ? $file_number : ''; ?>";
		
		imageCreateRequest(serviceId,4,fileNumber);	
	}
});

function imageCreateRequest(serviceId,methodId,fileNumber)
{    
	$('#page-preloader').css('background-color', 'rgba(0,0,0,.5)');
	$('#page-preloader').css('display', 'block');
    $.ajax({
        url: base_url+'imageCreateRequest',
        data: {
            serviceId: serviceId,
        },
        dataType: "xml",
        type: "POST"
    })
        .done(function(response, textStatus, jqXHR) {

            var responseStatus = $(response).find('ReturnStatus').text();
            
            if (responseStatus == 'Failed') 
            {      
            } 
            else if (responseStatus == 'Success') 
            {
                $requestId = $(response).find('RequestID').text();
                getRequestStatus($requestId,methodId,fileNumber);
            }
        })
        .fail(function(err) {
        });
}

function getRequestStatus(requestId,methodId,fileNumber)
{
	$('#page-preloader').css('background-color', 'rgba(0,0,0,.5)');
	$('#page-preloader').css('display', 'block');
    $.ajax({
        url: base_url+'getRequestStatus',
        data: {
            requestId: requestId
        },
        dataType: "xml",
        type:"POST"
    })
        .done(function(response, textStatus, jqXHR) {

            var responseStatus = $(response).find('ReturnStatus').text();
            
            if (responseStatus == 'Failed') 
            {
            } 
            else if (responseStatus == 'Success') 
            {
                $resultId = $(response).find("RequestId:first").text();
                generateImage($resultId,methodId,fileNumber);
            }
        })
        .fail(function(err) {
        });
}

function generateImage(requestId,methodId,fileNumber)
{
	$('#page-preloader').css('background-color', 'rgba(0,0,0,.5)');
	$('#page-preloader').css('display', 'block');
    $.ajax({
        url: base_url+'generateImage',
        data: {
            requestId: requestId,
            methodId: methodId,
            fileNumber: fileNumber,
        },
        dataType: "xml",
        type: "POST"
    })
        .done(function(response, textStatus, jqXHR) {

            var responseStatus = $(response).find('ReturnStatus').text();
            
            if (responseStatus == 'Failed') 
            {                
            } 
            else if (responseStatus == 'Success') 
            {
                <?php if (env('AWS_ENABLE_FLAG') == 1) { ?>
                    var url = '<?php echo env('AWS_PATH');?>'+'legal-vesting/'+fileNumber+'.pdf';
                <?php } else { ?>
                    var url = base_url+'uploads/legal-vesting/'+fileNumber+'.pdf';
                <?php } ?>
            	$('#legal-vesting-doc').attr('src',url);
            	$('#page-preloader').css('display', 'none');
            }
        })
        .fail(function(err) {
            
        });
}
</script>