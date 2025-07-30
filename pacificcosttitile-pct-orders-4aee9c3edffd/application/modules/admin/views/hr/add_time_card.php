<style>
	.smart-forms .checkbox, .smart-forms .radio {
		top : 5px;
	}
	.parsley-errors-list {
		padding-left: 0px !important;
		margin-top: 5px;
		list-style: none;
		color: red;
	}
</style>
<div class="content">
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-sm-6">
                <h1 class="h3 text-gray-800">Time Card</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Add new Time card</h6>
                    </div>
                    <div class="card-body">
					<div class="smart-wrap">
							<div class="smart-forms smart-container wrap-4">
								<?php if(!empty($success)) {?>
									<div id="time_card_success_msg" class="w-100 alert alert-success alert-dismissible">
										<?php foreach($success as $sucess) {
											echo $sucess."<br \>";	
										}?>
									</div>
								<?php } 
								if(!empty($errors)) {?>
									<div id="time_card_error_msg" class="w-100 alert alert-danger alert-dismissible">
										<?php foreach($errors as $error) {
											echo $error."<br \>";	
										}?>
									</div>
								<?php } ?>
								<form data-parsley-validate="" method="post" action="<?php echo base_url();?>hr/admin/save-time-cards" id="time-cards-form">
									<div class="form-body">
										<div class="frm-row">
											<div class="section colm colm12">
												<label class="field select">
													<select id="tc_select_employee" name="select_employee" required>
														<option value="">Select Employee</option>
														<?php foreach($employees as $employee) :?>
															<option value="<?php echo $employee->id; ?>"><?php echo $employee->first_name.' '.$employee->last_name; ?></option>
														<?php endforeach; ?>
													</select>
													<i class="arrow double"></i>
												</label>
											</div>
										</div>
										<div id="time-cards-clone-group-fields">
											<div class="toclone clone-widget">
												<div class="frm-row">
													<div class="spacer-b10 colm colm3">
														<label class="field prepend-icon">
															<input type="text" name="exception_date[]" id="exception_date" class="gui-input exp_date" placeholder="Exception Date" readonly required>
															<span class="field-icon"><i class="fa fa-calendar-alt"></i></span>
														</label>
													</div>
													<div class="spacer-b10 colm colm2">
														<label class="field select">
															<select id="reg_hours" name="reg_hours[]" required>
																<option value="">Reg Hours</option>
																<?php for($reg_i = 1;$reg_i<=9;$reg_i++): ?>
																		<option value="<?php echo $reg_i;?>"><?php echo $reg_i;?></option>
																<?php endfor; ?>
															</select>
															<i class="arrow double"></i>
														</label>
													</div>
													<div class="spacer-b10 colm colm2">
														<label class="field select">
															<select id="ot_hours" name="ot_hours[]" required>
															<option value="">OT Hours</option>
															<?php for($reg_i = 0;$reg_i<=9;$reg_i++): ?>
																<option value="<?php echo $reg_i;?>"><?php echo $reg_i;?></option>
															<?php endfor; ?>
															</select>
															<i class="arrow double"></i>
														</label>
													</div>
													<div class="spacer-b10 colm colm2">
														<label class="field select">
															<select id="double_ot" name="double_ot[]" required>
																<option value="">Double OT</option>
																<?php for($reg_i = 0;$reg_i<=9;$reg_i++): ?>
																	<option value="<?php echo $reg_i;?>"><?php echo $reg_i;?></option>
																<?php endfor; ?>
															</select>
															<i class="arrow double"></i>
														</label>
													</div>
													<div class="spacer-b10 colm colm2">
														<label class="prepend-icon">
															<input type="text" name="total_hours[]" id="total_hours" class="gui-input" placeholder="Total Hours" readonly>
															<span class="field-icon"><i class="fa fa-user"></i></span>
														</label>
													</div>

													<div class="spacer-b10 colm colm11">
														<label for="comment" class="field-label"> Questions &amp; Comments </label>
														<label for="comment" class="field prepend-icon">
															<textarea class="gui-textarea" id="comment" name="comment[]" placeholder="Your question or comment" required></textarea>
															<span class="field-icon"><i class="fa fa-comments"></i></span>
															<span class="input-hint">
																<strong>Please:</strong> Be as descriptive as possible
															</span>
														</label>
													</div>
												</div>
												<a href="#" class="clone button btn-primary"><i class="fa fa-plus"></i></a>
												<a href="#" class="delete button"><i class="fa fa-minus"></i></a>
											</div>
										</div>
									</div>
									<div class="form-footer">
										<button type="submit" class="button btn-primary"> Send Form </button>
									</div>
								</form>
							</div>
						</div>
					</div>
                </div>
            </div>
        </div>
    </div>
</div>


