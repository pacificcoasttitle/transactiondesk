<style>
.ui-menu .ui-menu-item-wrapper {
    font-size : 13px;
}

.ui-autocomplete {
    max-height: 300px !important;
}
</style>
<div class="content">
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-sm-6">
                <h1 class="h3 text-gray-800">Export Sales Rep Client</h1>
            </div>
            <div class="col-sm-6">
                <a href="<?php echo site_url('order/admin/sales-rep'); ?>" class="btn btn-info btn-icon-split float-right mr-2">
                    <span class="icon text-white-50">
                        <i class="fas fa-arrow-left"></i>
                    </span>
                    <span class="text"> Back </span>
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Export Sales Rep Client</h6>
                    </div>
                    <div class="card-body">
                        <div class="col-xs-12 success-msg hide">
                            <div class="alert alert-success">Client Successfully exported.</div>
                        </div>
                        <div class="col-xs-12 error-msg hide">
                            <div class="alert alert-danger">Something went wrong, Please contact administartive. </div>
                        </div>
                        <form id="export-sales-rep-user" method="POST">
                            <div class="form-group">
                                <label for="sales_rep" class="col-sm-2 col-form-label">Sales Rep<span class="required"> *</span></label>
                                <div class="col-sm-6">
                                    <select name="sales_rep" id="sales_rep" class="form-control">
                                        <?php foreach ($salesUsers as $salesUser) {?>														
                                            <option value="<?php echo $salesUser['id']; ?>"><?php echo $salesUser['first_name'] . " " . $salesUser['last_name']; ?></option>
                                        <?php }?>
                                    </select>
                                    <?php if (!empty($sales_rep_error_msg)) {?>
                                        <span class="error"><?php echo $sales_rep_error_msg; ?></span>
                                    <?php }?>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-6">
                                    <button type="submit" class="btn btn-info btn-icon-split">
                                        <span class="icon text-white-50">
                                            <i class="fas fa-file-export"></i>
                                        </span>
                                        <span class="text">Export Client</span>
                                    </button>
                                    <!-- <button type="submit" class="btn btn-secondary">Update</button> -->
                                    <a href="<?php echo base_url() . 'order/admin/sales-rep'; ?>" class="btn btn-secondary btn-icon-split">
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
