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
			<h1 class="h3 text-gray-800">Orders Listing</h1>
		</div>
        <?php  if (!in_array($roleName, ['CS Admin'])) : ?>
            <div class="col-sm-6">
                <a href="javascript:void(0);" data-export-type="csv" id="export_grant_documents" class="btn btn-success btn-icon-split float-right mr-2"> 
                    <span class="icon text-white-50"><i class="fas fa-file-export"></i></span><span class="text">Export</span> </a>
            </div>
        <?php endif; ?>
	</div>
    <div class="card shadow mb-4">
        <div class="card-header datatable-header py-3">
            <div class="datatable-header-titles" > 
                <span>
                    <i class="fas fa-table"></i>
                </span>
                <h6 class="m-0 font-weight-bold text-primary pl-10">Grant Deed Documents</h6> 
            </div>
        </div>
        <div class="card-body">
            <div id="cpl_document_success_msg" class="w-100 alert alert-success alert-dismissible" style="display:none;"></div>
            <div id="cpl_document_error_msg" class="w-100 alert alert-danger alert-dismissible" style="display:none;"></div>
            <div class="table-responsive">
                <table class="table table-bordered" id="tbl-grant-documents-listing" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Sr No</th>
                            <th>File Number</th>
                            <th>Document Name</th>
                            <th>Sent To Resware</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                    </thead>                
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- <script src="https://sdk.amazonaws.com/js/aws-sdk-2.895.0.min.js"></script> -->