<div class="content">
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-sm-6">
                <h1 class="h3 text-gray-800">Positions</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Add Position</h6>
                    </div>
                    <div class="card-body">
                        <form method="post" name="add_position_form" >
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Position Name<span class="required"> *</span></label>
                                        <input type="text" class="form-control" placeholder="Position Name" name="position_name" id="position_name" required="required">
                                    </div>
                                    <?php if(!empty($position_name_error_msg)){ ?>       
                                        <div class="typography-line text-danger">
                                            <?php echo $position_name_error_msg;?>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        
                            <button type="submit" class="btn btn-info btn-icon-split">
                                <span class="icon text-white-50">
                                    <i class="fas fa-save"></i>
                                </span>
                                <span class="text">Save</span>
                            </button>
                            <a href="<?php echo base_url().'hr/admin/positions'; ?>" class="btn btn-secondary btn-icon-split">
                                <span class="icon text-white-50">
                                    <i class="fas fa-arrow-right"></i>
                                </span>
                                <span class="text">Cancel</span>
                            </a>
                            <div class="clearfix"></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


