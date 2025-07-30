<style>
.l-main-content {
    padding-top: 50px !important;
}
</style>
<div class="typography-section__inner">
	<a class="btn-success btn-icon-split btn-sm" onclick="download_document(<?php echo $api_document_id;?>, <?php echo $order_id;?>, '<?php echo $document_name;?>');" href="javascript:void(0);">
		<span class="icon text-white-50">
			<i class="fa fa-download"></i>
		</span>
		<span class="text">Download</span>
	</a>
</div>

<div class="l-main-content">
	<article class="b-post b-post-full clearfix">
		<div class="">
			<iframe src="<?php echo $url;?>" width="825px" height="800px">
				This browser does not support PDFs. Please download the PDF to view it: Download PDF
			</iframe>
		</div>
	</article>
</div>
