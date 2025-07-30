<?php 
    $salesRep = isset($salesRep['data']) && !empty($salesRep['data']) ? $salesRep['data'] : array();

    $product_type = isset($product_type) && !empty($product_type) ? $product_type : '';
    
    $sales_rep = json_encode($salesRep);
    
    $master_users = json_encode($master_users);

    $userdata = $this->session->userdata('admin');
	$roleList = $this->common->getRoleList();
	$role_id = isset($userdata['role_id']) ? $userdata['role_id'] : 0;
	$roleName = $roleList[$role_id];
?>
<script type="text/javascript">
    var sales_rep = '<?php echo $sales_rep; ?>';
    var master_users = '<?php echo $master_users; ?>';
    var product_type = '<?php echo $product_type; ?>';
</script>
<style>
.dataTables_length {
    width: 250px !important;
    float: left;
}
.FilterOrderListing {
    display: flex;
    justify-content: space-between;
    width: 100%;
}
</style>
<div class="container-fluid">
    <!-- DataTables Example -->
    <div class="row mb-3">
		<div class="col-sm-6">
			<h1 class="h3 text-gray-800">Orders Listing</h1>
		</div>

        <?php  if (!in_array($roleName, ['CS Admin'])) : ?>
            <div class="col-sm-6">
                <a href="javascript:void(0);" data-export-type="csv" onclick="exportOrders();" id="export-orders-data" class="btn btn-success btn-icon-split float-right mr-2"> 
                    <span class="icon text-white-50">
                        <i class="fas fa-file-export"></i>
                    </span>
                    <span class="text"> Export </span> </a>
            </div>
        <?php endif; ?>

	</div>
    <div class="card shadow mb-4">
        <div class="card-header datatable-header py-3">
            <div class="datatable-header-titles" > 
                <span>
                    <i class="fas fa-table"></i>
                </span>
                <h6 class="m-0 font-weight-bold text-primary pl-10">Orders Listing</h6> 
            </div>
            
            <!-- <div class="float-right">
                <a href="javascript:void(0);" data-export-type="csv" onclick="exportOrders();" id="export-orders-data" class="btn btn-success btn-icon-split float-right mr-2"> 
                <span class="icon text-white-50">
                    <i class="fas fa-file-export"></i>
                </span>
                <span class="text"> Export </span> </a>
            </div> -->
        </div>

                
        <div class="card-body">
            <div id="order_success_msg" class="w-100 alert alert-success alert-dismissible" style="display:none;"></div>
            <div id="order_error_msg" class="w-100 alert alert-danger alert-dismissible" style="display:none;"></div>
            <div class="table-responsive">
                <table class="table table-bordered" id="tbl-orders-listing" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Sr No</th>
                            <th>Order#</th>
                            <th>Property Address</th>
                            <th>Product Type</th>
                            <th>Sales Rep</th> 
                            <th>Created By</th>   
                            <th>Email Status</th>          
                            <th>Avoid Duplication</th>           
                            <th>Created At</th>           
                            <th>Action</th>
                        </tr>
                    </thead>                
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div><!-- /.container-fluid -->
