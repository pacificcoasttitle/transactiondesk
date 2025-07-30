<div class="container-fluid">
	<div class="card shadow mb-4">
		<div class="card-body">
			<div class="align-wrapper">
				<h1 class="h3 mb-2 text-gray-800">Trends</h1>
				<div class="ui-decor-1a bg-accent"></div>
				<?php if(!empty($salesUsers) && $is_sales_rep_manager == 1) { ?>
					<div id="sales_user_listing">
						<label>
							<select style="width:auto;" name="sales_user_trend_filter" id="sales_user_trend_filter" class="custom-select custom-select-sm form-control form-control-sm"> 
								<!-- <option value="all"> All Sales Rep Users </option> -->
								<?php foreach($salesUsers as $salesUser) { ?>
									<option <?Php echo ($sales_user_id == $salesUser['id']) ? 'selected' : '';?> value="<?php echo $salesUser['id'];?>"><?php echo $salesUser['first_name']." ".$salesUser['last_name'];?></option>
								<?php }?>
							</select>
						</label>
					</div>
				<?php } ?>
			</div>			
			<div class="row">
				<div class="col-xl-12 col-lg-7">
					<div class="card shadow mb-4">
						<div class="card-header py-3">
							<h6 class="m-0 font-weight-bold text-primary">Title Openings MTD Overview</h6>
						</div>
						<div class="card-body">
							<div class="chart-area">
								<canvas id="openOrdersChart"></canvas>
							</div>
						</div>
					</div>

					<div class="card shadow mb-4">
						<div class="card-header py-3">
							<h6 class="m-0 font-weight-bold text-primary">Title Closings MTD Overview</h6>
						</div>
						<div class="card-body">
							<div class="chart-area">
								<canvas id="closedOrderChart"></canvas>
							</div>
						</div>
					</div>

					<div class="card shadow mb-4">
						<div class="card-header py-3">
							<h6 class="m-0 font-weight-bold text-primary">Title Revenue MTD Overview</h6>
						</div>
						<div class="card-body">
							<div class="chart-area">
								<canvas id="premiumTotalChart"></canvas>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- /.container-fluid -->


<script>
    var salesData = <?php echo json_encode($salesHistory);?>;
</script>
