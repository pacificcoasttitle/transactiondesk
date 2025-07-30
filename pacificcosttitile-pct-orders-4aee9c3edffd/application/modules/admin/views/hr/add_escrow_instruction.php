<style>
.bootstrap-select:not([class*="col-"]):not([class*="form-control"]):not(.input-group-btn) {
    width: -webkit-fill-available;
}
</style>
<div class="content">
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-sm-6">
                <h1 class="h3 text-gray-800">Escrow Instructions</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Add Escrow Instruction</h6>
                    </div>
                    <div class="card-body">
                        <form method="post" id="add_escrow_instruction_form" name="add_escrow_instruction_form" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="escrow_instruction">Select Escrow Instruction Type<span class="required"> *</span></label>
                                        <select name="escrow_instruction" id="escrow_instruction" class="form-control" required="required">
                                            <option>--Select Escrow Instruction Type--</option>
                                            <?php foreach($escrow_instruction_list as $escrow_instruction) {?>
                                                <option value="<?php echo $escrow_instruction->id;?>"><?php echo $escrow_instruction->name;?></option>
                                            <?php }?>
                                        </select>
                                    </div>
                                    <?php if(!empty($escrow_instruction_error_msg)){ ?>       
                                        <div class="typography-line text-danger">
                                            <?php echo $escrow_instruction_error_msg;?>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="subject">Custom Field Value ID<span class="required"> *</span></label>
                                        <input type="text" value="<?php echo set_value('custom_field_value_id')?>" class="form-control" placeholder="Custom Field Value ID" name="custom_field_value_id" id="custom_field_value_id" required="required">
                                    </div>
                                    <?php if(!empty($custom_field_value_id_error_msg)){ ?>       
                                        <div class="typography-line text-danger">
                                            <?php echo $custom_field_value_id_error_msg;?>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Custom Field ID<span class="required"> *</span></label>
                                        <input type="text" value="<?php echo set_value('custom_field_id')?>" class="form-control" placeholder="Custom Field ID" name="custom_field_id" id="custom_field_id" value="" required="required">
                                    </div>
                                    
                                    <?php if(!empty($custom_field_id_error_msg)){ ?>  
                                        <div class="typography-line text-danger">
                                            <?php echo $custom_field_id_error_msg;?>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Name<span class="required"> *</span></label>
                                        <input type="text" value="<?php echo set_value('name')?>" class="form-control" placeholder="Name" name="name" id="name" value="" required="required">
                                    </div>
                                    
                                    <?php if(!empty($name_error_msg)){ ?>  
                                        <div class="typography-line text-danger">
                                            <?php echo $name_error_msg;?>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Value<span class="required"> *</span></label>
                                        <textarea id="escrow_instruction_value" name="escrow_instruction_value" rows="15" required="required">
                                            <?php echo set_value('escrow_instruction_value')?>
                                        </textarea>
                                        
                                    </div>
                                    <?php if(!empty($escrow_instruction_value_error_msg)){ ?>  
                                        <div class="typography-line text-danger">
                                            <?php echo $escrow_instruction_value_error_msg;?>
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
                            <a href="<?php echo base_url().'hr/admin/memos'; ?>" class="btn btn-secondary btn-icon-split">
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



