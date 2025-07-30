<style>
	.dataTables_length {
		width: 250px !important;
		float: left;
	}

    .bootstrap-select:not([class*="col-"]):not([class*="form-control"]):not(.input-group-btn) {
        width: 100% !important;
    }
</style>
<div class="container-fluid">
	<div class="row mb-3">
		<div class="col-sm-6">
			<h1 class="h3 text-gray-800">Roles Listing</h1>
		</div>
		<div class="col-sm-6">
            <a href="javascript:void(0);" data-toggle="modal" data-target="#addRoleModal"  class="btn btn-success btn-icon-split float-right mr-2"> 
                <span class="icon text-white-50">
                    <i class="fas fa-plus"></i>
                </span>
                <span class="text"> Add Role </span> 
            </a>
		</div>
	</div>
	<div class="card shadow mb-4">
        <?php if($this->session->flashdata('error')) : ?>
            <div class="alert alert-danger" role="alert"><?php echo $this->session->flashdata('error');?></div>
        <?php elseif($this->session->flashdata('success')): ?>
            <div class="alert alert-success" role="alert"><?php echo $this->session->flashdata('success');?></div>
        <?php endif; ?>
        <div class="card-header datatable-header py-3">
            <div class="datatable-header-titles" > 
                <span>
                    <i class="fas fa-users"></i>
                </span>
                <h6 class="m-0 font-weight-bold text-primary pl-10">Roles</h6> 
            </div>
        </div>
                
        <div class="card-body">
            <div id="forms_success_msg" class="w-100 alert alert-success alert-dismissible" style="display:none;"></div>
            <div id="forms_error_msg" class="w-100 alert alert-danger alert-dismissible" style="display:none;"></div>
            <div class="table-responsive">
                <table class="table table-bordered" id="tbl-roles-users-listing" width="100%" cellspacing="0">
                    <thead>
                        <tr>
							<th>Sr No</th>
							<th>Title</th>
							<th>Created At</th>
							<th>Action</th>
                        </tr>
                    </thead>                
                    <tbody>
						<?php
						foreach($users_roles as $role_key=>$role) :
						?>
						<tr>
							<td><?=($role_key+1)?></td>
							<td><?=$role->title;?></td>
							
							<td><?=date('d F y',strtotime($role->created_at));?></td>
							<td><div style='display:flex;'> <a href='javascript::void();' onclick='editRoleInfo("<?=$role->id?>","<?=$role->title?>");'><i class='fas fa-fw fa-edit'></i></a>
								<?php if($role->id != 1 && $role->id != 2) : ?>
								<a href='javascript::void();'  class='delete-record-custom' data-url="<?php echo base_url('order/admin/delete-role-record/'.$role->id)?>" title ='Delete This User'><span class='fas fa-fw fa-trash' aria-hidden='true'></span></a>
								<?php endif; ?>
								</div>
							</td>
						</tr>
						<?php
						endforeach;
						?>
					</tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="addRoleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
	aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<form  method="post" id="add-edit-role-form">
				<div class="row">
					<div class="col-lg-12">
						<div class="card shadow">
							<div class="card-header py-3">
								<h6 class="m-0 font-weight-bold text-primary" >Add / Edit Role</h6>
							</div>
							<div class="card-body"> 
								<div class="smart-forms smart-container">
									<div class="modal-body search-result">
									
										<div class="form-group">
											<div class="row">
												<div class="col-sm-6">
													<label for="role-title" class="col-form-label">Title</label>
													<input name="title" required="" type="text" class="form-control" id="role-title">
												</div>
											</div>
										</div>
									</div>
									<div class="form-footer" style="padding: 0px 1rem !important;">
										<button type="submit" data-btntext-sending="Sending..." class="btn btn-success btn-icon-split btn-sm">
											<span class="icon text-white-50">
												<i class="fas fa-check"></i>
											</span>
											<span class="text">Submit</span>
										</button>
										<button type="reset" data-dismiss="modal" aria-label="Close" class="btn btn-danger btn-icon-split btn-sm">
											<span class="icon text-white-50">
												<i class="fas fa-ban"></i>
											</span>
											<span class="text">Cancel</span>
										</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				
                <input type="hidden" name="role_id" id="formId" value="">
			</form>
		</div>
	</div>
</div>

<script>

    function editRoleInfo(formId,title) 
    {
		$('#formId').val(formId);
        $('#page-preloader').css('background-color', 'rgba(0,0,0,.5)');
        $('#page-preloader').css('display', 'block');
		$('#role-title').val(title);
		$('#page-preloader').css('display', 'none');
        $('#addRoleModal').modal('show');
		
       
        return false;
	}
</script>
