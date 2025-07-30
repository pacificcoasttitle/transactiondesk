
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    <div id="dashboard_count">
        <?php $userdata = $this->session->userdata('hr_admin'); 
            if ($userdata['user_type_id'] == '4' || $userdata['user_type_id'] == 6) { ?>
                <div class="d-sm-flex">
                    <?php if (!empty($users)) { ?>
                        <label class="filter_label">Select User:</label>
                        <select name="user_filter" id="user_filter" class="form-control" style="width: auto !important;">
                            <option value="all_users">All Users</option>
                            <?php foreach ($users as $user) { ?>
                                <option <?php echo ($user['id'] == $user_id) ? 'selected' : '';?> value="<?php echo $user['id'];?>"><?php echo $user['first_name']." ".$user['last_name'];?></option>
                            <?php } ?>
                        </select> 
                    <?php } ?>
                    <label class="filter_label">Select Month:</label>
                    <select name="month" id="month" class="form-control" style="width: auto !important;">
                        <?php
                            $selected_month = $month ? $month : date('m'); 
                            for ($i_month = 1; $i_month <= 12; $i_month++) { 
                                $i_month = sprintf("%02d", $i_month);
                                $selected = ($selected_month == $i_month ? ' selected' : '');
                                echo '<option value="'.$i_month.'"'.$selected.'>'. date('F', mktime(0,0,0,$i_month)).'</option>'."\n";
                            }
                        ?>
                    </select> 
                </div>  
        <?php } else { ?> 
                <div class="d-sm-flex">
                    <?php if (!empty($managers)) { ?>
                        <label class="filter_label">Select Manager:</label>
                        <select name="manager_filter" id="manager_filter" class="form-control" style="width: auto !important;">
                            <option value="all_managers">All Managers</option>
                            <?php foreach ($managers as $manager) { ?>
                                <option <?php echo ($manager->id == $manager_id) ? 'selected' : '';?> value="<?php echo $manager->id;?>"><?php echo $manager->first_name." ".$manager->last_name;?></option>
                            <?php } ?>
                        </select> 
                    <?php } ?>
                    
                    <label class="filter_label">Select User:</label>
                    <select name="user_filter" id="user_filter" class="form-control" style="width: auto !important;">
                        <option value="all_users">All Users</option>
                        <?php if (!empty($users)) { 
                            foreach ($users as $user) { ?>
                                <option <?php echo ($user['id'] == $user_id) ? 'selected' : '';?> value="<?php echo $user['id'];?>"><?php echo $user['first_name']." ".$user['last_name'];?></option>
                            <?php } ?>
                        <?php } ?>
                    </select> 
                
                    <label class="filter_label">Select Month:</label>
                    <select name="month" id="month" class="form-control" style="width: auto !important;">
                        <?php
                            $selected_month = $month ? $month : date('m'); 
                            for ($i_month = 1; $i_month <= 12; $i_month++) { 
                                $i_month = sprintf("%02d", $i_month);
                                $selected = ($selected_month == $i_month ? ' selected' : '');
                                echo '<option value="'.$i_month.'"'.$selected.'>'. date('F', mktime(0,0,0,$i_month)).'</option>'."\n";
                            }
                        ?>
                    </select> 
                </div> 
        <?php } ?>
    </div>              
</div>

<div class="row">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">TITLE OPENINGS MTD</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800 mt-1 mb-1"><?Php echo $total_open_count; ?></div>
                        <div class="text-xs font-weight-bold text-success text-uppercase mt-1 mb-1">SALES = <?Php echo $sale_open_count;?></div>
                        <div class="text-xs font-weight-bold text-info text-uppercase mt-1 mb-1">REFI'S = <?Php echo $refi_open_count;?></div>
                        <div class="text-xs font-weight-bold text-warning text-uppercase mt-1 mb-1">PROJECTED  = <?Php echo $projected_open_count;?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-table fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">TITLE CLOSINGS MTD</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800 mt-1 mb-1"><?Php echo $total_close_count; ?></div>
                        <div class="text-xs font-weight-bold text-primary text-uppercase mt-1 mb-1">SALES = <?Php echo $sale_close_count;?></div>
                        <div class="text-xs font-weight-bold text-info text-uppercase mt-1 mb-1">REFI'S = <?Php echo $refi_close_count;?></div>
                        <div class="text-xs font-weight-bold text-warning text-uppercase mt-1 mb-1">PROJECTED  = <?Php echo $projected_close_count;?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-table fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">TITLE REVENUE MTD</div>
                        <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800 mt-1 mb-1">$<?php echo number_format($total_premium); ?></div>
                        <div class="text-xs font-weight-bold text-primary text-uppercase mt-1 mb-1">SALES = $<?php echo number_format($sale_total_premium); ?></div>
                        <div class="text-xs font-weight-bold text-success text-uppercase mt-1 mb-1">REFI'S = $<?php echo number_format($refi_total_premium); ?></div>
                        <div class="text-xs font-weight-bold text-warning text-uppercase mt-1 mb-1">PROJECTED  = $<?Php echo number_format($projected_revenue);?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">CLOSINGS RATIO AVG</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800 mt-1 mb-1"><?Php echo $close_order_percetage;?>%</div>
                        <div class="text-xs font-weight-bold text-primary text-uppercase mt-1 mb-1">SALES = <?Php echo $sale_close_order_percetage;?>%</div>
                        <div class="text-xs font-weight-bold text-success text-uppercase mt-1 mb-1">REFI'S = <?Php echo $refi_close_order_percetage;?>%</div>
                        <div class="text-xs font-weight-bold text-info text-uppercase mt-1 mb-1">PROJECTED  = 0%</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-percent fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
	
