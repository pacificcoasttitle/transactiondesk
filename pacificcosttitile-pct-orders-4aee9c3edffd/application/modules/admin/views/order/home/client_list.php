<style>
.dataTables_length {
    width: 250px !important;
    float: left;
}
</style>
<div class="container-fluid">
    <div class="row mb-3">
		<div class="col-sm-6">
			<h1 class="h3 text-gray-800"> Client User List </h1>
		</div>
	</div>

    <div class="card shadow mb-4">
        <div class="card-header datatable-header py-3">
            <div class="datatable-header-titles" >
                <span>
                    <i class="fas fa-users"></i>
                </span>
                <h6 class="m-0 font-weight-bold text-primary pl-10">Client User List</h6>
            </div>
        </div>

        <div class="card-body">
            <div id="client_list_success_msg" class="w-100 alert alert-success alert-dismissible" style="display:none;"></div>
            <div id="client_list_error_msg" class="w-100 alert alert-danger alert-dismissible" style="display:none;"></div>
            <div class="table-responsive">
                <table class="table table-bordered" id="tbl-client-user-listing" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email Address</th>
                            <th>Company Name</th>
                            <th>Client Type</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    function updateClientType(id, type)
    {
        console.log('id ===', id);
        console.log('val ===', type);
        if (type === null) {
            $('#client_list_error_msg').html('Please select client type').show();
            $([document.documentElement, document.body]).animate({
                scrollTop: $("#client_list_error_msg").offset().top
            }, 2000);
            return;
        }

        $.ajax({
                url: base_url+"update-client-type",
                method: "POST",
                data : {
                    user_id: id,
                    type: type
                },
                success: function(data){
                    var result = jQuery.parseJSON(data);
                    console.log('result ===', result);
                    if (result.status == 'success') {
                        console.log('in if');
                        $('body').animate({ opacity: 1.0 }, "slow");
                        $('#client_list_success_msg').html(result.msg).show();
                        $([document.documentElement, document.body]).animate({
                            scrollTop: $("#client_list_success_msg").offset().top
                        }, 1000);
                        client_users.ajax.reload( null, false );
                        setTimeout(function () {
                            $('#client_list_success_msg').html('').hide();
                        }, 4000);
                    } else {
                        $('#client_list_error_msg').html(result.msg).show();
                        $([document.documentElement, document.body]).animate({
                            scrollTop: $("#client_list_error_msg").offset().top
                        }, 1000);

                        setTimeout(function () {
                            $('#client_list_error_msg').html('').hide();
                        }, 4000);
                    }
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    $('#client_list_error_msg').html('Something went wrong. Please try it again.').show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#client_list_success_msg").offset().top
                    }, 1000);

                    setTimeout(function () {
                        $('#client_list_error_msg').html('').hide();
                    }, 4000);
                }
            });
    }
</script>
