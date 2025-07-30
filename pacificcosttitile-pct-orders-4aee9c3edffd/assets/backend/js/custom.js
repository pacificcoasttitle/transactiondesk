var customer_list = '';
var agent_list = '';
var credentials_customer_list = '';
var incorrect_customer_list = '';
var escrow_officers_list = '';
var fees_list = '';
var fees_type_list = '';
var code_book_list = '';
var forms_list = '';
var pre_listing_documents = '';
var lp_order_list = '';
var admin_user_logs = '';
var lp_document_list = '';
var daily_email_receiver_list = '';

$(document).ready(function () {

    if ($('.sectionSelect').length) {
        $('.sectionSelect').selectize({
            sortField: 'text'
        });
    }

    $(document).on(' change', 'input[name="check_all"]', function () {
        $('.action_all').prop("checked", this.checked);
    });

    // $('#clone-subtype-option').cloneya({
    //     maximum: 5
    // }).on('after_append.cloneya', function (event, toclone, newclone) {
    //     var name = $(newclone).find("select[name='subtype[]']").attr('id');
    //     // if($('#'+name).length) {
    //     //     $('#'+name).multiselect({
    //     //         includeSelectAllOption: true,
    //     //         buttonWidth: '100%',
    //     //     });
    //     // }
    //     // }
    //     // $('#'+name).multiselect({
    //     //     includeSelectAllOption: true
    //     //   });
    // }).off('remove.cloneya').on('remove.cloneya', function (event, clone) {
    //     $(clone).slideToggle('slow', function () {
    //         $(clone).remove();
    //     })
    // });

    // Add active class to menu
    // if(jQuery('#users').children().hasClass('active')) {
    //     jQuery('#users').parent('li').addClass('active');
    //     jQuery('#users').addClass('show');
    // } else {
    //     jQuery('#users').removeClass('show');
    //     jQuery('#users').parent('li').removeClass('active');
    // }

    // // Add active class to menu
    // if(jQuery('#documents').children().hasClass('active')) {
    //     jQuery('#documents').parent('li').addClass('active');
    //     jQuery('#documents').addClass('show');
    // } else {
    //     jQuery('#documents').removeClass('show');
    //     jQuery('#documents').parent('li').removeClass('active');
    // }

    // // Add active class to logs menu
    // if(jQuery('#logs').children().hasClass('active')) {
    //     jQuery('#logs').parent('li').addClass('active');
    //     jQuery('#logs').addClass('show');
    // } else {
    //     jQuery('#logs').removeClass('show');
    //     jQuery('#logs').parent('li').removeClass('active');
    // }

    // // Add active class to cpl menu
    // if(jQuery('#cpl_branches_section').children().hasClass('active')) {
    //     jQuery('#cpl_branches_section').parent('li').addClass('active');
    //     jQuery('#cpl_branches_section').addClass('show');
    // } else {
    //     jQuery('#cpl_branches_section').removeClass('show');
    //     jQuery('#cpl_branches_section').parent('li').removeClass('active');
    // }

    $('.sidebar .nav-item.dropdown .dropdown-menu a.dropdown-item').each(function () {
        if ($(this).hasClass('active')) {
            $(this).parent().parent().find('.dropdown-toggle').trigger('click');
        }
    });

    if ($('select').length) {
        $('select').not(".sectionSelect").selectpicker();
    }

    if ($('#tbl-customers-listing').length || $('#tbl-agents-listing').length || $('#tbl-lenders-listing').length || $('#tbl-sales-rep-listing').length || $('#tbl-title-officer-listing').length || $('#tbl-credentials-customers-listing').length || $('#tbl-cpl-documents-listing').length || $('#tbl-ion-fraud-documents-listing').length || $('#tbl-new-users-listing').length || $('#tbl-master-users-listing').length || $('#tbl-companies-listing').length || $('#tbl-cpl-proposed-users-listing').length || $('#tbl-escrow-instruction-listing').length || $('#tbl-lp-xml-listing').length) {
        jQuery.fn.DataTable.Api.register('buttons.exportData()', function (options) {

            if (this.context.length) {
                if (this.context[0].sTableId == 'tbl-customers-listing') {
                    var jsonResult = $.ajax({
                        type: "POST",
                        url: base_url + "admin/order/home/get_customer_list",
                        data: {
                            keyword: $('#tbl-customers-listing_filter input').val(),
                        },
                        success: function (result) {

                        },
                        async: false
                    });
                    var data = jsonResult.responseText;
                    var res = jQuery.parseJSON(data);
                    return { body: res.data, header: $("#tbl-customers-listing thead tr th:not(:last-child)").map(function () { return this.innerHTML; }).get() };
                } else if (this.context[0].sTableId == 'tbl-escrow-instruction-listing') {
                    var jsonResult = $.ajax({
                        type: "POST",
                        url: base_url + "admin/order/escrowInstruction/get_escrow_instruction_list",
                        data: {
                            keyword: $('#tbl-escrow-instruction-listing_filter input').val(),
                        },
                        success: function (result) {
                        },
                        async: false
                    });
                    var data = jsonResult.responseText;
                    var res = jQuery.parseJSON(data);
                    return { body: res.data, header: $("#tbl-escrow-instruction-listing thead tr th:not(:last-child)").map(function () { return this.innerHTML; }).get() };
                }
                else if (this.context[0].sTableId == 'tbl-lenders-listing') {
                    var jsonResult = $.ajax({
                        type: "POST",
                        url: base_url + "admin/order/home/get_lender_list",
                        data: {
                            keyword: $('#tbl-lenders-listing_filter input').val(),
                        },
                        success: function (result) {
                        },
                        async: false
                    });
                    var data = jsonResult.responseText;
                    var res = jQuery.parseJSON(data);
                    return { body: res.data, header: $("#tbl-lenders-listing thead tr th:not(:last-child)").map(function () { return this.innerHTML; }).get() };
                }
                else if (this.context[0].sTableId == 'tbl-agents-listing') {
                    var jsonResult = $.ajax({
                        type: "POST",
                        url: base_url + "admin/order/agent/get_agent_list",
                        data: {
                            keyword: $('#tbl-agents-listing_filter input').val(),
                        },
                        success: function (result) {
                        },
                        async: false
                    });
                    var data = jsonResult.responseText;
                    var res = jQuery.parseJSON(data);
                    return { body: res.data, header: $("#tbl-agents-listing thead tr th:not(:last-child)").map(function () { return this.innerHTML; }).get() };
                }
                else if (this.context[0].sTableId == 'tbl-sales-rep-listing') {
                    var jsonResult = $.ajax({
                        type: "POST",
                        url: base_url + "order/admin/get-sales-rep-list",
                        data: {
                            keyword: $('#tbl-sales-rep-listing_filter input').val(),
                        },
                        success: function (result) {
                        },
                        async: false
                    });
                    var data = jsonResult.responseText;
                    var res = jQuery.parseJSON(data);
                    return { body: res.data, header: $("#tbl-sales-rep-listing thead tr th:not(:last-child)").map(function () { return this.innerHTML; }).get() };
                }
                else if (this.context[0].sTableId == 'tbl-credentials-customers-listing') {
                    var jsonResult = $.ajax({
                        type: "POST",
                        url: base_url + "admin/order/customer/get_customer_list",
                        data: {
                            keyword: $('#tbl-credentials-customers-listing_filter input').val(),
                        },
                        success: function (result) {

                        },
                        async: false
                    });
                    var data = jsonResult.responseText;
                    var res = jQuery.parseJSON(data);
                    return { body: res.data, header: $("#tbl-credentials-customers-listing thead tr th:not(:last-child)").map(function () { return this.innerHTML; }).get() };
                }
                else if (this.context[0].sTableId == 'tbl-cpl-documents-listing') {
                    var jsonResult = $.ajax({
                        type: "POST",
                        url: base_url + "admin/order/home/get_cpl_document_list",
                        data: {
                            keyword: $('#tbl-cpl-documents-listing_filter input').val(),
                        },
                        success: function (result) {
                        },
                        async: false
                    });
                    var data = jsonResult.responseText;
                    var res = jQuery.parseJSON(data);
                    return { body: res.data, header: $("#tbl-cpl-documents-listing thead tr th:not(:last-child)").map(function () { return this.innerHTML; }).get() };
                }

                else if (this.context[0].sTableId == 'tbl-ion-fraud-documents-listing') {
                    var jsonResult = $.ajax({
                        type: "POST",
                        url: base_url + "admin/order/home/get_ion_fraud_document_list",
                        data: {
                            keyword: $('#tbl-ion-fraud-documents-listing_filter input').val(),
                        },
                        success: function (result) {
                        },
                        async: false
                    });
                    var data = jsonResult.responseText;
                    var res = jQuery.parseJSON(data);
                    return { body: res.data, header: $("#tbl-ion-fraud-documents-listing thead tr th:not(:last-child)").map(function () { return this.innerHTML; }).get() };
                }
                else if (this.context[0].sTableId == 'tbl-grant-documents-listing') {
                    var jsonResult = $.ajax({
                        type: "POST",
                        url: base_url + "admin/order/home/get_grant_deed_document_list",
                        data: {
                            keyword: $('#tbl-grant-documents-listing_filter input').val(),
                        },
                        success: function (result) {
                        },
                        async: false
                    });
                    var data = jsonResult.responseText;
                    var res = jQuery.parseJSON(data);
                    return { body: res.data, header: $("#tbl-grant-documents-listing thead tr th:not(:last-child)").map(function () { return this.innerHTML; }).get() };
                }
                else if (this.context[0].sTableId == 'tbl-lv-documents-listing') {
                    var jsonResult = $.ajax({
                        type: "POST",
                        url: base_url + "admin/order/home/get_lv_document_list",
                        data: {
                            keyword: $('#tbl-lv-documents-listing_filter input').val(),
                        },
                        success: function (result) {
                        },
                        async: false
                    });
                    var data = jsonResult.responseText;
                    var res = jQuery.parseJSON(data);
                    return { body: res.data, header: $("#tbl-lv-documents-listing thead tr th:not(:last-child)").map(function () { return this.innerHTML; }).get() };
                }
                else if (this.context[0].sTableId == 'tbl-tax-documents-listing') {
                    var jsonResult = $.ajax({
                        type: "POST",
                        url: base_url + "admin/order/home/get_tax_document_list",
                        data: {
                            keyword: $('#tbl-tax-documents-listing_filter input').val(),
                        },
                        success: function (result) {
                        },
                        async: false
                    });
                    var data = jsonResult.responseText;
                    var res = jQuery.parseJSON(data);
                    return { body: res.data, header: $("#tbl-tax-documents-listing thead tr th:not(:last-child)").map(function () { return this.innerHTML; }).get() };
                }
                else if (this.context[0].sTableId == 'tbl-pre-listing-documents-listing') {
                    var jsonResult = $.ajax({
                        type: "POST",
                        url: base_url + "admin/order/home/get_pre_listing_document_list",
                        data: {
                            keyword: $('#tbl-pre-listing-documents-listing_filter input').val(),
                        },
                        success: function (result) {
                        },
                        async: false
                    });
                    var data = jsonResult.responseText;
                    var res = jQuery.parseJSON(data);
                    return { body: res.data, header: $("#tbl-pre-listing-documents-listing thead tr th:not(:last-child)").map(function () { return this.innerHTML; }).get() };
                } else if (this.context[0].sTableId == 'tbl-lp-listing-documents-listing') {
                    var jsonResult = $.ajax({
                        type: "POST",
                        url: base_url + "admin/order/home/get_pre_listing_document_list",
                        data: {
                            keyword: $('#tbl-lp-listing-documents-listing_filter input').val(),
                        },
                        success: function (result) {
                        },
                        async: false
                    });
                    var data = jsonResult.responseText;
                    var res = jQuery.parseJSON(data);
                    return { body: res.data, header: $("#tbl-lp-listing-documents-listing thead tr th:not(:last-child)").map(function () { return this.innerHTML; }).get() };
                }
                else if (this.context[0].sTableId == 'tbl-curative-documents-listing') {
                    var jsonResult = $.ajax({
                        type: "POST",
                        url: base_url + "admin/order/home/get_curative_document_list",
                        data: {
                            keyword: $('#tbl-curative-documents-listing_filter input').val(),
                        },
                        success: function (result) {
                        },
                        async: false
                    });
                    var data = jsonResult.responseText;
                    var res = jQuery.parseJSON(data);
                    return { body: res.data, header: $("#tbl-curative-documents-listing thead tr th:not(:last-child)").map(function () { return this.innerHTML; }).get() };
                }
                else if (this.context[0].sTableId == 'tbl-new-users-listing') {
                    var jsonResult = $.ajax({
                        type: "POST",
                        url: base_url + "admin/order/home/get_new_users_list",
                        data: {
                            keyword: $('#tbl-new-users-listing_filter input').val(),
                        },
                        success: function (result) {
                        },
                        async: false
                    });
                    var data = jsonResult.responseText;
                    var res = jQuery.parseJSON(data);
                    return { body: res.data, header: $("#tbl-new-users-listing thead tr th:not('.not-take')").map(function () { return this.innerHTML; }).get() };
                }
                else if (this.context[0].sTableId == 'tbl-master-users-listing') {
                    var jsonResult = $.ajax({
                        type: "POST",
                        url: base_url + "admin/order/home/get_master_users_list",
                        data: {
                            keyword: $('#tbl-master-users-listing_filter input').val(),
                        },
                        success: function (result) {
                        },
                        async: false
                    });
                    var data = jsonResult.responseText;
                    var res = jQuery.parseJSON(data);
                    return { body: res.data, header: $("#tbl-master-users-listing thead tr th:not('.not-take')").map(function () { return this.innerHTML; }).get() };
                }
                else if (this.context[0].sTableId == 'tbl-companies-listing') {
                    var jsonResult = $.ajax({
                        type: "POST",
                        url: base_url + "admin/order/home/get_companies_list",
                        data: {
                            keyword: $('#tbl-companies-listing_filter input').val(),
                        },
                        success: function (result) {
                        },
                        async: false
                    });
                    var data = jsonResult.responseText;
                    var res = jQuery.parseJSON(data);
                    return { body: res.data, header: $("#tbl-companies-listing thead tr th:not('.not-take')").map(function () { return this.innerHTML; }).get() };
                }
                else if (this.context[0].sTableId == 'tbl-cpl-proposed-users-listing') {
                    var jsonResult = $.ajax({
                        type: "POST",
                        url: base_url + "admin/order/home/get_cpl_proposed_users_list",
                        data: {
                            keyword: $('#tbl-cpl-proposed-users-listing_filter input').val(),
                        },
                        success: function (result) {
                        },
                        async: false
                    });
                    var data = jsonResult.responseText;
                    var res = jQuery.parseJSON(data);
                    return { body: res.data, header: $("#tbl-cpl-proposed-users-listing thead tr th:not('.not-take')").map(function () { return this.innerHTML; }).get() };
                }
                else if (this.context[0].sTableId == 'tbl-password-listing') {
                    var jsonResult = $.ajax({
                        type: "POST",
                        url: base_url + "admin/order/home/get_password_list",
                        data: {
                            keyword: $('#tbl-password-listing_filter input').val(),
                        },
                        success: function (result) {
                        },
                        async: false
                    });
                    var data = jsonResult.responseText;
                    var res = jQuery.parseJSON(data);
                    return { body: res.data, header: $("#tbl-password-listing thead tr th:not(:last-child)").map(function () { return this.innerHTML; }).get() };
                }
                else if (this.context[0].sTableId == 'tbl-safewire-orders-listing') {
                    var jsonResult = $.ajax({
                        type: "POST",
                        url: base_url + "admin/order/order/get_safewire_orders_list",
                        data: {
                            keyword: $('#tbl-safewire-orders-listing_filter input').val(),
                        },
                        success: function (result) {
                        },
                        async: false
                    });
                    var data = jsonResult.responseText;
                    var res = jQuery.parseJSON(data);
                    return { body: res.data, header: $("#tbl-safewire-orders-listing thead tr th:not(:last-child)").map(function () { return this.innerHTML; }).get() };
                }
                else if (this.context[0].sTableId == 'tbl-notifications-listing') {
                    var jsonResult = $.ajax({
                        type: "POST",
                        url: base_url + "admin/order/home/get_notifications_list",
                        data: {
                            keyword: $('#tbl-notifications-listing_filter input').val(),
                        },
                        success: function (result) {
                        },
                        async: false
                    });
                    var data = jsonResult.responseText;
                    var res = jQuery.parseJSON(data);
                    return { body: res.data, header: $("#tbl-notifications-listing thead tr th:not(:last-child)").map(function () { return this.innerHTML; }).get() };
                } else if (this.context[0].sTableId == 'tbl-lp-xml-listing') {
                    var jsonResult = $.ajax({
                        type: "POST",
                        url: base_url + "admin/order/home/getLpXmlLogs",
                        data: {
                            keyword: $('#tbl-lp-xml-listing_filter input').val(),
                        },
                        success: function (result) {
                        },
                        async: false
                    });
                    var data = jsonResult.responseText;
                    var res = jQuery.parseJSON(data);
                    return { body: res.data, header: $("#tbl-lp-xml-listing thead tr th:not(:last-child)").map(function () { return this.innerHTML; }).get() };
                }
                else {
                    var jsonResult = $.ajax({
                        type: "POST",
                        url: base_url + "order/admin/get-title-officer-list",
                        data: {
                            keyword: $('#tbl-title-officer-listing_filter input').val(),
                        },
                        success: function (result) {
                        },
                        async: false
                    });
                    var data = jsonResult.responseText;
                    var res = jQuery.parseJSON(data);
                    return { body: res.data, header: $("#tbl-title-officer-listing thead tr th:not(:last-child)").map(function () { return this.innerHTML; }).get() };
                }

            }
        });
    }


    /* Customer listing table */
    if ($('#tbl-customers-listing').length) {
        customer_list = $('#tbl-customers-listing').DataTable({
            /*"pageLength": 2,*/
            "paging": true,
            "lengthMenu": [10, 20, 50, 100, 200, 500, 1000],
            "columnDefs": [
                { "searchable": false, "targets": [0, 1] }
            ],
            "language": {
                searchPlaceholder: "#Name, Email, Company Name",
                paginate: {
                    next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                    previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                },
                "emptyTable": "Record(s) not found.",
            },
            initComplete: function () {
                var $buttons = jQuery('.dt-buttons').hide();
                jQuery('#export-csv').on('click', function () {
                    var export_type = jQuery(this).attr('data-export-type');
                    if (export_type) {
                        var btnClass = '.buttons-' + export_type;
                    }
                    if (btnClass) $buttons.find(btnClass).click();
                })
            },
            dom: 'Blfrtip',
            buttons: [
                {
                    extend: 'csvHtml5',
                    text: 'Export',
                    title: 'Customers',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8],
                        format: {
                            body: function (data, row, column, node) {
                                // Strip $ from salary column to make it numeric
                                return (column === 0 || column === 1 || column === 2 || column === 3 || column === 4 || column === 5 || column === 6) ?
                                    data.replace(/[$,]/g, '') :
                                    data;
                            }
                        }
                    }
                },
            ],
            "drawCallback": function () {
                $('.dataTables_paginate > .pagination li').addClass('page-item');
                $('.dataTables_paginate > .pagination a').addClass('page-link');
                $('.dataTables_paginate > .pagination li.previous a, .dataTables_paginate > .pagination li.next a').addClass('rounded');
            },
            "ordering": false,
            "serverSide": true,
            "ajax": {
                url: base_url + "admin/order/home/get_customer_list", // json datasource
                type: "post", // method  , by default get
                beforeSend: function () {
                    $("#page-preloader").show();
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
                    $("#tbl-customers-listing tbody").append('<tr><td colspan="12" class="text-center">No records found</td></tr>');
                    $("#tbl-customers-listing_processing").css("display", "none");

                },
                complete: function () {
                    $("#page-preloader").hide();
                }
            }
        });
    }

    if ($('#tbl-escrow-instruction-listing').length) {
        customer_list = $('#tbl-escrow-instruction-listing').DataTable({
            /*"pageLength": 2,*/
            "paging": true,
            "lengthMenu": [10, 20, 50, 100, 200, 500, 1000],
            "columnDefs": [
                { "searchable": false, "targets": [0, 1] }
            ],
            "language": {
                // searchPlaceholder: "Customer Number",
                paginate: {
                    next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                    previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                },
                "emptyTable": "Record(s) not found.",
            },
            initComplete: function () {
                var $buttons = jQuery('.dt-buttons').hide();
                jQuery('#export-csv').on('click', function () {
                    var export_type = jQuery(this).attr('data-export-type');
                    if (export_type) {
                        var btnClass = '.buttons-' + export_type;
                    }
                    if (btnClass) $buttons.find(btnClass).click();
                })
            },
            dom: 'Blfrtip',
            buttons: [
                {
                    extend: 'csvHtml5',
                    text: 'Export',
                    title: 'Customers',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4],
                        format: {
                            body: function (data, row, column, node) {
                                // Strip $ from salary column to make it numeric
                                return (column === 0 || column === 1 || column === 2 || column === 3 || column === 4) ?
                                    data.replace(/[$,]/g, '') :
                                    data;
                            }
                        }
                    }
                },
            ],
            "drawCallback": function () {
                $('.dataTables_paginate > .pagination li').addClass('page-item');
                $('.dataTables_paginate > .pagination a').addClass('page-link');
                $('.dataTables_paginate > .pagination li.previous a, .dataTables_paginate > .pagination li.next a').addClass('rounded');
            },
            "ordering": false,
            "serverSide": true,
            "ajax": {
                url: base_url + "admin/order/escrowInstruction/get_escrow_instruction_list", // json datasource
                type: "post", // method  , by default get
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    if (parseInt(XMLHttpRequest.status) == 419) {
                        alert("You are logged out. Please login.");
                    }
                    if (parseInt(XMLHttpRequest.status) == 419) {
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                    }
                    $("#tbl-escrow-instruction-listing tbody").append('<tr><td colspan="5" class="text-center">No records found</td></tr>');
                    $("#tbl-escrow-instruction-listing_processing").css("display", "none");

                }
            }
        });
    }

    if (jQuery('#importFrm').length) {
        jQuery('#importFrm').validate({
            rules: {
                file: "required"
            },
            messages: {
                file: "Please upload file to import"
            },
            submitHandler: function (form) {
                form.submit();
                /*$(form).ajaxSubmit({      
                    error:function(){
                        // $('.form-footer').removeClass('progress');
                    },
                    success:function(data){
                        var res = jQuery.parseJSON(data);
                        if(res.status == 'success')
                        { 
                            var content = '<div class="alert alert-success">'+res.msg+'</div>';
                        }
                        else
                        {
                            var content = '<div class="alert alert-danger">'+res.msg+'</div>';
                        }
                        $("#importFrm").trigger("reset");
                        $('#import-result').html(content);
                        $('#import-result').delay(5000).fadeOut();
                    }
                });*/
            }
        });
    }

    if (jQuery('#importLenderFrm').length) {
        jQuery('#importLenderFrm').validate({
            rules: {
                file: "required",
                lenderType: "required",
            },
            messages: {
                file: "Please upload file to import",
                lenderType: "Please select lender type",
            },
            submitHandler: function (form) {
                form.submit();
                /*$(form).ajaxSubmit({      
                    error:function(){
                        // $('.form-footer').removeClass('progress');
                    },
                    success:function(data){
                        var res = jQuery.parseJSON(data);
                        if(res.status == 'success')
                        { 
                            var content = '<div class="alert alert-success">'+res.msg+'</div>';
                        }
                        else
                        {
                            var content = '<div class="alert alert-danger">'+res.msg+'</div>';
                        }
                        $("#importFrm").trigger("reset");
                        $('#import-result').html(content);
                        $('#import-result').delay(5000).fadeOut();
                    }
                });*/
            }
        });
    }

    if ($('#tbl-agents-listing').length) {
        agent_list = $('#tbl-agents-listing').DataTable({
            /*"pageLength": 2,*/
            "paging": true,
            "lengthMenu": [10, 20, 50, 100, 200, 500, 1000],
            /*"columnDefs": [
                { "searchable": false, "targets": [0,1] }
            ],*/
            "language": {
                searchPlaceholder: "#Name, Email, Address",
                paginate: {
                    next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                    previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                },
                "emptyTable": "Record(s) not found.",
            },
            initComplete: function () {
                var $buttons = jQuery('.dt-buttons').hide();
                jQuery('#export-agent-data').on('click', function () {
                    var export_type = jQuery(this).attr('data-export-type');
                    if (export_type) {
                        var btnClass = '.buttons-' + export_type;
                    }
                    if (btnClass) $buttons.find(btnClass).click();
                })
            },
            dom: 'Blfrtip',
            buttons: [
                {
                    extend: 'csvHtml5',
                    text: 'Export',
                    title: 'Agents',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4],
                        format: {
                            body: function (data, row, column, node) {
                                // Strip $ from salary column to make it numeric
                                return (column === 0 || column === 1 || column === 2 || column === 3 || column === 4 || column === 5 || column === 6) ?
                                    data.replace(/[$,]/g, '') :
                                    data;
                            }
                        }
                    }
                },
            ],
            "drawCallback": function () {
                $('.dataTables_paginate > .pagination li').addClass('page-item');
                $('.dataTables_paginate > .pagination a').addClass('page-link');
                $('.dataTables_paginate > .pagination li.previous a, .dataTables_paginate > .pagination li.next a').addClass('rounded');
            },
            "ordering": false,
            "serverSide": true,
            "ajax": {
                url: base_url + "admin/order/agent/get_agent_list", // json datasource
                type: "post", // method  , by default get
                beforeSend: function () {
                    $("#page-preloader").show();
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
                    $("#tbl-customers-listing tbody").append('<tr><td colspan="12" class="text-center">No records found</td></tr>');
                    $("#tbl-customers-listing_processing").css("display", "none");

                },
                complete: function () {
                    $("#page-preloader").hide();
                }
            }
        });
    }

    if (jQuery('#edit-agent').length) {
        jQuery('#edit-agent').validate({
            rules: {
                name: "required",
                email_address: {
                    required: true,
                    email: true,
                },
                telephone_no: "required",
                company: "required",
            },
            messages: {
                name: {
                    required: 'Enter your name'
                },
                email_address: {
                    required: 'Enter your email address',
                    email: 'Enter a valid email address'
                },
                telephone_no: {
                    required: 'Enter your telephone no'
                },
                company: {
                    required: 'Enter your company name'
                },
            },
            submitHandler: function (form) {
                form.submit();
            }
        });
    }

    /* Lender listing table */
    if ($('#tbl-lenders-listing').length) {
        customer_list = $('#tbl-lenders-listing').DataTable({
            /*"pageLength": 2,*/
            "paging": true,
            "lengthMenu": [10, 20, 50, 100, 200, 500, 1000],
            "columnDefs": [
                { "searchable": false, "targets": [0, 1] }
            ],
            "language": {
                searchPlaceholder: "#Name, Email, Company Name",
                paginate: {
                    next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                    previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                },
                "emptyTable": "Record(s) not found.",
            },
            initComplete: function () {
                var $buttons = jQuery('.dt-buttons').hide();
                jQuery('#export-csv').on('click', function () {
                    var export_type = jQuery(this).attr('data-export-type');
                    if (export_type) {
                        var btnClass = '.buttons-' + export_type;
                    }
                    if (btnClass) $buttons.find(btnClass).click();
                })
            },
            dom: 'Blfrtip',
            buttons: [
                {
                    extend: 'csvHtml5',
                    text: 'Export',
                    title: 'Lenders',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8],
                        format: {
                            body: function (data, row, column, node) {
                                // Strip $ from salary column to make it numeric
                                return (column === 0 || column === 1 || column === 2 || column === 3 || column === 4 || column === 5 || column === 6) ?
                                    data.replace(/[$,]/g, '') :
                                    data;
                            }
                        }
                    }
                },
            ],
            "drawCallback": function () {
                $('.dataTables_paginate > .pagination li').addClass('page-item');
                $('.dataTables_paginate > .pagination a').addClass('page-link');
                $('.dataTables_paginate > .pagination li.previous a, .dataTables_paginate > .pagination li.next a').addClass('rounded');
            },
            "ordering": false,
            "serverSide": true,
            "ajax": {
                url: base_url + "admin/order/home/get_lender_list", // json datasource
                type: "post", // method  , by default get
                beforeSend: function () {
                    $("#page-preloader").show();
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
                    $("#tbl-lenders-listing tbody").append('<tr><td colspan="12" class="text-center">No records found</td></tr>');
                    $("#tbl-lenders-listing_processing").css("display", "none");

                },
                complete: function () {
                    $("#page-preloader").hide();
                }
            }
        });
    }

    /* Mortgage Brokers listing table */
    if ($('#tbl-mortgage-listing').length) {
        mortgage_list = $('#tbl-mortgage-listing').DataTable({
            "paging": true,
            "lengthMenu": [10, 20, 50, 100, 200, 500, 1000],
            "columnDefs": [
                { "searchable": false, "targets": [0, 1] }
            ],
            "language": {
                searchPlaceholder: "#Name, Email, Company Name",
                paginate: {
                    next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                    previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                },
                "emptyTable": "Record(s) not found.",
            },
            initComplete: function () {
                var $buttons = jQuery('.dt-buttons').hide();
                jQuery('#export-csv').on('click', function () {
                    var export_type = jQuery(this).attr('data-export-type');
                    if (export_type) {
                        var btnClass = '.buttons-' + export_type;
                    }
                    if (btnClass) $buttons.find(btnClass).click();
                })
            },
            dom: 'Blfrtip',
            buttons: [
                {
                    extend: 'csvHtml5',
                    text: 'Export',
                    title: 'Lenders',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8],
                        format: {
                            body: function (data, row, column, node) {
                                return (column === 0 || column === 1 || column === 2 || column === 3 || column === 4 || column === 5 || column === 6) ?
                                    data.replace(/[$,]/g, '') :
                                    data;
                            }
                        }
                    }
                },
            ],
            "drawCallback": function () {
                $('.dataTables_paginate > .pagination li').addClass('page-item');
                $('.dataTables_paginate > .pagination a').addClass('page-link');
                $('.dataTables_paginate > .pagination li.previous a, .dataTables_paginate > .pagination li.next a').addClass('rounded');
            },
            "ordering": false,
            "serverSide": true,
            "ajax": {
                url: base_url + "admin/order/home/get_mortgage_brokers_list",
                type: "post",
                beforeSend: function () {
                    $("#page-preloader").show();
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
                    $("#tbl-mortgage-listing tbody").append('<tr><td colspan="12" class="text-center">No records found</td></tr>');
                    $("#tbl-mortgage-listing_processing").css("display", "none");

                },
                complete: function () {
                    $("#page-preloader").hide();
                }
            }
        });
    }

    /* Client users listing table */
    if ($('#tbl-client-user-listing').length) {
        client_users = $('#tbl-client-user-listing').DataTable({
            "paging": true,
            "lengthMenu": [10, 20, 50, 100, 200, 500, 1000],
            "columnDefs": [
                { "searchable": false, "targets": [0, 1] }
            ],
            "language": {
                searchPlaceholder: "#Name, Email, Company Name",
                paginate: {
                    next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                    previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                },
                "emptyTable": "Record(s) not found.",
            },
            initComplete: function () {
                console.log('hsfksdhf');
            },
            "dom": '<"row"<"col-sm-12"<"text-left"f>>><"row"<"col-sm-12"rt>><"row"<"col-sm-12"l><"col-sm-5"i><"col-sm-7"p>><"clear">',
            "drawCallback": function () {
                $('.dataTables_paginate > .pagination li').addClass('page-item');
                $('.dataTables_paginate > .pagination a').addClass('page-link');
                $('.dataTables_paginate > .pagination li.previous a, .dataTables_paginate > .pagination li.next a').addClass('rounded');
            },
            "ordering": false,
            "serverSide": true,
            "ajax": {
                url: base_url + "admin/order/home/get_active_client_users",
                type: "post",
                beforeSend: function () {
                    $("#page-preloader").show();
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
                    $("#tbl-client-user-listing tbody").append('<tr><td colspan="12" class="text-center">No records found</td></tr>');
                    $("#tbl-client-user-listing_processing").css("display", "none");

                },
                complete: function () {
                    $("#page-preloader").hide();
                }
            }
        });
    }

    if ($('#tbl-sales-rep-listing').length) {
        sales_rep_list = $('#tbl-sales-rep-listing').DataTable({
            "paging": true,
            "lengthMenu": [10, 20, 50, 100, 200, 500, 1000],
            "columnDefs": [
                { "searchable": false, "targets": [0, 1] }
            ],
            "language": {
                searchPlaceholder: "#Name, Email, Telephone",
                paginate: {
                    next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                    previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                },
                "emptyTable": "Record(s) not found.",
            },
            initComplete: function () {
                var $buttons = jQuery('.dt-buttons').hide();
                jQuery('#export-sales-rep-data').on('click', function () {
                    var export_type = jQuery(this).attr('data-export-type');
                    if (export_type) {
                        var btnClass = '.buttons-' + export_type;
                    }
                    if (btnClass) $buttons.find(btnClass).click();
                })
            },
            dom: 'Bl<"FilterOrderListing">frtip',
            buttons: [
                {
                    extend: 'csvHtml5',
                    text: 'Export',
                    title: 'Sales Rep.',
                    exportOptions: {
                        columns: [0, 1, 2],
                        format: {
                            body: function (data, row, column, node) {
                                return (column === 0 || column === 1 || column === 2) ?
                                    data.replace(/[$,]/g, '') :
                                    data;
                            }
                        }
                    }
                },
            ],
            "drawCallback": function () {
                $('.dataTables_paginate > .pagination li').addClass('page-item');
                $('.dataTables_paginate > .pagination a').addClass('page-link');
                $('.dataTables_paginate > .pagination li.previous a, .dataTables_paginate > .pagination li.next a').addClass('rounded');
            },
            "ordering": false,
            "serverSide": true,
            "ajax": {
                url: base_url + "order/admin/get-sales-rep-list",
                type: "post",
                beforeSend: function () {
                    $("#page-preloader").show();
                },
                data: function (d) {
                    d.sales_rep_enable = $('#enable_sales_rep').is(":checked") || $('#sales_rep_status_flag').val() == '1' ? 1 : 0;
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
                    $("#tbl-sales-rep-listing tbody").append('<tr><td colspan="4" class="text-center">No records found</td></tr>');
                    $("#tbl-sales-rep-listing_processing").css("display", "none");

                },
                complete: function () {
                    $("#page-preloader").hide();
                }
            }
        });
        var sales_rep_status_flag = $('#sales_rep_status_flag').val();
        var sales_rep_status_checked = '';
        if (sales_rep_status_flag == '1') {
            sales_rep_status_checked = 'checked';
        } else {
            sales_rep_status_checked = '';
        }
        $("div.FilterOrderListing").html('<label> Show Hidden: <input style="width:20px;height:20px;" ' + sales_rep_status_checked + ' type="checkbox" id="enable_sales_rep" name="enable_sales_rep"></label>');
    }

    $("#enable_sales_rep").on("change", function () {
        if ($('#enable_sales_rep').is(":checked")) {
            $('#sales_rep_status_flag').val('1');
        } else {
            $('#sales_rep_status_flag').val('0');
        }
        sales_rep_list.ajax.reload();
    });

    if ($('#tbl-title-officer-listing').length) {
        title_officer_list = $('#tbl-title-officer-listing').DataTable({
            "paging": true,
            "lengthMenu": [10, 20, 50, 100, 200, 500, 1000],
            "columnDefs": [
                { "searchable": false, "targets": [0, 1] }
            ],
            "language": {
                searchPlaceholder: "Search #",
                paginate: {
                    next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                    previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                },
                "emptyTable": "Record(s) not found.",
            },
            initComplete: function () {
                var $buttons = jQuery('.dt-buttons').hide();
                jQuery('#export-title-officer-data').on('click', function () {
                    var export_type = jQuery(this).attr('data-export-type');
                    if (export_type) {
                        var btnClass = '.buttons-' + export_type;
                    }
                    if (btnClass) $buttons.find(btnClass).click();
                })
            },
            dom: 'Blfrtip',
            buttons: [
                {
                    extend: 'csvHtml5',
                    text: 'Export',
                    title: 'Title Officers',
                    exportOptions: {
                        columns: [0, 1, 2],
                        format: {
                            body: function (data, row, column, node) {
                                return (column === 0 || column === 1 || column === 2) ?
                                    data.replace(/[$,]/g, '') :
                                    data;
                            }
                        }
                    }
                },
            ],
            "drawCallback": function () {
                $('.dataTables_paginate > .pagination li').addClass('page-item');
                $('.dataTables_paginate > .pagination a').addClass('page-link');
                $('.dataTables_paginate > .pagination li.previous a, .dataTables_paginate > .pagination li.next a').addClass('rounded');
            },
            "ordering": false,
            "serverSide": true,
            "ajax": {
                url: base_url + "order/admin/get-title-officer-list",
                type: "post",
                beforeSend: function () {
                    $("#page-preloader").show();
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
                    $("#tbl-title-officer-listing tbody").append('<tr><td colspan="4" class="text-center">No records found</td></tr>');
                    $("#tbl-title-officer-listing_processing").css("display", "none");

                },
                complete: function () {
                    $("#page-preloader").hide();
                }
            }
        });
    }

    if ($('#tbl-credentials-customers-listing').length) {
        credentials_customer_list = $('#tbl-credentials-customers-listing').DataTable({
            /*"pageLength": 2,*/
            "paging": true,
            "lengthMenu": [10, 20, 50, 100, 200, 500, 1000],
            "columnDefs": [
                { "searchable": false, "targets": [0, 1, 2] }
            ],
            "language": {
                // searchPlaceholder: "Customer Number",
                paginate: {
                    next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                    previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                },
                "emptyTable": "Record(s) not found.",
            },
            initComplete: function () {
                var $buttons = jQuery('.dt-buttons').hide();
                jQuery('#export_customer').on('click', function () {
                    var export_type = jQuery(this).attr('data-export-type');
                    if (export_type) {
                        var btnClass = '.buttons-' + export_type;
                    }
                    if (btnClass) $buttons.find(btnClass).click();
                })
            },
            dom: 'lf<"FilterCredentialListing">rtip',
            buttons: [
                {
                    extend: 'csvHtml5',
                    text: 'Export',
                    title: 'Customers',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9],
                        format: {
                            body: function (data, row, column, node) {
                                return (column === 0 || column === 1 || column === 2 || column === 3 || column === 4 || column === 5 || column === 6 || column === 7 || column === 8 || column === 9) ?
                                    data.replace(/[$,]/g, '') :
                                    data;
                            }
                        }
                    }
                },
            ],
            "drawCallback": function () {
                $('.dataTables_paginate > .pagination li').addClass('page-item');
                $('.dataTables_paginate > .pagination a').addClass('page-link');
                $('.dataTables_paginate > .pagination li.previous a, .dataTables_paginate > .pagination li.next a').addClass('rounded');
            },
            "ordering": false,
            "serverSide": true,
            "ajax": {
                url: base_url + "admin/order/customer/get_customer_list", // json datasource
                type: "post",
                data: function (d) {
                    d.credentials_check = $('#FilterCredentialListing').val();
                }, // method  , by default get
                beforeSend: function () {
                    $("#page-preloader").show();
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
                    $("#tbl-credentials-customers-listing tbody").append('<tr><td colspan="12" class="text-center">No records found</td></tr>');
                    $("#tbl-credentials-customers-listing_processing").css("display", "none");

                },
                complete: function () {
                    $("#page-preloader").hide();
                }
            },
            "createdRow": function (row, data, index) {

                if (data[7] == 'Correct') {
                    $(row).addClass('alert alert-success');
                }
                else {
                    $(row).addClass('alert alert-danger');
                }
            }
        });

        $("div.FilterCredentialListing").html('<label style="margin-bottom:10px;"> Credentials Check: <select style="width:auto;" class="custom-select custom-select-sm form-control form-control-sm" name="FilterCredentialListing" id="FilterCredentialListing"> <option value="" > All </option><option value="1" > Correct </option><option value="0" > Incorrect </option><option value="2" > Duplicate Email </option></select></label>');
    }

    $("#FilterCredentialListing").on("change", function () {
        credentials_customer_list.ajax.reload();
    });

    if ($('#tbl-lv-log-listing').length) {
        log_list = $('#tbl-lv-log-listing').DataTable({
            "paging": true,
            "lengthMenu": [10, 20, 50, 100, 200, 500, 1000],
            "columnDefs": [
                { "searchable": false, "targets": [0, 1] }
            ],
            "language": {
                searchPlaceholder: "#Order No, Address",
                paginate: {
                    next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                    previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                },
                "emptyTable": "Record(s) not found.",
            },
            "dom": '<"row"<"col-sm-12"<"text-left"f>>><"row"<"col-sm-12"rt>><"row"<"col-sm-12"l><"col-sm-5"i><"col-sm-7"p>><"clear">',
            initComplete: function () {
                var input = $('.dataTables_filter input').unbind(),
                    self = this.api(),
                    $lvLogDropDown = $('<span style="margin-left:20px;">Message: </span><select style="width:auto;" id="lvLog" class="custom-select custom-select-sm form-control form-control-sm"><option value="">All</option><option value="success">Success</option><option value="error">Error</option></select>'),
                    $lvLogDateRange = $('<span style="margin-left:20px;" class="date-range-span">Created Date: </span><div id="lvDateRangeControl" class="date-range-control"><i class="fa fa-calendar"></i>&nbsp;<span></span> <i class="fa fa-caret-down float-right"></i><input type="hidden" id="lvDateRange" /></div>'),
                    $searchButton = $('<button style="margin-left:20px;" class="btn btn-success btn-icon-split float-right mr-2"><span class="icon text-white-50"><i class="fa fa-search"></i></span><span class="text">Search</span></button>')
                        // .text('')
                        .click(function () {
                            self.search(input.val(), $('#customerDateRange').val()).draw();
                        }),
                    $clearButton = $('<button style="margin-left:20px;" class="btn btn-secondary btn-icon-split float-right mr-2"><span class="icon text-white-50"><i class="fa fa-eraser"></i></span><span class="text">Clear</span></button>')
                        // .text('Clear')
                        .click(function () {
                            input.val('');
                            $("#lvLog").val('');
                            $('#lvDateRangeControl span').html('');
                            $('#lvDateRange').val('');
                            $searchButton.click();
                        })

                $('.dataTables_filter').append($lvLogDropDown, $lvLogDateRange, $clearButton, $searchButton);

            },
            "drawCallback": function () {
                $('.dataTables_paginate > .pagination li').addClass('page-item');
                $('.dataTables_paginate > .pagination a').addClass('page-link');
                $('.dataTables_paginate > .pagination li.previous a, .dataTables_paginate > .pagination li.next a').addClass('rounded');
            },
            "ordering": false,
            "serverSide": true,
            "ajax": {
                url: base_url + "admin/order/titlePoint/get_logs", // json datasource
                type: "post", // method  , by default get
                "data": function (d) {
                    d.dateRange = $('#lvDateRange').val();
                    d.lvLog = $('#lvLog').val();
                },
                beforeSend: function () {
                    $("#page-preloader").show();
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
                    $("#tbl-lv-log-listing tbody").append('<tr><td colspan="12" class="text-center">No records found</td></tr>');
                    $("#tbl-lv-log-listing_processing").css("display", "none");

                },
                complete: function () {
                    $("#page-preloader").hide();

                    setTimeout(function () {
                        var start = moment().startOf('month')
                        var end = moment();
                        function cb(start, end) {
                            $('#lvDateRangeControl span').html(start.format('MM/DD/YYYY') + ' - ' + end.format('MM/DD/YYYY'));
                            $('#lvDateRange').val(start.format('MM/DD/YYYY') + ' - ' + end.format('MM/DD/YYYY'));
                        }

                        var dateRange = $('#lvDateRangeControl').daterangepicker({
                            autoUpdateInput: false,
                            locale: {
                                cancelLabel: 'Clear'
                            },
                            opens: 'right',
                            startDate: start,
                            endDate: end,
                            ranges: {
                                'Today': [moment(), moment()],
                                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                                'This Month': [moment().startOf('month'), moment().endOf('month')],
                                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                            }
                        }, cb);

                        dateRange.on('apply.daterangepicker', function (ev, picker) {
                            $('#lvDateRange').val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
                            $('#lvDateRangeControl span').html(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
                        });

                        dateRange.on('cancel.daterangepicker', function (ev, picker) {
                            $('#lvDateRangeControl span').html('');
                            $('#lvDateRange').val('');
                        });

                        //cb(start, end);

                    }, 100);
                }
            },

        });


    }

    if ($('#tbl-pre-listing-log-listing').length) {
        log_list = $('#tbl-pre-listing-log-listing').DataTable({
            "paging": true,
            "lengthMenu": [10, 20, 50, 100, 200, 500, 1000],
            "columnDefs": [
                { "searchable": false, "targets": [0, 1] }
            ],
            "language": {
                searchPlaceholder: "#Order No, Address",
                paginate: {
                    next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                    previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                },
                "emptyTable": "Record(s) not found.",
            },
            "dom": '<"row"<"col-sm-12"<"text-left"f>>><"row"<"col-sm-12"rt>><"row"<"col-sm-12"l><"col-sm-5"i><"col-sm-7"p>><"clear">',
            initComplete: function () {
                var input = $('.dataTables_filter input').unbind(),
                    self = this.api(),
                    $preListingLogDropDown = $('<span style="margin-left:20px;">Message: </span><select style="width:auto;" id="preListingLog" class="custom-select custom-select-sm form-control form-control-sm"><option value="">All</option><option value="success">Success</option><option value="error">Error</option></select>'),
                    $preListingLogDateRange = $('<span style="margin-left:20px;" class="date-range-span">Created Date: </span><div id="preListingDateRangeControl" class="date-range-control"><i class="fa fa-calendar"></i>&nbsp;<span></span> <i class="fa fa-caret-down float-right"></i><input type="hidden" id="preListingDateRange" /></div>'),
                    $searchButton = $('<button style="margin-left:20px;" class="btn btn-success btn-icon-split float-right mr-2"><span class="icon text-white-50"><i class="fa fa-search"></i></span><span class="text">Search</span></button>')
                        // .text('Search')
                        .click(function () {
                            self.search(input.val(), $('#customerDateRange').val()).draw();
                        }),
                    // $clearButton = $('<button style="margin-left:20px;" class="btn btn-secondary">')
                    // .text('Clear')
                    $clearButton = $('<button style="margin-left:20px;" class="btn btn-secondary btn-icon-split float-right mr-2"><span class="icon text-white-50"><i class="fa fa-eraser"></i></span><span class="text">Clear</span></button>')
                        .click(function () {
                            input.val('');
                            $("#preListingLog").val('');
                            $('#preListingDateRangeControl span').html('');
                            $('#preListingDateRange').val('');
                            $searchButton.click();
                        })

                $('.dataTables_filter').append($preListingLogDropDown, $preListingLogDateRange, $clearButton, $searchButton);

            },
            "drawCallback": function () {
                $('.dataTables_paginate > .pagination li').addClass('page-item');
                $('.dataTables_paginate > .pagination a').addClass('page-link');
                $('.dataTables_paginate > .pagination li.previous a, .dataTables_paginate > .pagination li.next a').addClass('rounded');
            },
            "ordering": false,
            "serverSide": true,
            "ajax": {
                url: base_url + "admin/order/titlePoint/get_pre_listing_logs", // json datasource
                type: "post", // method  , by default get
                beforeSend: function () {
                    $("#page-preloader").show();
                },
                "data": function (d) {
                    d.dateRange = $('#preListingDateRange').val();
                    d.preListingLog = $('#preListingLog').val();
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
                    $("#tbl-pre-listing-log-listing tbody").append('<tr><td colspan="12" class="text-center">No records found</td></tr>');
                    $("#tbl-pre-listing-log-listing_processing").css("display", "none");

                },
                complete: function () {
                    $("#page-preloader").hide();

                    setTimeout(function () {
                        var start = moment().startOf('month')
                        var end = moment();

                        function cb(start, end) {
                            $('#preListingDateRangeControl span').html(start.format('MM/DD/YYYY') + ' - ' + end.format('MM/DD/YYYY'));
                            $('#preListingDateRange').val(start.format('MM/DD/YYYY') + ' - ' + end.format('MM/DD/YYYY'));
                        }

                        var dateRange = $('#preListingDateRangeControl').daterangepicker({
                            autoUpdateInput: false,
                            locale: {
                                cancelLabel: 'Clear'
                            },
                            opens: 'right',
                            startDate: start,
                            endDate: end,
                            ranges: {
                                'Today': [moment(), moment()],
                                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                                'This Month': [moment().startOf('month'), moment().endOf('month')],
                                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                            }
                        }, cb);

                        dateRange.on('apply.daterangepicker', function (ev, picker) {
                            $('#preListingDateRange').val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
                            $('#preListingDateRangeControl span').html(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
                        });

                        dateRange.on('cancel.daterangepicker', function (ev, picker) {
                            $('#preListingDateRangeControl span').html('');
                            $('#preListingDateRange').val('');
                        });

                        //cb(start, end);

                    }, 100);
                }
            },

        });

    }

    if ($('#tbl-ion-fraud-log-listing').length) {
        log_list = $('#tbl-ion-fraud-log-listing').DataTable({
            "paging": true,
            "lengthMenu": [10, 20, 50, 100, 200, 500, 1000],
            "columnDefs": [
                { "searchable": false, "targets": [0, 1] }
            ],
            "language": {
                searchPlaceholder: "#Order No",
                paginate: {
                    next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                    previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                },
                "emptyTable": "Record(s) not found.",
            },
            "dom": '<"row"<"col-sm-12"<"text-left"f>>><"row"<"col-sm-12"rt>><"row"<"col-sm-12"l><"col-sm-5"i><"col-sm-7"p>><"clear">',
            initComplete: function () {
                var input = $('.dataTables_filter input').unbind(),
                    self = this.api(),
                    $ionFraudStatusDropDown = $('<span style="margin-left:20px;">ION Fraud Status: </span><select style="width:auto;" id="ionFraudStatusDropDown" class="custom-select custom-select-sm form-control form-control-sm"><option value="">All</option><option value="yes">Yes</option><option value="no">No</option></select>'),
                    $userProceedStatusDropDown = $('<span style="margin-left:20px;">User Proceed Status: </span><select style="width:auto;" id="userProceedStatusDropDown" class="custom-select custom-select-sm form-control form-control-sm"><option value="">All</option><option value="proceed">Proceed</option><option value="review fraud">Review Fraud</option></select>'),

                    $searchButton = $('<button style="margin-left:20px;" class="btn btn-success btn-icon-split float-right mr-2"><span class="icon text-white-50"><i class="fa fa-search"></i></span><span class="text">Search</span></button>')
                        // .text('Search')
                        .click(function () {
                            self.search(input.val(), $('#customerDateRange').val()).draw();
                        }),
                    // $clearButton = $('<button style="margin-left:20px;" class="btn btn-secondary">')
                    // .text('Clear')
                    $clearButton = $('<button style="margin-left:20px;" class="btn btn-secondary btn-icon-split float-right mr-2"><span class="icon text-white-50"><i class="fa fa-eraser"></i></span><span class="text">Clear</span></button>')
                        .click(function () {
                            input.val('');
                            $("#ionFraudStatusDropDown").val('');
                            $("#userProceedStatusDropDown").val('');
                            $searchButton.click();
                        })

                $('.dataTables_filter').append($ionFraudStatusDropDown, $userProceedStatusDropDown, $clearButton, $searchButton);

            },
            "drawCallback": function () {
                $('.dataTables_paginate > .pagination li').addClass('page-item');
                $('.dataTables_paginate > .pagination a').addClass('page-link');
                $('.dataTables_paginate > .pagination li.previous a, .dataTables_paginate > .pagination li.next a').addClass('rounded');
            },
            "ordering": false,
            "serverSide": true,
            "ajax": {
                url: base_url + "admin/order/home/get_ion_fraud_listing_logs", // json datasource
                type: "post", // method  , by default get
                beforeSend: function () {
                    $("#page-preloader").show();
                },
                "data": function (d) {
                    d.ionFraudStatus = $('#ionFraudStatusDropDown').val();
                    d.ionFraudProceedStatus = $('#userProceedStatusDropDown').val();
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
                    $("#tbl-ion-fraud-log-listing tbody").append('<tr><td colspan="12" class="text-center">No records found</td></tr>');
                    $("#tbl-ion-fraud-log-listing_processing").css("display", "none");

                },
                complete: function () {
                    $("#page-preloader").hide();
                }
            },

        });

    }

    if ($('#refresh-data').length) {
        $('#refresh-data').click(function (e) {
            $('body').animate({ opacity: 0.5 }, "slow");
            $.ajax({
                url: base_url + "/check-update-password",
                method: "POST",
                data: {
                    new_users: 0
                },
                success: function (data) {
                    var result = jQuery.parseJSON(data);
                    if (result.status == 'success') {
                        $('body').animate({ opacity: 1.0 }, "slow");
                        $('#customer_success_msg').html(result.msg).show();
                        $([document.documentElement, document.body]).animate({
                            scrollTop: $("#customer_success_msg").offset().top
                        }, 1000);
                        credentials_customer_list.ajax.reload(null, false);
                        setTimeout(function () {
                            $('#customer_success_msg').html('').hide();
                        }, 4000);
                    } else {
                        $('#customer_error_msg').html(result.message).show();
                        $([document.documentElement, document.body]).animate({
                            scrollTop: $("#customer_error_msg").offset().top
                        }, 1000);

                        setTimeout(function () {
                            $('#customer_error_msg').html('').hide();
                        }, 4000);
                    }
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    $('#customer_error_msg').html('Something went wrong. Please try it again.').show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#customer_success_msg").offset().top
                    }, 1000);

                    setTimeout(function () {
                        $('#customer_error_msg').html('').hide();
                    }, 4000);
                }
            })
        });
    }

    if ($('#refresh-new-users-data').length) {
        $('#refresh-new-users-data').click(function (e) {
            $('body').animate({ opacity: 0.5 }, "slow");
            $.ajax({
                url: base_url + "/check-update-password",
                method: "POST",
                data: {
                    new_users: 1
                },
                success: function (data) {
                    var result = jQuery.parseJSON(data);
                    if (result.status == 'success') {
                        $('body').animate({ opacity: 1.0 }, "slow");
                        $('#customer_success_msg').html(result.msg).show();
                        $([document.documentElement, document.body]).animate({
                            scrollTop: $("#customer_success_msg").offset().top
                        }, 1000);
                        credentials_customer_list.ajax.reload(null, false);
                        setTimeout(function () {
                            $('#customer_success_msg').html('').hide();
                        }, 4000);
                    } else {
                        $('#customer_error_msg').html(result.message).show();
                        $([document.documentElement, document.body]).animate({
                            scrollTop: $("#customer_error_msg").offset().top
                        }, 1000);

                        setTimeout(function () {
                            $('#customer_error_msg').html('').hide();
                        }, 4000);
                    }
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    $('#customer_error_msg').html('Something went wrong. Please try it again.').show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#customer_success_msg").offset().top
                    }, 1000);

                    setTimeout(function () {
                        $('#customer_error_msg').html('').hide();
                    }, 4000);
                }
            })
        });
    }

    if ($('#refresh-company-data').length) {
        $('#refresh-company-data').click(function (e) {
            $('body').animate({ opacity: 0.5 }, "slow");
            $.ajax({
                url: base_url + "/company-information",
                method: "POST",
                success: function (data) {
                    var result = jQuery.parseJSON(data);
                    if (result.status == 'success') {
                        $('body').animate({ opacity: 1.0 }, "slow");
                        $('#companies_success_msg').html(result.msg).show();
                        $([document.documentElement, document.body]).animate({
                            scrollTop: $("#companies_success_msg").offset().top
                        }, 1000);
                        companies_list.ajax.reload(null, false);
                        setTimeout(function () {
                            $('#companies_success_msg').html('').hide();
                        }, 4000);
                    } else {
                        $('#companies_error_msg').html(result.message).show();
                        $([document.documentElement, document.body]).animate({
                            scrollTop: $("#companies_error_msg").offset().top
                        }, 1000);

                        setTimeout(function () {
                            $('#companies_error_msg').html('').hide();
                        }, 4000);
                    }
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    $('#companies_error_msg').html('Something went wrong. Please try it again.').show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#companies_success_msg").offset().top
                    }, 1000);

                    setTimeout(function () {
                        $('#companies_error_msg').html('').hide();
                    }, 4000);
                }
            })
        });
    }

    if ($('#refresh-safewire-data').length) {
        $('#refresh-safewire-data').click(function (e) {
            $('body').animate({ opacity: 0.5 }, "slow");
            $.ajax({
                url: base_url + "update-safewire-orders-status",
                method: "POST",
                success: function (data) {
                    var result = jQuery.parseJSON(data);
                    if (result.success) {
                        $('body').animate({ opacity: 1.0 }, "slow");
                        $('#safewire_success_msg').html(result.message).show();
                        $([document.documentElement, document.body]).animate({
                            scrollTop: $("#safewire_success_msg").offset().top
                        }, 1000);
                        safewire_orders_list.ajax.reload(null, false);
                        setTimeout(function () {
                            $('#safewire_success_msg').html('').hide();
                        }, 4000);
                    } else {
                        $('#safewire_error_msg').html(result.message).show();
                        $([document.documentElement, document.body]).animate({
                            scrollTop: $("#safewire_error_msg").offset().top
                        }, 1000);

                        setTimeout(function () {
                            $('#safewire_error_msg').html('').hide();
                        }, 4000);
                    }
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    $('#safewire_error_msg').html('Something went wrong. Please try it again.').show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#safewire_success_msg").offset().top
                    }, 1000);

                    setTimeout(function () {
                        $('#safewire_error_msg').html('').hide();
                    }, 4000);
                }
            })
        });
    }

    if ($('#tbl-orders-listing').length) {
        order_list = $('#tbl-orders-listing').DataTable({
            /*"pageLength": 2,*/
            "paging": true,
            "lengthMenu": [10, 20, 50, 100, 200, 500, 1000],
            "lengthChange": true,
            /*"columnDefs": [
                { "searchable": false, "targets": [0,1] }
            ],*/
            "language": {
                searchPlaceholder: "#Order No, Address",
                paginate: {
                    next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                    previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                },
                "emptyTable": "Record(s) not found.",
            },
            initComplete: function () {
                $("#page-preloader").show();
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
                url: base_url + "admin/order/order/get_order_list", // json datasource
                type: "post", // method  , by default get
                beforeSend: function () {
                    $("#page-preloader").show();
                    // $('#page-preloader').css('background-color', 'rgba(0,0,0,.5)');
                    // $('#page-preloader').css('display', 'block');
                },
                data: function (d) {
                    d.sales_rep = $('#FilterOrderListing').val();
                    d.created_by = $('#FilterCreatedBy').val();
                    d.product_type = product_type;
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
                    $("#tbl-orders-listing tbody").append('<tr><td colspan="12" class="text-center">No records found</td></tr>');
                    $("#tbl-orders-listing_processing").css("display", "none");

                },
                complete: function () {
                    $("#page-preloader").hide();
                    // $('#page-preloader').css('display', 'none');
                }
            }
        });

        if (sales_rep) {
            var obj = jQuery.parseJSON(sales_rep);
            var options = '';
            $.each(obj, function (key, value) {
                options += '<option value="' + value.id + '">' + value.first_name + ' ' + value.last_name + '</option>'
            });
            $("div.FilterOrderListing").html('<label> Sales Rep: <select style="width:auto;" name="FilterOrderListing" id="FilterOrderListing" class="custom-select custom-select-sm form-control form-control-sm"> <option value="" > All </option>"' + options + '"</select></label>');
        }

        if (master_users) {
            var obj = jQuery.parseJSON(master_users);
            var options = '';
            $.each(obj, function (key, value) {
                options += '<option value="' + value.id + '">' + value.first_name + ' ' + value.last_name + '</option>'
            });

            $("div.FilterOrderListing").append('<div class="col-sm-3" style="display:inline;padding-right: 0;text-align: right;"><label> Created By: <select name="FilterCreatedBy" id="FilterCreatedBy" class="custom-select custom-select-sm form-control form-control-sm" style="width:auto;"> <option value="" > All </option>"' + options + '"</select></label></div>');
        }

        if (product_type) {
            order_list.ajax.reload();
        }

    }
    $("#FilterOrderListing").on("change", function () {
        order_list.ajax.reload();
    });

    $("#FilterCreatedBy").on("change", function () {
        order_list.ajax.reload();
    });

    if ($('#tbl-lp-orders-listing').length) {
        lp_order_list = $('#tbl-lp-orders-listing').DataTable({
            /*"pageLength": 2,*/
            "paging": true,
            "lengthMenu": [10, 20, 50, 100, 200, 500, 1000],
            "lengthChange": true,
            /*"columnDefs": [
                { "searchable": false, "targets": [0,1] }
            ],*/
            "language": {
                searchPlaceholder: "#Order No, Property Address",
                paginate: {
                    next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                    previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                },
                "emptyTable": "Record(s) not found.",
            },
            initComplete: function () {
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
                url: base_url + "admin/order/order/get_lp_order_list", // json datasource
                type: "post", // method  , by default get
                beforeSend: function () {
                    $("#page-preloader").show();
                    // $('#page-preloader').css('background-color', 'rgba(0,0,0,.5)');
                    // $('#page-preloader').css('display', 'block');
                },
                data: function (d) {
                    d.sales_rep = $('#FilterLpOrderListing').val();
                    d.start_date = $('#FilterLpStartDate').val();
                    d.end_date = $('#FilterLpEndDate').val();
                    //   d.product_type= lp_product_type;
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
                    $("#tbl-lp-orders-listing tbody").append('<tr><td colspan="12" class="text-center">No records found</td></tr>');
                    $("#tbl-lp-orders-listing_processing").css("display", "none");

                },
                complete: function () {
                    $("#page-preloader").hide();
                    // $('#page-preloader').css('display', 'none');
                }
            }
        });

        if (lp_sales_rep) {
            var obj = jQuery.parseJSON(lp_sales_rep);
            var options = '';
            $.each(obj, function (key, value) {
                options += '<option value="' + value.id + '">' + value.first_name + ' ' + value.last_name + '</option>'
            });
            $("div.FilterOrderListing").html('<div class="col-sm-6" style="padding-left: 0;"><label> Sales Rep: <select style="width:auto;" name="FilterLpOrderListing" id="FilterLpOrderListing" class="custom-select custom-select-sm form-control form-control-sm"> <option value="" > All </option>"' + options + '"</select></label></div>');
        }

        // if (lp_master_users) {
        // var obj = jQuery.parseJSON(lp_master_users);
        // var options='';
        // $.each( obj, function( key, value ) {
        //   options += '<option value="'+value.id+'">'+value.first_name+' '+value.last_name+'</option>'
        // });

        $("div.FilterOrderListing").append('<div class="col-sm-3" style="display:inline"><label> Start Date: <input type="date" name="FilterLpStartDate" id="FilterLpStartDate" class="custom-select form-control form-control-sm" style="width:auto;"></label></div><div class="col-sm-3" style="display:inline; text-align: right;padding-right: 0;"><label> End Date: <input type="date" name="FilterLpEndDate" id="FilterLpEndDate" class="custom-select form-control form-control-sm" style="width:auto;"></label></div>');
        // }

        if (lp_product_type) {
            lp_order_list.ajax.reload();
        }

    }
    $("#FilterLpOrderListing").on("change", function () {
        lp_order_list.ajax.reload();
    });

    $("#FilterLpEndDate, #FilterLpStartDate").on("change", function () {
        if ($('#FilterLpEndDate').val() != '' && $('#FilterLpStartDate').val() != '') {
            lp_order_list.ajax.reload();
        }
    });

    if ($('#tbl-cpl-documents-listing').length) {
        cpl_document_list = $('#tbl-cpl-documents-listing').DataTable({
            "paging": true,
            "lengthMenu": [10, 20, 50, 100, 200, 500, 1000],
            "columnDefs": [
                { "searchable": false, "targets": [0, 1] }
            ],
            "language": {
                searchPlaceholder: "#FileNumber, DocumentName",
                paginate: {
                    next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                    previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                },
                "emptyTable": "Record(s) not found.",
            },
            initComplete: function () {
                var $buttons = jQuery('.dt-buttons').hide();
                jQuery('#export_cpl_documents').on('click', function () {
                    var export_type = jQuery(this).attr('data-export-type');
                    if (export_type) {
                        var btnClass = '.buttons-' + export_type;
                    }
                    if (btnClass) $buttons.find(btnClass).click();
                })
            },
            dom: 'Blfrtip',
            buttons: [
                {
                    extend: 'csvHtml5',
                    text: 'Export',
                    title: 'CPL Documents',
                    exportOptions: {
                        columns: [0, 1, 2],
                        format: {
                            body: function (data, row, column, node) {
                                return (column === 0 || column === 1 || column === 2) ?
                                    data.replace(/[$,]/g, '') :
                                    data;
                            }
                        }
                    }
                },
            ],
            "drawCallback": function () {
                $('.dataTables_paginate > .pagination li').addClass('page-item');
                $('.dataTables_paginate > .pagination a').addClass('page-link');
                $('.dataTables_paginate > .pagination li.previous a, .dataTables_paginate > .pagination li.next a').addClass('rounded');
            },
            "ordering": false,
            "serverSide": true,
            "ajax": {
                url: base_url + "admin/order/home/get_cpl_document_list",
                type: "post",
                beforeSend: function () {
                    $("#page-preloader").show();
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
                    $("#tbl-cpl-documents-listing tbody").append('<tr><td colspan="4" class="text-center">No records found</td></tr>');
                    $("#tbl-cpl-documents-listing_processing").css("display", "none");

                },
                complete: function () {
                    $("#page-preloader").hide();
                }
            }
        });
    }

    if ($('#tbl-ion-fraud-documents-listing').length) {
        ion_fraud_document_list = $('#tbl-ion-fraud-documents-listing').DataTable({
            "paging": true,
            "lengthMenu": [10, 20, 50, 100, 200, 500, 1000],
            "columnDefs": [
                { "searchable": false, "targets": [0, 1] }
            ],
            "language": {
                searchPlaceholder: "#FileNumber, DocumentName",
                paginate: {
                    next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                    previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                },
                "emptyTable": "Record(s) not found.",
            },
            initComplete: function () {
                var $buttons = jQuery('.dt-buttons').hide();
                jQuery('#export_ion_fraud_documents').on('click', function () {
                    var export_type = jQuery(this).attr('data-export-type');
                    if (export_type) {
                        var btnClass = '.buttons-' + export_type;
                    }
                    if (btnClass) $buttons.find(btnClass).click();
                })
            },
            dom: 'Blfrtip',
            buttons: [
                {
                    extend: 'csvHtml5',
                    text: 'Export',
                    title: 'ION Fraud Documents',
                    exportOptions: {
                        columns: [0, 1, 2],
                        format: {
                            body: function (data, row, column, node) {
                                return (column === 0 || column === 1 || column === 2) ?
                                    data.replace(/[$,]/g, '') :
                                    data;
                            }
                        }
                    }
                },
            ],
            "drawCallback": function () {
                $('.dataTables_paginate > .pagination li').addClass('page-item');
                $('.dataTables_paginate > .pagination a').addClass('page-link');
                $('.dataTables_paginate > .pagination li.previous a, .dataTables_paginate > .pagination li.next a').addClass('rounded');
            },
            "ordering": false,
            "serverSide": true,
            "ajax": {
                url: base_url + "admin/order/home/get_ion_fraud_document_list",
                type: "post",
                beforeSend: function () {
                    $("#page-preloader").show();
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
                    $("#tbl-ion-fraud-documents-listing tbody").append('<tr><td colspan="4" class="text-center">No records found</td></tr>');
                    $("#tbl-ion-fraud-documents-listing_processing").css("display", "none");

                },
                complete: function () {
                    $("#page-preloader").hide();
                }
            }
        });
    }

    if ($('#tbl-grant-documents-listing').length) {
        cpl_document_list = $('#tbl-grant-documents-listing').DataTable({
            "paging": true,
            "lengthMenu": [10, 20, 50, 100, 200, 500, 1000],
            "columnDefs": [
                { "searchable": false, "targets": [0, 1] }
            ],
            "language": {
                searchPlaceholder: "#FileNumber, DocumentName",
                paginate: {
                    next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                    previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                },
                "emptyTable": "Record(s) not found.",
            },
            initComplete: function () {
                var $buttons = jQuery('.dt-buttons').hide();
                jQuery('#export_grant_documents').on('click', function () {
                    var export_type = jQuery(this).attr('data-export-type');
                    if (export_type) {
                        var btnClass = '.buttons-' + export_type;
                    }
                    if (btnClass) $buttons.find(btnClass).click();
                })
            },
            dom: 'Blfrtip',
            buttons: [
                {
                    extend: 'csvHtml5',
                    text: 'Export',
                    title: 'Grant Deed Documents',
                    exportOptions: {
                        columns: [0, 1, 2],
                        format: {
                            body: function (data, row, column, node) {
                                return (column === 1 || column === 2) ?
                                    data.replace(/[$,]/g, '') :
                                    data;
                            }
                        }
                    }
                },
            ],
            "drawCallback": function () {
                $('.dataTables_paginate > .pagination li').addClass('page-item');
                $('.dataTables_paginate > .pagination a').addClass('page-link');
                $('.dataTables_paginate > .pagination li.previous a, .dataTables_paginate > .pagination li.next a').addClass('rounded');
            },
            "ordering": false,
            "serverSide": true,
            "ajax": {
                url: base_url + "admin/order/home/get_grant_deed_document_list",
                type: "post",
                beforeSend: function () {
                    $("#page-preloader").show();
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
                    $("#tbl-grant-documents-listing tbody").append('<tr><td colspan="4" class="text-center">No records found</td></tr>');
                    $("#tbl-grant-documents-listing_processing").css("display", "none");

                },
                complete: function () {
                    $("#page-preloader").hide();
                }
            }
        });
    }

    if ($('#tbl-lv-documents-listing').length) {
        cpl_document_list = $('#tbl-lv-documents-listing').DataTable({
            "paging": true,
            "lengthMenu": [10, 20, 50, 100, 200, 500, 1000],
            "columnDefs": [
                { "searchable": false, "targets": [0, 1] }
            ],
            "language": {
                searchPlaceholder: "#FileName, DocumentName",
                paginate: {
                    next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                    previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                },
                "emptyTable": "Record(s) not found.",
            },
            initComplete: function () {
                var $buttons = jQuery('.dt-buttons').hide();
                jQuery('#export_lv_documents').on('click', function () {
                    var export_type = jQuery(this).attr('data-export-type');
                    if (export_type) {
                        var btnClass = '.buttons-' + export_type;
                    }
                    if (btnClass) $buttons.find(btnClass).click();
                })
            },
            dom: 'Blfrtip',
            buttons: [
                {
                    extend: 'csvHtml5',
                    text: 'Export',
                    title: 'Legal & Vesting Documents',
                    exportOptions: {
                        columns: [0, 1, 2],
                        format: {
                            body: function (data, row, column, node) {
                                return (column === 1 || column === 2) ?
                                    data.replace(/[$,]/g, '') :
                                    data;
                            }
                        }
                    }
                },
            ],
            "drawCallback": function () {
                $('.dataTables_paginate > .pagination li').addClass('page-item');
                $('.dataTables_paginate > .pagination a').addClass('page-link');
                $('.dataTables_paginate > .pagination li.previous a, .dataTables_paginate > .pagination li.next a').addClass('rounded');
            },
            "ordering": false,
            "serverSide": true,
            "ajax": {
                url: base_url + "admin/order/home/get_lv_document_list",
                type: "post",
                beforeSend: function () {
                    $("#page-preloader").show();
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
                    $("#tbl-lv-documents-listing tbody").append('<tr><td colspan="4" class="text-center">No records found</td></tr>');
                    $("#tbl-lv-documents-listing_processing").css("display", "none");

                },
                complete: function () {
                    $("#page-preloader").hide();
                }
            }
        });
    }

    /* New Users listing table */
    if ($('#tbl-new-users-listing').length) {
        customer_list = $('#tbl-new-users-listing').DataTable({
            "paging": true,
            "lengthMenu": [10, 20, 50, 100, 200, 500, 1000],
            "columnDefs": [
                { "searchable": false, "targets": [0, 1] }
            ],
            "language": {
                searchPlaceholder: "Search #",
                paginate: {
                    next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                    previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                },
                "emptyTable": "Record(s) not found.",
            },
            initComplete: function () {
                var $buttons = jQuery('.dt-buttons').hide();
                jQuery('#export_new_user').on('click', function () {
                    var export_type = jQuery(this).attr('data-export-type');
                    if (export_type) {
                        var btnClass = '.buttons-' + export_type;
                    }
                    if (btnClass) $buttons.find(btnClass).click();
                })
            },
            dom: 'Blfrtip',
            buttons: [
                {
                    extend: 'csvHtml5',
                    text: 'Export',
                    title: '',
                    exportOptions: {
                        columns: [2, 4, 5],
                        format: {
                            body: function (data, row, column, node) {
                                return (column === 2 || column === 4 || column === 5) ?
                                    data.replace(/[$,]/g, '') :
                                    data;
                            }
                        }
                    }
                },
            ],
            "drawCallback": function () {
                $('.dataTables_paginate > .pagination li').addClass('page-item');
                $('.dataTables_paginate > .pagination a').addClass('page-link');
                $('.dataTables_paginate > .pagination li.previous a, .dataTables_paginate > .pagination li.next a').addClass('rounded');
            },
            "ordering": false,
            "serverSide": true,
            "ajax": {
                url: base_url + "admin/order/home/get_new_users_list",
                type: "post",
                beforeSend: function () {
                    $("#page-preloader").show();
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
                    $("#tbl-new-users-listing tbody").append('<tr><td colspan="12" class="text-center">No records found</td></tr>');
                    $("#tbl-new-users-listing_processing").css("display", "none");

                },
                complete: function () {
                    $("#page-preloader").hide();
                }
            }
        });
    }

    if ($('#tbl-master-users-listing').length) {
        customer_list = $('#tbl-master-users-listing').DataTable({
            "paging": true,
            "lengthMenu": [10, 20, 50, 100, 200, 500, 1000],
            "columnDefs": [
                { "searchable": false, "targets": [0, 1] }
            ],
            "language": {
                searchPlaceholder: "Search #",
                paginate: {
                    next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                    previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                },
                "emptyTable": "Record(s) not found.",
            },
            initComplete: function () {
                var $buttons = jQuery('.dt-buttons').hide();
                jQuery('#export_master_users').on('click', function () {
                    var export_type = jQuery(this).attr('data-export-type');
                    if (export_type) {
                        var btnClass = '.buttons-' + export_type;
                    }
                    if (btnClass) $buttons.find(btnClass).click();
                })
            },
            dom: 'Blfrtip',
            buttons: [
                {
                    extend: 'csvHtml5',
                    text: 'Export',
                    title: '',
                    exportOptions: {
                        columns: [0, 1, 2, 3],
                        format: {
                            body: function (data, row, column, node) {
                                return (column === 0 || column === 1 || column === 2 || column === 3) ?
                                    data.replace(/[$,]/g, '') :
                                    data;
                            }
                        }
                    }
                },
            ],
            "drawCallback": function () {
                $('.dataTables_paginate > .pagination li').addClass('page-item');
                $('.dataTables_paginate > .pagination a').addClass('page-link');
                $('.dataTables_paginate > .pagination li.previous a, .dataTables_paginate > .pagination li.next a').addClass('rounded');
            },
            "ordering": false,
            "serverSide": true,
            "ajax": {
                url: base_url + "admin/order/home/get_master_users_list",
                type: "post",
                beforeSend: function () {
                    $("#page-preloader").show();
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
                    $("#tbl-master-users-listing tbody").append('<tr><td colspan="12" class="text-center">No records found</td></tr>');
                    $("#tbl-master-users-listing_processing").css("display", "none");

                },
                complete: function () {
                    $("#page-preloader").hide();
                }
            }
        });
    }

    /* Tax data */
    if ($('#tbl-tax-data-listing').length) {
        log_list = $('#tbl-tax-data-listing').DataTable({
            "paging": true,
            "lengthMenu": [10, 20, 50, 100, 200, 500, 1000],
            "columnDefs": [
                { "searchable": false, "targets": [0, 1] }
            ],
            "columns": [
                {
                    "width": "5%"
                },
                {
                    "width": "10%"
                },
                {
                    "width": "30%"
                },
                {
                    "width": "15%"
                },
                {
                    "width": "15%"
                },
                {
                    "width": "15%"
                },
                {
                    "width": "10%"
                },
            ],
            "language": {
                searchPlaceholder: "#Order No, Address",
                paginate: {
                    next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                    previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                },
                "emptyTable": "Record(s) not found.",
            },
            "dom": '<"row"<"col-sm-12"<"text-left"f>>><"row"<"col-sm-12"rt>><"row"<"col-sm-12"l><"col-sm-5"i><"col-sm-7"p>><"clear">',
            initComplete: function () {
                var input = $('.dataTables_filter input').unbind(),
                    self = this.api(),
                    $taxLogDropDown = $('<span style="margin-left:20px;">Message: </span><select style="width:auto;" id="taxLog" class="custom-select custom-select-sm form-control form-control-sm"><option value="">All</option><option value="success">Success</option><option value="error">Error</option></select>'),
                    $taxLogDateRange = $('<span style="margin-left:20px;" class="date-range-span">Created Date: </span><div id="taxDateRangeControl" class="date-range-control"><i class="fa fa-calendar"></i>&nbsp;<span></span> <i class="fa fa-caret-down float-right"></i><input type="hidden" id="taxDateRange" /></div>'),
                    // $searchButton = $('<button style="margin-left:20px;" class="btn btn-secondary">')
                    // .text('Search')
                    $searchButton = $('<button style="margin-left:20px;" class="btn btn-success btn-icon-split float-right mr-2"><span class="icon text-white-50"><i class="fa fa-search"></i></span><span class="text">Search</span></button>')
                        .click(function () {
                            self.search(input.val(), $('#customerDateRange').val()).draw();
                        }),
                    // $clearButton = $('<button style="margin-left:20px;" class="btn btn-secondary">')
                    // .text('Clear')
                    $clearButton = $('<button style="margin-left:20px;" class="btn btn-secondary btn-icon-split float-right mr-2"><span class="icon text-white-50"><i class="fa fa-eraser"></i></span><span class="text">Clear</span></button>')
                        .click(function () {
                            input.val('');
                            $("#taxLog").val('');
                            $('#taxDateRangeControl span').html('');
                            $('#taxDateRange').val('');
                            $searchButton.click();
                        })

                $('.dataTables_filter').append($taxLogDropDown, $taxLogDateRange, $clearButton, $searchButton);

            },
            "drawCallback": function () {
                $('.dataTables_paginate > .pagination li').addClass('page-item');
                $('.dataTables_paginate > .pagination a').addClass('page-link');
                $('.dataTables_paginate > .pagination li.previous a, .dataTables_paginate > .pagination li.next a').addClass('rounded');
            },
            "ordering": false,
            "serverSide": true,
            "ajax": {
                url: base_url + "admin/order/titlePoint/get_tax_data", // json datasource
                type: "post",
                "data": function (d) {
                    d.dateRange = $('#taxDateRange').val();
                    d.taxLog = $('#taxLog').val();
                },
                beforeSend: function () {
                    $("#page-preloader").show();
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
                    $("#tbl-tax-data-listing tbody").append('<tr><td colspan="12" class="text-center">No records found</td></tr>');
                    $("#tbl-tax-data-listing_processing").css("display", "none");

                },
                complete: function () {
                    $("#page-preloader").hide();

                    setTimeout(function () {
                        var start = moment().startOf('month')
                        var end = moment();

                        function cb(start, end) {
                            $('#taxDateRangeControl span').html(start.format('MM/DD/YYYY') + ' - ' + end.format('MM/DD/YYYY'));
                            $('#taxDateRange').val(start.format('MM/DD/YYYY') + ' - ' + end.format('MM/DD/YYYY'));
                        }

                        var dateRange = $('#taxDateRangeControl').daterangepicker({
                            autoUpdateInput: false,
                            locale: {
                                cancelLabel: 'Clear'
                            },
                            opens: 'right',
                            startDate: start,
                            endDate: end,
                            ranges: {
                                'Today': [moment(), moment()],
                                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                                'This Month': [moment().startOf('month'), moment().endOf('month')],
                                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                            }
                        }, cb);

                        dateRange.on('apply.daterangepicker', function (ev, picker) {
                            $('#taxDateRange').val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
                            $('#taxDateRangeControl span').html(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
                        });

                        dateRange.on('cancel.daterangepicker', function (ev, picker) {
                            $('#taxDateRangeControl span').html('');
                            $('#taxDateRange').val('');
                        });

                        //cb(start, end);

                    }, 100);
                }
            },

        });

    }
    /* Tax data */

    /* Tax logs */
    if ($('#tbl-tax-log-listing').length) {
        log_list = $('#tbl-tax-log-listing').DataTable({
            "paging": true,
            "lengthMenu": [10, 20, 50, 100, 200, 500, 1000],
            "columnDefs": [
                { "searchable": false, "targets": [0, 1] }
            ],
            "columns": [
                {
                    "width": "5%"
                },
                {
                    "width": "10%"
                },
                {
                    "width": "30%"
                },
                {
                    "width": "15%"
                },
                {
                    "width": "15%"
                },
                {
                    "width": "15%"
                },
                {
                    "width": "10%"
                },
            ],
            "language": {
                searchPlaceholder: "#Order No, Address",
                paginate: {
                    next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                    previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                },
                "emptyTable": "Record(s) not found.",
            },
            "dom": '<"row"<"col-sm-12"<"text-left"f>>><"row"<"col-sm-12"rt>><"row"<"col-sm-12"l><"col-sm-5"i><"col-sm-7"p>><"clear">',
            initComplete: function () {
                var input = $('.dataTables_filter input').unbind(),
                    self = this.api(),
                    $taxLogDropDown = $('<span style="margin-left:20px;">Message: </span><select style="width:auto;" id="taxLog" class="custom-select custom-select-sm form-control form-control-sm"><option value="">All</option><option value="success">Success</option><option value="error">Error</option></select>'),
                    $taxLogDateRange = $('<span style="margin-left:20px;" class="date-range-span">Created Date: </span><div id="taxDateRangeControl" class="date-range-control"><i class="fa fa-calendar"></i>&nbsp;<span></span> <i class="fa fa-caret-down float-right"></i><input type="hidden" id="taxDateRange" /></div>'),
                    // $searchButton = $('<button style="margin-left:20px;" class="btn btn-secondary">')
                    // .text('Search')
                    $searchButton = $('<button style="margin-left:20px;" class="btn btn-success btn-icon-split float-right mr-2"><span class="icon text-white-50"><i class="fa fa-search"></i></span><span class="text">Search</span></button>')
                        .click(function () {
                            self.search(input.val(), $('#customerDateRange').val()).draw();
                        }),
                    // $clearButton = $('<button style="margin-left:20px;" class="btn btn-secondary">')
                    // .text('Clear')
                    $clearButton = $('<button style="margin-left:20px;" class="btn btn-secondary btn-icon-split float-right mr-2"><span class="icon text-white-50"><i class="fa fa-eraser"></i></span><span class="text">Clear</span></button>')
                        .click(function () {
                            input.val('');
                            $("#taxLog").val('');
                            $('#taxDateRangeControl span').html('');
                            $('#taxDateRange').val('');
                            $searchButton.click();
                        })

                $('.dataTables_filter').append($taxLogDropDown, $taxLogDateRange, $clearButton, $searchButton);

            },
            "drawCallback": function () {
                $('.dataTables_paginate > .pagination li').addClass('page-item');
                $('.dataTables_paginate > .pagination a').addClass('page-link');
                $('.dataTables_paginate > .pagination li.previous a, .dataTables_paginate > .pagination li.next a').addClass('rounded');
            },
            "ordering": false,
            "serverSide": true,
            "ajax": {
                url: base_url + "admin/order/titlePoint/get_tax_logs", // json datasource
                type: "post",
                "data": function (d) {
                    d.dateRange = $('#taxDateRange').val();
                    d.taxLog = $('#taxLog').val();
                },
                beforeSend: function () {
                    $("#page-preloader").show();
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
                    $("#tbl-tax-log-listing tbody").append('<tr><td colspan="12" class="text-center">No records found</td></tr>');
                    $("#tbl-tax-log-listing_processing").css("display", "none");

                },
                complete: function () {
                    $("#page-preloader").hide();

                    setTimeout(function () {
                        var start = moment().startOf('month')
                        var end = moment();

                        function cb(start, end) {
                            $('#taxDateRangeControl span').html(start.format('MM/DD/YYYY') + ' - ' + end.format('MM/DD/YYYY'));
                            $('#taxDateRange').val(start.format('MM/DD/YYYY') + ' - ' + end.format('MM/DD/YYYY'));
                        }

                        var dateRange = $('#taxDateRangeControl').daterangepicker({
                            autoUpdateInput: false,
                            locale: {
                                cancelLabel: 'Clear'
                            },
                            opens: 'right',
                            startDate: start,
                            endDate: end,
                            ranges: {
                                'Today': [moment(), moment()],
                                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                                'This Month': [moment().startOf('month'), moment().endOf('month')],
                                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                            }
                        }, cb);

                        dateRange.on('apply.daterangepicker', function (ev, picker) {
                            $('#taxDateRange').val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
                            $('#taxDateRangeControl span').html(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
                        });

                        dateRange.on('cancel.daterangepicker', function (ev, picker) {
                            $('#taxDateRangeControl span').html('');
                            $('#taxDateRange').val('');
                        });

                        //cb(start, end);

                    }, 100);
                }
            },

        });

    }
    /* Tax logs */

    if ($('#tbl-tax-documents-listing').length) {
        cpl_document_list = $('#tbl-tax-documents-listing').DataTable({
            "paging": true,
            "lengthMenu": [10, 20, 50, 100, 200, 500, 1000],
            "columnDefs": [
                { "searchable": false, "targets": [0, 1] }
            ],
            "language": {
                searchPlaceholder: "#FileNamber, DocumentName",
                paginate: {
                    next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                    previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                },
                "emptyTable": "Record(s) not found.",
            },
            initComplete: function () {
                var $buttons = jQuery('.dt-buttons').hide();
                jQuery('#export_tax_documents').on('click', function () {
                    var export_type = jQuery(this).attr('data-export-type');
                    if (export_type) {
                        var btnClass = '.buttons-' + export_type;
                    }
                    if (btnClass) $buttons.find(btnClass).click();
                })
            },
            dom: 'Blfrtip',
            buttons: [
                {
                    extend: 'csvHtml5',
                    text: 'Export',
                    title: 'Tax Documents',
                    exportOptions: {
                        columns: [0, 1, 2],
                        format: {
                            body: function (data, row, column, node) {
                                return (column === 1 || column === 2) ?
                                    data.replace(/[$,]/g, '') :
                                    data;
                            }
                        }
                    }
                },
            ],
            "drawCallback": function () {
                $('.dataTables_paginate > .pagination li').addClass('page-item');
                $('.dataTables_paginate > .pagination a').addClass('page-link');
                $('.dataTables_paginate > .pagination li.previous a, .dataTables_paginate > .pagination li.next a').addClass('rounded');
            },
            "ordering": false,
            "serverSide": true,
            "ajax": {
                url: base_url + "admin/order/home/get_tax_document_list",
                type: "post",
                beforeSend: function () {
                    $("#page-preloader").show();
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
                    $("#tbl-tax-documents-listing tbody").append('<tr><td colspan="4" class="text-center">No records found</td></tr>');
                    $("#tbl-tax-documents-listing_processing").css("display", "none");

                },
                complete: function () {
                    $("#page-preloader").hide();
                }
            }
        });
    }

    if ($('#tbl-pre-listing-documents-listing').length) {
        pre_listing_documents = $('#tbl-pre-listing-documents-listing').DataTable({
            "paging": true,
            "lengthMenu": [10, 20, 50, 100, 200, 500, 1000],
            "columnDefs": [
                { "searchable": false, "targets": [0, 1] }
            ],
            "language": {
                searchPlaceholder: "Search",
                paginate: {
                    next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                    previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                },
                "emptyTable": "Record(s) not found.",
            },
            initComplete: function () {

            },
            dom: 'Blfrtip',
            buttons: [],
            "drawCallback": function () {
                $('.dataTables_paginate > .pagination li').addClass('page-item');
                $('.dataTables_paginate > .pagination a').addClass('page-link');
                $('.dataTables_paginate > .pagination li.previous a, .dataTables_paginate > .pagination li.next a').addClass('rounded');
            },
            "ordering": false,
            "serverSide": true,
            "ajax": {
                url: base_url + "admin/order/home/get_pre_listing_document_list",
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
                    $("#tbl-pre-listing-documents-listing tbody").append('<tr><td colspan="4" class="text-center">No records found</td></tr>');
                    $("#tbl-pre-listing-documents-listing_processing").css("display", "none");

                }
            }
        });
    }
    if ($('#tbl-lp-listing-documents-listing').length) {
        lp_listing_documents = $('#tbl-lp-listing-documents-listing').DataTable({
            "paging": true,
            "lengthMenu": [10, 20, 50, 100, 200, 500, 1000],
            "columnDefs": [
                { "searchable": false, "targets": [0, 1] }
            ],
            "language": {
                searchPlaceholder: "Search",
                paginate: {
                    next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                    previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                },
                "emptyTable": "Record(s) not found.",
            },
            initComplete: function () {

            },
            dom: 'Blfrtip',
            buttons: [],
            "drawCallback": function () {
                $('.dataTables_paginate > .pagination li').addClass('page-item');
                $('.dataTables_paginate > .pagination a').addClass('page-link');
                $('.dataTables_paginate > .pagination li.previous a, .dataTables_paginate > .pagination li.next a').addClass('rounded');
            },
            "ordering": false,
            "serverSide": true,
            "ajax": {
                url: base_url + "admin/order/home/get_lp_listing_document_list",
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
                    $("#tbl-lp-listing-documents-listing tbody").append('<tr><td colspan="4" class="text-center">No records found</td></tr>');
                    $("#tbl-lp-listing-documents-listing_processing").css("display", "none");

                }
            }
        });
    }

    if ($('#tbl-lp-xml-listing').length) {
        pre_listing_documents = $('#tbl-lp-xml-listing').DataTable({
            "paging": true,
            "lengthMenu": [10, 20, 50, 100, 200, 500, 1000],
            "columnDefs": [
                { "searchable": false, "targets": [0, 1] }
            ],
            "language": {
                searchPlaceholder: "Search #File Number",
                paginate: {
                    next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                    previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                },
                "emptyTable": "Record(s) not found.",
            },
            initComplete: function () {

            },
            dom: 'Blfrtip',
            buttons: [],
            "drawCallback": function () {
                $('.dataTables_paginate > .pagination li').addClass('page-item');
                $('.dataTables_paginate > .pagination a').addClass('page-link');
                $('.dataTables_paginate > .pagination li.previous a, .dataTables_paginate > .pagination li.next a').addClass('rounded');
            },
            "ordering": false,
            "serverSide": true,
            "ajax": {
                url: base_url + "order/admin/get-lp-xml-logs",
                type: "post",
                beforeSend: function () {
                    $("#page-preloader").show();
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
                    $("#tbl-lp-xml-listing tbody").append('<tr><td colspan="4" class="text-center">No records found</td></tr>');
                    $("#tbl-lp-xml-listing_processing").css("display", "none");

                },
                complete: function () {
                    $("#page-preloader").hide();
                }
            }
        });
    }

    if ($('#tbl-admin-user-logs').length) {
        admin_user_logs = $('#tbl-admin-user-logs').DataTable({
            "paging": true,
            "lengthMenu": [10, 20, 50, 100, 200, 500, 1000],
            "columnDefs": [
                { "searchable": false, "targets": [0, 1] }
            ],
            "language": {
                searchPlaceholder: "Search #",
                paginate: {
                    next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                    previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                },
                "emptyTable": "Record(s) not found.",
            },
            initComplete: function () {

            },
            dom: 'Blfrtip',
            buttons: [],
            "drawCallback": function () {
                $('.dataTables_paginate > .pagination li').addClass('page-item');
                $('.dataTables_paginate > .pagination a').addClass('page-link');
                $('.dataTables_paginate > .pagination li.previous a, .dataTables_paginate > .pagination li.next a').addClass('rounded');
            },
            "ordering": false,
            "serverSide": true,
            "ajax": {
                url: base_url + "admin/order/home/get_admin_user_logs",
                type: "post",
                beforeSend: function () {
                    $("#page-preloader").show();
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
                    $("#tbl-lp-listing-documents-listing tbody").append('<tr><td colspan="4" class="text-center">No records found</td></tr>');
                    $("#tbl-lp-listing-documents-listing_processing").css("display", "none");

                },
                complete: function () {
                    $("#page-preloader").hide();
                }
            }
        });
    }

    if ($('#tbl-lp-document-types-listing').length) {
        lp_document_list = $('#tbl-lp-document-types-listing').DataTable({
            "paging": true,
            "lengthMenu": [10, 20, 50, 100, 200, 500, 1000],
            "columnDefs": [
                { "searchable": false, "targets": [0, 1] },
                { "targets": [0, 5], "orderable": false },
                { "targets": [0, 6], "orderable": false },
                { "targets": [0, 7], "orderable": false }
            ],
            "language": {
                searchPlaceholder: "Search #Code, Type",
                paginate: {
                    next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                    previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                },
                "emptyTable": "Record(s) not found.",
            },
            initComplete: function () {

            },
            // dom: 'Blfrtip',
            "dom": 'lf<"FilterOrderListing">rtip',
            buttons: [],
            "drawCallback": function () {
                $('.dataTables_paginate > .pagination li').addClass('page-item');
                $('.dataTables_paginate > .pagination a').addClass('page-link');
                $('.dataTables_paginate > .pagination li.previous a, .dataTables_paginate > .pagination li.next a').addClass('rounded');
            },
            "ordering": true,
            "serverSide": true,
            "ajax": {
                url: base_url + "admin/order/home/get_lp_document_list",
                type: "post",
                data: function (d) {
                    d.is_display = $('#isDisplayFilter').val();
                },
                beforeSend: function () {
                    $("#page-preloader").show();
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
                    $("#tbl-lp-document-types-listing tbody").append('<tr><td colspan="4" class="text-center">No records found</td></tr>');
                    $("#tbl-lp-document-types-listing_processing").css("display", "none");

                },
                complete: function () {
                    $("#page-preloader").hide();
                }
            }
        });

        var options = '<option value="1">Checked</option><option value="0">Unchecked</option>';

        $("div.FilterOrderListing").html('<label> Is Display filter: <select style="width:auto;" name="isDisplayFilter" id="isDisplayFilter" class="custom-select custom-select-sm form-control form-control-sm"> <option value="" > All </option>"' + options + '"</select></label>');
        $("#isDisplayFilter").on("change", function () {
            lp_document_list.ajax.reload();
        });
    }

    if ($('#tbl-lp-alert-listing').length) {
        lp_document_list = $('#tbl-lp-alert-listing').DataTable({
            "paging": true,
            "lengthMenu": [10, 20, 50, 100, 200, 500, 1000],
            "columnDefs": [
                { "searchable": false, "targets": [0, 1] }
            ],
            "language": {
                searchPlaceholder: "Search #Days, Color, Code",
                paginate: {
                    next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                    previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                },
                "emptyTable": "Record(s) not found.",
            },
            initComplete: function () {

            },
            // dom: 'Blfrtip',
            "dom": 'lf<"FilterOrderListing">rtip',
            buttons: [],
            "drawCallback": function () {
                $('.dataTables_paginate > .pagination li').addClass('page-item');
                $('.dataTables_paginate > .pagination a').addClass('page-link');
                $('.dataTables_paginate > .pagination li.previous a, .dataTables_paginate > .pagination li.next a').addClass('rounded');
            },
            "ordering": true,
            "serverSide": true,
            "ajax": {
                url: base_url + "admin/order/home/get_lp_alert_list",
                type: "post",
                data: function (d) {
                    // d.is_display= $('#isDisplayFilter').val();
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
                    $("#tbl-lp-alert-listing tbody").append('<tr><td colspan="4" class="text-center">No records found</td></tr>');
                    $("#tbl-lp-alert-listing_processing").css("display", "none");

                }
            }
        });
    }


    /* Grant deed logs */
    if ($('#tbl-grant-deed-log-listing').length) {
        log_list = $('#tbl-grant-deed-log-listing').DataTable({
            "paging": true,
            "lengthMenu": [10, 20, 50, 100, 200, 500, 1000],
            "columnDefs": [
                { "searchable": false, "targets": [0, 1] }
            ],
            "columns": [
                {
                    "width": "5%"
                },
                {
                    "width": "10%"
                },
                {
                    "width": "30%"
                },
                {
                    "width": "20%"
                },
                {
                    "width": "17%"
                }, {
                    "width": "18%"
                },
            ],
            "language": {
                searchPlaceholder: "#Order No, Address",
                paginate: {
                    next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                    previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                },
                "emptyTable": "Record(s) not found.",
            },
            "dom": '<"row"<"col-sm-12"<"text-left"f>>><"row"<"col-sm-12"rt>><"row"<"col-sm-12"l><"col-sm-5"i><"col-sm-7"p>><"clear">',
            initComplete: function () {
                var input = $('.dataTables_filter input').unbind(),
                    self = this.api(),
                    $grantLogDropDown = $('<span style="margin-left:20px;">Message: </span><select style="width:auto;" id="grantLog" class="custom-select custom-select-sm form-control form-control-sm"><option value="">All</option><option value="success">Success</option><option value="error">Error</option></select>'),
                    $grantLogDateRange = $('<span style="margin-left:20px;" class="date-range-span">Created Date: </span><div id="grantDateRangeControl" class="date-range-control"><i class="fa fa-calendar"></i>&nbsp;<span></span> <i class="fa fa-caret-down float-right"></i><input type="hidden" id="grantDateRange" /></div>'),
                    // $searchButton = $('<button style="margin-left:20px;" class="btn btn-secondary">')
                    // .text('Search')
                    $searchButton = $('<button style="margin-left:20px;" class="btn btn-success btn-icon-split float-right mr-2"><span class="icon text-white-50"><i class="fa fa-search"></i></span><span class="text">Search</span></button>')
                        .click(function () {
                            self.search(input.val(), $('#customerDateRange').val()).draw();
                        }),
                    // $clearButton = $('<button style="margin-left:20px;" class="btn btn-secondary">')
                    // .text('Clear')
                    $clearButton = $('<button style="margin-left:20px;" class="btn btn-secondary btn-icon-split float-right mr-2"><span class="icon text-white-50"><i class="fa fa-eraser"></i></span><span class="text">Clear</span></button>')
                        .click(function () {
                            input.val('');
                            $("#grantLog").val('');
                            $('#grantDateRangeControl span').html('');
                            $('#grantDateRange').val('');
                            $searchButton.click();
                        })

                $('.dataTables_filter').append($grantLogDropDown, $grantLogDateRange, $clearButton, $searchButton);

            },
            "drawCallback": function () {
                $('.dataTables_paginate > .pagination li').addClass('page-item');
                $('.dataTables_paginate > .pagination a').addClass('page-link');
                $('.dataTables_paginate > .pagination li.previous a, .dataTables_paginate > .pagination li.next a').addClass('rounded');
            },
            "ordering": false,
            "serverSide": true,
            "ajax": {
                url: base_url + "admin/order/titlePoint/get_grant_deed_logs", // json datasource
                type: "post", // method  , by default get
                beforeSend: function () {
                    $("#page-preloader").show();
                },
                "data": function (d) {
                    d.dateRange = $('#grantDateRange').val();
                    d.grantLog = $('#grantLog').val();
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
                    $("#tbl-grant-deed-log-listing tbody").append('<tr><td colspan="12" class="text-center">No records found</td></tr>');
                    $("#tbl-grant-deed-log-listing_processing").css("display", "none");

                },
                complete: function () {
                    $("#page-preloader").hide();

                    setTimeout(function () {
                        var start = moment().startOf('month')
                        var end = moment();

                        function cb(start, end) {
                            $('#grantDateRangeControl span').html(start.format('MM/DD/YYYY') + ' - ' + end.format('MM/DD/YYYY'));
                            $('#grantDateRange').val(start.format('MM/DD/YYYY') + ' - ' + end.format('MM/DD/YYYY'));
                        }

                        var dateRange = $('#grantDateRangeControl').daterangepicker({
                            autoUpdateInput: false,
                            locale: {
                                cancelLabel: 'Clear'
                            },
                            opens: 'right',
                            startDate: start,
                            endDate: end,
                            ranges: {
                                'Today': [moment(), moment()],
                                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                                'This Month': [moment().startOf('month'), moment().endOf('month')],
                                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                            }
                        }, cb);

                        dateRange.on('apply.daterangepicker', function (ev, picker) {
                            $('#grantDateRange').val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
                            $('#grantDateRangeControl span').html(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
                        });

                        dateRange.on('cancel.daterangepicker', function (ev, picker) {
                            $('#grantDateRangeControl span').html('');
                            $('#grantDateRange').val('');
                        });

                        //cb(start, end);

                    }, 100);
                }
            },

        });

    }
    /* Grant deed logs */

    if ($('#tbl-curative-documents-listing').length) {
        cpl_document_list = $('#tbl-curative-documents-listing').DataTable({
            "paging": true,
            "lengthMenu": [10, 20, 50, 100, 200, 500, 1000],
            "columnDefs": [
                { "searchable": false, "targets": [0, 1] }
            ],
            "language": {
                searchPlaceholder: "#FileNumber, DocumentName",
                paginate: {
                    next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                    previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                },
                "emptyTable": "Record(s) not found.",
            },
            initComplete: function () {
                var $buttons = jQuery('.dt-buttons').hide();
                jQuery('#export_curative_documents').on('click', function () {
                    var export_type = jQuery(this).attr('data-export-type');
                    if (export_type) {
                        var btnClass = '.buttons-' + export_type;
                    }
                    if (btnClass) $buttons.find(btnClass).click();
                })
            },
            dom: 'Blfrtip',
            buttons: [
                {
                    extend: 'csvHtml5',
                    text: 'Export',
                    title: 'Curative Documents',
                    exportOptions: {
                        columns: [0, 1, 2],
                        format: {
                            body: function (data, row, column, node) {
                                return (column === 1 || column === 2) ?
                                    data.replace(/[$,]/g, '') :
                                    data;
                            }
                        }
                    }
                },
            ],
            "drawCallback": function () {
                $('.dataTables_paginate > .pagination li').addClass('page-item');
                $('.dataTables_paginate > .pagination a').addClass('page-link');
                $('.dataTables_paginate > .pagination li.previous a, .dataTables_paginate > .pagination li.next a').addClass('rounded');
            },
            "ordering": false,
            "serverSide": true,
            "ajax": {
                url: base_url + "admin/order/home/get_curative_document_list",
                type: "post",
                beforeSend: function () {
                    $("#page-preloader").show();
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
                    $("#tbl-curative-documents-listing tbody").append('<tr><td colspan="4" class="text-center">No records found</td></tr>');
                    $("#tbl-curative-documents-listing_processing").css("display", "none");
                },
                complete: function () {
                    $("#page-preloader").hide();
                }
            }
        });
    }

    if ($('#tbl-file-documents-listing').length) {
        forms_list = $('#tbl-file-documents-listing').DataTable({
            "paging": true,
            "info": false,
            "bLengthChange": false,
            // "lengthMenu": [10, 20, 50, 100, 200, 500, 1000],
            "columnDefs": [
                { "searchable": false, "targets": [0, 1] }
            ],
            "searching": false,
            "language": {
                searchPlaceholder: "Search",
                paginate: {
                    next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                    previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                },
                "emptyTable": "Record(s) not found.",
            },
            initComplete: function () {
                var $buttons = jQuery('.dt-buttons').hide();
                jQuery('#export_curative_documents').on('click', function () {
                    var export_type = jQuery(this).attr('data-export-type');
                    if (export_type) {
                        var btnClass = '.buttons-' + export_type;
                    }
                    if (btnClass) $buttons.find(btnClass).click();
                })
            },
            dom: 'Blfrtip',
            buttons: [
                {
                    extend: 'csvHtml5',
                    text: 'Export',
                    title: 'Curative Documents',
                    exportOptions: {
                        columns: [0, 1, 2],
                        format: {
                            body: function (data, row, column, node) {
                                return (column === 0 || column === 1 || column === 2) ?
                                    data.replace(/[$,]/g, '') :
                                    data;
                            }
                        }
                    }
                },
            ],
            "drawCallback": function () {
                $('.dataTables_paginate > .pagination li').addClass('page-item');
                $('.dataTables_paginate > .pagination a').addClass('page-link');
                $('.dataTables_paginate > .pagination li.previous a, .dataTables_paginate > .pagination li.next a').addClass('rounded');
            },
            "ordering": false,
            "serverSide": true,
            "ajax": {
                url: base_url + "admin/order/home/get_file_document_list",
                type: "post",
                beforeSend: function () {
                    $("#page-preloader").show();
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
                    $("#tbl-file-documents-listing tbody").append('<tr><td colspan="4" class="text-center">No records found</td></tr>');
                    $("#tbl-file-documents-listing_processing").css("display", "none");
                },
                complete: function () {
                    $("#page-preloader").hide();
                }
            }
        });
    }

    if ($('#tbl-companies-listing').length) {
        companies_list = $('#tbl-companies-listing').DataTable({
            "paging": true,
            "lengthMenu": [10, 20, 50, 100, 200, 500, 1000],
            "columnDefs": [
                { "searchable": false, "targets": [0, 1] }
            ],
            "language": {
                searchPlaceholder: "Search #",
                paginate: {
                    next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                    previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                },
                "emptyTable": "Record(s) not found.",
            },
            initComplete: function () {
                var $buttons = jQuery('.dt-buttons').hide();
                jQuery('#export_companies').on('click', function () {
                    var export_type = jQuery(this).attr('data-export-type');
                    if (export_type) {
                        var btnClass = '.buttons-' + export_type;
                    }
                    if (btnClass) $buttons.find(btnClass).click();
                })
            },
            dom: 'Blfrtip',
            buttons: [
                {
                    extend: 'csvHtml5',
                    text: 'Export',
                    title: 'Companies',
                    exportOptions: {
                        columns: [0, 1, 2],
                        format: {
                            body: function (data, row, column, node) {
                                return (column === 0 || column === 1 || column === 2 || column === 3 || column === 4 || column === 5) ?
                                    data.replace(/[$,]/g, '') :
                                    data;
                            }
                        }
                    }
                },
            ],
            "drawCallback": function () {
                $('.dataTables_paginate > .pagination li').addClass('page-item');
                $('.dataTables_paginate > .pagination a').addClass('page-link');
                $('.dataTables_paginate > .pagination li.previous a, .dataTables_paginate > .pagination li.next a').addClass('rounded');
            },
            "ordering": false,
            "serverSide": true,
            "ajax": {
                url: base_url + "admin/order/home/get_companies_list",
                type: "post",
                beforeSend: function () {
                    $("#page-preloader").show();
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
                    $("#tbl-companies-listing tbody").append('<tr><td colspan="4" class="text-center">No records found</td></tr>');
                    $("#tbl-companies-listing_processing").css("display", "none");
                },
                complete: function () {
                    $("#page-preloader").hide();
                }
            }
        });
    }

    if ($('#tbl-incorrect-customers-listing').length) {
        incorrect_customer_list = $('#tbl-incorrect-customers-listing').DataTable({
            /*"pageLength": 2,*/
            "paging": true,
            "lengthMenu": [10, 20, 50, 100, 200, 500, 1000],
            "columnDefs": [
                { "searchable": false, "targets": [0, 1, 2] }
            ],
            "language": {
                searchPlaceholder: "#Name, Email, Company Name",
                paginate: {
                    next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                    previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                },
                "emptyTable": "Record(s) not found.",
            },
            initComplete: function () {
                var $buttons = jQuery('.dt-buttons').hide();
                jQuery('#export_customer').on('click', function () {
                    var export_type = jQuery(this).attr('data-export-type');
                    if (export_type) {
                        var btnClass = '.buttons-' + export_type;
                    }
                    if (btnClass) $buttons.find(btnClass).click();
                })
            },
            dom: '<"FilterCredentialListing">lfrtip',
            buttons: [
                {
                    extend: 'csvHtml5',
                    text: 'Export',
                    title: 'Customers',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9],
                        format: {
                            body: function (data, row, column, node) {
                                return (column === 0 || column === 1 || column === 2 || column === 3 || column === 4 || column === 5 || column === 6 || column === 7 || column === 8 || column === 9) ?
                                    data.replace(/[$,]/g, '') :
                                    data;
                            }
                        }
                    }
                },
            ],
            "drawCallback": function () {
                $('.dataTables_paginate > .pagination li').addClass('page-item');
                $('.dataTables_paginate > .pagination a').addClass('page-link');
                $('.dataTables_paginate > .pagination li.previous a, .dataTables_paginate > .pagination li.next a').addClass('rounded');
            },
            "ordering": false,
            "serverSide": true,
            "ajax": {
                url: base_url + "admin/order/home/get_incorrect_customer_list", // json datasource
                type: "post",
                beforeSend: function () {
                    $("#page-preloader").show();
                },
                /*data   : function( d ) {
                    d.credentials_check = $('#FilterCredentialListing').val();
                }, */// method  , by default get
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    if (parseInt(XMLHttpRequest.status) == 419) {
                        alert("You are logged out. Please login.");
                    }
                    if (parseInt(XMLHttpRequest.status) == 419) {
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                    }
                    $("#tbl-credentials-customers-listing tbody").append('<tr><td colspan="12" class="text-center">No records found</td></tr>');
                    $("#tbl-credentials-customers-listing_processing").css("display", "none");

                },
                complete: function () {
                    $("#page-preloader").hide();
                }
            },
        });
    }

    $('#frmSearch #btnClear').click(function () {
        $('#frmSearch #keyword').val('');
        $('#frmSearch #btnSearch').click();
    });

    if ($('#tbl-partner-api-log-listing').length) {
        partner_log_list = $('#tbl-partner-api-log-listing').DataTable({
            // "searching": false,
            "paging": true,
            "lengthMenu": [10, 20, 50, 100, 200, 500, 1000],
            "columnDefs": [
                { "searchable": false, "targets": [0, 1] }
            ],
            "columns": [
                {
                    "width": "5%"
                },
                {
                    "width": "10%"
                },
                {
                    "width": "15%"
                },
                {
                    "width": "8%"
                },
                {
                    "width": "7%"
                },
                {
                    "width": "35%"
                },
                {
                    "width": "20%"
                },
            ],
            "language": {
                searchPlaceholder: "#Order No",
                paginate: {
                    next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                    previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                },
                "emptyTable": "Record(s) not found.",
            },
            initComplete: function () {
            },
            "dom": 'lf<"custom_filter">rtip',
            "drawCallback": function () {
                $('.dataTables_paginate > .pagination li').addClass('page-item');
                $('.dataTables_paginate > .pagination a').addClass('page-link');
                $('.dataTables_paginate > .pagination li.previous a, .dataTables_paginate > .pagination li.next a').addClass('rounded');
            },
            "ordering": false,
            "serverSide": true,
            "ajax": {
                url: base_url + "admin/order/order/get_partner_api_logs", // json datasource
                type: "post", // method  , by default get
                data: function (d) {
                    d.sales_rep = $('#log_sales_rep').val();
                    d.title_officer = $('#log_title_officer').val();
                },
                beforeSend: function () {
                    $("#page-preloader").show();
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
                    $("#tbl-partner-api-log-listing tbody").append('<tr><td colspan="12" class="text-center">No records found</td></tr>');
                    $("#tbl-partner-api-log-listing_processing").css("display", "none");

                },
                complete: function () {
                    $("#page-preloader").hide();
                }
            },

        });

        if (sales_rep) {
            var obj = jQuery.parseJSON(sales_rep);
            var options = '';
            $.each(obj, function (key, value) {
                options += '<option value="' + value.id + '">' + value.first_name + ' ' + value.last_name + '</option>'
            });
            $("div.custom_filter").html('<label> Sales Rep: <select style="width:auto;" class="custom-select custom-select-sm form-control form-control-sm" name="log_sales_rep" id="log_sales_rep"> <option value="" > All </option>"' + options + '"</select></label>');
        }

        if (title_officer) {
            var obj = jQuery.parseJSON(title_officer);
            var options = '';
            $.each(obj, function (key, value) {
                options += '<option value="' + value.id + '">' + value.first_name + ' ' + value.last_name + '</option>'
            });
            $("div.custom_filter").append('<div class="col-sm-3" style="display:inline"><label> Title Officer: <select style="width:auto;" class="custom-select custom-select-sm form-control form-control-sm" name="log_title_officer" id="log_title_officer"> <option value="" > All </option>"' + options + '"</select></label>');
        }
    }

    $("#log_sales_rep").on("change", function () {
        partner_log_list.ajax.reload();
    });

    $("#log_title_officer").on("change", function () {
        partner_log_list.ajax.reload();
    });

    /* Fees listing */
    if ($('#tbl-fees').length) {
        fees_list = $('#tbl-fees').DataTable({
            "paging": true,
            "pageLength": 50,
            "lengthMenu": [10, 20, 50, 100, 200, 500, 1000],
            "columnDefs": [
                { "searchable": false, "targets": [0, 1] }
            ],
            "language": {
                searchPlaceholder: "#Name",
                paginate: {
                    next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                    previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                },
                "emptyTable": "Record(s) not found.",
            },
            initComplete: function () {
            },
            "drawCallback": function () {
                $('.dataTables_paginate > .pagination li').addClass('page-item');
                $('.dataTables_paginate > .pagination a').addClass('page-link');
                $('.dataTables_paginate > .pagination li.previous a, .dataTables_paginate > .pagination li.next a').addClass('rounded');
            },
            "ordering": false,
            "serverSide": true,
            "ajax": {
                url: base_url + "admin/order/fees/get_fees", // json datasource
                type: "post", // method  , by default get
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    if (parseInt(XMLHttpRequest.status) == 419) {
                        alert("You are logged out. Please login.");
                    }
                    if (parseInt(XMLHttpRequest.status) == 419) {
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                    }
                    $("#tbl-fees tbody").append('<tr><td colspan="12" class="text-center">No records found</td></tr>');
                    $("#tbl-fees_processing").css("display", "none");

                }
            },

        });
    }
    /* Fees listing */

    /* Holidays listing */
    if ($('#tbl-holidays').length) {
        holidays_list = $('#tbl-holidays').DataTable({
            "paging": true,
            "lengthMenu": [10, 20, 50, 100, 200, 500, 1000],
            "columnDefs": [
                { "searchable": false, "targets": [0, 1] }
            ],
            "language": {
                searchPlaceholder: "#Name",
                paginate: {
                    next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                    previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                },
                "emptyTable": "Record(s) not found.",
            },
            initComplete: function () {
            },
            "drawCallback": function () {
                $('.dataTables_paginate > .pagination li').addClass('page-item');
                $('.dataTables_paginate > .pagination a').addClass('page-link');
                $('.dataTables_paginate > .pagination li.previous a, .dataTables_paginate > .pagination li.next a').addClass('rounded');
            },
            "ordering": false,
            "serverSide": true,
            "ajax": {
                url: base_url + "admin/order/holidays/get_holidays", // json datasource
                type: "post", // method  , by default get
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    if (parseInt(XMLHttpRequest.status) == 419) {
                        alert("You are logged out. Please login.");
                    }
                    if (parseInt(XMLHttpRequest.status) == 419) {
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                    }
                    $("#tbl-holidays tbody").append('<tr><td colspan="12" class="text-center">No records found</td></tr>');
                    $("#tbl-holidays_processing").css("display", "none");

                }
            },

        });
    }
    /* Holidays listing */

    /* Holidays listing */
    if ($('#tbl-daily-email-control').length) {
        daily_email_receiver_list = $('#tbl-daily-email-control').DataTable({
            "paging": true,
            "lengthMenu": [10, 20, 50, 100, 200, 500, 1000],
            "columnDefs": [
                { "searchable": false, "targets": [0, 1] }
            ],
            "language": {
                searchPlaceholder: "#Email",
                paginate: {
                    next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                    previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                },
                "emptyTable": "Record(s) not found.",
            },
            initComplete: function () {
            },
            "drawCallback": function () {
                $('.dataTables_paginate > .pagination li').addClass('page-item');
                $('.dataTables_paginate > .pagination a').addClass('page-link');
                $('.dataTables_paginate > .pagination li.previous a, .dataTables_paginate > .pagination li.next a').addClass('rounded');
            },
            "ordering": false,
            "serverSide": true,
            "ajax": {
                url: base_url + "order/admin/get-daily-emailer", // json datasource
                type: "post", // method  , by default get
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    if (parseInt(XMLHttpRequest.status) == 419) {
                        alert("You are logged out. Please login.");
                    }
                    if (parseInt(XMLHttpRequest.status) == 419) {
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                    }
                    $("#tbl-daily-email-control tbody").append('<tr><td colspan="12" class="text-center">No records found</td></tr>');
                    $("#tbl-daily-email-control_processing").css("display", "none");

                }
            },

        });
    }
    /* Holidays listing */

    /* Add Holiday validation */
    if (jQuery('#frm-add-holiday').length || jQuery('#frm-edit-holiday').length) {
        jQuery('#frm-add-holiday,#frm-edit-holiday').validate({
            rules: {
                holiday_name: "required",
                holiday_date: "required"
            },
            messages: {
                holiday_name: "Please enter Holiday Name",
                holiday_date: "Please select Holiday Date"
            },
            submitHandler: function (form) {
                form.submit();
            }
        });
    }
    /* Add fee validation */

    /* Add fee validation */
    if (jQuery('#frm-add-fee').length || jQuery('#frm-edit-fee').length) {
        jQuery('#frm-add-fee,#frm-edit-fee').validate({
            rules: {
                txn_type: "required",
                fee_type: "required",
                fee_name: "required",
                fee_value: "required"
            },
            messages: {
                txn_type: "Please select Transaction Type",
                fee_type: "Please select fee Type",
                fee_name: "Please enter fee name",
                fee_value: "Please enter fee value"
            },
            submitHandler: function (form) {
                form.submit();
            }
        });
    }
    /* Add fee validation */

    /* Fees type listing */
    if ($('#tbl-fees-types').length) {
        fees_type_list = $('#tbl-fees-types').DataTable({
            "paging": true,
            "lengthMenu": [10, 20, 50, 100, 200, 500, 1000],
            "columnDefs": [
                { "searchable": false, "targets": [0, 1] }
            ],
            "language": {
                searchPlaceholder: "#Name",
                paginate: {
                    next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                    previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                },
                "emptyTable": "Record(s) not found.",
            },
            initComplete: function () {
            },
            "drawCallback": function () {
                $('.dataTables_paginate > .pagination li').addClass('page-item');
                $('.dataTables_paginate > .pagination a').addClass('page-link');
                $('.dataTables_paginate > .pagination li.previous a, .dataTables_paginate > .pagination li.next a').addClass('rounded');
            },
            "ordering": false,
            "serverSide": true,
            "ajax": {
                url: base_url + "admin/order/feesTypes/get_fees_types", // json datasource
                type: "post", // method  , by default get
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    if (parseInt(XMLHttpRequest.status) == 419) {
                        alert("You are logged out. Please login.");
                    }
                    if (parseInt(XMLHttpRequest.status) == 419) {
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                    }
                    $("#tbl-fees-types tbody").append('<tr><td colspan="12" class="text-center">No records found</td></tr>');
                    $("#tbl-fees-types_processing").css("display", "none");

                }
            },

        });
    }
    /* Fees type listing */

    /* Add fee type validation */
    if (jQuery('#frm-add-fee-type').length || jQuery('#frm-edit-fee-type').length) {
        jQuery('#frm-add-fee-type,#frm-edit-fee-type').validate({
            rules: {
                fee_type: "required"
            },
            messages: {
                fee_type: "Please enter fee type"
            },
            submitHandler: function (form) {
                form.submit();
            }
        });
    }
    /* Add fee type validation */

    /* Fees type listing */
    if ($('#tbl-code-book').length) {
        code_book_list = $('#tbl-code-book').DataTable({
            "paging": true,
            "lengthMenu": [10, 20, 50, 100, 200, 500, 1000],
            "columnDefs": [
                { "searchable": false, "targets": [0, 1] }
            ],
            "language": {
                searchPlaceholder: "Search #",
                paginate: {
                    next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                    previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                },
                "emptyTable": "Record(s) not found.",
            },
            initComplete: function () {
            },
            "drawCallback": function () {
                $('.dataTables_paginate > .pagination li').addClass('page-item');
                $('.dataTables_paginate > .pagination a').addClass('page-link');
                $('.dataTables_paginate > .pagination li.previous a, .dataTables_paginate > .pagination li.next a').addClass('rounded');
            },
            "ordering": false,
            "serverSide": true,
            "ajax": {
                url: base_url + "admin/order/codeBook/get_code_book", // json datasource
                type: "post", // method  , by default get
                beforeSend: function () {
                    $("#page-preloader").show();
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
                    $("#tbl-code-book tbody").append('<tr><td colspan="12" class="text-center">No records found</td></tr>');
                    $("#tbl-code-book_processing").css("display", "none");

                },
                complete: function () {
                    $("#page-preloader").hide();
                }
            },

        });
    }
    /* Fees type listing */

    if (jQuery('#import-code-book').length) {
        jQuery('#import-code-book').validate({
            rules: {
                file: "required"
            },
            messages: {
                file: "Please upload file to import"
            },
            submitHandler: function (form) {
                form.submit();
            }
        });
    }

    /* Add code book validation */
    if (jQuery('#frm-add-code-book').length || jQuery('#frm-edit-code-book').length) {
        jQuery('#frm-add-code-book,#frm-edit-code-book').validate({
            rules: {
                code: "required",
                type: "required",
            },
            messages: {
                code: "Please enter code",
                type: "Please select type",
            },
            submitHandler: function (form) {
                form.submit();
            }
        });
    }
    /* Add code book validation */

    if ($('#tbl-cpl-proposed-users-listing').length) {
        customer_list = $('#tbl-cpl-proposed-users-listing').DataTable({
            "paging": true,
            "lengthMenu": [10, 20, 50, 100, 200, 500, 1000],
            "columnDefs": [
                { "searchable": false, "targets": [0, 1] }
            ],
            "language": {
                searchPlaceholder: "Search #",
                paginate: {
                    next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                    previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                },
                "emptyTable": "Record(s) not found.",
            },
            initComplete: function () {
                var $buttons = jQuery('.dt-buttons').hide();
                jQuery('#export_cpl_proposed_users').on('click', function () {
                    var export_type = jQuery(this).attr('data-export-type');
                    if (export_type) {
                        var btnClass = '.buttons-' + export_type;
                    }
                    if (btnClass) $buttons.find(btnClass).click();
                })
            },
            dom: 'Blfrtip',
            buttons: [
                {
                    extend: 'csvHtml5',
                    text: 'Export',
                    title: '',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4],
                        format: {
                            body: function (data, row, column, node) {
                                return (column === 0 || column === 1 || column === 2 || column === 3 || column === 4) ?
                                    data.replace(/[$,]/g, '') :
                                    data;
                            }
                        }
                    }
                },
            ],
            "drawCallback": function () {
                $('.dataTables_paginate > .pagination li').addClass('page-item');
                $('.dataTables_paginate > .pagination a').addClass('page-link');
                $('.dataTables_paginate > .pagination li.previous a, .dataTables_paginate > .pagination li.next a').addClass('rounded');
            },
            "ordering": false,
            "serverSide": true,
            "ajax": {
                url: base_url + "admin/order/home/get_cpl_proposed_users_list",
                type: "post",
                beforeSend: function () {
                    $("#page-preloader").show();
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
                    $("#tbl-cpl-proposed-users-listing tbody").append('<tr><td colspan="12" class="text-center">No records found</td></tr>');
                    $("#tbl-cpl-proposed-users-listing_processing").css("display", "none");
                },
                complete: function () {
                    $("#page-preloader").hide();
                }
            }
        });
    }

    if ($('#tbl-password-listing').length) {
        password_list = $('#tbl-password-listing').DataTable({
            "paging": true,
            "lengthMenu": [10, 20, 50, 100, 200, 500, 1000],
            "columnDefs": [
                { "searchable": false, "targets": [0, 1] }
            ],
            "language": {
                paginate: {
                    next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                    previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                },
                "emptyTable": "Record(s) not found.",
            },
            initComplete: function () {

            },
            dom: 'lf<"password_listing_filter">rtip',
            buttons: [],
            "drawCallback": function () {
                $('.dataTables_paginate > .pagination li').addClass('page-item');
                $('.dataTables_paginate > .pagination a').addClass('page-link');
                $('.dataTables_paginate > .pagination li.previous a, .dataTables_paginate > .pagination li.next a').addClass('rounded');
            },
            "ordering": false,
            "serverSide": true,
            "ajax": {
                url: base_url + "admin/order/home/get_password_list",
                type: "post",
                beforeSend: function () {
                    $("#page-preloader").show();
                },
                data: function (d) {
                    d.user_type = $('#user_filter').val();
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
                    $("#tbl-password-listing tbody").append('<tr><td colspan="12" class="text-center">No records found</td></tr>');
                    $("#tbl-password-listing_processing").css("display", "none");
                },
                complete: function () {
                    $("#page-preloader").hide();
                }
            }
        });

        $("div.password_listing_filter").html('<label> User Types: <select style="width:auto;" name="user_filter" id="user_filter" class="custom-select custom-select-sm form-control form-control-sm"><option value=""> Select User Type </option><option value="escrow"> Escrow </option> <option value="lender"> Lender </option><option value="title_officer"> Title Officer </option><option value="sales_rep"> Sales Rep </option><option value="sales_rep_manager">Sales Rep Manager</option><option value="special_lender">Special Lender User</option></select></label>');
    }

    $("#user_filter").on("change", function () {
        password_list.ajax.reload();
    });


    if ($('#tbl-import-order-customers-listing').length) {
        order_customer_list = $('#tbl-import-order-customers-listing').DataTable({
            /*"pageLength": 2,*/
            "paging": true,
            "lengthMenu": [10, 20, 50, 100, 200, 500, 1000],
            "columnDefs": [
                { "searchable": false, "targets": [0, 1, 2] }
            ],
            "language": {
                // searchPlaceholder: "Customer Number",
                paginate: {
                    next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                    previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                },
                "emptyTable": "Record(s) not found.",
            },
            initComplete: function () {
                var $buttons = jQuery('.dt-buttons').hide();
                jQuery('#export_customer').on('click', function () {
                    var export_type = jQuery(this).attr('data-export-type');
                    if (export_type) {
                        var btnClass = '.buttons-' + export_type;
                    }
                    if (btnClass) $buttons.find(btnClass).click();
                })
            },
            dom: '<"FilterCredentialListing">lfrtip',
            buttons: [
                {
                    extend: 'csvHtml5',
                    text: 'Export',
                    title: 'Customers',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9],
                        format: {
                            body: function (data, row, column, node) {
                                return (column === 0 || column === 1 || column === 2 || column === 3 || column === 4 || column === 5 || column === 6 || column === 7 || column === 8 || column === 9) ?
                                    data.replace(/[$,]/g, '') :
                                    data;
                            }
                        }
                    }
                },
            ],
            "drawCallback": function () {
                $('.dataTables_paginate > .pagination li').addClass('page-item');
                $('.dataTables_paginate > .pagination a').addClass('page-link');
                $('.dataTables_paginate > .pagination li.previous a, .dataTables_paginate > .pagination li.next a').addClass('rounded');
            },
            "ordering": false,
            "serverSide": true,
            "ajax": {
                url: base_url + "admin/order/home/get_import_order_customer_list", // json datasource
                type: "post",
                /*data   : function( d ) {
                    d.credentials_check = $('#FilterCredentialListing').val();
                }, */// method  , by default get
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    if (parseInt(XMLHttpRequest.status) == 419) {
                        alert("You are logged out. Please login.");
                    }
                    if (parseInt(XMLHttpRequest.status) == 419) {
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                    }
                    $("#tbl-import-order-customers-listing tbody").append('<tr><td colspan="12" class="text-center">No records found</td></tr>');
                    $("#tbl-import-order-customers-listing_processing").css("display", "none");

                }
            },
        });
    }

    if ($('#tbl-cpl-log-listing').length) {
        cpl_logs_list = $('#tbl-cpl-log-listing').DataTable({
            "paging": true,
            "lengthMenu": [10, 20, 50, 100, 200, 500, 1000],
            "columnDefs": [
                { "searchable": false, "targets": [0, 1] }
            ],
            "language": {
                searchPlaceholder: "Order #Order No",
                paginate: {
                    next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                    previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                },
                "emptyTable": "Record(s) not found.",
            },
            dom: '<"FilterCredentialListing">lfrtip',
            buttons: [],
            initComplete: function () {


            },
            "drawCallback": function () {
                $('.dataTables_paginate > .pagination li').addClass('page-item');
                $('.dataTables_paginate > .pagination a').addClass('page-link');
                $('.dataTables_paginate > .pagination li.previous a, .dataTables_paginate > .pagination li.next a').addClass('rounded');
            },
            "ordering": false,
            "serverSide": true,
            "ajax": {
                url: base_url + "admin/order/order/getCplErrorLogs",
                type: "post",
                beforeSend: function () {
                    $("#page-preloader").show();
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
                    $("#tbl-cpl-log-listing tbody").append('<tr><td colspan="12" class="text-center">No records found</td></tr>');
                    $("#tbl-cpl-log-listing_processing").css("display", "none");

                },
                complete: function () {
                    $("#page-preloader").hide();
                }
            },

        });
    }

    if ($('#tbl-resware-log-listing').length) {
        resware_logs_list = $('#tbl-resware-log-listing').DataTable({
            "paging": true,
            "lengthMenu": [10, 20, 50, 100, 200, 500, 1000],
            "columnDefs": [
                { "searchable": false, "targets": [0, 1] }
            ],
            "language": {
                searchPlaceholder: "Search #",
                paginate: {
                    next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                    previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                },
                "emptyTable": "Record(s) not found.",
            },
            dom: '<"FilterCredentialListing">lfrtip',
            buttons: [],
            initComplete: function () {


            },
            "drawCallback": function () {
                $('.dataTables_paginate > .pagination li').addClass('page-item');
                $('.dataTables_paginate > .pagination a').addClass('page-link');
                $('.dataTables_paginate > .pagination li.previous a, .dataTables_paginate > .pagination li.next a').addClass('rounded');
            },
            "ordering": false,
            "serverSide": true,
            "ajax": {
                url: base_url + "admin/order/order/getReswareLogs",
                type: "post",
                beforeSend: function () {
                    $("#page-preloader").show();
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
                    $("#tbl-resware-log-listing tbody").append('<tr><td colspan="12" class="text-center">No records found</td></tr>');
                    $("#tbl-resware-log-listing_processing").css("display", "none");

                },
                complete: function () {
                    $("#page-preloader").hide();
                }
            },

        });
    }

    /* Rules listing */
    if ($('#tbl-rules-manager').length) {
        rules_list = $('#tbl-rules-manager').DataTable({
            "paging": true,
            "searching": false,
            "lengthMenu": [10, 20, 50, 100, 200, 500, 1000],
            "columnDefs": [
                { "searchable": false, "targets": [0, 1] }
            ],
            "columns": [
                {
                    "width": "5%"
                },
                {
                    "width": "30%"
                },
                {
                    "width": "65%"
                }
            ],
            "language": {
                // searchPlaceholder: "Name",
                paginate: {
                    next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                    previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                },
                "emptyTable": "Record(s) not found.",
            },
            initComplete: function () {
                $('#rules_county').selectpicker();
                $('.bootstrap-select').on('hidden.bs.dropdown', function () {
                    var selected = []
                    selected = $('.selectpicker').val();
                    var ruleId = $('#rules_county').data("id");
                    updateCounty(selected, ruleId);
                });
            },
            "drawCallback": function () {
                $('.dataTables_paginate > .pagination li').addClass('page-item');
                $('.dataTables_paginate > .pagination a').addClass('page-link');
                $('.dataTables_paginate > .pagination li.previous a, .dataTables_paginate > .pagination li.next a').addClass('rounded');
            },
            "ordering": false,
            "serverSide": true,
            "ajax": {
                url: base_url + "admin/order/rulesManager/get_rules", // json datasource
                type: "post", // method  , by default get
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    if (parseInt(XMLHttpRequest.status) == 419) {
                        alert("You are logged out. Please login.");
                    }
                    if (parseInt(XMLHttpRequest.status) == 419) {
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                    }
                    $("#tbl-rules-manager tbody").append('<tr><td colspan="12" class="text-center">No records found</td></tr>');
                    $("#tbl-rules-manager_processing").css("display", "none");

                }
            },

        });
    }
    /* Rules listing */

    if ($('#tbl-safewire-orders-listing').length) {
        safewire_orders_list = $('#tbl-safewire-orders-listing').DataTable({
            /*"pageLength": 2,*/
            "paging": true,
            "lengthMenu": [10, 20, 50, 100, 200, 500, 1000],
            "columnDefs": [
                { "searchable": false, "targets": [0, 1, 2] }
            ],
            "language": {
                // searchPlaceholder: "Customer Number",
                paginate: {
                    next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                    previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                },
                "emptyTable": "Record(s) not found.",
            },
            initComplete: function () {
                var $buttons = jQuery('.dt-buttons').hide();
                jQuery('#export_safewire_orders').on('click', function () {
                    var export_type = jQuery(this).attr('data-export-type');
                    if (export_type) {
                        var btnClass = '.buttons-' + export_type;
                    }
                    if (btnClass) $buttons.find(btnClass).click();
                })
            },
            dom: 'Blfrtip',
            buttons: [
                {
                    extend: 'csvHtml5',
                    text: 'Export',
                    title: 'Safewire Orders',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4],
                        format: {
                            body: function (data, row, column, node) {
                                return (column === 0 || column === 1 || column === 2 || column === 3 || column === 4) ?
                                    data.toString().replace(/[$,]/g, '') :
                                    data;
                            }
                        }
                    }
                },
            ],
            "drawCallback": function () {
                $('.dataTables_paginate > .pagination li').addClass('page-item');
                $('.dataTables_paginate > .pagination a').addClass('page-link');
                $('.dataTables_paginate > .pagination li.previous a, .dataTables_paginate > .pagination li.next a').addClass('rounded');
            },
            "ordering": false,
            "serverSide": true,
            "ajax": {
                url: base_url + "admin/order/order/get_safewire_orders_list", // json datasource
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
                    $("#tbl-safewire-orders-listing tbody").append('<tr><td colspan="5" class="text-center">No records found</td></tr>');
                    $("#tbl-safewire-orders-listing_processing").css("display", "none");

                }
            },
        });
    }

    if ($('#tbl-notifications-listing').length) {
        notifications_list = $('#tbl-notifications-listing').DataTable({
            /*"pageLength": 2,*/
            "paging": true,
            "lengthMenu": [10, 20, 50, 100, 200, 500, 1000],
            "columnDefs": [
                { "searchable": false, "targets": [0, 1, 2] }
            ],
            "language": {
                searchPlaceholder: "Search #Name",
                paginate: {
                    next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                    previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                },
                "emptyTable": "Record(s) not found.",
            },
            initComplete: function () {
                var $buttons = jQuery('.dt-buttons').hide();
                jQuery('#export_notification').on('click', function () {
                    var export_type = jQuery(this).attr('data-export-type');
                    if (export_type) {
                        var btnClass = '.buttons-' + export_type;
                    }
                    if (btnClass) $buttons.find(btnClass).click();
                })
            },
            dom: 'Blfrtip',
            buttons: [
                {
                    extend: 'csvHtml5',
                    text: 'Export',
                    title: 'Safewire Orders',
                    exportOptions: {
                        columns: [0, 1],
                        format: {
                            body: function (data, row, column, node) {
                                return (column === 0 || column === 1) ?
                                    data.toString().replace(/[$,]/g, '') :
                                    data;
                            }
                        }
                    }
                },
            ],
            "drawCallback": function () {
                $('.dataTables_paginate > .pagination li').addClass('page-item');
                $('.dataTables_paginate > .pagination a').addClass('page-link');
                $('.dataTables_paginate > .pagination li.previous a, .dataTables_paginate > .pagination li.next a').addClass('rounded');
            },
            "ordering": false,
            "serverSide": true,
            "ajax": {
                url: base_url + "admin/order/home/get_notifications_list", // json datasource
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
                    $("#tbl-notifications-listing tbody").append('<tr><td colspan="5" class="text-center">No records found</td></tr>');
                    $("#tbl-notifications-listing_processing").css("display", "none");

                }
            },
        });
    }

    if ($('#tbl-escrow-officers-listing').length) {
        escrow_officers_list = $('#tbl-escrow-officers-listing').DataTable({
            /*"pageLength": 2,*/
            "paging": true,
            "lengthMenu": [10, 20, 50, 100, 200, 500, 1000],
            "columnDefs": [
                { "searchable": false, "targets": [0, 1, 2] }
            ],
            "language": {
                searchPlaceholder: "Search #",
                paginate: {
                    next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                    previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                },
                "emptyTable": "Record(s) not found.",
            },
            initComplete: function () {
                var $buttons = jQuery('.dt-buttons').hide();
                jQuery('#export_customer').on('click', function () {
                    var export_type = jQuery(this).attr('data-export-type');
                    if (export_type) {
                        var btnClass = '.buttons-' + export_type;
                    }
                    if (btnClass) $buttons.find(btnClass).click();
                })
            },
            dom: '<"FilterCredentialListing">lfrtip',
            buttons: [
                {
                    extend: 'csvHtml5',
                    text: 'Export',
                    title: 'Customers',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9],
                        format: {
                            body: function (data, row, column, node) {
                                return (column === 0 || column === 1 || column === 2 || column === 3 || column === 4 || column === 5 || column === 6 || column === 7 || column === 8 || column === 9) ?
                                    data.replace(/[$,]/g, '') :
                                    data;
                            }
                        }
                    }
                },
            ],
            "drawCallback": function () {
                $('.dataTables_paginate > .pagination li').addClass('page-item');
                $('.dataTables_paginate > .pagination a').addClass('page-link');
                $('.dataTables_paginate > .pagination li.previous a, .dataTables_paginate > .pagination li.next a').addClass('rounded');
            },
            "ordering": false,
            "serverSide": true,
            "ajax": {
                url: base_url + "admin/order/home/get_escrow_officers_list", // json datasource
                type: "post",
                beforeSend: function () {
                    $("#page-preloader").show();
                },
                /*data   : function( d ) {
                    d.credentials_check = $('#FilterCredentialListing').val();
                }, */// method  , by default get
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    if (parseInt(XMLHttpRequest.status) == 419) {
                        alert("You are logged out. Please login.");
                    }
                    if (parseInt(XMLHttpRequest.status) == 419) {
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                    }
                    $("#tbl-escrow-officers-listing-listing tbody").append('<tr><td colspan="12" class="text-center">No records found</td></tr>');
                    $("#tbl-escrow-officers-listing-listing_processing").css("display", "none");

                },
                complete: function () {
                    $("#page-preloader").hide();
                }
            },
        });
    }

    if ($('#tbl-payoff-users-listing').length) {
        payoff_user_list = $('#tbl-payoff-users-listing').DataTable({
            /*"pageLength": 2,*/
            "paging": true,
            "lengthMenu": [10, 20, 50, 100, 200, 500, 1000],
            "columnDefs": [
                { "searchable": false, "targets": [0, 1, 2] }
            ],
            "language": {
                searchPlaceholder: "Search #",
                paginate: {
                    next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                    previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                },
                "emptyTable": "Record(s) not found.",
            },
            initComplete: function () {
                // var $buttons = jQuery('.dt-buttons').hide();
                // jQuery('#export_customer').on('click', function () {
                //     var export_type = jQuery(this).attr('data-export-type');
                //     if (export_type) {
                //         var btnClass = '.buttons-' + export_type;
                //     }
                //     if (btnClass) $buttons.find(btnClass).click();
                // })
            },
            dom: '<"FilterCredentialListing">lfrtip',

            "drawCallback": function () {
                $('.dataTables_paginate > .pagination li').addClass('page-item');
                $('.dataTables_paginate > .pagination a').addClass('page-link');
                $('.dataTables_paginate > .pagination li.previous a, .dataTables_paginate > .pagination li.next a').addClass('rounded');
            },
            "ordering": false,
            "serverSide": true,
            "ajax": {
                url: base_url + "admin/order/home/get_payoff_users_list", // json datasource
                type: "post",
                beforeSend: function () {
                    $("#page-preloader").show();
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
                    $("#tbl-payoff-users-listing-listing tbody").append('<tr><td colspan="12" class="text-center">No records found</td></tr>');
                    $("#tbl-payoff-users-listing-listing_processing").css("display", "none");

                },
                complete: function () {
                    $("#page-preloader").hide();
                }
            },
        });
    }

    if ($('#tbl-transactees-listing').length) {
        transactee_list = $('#tbl-transactees-listing').DataTable({
            /*"pageLength": 2,*/
            "paging": true,
            "lengthMenu": [10, 20, 50, 100, 200, 500, 1000],
            "columnDefs": [
                { "searchable": false, "targets": [0, 1, 2] }
            ],
            "language": {
                searchPlaceholder: "Search #",
                paginate: {
                    next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                    previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                },
                "emptyTable": "Record(s) not found.",
            },
            initComplete: function () {
                // var $buttons = jQuery('.dt-buttons').hide();
                // jQuery('#export_customer').on('click', function () {
                //     var export_type = jQuery(this).attr('data-export-type');
                //     if (export_type) {
                //         var btnClass = '.buttons-' + export_type;
                //     }
                //     if (btnClass) $buttons.find(btnClass).click();
                // })
            },
            dom: '<"FilterTransacteeListing">lfrtip',

            "drawCallback": function () {
                $('.dataTables_paginate > .pagination li').addClass('page-item');
                $('.dataTables_paginate > .pagination a').addClass('page-link');
                $('.dataTables_paginate > .pagination li.previous a, .dataTables_paginate > .pagination li.next a').addClass('rounded');
            },
            "ordering": false,
            "serverSide": true,
            "ajax": {
                url: base_url + "order/admin/get-transactee-list", // json datasource
                type: "post",
                beforeSend: function () {
                    $("#page-preloader").show();
                },
                data: function (d) {
                    d.user_id = $('#FilterTransacteeListing').val();
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
                    $("#tbl-transactees-listing-listing tbody").append('<tr><td colspan="12" class="text-center">No records found</td></tr>');
                    $("#tbl-transactees-listing-listing_processing").css("display", "none");
                },
                complete: function () {
                    $("#page-preloader").hide();
                }
            },
        });

        if (payoff_user_list) {
            var obj = jQuery.parseJSON(payoff_user_list);
            var options = '';
            $.each(obj, function (key, value) {
                options += '<option value="' + value.id + '">' + value.first_name + ' ' + value.last_name + '</option>'
            });
            $("div.FilterTransacteeListing").html('<div class="col-sm-12" style="display: flex; justify-content: flex-end;"><label> User List: <select style="width:auto;" name="FilterTransacteeListing" id="FilterTransacteeListing" class="custom-select custom-select-sm form-control form-control-sm"> <option value="" > All </option>"' + options + '"</select></label></div>');
        }

    }

    $("#FilterTransacteeListing").on("change", function () {
        transactee_list.ajax.reload();
    });

    if ($('#frm-add-sales-rep #accordionEx.accordion').length) {
        var collapse_class_id = $(".form-group .error").closest(".collapse").attr('id');
        $('#' + collapse_class_id).collapse('show');
    }
    if ($('.cusom__common__datatable').length) {
        $('.cusom__common__datatable').DataTable({
            "language": {
                searchPlaceholder: "Search #",
                paginate: {
                    next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                    previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                },
                "emptyTable": "Record(s) not found.",
            },
        });
    }

    $('.delete-record-custom').click(function () {
        var call_url = $(this).data('url');
        if (call_url != '') {
            confirm_msg = confirm('Are you sure to want to delete this record?')
            if (confirm_msg) {

                $.ajax({
                    url: call_url,
                    method: "POST",
                    data: { action: 'delete' },
                    success: function (data) {
                        location.reload();
                    },
                    error: function (XMLHttpRequest, textStatus, errorThrown) {
                        location.reload();
                    }
                });
            }
        }
    });

    if ($('.show_hide_commissiontypes_select').length) {
        show_hide_commission_div();
        $('.show_hide_commissiontypes_select').on('change', function () {
            show_hide_commission_div();
        });

        $('.threshold-add-btn').click(function () {
            var clone_div = $(this).parents('.threshold__amounts').find('.clone-main-div').clone();
            clone_div.find('input').val("");
            clone_div.removeClass('clone-main-div');
            $(this).parents('.threshold__amounts').find('.clone-to-threshold').append(clone_div);

        })
    }
    if ($('.show_hide_underwriter_tier_select').length) {
        show_hide_underwriter_tier_div();
        $('.show_hide_underwriter_tier_select').on('change', function () {
            show_hide_underwriter_tier_div();
        });
    }

    if ($('select.filter__commission_range_tier').length) {
        $('select.filter__commission_range_tier').on('change', function () {
            var prod_type = $('#filter__commission_range_type').val();
            var tier = $(this).val();
            var redirect_url = $(this).attr('data-url');
            window.location = redirect_url + '/' + prod_type + '/' + tier;


        });
        $('select#filter__commission_range_type').on('change', function () {
            var select_val = $(this).val();
            $('.show_hide_underwriter_tier-' + select_val + ' select.filter__commission_range_tier').val(0).trigger('change')
        });
    }

    if ($('select.filter-commission-files').length) {
        $('select.filter-commission-files').on('change', function () {
            var comm_year = $('#commission-file-year-filter').val();
            var comm_month = $('#commission-file-month-filter').val();
            var redirect_url = $(this).attr('data-url');
            window.location = redirect_url + '/' + comm_year + '/' + comm_month;


        });
    }
    if ($('#add-edit-admin-form').length) {
        $('#add-edit-admin-form').validate({
            rules: {
                email_id: {
                    required: true,
                    email: true,
                    remote: {
                        depends: function (element) {
                            return $("#formId").val() == "";
                        },
                        param: {
                            url: base_url + "order/admin/admin_users_email",
                            type: "post"

                        }
                    }
                },
                first_name: "required",
                last_name: "required",
                password: {
                    required: "#password-edit:visible",
                    minlength: 6
                },
                confirm_password: {
                    equalTo: "#admin_password"
                },
                role_id: "required",
            },
            messages: {
                email_id: {
                    remote: "Email already in use!"
                }
            },
            submitHandler: function (form) {
                form.submit();

            }
        });

        $('#addAdminModal').on('hidden.bs.modal', function () {
            $('#add-edit-admin-form').trigger("reset");
            $('#password-edit').show();
            $("#password-check").hide();
            $('#email_id').attr("readonly", false);
            $('#formId').val("");
            $("#add-edit-admin-form label.error").remove();
            $("#add-edit-admin-form.error").removeClass("error");
        });

        $('#password_update').on('change', function () {
            if ($(this).is(":checked")) {
                $('#password-edit').show();
            }
            else {
                $('#password-edit').hide();
            }
        });


    }
    if ($('#add-edit-role-form').length) {
        $('#addRoleModal').on('hidden.bs.modal', function () {
            $('#add-edit-role-form').trigger("reset");
            $('#formId').val("");
            $("#add-edit-role-form label.error").remove();
            $("#add-edit-role-form.error").removeClass("error");
        });

        $('#add-edit-role-form').validate({
            rules: {

                title: "required",
            },
            submitHandler: function (form) {
                form.submit();

            }
        });
    }

    if ($('#holiday_date').length) {
        $('#holiday_date').datepicker().datepicker("setDate", new Date());
    }

    if ($('#instrument-file-upload-form #recorded_date').length > 0) {
        $('#instrument-file-upload-form #recorded_date').datepicker({
            changeMonth: true,
            changeYear: true,
            maxDate: "0",
            yearRange: "-120:+0",
        });
    }
});

