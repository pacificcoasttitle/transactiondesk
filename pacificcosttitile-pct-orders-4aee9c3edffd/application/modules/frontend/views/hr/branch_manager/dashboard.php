<style>
	.pagination {
		overflow: hidden;
	}

	.pagination>li>a {
		width: 42px;
		height: 42px;
		margin-right: 8px;
		padding-top: 14px;
		border: 1px solid rgba(221, 221, 221, 0.5);
	}

	.pagination>.active>a,
	.pagination>.active>span,
	.pagination>.active>a:hover,
	.pagination>.active>span:hover,
	.pagination>.active>a:focus,
	.pagination>.active>span:focus {
		background-color: #6533d7;
		background-image: -webkit-linear-gradient(305deg, #6533d7 0%, #339bd7 100%);
	}

	.pagination>li>a:hover,
	.pagination>li>span:hover,
	.pagination>li>a:focus,
	.pagination>li>span:focus {
		background-color: #6533d7;
		background-image: -webkit-linear-gradient(305deg, #6533d7 0%, #339bd7 100%);
	}

	.dataTables_paginate {
		padding-top: 50px;
		padding-bottom: 100px;
		text-align: right;
	}

	.typography-section {
		padding-bottom: 0px;
	}

	.dataTables_filter input {
		height: calc(1.5em + 0.5rem + 2px);
		background: #fff;
		position: relative;
		vertical-align: top;
		border: 1px solid #cbd2d6;
		display: -moz-inline-stack;
		display: inline-block;
		color: #34495E;
		outline: none;
		height: 42px;
		width: 96%;
		zoom: 1;
		border-radius: 3px;
		margin: 0;
		font-size: 14px;
		font-family: "Roboto", Arial, Helvetica, sans-serif;
		font-weight: 400;
	}

	.button-color {
		color: #888888;
	}

	.button-color-green {
		background: rgb(0, 102, 68);
	}



	table#orders_listing tr td:last-child {
		display: inline-flex;
	}

	.ui-autocomplete {
		max-height: 300px !important;
	}

	#orders_listing_filter {
		display: inline-flex;
		float: right;
	}

	select#month_filter {
		margin-bottom: 0px;
		margin-left: 0.5em;
		border: 1px solid #cbd2d6;
		border-radius: 3px;
		padding: 9px 22px 12px;
	}

	select#orders_filter {
		margin-bottom: 0px;
		margin-left: 0.5em;
		border: 1px solid #cbd2d6;
		border-radius: 3px;
		padding: 9px 22px 12px;
	}

	.button-color {
		color: #888888;
	}

	td.dataTables_empty {
		display: table-cell !important;
	}

	.modal-dialog {
		overflow-y: initial !important
	}

	.modal-body {
		height: 700px;
		overflow-y: auto;
	}

	.square-box {
		background-color: #f0f0f0;
		width: 23% !important;
		margin-right: 2%;
		margin-bottom: 50px;
		padding-bottom: 35px;
		padding-top: 15px;
	}

	.order-count-cotainer {
		margin-top: 50px;
	}

	.title {
		text-align: center;
		color: #a0a0a0;
		width: 23% !important;
		margin-right: 2%;
		text-transform: uppercase;
		font-size: 15px;
		letter-spacing: 0px;
	}

	.sales_loan_count {
		font-size: 48px;
		color: #0D5772;
		text-align: center;
		font-weight: 800;
		letter-spacing: -1.00px;
		border-bottom: 1px solid #fff;
	}

	.sales_loan_section {
		text-align: center;
		text-transform: uppercase;
		font-size: 21px;
		line-height: 27px;
		color: #a0a0a0;
	}

	.salesdivider {
		border-bottom: 1px solid #fff;
		padding-top: 20px;
		padding-bottom: 20px;
	}

	.projected_goal_section {
		color: #d35411;
		/* font-weight: bold;*/
		text-align: center;
		text-transform: uppercase;
		font-size: large;
		line-height: 21px;
	}

	#orders_listing_filter {
		margin-bottom: 20px;
	}

	th {
		text-align: center;
	}


	#sales_user_listing {
		margin-bottom: 20px;
		float: right;
		margin-right: 25px;
	}

</style>

