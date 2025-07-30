<?php 
    $userdata = $this->session->userdata('admin');
	$roleList = $this->common->getRoleList();
	$role_id = isset($userdata['role_id']) ? $userdata['role_id'] : 0;
	$roleName = $roleList[$role_id];
?>
<style>
.dataTables_length {
    width: 250px !important;
    float: left;
}
</style>
<div class="container-fluid">
    <!-- DataTables Example -->
    <div class="row mb-3">
		<div class="col-sm-6">
			<h1 class="h3 text-gray-800">Credentials Check</h1>
		</div>
		<div class="col-sm-6">
            <?php  if (!in_array($roleName, ['CS Admin'])) : ?>
                <a href="javascript:void(0);" data-export-type="csv" id="export_customer" class="btn btn-success btn-icon-split float-right mr-2"> 
                    <span class="icon text-white-50"><i class="fas fa-file-export"></i></span><span class="text">Export</span> </a>
            <?php endif; ?>
            <a href="javascript:void(0);" id="refresh-new-users-data" class="btn btn-success btn-icon-split float-right mr-2"> 
                <span class="icon text-white-50"><i class="fas fa-user"></i></span><span class="text">New Users</span> </a>
            <a href="javascript:void(0);" id="refresh-data" class="btn btn-success btn-icon-split float-right mr-2"> 
                <span class="icon text-white-50"><i class="fas fa-users"></i></span><span class="text">All Users</span> </a>
		</div>
	</div>
    <div class="card shadow mb-4">
        <div class="card-header datatable-header py-3">
            <div class="datatable-header-titles" > 
                <span>
                    <i class="fas fa-table"></i>
                </span>
                <h6 class="m-0 font-weight-bold text-primary pl-10">Credentials Check</h6> 
            </div>
        </div>  
        <div class="card-body">
            <div id="customer_success_msg" class="w-100 alert alert-success alert-dismissible" style="display:none;"></div>
            <div id="customer_error_msg" class="w-100 alert alert-danger alert-dismissible" style="display:none;"></div>
            <div class="table-responsive">
                <table class="table table-bordered" id="tbl-credentials-customers-listing" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email Address</th>
                            <th>Company Name</th>
                            <th>property Address</th>
                            <th>Password</th>
                            <th>Customer Type</th>
                            <th>Crediential</th>
                            <th>Error Message</th>
                            <th>Action</th>
                        </tr>
                    </thead>                
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div><!-- /.container-fluid -->