if (('.threshold-remove-btn').length) {
    $(document).on('click', '.threshold-remove-btn', function () {
        $(this).parents('.clone-this-threshold').remove();
    })
}

function show_hide_commission_div() {
    $('.show_hide_commissiontypes_select').each(function () {
        $(this).parents('.underwriters-div').find('.show_hide_commissiontypes').hide();
        var select_comission_type_val = $(this).val();
        var shown_class = '.show_hide_commissiontypes-' + select_comission_type_val;
        $(this).parents('.underwriters-div').find(shown_class).show();

    });
}
function show_hide_underwriter_tier_div() {

    $('.show_hide_underwriter_tier').hide();
    var select_comission_type_val = $('.selectpicker.show_hide_underwriter_tier_select').val();
    var shown_class = '.show_hide_underwriter_tier-' + select_comission_type_val;
    $(shown_class).show();


}

function updateCounty(counties, ruleId) {
    if (counties == '') {
        alert('Please select county');
        return false;
    }
    else {
        var list = JSON.stringify(counties);
        $.ajax({
            url: base_url + "admin/order/rulesManager/updateCounties",
            method: "POST",
            data: { counties: counties, rule_id: ruleId },
            success: function (data) {
                var result = jQuery.parseJSON(data);
                if (result.status == 'success') {
                    $('#rules_success_msg').html(result.message).show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#rules_success_msg").offset().top
                    }, 1000);
                    // rules_list.ajax.reload( null, false );
                    setTimeout(function () {
                        $('#rules_success_msg').html('').hide();
                    }, 4000);
                } else {
                    $('#rules_error_msg').html(result.message).show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#rules_error_msg").offset().top
                    }, 1000);

                    setTimeout(function () {
                        $('#rules_error_msg').html('').hide();
                    }, 4000);
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                $('#rules_error_msg').html('Something went wrong. Please try it again.').show();
                $([document.documentElement, document.body]).animate({
                    scrollTop: $("#rules_success_msg").offset().top
                }, 1000);

                setTimeout(function () {
                    $('#rules_error_msg').html('').hide();
                }, 4000);
            }
        })
    }
}

