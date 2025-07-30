$(document).ready(function() {
    if(customer_number)
    {
        var content = "<h3>Your Customer Number is:"+customer_number+"</h3>";
        $("#showOrderCustomerNumber").html(content);
        $("#showOrderCustomernumberModal").modal('show');
    }
});

if($('#legalDescription').length || $('#vestingInformation').length)
{
	createService4();
}

if($('#taxInformation').length)
{
	createService3();
}

function createService4()
{
	/*var fipCode = localStorage.getItem('fipCode');
	var address = localStorage.getItem('address');
	var city = localStorage.getItem('city');*/

	$.ajax({
        url: 'php/createservice.php',
        data: {
            fipCode: fipCode,
            address: address,
            city: city,
            methodId: 4,
        },
        dataType: "xml"
    })
    	.done(function(response, textStatus, jqXHR) {

            var responseStatus = $(response).find('ReturnStatus').text();
            
            if (responseStatus == 'Failed') 
            {
                $('#legalDescription, #vestingInformation').prev('.loader').hide();
                $('#legalDescription').html('No data found.');
                $('#vestingInformation').html('No data found.');
                $('#grantDeedInfoFile').prev('.loader').hide();
                $('#grantDeedInfoFile').css('border','1px solid #000000');
                $('#grantDeedInfoFile').css('padding','15px');
                $('#grantDeedInfoFile').html('<span class="orderinfo1">No data found.</span>');
            } 
            else if (responseStatus == 'Success') 
            {
                $requestId = $(response).find('RequestID').text();
                getRequestSummaries($requestId,'4');
            }
        })
        .fail(function(err) {
            $('#legalDescription, #vestingInformation').prev('.loader').hide();
            $('#legalDescription').html('No data found.');
            $('#vestingInformation').html('No data found.');
            $('#grantDeedInfoFile').prev('.loader').hide();
            $('#grantDeedInfoFile').css('border','1px solid #000000');
            $('#grantDeedInfoFile').css('padding','15px');
            $('#grantDeedInfoFile').html('<span class="orderinfo1">No data found.</span>');
        });
}

function createService3()
{
	/*var apn = localStorage.getItem('apn');
	var state = localStorage.getItem('state');
	var county = localStorage.getItem('county');*/

	$.ajax({
        url: 'php/createservice.php',
        data: {
            apn: apn,
            state: state,
            county: county,
            methodId: 3,
        },
        dataType: "xml"
    })
    	.done(function(response, textStatus, jqXHR) {

            var responseStatus = $(response).find('ReturnStatus').text();
            
            if (responseStatus == 'Failed') 
            {
                $('#firstInstallment, #secondInstallment').prev('.loader').hide();
                $('#firstInstallment').css('border','1px solid #000000');
                $('#firstInstallment').css('padding','15px');
                $('#firstInstallment').html('<span class="orderinfo1">No data found.</span>');
                $('#secondInstallment').css('border','1px solid #000000');
                $('#secondInstallment').css('padding','15px');
                $('#secondInstallment').html('<span class="orderinfo1">No data found.</span>');
            } 
            else if (responseStatus == 'Success') 
            {
                $requestId = $(response).find('RequestID').text();
                getRequestSummaries($requestId,'3');
            }
        })
        .fail(function(err) {
            $('#firstInstallment, #secondInstallment').prev('.loader').hide();
            $('#firstInstallment').css('border','1px solid #000000');
            $('#firstInstallment').css('padding','15px');
            $('#firstInstallment').html('<span class="orderinfo1">No data found.</span>');
            $('#secondInstallment').css('border','1px solid #000000');
            $('#secondInstallment').css('padding','15px');
            $('#secondInstallment').html('<span class="orderinfo1">No data found.</span>');            
        });
}

