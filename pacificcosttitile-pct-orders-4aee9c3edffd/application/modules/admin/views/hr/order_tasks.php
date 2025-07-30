<style>
	.card .card-header[data-toggle=collapse] {
		border: none;
	}

	.card .custom-control-label {
		cursor: pointer;
		width: 100%;
	}

	.custom-checkbox input[type="checkbox"]:checked+label {
		text-decoration: line-through;
	}

	.custom-checkbox .custom-control-label::before,
	.custom-control-label::after {
		left: -2rem;
		width: 1.5rem;
		height: 1.5rem;
		top: 0;
		border-radius: 0;
	}

	.table td,
	.table th {
		border-top: none !important;
	}

	.btnFile {
		display: inline-block;
		margin-bottom: 0;
		font-weight: 400;
		text-align: center;
		vertical-align: middle;
		cursor: pointer;
		background-image: none;
		border: 1px solid transparent;
		white-space: nowrap;
		padding: 6px 12px;
		font-size: 14px;
		line-height: 1.42857143;
		border-radius: 4px;
		-webkit-user-select: none;
		-moz-user-select: none;
		-ms-user-select: none;
		user-select: none;
	}

	.glyphicon {
		position: relative;
		top: 1px;
		display: inline-block;
		font-family: 'Glyphicons Halflings';
		font-style: normal;
		font-weight: 400;
		line-height: 1;
		-webkit-font-smoothing: antialiased;
		-moz-osx-font-smoothing: grayscale;
	}

	.glyphicon-plus:before {
		content: "\2b";
	}

	.btnFile-success {
		color: #fff;
		font-weight: bold;
		text-align: center;
		padding: 5em 22em;
		margin: 10px 0px 0px 0px;
		color: #555;
		border: 2px dashed #cbd2d6;
		border-radius: 7px;
		margin-bottom: 20px;
		width: 97%;
	}

	.fileinput-button input {
		position: absolute;
		top: 0;
		right: 0;
		left: 0;
		margin: 0;
		opacity: 0;
		-ms-filter: 'alpha(opacity=0)';
		font-size: 108px;
		direction: ltr;
		cursor: pointer;
	}

	input[type=file] {
		display: block;
	}

	input[type="file"] {
		align-items: baseline;
		color: inherit;
		text-align: start;
	}

	input[type="file"] {
		-webkit-appearance: initial;
		padding: initial;
		background-color: initial;
		border: initial;
	}

	user agent stylesheet input, textarea, keygen, select, button, meter, progress {
		-webkit-writing-mode: horizontal-tb;
	}


	.ui-form-1 .form-control {
		border: 1px solid #cbd2d6;
		border-radius: 25px;
		color: #555;
	}
