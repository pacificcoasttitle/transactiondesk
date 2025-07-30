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
    <!-- DataTables Example -->
    <div class="row mb-3">
		<div class="col-sm-6">
			<h1 class="h3 text-gray-800">Proposed Insured Branches</h1>
		</div>
		<div class="col-sm-6">
            <a href="javascript:void(0);" data-toggle="modal"
                    data-target="#addBranchModal" class="btn btn-success btn-icon-split float-right mr-2"> 
                <span class="icon text-white-50"><i class="fas fa-file-export"></i></span><span class="text"> Add </span> </a>
		</div>
	</div>
    <div class="card shadow mb-4">
        <div class="card-header datatable-header py-3">
            <div class="datatable-header-titles" > 
                <span>
                    <i class="fas fa-table"></i>
                </span>
                <h6 class="m-0 font-weight-bold text-primary pl-10">Proposed Insured Branches</h6> 
            </div>
        </div>

		<div class="card-body">
			<?php if($this->session->flashdata('error')) :?>
			    <div class="alert alert-danger" role="alert"><?php echo $this->session->flashdata('error');?></div>
			<?php elseif($this->session->flashdata('success')): ?>
			    <div class="alert alert-success" role="alert"><?php echo $this->session->flashdata('success');?></div>
			<?php endif; ?>

			<div id="forms_success_msg" class="w-100 alert alert-success alert-dismissible" style="display:none;"></div>
			<div id="forms_error_msg" class="w-100 alert alert-danger alert-dismissible" style="display:none;"></div>

			<div class="table-responsive">
				<table class="table table-bordered cusom__common__datatable" id="tbl-admin-users-listing" width="100%" cellspacing="0">
					<thead>
						<tr>
							<th>Sr No</th>
							<th>Address</th>
							<th>City</th>
							<th>State</th>
                            <th>Zip</th>
							<th>Created At</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($proposed_branches as $branch_key=>$branch) : ?>
                            <tr>
                                <td><?=($branch_key+1)?></td>
                                <td><?=$branch->address;?></td>
                                <td><?=$branch->city;?></td>
                                <td><?=$branch->state;?></td>
                                <td><?=$branch->zip;?></td>
                                <td><?=date('d F y',strtotime($branch->created_at));?></td>
                                <td><div style='display:flex;'> <a href='javascript::void();' onclick='editBranchInfo("<?=$branch->id?>");'><i class='fas fa-fw fa-edit'></i></a>
                                    
                                    <a href="javascript:void(0);"  class='delete-record-custom' data-url="<?php echo base_url('order/admin/delete-proposed-branch/'.$branch->id)?>" title ='Delete This User'><span class='fas fa-fw fa-trash' aria-hidden='true'></span></a>
                                    </div>
                                </td>
                            </tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="addBranchModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
	aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<form  method="post" id="add-edit-branch-form">
				<div class="modal-header">
					<h5 class="modal-title" id="branch_title">Add New Branch</h5>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label for="address" class="col-form-label">Address</label>
						<input required="" name="address" type="text" id="address" class="form-control">
					</div>
					<div class="form-group">
						<label for="address" class="col-form-label">City</label>
						<input required="" name="city" type="text" id="city" class="form-control">
					</div>
					<div class="form-group">
						<label for="address" class="col-form-label">Zip</label>
						<input required="" name="zip" type="text" id="zip" class="form-control">
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<button class="btn btn-primary">Submit</button>
				</div>
                <input type="hidden" name="branch_id" id="branch_id" value="">
			</form>
		</div>
	</div>
</div>

<script>

    function editBranchInfo(branch_id) 
    {
		$('#branch_id').val(branch_id);
        $('#page-preloader').css('background-color', 'rgba(0,0,0,.5)');
        $('#page-preloader').css('display', 'block');
		$("#password-edit").hide();
		$("#password-check").show();
        $.ajax({
            url: base_url + "order/admin/get_branch_details",
            type: "post",
            data: {
                branch_id: branch_id
            },
            success: function (response) {
                var res = jQuery.parseJSON(response);
                if(res.status) {
                    res_data = res.data;
					$('#address').val(res_data.address);
					$('#city').val(res_data.city);
					$('#zip').val(res_data.zip);
                }  
                $('#page-preloader').css('display', 'none');
                $('#addBranchModal').modal('show');
            }
        });
        return false;
	}
</script>