function getRequestSummaries(requestId,methodId)
{
	$.ajax({
        url: 'php/getrequestsummaries.php',
        data: {
            requestId: requestId
        },
        dataType: "xml"
    })
    	.done(function(response, textStatus, jqXHR) {

            var responseStatus = $(response).find('ReturnStatus').text();
            
            if (responseStatus == 'Failed') 
            {
                $('#legalDescription, #vestingInformation').prev('.loader').hide();
                $('#legalDescription').html('No data found.');
                $('#vestingInformation').html('No data found.');
                $('#firstInstallment, #secondInstallment').prev('.loader').hide();
                $('#firstInstallment').css('border','1px solid #000000');
                $('#firstInstallment').css('padding','15px');
                $('#firstInstallment').html('<span class="orderinfo1">No data found.</span>');
                $('#secondInstallment').css('border','1px solid #000000');
                $('#secondInstallment').css('padding','15px');
                $('#secondInstallment').html('<span class="orderinfo1">No data found.</span>');
                $('#grantDeedInfoFile').prev('.loader').hide();
                $('#grantDeedInfoFile').css('border','1px solid #000000');
                $('#grantDeedInfoFile').css('padding','15px');
                $('#grantDeedInfoFile').html('<span class="orderinfo1">No data found.</span>');
            } 
            else if (responseStatus == 'Success') 
            {
                $resultId = $(response).find("ResultThumbNail:first").find("ID").text();
                serviceId = '';
                if(methodId == 4)
                {
                    serviceId = $(response).find("RequestSummaries:first").find("RequestSummary:first").find("Order:first").find("Services:first").find("Service:first").find("ID:first").text();
                    imageCreateRequest(serviceId);
                }
                getResultById($resultId,methodId);
            }
        })
        .fail(function(err) {
            $('#legalDescription, #vestingInformation').prev('.loader').hide();
            $('#legalDescription').html('No data found.');
            $('#vestingInformation').html('No data found.');
            $('#firstInstallment, #secondInstallment').prev('.loader').hide();
            $('#firstInstallment').css('border','1px solid #000000');
            $('#firstInstallment').css('padding','15px');
            $('#firstInstallment').html('<span class="orderinfo1">No data found.</span>');
            $('#secondInstallment').css('border','1px solid #000000');
            $('#secondInstallment').css('padding','15px');
            $('#secondInstallment').html('<span class="orderinfo1">No data found.</span>');
            $('#grantDeedInfoFile').prev('.loader').hide();
            $('#grantDeedInfoFile').css('border','1px solid #000000');
            $('#grantDeedInfoFile').css('padding','15px');
            $('#grantDeedInfoFile').html('<span class="orderinfo1">No data found.</span>');
        });
}

