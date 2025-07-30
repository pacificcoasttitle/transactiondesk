<style>
.dataTables_length {
    width: 250px !important;
    float: left;
}
.FilterOrderListing {
    width: 100%;
    display: flex;
}
</style>
<div class="container-fluid">
    <?php if(!empty($this->session->userdata('success'))){ ?>
        <div class="col-xs-12">
            <div class="alert alert-success"><?php echo $this->session->userdata('success'); ?></div>
        </div>
    <?php } ?>

    <?php if(!empty($error_msg)){ ?>
        <div class="col-xs-12">
            <div class="alert alert-danger"><?php echo $error_msg; ?></div>
        </div>
    <?php } ?>
    <div id="lp_order_success_msg" class="w-100 alert alert-success alert-dismissible" style="display:none;"></div>
    <div id="lp_order_error_msg" class="w-100 alert alert-danger alert-dismissible" style="display:none;"></div>
    <!-- DataTables Example -->
    <div class="row mb-3">
        <div class="col-sm-6">
            <h1 class="h3 text-gray-800">LP Document Types</h1>
        </div>
        <div class="col-sm-6">
            <a href="<?php echo base_url()?>order/admin/import-lp-document-types" class="btn btn-success btn-icon-split float-right mr-2"> 
                <span class="icon text-white-50"><i class="fas fa-file-import"></i></span><span class="text">Import</span> </a>
            <a href="<?php echo base_url()?>order/admin/add-lp-document-types" class="btn btn-success btn-icon-split float-right mr-2"> 
                <span class="icon text-white-50"><i class="fas fa-plus"></i></span><span class="text">Add LP Document Type</span> </a>
        </div>
    </div>
    <div class="card shadow mb-4">
        <div class="card-header datatable-header py-3">
            <div class="datatable-header-titles" > 
                <span>
                    <i class="fas fa-table"></i>
                </span>
                <h6 class="m-0 font-weight-bold text-primary pl-10">LP Document Types</h6> 
            </div>
        </div>
                
        <div class="card-body">
            <div id="lp_document_types_success_msg" class="w-100 alert alert-success alert-dismissible" style="display:none;"></div>
            <div id="lp_document_types_error_msg" class="w-100 alert alert-danger alert-dismissible" style="display:none;"></div>
            <div class="table-responsive">
                <table class="table table-bordered" id="tbl-lp-document-types-listing" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Sr No</th>
                            <th>Code</th>
                            <th>Instrument Type</th>
                            <th>Is Subtype</th>
                            <th>Selected Subtype Code</th>
                            <th>Select Section</th>
                            <th>Is Display</th>
                            <th>Is Ves</th>
                            <th>Action</th>
                        </tr>
                    </thead>                
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>