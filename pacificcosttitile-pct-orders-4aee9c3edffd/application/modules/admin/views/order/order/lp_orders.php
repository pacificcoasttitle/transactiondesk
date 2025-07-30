<?php
$salesRep = isset($salesRep['data']) && !empty($salesRep['data']) ? $salesRep['data'] : array();
$product_type = isset($product_type) && !empty($product_type) ? $product_type : '';
$sales_rep = json_encode($salesRep);
$master_users = json_encode($master_users);

$userdata = $this->session->userdata('admin');
$roleList = $this->common->getRoleList();
$role_id = isset($userdata['role_id']) ? $userdata['role_id'] : 0;
$roleName = $roleList[$role_id];
?>
<script type="text/javascript">
    var lp_sales_rep = '<?php echo $sales_rep; ?>';
    var lp_master_users = '<?php echo $master_users; ?>';
    var lp_product_type = '<?php echo $product_type; ?>';

</script>
<style>
    .dataTables_length {
        width: 250px !important;
        float: left;
    }

    input[type=checkbox] {
        height: 20px !important;
        width: 20px !important;
    }

    .FilterOrderListing {
        display: flex;
        width: 100%
    }

    @media (min-width: 992px) {
        .modal-lg {
            max-width: 1400px !important;
        }
    }

    .ui-autocomplete {
        max-height: 300px !important;
    }

    #note {
        height: auto !important;
    }
</style>
<div class="container-fluid">
    <!-- DataTables Example -->
    <div class="row mb-3">
        <div class="col-sm-6">
            <h1 class="h3 text-gray-800">Lp Orders</h1>
        </div>
        <div class="col-sm-6">
            <?php if (!in_array($roleName, ['CS Admin'])): ?>
                <a href="javascript:void(0);" data-export-type="csv" onclick="exportLPOrders();" id="export-orders-data"
                    class="btn btn-success btn-icon-split float-right mr-2">
                    <span class="icon text-white-50">
                        <i class="fas fa-file-export"></i>
                    </span>
                    <span class="text"> Export </span>
                </a>
            <?php endif;?>
        </div>
    </div>
    <div class="card shadow mb-4">
        <div class="card-header datatable-header py-3">
            <div class="datatable-header-titles">
                <span>
                    <i class="fas fa-table"></i>
                </span>
                <h6 class="m-0 font-weight-bold text-primary pl-10">LP Orders Listing</h6>
            </div>
        </div>


        <div class="card-body">
            <?php if (!empty($success)) {?>
                <div id="" class="w-100 alert alert-success alert-dismissible">
                    <?php echo $success; ?>
                </div>
            <?php }
if (!empty($errors)) {?>
                <div id="" class="w-100 alert alert-danger alert-dismissible">
                    <?php echo $errors; ?>
                </div>
            <?php }?>
            <div id="lp_order_success_msg" class="w-100 alert alert-success alert-dismissible" style="display:none;">
            </div>
            <div id="lp_order_error_msg" class="w-100 alert alert-danger alert-dismissible" style="display:none;"></div>
            <div class="table-responsive">
                <table class="table table-bordered" id="tbl-lp-orders-listing" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="5%">Sr No</th>
                            <th width="10%">Order#</th>
                            <th width="10%">Property Address</th>
                            <th width="8%">Product Type</th>
                            <th width="7%">Sales Rep</th>
                            <th width="7%">Created By</th>
                            <th width="7%">Email Status</th>
                            <!-- <th width="7%">Lp Doc Name</th> -->
                            <th width="10%">Report Status</th>
                            <th width="10%">Avoid Duplication</th>
                            <th width="5%">Sync To Resware</th>
                            <th width="8%">Created At</th>
                            <th width="16%">Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div><!-- /.container-fluid -->

