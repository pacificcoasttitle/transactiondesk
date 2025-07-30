
<style type="text/css">

table#orders_listing tr td:last-child {
    display: inline-flex;
}

.ui-autocomplete {
    max-height: 300px !important;
}

th {
    text-align: center;
}

.button-color {
    color: #888888;
}

td.dataTables_empty {
    display: table-cell !important;
}

.modal-dialog{
    overflow-y: initial !important
}

.modal-body{
    height: 700px;
    overflow-y: auto;
}

.square-box{
    background-color: #f0f0f0;
    width: 23% !important;
    margin-right: 2%;
    margin-bottom: 50px;
    padding-bottom: 35px;
    padding-top: 15px;
}

.order-count-cotainer {
    margin-top: 50px;
}

.title {
    text-align: center;
    color: #a0a0a0;
    width: 23% !important;
    margin-right: 2%;
    text-transform: uppercase;
    font-size: 15px;
    letter-spacing: 0px;
}

.sales_loan_count {
    font-size: 48px;
    color: #0D5772;
    text-align: center;
    font-weight: 800;
    letter-spacing: -1.00px;
    border-bottom: 1px solid #fff;
}

.sales_loan_section {
    text-align: center;
    text-transform: uppercase;
    font-size: 21px;
    line-height: 27px;
    color: #a0a0a0;
}

.salesdivider {
    border-bottom: 1px solid #fff;
    padding-top: 20px;
    padding-bottom: 20px;
}

.projected_goal_section {
    color: #d35411;
    /* font-weight: bold;*/
    text-align: center;
    text-transform: uppercase;
    font-size: large;
    line-height: 21px;
}

#orders_listing_filter {
    margin-bottom: 20px;
}

.percentage {
    font-weight: 500;
    font-size: 21px;
    color: #223D7F;
}

.fs-28 {
    font-size: 28px;
}

.fs-16 {
    font-size: 16px;
}
</style>
<!-- <section class="section-type-4a section-defaulta" style="padding-bottom:0px;"> -->
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="col-xs-12">
                <div class="typography-section__inner">
                    <h2 class="ui-title-block ui-title-block_light fs-28">Welcome Back <?php echo $name; ?>,</h2>
                    <div class="ui-decor-1a bg-accent"></div>
                    <h4 class="ui-title-block_light fs-16">Below is order list of pay off.</b></h3>
                </div>
                <?php if(!empty($success)) {?>
                <div id="agent_success_msg" class="w-100 alert alert-success alert-dismissible">
                    <?php foreach($success as $sucess) {
                            echo $sucess."<br \>";	
                        }?>
                </div>
                <?php } 
                    if(!empty($errors)) {?>
                <div id="agent_error_msg" class="w-100 alert alert-danger alert-dismissible">
                    <?php foreach($errors as $error) {
                            echo $error."<br \>";	
                        }?>
                </div>
                <?php } ?>
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0" id="escrow_orders_listing">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>File Number</th>
                                        <th>Property Address</th>
                                        <th>Product Type</th>
                                        <th>Created At</th>
                                        <th>Completed %</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                            <div class="typography-sectionab"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</section>

