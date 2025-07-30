<style>

</style>
<div class="container-fluid">
	<div class="card shadow mb-4">
		<div class="card-body">
			<div class="col-xs-12" style="display:flex;">
				<div class="col-sm-8">
					<div class="typography-section__inner">	
						<div class="row">
							<div class="">
								<h5 class="ui-title-block_light">Below you will find a summary the clients who you closed a transaction(s) with in this year.</h5>
							</div>
						</div>
					</div>
					
				</div>
				<div class="col-sm-4 text-right custom__task_button">
						<div class="typography-section__inner">
							<?php if (!empty($salesUsers)) { ?>
								<select name="sales_user_summary_filter" id="sales_user_summary_filter" class="custom-select custom-select-sm form-control form-control-sm select_user">
									<?php foreach($salesUsers as $salesUser) { ?>
										<option <?php echo ($sales_user_id == $salesUser['id']) ? 'selected' : '' ;?> value="<?php echo $salesUser['id'];?>"><?php echo $salesUser['first_name']." ".$salesUser['last_name'];?></option>
									<?php }?>
								</select>
							<?php } ?>
							<button type="button" class="btn btn-primary btn-sm task_show_all"><i class="fa fa-plus"></i></button>
							<button type="button" class="btn btn-primary btn-sm task_hide_all"><i class="fa fa-minus"></i></button>
						</div>
						<div class="mt-20">
							
						</div>
					</div>
				<div id="result"></div>
			</div>
			
			<form method="post" class="mt-20">
				<div class="row">
					<div class="col-md-12">
						<div class="b-task-list__item task__info">
							
							<div class="card custom__task_card" style="background-color: #f2f2f2;">
								<div class="card-header py-3">
									<div class="row">
										<div class="col-md-3" style="padding-left: 40px;margin-top: 15px;">
											<label class="custom-control custom-checkbox task__name">
												<div class="check_box_text title_text"> SALES REP</div>
											</label>
										</div>
										<div class="col-md-5" style="padding-left: 40px;margin-top: 15px;">
											<label class="custom-control custom-checkbox task__name">
												<div class="check_box_text title_text"> COMPANY NAME</div>
											</label>
										</div>
										<div class="col-md-2" style="padding-left: 40px;margin-top: 15px;">
											<label class="custom-control custom-checkbox task__name">
												<div class="check_box_text title_text"> # OF DEALS</div>
											</label>
										</div>
										<div class="col-md-2 text-right" style="padding-left: 40px;margin-top: 15px;">
											<a href="#collapseCard_<?php echo $summary['company_id']; ?>" class="custom__collapse_arrow collapsed" data-toggle="collapse"
												role="button" aria-expanded="false" aria-controls="collapseCard_<?php echo $summary['company_id']; ?>">
												<i class="fa fa-angle-down"></i>
												<i class="fa fa-angle-up"></i>
											</a>
										</div>
									</div>
								</div>
							</div>
							
							<?php if (!empty($summary_info)) {
								foreach($summary_info as $summary) {
									if ($summary['parent_id'] == 0) { 
										$keys = array();
										$keys = array_keys(array_column($summary_info, 'parent_id'), $summary['company_id']);?>
										<div class="card custom__task_card">
											<div class="">
												<div class="row">
													<div class="col-md-3" style="padding-left: 40px;margin-top: 15px;">
														<label class="custom-control custom-checkbox task__name">
															<div class="check_box_text"> <?php echo $summary['sales_name']; ?></div>
														</label>
													</div>
													<div class="col-md-5" style="padding-left: 40px;margin-top: 15px;">
														<label class="custom-control custom-checkbox task__name">
															<div class="check_box_text"> <?php echo $summary['company_name']; ?></div>
														</label>
													</div>
													<div class="col-md-2" style="padding-left: 40px;margin-top: 15px;">
														<label class="custom-control custom-checkbox task__name">
															<div class="check_box_text"> <?php echo $summary['num_of_deals']; ?></div>
														</label>
													</div>
													<div class="col-md-2 text-right" style="padding-left: 40px;margin-top: 15px;">
														<a href="#collapseCard_<?php echo $summary['company_id']; ?>" class="custom__collapse_arrow collapsed" data-toggle="collapse"
															role="button" aria-expanded="false" aria-controls="collapseCard_<?php echo $summary['company_id']; ?>">
															<i class="fa fa-angle-down"></i>
															<i class="fa fa-angle-up"></i>
														</a>
													</div>
												</div>
											</div>
											<div class="collapse custom__task_collapse" id="collapseCard_<?php echo $summary['company_id']; ?>">
												<div class="card-body">												
													<div class="smart-forms spacer-b30 spacer-t30">
														<div class="tagline text-success "><span>Client Summary</span></div>
													</div>
													<table class="table table-bordered" width="100%" cellspacing="0">
														<thead>
															<tr>
																<th>#</th>
																<th>CLIENT SOURCE NAME</th>
																<th>COMPANY NAME</th>
																<th># OF DEALS</th>
															</tr>
														</thead>
														<tbody>
															<?php $j = 1;
																if (!empty($keys)) { 
																	foreach ($keys as $key) { ?>
																		<tr role="row" class="odd">
																			<td><?php echo $j;?></td>
																			<td><?php echo $summary_info[$key]['name'];?></td>
																			<td><?php echo $summary_info[$key]['company_name'];?></td>
																			<td><?php echo $summary_info[$key]['num_of_deals'];?></td> 
																		</tr>
																	<?php $j++; }
																} ?>
														</tbody>
													</table>
												</div>
											</div>
										</div>
									
									<?php }  
									}
							} else { ?>
									<div class="card custom__task_card">
											<div class="card-header py-3">
												<div class="row">
													<div class="col-xs-3">
														<div>No Records Found</div>
													</div>
											</div>
										</div>
									</div>
							<?php } ?>
						</div>
					</div>
				</div>
			</form> 
		</div>
		<div class="typography-sectionab"></div>
	</div>
</div>



