<style>
.dataTables_length {
    width: 250px !important;
    float: left;
}
</style>
<div class="container-fluid">
    <div class="row mb-3">
		<div class="col-sm-6">
			<h1 class="h3 text-gray-800"> Mortgage Users </h1>
		</div>
	</div>

    <div class="card shadow mb-4">
        <div class="card-header datatable-header py-3">
            <div class="datatable-header-titles" > 
                <span>
                    <i class="fas fa-users"></i>
                </span>
                <h6 class="m-0 font-weight-bold text-primary pl-10">Mortgage Users</h6> 
            </div>
        </div>
  
        <div class="card-body">
            <div id="mortgage_success_msg" class="w-100 alert alert-success alert-dismissible" style="display:none;"></div>
            <div id="mortgage_error_msg" class="w-100 alert alert-danger alert-dismissible" style="display:none;"></div>
            <div class="table-responsive">
                <table class="table table-bordered" id="tbl-mortgage-listing" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email Address</th>
                            <th>Company Name</th>
                            <th>Address</th>
                            <th>Primary Mortgage User</th>
                        </tr>
                    </thead>                
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    function isMortgagePrimaryUser()
    {    
        $('input[type="checkbox"]').on('change', function() {
            $('body').animate({ opacity: 0.5 }, "slow");
            var user_id = $(this).attr('id');
            if ($(this).is(":checked")) {
                var primaryMortgageUserFlag = 1;
            } else {
                var primaryMortgageUserFlag = 0;
            }
            $.ajax({
                url: base_url+"is-mortgage-primary-user",
                method: "POST",
                data : {
                    user_id: user_id,
                    primaryMortgageUserFlag: primaryMortgageUserFlag
                },
                success: function(data){
                    var result = jQuery.parseJSON(data);
                    if (result.status == 'success') {
                        $('body').animate({ opacity: 1.0 }, "slow");
                        $('#mortgage_success_msg').html(result.msg).show();
                        $([document.documentElement, document.body]).animate({
                            scrollTop: $("#mortgage_success_msg").offset().top
                        }, 1000);
                        customer_list.ajax.reload( null, false );
                        setTimeout(function () {
                            $('#mortgage_success_msg').html('').hide();
                        }, 4000);
                    } else {
                        $('#mortgage_error_msg').html(result.message).show();
                        $([document.documentElement, document.body]).animate({
                            scrollTop: $("#mortgage_error_msg").offset().top
                        }, 1000);

                        setTimeout(function () {
                            $('#mortgage_error_msg').html('').hide();
                        }, 4000);
                    }
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    $('#mortgage_error_msg').html('Something went wrong. Please try it again.').show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#mortgage_success_msg").offset().top
                    }, 1000);

                    setTimeout(function () {
                        $('#mortgage_error_msg').html('').hide();
                    }, 4000);
                }
            });
        });
    }
</script>