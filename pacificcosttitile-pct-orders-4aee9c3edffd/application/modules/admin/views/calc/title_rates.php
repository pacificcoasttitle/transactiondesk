<div class="container-fluid">
    <div class="card mb-3">
        <div class="card-header">
            <i class="fas fa-table"></i>
            Title Rates
            <div class="float-right">
                <a href="<?php echo base_url(); ?>calculator/admin/import_title_rates" class="btn btn-secondary"> Import </a>
                <a href="javascript:void(0);" data-export-type="csv" id="export-csv" class="btn btn-secondary"> Export </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="tbl-title-rates" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#ID</th>
                            <th>Min Range</th>
                            <th>Max Range</th>
                            <th>Owner Rate</th>
                            <th>Home Owner Rate</th>
                            <th>Con Loan Rate</th>
                            <th>Resi Loan Rate</th>
                            <th>Con Full Loan Rate</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            if(!empty($rates)){ foreach($rates as $row){
                        ?>
                            <tr>
                               <td><?php echo $row['title_rate_id_pk']; ?></td>
                               <td><?php echo "$".number_format($row['min_range']); ?></td>
                               <td><?php echo "$".number_format($row['max_range']); ?></td>
                               <td><?php echo "$".number_format($row['owner_rate']); ?></td>
                               <td><?php echo "$".number_format($row['home_owner_rate']); ?></td>
                               <td><?php echo "$".number_format($row['con_loan_rate']); ?></td>
                               <td><?php echo "$".number_format($row['resi_loan_rate']); ?></td>
                               <td><?php echo "$".number_format($row['con_full_loan_rate']); ?></td>
                               <td>
                                <a href="<?php echo base_url(); ?>calculator/admin/edit_title_rates/<?php echo $row['title_rate_id_pk']; ?>"><i class="fas fa-edit" aria-hidden="true"></i></a>
                                <a href="javascript:void(0);" onclick="delete_rates(<?php echo $row['title_rate_id_pk']; ?>);" class="grey button small2"><i class="fas fa-trash" aria-hidden="true"></i></a>
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
          url     : "<?php echo base_url(); ?>admin/calc/admin/delete_title_rates",
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