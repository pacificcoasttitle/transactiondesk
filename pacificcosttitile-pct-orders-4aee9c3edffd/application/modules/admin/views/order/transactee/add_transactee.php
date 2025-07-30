<div class="content">
    <div class="container-fluid pc__top-element">
        <div class="row mb-4">
            <div class="col-md-12 d-right">
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
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Add Transctee</h6>
                        </div>
                        <div class="card-body">
                            <form id="frm-add-transactee" method="POST" enctype="multipart/form-data" >

                                <div class="form-group">
                                    <label for="transctee_name" class="col-sm-2 col-form-label">Transactee Name<span class="required"> *</span></label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" name="transctee_name" id="transctee_name" value="<?php echo set_value('transctee_name'); ?>"  placeholder="Transactee Name">
                                        <?php if (!empty($transctee_name_error_msg)) {?>
                                            <span class="error"><?php echo $transctee_name_error_msg; ?></span>
                                        <?php }?>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="file_number" class="col-sm-2 col-form-label">File Number<span class="required"> *</span></label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" name="file_number" id="file_number" value="<?php echo set_value('file_number'); ?>" placeholder="File Number">
                                        <?php if (!empty($file_number_error_msg)) {?>
                                            <span class="error"><?php echo $file_number_error_msg; ?></span>
                                        <?php }?>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="account_number" class="col-sm-2 col-form-label">Account Number<span class="required"> *</span></label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" name="account_number" id="account_number" value="<?php echo set_value('account_number'); ?>" placeholder="Account Number">
                                        <?php if (!empty($account_number_error_msg)) {?>
                                            <span class="error"><?php echo $account_number_error_msg; ?></span>
                                        <?php }?>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="aba" class="col-sm-2 col-form-label">ABA/Routing #<span class="required"> *</span></label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" name="aba" id="aba" value="<?php echo set_value('aba'); ?>" placeholder="ABA/Routing">
                                        <?php if (!empty($aba_error_msg)) {?>
                                            <span class="error"><?php echo $aba_error_msg; ?></span>
                                        <?php }?>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="bank_name" class="col-sm-2 col-form-label">Bank Name<span class="required"> *</span></label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" name="bank_name" id="bank_name" value="<?php echo set_value('bank_name'); ?>" placeholder="Bank Name">
                                        <?php if (!empty($bank_name_error_msg)) {?>
                                            <span class="error"><?php echo $bank_name_error_msg; ?></span>
                                        <?php }?>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="admin_notes" class="col-sm-2 col-form-label">Add Admin Notes</label>
                                    <div class="col-sm-6">
                                        <textarea type="text" class="form-control" name="admin_notes" id="admin_notes" placeholder="Add Admin Notes"> <?php echo set_value('admin_notes'); ?> </textarea>
                                        <?php if (!empty($admin_notes_error_msg)) {?>
                                            <span class="error"><?php echo $admin_notes_error_msg; ?></span>
                                        <?php }?>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="transactee_documents" class="col-sm-2 col-form-label">Upload Document<span class="required"> *</span></label>
                                    <div class="col-sm-6">
                                        <input type="file" class="form-control" name="transactee_documents" id="transactee_documents" accept="application/pdf">
                                        <!-- <textarea type="text" class="form-control" name="transactee_documents" id="transactee_documents" class="form-control" placeholder="Upload Document"></textarea> -->
                                        <?php if (!empty($transactee_documents_error_msg)) {?>
                                            <span class="error"><?php echo $transactee_documents_error_msg; ?></span>
                                        <?php }?>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-6">
                                        <button type="submit" id="add-transactee" name="add-transactee" class="btn btn-info btn-icon-split">
                                            <span class="icon text-white-50">
                                                <i class="fas fa-save"></i>
                                            </span>
                                            <span class="text">Add</span>
                                        </button>
                                        <a href="<?php echo site_url('order/admin/transactees-list'); ?>" class="btn btn-secondary btn-icon-split">
                                            <span class="icon text-white-50">
                                                <i class="fas fa-arrow-left"></i>
                                            </span>
                                            <span class="text">Cancel</span>
                                        </a>
                                    </div>
                                </div>

                                <!-- <div class="pull-right">
                                    <button type="submit" id="add-title-officer" name="add-title-officer" class="btn btn-secondary">Add</button>
                                    <a href="<?php echo site_url('order/admin/title-officers'); ?>" id="cancel" name="cancel" class="btn btn-secondary">Cancel</a>
                                </div> -->
                            </form>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>




