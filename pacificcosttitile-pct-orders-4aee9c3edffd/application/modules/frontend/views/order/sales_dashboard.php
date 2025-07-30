<!-- <section class="section-type-4a section-defaulta container-fluid" style="padding-bottom:0px;"> -->
	<div class="container-fluid">
		<div class="card shadow mb-4">
			<div class="card-body">
				<div class="col-xs-12">
					
					<div class="typography-section__inner">
						<div class="row">
							<div class="col-sm-12">
								<h2 class="ui-title-block ui-title-block_light fs-28">Welcome <?php echo $name; ?>,</h2>
								<div class="ui-decor-1a bg-accent"></div>
								<div class="sales-user-listing mb-4">
									<h4 class="ui-title-block_light fs-16">Production figures for the current month of <b class="month-name"><?php echo date('F');?></b></h3>
									<?php if(!empty($salesUsers) && $is_sales_rep_manager == 1) { ?>
										<div id="sales_user_listing">
											<label>
												<select style="width:auto;" name="sales_user_filter" id="sales_user_filter" class="custom-select custom-select-sm form-control form-control-sm"> 
													<?php foreach($salesUsers as $salesUser) { ?>
														<option <?Php echo ($user_id == $salesUser['id']) ? 'selected' : '';?> value="<?php echo $salesUser['id'];?>"><?php echo $salesUser['first_name']." ".$salesUser['last_name'];?></option>
													<?php }?>
												</select>
											</label>
										</div>
									<?php } ?>
								</div>
							</div>

						</div>
					</div>
					<div class="row mb-4">
						<div class="col-sm-12">
							<div class="row mb-2" >
									<div class="col-md-3 col-sm-12 title text-primary">Title Openings MTD</div>
									<div class="col-md-3 col-sm-12 title text-success">Title Closings MTD</div>
									<div class="col-md-3 col-sm-12 title text-info">Title Revenue MTD</div>
									<div class="col-md-3 col-sm-12 title text-warning">Closings Ratio Avg</div>
								
							</div>

								<div class="row">
									<div class="col-md-3 col-sm-12">
										<div class="card border-left-primary shadow h-100 py-2">
											<div class="card-body">
												<div class="row no-gutters align-items-center">
													<div class="col mr-2">
														<div class="text-xs font-weight-bold text-primary text-uppercase mb-1 sales_loan_count" id="open_order_count"><?Php echo $total_open_count; ?></div>
														<div class="salesdivider">
														<div class="h5 mb-0 font-weight-bold text-gray-800 sales_loan_section">Sales = <span id="sale_open_count"><?Php echo $sale_open_count;?></span></div>
														<div class="h5 mb-0 font-weight-bold text-gray-800 sales_loan_section">Refi's = <span id="refi_open_count"><?Php echo $refi_open_count;?></span></div>
														</div>
													</div>
												</div>
												<div class="clearfix small z-1 viewDetails text-primary projected_goal_section">
													Projected = <span id="projected_open_section"><?Php echo $projected_open_count;?></span>
													<?php if($sales_rep_info['sales_rep_no_of_open_orders'] > 0) { ?>
														<div class="projected_goal_section">Goal = <span id="goal_open_section"><?Php echo round($sales_rep_info['sales_rep_no_of_open_orders']/12);?></span></div>
													<?php } else { ?>
														<div class="projected_goal_section">&nbsp;</div>
													<?php } ?>
												</div>
											</div>
										</div>
									</div>
									<div class="col-md-3 col-sm-12">
										<div class="card border-left-success shadow h-100 py-2">
											<div class="card-body">
												<div class="row no-gutters align-items-center">
													<div class="col mr-2">
														<div class="text-xs font-weight-bold text-success text-uppercase mb-1 sales_loan_count" id="close_order_count"><?Php echo $total_close_count; ?></div>
														<div class="salesdivider">
														<div class="h5 mb-0 font-weight-bold text-gray-800 sales_loan_section">Sales = <span id="sale_close_count"><?Php echo $sale_close_count;?></span></div>
														<div class="h5 mb-0 font-weight-bold text-gray-800 sales_loan_section">Refi's = <span id="refi_close_count"><?Php echo $refi_close_count;?></span></div>
														</div>
													</div>
												</div>
												<div class="clearfix small z-1 viewDetails text-success projected_goal_section">
													Projected = <span id="projected_close_section"><?Php echo $projected_close_count;?></span>
													<?php if($sales_rep_info['sales_rep_no_of_open_orders'] > 0) { ?>
														<div class="projected_goal_section">Goal = <span id="goal_open_section"><?Php echo round($sales_rep_info['sales_rep_no_of_open_orders']/12);?></span></div>
													<?php } else { ?>
														<div class="projected_goal_section">&nbsp;</div>
													<?php } ?>
												</div>
											</div>
										</div>
									</div>
									<div class="col-md-3 col-sm-12">
										<div class="card border-left-info shadow h-100 py-2">
											<div class="card-body">
												<div class="row no-gutters align-items-center">
													<div class="col mr-2">
														<div class="text-xs font-weight-bold text-info text-uppercase mb-1 sales_loan_count" id="total_premium"><a class="text-info" href="javascript:void(0)" onclick="getRevenueData();">$<span id="total_premium"><?php echo number_format($total_premium); ?></span></a></div>
														<div class="salesdivider">
														<div class="h5 mb-0 font-weight-bold text-gray-800 sales_loan_section">Sales = $<span id="sale_total_premium"><?Php echo $sale_total_premium;?></span></div>
														<div class="h5 mb-0 font-weight-bold text-gray-800 sales_loan_section">Refi's = $<span id="refi_total_premium"><?Php echo $refi_total_premium;?></span></div>
														</div>
													</div>
												</div>
												<div class="clearfix small z-1 viewDetails text-info projected_goal_section">
													Projected = $<span id="projected_revenue_section"><?Php echo number_format($projected_revenue);?></span>
													<?php if($sales_rep_info['sales_rep_premium'] > 0) { ?>
														<div class="projected_goal_section">Goal = $<span id="goal_revenue_section"><?Php echo number_format(round($sales_rep_info['sales_rep_premium']/12));?></span></div>
													<?php } else { ?>
														<div class="projected_goal_section">&nbsp;</div>
													<?php } ?>
												</div>
											</div>
										</div>
									</div>
									<div class="col-md-3 col-sm-12">
										<div class="card border-left-warning shadow h-100 py-2">
											<div class="card-body">
												<div class="row no-gutters align-items-center">
													<div class="col mr-2">
														<div class="text-xs font-weight-bold text-warning text-uppercase mb-1 sales_loan_count" id="close_order_percetage"><?Php echo $close_order_percetage; ?>%</div>
														<div class="salesdivider">
														<div class="h5 mb-0 font-weight-bold text-gray-800 sales_loan_section">Sales = <span id="sale_close_order_percetage"><?Php echo $sale_close_order_percetage;?></span>%</div>
														<div class="h5 mb-0 font-weight-bold text-gray-800 sales_loan_section">Refi's = <span id="refi_close_order_percetage"><?Php echo $refi_close_order_percetage;?></span>%</div>
														</div>
													</div>
												</div>
												
												<div class="clearfix small z-1 viewDetails text-warning projected_goal_section">
													Projected = <span id="refi_open_count">0%</span>
													<div class="projected_goal_section">&nbsp;</div>
												</div>
											</div>
										</div>
									</div>

								</div>
						</div>
					</div>
					<div class="order-count-cotainer">
						<h4 class="ui-title-block_light">Below is list of all your files. You can search for files by month that have a status open, closed, or cancelled.</h3>
					</div>	
					
					<div class="card shadow mb-4">
						<div class="card-body">
							<div class="table-responsive">
								<table class="table table-bordered" id="orders_listing" width="100%" cellspacing="0">
									<thead>
										<tr>
											<th>#</th>
											<?php if(!empty($salesUsers)) { ?>
												<th>Sales Rep</th>
											<?php } ?>
											<th>Opened</th>
											<th>Property Address</th>
											<th>Status</th>
											<th>Action</th>
										</tr>
									</thead>                
									<tbody></tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<!-- </section> -->
