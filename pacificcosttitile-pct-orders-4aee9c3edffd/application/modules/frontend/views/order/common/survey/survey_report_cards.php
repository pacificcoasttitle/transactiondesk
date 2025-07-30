<?php //echo "<pre>";
//print_r($value);die; ?>
<div class="col-md-1 col-sm-12"></div>
<div class="col-md-2 col-sm-12">
    <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1 sales_loan_count" id="open_order_count"><?Php echo $avg['Q1'] ?? 0;?></div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800 sales_loan_section">
                        <i class="fa fa-star filled-str" aria-hidden="true"></i>
                        AVG. STARS
                    </div>
                </div>
            </div>
            <div class="clearfix small z-1 viewDetails text-primary projected_goal_section">
                RESPONSES = <span id="projected_open_section"><?Php echo count($rating);?></span>
            </div>
        </div>
    </div>
</div>
<div class="col-md-2 col-sm-12">
    <div class="card border-left-success shadow h-100 py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1 sales_loan_count" id="open_order_count"><?Php echo $avg['Q2'] ?? 0;?></div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800 sales_loan_section">
                        <i class="fa fa-star filled-str" aria-hidden="true"></i>
                        AVG. STARS
                    </div>
                </div>
            </div>
            <div class="clearfix small z-1 viewDetails text-success projected_goal_section">
                RESPONSES = <span id="projected_open_section"><?Php echo count($rating);?></span>
            </div>
        </div>
    </div>
</div>
<div class="col-md-2 col-sm-12">
    <div class="card border-left-info shadow h-100 py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1 sales_loan_count" id="open_order_count"><?Php echo $avg['Q3'] ?? 0;?></div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800 sales_loan_section">
                        <i class="fa fa-star filled-str" aria-hidden="true"></i>
                        AVG. STARS
                    </div>
                </div>
            </div>
            <div class="clearfix small z-1 viewDetails text-info projected_goal_section">
                RESPONSES = <span id="projected_open_section"><?Php echo count($rating);?></span>
            </div>
        </div>
    </div>
</div>
<div class="col-md-2 col-sm-12">
    <div class="card border-left-warning shadow h-100 py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1 sales_loan_count" id="open_order_count"><?Php echo $avg['Q4'] ?? 0;?></div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800 sales_loan_section">
                        <i class="fa fa-star filled-str" aria-hidden="true"></i>
                        AVG. STARS
                    </div>
                </div>
            </div>
            <div class="clearfix small z-1 viewDetails text-warning projected_goal_section">
                RESPONSES = <span id="projected_open_section"><?Php echo count($rating);?></span>
            </div>
        </div>
    </div>
</div>
<div class="col-md-2 col-sm-12">
    <div class="card border-left-danger shadow h-100 py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1 sales_loan_count" id="open_order_count"><?Php echo $avg['Q5'] ?? 0;?></div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800 sales_loan_section">
                        <i class="fa fa-star filled-str" aria-hidden="true"></i>
                        AVG. STARS
                    </div>
                </div>
            </div>
            <div class="clearfix small z-1 viewDetails text-danger projected_goal_section">
                RESPONSES = <span id="projected_open_section"><?Php echo count($rating);?></span>
            </div>
        </div>
    </div>
</div>
<div class="col-md-1 col-sm-12"></div>
    <?php //print_r($value);die;?>