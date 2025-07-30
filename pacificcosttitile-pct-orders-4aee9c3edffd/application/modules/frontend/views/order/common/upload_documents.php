<style type="text/css">
    th {
        text-align: center;
    }

	.table-container {
		overflow-y: initial !important;
	}

	td {
        text-align: center;
    }

	.ui-form-1 .form-control {
		border: 1px solid #eee;
		border-radius: 10px;
	}

	.fs-28 {
	    font-size: 28px;
	}

	.fs-16 {
		font-size: 16px;
	}

	.ui-decor {
		margin-top: 22px;
		margin-bottom: 22px;
		background: #36b9cc;
		width: 60px;
		height: 5px;
 	}

	.m-50 {
		margin: 50px;
	}
</style>

<!-- <section class="section-type-4a section-defaulta" style="padding-bottom:0px;"> -->
<div class="content">
	<h2 class="card-header">Upload a Document
		<a style="float:right" class="btn-success btn-icon-split btn-sm " href="<?php echo base_url();?>fees">
			<span class="icon text-white-50">
				<i class="fa fa-arrow-left"></i>
			</span>
			<span class="text">Back</span>
		</a>
	</h2>
	
	<div class="card-body">
		<!-- <div class="ui-decor-1a bg-primary"></div> -->
		<h3 class="ui-title-block_light fs-16 col-md-8">File Number <?php echo $orderDetails['file_number'];?></h3>
		<h3 class="ui-title-block_light fs-16 col-md-8"><?php echo $orderDetails['full_address'];?></h3>

		<div class="mr-1 mt-3">
			<?php if(!empty($success)) {?>
				<div id="agent_success_msg" class="w-100 alert alert-success alert-dismissible" >
					<?php foreach($success as $sucess) {
						echo $sucess."<br \>";	
					}?>
				</div>
			<?php } 
				if(!empty($errors)) {?>
				<div id="agent_error_msg" class="w-100 alert alert-danger alert-dismissible" >
					<?php foreach($errors as $error) {
						echo $error."<br \>";
					}?>
				</div>
			<?php } ?>
			<div class="loader"></div>
			
			<!-- <div class="typography-sectiona">
				<div class="col-md-12">
					<div class="table-container">
						<div class="typography-sectione typography-section-border">
							<div class="container">
								<div class="row">
									<form id="files_upload" action="<?php echo base_url();?>files-upload" method="POST" enctype="multipart/form-data">
										<div class="col-md-12">
											<a href="">
												<button id="up_btn" style="color: #c7c7c7;width: 220px;" class="btn btn-grad-2a" type="submit">Upload Documents</button>
											</a>
										</div>
										<input type="hidden" id="file_id" name="file_id" value="<?php echo $orderDetails['file_id'];?>">
										<input type="hidden" id="order_id" name="order_id" value="<?php echo $orderDetails['order_id'];?>">
										
										<?php for($i= 1; $i <= 4; $i++) { ?>	
											<div class="col-md-6">
												<blockquote class="b-blockquote b-blockquote-3">
													<footer class="b-blockquote-3__footer">
														<cite class="b-blockquote-3__cite"
															title="Blockquote Title"><span
																class="b-blockquote-3__author">Document
																#<?php echo $i;?></span></cite>
													</footer>
													<div class="b-blockquote-3__content">
														<div class="header-language-nav dropdown">
															<select name="document_type_<?php echo $i;?>" id="document_type_<?php echo $i;?>" class="dropdown-toggle" <?php echo $i == 1 ? 'required="required"' : '';?>>
																<option value="">DOCUMENT TYPE:</option>
																<?php foreach($documentTypes as $documentType) { ?>
																<option
																	value="<?php echo $documentType['api_id'];?>">
																	<?php echo $documentType['name'];?></option>
																<?php } ?>
															</select>
															<span style="color:red;">*</span>
														</div>
														<div class="input-group" style="width: 100%;margin-left: 14%;">
															<div class="custom-file"  style="float:left;">
																<input type="file" accept=".doc,.docx,.gif,.msg,.pdf,.tif,.tiff,.xls,.xlsx,.xml" class="custom-file-input"
																	id="document_<?php echo $i;?>" name="document_<?php echo $i;?>" <?php echo $i == 1 ? 'required="required"' : '';?>>
															</div>
															<span style="color:red;float:left;">*</span>
														</div>
														<div class="header-language-nav dropdown">
															<div class="" style="margin-top: 10px;float:left;">
																<textarea name="description_<?php echo $i;?>" id="description_<?php echo $i;?>" placeholder="DESCRIPTION" rows="3" cols="52" type="file"
																	class="custom-file-input dropdown-toggle" id="inputGroupFile01"
																	style="border: 1px solid rgba(238,238,238);" <?php echo $i == 1 ? 'required="required"' : '';?>></textarea>
															</div>
															<span style="color:red;float:left;">*</span>
														</div>
														<?php if (!empty($tasks)) { ?>
															<div class="header-language-nav dropdown">
																<select style="width:100%;" name="task_id_<?php echo $i;?>" id="task_id_<?php echo $i;?>" class="dropdown-toggle">
																	<option value="">Select Task:</option>
																	<?php foreach($tasks as $task) { ?>
																		<option value="<?php echo $task->id;?>"><?php echo $task->name;?></option>
																	<?php } ?>
																</select>
															</div>
														<?php } ?>
													</div>
												</blockquote>
											</div>
										<?php } ?>
										
										<div class="col-md-12">
											<a href="">
												<button id="down_btn" style="color: #c7c7c7;width: 220px;" class="btn btn-grad-2a" type="submit">Upload Documents</button>
											</a>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div> -->
		</div>

		<div class="m-50" >
			<form id="files_upload" action="<?php echo base_url();?>files-upload" method="POST" enctype="multipart/form-data" class="form-reply ui-form-1" >
				<input type="hidden" id="file_id" name="file_id" value="<?php echo $orderDetails['file_id'];?>">
				<input type="hidden" id="order_id" name="order_id" value="<?php echo $orderDetails['order_id'];?>">
				<div class="row">
				<?php for($i= 1; $i <= 4; $i++) { ?>	
					<div class="col-md-6 <?php if ($i > 2) { ?> mt-5 <?php } ?>">
						<!-- <div class="col-md-12"> -->
							<div class="form-group">
								<label>Document #<?php echo $i;?><?php if ($i == 1) { ?> <span class="required"> * </span> <?php } ?></label>
								<select class="form-control mb-2" name="document_type_<?php echo $i;?>" id="document_type_<?php echo $i;?>" class="dropdown-toggle" <?php echo $i == 1 ? 'required="required"' : '';?>>
									<option value="">DOCUMENT TYPE:</option>
									<?php foreach($documentTypes as $documentType) { ?>
									<option
										value="<?php echo $documentType['api_id'];?>">
										<?php echo $documentType['name'];?></option>
									<?php } ?>
								</select>
								<input type="file" accept=".doc,.docx,.gif,.msg,.pdf,.tif,.tiff,.xls,.xlsx,.xml" class="form-control mb-2"
											id="document_<?php echo $i;?>" name="document_<?php echo $i;?>" <?php echo $i == 1 ? 'required="required"' : '';?>>
								<textarea name="description_<?php echo $i;?>" id="description_<?php echo $i;?>" placeholder="DESCRIPTION" rows="3" cols="52" type="file"
											class="form-control dropdown-toggle mb-2" id="inputGroupFile01" <?php echo $i == 1 ? 'required="required"' : '';?>></textarea>
								<?php if (!empty($tasks)) { ?>
									<div class="header-language-nav dropdown">
										<select style="width:100%;" name="task_id_<?php echo $i;?>" id="task_id_<?php echo $i;?>" class="form-control dropdown-toggle">
											<option value="">Select Task:</option>
											<?php foreach($tasks as $task) { ?>
												<option value="<?php echo $task->id;?>"><?php echo $task->name;?></option>
											<?php } ?>
										</select>
									</div>
								<?php } ?>
						</div>
					</div>
					<!-- <div class="col-md-2"></div> -->
				<?php } ?>
				</div>
				<div class="col-md-12">
					<button type="submit" data-btntext-sending="Sending..." class="btn btn-info btn-round btn-icon-split btn-sm">
						<span class="icon text-white-50">
							<i class="fas fa-upload"></i>
						</span>
						<span class="text">Upload Documents</span>
					</button>
				</div>
			</form>
		</div>
	</div>

</div>
<!-- </section> -->
<!-- <section class="section-type-4a section-defaulta" style="padding-bottom:0px;"> -->
<div class="container-fluid">
	<div class="shadow mb-4">
		<div class="card-body">
			<div class="col-xs-12">
				<div class="mb-4">
					<div class="mr-1 mt-2">
						<div class="col-xs-12">
							<div class="">
								<h2 class="ui-title-block ui-title-block_light fs-28">Documents</h2>
								<div class="ui-decor"></div>
								<h3 class="ui-title-block_light fs-16">Below is list of all your documents.</h3>
							</div>
							<div class="typography-sectiona">
								<div class="col-md-12">
									<div class="table-container">
										<table class="table table_primary" id="document_listing">
											<thead>
												<tr>
													<th>#</th>
													<th>Document Name</th>
													<?php $userdata = $this->session->userdata('user'); 
														if ($userdata['is_escrow_officer'] == 1 || $userdata['is_escrow_assistant'] == 1) { ?>
															<th>Uploaded By Borrower</th>
													<?php } ?>
													<th>Created</th>
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
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- </section> -->
	


