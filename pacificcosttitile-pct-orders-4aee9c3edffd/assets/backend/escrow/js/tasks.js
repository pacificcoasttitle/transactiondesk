var tasks ='';
$(document).ready(function () {
    // $('#buyer-info-clone-group-fields').cloneya({
    //     maximum: 5
    // }).on('after_append.cloneya', function (event, toclone, newclone) {
    //     var id = $(newclone).find("input[name='is_main_buyer']").attr('id');
    //     $('#'+id).val(id);
    // }).off('remove.cloneya').on('remove.cloneya', function (event, clone) {
    //     $(clone).slideToggle('slow', function () {
    //         $(clone).remove();
    //     })
    // });

    // $('#seller-info-clone-group-fields').cloneya({
    //     maximum: 5
    // }).on('after_append.cloneya', function (event, toclone, newclone) {
    //     var id = $(newclone).find("input[name='is_main_buyer']").attr('id');
    //     $('#'+id).val(id);
    // }).off('remove.cloneya').on('remove.cloneya', function (event, clone) {
    //     $(clone).slideToggle('slow', function () {
    //         $(clone).remove();
    //     })
    // });

    if ($('#tasks').length > 0)  {
        tasks = $('#tasks').DataTable({
           "paging": true,
            "lengthMenu": [10, 20, 50, 100, 200, 500, 1000],
            "lengthChange": true,
            "language": {
                paginate: {
                  next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                  previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                },
                "emptyTable": "Record(s) not found.",
            },
            initComplete: function() {
            },
            "dom": 'Blfrtip',
            "drawCallback": function () {               
                $('.dataTables_paginate > .pagination li').addClass('page-item');
                $('.dataTables_paginate > .pagination a').addClass('page-link');
                $('.dataTables_paginate > .pagination li.previous a, .dataTables_paginate > .pagination li.next a').addClass('rounded');
            },
            "ordering": false,            
            "serverSide": true,
            "ajax": {                
                url: base_url+"hr/admin/get-tasks", 
                type: "post", 
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    if (parseInt(XMLHttpRequest.status) == 419) {
                        alert("You are logged out. Please login.");
                    }
                    if (parseInt(XMLHttpRequest.status) == 419) {
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                    }
                    $("#tasks tbody").append('<tr><td colspan="6" class="text-center">No records found</td></tr>');
                    $("#tasks_processing").css("display", "none");
                }
            }            
        });
    }
	if ($('.custom__task_button').length > 0){
		change_progress();
		$('.task_check_all').click(function(){
			$('.custom__task_card .custom__task_checkbox').prop('checked', true);
			change_progress();
		});
		$('.task_un_check_all').click(function(){
			$('.custom__task_card .custom__task_checkbox').prop('checked', false);
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

    $("form").submit(function() {
        $("input").removeAttr("disabled");
    });
    
});

function change_progress() {
	var total_task_list = $("[type='checkbox'].custom__task_checkbox").length;
	var checked_task_list = $("[type='checkbox'].custom__task_checkbox:checked").length;
	var progress_precent = Math.floor((100*checked_task_list)/total_task_list);
	$('.custom__task_progress').css('width', progress_precent+'%').attr('aria-valuenow', progress_precent).text(progress_precent+'%');    
}

function deleteTask(id)
{
    if (id=='') {
        alert('Task ID is required.');
        return false;
    }
    var ready = confirm("Are you sure want to delete?");
    if (ready) {
        $.ajax({
            url: base_url+"hr/admin/delete-task",
            method: "POST",
            data : {
                id : id
            },
            success: function(data){
                var result = jQuery.parseJSON(data);
                if (result.status == 'success') {
                    $('#tasks_success_msg').html(result.message).show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#tasks_success_msg").offset().top
                    }, 1000);
                    tasks.ajax.reload( null, false );
                    setTimeout(function () {
                        $('#tasks_success_msg').html('').hide();
                    }, 4000);
                } else {
                    $('#tasks_error_msg').html(result.message).show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#tasks_error_msg").offset().top
                    }, 1000);

                    setTimeout(function () {
                        $('#tasks_error_msg').html('').hide();
                    }, 4000);
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                $('#tasks_error_msg').html('Something went wrong. Please try it again.').show();
                $([document.documentElement, document.body]).animate({
                    scrollTop: $("#tasks_error_msg").offset().top
                }, 1000);

                setTimeout(function () {
                    $('#tasks_error_msg').html('').hide();
                }, 4000);
            }
        })
    } else {
        return false;
    }
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
        url: base_url+"hr/admin/create-note",
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
        url: base_url+"hr/admin/task-documents",
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

