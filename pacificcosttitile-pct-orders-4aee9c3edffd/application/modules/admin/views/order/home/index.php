<style>
	#calendar {
		margin: 25px;
		font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
		font-size: 14px;
	}

	#loading {
		display: none;
		position: absolute;
		top: 10px;
		right: 10px;
	}

	.filter_label {
		padding:8px 10px 8px 25px;
	}

    .viewDetails {
        float: right;
        margin-top: 10px;
    }
    .cursorPoint {
        cursor: pointer;
    }
    .card-header-danger {
        background-color: #cb656508;
        border-bottom: 1px solid #cb656508;
    }
    .card-header-info {
        background-color: #e6f6f9;
        border-bottom: 1px solid #e6f6f9;
    }
    .card-header-primary {
        background-color: #dcedfe;
        border-bottom: 1px solid #dcedfe;
    }
</style>
<div class="container-fluid">
    <!-- Icon Cards-->
    <div class="row mb-4">
        <div class="col-md-12 col-sm-12">
            <div class="row">
                <div id="daily_prod_success_msg" class="w-100 alert alert-success alert-dismissible" style="display:none;"></div>
                <div id="daily_prod_error_msg" class="w-100 alert alert-danger alert-dismissible" style="display:none;"></div>

                <div class="col-sm-12 mb-4">
                    <button type="button" class="btn btn-success text-right float-right" onclick="sendDailyProductionReport();">Send Daily Production Email</button>
                    <a href="javascript:void(0);" data-toggle="modal" data-target="#generateSalesReportModel" class="btn btn-success btn-icon-split float-right mr-2">
                    <span class="icon text-white-50">
                        <i class="fas fa-file-export"></i>
                    </span>
                    <span class="text"> Sales Rep Transaction Report </span> </a>

                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-12">
                <div class="row">
                    <div class="col-sm-12 mb-4">
                        <h1 class="h5 mb-0 text-primary">Open Orders of <?=date('F Y')?></h1>
                        <!-- <div class="card-header card-header-primary text-primary">
                        Open Orders of <?=date('F Y')?>
                        </div> -->
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Sales Orders</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $openSalesCount; ?></div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-table fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                                <a class="clearfix small z-1 viewDetails" href="<?php echo base_url() . 'order/admin/orders' ?>">
                                    <span class="">View Details</span>
                                    <span class="">
                                        <i class="fas fa-angle-right"></i>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="col-md-6 col-sm-12">
                        <div class="card border-primary">

                            <div class="card-body text-primary">
                                <div class="mr-5"><?php echo $openSalesCount . ' Sales Orders'; ?></div>
                            </div>

                            <a class="card-footer clearfix small z-1 bg-primary text-white" href="<?php echo base_url() . 'order/admin/orders' ?>">
                                <span class="float-left">View Details</span>
                                <span class="float-right">
                                    <i class="fas fa-angle-right"></i>
                                </span>
                            </a>
                        </div>

                    </div> -->
                    <div class="col-md-6 col-sm-12">
                        <div class="card border-left-success shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Refi Orders</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $openLoanCount; ?></div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-table fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                                <a class="clearfix small z-1 viewDetails text-success" href="<?php echo base_url() . 'order/admin/orders' ?>">
                                    <span class="">View Details</span>
                                    <span class="">
                                        <i class="fas fa-angle-right"></i>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="col-md-6 col-sm-12">
                        <div class="card border-primary">

                            <div class="card-body text-primary">
                                <div class="mr-5"><?php echo $openLoanCount . ' Refi Orders'; ?></div>
                            </div>
                            <a class="card-footer clearfix small z-1 bg-primary text-white" href="<?php echo base_url() . 'order/admin/orders' ?>">
                                <span class="float-left">View Details</span>
                                <span class="float-right">
                                    <i class="fas fa-angle-right"></i>
                                </span>
                            </a>
                        </div>
                    </div> -->
                </div>
        </div>
        <div class="col-md-6 col-sm-12">
                <div class="row">
                    <div class="col-sm-12 mb-4">
                        <h1 class="h5 mb-0 text-info">Closed Orders of <?=date('F Y')?></h1>
                        <!-- <div class="card-header card-header-primary text-primary">Closed Orders of <?=date('F Y')?></div> -->
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="card border-left-info shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Sales Orders</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $closedSalesCount; ?></div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-table fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                                <a class="clearfix small z-1 viewDetails text-info" href="<?php echo base_url() . 'order/admin/orders' ?>">
                                    <span class="">View Details</span>
                                    <span class="">
                                        <i class="fas fa-angle-right"></i>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="col-md-6 col-sm-12">
                        <div class="card border-primary">

                            <div class="card-body text-primary">
                                <div class="mr-5"><?php echo $closedSalesCount . ' Sales Orders'; ?></div>
                            </div>

                            <a class="card-footer clearfix small z-1 bg-primary text-white" href="<?php echo base_url() . 'order/admin/orders' ?>">
                                <span class="float-left">View Details</span>
                                <span class="float-right">
                                    <i class="fas fa-angle-right"></i>
                                </span>
                            </a>
                        </div>

                    </div> -->
                    <div class="col-md-6 col-sm-12">
                        <div class="card border-left-warning  shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-warning  text-uppercase mb-1">Refi Orders</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $closedLoanCount; ?></div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-table fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                                <a class="clearfix small z-1 viewDetails text-warning" href="<?php echo base_url() . 'order/admin/orders' ?>">
                                    <span class="">View Details</span>
                                    <span class="">
                                        <i class="fas fa-angle-right"></i>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="col-md-6 col-sm-12">
                        <div class="card border-primary">

                            <div class="card-body text-primary">
                                <div class="mr-5"><?php echo $closedLoanCount . ' Refi Orders'; ?></div>
                            </div>
                            <a class="card-footer clearfix small z-1 bg-primary text-white" href="<?php echo base_url() . 'order/admin/orders' ?>">
                                <span class="float-left">View Details</span>
                                <span class="float-right">
                                    <i class="fas fa-angle-right"></i>
                                </span>
                            </a>
                        </div>
                    </div> -->
                </div>
        </div>
    </div>


    <div class="row mb-4">
        <div class="col-sm-12">
                <div class="row">
                    <div class="col-sm-12 mb-3">
                        <h1 class="h5 mb-0 text-primary"># Of Clients</h1>
                        <!-- <div class="card-header card-header-info text-info"> # of Clients </div> -->
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 col-sm-12">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Escrows</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $escrowUsersCount; ?></div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-users fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                                <a class="clearfix small z-1 viewDetails text-primary" href="<?php echo base_url() . 'order/admin/escrow' ?>">
                                    <span class="">View Details</span>
                                    <span class="">
                                        <i class="fas fa-angle-right"></i>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="col-md-4 col-sm-12">
                        <div class="card border-info">

                            <div class="card-body text-info">
                                <div class="mr-5"><?php echo $escrowUsersCount . ' Escrows'; ?></div>
                            </div>

                            <a class="card-footer clearfix small z-1 bg-info text-white" href="<?php echo base_url('order/admin/escrow'); ?>">
                                <span class="float-left">View Details</span>
                                <span class="float-right">
                                    <i class="fas fa-angle-right"></i>
                                </span>
                            </a>
                        </div>

                    </div> -->
                    <div class="col-md-4 col-sm-12">
                        <div class="card border-left-success shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Lenders</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $lenderUsersCount; ?></div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-users fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                                <a class="clearfix small z-1 viewDetails text-success" href="<?php echo base_url() . 'order/admin/lenders' ?>">
                                    <span class="">View Details</span>
                                    <span class="">
                                        <i class="fas fa-angle-right"></i>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="col-md-4 col-sm-12">
                        <div class="card border-info">

                            <div class="card-body text-info">
                                <div class="mr-5"><?php echo $lenderUsersCount . ' Lenders'; ?></div>
                            </div>

                            <a class="card-footer clearfix small z-1 bg-info text-white" href="<?php echo base_url('order/admin/lenders'); ?>">
                                <span class="float-left">View Details</span>
                                <span class="float-right">
                                    <i class="fas fa-angle-right"></i>
                                </span>
                            </a>
                        </div>
                    </div> -->
                    <div class="col-md-4 col-sm-12">
                        <div class="card border-left-info shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-cente">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Sales Rep</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $salesRepUsersCount; ?></div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-users fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                                <a class="clearfix small z-1 viewDetails text-info" href="<?php echo base_url() . 'order/admin/sales-rep' ?>">
                                    <span class="">View Details</span>
                                    <span class="">
                                        <i class="fas fa-angle-right"></i>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="col-md-4 col-sm-12">
                        <div class="card border-info">

                            <div class="card-body text-info">
                                <div class="mr-5"><?php echo $salesRepUsersCount . ' Sales Rep'; ?></div>
                            </div>

                            <a class="card-footer clearfix small z-1 bg-info text-white" href="<?php echo base_url('order/admin/sales-rep'); ?>">
                                <span class="float-left">View Details</span>
                                <span class="float-right">
                                    <i class="fas fa-angle-right"></i>
                                </span>
                            </a>
                        </div>
                    </div> -->

                </div>
        </div>
    </div>
    <div class="row mb-4">
            <div class="col-md-6 col-sm-12">
                <div class="card border-left-primary shadow h-100 py-2">
                    <h1 class="card-header h5 mb-0 text-primary"># Of expired passwords</h1>
                    <!-- <div class="card-header card-header-primary text-primary "># of expired passwords </div> -->
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Passwords expired</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $expiredPasswordCount; ?></div>
                                <!-- <button type="button" class="btn btn-danger text-right float-right" onclick="refreshExipredPasswords();"><i class="fas fa-refresh"></i></button> -->
                            </div>
                            <div class="col-auto cursorPoint" onclick="refreshExipredPasswords();">
                                <i class="fas fa-refresh fa-2x text-gray-300"></i>
                            </div>
                        </div>
                        <a class="clearfix small z-1 viewDetails text-primary" href="<?php echo base_url() . 'order/admin/incorrect-users' ?>">
                            <span class="">View Details</span>
                            <span class="">
                                <i class="fas fa-angle-right"></i>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
            <!-- <div class="col-md-6 col-sm-12">
                <div class="card">

                    <div class="card-header bg-danger text-white">
                        # of expired passwords
                    </div>

                    <div class="card-body text-danger">
                        <div id="refresh_password_success_msg" class="w-100 alert alert-success alert-dismissible" style="display:none;"></div>
                        <div id="refresh_password_error_msg" class="w-100 alert alert-danger alert-dismissible" style="display:none;"></div>
                        <div class="mr-5"><?php echo $expiredPasswordCount . ' Passwords expired'; ?>
                            <button type="button" class="btn btn-danger text-right float-right" onclick="refreshExipredPasswords();"><i class="fas fa-refresh"></i></button>
                        </div>
                    </div>

                    <a class="card-footer clearfix small z-1 bg-danger text-white" href="<?php echo base_url('order/admin/incorrect-users'); ?>">
                        <span class="float-left">View Details</span>
                        <span class="float-right">
                            <i class="fas fa-angle-right"></i>
                        </span>
                    </a>
                </div>

            </div> -->
            <div class="col-md-6 col-sm-12">
                <div class="card border-left-info shadow h-100 py-2">
                    <h1 class="card-header h5 mb-0 text-info">Not received JSON</h1>
                    <!-- <div class="card-header card-header-info text-info ">Not received JSON</div> -->
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Files</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $failedJsonCount; ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- <div class="col-md-6 col-sm-12">
                <div class="card">

                    <div class="card-header bg-danger text-white">
                        Not received JSON
                    </div>

                    <div class="card-body text-danger">
                        <div class="mr-5"><?php echo $failedJsonCount . ' files'; ?></div>
                    </div>

                    <div class="card-footer clearfix small z-1 bg-danger text-white">
                        <span class="float-left">&nbsp;</span>
                        <span class="float-right">
                        </span>
                    </div>
                </div>

            </div> -->
    </div>

    <div class="row">
		<div class="col-xl-12">

			<div class="card shadow mb-4">
				<div class="card-header py-3">
					<h6 class="m-0 font-weight-bold text-primary">Vacation Requests Calendar</h6>
				</div>
				<div id='loading'>loading...</div>
				<div id='calendar'></div>
			</div>
		</div>
	</div>
