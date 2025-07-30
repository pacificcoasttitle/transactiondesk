<?php
	$file_url = isset($file_url) && !empty($file_url) ? $file_url : '';
?>
<div class="l-main-content">
	<article class="b-post b-post-full clearfix">
		<div class="">
            <div class="pma-error alert alert-danger" style="display:none;"></div>
			<iframe id="plat-map-doc" src="<?php echo $file_url; ?>" width="825px" height="800px">
				This browser does not support PDFs. Please download the PDF to view it: Download PDF
			</iframe>
		</div>
	</article>
</div>

<script type="text/javascript">
$(document).ready(function() {
	reportData = {};
	var request = '';

	var file_url = "<?php echo isset($file_url) && !empty($file_url) ? $file_url : ''; ?>";
	if(file_url == '')
	{
		var address = "<?php echo isset($address) && !empty($address) ? $address : ''; ?>";
		var locale = "<?php echo isset($locale) && !empty($locale) ? $locale : ''; ?>";
		var zip = "<?php echo isset($zip) && !empty($zip) ? $zip : ''; ?>";
		
		getPlat(address,zip,locale);	
	}
});
// run query for plat map report 
function getPlat(address,zip,locale) {
	$('#page-preloader').css('background-color', 'rgba(0,0,0,.5)');
	$('#page-preloader').css('display', 'block');
    var request = 'http://api.sitexdata.com/sitexapi/sitexapi.asmx/AddressSearch?';
    dataObj = {};
    dataObj.Address = address;
    dataObj.LastLine = locale.toString();
    dataObj.ClientReference = '<CustCompFilter><CompNum>8</CompNum><MonthsBack>12</MonthsBack></CustCompFilter>';
    dataObj.OwnerName = '';

    request += $.param(dataObj);
    $.ajax({
        url: base_url+'getSearchResults?',
        // url: 'http://cardbanana.net/demo/jerry/lp/lp/lp/proxy.php',
        data: {
            requrl: request + '?&reportType=111'
        },
        dataType: 'xml'
    })
        .done(function(response, textStatus, jqXHR) {
            var responseStatus = $(response).find('StatusCode').text();
            if (responseStatus != 'OK') 
            {
                displayError(responseStatus);
            }
            else
            {
                reportUrl = $(response).find('ReportURL').text();
                reportData.report111 = reportUrl;
                get111();
            }
            
        });
}

function get111() {
    $('#page-preloader').css('background-color', 'rgba(0,0,0,.5)');
	$('#page-preloader').css('display', 'block');
    $.ajax({
        url: base_url+'getSearchResults?',
        data: {
            requrl: reportData.report111,
        },
        dataType: "xml",
        success: function(xml) {
            reportXML = xml;
            parse111();
        },
        error: function() {
            console.log("An error occurred while processing XML file.");
        }
    });
}

function parse111() 
{
	$('#page-preloader').css('background-color', 'rgba(0,0,0,.5)');
	$('#page-preloader').css('display', 'block');
    var imagedata = $(reportXML).find("Content").text();
    var file_number = <?php echo isset($file_number) && !empty($file_number) ? $file_number : ''; ?>;

    $.ajax({
        url: base_url+'/generate-plat-map',
        data: {
            imagedata: imagedata,
            file_number: file_number,
        },
        type: "POST",
        success: function(response) {
            var result = jQuery.parseJSON(response);
            if(result.status == 'error')
			{
                $('#plat-map-doc').attr('src','');
			}
			else if(result.status == 'success')
			{
				var url = result.plat_map_url;
				$('#plat-map-doc').attr('src',url);
            	
			}
            $('#page-preloader').css('display', 'none');
        },
        error: function() {
           // console.log("An error occurred while processing XML file.");
        }
    });
}

// display error returned in API query
function displayError(responseStatus) {
    $('#page-preloader').css('background-color', 'rgba(0,0,0,.5)');
    $('#page-preloader').css('display', 'block');
    // determine and display specific error
    var errorDisplay = "";
    switch (responseStatus) {
        case 'NM':
            error = 'No exact match';
            break;
        case 'NC':
            error = 'Out of coverage area';
            break;
        case 'IP':
            error = 'Invalid IP';
            break;
        case 'IK':
            error = 'Invalid key';
            break;
        case 'IR':
            error = 'Invalid report type';
            break;
        case 'IN':
            error = 'Invalid property address. Please try once with Zip Code instead of City.';
            break;
        case 'CR':
            error = 'No credits';
            break;
        case 'NH':
            error = 'Valid address, but no hit';
            notifyAdminPlat('No Hit on property search');
            break;
        default:
            error = "Error"
    }
    $('.pma-error').text(error);
    var img = base_url+'assets/frontend/images/no_match.jpeg';
    $('#plat-map-doc').attr('src',img);
    $('#plat-map-doc').css('width', '800px');
    $('#plat-map-doc').css('height', '600px');
    $('#page-preloader').css('display', 'none');
    $('.pma-error').show();
}
</script>