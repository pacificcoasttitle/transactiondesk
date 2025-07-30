<div class="container-fluid">	
	<div class="row mb-3">
		<div class="col-sm-6">
			<h1 class="h3 text-gray-800">Training</h1>
		</div>
		<div class="col-sm-6">
            <a href="<?php echo base_url().'hr/admin/add-training'; ?>" class="btn btn-success btn-icon-split float-right">
                <span class="icon text-white-50">
                    <i class="fa fa-plus"></i>
                </span>
                <span class="text">Add New</span>
            </a>
		</div>
	</div>

    <?php if(!empty($success)) {?>
        <a href="#" class="btn btn-success btn-block mt-1 mb-3"><?php echo $success;?></a>
    <?php }   
    if(!empty($errors)) {?>
        <a href="#" class="btn btn-danger btn-block mt-1 mb-3"><?php echo $errors;?></a>
    <?php } ?>

	<!-- <div id="taskList_category_success_msg" class="btn btn-success btn-block mt-1 mb-3" style="display:none;"></div>
	<div id="taskList_category_error_msg" class="btn btn-danger btn-block mt-1 mb-3" style="display:none;"></div> -->

	<!-- DataTales Example -->
	<div class="card shadow mb-4">
		<div class="card-header py-3">
			<h6 class="m-0 font-weight-bold text-primary">Training List</h6>
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-bordered" id="commonAdminTbl" data-url="<?php echo base_url('hr/admin/get-training');?>" width="100%" cellspacing="0">
					<thead>
						<tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Materials</th>
							<th>Department</th>
							<th>Position</th>
							<th>Status</th>
                            <th>Actions</th>
						</tr>
					</thead>
					<tbody>

					</tbody>
				</table>
			</div>
		</div>
	</div>

</div>

<div class="modal fade" width="500px" id="pct__delete_modal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document" style="width:40%;">
		<div class="modal-content">
			<form method="POST" action="<?php echo base_url('hr/admin/delete-training');?>">
			<div class="modal-header">
                <h4 class="modal-title">Are you sure?</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              </div>
              <div class="modal-body">
                <p>Do you really want to delete this record? This process cannot be undone.</p>
				<input type="hidden" name="action" value="delete" />
				<input type="hidden" name="id" id="pct__delete_record_id">
              </div>
              <div class="modal-footer justify-content-center">
                <button type="button" class="btn  btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-danger">Delete</button>
              </div>
			</form>
		</div>
	</div>
</div>
