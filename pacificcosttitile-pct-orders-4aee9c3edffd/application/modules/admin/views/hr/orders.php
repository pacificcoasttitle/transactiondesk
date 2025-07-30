<style>
.percentage {
    font-weight: 900;
    font-size: 21px;
    color: #a324ec;
}
</style>

<div class="container-fluid">	
	<div class="row mb-3">
		<div class="col-sm-6">
			<h1 class="h3 text-gray-800">Orders</h1>
		</div>
	</div>

    <?php if(!empty($success)) {?>
        <a href="#" class="btn btn-success btn-block mt-1 mb-3"><?php echo $success;?></a>
    <?php }   
    if(!empty($errors)) {?>
        <a href="#" class="btn btn-danger btn-block mt-1 mb-3"><?php echo $errors;?></a>
    <?php } ?>

	<div id="orders_success_msg" class="btn btn-success btn-block mt-1 mb-3" style="display:none;"></div>
	<div id="orders_error_msg" class="btn btn-danger btn-block mt-1 mb-3" style="display:none;"></div>

	<!-- DataTales Example -->
	<div class="card shadow mb-4">
		<div class="card-header py-3">
			<h6 class="m-0 font-weight-bold text-primary">Orders Listing</h6>
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-bordered" id="orders" width="100%" cellspacing="0">
					<thead>
						<tr>
                            <th>No</th>
                            <th>File Number</th>
                            <th>Property Address</th>
                            <th>Product Type</th>
							<th>Escrow Officer</th>
							<th>Completed %</th>
							<th>Created At</th>
                            <th>Action</th>
						</tr>
					</thead>

					<tbody>

					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>