<section class="section-type-4a section-defaulta typography-section-border">
	<div class="container">
		<div class="row">
			<div class="row">
				<div class="col-xs-12">
					<div class="typography-section__inner">
						<?php $userdata = $this->session->userdata('hr_user');?>
						<h2 class="ui-title-block ui-title-block_light">Welcome <?php echo $userdata['name'];?>,</h2>
						<div class="ui-decor-1a bg-accent"></div>
						<h3 class="ui-title-block_light">Below is your production figures for your branch in the month
							of January</h3>
					</div>
					<div class="order-count-cotainer">
						<div class="col-md-3 title">Title Openings MTD</div>
						<div class="col-md-3 title">Title Closings MTD</div>
						<div class="col-md-3 title">Title Revenue MTD</div>
						<div class="col-md-3 title">Closings Ratio Avg</div>

						<div class="col-md-3 square-box">
							<div class="sales_loan_count" id="open_order_count"><?Php echo $total_open_count; ?></div>
							<div class="salesdivider">
							<div class="sales_loan_section">Sales = <span id="sale_open_count"><?Php echo $sale_open_count;?></span></div>
							<div class="sales_loan_section">Refi's = <span id="refi_open_count"><?Php echo $refi_open_count;?></span></div>
							</div>
							<div style="margin-top: 20px;" class="projected_goal_section">Projected = <span id="projected_open_section"><?Php echo $projected_open_count;?></span></div>
							<?php if($sales_rep_info['sales_rep_no_of_open_orders'] > 0) { ?>
								<div class="projected_goal_section">Goal = <span id="goal_open_section"><?Php echo round($sales_rep_info['sales_rep_no_of_open_orders']/12);?></span></div>
							<?php } else { ?>
								<div class="projected_goal_section">&nbsp;</div>
							<?php } ?>
						</div>

						<div class="col-md-3 square-box">
							<div class="sales_loan_count" id="close_order_count"><?Php echo $total_close_count; ?></div>
							<div class="salesdivider">
							<div class="sales_loan_section">Sales = <span id="sale_close_count"><?Php echo $sale_close_count;?></span></div>
							<div class="sales_loan_section">Refi's = <span id="refi_close_count"><?Php echo $refi_close_count;?></span></div>
							</div>
							<div style="margin-top: 20px;" class="projected_goal_section">Projected = <span id="projected_close_section"><?Php echo $projected_close_count;?></span></div>
							<?php if($sales_rep_info['sales_rep_no_of_close_orders'] > 0) { ?>
								<div class="projected_goal_section">Goal = <span id="goal_close_section"><?Php echo round($sales_rep_info['sales_rep_no_of_close_orders']/12);?></span></div>
							<?php } else { ?>
								<div class="projected_goal_section">&nbsp;</div>
							<?php } ?>
						</div>

						<div class="col-md-3 square-box">
							<div class="sales_loan_count">$<span id="total_premium"><?php echo number_format($total_premium); ?></span></div>
							<div class="salesdivider">
							<div class="sales_loan_section">Sales = $<span id="sale_total_premium"><?php echo number_format($sale_total_premium); ?></span></div>
							<div class="sales_loan_section">Refi's = $<span id="refi_total_premium"><?php echo number_format($refi_total_premium); ?></span></div>
							</div>
							<div style="margin-top: 20px;" class="projected_goal_section">Projected = $<span id="projected_revenue_section"><?Php echo number_format($projected_revenue);?></span></div>
							<?php if($sales_rep_info['sales_rep_premium'] > 0) { ?>
								<div class="projected_goal_section">Goal = $<span id="goal_revenue_section"><?Php echo number_format(round($sales_rep_info['sales_rep_premium']/12));?></span></div>
							<?php } else { ?>
								<div class="projected_goal_section">&nbsp;</div>
							<?php } ?>
						</div>

						<div class="col-md-3 square-box">
							<div class="sales_loan_count"><span id="close_order_percetage"><?Php echo $close_order_percetage;?></span>%</div>
							<div class="salesdivider">
							<div class="sales_loan_section">Sales = <span id="sale_close_order_percetage"><?Php echo $sale_close_order_percetage;?></span>%</div>
							<div class="sales_loan_section">Refi's = <span id="refi_close_order_percetage"><?Php echo $refi_close_order_percetage;?></span>%</div>
							</div>
							<div style="margin-top: 20px;" class="projected_goal_section">Projected = <span id="refi_open_count">0%</span></div>
							<div class="projected_goal_section">&nbsp;</div>
						</div>

						
					</div>
					
				</div>
			</div>
		</div>
	</div>
</section>

<section class="section-type-4a section-defaulta typography-section-border" style="padding-bottom:0px;">
	<div class="container">
		<div class="row">
			<div class="row">
				<div class="col-xs-12">
					<div class="typography-section__inner">
						<h2 class="ui-title-block ui-title-block_light">Branch Requests,</h2>
						<div class="ui-decor-1a bg-accent"></div>
						<h3 class="ui-title-block_light">How can we help you today?</h3>
					</div>

					<div class="typography-sectionButton">
						<div class="col-md-12">
							<div class="col-md-4">
								<a href="">
									<div class="buttonOuter">
										<button class="btn2 btn-type-6a btn-lg2" type="button">
											<img src="<?php echo base_url(); ?>assets/media/hr/new.png" class="buttImg">
											<p class="buttP">New Employee</p>
										</button>
									</div>
								</a>
							</div>

							<div class="col-md-4">
								<a href="<?php echo base_url(); ?>hr/incident-reports">
									<div class="buttonOuter">
										<button class="btn2 btn-type-6a btn-lg2" type="button">
											<img src="<?php echo base_url(); ?>assets/media/hr/incident.png"
												class="buttImg">
											<p class="buttP">Incident Report</p>
										</button>
									</div>
								</a>
							</div>
							<div class="col-md-4">
								<a href="<?php echo base_url(); ?>hr/time-cards">
									<div class="buttonOuter">
										<button class="btn2 btn-type-6a btn-lg2" type="button">
											<img src="<?php echo base_url(); ?>assets/media/hr/timecard.png"
												class="buttImg">
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
											<img src="<?php echo base_url(); ?>assets/media/hr/vacation.png"
												class="buttImg">
											<p class="buttP">Vacation Requests</p>
										</button>
									</div>
								</a>
							</div>

							<div class="col-md-4">
								<a href="<?php echo base_url(); ?>hr/trainings">
									<div class="buttonOuter">
										<button class="btn2 btn-type-6a btn-lg2" type="button">
											<img src="<?php echo base_url(); ?>assets/media/hr/onboarding.png"
												class="buttImg">
											<p class="buttP">OnBoarding</p>
										</button>
									</div>
								</a>
							</div>

							<div class="col-md-4">
								<a href="">
									<div class="buttonOuter">
										<button class="btn2 btn-type-6a btn-lg2" type="button">
											<img src="<?php echo base_url(); ?>assets/media/hr/training.png"
												class="buttImg">
											<p class="buttP">Trainings</p>
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


