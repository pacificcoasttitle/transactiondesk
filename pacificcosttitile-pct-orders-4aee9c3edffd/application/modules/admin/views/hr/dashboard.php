<style>
	#calendar {
		margin: 25px;
		font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
		font-size: 14px;
	}

	#loading {
		display: none;
		position: absolute;
		top: 10px;
		right: 10px;
	}

	.filter_label {
		padding:8px 10px 8px 25px;
	}
</style>

<div class="container-fluid">
	
	<div id="dashboard_count"></div>
	<div class="row">
		<div class="col-xl-3 col-md-6 mb-4">
			<div class="card border-left-primary shadow h-100 py-2">
				<div class="card-body">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
								Time Cards (Pending)</div>
							<div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $pending_timecard_count;?></div>
						</div>
						<div class="col-auto">
							<i class="fas fa-clock fa-2x text-gray-300"></i>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="col-xl-3 col-md-6 mb-4">
			<div class="card border-left-success shadow h-100 py-2">
				<div class="card-body">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<div class="text-xs font-weight-bold text-success text-uppercase mb-1">
								Vacation Requests (Pending)</div>
							<div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $pending_vacation_request_count;?></div>
						</div>
						<div class="col-auto">
							<i class="fas fa-table fa-2x text-gray-300"></i>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="col-xl-3 col-md-6 mb-4">
			<div class="card border-left-info shadow h-100 py-2">
				<div class="card-body">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<div class="text-xs font-weight-bold text-info text-uppercase mb-1">Report Incident (Pending)</div>
							<div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo $pending_report_incident_count;?></div>
						</div>
						<div class="col-auto">
							<i class="fas fa-file fa-2x text-gray-300"></i>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="col-xl-3 col-md-6 mb-4">
			<div class="card border-left-warning shadow h-100 py-2">
				<div class="card-body">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
								Pending Training</div>
							<div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $pending_training_count;?></div>
						</div>
						<div class="col-auto">
							<i class="fas fa-sticky-note fa-2x text-gray-300"></i>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-xl-12">
			
			<div class="card shadow mb-4">
				<div class="card-header py-3">
					<h6 class="m-0 font-weight-bold text-primary">Vacation Requests Calendar</h6>
				</div>
				<div id='loading'>loading...</div>	
				<div id='calendar'></div>
			</div>
		</div>
	</div>
</div>
<!-- /.container-fluid -->