<!-- Partners Modal -->
<div class="modal" id="partnersModal">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="card shadow mb-4">
				<div class="card-header py-3">
					<h6 class="m-0 font-weight-bold text-primary" >Partners</h6>
				</div>
				
				<div class="card-body"> 
					<div class="table-responsive">
						<table class="table table-bordered" id="tbl-partners-data" width="100%" cellspacing="0">
							<thead>
								<tr>
									<th>PartnerID</th>
									<th>PartnerTypeID</th>
									<th>PartnerTypeName</th>
									<th>PartnerName</th>
									<!-- <th>EmailAddress</th> -->
								</tr>
							</thead>            
							<tbody></tbody>
						</table>
					</div>
					<div class="form-footer">
						<button type="reset" data-dismiss="modal" aria-label="Close" class="btn-danger btn-icon-split btn-sm">
							<span class="icon text-white-50">
								<i class="fas fa-ban"></i>
							</span>
							<span class="text">Cancel</span>
						</button>
						<!-- <button type="button" class="btn btn-success" data-dismiss="modal" >Close</button> -->
					</div>
				</div>
			</div>
			<!-- <div class="modal-header">
				<h4 class="modal-title">Partners</h4>
			</div> -->

			<!-- <div class="modal-body">
				<table class="table table-striped" id="tbl-partners-data">
					<thead>
					<tr>
						<th>PartnerID</th>
						<th>PartnerTypeID</th>
						<th>PartnerTypeName</th>
						<th>PartnerName</th>
						
					</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal" style="background: #d35411;">Close</button>
			</div> -->
		</div>
	</div>
</div>

<div class="modal fade" width="1200px" id="revenue_model" tabindex="-1" role="dialog"
    aria-labelledby="Revenue Information" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document" style="width:100%;height:auto;">
        <div class="modal-content">
            <form method="POST" action="">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Revenue Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="smart-forms smart-container">
                                    <div class="search-result">
                                        <div id="deliverables-details-fields">
                                            <div class="frm-row" id="clone_container">
                                                <div class="section colm colm12" id="clone-email-address"
                                                    style="margin-bottom: 0px !important;">
                                                    <div class="toclone">
                                                        <div class="spacer-b10">
                                                            <label class="field" id="revenue_container">

                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

