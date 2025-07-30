<div class="container-fluid">
    <div class="card mb-3">
        <div class="card-header">
            <i class="fas fa-table"></i>
            Resale Rates
            <div class="float-right">
                <a href="<?php echo base_url(); ?>calculator/admin/add_resale_rates" class="btn btn-secondary"> Add Rates </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="tbl-escrow-resale-rates" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>County</th>
                            <th>Min Range</th>
                            <th>Max Range</th>
                            <th>Base Amount</th>
                            <th>Per thousand Price</th>
                            <th>Base Rate</th>
                            <th>Minimum Rate</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            if(!empty($rates)){ foreach($rates as $row){
                                $max = isset($row['max_range']) && !empty($row['max_range']) ? "$".number_format($row['max_range']) : '*';
                                $base_amount = isset($row['base_amount']) && !empty($row['base_amount']) ? "$".number_format($row['base_amount']) : '-';
                                $per_thousand_price = isset($row['per_thousand_price']) && !empty($row['per_thousand_price']) ? "$".number_format($row['per_thousand_price']) : '-';
                                $base_rate = isset($row['base_rate']) && !empty($row['base_rate']) ? "$".number_format($row['base_rate']) : '-';
                                $minimum_rate = isset($row['minimum_rate']) && !empty($row['minimum_rate']) ? "$".number_format($row['minimum_rate']) : '-';
                        ?>
                          <tr>
                              <td><?php echo $row['escrow_resale_id_pk']; ?></td>
                              <td><?php echo $row['county']; ?></td>
                              <td><?php echo "$".number_format($row['min_range']); ?></td>
                              <td><?php echo $max; ?></td>
                              <td><?php echo $base_amount; ?></td>
                              <td><?php echo $per_thousand_price; ?></td>
                              <td><?php echo $base_rate; ?></td>
                              <td><?php echo $minimum_rate; ?></td>
                              
                              <td>
                                <a href="<?php echo base_url(); ?>calculator/admin/edit_resale_rates/<?php echo $row['escrow_resale_id_pk']; ?>"><i class="fas fa-edit" aria-hidden="true"></i></a>
                                <a href="javascript:void(0);" onclick="delete_rates(<?php echo $row['escrow_resale_id_pk']; ?>);" class="grey button small2"><i class="fas fa-trash" aria-hidden="true"></i></a>
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
    function delete_rates(id) 
    {
        $.ajax({
          url     : "<?php echo base_url(); ?>admin/calc/admin/delete_resale_rates",
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