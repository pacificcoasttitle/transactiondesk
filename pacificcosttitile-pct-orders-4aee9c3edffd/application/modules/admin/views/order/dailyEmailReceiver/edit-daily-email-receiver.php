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
                <h1 class="h3 text-gray-800">Edit Daily Email Receiver</h1>
            </div>
            <div class="col-sm-6">
                <a href="<?php echo base_url() . 'order/admin/daily-email-control'; ?>" class="btn btn-info btn-icon-split float-right mr-2">
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
            <h6 class="m-0 font-weight-bold text-primary">Edit Daily Email Receiver</h6>
        </div>
        <div class="card-body">
            <form id="frm-add-holiday" method="POST">
                <div class="form-group">
                    <label for="email" class="col-sm-2 col-form-label">Email<span class="required"> *</span></label>
                    <div class="col-sm-6">
                        <input type="text" value="<?php echo $receiver_info['email']; ?>" class="form-control" name="email" id="email" class="form-control" placeholder="Email">
                        <?php if (!empty($email_error_msg)) {?>
                            <span class="error"><?php echo $email_error_msg; ?></span>
                        <?php }?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="branch" class="col-sm-2 col-form-label">Branch<span class="required"> *</span></label>
                    <div class="col-sm-6">
                        <select name="branch" id="branch" class="form-control" placeholder="Please select branch" >
                            <option value="">Please Select Branch</option>
                            <option value="glendale" <?php echo ($receiver_info['branch'] == 'glendale') ? 'selected' : '' ?>>Glendale</option>
                            <option value="orange" <?php echo ($receiver_info['branch'] == 'orange') ? 'selected' : '' ?>>Orange</option>
                            <option value="both" <?php echo ($receiver_info['branch'] == 'both') ? 'selected' : '' ?>>Both</option>
                        </select>

                        <?php if (!empty($branch_error_msg)) {?>
                            <span class="error"><?php echo $branch_error_msg; ?></span>
                        <?php }?>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-6">
                        <div class="row">
                            <label for="status" class="col-sm-5 col-form-label">Email receiving Status<span class="required"> *</span></label>
                            <input type="checkbox" class="col-sm-3 form-control" name="status" id="status" class="form-control" <?php echo ($receiver_info['status']) ? "checked" : ""; ?>>
                        </div>
                        <?php if (!empty($status_error_msg)) {?>
                            <span class="error"><?php echo $status_error_msg; ?></span>
                        <?php }?>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-6">
                        <button type="submit" id="addFee" name="addHoliday" class="btn btn-info btn-icon-split">
                            <span class="icon text-white-50">
                                <i class="fas fa-save"></i>
                            </span>
                            <span class="text">Update</span>
                        </button>
                        <a href="<?php echo base_url() . 'order/admin/daily-email-control'; ?>" class="btn btn-secondary btn-icon-split">
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