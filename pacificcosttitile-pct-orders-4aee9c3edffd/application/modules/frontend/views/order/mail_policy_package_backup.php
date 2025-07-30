<style>
	.smart-forms .prepend-icon .field-icon {
		top: 14px !important;
	}
	.ui-autocomplete { 
		position: absolute; cursor: default;z-index:10000 !important;
	}  
	.ui-autocomplete {
		max-height: 300px !important;
	} 
	.radio {
		top: 5px !important;
		margin: 0px 10px !important;
	}
	.radio:before {
		background: none !important;
	}
	th {
		text-align: center;
	}
</style>
<body>
	<?php
        $this->load->view('layout/header_dashboard');
		if(!empty($policyDocuments))  { ?>
			<section class="section-type-4a section-defaulta" style="padding-bottom:100px;">
				<div class="container">
					<div class="row">
						<div class="row">
							<div class="col-xs-12">
								<div class="typography-section__inner" style="padding: 0px 17px;">
									<h2 class="ui-title-block ui-title-block_light">Get Policy</h2>
									<div class="ui-decor-1a bg-accent"></div>
									<h3 class="ui-title-block_light">File Number <?php echo $file_number;?></h3>
									<h3 class="ui-title-block_light"><?php echo $full_address;?></h3>
								</div>

								<?php if(!empty($success)) {?>
									<div id="agent_success_msg" class="w-100 alert alert-success alert-dismissible">
										<?php foreach($success as $sucess) {
												echo $sucess."<br \>";	
											}?>
									</div>
								<?php } 
								if(!empty($errors)) {?>
									<div id="agent_error_msg" class="w-100 alert alert-danger alert-dismissible">
										<?php foreach($errors as $error) {
												echo $error."<br \>";	
											}?>
									</div>
								<?php } ?>
								<div class="typography-sectiona">
									<div class="col-md-12">
										<div class="table-container">
											<table class="table table-type-3 typography-last-elem no-footer" id="cpl_listing">
												<thead>
													<tr>
														<th>#</th>
														<th>Document Name</th>
														<th>Created</th>
														<th>Action</th>
													</tr>
												</thead>
												<tbody>
													<?php foreach($policyDocuments as $policyDocument) {
														$documentName = $policyDocument['document_name'];?>
														<tr role="row" class="odd">
															<td><?php echo $policyDocument['no'];?></td>
															<td><?php echo $documentName;?></td>
															<td><?php echo $policyDocument['created_at'];?></td>
															<td><a href='javascript:void(0);' onclick='download_policy_doc(<?php echo $policyDocument["api_document_id"];?>, <?php echo $order_id;?>, "<?php echo $documentName;?>");'><button class='btn btn-grad-2a' style='background: #d35411;' type='button'>Download</button></a></td> 
														</tr>
													<?php }  ?>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
	<?php } else { ?>
		<section class="section-type-4a section-defaulta" style="padding-bottom:100px;">
			<div class="container">
				<div class="row">
					<div class="row">
						<div class="col-xs-12">
							<div class="typography-section__inner" style="padding: 0px 17px;">
								<h2 class="ui-title-block ui-title-block_light">Get Policy</h2>
								<div class="ui-decor-1a bg-accent"></div>
							</div>
							<?php if(!empty($success)) {?>
							<div id="agent_success_msg" class="w-100 alert alert-success alert-dismissible">
								<?php foreach($success as $sucess) {
										echo $sucess."<br \>";	
									}?>
							</div>
							<?php } 
							if(!empty($errors)) {?>
							<div id="agent_error_msg" class="w-100 alert alert-danger alert-dismissible">
								<?php foreach($errors as $error) {
										echo $error."<br \>";	
									}?>
							</div>
							<?php } ?>
							<div class="typography-sectiona">
								<div class="col-md-12">
									<div class="table-container">
										<table class="table table-type-3 typography-last-elem no-footer" id="cpl_listing">
											<thead>
												<tr>
													<th>#</th>
													<th>File Number</th>
													<th>Property Address</th>
													<th>Created</th>
													<th>Action</th>
												</tr>
											</thead>
											<tbody>
												<tr role="row" class="odd">
													<td>1</td>
													<td><?php echo $file_number;?></td>
													<td><?php echo $full_address;?></td>
													<td><?php echo $created;?></td>
													<td><a href='javascript:void(0)'><button class='btn btn-grad-2a' style='background: #d35411;' type='button'>Not Ready</button></a></td> 
												</tr>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	<?php }
        $this->load->view('layout/footer');
    ?>
</body>

</html>

<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/frontend/css/smart-forms.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/frontend/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/frontend/css/jquery-ui.css">

<script type="text/javascript" src="<?php echo base_url(); ?>assets/frontend/js/jquery.form.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/frontend/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/frontend/js/additional-methods.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/frontend/js/smart-form.js"></script> 
<script type="text/javascript" src="<?php echo base_url(); ?>assets/frontend/js/jquery-ui.min.js"></script>


<script>
	function download_policy_doc(documentId, order_id, documentName) {
		$('#page-preloader').css('background-color', 'rgba(0,0,0,.5)');
		$('#page-preloader').css('display', 'block');
		$.ajax({
			url: base_url + "download-policy-doc",
			type: "post",
			data: {
				documentId: documentId,
				order_id: order_id 
			},
			success: function (response) {
				$('#page-preloader').css('display', 'none');
				console.log(response);
				if (response) {
					if (navigator.msSaveBlob) {
						var csvData = base64toBlob(response, 'application/octet-stream');
						var csvURL = navigator.msSaveBlob(csvData, 'policy.pdf');
						var element = document.createElement('a');
						element.setAttribute('href', csvURL);
						element.setAttribute('download', documentName);
						element.style.display = 'none';
						document.body.appendChild(element);
						document.body.removeChild(element);
					} else {
						var csvURL = 'data:application/octet-stream;base64,' + response;
						var element = document.createElement('a');
						element.setAttribute('href', csvURL);
						element.setAttribute('download', documentName);
						element.style.display = 'none';
						document.body.appendChild(element);
						element.click();
						document.body.removeChild(element);
					}
				}
			}
		});
	}

	function base64toBlob(base64Data, contentType) {
		contentType = contentType || '';
		var sliceSize = 1024;
		var byteCharacters = atob(base64Data);
		var bytesLength = byteCharacters.length;
		var slicesCount = Math.ceil(bytesLength / sliceSize);
		var byteArrays = new Array(slicesCount);

		for (var sliceIndex = 0; sliceIndex < slicesCount; ++sliceIndex) {
			var begin = sliceIndex * sliceSize;
			var end = Math.min(begin + sliceSize, bytesLength);

			var bytes = new Array(end - begin);
			for (var offset = begin, i = 0; offset < end; ++i, ++offset) {
				bytes[i] = byteCharacters[offset].charCodeAt(0);
			}
			byteArrays[sliceIndex] = new Uint8Array(bytes);
		}
		return new Blob(byteArrays, {
			type: contentType
		});
	}

	
</script>
