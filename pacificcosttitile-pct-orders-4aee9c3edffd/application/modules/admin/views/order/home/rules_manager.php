<style>
.bootstrap-select:not([class*="col-"]):not([class*="form-control"]):not(.input-group-btn) {
    width: -webkit-fill-available;
}
</style>
<div class="container-fluid">
    <!-- DataTables Example -->
    <div class="row mb-3">
		<div class="col-sm-6">
			<h1 class="h3 text-gray-800">Rules Manager</h1>
		</div>
	</div>
    <div class="card shadow mb-4">
        <div class="card-header datatable-header py-3">
            <div class="datatable-header-titles" > 
                <span>
                    <i class="fas fa-table"></i>
                </span>
                <h6 class="m-0 font-weight-bold text-primary pl-10">Rules Manager</h6> 
            </div>
        </div>
        <div class="card-body">
            <div id="rules_success_msg" class="w-100 alert alert-success alert-dismissible" style="display:none;"></div>
            <div id="rules_error_msg" class="w-100 alert alert-danger alert-dismissible" style="display:none;"></div>
            <div class="table-responsive">
                <table class="table table-bordered" id="tbl-rules-manager" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Sr No</th>
                            <th>Title</th>
                            <th>Value</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>