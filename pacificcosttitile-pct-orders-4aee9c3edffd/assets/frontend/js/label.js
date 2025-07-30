$(document).ready(function(){
    $('#label_listing').DataTable({
        "aaSorting": [],
        "language": {
            paginate: {
                next: '<span class="fa fa-angle-right"></span>',
                previous: '<span class="fa fa-angle-left"></span>',
            },
            "emptyTable": "Record(s) not found.",
        },
    });

    $('input:radio[name=line_1_columns]').change(function() {
        if (this.value == '1') {
            $("#line_1_1").prop('required',true);
            $("#line_1_2").prop('required',false);
            $("#line_1_3").prop('required',false);
            $("#line_1_1_container").show();
            $("#line_1_2_container").hide();
            $("#line_1_3_container").hide();
        } else if (this.value == '2') {
            $("#line_1_1").prop('required',true);
            $("#line_1_2").prop('required',true);
            $("#line_1_3").prop('required',false);
            $("#line_1_1_container").show();
            $("#line_1_2_container").show();
            $("#line_1_3_container").hide();
        } else if (this.value == '3') {
            $("#line_1_1").prop('required',true);
            $("#line_1_2").prop('required',true);
            $("#line_1_3").prop('required',true);
            $("#line_1_1_container").show();
            $("#line_1_2_container").show();
            $("#line_1_3_container").show();
        }
    });

    $('input:radio[name=line_2_columns]').change(function() {
        if (this.value == '1') {
            $("#line_2_1").prop('required',true);
            $("#line_2_2").prop('required',false);
            $("#line_2_3").prop('required',false);
            $("#line_2_1_container").show();
            $("#line_2_2_container").hide();
            $("#line_2_3_container").hide();
        } else if (this.value == '2') {
            $("#line_2_1").prop('required',true);
            $("#line_2_2").prop('required',true);
            $("#line_2_3").prop('required',false);
            $("#line_2_1_container").show();
            $("#line_2_2_container").show();
            $("#line_2_3_container").hide();
        } else if (this.value == '3') {
            $("#line_2_1").prop('required',true);
            $("#line_2_2").prop('required',true);
            $("#line_2_3").prop('required',true);
            $("#line_2_1_container").show();
            $("#line_2_2_container").show();
            $("#line_2_3_container").show();
        }
    });

    $('input:radio[name=line_3_columns]').change(function() {
        if (this.value == '1') {
            $("#line_3_1").prop('required',true);
            $("#line_3_2").prop('required',false);
            $("#line_3_3").prop('required',false);
            $("#line_3_1_container").show();
            $("#line_3_2_container").hide();
            $("#line_3_3_container").hide();
        } else if (this.value == '2') {
            $("#line_3_1").prop('required',true);
            $("#line_3_2").prop('required',true);
            $("#line_3_3").prop('required',false);
            $("#line_3_1_container").show();
            $("#line_3_2_container").show();
            $("#line_3_3_container").hide();
        } else if (this.value == '3') {
            $("#line_3_1").prop('required',true);
            $("#line_3_2").prop('required',true);
            $("#line_3_3").prop('required',true);
            $("#line_3_1_container").show();
            $("#line_3_2_container").show();
            $("#line_3_3_container").show();
        }
    });
});

