<style>
.dataTables_length {
    width: 250px !important;
    float: left;
}
.fa-trash {
    color: #223D7F;
    text-decoration: none;
    background-color: transparent;
}
</style>
<div class="container-fluid">
    <div class="row mb-3">
		<div class="col-sm-6">
			<h1 class="h3 text-gray-800">Sales Rep </h1>
		</div>
		<div class="col-sm-6">
            <a href="<?php echo base_url('order/admin/add-underwriter-tier')?>" class="btn btn-success btn-icon-split float-right mr-2"> 
                <span class="icon text-white-50">
                    <i class="fas fa-plus"></i>
                </span>
                <span class="text"> Add Underwriter Tier </span> 
            </a>
		</div>
	</div>
    <!-- DataTables Example -->
    <div class="card shadow mb-4">
        <div class="card-header datatable-header py-3">
            <div class="datatable-header-titles" > 
                <span>
                    <i class="fas fa-users"></i>
                </span>
                <h6 class="m-0 font-weight-bold text-primary pl-10">Sales Rep</h6> 
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
                <table class="table table-bordered cusom__common__datatable" id="tbl-underwriter-tier-listing" width="100%" cellspacing="0">
                    <thead>
                        <tr>
							<th>Product Type</th>
                            <th>Underwriter</th>
                            <th>Title</th>
                            <th>Commission</th>
                            <th>Description</th>
                            <th>Action</th>
                        </tr>
						
                    </thead>                
                    <tbody>
					<?php foreach($underwriter_tier_details as $underwriter_tier_record): ?>
						<tr>
							<td><?php echo ucfirst($underwriter_tier_record->product_type); ?></td>
							<td><?php echo ucfirst($underwriter_tier_record->underwriter); ?></td>
							<td><?php echo $underwriter_tier_record->title; ?></td>
							<td><?php echo $underwriter_tier_record->commission; ?> %</td>
							<td><?php echo substr($underwriter_tier_record->description,0,60); ?></td>
							<td> <a href="<?php echo base_url('order/admin/edit-underwriter-tier/'.$underwriter_tier_record->id)?>" title ='Edit Underwriter Tier'><span class='fas fa-edit' aria-hidden='true'></span></a>
								<button type="button"  class='btn btn-action delete-record-custom' data-url="<?php echo base_url('order/admin/delete-underwriter-tier/'.$underwriter_tier_record->id)?>" title ='Delete Underwriter Tier'><span class='fas fa-trash' aria-hidden='true'></span></button>
							</td>
						</tr>
						<?php endforeach;?>
					</tbody>
                </table>
            </div>
        </div>
    </div>
</div>
