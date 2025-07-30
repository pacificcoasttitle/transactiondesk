<section class="section-type-4a section-default" style="padding-bottom:50px;">
    <div class="container">
        <div class="row">
            <div class="row">
                <div class="col-xs-12">
                    <div class="typography-section__innera">
                        <?php $userdata = $this->session->userdata('hr_user');?>
						<div class="row">
							<div class="col-sm-8">
								<h2 class="ui-title-block ui-title-block_light">Welcome <?php echo $userdata['name'];?>,</h2>
							</div>
							<div class="col-sm-4">
							<div class="pull-right row">
								<?php
								$clock_in_cls = '';
								$clock_out_cls = 'hide';
								if($clock_event == 'OUT'):
									$clock_in_cls = 'hide';
									$clock_out_cls = '';
								endif;
								?>
								<div id="timeClock" class="col-sm-6"></div>
								<div class="col-sm-6">

									<button type="button" class="btn btn-warning time-start track-time-btn <?php echo $clock_in_cls; ?>">Clock In</button>
									<button type="button" class="btn btn-warning time-stop track-time-btn <?php echo $clock_out_cls; ?>">Clock Out</button>
								</div>
							</div>
							</div>

						</div>
                        <div class="ui-decor-1a bg-accent"></div>
                        <h3 class="ui-title-block_light">How can we help you today?</h3>
                    </div>
                    <div class="typography-sectionButton">
                        <div class="col-md-12">
                            <div class="col-md-4">
                                <a href=https://myapps.paychex.com/landing_remote/login.do?TYPE=33554433&REALMOID=06-fd3ba6b8-7a2f-1013-ba03-83af2ce30cb3&GUID=&SMAUTHREASON=0&METHOD=GET&SMAGENTNAME=-SM-DcRXd3RBkM%2bIAuUkJhio4qMQPGHXSlwC5NHvGd60RCkP6guTqWS4qLnJtYdJd9Ge&TARGET=-SM-https%3a%2f%2fmyapps%2epaychex%2ecom%2f" target="_blank">
                                    <div class="buttonOuter">
                                        <button class="btn2 btn-type-6a btn-lg2" type="button">
                                            <img src="<?php echo base_url(); ?>assets/media/hr/paychex.png" class="buttImg">
                                            <p class="buttP">Access Paychex</p>
                                        </button>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-4">
                                <a href="<?php echo base_url(); ?>hr/profile"> 
                                    <div class="buttonOuter">
                                        <button class="btn2 btn-type-6a btn-lg2" type="button">
                                            <img src="<?php echo base_url(); ?>assets/media/hr/profile.png" class="buttImg">
                                            <p class="buttP">My Profile</p>
                                        </button>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-4">
                                <a href="<?php echo base_url(); ?>hr/time-cards">
                                    <div class="buttonOuter">
                                        <button class="btn2 btn-type-6a btn-lg2" type="button">
                                            <img src="<?php echo base_url(); ?>assets/media/hr/timecard.png" class="buttImg">
                                            <p class="buttP">Time Cards</p>
                                        </button>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="typography-sectionButton-2">
                        <div class="col-md-12">
                            <div class="col-md-4">
                                <a href="<?php echo base_url(); ?>hr/vacation-requests">
                                    <div class="buttonOuter">
                                        <button class="btn2 btn-type-6a btn-lg2" type="button">
                                            <img src="<?php echo base_url(); ?>assets/media/hr/vacation.png" class="buttImg">
                                            <p class="buttP">Vacation Requests</p>
                                        </button>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-4">
                                <a href="<?php echo base_url(); ?>hr/trainings">
                                    <div class="buttonOuter">
                                        <button class="btn2 btn-type-6a btn-lg2" type="button">
                                            <img src="<?php echo base_url(); ?>assets/media/hr/training.png" class="buttImg">
                                            <p class="buttP">Trainings</p>
                                        </button>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-4">
                                <a href="<?php echo base_url(); ?>hr/incident-reports">
                                    <div class="buttonOuter">
                                        <button class="btn2 btn-type-6a btn-lg2" type="button">
                                            <img src="<?php echo base_url(); ?>assets/media/hr/incident.png" class="buttImg">
                                            <p class="buttP">Report Incident</p>
                                        </button>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<div class="modal fade" id="timeTrackingModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog  modal-dialog-centered" role="document">
		<div class="modal-content">
			<form id="inviteForm" method="post">
				<div class="modal-header">
					<button type="button" class="close pull-right" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title">Select reason to stop the timer</h4>
				</div>
				<div class="modal-body ">
					<div class="row">
						<div class="col-sm-12">
							
							<div class="radio">
    							<label><input type="radio" name="break_reason" value="1" checked> Lunch</label>
							</div>
							<div class="radio">
								<label><input type="radio" name="break_reason" value="0"> Break</label>
							</div>
							<div class="radio">
								<label><input type="radio" name="break_reason" value="0"> Leaving for the Day</label>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-warning" data-dismiss="modal">Cancel</button>
					<button type="button" class="btn btn-danger track-time-confirm-btn time-stop" >Submit</button>
				</div>
			</form>
		</div> 
	</div>
</div>
