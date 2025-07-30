<style>
    .dataTables_length {
        width: 250px !important;
        float: left;
    }
    .FilterOrderListing {
        width: 100%;
        display: flex;
    }
    .form-footer {
        color: #fff;
        display: flex;
        justify-content: flex-start;
    }

    .form-footer .btn {
        color: #fff;
    }
    .dropdown-menu {
        margin-top: 0px !important;
    }
    .center-align {
        display: flex;
        justify-content: center;
    }
    .modal-footer .btn {
        padding: 5px 10px;
    }

    .filled-str {
        color: #e6e63a
    }

</style>
<!-- <section class="section-type-4a section-defaulta container-fluid" style="padding-bottom:0px;"> -->
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="col-xs-12">
                
                <div class="typography-section__inner">
                    <div class="row">
                        <div class="col-sm-12">
                            <h2 class="ui-title-block ui-title-block_light fs-28">View Survey Results</h2>
                            <div class="ui-decor-1a bg-accent"></div>
                            <div class="sales-user-listing mb-4">
                                <h4 class="ui-title-block_light fs-16">Below are the results for the following unit: </h4>
                                <div id="sales_user_listing">
                                    <label>
                                        <select style="width:auto;" name="title_officer_list" id="title_officer_list" class="custom-select custom-select-sm form-control form-control-sm">
                                            <?php 
                                            if (!empty($title_officer_list)) {
                                            foreach ($title_officer_list as $key => $value) { ?>
                                            <option value="<?php echo $value['id']; ?>"><?php echo $value['title']; ?></option>
                                            <?php }}?>
                                        </select>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-sm-12">
                        <div class="row mb-2" >
                            <div class="col-md-1 col-sm-12 title text-primary center-align"></div>
                            <div class="col-md-2 col-sm-12 title text-primary center-align">SERVICE SATISFACTION</div>
                            <div class="col-md-2 col-sm-12 title text-success center-align">TRANSACTION EXPERIENCE</div>
                            <div class="col-md-2 col-sm-12 title text-info center-align">COMMUNICATION</div>
                            <div class="col-md-2 col-sm-12 title text-warning center-align">HELPFULNESS</div>
                            <div class="col-md-2 col-sm-12 title text-danger center-align">REFER</div>
                            <div class="col-md-1 col-sm-12 title center-align"></div>
                        </div>
                        <?php 
                        //if (!empty($survey)) {
                        //foreach ($survey as $key => $value) { 
                        ?>
                        <div class="row survey-cards">
                        <?php echo $survey_cards; ?>
                        </div>
                        <!-- <div class="row  switchCls <?php echo $value['id']; ?>">
                            <div class="col-md-1 col-sm-12"></div>
                            <div class="col-md-2 col-sm-12">
                                <div class="card border-left-primary shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1 sales_loan_count" id="open_order_count"><?Php echo $value['avg']['Q1'];?></div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800 sales_loan_section">AVG. STARS</div>
                                            </div>
                                        </div>
                                        <div class="clearfix small z-1 viewDetails text-primary projected_goal_section">
                                            RESPONSES = <span id="projected_open_section"><?Php echo count($value['rating']);?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-12">
                                <div class="card border-left-success shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1 sales_loan_count" id="open_order_count"><?Php echo $value['avg']['Q2'];?></div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800 sales_loan_section">AVG. STARS</div>
                                            </div>
                                        </div>
                                        <div class="clearfix small z-1 viewDetails text-success projected_goal_section">
                                            RESPONSES = <span id="projected_open_section"><?Php echo count($value['rating']);?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-12">
                                <div class="card border-left-info shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1 sales_loan_count" id="open_order_count"><?Php echo $value['avg']['Q3'];?></div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800 sales_loan_section">AVG. STARS</div>
                                            </div>
                                        </div>
                                        <div class="clearfix small z-1 viewDetails text-info projected_goal_section">
                                            RESPONSES = <span id="projected_open_section"><?Php echo count($value['rating']);?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-12">
                                <div class="card border-left-warning shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1 sales_loan_count" id="open_order_count"><?Php echo $value['avg']['Q4'];?></div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800 sales_loan_section">AVG. STARS</div>
                                            </div>
                                        </div>
                                        <div class="clearfix small z-1 viewDetails text-warning projected_goal_section">
                                            RESPONSES = <span id="projected_open_section"><?Php echo count($value['rating']);?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-12">
                                <div class="card border-left-danger shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1 sales_loan_count" id="open_order_count"><?Php echo $value['avg']['Q5'];?></div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800 sales_loan_section">AVG. STARS</div>
                                            </div>
                                        </div>
                                        <div class="clearfix small z-1 viewDetails text-danger projected_goal_section">
                                            RESPONSES = <span id="projected_open_section"><?Php echo count($value['rating']);?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-1 col-sm-12"></div>

                        </div> -->
                        <?php //}}?>
                    </div>
                </div>
                <div class="order-count-cotainer">
                    <h4 class="ui-title-block_light">Below is list of all survey response.</h3>
                </div>	
                
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <div class="table-responsive survey-table">
                            <?php echo $survey_rating_details; ?>
                            <!-- <table class="table table-bordered" id="tbl-surveys-listing" width="100%" cellspacing="0">
                                <thead>
                                    <tr align="center">
                                        <th width="10%">Recipiente</th>
                                        <th width="25%">Sales rep</th>
                                        <th width="9%">Service</th>
                                        <th width="9%">Experience</th>
                                        <th width="9%">Comminication</th>
                                        <th width="9%">Helpful</th>
                                        <th width="9%">Refer</th>
                                        <th width="20%">Action</th>
                                    </tr>
                                </thead>
                                <?php 
                                if (!empty($survey)) {
                                foreach ($survey as $key => $value) { ?>              
                                <tbody class=" switchCls <?php echo $value['id']; ?>" >
                                    <?php 
                                    if (!empty($value['rating'])) {
                                    foreach ($value['rating'] as $k => $val) { ?>
                                        <tr align="center">
                                            <td><?php echo $value['title']; ?></td>
                                            <td><?php echo $val['sales_rep']; ?></td>
                                            <td><?php echo ($val['Q1']); ?></td>
                                            <td><?php echo ($val['Q2']); ?></td>
                                            <td><?php echo ($val['Q3']); ?></td>
                                            <td><?php echo ($val['Q4']); ?></td>
                                            <td><?php echo ($val['Q5']); ?></td>
                                            <td>
                                                <div class="dropdown">
                                                    <a class="btn dropdown-toggle click-action-type" type="button" data-toggle="dropdown" href="#">Click Action Type
                                                        <span class="caret"></span>
                                                    </a>
                                                    <ul class="dropdown-menu" style="width:210px !important;max-width:none !important; margin-top: 0px;">
                                                        <li>
                                                            <a href="javascript:void(0)" onclick='displayComment(<?php echo $val["comment"]; ?>);' title="View Comment">
                                                                <button class="btn btn-grad-2a button-color" type="button">
                                                                    <i class="fas fa-eye" aria-hidden="true" style="margin-right:5px;"></i>
                                                                    View Comment
                                                                </button>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php } } else { ?>
                                        <tr>
                                            <td colspan="8" class="text-center">No record found</td>
                                        </tr>
                                    <?php }?>

                                </tbody>
                                <?php }}?>
                            </table> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- </section> -->
