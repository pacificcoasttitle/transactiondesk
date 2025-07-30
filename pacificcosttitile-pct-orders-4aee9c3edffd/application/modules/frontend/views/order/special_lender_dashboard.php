<style>
    td, th {
        line-height: 20px !important;
    }
</style>

<!-- <section class="section-type-4a section-default typography-section-border" style="margin-bottom:50px;">
	<div class="container">
		<div class="row">
			<div class="row">
				<div class="col-xs-12">
					<div class="typography-section__innera">
						<h2 class="ui-title-block ui-title-block_light">Welcome Back <?php echo $name; ?>,</h2>
						<div class="ui-decor-1a bg-accent"></div>
						<h3 class="ui-title-block_light">How can we help you today?</h3>
					</div>
				</div>
			</div>
		</div>
	</div>
</section> -->
<section class="section-type-4a section-defaulta" style="padding-bottom:0px;">
	<div class="container-fluid">
		<div class="row mb-3">
			<div class="col-sm-6">
				<h1 class="h3 text-gray-800">Welcome Back <?php echo $name; ?> </h1>
			</div>
		</div>
		<div class="card shadow mb-4">
			<div class="card-header datatable-header py-3">
				<div class="datatable-header-titles" > 
					<span>
						<i class="fas fa-users"></i>
					</span>
					<h6 class="m-0 font-weight-bold text-primary pl-10">Below are all your orders</h6> 
				</div>
			</div>
			
			<div class="card-body">
				<div class="table-responsive">
					<table class="table table-bordered" id="orders_listing" width="100%" cellspacing="0">
						<thead>
							<tr>
								<th>#</th>
								<th>File Number</th>
								<th>Opened</th>
								<th>Property Address</th>
								<th>Buyer/Seller</th>
								<th>Sales Rep.</th>
								<th>Escrow Partner Company Name</th>
							</tr>
						</thead>                
						<tbody></tbody>
					</table>
				</div>
			</div>
		</div>
		<!-- <div class="row">
			<div class="row">
				<div class="col-xs-12">
					<div class="typography-section__inner">
						<h2 class="ui-title-block ui-title-block_light">Orders</h2>
						<div class="ui-decor-1a bg-accent"></div>
						<h3 class="ui-title-block_light">Below are all your orders.</h3>
					</div>
					<div class="typography-sectiona">
						<div class="col-md-12">
							<div class="table-container">
								<table class="table table-type-3 typography-last-elem" id="orders_listing">
									<thead>
										<tr>
											<th>#</th>
											<th>File Number</th>
											<th>Opened</th>
											<th>Property Address</th>
											<th>Buyer/Seller</th>
											<th>Sales Rep.</th>
											<th>Escrow Partner Company Name</th>
										</tr>
									</thead>
									<tbody>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div> -->
	</div>
</section>



