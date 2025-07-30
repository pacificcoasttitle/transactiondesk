<style>
.dataTables_length {
    width: 250px !important;
    float: left;
}
.bootstrap-select {
	width: 200px !important;
}
</style>
<!-- <div class="container-fluid">
    <div class="card mb-3">
        <div class="card-header">
            <i class="fas fa-table"></i>
            Commission Ranges
			<div class="row">
				<div class="col-sm-6 ">
					<div class="form-group row">
					
						<div class="col-sm-5">
							<select name="product_type" data-style="btn-secondary"  class="selectpicker show_hide_underwriter_tier_select" data-actions-box="true" id="filter__commission_range_type">
								<option value="all">Product Type - All</option>
								<?php foreach($product_types as $product_type) {?>
									<?php $selected = '';
										if($filter_product == $product_type)  {
											$selected = 'selected';
										} 
									?> 
									<option <?php echo $selected;?> value="<?php echo $product_type;?>"><?php echo ucwords($product_type);?></option>
								<?php }?>
							</select>
							<?php if(!empty(form_error('product_type'))){ ?>                     
								<span class="error"><?php echo form_error('product_type'); ?></span>
							<?php } ?>
						</div>
						<div class="col-sm-7">
							<div class="show_hide_underwriter_tier show_hide_underwriter_tier-all" >
								<select data-style="btn-secondary"   class="selectpicker filter__commission_range_tier" data-actions-box="true" data-url="<?php echo base_url('order/admin/commission-range') ?>">
								<option value="0">Underwriter Tier - All</option>
								</select>
							</div>
							<?php foreach($underwriter_tiers as $underwriter_tier_key=>$underwriter_tier_obj) :?>
								<div class="show_hide_underwriter_tier show_hide_underwriter_tier-<?php echo $underwriter_tier_key; ?>" >
									<select data-style="btn-secondary"  name="underwriter_tier[<?php echo $underwriter_tier_key; ?>]"  class="selectpicker filter__commission_range_tier" data-actions-box="true"  data-url="<?php echo base_url('order/admin/commission-range') ?>">
										<option value="0">Underwriter Tier - All</option>
										<?php $last_label = ''; ?>
										<?php foreach($underwriter_tier_obj as $underwriter_tier) {?>
											<?php
											if ($last_label != $underwriter_tier->underwriter) : ?>
												<?php if($last_label != '') : ?>
													</optgroup>
												<?php endif; ?>
												<optgroup label="<?php echo ucwords($underwriter_tier->underwriter); ?>" class="opt_group_<?php echo $underwriter_tier->product_type; ?>">
												<?php 
												endif;
												$last_label = $underwriter_tier->underwriter; 
												$selected = '';
												if($filter_underwriter == $underwriter_tier->id)  {
													$selected = 'selected';
												} 
											?> 
											<option <?php echo $selected;?> value="<?php echo $underwriter_tier->id;?>"><?php echo $underwriter_tier->title;?></option>
											
										<?php }?>
										<?php if($last_label != '') : ?>
											</optgroup>
										<?php endif; ?>
									</select>
								</div>
								<?php if(!empty(form_error('underwriter_tier['.$underwriter_tier_key.']'))){ ?>                     
									<span class="error"><?php echo form_error('underwriter_tier['.$underwriter_tier_key.']'); ?></span>
								<?php } ?>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
			</div>
        </div> -->
