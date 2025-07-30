<style>
.bootstrap-select:not([class*="col-"]):not([class*="form-control"]):not(.input-group-btn) {
    width: -webkit-fill-available;
}
</style>
<div class="content">
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-sm-6">
                <h1 class="h3 text-gray-800">Memos</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Edit Memo</h6>
                    </div>
                    <div class="card-body">
                        <form method="post" id="edit_memo_form" name="edit_memo_form" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="subject">Subject<span class="required"> *</span></label>
                                        <input type="text" value="<?php echo $memoInfo['subject'];?>" class="form-control" placeholder="Subject" name="subject" id="subject" required="required">
                                    </div>
                                    <?php if(!empty($subject_error_msg)){ ?>       
                                        <div class="typography-line text-danger">
                                            <?php echo $subject_error_msg;?>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="users">Select Users<span class="required"> *</span></label>
                                        <select name="users[]" class="selectpicker" multiple data-live-search="true" data-actions-box="true" required="required">
                                            <?php foreach($users as $user) {?>
                                                <?php $selected = '';
                                                    if(set_value('users') && in_array($user['id'], set_value('users')))  {
                                                        $selected = 'selected';
                                                    } else {
                                                        $memoInfoUsers = explode(',', $assignedMemoUsers['user_ids']);
                                                        if(in_array($user['id'], $memoInfoUsers))  {
                                                            $selected = 'selected';
                                                        }
                                                    }
                                                ?> 
                                                <option <?php echo $selected;?> value="<?php echo $user['id'];?>"><?php echo $user['first_name']." ".$user['last_name'];?></option>
                                            <?php }?>
                                        </select>
                                    </div>
                                    <?php if(!empty($users_error_msg)){ ?>       
                                        <div class="typography-line text-danger">
                                            <?php echo $users_error_msg;?>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Date<span class="required"> *</span></label>
                                        <input type="text" value="<?php echo date("m/d/Y", strtotime($memoInfo['date']));?>" class="form-control" placeholder="Memo Date" name="memo_date" id="memo_date" value="" required="required">
                                    </div>
                                    <input type="hidden" id="memo_date_val" name="memo_date_val" value="<?php echo date("m/d/Y", strtotime($memoInfo['date']));?>">
                                    <?php if(!empty($memo_date_error_msg)){ ?>  
                                        <div class="typography-line text-danger">
                                            <?php echo $memo_date_error_msg;?>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Description<span class="required"> *</span></label>
                                        <textarea id="memo_description" name="memo_description" rows="15" required="required">
                                            <?php echo $memoInfo['description'];?>
                                        </textarea>
                                    </div>
                                    <?php if(!empty($memo_description_error_msg)){ ?>  
                                        <div class="typography-line text-danger">
                                            <?php echo $memo_description_error_msg;?>
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



