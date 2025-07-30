var adminList ='';
var users ='';
var time_cards = '';
var vacation_requests = '';
var incident_reports = '';
var user_types = '';
var departments = '';
var positions = '';
var memos = '';
var memos_status = '';
var notifications = '';
var training_status = '';
var branches = '';
var training_branch_manager = '';
var escrow_instruction = '';

$(document).ready(function () {
    $('#hire_date').datepicker().datepicker("setDate", new Date());
	var past_10_years = -(365*10);
    $('#birth_date').datepicker({
		defaultDate: past_10_years,
		changeMonth: true,
      	changeYear: true,
		maxDate: "0",
		yearRange: "-120:+0",
	});
    if ($("#hire_date_val").length != 0) {
        $('#hire_date').val($("#hire_date_val").val());
    }
	if($('#task_position').length) {
		$('#task_position').multiselect({
			includeSelectAllOption: true,
			buttonWidth: '100%',
		});
	}

    $('#status').change(function() {
        if ($(this).prop('checked')){
            $(this).val(1);
        } else {
            $(this).val(0);
        }
    });

    if ($('#admin_users').length)  {
        adminList = $('#admin_users').DataTable({
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
            "dom": 'lf<"FilterOrderListing">rtip',
            "drawCallback": function () {               
                $('.dataTables_paginate > .pagination li').addClass('page-item');
                $('.dataTables_paginate > .pagination a').addClass('page-link');
                $('.dataTables_paginate > .pagination li.previous a, .dataTables_paginate > .pagination li.next a').addClass('rounded');
            },
            "ordering": false,            
            "serverSide": true,
            "ajax": {                
                url: base_url+"hr/admin/get-admin-users", 
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
                    $("#admin_users tbody").append('<tr><td colspan="6" class="text-center">No records found</td></tr>');
                    $("#admin_users_processing").css("display", "none");
                }
            }            
        });
    } 
    
    if ($('#users').length > 0)  {
        users = $('#users').DataTable({
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
                url: base_url+"hr/admin/get-users", 
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
                    $("#users tbody").append('<tr><td colspan="6" class="text-center">No records found</td></tr>');
                    $("#users_processing").css("display", "none");
                }
            }            
        });
    } 

    if ($('#time_cards').length)  {
        time_cards = $('#time_cards').DataTable({
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
                url: base_url+"hr/admin/get-time-cards", 
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
                    $("#time_cards tbody").append('<tr><td colspan="8" class="text-center">No records found</td></tr>');
                    $("#time_cards_processing").css("display", "none");
                }
            }            
        });
    } 

    if ($('#vacation_requests').length)  {
        vacation_requests = $('#vacation_requests').DataTable({
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
                url: base_url+"hr/admin/get-vacation-requests", 
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
                    $("#vacation_requests tbody").append('<tr><td colspan="7" class="text-center">No records found</td></tr>');
                    $("#vacation_requests_processing").css("display", "none");
                }
            }            
        });
    } 

    if ($('#incident_reports').length)  {
        incident_reports = $('#incident_reports').DataTable({
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
            "dom": 'lf<"FilterOrderListing">rtip',
            "drawCallback": function () {               
                $('.dataTables_paginate > .pagination li').addClass('page-item');
                $('.dataTables_paginate > .pagination a').addClass('page-link');
                $('.dataTables_paginate > .pagination li.previous a, .dataTables_paginate > .pagination li.next a').addClass('rounded');
            },
            "ordering": false,            
            "serverSide": true,
            "ajax": {                
                url: base_url+"hr/admin/get-incident-reports", 
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
                    $("#incident_reports tbody").append('<tr><td colspan="7" class="text-center">No records found</td></tr>');
                    $("#incident_reports_processing").css("display", "none");
                }
            }            
        });
    } 

    if ($('#user_types').length > 0)  {
        user_types = $('#user_types').DataTable({
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
                url: base_url+"hr/admin/get-user-types", 
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
                    $("#user_types tbody").append('<tr><td colspan="6" class="text-center">No records found</td></tr>');
                    $("#user_types_processing").css("display", "none");
                }
            }            
        });
    }
    
    if ($('#departments').length > 0)  {
        departments = $('#departments').DataTable({
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
                url: base_url+"hr/admin/get-departments", 
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
                    $("#departments tbody").append('<tr><td colspan="6" class="text-center">No records found</td></tr>');
                    $("#departments_processing").css("display", "none");
                }
            }            
        });
    } 

    if ($('#positions').length > 0)  {
        positions = $('#positions').DataTable({
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
                url: base_url+"hr/admin/get-positions", 
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
                    $("#positions tbody").append('<tr><td colspan="6" class="text-center">No records found</td></tr>');
                    $("#positions_processing").css("display", "none");
                }
            }            
        });
    }

    if ($('#memos').length > 0)  {
        memos = $('#memos').DataTable({
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
                url: base_url+"hr/admin/get-memos", 
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
                    $("#memos tbody").append('<tr><td colspan="6" class="text-center">No records found</td></tr>');
                    $("#memos_processing").css("display", "none");
                }
            }            
        });
    } 

    if ($('#commonAdminTbl').length)  {
		var ajax_url = $('#commonAdminTbl').attr('data-url');
        commonAdminTbl = $('#commonAdminTbl').DataTable({
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
            "dom": 'lf<"FilterOrderListing">rtip',
            "drawCallback": function () {               
                $('.dataTables_paginate > .pagination li').addClass('page-item');
                $('.dataTables_paginate > .pagination a').addClass('page-link');
                $('.dataTables_paginate > .pagination li.previous a, .dataTables_paginate > .pagination li.next a').addClass('rounded');
            },
            "ordering": false,            
            // "serverSide": true,
            "ajax": {                
			url:ajax_url, 
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
                    $("#commonAdminTbl tbody").append('<tr><td colspan="7" class="text-center">No records found</td></tr>');
                    $("#commonAdminTbl_processing").css("display", "none");
                }
            }            
        });
    }

    if ($('#memos_status').length > 0)  {
        memos_status = $('#memos_status').DataTable({
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
            "dom": 'lf<"FilterOrderListing">rtip',
            "drawCallback": function () {               
                $('.dataTables_paginate > .pagination li').addClass('page-item');
                $('.dataTables_paginate > .pagination a').addClass('page-link');
                $('.dataTables_paginate > .pagination li.previous a, .dataTables_paginate > .pagination li.next a').addClass('rounded');
            },
            "ordering": false,            
            // "serverSide": true,
            "ajax": {                
                url: base_url+"hr/admin/get-memos-status", 
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
                    $("#memos_status tbody").append('<tr><td colspan="6" class="text-center">No records found</td></tr>');
                    $("#memos_status_processing").css("display", "none");
                }
            }            
        });
    } 

    if ($('#notifications').length > 0)  {
        notifications = $('#notifications').DataTable({
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
                url: base_url+"hr/admin/get-notifications", 
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
                    $("#notifications tbody").append('<tr><td colspan="6" class="text-center">No records found</td></tr>');
                    $("#notifications_processing").css("display", "none");
                }
            }            
        });
    }

    if ($('#training_status').length > 0)  {
        training_status = $('#training_status').DataTable({
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
                url: base_url+"hr/admin/get-training-status", 
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
                    $("#training_status tbody").append('<tr><td colspan="6" class="text-center">No records found</td></tr>');
                    $("#training_status_processing").css("display", "none");
                }
            }            
        });
    }

    if ($('#training_branch_manager').length) {
        training_branch_manager = $('#training_branch_manager').DataTable({
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
                url: base_url + "hr/admin/get-branch-manager-trainings", 
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
                    $("#training_branch_manager tbody").append(
                        '<tr><td colspan="4" class="text-center">No records found</td></tr>');
                    $("#training_branch_manager_processing").css("display", "none");
                }
            }
        });
    }

    if ($('#branches').length > 0)  {
        branches = $('#branches').DataTable({
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
                url: base_url+"hr/admin/get-branches", 
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
                    $("#branches tbody").append('<tr><td colspan="6" class="text-center">No records found</td></tr>');
                    $("#dbranches_processing").css("display", "none");
                }
            }            
        });
    } 
    if ($('#tbl-escrow-instruction-listing').length > 0)  {
        escrow_instruction = $('#tbl-escrow-instruction-listing').DataTable({
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
                url: base_url+"admin/hr/escrowInstruction/get_escrow_instruction_list", 
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
                    $("#tbl-escrow-instruction-listing tbody").append('<tr><td colspan="6" class="text-center">No records found</td></tr>');
                    $("#tbl-escrow-instruction-listing_processing").css("display", "none");
                }
            }            
        });
    }
	if($("#incident-report-form").length) {

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
				select_employee : {
					required: true
				},
				// firstname: {
				//     required: true
				// },
				// lastname: {
				//     required: true
				// },					
				// emailaddress: {
				//     required: true,
				//     email: true
				// },
				employee_number: {
					required: true,
					number: true
				},
				// position: {
				// 	required: true,
				// },
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
		$('#inc_select_employee').change(function(){
			var first_name = $(this).find(':selected').data('first');
			var last_name = $(this).find(':selected').data('last');
			var email = $(this).find(':selected').data('email');
			var position = $(this).find(':selected').data('position');
            var employee_id = $(this).find(':selected').data('employee');
            console.log(employee_id+'hi');
			$("#firstname").val(first_name);
			$("#lastname").val(last_name);
			$("#emailaddress").val(email);
			$("#position").val(position);
            $("#employee_id").val(employee_id);
		});
		$(".incident_date").datepicker({
			defaultDate: "+1w",
			changeMonth: false,
			numberOfMonths: 1,
			prevText: '<i class="fa fa-chevron-left"></i>',
			nextText: '<i class="fa fa-chevron-right"></i>',
			onClose: function () {
				// $(this).parsley().validate();
			}
		});
	}


	if($('#time-cards-clone-group-fields').length) {

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

		$("#reg_hours, #ot_hours, #double_ot").on('change', function() {
			var reg_hours = $('#reg_hours').val() != '' ? $('#reg_hours').val() : 0;
			var ot_hours = $('#ot_hours').val() != '' ? $('#ot_hours').val() : 0;
			var double_ot = $('#double_ot').val() != '' ? $('#double_ot').val() : 0;
			$('#total_hours').val(parseInt(reg_hours) + parseInt (ot_hours) + parseInt(double_ot));
		});

		$(".exp_date").datepicker({
			defaultDate: -1,
			maxDate:0,
			minDate:-14,
			prevText: '<i class="fa fa-chevron-left"></i>',
			nextText: '<i class="fa fa-chevron-right"></i>',
			onClose: function () {
				$(this).parsley().validate();
			}
		});

	}
	
	if($('#vacation-requests-clone-group-fields').length) {

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
		}

	
});


