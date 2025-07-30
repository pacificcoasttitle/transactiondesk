<div class="content">
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-sm-6">
                <h1 class="h3 text-gray-800">Task Category</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Add Category</h6>
                    </div>
                    <div class="card-body">
                        <form method="post" name="add_task_category_form" >
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="category_name">Name<span class="required"> *</span></label>
                                        <input type="text" class="form-control" placeholder="Category Name" name="category_name" id="category_name" required="required">
                                    </div>
                                    <?php if(!empty($category_name_error_msg)){ ?>       
                                        <div class="typography-line text-danger">
                                            <?php echo $category_name_error_msg;?>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>

							<div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="category_description">Description</label>
										<textarea class="form-control" placeholder="Category Description" name="category_description" id="category_description"></textarea>
                                    </div>
                                    <?php if(!empty($category_description_error_msg)){ ?>       
                                        <div class="typography-line text-danger">
                                            <?php echo $category_description_error_msg;?>
                                        </div>
                                    <?php } ?>
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
									<a href="<?php echo base_url().'hr/admin/task-category'; ?>" class="btn btn-secondary btn-icon-split">
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


