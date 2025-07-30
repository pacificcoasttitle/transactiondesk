jQuery(document).ready(function () {
    if(jQuery('#add-refinance-rates').length || jQuery('#edit-refinance-rates').length )
    {
       jQuery('#add-refinance-rates,#edit-refinance-rates').validate({ 
            rules: {
                county:"required",
                min_price:"required",
                // max_price:"required",
                rate:"required",
            },
            messages: {
                county:"Please select county",
                min_price: "Please enter min price",
                // max_price:"Please enter max price",
                rate: "Please enter rate",
            },
            submitHandler: function(form) {
                form.submit();
            }
        }); 
    }   

    if(jQuery('.alert').length)
    {
        jQuery('.alert').delay(5000).fadeOut(300);
    }

    // $('#dataTable').DataTable();

    if(jQuery('#add-resale-rates').length || jQuery('#edit-resale-rates').length)
    {
       jQuery('#add-resale-rates,#edit-resale-rates').validate({ 
            rules: {
                county:"required",
                min_price:"required",
                /*max_price:"required",
                base_amount:"required",
                per_thousand_price:"required",
                base_rate:"required",
                minimum_rate:"required",*/
            },
            messages: {
                county:"Please select county",
                min_price: "Please enter min price",
                /*max_price:"Please enter max price",
                base_amount: "Please enter base amount",
                per_thousand_price: "Please enter per thousand price",
                base_rate:"Please enter base rate",
                minimum_rate: "Please enter minimum rate",*/
            },
            submitHandler: function(form) {
                form.submit();
            }
        }); 
    }

    if(jQuery('#tbl-escrow-resale-rates').length)
    {
        jQuery('#tbl-escrow-resale-rates').DataTable({
           "pageLength": 20,
           "lengthChange": false,
           "searching": false,
           "language": {
              paginate: {
                next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
            },
            emptyTable: "No rate(s) deatils found..."
          },
          "columnDefs": [
                { "orderable": false, "targets": [1,8] }
            ]
        });
    }

    if(jQuery('#tbl-users-listing').length)
    {
        jQuery('#tbl-users-listing').DataTable({
           "pageLength": 20,
           "lengthChange": false,
           "searching": false,
           "language": {
              paginate: {
                next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
            },
            emptyTable: "No rate(s) deatils found..."
          },
          "columnDefs": [
                { "orderable": false, "targets": [1,2,3,4] }
            ]
        });
    }


    // Add Fee form validation

    if(jQuery('#frm-add-fee').length || jQuery('#frm-edit-fee').length)
    {
       jQuery('#frm-add-fee,#frm-edit-fee').validate({ 
            rules: {
                txn_type:"required",
                section:"required",
                fee_name:"required",
                fee_value:"required"
            },
            messages: {
                txn_type:"Please select Transaction Type",
                section: "Please select Fee Type",
                fee_name:"Please enter fee name",
                fee_value: "Please enter fee value"
            },
            submitHandler: function(form) {
                form.submit();
            }
        }); 
    }

    if(jQuery('#tbl-fees').length)
    {
        jQuery('#tbl-fees').DataTable({
            "pageLength": 20,
            "lengthChange": false,
            "searching": false,
            "language": {
                paginate: {
                   next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                   previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                }
            },
            // "ordering": false,
            "columnDefs": [
                { "orderable": false, "targets": [2,5] }
            ]
        });
    }

    if(jQuery('#importFrm').length)
    {
       jQuery('#importFrm').validate({ 
            rules: {
                file:"required"
            },
            messages: {
                file:"Please upload file to import"
            },
            submitHandler: function(form) {
                form.submit();
            }
        }); 
    }

    if(jQuery('#tbl-title-rates').length)
    {
        jQuery('#tbl-title-rates').DataTable({
           "pageLength": 20,
           "lengthChange": false,
           "searching": false,
           "language": {
                paginate: {
                    next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                    previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                },
                emptyTable: "No rate(s) deatils found..."
            },
            "columnDefs": [
                { "orderable": false, "targets": [8] }
            ],
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'csvHtml5',
                    text: 'Export',
                    exportOptions: {
                        columns: [ 1, 2, 3, 4, 5, 6, 7],
                        format: {
                            body: function ( data, row, column, node ) {
                                // Strip $ from salary column to make it numeric
                                return (column === 0 || column === 1|| column === 2 || column === 3 || column === 4|| column === 5|| column === 6) ?
                                    data.replace( /[$,]/g, '' ) :
                                    data;
                            }
                        }
                    }
                },
            ],
            initComplete: function() {
                var $buttons = jQuery('.dt-buttons').hide();
                jQuery('#export-csv').on('click', function() {
                    var export_type = jQuery(this).attr('data-export-type');
                    if(export_type)
                    {
                        var btnClass = '.buttons-' + export_type;
                    }
                    if (btnClass) $buttons.find(btnClass).click();
                })
            }
        });
    }

    if(jQuery('#tbl-escrow-refinance-rates').length)
    {
        jQuery('#tbl-escrow-refinance-rates').DataTable({
           "pageLength": 20,
           "lengthChange": false,
           "searching": false,
           "language": {
                paginate: {
                    next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                    previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                },
                emptyTable: "No rate(s) deatils found..."
            },
            "columnDefs": [
                { "orderable": false, "targets": [4,5] }
            ]
        });
    }

    // Add active class to menu
    if(jQuery('#rates-sub-menu').children().hasClass('active'))
    {
        jQuery('#rates-sub-menu').parent('li').addClass('active');
        jQuery('#rates-sub-menu').addClass('show');
    }
    else
    {
        jQuery('#rates-sub-menu').removeClass('show');
        jQuery('#rates-sub-menu').parent('li').removeClass('active');
    }

    //validation for edit title rates
    if(jQuery('#edit-title-rates').length)
    {
        jQuery('#edit-title-rates').validate({ 
            rules: {
                min_price:"required",
                max_price:"required",
                owner_rate:"required",
                home_owner_rate:"required",
                con_loan_rate:"required",
                resi_loan_rate:"required",
                con_full_loan_rate:"required",
            },
            messages: {
                min_price: "Please enter min price",
                max_price:"Please enter max price",
                owner_rate: "Please enter owner rate",
                home_owner_rate: "Please enter home owner rate",
                con_loan_rate:"Please enter con loan rate",
                resi_loan_rate: "Please enter resi loan rate",
                con_full_loan_rate:"Please enter con full loan rate",
            },
            submitHandler: function(form) {
                form.submit();
            }
        }); 
    }
    //validation for edit title rates
});

function formToggle(ID)
{
  var element = document.getElementById(ID);
  if(element.style.display === "none"){
    element.style.display = "block";
  }else{
    element.style.display = "none";
  }
}