<div class="container-fluid">
    <!-- DataTables Example -->
    <div class="row mb-3">
		<div class="col-sm-6">
			<h1 class="h3 text-gray-800">Code Book</h1>
		</div>
		<div class="col-sm-6">
            <a href="<?php echo base_url(); ?>order/admin/add-code-book" class="btn btn-success btn-icon-split float-right mr-2" style="display: none;"> 
                <span class="icon text-white-50"><i class="fas fa-plus"></i></span><span class="text">Add</span> </a>
            <a href="<?php echo base_url(); ?>order/admin/import-code-book" class="btn btn-success btn-icon-split float-right mr-2"> 
                <span class="icon text-white-50"><i class="fas fa-file-import"></i></span><span class="text">Import</span> </a>
		</div>
	</div>
    <div class="card shadow mb-4">
        <div class="card-header datatable-header py-3">
            <div class="datatable-header-titles" > 
                <span>
                    <i class="fas fa-table"></i>
                </span>
                <h6 class="m-0 font-weight-bold text-primary pl-10">Code Book</h6> 
            </div>
        </div>
        <div class="card-body">
            <div id="code_book_success_msg" class="w-100 alert alert-success alert-dismissible" style="display:none;"></div>
            <div id="code_book_error_msg" class="w-100 alert alert-danger alert-dismissible" style="display:none;"></div>
            <div class="table-responsive">
                <table class="table table-bordered" id="tbl-code-book" style="table-layout: fixed;" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th style="width: 7%;">Sr No</th>
                            <th style="width: 10%;">Code</th>
                            <th style="width: 10%;">Type Id</th>
                            <th style="width: 12%;">Type</th>
                            <th style="width: 40%;">Language</th>
                            <th style="width: 10%;">Required Number</th>
                            <th style="width: 10%;">Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>