function deleteCustomer(id) {
    if (id == '') {
        alert('Customer ID is required.');
        return false;
    }

    var ready = confirm("Are you sure want to delete?");

    if (ready) {
        $.ajax({
            url: base_url + "admin/order/home/delete_customer",
            method: "POST",
            data: { id: id },
            success: function (data) {
                var result = jQuery.parseJSON(data);
                if (result.status == 'success') {
                    $('#customer_success_msg').html(result.message).show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#customer_success_msg").offset().top
                    }, 1000);
                    customer_list.ajax.reload(null, false);
                    setTimeout(function () {
                        $('#customer_success_msg').html('').hide();
                    }, 4000);
                } else {
                    $('#customer_error_msg').html(result.message).show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#customer_error_msg").offset().top
                    }, 1000);

                    setTimeout(function () {
                        $('#customer_error_msg').html('').hide();
                    }, 4000);
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                $('#customer_error_msg').html('Something went wrong. Please try it again.').show();
                $([document.documentElement, document.body]).animate({
                    scrollTop: $("#customer_success_msg").offset().top
                }, 1000);

                setTimeout(function () {
                    $('#customer_error_msg').html('').hide();
                }, 4000);
            }
        })
    } else {
        return false;
    }
}

function deleteAgent(id) {
    if (id == '') {
        alert('Agent ID is required.');
        return false;
    }

    var ready = confirm("Are you sure want to delete?");

    if (ready) {
        $.ajax({
            url: base_url + "admin/order/agent/delete_agent",
            method: "POST",
            data: { id: id },
            success: function (data) {

                var result = jQuery.parseJSON(data);

                if (result.status == 'success') {
                    $('#agent_success_msg').html(result.message).show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#agent_success_msg").offset().top
                    }, 1000);

                    agent_list.ajax.reload(null, false);
                    setTimeout(function () {
                        $('#agent_success_msg').html('').hide();
                    }, 4000);
                } else {
                    $('#agent_error_msg').html(result.message).show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#agent_error_msg").offset().top
                    }, 1000);

                    setTimeout(function () {
                        $('#agent_error_msg').html('').hide();
                    }, 4000);
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                $('#agent_error_msg').html('Something went wrong. Please try it again.').show();
                $([document.documentElement, document.body]).animate({
                    scrollTop: $("#agent_success_msg").offset().top
                }, 1000);

                setTimeout(function () {
                    $('#agent_error_msg').html('').hide();
                }, 4000);
            }
        })
    } else {
        return false;
    }
}

