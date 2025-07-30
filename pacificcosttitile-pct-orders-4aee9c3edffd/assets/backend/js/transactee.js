$(document).ready(function () {
    $('#upload_transactee_documents').click(function () {
        $('#file_upload_suc, #file_upload_err').addClass('d-none');
        var fileInput = $('#transactee_documents')[0];
        var transactee_id = $('#transactee_id').val();
        if (fileInput.files.length === 0) {
            alert('Please select a file to upload.');
            return;
        }

        if (!transactee_id) {
            alert('Invalid transactee, Please try again.');
            return;
        }

        $('#upload_transactee_documents').addClass('disabled');
        var formData = new FormData();
        formData.append('transactee_documents', fileInput.files[0]);
        formData.append('transactee_id', transactee_id);

        $.ajax({
            url: 'upload-transactee-documents', // URL to your CodeIgniter controller method
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                let res = JSON.parse(response);
                if (res.success != null) {
                    $('#file_upload_suc').text(res.success);
                    $('#file_upload_suc').removeClass('d-none');
                    transactee_document_list.ajax.reload();
                } else if (res.error != null) {
                    $('#file_upload_err').text(res.error);
                    $('#file_upload_err').removeClass('d-none');
                }
                // $('#transactee_id').val('');
                $('#transactee_documents').val('');
                $('#upload_transactee_documents').removeClass('disabled');
            },
            error: function (xhr, status, error) {
                alert('An error occurred while uploading the file');
                console.log(xhr, status, error);
                $('#file_upload_err').text(error);
                $('#file_upload_err').removeClass('d-none');
                $('#upload_transactee_documents').removeClass('disabled');
            }
        });
    });
});

function openNotes(id, notes, admin_notes) {
    $("#admin_notes").val(admin_notes)
    $("#notes").val(notes)
    $('#notesModal').modal('show');
}

function getDocuments(id) {
    $('#transactee_id').val(id);
    $('#openUploadModel').modal('show');
    if ($('#transactee_documents_list').length) {
        transactee_document_list = $('#transactee_documents_list').DataTable({
            "paging": false,
            // "lengthChange": false,
            "language": {
                // searchPlaceholder: "Search File# or Address",
                paginate: false,
                "emptyTable": "Record(s) not found.",
                // "search": "",
            },
            "bDestroy": true,
            "searching": false,
            // "bStateSave": true,

            // dom: 'Bfrtip',
            buttons: [],
            "drawCallback": function () {

            },
            // "ordering": false,
            // "serverSide": true,
            "ajax": {
                url: base_url + "order/admin/get-transactee-document-list", // json datasource
                type: "post", // method  , by default get
                data: { id: id },
                beforeSend: function () {
                    $('#page-list-loader').css('background-color', 'rgba(0,0,0,.5)');
                    $('#page-list-loader').css('display', 'block');
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
                    $("#transactee_documents_list tbody").append(
                        '<tr><td colspan="4" class="text-center">No records found</td></tr>');
                    $("#transactee_documents_list_processing").css("display", "none");
                },
                complete: function () {
                    $("#page-list-loader").hide();
                    $('#page-list-loader').css('display', 'none');
                }
            }
        });
    }
}

function activateTransactee() {
    $('input[type="checkbox"]').on('change', function () {
        console.log('change event');
        $('body').animate({ opacity: 0.5 }, "slow");
        var id = $(this).attr('id');
        if ($(this).is(":checked")) {
            var status = 1;
        } else {
            var status = 0;
        }
        $.ajax({
            url: base_url + "order/admin/update-transactee-status",
            method: "POST",
            data: {
                id: id,
                status: status
            },
            success: function (data) {
                var result = jQuery.parseJSON(data);
                console.log('result ===', result);
                if (result.status == 'success') {
                    $('body').animate({ opacity: 1.0 }, "slow");
                    $('#transactee_success_msg').html(result.msg).show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#transactee_success_msg").offset().top
                    }, 1000);
                    transactee_list.ajax.reload(null, false);
                    setTimeout(function () {
                        $('#transactee_success_msg').html('').hide();
                    }, 4000);
                } else {
                    $('#transactee_error_msg').html(result.message).show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#transactee_error_msg").offset().top
                    }, 1000);

                    setTimeout(function () {
                        $('#transactee_error_msg').html('').hide();
                    }, 4000);
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                $('#transactee_error_msg').html('Something went wrong. Please try it again.').show();
                $([document.documentElement, document.body]).animate({
                    scrollTop: $("#transactee_success_msg").offset().top
                }, 1000);

                setTimeout(function () {
                    $('#transactee_error_msg').html('').hide();
                }, 4000);
            }
        });
    });
}

