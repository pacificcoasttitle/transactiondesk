<div class="content">
<?php if (!empty($success_msg)) {?>
    <div class="col-xs-12">
        <div class="alert alert-success"><?php echo $success_msg; ?></div>
    </div>
<?php }?>
<?php if (!empty($error_msg)) {?>
    <div class="col-xs-12">
        <div class="alert alert-danger"><?php echo $error_msg; ?></div>
    </div>
<?php }?>
<div class="container-fluid">
        <div class="row mb-3">
            <div class="col-sm-6">
                <h1 class="h3 text-gray-800">Fee Type</h1>
            </div>
            <div class="col-sm-6">
                <a href="<?php echo base_url() . 'order/admin/fees-types'; ?>" class="btn btn-info btn-icon-split float-right mr-2">
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
            <h6 class="m-0 font-weight-bold text-primary">Add Fee Type</h6>
        </div>
        <div class="card-body">
            <form id="frm-add-fee-type" method="POST">
                <div class="form-group">
                    <label for="fee_type" class="col-sm-2 col-form-label">Fee Type<span class="required"> *</span></label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" name="fee_type" id="fee_type" class="form-control" placeholder="Fee Type">

                        <?php if (!empty($name_error_msg)) {?>
                            <span class="error"><?php echo $name_error_msg; ?></span>
                        <?php }?>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-6">
                        <button type="submit" id="addFeeType" name="addFeeType" class="btn btn-info btn-icon-split">
                            <span class="icon text-white-50">
                                <i class="fas fa-save"></i>
                            </span>
                            <span class="text">Add</span>
                        </button>
                        <!-- <button type="submit" class="btn btn-secondary">Update</button> -->
                        <a href="<?php echo base_url() . 'order/admin/fees-types'; ?>" class="btn btn-secondary btn-icon-split">
                            <span class="icon text-white-50">
                                <i class="fas fa-arrow-left"></i>
                            </span>
                            <span class="text">Cancel</span>
                        </a>
                    </div>
                </div>

                <!-- <div class="pull-right">
                    <button type="submit" id="addFeeType" name="addFeeType" class="btn btn-secondary">Add</button>
                    <a href="<?php echo base_url() . 'order/admin/fees-types'; ?>" id="cancel" name="cancel" class="btn btn-secondary">Cancel</a>
                </div> -->
            </form>
        </div>
    </div>
</div>