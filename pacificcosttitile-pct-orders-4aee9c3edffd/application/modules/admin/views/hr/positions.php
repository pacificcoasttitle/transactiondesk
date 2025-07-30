<div class="container-fluid">	
	<div class="row mb-3">
		<div class="col-sm-6">
			<h1 class="h3 text-gray-800">Positions</h1>
		</div>
		<div class="col-sm-6">
            <a href="<?php echo base_url().'hr/admin/add-position'; ?>" class="btn btn-success btn-icon-split float-right">
                <span class="icon text-white-50">
                    <i class="fa fa-plus"></i>
                </span>
                <span class="text">Add Position</span>
            </a>
		</div>
	</div>

    <?php if(!empty($success)) {?>
        <a href="#" class="btn btn-success btn-block mt-1 mb-3"><?php echo $success;?></a>
    <?php }   
    if(!empty($errors)) {?>
        <a href="#" class="btn btn-danger btn-block mt-1 mb-3"><?php echo $errors;?></a>
    <?php } ?>

	<div id="positions_success_msg" class="btn btn-success btn-block mt-1 mb-3" style="display:none;"></div>
	<div id="positions_error_msg" class="btn btn-danger btn-block mt-1 mb-3" style="display:none;"></div>

	<!-- DataTales Example -->
	<div class="card shadow mb-4">
		<div class="card-header py-3">
			<h6 class="m-0 font-weight-bold text-primary">Positions Listing</h6>
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-bordered" id="positions" width="100%" cellspacing="0">
					<thead>
						<tr>
                            <th>No</th>
                            <th>Name</th>
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


