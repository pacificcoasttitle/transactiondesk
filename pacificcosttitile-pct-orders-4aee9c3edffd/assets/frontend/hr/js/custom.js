var time_card_listing = '';
var vacation_requests_listing = '';
var incident_reports_listing = '';
var memos_listing = '';
var notifications_listing = '';

$(document).ready(function() {
    "use strict";
    var $preloader = $('#page-preloader'),
    $spinner   = $preloader.find('.spinner-loader');
    $spinner.fadeOut();
    $preloader.delay(50).fadeOut('slow');

    $('#time-cards-clone-group-fields').cloneya({
        maximum: 5
    }).on('after_append.cloneya', function (event, toclone, newclone) {
        $(newclone).find("li").remove();
        $(newclone).find("ul").remove();
        $("#reg_hours1, #ot_hours1, #double_ot1").on('change', function(){
            console.log('dfd');
            var reg_hours = $('#reg_hours1').val() != '' ? $('#reg_hours1').val() : 0;
            var ot_hours = $('#ot_hours1').val() != '' ? $('#ot_hours1').val() : 0;
            var double_ot = $('#double_ot1').val() != '' ? $('#double_ot1').val() : 0;
            $('#total_hours1').val(parseInt(reg_hours) + parseInt (ot_hours) + parseInt(double_ot));
        });
    
        $("#reg_hours2, #ot_hours2, #double_ot2").on('change', function(){
            var reg_hours = $('#reg_hours2').val() != '' ? $('#reg_hours2').val() : 0;
            var ot_hours = $('#ot_hours2').val() != '' ? $('#ot_hours2').val() : 0;
            var double_ot = $('#double_ot2').val() != '' ? $('#double_ot2').val() : 0;
            $('#total_hours2').val(parseInt(reg_hours) + parseInt (ot_hours) + parseInt(double_ot));
        });
    
        $("#reg_hours3, #ot_hours3, #double_ot3").on('change', function(){
            var reg_hours = $('#reg_hours3').val() != '' ? $('#reg_hours3').val() : 0;
            var ot_hours = $('#ot_hours3').val() != '' ? $('#ot_hours3').val() : 0;
            var double_ot = $('#double_ot3').val() != '' ? $('#double_ot3').val() : 0;
            $('#total_hours3').val(parseInt(reg_hours) + parseInt (ot_hours) + parseInt(double_ot));
        });
    
        $("#reg_hours4, #ot_hours4, #double_ot4").on('change', function(){
            var reg_hours = $('#reg_hours4').val() != '' ? $('#reg_hours4').val() : 0;
            var ot_hours = $('#ot_hours4').val() != '' ? $('#ot_hours4').val() : 0;
            var double_ot = $('#double_ot4').val() != '' ? $('#double_ot4').val() : 0;
            $('#total_hours4').val(parseInt(reg_hours) + parseInt (ot_hours) + parseInt(double_ot));
        });
        $(newclone).find("input.exp_date")
        .removeClass('hasDatepicker')
        .removeData('datepicker')
        .unbind()
        .datepicker({
            // defaultDate: "+1w",
            // changeMonth: false,
            // numberOfMonths: 1,
			defaultDate: -1,
			maxDate:0,
			minDate:-14,
            prevText: '<i class="fa fa-chevron-left"></i>',
            nextText: '<i class="fa fa-chevron-right"></i>',
            beforeShow: function() {
                setTimeout(function() {
                    $('.ui-datepicker').css('z-index', 99999999999999);
    
                }, 0);
            }
        });
    }).off('remove.cloneya').on('remove.cloneya', function (event, clone) {
        $(clone).slideToggle('slow', function () {
            $(clone).remove();
        })
    });


    $('#vacation-requests-clone-group-fields').cloneya({
        maximum: 5
    }).on('after_append.cloneya', function (event, toclone, newclone) {
        $(newclone).find("li").remove();
        $(newclone).find("ul").remove();
        $(newclone).find("input.from_date, input.to_date")
            .removeClass('hasDatepicker')
            .removeData('datepicker')
            .unbind()
            .datepicker({
                defaultDate: "+1w",
                changeMonth: false,
                numberOfMonths: 1,
                prevText: '<i class="fa fa-chevron-left"></i>',
                nextText: '<i class="fa fa-chevron-right"></i>',
                beforeShow: function() {
                    setTimeout(function() {
                        $('.ui-datepicker').css('z-index', 99999999999999);
        
                    }, 0);
                }
            });
    }).off('remove.cloneya').on('remove.cloneya', function (event, clone) {
        $(clone).slideToggle('slow', function () {
            $(clone).remove();
        })
    });

    if ($('#time_card_listing').length) {
        time_card_listing = $('#time_card_listing').DataTable({
            "paging": true,
            "lengthChange": false,
            "language": {
                searchPlaceholder: "Search",
                paginate: {
                    next: '<span class="fa fa-angle-right"></span>',
                    previous: '<span class="fa fa-angle-left"></span>',
                },
                "emptyTable": "Record(s) not found.",
                "search": "",
            },
            /*"searching": false,*/
            "bStateSave": true,
            "fnStateSave": function (oSettings, oData) {
                localStorage.setItem('offersDataTables', JSON.stringify(oData));
            },
            "fnStateLoad": function (oSettings) {
                return JSON.parse(localStorage.getItem('offersDataTables'));
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
                url: base_url + "hr/get-time-cards", 
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
                    $("#time_card_listing tbody").append(
                        '<tr><td colspan="4" class="text-center">No records found</td></tr>');
                    $("#time_card_listing_processing").css("display", "none");
                }
            }
        });
    }

    if ($('#vacation_requests_listing').length) {
        vacation_requests_listing = $('#vacation_requests_listing').DataTable({
            "paging": true,
            "lengthChange": false,
            "language": {
                searchPlaceholder: "Search",
                paginate: {
                    next: '<span class="fa fa-angle-right"></span>',
                    previous: '<span class="fa fa-angle-left"></span>',
                },
                "emptyTable": "Record(s) not found.",
                "search": "",
            },
            /*"searching": false,*/
            "bStateSave": true,
            "fnStateSave": function (oSettings, oData) {
                localStorage.setItem('offersDataTables', JSON.stringify(oData));
            },
            "fnStateLoad": function (oSettings) {
                return JSON.parse(localStorage.getItem('offersDataTables'));
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
                url: base_url + "hr/get-vacation-requests", 
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
                    $("#vacation_requests_listing tbody").append(
                        '<tr><td colspan="4" class="text-center">No records found</td></tr>');
                    $("#vacation_requests_listing_processing").css("display", "none");
                }
            }
        });
    }

    if ($('#incident_reports_listing').length) {
        incident_reports_listing = $('#incident_reports_listing').DataTable({
            "paging": true,
            "lengthChange": false,
            "language": {
                searchPlaceholder: "Search",
                paginate: {
                    next: '<span class="fa fa-angle-right"></span>',
                    previous: '<span class="fa fa-angle-left"></span>',
                },
                "emptyTable": "Record(s) not found.",
                "search": "",
            },
            /*"searching": false,*/
            "bStateSave": true,
            "fnStateSave": function (oSettings, oData) {
                localStorage.setItem('offersDataTables', JSON.stringify(oData));
            },
            "fnStateLoad": function (oSettings) {
                return JSON.parse(localStorage.getItem('offersDataTables'));
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
                url: base_url + "hr/get-incident-reports", 
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
                    $("#incident_reports_listing tbody").append(
                        '<tr><td colspan="4" class="text-center">No records found</td></tr>');
                    $("#incident_reports_listing_processing").css("display", "none");
                }
            }
        });
    }

    if ($('#memos_listing').length) {
        memos_listing = $('#memos_listing').DataTable({
            "paging": true,
            "lengthChange": false,
            "language": {
                searchPlaceholder: "Search",
                paginate: {
                    next: '<span class="fa fa-angle-right"></span>',
                    previous: '<span class="fa fa-angle-left"></span>',
                },
                "emptyTable": "Record(s) not found.",
                "search": "",
            },
            /*"searching": false,*/
            "bStateSave": true,
            "fnStateSave": function (oSettings, oData) {
                localStorage.setItem('offersDataTables', JSON.stringify(oData));
            },
            "fnStateLoad": function (oSettings) {
                return JSON.parse(localStorage.getItem('offersDataTables'));
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
                url: base_url + "hr/get-memos", 
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
                    $("#memos_listing tbody").append(
                        '<tr><td colspan="4" class="text-center">No records found</td></tr>');
                    $("#memos_listing_processing").css("display", "none");
                }
            }
        });
    }

    if ($('#common_tbl_listing').length) {
		var tbl_url = $('#common_tbl_listing').attr('data-url');
        $('#common_tbl_listing').DataTable({
            "paging": true,
            "lengthChange": false,
            "language": {
                searchPlaceholder: "Search",
                paginate: {
                    next: '<span class="fa fa-angle-right"></span>',
                    previous: '<span class="fa fa-angle-left"></span>',
                },
                "emptyTable": "Record(s) not found.",
                "search": "",
            },
            /*"searching": false,*/
            "bStateSave": true,
            "fnStateSave": function (oSettings, oData) {
                localStorage.setItem('offersDataTables', JSON.stringify(oData));
            },
            "fnStateLoad": function (oSettings) {
                return JSON.parse(localStorage.getItem('offersDataTables'));
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
                url: tbl_url, 
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
                    $("#common_tbl_listing tbody").append(
                        '<tr><td colspan="4" class="text-center">No records found</td></tr>');
                    $("#common_tbl_listing_processing").css("display", "none");
                }
            }
        });
    }

    if ($('#notifications_listing').length) {
        notifications_listing = $('#notifications_listing').DataTable({
            "paging": true,
            "lengthChange": false,
            "language": {
                searchPlaceholder: "Search",
                paginate: {
                    next: '<span class="fa fa-angle-right"></span>',
                    previous: '<span class="fa fa-angle-left"></span>',
                },
                "emptyTable": "Record(s) not found.",
                "search": "",
            },
            /*"searching": false,*/
            "bStateSave": true,
            "fnStateSave": function (oSettings, oData) {
                localStorage.setItem('offersDataTables', JSON.stringify(oData));
            },
            "fnStateLoad": function (oSettings) {
                return JSON.parse(localStorage.getItem('offersDataTables'));
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
                url: base_url + "hr/get-notifications", 
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
                    $("#notifications_listing tbody").append(
                        '<tr><td colspan="4" class="text-center">No records found</td></tr>');
                    $("#notifications_listing_processing").css("display", "none");
                }
            }
        });
    }

    if ($('#trainings_listing').length) {
        trainings_listing = $('#trainings_listing').DataTable({
            "paging": true,
            "lengthChange": false,
            "language": {
                searchPlaceholder: "Search",
                paginate: {
                    next: '<span class="fa fa-angle-right"></span>',
                    previous: '<span class="fa fa-angle-left"></span>',
                },
                "emptyTable": "Record(s) not found.",
                "search": "",
            },
            /*"searching": false,*/
            "bStateSave": true,
            "fnStateSave": function (oSettings, oData) {
                localStorage.setItem('offersDataTables', JSON.stringify(oData));
            },
            "fnStateLoad": function (oSettings) {
                return JSON.parse(localStorage.getItem('offersDataTables'));
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
                url: base_url + "hr/get-trainings", 
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
                    $("#trainings_listing tbody").append(
                        '<tr><td colspan="4" class="text-center">No records found</td></tr>');
                    $("#trainings_listing_processing").css("display", "none");
                }
            }
        });
    }

    $("#reg_hours, #ot_hours, #double_ot").on('change', function() {
        var reg_hours = $('#reg_hours').val() != '' ? $('#reg_hours').val() : 0;
        var ot_hours = $('#ot_hours').val() != '' ? $('#ot_hours').val() : 0;
        var double_ot = $('#double_ot').val() != '' ? $('#double_ot').val() : 0;
        $('#total_hours').val(parseInt(reg_hours) + parseInt (ot_hours) + parseInt(double_ot));
    });

    $("#incident-report-form").steps({
        bodyTag: "fieldset",
        headerTag: "h2",
        bodyTag: "fieldset",
        transitionEffect: "slideLeft",
        titleTemplate: "<span class='number'>#index#</span> #title#",
        labels: {
            finish: "Submit Form",
            next: "Continue",
            previous: "Go Back",
            loading: "Loading..." 
        },
        onStepChanging: function (event, currentIndex, newIndex){
            if (currentIndex > newIndex){return true; }
            var form = $(this);
            if (currentIndex < newIndex){}
            return form.valid();
        },
        onStepChanged: function (event, currentIndex, priorIndex){
        },
        onFinishing: function (event, currentIndex){
            var form = $(this);
            form.validate().settings.ignore = ":disabled";
            return form.valid();
        },
        onFinished: function (event, currentIndex){
            var form = $(this);
            if(form.valid() === true) {
                $("#incident-report-form")[0].submit();
            } else {
                return form.valid();
            }				
        }
    }).validate({
        errorClass: "state-error",
        validClass: "state-success",
        errorElement: "em",
        onkeyup: false,
        onclick: false,
        rules: {
            firstname: {
                required: true
            },
            lastname: {
                required: true
            },					
            emailaddress: {
                required: true,
                email: true
            },
            employee_number: {
                required: true,
                number: true
            },
            position: {
                required: true,
            },
            incident_date: {
                required: true,
            },
            incident_detail: {
                required: true
            },
            'actions[]': {
                required: true,
            },	
            'num_of_incidents[]': {
                required: true,
                maxlength: 1
            },			
        },
        messages: {
            firstname: {
                required: "Please enter firstname"
            },
            lastname: {
                required: "Please enter lastname"
            },
            emailaddress: {
                required: 'Please enter your email',
                email: 'You must enter a VALID email'
            },
            employee_number: {
                required: 'Please enter your employee number',
                number: 'Please enter numbers only'
            },
            position: {
                required: 'Please enter your position',
            },
            incident_date: {
                required: 'Please select incident date',
            },						
            incident_detail: {
                required: "Please enter the incident detail"
            },
            'actions[]': {
                required: "You must check at least 1 box",
            },
            'num_of_incidents[]': {
                required: "You must check at least 1 box",
                maxlength: "Check no more than {0} boxes"
            },	
        },
        highlight: function(element, errorClass, validClass) {
            $(element).closest('.field').addClass(errorClass).removeClass(validClass);
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).closest('.field').removeClass(errorClass).addClass(validClass);
        },
        errorPlacement: function(error, element) {
            if (element.is(":radio") || element.is(":checkbox")) {
                element.closest('.option-group').after(error);
            } else {
                error.insertAfter(element.parent());
            }
        }
    
    });

	$("#show-hide-form-btn").click(function(){
		$('.show-hide-form-div').toggleClass('hide');
	});
	$(".profile-show-hide-btn").click(function(){
		$('.profile-show-hide').toggleClass('hide');
	});
});

