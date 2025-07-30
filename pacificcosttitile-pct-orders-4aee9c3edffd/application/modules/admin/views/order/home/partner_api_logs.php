<?php 
    $salesRep = isset($salesRep['data']) && !empty($salesRep['data']) ? $salesRep['data'] : array();
    
    $sales_rep = json_encode($salesRep);

    $titleOfficer = isset($titleOfficer['data']) && !empty($titleOfficer['data']) ? $titleOfficer['data'] : array();
    
    $title_officer = json_encode($titleOfficer);

?>
<script type="text/javascript">
    var sales_rep = '<?php echo $sales_rep; ?>';
    var title_officer = '<?php echo $title_officer; ?>';
</script>
<style>
.dataTables_length {
    width: 250px !important;
    float: left;
}
</style>

<div class="container-fluid">
    <div class="row mb-3">
		<div class="col-sm-6">
			<h1 class="h3 text-gray-800">Partner Api Logs</h1>
		</div>
	</div>
    <!-- DataTables Example -->
    <div class="card shadow mb-4">
        <div class="card-header datatable-header py-3">
            <div class="datatable-header-titles" > 
                <span>
                    <i class="fas fa-history"></i>
                </span>
                <h6 class="m-0 font-weight-bold text-primary pl-10">Partner Api Logs</h6> 
            </div>
        </div>

                
        <div class="card-body">
            <div id="customer_success_msg" class="w-100 alert alert-success alert-dismissible" style="display:none;"></div>
            <div id="customer_error_msg" class="w-100 alert alert-danger alert-dismissible" style="display:none;"></div>
            <div class="table-responsive">
                <table class="table table-bordered" id="tbl-partner-api-log-listing" width="100%" cellspacing="0" >
                    <thead>
                        <tr>
                            <th>Sr No</th>
                            <th>Order No</th>
                            <th>Title Officer</th>
                            <th>Sales Rep</th>
                            <th>Underwriter</th>
                            <th>Message</th>
                            <th>Created At</th>
                        </tr>
                    </thead>                
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div><!-- /.container-fluid -->