function deleteSalesRep(id) {
    if (id == '') {
        alert('Sales Rep. ID is required.');
        return false;
    }

    var ready = confirm("Are you sure want to delete?");

    if (ready) {
        $.ajax({
            url: base_url + "admin/order/sales/delete_sales_rep",
            method: "POST",
            data: {
                id: id
            },
            success: function (data) {
                var result = jQuery.parseJSON(data);
                if (result.status == 'success') {
                    $('#sales_rep_success_msg').html(result.message).show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#sales_rep_success_msg").offset().top
                    }, 1000);
                    sales_rep_list.ajax.reload(null, false);
                    setTimeout(function () {
                        $('#sales_rep_success_msg').html('').hide();
                    }, 4000);
                } else {
                    $('#sales_rep_error_msg').html(result.message).show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#sales_rep_error_msg").offset().top
                    }, 1000);

                    setTimeout(function () {
                        $('#sales_rep_error_msg').html('').hide();
                    }, 4000);
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                $('#sales_rep_error_msg').html('Something went wrong. Please try it again.').show();
                $([document.documentElement, document.body]).animate({
                    scrollTop: $("#sales_rep_success_msg").offset().top
                }, 1000);

                setTimeout(function () {
                    $('#sales_rep_error_msg').html('').hide();
                }, 4000);
            }
        })
    } else {
        return false;
    }
}

