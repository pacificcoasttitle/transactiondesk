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
			<h1 class="h3 text-gray-800">ION Fraud Logs</h1>
		</div>
	</div>
    <!-- DataTables Example -->
    <div class="card shadow mb-4">
        <div class="card-header datatable-header py-3">
            <div class="datatable-header-titles" >
                <span>
                    <i class="fas fa-history"></i>
                </span>
                <h6 class="m-0 font-weight-bold text-primary pl-10">ION Fraud Logs</h6>
            </div>
        </div>

        <div class="card-body">
            <div id="ion_fraud_success_msg" class="w-100 alert alert-success alert-dismissible" style="display:none;"></div>
            <div id="ion_fraud_error_msg" class="w-100 alert alert-danger alert-dismissible" style="display:none;"></div>
            <div class="table-responsive">
                <table class="table table-bordered" id="tbl-ion-fraud-log-listing" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Sr No</th>
                            <th>Order No</th>
                            <th>ION Fraud Status</th>
                            <th>User Proceed Status</th>
                            <th>Created at</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div><!-- /.container-fluid -->
