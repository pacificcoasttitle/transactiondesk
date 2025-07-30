<div class="container-fluid">
    <div class="card mb-3">
        <div class="card-header">
            <i class="fas fa-table"></i>
            Fees
            <div class="float-right">
                <a href="<?php echo base_url(); ?>calculator/admin/add_fees" class="btn btn-secondary"> Add Fees </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="tbl-fees" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#ID</th>
                            <th>Transaction Type</th>
                            <th>Section</th>
                            <th>Name</th>
                            <th>Value</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            if(!empty($fees)){ foreach($fees as $row){ 
                                $txn_type = (isset($row['transaction_type']) && $row['transaction_type'] == 'resale') ? "Purchase" : "Refinance";
                        ?>
                            <tr id="fees_<?php echo $row['id'];?>">
                               <td class="fee_id"><?php echo $row['id']; ?></td>
                               <td class="txn_type"><?php echo $txn_type; ?></td>
                               <td class="fee_section"><?php echo $row['parent_name']; ?></td>
                               <td class="fee_name"><?php echo $row['name']; ?></td>
                               <td class="fee_value"><?php echo "$".number_format($row['value']); ?></td>
                               <td>
                                   <a href="<?php echo base_url(); ?>calculator/admin/edit_fees/<?php echo $row['id']; ?>"><i class="fas fa-edit " aria-hidden="true"></i></a>
                                   <a href="javascript:void(0);" onclick="delete_fees(<?php echo $row['id']; ?>);" class="grey button small2"><i class="fas fa-trash" aria-hidden="true"></i></a>
                                </td>
                            </tr>
                          <?php } } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function delete_fees(id)
    {
        $.ajax({
          url     : "<?php echo base_url(); ?>admin/calc/admin/delete_fees",
          type    : "POST",
          data    : {id:id},
          success : function( data )
          {
            var result = jQuery.parseJSON( data );

            if(result.status == 'success')
            {
                alert(result.message);
                location.reload();
            }
          },
          error   : function( xhr, err )
          {
            alert('Connection Problem !!');
            return false;
          }
      });
    }
</script>