<style>
th {
    text-align: center;
}
#vacation_requests_listing th, #vacation_requests_listing td {
    padding: 16px 5px 16px !important;
}
</style>
<section class="section-type-4a section-defaulta" style="padding-bottom:0px;">
    <div class="container">
        <div class="row">
            <div class="row">
                <div class="col-xs-12">
                    <div class="typography-section__inner">
						<div class="show-hide-form-div hide">
                        <h2 class="ui-title-block ui-title-block_light">Vacation Request Form,</h2>
							<div class="ui-decor-1a bg-accent"></div>
							<h3 class="ui-title-block_light">Use the form below to report your time exception.</h3><br>
							<h4 class="ui-title-block_light"><strong>Employee Name:</strong> <?php echo $name;?>.</h4>
							<h4 class="ui-title-block_light"><strong>Today's Date:</strong> <?php echo date('m/d/Y');?></h4>
							<h4 class="ui-title-block_light"><strong>Manager Name:</strong> </h4>
						</div>
						<button id="show-hide-form-btn" type="button" class="btn btn-danger show-hide-form-div">Add Request</button>
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
                                    <form data-parsley-validate="" method="post" action="<?php echo base_url();?>hr/save-vacation-requests" id="vacation-requests-form">
                                        <div class="form-body">
                                            <div id="vacation-requests-clone-group-fields">
                                                <div class="toclone clone-widget">
                                                    <div class="frm-row">
                                                        <div class="spacer-t10 spacer-b10 colm colm5">
                                                            <label class="field prepend-icon">
                                                                <input type="text" name="from_dates[]" id="from_dates" class="gui-input from_date" placeholder="From Date" readonly required>
                                                                <span class="field-icon"><i class="fa fa-calendar-o"></i></span>
                                                            </label>
                                                        </div>

                                                        <div class="spacer-t10 spacer-b10 colm colm5">
                                                            <label class="field prepend-icon">
                                                                <input type="text" name="to_dates[]" id="to_dates" class="gui-input to_date" placeholder="To Date " readonly required>
                                                                <span class="field-icon"><i class="fa fa-calendar-o"></i></span>
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
</section>

<section class="section-type-4a section-defaulta" style="padding-bottom:0px;">
    <div class="container">
        <div class="row">
            <div class="row">
                <div class="col-xs-12">
                    <div class="typography-section__inner">
						
                        <h2 class="ui-title-block ui-title-block_light">Vacation Requests Calendar</h2>	
						<div class="ui-decor-1a bg-accent"></div>
                    </div>
                    
                    <div class="typography-sectionabcde">
                        <div class="col-md-12">
                            <div id='loading'>loading...</div>	
                            <div id='calendar'></div>
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
                        <h2 class="ui-title-block ui-title-block_light">Vaction Request History,</h2>
                        <div class="ui-decor-1a bg-accent"></div>
                        <h3 class="ui-title-block_light">Below is a detail of all your requests.</h3>
                    </div>
                    <div class="typography-sectiona">
                        <div class="table-container">
                            <table class="table table-type-3 typography-last-elem" id="vacation_requests_listing" width="100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Employee</th>
                                        <th>From Date</th>
                                        <th>To Date</th>
                                        <th>Salary Deduction</th>
                                        <th>Time Charged Vacation</th>
                                        <th>Status</th>
                                        <th>Updated On</th>
                                        <th>Approved By</th>
                                        <?php /*  $userdata = $this->session->userdata('hr_user');
                                        if ($userdata['user_type_id'] == 1) { ?>
                                            <th>Approved Date</th> 
                                        <?php }
                                        if ($userdata['user_type_id'] == 2) { ?>
                                            <th>Actions</th>
                                        <?php } */ ?>
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
                    <input type="hidden" id="request_type" name="request_type" value="vacation_request">
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
