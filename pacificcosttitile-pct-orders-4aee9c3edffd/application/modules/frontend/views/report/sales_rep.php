<style type="text/css">
	.u-list {
	    margin-bottom: 15px;
	}
	.u-list, .u-list li {
	    margin: 0;
	    padding: 0;
	    list-style: none;
	}
	.u-list li:nth-child(2n+1) {
	    background: #bff0c685;
	}
	.u-list li {
	    padding: 10px;
	    display: table;
	    width: 100%;
	}
	.u-list .u-pic, .u-list .u-info {
	    display: block;
	    vertical-align: top;
	}
	.u-list .u-pic {
	    width: 80px;
	    height: 80px;
	    overflow: hidden;
	    border-radius: 100%;
	    margin-right: 15px;
	    float: left;
	}
	.u-list .u-pic img {
	    margin: 0;
	    border: 0;
	    max-width: 100%;
	}
	.u-list .u-info {
	    padding-top: 5px;
	    margin-left: 35px;
	    float: left;
	}
	.u-list .u-name {
	    font-weight: bold;
	    color: #000;
	}
	.u-list .u-count {
		float: right;
		font-size: 20px;
    	margin-right: 10px;
	}
	.no-report-image {
	    text-align: center;
	    background: #f0f0f0;
	    height: 80px;
	    padding-top: 16px;
	    font-size: 30px;
	    font-weight: 600;
	}
	.u-list li:nth-child(2n+1) .no-report-image{
	    background: #ffffff;
	}

	.ui-title-block + .ui-decor-1a {
		margin-top: 22px;
		margin-bottom: 34px;
	}

	.bg-accent {
		background-color: #d35411;
	}

	.ui-decor-1a {
		display: inline-block;
		width: 100px;
		height: 2px;
	}
	
	.align-justify {
		justify-content: space-between;
	}
</style>

<section class="section-type-4a section-defaulta" style="padding-bottom:0px;">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">                                                                                                                                                                                                                                                                                                                                                                         
				<div class="">
					<div class="d-flex align-justify">
						<h2 class="ui-title-block ui-title-block_light mb-0">Sales Representatives</h2>
						<a href="<?php echo base_url('reports') ?>" class="btn btn-info btn-icon-split pull-right">
							<span class="icon text-white-50">
								<i class="fas fa-arrow-left"></i>
							</span>
							<span class="text">Back</span>
						</a>
					</div>
					<div class="ui-decor-1a bg-accent"></div>
					
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<!-- <h2>List</h2> -->
				<ul class="u-list">
					<?php
					foreach($salesReps as $key=>$salesRep):
					?>
					<li>
						<div class="u-pic">
						<?php
						$image_url = trim(env('AWS_PATH').$salesRep['sales_rep_report_image']);
						if (!empty($salesRep['sales_rep_report_image'])):
						?>
							<img src="<?php echo $image_url;?>" alt="main-logo" class="retina">
							<?php else : ?>
							<div class="no-report-image"><span><?php echo strtoupper(substr(trim($salesRep['first_name']) , 0,1).substr(trim($salesRep['last_name']) , 0,1)) ?></span></div>
							<?php endif; ?>
						</div>
						<div class="u-info">
							<div class="u-name"><?php echo $salesRep['first_name'].' '.$salesRep['last_name'] ?></div>
							<div><?php echo $salesRep['email_address'];?></div>
							<div>
							<?php echo $salesRep['telephone_no'];?>
							</div>
						</div>
						<div class="u-count">
							<div>
								<a href="<?php echo base_url('reports/sales_rep').'/'.$salesRep['id'] ?>" class="btn btn-primary btn-icon-split">
									<span class="icon text-white-50">
										<i class="fas fa-edit"></i>
									</span>
									<span class="text">Edit</span>
								</a>
							</div>
						</div>
					</li>
					<?php
					
					endforeach;
					?>
				</ul>
					
			</div>

		</div>
	</div>
</section>

	

