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
      <div class="card-header">Import LP Document Type</div>
        <div class="card-body">        
            <form id="importFrm" method="POST" enctype="multipart/form-data">
                <div class="form-group row">
                    <label for="file" class="col-sm-2 col-form-label">Import File<span class="required"> *</span></label>
                    <div class="col-sm-10">
                        <input type="file" class="" name="file" class="form-control" placeholder="Fee Name">

                        <?php if(!empty($file_error_msg)){ ?>                     
                            <span class="error"><?php echo $file_error_msg; ?></span>
                        <?php } ?>
                    </div>
                </div>
                
                <div class="pull-right">
                    <button type="submit" value="import" name="importSubmit" class="btn btn-secondary">Import</button>
                    <a href="<?php echo base_url(); ?>order/admin/lp-document-types" id="cancel" name="cancel" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>