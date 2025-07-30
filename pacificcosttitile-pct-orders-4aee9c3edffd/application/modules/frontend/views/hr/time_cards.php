<style>
th {
    text-align: center;
}
.section-type-4a .btn.btn-default:hover {
	color : initial;
}
.action_btn .btn {
	display: inline;
    width: 150px;
    padding: 9px;
}
</style>
<section class="section-type-4a section-defaulta" style="padding-bottom:0px;">
    <div class="container">
        <div class="row">
            <div class="row">
                <div class="col-xs-12">
                    <div class="typography-section__inner">
						<div class="show-hide-form-div hide">
							<h2 class="ui-title-block ui-title-block_light">Timecard Exception Form,</h2>
							<div class="ui-decor-1a bg-accent"></div>
							<h3 class="ui-title-block_light">Use the form below to report your time exception.</h3><br>
							<h4 class="ui-title-block_light"><strong>Employee Name:</strong> <?php echo $name;?>.</h4>
							<h4 class="ui-title-block_light"><strong>Today's Date:</strong> <?php echo date('m/d/Y');?></h4>
							<h4 class="ui-title-block_light"><strong>Manager Name:</strong> </h4>
						</div>
						<button id="show-hide-form-btn" type="button" class="btn btn-danger show-hide-form-div" style="width: auto;">Add Time Exception</button>
                    </div>

                    <div class="typography-sectionabcde show-hide-form-div hide">
                        <div class="col-md-12">
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
                                    <form data-parsley-validate="" method="post" action="<?php echo base_url();?>hr/save-time-cards" id="time-cards-form">
                                        <div class="form-body">
                                            <div id="time-cards-clone-group-fields">
                                                <div class="toclone clone-widget">
                                                    <div class="frm-row">
                                                        <div class="spacer-b10 colm colm3">
                                                            <label class="field prepend-icon">
                                                                <input type="text" name="exception_date[]" id="exception_date" class="gui-input exp_date" placeholder="Exception Date" readonly required>
                                                                <span class="field-icon"><i class="fa fa-calendar-o"></i></span>
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
</section>

