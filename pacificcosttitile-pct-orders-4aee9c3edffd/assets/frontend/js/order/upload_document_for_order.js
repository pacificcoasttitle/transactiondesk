var documents_list = '';
$(document).ready(function(){
    $("#files_upload").submit(function( event ) {
        $('#page-preloader').css('background-color', 'rgba(0,0,0,.5)');
        $('#page-preloader').css('display', 'block');
    });

    $("#document_1").change(function(){
        $('#up_btn').css({"background": "#d35411", "color": "white"});
        $('#down_btn').css({"background": "#d35411", "color": "white"});
    });

    $("#document_2").change(function(){
        $("#document_type_2").prop('required',true);
        $("#description_2").prop('required',true);
        $('#up_btn').css({"background": "#d35411", "color": "white"});
        $('#down_btn').css({"background": "#d35411", "color": "white"});
    });
    
    $("#document_3").change(function(){
        $("#document_type_3").prop('required',true);
        $("#description_3").prop('required',true);
        $('#up_btn').css({"background": "#d35411", "color": "white"});
        $('#down_btn').css({"background": "#d35411", "color": "white"});
    });
    
    $("#document_4").change(function(){
        $("#document_type_4").prop('required',true);
        $("#description_4").prop('required',true);
        $('#up_btn').css({"background": "#d35411", "color": "white"});
        $('#down_btn').css({"background": "#d35411", "color": "white"});
    });
    
    if ($('#document_listing').length) {
        documents_list = $('#document_listing').DataTable({
            "paging": true,
            "lengthChange": false,
            "language": {
                paginate: {
                    next: '<span class="fa fa-angle-right"></span>',
                    previous: '<span class="fa fa-angle-left"></span>',
                },
                "emptyTable": "Record(s) not found.",
                "search": "",
            },
            initComplete: function () {
                
                
            },
            dom: 'Bfrtip',
            buttons: [],
            "drawCallback": function () {
                
            },
            "ordering": false,
            "serverSide": true,
            "ajax": {
                url: base_url + "get-order-documents", 
                type: "post", 
                data: {
                    order_id: $('#order_id').val(),
                    file_id: $('#file_id').val()
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    if (parseInt(XMLHttpRequest.status) == 419) {
                        alert("You are logged out. Please login.");
                    }
                    if (parseInt(XMLHttpRequest.status) == 419) {
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                    }
                    $("#document_listing tbody").append(
                        '<tr><td colspan="4" class="text-center">No records found</td></tr>');
                    $("#document_listing_processing").css("display", "none");
                }
            }
        });
    }
});

function downloadDocumentFromAws(url, api_document_id)
{
    $('#page-preloader').css('background-color', 'rgba(0,0,0,.5)');
    $('#page-preloader').css('display', 'block');
    var fileNameIndex = url.lastIndexOf("/") + 1;
    var filename = url.substr(fileNameIndex);
    $.ajax({
        url: base_url + "download-aws-document",
        type: "post",
        data: {
            url: url,
            api_document_id: api_document_id
        },
        async: false,
        success: function (response) {
            if (response) {
                if (navigator.msSaveBlob) {
                    var csvData = base64toBlob(response, 'application/octet-stream');
                    var csvURL = navigator.msSaveBlob(csvData, filename);
                    var element = document.createElement('a');
                    element.setAttribute('href', csvURL);
                    element.setAttribute('download', api_document_id+"_"+filename);
                    element.style.display = 'none';
                    document.body.appendChild(element);
                    document.body.removeChild(element);
                } else {
                    console.log(response);
                    var csvURL = 'data:application/octet-stream;base64,' + response;
                    var element = document.createElement('a');
                    element.setAttribute('href', csvURL);
                    element.setAttribute('download', api_document_id+"_"+filename);
                    element.style.display = 'none';
                    document.body.appendChild(element);
                    element.click();
                    document.body.removeChild(element);
                }
            }
            $('#page-preloader').css('display', 'none');
        }
    });
}