function selectColumns(label_id, columns, file_name) 
{
    var line_options = "";
    var columnsArr = columns.split(',');
    $("input[name=line_1_columns][value=1]").attr('checked', 'checked');
    $("input[name=line_2_columns][value=1]").attr('checked', 'checked');
    $("input[name=line_3_columns][value=1]").attr('checked', 'checked');

    for (var i = 0; i < columnsArr.length; i++) {
        line_options += "<option value='" + columnsArr[i] + "'>" + columnsArr[i] + "</option>";	
    }

    $('select[name="line_1_1"]').children('option:not(:first)').remove();
    $('select[name="line_1_1"]' ).append( line_options );
    $('select[name="line_1_2"]').children('option:not(:first)').remove();
    $('select[name="line_1_2"]' ).append( line_options );
    $('select[name="line_1_3"]').children('option:not(:first)').remove();
    $('select[name="line_1_3"]' ).append( line_options );

    $('select[name="line_2_1"]').children('option:not(:first)').remove();
    $('select[name="line_2_1"]' ).append( line_options );
    $('select[name="line_2_2"]').children('option:not(:first)').remove();
    $('select[name="line_2_2"]' ).append( line_options );
    $('select[name="line_2_3"]').children('option:not(:first)').remove();
    $('select[name="line_2_3"]' ).append( line_options );

    $('select[name="line_3_1"]').children('option:not(:first)').remove();
    $('select[name="line_3_1"]' ).append( line_options );
    $('select[name="line_3_2"]').children('option:not(:first)').remove();
    $('select[name="line_3_2"]' ).append( line_options );
    $('select[name="line_3_3"]').children('option:not(:first)').remove();
    $('select[name="line_3_3"]' ).append( line_options );

    $("#line_1_1").prop('required',true);
    $("#line_2_1").prop('required',true);
    $("#line_3_1").prop('required',true);

    $("#line_1_2").prop('required',false);
    $("#line_2_2").prop('required',false);
    $("#line_3_2").prop('required',false);

    $("#line_1_3").prop('required',false);
    $("#line_2_3").prop('required',false);
    $("#line_3_3").prop('required',false);

    $("#line_1_1_container").show();
    $("#line_2_1_container").show();
    $("#line_3_1_container").show();

    $("#line_1_2_container").hide();
    $("#line_2_2_container").hide();
    $("#line_3_2_container").hide();

    $("#line_1_3_container").hide();
    $("#line_2_3_container").hide();
    $("#line_3_3_container").hide();
    
    $("#label_id").val(label_id);
    $("#file_name").val(file_name);
    $('#select_columns').modal('show');
}

function downloadFile(binaryData, fileName)
{
    if (navigator.msSaveBlob) {                       
        var csvData = base64toBlob(binaryData,'application/octet-stream');
        var csvURL = navigator.msSaveBlob(csvData, 'ProposedInsured.pdf');
        var element = document.createElement('a');
        element.setAttribute('href', csvURL);
        element.setAttribute('download', fileName);
        element.style.display = 'none';
        document.body.appendChild(element);
        document.body.removeChild(element);
    } else {
        var csvURL = 'data:application/octet-stream;base64,'+binaryData;
        var element = document.createElement('a');
        element.setAttribute('href', csvURL);
        element.setAttribute('download', fileName);
        element.style.display = 'none';
        document.body.appendChild(element);
        element.click();
        document.body.removeChild(element);
    }
}

function generatePdf() 
{	
    $('#page-preloader').css('background-color', 'rgba(0,0,0,.5)');
    $('#page-preloader').css('display', 'block');
    $.ajax({
        url: base_url + "download-label-pdf",
        type: "post",
        data:{
            or_current_resident: $('#or_current_resident').is(":checked") ? 1 : 0,
            line_1_1: $('#line_1_1').val(),
            line_1_2: $('#line_1_2').val(),
            line_1_3: $('#line_1_3').val(),
            line_2_1: $('#line_2_1').val(),
            line_2_2: $('#line_2_2').val(),
            line_2_3: $('#line_2_3').val(),
            line_3_1: $('#line_3_1').val(),
            line_3_2: $('#line_3_2').val(),
            line_3_3: $('#line_3_3').val(),
            line_3: $('#line_3').val(),
            label_id: $('#label_id').val(),
            file_name: $('#file_name').val(),
            line_1_columns: $("input[name='line_1_columns']:checked").val(),
            line_2_columns: $("input[name='line_2_columns']:checked").val(),
            line_3_columns: $("input[name='line_3_columns']:checked").val()
        }, 
        success: function(response) {
            $('#page-preloader').css('display', 'none');
            var res = JSON.parse(response);
            if (res.status == 'success') {
                $('#select_columns').modal('hide');
                if (res.data) {
                    var binaryData = res.data;
                    downloadFile(binaryData, res.file_name);
                }
            } else if(res.status == 'error') {
                $('.modal-body.search-result').append('<div class="error">Something went wrong. Please try again.</div>');
                $('#select_columns').modal('hide');
            }
        }
    });
    return false;
}