<div class="modal fade" width="1200px" id="instrument_model" tabindex="-1" role="dialog"
    aria-labelledby="Lender Infromation" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document" style="width:90%;">
        <div class="modal-content">
            <form method="POST" action="<?php echo base_url(); ?>order/admin/store-lp-document-info">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Select Documents</h6>
                            </div>

                            <div class="card-body">
                                <div class="smart-forms smart-container">
                                    <div class="modal-body search-result">
                                        <div id="deliverables-details-fields">
                                            <div class="frm-row" id="clone_container">
                                                <div class="section colm colm12" id="clone-email-address"
                                                    style="margin-bottom: 0px !important;">
                                                    <div class="toclone">
                                                        <div class="spacer-b10">
                                                            <label class="field" id="instrument_number_container">

                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-footer" style="padding: 0px 1rem !important;">
                                        <button type="submit" data-btntext-sending="Sending..."
                                            class="btn btn-success btn-icon-split btn-sm">
                                            <span class="icon text-white-50">
                                                <i class="fas fa-check"></i>
                                            </span>
                                            <span class="text">Submit</span>
                                        </button>
                                        <button type="reset" data-dismiss="modal" aria-label="Close"
                                            class="btn btn-danger btn-icon-split btn-sm">
                                            <span class="icon text-white-50">
                                                <i class="fas fa-ban"></i>
                                            </span>
                                            <span class="text">Cancel</span>
                                        </button>
                                        <!-- <button type="submit" data-btntext-sending="Sending..." class="button btn-primary">Submit</button>
                                        <button type="reset" data-dismiss="modal" aria-label="Close" class="button">Cancel</button> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- <div class="smart-forms smart-container" style="margin:30px">
                    <div class="modal-body search-result">
                        <div id="deliverables-details-fields">
                            <div class="spacer-b20">
                                <div class="tagline"><span>Select Documents</span></div>
                            </div>
                            <div class="frm-row" id="clone_container">
                                <div class="section colm colm12" id="clone-email-address" style="margin-bottom: 0px !important;">
                                    <div class="toclone">
                                        <div class="spacer-b10">
                                            <label class="field" id="instrument_number_container">

                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-footer" style="padding: 0px 1rem !important;">
                        <button type="submit" data-btntext-sending="Sending..."
                            class="button btn-primary">Submit</button>
                        <button type="reset" data-dismiss="modal" aria-label="Close" class="button">Cancel</button>
                    </div>
                </div> -->
            </form>
        </div>
    </div>
</div><!-- /.container-fluid -->


<div class="modal fade" width="1200px" id="vesting_model" tabindex="-1" role="dialog"
    aria-labelledby="Vesting Infromation" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document" style="width:40%;">
        <div class="modal-content">
            <form method="POST" action="<?php echo base_url(); ?>order/admin/store-vesting-info">
                <div class="smart-forms smart-container" style="margin:30px">
                    <div class="modal-body search-result">
                        <div id="deliverables-details-fields">
                            <div class="spacer-b20">
                                <div class="tagline"><span>Add Vesting Info</span></div>
                            </div>
                            <div class="frm-row" id="clone_container">
                                <div class="section colm colm12" id="clone-email-address"
                                    style="margin-bottom: 0px !important;">
                                    <div class="toclone">
                                        <div class="spacer-b10">
                                            <label class="field" id="vesting_container">
                                                <textarea id="vesting_info" name="vesting_info" class="smart-forms"
                                                    rows="8" cols="60" required=""></textarea>
                                            </label>
                                            <input type="hidden" name="file_id" id="file_id" value="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-footer" style="padding: 0px 1rem !important;">
                        <button type="submit" data-btntext-sending="Sending..."
                            class="button btn-primary">Submit</button>
                        <button type="reset" data-dismiss="modal" aria-label="Close" class="button">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
</div>

<div class="modal fade" id="fileUploadModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document" style="width: 50%;">
        <div class="modal-content">
            <form method="post" id="instrument-file-upload-form" name="instrument-file-upload-form"
                enctype="multipart/form-data" action="<?php echo base_url(); ?>order/admin/add-instrument-info">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card shadow">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Add Document</h6>
                            </div>

                            <div class="card-body">
                                <div class="smart-forms smart-container">
                                    <div class="modal-body search-result">

                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <label for="document_type" class="col-form-label">Document
                                                        Type</label>
                                                    <input name="document_type" required="" type="text"
                                                        class="form-control" id="document_type">
                                                </div>
                                                <div class="col-sm-6">
                                                    <label for="document_sub_type" class="col-form-label">Document Sub Type</label>
                                                    <input name="document_sub_type" type="text" class="form-control"
                                                        id="document_sub_type">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <label for="instrument_number" class="col-form-label">Instrument
                                                        Number</label>
                                                    <input required="" name="instrument_number" type="text"
                                                        id="instrument_number" class="form-control">
                                                </div>
                                                <!-- <div class="col-sm-6">
                                                    <label for="lender" class="col-form-label">Lender</label>
                                                    <input name="lender" type="text" class="form-control" id="lender">
                                                </div> -->
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <label for="recorded_date" class="col-form-label">Recorded
                                                        Date</label>
                                                    <input required="" name="recorded_date" type="text"
                                                        id="recorded_date" class="form-control">
                                                </div>
                                                <div class="col-sm-6">
                                                    <label for="amount" class="col-form-label">Amount</label>
                                                    <input name="amount" type="text" class="form-control" id="amount">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="recorded_date" class="col-form-label">Parties</label>
                                            <input required="" name="parties" type="text" id="parties"
                                                class="form-control">
                                        </div>

                                        <div class="form-group">
                                            <label for="recorded_date" class="col-form-label">Upload File</label>
                                            <input required="" name="file_upload" type="file" id="file_upload"
                                                class="form-control" accept="application/pdf">
                                        </div>
                                        <input type="hidden" name="upload_file_id" id="upload_file_id" value="">
                                        <input type="hidden" name="document_name" id="document_name" value="">

                                    </div>
                                    <div class="form-footer" style="padding: 0px 1rem !important;">
                                        <button type="submit" data-btntext-sending="Sending..."
                                            class="btn btn-success btn-icon-split btn-sm">
                                            <span class="icon text-white-50">
                                                <i class="fas fa-check"></i>
                                            </span>
                                            <span class="text">Submit</span>
                                        </button>
                                        <button type="reset" data-dismiss="modal" aria-label="Close"
                                            class="btn btn-danger btn-icon-split btn-sm">
                                            <span class="icon text-white-50">
                                                <i class="fas fa-ban"></i>
                                            </span>
                                            <span class="text">Cancel</span>
                                        </button>
                                        <!-- <button type="submit" data-btntext-sending="Sending..." class="button btn-primary">Submit</button>
                                        <button type="reset" data-dismiss="modal" aria-label="Close" class="button">Cancel</button> -->
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

