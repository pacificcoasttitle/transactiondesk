<style>
.dataTables_length {
    width: 250px !important;
    float: left;
}
.password_listing_filter {
    display: flex;
    justify-content: end;
}
</style>
<div class="container-fluid">
    <!-- DataTables Example -->
    <div class="row mb-3">
		<div class="col-sm-6">
			<h1 class="h3 text-gray-800">Users</h1>
		</div>
	</div>
    <div class="card shadow mb-4">
        <div class="card-header datatable-header py-3">
            <div class="datatable-header-titles" > 
                <span>
                    <i class="fas fa-key"></i>
                </span>
                <h6 class="m-0 font-weight-bold text-primary pl-10">Users</h6> 
            </div>
        </div>
        <div class="card-body">
            <div id="password_listing_success_msg" class="w-100 alert alert-success alert-dismissible" style="display:none;"></div>
            <div id="password_listing_error_msg" class="w-100 alert alert-danger alert-dismissible" style="display:none;"></div>
            <div class="table-responsive">
                <table class="table table-bordered" id="tbl-password-listing" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <!-- <th>Customer Number</th> -->
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email Address</th>
                            <th>User Type</th>
                            <th>Company Name</th>
                            <th>Street Address</th>
                            <th>City</th>
                            <th>Zipcode</th>
                            <th>Password Required</th>
                            <th>Action</th>
                        </tr>
                    </thead>                
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div><!-- /.container-fluid -->