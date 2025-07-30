<style type="text/css">
	.error {
		color: #FF2F0F;
	}
    th {
        text-align: center;
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
</style>
<!-- <section class="section-type-4a section-defaulta" style="padding-bottom:0px;"> -->
<div class="content">
	<h2 class="card-header">Create a Note</h2>
	<div class="card-body">
		<h3 class="ui-title-block_light fs-16 col-md-8 ">File Number <?php echo $orderDetails['file_number'];?></h3>
		<h3 class="ui-title-block_light fs-16 col-md-8 "><?php echo $orderDetails['full_address'];?></h3>
		
		<div class="mr-1 mt-3">
			<form class="form-reply ui-form-1" action="<?php echo base_url();?>create-note" method="POST" id="create-note" name="create-note">
				<div class="col-md-6 mt-2">
					<input class="form-control" type="text" name="subject" id="subject" placeholder="Subject" required>
					<input type="hidden" name="fileId" id="fileId" value="<?php echo $orderDetails['file_id']; ?>">
				</div>
				<div class="col-md-6 mt-2">
					<textarea class="form-control" rows="4" name="body" id="body" placeholder="Note" required></textarea>
				</div>
				<?php if (!empty($tasks)) { ?>
					<div class="col-md-6 mt-2">
						<select class="form-control" id="task_id" name="task_id" required>
							<option value="">Select Task</option>
							<?php foreach($tasks as $task) {?>
								<option value="<?php echo $task->id;?>"><?php echo $task->name;?></option>
							<?php } ?>
						</select>
				
					</div>
				<?php } ?>
				<div class="col-md-6 mt-2">
				<button type="submit" data-btntext-sending="Sending..."
									class="btn btn-info btn-round btn-icon-split btn-sm">
									<span class="icon text-white-50">
										<i class="fas fa-save"></i>
									</span>
									<span class="text">Save</span>
								</button>
					<!-- <button type="submit" class="btn btn-default btn-round btn-block">create</button> -->
				</div>
			</form>
			<div id="result"></div>
		</div>	
	</div>
	<div class="container-fluid">
		<div class="shadow mb-4">
			<div class="card-body">
				<div class="col-xs-12">
					<?php if(!empty($success)) {?>
					<div id="agent_success_msg" class="w-100 alert alert-success alert-dismissible">
						<?php foreach($success as $sucess) {
								echo $sucess."<br \>";	
							}?>
					</div>
					<?php } 
						if(!empty($errors)) {?>
					<div id="agent_error_msg" class="w-100 alert alert-danger alert-dismissible">
						<?php foreach($errors as $error) {
								echo $error."<br \>";	
							}?>
					</div>
					<?php } ?>
					<div class="loader"></div>

					<div class="mb-4">
						<div class="mr-1 mt-2">
							<div class="col-xs-12">
								<div class="">
									<span class="ui-title-block ui-title-block_light fs-28">Notes</span>
									<div class="ui-decor"></div>
									<span class="ui-title-block_light fs-16">Below is list of all your notes.</span>
								</div>
								<div class="typography-sectiona">
									<div class="col-md-12">
										<div class="table-container">
											<table class="table table_primary" id="notes_listing">
												<thead>
													<tr>
														<th style="width: 8%;">#</th>
														<th style="width: 23%;">Subject</th>
														<th style="width: 23%;">Note</th>
														<th style="width: 23%;">Task</th>
														<th style="width: 23%;">Created</th>
													</tr>
												</thead>
												<tbody>
													<?php if(!empty($notes)) {
														$i = 1;
														foreach($notes as $note) { ?>
														<tr>
															<td><?php echo $i;?></td>
															<td><?php echo $note['subject'];?></td>
															<td><?php echo $note['note'];?></td>
															<td><?php echo $note['name'];?></td>
															<td><?php echo date("m/d/Y", strtotime($note['created_at']));?></td>
														</tr>
														<?php $i++; } 
													} else { ?>
														<tr>
															<td colspan="5">No Records Found.</td>
														</tr>
													<?php }?>
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
</div>
<!-- </section> -->
	