$(function() {
    $(".exp_date").datepicker({
        defaultDate: -1,
        // changeMonth: false,
        // numberOfMonths: 1,
		maxDate:0,
		minDate:-14,
        prevText: '<i class="fa fa-chevron-left"></i>',
        nextText: '<i class="fa fa-chevron-right"></i>',
        onClose: function () {
            $(this).parsley().validate();
        }
    });

    $(".from_date").datepicker({
        defaultDate: "+1w",
        changeMonth: false,
        numberOfMonths: 1,
        prevText: '<i class="fa fa-chevron-left"></i>',
        nextText: '<i class="fa fa-chevron-right"></i>',
        onClose: function () {
            $(this).parsley().validate();
        }
    });

    $(".to_date").datepicker({
        defaultDate: "+1w",
        changeMonth: false,
        numberOfMonths: 1,
        prevText: '<i class="fa fa-chevron-left"></i>',
        nextText: '<i class="fa fa-chevron-right"></i>',
        onClose: function () {
            $(this).parsley().validate();
        }
    });

    $(".incident_date").datepicker({
        defaultDate: "+1w",
        changeMonth: false,
        numberOfMonths: 1,
        prevText: '<i class="fa fa-chevron-left"></i>',
        nextText: '<i class="fa fa-chevron-right"></i>',
        onClose: function () {
            $(this).parsley().validate();
        }
    });
});