document.addEventListener('DOMContentLoaded', function () {
    // Query the list element
    const list = document.getElementById('list');

    if ($('#list').length > 0) {
        let draggingEle;
        let placeholder;
        let isDraggingStarted = false;

        // The current position of mouse relative to the dragging element
        let x = 0;
        let y = 0;
        
        // Swap two nodes
        const swap = function (nodeA, nodeB) {
            var pos = 1;
            const parentA = nodeA.parentNode;
            const siblingA = nodeA.nextSibling === nodeB ? nodeA : nodeA.nextSibling;

            // Move `nodeA` to before the `nodeB`
            nodeB.parentNode.insertBefore(nodeA, nodeB);

            // Move `nodeB` to before the sibling of `nodeA`
            parentA.insertBefore(nodeB, siblingA);

            $(".draggable").each(function(){
                var id = $(this)[0].id;
                $('#task_position_'+id).val(pos);
                console.log('#task_position_'+id+'---'+pos);
                pos++;
            });
        };

        // Check if `nodeA` is above `nodeB`
        const isAbove = function (nodeA, nodeB) {
            // Get the bounding rectangle of nodes
            const rectA = nodeA.getBoundingClientRect();
            const rectB = nodeB.getBoundingClientRect();

            return rectA.top + rectA.height / 2 < rectB.top + rectB.height / 2;
        };

        const mouseDownHandler = function (e) {
            draggingEle = e.target;

            // Calculate the mouse position
            const rect = draggingEle.getBoundingClientRect();
            x = e.pageX - rect.left;
            y = e.pageY - rect.top;

            // Attach the listeners to `document`
            document.addEventListener('mousemove', mouseMoveHandler);
            document.addEventListener('mouseup', mouseUpHandler);
        };

        const mouseMoveHandler = function (e) {
            const draggingRect = draggingEle.getBoundingClientRect();

            if (!isDraggingStarted) {
                isDraggingStarted = true;

                // Let the placeholder take the height of dragging element
                // So the next element won't move up
                placeholder = document.createElement('div');
                placeholder.classList.add('placeholder');
                draggingEle.parentNode.insertBefore(placeholder, draggingEle.nextSibling);
                placeholder.style.height = `${draggingRect.height}px`;
            }

            // Set position for dragging element
            draggingEle.style.position = 'absolute';
            draggingEle.style.top = `${e.pageY - y}px`;
            draggingEle.style.left = `${e.pageX - x}px`;

            // The current order
            // prevEle
            // draggingEle
            // placeholder
            // nextEle
            const prevEle = draggingEle.previousElementSibling;
            const nextEle = placeholder.nextElementSibling;

            // The dragging element is above the previous element
            // User moves the dragging element to the top
            if (prevEle && isAbove(draggingEle, prevEle)) {
                // The current order    -> The new order
                // prevEle              -> placeholder
                // draggingEle          -> draggingEle
                // placeholder          -> prevEle
                swap(placeholder, draggingEle);
                swap(placeholder, prevEle);
                return;
            }

            // The dragging element is below the next element
            // User moves the dragging element to the bottom
            if (nextEle && isAbove(nextEle, draggingEle)) {
                // The current order    -> The new order
                // draggingEle          -> nextEle
                // placeholder          -> placeholder
                // nextEle              -> draggingEle
                swap(nextEle, placeholder);
                swap(nextEle, draggingEle);
            }
        };

        const mouseUpHandler = function () {
            // Remove the placeholder
            placeholder && placeholder.parentNode.removeChild(placeholder);

            draggingEle.style.removeProperty('top');
            draggingEle.style.removeProperty('left');
            draggingEle.style.removeProperty('position');

            x = null;
            y = null;
            draggingEle = null;
            isDraggingStarted = false;

            // Remove the handlers of `mousemove` and `mouseup`
            document.removeEventListener('mousemove', mouseMoveHandler);
            document.removeEventListener('mouseup', mouseUpHandler);
        };

        // Query all items
        [].slice.call(list.querySelectorAll('.draggable')).forEach(function (item) {
            item.addEventListener('mousedown', mouseDownHandler);
        });
    }
    
});

