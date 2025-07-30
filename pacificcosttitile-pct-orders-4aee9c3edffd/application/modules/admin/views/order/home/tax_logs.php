<style>

.date-range-control {
    cursor: pointer;
    padding: 5px 10px;
    border-radius: 0.2rem;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #ced4da;
    width: 230px;
    display: inline-block;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out, -webkit-box-shadow 0.15s ease-in-out;
}

div.dataTables_wrapper div.dataTables_filter {
    text-align: left;
}
</style>

<div class="container-fluid">
    <div class="row mb-3">
		<div class="col-sm-6">
			<h1 class="h3 text-gray-800">Tax Logs</h1>
		</div>
	</div>
    <!-- DataTables Example -->
    <div class="card shadow mb-4">
        <div class="card-header datatable-header py-3">
            <div class="datatable-header-titles" > 
                <span>
                    <i class="fas fa-history"></i>
                </span>
                <h6 class="m-0 font-weight-bold text-primary pl-10">Tax Logs</h6> 
            </div>
        </div>

                
        <div class="card-body">
            <div id="customer_success_msg" class="w-100 alert alert-success alert-dismissible" style="display:none;"></div>
            <div id="customer_error_msg" class="w-100 alert alert-danger alert-dismissible" style="display:none;"></div>
            <div class="table-responsive">
                <table class="table table-bordered" id="tbl-tax-log-listing" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Sr No</th>
                            <th>Order No</th>
                            <th>Property Address</th>
                            <th>APN</th>
                            <th>Message</th>
                            <th>Created at</th>
                            <th>Action</th>
                        </tr>
                    </thead>                
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div><!-- /.container-fluid -->

<script>
    function regenerateTaxDocument(requestId, orderId, fileNumber) {
        $('body').animate({
            opacity: 0.5
        }, "slow");
        $.ajax({
            url: base_url + "order/admin/regenerate-tax-document",
            method: "POST",
            data: {
                request_id: requestId,
                order_id: orderId,
                file_number: fileNumber
            },
            success: function (data) {
                var result = jQuery.parseJSON(data);
                if (result.status == 'success') {
                    $('body').animate({
                        opacity: 1.0
                    }, "slow");
                    $('#customer_success_msg').html(result.message).show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#customer_success_msg").offset().top
                    }, 1000);
                    log_list.ajax.reload(null, false);
                    setTimeout(function () {
                        $('#customer_success_msg').html('').hide();
                        $('#customer_error_msg').html('').hide();
                    }, 5000);
                } else {
                    $('body').animate({
                        opacity: 1.0
                    }, "slow");
                    $('#customer_error_msg').html(result.message).show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#customer_error_msg").offset().top
                    }, 1000);
                    setTimeout(function () {
                        $('#customer_error_msg').html('').hide();
                    }, 5000);
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                $('#customer_error_msg').html('Something went wrong. Please try it again.').show();
                $([document.documentElement, document.body]).animate({
                    scrollTop: $("#customer_success_msg").offset().top
                }, 1000);

                setTimeout(function () {
                    $('#customer_error_msg').html('').hide();
                }, 5000);
            }
        });
    }

    function generateTaxDocument(serviceId, orderId, fileNumber) {
        $('body').animate({
            opacity: 0.5
        }, "slow");
        $.ajax({
            url: base_url + "order/admin/generate-tax-document",
            method: "POST",
            data: {
                cs3_service_id: serviceId,
                order_id: orderId,
                file_number: fileNumber
            },
            success: function (data) {
                var result = jQuery.parseJSON(data);
                if (result.status == 'success') {
                    $('body').animate({
                        opacity: 1.0
                    }, "slow");
                    $('#customer_success_msg').html(result.message).show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#customer_success_msg").offset().top
                    }, 1000);
                    log_list.ajax.reload(null, false);
                    setTimeout(function () {
                        $('#customer_success_msg').html('').hide();
                        $('#customer_error_msg').html('').hide();
                    }, 5000);
                } else {
                    $('body').animate({
                        opacity: 1.0
                    }, "slow");
                    $('#customer_error_msg').html(result.message).show();
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#customer_error_msg").offset().top
                    }, 1000);
                    setTimeout(function () {
                        $('#customer_error_msg').html('').hide();
                    }, 5000);
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                $('#customer_error_msg').html('Something went wrong. Please try it again.').show();
                $([document.documentElement, document.body]).animate({
                    scrollTop: $("#customer_success_msg").offset().top
                }, 1000);

                setTimeout(function () {
                    $('#customer_error_msg').html('').hide();
                }, 5000);
            }
        });
    }
</script>

