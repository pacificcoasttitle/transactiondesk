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
                <h1 class="h3 text-gray-800">Add Holiday</h1>
            </div>
            <div class="col-sm-6">
                <a href="<?php echo base_url() . 'order/admin/holidays'; ?>" class="btn btn-info btn-icon-split float-right mr-2">
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
            <h6 class="m-0 font-weight-bold text-primary">Add Holiday</h6>
        </div>
        <div class="card-body">
            <form id="frm-add-holiday" method="POST">
                <div class="form-group">
                    <label for="holiday_name" class="col-sm-2 col-form-label">Holiday Name<span class="required"> *</span></label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" name="holiday_name" id="holiday_name" class="form-control" placeholder="Holiday Name">
                        <?php if (!empty($holiday_name_error_msg)) {?>
                            <span class="error"><?php echo $holiday_name_error_msg; ?></span>
                        <?php }?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="holiday_date" class="col-sm-2 col-form-label">Holiday Date<span class="required"> *</span></label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" name="holiday_date" id="holiday_date" class="form-control" placeholder="Holiday Date">
                        <?php if (!empty($holiday_date_error_msg)) {?>
                            <span class="error"><?php echo $holiday_date_error_msg; ?></span>
                        <?php }?>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-6">
                        <button type="submit" id="addFee" name="addHoliday" class="btn btn-info btn-icon-split">
                            <span class="icon text-white-50">
                                <i class="fas fa-save"></i>
                            </span>
                            <span class="text">Add</span>
                        </button>
                        <!-- <button type="submit" class="btn btn-secondary">Update</button> -->
                        <a href="<?php echo base_url() . 'order/admin/holidays'; ?>" class="btn btn-secondary btn-icon-split">
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