function deleteAdminUser(id)
{
    if (id=='') {
        alert('Admin ID is required.');
        return false;
    }
    var ready = confirm("Are you sure want to delete?");
    if (ready) {
        $.ajax({
            url: base_url+"hr/admin/delete-admin-user",
            method: "POST",
            data : {
                id : id
            },
            success: function(data){
                var result = jQuery.parseJSON(data);
                if (result.status == 'success') {
                    $('#admin_user_success_msg').html(result.message).show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#admin_user_success_msg").offset().top
                    }, 1000);
                    adminList.ajax.reload( null, false );
                    setTimeout(function () {
                        $('#admin_user_success_msg').html('').hide();
                    }, 4000);
                } else {
                    $('#admin_user_error_msg').html(result.message).show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#admin_user_error_msg").offset().top
                    }, 1000);

                    setTimeout(function () {
                        $('#admin_user_error_msg').html('').hide();
                    }, 4000);
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                $('#admin_user_error_msg').html('Something went wrong. Please try it again.').show();
                $([document.documentElement, document.body]).animate({
                    scrollTop: $("#admin_user_success_msg").offset().top
                }, 1000);

                setTimeout(function () {
                    $('#admin_user_error_msg').html('').hide();
                }, 4000);
            }
        })
    } else {
        return false;
    }
}

