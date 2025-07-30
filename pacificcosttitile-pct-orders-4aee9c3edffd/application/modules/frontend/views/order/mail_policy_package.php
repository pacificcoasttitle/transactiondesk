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

    table tbody tr {
        text-align: center;
    }
</style>
<body>
<?php if (!empty($policyDocuments)) {
    ?>
        <div class="container-fluid padding-0">
            <div class="row mb-3">
                <div class="col-sm-12">
                    <h1 class="h3 text-gray-800">Get Policy</h1>
                    <div class="ui-decor-1a bg-accent"></div>
                </div>
            </div>
            <div class="card-body shadow mb-4">
                <div class="card-header datatable-header" style="border: none;">
                    <div class="datatable-header-titles">
                        <h6 class="m-0 font-weight-bold text-primary pl-10">File Number:</h6>&nbsp;<?php echo $file_number; ?>
                    </div>

                </div>
                <div class="card-header datatable-header py-3">
                    <div class="datatable-header-titles">
                        <h6 class="m-0 font-weight-bold text-primary pl-10">Property Address:</h6> &nbsp;<?php echo $full_address; ?>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Document Name</th>
                                <th>Created</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($policyDocuments as $policyDocument) {
        $documentName = $policyDocument['document_name'];?>
                                <tr>
                                    <td><?php echo $policyDocument['no']; ?></td>
                                    <td><?php echo $documentName; ?></td>
                                    <td><?php echo $policyDocument['created_at']; ?></td>
                                    <td>
                                        <a href='javascript:void(0);' onclick='download_policy_doc(<?php echo $policyDocument["api_document_id"]; ?>, <?php echo $order_id; ?>, "<?php echo $documentName; ?>");'>
                                            <button type='submit' class='btn btn-success btn-icon-split'>
                                                <span class='icon text-white-50'>
                                                    <i class='fas fa-download'></i>
                                                </span>
                                                <span class='text'>Download</span>
                                            </button>
                                        </a>
                                    </td>
                                </tr>
                            <?php

    }?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
<?php

} else {?>
    <div class="container-fluid padding-0">
        <div class="row mb-3">
            <div class="col-sm-12">
                <h1 class="h3 text-gray-800">Get Policy</h1>
            </div>
        </div>

        <div class="card-body shadow mb-4">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
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
                        <tr>
                            <td>1</td>
                            <td><?php echo $file_number; ?></td>
                            <td><?php echo $full_address; ?></td>
                            <td><?php echo $created; ?></td>
                            <td>
                                <a href='javascript:void(0)'>
                                    <button type='submit' class='btn btn-info btn-icon-split'>
										<span class='icon text-white-50'>
											<i class='fas fa-file'></i>
										</span>
										<span class='text'>Not Ready</span>
									</button>
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php }?>
</body>

</html>

<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/frontend/css/smart-forms.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/frontend/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/frontend/css/jquery-ui.css">

<script type="text/javascript" src="<?php echo base_url(); ?>assets/libs/jquery-1.12.4.min.js"></script>
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