<div class="modal fade" id="changeClientModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document" style="width: 50%;">
        <div class="modal-content">
            <form method="post" id="instrument-file-upload-form" name="instrument-file-upload-form"
                enctype="multipart/form-data" action="<?php echo base_url(); ?>order/admin/change-client">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card shadow">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Change Client</h6>
                            </div>

                            <div class="card-body">
                                <div class="smart-forms smart-container">
                                    <div class="modal-body search-result">

                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <label for="company_name" class="col-form-label">Company Name</label>
                                                    <input name="company_name" required="" type="text"
                                                        class="form-control" id="company_name">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <label for="email_address" class="col-form-label">Email Addres</label>
                                                    <input name="email_address" required="" type="text"
                                                        class="form-control" id="email_address">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <label for="first_name" class="col-form-label">First Name</label>
                                                    <input name="first_name" required="" type="text"
                                                        class="form-control" id="first_name">
                                                </div>
                                                <div class="col-sm-6">
                                                    <label for="last_name" class="col-form-label">Last Name</label>
                                                    <input name="last_name" required="" type="text" class="form-control"
                                                        id="last_name">
                                                </div>
                                            </div>
                                        </div>


                                        <input type="hidden" name="client_id" id="client_id" value="">
                                        <input type="hidden" name="client_file_id" id="client_file_id" value="">

                                    </div>
                                    <div class="form-footer" style="padding: 0px 1rem !important;">
                                        <button type="submit" data-btntext-sending="Sending..."
                                            class="btn btn-success btn-icon-split btn-sm">
                                            <span class="icon text-white-50">
                                                <i class="fas fa-check"></i>
                                            </span>
                                            <span class="text">Submit</span>
                                        </button>
                                        <button type="reset" data-dismiss="modal" aria-label="Close"
                                            class="btn btn-danger btn-icon-split btn-sm">
                                            <span class="icon text-white-50">
                                                <i class="fas fa-ban"></i>
                                            </span>
                                            <span class="text">Cancel</span>
                                        </button>
                                        <!-- <button type="submit" data-btntext-sending="Sending..." class="button btn-primary">Submit</button>
                                        <button type="reset" data-dismiss="modal" aria-label="Close" class="button">Cancel</button> -->
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

<div class="modal fade" width="500px" id="ion_fraud_note" tabindex="-1" role="dialog"
	aria-labelledby="Create a Note" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document" style="width:40%;">
		<div class="modal-content">
			<form method="POST" action="<?php echo base_url(); ?>order/admin/add-ion-fraud-notes" enctype="multipart/form-data" id="ion_fraud_note_form">
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
													<input type="hidden" name="file_id" id="file_id" class="form-control gui-input ui-autocomplete-input" required="">
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

										<!-- <div class="form-group">
                                            <label for="recorded_date" class="col-form-label">Upload File</label>
                                            <input required="" name="file_upload" type="file" id="file_upload" class="form-control" accept="application/pdf">
                                        </div>
                                        <input type="hidden" name="upload_file_id" id="upload_file_id" value="">
                                        <input type="hidden" name="document_name" id="document_name" value=""> -->
									</div>

									<div class="form-footer" style="padding: 0px 1rem !important;">
										<button type="submit" id="ionFraudSubmit" data-btntext-sending="Sending..." class="btn btn-success btn-icon-split btn-sm">
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
