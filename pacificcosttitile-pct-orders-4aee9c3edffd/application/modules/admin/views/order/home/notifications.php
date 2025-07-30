<?php 
    $userdata = $this->session->userdata('admin');
	$roleList = $this->common->getRoleList();
	$role_id = isset($userdata['role_id']) ? $userdata['role_id'] : 0;
	$roleName = $roleList[$role_id];
?>
<style>
.dataTables_length {
    width: 250px !important;
    float: left;
}
</style>
<div class="container-fluid">
    <!-- DataTables Example -->
    <div class="row mb-3">
		<div class="col-sm-6">
			<h1 class="h3 text-gray-800">Notifications</h1>
		</div>
        <?php  if (!in_array($roleName, ['CS Admin'])) : ?>
            <div class="col-sm-6">
                <a href="javascript:void(0);" data-export-type="csv" id="export_notification" class="btn btn-success btn-icon-split float-right mr-2"> 
                    <span class="icon text-white-50"><i class="fas fa-file-export"></i></span><span class="text">Export</span> </a>
            </div>
        <?php endif; ?>
	</div>
    <div class="card shadow mb-4">
        <div class="card-header datatable-header py-3">
            <div class="datatable-header-titles" > 
                <span>
                    <i class="fas fa-table"></i>
                </span>
                <h6 class="m-0 font-weight-bold text-primary pl-10">Notifications Listing</h6> 
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="tbl-notifications-listing" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Sr No</th>
                            <th>Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>                
                    <tbody></tbody>
                </table>
            </div>
        </div>

        <div class="modal fade" id="email_preview" tabindex="-1" role="dialog" aria-labelledby="Email Preview" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document" style="width:100%;">
                
                <div class="modal-content">
                    <div id="mail_preview"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function preview_email(notificationId)
	{
		$('#page-preloader').css('background-color', 'rgba(0,0,0,.5)');
		$('#page-preloader').css('display', 'block');
		$.ajax({
			url:base_url+"admin/order/home/email_preview",
			type: "post",
			data: {
				notificationId: notificationId
			},
			dataType: "html",
			success: function (response) {
				var results = JSON.parse(response);
				$('#mail_preview').html(results);
                $('#email_preview').modal('show');
				$('#page-preloader').css('display', 'none');
			}
		});
	}
</script>