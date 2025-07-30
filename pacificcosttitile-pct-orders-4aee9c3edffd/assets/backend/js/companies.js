jQuery(document).ready(function ($) {

    $('#clone-email-address').cloneya({
        maximum: 5
    }).on('after_append.cloneya', function (event, toclone, newclone) {
        var name = $(newclone).find("input[type='email']").attr('id');
    }).off('remove.cloneya').on('remove.cloneya', function (event, clone) {
        $(clone).slideToggle('slow', function () {
            $(clone).remove();
        })
    });
});

function addOrUpdateDeliverables(partner_id)
{
    $('#page-preloader').css('background-color', 'rgba(0,0,0,.5)');
    $('#page-preloader').css('display', 'block');
    $('#deliverables_information').modal('show');
    $('#partner_id').val(partner_id);
    $.ajax({
        url:base_url+"admin/order/home/getDeliverables",
        type: "POST",
        data: {
            partner_id: partner_id,
        },
        async: false,
        success: function(result) {
            $('#page-preloader').css('display', 'none');
            $('#borrower_page').css('opacity', '1');
            var res = jQuery.parseJSON(result);
            if (res.deliverables.length > 0) {
                for (i = 0; i < res.deliverables.length; i++) {
                    if(i == 0) {
                        $('#AdditionalEmail').val(res.deliverables[i]);
                    } else {
                        $("#clonea")[0].click();
                    } 
                }
                for (i = 0; i < res.deliverables.length; i++) {
                    if(i != 0) {
                        var emailVal = res.deliverables[i];
                        $('#AdditionalEmail'+i).val(emailVal);
                    } 
                }
            } 
        },
        error:function(){
              
        },
    });
}

function deleteCompany(partner_id)
{
    let confirm_msg = confirm('Are you sure to want to delete this record?');
    if(confirm_msg){
        $('#page-preloader').css('background-color', 'rgba(0,0,0,.5)');
        $('#page-preloader').css('display', 'block');

        $.ajax({
            url:base_url+"order/admin/delete-company",
            type: "POST",
            data: {
                partner_id: partner_id,
            },
            async: false,
            success: function(result) {
                $('#page-preloader').css('display', 'none');
                $('#borrower_page').css('opacity', '1');
                var res = jQuery.parseJSON(result);
                console.log('res ===', res);
                if (res.status === 'success') {
                    location.reload();
                }
            },
            error:function(){
                  
            },
        });
    }
    
}