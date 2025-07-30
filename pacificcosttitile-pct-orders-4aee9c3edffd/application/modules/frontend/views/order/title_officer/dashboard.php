
	<style type="text/css">
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

		select#month_filter, select#orders_filter, select#order_type_filter {
			margin-bottom: 0px;
			margin-left: 0.5em;
			border: 1px solid #cbd2d6;
			border-radius: 3px;
			padding: 0px 22px 0px;
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
			font-size: 20px;
			line-height: 23px;
			color: #a0a0a0;
		}

		.salesdivider {
			border-bottom: 1px solid #fff;
			padding-top: 20px;
			padding-bottom: 20px;
		}

		.projected_goal_section {
			color: #d35411;
			font-weight: bold;
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

		#title_officer_orders_listing {
			margin-right: 25px;
			width: 100%;
		}
		.custom-select {
			border-radius: 5px;
		}
		.ui-title-block {
			font-size: 36px;
		}
		.section-type-4a .btn {
			border-radius: 5px;
		}
		.pagination > li > a {
			border-radius: 5px;
		}
		.dropdown-menu {
			margin-top: 0px !important
		}
		.dropdown-menu > li > a {
			padding: 5px 0px 5px 0px;
		}
		.dropdown .click-action-type {
			color: #222222;
			text-decoration: none;
		}
		.tooltiptext {
			visibility: hidden;
			/* width: 120px; */
			background-color: #969393;
			color: #fff;
			text-align: center;
			border-radius: 6px;
			padding: 5px;
			margin-left: 5px;
			margin-top: 15px;
			position: absolute;
			z-index: 1;
		}

		.fa-info-circle {
			font-size: 16px;
		}

		.fs-2 {
			font-size: 20px;
		}

		.fs-1-half {
			font-size: 15px;
		}

		.text-center {
			text-align: center;
		}

		.anchor-hover {
			position: absolute;
			z-index: 1;
			height: 100%;
			top: 0;
			width: 100%;
		}

		.padding-0 {
			padding: 0;
		}

		.dashboard-menu-icon {
			height: 4rem;
			width: 4rem;
		}
		.main-wrapper {
			scale: 95%;
		}
	</style>
	<!-- <section class="section-type-4a section-defaulta" style="padding-bottom:0px;"> -->
	<div class="container-fluid p-5 main-wrapper">
		<div class="row">
			<div class="col-md-12">
				<div class="typography-section__innera">
					<h2 class="ui-title-block ui-title-block_light mb-5">Welcome Back <?php echo $name; ?></h2>
					<div class="ui-decor-1a bg-accent"></div>
				</div>
			</div>
		</div>

		<div class="row mb-4">
			<div class="col-md-4 col-sm-12">
				<div class="card border-left-primary shadow h-100 py-2">
					<div class="card-body">
						<div class="row no-gutters align-items-center" style="flex-direction: column; text-align: center;" >
							<div class="col-auto">
								<img class="dashboard-menu-icon" src="<?php echo base_url()?>assets/frontend/images/New.png">
							</div>
							<div class="col mr-2 mt-3">
								<div class="text-xl font-weight-bold text-primary text-uppercase mb-1">Blank Forms</div>
							</div>
						</div>
					</div>
					<a href="<?php echo base_url('uplod-file-document') ?>" class="anchor-hover"></a>
				</div>
			</div>

			<div class="col-md-4 col-sm-12">
				<div class="card border-left-primary shadow h-100 py-2">
					<div class="card-body">
						<div class="row no-gutters align-items-center" style="flex-direction: column; text-align: center;" >
							<div class="col-auto">
								<img class="dashboard-menu-icon" src="<?php echo base_url()?>assets/frontend/images/CPL.png">
							</div>
							<div class="col mr-2 mt-3">
								<div class="text-xl font-weight-bold text-primary text-uppercase mb-1">Generate CPL</div>
							</div>
						</div>
					</div>
					<a href="<?php echo base_url().'cpl-dashboard'; ?>" class="anchor-hover"></a>
				</div>
			</div>

			<div class="col-md-4 col-sm-12">
				<div class="card border-left-primary shadow h-100 py-2">
					<div class="card-body">
						<div class="row no-gutters align-items-center" style="flex-direction: column; text-align: center;" >
							<div class="col-auto">
								<img class="dashboard-menu-icon" src="<?php echo base_url()?>assets/frontend/images/Proposed.png">
							</div>
							<div class="col mr-2 mt-3">
								<div class="text-xl font-weight-bold text-primary text-uppercase mb-1">Proposed</div>
							</div>
						</div>
					</div>
					<a href="<?php echo base_url().'proposed-insured'; ?>" class="anchor-hover"></a>
				</div>
			</div>
		</div>

		<div class="row mt-3 mb-4">
			<div class="col-md-4 col-sm-12">
				<div class="card border-left-primary shadow h-100 py-2">
					<div class="card-body">
						<div class="row no-gutters align-items-center" style="flex-direction: column; text-align: center;" >
							<div class="col-auto">
								<img class="dashboard-menu-icon" src="<?php echo base_url()?>assets/frontend/images/Fees.png">
							</div>
							<div class="col mr-2 mt-3">
								<div class="text-xl font-weight-bold text-primary text-uppercase mb-1">Add Notes</div>
							</div>
						</div>
					</div>
					<a href="<?php echo base_url().'notes'; ?>" class="anchor-hover"></a>
				</div>
			</div>

			<div class="col-md-4 col-sm-12">
				<div class="card border-left-primary shadow h-100 py-2">
					<div class="card-body">
						<div class="row no-gutters align-items-center" style="flex-direction: column; text-align: center;" >
							<div class="col-auto">
								<img class="dashboard-menu-icon" src="<?php echo base_url()?>assets/frontend/images/Prelim.png">
							</div>
							<div class="col mr-2 mt-3">
								<div class="text-xl font-weight-bold text-primary text-uppercase mb-1">Review Prelime</div>
							</div>
						</div>
					</div>
					<a href="<?php echo base_url().'prelim-files'; ?>" class="anchor-hover"></a>
				</div>
			</div>

			<div class="col-md-4 col-sm-12">
				<div class="card border-left-primary shadow h-100 py-2">
					<div class="card-body">
						<div class="row no-gutters align-items-center" style="flex-direction: column; text-align: center;" >
							<div class="col-auto">
								<img class="dashboard-menu-icon" src="<?php echo base_url()?>assets/frontend/images/Upload.png">
							</div>
							<div class="col mr-2 mt-3">
								<div class="text-xl font-weight-bold text-primary text-uppercase mb-1">Upload Docs</div>
							</div>
						</div>
					</div>
					<a href="<?php echo base_url().'upload-doc-orders'; ?>" class="anchor-hover"></a>
				</div>
			</div>
		</div>
	
		<section class="section-type-4a section-defaulta mt-5" style="padding-bottom:0px;">
			<div class="container-fluid padding-0">
				<div class="row mb-3">
					<div class="col-sm-12">
						<h1 class="h3 text-gray-800 text-center">Recent Orders </h1>
					</div>
				</div>
				<div class="card shadow mb-4">
					<div class="card-header datatable-header py-3">
						<div class="datatable-header-titles" > 
							
							<h6 class="m-0 font-weight-bold text-primary pl-10">Below are all your orders</h6> 
						</div>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<table class="table table-bordered" id="title_officer_orders_listing" width="100%" cellspacing="0">
								<thead>
									<tr>
										<th>#</th>
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
		</section>
				<div class="row">
					<div class="col-xs-12">
						<!-- <div class="typography-section__inner">
							<h2 class="ui-title-block ui-title-block_light">Welcome Back <?php echo $name; ?>,</h2>
							<div class="ui-decor-1a bg-accent"></div>
							<div class="typography-sectiona">
								<div class="col-md-12">
									<a href="<?php echo base_url('uplod-file-document') ?>">
										<button class="btn1 btn-type-1a btn-lg" type="button">Blank Forms</button>
									</a>
									<a href="<?php echo base_url().'cpl-dashboard'; ?>">
										<button class="btn1 btn-type-1b btn-lg" type="button">Generate CPL</button>
									</a>
									<a href="<?php echo base_url().'proposed-insured'; ?>">
										<button class="btn1 btn-type-1e btn-lg" type="button">Proposed</button>
									</a>
								</div>
							</div>

							<div class="typography-sectionc">
								<div class="col-md-12">
									<a href="<?php echo base_url().'notes'; ?>">
										<button class="btn1 btn-type-1g btn-lg" type="button">Add Notes</button>
									</a>
									
									<a href="<?php echo base_url().'prelim-files'; ?>">
										<button class="btn1 btn-type-1c btn-lg" type="button">Review Prelim</button>
									</a>
									<a href="<?php echo base_url().'upload-doc-orders'; ?>">
										<button class="btn1 btn-type-1d btn-lg" type="button">Upload Doc</button>
									</a>
								</div>
							</div>
						</div> -->

						<!-- <div class="typography-sectiona">
							<div class="col-md-12">
								<div class="table-container">
									<table class="table table-type-3 typography-last-elem" id="title_officer_orders_listing">
										<thead>
											<tr>
												<th>#</th>
												<th>Opened</th>
												<th>Property Address</th>
												<th>Status</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody></tbody>
									</table>
									<div class="typography-sectionab"></div>
								</div>
							</div>
						</div> -->
					</div>
				</div>
			<!-- </div> -->
		</div>
	<!-- </section> -->
	<div class="modal" id="partnersModal">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">Partners</h4>
				</div>

				<div class="modal-body">
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
					<button type="button" class="btn btn-danger" data-dismiss="modal"
						style="background: #d35411;">Close</button>
				</div>

			</div>
		</div>
	</div>
	