function getResultById(resultId,methodId)
{
	$.ajax({
        url: 'php/getresultbyid.php',
        data: {
            resultId: resultId,
            methodId: methodId
        },
        dataType: "xml"
    })
    	.done(function(response, textStatus, jqXHR) {

            var responseStatus = $(response).find('ReturnStatus').text();
            
            if (responseStatus == 'Failed') 
            {
                $('#legalDescription, #vestingInformation').prev('.loader').hide();
                $('#legalDescription').html('No data found.');
                $('#vestingInformation').html('No data found.');
                $('#firstInstallment, #secondInstallment').prev('.loader').hide();
                $('#firstInstallment').css('border','1px solid #000000');
                $('#firstInstallment').css('padding','15px');
                $('#firstInstallment').html('<span class="orderinfo1">No data found.</span>');
                $('#secondInstallment').css('border','1px solid #000000');
                $('#secondInstallment').css('padding','15px');
                $('#secondInstallment').html('<span class="orderinfo1">No data found.</span>');
            } 
            else if (responseStatus == 'Success') 
            {
            	
            	if(methodId == 4)
            	{
            		var briefLegal = $(response).find("Result:first").find("BriefLegal").text();
                	var vesting = $(response).find("Result:first").find("Vesting").text();
                    $('#legalDescription, #vestingInformation').prev('.loader').hide();
                   
                	if(briefLegal)
                    {
                        $('#legalDescription').html(briefLegal);
                    }
                    else
                    {
                        $('#legalDescription').html('No data found.');
                    }

                    if(vesting)
                    {
                        $('#vestingInformation').html(vesting);
                    }
                    else
                    {
                        $('#vestingInformation').html('No data found.');
                    }

            	}
            	else if(methodId == 3)
            	{      
                    $('#firstInstallment, #secondInstallment').prev('.loader').hide();
                    if($(response).find("Result:first").find("TaxReport").find("Installments").children('item').length)
                    {
                        $(response).find("Result:first").find("TaxReport").find("Installments").children('item').each(function(i) {
                            var balance = $(this).find('Balance').text() ? $(this).find('Balance').text() : ' - ';
                            var amount  = $(this).find('Amount').text()? $(this).find('Amount').text() : ' - ';
                            var duedate = $(this).find('DueDate').text() ? $(this).find('DueDate').text() : ' - ';
                            var number  = $(this).find('Number').text() ? $(this).find('Number').text() : ' - ';
                            var paymentdate = $(this).find('PaymentDate').text() ? $(this).find('PaymentDate').text() : ' - ';
                            var penalty = $(this).find('Penalty').text() ? $(this).find('Penalty').text() : ' - ';
                            var status = $(this).find('Status').text() ? $(this).find('Status').text() : ' - ';
                            var amountpaid = $(this).find('AmountPaid').text() ? $(this).find('AmountPaid').text() : ' - ';
                            var taxyear = $(this).find('TaxYear').text() ? $(this).find('TaxYear').text() : ' - ';
                            if(i == 0)
                            {
                                var firstIntdata = '<p>Balance: '+balance+'</p><p>Amount: '+amount+'</p><p>DueDate: '+duedate+'</p><p>Number: '+number+'</p><p>PaymentDate: '+paymentdate+'</p><p>Penalty: '+penalty+'</p><p>Status: '+status+'</p><p>AmountPaid: '+amountpaid+'</p><p>TaxYear: '+taxyear+'</p>';
                                
                                $('#firstInstallment').css('border','1px solid #000000');
                                $('#firstInstallment').css('padding','15px');
                                $('#firstInstallment').html(firstIntdata);
                            }
                            else if(i == 1)
                            {
                                var secondIntdata = '<p>Balance: '+balance+'</p><p>Amount: '+amount+'</p><p>DueDate: '+duedate+'</p><p>Number: '+number+'</p><p>PaymentDate: '+paymentdate+'</p><p>Penalty: '+penalty+'</p><p>Status: '+status+'</p><p>AmountPaid: '+amountpaid+'</p><p>TaxYear: '+taxyear+'</p>';
                                
                                $('#secondInstallment').css('border','1px solid #000000');
                                $('#secondInstallment').css('padding','15px');
                                $('#secondInstallment').html(secondIntdata);
                            }
                        });
                    }             
                    else
                    {
                        $('#firstInstallment').css('border','1px solid #000000');
                        $('#firstInstallment').css('padding','15px');
                        $('#firstInstallment').html('<span class="orderinfo1">No data found.</span>');
                        $('#secondInstallment').css('border','1px solid #000000');
                        $('#secondInstallment').css('padding','15px');
                        $('#secondInstallment').html('<span class="orderinfo1">No data found.</span>');
                    }
            	}
            }
        })
        .fail(function(err) {
            $('#legalDescription, #vestingInformation').prev('.loader').hide();
            $('#legalDescription').html('No data found.');
            $('#vestingInformation').html('No data found.');
            $('#firstInstallment, #secondInstallment').prev('.loader').hide();
            $('#firstInstallment').css('border','1px solid #000000');
            $('#firstInstallment').css('padding','15px');
            $('#firstInstallment').html('<span class="orderinfo1">No data found.</span>');
            $('#secondInstallment').css('border','1px solid #000000');
            $('#secondInstallment').css('padding','15px');
            $('#secondInstallment').html('<span class="orderinfo1">No data found.</span>');
        });
}

