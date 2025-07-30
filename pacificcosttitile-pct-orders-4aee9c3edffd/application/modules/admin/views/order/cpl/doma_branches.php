<div class="container-fluid">
    <!-- DataTables Example -->
    <div class="row mb-3">
		<div class="col-sm-6">
			<h1 class="h3 text-gray-800">North American Branches</h1>
		</div>
		<div class="col-sm-6">
            <a href="javascript:void(0);" id="refresh_north_american_doma_branches" class="btn btn-success btn-icon-split float-right mr-2">
                <span class="icon text-white-50"><i class="fas fa-refresh"></i></span><span class="text">Refresh</span> </a>
		</div>
	</div>
    <div class="card shadow mb-4">
        <div class="card-header datatable-header py-3">
            <div class="datatable-header-titles" >
                <span>
                    <i class="fas fa-table"></i>
                </span>
                <h6 class="m-0 font-weight-bold text-primary pl-10">North American Branches</h6>
            </div>
        </div>
        <div class="card-body">
            <div id="north_american_doma_success_msg" class="w-100 alert alert-success alert-dismissible" style="display:none;"></div>
            <div id="north_american_doma_error_msg" class="w-100 alert alert-danger alert-dismissible" style="display:none;"></div>
            <div class="table-responsive">
                <table class="table table-bordered" id="tbl-north-american" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Sr No</th>
                            <th>Unique Id</th>
                            <th>Address</th>
                            <th>Address1</th>
                            <th>City</th>
                            <th>State</th>
                            <th>Zipcode</th>
                        </tr>
                    </thead>
                    <?php if (!empty($branchesData)) {?>
                        <tbody>
                            <?php $i = 1;
    foreach ($branchesData as $branchData) {?>
                                <tr>
                                    <td><?php echo $i ?></td>
                                    <td><?php echo $branchData['unique_id']; ?></td>
                                    <td><?php echo $branchData['address']; ?></td>
                                    <td><?php echo $branchData['address1']; ?></td>
                                    <td><?php echo $branchData['city']; ?></td>
                                    <td><?php echo $branchData['state']; ?></td>
                                    <td><?php echo $branchData['zip']; ?></td>
                                </tr>
                            <?php $i++;}?>
                        </tbody>
                    <?php } else {?>
                        <tr>
                            <td align="center" colspan="7"> No Records Found.</td>
                        </tr>
                    <?php }?>
                </table>
            </div>
        </div>
    </div>
</div>