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
			<h1 class="h3 text-gray-800">CPL Error Logs</h1>
		</div>
	</div>
    <!-- DataTables Example -->
    <div class="card shadow mb-4">
        <div class="card-header datatable-header py-3">
            <div class="datatable-header-titles" > 
                <span>
                    <i class="fas fa-history"></i>
                </span>
                <h6 class="m-0 font-weight-bold text-primary pl-10">CPL Error Logs</h6> 
            </div>
        </div>                
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="tbl-cpl-log-listing" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Sr No</th>
                            <th>File Number</th>
                            <th>Error From</th>
                            <th>Error</th>
                            <th>Created at</th>
                        </tr>
                    </thead>                
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div><!-- /.container-fluid -->