$(document).ready(function(){
    summary();
});

var dropdown = document.getElementsByClassName("dropdown-btn");
var i;
for (i = 0; i < dropdown.length; i++) {
    dropdown[i].addEventListener("click", function() {
        this.classList.toggle("active");
        var dropdownContent = this.nextElementSibling;
        if (dropdownContent.style.display === "block") {
            dropdownContent.style.display = "none";
        } else {
            dropdownContent.style.display = "block";
        }
    });
}

function updateAction()
{
    $('#note_information').modal('show');
}

function summary()
{
    $('#page-preloader').css('background-color', 'rgba(0,0,0,.5)');
    $('#page-preloader').css('display', 'block');
    $.ajax({
        url: base_url + "summary",
        type: "post",
        data: {
            fileId: $('#fileId').val()
        },
        dataType: "html",
        success: function (response) {
            var results = JSON.parse(response);
            $('#links_details').html(results);
            $('#page-preloader').css('display', 'none');
        }
    });
}

function prelim()
{
    $('#page-preloader').css('background-color', 'rgba(0,0,0,.5)');
    $('#page-preloader').css('display', 'block');
    $.ajax({
        url: base_url + "prelim",
        type: "post",
        data: {
            fileId: $('#fileId').val()
        },
        dataType: "html",
        success: function (response) {
            var results = JSON.parse(response);
            $('#links_details').html(results);
            $('#page-preloader').css('display', 'none');
        }
    });
}

function load_doc(is_sync, resware_document_id, order_id, document_id)
{
    $('#page-preloader').css('background-color', 'rgba(0,0,0,.5)');
    $('#page-preloader').css('display', 'block');
    $.ajax({
        url: base_url + "load-doc",
        type: "post",
        data: {
            resware_document_id: resware_document_id,
            is_sync: is_sync,
            order_id: order_id,
            document_id: document_id
        },
        dataType: "html",
        success: function (response) {
            var results = JSON.parse(response);
            $('#links_details').html(results);
            $('#'+resware_document_id).attr("onclick", "load_doc(1, "+resware_document_id+", "+order_id+", "+document_id+")");
            $('#page-preloader').css('display', 'none');
        }
    });
}

function legal_vesting()
{
    $('#page-preloader').css('background-color', 'rgba(0,0,0,.5)');
    $('#page-preloader').css('display', 'block');
    $.ajax({
        url: base_url + "legal-vesting",
        type: "post",
        data: {
            fileId: $('#fileId').val()
        },
        dataType: "html",
        success: function (response) {
            var results = JSON.parse(response);
            $('#links_details').html(results);
            $('#page-preloader').css('display', 'none');
        }
    });
}

function plat_map()
{
    $('#page-preloader').css('background-color', 'rgba(0,0,0,.5)');
    $('#page-preloader').css('display', 'block');
    $.ajax({
        url: base_url + "plat-map",
        type: "post",
        data: {
            fileId: $('#fileId').val()
        },
        dataType: "html",
        success: function (response) {
            var results = JSON.parse(response);
            $('#links_details').html(results);
            $('#page-preloader').css('display', 'none');
        }
    });
}

function download_document(resware_document_id, order_id, document_name) 
{
    $('#page-preloader').css('background-color', 'rgba(0,0,0,.5)');
    $('#page-preloader').css('display', 'block');
    var fileId = $('#fileId').val();
    $.ajax({
        url: base_url + "download-document",
        type: "post",
        data: {
            resware_document_id: resware_document_id,
            order_id: order_id,
            document_name: document_name,
            fileId: fileId
        },
        dataType: "html",
        success: function (response) {
            $('#page-preloader').css('display', 'none');
            if (response) {
                if (navigator.msSaveBlob) {
                    var csvData = base64toBlob(response, 'application/octet-stream');
                    var csvURL = navigator.msSaveBlob(csvData, 'FeeEstimation.pdf');
                    var element = document.createElement('a');
                    element.setAttribute('href', csvURL);
                    element.setAttribute('download', document_name);
                    element.style.display = 'none';
                    document.body.appendChild(element);
                    document.body.removeChild(element);
                } else {
                    var csvURL = 'data:application/octet-stream;base64,' + response;
                    var element = document.createElement('a');
                    element.setAttribute('href', csvURL);
                    element.setAttribute('download', document_name);
                    /** To download docx file */
                    // element.setAttribute('download', fileId+".docx");
                    /** End */
                    element.style.display = 'none';
                    document.body.appendChild(element);
                    element.click();
                    document.body.removeChild(element);
                }
            }
        }
    });
}

function upload_document(resware_document_id, order_id, document_name) 
{
    $('#page-preloader').css('background-color', 'rgba(0,0,0,.5)');
    $('#page-preloader').css('display', 'block');
    $.ajax({
        url: base_url + "upload-document",
        type: "post",
        data: {
            resware_document_id: resware_document_id,
            order_id: order_id,
            document_name: document_name,
            fileId: $('#fileId').val()
        },
        dataType: "html",
        success: function (response) {
            $('#page-preloader').css('display', 'none');
            var results = JSON.parse(response);
            if (results.status == 'success') {
                alert(results.msg);
            } else if(results.status == 'error') {
                alert(results.msg);
            }
        }
    });
}

function base64toBlob(base64Data, contentType) 
{
    contentType = contentType || '';
    var sliceSize = 1024;
    var byteCharacters = atob(base64Data);
    var bytesLength = byteCharacters.length;
    var slicesCount = Math.ceil(bytesLength / sliceSize);
    var byteArrays = new Array(slicesCount);

    for (var sliceIndex = 0; sliceIndex < slicesCount; ++sliceIndex) {
        var begin = sliceIndex * sliceSize;
        var end = Math.min(begin + sliceSize, bytesLength);

        var bytes = new Array(end - begin);
        for (var offset = begin, i = 0; offset < end; ++i, ++offset) {
            bytes[i] = byteCharacters[offset].charCodeAt(0);
        }
        byteArrays[sliceIndex] = new Uint8Array(bytes);
    }
    return new Blob(byteArrays, {
        type: contentType
    });
}