function editTransactee(id, editFlag) {
    $('#transactee_id').val(id);
    // $('#page-preloader').css('background-color', 'rgba(0,0,0,.5)');
    // $('#page-preloader').css('display', 'block');

    $.ajax({
        url: base_url + "order/admin/get-transactee-details",
        type: "post",
        data: {
            transactee_id: id
        },
        success: function (response) {
            // console.log('editTransactee', response);
            // return;
            var res = $.parseJSON(response);
            console.log('editTransactee', res);
            if (res.status) {
                res_data = res.data;
                $('#transctee_name').val(res_data.transctee_name);
                $('#file_number').val(res_data.file_number);
                $('#account_number').val(res_data.account_number);
                $('#aba').val(res_data.aba);
                $('#bank_name').val(res_data.bank_name);
                $('#note').val(res_data.notes);
                $('#admin_note').val(res_data.admin_notes);
                $('#add-edit-transactee-form input.form-control, #add-edit-transactee-form textarea.form-control').attr('disabled', true);
                $('.form-footer').addClass('d-none');
                if (editFlag) {
                    $('input, textarea').attr('disabled', false);
                    $('.form-footer').removeClass('d-none');
                }
            }
            $('#page-preloader').css('display', 'none');
            $('#addTransacteeModal').modal('show');
        }
    });
    return false;
}

function deleteTransactee(id) {
    if (id == '') {
        alert('Transactee ID is required.');
        return false;
    }

    var ready = confirm("Are you sure want to delete?");

    if (ready) {
        $.ajax({
            url: base_url + "order/admin/delete-transactee-details",
            method: "POST",
            data: {
                id: id
            },
            success: function (data) {
                var result = jQuery.parseJSON(data);
                if (result.status == 'success') {
                    $('#transactee_success_msg').html(result.message).show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#transactee_success_msg").offset().top
                    }, 1000);
                    transactee_list.ajax.reload(null, false);
                    setTimeout(function () {
                        $('#transactee_success_msg').html('').hide();
                    }, 4000);
                } else {
                    $('#transactee_error_msg').html(result.message).show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#transactee_error_msg").offset().top
                    }, 1000);

                    setTimeout(function () {
                        $('#transactee_error_msg').html('').hide();
                    }, 4000);
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                $('#transactee_error_msg').html('Something went wrong. Please try it again.').show();
                $([document.documentElement, document.body]).animate({
                    scrollTop: $("#transactee_success_msg").offset().top
                }, 1000);

                setTimeout(function () {
                    $('#transactee_error_msg').html('').hide();
                }, 4000);
            }
        })
    } else {
        return false;
    }
}

function downloadDocumentFromAws(url, documentType) {
    $('#page-preloader').css('background-color', 'rgba(0,0,0,.5)');
    $('#page-preloader').css('display', 'block');
    var fileNameIndex = url.lastIndexOf("/") + 1;
    var filename = url.substr(fileNameIndex);
    $.ajax({
        url: base_url + "download-aws-document-admin",
        type: "post",
        data: {
            url: url
        },
        async: false,
        success: function (response) {
            if (response) {
                if (navigator.msSaveBlob) {
                    var csvData = base64toBlob(response, 'application/octet-stream');
                    var csvURL = navigator.msSaveBlob(csvData, filename);
                    var element = document.createElement('a');
                    element.setAttribute('href', csvURL);
                    element.setAttribute('download', documentType + "_" + filename);
                    element.style.display = 'none';
                    document.body.appendChild(element);
                    document.body.removeChild(element);
                } else {
                    console.log(response);
                    var csvURL = 'data:application/octet-stream;base64,' + response;
                    var element = document.createElement('a');
                    element.setAttribute('href', csvURL);
                    element.setAttribute('download', documentType + "_" + filename);
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