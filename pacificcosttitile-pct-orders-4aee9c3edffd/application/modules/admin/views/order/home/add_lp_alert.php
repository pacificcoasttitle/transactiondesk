<style>
.ui-menu .ui-menu-item-wrapper {
    font-size : 13px;
}

.ui-autocomplete {
    max-height: 300px !important;
}
</style>
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
                <h1 class="h3 text-gray-800">LP Alert</h1>
            </div>
            <div class="col-sm-6">
                <a href="<?php echo base_url() . 'order/admin/lp-alert'; ?>" class="btn btn-info btn-icon-split float-right mr-2">
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
                        <h6 class="m-0 font-weight-bold text-primary">Add New LP Alert</h6>
                    </div>
                    <div class="card-body">
                        <form id="add-alert" method="POST">

                            <div class="form-group">
                                <label for="days" class="col-sm-4 col-form-label">Days<span class="required"> *</span></label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="days" id="days" value="<?php echo set_value('days') ?>" class="form-control" placeholder="Days">
                                    <?php if (!empty($days_error_msg)) {?>
                                        <span class="error"><?php echo $days_error_msg; ?></span>
                                    <?php }?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="description" class="col-sm-4 col-form-label">Description</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="description" id="description" value="<?php echo set_value('description') ?>" class="form-control" placeholder="Add Description">
                                    <?php if (!empty($description_error_msg)) {?>
                                        <span class="error"><?php echo $description_error_msg; ?></span>
                                    <?php }?>
                                </div>
                            </div>

                            <div class="form-group row ml-1">
                                <label for="color_code" class="col-sm-2 col-form-label">Color Code</label>
                                <div class="col-sm-2">
                                    <input type="color" class="form-control" name="color_code" id="color_code" value="#FFFFFF" class="form-control" placeholder="Color Code" style="width: 40%;" >
                                </div>
                            </div>

                            <div class="form-group row ml-1">
                                <label for="text_color" class="col-sm-2 col-form-label">Text Color</label>
                                <div class="col-sm-2">
                                    <input type="color" class="form-control" name="text_color" id="text_color" value="#FFFFFF" class="form-control" placeholder="Text Code" style="width: 40%;" >
                                </div>
                            </div>

                            <div class="form-group row ml-1">
                                <label for="regular_order_color_code" class="col-sm-2 col-form-label"> Regular Order Code </label>
                                <div class="col-sm-2">
                                    <input type="color" class="form-control" name="regular_order_color_code" id="regular_order_color_code" value="#FFFFFF" class="form-control" style="width: 40%;" >
                                </div>
                            </div>

                            <div class="form-group row ml-1">
                                <label for="delete" class="col-sm-2 col-form-label">Delete</label>
                                <div class="col-sm-2">
                                    <input type="checkbox" value="1" class="form-control" style="width: 20px;"  name="delete" id="delete" class="form-control">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-6">
                                    <button type="submit" class="btn btn-info btn-icon-split">
                                        <span class="icon text-white-50">
                                            <i class="fas fa-save"></i>
                                        </span>
                                        <span class="text">Add</span>
                                    </button>
                                    <!-- <button type="submit" class="btn btn-secondary">Update</button> -->
                                    <a href="<?php echo base_url() . 'order/admin/lp-alert'; ?>" class="btn btn-secondary btn-icon-split">
                                        <span class="icon text-white-50">
                                            <i class="fas fa-arrow-left"></i>
                                        </span>
                                        <span class="text">Cancel</span>
                                    </a>
                                </div>
                            </div>

                            <!-- <div class="pull-right">
                                <button type="submit" class="btn btn-secondary">Add</button>
                                <a href="<?php echo base_url() . 'order/admin/lp-alert'; ?>" id="cancel" name="cancel" class="btn btn-secondary">Cancel</a>
                            </div>       -->
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
