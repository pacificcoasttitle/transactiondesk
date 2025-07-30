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
            <div class="col-sm-12 d-space-between">
                <h1 class="h3 text-gray-800"><?=$pageTitle?></h1>
                <a href="<?php echo site_url('order/admin/transactees-list'); ?>" class="btn btn-info btn-icon-split">
                    <span class="icon text-white-50">
                        <i class="fas fa-arrow-left"></i>
                    </span>
                    <span class="text">Back</span>
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Edit <?=$pageTitle?></h6>
                    </div>
                    <div class="card-body">
                        <form id="frm-update-transactee" method="POST">

                            <div class="form-group">
                                <label for="transctee_name" class="col-sm-2 col-form-label">Transctee Name<span class="required"> *</span></label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="transctee_name" id="transctee_name" class="form-control" value="<?php echo $transactee_info['transctee_name']; ?>" placeholder="First Name">
                                    <?php if (!empty($transctee_name_error_msg)) {?>
                                        <span class="error"><?php echo $transctee_name_error_msg; ?></span>
                                    <?php }?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="file_number" class="col-sm-2 col-form-label">File Number<span class="required"> *</span></label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="file_number" id="file_number" class="form-control" value="<?php echo $transactee_info['file_number']; ?>" placeholder="Last Name">
                                    <?php if (!empty($file_number_error_msg)) {?>
                                        <span class="error"><?php echo $file_number_error_msg; ?></span>
                                    <?php }?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="account_number" class="col-sm-2 col-form-label">Account Number<span class="required"> *</span></label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="account_number" id="account_number" class="form-control" placeholder="Email Address" value="<?php echo isset($transactee_info['account_number']) && !empty($transactee_info['account_number']) ? $transactee_info['account_number'] : '' ?>">
                                    <?php if (!empty($account_number_error_msg)) {?>
                                        <span class="error"><?php echo $account_number_error_msg; ?></span>
                                    <?php }?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="aba" class="col-sm-2 col-form-label">ABA/Routing #<span class="required"> *</span></label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="aba" id="aba" class="form-control" placeholder="Company Name" value="<?php echo isset($transactee_info['aba']) && !empty($transactee_info['aba']) ? $transactee_info['aba'] : '' ?>">
                                    <?php if (!empty($aba_error_msg)) {?>
                                        <span class="error"><?php echo $aba_error_msg; ?></span>
                                    <?php }?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="bank_name" class="col-sm-2 col-form-label">Bank Name<span class="required"> *</span></label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="bank_name" id="bank_name" class="form-control" placeholder="Address" value="<?php echo isset($transactee_info['bank_name']) && !empty($transactee_info['bank_name']) ? $transactee_info['bank_name'] : '' ?>">
                                    <?php if (!empty($bank_name_error_msg)) {?>
                                        <span class="error"><?php echo $bank_name_error_msg; ?></span>
                                    <?php }?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="admin_notes" class="col-sm-2 col-form-label">Admin Notes<span class="required"> *</span></label>
                                <div class="col-sm-6">
                                    <textarea type="text" class="form-control" name="admin_notes" id="admin_notes" class="form-control" placeholder="Admin Notes" ><?php echo isset($transactee_info['admin_notes']) && !empty($transactee_info['admin_notes']) ? $transactee_info['admin_notes'] : '' ?></textarea>

                                    <?php if (!empty($admin_notes_error_msg)) {?>
                                        <span class="error"><?php echo $admin_notes_error_msg; ?></span>
                                    <?php }?>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-6">
                                    <button type="submit" id="update-transactee" name="update-transactee" class="btn btn-info btn-icon-split">
                                        <span class="icon text-white-50">
                                            <i class="fas fa-save"></i>
                                        </span>
                                        <span class="text">Update</span>
                                    </button>

                                    <a href="<?php echo site_url('order/admin/transactees-list'); ?>" class="btn btn-secondary btn-icon-split">
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