<section class="section-type-4a section-defaulta" style="padding-bottom:0px;">
    <div class="container">
        <div class="row">
            <div class="row">
                <div class="col-xs-12">
                    <div class="typography-section__inner">
                        <h2 class="ui-title-block ui-title-block_light">Payroll Schedule</h2>
                        <div class="ui-decor-1a bg-accent"></div>
                        <h3 class="ui-title-block_light">Below is a list of Bi-weekly Payroll Schedule.</h3>
                    </div>
                    <div class="">
                        <div class="table-container">
                            <table class="table table-type-3 typography-last-elem" id="payroll_schedule_listing" width="100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Pay Period Begins</th>
                                        <th>Pay Period Ends</th>
                                        <!-- <th>Payroll Monday</th> -->
                                        <!-- <th>Pay Day</th> -->
                                        <th>Status</th>
                                        <th>Submitted On</th>
                                        <th>Action</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
									<?php
									$pay_period_begins_time_stamp = strtotime($pay_period_start);
									$pay_period_current_time_stamp = strtotime($current_date);
									$int_i =1;
									if($pay_period_begins_time_stamp && $pay_period_current_time_stamp) :
										
										while($pay_period_begins_time_stamp < $pay_period_current_time_stamp):
											$pay_period_ends_time_stamp = strtotime("+13 day",$pay_period_begins_time_stamp);
											$pay_period_monday_time_stamp = strtotime("+1 day",$pay_period_ends_time_stamp);
											$status = $submitted_on = '';
											$submit_btn = '<button type="button" class="btn btn-default submitTimesheetBtn" data-begin-time="'.$pay_period_begins_time_stamp.'">Submit</a>';
											$search_key_status = array_search(date('Y-m-d', $pay_period_begins_time_stamp), array_column($timesheet_status, 'start_date'));
											
											if($search_key_status !== false):
												$timesheet_status_record = $timesheet_status[$search_key_status];
												// $status = ucfirst($timesheet_status_record->status);
												$submitted_on = $this->common->convertTimezone($timesheet_status_record->created_at,'m/d/Y');
												$status = '<span class="badge-new badge-new-info">Submitted</span>';
												if($timesheet_status_record->status == 'approved') {
													$status = '<span class="badge-new badge-new-success">Approved</span>';
												} elseif($timesheet_status_record->status == 'denied') {
													$denied_reason = $timesheet_status_record->denied_reason;
													$status = '<span class="badge-new badge-new-danger">Denied</span><span role="button" class="icon" data-toggle="popover" title="Denied Reason" data-content="'.$denied_reason.'">
																<i class="fa fa-info"></i>
															</span>';
												}
												$submit_btn = '';
											endif;
											?>
											<tr>
											<td><?php echo $int_i++; ?></td>
											<td><?php echo date("m/d/Y",$pay_period_begins_time_stamp) ?></td>
											<td><?php echo date("m/d/Y",$pay_period_ends_time_stamp) ?></td>
											<!-- <td><?php echo date("m/d/Y",$pay_period_monday_time_stamp) ?></td> -->
											
											<!-- <td> - </td> -->
											<td><?php echo $status;?></td>
											<td><?php echo $submitted_on; ?></td>
											<td class="action_btn">
												<a target="_blank" href="<?php echo base_url('hr/view-time-sheet/'.$pay_period_begins_time_stamp);?>" class="btn btn-default"  >View TimeSheet</a>
												&nbsp;&nbsp;
												<?php echo $submit_btn; ?>
											</td>
											</tr>
											<?php
											$pay_period_begins_time_stamp = strtotime("+1 day",$pay_period_ends_time_stamp);
										endwhile;
									endif;
									?>
                                    
                                </tbody>
                            </table>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section-type-4a section-defaulta" >
    <div class="container">
        <div class="row">
            <div class="row">
                <div class="col-xs-12">
                    <div class="typography-section__inner">
                        <h2 class="ui-title-block ui-title-block_light">Timecard Submission History,</h2>
                        <div class="ui-decor-1a bg-accent"></div>
                        <h3 class="ui-title-block_light">Below is a detail of all your requests.</h3>
                    </div>
                    <div class="">
                        <div class="table-container">
                            <table class="table table-type-3 typography-last-elem" id="time_card_listing" width="100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Employee</th>
                                        <th>Date</th>
                                        <th>Reg Hours</th>
                                        <th>OT Hours</th>
                                        <th>Double OT</th>
                                        <th>Status</th>
                                        <th>Approved By</th>
                                        <?php  $userdata = $this->session->userdata('hr_user');
                                        if ($userdata['user_type_id'] == 1) { ?>
                                            <th>Approved Date</th> 
                                        <?php }
                                        if ($userdata['user_type_id'] == 2) { ?>
                                            <th>Actions</th>
                                        <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                </tbody>
                            </table>
							<div class="typography-sectionab"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>




<div class="modal fade" width="500px" id="approve_deny_popup" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document" style="width:40%;">
        <div class="modal-content">
            <form method="POST" action="<?php echo base_url();?>hr/approve-deny-request" enctype="multipart/form-data">
                <div class="smart-forms smart-container wrap-2" style="margin:30px">
                    <div class="modal-body search-result">
                        <div id="lender-details-fields">
                            <div class="spacer-b20">
                                <div class="tagline"><span id="approve_deny_title"></span></div>
                            </div>
                            <div class="frm-row">
                                <div class="section colm colm12">
                                    <label class="field prepend-icon" id="approve_deny_msg">
                                       
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="request_type" name="request_type" value="time_card">
                    <input type="hidden" id="request_id" name="request_id" value="">
                    <input type="hidden" id="status" name="status" value="">
                    <div class="" style="padding: 0px 25px 20px;">
                        <button type="submit" data-btntext-sending="Sending..."
                            class="button btn-primary">Yes </button>
                        <button type="reset" data-dismiss="modal" aria-label="Close" class="button">No</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" width="500px" id="submitTimesheetModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document" style="width:40%;">
        <div class="modal-content">
            <form method="POST" action="<?php echo base_url();?>hr/submit-timesheet" >
                <div class="smart-forms smart-container wrap-2" style="margin:30px">
                    <div class="modal-body search-result">
                        <div >
                            <div class="spacer-b20">
                                <div class="tagline"><span >Confirmation</span></div>
                            </div>
                            <div class="frm-row">
                                <div class="section colm colm12">
                                    <label class="field prepend-icon" >
                                       Are you sure you want to submit this?
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="ts_begin_time" name="start_date" value="">
                    <div class="" style="padding: 0px 25px 20px;">
                        <button type="submit" data-btntext-sending="Sending..."
                            class="button btn-primary">Yes </button>
                        <button type="reset" data-dismiss="modal" aria-label="Close" class="button">No</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