function deleteTitleOfficer(id) {
    if (id == '') {
        alert('Title Officer ID is required.');
        return false;
    }

    var ready = confirm("Are you sure want to delete?");

    if (ready) {
        $.ajax({
            url: base_url + "admin/order/title/delete_title_officer",
            method: "POST",
            data: {
                id: id
            },
            success: function (data) {
                var result = jQuery.parseJSON(data);
                if (result.status == 'success') {
                    $('#title_officer_success_msg').html(result.message).show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#title_officer_success_msg").offset().top
                    }, 1000);
                    title_officer_list.ajax.reload(null, false);
                    setTimeout(function () {
                        $('#title_officer_success_msg').html('').hide();
                    }, 4000);
                } else {
                    $('#title_officer_error_msg').html(result.message).show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#title_officer_error_msg").offset().top
                    }, 1000);

                    setTimeout(function () {
                        $('#title_officer_error_msg').html('').hide();
                    }, 4000);
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                $('#title_officer_error_msg').html('Something went wrong. Please try it again.').show();
                $([document.documentElement, document.body]).animate({
                    scrollTop: $("#title_officer_success_msg").offset().top
                }, 1000);

                setTimeout(function () {
                    $('#title_officer_error_msg').html('').hide();
                }, 4000);
            }
        })
    } else {
        return false;
    }
}

