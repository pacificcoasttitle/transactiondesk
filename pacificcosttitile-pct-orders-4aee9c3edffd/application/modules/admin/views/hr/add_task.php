<div class="content">
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-sm-6">
                <h1 class="h3 text-gray-800">Tasks</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Add Task</h6>
                    </div>
                    <div class="card-body">
                        <form method="post" name="add_task" >
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Task Name<span class="required"> *</span></label>
                                        <input type="text" class="form-control" placeholder="Task Name" name="name" id="name" required="required">
                                    </div>
                                    <?php if(!empty($name_error_msg)){ ?>       
                                        <div class="typography-line text-danger">
                                            <?php echo $name_error_msg;?>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label style="width:100%;">Product Type<span class="required"> *</span></label>
                                        <input style="width:15px;height:15px;" class="" type="radio" name="prod_type" value="loan" required>&nbsp;&nbsp;Loan&nbsp;&nbsp;
                                        <input style="width:15px;height:15px;" class="" type="radio" name="prod_type" value="sale" required>&nbsp;&nbsp;Sale&nbsp;&nbsp;
                                        <input style="width:15px;height:15px;" class="" type="radio" name="prod_type" value="both" required>&nbsp;&nbsp;Both&nbsp;&nbsp;
                                    </div>
                                    <?php if(!empty($prod_type_error_msg)){ ?>  
                                        <div class="typography-line text-danger">
                                            <?php echo $prod_type_error_msg;?>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>

                            <div class="row" id="tasks_container">
                                <div class="col-md-6">
                                    <div class="form-group">
										<label for="parent_task_id">Select Parent Task <span class="required"> </span></label>
										<select class="form-control" name="parent_task_id" id="parent_task_id">
											<option value="">Select Parent Task</option>
											<?php foreach($tasks as $task): ?>
												<option value="<?php echo $task->id;?>"><?php echo $task->name; ?></option>
											<?php endforeach; ?>
										</select>
									</div>
								</div>
							</div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Notes</label>
                                        <textarea rows="5" class="form-control" placeholder="Please enter Notes....." name="notes" id="notes"></textarea>
                                    </div>
                                </div>
                            </div>
                        
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
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


