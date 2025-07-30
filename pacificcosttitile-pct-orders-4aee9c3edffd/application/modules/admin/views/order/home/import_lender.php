<div class="container">
<?php if(!empty($success_msg)){ ?>
    <div class="col-xs-12">
        <div class="alert alert-success"><?php echo $success_msg; ?></div>
    </div>
<?php } ?>
<?php if(!empty($error_msg)){ ?>
    <div class="col-xs-12">
        <div class="alert alert-danger"><?php echo $error_msg; ?></div>
    </div>
<?php } ?>
    <div id="import-result"></div>
    <div class="card mx-auto mt-5">
      <div class="card-header">Import Lenders</div>
        <div class="card-body">        
            <form id="importLenderFrm" method="POST" enctype="multipart/form-data">

                <div class="form-group row">
                    <label for="file" class="col-sm-2 col-form-label">Lender Type<span class="required"> *</span></label>
                    <div class="col-sm-10">
                        <select name="lenderType" id="lenderType" class="form-control">
                            <option value="">Select Lender Type</option>
                            <option value="wescor">Wescor</option>
                            <option value="natic">Natic</option>
                            <option value="none">None</option>
                        </select>

                        <?php if(!empty($file_error_msg)){ ?>                     
                            <span class="error"><?php echo $file_error_msg; ?></span>
                        <?php } ?>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="file" class="col-sm-2 col-form-label">Import File<span class="required"> *</span></label>
                    <div class="col-sm-10">
                        <input type="file" name="file" class="">

                        <?php if(!empty($file_error_msg)){ ?>                     
                            <span class="error"><?php echo $file_error_msg; ?></span>
                        <?php } ?>
                    </div>
                </div>
                
                <div class="pull-right">
                    <button type="submit" value="import" name="importSubmit" class="btn btn-secondary">Import</button>
                    <a href="<?php echo base_url(); ?>order/admin/lenders" id="cancel" name="cancel" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>