function deletePayoffUser(id) {
    if (id == '') {
        alert('Payoff User ID is required.');
        return false;
    }

    var ready = confirm("Are you sure want to delete?");

    if (ready) {
        $.ajax({
            url: base_url + "order/admin/delete-payoff-user",
            method: "POST",
            data: {
                id: id,
                status: 0
            },
            success: function (data) {
                var result = jQuery.parseJSON(data);
                if (result.status == 'success') {
                    $('#payoff_user_success_msg').html(result.message).show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#payoff_user_success_msg").offset().top
                    }, 1000);
                    payoff_user_list.ajax.reload(null, false);
                    setTimeout(function () {
                        $('#payoff_user_success_msg').html('').hide();
                    }, 4000);
                } else {
                    $('#payoff_user_error_msg').html(result.message).show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#payoff_user_error_msg").offset().top
                    }, 1000);

                    setTimeout(function () {
                        $('#payoff_user_error_msg').html('').hide();
                    }, 4000);
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                $('#payoff_user_error_msg').html('Something went wrong. Please try it again.').show();
                $([document.documentElement, document.body]).animate({
                    scrollTop: $("#payoff_user_success_msg").offset().top
                }, 1000);

                setTimeout(function () {
                    $('#payoff_user_error_msg').html('').hide();
                }, 4000);
            }
        })
    } else {
        return false;
    }
}

