CKEDITOR.replace( 'esw_ins_value_1',
{
    toolbar : [
        ['Bold','Italic','Underline','StrikeThrough','-','Undo','Redo','-','Cut','Copy','Styles','Format','Font','FontSize', 'TextColor'],
    ],
});
CKEDITOR.on('instanceReady', function( ev ) {
    ev.editor.dataProcessor.htmlFilter.addRules({
      elements: {
        p: function (e) { e.attributes.style = 'font-size: 12px;'; }
      }
    });
  });
CKEDITOR.replace( 'esw_ins_value_2',
{
    toolbar : [
        ['Bold','Italic','Underline','StrikeThrough','-','Undo','Redo','-','Cut','Copy','Styles','Format','Font','FontSize', 'TextColor'],
    ],
});
CKEDITOR.replace( 'esw_ins_value_3',
{
    toolbar : [
        ['Bold','Italic','Underline','StrikeThrough','-','Undo','Redo','-','Cut','Copy','Styles','Format','Font','FontSize', 'TextColor'],
    ],
});
CKEDITOR.replace( 'esw_ins_value_4',
{
    toolbar : [
        ['Bold','Italic','Underline','StrikeThrough','-','Undo','Redo','-','Cut','Copy','Styles','Format','Font','FontSize', 'TextColor'],
    ],
});
CKEDITOR.replace( 'esw_ins_value_5',
{
    toolbar : [
        ['Bold','Italic','Underline','StrikeThrough','-','Undo','Redo','-','Cut','Copy','Styles','Format','Font','FontSize', 'TextColor'],
    ],
    
});
CKEDITOR.replace( 'esw_ins_value_6',
{
    toolbar : [
        ['Bold','Italic','Underline','StrikeThrough','-','Undo','Redo','-','Cut','Copy','Styles','Format','Font','FontSize', 'TextColor'],
    ],
    
});
CKEDITOR.replace( 'esw_ins_value_7',
{
    toolbar : [
        ['Bold','Italic','Underline','StrikeThrough','-','Undo','Redo','-','Cut','Copy','Styles','Format','Font','FontSize', 'TextColor'],
    ],
    
});
CKEDITOR.replace( 'esw_ins_value_8',
{
    toolbar : [
        ['Bold','Italic','Underline','StrikeThrough','-','Undo','Redo','-','Cut','Copy','Styles','Format','Font','FontSize', 'TextColor'],
    ],
    
});
CKEDITOR.replace( 'esw_ins_value_9',
{
    toolbar : [
        ['Bold','Italic','Underline','StrikeThrough','-','Undo','Redo','-','Cut','Copy','Styles','Format','Font','FontSize', 'TextColor'],
    ],
    
});
CKEDITOR.replace( 'esw_ins_value_10',
{
    toolbar : [
        ['Bold','Italic','Underline','StrikeThrough','-','Undo','Redo','-','Cut','Copy','Styles','Format','Font','FontSize', 'TextColor'],
    ],
    
});
CKEDITOR.replace( 'esw_ins_value_11',
{
    toolbar : [
        ['Bold','Italic','Underline','StrikeThrough','-','Undo','Redo','-','Cut','Copy','Styles','Format','Font','FontSize', 'TextColor'],
    ],
    
});
CKEDITOR.replace( 'esw_ins_value_12',
{
    toolbar : [
        ['Bold','Italic','Underline','StrikeThrough','-','Undo','Redo','-','Cut','Copy','Styles','Format','Font','FontSize', 'TextColor'],
    ],
    
});
CKEDITOR.replace( 'esw_ins_value_13',
{
    toolbar : [
        ['Bold','Italic','Underline','StrikeThrough','-','Undo','Redo','-','Cut','Copy','Styles','Format','Font','FontSize', 'TextColor'],
    ],
    
});
CKEDITOR.replace( 'esw_ins_value_14',
{
    toolbar : [
        ['Bold','Italic','Underline','StrikeThrough','-','Undo','Redo','-','Cut','Copy','Styles','Format','Font','FontSize', 'TextColor'],
    ],
    
});
CKEDITOR.replace( 'esw_ins_value_15',
{
    toolbar : [
        ['Bold','Italic','Underline','StrikeThrough','-','Undo','Redo','-','Cut','Copy','Styles','Format','Font','FontSize', 'TextColor'],
    ],
    
});
CKEDITOR.replace( 'esw_ins_value_16',
{
    toolbar : [
        ['Bold','Italic','Underline','StrikeThrough','-','Undo','Redo','-','Cut','Copy','Styles','Format','Font','FontSize', 'TextColor'],
    ],
    
});
CKEDITOR.replace( 'esw_ins_value_17',
{
    toolbar : [
        ['Bold','Italic','Underline','StrikeThrough','-','Undo','Redo','-','Cut','Copy','Styles','Format','Font','FontSize', 'TextColor'],
    ],
    
});
CKEDITOR.replace( 'esw_ins_value_18',
{
    toolbar : [
        ['Bold','Italic','Underline','StrikeThrough','-','Undo','Redo','-','Cut','Copy','Styles','Format','Font','FontSize', 'TextColor'],
    ],
    
});
CKEDITOR.replace( 'esw_ins_value_19',
{
    toolbar : [
        ['Bold','Italic','Underline','StrikeThrough','-','Undo','Redo','-','Cut','Copy','Styles','Format','Font','FontSize', 'TextColor'],
    ],
    
});
CKEDITOR.replace( 'esw_ins_value_20',
{
    toolbar : [
        ['Bold','Italic','Underline','StrikeThrough','-','Undo','Redo','-','Cut','Copy','Styles','Format','Font','FontSize', 'TextColor'],
    ],
    
});
$(document).ready(function () {
    $('#buyer-info-clone-group-fields').cloneya({
        maximum: 5
    }).on('after_append.cloneya', function (event, toclone, newclone) {
        var id = $(newclone).find("input[name='is_main_buyer']").attr('id');
        $('#'+id).val(id);
    }).off('remove.cloneya').on('remove.cloneya', function (event, clone) {
        $(clone).slideToggle('slow', function () {
            $(clone).remove();
        })
    });

    $.fn.modal.Constructor.prototype.enforceFocus = function () {
        modal_this = this
        $(document).on('focusin.modal', function (e) {
            if (modal_this.$element[0] !== e.target && !modal_this.$element.has(e.target).length
            // add whatever conditions you need here:
            &&
            !$(e.target.parentNode).hasClass('cke_dialog_ui_input_select') && !$(e.target.parentNode).hasClass('cke_dialog_ui_input_text')) {
                modal_this.$element.focus()
            }
        })
    };

    $('#seller-info-clone-group-fields').cloneya({
        maximum: 5
    }).on('after_append.cloneya', function (event, toclone, newclone) {
        var id = $(newclone).find("input[name='is_main_seller']").attr('id');
        $('#'+id).val(id);
    }).off('remove.cloneya').on('remove.cloneya', function (event, clone) {
        $(clone).slideToggle('slow', function () {
            $(clone).remove();
        })
    });

	if ($('.custom__task_button').length > 0){
		change_progress();
		$('.task_check_all').click(function(){
			$('.custom__task_card .custom__task_checkbox').prop('checked', true);
			$('.custom_sub_task_checkbox').prop('checked', true);
			change_progress();
		});
		$('.task_un_check_all').click(function(){
			$('.custom__task_card .custom__task_checkbox').prop('checked', false);
			$('.custom_sub_task_checkbox').prop('checked', false);
			change_progress();
		});
		$('.task_show_all').click(function(){
			$(".custom__task_card .custom__task_collapse").collapse('show');
		});
		$('.task_hide_all').click(function(){
			$(".custom__task_card .custom__task_collapse").collapse('hide');
		});

		$('.custom__task_checkbox').change(function(){
			change_progress();
		});
	}

	$("#show-hide-form-btn").click(function(){
		$('.show-hide-form-div').toggleClass('hide');
	});

	$('input[type=checkbox]').change(function(){
		var childFlag = $(this).attr('data-child');
		var parentTask = $(this).attr('data-parent-task');
		if (this.checked && childFlag == '1') {
			var lenchk = $('#collapseCard_'+parentTask).find(':checkbox');
			var lenchkChecked = $('#collapseCard_'+parentTask).find(':checkbox:checked');
			if (lenchk.length == lenchkChecked.length) {
				$('#check_'+parentTask).prop('checked', true);
				change_progress();
			} 
		} else {
			$('#check_'+parentTask).prop('checked', false);
			change_progress();
		}
	});

	$('input[type=checkbox]').each(function () {
		var childFlag = $(this).attr('data-child');
		if (childFlag == 0) {
			if ($('#sub_task_'+$(this).val()).length > 0 ) {
				$(this).prop('disabled', true);
			}
		}
	});

	$("form").submit(function() {
		$("input").removeAttr("disabled");
	});

    $("input[type=file]").change(function () {
        $("#output_"+$(this).attr('data-task_id')+" ul").empty();
        var ele = document.getElementById($(this).attr('id'));
        var result = ele.files;
        for (var x = 0; x < result.length; x++) {
            var fle = result[x];
            $("#output_"+$(this).attr('data-task_id')+" ul").append("<li>" + fle.name + "(TYPE: " + fle.type + ", SIZE: " + fle.size +
                ")</li>");
        }
    });

    $('.esw_ins_name').change(function(){
        var esw_name_id = $(this).attr('id');
        var selected = $(this).find('option:selected');
        CKEDITOR.instances['esw_ins_value_'+esw_name_id].setData(selected.data('foo'));
        //$('#esw_ins_value_'+esw_name_id).val(selected.data('foo')); 
    }).change();
});

