<style>
	.alert-1, .alert-6, .alert-4, .alert-7 {
		padding: 15px !important;
	}
	.entry-content p {
		font-size: 18px;
	}
	.dropdown-btn {
		border-bottom: none !important;
	}

	.btn .icon {
		display: inline-block;
		width: auto;
		height: auto;
		margin-right: 0px;
		margin-left: 0px;
		vertical-align: super;
		background: rgba(0,0,0,.15);
	}
</style>

<!-- <section class="section-type-4a section-defaulta" style="padding-bottom:0px;"> -->
	<div class="container-fluid">
		<div class="row mb-3">
            <div class="col-sm-12">
				<a style="float:right" class="btn-success btn-icon-split btn-sm " href="<?php echo base_url();?>prelim-files">
					<span class="icon text-white-50">
						<i class="fa fa-arrow-left"></i>
					</span>
					<span class="text">Back</span>
				</a>
            </div>
        </div>
		<div class="card shadow mb-4">
			<div class="card-body">
				<div class="col-xs-12">
					<div class="typography-section__inner mt-0">
						<h2 class="ui-title-block ui-title-block_light fn-36">Preliminary Report Review</h2>
						<div style="border-bottom: 4px solid #D35411;"></div>
						<div class="ui-decor-1a bg-accent mt-0 mb-0"></div>
						<h3 class="ui-title-block_light"><b>File Number <?php echo $orderDetails['file_number']; ?></b></h3>
						<h3 class="ui-title-block_light"></h3>
						<div class="wrapper-alignment">
							<h3 class="ui-title-block_light" style="display: inline-block;"><?php echo $orderDetails['full_address'];?></h3>
							<!-- <span class="bg-border" style="float: right;background: #d35411;margin-left:10px;"><a style="color:#fff;" href="<?php echo base_url();?>update-prelim-action/<?php echo $orderDetails['file_id'];?>">Update Prelim Action</a></span> -->
							<div>
								<button class="btn-success btn-icon-split btn-sm" onClick="window.location.reload();">
									<span class="icon text-white-50">
										<i class="fa fa-refresh"></i>
									</span>
									<span class="text">Refresh</span>
								</button>
								<button class="btn-success btn-icon-split btn-sm" onClick="updateAction();">
									<span class="icon text-white-50">
										<i class="fa fa-upload"></i>
									</span>
									<span class="text">Update Prelim Action</span>
								</button>
							</div>

							<!-- <span class="bg-border" style="float: right;cursor:pointer;background: #d35411;" onClick="window.location.reload();">Refresh</span> -->
						</div>

						<div style="width: 100%;margin-top:10px;">
							<?php if(!empty($success)) { ?>
								<div id="prelim_action_success_msg" class="w-100 alert alert-success alert-dismissible">
									<?php echo $success;?>
								</div>
							<?php } if(!empty($error)) { ?>
								<div id="prelim_action_success_msg" class="w-100 alert alert-danger alert-dismissible">
									<?php echo $error."<br \>";	?>
								</div>
							<?php } ?>
						</div>

						<input type="hidden" id="fileId" name="fileId" value="<?php echo $orderDetails['file_id'];?>">
						<input type="hidden" id="orderId" name="orderId" value="<?php echo $orderDetails['order_id'];?>">
					</div>

					<div class="typography-sectionabcd">
						<div class="row col-md-12">
							<div class="col-md-2">
								<div class="typography-section__inner">
									<h3 class="ui-title-block_light">Doc Links</h3>
									<div class="ui-decor-1a bg-accent"></div>
								</div>
								<aside class="l-sidebarb l-sidebar_right">
									<section class="widget section-sidebara">

										<div class="widget-contenta">
											<div class="header-navibox-2">
												<ul class="yamm2 nav navbar-nav2">
													<li class="review_li nav-bottom-border"><a href="javascript:void(0);" onclick="summary();">Summary</a></li><br>
													<?php  if(!empty($prelimDocument)) { ?>
														<li class="review_li nav-bottom-border">
															<a onclick="load_doc(<?php echo $prelimDocument['is_sync'];?>, <?php echo $prelimDocument['api_document_id'];?>, <?php echo $prelimDocument['order_id'];?>, <?php echo $prelimDocument['id'];?>);" href="javascript:void(0);">
																Prelim
															</a>
														</li>
														<br>
													<?php } else { ?>
														<li class="review_li nav-bottom-border">
															<a href="javascript:void(0);" >Prelim</a></li><br>
													<?php } ?>
													<li class="review_li nav-bottom-border">
														<button class="dropdown-btn">Linked Docs
															<i style="font-size:16px;" class="fa fa-caret-down"></i>
														</button>
														<div class="dropdown-container">
															<ol> 
															<?php 
																if(!empty($linked_doc)) {
																	$count = count($linked_doc);
																	$i = 1;
																	
																	foreach($linked_doc as $document) { 
																		if (!empty($document['original_document_name'])) {
																			
																		?>
																		<li ><a id="<?php echo $document['api_document_id'];?>" style="<?php echo $style;?>" onclick="load_doc(<?php echo $document['is_sync'];?>, <?php echo $document['api_document_id'];?>, <?php echo $document['order_id'];?>, <?php echo $document['id'];?>);" class="linked_doc" href="javascript:void(0);"><?php echo $document['index_number'].". ".$document['original_document_name'];?></a></li>
																	<?php  } $i++; } 
																	} else { ?>
																	<a class="linked_doc" href="#">No Documents Found</a>
																<?php } 
															?>
														</ol>
														</div>
													</li>
													<br>
													<li class="review_li nav-bottom-border"><a href="javascript:void(0);" onclick="legal_vesting();">Legal Vesting</a></li><br>
													<li class="review_li nav-bottom-border"><a href="javascript:void(0);" onclick="plat_map();">Plat Map</a></li>
													<li class="review_li nav-bottom-border">
														<button class="dropdown-btn">Uploaded Docs
															<i style="font-size:16px;" class="fa fa-caret-down"></i>
														</button>
														<div class="dropdown-container">
															<ol > 
															<?php 
																if(!empty($uploaded_docs)) {
																	$count = count($uploaded_docs);
																	$i = 1;
																	foreach($uploaded_docs as $document) { 
																		?>
																		<li style="width: 100%;"><a id="<?php echo $document['api_document_id'];?>" style="<?php echo $style;?>display: list-item;" onclick="load_doc(<?php echo $document['is_sync'];?>, <?php echo $document['api_document_id'];?>, <?php echo $document['order_id'];?>, <?php echo $document['id'];?>);" class="linked_doc" href="javascript:void(0);"><?php echo $i.". ".$document['original_document_name'];?></a></li>
																	<?php  $i++; } 
																	} else { ?>
																	<a class="linked_doc" href="#">No Documents Found</a>
																<?php } 
															?>
														</ol>
														</div>
													</li>
												</ul>
											</div>
										</div>
									</section>

									<div class="typography-section__inner">
										<h3 class="ui-title-block_light">Order Details</h3>
										<div class="ui-decor-1a bg-accent"></div>
									</div>
									
									<section class="widget section-sidebar">
										<div class="widget-content2">
											<ul class="widget-list lista">
												<li class="widget-list__item"><a class="widget-list__link fs-20"
														href="javascript:void(0)">Borrower Name</a><br><?php echo $orderDetails['primary_owner'];?></li>
												<div class="ui-decor-3"></div>
												<li class="widget-list__itema"><a class="widget-list__link fs-20"
														href="javascript:void(0)">Transaction Type</a><br><?php echo $orderDetails['product_type']; ?></li>
												<div class="ui-decor-3"></div>
												<?php
													if(strpos($orderDetails['product_type'], 'Sale') !== false)
													{
														if(isset($orderDetails['sales_amount']) && !empty($orderDetails['sales_amount']))
														{
															$sales_amount = str_replace(",", "", $orderDetails['sales_amount']);
														}
												?>
														<li class="widget-list__itema"><a class="widget-list__link fs-20"
														href="javascript:void(0)">Sales Amount</a><br><?php echo isset($sales_amount) && !empty($sales_amount) ? "$".number_format($sales_amount) : '-' ;?></li>
														<div class="ui-decor-3"></div>

												<?php
													}
												?>
												<?php
													if(isset($orderDetails['loan_amount']) && !empty($orderDetails['loan_amount']))
													{
														$loan_amount = str_replace(",", "", $orderDetails['loan_amount']);
													}
												?>
												<li class="widget-list__itema"><a class="widget-list__link fs-20"
														href="javascript:void(0)">Loan Amount</a><br><?php echo isset($loan_amount) && !empty($loan_amount) ? "$".number_format($loan_amount) : '-' ;?></li>
												<div class="ui-decor-3"></div>
												<li class="widget-list__itema"><a class="widget-list__link fs-20"
														href="javascript:void(0)">Open Date</a><br><?php echo date("m/d/Y", strtotime($orderDetails['opened_date'])); ?></li>
											</ul>
										</div>
									</section>
									<!-- end .widget-->
								</aside>
							</div>
							<div class="col-md-1"></div>
							<!-- <div class="col-md-1"></div> -->
							<div class="col-md-9" id="links_details">
								
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<!-- </section> -->