</style>
<div class="content">
	<div class="container-fluid">
		<div class="row mb-3">
			<div class="col-sm-8">
				<div class="row">
					<div class="col-sm-4">
						<h1 class="h3 text-gray-800">Order's Tasks</h1>
					</div>
					<div class="col-sm-3">
						<div class="progress mt-1">
							<div class="progress-bar custom__task_progress" role="progressbar" style="width: 0%"
								aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-4 text-right custom__task_button">
				<button type="button" class="btn btn-primary btn-sm task_check_all"><i class="fa fa-check"></i></button>
				<button type="button" class="btn btn-primary btn-sm task_un_check_all"><i
						class="fa fa-square"></i></button>
				<button type="button" class="btn btn-primary btn-sm task_show_all"><i class="fa fa-plus"></i></button>
				<button type="button" class="btn btn-primary btn-sm task_hide_all"><i class="fa fa-minus"></i></button>
			</div>
		</div>

		<?php if (!empty($success)) {?>
		<a href="#" class="btn btn-success btn-block mt-1 mb-3"><?php echo $success; ?></a>
		<?php }
		if (!empty($errors)) {?>
		<a href="#" class="btn btn-danger btn-block mt-1 mb-3"><?php echo $errors; ?></a>
		<?php }?>

		<div id="order_tasks_success_msg" class="btn btn-success btn-block mt-1 mb-3" style="display:none;"></div>
		<div id="order_tasks_error_msg" class="btn btn-danger btn-block mt-1 mb-3" style="display:none;"></div>

		<form method="post">
			<div class="row">
				<div class="col-md-12">
					<div class="card shadow mb-4">
						<div class="card-header py-3">
							<h6 class="m-0 font-weight-bold text-primary">Tasks List</h6>
						</div>
						<input type="hidden" name="order_id" id="order_id" value="<?php echo $orderInfo->id; ?>">
						<input type="hidden" name="file_id" id="file_id" value="<?php echo $orderInfo->file_id; ?>">
						<div class="card-body">
							<?php if (!empty($tasks)) {
							foreach ($tasks as $task) {
								if ($task['parent_task_id'] == 0) {
									$keys = array();
									$keys = array_keys(array_column($tasks, 'parent_task_id'), $task['id']);?>
							<div class="card custom__task_card">
								<div class="card-header py-3">
									<div class="row">
										<div class="col-sm-10">
											<div class="custom-control custom-checkbox">
												<input data-child="0" type="checkbox"
													class="custom-control-input custom__task_checkbox"
													id="check_<?php echo $task['id']; ?>" name="task_done[]"
													value="<?php echo $task['id']; ?>"
													<?php if (in_array($task['id'], $completedTaskIds)) {
														echo "checked";
													}
													?>>
												<label class="custom-control-label"
													for="check_<?php echo $task['id']; ?>"><?php echo $task['name']; ?></label>
											</div>
											<!-- <h6 class="m-0 font-weight-bold text-primary">Collapsable Card Example</h6> -->
										</div>
										<div class="col-sm-2 text-right">
											<a href="#collapseCard_<?php echo $task['id']; ?>" class=" card-header"
												data-toggle="collapse" role="button" aria-expanded="false"
												aria-controls="collapseCard_<?php echo $task['id']; ?>">

											</a>
										</div>
									</div>
								</div>
								<div class="collapse custom__task_collapse"
									id="collapseCard_<?php echo $task['id']; ?>">
									<div class="card-body">
										<!-- <?php if (empty($task['notes'])): ?>
															-
															<?php else: ?>
															<?php echo nl2br($task['notes']); ?>
														<?php endif;?> -->

										<?php $j = 0;if (!empty($keys)) {?>
										<div class="mb-4" id="sub_task_<?php echo $task['id']; ?>">
											<div class="card-header py-3 my-3">
												<h6 class="m-0 font-weight-bold text-primary" style="<?php echo ($task['id'] == 4 || $task['id'] == 6 || $task['id'] == 7) ? 'height:38px;' : ''; ?>"> Sub Tasks
													<?php if ($task['id'] == 4) {?>

													<a data-target="#borrower_information" data-toggle="modal"
														role="button" href="#"
														class="btn button btn-success btn-icon-split float-right"
														style="width:auto;float:right;">
														<span class="icon text-white-50">
															<i class="fa fa-paper-plane"></i>
														</span>
														<span class="text">Send Package</span>
													</a>

													<a data-target="#seller_welcome" data-toggle="modal"
														role="button" href="#"
														class="btn button btn-success btn-icon-split float-right mr-2"
														style="width:auto;float:right;">
														<span class="icon text-white-50">
															<i class="fa fa-paper-plane"></i>
														</span>
														<span class="text">Send Seller welcome</span>
													</a>
													<a data-target="#buyer_welcome" data-toggle="modal"
														role="button" href="#"
														class="btn button btn-success btn-icon-split float-right mr-2"
														style="width:auto;float:right;">
														<span class="icon text-white-50">
															<i class="fa fa-paper-plane"></i>
														</span>
														<span class="text">Send Buyer Welcome</span>
													</a>
													<?php } else if ($task['id'] == 6) {?>
													<a data-target="#borrower_information_payoff" data-toggle="modal"
														role="button" href="#"
														class="btn button btn-success btn-icon-split float-right"
														style="width:auto;float:right;">
														<span class="icon text-white-50">
															<i class="fa fa-paper-plane"></i>
														</span>
														<span class="text">Send Package</span>
													</a>
													<?php } else if ($task['id'] == 7) {?>
													<a data-target="#lender_information" data-toggle="modal"
														role="button" href="#"
														class="btn button btn-success btn-icon-split float-right"
														style="width:auto;float:right;">
														<span class="icon text-white-50">
															<i class="fa fa-paper-plane"></i>
														</span>
														<span class="text">Send Package</span>
													</a>
													<?php } ?>
												</h6>
											</div>
											<?php foreach ($keys as $key) {
                    							$j++;?>
											<div class="custom-control custom-checkbox" style="margin: 10px 25px;">
												<input data-child="1" data-parent-task="<?php echo $task['id']; ?>"
													type="checkbox" class="custom-control-input"
													id="check_<?php echo $tasks[$key]['id']; ?>" name="task_done[]"
													value="<?php echo $tasks[$key]['id']; ?>"
													<?php if (in_array($tasks[$key]['id'], $completedTaskIds)) {
														echo "checked";
													}
													?>>
												<label class="custom-control-label"
													for="check_<?php echo $tasks[$key]['id']; ?>"><?php echo $tasks[$key]['name']; ?></label>
											</div>
											<?php }?>
										</div>
										<?php }?>

										<div class="mb-4">
											<div class="card-header py-3 my-3">
												<h6 class="m-0 font-weight-bold text-primary" style="height: 38px;">
													Notes
													<a href="#note_<?php echo $task['id']; ?>" data-toggle="collapse"
														role="button" aria-expanded="false"
														aria-controls="note_<?php echo $task['id']; ?>" href="#"
														class="btn button btn-success btn-icon-split float-right"
														style="width:auto;float:right;">
														<span class="icon text-white-50">
															<i class="fa fa-plus"></i>
														</span>
														<span class="text">Add Note</span>
													</a>
													<?php if ($task['id'] == 62) { ?>
														<a data-target="#request_docs_information" data-toggle="modal"
															role="button" href="#"
															class="btn button btn-success btn-icon-split float-right"
															style="width:auto;float:right;margin-right:10px;">
															<span class="icon text-white-50">
																<i class="fa fa-plus"></i>
															</span>
															<span class="text">Send Docs Request</span>
														</a>
													<?php } ?>
												</h6>
											</div>

											<table class="table table-type-3 typography-last-elem no-footer  spacer-t30"
												style="margin: 10px 15px;">
												<thead>
													<tr>
														<th>#</th>
														<th>Subject</th>
														<th>Note</th>
														<th>Created</th>
													</tr>
												</thead>
												<tbody>
													<?php $j = 1;
													if (!empty($order_task_notes)) {
														foreach ($order_task_notes as $order_task_note) {
															if ($order_task_note['task_id'] == $task['id']) {
																?>
													<tr role="row" class="odd">
														<td><?php echo $j; ?></td>
														<td><?php echo $order_task_note['subject']; ?></td>
														<td>
														<?php echo $order_task_note['note']; ?>
														</td>
														<td>
														<?php echo date("m/d/Y", strtotime($order_task_note['created_at'])); ?>
														</td>
													</tr>
													<?php $j++;
															}
														}
													}
													if ($j == 1) {?>
													<tr role="row" class="odd">
														<td colspan="4" class="text-center">No notes found</td>
													</tr>
													<?php }?>
												</tbody>
											</table>

											<div class="collapse" id="note_<?php echo $task['id']; ?>"
												style="margin: 10px 25px;">
												<h5 class="m-0 font-weight-bold text-primary mt-3">Create a Note</h5>
												<div class="row mt-3">
													<div class="col-md-6">
														<div class="form-group">
															<label for="traning_name">Subject<span class="required">
																	*</span></label>
															<input type="text" class="form-control"
																placeholder="Subject"
																name="subject_<?php echo $task['id']; ?>"
																id="subject_<?php echo $task['id']; ?>">
														</div>
													</div>
												</div>
												<input type="hidden" name="num_of_notes_<?php echo $task['id']; ?>"
													id="num_of_notes_<?php echo $task['id']; ?>"
													value="<?php echo $i; ?>">
												<div class="row">
													<div class="col-md-6">
														<div class="form-group">
															<label for="traning_description">Note</label>
															<textarea class="form-control" placeholder="Note"
																name="note_desc_<?php echo $task['id']; ?>"
																id="note_desc_<?php echo $task['id']; ?>"></textarea>
														</div>
													</div>
												</div>

												<div class="row">
													<div class="col-md-6">
														<button
															onclick="return create_note(<?php echo $task['id']; ?>);"
															type="save" class="btn btn-info btn-icon-split">
															<span class="icon text-white-50">
																<i class="fas fa-save"></i>
															</span>
															<span class="text">Save</span>
														</button>
														<div class="clearfix"></div>
													</div>
												</div>
											</div>
										</div>

										<div class="mb-4">
											<div class="card-header py-3 my-3">
												<h6 class="m-0 font-weight-bold text-primary" style="height: 38px;">
													Documents
													<a href="#documents_<?php echo $task['id']; ?>" data-toggle="collapse"
														role="button" aria-expanded="false"
														aria-controls="documents_<?php echo $task['id']; ?>" href="#"
														class="btn button btn-success btn-icon-split float-right"
														style="width:auto;float:right;">
														<span class="icon text-white-50">
															<i class="fa fa-plus"></i>
														</span>
														<span class="text">Upload Documents</span>
													</a>
												</h6>
											</div>

											<div class="collapse" id="documents_<?php echo $task['id']; ?>" style="margin: 10px 25px;">
												<div class="form-reply ui-form-1 collapse" id="documents_<?php echo $task['id']; ?>">
													<div class="row">
														<div class="col-xs-12">
															<span class="btnFile btnFile-success fileinput-button" style="margin-top:10px;position:relative;">
																<i class="glyphicon glyphicon-plus"></i>
																<span>Drag and Drop files...</span>
																<input data-task_id="<?php echo $task['id']; ?>" type="file" name="document_files_<?php echo $task['id']; ?>[]" id="ufile_<?php echo $task['id']; ?>" multiple>
															</span>
															<div id="output_<?php echo $task['id']; ?>">
																<ul></ul>
															</div>
														</div>
													</div>
													<div class="row">
														<div class="col-md-6">
															<button
																onclick="return upload_documents(<?php echo $task['id']; ?>);"
																type="save" class="btn btn-info btn-icon-split">
																<span class="icon text-white-50">
																	<i class="fas fa-save"></i>
																</span>
																<span class="text">Upload Documents</span>
															</button>
															<div class="clearfix"></div>

														</div>
													</div>
												</div>

											</div>

											<table class="table table-type-3 typography-last-elem no-footer"
												style="margin: 10px 15px;">
												<thead>
													<tr>
														<th>#</th>
														<th>Document Name</th>
														<th>Action</th>
													</tr>
												</thead>
												<tbody>
													<?php $j = 1;
            if (!empty($borrowerDocuments)) {
                foreach ($borrowerDocuments as $document) {
                    if ($document['task_id'] == $task['id']) {
                        ?>
													<tr role="row" class="odd">
														<td><?php echo $j; ?></td>
														<td><?php echo $document['original_document_name']; ?></td>
														<td>
															<div class="custom__task_actions"
																style="display: inline-block;">
																<a target="_blank"
																	href="<?php echo env('AWS_PATH') . 'borrower/' . $document['document_name']; ?>"
																	class="btn button btn-info btn-icon-split">
																	<span class="icon text-white-50">
																		<i class="fas fa-eye"></i>
																	</span>
																	<span class="text">View</span>
																</a>
																<?php if ($document['api_document_id'] > 0) {?>
																<a href="#"
																	class="btn button btn-success btn-icon-split"
																	style="width:auto;">
																	<span class="icon text-white-50">
																		<i class="fa fa-check"></i>
																	</span>
																	<span class="text">Approved & Pushed</span>
																</a>
																<?php } else {?>
																<a style="width:auto;"
																	href="<?php echo base_url() . "hr/admin/upload-documet-resware/" . $document['id']; ?>"
																	class="btn button btn-success btn-icon-split">
																	<span class="icon text-white-50">
																		<i class="fa fa-check"></i>
																	</span>
																	<span class="text">Approve & Push</span>
																</a>
																<?php }?>
																<div class="clearfix"></div>
															</div>
														</td>
													</tr>
													<?php $j++;
                    }
                }
            }
            if ($j == 1) {?>
													<tr role="row" class="odd">
														<td colspan="4" class="text-center">No documents found</td>
													</tr>
													<?php }?>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
							<?php }
    }
} else {?>
							<div>No Task Found</div>
							<?php }?>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<button type="submit" class="btn btn-info btn-icon-split">
						<span class="icon text-white-50">
							<i class="fas fa-save"></i>
						</span>
						<span class="text">Save</span>
					</button>
					<a href="<?php echo base_url() . 'hr/admin/orders'; ?>" class="btn btn-secondary btn-icon-split">
						<span class="icon text-white-50">
							<i class="fas fa-arrow-right"></i>
						</span>
						<span class="text">Cancel</span>
					</a>
					<div class="clearfix"></div>
				</div>
			</div>
		</form>
	</div>
