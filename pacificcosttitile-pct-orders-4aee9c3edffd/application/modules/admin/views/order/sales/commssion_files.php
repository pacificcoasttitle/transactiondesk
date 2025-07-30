<style>
	.dataTables_length {
		width: 250px !important;
		float: left;
	}

	#fileUploadModal .form-control {
		height: auto;
	}

    /* .bootstrap-select:not([class*="col-"]):not([class*="form-control"]):not(.input-group-btn) {
        width: 100% !important;
    } */
	.bootstrap-select:not([class*="col-"]):not([class*="form-control"]):not(.input-group-btn) {
		width: -webkit-fill-available;
	}
</style>
<div class="container-fluid">
	<div class="card mb-3">
		<div class="card-header">
			<i class="fas fa-table"></i>
			Commission Files
			<div class="row">
				<div class="col-sm-9">
					<div class="form-group row">
						<div class="col-sm-6">
							<select class="selectpicker filter-commission-files" data-style="btn-secondary" id="commission-file-year-filter" data-live-search="true" data-url="<?php echo base_url('order/admin/commission-files');?>">
								<!-- <option value="0">Select Year</option> -->
								<?php 
									$current_year = date('Y');
									for ($year_i=($current_year-5); $year_i <= $current_year; $year_i++) : 
									?>
										<option <?php echo  ($filter_year == $year_i) ? 'selected' :""; ?> value="<?php echo $year_i ;?>"><?php echo $year_i ;?></option>
									<?php 
									endfor;
								?>
							</select>
						</div>
						<div class="col-sm-6">
							<select  class="selectpicker filter-commission-files" data-style="btn-secondary" id="commission-file-month-filter" data-live-search="true" data-url="<?php echo base_url('order/admin/commission-files');?>">
								<!-- <option value="0">Select Month</option> -->
								<?php 
									for ($month_i=1; $month_i <= 12; $month_i++) : 
										$dt = DateTime::createFromFormat('!m', $month_i);
									?>
										<option <?php echo  ($filter_month == $month_i) ? 'selected' :""; ?>  value="<?php echo $month_i ;?>"><?php echo $dt->format('F') ;?></option>
									<?php 
									endfor;
								?>
							</select>
						</div>
					</div>
				</div>
				<div class="col-sm-3">
					<div class="float-right">
						<a href="javascript:void(0);" class="btn btn-secondary" data-toggle="modal"
							data-target="#fileUploadModal"> Upload </a>
					</div>
				</div>
			</div>
			
		</div>

		<div class="card-body">
			<?php
                if($this->session->flashdata('error')) :
                ?>
			<div class="alert alert-danger" role="alert"><?php echo $this->session->flashdata('error');?></div>
			<?php
                elseif($this->session->flashdata('success')):
                ?>
			<div class="alert alert-success" role="alert"><?php echo $this->session->flashdata('success');?></div>
			<?php
                endif;
            ?>
			<div id="forms_success_msg" class="w-100 alert alert-success alert-dismissible"
				style="display:none;"></div>
			<div id="forms_error_msg" class="w-100 alert alert-danger alert-dismissible"
				style="display:none;"></div>
			<div class="table-responsive">
				<table class="table table-bordered cusom__common__datatable" id="tbl--commisssion-file-listing" width="100%" cellspacing="0">
					<thead>
						<tr>
							<th>Sr No</th>
							<th>Name</th>
							<th>Sales Rep</th>
							<th>Timestamp</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
					<?php foreach($commission_files as $key=>$commission_file): ?>
						<tr>
							<td><?php echo ($key+1); ?></td>
							<td><?php echo $commission_file->pdf_name ?></td>
							<td><?php echo $commission_file->sales_rep_obj->first_name.' '.$commission_file->sales_rep_obj->last_name; ?></td>
							<?php
							$dt = DateTime::createFromFormat('!m', $commission_file->commission_month);
							?>
							<td><?php echo $dt->format('F').' '.$commission_file->commission_year; ?></td>
							<?php
							$documentUrl = env('AWS_PATH')."file_document/".$commission_file->commisssion_pdf;
							// $action = "<div style='display:flex;'><a href='javascript::void();' onclick='downloadDocumentFromAws(".'"'.$documentUrl.'"'.", ".'"'.$documentName.'"'.");'><i class='fas fa-fw fa-download'></i></a>
							// 	<a style='margin-left:10px;' target='_blank' href='$documentUrl'><i class='fas fa-fw fa-eye'></i></a><a style='margin-left:10px;' href='javascript::void();' onclick='deleteForm($formId);'><i class='fas fa-fw fa-trash'></i></a></div>";
								?>
							<td><div style='display:flex;'> <a class='btn btn-action" href='javascript::void();' onclick='downloadDocumentFromAws("<?php echo $documentUrl;?>", "<?php echo $commission_file->pdf_name; ?>");'><i class='fas fa-fw fa-download'></i></a>
								<a class='btn btn-action" style='margin-left:10px;' target='_blank' href='<?php echo $documentUrl;?>'><i class='fas fa-fw fa-eye'></i></a>
								<button type="button"  class='btn btn-action delete-record-custom' data-url="<?php echo base_url('order/admin/delete-commission-file/'.$commission_file->id)?>" title ='Delete This File'><span class='fas fa-fw fa-trash' aria-hidden='true'></span></button>
								</div>
							</td>
						</tr>
						<?php endforeach;?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="fileUploadModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
	aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<form enctype="multipart/form-data" method="post">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Upload File</h5>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label for="file-upload" class="col-form-label">Commission File</label>
						<input required="" name="file" type="file" id="file-upload" class="form-control" accept="application/pdf">
					</div>
					<div class="form-group">
						<label for="file-name" class="col-form-label">Name / Title</label>
						<input name="name" required="" type="text" class="form-control" id="file-name" >
					</div>
					<div class="form-group">
						<div class="row">
							<div class="col-sm-12">
								<label for="title-officer" class="col-form-label">Select Sales Rep</label>
							</div>
								<div class="col-sm-12">
									<select required="" name="sales_rep" class="selectpicker" data-live-search="true" required>
										<option value="">Select Sales Rep</option>
										<?php foreach($sales_reps as $sales_rep) {?>
											<option value="<?php echo $sales_rep->id;?>"> <?php echo $sales_rep->first_name." ".$sales_rep->last_name;?></option>
										<?php }?>
									</select>
								</div>
							<!-- </div> -->
						</div>
					</div>
					<div class="form-group">
						<div class="row">
							<div class="col-sm-6">

								<label for="commission-month" class="col-form-label">Select Month</label>
								<div class="">
									<select required="" name="commission_month" class="selectpicker" data-live-search="true" required>
										<option value="">Select Month</option>
										<?php 
											for ($month_i=1; $month_i <= 12; $month_i++) : 
												$dt = DateTime::createFromFormat('!m', $month_i);
											?>
												<option  <?php echo  (date('m') == $month_i) ? 'selected' :""; ?> value="<?php echo $month_i ;?>"><?php echo $dt->format('F') ;?></option>
											<?php 
											endfor;
										?>
									</select>
								</div>
							</div>
							<div class="col-sm-6">
								<label for="commission-year" class="col-form-label">Select Year</label>
								<div class="">
									<select required="" name="commission_year" class="selectpicker" data-live-search="true" required>
										<option value="">Select Year</option>
										<?php 
											$current_year = date('Y');
											for ($year_i=($current_year-5); $year_i <= $current_year; $year_i++) : 
											?>
												<option <?php echo  (date('Y') == $year_i) ? 'selected' :""; ?> value="<?php echo $year_i ;?>"><?php echo $year_i ;?></option>
											<?php 
											endfor;
										?>
									</select>
								</div>
								
							</div>
						</div>
					</div>
					
					
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<button class="btn btn-primary">Upload</button>
				</div>
                
			</form>
		</div>
	</div>
</div>

<script>
	function downloadDocumentFromAws(url, documentType) 
    {
		$('#page-preloader').css('background-color', 'rgba(0,0,0,.5)');
		$('#page-preloader').css('display', 'block');
		var fileNameIndex = url.lastIndexOf("/") + 1;
		var filename = url.substr(fileNameIndex);
		$.ajax({
			url: base_url + "download-aws-document-admin",
			type: "post",
			data: {
				url: url
			},
			async: false,
			success: function (response) {
				if (response) {
					if (navigator.msSaveBlob) {
						var csvData = base64toBlob(response, 'application/octet-stream');
						var csvURL = navigator.msSaveBlob(csvData, filename);
						var element = document.createElement('a');
						element.setAttribute('href', csvURL);
						element.setAttribute('download', documentType + "_" + filename);
						element.style.display = 'none';
						document.body.appendChild(element);
						document.body.removeChild(element);
					} else {
						console.log(response);
						var csvURL = 'data:application/octet-stream;base64,' + response;
						var element = document.createElement('a');
						element.setAttribute('href', csvURL);
						element.setAttribute('download', documentType + "_" + filename);
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
