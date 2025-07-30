<style>
.dataTables_length {
    width: 250px !important;
    float: left;
}
</style>
<div class="container-fluid">
    <!-- DataTables Example -->
    <div class="card mb-3">
        <div class="card-header">
            <i class="fas fa-table"></i>
            Escrow Instruction
            <div class="float-right">
                <a href="<?php echo base_url()?>order/admin/escrow-instruction-import" class="btn btn-secondary">Import</a>
                <a href="javascript:void(0);" data-export-type="csv" id="export-csv" class="btn btn-secondary">Export</a>
            </div>
        </div>
                
        <div class="card-body">
            <div id="escrow_instruction_success_msg" class="w-100 alert alert-success alert-dismissible" style="display:none;"></div>
            <div id="escrow_instruction_error_msg" class="w-100 alert alert-danger alert-dismissible" style="display:none;"></div>
            <div class="table-responsive">
                <table class="table table-bordered" id="tbl-escrow-instruction-listing" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <!-- <th>Customer Number</th> -->
                            <th>Escrow Instruction Name</th>
                            <th>Custom Field Value ID</th>
                            <th>Custom Field ID</th>
                            <th>Name</th>
                            <th>Value</th>
                        </tr>
                    </thead>                
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div><!-- /.container-fluid -->