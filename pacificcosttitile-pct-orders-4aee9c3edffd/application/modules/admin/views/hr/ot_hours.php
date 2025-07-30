<div class="container-fluid">	
	<div class="row mb-3">
		<div class="col-sm-6">
			<h1 class="h3 text-gray-800">OT Hours</h1>
		</div>
		<div class="col-sm-6">
            
		</div>
	</div>

    <?php if(!empty($success)) {?>
        <a href="#" class="btn btn-success btn-block mt-1 mb-3"><?php echo $success;?></a>
    <?php }   
    if(!empty($errors)) {?>
        <a href="#" class="btn btn-danger btn-block mt-1 mb-3"><?php echo $errors;?></a>
    <?php } ?>

	
	<!-- DataTales Example -->
	<div class="card shadow mb-4">
		<div class="card-header py-3">
			<h6 class="m-0 font-weight-bold text-primary">Employee OT Hours</h6>
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-bordered" id="commonAdminTbl" data-url="<?php echo base_url('hr/admin/get-ot-hours');?>" width="100%" cellspacing="0">
					<thead>
						<tr>
                            <th>No</th>
                            <th>Employee</th>
                            <th>Date</th>
                            <th>Total Hours</th>
							<th>OT Hours</th>
							<th>Action </th>
						</tr>
					</thead>

					<tbody>

					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" width="500px" id="ot_approve_deny_popup" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document" style="width:40%;">
		<div class="modal-content">
			<form method="POST" action="<?php echo base_url();?>hr/admin/add-ot-request" >
				<div class="row">
					<div class="col-lg-12">
						<div class="card shadow mb-4">
							<div class="card-header py-3">
								<h6 class="m-0 font-weight-bold text-primary">Confirmation</h6>
							</div>
							<input type="hidden" id="ot_employee_id" name="employee_id" value="">
							<input type="hidden" id="ot_date" name="ot_date" value="">
							<input type="hidden" id="ot_is_approved" name="status" value="">
							<div class="card-body" > Are you sure to <span id='ot_request_type'></span> this request? </div>
							<div class="card-body"> 
								<button type="submit" data-btntext-sending="Sending..."
									class="btn btn-success btn-icon-split btn-sm">
									<span class="icon text-white-50">
										<i class="fas fa-check"></i>
									</span>
									<span class="text">Yes</span>
								</button>
								<button type="reset" data-dismiss="modal" aria-label="Close"
									class="btn btn-danger btn-icon-split btn-sm">
									<span class="icon text-white-50">
										<i class="fas fa-ban"></i>
									</span>
									<span class="text">No</span>
								</button>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>


