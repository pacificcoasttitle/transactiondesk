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
			<h1 class="h3 text-gray-800">Sales Rep </h1>
		</div>
		<div class="col-sm-6">
            <a href="<?php echo base_url()?>order/admin/add-sales-rep"  class="btn btn-success btn-icon-split float-right mr-2"> 
                <span class="icon text-white-50">
                    <i class="fas fa-plus"></i>
                </span>
                <span class="text"> Add Sales Rep </span> 
            </a>
            <?php  if (!in_array($roleName, ['CS Admin'])) : ?>
                <a href="javascript:void(0);" data-export-type="csv" id="export-sales-rep-data" class="btn btn-success btn-icon-split float-right mr-2"> 
                    <span class="icon text-white-50">
                        <i class="fas fa-file-export"></i>
                    </span>
                    <span class="text"> Export </span> 
                </a>

                <a href="<?php echo base_url()?>order/admin/export-sales-rep-client" class="btn btn-success btn-icon-split float-right mr-2"> 
                    <span class="icon text-white-50">
                        <i class="fas fa-file-export"></i>
                    </span>
                    <span class="text"> Export Sales Rep Client</span> 
                </a>
            <?php endif; ?>
		</div>
	</div>
    <div class="card shadow mb-4">
        <div class="card-header datatable-header py-3">
            <div class="datatable-header-titles" > 
                <span>
                    <i class="fas fa-users"></i>
                </span>
                <h6 class="m-0 font-weight-bold text-primary pl-10">Sales Rep</h6> 
            </div>
        </div>
     
        <input type="hidden" name="sales_rep_status_flag" id="sales_rep_status_flag" value="<?php echo $sales_rep_status_flag;?>">
        <div class="card-body">
            <div id="sales_rep_success_msg" class="w-100 alert alert-success alert-dismissible" style="display:none;"></div>
            <div id="sales_rep_error_msg" class="w-100 alert alert-danger alert-dismissible" style="display:none;"></div>
            <div class="table-responsive">
                <table class="table table-bordered" id="tbl-sales-rep-listing" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email Address</th>
                            <th>Telephone</th>
                            <th>Partner Id</th>
                            <th>Sales Rep Type</th>
                            <th>Mail Notification</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>                
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

