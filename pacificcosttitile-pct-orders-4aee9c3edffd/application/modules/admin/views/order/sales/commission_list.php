<style>
.custom__collapse_arrow .div__expand {
    display: none;
}
	.custom__collapse_arrow.collapsed .div__expand {
	display: inline-block;
}
.custom__collapse_arrow.collapsed .div__collapse {
	display: none;
}
.table > tbody > tr.custom__total > th ,.table > tbody > tr.custom__total > td {
	border-top: 2px solid;
}
.align-center{
	text-align: center;
}
.btn-secondary {
    background-color: #223D7F;
}
</style>
<div class="container-fluid">
    <div class="row mb-3">
		<div class="col-sm-6">
			<h1 class="h3 text-gray-800">Sales Rep Commissison </h1>
		</div>
	</div>
    <!-- DataTables Example -->
    <div class="card shadow mb-4">
        <div class="card-header datatable-header py-3">
            <div class="datatable-header-titles" > 
                <span>
                    <i class="fas fa-percent"></i>
                </span>
                <h6 class="m-0 font-weight-bold text-primary pl-10">Sales Rep Commissison</h6> 
            </div>
        </div>
     
        <div class="card-body">
		
            <div class="table-responsive">
                <table class="table " id="tbl-sales-rep-commission-listing" width="100%" cellspacing="0">
                    <thead>
                        <tr class="align-center" >
							<th >Month</th>
							<th>Commission</th>
							<th>File Name</th>
							<th>Action</th>
                        </tr>
						
                    </thead>                
					<?php if(!empty($commissionHistory)) {?>
										<tbody>
											<?php foreach($commissionHistory as $key=>$commissionRecord) { ?>
												<?php
													$total_commission = ($commissionRecord['commission_data']) ? $commissionRecord['commission_data']->commission : 0;
													$details_json = $commissionRecord['commission_data']->commission_details;
													$details = array();
													$draw_amount = $commission_sub_total=$escrow_commission=$first_in_threshold=$override_commission_total = $bonus= 0;
													$override_add_user = "";
													$override_add_per=$override_add_val = array();
													$month_num = $commissionRecord['month_num'];

													if(!empty( $details_json) && json_decode( $details_json)) {

														$details = json_decode($details_json);
													}
													$details_arr = array();
													$prod_array = PRODUCT_TYPE;
													$underwriter_array = UNDERWRITERS;
													$underwriter_array['escrow'] = 'Escrow';
													foreach($prod_array as $prod) {
														foreach($underwriter_array as $und_key=>$underwriter) {
															$details_arr[$prod][$und_key] = 0;
														}
													}

													foreach($details as $detail_json) {
														$detail = json_decode($detail_json);
														$prod_type = $detail->prod_type;
														$underwriter = $detail->underwriter;
														if( in_array($prod_type,$prod_array)) {
															
															if(isset($details_arr[$prod_type][$underwriter])) {
																$details_arr[$prod_type][$underwriter] += $detail->commisison;
															}
															else {
																$details_arr[$prod_type][$underwriter] = $detail->commisison;
															}
														}
														elseif($prod_type == 'draw') {
															$draw_amount = $detail->commisison;
														}
														elseif($prod_type == 'first_threshold') {
															$first_in_threshold = $detail->commisison;
														}
														elseif($prod_type == 'override_add') {
															$override_add_user = getUserName($detail->user_id);
															if($detail->loan > 0) {
																$override_add_per['loan'] = $detail->loan;
															}
															if($detail->sale > 0) {
																$override_add_per['sale'] = $detail->sale;
															}
															if($detail->escrow > 0) {
																$override_add_per['escrow'] = $detail->escrow;
															}
															if(count($override_add_per)) {
																$condition = [
																	'user_id' => $detail->user_id,
																	'commission_month' => $month_num,
																	'commission_year' => date('Y'),
																];
																$override_add_val= getExtraCommission($override_add_per,$condition);
															}
															if ($override_add_user) :
															 	foreach($override_add_val as $override_add_key=>$override_add_comm) :
																	if($override_add_key == 'escrow' && is_array($override_add_comm)):
																		$override_commission_val = array_sum($override_add_comm);
																	else:
																		$override_commission_val = $override_add_comm;
																	endif;

																	$total_commission += $override_commission_val;
																	
																	endforeach;
															endif;

														}
														elseif($prod_type == 'bonus') {
															$bonus = $detail->commisison;
														}
													}
													if($total_commission < 0 &&  abs($draw_amount) == 0 && abs($first_in_threshold) > 0 ):
														$total_commission = 0;
													endif;
													?>
												<tr class="text-center">
													<td><?php echo $commissionRecord['month'];?></td>
													<th>$ <?php echo number_format($total_commission,2);?> </th>
													
													<td><?php echo ($commissionRecord['commission_data']) ? $commissionRecord['commission_data']->pdf_name : '';?></td>
													<td>
													<?php
													if($commissionRecord['commission_data'] && $commissionRecord['commission_data']->commisssion_pdf) :
														$documentUrl = env('AWS_PATH')."file_document/".$commissionRecord['commission_data']->commisssion_pdf;
														?>
														<a class="btn btn-info"   target='_blank' href='<?php echo $documentUrl;?>'>View</a>
														<?php
													else : ?>
														 &nbsp;
													<?php endif; ?>
													
													<a href="#collapseCard_<?php echo $key; ?>" class="btn btn-secondary custom__collapse_arrow collapsed" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="collapseCard_<?php echo $key; ?>">
															<span class="div__expand">Expand</span>
															<span class="div__collapse">Collapse</span>
														</a>
													</td>
												</tr> 
												<tr  class="custom__task_collapse collapse" id="collapseCard_<?php echo $key; ?>" aria-expanded="false">
													<td colspan="4">
														<div class="card">
															<div class="card-body">
																<table class="table">
																	
																	<tr>
																		<?php
																		foreach($details_arr as $prod_key=>$details_obj) :
																			$total_commission_val = 0;
																		?>
																		<td style="border: none;">
																			
																			<table class="table">
																				<?php
																				$numItems = count($details_obj);
																				$comm_i = 0;
																					foreach($details_obj as $und_key=>$commission_val):
																						$total_commission_val += $commission_val;
																						if($comm_i == 0) : 
																					?>
																					<tr>
																						<th colspan="2" class="text-center"><?php echo ucwords($prod_key); ?></th>
																					</tr>
																					<?php
																						endif;
																					?>
																					<tr>
																						<th class="text-left"><?php echo ucwords($und_key); ?></th>
																						<td class="text-right">$ <?php echo number_format($commission_val,2) ; ?></td>
				
																					</tr>
																					
																				<?php
																					if(++$comm_i === $numItems) : 
																						$commission_sub_total += $total_commission_val;
																					?>
																					<tr class="custom__total">
																						<th class="text-left">Total Commission</th>
																						<td class="text-right">$ <?php echo number_format($total_commission_val,2); ?></td>
																					</tr>
		
																				<?php
																					endif;
																					endforeach;
																				?>
																			</table>
		
																		</td>
																			
																			
																		<?php
																		endforeach;
																		?>
		
																	</tr>
																	<tr class="custom__total">
																		<th class="text-left">Commission SubTotal : ( <?= implode(' + ',array_map("ucwords", PRODUCT_TYPE)); ?> )</th>
																		<td class="text-right">$ <?php echo number_format($commission_sub_total,2); ?></td>
																	</tr>
																	<?php if ($bonus) : ?>
																	<tr class="custom__total">
																		<th class="text-left">Bonus</th>
																		<td class="text-right">$ <?php echo number_format($bonus,2); ?></td>
																	</tr>
																	<?php endif; ?>
																	<?php if ($draw_amount) : ?>
																	<tr class="custom__total">
																		<th class="text-left">Draw Amount</th>
																		<td class="text-right">- $ <?php echo number_format(abs($draw_amount),2); ?></td>
																	</tr>
																	<?php endif; ?>
																	<?php if ($first_in_threshold) : ?>
																	<tr class="custom__total">
																		<th class="text-left">First In Threshold Amount</th>
																		<td class="text-right">- $ <?php echo number_format(abs($first_in_threshold),2); ?></td>
																	</tr>
																	<?php endif; ?>
																	<?php if ($override_add_user) : 
																		$override_i = 0;
																		?>
																		<?php foreach($override_add_val as $override_add_key=>$override_add_comm) :
																				if($override_add_key == 'escrow' && is_array($override_add_comm)):
																					$override_commission_val = array_sum($override_add_comm);
																				else:
																					$override_commission_val = $override_add_comm;
																				endif;

																				$override_commission_total += $override_commission_val;
																			?>
																			
																			<tr class="<?=($override_i++ == 0) ? 'custom__total' : '';?>">
																				<th class="text-left">Extra Commission <?php echo ucwords($override_add_key) ?> : <?php echo $override_add_user; ?></th>
																				<td class="text-right">$ <?php echo number_format($override_commission_val,2); ?></td>
																			</tr>
																		<?php endforeach; ?>
																	<?php endif; ?>
																	
																	<tr class="custom__total">
																		<th class="text-left">Total Commission</th>
																		<td class="text-right"> $ <?php echo number_format(($commission_sub_total - abs($draw_amount) - abs($first_in_threshold) + $override_commission_total + $bonus),2); ?></td>
																	</tr>
																	
		
																</table>

															</div>

														</div>
														
													</td>

												</tr>
											<?php } ?> 
										</tbody>
									<?php } else {?>
										<tbody>
											<tr>
												<td align="center" colspan="4"> No Records Found.</td>
											</tr>
										</tbody>
									<?php } ?>
                </table>
            </div>
        </div>
    </div>
</div>