function download(filename, text) {
    if (navigator.msSaveBlob) {
        var csvData = base64toBlob(text, 'application/octet-stream');
        var csvURL = navigator.msSaveBlob(csvData, filename);
        var element = document.createElement('a');
        element.setAttribute('href', csvURL);
        element.setAttribute('download', filename);

        element.style.display = 'none';
        document.body.appendChild(element);

        document.body.removeChild(element);
    }
    else {
        var csvURL = 'data:application/octet-stream;base64,' + text;
        var element = document.createElement('a');
        element.setAttribute('href', csvURL);
        element.setAttribute('download', filename);

        element.style.display = 'none';
        document.body.appendChild(element);
        element.click();
        document.body.removeChild(element);
    }
}

function exportOrders() {
    var sales_rep = $('#FilterOrderListing').val();
    var seachValue = $('.dataTables_filter input').val();

    $.ajax({
        url: base_url + "admin/order/order/export_orders",
        method: "POST",
        data: { sales_rep: sales_rep, seachValue: seachValue },
        success: function (data) {
            if (data.status == 'success') {
                download('orders.csv', data.data);
            }
            else {
                $('#order_error_msg').html(result.message).show();
                $([document.documentElement, document.body]).animate({
                    scrollTop: $("#order_success_msg").offset().top
                }, 1000);

                setTimeout(function () {
                    $('#order_error_msg').html('').hide();
                }, 4000);
            }

        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            $('#order_error_msg').html('Something went wrong. Please try it again.').show();
            $([document.documentElement, document.body]).animate({
                scrollTop: $("#customer_success_msg").offset().top
            }, 1000);

            setTimeout(function () {
                $('#order_error_msg').html('').hide();
            }, 4000);
        }
    });
}

function exportSalesRepReports() {
    let month = $('#sales-rep-csv-report #select_month').val();
    let year = $('#sales-rep-csv-report #select_year').val();
    if (!month || !year) {
        alert("Please specify month and year to generate report");
        return;
    }
    d = {};
    d.month = month;
    d.year = year;
    $.ajax({
        url: base_url + "order/admin/export_sales_rep_reports",
        method: "POST",
        data: d,
        success: function (data) {
            if (data.status == 'success') {
                download('sales_rep_report.csv', data.data);
                $("#sales_report_msg").html('Report downloaded successfully').show();
                setTimeout(function () {
                    $('#sales_report_msg').html('').hide();
                    $('#generateSalesReportModel').modal('hide');
                }, 4000);
                return;
            }
            else {
                $('#sales_report_err_msg').html(data.data).show();
                $([document.documentElement, document.body]).animate({
                    scrollTop: $("#sales_report_msg").offset().top
                }, 1000);

                setTimeout(function () {
                    $('#sales_report_err_msg').html('').hide();
                }, 4000);
            }

        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            alert('Something went wrong. Please try it again.');
            $('#sales_report_err_msg').html('Something went wrong. Please try it again.').show();
            $([document.documentElement, document.body]).animate({
                scrollTop: $("#customer_success_msg").offset().top
            }, 1000);

            setTimeout(function () {
                $('#sales_report_err_msg').html('').hide();
            }, 4000);
        }
    });
}

function exportLPOrders() {
    var sales_rep = $('#FilterLpOrderListing').val();
    var seachValue = $('.dataTables_filter input').val();
    d = {};
    d.sales_rep = sales_rep;
    d.seachValue = seachValue;
    d.start_date = $('#FilterLpStartDate').val();
    d.end_date = $('#FilterLpEndDate').val();
    $.ajax({
        url: base_url + "order/admin/export_lp_orders",
        method: "POST",
        data: d,
        success: function (data) {
            if (data.status == 'success') {
                download('lp-orders.csv', data.data);
            }
            else {
                $('#lp_order_error_msg').html(result.message).show();
                $([document.documentElement, document.body]).animate({
                    scrollTop: $("#lp_order_success_msg").offset().top
                }, 1000);

                setTimeout(function () {
                    $('#lp_order_error_msg').html('').hide();
                }, 4000);
            }

        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            $('#lp_order_error_msg').html('Something went wrong. Please try it again.').show();
            $([document.documentElement, document.body]).animate({
                scrollTop: $("#lp_order_success_msg").offset().top
            }, 1000);

            setTimeout(function () {
                $('#lp_order_error_msg').html('').hide();
            }, 4000);
        }
    });
}

function deleteMasterUser(id) {
    if (id == '') {
        alert('Customer ID is required.');
        return false;
    }

    var ready = confirm("Are you sure want to delete?");

    if (ready) {
        $.ajax({
            url: base_url + "admin/order/home/delete_customer",
            method: "POST",
            data: { id: id },
            success: function (data) {
                var result = jQuery.parseJSON(data);
                if (result.status == 'success') {
                    $('#master_users_success_msg').html('Master User deleted successfully').show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#master_users_success_msg").offset().top
                    }, 1000);
                    customer_list.ajax.reload(null, false);
                    setTimeout(function () {
                        $('#master_users_success_msg').html('').hide();
                    }, 4000);
                } else {
                    $('#master_users_error_msg').html(result.message).show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#master_users_error_msg").offset().top
                    }, 1000);

                    setTimeout(function () {
                        $('#master_users_error_msg').html('').hide();
                    }, 4000);
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                $('#master_users_error_msg').html('Something went wrong. Please try it again.').show();
                $([document.documentElement, document.body]).animate({
                    scrollTop: $("#customer_success_msg").offset().top
                }, 1000);

                setTimeout(function () {
                    $('#master_users_error_msg').html('').hide();
                }, 4000);
            }
        })
    } else {
        return false;
    }
}

function deleteDailyReceiver(id) {
    if (id == '') {
        alert('Invalid attempt.');
        return false;
    }

    var ready = confirm("Are you sure want to delete?");

    if (ready) {
        $.ajax({
            url: base_url + "admin/order/home/deleteDailyEmailerReceiver",
            method: "POST",
            data: { id: id },
            success: function (data) {
                var result = jQuery.parseJSON(data);
                if (result.status == 'success') {
                    $('#tbl-daily-email-control_success_msg').html('Dailt email receiver deleted successfully').show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#tbl-daily-email-control_success_msg").offset().top
                    }, 1000);
                    daily_email_receiver_list.ajax.reload(null, false);
                    setTimeout(function () {
                        $('#tbl-daily-email-control_success_msg').html('').hide();
                    }, 4000);
                } else {
                    $('#tbl-daily-email-control_error_msg').html(result.message).show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#tbl-daily-email-control_error_msg").offset().top
                    }, 1000);

                    setTimeout(function () {
                        $('#tbl-daily-email-control_error_msg').html('').hide();
                    }, 4000);
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                $('#tbl-daily-email-control_error_msg').html('Something went wrong. Please try it again.').show();
                $([document.documentElement, document.body]).animate({
                    scrollTop: $("#tbl-daily-email-control_success_msg").offset().top
                }, 1000);

                setTimeout(function () {
                    $('#tbl-daily-email-control_error_msg').html('').hide();
                }, 4000);
            }
        })
    } else {
        return false;
    }
}

function makePrimary(i) {
    var id = $("input[name='email_address_" + i + "']:checked").data('id');
    var email = $("input[name='email_address_" + i + "']:checked").val();

    if (id == '') {
        alert('Customer ID is required.');
        return false;
    }

    var ready = confirm("Are you sure want to make account primary?");

    if (ready) {
        $.ajax({
            url: base_url + "admin/order/home/make_customer_primary",
            method: "POST",
            data: {
                id: id,
                email: email
            },
            success: function (data) {
                var result = jQuery.parseJSON(data);
                if (result.status == 'success') {
                    $('#customer_success_msg').html(result.message).show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#customer_success_msg").offset().top
                    }, 1000);
                    setTimeout(function () {
                        location.reload();
                    }, 5000);
                } else {
                    $('#customer_error_msg').html(result.message).show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#customer_error_msg").offset().top
                    }, 1000);
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                $('#customer_error_msg').html('Something went wrong. Please try it again.').show();
                $([document.documentElement, document.body]).animate({
                    scrollTop: $("#customer_success_msg").offset().top
                }, 1000);

                setTimeout(function () {
                    $('#customer_error_msg').html('').hide();
                }, 4000);
            }
        })
    } else {
        return false;
    }
}

function resetPassword(id) {
    if (id == '') {
        alert('Customer ID is required.');
        return false;
    }

    var ready = confirm("Are you sure want to reset password?");

    if (ready) {
        $.ajax({
            url: base_url + "admin/order/home/reset_user_password",
            method: "POST",
            data: {
                id: id
            },
            success: function (data) {
                var result = jQuery.parseJSON(data);
                if (result.status == 'success') {
                    $('#customer_success_msg').html(result.message).show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#customer_success_msg").offset().top
                    }, 1000);
                    setTimeout(function () {
                        incorrect_customer_list.ajax.reload();
                    }, 5000);
                } else {
                    $('#customer_error_msg').html(result.message).show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#customer_error_msg").offset().top
                    }, 1000);
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                $('#customer_error_msg').html('Something went wrong. Please try it again.').show();
                $([document.documentElement, document.body]).animate({
                    scrollTop: $("#customer_success_msg").offset().top
                }, 1000);

                setTimeout(function () {
                    $('#customer_error_msg').html('').hide();
                }, 4000);
            }
        })
    } else {
        return false;
    }
}

function deleteFees(id) {
    if (id == '') {
        alert('Fee ID is required.');
        return false;
    }
    var ready = confirm("Are you sure want to delete?");
    if (ready) {
        $.ajax({
            url: base_url + "admin/order/fees/delete_fees",
            type: "POST",
            data: { id: id },
            success: function (data) {

                var result = jQuery.parseJSON(data);

                if (result.status == 'success') {
                    $('#fees_success_msg').html(result.message).show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#fees_success_msg").offset().top
                    }, 1000);

                    fees_list.ajax.reload(null, false);
                    setTimeout(function () {
                        $('#fees_success_msg').html('').hide();
                    }, 4000);
                } else {
                    $('#fees_error_msg').html(result.message).show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#fees_error_msg").offset().top
                    }, 1000);

                    setTimeout(function () {
                        $('#fees_error_msg').html('').hide();
                    }, 4000);
                }
            },
            error: function (xhr, err) {
                alert('Connection Problem !!');
                return false;
            }
        });
    }
    else {
        return false;
    }
}

function deleteHoliday(id) {
    if (id == '') {
        alert('Holiday ID is required.');
        return false;
    }

    var ready = confirm("Are you sure want to delete?");
    if (ready) {
        $.ajax({
            url: base_url + "admin/order/holidays/delete_holiday",
            type: "POST",
            data: {
                id: id
            },
            success: function (data) {
                var result = jQuery.parseJSON(data);
                if (result.status == 'success') {
                    $('#holidays_success_msg').html(result.message).show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#holidays_success_msg").offset().top
                    }, 1000);

                    holidays_list.ajax.reload(null, false);
                    setTimeout(function () {
                        $('#holidays_success_msg').html('').hide();
                    }, 4000);
                } else {
                    $('#holidays_error_msg').html(result.message).show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#holidays_error_msg").offset().top
                    }, 1000);

                    setTimeout(function () {
                        $('#holidays_error_msg').html('').hide();
                    }, 4000);
                }
            },
            error: function (xhr, err) {
                alert('Connection Problem !!');
                return false;
            }
        });
    } else {
        return false;
    }
}

function updateUnderwriter(partner_id, underwriter_type, underwriter) {
    $('body').animate({ opacity: 0.5 }, "slow");
    $.ajax({
        url: base_url + "order/admin/update-underwriter",
        method: "POST",
        data: {
            partner_id: partner_id,
            underwriter_type: underwriter_type,
            underwriter: underwriter
        },
        success: function (data) {
            var result = jQuery.parseJSON(data);
            if (result.status == 'success') {
                $('body').animate({ opacity: 1.0 }, "slow");
                $('#companies_success_msg').html(result.msg).show();
                $([document.documentElement, document.body]).animate({
                    scrollTop: $("#companies_success_msg").offset().top
                }, 1000);
                companies_list.ajax.reload(null, false);
                setTimeout(function () {
                    $('#companies_success_msg').html('').hide();
                }, 4000);
            } else {
                $('#companies_error_msg').html(result.message).show();
                $([document.documentElement, document.body]).animate({
                    scrollTop: $("#companies_error_msg").offset().top
                }, 1000);

                setTimeout(function () {
                    $('#companies_error_msg').html('').hide();
                }, 4000);
            }
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            $('#companies_error_msg').html('Something went wrong. Please try it again.').show();
            $([document.documentElement, document.body]).animate({
                scrollTop: $("#companies_success_msg").offset().top
            }, 1000);

            setTimeout(function () {
                $('#companies_error_msg').html('').hide();
            }, 4000);
        }
    });
}

function updateTitleSalesUser(partner_id, id, user_type) {

    $('body').animate({ opacity: 0.5 }, "slow");
    $.ajax({
        url: base_url + "order/admin/update-title-sales-company",
        method: "POST",
        data: {
            partner_id: partner_id,
            user_id: id,
            user_type: user_type
        },
        success: function (data) {
            var result = jQuery.parseJSON(data);
            if (result.status == 'success') {
                $('body').animate({ opacity: 1.0 }, "slow");
                $('#companies_success_msg').html(result.msg).show();
                $([document.documentElement, document.body]).animate({
                    scrollTop: $("#companies_success_msg").offset().top
                }, 1000);
                companies_list.ajax.reload(null, false);
                setTimeout(function () {
                    $('#companies_success_msg').html('').hide();
                }, 4000);
            } else {
                $('#companies_error_msg').html(result.msg).show();
                $([document.documentElement, document.body]).animate({
                    scrollTop: $("#companies_error_msg").offset().top
                }, 1000);
                $('body').animate({ opacity: 1.0 }, "slow");
                setTimeout(function () {
                    $('#companies_error_msg').html('').hide();
                }, 4000);
            }
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            $('#companies_error_msg').html('Something went wrong. Please try it again.').show();
            $([document.documentElement, document.body]).animate({
                scrollTop: $("#companies_success_msg").offset().top
            }, 1000);

            setTimeout(function () {
                $('#companies_error_msg').html('').hide();
            }, 4000);
        }
    });
}

