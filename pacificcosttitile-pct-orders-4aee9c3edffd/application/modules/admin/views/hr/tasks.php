<div class="container-fluid">	
	<div class="row mb-3">
		<div class="col-sm-6">
			<h1 class="h3 text-gray-800">Tasks</h1>
		</div>
		<div class="col-sm-6">
			<a href="<?php echo base_url().'hr/admin/loan-tasks-position'; ?>" class="btn btn-info btn-icon-split float-right">
                <span class="icon text-white-50">
                    <i class="fas fa-tasks fa-fw"></i>
                </span>
                <span class="text">Set Loan Task Position</span>
            </a>
			<a href="<?php echo base_url().'hr/admin/sale-tasks-position'; ?>" class="btn btn-info btn-icon-split float-right mr-2">
                <span class="icon text-white-50">
                    <i class="fas fa-tasks fa-fw"></i>
                </span>
                <span class="text">Set Sale Task Position</span>
            </a>
            <a href="<?php echo base_url().'hr/admin/add-task'; ?>" class="btn btn-success btn-icon-split float-right mr-2">
                <span class="icon text-white-50">
                    <i class="fa fa-plus"></i>
                </span>
                <span class="text">Add Task</span>
            </a>
		</div>
	</div>

    <?php if(!empty($success)) {?>
        <a href="#" class="btn btn-success btn-block mt-1 mb-3"><?php echo $success;?></a>
    <?php }   
    if(!empty($errors)) {?>
        <a href="#" class="btn btn-danger btn-block mt-1 mb-3"><?php echo $errors;?></a>
    <?php } ?>

	<div id="tasks_success_msg" class="btn btn-success btn-block mt-1 mb-3" style="display:none;"></div>
	<div id="tasks_error_msg" class="btn btn-danger btn-block mt-1 mb-3" style="display:none;"></div>

	<!-- DataTales Example -->
	<div class="card shadow mb-4">
		<div class="card-header py-3">
			<h6 class="m-0 font-weight-bold text-primary">Tasks Listing</h6>
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-bordered" id="tasks" width="100%" cellspacing="0">
					<thead>
						<tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Product Type</th>
							<th>Parent Task</th>
							<th>Notes</th>
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


