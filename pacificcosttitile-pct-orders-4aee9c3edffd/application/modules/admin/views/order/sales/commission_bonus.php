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
			<h1 class="h3 text-gray-800">Bonus Ranges </h1>
		</div>
		<div class="col-sm-6">
			<a href="<?php echo base_url('order/admin/add-commission-bonus') ?>"  class="btn btn-success btn-icon-split float-right mr-2">
                <span class="icon text-white-50">
                    <i class="fas fa-plus"></i>
                </span>
                <span class="text"> Add Bonus </span>
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
                <h6 class="m-0 font-weight-bold text-primary pl-10">Bonus Ranges</h6>
            </div>
        </div>
        <div class="card-body">
			<?php if (!empty($success_msg)) {?>
				<div class="col-xs-12">
					<div class="alert alert-success"><?php echo $success_msg; ?></div>
				</div>
			<?php }?>
			<?php if (!empty($error_msg)) {?>
				<div class="col-xs-12">
					<div class="alert alert-danger"><?php echo $error_msg; ?></div>
				</div>
			<?php }?>
            <div class="table-responsive">
                <table class="table table-bordered" id="tbl-commission-bonus-listing" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>From Revenue</th>
                            <th>Bonus Amount</th>
                            <th>Action</th>
                        </tr>

                    </thead>
                    <tbody>
					<?php foreach ($bonus_details as $disp_key => $commission_record): ?>
						<tr>

							<td><?php echo ($disp_key + 1); ?></td>
							<td>$ <?php echo $commission_record->min_range; ?></td>
							<td>$ <?php echo $commission_record->bonus_amount; ?></td>
							<td> <a href="<?php echo base_url('order/admin/edit-commission-bonus/' . $commission_record->id) ?>" title ='Edit Bonus'><span class='fas fa-edit' aria-hidden='true'></span></a>
								<button type="button"  class='btn btn-action delete-record-custom' data-url="<?php echo base_url('order/admin/delete-commission-bonus/' . $commission_record->id) ?>" title ='Delete Bonus'><span class='fas fa-trash' aria-hidden='true'></span></button>
							</td>
						</tr>
						<?php endforeach;?>
					</tbody>
                </table>
            </div>
        </div>
    </div>
</div>
