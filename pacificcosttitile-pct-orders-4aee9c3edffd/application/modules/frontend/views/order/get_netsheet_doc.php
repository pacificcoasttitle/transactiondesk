<style>
	
	.ui-autocomplete { position: absolute; cursor: default;z-index:10000 !important;}  
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
</style>

<body>
	<?php
        $this->load->view('layout/header_dashboard');
    ?>

	<section class="section-type-4a section-defaulta" style="padding-bottom:100px;">
		<div class="container">
			<div class="row">
				<div class="row">
					<div class="col-xs-12">
						<div class="typography-section__inner" style="padding: 0px 17px;">
							<h2 class="ui-title-block ui-title-block_light">Netsheet</h2>
							<div class="ui-decor-1a bg-accent"></div>
							<h3 class="ui-title-block_light">Get your Netsheet</h3>
						</div>
						
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
											<?php if(!empty($file_number)) {?>
												<tr role="row" class="odd">
													<td>1</td>
													<td><?php echo $file_number;?></td>
													<td><?php echo $full_address;?></td>
													<td><?php echo $created;?></td>
													<td>
                                                        <div style="display:flex;">
                                                            <a href="#" onclick="downloadDocumentFromAws('<?php echo $documentUrl;?>', 'netsheet');">
                                                                <button class='btn btn-grad-2a' style='background: #d35411;' type='button'>Download</button>
                                                            </a>
                                                        </div>
                                                    </td> 
												</tr>
											<?php } else { ?>
												<tr role="row" class="odd"><td colspan="4" class="text-center">No record found</td></tr>
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

	
	<?php
        $this->load->view('layout/footer');
    ?>
</body>

</html>

<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/frontend/css/smart-forms.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/frontend/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/frontend/css/jquery-ui.css">
<script type="text/javascript" src="<?php echo base_url(); ?>assets/frontend/js/jquery.form.min.js"></script>

<script>

	function downloadDocumentFromAws(url, documentType)
    {
        $('#page-preloader').css('background-color', 'rgba(0,0,0,.5)');
		$('#page-preloader').css('display', 'block');
        var fileNameIndex = url.lastIndexOf("/") + 1;
        var filename = url.substr(fileNameIndex);
        $.ajax({
			url: base_url + "download-aws-document",
			type: "post",
			data: {
				url : url
			},
            async: false,
			success: function (response) {
				if (response) {
					if (navigator.msSaveBlob) {
						var csvData = base64toBlob(response, 'application/octet-stream');
						var csvURL = navigator.msSaveBlob(csvData, filename);
						var element = document.createElement('a');
						element.setAttribute('href', csvURL);
						element.setAttribute('download', documentType+"_"+filename);
						element.style.display = 'none';
						document.body.appendChild(element);
						document.body.removeChild(element);
					} else {
						console.log(response);
						var csvURL = 'data:application/octet-stream;base64,' + response;
						var element = document.createElement('a');
						element.setAttribute('href', csvURL);
						element.setAttribute('download', documentType+"_"+filename);
						element.style.display = 'none';
						document.body.appendChild(element);
						element.click();
						document.body.removeChild(element);
					}
				}
                $('#page-preloader').css('display', 'none');
			}
        });
    }

</script>
