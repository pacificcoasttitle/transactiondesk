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
                <h1 class="h3 text-gray-800">Code Book</h1>
            </div>
            <div class="col-sm-6">
                <a href="<?php echo base_url() . 'order/admin/code-book'; ?>" class="btn btn-info btn-icon-split float-right mr-2">
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
            <h6 class="m-0 font-weight-bold text-primary">Edit Code Book</h6>
        </div>
        <div class="card-body">
            <form id="add-new-master-user" method="POST">
                <div class="form-group row">
                    <label for="code" class="col-sm-2 col-form-label">Code<span class="required"> *</span></label>
                    <div class="col-sm-10">
                        <?php $code = set_value('code') ? set_value('code') : $codeBookInfo['code'];?>
                        <input type="text" class="form-control" name="code" id="code" value="<?php echo $code; ?>" class="form-control" placeholder="First Name">
                        <?php if (!empty($code_error_msg)) {?>
                            <span class="error"><?php echo $code_error_msg; ?></span>
                        <?php }?>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="type_id" class="col-sm-2 col-form-label">Type Id<span class="required"> *</span></label>
                    <div class="col-sm-10">
                        <?php $type_id = set_value('type_id') ? set_value('type_id') : $codeBookInfo['type_id'];?>
                        <input type="text" class="form-control" name="type_id" id="type_id" value="<?php echo $type_id; ?>" class="form-control" placeholder="Last Name">
                        <?php if (!empty($type_id_error_msg)) {?>
                            <span class="error"><?php echo $type_id_error_msg; ?></span>
                        <?php }?>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="type" class="col-sm-2 col-form-label">Type<span class="required"> *</span></label>
                    <div class="col-sm-10">
                        <?php $type = set_value('type') ? set_value('type') : $codeBookInfo['type'];?>
                        <select id="type" name="type">
                            <option value="">Select</option>
                            <option <?php echo (trim(strtolower($type)) == 'easement') ? 'selected' : ''; ?> value="Easement">Easement</option>
                            <option <?php echo (trim(strtolower($type)) == 'lien') ? 'selected' : ''; ?> value="Lien">Lien</option>
                            <option <?php echo (trim(strtolower($type)) == 'requirement') ? 'selected' : ''; ?> value="Requirement">Requirement</option>
                            <option <?php echo (trim(strtolower($type)) == 'restriction') ? 'selected' : ''; ?> value="Restriction">Restriction</option>
                            <option <?php echo (trim(strtolower($type)) == 'tax') ? 'selected' : ''; ?> value="Tax">Tax</option>
                        </select>
                        <?php if (!empty($type_error_msg)) {?>
                            <span class="error"><?php echo $type_error_msg; ?></span>
                        <?php }?>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="language" class="col-sm-2 col-form-label">Language</label>
                    <div class="col-sm-10">
                        <?php $language = set_value('language') ? set_value('language') : $codeBookInfo['language'];
$language = str_replace('<br>', PHP_EOL, $language);
?>
                        <textarea id="language" name="language" rows="10" cols="100"><?php echo $language; ?></textarea>
                        <?php if (!empty($language_error_msg)) {?>
                            <span class="error"><?php echo $language_error_msg; ?></span>
                        <?php }?>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="language" class="col-sm-2 col-form-label">Required Number</label>
                    <div class="col-sm-1">
                        <input <?php echo $codeBookInfo['required_number'] == 1 ? "checked" : ""; ?> type="checkbox" class="form-control" name="required_number" id="required_number" class="form-control">
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-6">
                        <button type="submit" class="btn btn-info btn-icon-split">
                            <span class="icon text-white-50">
                                <i class="fas fa-save"></i>
                            </span>
                            <span class="text">Save</span>
                        </button>
                        <a href="<?php echo base_url() . 'order/admin/code-book'; ?>" class="btn btn-secondary btn-icon-split">
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