function deleteUser(id)
{
    if (id=='') {
        alert('User ID is required.');
        return false;
    }
    var ready = confirm("Are you sure want to delete?");
    if (ready) {
        $.ajax({
            url: base_url+"hr/admin/delete-user",
            method: "POST",
            data : {
                id : id
            },
            success: function(data){
                var result = jQuery.parseJSON(data);
                if (result.status == 'success') {
                    $('#users_success_msg').html(result.message).show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#users_success_msg").offset().top
                    }, 1000);
                    adminList.ajax.reload( null, false );
                    setTimeout(function () {
                        $('#users_success_msg').html('').hide();
                    }, 4000);
                } else {
                    $('#users_error_msg').html(result.message).show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#users_error_msg").offset().top
                    }, 1000);

                    setTimeout(function () {
                        $('#users_error_msg').html('').hide();
                    }, 4000);
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                $('#users_error_msg').html('Something went wrong. Please try it again.').show();
                $([document.documentElement, document.body]).animate({
                    scrollTop: $("#users_success_msg").offset().top
                }, 1000);

                setTimeout(function () {
                    $('#users_error_msg').html('').hide();
                }, 4000);
            }
        })
    } else {
        return false;
    } 
}

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

function deleteUserType(id)
{
    if (id=='') {
        alert('User Type ID is required.');
        return false;
    }
    var ready = confirm("Are you sure want to delete?");
    if (ready) {
        $.ajax({
            url: base_url+"hr/admin/delete-user-type",
            method: "POST",
            data : {
                id : id
            },
            success: function(data){
                var result = jQuery.parseJSON(data);
                if (result.status == 'success') {
                    $('#user_types_success_msg').html(result.message).show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#user_types_success_msg").offset().top
                    }, 1000);
                    user_types.ajax.reload( null, false );
                    setTimeout(function () {
                        $('#user_types_success_msg').html('').hide();
                    }, 4000);
                } else {
                    $('#user_types_error_msg').html(result.message).show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#user_types_error_msg").offset().top
                    }, 1000);

                    setTimeout(function () {
                        $('#user_types_error_msg').html('').hide();
                    }, 4000);
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                $('#user_types_error_msg').html('Something went wrong. Please try it again.').show();
                $([document.documentElement, document.body]).animate({
                    scrollTop: $("#user_types_error_msg").offset().top
                }, 1000);

                setTimeout(function () {
                    $('#user_types_error_msg').html('').hide();
                }, 4000);
            }
        })
    } else {
        return false;
    } 
}

