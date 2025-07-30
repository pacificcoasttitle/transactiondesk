<style>

.draggable {
	cursor: move;
	margin-bottom: 1rem;
	user-select: none;

	/* Center the content */
	align-items: center;
	display: flex;
	justify-content: center;

	/* Size */
	height: 3rem;
	width: auto;

	/* Misc */
	border: 1px solid #cbd5e0;
	background-color: #f8f9fc;
}
.placeholder {
	background-color: #edf2f7;
	border: 2px dashed #cbd5e0;
	margin-bottom: 1rem;
}
</style>
<div class="content">
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-sm-8">
				<div class="row">
					<div class="col-sm-4">
						<h1 class="h3 text-gray-800">Tasks</h1>
					</div>
				</div>
            </div>
        </div>
		<?php if(!empty($success)) {?>
        	<a href="#" class="btn btn-success btn-block mt-1 mb-3"><?php echo $success;?></a>
		<?php }   
		if(!empty($errors)) {?>
			<a href="#" class="btn btn-danger btn-block mt-1 mb-3"><?php echo $errors;?></a>
		<?php } ?>
		
		<form method="post" action="<?php echo base_url('hr/admin/save-sale-tasks-position');?>" id="sale_position_task_form" name="sale_position_task_form">
			<div class="row">
				<div class="col-md-12">
					<div class="card shadow mb-4">
						<div class="card-header py-3">
							<h6 class="m-0 font-weight-bold text-primary">Sale Tasks List</h6>
						</div>

                        <div class="card-body" id="list">
                            <?php if (!empty($tasks)) {
								$i = 1;
                                foreach($tasks as $task) { 
                                    if($task['parent_task_id'] == 0) { ?> 
									<div class="draggable" id="<?php echo $task['id']?>">				
										<?php echo $task['name']; ?>
										<input type="hidden" name="task_position_<?php echo $task['id']?>" id="task_position_<?php echo $task['id']?>" value="<?php echo $i;?>">
									</div>
                                <?php  $i++; }}
                            } else { ?>
                                <div>No Task Found</div>
                            <?php } ?>
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
					<a href="<?php echo base_url().'hr/admin/tasks'; ?>" class="btn btn-secondary btn-icon-split">
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


