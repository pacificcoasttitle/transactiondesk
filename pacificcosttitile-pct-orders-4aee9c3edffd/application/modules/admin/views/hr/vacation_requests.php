<div class="container-fluid">	
	<div class="row mb-3">
		<div class="col-sm-6">
			<h1 class="h3 text-gray-800">Vacation Requests</h1>
		</div>
		<div class="col-sm-6">
            <a href="<?php echo base_url('hr/admin/add-vacation-request') ?>" class="btn btn-success btn-icon-split float-right">
                <span class="icon text-white-50">
                    <i class="fa fa-plus"></i>
                </span>
                <span class="text">Add Vacation Request</span>
            </a>
		</div>
	</div>

    <?php if(!empty($success)) {?>
        <a href="#" class="btn btn-success btn-block mt-1 mb-3"><?php echo $success;?></a>
    <?php }   
    if(!empty($errors)) {?>
        <a href="#" class="btn btn-danger btn-block mt-1 mb-3"><?php echo $errors;?></a>
    <?php } ?>

	<div id="vacation_requests_success_msg" class="btn btn-success btn-block mt-1 mb-3" style="display:none;"></div>
	<div id="vacation_requests_error_msg" class="btn btn-danger btn-block mt-1 mb-3" style="display:none;"></div>

	<!-- DataTales Example -->
	<div class="card shadow mb-4">
		<div class="card-header py-3">
			<h6 class="m-0 font-weight-bold text-primary">Vacation Requests Listing</h6>
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-bordered" id="vacation_requests" width="100%" cellspacing="0">
					<thead>
						<tr>
                            <th>No</th>
                            <th>Employee</th>
                            <th>From Date</th>
                            <th>To Date</th>
                            <th>Salary Deduction</th>
                            <th>Time Charged Vacation</th>
							<th>Status</th>
							<th>Approved By</th>
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

<div class="modal fade" width="500px" id="approve_deny_popup" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document" style="width:40%;">
		<div class="modal-content">
			<form method="POST" action="<?php echo base_url();?>hr/admin/approve-deny-request" enctype="multipart/form-data">
				<div class="row">
					<div class="col-lg-12">
						<div class="card shadow mb-4">
							<div class="card-header py-3">
								<h6 class="m-0 font-weight-bold text-primary" id="approve_deny_title"></h6>
							</div>
							<input type="hidden" id="request_type" name="request_type" value="vacation_request">
							<input type="hidden" id="request_id" name="request_id" value="">
							<input type="hidden" id="status" name="status" value="">
							<div class="card-body" id="approve_deny_msg"> </div>
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

<div class="modal fade" width="500px" id="vacation_request_deny_popup" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document" style="width:40%;">
		<div class="modal-content">
			<form method="POST" action="<?php echo base_url();?>hr/admin/approve-deny-request" enctype="multipart/form-data">
				<div class="row">
					<div class="col-lg-12">
						<div class="card shadow mb-4">
							<div class="card-header py-3">
								<h6 class="m-0 font-weight-bold text-primary" >Deny Vacation Request Confirmation</h6>
							</div>
							<input type="hidden"  name="request_type" value="vacation_request">
							<input type="hidden" id="deny_request_id" name="request_id" value="">
							<input type="hidden" name="status" value="0">
							<div class="card-body" > Please enter reason for deny this request </div>
							<div class="card-body"> 
								<div class="row">
									<div class="col-sm-12">
										<div class="form-group">
											<textarea name="deny_reason"cols="30" rows="3" class="form-control" required></textarea>
										</div>

									</div>

								</div>
								<button type="submit" data-btntext-sending="Sending..."
									class="btn btn-success btn-icon-split btn-sm">
									<span class="icon text-white-50">
										<i class="fas fa-check"></i>
									</span>
									<span class="text">Submit</span>
								</button>
								<button type="reset" data-dismiss="modal" aria-label="Close"
									class="btn btn-danger btn-icon-split btn-sm">
									<span class="icon text-white-50">
										<i class="fas fa-ban"></i>
									</span>
									<span class="text">Cancel</span>
								</button>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>


