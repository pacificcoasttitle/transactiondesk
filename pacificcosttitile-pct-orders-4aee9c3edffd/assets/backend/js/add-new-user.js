jQuery(document).ready(function ($) {
    $("#company").autocomplete({
        source: function(request, response) {
            $.ajax({
                url: base_url+"admin/order/home/get_company_list",
                data: {
                    term : request.term        
                },
                type: "POST",
                dataType: "json",
                success: function (data) {
                    if (data.length > 0) {
                        response($.map(data, function (item) {
                            return item;
                        }))
                    } else {
                        response([{ label: 'No results found.', val: -1}]);
                    }
                }
            });
        },
        delay: 0,
        minLength: 3,
        select: function( event, ui ) {
            event.preventDefault();
            $("#company").val(ui.item.partner_name);
            $("#partner_id").val(ui.item.partner_id);
            $("#address").val(ui.item.address1);
            $("#city").val(ui.item.city);
            $("#state").val(ui.item.state);
            $("#zipcode").val(ui.item.zip);
        },
        change: function( event, ui ) {
            if (ui.item == null) {
                $("#company").parent().removeClass('state-success').addClass('state-error');
            }
        }
    });

    $("#title_company").autocomplete({
        source: function(request, response) {
            $.ajax({
                url: base_url+"admin/order/home/get_title_company_list",
                data: {
                    term : request.term        
                },
                type: "POST",
                dataType: "json",
                success: function (data) {
                    if (data.length > 0) {
                        response($.map(data, function (item) {
                            return item;
                        }))
                    } else {
                        response([{ label: 'No results found.', val: -1}]);
                    }
                }
            });
        },
        delay: 0,
        minLength: 2,
        select: function( event, ui ) {
            event.preventDefault();
            $("#title_company").val(ui.item.partner_name);
            $("#title_partner_id").val(ui.item.partner_id);
        },
        change: function( event, ui ) {
            if (ui.item == null) {
                $("#title_company").parent().removeClass('state-success').addClass('state-error');
            }
        }
    });
});