function deleteDepartment(id)
{
    if (id=='') {
        alert('Department ID is required.');
        return false;
    }
    var ready = confirm("Are you sure want to delete?");
    if (ready) {
        $.ajax({
            url: base_url+"hr/admin/delete-department",
            method: "POST",
            data : {
                id : id
            },
            success: function(data){
                var result = jQuery.parseJSON(data);
                if (result.status == 'success') {
                    $('#departments_success_msg').html(result.message).show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#departments_success_msg").offset().top
                    }, 1000);
                    departments.ajax.reload( null, false );
                    setTimeout(function () {
                        $('#departments_success_msg').html('').hide();
                    }, 4000);
                } else {
                    $('#departments_error_msg').html(result.message).show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#departments_error_msg").offset().top
                    }, 1000);

                    setTimeout(function () {
                        $('#departments_error_msg').html('').hide();
                    }, 4000);
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                $('#departments_error_msg').html('Something went wrong. Please try it again.').show();
                $([document.documentElement, document.body]).animate({
                    scrollTop: $("#departments_error_msg").offset().top
                }, 1000);

                setTimeout(function () {
                    $('#departments_error_msg').html('').hide();
                }, 4000);
            }
        })
    } else {
        return false;
    } 
}

function deletePosition(id)
{
    if (id=='') {
        alert('Position ID is required.');
        return false;
    }
    var ready = confirm("Are you sure want to delete?");
    if (ready) {
        $.ajax({
            url: base_url+"hr/admin/delete-position",
            method: "POST",
            data : {
                id : id
            },
            success: function(data){
                var result = jQuery.parseJSON(data);
                if (result.status == 'success') {
                    $('#positions_success_msg').html(result.message).show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#positions_success_msg").offset().top
                    }, 1000);
                    positions.ajax.reload( null, false );
                    setTimeout(function () {
                        $('#positions_success_msg').html('').hide();
                    }, 4000);
                } else {
                    $('#positions_success_msg').html(result.message).show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#positions_error_msg").offset().top
                    }, 1000);

                    setTimeout(function () {
                        $('#positions_error_msg').html('').hide();
                    }, 4000);
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                $('#positions_error_msg').html('Something went wrong. Please try it again.').show();
                $([document.documentElement, document.body]).animate({
                    scrollTop: $("#positions_error_msg").offset().top
                }, 1000);

                setTimeout(function () {
                    $('#positions_error_msg').html('').hide();
                }, 4000);
            }
        })
    } else {
        return false;
    } 
}

