<style>
    .make-primary-btn {
        background-color: #223D7F;
    }
</style>
<div class="container-fluid">
    <!-- DataTables Example -->
    <div class="row mb-3">
		<div class="col-sm-6">
			<h1 class="h3 text-gray-800">Primary Check</h1>
		</div>
		
	</div>
    <div class="card shadow mb-4">
        <div class="card-header datatable-header py-3">
            <div class="datatable-header-titles" > 
                <span>
                    <i class="fas fa-table"></i>
                </span>
                <h6 class="m-0 font-weight-bold text-primary pl-10">Primary Check</h6> 
            </div>
        </div>

                
        <div class="card-body">
            <div id="customer_success_msg" class="w-100 alert alert-success alert-dismissible" style="display:none;"></div>
            <div id="customer_error_msg" class="w-100 alert alert-danger alert-dismissible" style="display:none;"></div>
            <form name="frmSearch" id="frmSearch" method="POST">
                <div class="form-group row">
                       <div class="col-sm-3">
                            <input type="text" class="form-control" name="keyword" id="keyword" placeholder="Search" value="<?php echo isset($_POST['keyword']) && !empty($_POST['keyword']) ? $_POST['keyword'] : ''; ?>">
                        </div>
                        <div class="">
                            <!-- <button type="submit" class="btn btn-secondary" id="btnSearch">Search</button> -->
                            <button type="submit" style="margin-left:20px;" id="btnSearch" class="btn btn-success btn-icon-split mr-2">
                                <span class="icon text-white-50">
                                    <i class="fa fa-search"></i>
                                </span>
                                <span class="text">Search</span>
                            </button>
                            <button style="margin-left:20px;" id="btnClear" name="cancel" class="btn btn-secondary btn-icon-split mr-2">
                                <span class="icon text-white-50">
                                    <i class="fa fa-eraser"></i>
                                </span>
                                <span class="text">Clear</span>
                            </button>
                            <!-- <a href="javascript:void(0);" id="btnClear" name="cancel" class="btn btn-secondary">Clear</a> -->
                        </div>
                </div>
            </form>
            <div class="table-responsive">
                <table class="table table-bordered" id="tbl-user-check-listing" style="table-layout: fixed;" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th style="width: 8%;">Resware User ID</th>
                            <th style="width: 8%;">Partner ID</th>
                            <th style="width: 10%;">First Name</th>
                            <th style="width: 10%;">Last Name</th>
                            <th style="width: 25%;">Email Address</th>
                            <th style="width: 13%;">Company</th>
                            <th style="width: 12%;">Credential</th>
                            <th style="width: 14%;">Action</th>
                        </tr>
                    </thead>                
                    <tbody>
                        <?php
                            if(isset($users) && !empty($users))
                            {
                                $i = 1;
                                foreach ($users as $key => $value) 
                                {
                                    $last_ele_key = array_key_last($value);
                                    $count = count($value);
                                    $class = '';
                                    foreach ($value as $k => $v) 
                                    {
                                        $checked = $disabled = $border = '';
                                        if($last_ele_key == $k)
                                        {
                                            $border = 'border-bottom:3px solid black';
                                        }
                                        if(isset($v['is_password_updated']) && !empty($v['is_password_updated']) && $v['is_primary'] == 1)
                                        {
                                            $checked = 'checked';
                                            
                                        }
                            ?>
                                        <tr>
                                            <td style="<?php echo $border; ?>"><?php echo $v['resware_user_id'];?></td>
                                            <td style="<?php echo $border; ?>"><?php echo $v['partner_id'];?></td>
                                            <td style="<?php echo $border; ?>"><?php echo $v['first_name'];?></td>
                                            <td style="<?php echo $border; ?>"><?php echo $v['last_name'];?></td>
                                            <td style="<?php echo $border; ?>">
                                                <input type="radio" name="email_address_<?php echo $i; ?>" data-id="<?php echo $v['id'];?>" <?php echo $checked; ?> value="<?php echo $v['email_address'];?>">
                                                <?php echo $v['email_address'];?>
                                                    
                                            </td>
                                            <td style="<?php echo $border; ?>"><?php echo $v['company_name']; ?></td>
                                            <?php
                                                    if ($v['is_password_updated'] == 1 )  
                                                    {
                                                        $credential = 'Correct';     
                                                    } 
                                                    else if($v['is_password_updated'] == 0 && !empty($v['random_password'])) 
                                                    {
                                                        $credential = 'Incorrect';     
                                                    } 
                                                    else 
                                                    {
                                                        $credential = 'Duplicate Email'; 
                                                    }
                                                ?>
                                            <td style="<?php echo $border; ?>">
                                                <?php echo $credential; ?>
                                            </td>
                                            <?php 
                                                if($k == 0)
                                                {
                                                    if ($v['is_password_updated'] == 1 )
                                                    {
                                                        $class = '';
                                                    }
                                            ?>
                                                    <td style="vertical-align: middle; border-bottom: 3px solid black;" align="center" rowspan="<?php echo $count; ?>"><a href="javascript:void(0);" onclick="makePrimary(<?php echo $i; ?>);"  class="make-primary-btn btn btn-secondary <?php echo $class; ?>"> Make Primary </a></td>
                                            <?php
                                                }
                                            ?>
                                            
                                        </tr>
                            <?php
                                    } 
                        ?>
                                    
                        <?php
                                    $i++;
                                }
                            }
                            else
                            {
                        ?>
                                <tr class="odd">
                                    <td valign="top" colspan="8" style="text-align: center;">No matching records found</td>
                                </tr>
                        <?php                                
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div><!-- /.container-fluid -->