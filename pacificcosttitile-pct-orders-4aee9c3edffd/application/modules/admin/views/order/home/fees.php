<div class="container-fluid">
    <!-- DataTables Example -->
    <div class="row mb-3">
		<div class="col-sm-6">
			<h1 class="h3 text-gray-800">Fees</h1>
		</div>
		<div class="col-sm-6">
            <a href="<?php echo base_url(); ?>order/admin/add-fee" class="btn btn-success btn-icon-split float-right mr-2">
                <span class="icon text-white-50"><i class="fas fa-plus"></i></span><span class="text">Add Fees</span> </a>
		</div>
	</div>
    <div class="card shadow mb-4">
        <div class="card-header datatable-header py-3">
            <div class="datatable-header-titles" >
                <span>
                    <i class="fas fa-table"></i>
                </span>
                <h6 class="m-0 font-weight-bold text-primary pl-10">Add Fees</h6>
            </div>
        </div>
        <div class="card-body">
            <div id="fees_success_msg" class="w-100 alert alert-success alert-dismissible" style="display:none;"></div>
            <div id="fees_error_msg" class="w-100 alert alert-danger alert-dismissible" style="display:none;"></div>
            <div class="table-responsive">
                <table class="table table-bordered" id="tbl-fees" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="10%">Sr No</th>
                            <th width="10%">Transaction Type</th>
                            <th width="20%">Fees Type</th>
                            <th width="32%">Name</th>
                            <th width="18%">Value</th>
                            <th width="10%">Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>