<div class="modal fade" width="500px" id="note_information" tabindex="-1" role="dialog"
	aria-labelledby="Create a Note" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document" style="width:40%;">
		<div class="modal-content">
			<form method="POST" action="<?php echo base_url();?>update-prelim-action/<?php echo $orderDetails['file_id'];?>" enctype="multipart/form-data">
				<div class="row">
					<div class="col-lg-12">
						<div class="card shadow">
							<div class="card-header py-3">
								<h6 class="m-0 font-weight-bold text-primary" >Add a Note</h6>
							</div>
							<div class="card-body"> 
								<div class="smart-forms smart-container">
									<div class="modal-body search-result">
										<div class="form-group">
											<div class="row">
												<div class="col-sm-12">
													<label for="note_subject" class="col-form-label">Subject</label>
													<input type="text" name="note_subject" id="note_subject" class="form-control gui-input ui-autocomplete-input" placeholder="Subject" required="">
												</div>
											</div>
										</div>

										<div class="form-group">
											<div class="row">
												<div class="col-sm-12">
													<label for="note" class="col-form-label">Note</label>
													<textarea name="note" id="note" class="gui-input form-control" rows="4" placeholder="Note" autocomplete="off" required=""></textarea>
												</div>
											</div>
										</div>

										<div class="form-group">
                                            <label for="recorded_date" class="col-form-label">Upload File</label>
                                            <input required="" name="file_upload" type="file" id="file_upload" class="form-control" accept="application/pdf">
                                        </div>
                                        <input type="hidden" name="upload_file_id" id="upload_file_id" value="">
                                        <input type="hidden" name="document_name" id="document_name" value="">
									</div>

									<div class="form-footer" style="padding: 0px 1rem !important;">
										<button type="submit" data-btntext-sending="Sending..." class="btn btn-success btn-icon-split btn-sm">
											<span class="icon text-white-50">
												<i class="fas fa-check"></i>
											</span>
											<span class="text">Submit</span>
										</button>

										<button type="reset" data-dismiss="modal" aria-label="Close" class="btn btn-danger btn-icon-split btn-sm">
											<span class="icon text-white-50">
												<i class="fas fa-ban"></i>
											</span>
											<span class="text">Cancel</span>
										</button>

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

