<style>
	.dataTables_length {
		width: 250px !important;
		float: left;
	}

	#fileUploadModal .form-control {
		height: auto;
	}

    .bootstrap-select:not([class*="col-"]):not([class*="form-control"]):not(.input-group-btn) {
        width: 100% !important;
    }
</style>
<div class="container-fluid">
    <!-- DataTables Example -->
    <div class="row mb-3">
		<div class="col-sm-6">
			<h1 class="h3 text-gray-800">Forms</h1>
		</div>
		<div class="col-sm-6">
            <a href="javascript:void(0);" data-export-type="csv" data-toggle="modal"
                    data-target="#fileUploadModal" class="btn btn-success btn-icon-split float-right mr-2"> 
                <span class="icon text-white-50"><i class="fa fa-upload"></i></span><span class="text">Upload</span> </a>
		</div>
	</div>
    <div class="card shadow mb-4">
        <div class="card-header datatable-header py-3">
            <div class="datatable-header-titles" > 
                <span>
                    <i class="fas fa-table"></i>
                </span>
                <h6 class="m-0 font-weight-bold text-primary pl-10">Forms</h6> 
            </div>
        </div>

		<div class="card-body">
			<?php
                if($this->session->flashdata('error')) :
                ?>
			<div class="alert alert-danger" role="alert"><?php echo $this->session->flashdata('error');?></div>
			<?php
                elseif($this->session->flashdata('success')):
                ?>
			<div class="alert alert-success" role="alert"><?php echo $this->session->flashdata('success');?></div>
			<?php
                endif;
            ?>
			<div id="forms_success_msg" class="w-100 alert alert-success alert-dismissible"
				style="display:none;"></div>
			<div id="forms_error_msg" class="w-100 alert alert-danger alert-dismissible"
				style="display:none;"></div>
			<div class="table-responsive">
				<table class="table table-bordered" id="tbl-file-documents-listing" width="100%" cellspacing="0">
					<thead>
						<tr>
							<th>Sr No</th>
							<th>Name</th>
							<th>Description</th>
							<th>Created At</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="fileUploadModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
	aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<form enctype="multipart/form-data" method="post">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Upload File</h5>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label for="file-upload" class="col-form-label">Select File:</label>
						<input required="" name="file" type="file" id="file-upload" class="form-control">
					</div>
					<div class="form-group">
						<label for="file-name" class="col-form-label">Enter Name:</label>
						<input name="name" required="" type="text" class="form-control" id="file-name">
					</div>
					<div class="form-group">
						<label for="title-officer" class="col-form-label">Select Title Officer</label>
						<div class="">
							<select required="" name="titleOfficers[]" class="selectpicker" multiple data-live-search="true">
                                <option value="all">All</option>
								<?php foreach($titleOfficers as $titleOfficer) {?>
                                    <option value="<?php echo $titleOfficer['id'];?>"> <?php echo $titleOfficer['first_name']." ".$titleOfficer['last_name'];?></option>
                                <?php }?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label for="file-description" class="col-form-label">Enter Description:</label>
						<textarea name="description" class="form-control" id="file-description"></textarea>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<button class="btn btn-primary">Upload</button>
				</div>
                <input type="hidden" name="formId" id="formId" value="">
			</form>
		</div>
	</div>
</div>