function change_progress() {
	var total_task_list = $("[type='checkbox'].custom__task_checkbox").length;
	var checked_task_list = $("[type='checkbox'].custom__task_checkbox:checked").length;
	var progress_precent = Math.floor((100*checked_task_list)/total_task_list);
	//linear-gradient(90deg, hsl(20deg 100% 50%) 20%, #ababab 20%)
	$('.custom__task_progress').css('background', 'linear-gradient(90deg,hsl('+progress_precent+'deg 90% 50%) '+progress_precent+'%, #f2f2f2 '+progress_precent+'%)').attr('aria-valuenow', progress_precent).text(progress_precent+'%');    
}

function create_note(task_id)
{
    var subject = $('#subject_'+task_id).val();
    var note = $('#note_desc_'+task_id).val();
    if (subject == '') {
       alert('Please enter the subject.');
       $('#subject_'+task_id).focus();
       return false;
    }
    if (note == '') {
        alert('Please enter the note.');
        $('#note_'+task_id).focus();
        return false;
    }
    $('#page-preloader').css('background-color', 'rgba(0,0,0,.5)');
	$('#page-preloader').css('display', 'block');
    
    $.ajax({
        url: base_url+"escrow-create-note",
        method: "POST",
        data : {
            task_id: task_id,
            subject: subject,
            note: note,
			file_id: $('#file_id').val(),
            order_id: $('#order_id').val(),
            num_of_notes: $('#num_of_notes_'+task_id).val()
        },
        success: function(data){
            var result = jQuery.parseJSON(data);
            $('#subject_'+task_id).val('');
            $('#note_desc_'+task_id).val('');
            $("#note_"+task_id).collapse('hide');
            if (result.status == 'success') {
                window.location.reload();
                // if (result.num_of_notes > 0) {
                //     $("#notes_"+task_id).append('<li><b>'+subject+'</b>: '+note+'</li>');
                // } else {
                //     $("#notes_"+task_id).empty(); 
                //     $("#notes_"+task_id).append('<li><b>'+subject+'</b>: '+note+'</li>'); 
                // }
                // $('#num_of_notes_'+task_id).val(result.num_of_notes+1);
                // $('#order_tasks_success_msg').html(result.message).show();
                // $([document.documentElement, document.body]).animate({
                //     scrollTop: $("#order_tasks_success_msg").offset().top
                // }, 1000);
                
                // setTimeout(function () {
                //     $('#order_tasks_success_msg').html('').hide();
                // }, 4000);
            } else {
                $('#order_tasks_error_msg').html(result.message).show();
                $([document.documentElement, document.body]).animate({
                    scrollTop: $("#order_tasks_error_msg").offset().top
                }, 1000);

                setTimeout(function () {
                    $('#order_tasks_error_msg').html('').hide();
                }, 4000);
            }
            $('#page-preloader').css('display', 'none');
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            $('#order_tasks_error_msg').html('Something went wrong. Please try it again.').show();
            $([document.documentElement, document.body]).animate({
                scrollTop: $("#order_tasks_error_msg").offset().top
            }, 1000);

            setTimeout(function () {
                $('#order_tasks_error_msg').html('').hide();
            }, 4000);

            $('#page-preloader').css('display', 'none');
        }
    });
    return false;
}