function deleteMemo(id)
{
    if (id=='') {
        alert('Memo ID is required.');
        return false;
    }
    var ready = confirm("Are you sure want to delete?");
    if (ready) {
        $.ajax({
            url: base_url+"hr/admin/delete-memo",
            method: "POST",
            data : {
                id : id
            },
            success: function(data){
                var result = jQuery.parseJSON(data);
                if (result.status == 'success') {
                    $('#memos_success_msg').html(result.message).show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#memos_success_msg").offset().top
                    }, 1000);
                    memos.ajax.reload( null, false );
                    setTimeout(function () {
                        $('#memos_success_msg').html('').hide();
                    }, 4000);
                } else {
                    $('#memos_error_msg').html(result.message).show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#memos_error_msg").offset().top
                    }, 1000);

                    setTimeout(function () {
                        $('#memos_error_msg').html('').hide();
                    }, 4000);
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                $('#memos_error_msg').html('Something went wrong. Please try it again.').show();
                $([document.documentElement, document.body]).animate({
                    scrollTop: $("#memos_error_msg").offset().top
                }, 1000);

                setTimeout(function () {
                    $('#memos_error_msg').html('').hide();
                }, 4000);
            }
        })
    } else {
        return false;
    } 
}

function deleteBranch(id)
{
    if (id=='') {
        alert('Branch ID is required.');
        return false;
    }
    var ready = confirm("Are you sure want to delete?");
    if (ready) {
        $.ajax({
            url: base_url+"hr/admin/delete-branch",
            method: "POST",
            data : {
                id : id
            },
            success: function(data){
                var result = jQuery.parseJSON(data);
                if (result.status == 'success') {
                    $('#branches_success_msg').html(result.message).show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#branches_success_msg").offset().top
                    }, 1000);
                    branches.ajax.reload( null, false );
                    setTimeout(function () {
                        $('#branches_success_msg').html('').hide();
                    }, 4000);
                } else {
                    $('#branches_error_msg').html(result.message).show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#branches_error_msg").offset().top
                    }, 1000);

                    setTimeout(function () {
                        $('#branches_error_msg').html('').hide();
                    }, 4000);
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                $('#branches_error_msg').html('Something went wrong. Please try it again.').show();
                $([document.documentElement, document.body]).animate({
                    scrollTop: $("#branches_error_msg").offset().top
                }, 1000);

                setTimeout(function () {
                    $('#branches_error_msg').html('').hide();
                }, 4000);
            }
        })
    } else {
        return false;
    } 
}

function deleteEscrowInstruction(id)
{
    if (id=='') {
        alert('Branch ID is required.');
        return false;
    }
    var ready = confirm("Are you sure want to delete?");
    if (ready) {
        $.ajax({
            url: base_url+"hr/admin/delete-escrow-instruction",
            method: "POST",
            data : {
                id : id
            },
            success: function(data){
                var result = jQuery.parseJSON(data);
                if (result.status == 'success') {
                    $('#escrow_instruction_success_msg').html(result.message).show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#escrow_instruction_success_msg").offset().top
                    }, 1000);
                    escrow_instruction.ajax.reload( null, false );
                    setTimeout(function () {
                        $('#escrow_instruction_success_msg').html('').hide();
                    }, 4000);
                } else {
                    $('#escrow_instruction_error_msg').html(result.message).show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#escrow_instruction_error_msg").offset().top
                    }, 1000);

                    setTimeout(function () {
                        $('#escrow_instruction_error_msg').html('').hide();
                    }, 4000);
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                $('#escrow_instruction_error_msg').html('Something went wrong. Please try it again.').show();
                $([document.documentElement, document.body]).animate({
                    scrollTop: $("#escrow_instruction_error_msg").offset().top
                }, 1000);

                setTimeout(function () {
                    $('#escrow_instruction_error_msg').html('').hide();
                }, 4000);
            }
        })
    } else {
        return false;
    } 
}

$('#pct__delete_modal').on('show.bs.modal', function (event) {
	let record_id = $(event.relatedTarget).data('id') 
	$(this).find('.modal-body #pct__delete_record_id').val(record_id)
})

