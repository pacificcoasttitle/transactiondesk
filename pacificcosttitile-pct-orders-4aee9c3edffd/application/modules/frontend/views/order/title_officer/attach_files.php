<style type="text/css">
	#fileUploadModal .form-control {
		border: 1px solid;
	}

	#fileUploadModal .btn-default {
		color: #222222;
	}

	#orders_listing_filter {
		display: none;
	}

</style>
<section class="section-type-4a section-defaulta" style="padding-bottom:0px;">
	<div class="container-fluid">
		<div class="row mb-3">
			<div class="col-sm-6">
				<h1 class="h3 text-gray-800"> Forms </h1>
			</div>
		</div>
		<div class="card shadow mb-4">
			<div class="card-header datatable-header py-3">
				<div class="datatable-header-titles" > 
					<span>
						<i class="fas fa-users"></i>
					</span>
					<h6 class="m-0 font-weight-bold text-primary pl-10">Below you will find all uploaded fiels</h6> 
				</div>
			</div>
		
			<div class="card-body">
				<?php if($this->session->flashdata('error')) :?>
					<div class="alert alert-danger" role="alert"><?php echo $this->session->flashdata('error');?></div>
				<?php elseif($this->session->flashdata('success')): ?>
					<div class="alert alert-success" role="alert"><?php echo $this->session->flashdata('success');?></div>
				<?php endif; ?>
				<div class="table-responsive">
					<table class="table table-bordered" id="title_officer_forms_listing" width="100%" cellspacing="0">
						<thead>
							<tr>
								<th>#</th>
								<th>Name</th>
								<th>Description</th>
								<th>Created At</th>
								<th>Download</th>
							</tr>
						</thead>                
						<tbody></tbody>
					</table>
				</div>
			</div>
		</div>
		<!-- <div class="row mb-3">
			<div class="col-xs-12">
				<div class="typography-section__inner">
					<h2 class="ui-title-block ui-title-block_light"><span>Forms</span>
					</h2>
					<div class="ui-decor-1a bg-primary"></div>
					<h3 class="ui-title-block_light">Below you will find all uploaded fiels</h3>
				</div>
				<?php if($this->session->flashdata('error')) :?>
					<div class="alert alert-danger" role="alert"><?php echo $this->session->flashdata('error');?></div>
				<?php elseif($this->session->flashdata('success')): ?>
					<div class="alert alert-success" role="alert"><?php echo $this->session->flashdata('success');?></div>
				<?php endif; ?>

				<div class="typography-sectiona">
					<div class="col-md-12">
						<div class="table-container">
							<table class="table table_primary" id="title_officer_forms_listing">
								<thead>
									<tr>
										<th>#</th>
										<th>Name</th>
										<th>Description</th>
										<th>Created At</th>
										<th>Download</th>
									</tr>
								</thead>
								<tbody></tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div> -->
	</div>
</section>
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
						<label for="file-description" class="col-form-label">Enter Description:</label>
						<textarea name="description" class="form-control" id="file-description"></textarea>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button class="btn btn-warning">Upload</button>
				</div>
			</form>
		</div>
	</div>
</div>