function upload_documents(task_id)
{
    var document_files = $('#ufile_'+task_id).prop('files'); 
         
    if (document_files.length <= 0) {
        alert('Please upload at least one file.');
        $('#ufile_'+task_id).focus();
        return false;
    }
    var formData = new FormData();
    $.each($('#ufile_'+task_id)[0].files, function(i, file) {
        formData.append('files[]', file);
    }); 
    formData.append('task_id', task_id);
    formData.append('order_id', $('#order_id').val());
    formData.append('file_id', $('#file_id').val());
    $('#page-preloader').css('background-color', 'rgba(0,0,0,.5)');
	$('#page-preloader').css('display', 'block');
    
    $.ajax({
        url: base_url+"task-documents",
        method: "POST",
        data : formData,
        cache: false,
        contentType: false,
        processData: false,
        success: function(data) {
            var result = jQuery.parseJSON(data);
            if (result.status == 'success') {
                location.reload();
            } 
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            $('#order_tasks_error_msg').html('Something went wrong. Please try it again.').show();
            $([document.documentElement, document.body]).animate({
                scrollTop: $("#order_tasks_error_msg").offset().top
            }, 1000);

            setTimeout(function () {
                $('#order_tasks_error_msg').html('').hide();
            }, 4000);

            $('#page-preloader').css('display', 'none');
        }
    });
    return false;
}


