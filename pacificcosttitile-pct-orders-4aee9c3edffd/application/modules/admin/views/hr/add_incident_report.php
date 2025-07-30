<style>
	.smart-forms .checkbox, .smart-forms .radio {
		top : 5px;
	}
</style>
<div class="content">
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-sm-6">
                <h1 class="h3 text-gray-800">Incident Report</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Report an Incident</h6>
                    </div>
                    <div class="card-body">
					<div class="smart-wrap">
                                <div class="smart-forms smart-container wrap-0">
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
                                    <div class="form-body smart-steps stp-three">
                                        <form method="post" action="<?php echo base_url('hr/admin/save-incident-reports');?>" id="incident-report-form" name="incident-report-form">
                                            <h2>Employee Details</h2>
                                            <fieldset>
											<div class="text-center">
                                                <h4> Employee Details</h4>
                                                <p> Please select employee. <br><br></p>
											</div>
												<div class="frm-row">
                                                    <div class="section colm colm12">
                                                        <label class="field select">
                                                            <select id="inc_select_employee" name="select_employee" required>
																<option value="">Select Employee</option>
                                                                <?php foreach($employees as $employee) :?>
																	<option value="<?php echo $employee->id; ?>" data-first="<?php echo $employee->first_name; ?>" data-last="<?php echo $employee->last_name; ?>" data-email="<?php echo $employee->email; ?>" data-position="<?php echo $employee->position->name; ?>" data-employee="<?php echo $employee->employee_id; ?>"><?php echo $employee->first_name.' '.$employee->last_name; ?></option>
																<?php endforeach; ?>
                                                            </select>
                                                            <i class="arrow double"></i>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="frm-row">
                                                    <div class="section colm colm6">
                                                        <label class="field prepend-icon">
                                                            <input type="text" name="firstname" id="firstname" class="gui-input" placeholder="First name" value="" readonly>
                                                            <span class="field-icon"><i class="fa fa-user"></i></span>
                                                        </label>
                                                    </div>
                                                    <div class="section colm colm6">
                                                        <label class="field prepend-icon">
                                                            <input type="text" name="lastname" id="lastname" class="gui-input" placeholder="Last name" value="" readonly>
                                                            <span class="field-icon"><i class="fa fa-user"></i></span>
                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="frm-row">
                                                    <div class="section colm colm6">
                                                        <label class="field prepend-icon">
                                                            <input type="email" name="emailaddress" id="emailaddress" class="gui-input" placeholder="Email address" value="" readonly>
                                                            <span class="field-icon"><i class="fa fa-envelope"></i></span>
                                                        </label>
                                                    </div>

                                                    <div class="section colm colm6">
                                                        <label class="field prepend-icon">
                                                            <input type="tel" name="employee_id" id="employee_id" class="gui-input" placeholder="Employee number" readonly>
                                                            <span class="field-icon"><i class="fa fa-phone-square"></i></span>
                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="frm-row">
                                                    <div class="section colm colm6">
                                                        <label class="field prepend-icon">
                                                            <input type="text" name="position" id="position" class="gui-input" placeholder="Employee Position" readonly>
                                                            <span class="field-icon"><i class="fa fa-user"></i></span>
                                                        </label>
                                                    </div>
                                                    <div class="section colm colm6">
                                                        <label class="field prepend-icon">
                                                            <input type="text" name="incident_date" id="incident_date" class="gui-input incident_date" placeholder="Incident Date" readonly>
                                                            <span class="field-icon"><i class="fa fa-calendar-alt"></i></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </fieldset>
                                            <h2>Incident Details</h2>
                                            <fieldset>
												<div class="text-center">
													<h4> Incident Details</h4>
													<p> Please enter the details of the incident<br><br></p>
												</div>
                                                <div class="frm-row">
                                                    <div class="section colm colm12">
                                                        <label class="field select">
                                                            <select id="incident_reason" name="incident_reason">
                                                                <option value="Late">Late</option>
                                                                <option value="Missing Work">Missing Work</option>
                                                                <option value="Peer Interaction">Peer Interaction</option>
                                                                <option value="Other">Other</option>
                                                            </select>
                                                            <i class="arrow double"></i>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="section">
                                                    <label class="field prepend-icon">
                                                        <textarea class="gui-textarea" id="incident_detail" name="incident_detail" placeholder="Please enter the specific details of the incident"></textarea>
                                                        <span class="field-icon"><i class="fa fa-comments"></i></span>
                                                        <span class="input-hint">
                                                            <strong>Event Details:</strong> add more specific details
                                                        </span>
                                                    </label>
                                                </div>
                                            </fieldset>
                                            <h2>Actions Taken</h2>
                                            <fieldset>
											<div class="text-center">
                                                <h4> Actions Taken</h4>
                                                <p class="nospace">Please enter the amount of times this incident has taken place:<br><br></p>
											</div>
                                                <div class="frm-row">
                                                    <div class="option-group field">
                                                        <div class="section colm colm4">
                                                            <label class="option block">
                                                                <input type="checkbox" name="num_of_incidents[]" value="First">
                                                                <span class="checkbox"></span> First
                                                            </label>
                                                        </div>

                                                        <div class="section colm colm4">
                                                            <label class="option block">
                                                                <input type="checkbox" name="num_of_incidents[]" value="Second">
                                                                <span class="checkbox"></span> Second
                                                            </label>
                                                        </div>

                                                        <div class="section colm colm4">
                                                            <label class="option block">
                                                                <input type="checkbox" name="num_of_incidents[]" value="Third">
                                                                <span class="checkbox"></span> Third
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
												<div class="text-center">

													<h4>Frequency of Incident:</h4>
													<p class="nospace">  Please enter the actions taken.<br><br></p>
												</div>

                                                <div class="frm-row">
                                                    <div class="option-group field">
                                                        <div class="section colm colm4">
                                                            <label class="option block">
                                                                <input type="checkbox" name="actions[]" value="Verbal Warning">
                                                                <span class="checkbox"></span> Warned Employee
                                                            </label>
                                                        </div>

                                                        <div class="section colm colm4">
                                                            <label class="option block">
                                                                <input type="checkbox" name="actions[]" value="Sent Home">
                                                                <span class="checkbox"></span> Sent Home
                                                            </label>
                                                        </div>

                                                        <div class="section colm colm4">
                                                            <label class="option block">
                                                                <input type="checkbox" name="actions[]" value="No Action">
                                                                <span class="checkbox"></span> No Action
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="result spacer-b10"></div>
                                            </fieldset>
                                        </form>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


