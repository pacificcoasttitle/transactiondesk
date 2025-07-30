
<style type="text/css">
	th {
		text-align: center;
	}
	.month-name {
		text-decoration: underline;
		color: #d35411
	}
</style>
<div class="container-fluid">
		<div class="card shadow mb-4">
			<div class="card-body">
				<div class="col-xs-12">
					<div class="order-count-cotainer">
						<h4 class="ui-title-block_light">Production figures for the current month of <b class="month-name"><?php echo date('F');?></b></h3>
					</div>	
					
					<div class="card shadow mb-4">
						<div class="card-body">
							<div class="table-responsive">
								<table class="table table-bordered" id="production_history_tab" width="100%" cellspacing="0">
									<thead>
										<tr>
											<th>Sales Rep.</th>
											<th>Total Openings</th>
											<th>Total Closings</th>
											<th>Total Revenue</th>
										</tr>
									</thead>
									<?php if(!empty($salesHistory)) {?>
										<tbody>
											<?php foreach($salesHistory as $salesData) { ?>
												<tr>
													<td align="center"><?php echo $salesData['sales_rep'];?></td>
													<td align="center"><?php echo $salesData['total_open_count'];?></td>
													<td align="center"><?php echo $salesData['total_close_count'];?></td>
													<td align="center"><?php echo "$".number_format($salesData['total_premium']);?></td>
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