if($("#training__material").length) {
	var new_mat_id = 1;
	$( ".select_material_type" ).click(function() {
		var selected_val = $(this).val();
		var new_input_type = '';
		var new_div_id="matrerial_type_"+new_mat_id;
		if(selected_val == 'url') {
			new_input_type = `<div  class="clearfix"><div class="float-left">Enter Url<span class="required"> *</span></div><div class="float-right"><button type="button" class="btn btn-danger btn-sm remove_material_type" data-div="`+new_div_id+`"><i class="fas fa-trash"></i></button></div></div>
			<div><input type="url" class="form-control" placeholder="Url" name="material_url[]" id="material_url_`+new_mat_id+`" required="required"></div>`;
		}
		else if(selected_val == 'file') {
			new_input_type = `<div  class="clearfix"><div class="float-left">Select File<span class="required"> *</span></div><div class="float-right"><button type="button" class="btn btn-danger btn-sm remove_material_type" data-div="`+new_div_id+`"><i class="fas fa-trash"></i></button></div></div>
			<div><input type="file" accept="application/pdf" class="form-control" name="material_file[]" id="material_file_`+new_mat_id+`" required="required"></div>`;
		}
		var new_input = `<div id="`+new_div_id+`" class="form-group">`+new_input_type+`</div>`;

	$('.material_container').append(new_input);
	new_mat_id++;

	});

	$(document).on('click','.remove_material_type', function (event) {
		var removed_div = '#'+$(this).attr('data-div');
		console.log(removed_div);
		$(removed_div).remove();
	});
}

$('input[name=user_selection]').on('change', function() {
    var user_selection_val = $('input[name=user_selection]:checked').val(); 
    if (user_selection_val == 'based_on_user_listing') {
        $('#user_container').removeClass('d-none');
        $('#department_container').addClass('d-none');
        $('#position_container').addClass('d-none');
        $('input[name=user_selection]').prop('required',true);
        // $('#traning_position').prop('required', false);
        $('#traning_department').prop('required', false);
    } else if (user_selection_val == 'based_on_position_and_department') {
        $('#user_container').addClass('d-none');
        $('#department_container').removeClass('d-none');
        $('#position_container').removeClass('d-none');
        $('input[name=user_selection]').prop('required',false);
        // $('#traning_position').prop('required', true);
        $('#traning_department').prop('required', true);
    }
});

function showMemoInfo(memoId) 
{
    $('#page-preloader').css('background-color', 'rgba(0,0,0,.5)');
    $('#page-preloader').css('display', 'block');
    $.ajax({
        url: base_url + "hr/admin/get-memo-info",
        type: "post",
        data: {
            memoId: memoId
        },
        success: function (response) {
            var res = jQuery.parseJSON(response);
            if(res.status == 'success') {
                $("#subject_container").html(res.memoInfo['subject']+ ' Memo');
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

$(document).on('click','.ot-action-btn',function(){
	var ot_emp_id = $(this).data('user');
	var ot_date = $(this).data('ot-date');
	var ot_is_approved = $(this).data('is-approved');

	$('#ot_employee_id').val(ot_emp_id);
	$('#ot_date').val(ot_date);
	$('#ot_is_approved').val(ot_is_approved);
	$('#ot_request_type').text('Reject');
	if(ot_is_approved) {
		$('#ot_request_type').text('Approve');
	}
	$("#ot_approve_deny_popup").modal('show');
})

$(document).on('click','.timecard-action-btn',function(){
	var req_id = $(this).data('req-id');

	$('#deny_request_id').val(req_id);
	
	$("#timecard_deny_popup").modal('show');

})

$(document).on('click','.timesheet-action-btn',function(){
	var req_id = $(this).data('req-id');

	$('#deny_request_id').val(req_id);
	
	$("#timesheet_deny_popup").modal('show');

})

$(document).on('click','.vacation-request-action-btn',function(){
	var req_id = $(this).data('req-id');
	$('#deny_request_id').val(req_id);
	$("#vacation_request_deny_popup").modal('show');
});

$(document).ajaxComplete(function() {
    $('[data-toggle="popover"]').popover({
        placement: 'top',
        trigger: 'hover'
    });
});
