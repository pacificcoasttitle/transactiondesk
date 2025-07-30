<div class="content">
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-sm-6">
                <h1 class="h3 text-gray-800">Branches</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Add Branch</h6>
                    </div>
                    <div class="card-body">
                        <form method="post" name="add_branch_form" >
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Branch Name<span class="required"> *</span></label>
                                        <input type="text" value="<?php echo $branchInfo['name'];?>" class="form-control" placeholder="Branch Name" name="branch_name" id="branch_name" required="required">
                                    </div>
                                    <?php if(!empty($branch_name_error_msg)){ ?>       
                                        <div class="typography-line text-danger">
                                            <?php echo $branch_name_error_msg;?>
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
                            <a href="<?php echo base_url().'hr/admin/branches'; ?>" class="btn btn-secondary btn-icon-split">
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