</div>
<!-- /.container-fluid -->

<!-- /.content-wrapper -->

<!-- Modal -->
<div class="modal fade" id="generateSalesReportModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
	aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<form  method="post" id="sales-rep-csv-report">
			<div class="row">
                    <div class="col-lg-12">
                        <div class="card shadow">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary" >Generate Sales Rep CSV Report</h6>
                            </div>
                            <div id="sales_report_msg" class="w-100 alert alert-success alert-dismissible" style="display:none;"></div>
                            <div id="sales_report_err_msg" class="w-100 alert alert-danger alert-dismissible" style="display:none;"></div>

                            <div class="card-body">
                                <div class="smart-forms smart-container">
                                    <div class="modal-body search-result">



                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <label for="select_month" class="col-form-label">Select Month</label>
                                                    <select id="select_month" required="" name="select_month" required>
														<option value="1" <?php echo (date('m') == 1) ? "selected" : "" ?> >January</option>
														<option value="2" <?php echo (date('m') == 2) ? "selected" : "" ?> >Febuary</option>
														<option value="3" <?php echo (date('m') == 3) ? "selected" : "" ?> >March</option>
														<option value="4" <?php echo (date('m') == 4) ? "selected" : "" ?> >April</option>
														<option value="5" <?php echo (date('m') == 5) ? "selected" : "" ?> >May</option>
														<option value="6" <?php echo (date('m') == 6) ? "selected" : "" ?> >June</option>
														<option value="7" <?php echo (date('m') == 7) ? "selected" : "" ?> >July</option>
														<option value="8" <?php echo (date('m') == 8) ? "selected" : "" ?> >August</option>
														<option value="9" <?php echo (date('m') == 9) ? "selected" : "" ?> >September</option>
														<option value="10" <?php echo (date('m') == 10) ? "selected" : "" ?> >October</option>
														<option value="11" <?php echo (date('m') == 11) ? "selected" : "" ?> >November</option>
														<option value="12" <?php echo (date('m') == 12) ? "selected" : "" ?> >December</option>
													</select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <label for="select_year" class="col-form-label">Select Year</label>
                                                    <select id="select_year" required="" name="select_year" required>
														<?php
$currently_selected = date('Y');
$earliest_year = 2010;
$latest_year = date('Y');
foreach (range($latest_year, $earliest_year) as $i) {?>
															<option value="<?php echo $i; ?>" <?php echo ($i == $currently_selected) ? "selected" : "" ?> > <?php echo $i; ?></option>
														<?php }?>
													</select>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="form-footer" style="padding: 0px 1rem !important;">
                                        <button type="button" data-btntext-sending="Sending..." class="btn btn-success btn-icon-split btn-sm" onclick="exportSalesRepReports()">
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
				<input type="hidden" name="admin_id" id="formId" value="">
			</form>
		</div>
	</div>
</div>