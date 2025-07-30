<style>

.dropdown-btn {
	border: none;
    background: none;
    width: 100%;
    text-align: left;
    color: #04415D;
    background: #ffffff;
    width: 100%;
    border-bottom: 2px #D0D0D0 dotted;
    padding: 10px 15px 10px 0;
}

.dropdown-container {
	display: none;
}

.active {
  border :none;
}

.fa-caret-down {
  float: right;
  padding-right: 8px;
}

.review_li {
	float: left;
	width: 100%;
}

.linked_doc {
	float: left;
    padding: 5px 0px 10px 0;
	border: none !important;
	font-weight:bold;
}

.yamm2 li a {
	width:100%;
}

.yamm2 li a:hover {
	width:100%;
}

button:focus {outline:0;}

.l-main-content {
    padding-top: 20px;
}
</style>

<section class="section-type-4a section-defaulta" style="padding-bottom:0px;">
    <div class="container">
        <div class="row">
            <div class="row">
                <div class="col-xs-12">
                    <div class="typography-section__inner">
                        <h2 class="ui-title-block ui-title-block_light"><?php echo $trainingMaterials->name;?> Training</h2>
                        <div class="ui-decor-1a bg-accent"></div>
                    </div>

                    <div class="typography-sectionabcd">
                        <div class="col-md-12">
                            <div class="col-md-3">
                                <div class="typography-section__inner">
                                    <h3 class="ui-title-block_light">Docs</h3>
                                    <div class="ui-decor-1a bg-accent"></div>
                                </div>
                                <aside class="l-sidebarb l-sidebar_right">
                                    <section class="widget section-sidebara">
                                        <div class="widget-contenta">
                                            <div class="header-navibox-2">
                                                <ul class="yamm2 nav navbar-nav2">
                                                    <?php  
                                                        $fileFlag = 0;
                                                        $url = '';
                                                        $i = 0;
                                                        foreach($trainingMaterials->materials as $material): ?>
														<?php if($material->type == 'file'): ?>
                                                            <li class="review_li">
                                                                <a onclick="load_doc('<?php echo $material->path;?>', 'file');" href="javascript:void(0);">
                                                                    <?php echo $material->path;?>
                                                                </a>
                                                            </li>
                                                            <br>

                                                        <?php 
                                                            if ($i == 0) {
                                                                $url = env('AWS_PATH').'hr/training/'.$material->path;
                                                            }
                                                            $fileFlag = 1; 
                                                            $i++;
                                                        endif; ?>
													<?php endforeach; ?>
                                                    <?php if ($fileFlag == 0): ?>
                                                        <li class="review_li">
                                                            <a href="javascript:void(0);">
                                                                Document(s) not Found.
                                                            </a>
                                                        </li>
                                                    <?php endif; ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </section>

                                    <div class="typography-section__inner">
                                        <h3 class="ui-title-block_light">Doc Links</h3>
                                        <div class="ui-decor-1a bg-accent"></div>
                                    </div>
                                    
                                    <section class="widget section-sidebara">
                                        <div class="widget-contenta">
                                            <div class="header-navibox-2">
                                                <ul class="yamm2 nav navbar-nav2">
                                                    <?php $urlFlag = 0;
                                                        $j = 0;
                                                        foreach($trainingMaterials->materials as $material): ?>
														<?php if($material->type == 'url'): ?>
                                                            <li class="review_li">
                                                                <a onclick="load_doc('<?php echo $material->path;?>', 'url');" href="javascript:void(0);">
                                                                    <?php echo $material->path;?>
                                                                </a>
                                                            </li>
                                                            <br>
                                                        <?php 
                                                            if ($j == 0 && empty($url)) {
                                                                $url = $material->path;
                                                            }
                                                            $j++;
                                                            $urlFlag = 1;
                                                        endif; ?>
													<?php endforeach; ?>
                                                    <?php if ($urlFlag == 0): ?>
                                                        <li class="review_li">
                                                            <a href="javascript:void(0);">
                                                                Doc Links(s) not Found.
                                                            </a>
                                                        </li>
                                                    <?php endif; ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </section>
                                </aside>
                            </div>

                            <div class="col-md-1"></div>
                            <div class="col-md-8" id="links_details">
                                <div class="typography-section__inner smart-forms">
                                    <!-- <a onclick="" href="javascript:void(0);"><button class='button btn-primary' type='button'>Download</button></a> -->
                                    <?php if ($training_status[0]->is_complete == 1) {?>
                                        <button class='button btn-primary' type='button'>Training Completed</button>
                                    <?php } else { ?>
                                        <a href="javascript:void(0)"><button data-toggle="modal" data-target="#training_complete" class='button btn-primary' type='button'>Complete Training</button></a>
                                    <?php } ?>
                                </div>
                                <div class="l-main-content">
                                    <article class="b-post b-post-full clearfix">
                                        <div class="">
                                            <iframe name="doc_ifrm" id="doc_ifrm" src="<?php echo $url;?>" width="825px" height="800px">
                                                This browser does not support PDFs. Please download the PDF to view it: Download PDF
                                            </iframe>
                                        </div>
                                    </article>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" width="500px" id="training_complete" tabindex="-1" role="dialog" aria-labelledby="Training Confirmation" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document" style="width:50%;">
        <div class="modal-content">
            <form method="POST" action="<?php echo base_url();?>hr/complete-training/<?php echo $trainingMaterials->id;?>" id="complete_training_form" name="complete_training_form">
                <div class="smart-forms smart-container" style="margin:30px">
                    <div class="modal-body search-result" style="padding-bottom: 0px;">
                        <div id="memo-details-fields" >
                            <div class="frm-row">
                                <div class="section colm colm12" id="description" style="line-height: 2;">Are you sure want to complete this training?</div>
                            </div>
                        </div>
                    </div>
                    <div style="margin: 0px 20px;text-align:left;">
                        <button style="margin: 0px 10px 20px 0px;" type="submit" data-btntext-sending="Sending..."
                            class="button btn-primary">Yes</button>
                        <button type="reset" data-dismiss="modal" aria-label="Close" class="button">No</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>

	function load_doc(document_name, type)
	{
        if (type == 'file') {
            var path = '<?php echo env('AWS_PATH');?>'+'hr/training/'+document_name;
        } else {
            var path = document_name;
        }
    
		$('#page-preloader').css('background-color', 'rgba(0,0,0,.5)');
		$('#page-preloader').css('display', 'block');
        var $iframe = $('#doc_ifrm');
        if ($iframe.length) {
            $iframe.attr('src', path);
        }
        $('#page-preloader').css('display', 'none');
	}

	function download_document() 
    {
		$('#page-preloader').css('background-color', 'rgba(0,0,0,.5)');
		$('#page-preloader').css('display', 'block');
		$.ajax({
			url: base_url + "download-document",
			type: "post",
			data: {
				resware_document_id: resware_document_id,
                order_id: order_id,
				document_name: document_name,
				fileId: $('#fileId').val()
			},
			dataType: "html",
			success: function (response) {
				$('#page-preloader').css('display', 'none');
				if (response) {
					if (navigator.msSaveBlob) {
						var csvData = base64toBlob(response, 'application/octet-stream');
						var csvURL = navigator.msSaveBlob(csvData, 'FeeEstimation.pdf');
						var element = document.createElement('a');
						element.setAttribute('href', csvURL);
						element.setAttribute('download', document_name);
						element.style.display = 'none';
						document.body.appendChild(element);
						document.body.removeChild(element);
					} else {
						console.log(response);
						var csvURL = 'data:application/octet-stream;base64,' + response;
						var element = document.createElement('a');
						element.setAttribute('href', csvURL);
						element.setAttribute('download', document_name);
						element.style.display = 'none';
						document.body.appendChild(element);
						element.click();
						document.body.removeChild(element);
					}
				}
			}
		});
	}

	function upload_document(resware_document_id, order_id, document_name) 
    {
		$('#page-preloader').css('background-color', 'rgba(0,0,0,.5)');
		$('#page-preloader').css('display', 'block');
		$.ajax({
			url: base_url + "upload-document",
			type: "post",
			data: {
				resware_document_id: resware_document_id,
                order_id: order_id,
				document_name: document_name,
				fileId: $('#fileId').val()
			},
			dataType: "html",
			success: function (response) {
				$('#page-preloader').css('display', 'none');
				var results = JSON.parse(response);
				if (results.status == 'success') {
					alert(results.msg);
				} else if(results.status == 'error') {
					alert(results.msg);
				}
			}
		});
	}

	function base64toBlob(base64Data, contentType) 
    {
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
</html>