<div class="modal fade" id="send_sample_email" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document" style="width: 40%;">
        <div class="modal-content">
            <!-- <form method="post" id="instrument-file-upload-form" name="instrument-file-upload-form" enctype="multipart/form-data" action="<?php echo base_url(); ?>order/admin/change-client"> -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card shadow">
                            <div class="card-header py-3">
                                <div class="w-100 alert alert-danger alert-dismissible surveys_error_msg" style="display:none;"></div>
                                <h6 class="m-0 font-weight-bold text-primary">Send Sample Email</h6>
                            </div>
                            <div class="card-body">
                                <div class="smart-forms smart-container">
                                    <div class="modal-body search-result">

                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <label for="email_address" class="col-form-label">Email Address</label>
                                                    <input name="email_address" required="" type="text"
                                                        class="form-control" id="email_address">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-footer" style="padding: 0px 1rem !important;">
                                        <button type="button" id="send_sample_mail_btn" data-btntext-sending="Sending..." class="btn btn-success btn-icon-split btn-sm">
                                            <span class="icon text-white-50">
                                                <i class="fas fa-check"></i>
                                            </span>
                                            <span class="text">Send</span>
                                        </button>
                                        <button type="reset" data-dismiss="modal" aria-label="Close" class="btn btn-danger btn-icon-split btn-sm">
                                            <span class="icon text-white-50">
                                                <i class="fas fa-ban"></i>
                                            </span>
                                            <span class="text">Cancel</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <!-- </form> -->
        </div>
    </div>
</div>

<div class="modal fade" id="commentModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document" style="width: 40%;">
        <div class="modal-content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card shadow">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Comments List</h6>
                        </div>
                        <div class="card-body">
                            <div class="smart-forms smart-container">
                                <ul id="commentList" class="list-group">
                                    <!-- List items will be added dynamically here -->
                                </ul>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="reset" data-dismiss="modal" aria-label="Close" class="btn btn-secondary btn-icon-split btn-sm">
                                <span class="text">Close</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    
</script>
