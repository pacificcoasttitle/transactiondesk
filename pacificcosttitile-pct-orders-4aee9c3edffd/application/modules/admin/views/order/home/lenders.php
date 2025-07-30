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
    <div class="row mb-3">
		<div class="col-sm-6">
			<h1 class="h3 text-gray-800">Lenders </h1>
		</div>
		<div class="col-sm-6">
            <a href="<?php echo base_url()?>order/admin/import-lenders"  class="btn btn-success btn-icon-split float-right mr-2"> 
                <span class="icon text-white-50">
                    <i class="fas fa-file-import"></i>
                </span>
                <span class="text"> Import </span> 
            </a>
            <?php  if (!in_array($roleName, ['CS Admin'])) : ?>
                <a href="javascript:void(0);" data-export-type="csv" id="export-csv" class="btn btn-success btn-icon-split float-right mr-2"> 
                    <span class="icon text-white-50">
                        <i class="fas fa-file-export"></i>
                    </span>
                    <span class="text"> Export </span> 
                </a>
            <?php endif; ?>
		</div>
	</div>
    <!-- DataTables Example -->
    <div class="card shadow mb-4">
        <div class="card-header datatable-header py-3">
            <div class="datatable-header-titles" > 
                <span>
                    <i class="fas fa-users"></i>
                </span>
                <h6 class="m-0 font-weight-bold text-primary pl-10">Lenders</h6> 
            </div>
        </div>

                
        <div class="card-body">
            <div id="customer_success_msg" class="w-100 alert alert-success alert-dismissible" style="display:none;"></div>
            <div id="customer_error_msg" class="w-100 alert alert-danger alert-dismissible" style="display:none;"></div>
            <div class="table-responsive">
                <table class="table table-bordered" id="tbl-lenders-listing" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="7%" >First Name</th>
                            <th width="7%">Last Name</th>
                            <th width="15%">Email Address</th>
                            <th width="15%">Company Name</th>
                            <th width="15%">Address</th>
                            <th width="5%">Mortgage User</th>
                            <th width="10%">User Type</th>
                            <th width="5%">Dual CPL</th>
                            <th width="5%">Allow Only Resware Order</th>
                            <th width="5%">Action</th>
                        </tr>
                    </thead>                
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div><!-- /.container-fluid -->