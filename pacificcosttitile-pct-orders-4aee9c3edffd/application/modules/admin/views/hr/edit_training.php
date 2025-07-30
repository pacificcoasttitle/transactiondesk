<style>
	.remove_material_type,.remove_material_type_exist {
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
                <h1 class="h3 text-gray-800">Traning</h1>
            </div>
        </div>
		<?php if(!empty($success)) {?>
        <a href="#" class="btn btn-success btn-block mt-1 mb-3"><?php echo $success;?></a>
    <?php }   
     ?>
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Edit</h6>
                    </div>
                    <div class="card-body">
                        <form method="post" name="edit_task_list_form" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="traning_name">Name<span class="required"> *</span></label>
                                        <input type="text" class="form-control" value="<?php echo $record->name;?>" placeholder="Traning Name / Titile" name="traning_name" id="traning_name" required="required">
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
										<textarea class="form-control" placeholder="Traning Description" name="traning_description" id="traning_description"><?php echo $record->description;?></textarea>
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
										<input style="width:15px;height:15px;" <?php echo $record->user_selection == 'based_on_user_listing' ? 'checked' : '';?> class="" type="radio" name="user_selection" value="based_on_user_listing" disabled >&nbsp;&nbsp;&nbsp;Based On User Listing&nbsp;&nbsp;&nbsp;
										<input style="width:15px;height:15px;" <?php echo $record->user_selection == 'based_on_position_and_department' ? 'checked' : '';?> class="" type="radio" name="user_selection" value="based_on_position_and_department" disabled >&nbsp;&nbsp;&nbsp;Based On Position And Department&nbsp;&nbsp;&nbsp;
                                    </div>
                                    <?php if(!empty($user_selection_error_msg)){ ?>  
                                        <div class="typography-line text-danger">
                                            <?php echo $user_selection_error_msg;?>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>

							<div class="row <?php echo $record->user_selection == 'based_on_user_listing' ? '' : 'd-none';?>" id="user_container">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="users">Select Users<span class="required"> *</span></label>
                                        <select name="users[]" class="selectpicker" multiple data-live-search="true" data-actions-box="true" <?php echo $record->user_selection == 'based_on_user_listing' ? 'required' : '';?>>
                                            <?php foreach($users as $user) {?>
                                                <?php $selected = '';
													print_r($trainingUsers);
                                                    if(set_value('users') && in_array($user['id'], set_value('users')))  {
                                                        $selected = 'selected';
                                                    } else {
                                                        if(in_array($user['id'], $trainingUsers))  {
                                                            $selected = 'selected';
                                                        }
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

							<div class="row <?php echo $record->user_selection == 'based_on_position_and_department' ? '' : 'd-none';?>" id="department_container">
                                <div class="col-md-6">
                                    <div class="form-group">
										<label for="traning_department">Department <span class="required"> *</span></label>
										<select class="form-control" name="traning_department" id="traning_department" <?php echo $record->user_selection == 'based_on_position_and_department' ? 'required' : '';?>>
											<option value="">Select Department</option>
											<option value="all" <?php if($record->department_id == 0) echo 'selected';?>>All Departments</option>
											<?php foreach($departments as $department): ?>
												<option value="<?php echo $department->id;?>" <?php if($record->department_id == $department->id) echo 'selected';?>><?php echo $department->name; ?></option>
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

							<div class="row <?php echo $record->user_selection == 'based_on_position_and_department' ? '' : 'd-none';?>" id="position_container">
                                <div class="col-md-6">
                                    <div class="form-group">
										<label for="traning_position">Position </label>
										<select class="form-control" name="traning_position" id="traning_position" >
											<option value="">Select Position</option>
											<?php foreach($positions as $position): ?>
												<option value="<?php echo $position->id;?>" <?php if($record->position_id == $position->id) echo 'selected';?>><?php echo $position->name; ?></option>
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
													<?php foreach($record->materials as $material): ?>
														<?php if($material->type == 'url'): ?>
															<div id="matrerial_type__exist_<?php echo $material->id; ?>" class="form-group">
																<div class="clearfix">
																	<div class="float-left">Url<span class="required"> *</span></div>
																	<div class="float-right">
																		<button type="button"  data-toggle="modal" data-target="#pct__delete_modal" data-id="<?php echo $material->id; ?>" class="btn btn-danger btn-sm remove_material_type_exist pct__btn_delete" data-div="matrerial_type_exist_<?php echo $material->id; ?>">
																			<i class="fas fa-trash"></i>
																		</button>
																	</div>
																</div>
																<div>
																	<input type="url" value="<?php echo $material->path; ?>" class="form-control" placeholder="Url" name="material_exist_url[<?php echo $material->id; ?>]"  required="required">
																</div>
															</div>
														<?php elseif($material->type == 'file'): ?>
															<div id="matrerial_type__exist_<?php echo $material->id; ?>" class="form-group">
																<div class="clearfix">
																	<div class="float-left">File<span class="required"> *</span></div>
																	<div class="float-right">
																		<?php 
																		$path = '';
																		if (isset($material->path) && !empty($material->path)) {
																			$path = env('AWS_PATH').'hr/training/'.$material->path;
																		} ?>
																		<a href="<?php echo $path;?>" target="_blank">
																			<button type="button" class="btn btn-info btn-sm remove_material_type_exist pct__btn_delete" data-div="matrerial_type_exist_<?php echo $material->id; ?>">
																				<i class="fas fa-eye"></i>
																			</button>
																		</a>
																		<button type="button" data-toggle="modal" data-target="#pct__delete_modal" data-id="<?php echo $material->id; ?>" class="btn btn-danger btn-sm remove_material_type_exist pct__btn_delete" data-div="matrerial_type_exist_<?php echo $material->id; ?>">
																			<i class="fas fa-trash"></i>
																		</button>
																	</div>
																</div>
																<div>
																	<input type="file" class="form-control" placeholder="File" name="material_exist_file[<?php echo $material->id; ?>]">
																</div>
																<div style="margin-top: 10px;"><?php echo $material->path; ?></div>
															</div>
														<?php endif; ?>
													<?php endforeach; ?>
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
									</div>
								</div>
							</div>
                        
							<div class="row">
                                <div class="col-md-6">
									<div class="form-check form-group">
										<input type="checkbox" class="form-check-input" id="check_status" name="check_status" value="1" <?php if($record->status) echo 'checked';?>>
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

<div class="modal fade" width="500px" id="pct__delete_modal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document" style="width:40%;">
		<div class="modal-content">
			<form method="POST" action="<?php echo base_url('hr/admin/delete-training-material/'.$record->id);?>">
			<div class="modal-header">
                <h4 class="modal-title">Are you sure?</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              </div>
              <div class="modal-body">
                <p>Do you really want to delete this record? This process cannot be undone.</p>
				<input type="hidden" name="action" value="delete" />
				<input type="hidden" name="id" id="pct__delete_record_id">
              </div>
              <div class="modal-footer justify-content-center">
                <button type="button" class="btn  btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-danger">Delete</button>
              </div>
			</form>
		</div>
	</div>
</div>
