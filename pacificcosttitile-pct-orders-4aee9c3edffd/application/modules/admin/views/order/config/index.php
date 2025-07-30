<style>
.dataTables_length {
    width: 250px !important;
    float: left;
}
</style>

<div class="container-fluid">
    <div class="row mb-3">
		<div class="col-sm-6">
			<h1 class="h3 text-gray-800">Commisison Configuration </h1>
		</div>
	</div>
    <!-- DataTables Example -->
    <div class="card shadow mb-4">
        <div class="card-header datatable-header py-3">
            <div class="datatable-header-titles" > 
                <span>
                    <i class="fas fa-users"></i>
                </span>
                <h6 class="m-0 font-weight-bold text-primary pl-10">Commisison Configuration</h6> 
            </div>
        </div>    
        <div class="card-body">
            <?php if(!empty($success_msg)){ ?>
                <div class="col-xs-12">
                    <div class="alert alert-success"><?php echo $success_msg; ?></div>
                </div>
            <?php } ?>
            <?php if(!empty($error_msg)){ ?>
                <div class="col-xs-12">
                    <div class="alert alert-danger"><?php echo $error_msg; ?></div>
                </div>
            <?php } ?>
                <div class="table-responsive">
                    <table class="table table-bordered cusom__common__datatable" id="tbl-commission-config-listing" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Value</th>
                                <th>Action</th>
                            </tr>
                            
                        </thead>                
                        <tbody>
                        <?php foreach($config_details as $key=>$config_record): ?>
                            <tr>
                                <td><?=($key+1)?></td>
                                <td><?=ucfirst($config_record->title)?></td>
                                <td><?=($config_record->value)?>%</td>
                                <td><a href="<?php echo base_url('order/admin/edit-commission-config/'.$config_record->id)?>" class='btn btn-action 'title ='Edit Record'><span class='fas fa-edit' aria-hidden='true'></span></a></td>
                            </tr>
                            <?php endforeach;?>
                        </tbody>
                    </table>
                </div>
            </div>
    </div>
</div>