function approve_deny_popup(status, requestId) 
{ 
    if(status == 1) {
        $('#approve_deny_title').html('Approve Request Confirmation');   
        $('#approve_deny_msg').html('Are you sure to approve this request?');   
    } else {
        $('#approve_deny_title').html('Deny Request Confirmation');   
        $('#approve_deny_msg').html('Are you sure to deny this request?');  
    }
    $('#status').val(status);
    $('#request_id').val(requestId);
    $('#approve_deny_popup').modal('show');   
    return false;
}

function showMemoInfo(memoId) 
{
    $('#page-preloader').css('background-color', 'rgba(0,0,0,.5)');
    $('#page-preloader').css('display', 'block');
    $.ajax({
        url: base_url + "hr/get-memo-info",
        type: "post",
        data: {
            memoId: memoId
        },
        success: function (response) {
            var res = jQuery.parseJSON(response);
            if(res.status == 'success') {
                $("#subject_container").html(res.memoInfo['subject'] + ' Memo');
                $("#subject").val(res.memoInfo['subject']);
                $("#to").html('<b>To: </b>'+res.memoInfo['to']);
                $("#date").html('<b>Date: </b>'+res.memoInfo['date']);
                $("#from").html('<b>From: </b>'+res.memoInfo['first_name']+' '+res.memoInfo['last_name']);
                $("#description").html(res.memoInfo['description']);
               
            }  
            $('#page-preloader').css('display', 'none');
            $('#memo_information').modal('show');
            $('#memoId').val(memoId);
        }
    });
    return false;
}
$(document).on('click','.submitTimesheetBtn',function(){
	var begin_time = $(this).data('begin-time');
	$("#ts_begin_time").val(begin_time);
	$("#submitTimesheetModal").modal("show");
})

$(document).ajaxComplete(function() {
    $('[data-toggle="popover"]').popover({
        placement: 'top',
        trigger: 'hover'
    });
});