</div>
<div class="modal fade" width="500px" id="borrower_information" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document" style="width:40%;">
		<div class="modal-content">
			<form method="POST" action="<?php echo base_url(); ?>hr/admin/add-borrower-on-order"
				enctype="multipart/form-data">
				<div class="row">
					<div class="col-lg-12">
						<div class="card shadow mb-4">
							<div class="card-header py-3">
								<h6 class="m-0 font-weight-bold text-primary">Borrower Information</h6>
							</div>
							<input type="hidden" name="order_id" id="order_id" value="<?php echo $orderInfo->id; ?>">
							<input type="hidden" name="file_id" id="file_id" value="<?php echo $orderInfo->file_id; ?>">

							<div class="card-body">
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<label style="width:100%;">Select Package<span class="required">
													*</span></label>
											<input style="width:15px;height:15px;" class="" type="radio"
												name="package_type" value="buyer" required="">&nbsp;Buyer&nbsp;
											&nbsp; <input style="width:15px;height:15px;" class="" type="radio"
												name="package_type" value="Seller" required="">&nbsp;Seller&nbsp;
											&nbsp; </div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<label>Borrower Email<span class="required"> *</span></label>
											<input type="text" class="form-control" placeholder="Employee Id"
												name="borrower_email" id="borrower_email" value="" required="required">
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
<div class="modal fade" width="500px" id="borrower_information_payoff" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document" style="width:40%;">
		<div class="modal-content">
			<form method="POST" action="<?php echo base_url(); ?>hr/admin/add-borrower-on-order-for-payoff"
				enctype="multipart/form-data">
				<div class="row">
					<div class="col-lg-12">
						<div class="card shadow mb-4">
							<div class="card-header py-3">
								<h6 class="m-0 font-weight-bold text-primary">Borrower Information</h6>
							</div>
							<input type="hidden" name="order_id" id="order_id" value="<?php echo $orderInfo->id; ?>">
							<input type="hidden" name="file_id" id="file_id" value="<?php echo $orderInfo->file_id; ?>">

							<div class="card-body">
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<label>Borrower Email<span class="required"> *</span></label>
											<input type="text" class="form-control" placeholder="Borrower Email"
												name="borrower_email" id="borrower_email" value="" required="required">
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
<div class="modal fade" width="500px" id="lender_information" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document" style="width:40%;">
		<div class="modal-content">
			<form method="POST" action="<?php echo base_url(); ?>hr/admin/add-lender-on-order"
				enctype="multipart/form-data">
				<div class="row">
					<div class="col-lg-12">
						<div class="card shadow mb-4">
							<div class="card-header py-3">
								<h6 class="m-0 font-weight-bold text-primary">Lender Information</h6>
							</div>
							<input type="hidden" name="order_id" id="order_id" value="<?php echo $orderInfo->id; ?>">
							<input type="hidden" name="file_id" id="file_id" value="<?php echo $orderInfo->file_id; ?>">

							<div class="card-body">
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<label>Lender Email<span class="required"> *</span></label>
											<input type="text" class="form-control" placeholder="Lender Email"
												name="lender_email" id="lender_email" value="" required="required">
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