function imageCreateRequest(serviceId)
{
    $.ajax({
        url: 'php/imagecreaterequest.php',
        data: {
            serviceId: serviceId,
        },
        dataType: "xml"
    })
        .done(function(response, textStatus, jqXHR) {

            var responseStatus = $(response).find('ReturnStatus').text();
            
            if (responseStatus == 'Failed') 
            {
                $('#grantDeedInfoFile').prev('.loader').hide();
                $('#grantDeedInfoFile').css('border','1px solid #000000');
                $('#grantDeedInfoFile').css('padding','15px');
                $('#grantDeedInfoFile').html('<span class="orderinfo1">No data found.</span>');
            } 
            else if (responseStatus == 'Success') 
            {
                $requestId = $(response).find('RequestID').text();
                getRequestStatus($requestId);
            }
        })
        .fail(function(err) {
            $('#grantDeedInfoFile').prev('.loader').hide();
            $('#grantDeedInfoFile').css('border','1px solid #000000');
            $('#grantDeedInfoFile').css('padding','15px');
            $('#grantDeedInfoFile').html('<span class="orderinfo1">No data found.</span>');
        });
}

function getRequestStatus(requestId)
{
    $.ajax({
        url: 'php/getrequeststatus.php',
        data: {
            requestId: requestId
        },
        dataType: "xml"
    })
        .done(function(response, textStatus, jqXHR) {

            var responseStatus = $(response).find('ReturnStatus').text();
            
            if (responseStatus == 'Failed') 
            {
                $('#grantDeedInfoFile').prev('.loader').hide();
                $('#grantDeedInfoFile').css('border','1px solid #000000');
                $('#grantDeedInfoFile').css('padding','15px');
                $('#grantDeedInfoFile').html('<span class="orderinfo1">No data found.</span>');
            } 
            else if (responseStatus == 'Success') 
            {
                $resultId = $(response).find("RequestId:first").text();
                generateImage($resultId);
            }
        })
        .fail(function(err) {
            $('#grantDeedInfoFile').prev('.loader').hide();
            $('#grantDeedInfoFile').css('border','1px solid #000000');
            $('#grantDeedInfoFile').css('padding','15px');
            $('#grantDeedInfoFile').html('<span class="orderinfo1">No data found.</span>');
        });
}

function generateImage(requestId)
{
    $.ajax({
        url: 'php/generateimage.php',
        data: {
            requestId: requestId
        },
        dataType: "xml"
    })
        .done(function(response, textStatus, jqXHR) {

            var responseStatus = $(response).find('ReturnStatus').text();
            
            if (responseStatus == 'Failed') 
            {
                $('#grantDeedInfoFile').prev('.loader').hide();
                $('#grantDeedInfoFile').css('border','1px solid #000000');
                $('#grantDeedInfoFile').css('padding','15px');
                $('#grantDeedInfoFile').html('<span class="orderinfo1">No data found.</span>');
            } 
            else if (responseStatus == 'Success') 
            {
                var base64_data = $(response).find("Data:first").text();
                var bin = atob(base64_data);
               

                // Embed the PDF into the HTML page and show it to the user
                /*var obj = document.createElement('object');
                obj.style.width = '100%';
                obj.style.height = '842pt';
                obj.type = 'application/pdf';
                obj.data = 'data:application/pdf;base64,' + base64_data;
                document.body.appendChild(obj);*/

                // Insert a link that allows the user to download the PDF file
                // <a class="btn btn-default btn-sm btn_mrg-top_30" href="/industry-documents/pctReadPrelim.pdf">Download PDF</a>

                var link = document.createElement('a');
                link.innerHTML = 'Download PDF';
                link.download = 'GrantDeedInfo.pdf';
                link.className= 'btn btn-default btn-sm btn_mrg-top_30';
                link.href = 'data:application/octet-stream;base64,' + base64_data;
                document.body.appendChild(link);
               
                $('#grantDeedInfoFile').prev('.loader').hide();
                $('#grantDeedInfoFile').html(link);              
            }
        })
        .fail(function(err) {
            $('#grantDeedInfoFile').prev('.loader').hide();
            $('#grantDeedInfoFile').css('border','1px solid #000000');
            $('#grantDeedInfoFile').css('padding','15px');
            $('#grantDeedInfoFile').html('<span class="orderinfo1">No data found.</span>');
        });
}