<div class="container-fluid">
    <div class="row mb-3">
		<div class="col-sm-4">
			<h1 class="h3 text-gray-800">Commission Ranges </h1>
		</div>
		<div class="col-sm-4 ">
			<div class="form-group row">
			
				<div class="col-sm-6">
					<select name="product_type" data-style="btn-secondary"  class="selectpicker show_hide_underwriter_tier_select" data-actions-box="true" id="filter__commission_range_type">
						<option value="all">Product Type - All</option>
						<?php foreach($product_types as $product_type) {?>
							<?php $selected = '';
								if($filter_product == $product_type)  {
									$selected = 'selected';
								} 
							?> 
							<option <?php echo $selected;?> value="<?php echo $product_type;?>"><?php echo ucwords($product_type);?></option>
						<?php }?>
					</select>
					<?php if(!empty(form_error('product_type'))){ ?>                     
						<span class="error"><?php echo form_error('product_type'); ?></span>
					<?php } ?>
				</div>
				<div class="col-sm-6">
					<div class="show_hide_underwriter_tier show_hide_underwriter_tier-all" >
						<select data-style="btn-secondary"   class="selectpicker filter__commission_range_tier" data-actions-box="true" data-url="<?php echo base_url('order/admin/commission-range') ?>">
						<option value="0">Underwriter Tier - All</option>
						</select>
					</div>
					<?php foreach($underwriter_tiers as $underwriter_tier_key=>$underwriter_tier_obj) :?>
						<div class="show_hide_underwriter_tier show_hide_underwriter_tier-<?php echo $underwriter_tier_key; ?>" >
							<select data-style="btn-secondary"  name="underwriter_tier[<?php echo $underwriter_tier_key; ?>]"  class="selectpicker filter__commission_range_tier" data-actions-box="true"  data-url="<?php echo base_url('order/admin/commission-range') ?>">
								<option value="0">Underwriter Tier - All</option>
								<?php $last_label = ''; ?>
								<?php foreach($underwriter_tier_obj as $underwriter_tier) {?>
									<?php
									if ($last_label != $underwriter_tier->underwriter) : ?>
										<?php if($last_label != '') : ?>
											</optgroup>
										<?php endif; ?>
										<optgroup label="<?php echo ucwords($underwriter_tier->underwriter); ?>" class="opt_group_<?php echo $underwriter_tier->product_type; ?>">
										<?php 
										endif;
										$last_label = $underwriter_tier->underwriter; 
										$selected = '';
										if($filter_underwriter == $underwriter_tier->id)  {
											$selected = 'selected';
										} 
									?> 
									<option <?php echo $selected;?> value="<?php echo $underwriter_tier->id;?>"><?php echo $underwriter_tier->title;?></option>
									
								<?php }?>
								<?php if($last_label != '') : ?>
									</optgroup>
								<?php endif; ?>
							</select>
						</div>
						<?php if(!empty(form_error('underwriter_tier['.$underwriter_tier_key.']'))){ ?>                     
							<span class="error"><?php echo form_error('underwriter_tier['.$underwriter_tier_key.']'); ?></span>
						<?php } ?>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
		<div class="col-sm-4">
			<a href="<?php echo base_url('order/admin/add-commission-range')?>" class="btn btn-success btn-icon-split float-right mr-2"> 
                <span class="icon text-white-50">
                    <i class="fas fa-plus"></i>
                </span>
                <span class="text"> Add </span> 
            </a>
			<a href="<?php echo base_url('order/admin/import-commission-range')?>"  class="btn btn-success btn-icon-split float-right mr-2"> 
                <span class="icon text-white-50">
                    <i class="fas fa-file-import"></i>
                </span>
                <span class="text"> Import </span> 
            </a>
            <a href="<?php echo base_url('order/admin/export-commission-range')?>" class="btn btn-success btn-icon-split float-right mr-2"> 
                <span class="icon text-white-50">
                    <i class="fas fa-file-export"></i>
                </span>
                <span class="text"> Export </span> 
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
                <h6 class="m-0 font-weight-bold text-primary pl-10">Commission Ranges</h6> 
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
                <table class="table table-bordered cusom__common__datatable" id="tbl-commission-range-listing" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Product Type</th>
                            <th>Underwriter Tier</th>
                            <th>Minimum Revenue</th>
                            <th>Maximum Revenue</th>
                            <th>Premium</th>
                            <th>Total Commission</th>
                            <th>Action</th>
                        </tr>
						
                    </thead>                
                    <tbody>
					<?php foreach($commission_details as $commission_record): ?>
						<tr>
							<td><?php echo ucfirst($commission_record->product_type); ?></td>
							<td><?php echo ($commission_record->underwriter_tier_obj) ? $commission_record->underwriter_tier_obj->title : '-' ?></td>
							<td><?php echo $commission_record->min_revenue; ?></td>
							<td><?php echo $commission_record->max_revenue; ?></td>
							<td><?php echo $commission_record->premium; ?></td>
							<td><?php echo ($commission_record->underwriter_tier_obj) ? $commission_record->underwriter_tier_obj->commission : '0.00' ?> %</td>
							<td> <a href="<?php echo base_url('order/admin/edit-commission-range/'.$commission_record->id)?>" class='btn btn-action 'title ='Edit Commission Range'><span class='fas fa-edit' aria-hidden='true'></span></a>
								<button type="button"  class='btn btn-action delete-record-custom' data-url="<?php echo base_url('order/admin/delete-commission-range/'.$commission_record->id)?>" title ='Delete Commission Range'><span class='fas fa-trash' aria-hidden='true'></span></button>
							</td>
						</tr>
						<?php endforeach;?>
					</tbody>
                </table>
            </div>
        </div>
    </div>
</div>
