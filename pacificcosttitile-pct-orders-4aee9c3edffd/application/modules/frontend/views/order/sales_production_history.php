<div class="container-fluid">
	<div class="card shadow mb-4">
		<div class="card-body">
			<div class="col-xs-12">
				<div class="typography-section__inner align-wrapper">
					<h4 class="ui-title-block_light">Below is list of your month order's count for the current year of <b class="month-name"><?php echo date('Y');?></b></h3>
					<?php if(!empty($salesUsers)) { ?>
						<div id="sales_user_listing">
							<label>
								<select style="width:auto;" name="sales_user_production_filter" id="sales_user_production_filter" class="custom-select custom-select-sm form-control form-control-sm"> 
									<!-- <option value="all"> All Sales Rep Users </option> -->
									<?php foreach($salesUsers as $salesUser) { ?>
										<option <?php echo ($sales_user_id == $salesUser['id']) ? 'selected' : '' ;?> value="<?php echo $salesUser['id'];?>"><?php echo $salesUser['first_name']." ".$salesUser['last_name'];?></option>
									<?php }?>
								</select>
							</label>
						</div>
					<?php } ?>
				</div>
				<div class="card shadow mb-4">
					<div class="card-body">
						<div class="table-responsive">
							<table class="table table-bordered" id="production_history_tab" width="100%" cellspacing="0">
								<thead>
									<tr>
										<th align="center">Month</th>
										<th>Trending</th>
										<th>Total Openings</th>
										<th>Total Closings</th>
										<th>Total Revenue</th>
										<th>Closing %</th>
									</tr>
								</thead>
								<?php if(!empty($salesHistory)) {?>
									<tbody>
										<?php foreach($salesHistory as $salesData) { ?>
											<tr>
												<td align="center"><?php echo $salesData['month'];?></td>
												<td align="center"><?php echo $salesData['trending'];?></td>
												<td align="center"><?php echo $salesData['total_open_count'];?></td>
												<td align="center"><?php echo $salesData['total_close_count'];?></td>
												<td align="center"><a href="javascript:void(0)" onclick="getRevenueDataBasedOnMonth('<?php echo $salesData['month_val']?>');"><?php echo "$".number_format($salesData['total_premium']);?></a></td>
												<td align="center"><?php echo $salesData['close_order_percetage']."%";?></td>
											</tr> 
										<?php } ?> 
									</tbody>
								<?php } else {?>
									<tbody>
										<tr>
											<td align="center" colspan="5"> No Records Found.</td>
										</tr>
									</tbody>
								<?php } ?>
							</table>
						</div>
					</div>
				</div>
			</div>
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


