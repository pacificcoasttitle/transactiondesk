<div class="container-fluid">	
	<div class="row mb-3">
		<div class="col-sm-6">
			<h1 class="h3 text-gray-800">Users</h1>
		</div>
		<?php $userdata = $this->session->userdata('hr_admin');
		if($userdata['user_type_id'] == 1 || $userdata['user_type_id'] == 2) { ?>
			<div class="col-sm-6">
				<a href="<?php echo base_url().'hr/admin/add-user'; ?>" class="btn btn-success btn-icon-split float-right">
					<span class="icon text-white-50">
						<i class="fa fa-plus"></i>
					</span>
					<span class="text">Add User</span>
				</a>
			</div>
		<?php } ?>
	</div>

    <?php if(!empty($success)) {?>
        <a href="#" class="btn btn-success btn-block mt-1 mb-3"><?php echo $success;?></a>
    <?php }   
    if(!empty($errors)) {?>
        <a href="#" class="btn btn-danger btn-block mt-1 mb-3"><?php echo $errors;?></a>
    <?php } ?>

	<div id="users_success_msg" class="btn btn-success btn-block mt-1 mb-3" style="display:none;"></div>
	<div id="users_error_msg" class="btn btn-danger btn-block mt-1 mb-3" style="display:none;"></div>

	<!-- DataTales Example -->
	<div class="card shadow mb-4">
		<div class="card-header py-3">
			<h6 class="m-0 font-weight-bold text-primary">Users Listing</h6>
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-bordered" id="users" width="100%" cellspacing="0">
					<thead>
						<tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Position</th>
							<th>Branch</th>
                            <th>User Type</th>
							<!-- <th>Employee Id</th> -->
                            <th>Hire Date</th>
							<?php if($userdata['user_type_id'] == 1 || $userdata['user_type_id'] == 2 /* || $userdata['user_type_id'] == 4 */ ) { ?>
                            	<th>Action</th>
							<?php } ?>
						</tr>
					</thead>

					<tbody>

					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>


