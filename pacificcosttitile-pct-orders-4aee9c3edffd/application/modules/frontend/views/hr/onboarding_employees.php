<style>
th {
    text-align: center;
}

.smart-forms p {
	margin-bottom: 0px;
}
</style>

<section class="section-type-4a section-defaulta" style="padding-bottom:0px;">
    <div class="container">
        <div class="row">
            <div class="row">
                <div class="col-xs-12">
                    <div class="typography-section__inner">
                        <h2 class="ui-title-block ui-title-block_light">Employees,</h2>
                        <div class="ui-decor-1a bg-accent"></div>
                        <h3 class="ui-title-block_light">Below is a detail of all employees.</h3>
                    </div>
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
                    <div class="typography-sectiona">
                        <div class="table-container">
                            <table class="table table-type-3 typography-last-elem" id="common_tbl_listing" data-url="<?php echo base_url('hr/onboarding/get-employees');?>" width="100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Hire Date</th>
                                        <th>Action</th> 
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

<div class="modal fade" width="500px" id="memo_information" tabindex="-1" role="dialog"
		aria-labelledby="Memoo Infromation" aria-hidden="true">
		<div class="modal-dialog modal-lg" role="document" style="width:50%;">
			<div class="modal-content">
				<form method="POST" action="<?php echo base_url();?>hr/accept-memo" id="accept_memo_form" name="accept_memo_form">
					<div class="smart-forms smart-container" style="margin:30px">
						<div class="modal-body search-result" style="padding-bottom: 0px;">
							<div id="memo-details-fields" >
								<div class="spacer-b20">
									<div class="tagline"><span id="subject_container"></span></div>
								</div>

								<div class="frm-row">
									<div class="section colm colm12" id="date"></div>
								</div>

								<div class="frm-row">
									<div class="section colm colm12" id="to"></div>
								</div>

								<div class="frm-row">
									<div class="section colm colm12" id="from"></div>
								</div>
								
								<div class="spacer-b20">
									<div class="tagline"></div>
								</div>
								<br/>
							
								<div class="frm-row">
									<div class="section colm colm12" id="description" style="line-height: 2;"></div>
								</div>

								<input type="hidden" name="memoId" id="memoId" value="">	
								<input type="hidden" name="subject" id="subject" value="">	
							</div>
						</div>
						<div style="margin: 0px 20px;text-align:left;">
							<button style="margin: 0px 10px 20px 0px;" type="submit" data-btntext-sending="Sending..."
								class="button btn-primary">Accept</button>
							<button type="reset" data-dismiss="modal" aria-label="Close" class="button">Cancel</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>