function updateSalesUserForOrder(transaction_id, id) {

    $('body').animate({ opacity: 0.5 }, "slow");
    $.ajax({
        url: base_url + "order/admin/update-sales-rep-order",
        method: "POST",
        data: {
            transaction_id: transaction_id,
            user_id: id
        },
        success: function (data) {
            var result = jQuery.parseJSON(data);
            if (result.status == 'success') {
                $('body').animate({ opacity: 1.0 }, "slow");
                $('#order_success_msg').html(result.msg).show();
                $([document.documentElement, document.body]).animate({
                    scrollTop: $("#order_success_msg").offset().top
                }, 1000);
                order_list.ajax.reload(null, false);
                setTimeout(function () {
                    $('#order_success_msg').html('').hide();
                }, 4000);
            } else {
                $('#order_error_msg').html(result.msg).show();
                $([document.documentElement, document.body]).animate({
                    scrollTop: $("#order_error_msg").offset().top
                }, 1000);
                $('body').animate({ opacity: 1.0 }, "slow");
                setTimeout(function () {
                    $('#order_error_msg').html('').hide();
                }, 4000);
            }
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            $('#order_error_msg').html('Something went wrong. Please try it again.').show();
            $([document.documentElement, document.body]).animate({
                scrollTop: $("#order_success_msg").offset().top
            }, 1000);

            setTimeout(function () {
                $('#order_error_msg').html('').hide();
            }, 4000);
        }
    });
}

function deleteFeesType(id) {
    if (id == '') {
        alert('Fee type ID is required.');
        return false;
    }
    var ready = confirm("Are you sure want to delete?");
    if (ready) {
        $.ajax({
            url: base_url + "admin/order/feesTypes/delete_fee_type",
            type: "POST",
            data: { id: id },
            success: function (data) {

                var result = jQuery.parseJSON(data);

                if (result.status == 'success') {
                    $('#fees_success_msg').html(result.message).show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#fees_success_msg").offset().top
                    }, 1000);

                    fees_type_list.ajax.reload(null, false);
                    setTimeout(function () {
                        $('#fees_success_msg').html('').hide();
                    }, 4000);
                } else {
                    $('#fees_error_msg').html(result.message).show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#fees_error_msg").offset().top
                    }, 1000);

                    setTimeout(function () {
                        $('#fees_error_msg').html('').hide();
                    }, 4000);
                }
            },
            error: function (xhr, err) {
                alert('Connection Problem !!');
                return false;
            }
        });
    }
    else {
        return false;
    }
}

function updateType(id, type) {
    $('body').animate({ opacity: 0.5 }, "slow");
    $.ajax({
        url: base_url + "order/admin/update-type",
        method: "POST",
        data: {
            id: id,
            type: type
        },
        success: function (data) {
            var result = jQuery.parseJSON(data);
            if (result.status == 'success') {
                $('body').animate({ opacity: 1.0 }, "slow");
                $('#code_book_success_msg').html(result.msg).show();
                $([document.documentElement, document.body]).animate({
                    scrollTop: $("#code_book_success_msg").offset().top
                }, 1000);
                code_book_list.ajax.reload(null, false);
                setTimeout(function () {
                    $('#code_book_success_msg').html('').hide();
                }, 4000);
            } else {
                $('#code_book_error_msg').html(result.message).show();
                $([document.documentElement, document.body]).animate({
                    scrollTop: $("#code_book_error_msg").offset().top
                }, 1000);

                setTimeout(function () {
                    $('#code_book_error_msg').html('').hide();
                }, 4000);
            }
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            $('#code_book_error_msg').html('Something went wrong. Please try it again.').show();
            $([document.documentElement, document.body]).animate({
                scrollTop: $("#companies_success_msg").offset().top
            }, 1000);

            setTimeout(function () {
                $('#code_book_error_msg').html('').hide();
            }, 4000);
        }
    });
}

function sendPasswordMail(id) {
    if (id == '') {
        alert('Title Officer ID is required.');
        return false;
    }
    var ready = confirm("Are you sure want to send mail for reset password?");
    if (ready) {
        $.ajax({
            url: base_url + "admin/order/home/sendPasswordMail",
            method: "POST",
            data: {
                id: id
            },
            success: function (data) {
                var result = jQuery.parseJSON(data);
                if (result.status == 'success') {
                    $('#password_listing_success_msg').html(result.message).show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#password_listing_success_msg").offset().top
                    }, 1000);
                    password_list.ajax.reload(null, false);
                    setTimeout(function () {
                        $('#password_listing_success_msg').html('').hide();
                    }, 4000);
                } else {
                    $('#password_listing_error_msg').html(result.message).show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#password_listing_error_msg").offset().top
                    }, 1000);

                    setTimeout(function () {
                        $('#password_listing_error_msg').html('').hide();
                    }, 4000);
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                $('#password_listing_error_msg').html('Something went wrong. Please try it again.').show();
                $([document.documentElement, document.body]).animate({
                    scrollTop: $("#password_listing_success_msg").offset().top
                }, 1000);

                setTimeout(function () {
                    $('#password_listing_error_msg').html('').hide();
                }, 4000);
            }
        })
    } else {
        return false;
    }
}

function importOrders(id) {
    if (id == '') {
        alert('Customer ID is required.');
        return false;
    }
    else {
        $('body').animate({ opacity: 0.5 }, "slow");

        $.ajax({
            url: base_url + "import-orders",
            type: "post",
            data: {
                id: id,
                'is_admin': 1,
            },
            success: function (response) {

                var results = JSON.parse(response);
                if (results.status == 'success') {
                    $('body').animate({ opacity: 1.0 }, "slow");
                    $('#customer_success_msg').html(results.msg).show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#customer_success_msg").offset().top
                    }, 1000);
                    setTimeout(function () {
                        $('#customer_success_msg').html('').hide();
                    }, 4000);
                }
                else if (results.status == 'error') {
                    $('body').animate({ opacity: 1.0 }, "slow");
                    $('#customer_error_msg').html(results.msg).show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#customer_error_msg").offset().top
                    }, 1000);
                    setTimeout(function () {
                        $('#customer_error_msg').html('').hide();
                    }, 4000);
                }

            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                $('body').animate({ opacity: 1.0 }, "slow");
                $('#customer_error_msg').html('Something went wrong. Please try it again.').show();
                $([document.documentElement, document.body]).animate({
                    scrollTop: $("#customer_error_msg").offset().top
                }, 1000);

                setTimeout(function () {
                    $('#customer_error_msg').html('').hide();
                }, 4000);
            }
        });


    }

}

function updateTransaction(partner_id, transaction) {
    $('body').animate({ opacity: 0.5 }, "slow");
    $.ajax({
        url: base_url + "order/admin/update-transaction",
        method: "POST",
        data: {
            partner_id: partner_id,
            transaction: transaction
        },
        success: function (data) {
            var result = jQuery.parseJSON(data);
            if (result.status == 'success') {
                $('body').animate({ opacity: 1.0 }, "slow");
                $('#companies_success_msg').html(result.msg).show();
                $([document.documentElement, document.body]).animate({
                    scrollTop: $("#companies_success_msg").offset().top
                }, 1000);
                companies_list.ajax.reload(null, false);
                setTimeout(function () {
                    $('#companies_success_msg').html('').hide();
                }, 4000);
            } else {
                $('#companies_error_msg').html(result.message).show();
                $([document.documentElement, document.body]).animate({
                    scrollTop: $("#companies_error_msg").offset().top
                }, 1000);

                setTimeout(function () {
                    $('#companies_error_msg').html('').hide();
                }, 4000);
            }
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            $('#companies_error_msg').html('Something went wrong. Please try it again.').show();
            $([document.documentElement, document.body]).animate({
                scrollTop: $("#companies_success_msg").offset().top
            }, 1000);

            setTimeout(function () {
                $('#companies_error_msg').html('').hide();
            }, 4000);
        }
    });
}

function removeSalesRepThankYouProfileImg(id) {

    if (id == '') {
        alert('Sales Rep. ID is required.');
        return false;
    }

    var ready = confirm("Are you sure want to remove thank you image?");
    if (ready) {
        $.ajax({
            url: base_url + "admin/order/sales/remove_sales_rep_thank_you",
            method: "POST",
            data: {
                id: id
            },
            success: function (data) {
                var result = jQuery.parseJSON(data);
                if (result.status == 'success') {
                    $('.alert-success').html(result.message).show();
                    /*$([document.documentElement, document.body]).animate({
                        scrollTop: $(".alert-success").offset().top
                    }, 1000);*/
                    // sales_rep_list.ajax.reload( null, false );
                    location.reload();
                    setTimeout(function () {
                        $('.alert-success').html('').hide();
                    }, 4000);
                } else {
                    $('.alert-danger').html(result.message).show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $(".alert-danger").offset().top
                    }, 1000);

                    setTimeout(function () {
                        $('.alert-danger').html('').hide();
                    }, 4000);
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                $('.alert-danger').html('Something went wrong. Please try it again.').show();
                $([document.documentElement, document.body]).animate({
                    scrollTop: $(".alert-danger").offset().top
                }, 1000);

                setTimeout(function () {
                    $('.alert-danger').html('').hide();
                }, 4000);
            }
        })
    } else {
        return false;
    }
}

function removeSalesRepProfileImg(id) {

    if (id == '') {
        alert('Sales Rep. ID is required.');
        return false;
    }

    var ready = confirm("Are you sure want to remove borrower image?");
    if (ready) {
        $.ajax({
            url: base_url + "admin/order/sales/remove_sales_rep",
            method: "POST",
            data: {
                id: id
            },
            success: function (data) {
                var result = jQuery.parseJSON(data);
                if (result.status == 'success') {
                    $('.alert-success').html(result.message).show();
                    /*$([document.documentElement, document.body]).animate({
                        scrollTop: $(".alert-success").offset().top
                    }, 1000);*/
                    // sales_rep_list.ajax.reload( null, false );
                    location.reload();
                    setTimeout(function () {
                        $('.alert-success').html('').hide();
                    }, 4000);
                } else {
                    $('.alert-danger').html(result.message).show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $(".alert-danger").offset().top
                    }, 1000);

                    setTimeout(function () {
                        $('.alert-danger').html('').hide();
                    }, 4000);
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                $('.alert-danger').html('Something went wrong. Please try it again.').show();
                $([document.documentElement, document.body]).animate({
                    scrollTop: $(".alert-danger").offset().top
                }, 1000);

                setTimeout(function () {
                    $('.alert-danger').html('').hide();
                }, 4000);
            }
        })
    } else {
        return false;
    }
}

function deleteEscrowOfficer(id) {
    if (id == '') {
        alert('Escrow Officer ID is required.');
        return false;
    }

    var ready = confirm("Are you sure want to delete?");

    if (ready) {
        $.ajax({
            url: base_url + "admin/order/home/delete_escrow_officer",
            method: "POST",
            data: { id: id },
            success: function (data) {

                var result = jQuery.parseJSON(data);

                if (result.status == 'success') {
                    $('#escrow_officer_success_msg').html(result.message).show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#escrow_officer_success_msg").offset().top
                    }, 1000);

                    escrow_officers_list.ajax.reload(null, false);
                    setTimeout(function () {
                        $('#escrow_officer_success_msg').html('').hide();
                    }, 4000);
                } else {
                    $('#escrow_officer_error_msg').html(result.message).show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#escrow_officer_error_msg").offset().top
                    }, 1000);

                    setTimeout(function () {
                        $('#escrow_officer_error_msg').html('').hide();
                    }, 4000);
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                $('#escrow_officer_error_msg').html('Something went wrong. Please try it again.').show();
                $([document.documentElement, document.body]).animate({
                    scrollTop: $("#agent_success_msg").offset().top
                }, 1000);

                setTimeout(function () {
                    $('#escrow_officer_error_msg').html('').hide();
                }, 4000);
            }
        })
    } else {
        return false;
    }
}

function deleteForm(id) {
    if (id == '') {
        alert('Form ID is required.');
        return false;
    }
    var ready = confirm("Are you sure want to delete?");
    if (ready) {
        $.ajax({
            url: base_url + "delete-form",
            method: "POST",
            data: {
                id: id
            },
            success: function (data) {
                var result = jQuery.parseJSON(data);
                if (result.status == 'success') {
                    $('#forms_success_msg').html(result.message).show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#forms_success_msg").offset().top
                    }, 1000);

                    forms_list.ajax.reload(null, false);
                    setTimeout(function () {
                        $('#forms_success_msg').html('').hide();
                    }, 4000);
                } else {
                    $('#forms_error_msg').html(result.message).show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#forms_error_msg").offset().top
                    }, 1000);

                    setTimeout(function () {
                        $('#forms_error_msg').html('').hide();
                    }, 4000);
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                $('#forms_error_msg').html('Something went wrong. Please try it again.').show();
                $([document.documentElement, document.body]).animate({
                    scrollTop: $("#forms_success_msg").offset().top
                }, 1000);

                setTimeout(function () {
                    $('#forms_error_msg').html('').hide();
                }, 4000);
            }
        })
    } else {
        return false;
    }
}

function changePassword(id) {
    if (id == '') {
        alert('User ID is required.');
        return false;
    }
    var ready = confirm("Are you sure want to change password?");
    if (ready) {
        $.ajax({
            url: base_url + "admin/order/home/changePassword",
            method: "POST",
            data: {
                id: id
            },
            success: function (data) {
                var result = jQuery.parseJSON(data);
                if (result.status == 'success') {
                    $('#customer_success_msg').html(result.message).show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#customer_success_msg").offset().top
                    }, 1000);

                    credentials_customer_list.ajax.reload(null, false);
                    setTimeout(function () {
                        $('#customer_success_msg').html('').hide();
                    }, 4000);
                } else {
                    $('#customer_error_msg').html(result.message).show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#customer_error_msg").offset().top
                    }, 1000);

                    setTimeout(function () {
                        $('#customer_error_msg').html('').hide();
                    }, 4000);
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                $('#customer_error_msg').html('Something went wrong. Please try it again.').show();
                $([document.documentElement, document.body]).animate({
                    scrollTop: $("#customer_success_msg").offset().top
                }, 1000);

                setTimeout(function () {
                    $('#customer_error_msg').html('').hide();
                }, 4000);
            }
        })
    } else {
        return false;
    }
}
function refreshExipredPasswords() {
    var ready = confirm("Are you sure want to execute password update script? It will take time to update all passwords.");
    if (ready) {
        $.ajax({
            url: base_url + "admin/order/home/refreshExipredPasswords",
            success: function (data) {
                var result = jQuery.parseJSON(data);
                if (result.status == 'success') {
                    $('#refresh_password_success_msg').html(result.message).show();

                    setTimeout(function () {
                        $('#refresh_password_success_msg').html('').hide();
                    }, 4000);
                } else {
                    $('#refresh_password_error_msg').html('Something went wrong. Please try it again.').show();

                    setTimeout(function () {
                        $('#refresh_password_error_msg').html('').hide();
                    }, 4000);
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                $('#refresh_password_error_msg').html('Something went wrong. Please try it again.').show();


                setTimeout(function () {
                    $('#refresh_password_error_msg').html('').hide();
                }, 4000);
            }
        })
    } else {
        return false;
    }
}

function sendDailyProductionReport() {
    var ready = confirm("Are you sure want to send daily production email?");
    if (ready) {
        $('body').animate({ opacity: 0.5 }, "slow");
        $.ajax({
            url: base_url + "admin/order/home/sendDailyProductionReport",
            success: function (data) {
                var result = jQuery.parseJSON(data);
                if (result.status == 'success') {
                    $('body').animate({ opacity: 1.0 }, "slow");
                    $('#manual_report_success_msg').html(result.message).show();
                    setTimeout(function () {
                        $('#manual_report_success_msg').html('').hide();
                    }, 4000);
                } else {
                    $('body').animate({ opacity: 1.0 }, "slow");
                    $('#manual_report_error_msg').html('Something went wrong. Please try it again.').show();
                    setTimeout(function () {
                        $('#manual_report_error_msg').html('').hide();
                    }, 4000);
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                $('body').animate({ opacity: 1.0 }, "slow");
                $('#manual_report_error_msg').html('Something went wrong. Please try it again.').show();
                setTimeout(function () {
                    $('#manual_report_error_msg').html('').hide();
                }, 4000);
            }
        })
    } else {
        return false;
    }
}

function sendLPReports() {
    var ready = confirm("Are you sure want to send LP report stat email?");
    if (ready) {
        $('body').animate({ opacity: 0.5 }, "slow");
        $.ajax({
            url: base_url + "admin/order/home/sendLPReports",
            success: function (data) {
                var result = jQuery.parseJSON(data);
                if (result.status == 'success') {
                    $('body').animate({ opacity: 1.0 }, "slow");
                    $('#manual_report_success_msg').html(result.message).show();
                    setTimeout(function () {
                        $('#manual_report_success_msg').html('').hide();
                    }, 4000);
                } else {
                    $('body').animate({ opacity: 1.0 }, "slow");
                    $('#manual_report_error_msg').html('Something went wrong. Please try it again.').show();
                    setTimeout(function () {
                        $('#manual_report_error_msg').html('').hide();
                    }, 4000);
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                $('body').animate({ opacity: 1.0 }, "slow");
                $('#manual_report_error_msg').html('Something went wrong. Please try it again.').show();
                setTimeout(function () {
                    $('#manual_report_error_msg').html('').hide();
                }, 4000);
            }
        })
    } else {
        return false;
    }
}

function isDualCplUser() {
    $('input[type="checkbox"]').on('change', function () {
        $('body').animate({ opacity: 0.5 }, "slow");
        var user_id = $(this).attr('id');
        if ($(this).is(":checked")) {
            var dualCplUserFlag = 1;
        } else {
            var dualCplUserFlag = 0;
        }
        $.ajax({
            url: base_url + "update-dual-cpl-user",
            method: "POST",
            data: {
                user_id: user_id,
                dualCplUserFlag: dualCplUserFlag
            },
            success: function (data) {
                var result = jQuery.parseJSON(data);
                if (result.status == 'success') {
                    $('body').animate({ opacity: 1.0 }, "slow");
                    $('#customer_success_msg').html(result.msg).show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#customer_success_msg").offset().top
                    }, 1000);
                    customer_list.ajax.reload(null, false);
                    setTimeout(function () {
                        $('#customer_success_msg').html('').hide();
                    }, 4000);
                } else {
                    $('#customer_error_msg').html(result.message).show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#customer_error_msg").offset().top
                    }, 1000);

                    setTimeout(function () {
                        $('#customer_error_msg').html('').hide();
                    }, 4000);
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                $('#customer_error_msg').html('Something went wrong. Please try it again.').show();
                $([document.documentElement, document.body]).animate({
                    scrollTop: $("#customer_success_msg").offset().top
                }, 1000);

                setTimeout(function () {
                    $('#customer_error_msg').html('').hide();
                }, 4000);
            }
        });
    });
}

function deleteDocumentType(id) {
    if (id == '') {
        alert('ID is required.');
        return false;
    }

    var ready = confirm("Are you sure want to delete?");

    if (ready) {
        $.ajax({
            url: base_url + "order/admin/delete-lp-document-type",
            method: "POST",
            data: { id: id },
            success: function (data) {
                var result = jQuery.parseJSON(data);
                if (result.status == 'success') {
                    $('#lp_document_types_success_msg').html(result.message).show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#lp_document_types_success_msg").offset().top
                    }, 1000);
                    lp_document_list.ajax.reload(null, false);
                    setTimeout(function () {
                        $('#lp_document_types_success_msg').html('').hide();
                    }, 4000);
                } else {
                    $('#lp_document_types_error_msg').html(result.message).show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#lp_document_types_error_msg").offset().top
                    }, 1000);

                    setTimeout(function () {
                        $('#lp_document_types_error_msg').html('').hide();
                    }, 4000);
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                $('#lp_document_types_error_msg').html('Something went wrong. Please try it again.').show();
                $([document.documentElement, document.body]).animate({
                    scrollTop: $("#lp_document_types_success_msg").offset().top
                }, 1000);

                setTimeout(function () {
                    $('#lp_document_types_error_msg').html('').hide();
                }, 4000);
            }
        })
    } else {
        return false;
    }
}

function deleteAlert(id) {
    if (id == '') {
        alert('ID is required.');
        return false;
    }

    var ready = confirm("Are you sure want to delete?");

    if (ready) {
        $.ajax({
            url: base_url + "order/admin/delete-lp-alert",
            method: "POST",
            data: { id: id },
            success: function (data) {
                var result = jQuery.parseJSON(data);
                if (result.status == 'success') {
                    $('#lp_alert_success_msg').html(result.message).show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#lp_alert_success_msg").offset().top
                    }, 1000);
                    lp_document_list.ajax.reload(null, false);
                    setTimeout(function () {
                        $('#lp_alert_success_msg').html('').hide();
                    }, 4000);
                } else {
                    $('#lp_alert_error_msg').html(result.message).show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#lp_alert_error_msg").offset().top
                    }, 1000);

                    setTimeout(function () {
                        $('#lp_alert_error_msg').html('').hide();
                    }, 4000);
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                $('#lp_alert_error_msg').html('Something went wrong. Please try it again.').show();
                $([document.documentElement, document.body]).animate({
                    scrollTop: $("#lp_alert_success_msg").offset().top
                }, 1000);

                setTimeout(function () {
                    $('#lp_alert_error_msg').html('').hide();
                }, 4000);
            }
        })
    } else {
        return false;
    }
}

$('#subtype_flag').change(function () {
    if (this.checked) {
        $('.selectpicker').selectpicker('refresh');
        $('.selectpicker').selectpicker('val', '');
        $('.selectsubtype').hide();
    } else {
        // $('.selectsubtype').show();
        $('.selectsubtype').show();
    }
});

function isAllowOnlyReswareOrders() {
    $('input[type="checkbox"]').on('change', function () {
        $('body').animate({ opacity: 0.5 }, "slow");
        var user_id = $(this).attr('id');
        if ($(this).is(":checked")) {
            var allowOnlyReswareOrderFlag = 1;
        } else {
            var allowOnlyReswareOrderFlag = 0;
        }
        $.ajax({
            url: base_url + "update-all-only-resware-order",
            method: "POST",
            data: {
                user_id: user_id,
                allowOnlyReswareOrderFlag: allowOnlyReswareOrderFlag
            },
            success: function (data) {
                var result = jQuery.parseJSON(data);
                if (result.status == 'success') {
                    $('body').animate({ opacity: 1.0 }, "slow");
                    $('#customer_success_msg').html(result.msg).show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#customer_success_msg").offset().top
                    }, 1000);
                    customer_list.ajax.reload(null, false);
                    setTimeout(function () {
                        $('#customer_success_msg').html('').hide();
                    }, 4000);
                } else {
                    $('#customer_error_msg').html(result.message).show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#customer_error_msg").offset().top
                    }, 1000);

                    setTimeout(function () {
                        $('#customer_error_msg').html('').hide();
                    }, 4000);
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                $('#customer_error_msg').html('Something went wrong. Please try it again.').show();
                $([document.documentElement, document.body]).animate({
                    scrollTop: $("#customer_success_msg").offset().top
                }, 1000);

                setTimeout(function () {
                    $('#customer_error_msg').html('').hide();
                }, 4000);
            }
        });
    });
}

function updateTitleOfficerEmailReceiveFlag() {
    $('input[type="checkbox"]').on('change', function () {
        $('body').animate({ opacity: 0.5 }, "slow");
        var title_officer_id = $(this).attr('id');
        if ($(this).is(":checked")) {
            var displayFlag = 1;
        } else {
            var displayFlag = 0;
        }
        $.ajax({
            url: base_url + "update-title-officer-email-receive-flag",
            method: "POST",
            data: {
                title_officer_id: title_officer_id,
                displayFlag: displayFlag
            },
            success: function (data) {
                var result = jQuery.parseJSON(data);
                if (result.status == 'success') {
                    $('body').animate({ opacity: 1.0 }, "slow");
                    $('#title_officer_success_msg').html(result.msg).show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#title_officer_success_msg").offset().top
                    }, 1000);
                    title_officer_list.ajax.reload(null, false);
                    setTimeout(function () {
                        $('#title_officer_success_msg').html('').hide();
                    }, 4000);
                } else {
                    $('#title_officer_error_msg').html(result.message).show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#title_officer_error_msg").offset().top
                    }, 1000);

                    setTimeout(function () {
                        $('#title_officer_error_msg').html('').hide();
                    }, 4000);
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                $('#title_officer_error_msg').html('Something went wrong. Please try it again.').show();
                $([document.documentElement, document.body]).animate({
                    scrollTop: $("#title_officer_success_msg").offset().top
                }, 1000);

                setTimeout(function () {
                    $('#title_officer_error_msg').html('').hide();
                }, 4000);
            }
        });
    });
}