<div class="modal fade" width="500px" id="request_docs_information" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document" style="width:40%;">
		<div class="modal-content">
			<form method="POST" action="<?php echo base_url(); ?>hr/admin/send-request-docs"
				enctype="multipart/form-data">
				<div class="row">
					<div class="col-lg-12">
						<div class="card shadow mb-4">
							<div class="card-header py-3">
								<h6 class="m-0 font-weight-bold text-primary">Request Docs Information</h6>
							</div>
							<input type="hidden" name="order_id" id="order_id" value="<?php echo $orderInfo->id; ?>">
							<input type="hidden" name="file_id" id="file_id" value="<?php echo $orderInfo->file_id; ?>">

							<div class="card-body">
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<label>Email<span class="required"> *</span></label>
											<input type="text" class="form-control" placeholder="Email"
												name="email" id="email" value="" required="required">
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

<div class="modal fade" width="500px" id="buyer_welcome" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document" style="width:40%;">
		<div class="modal-content">
			<form method="POST" action="<?php echo base_url(); ?>hr/admin/add-buyer-on-order"
				enctype="multipart/form-data">
				<div class="row">
					<div class="col-lg-12">
						<div class="card shadow mb-4">
							<div class="card-header py-3">
								<h6 class="m-0 font-weight-bold text-primary">Buyer Information</h6>
							</div>
							<input type="hidden" name="order_id" id="order_id" value="<?php echo $orderInfo->id; ?>">
							<input type="hidden" name="file_id" id="file_id" value="<?php echo $orderInfo->file_id; ?>">

							<div class="card-body">
								<div id="buyer-info-clone-group-fields">
									<div class="toclone clone-widget">
										<div class="row">
											<div class="col-md-12">
												<div class="form-group">
													<label>Buyer Email<span class="required"> *</span></label>
													<input type="text" class="form-control" placeholder="Buyer Email"
														name="buyer_emails[]" id="buyer_email" value="" required="required">
												</div>
											</div>

											<div class="col-md-6">
												<div class="form-group">
													<label>First Name<span class="required"> *</span></label>
													<input type="text" class="form-control" name="buyer_first_names[]" id="buyer_first_name" placeholder="First Name" required="required">
												</div>
											</div>

											<div class="col-md-6">
												<div class="form-group">
													<label>Last Name<span class="required"> *</span></label>
													<input type="text" class="form-control" name="buyer_last_names[]" id="buyer_last_names" placeholder="Last Name" required="required">
												</div>
											</div>

											<div class="col-md-6">
												<div class="form-group">
													<input class="" type="radio" name="is_main_buyer" id="is_main_buyer" value="is_main_buyer0" required="required">&nbsp;&nbsp;Primary Buyer
												</div>
											</div>

										</div>
										<a href="#" style="height:fit-content;" class="mb-3 clone btn btn-success"><i class="fa fa-plus"></i></a>
											<a href="#" style="height:fit-content;" class="mb-3 delete btn btn-danger"><i class="fa fa-minus"></i></a>

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

