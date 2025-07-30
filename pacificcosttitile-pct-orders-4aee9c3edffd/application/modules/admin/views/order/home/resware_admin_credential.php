
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
                <h1 class="h3 text-gray-800">Resware Admin Credential</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Resware Admin Credential</h6>
                    </div>
                    <div class="card-body">
                        <form id="resware-admin-credential" method="POST">

                            <div class="form-group">
                                <label for="resware_username" class="col-sm-2 col-form-label">Username<span class="required"> *</span></label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="resware_username" id="resware_username" value="<?php echo set_value('username') ? set_value('username') : $credResult['username']; ?>" class="form-control" placeholder="Resware Admin Username" required>

                                    <?php if (!empty($resware_username_error_msg)) {?>
                                        <span class="error"><?php echo $resware_username_error_msg; ?></span>
                                    <?php }?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="resware_password" class="col-sm-2 col-form-label">Password<span class="required"> *</span></label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="resware_password" id="resware_password" value="<?php echo set_value('password') ? set_value('password') : $credResult['password']; ?>" class="form-control" placeholder="Resware Admin Password" required>
                                    <?php if (!empty($resware_password_error_msg)) {?>
                                        <span class="error"><?php echo $resware_password_error_msg; ?></span>
                                    <?php }?>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-6">
                                    <button type="submit" class="btn btn-info btn-icon-split">
                                        <span class="icon text-white-50">
                                            <i class="fas fa-save"></i>
                                        </span>
                                        <span class="text">Update</span>
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




