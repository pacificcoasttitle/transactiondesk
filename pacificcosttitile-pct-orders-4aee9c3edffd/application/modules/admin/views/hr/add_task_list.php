<style>
.multiselect-selected-text {
    float: left;
}
</style>
<div class="content">
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-sm-6">
                <h1 class="h3 text-gray-800">Task List</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Add Task</h6>
                    </div>
                    <div class="card-body">
                        <form method="post" name="add_task_list_form" >
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="task_name">Name<span class="required"> *</span></label>
                                        <input type="text" class="form-control" placeholder="Task Name" name="task_name" id="task_name" required="required">
                                    </div>
                                    <?php if(!empty($task_name_error_msg)){ ?>       
                                        <div class="typography-line text-danger">
                                            <?php echo $task_name_error_msg;?>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>

							<div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="task_description">Description</label>
										<textarea class="form-control" placeholder="Task Description" name="task_description" id="task_description"></textarea>
                                    </div>
                                    <?php if(!empty($task_description_error_msg)){ ?>       
                                        <div class="typography-line text-danger">
                                            <?php echo $task_description_error_msg;?>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>

							<div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
										<label for="task_category">Category <span class="required"> *</span></label>
										<select class="form-control" name="task_category" id="task_category" required>
											<option value="">Select Category</option>
											<?php foreach($task_list_category as $category): ?>
												<option value="<?php echo $category->id;?>"><?php echo $category->name; ?></option>
											<?php endforeach; ?>
										</select>
										<?php if(!empty($task_category_error_msg)){ ?>       
                                        <div class="typography-line text-danger">
                                            <?php echo $task_category_error_msg;?>
                                        </div>
                                    <?php } ?>
									</div>
								</div>
							</div>

							<div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
										<label for="task_position" style="display: block;">For EMployee Position <span class="required"> *</span></label>
										<select class="form-control" name="task_position[]" id="task_position" required multiple>
											<!-- <option value="">Select Position</option> -->
											<?php foreach($users_position as $position): ?>
												<option value="<?php echo $position->id;?>"><?php echo $position->name; ?></option>
											<?php endforeach; ?>
										</select>
										<?php if(!empty($task_position_error_msg)){ ?>       
                                        <div class="typography-line text-danger">
                                            <?php echo $task_position_error_msg;?>
                                        </div>
                                    <?php } ?>
									</div>
								</div>
							</div>
                        
							<div class="row">
                                <div class="col-md-6">
									<div class="form-check form-group">
										<input type="checkbox" class="form-check-input" id="check_status" name="check_status" value="1" checked>
										<label class="form-check-label" for="check_status">Active</label>
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
									<a href="<?php echo base_url().'hr/admin/task-list'; ?>" class="btn btn-secondary btn-icon-split">
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
            </div>
        </div>
    </div>
</div>


