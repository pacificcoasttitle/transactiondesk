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
      <div class="card-header">Import Escrow Instruction</div>
        <div class="card-body">        
            <form id="esw_ins" method="POST" enctype="multipart/form-data">

                <div class="form-group row">
                    <label for="file" class="col-sm-4 col-form-label">Escrow Instruction Type<span class="required"> *</span></label>
                    <div class="col-sm-8">
                        <select name="escrow_instruction" id="escrow_instruction" class="form-control" required="required">
                            <option value="">Select Escrow Instruction</option>
                            <?php if (!empty($escrow_instruction_list)) {
                                foreach ($escrow_instruction_list as $escrow_instruction){ ?>
                                <option value="<?php echo $escrow_instruction->id;?>"><?php echo $escrow_instruction->name;?></option>
                            <?php } }?>
                        </select>

                        
                    </div>
                </div>

                <div class="form-group row">
                    <label for="file" class="col-sm-4 col-form-label">Import File<span class="required"> *</span></label>
                    <div class="col-sm-8">
                        <input type="file" class="" name="file" class="form-control">

                        <?php if(!empty(form_error('file'))){ ?>                     
						    <span class="text-danger"><?php echo form_error('file'); ?></span>
					    <?php } ?>
                    </div>
                </div>
                
                <div class="pull-right">
                    <button type="submit" value="import" name="importSubmit" class="btn btn-secondary">Import</button>
                    <a href="<?php echo base_url(); ?>hr/admin/escrow-instruction" id="cancel" name="cancel" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>