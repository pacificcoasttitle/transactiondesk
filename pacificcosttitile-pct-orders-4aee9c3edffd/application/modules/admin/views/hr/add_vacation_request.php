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
                <h1 class="h3 text-gray-800">Vacation Request</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Add Vacation Request</h6>
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
								<form data-parsley-validate="" method="post" action="<?php echo base_url();?>hr/admin/save-vacation-requests" id="vacation-requests-form">
									<div class="form-body">
										<div class="frm-row">
											<div class="section colm colm12">
												<label class="field select">
													<select id="vac_select_employee" name="select_employee" required>
														<option value="">Select Employee</option>
														<?php foreach($employees as $employee) :?>
															<option value="<?php echo $employee->id; ?>"><?php echo $employee->first_name.' '.$employee->last_name; ?></option>
														<?php endforeach; ?>
													</select>
													<i class="arrow double"></i>
												</label>
											</div>
										</div>
										<div id="vacation-requests-clone-group-fields">
											<div class="toclone clone-widget">
												<div class="frm-row">
													<div class="spacer-t10 spacer-b10 colm colm5">
														<label class="field prepend-icon">
															<input type="text" name="from_dates[]" id="from_dates" class="gui-input from_date" placeholder="From Date" readonly required>
															<span class="field-icon"><i class="fa fa-calendar-alt"></i></span>
														</label>
													</div>

													<div class="spacer-t10 spacer-b10 colm colm5">
														<label class="field prepend-icon">
															<input type="text" name="to_dates[]" id="to_dates" class="gui-input to_date" placeholder="To Date " readonly required>
															<span class="field-icon"><i class="fa fa-calendar-alt"></i></span>
														</label>
													</div>

													<div class="spacer-t10 colm colm10">
														<label for="comment" class="field prepend-icon">
															<textarea class="gui-textarea" id="comments" name="comments[]" placeholder="Your question or comment" required></textarea>
															<span class="field-icon"><i class="fa fa-comments"></i></span>
															<span class="input-hint">
																<strong>Please:</strong> Be as descriptive as possible
															</span>
														</label>
													</div>

													<div class="spacer-t10 colm colm10">
														<div class="option-group field">
															<div class="section colm colm6">
																<label class="option block">
																	<input type="checkbox" id="is_salary_deductions" name="is_salary_deductions[]">
																	<span class="checkbox"></span> Salary Deduction(s) be made for such time
																</label>
															</div>

															<div class="section colm colm5">
																<label class="option block">
																	<input type="checkbox" id="is_time_charged_vacations" name="is_time_charged_vacations[]">
																	<span class="checkbox"></span> The time is charged against vacation
																</label>
															</div>
														</div>
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


