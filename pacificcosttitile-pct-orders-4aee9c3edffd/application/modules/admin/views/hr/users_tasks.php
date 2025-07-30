<div class="content">
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-sm-6">
                <h1 class="h3 text-gray-800">New Rep Checklist</h1>
            </div>
        </div>
		<form method="post" >
			<div class="row">
				<div class="col-md-12">
					<div class="card shadow mb-4">
						<?php foreach($tasks as $task_cat): ?>
							<div class="card-header py-3">
								<h6 class="m-0 font-weight-bold text-primary"><?php echo $task_cat->name; ?></h6>
							</div>
							<div class="card-body">
								<?php $cnt_task = 0; ?>
								<?php foreach($task_cat->tasks as $task): ?>
									<?php if($task->status && in_array($task->id,$hr_task_positions)) : ?>
										<?php $cnt_task++; ?>
										<!-- <p><?php echo $task->name; ?></p> -->
										<div class="custom-control custom-checkbox">
											<input type="checkbox" class="custom-control-input" id="check_<?php echo $task->id; ?>" name="task_done[]" value="<?php echo $task->id; ?>" <?php if(in_array($task->id,$users_tasks)) echo "checked";?>>
											<label class="custom-control-label" for="check_<?php echo $task->id; ?>"><?php echo $task->name; ?></label>
										</div>
									<?php endif; ?>
									
								<?php endforeach;?>
								<?php
									if($cnt_task == 0) : ?>
									<div>No Task Found</div>
								<?php endif; ?>
							</div>
						<?php endforeach; ?>
						
						
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
					<a href="<?php echo base_url().'hr/admin/users'; ?>" class="btn btn-secondary btn-icon-split">
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


