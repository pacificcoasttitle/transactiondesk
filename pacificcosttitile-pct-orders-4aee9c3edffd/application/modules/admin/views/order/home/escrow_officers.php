<style>
.dataTables_length {
    width: 250px !important;
    float: left;
}
</style>
<div class="container-fluid">
    <div class="row mb-3">
		<div class="col-sm-6">
			<h1 class="h3 text-gray-800"> Escrow Officers </h1>
		</div>
		<div class="col-sm-6">
            <a href="<?php echo base_url()?>order/admin/add-escrow-officer"  class="btn btn-success btn-icon-split float-right mr-2"> 
                <span class="icon text-white-50">
                    <i class="fas fa-plus"></i>
                </span>
                <span class="text"> Add </span> 
            </a>
		</div>
	</div>

    <!-- DataTables Example -->
    <div class="card shadow mb-4">
        <div class="card-header datatable-header py-3">
            <div class="datatable-header-titles" > 
                <span>
                    <i class="fas fa-users"></i>
                </span>
                <h6 class="m-0 font-weight-bold text-primary pl-10">Escrow Officers</h6> 
            </div>
        </div>
        <div class="card-body">
            <div id="escrow_officer_success_msg" class="w-100 alert alert-success alert-dismissible" style="display:none;"></div>
            <div id="escrow_officer_error_msg" class="w-100 alert alert-danger alert-dismissible" style="display:none;"></div>
            <div class="table-responsive">
                <table class="table table-bordered" id="tbl-escrow-officers-listing" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Partner ID</th>
                            <th>Partner Type ID</th>
                            <th>Name</th>
                            <th>Email Address</th>
                            <th>Action</th>
                        </tr>
                    </thead>                
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div><!-- /.container-fluid -->