<style>
.dataTables_length {
    width: 250px !important;
    float: left;
}
</style>
<div class="container-fluid">
    <div class="row mb-3">
		<div class="col-sm-6">
			<h1 class="h3 text-gray-800">Admin user Listing</h1>
		</div>
		<div class="col-sm-6">
            <a href="javascript:void(0);" data-toggle="modal" data-target="#addAdminModal"  class="btn btn-success btn-icon-split float-right mr-2"> 
                <span class="icon text-white-50">
                    <i class="fas fa-plus"></i>
                </span>
                <span class="text"> Add Admin </span> 
            </a>
		</div>
	</div>
    <!-- DataTables Example -->
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
                <h6 class="m-0 font-weight-bold text-primary pl-10">Admin User</h6> 
            </div>
        </div>
                
        <div class="card-body">
            <div id="forms_success_msg" class="w-100 alert alert-success alert-dismissible" style="display:none;"></div>
            <div id="forms_error_msg" class="w-100 alert alert-danger alert-dismissible" style="display:none;"></div>
            <div class="table-responsive">
                <table class="table table-bordered" id="tbl-admin-users-listing" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Sr No</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                    </thead>                
                    <tbody>
                        <?php
                        foreach($admin_users as $admin_key=>$admin) :
                        ?>
                        <tr>
                            <td><?=($admin_key+1)?></td>
                            <td><?=$admin->first_name.' '.$admin->last_name;?></td>
                            <td><?=$admin->email_id;?></td>
                            <td><?=$admin->role_obj ? $admin->role_obj->title : '-';?></td>
                            <td><?=date('d F y',strtotime($admin->created_at));?></td>
                            <td><div style='display:flex;'> <a href='javascript::void();' onclick='editAdminInfo("<?=$admin->id?>");'><i class='fas fa-fw fa-edit'></i></a>
                                <a href='javascript::void();' class='delete-record-custom' data-url="<?php echo base_url('order/admin/delete-admin-record/'.$admin->id)?>" title ='Delete This User'><span class='fas fa-fw fa-trash' aria-hidden='true'></span></a>
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
<div class="modal fade" id="addAdminModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
	aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<form  method="post" id="add-edit-admin-form">
			<div class="row">
                    <div class="col-lg-12">
                        <div class="card shadow">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary" >Add / Edit New Admin</h6>
                            </div>
                            <div class="card-body"> 
                                <div class="smart-forms smart-container">
                                    <div class="modal-body search-result">
                                    
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <label for="email_id" class="col-form-label">Email</label>
                                                    <input name="email_id" required="" type="email" class="form-control" id="email_id">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <label for="first_name" class="col-form-label">First Name</label>
                                                    <input required="" name="first_name" type="text" id="first-name" class="form-control">
                                                </div>
												<div class="col-sm-6">
                                                    <label for="last_name" class="col-form-label">Last Name</label>
                                                    <input required="" name="last_name" type="text" id="last-name" class="form-control">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <label for="role_id" class="col-form-label">Select Role</label>
                                                    <select id="user-role" required="" name="role_id" class="selectpicker" data-live-search="true" required>
														<option value="">Select Role</option>
														<?php foreach($users_roles as $users_role) {?>
															<option value="<?php echo $users_role->id;?>"> <?php echo $users_role->title;?></option>
														<?php }?>
													</select>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="password-check" style="display: none;">
											<div class="form-group">
												<div class="row">
													<div class="col-sm-6">
														<label for="password_update" class="col-form-label">Update Password</label>
													</div>
													<div class="col-sm-6 text-left">
														<input name="password_update" value="1" type="checkbox" class="form-control" id="password_update" style="width: 20px;height: 20px;">
													</div>
												</div>
											</div>
										</div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <label for="admin_password" class="col-form-label">Password</label>
                                                    <input required="" name="password" type="password" id="admin_password" class="form-control">
                                                </div>
												<div class="col-sm-6">
                                                    <label for="confirm-password" class="col-form-label">Confirm Password</label>
                                                    <input required="" name="confirm_password" type="password" id="confirm-password" class="form-control">
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
				<input type="hidden" name="admin_id" id="formId" value="">
			</form>
		</div>
	</div>
</div>

<script>

    function editAdminInfo(formId) 
    {
		$('#formId').val(formId);
        $('#page-preloader').css('background-color', 'rgba(0,0,0,.5)');
        $('#page-preloader').css('display', 'block');
		$("#password-edit").hide();
		$("#password-check").show();
        $.ajax({
            url: base_url + "order/admin/get_admin_details",
            type: "post",
            data: {
                admin_id: formId
            },
            success: function (response) {
                var res = jQuery.parseJSON(response);
                if(res.status) {
                    res_data = res.data;
					$('#email_id').val(res_data.email_id);
					$('#email_id').attr("readonly",true);
					$('#first-name').val(res_data.first_name);
					$('#last-name').val(res_data.last_name);
					$('select[name=role_id]').val(res_data.role_id);
					$('.selectpicker').selectpicker('refresh');
                }  
                $('#page-preloader').css('display', 'none');
                $('#addAdminModal').modal('show');
            }
        });
        return false;
	}
</script>
