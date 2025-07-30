<style>
	.task__category h3 {
		text-transform: uppercase;
		border-bottom: 1px solid #ababab;
		color: #111;
		font-weight: bold;
		margin-left: 30px;
		padding-bottom: 10px;
    margin-top: 30px;
	}
	.task__info label {
		display: block;
		position: relative;
		padding-left: 35px;
		margin-bottom: 12px;
		cursor: pointer;
		font-size: 22px;
		-webkit-user-select: none;
		-moz-user-select: none;
		-ms-user-select: none;
		user-select: none;
		}

		/* Hide the browser's default checkbox */
		.task__info label input {
		position: absolute;
		opacity: 0;
		cursor: pointer;
		height: 0;
		width: 0;
		}

		/* Create a custom checkbox */
		.checkmark {
		position: absolute;
		top: 12px;
		left: 0;
		height: 25px;
		width: 25px;
		/* background-color: #eee; */
		border: 1px solid #da7047;
		}

		.b-task-list__item {
			padding: 20px;
		}

		/* Create the checkmark/indicator (hidden when not checked) */
		.checkmark:after {
		content: "";
		position: absolute;
		display: none;
		}

		/* Show the checkmark when checked */
		.task__info label input:checked ~ .checkmark:after {
		display: block;
		}

		/* Style the checkmark/indicator */
		.task__info label .checkmark:after {
		left: 9px;
		top: 5px;
		width: 7px;
		height: 12px;
		border: solid #da7047;
		border-width: 0 3px 3px 0;
		-webkit-transform: rotate(45deg);
		-ms-transform: rotate(45deg);
		transform: rotate(45deg);
		}
		.task__info div {
			font-size: 16px;
			border-bottom: 1px solid #ababab;
			font-weight: normal;
			padding-bottom: 10px;
			padding-top: 5px;
		}
		.task__info label input:checked ~ .check_box_text {
			text-decoration: line-through;
		}
		.task__info.no__task {
    margin-left: 35px;
    font-size: 16px;
}
</style>
<section class="section-type-4a section-defaulta b-contact b-contact_mod-a" style="padding-bottom:0px;">
<div class="content">
    <div class="container">
        <div class="row mb-3">
            <div class="col-sm-6">
				<div class="typography-section__inner">
						<h2 class="ui-title-block ui-title-block_light">New Rep Checklist</h2>
                        <div class="ui-decor-1a bg-accent"></div>
                        <!-- <h3 class="ui-title-block_light">Please check the task which is completed.</h3> -->
				</div>
            </div>
        </div>
		<?php if(!empty($success)) {?>
			<div id="time_card_success_msg" class="w-100 alert alert-success alert-dismissible">
				<?php
				if(is_array($success)){
					foreach($success as $sucess) {
						echo $sucess."<br \>";	
					}
				} 
				else {
					echo $success;
				}
				?>
			</div>
		<?php } 
		if(!empty($errors)) {?>
			<div id="time_card_error_msg" class="w-100 alert alert-danger alert-dismissible">
				<?php foreach($errors as $error) {
					echo $error."<br \>";	
				}?>
			</div>
		<?php } ?>
		<form method="post" >
			<div class="row">
				<div class="col-md-12">
					<div class="b-task-list__item">
						<?php foreach($tasks as $task_cat): ?>
							<div class="task__category">
								<h3 class="m-0 font-weight-bold text-primary"><?php echo $task_cat->name; ?></h3>
							</div>
							<?php $cnt_task = 0; ?>
							<div class="task__info">
								<?php foreach($task_cat->tasks as $task): ?>
									<?php if($task->status && in_array($task->id,$hr_task_positions)) : ?>
										<?php $cnt_task++; ?>
										<label class="custom-control custom-checkbox"> 
											<input type="checkbox" class="custom-control-input" id="check_<?php echo $task->id; ?>" name="task_done[]" value="<?php echo $task->id; ?>" <?php if(in_array($task->id,$users_tasks)) echo "checked";?>>
											<div class="check_box_text"> <?php echo $task->name; ?></div>
											<span class="checkmark"></span>
											<!-- <label class="custom-control-label" for="check_<?php echo $task->id; ?>"><?php echo $task->name; ?></label> -->
										</label>
									<?php endif; ?>
								<?php endforeach;?>
							</div>
							<?php
								if($cnt_task == 0) : ?>
								<div class="task__info no__task">No Task Found</div>
							<?php endif; ?>
						<?php endforeach; ?>
						
						
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<button type="submit" class="btn btn-info btn-icon-split">
						<span class="text">Save</span>
					</button>
					<a href="<?php echo base_url().'hr/onboarding/employees'; ?>" class="btn btn-danger btn-icon-split">
						
						<span class="text">Cancel</span>
					</a>
					<div class="clearfix"></div>
				</div>
			</div>
		</form> 
    </div>
	<div class="typography-sectionab"></div>
</div>

</section>
