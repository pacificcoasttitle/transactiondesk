
<div class="content">
    <?php if(!empty($success)){ ?>
        <div class="col-xs-12">
            <div class="alert alert-success"><?php echo $success; ?></div>
        </div>
    <?php } ?>

    <?php if(!empty($errors)){ ?>
        <div class="col-xs-12">
            <div class="alert alert-danger"><?php echo $errors; ?></div>
        </div>
    <?php } ?>

    <div class="container-fluid">
        <div class="row">
            <div id="manual_report_success_msg" class="w-100 alert alert-success alert-dismissible" style="display:none;"></div>
            <div id="manual_report_error_msg" class="w-100 alert alert-danger alert-dismissible" style="display:none;"></div>
        </div>

        <div class="row mb-3">
            <div class="col-sm-6">
                <h1 class="h3 text-gray-800">Manual Report</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Daily Production</h6>
                    </div>
                    <div class="card-body">        
                        <div class="form-group">
                            <div class="col-sm-6">
                                <button type="submit" class="btn btn-success btn-icon-split" onclick="sendDailyProductionReport();">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-paper-plane"></i>
                                    </span>
                                    <span class="text">Send Daily Production Email</span>
                                </button>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">LP reports Stats</h6>
                    </div>
                    <div class="card-body">        
                        <div class="form-group">
                            <div class="col-sm-6">
                                <button type="submit" class="btn btn-success btn-icon-split" onclick="sendLPReports();">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-paper-plane"></i>
                                    </span>
                                    <span class="text">Send LP Stats Email</span>
                                </button>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary"> Top Closers</h6>
                    </div>
                    <div class="card-body">        
                        <form id="resware-admin-credential" method="POST" action="<?php echo base_url();?>send-summary-mail-sales-rep">
                            <div class="form-group">
                                <label for="user_type" class="col-sm-2 col-form-label">Sales Rep<span class="required"> *</span></label>
                                <div class="col-sm-6">
                                    <select name="sales_rep" id="sales_rep" class="form-control" required>
                                        <option value="">Select Sales Rep</option>
                                        <?php foreach($salesUsers as $salesUser) { ?>
                                            <option value="<?php echo $salesUser['id'];?>"><?php echo $salesUser['first_name']." ".$salesUser['last_name'];?></option>
                                        <?php }?>
                                    </select>
                                </div>
                            </div>
                          
                            <div class="form-group">
                                <div class="col-sm-6">
                                    <button type="submit" class="btn btn-success btn-icon-split">
                                        <span class="icon text-white-50">
                                            <i class="fas fa-save"></i>
                                        </span>
                                        <span class="text">Send Summary Email</span>
                                    </button>
                                    <a href="<?php echo site_url('order/admin'); ?>" class="btn btn-secondary btn-icon-split">
                                        <span class="icon text-white-50">
                                            <i class="fas fa-arrow-left"></i>
                                        </span>
                                        <span class="text">Cancel</span>
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary"> Non-Openers - New</h6>
                    </div>
                    <div class="card-body">        
                        <form id="resware-admin-credential" method="POST" action="<?php echo base_url();?>send-non-openers-email">
                            <div class="form-group">
                                <label for="user_type" class="col-sm-2 col-form-label">Sales Rep<span class="required"> *</span></label>
                                <div class="col-sm-6">
                                    <select name="sales_rep" id="sales_rep" class="form-control" required>
                                        <option value="">Select Sales Rep</option>
                                        <?php foreach($salesUsers as $salesUser) { ?>
                                            <option value="<?php echo $salesUser['id'];?>"><?php echo $salesUser['first_name']." ".$salesUser['last_name'];?></option>
                                        <?php }?>
                                    </select>
                                </div>
                            </div>
                          
                            <div class="form-group">
                                <div class="col-sm-6">
                                    <button type="submit" class="btn btn-success btn-icon-split">
                                        <span class="icon text-white-50">
                                            <i class="fas fa-save"></i>
                                        </span>
                                        <span class="text">Send Non Openers Email</span>
                                    </button>
                                    <a href="<?php echo site_url('order/admin'); ?>" class="btn btn-secondary btn-icon-split">
                                        <span class="icon text-white-50">
                                            <i class="fas fa-arrow-left"></i>
                                        </span>
                                        <span class="text">Cancel</span>
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div> 
    </div>
</div>




