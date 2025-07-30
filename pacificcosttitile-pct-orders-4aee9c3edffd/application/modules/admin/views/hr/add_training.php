<style>
	.remove_material_type {
		margin-bottom: 5px;
	}
	.bootstrap-select:not([class*="col-"]):not([class*="form-control"]):not(.input-group-btn) {
		width: -webkit-fill-available;
	}
</style>
<div class="content">
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-sm-6">
                <h1 class="h3 text-gray-800">Training</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Add New</h6>
                    </div>
                    <div class="card-body">
                        <form method="post" name="add_training_form" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="traning_name">Name<span class="required"> *</span></label>
                                        <input type="text" class="form-control" placeholder="Training Name / Titile" name="traning_name" id="traning_name" required="required">
                                    </div>
                                    <?php if(!empty($traning_name_error_msg)){ ?>       
                                        <div class="typography-line text-danger">
                                            <?php echo $traning_name_error_msg;?>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>

							<div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="traning_description">Description</label>
										<textarea class="form-control" placeholder="Training Description" name="traning_description" id="traning_description"></textarea>
                                    </div>
                                    <?php if(!empty($traning_description_error_msg)){ ?>       
                                        <div class="typography-line text-danger">
                                            <?php echo $traning_description_error_msg;?>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>

							<div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label style="width:100%;">User Selection<span class="required"> *</span></label>
										<input style="width:15px;height:15px;" class="" type="radio" name="user_selection" value="based_on_user_listing" required>&nbsp;&nbsp;&nbsp;Based On User Listing&nbsp;&nbsp;&nbsp;
										<input style="width:15px;height:15px;" class="" type="radio" name="user_selection" value="based_on_position_and_department" required>&nbsp;&nbsp;&nbsp;Based On Position And Department&nbsp;&nbsp;&nbsp;
                                    </div>
                                    <?php if(!empty($user_selection_error_msg)){ ?>  
                                        <div class="typography-line text-danger">
                                            <?php echo $user_selection_error_msg;?>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>

							<div class="row d-none" id="user_container">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="users">Select Users<span class="required"> *</span></label>
                                        <select name="users[]" class="selectpicker" multiple data-live-search="true" data-actions-box="true">
                                            <?php foreach($users as $user) {?>
                                                <?php $selected = '';
                                                    if(set_value('users') && in_array($user['id'], set_value('users')))  {
                                                        $selected = 'selected';
                                                    } 
                                                ?> 
                                                <option <?php echo $selected;?> value="<?php echo $user['id'];?>"><?php echo $user['first_name']." ".$user['last_name'];?></option>
                                            <?php }?>
                                        </select>
                                    </div>
                                    <?php if(!empty($users_error_msg)){ ?>       
                                        <div class="typography-line text-danger">
                                            <?php echo $users_error_msg;?>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>

							<div class="row d-none" id="department_container">
                                <div class="col-md-6">
                                    <div class="form-group">
										<label for="traning_department">Department <span class="required"> *</span></label>
										<select class="form-control" name="traning_department" id="traning_department">
											<option value="">Select Department</option>
											<option value="all">All Departments</option>

											<?php foreach($departments as $department): ?>
												<option value="<?php echo $department->id;?>"><?php echo $department->name; ?></option>
											<?php endforeach; ?>
										</select>
										<?php if(!empty($traning_department_error_msg)){ ?>       
                                        <div class="typography-line text-danger">
                                            <?php echo $traning_department_error_msg;?>
                                        </div>
                                    <?php } ?>
									</div>
								</div>
							</div>

							<div class="row d-none" id="position_container">
                                <div class="col-md-6">
                                    <div class="form-group">
										<label for="traning_position">Position </label>
										<select class="form-control" name="traning_position" id="traning_position">
											<option value="">Select Position</option>
											<?php foreach($positions as $position): ?>
												<option value="<?php echo $position->id;?>"><?php echo $position->name; ?></option>
											<?php endforeach; ?>
										</select>
										<?php if(!empty($traning_position_error_msg)){ ?>       
                                        <div class="typography-line text-danger">
                                            <?php echo $traning_position_error_msg;?>
                                        </div>
                                    <?php } ?>
									</div>
								</div>
							</div>

							<div class="row" id="training__material">
								<div class="col-md-6">
									<div class="form-group">
										<div class="card">
											<div class="card-header">Materials</div>
											<div class="card-body">
												<div class="material_container">
													
												</div>
												<div class="dropdown">
													<button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
														Add Material
													</button>
													<div class="dropdown-menu">
														<button type="button" class="dropdown-item select_material_type" value="file">Upload File</button>
														<button type="button" class="dropdown-item select_material_type" value="url">Enter Url</button>
													</div>
												</div>
											</div>
										</div>
										</select>
										<?php if(!empty($material_files_error)){ ?>  
											<?php foreach($material_files_error as $material_file_error): ?>     
												<div class="typography-line text-danger">
													<?php echo $material_file_error;?>
												</div>
											<?php endforeach; ?>
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
									<a href="<?php echo base_url().'hr/admin/training'; ?>" class="btn btn-secondary btn-icon-split">
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