<div class="modal fade" width="500px" id="seller_welcome" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document" style="width:40%;">
		<div class="modal-content">
			<form method="POST" action="<?php echo base_url(); ?>hr/admin/add-seller-on-order"
				enctype="multipart/form-data">
				<div class="row">
					<div class="col-lg-12">
						<div class="card shadow mb-4">
							<div class="card-header py-3">
								<h6 class="m-0 font-weight-bold text-primary">Seller Information</h6>
							</div>
							<input type="hidden" name="order_id" id="order_id" value="<?php echo $orderInfo->id; ?>">
							<input type="hidden" name="file_id" id="file_id" value="<?php echo $orderInfo->file_id; ?>">

							<div class="card-body">
								<div id="seller-info-clone-group-fields">
									<div class="toclone clone-widget">
										<div class="row">
											<div class="col-md-12">
												<div class="form-group">
													<label>Seller Email<span class="required"> *</span></label>
													<input type="text" class="form-control" placeholder="Seller Email"
														name="seller_emails[]" id="seller_email" value="" required="required">
												</div>
											</div>

											<div class="col-md-6">
												<div class="form-group">
													<label>First Name<span class="required"> *</span></label>
													<input type="text" class="form-control" name="seller_first_names[]" id="seller_first_name" placeholder="First Name" required="required">
												</div>
											</div>

											<div class="col-md-6">
												<div class="form-group">
													<label>Last Name<span class="required"> *</span></label>
													<input type="text" class="form-control" name="seller_last_names[]" id="seller_last_names" placeholder="Last Name" required="required">
												</div>
											</div>

											<div class="col-md-6">
												<div class="form-group">
													<input class="" type="radio" name="is_main_seller" id="is_main_seller" value="is_main_seller0" required="required">&nbsp;&nbsp;Primary Seller
												</div>
											</div>


										</div>
										<a href="#" style="height:fit-content;" class="mb-3 clone btn btn-success"><i class="fa fa-plus"></i></a>
											<a href="#" style="height:fit-content;" class="mb-3 delete btn btn-danger"><i class="fa fa-minus"></i></a>

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