<!-- pagetitle start here -->
<div class="section-title-page7f area-bg area-bg_blue area-bg_op_60 parallax">
   <div class="area-bg__inner">
      <div class="container">
         <div class="row">
            <div class="col-xs-12">
               <h1 class="b-title-page">Lender Portal</h1>
               <div class="b-title-page__info">get your rate instantly</div>
               <!-- end breadcrumb-->
            </div>
         </div>
      </div>
   </div>
</div>
<!-- pagetitle end here -->
<div class="wrapper">
  <?php $this->load->view('front/sidebar'); ?>
  <section class="content-wrapper">
     <div class="row">
        
      <h2>Escrow Refinance Rate</h2>
     
      <!-- Display status message -->
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
     
      <!-- <div class="row"> -->
          <!-- Import link -->
          <div class="col-md-12 head">
              <div class="float-right">
                  <a href="javascript:void(0);" class="btn btn-success" onclick="formToggle('importFrm');"><i class="plus"></i> Import</a>
              </div>
          </div>
        
          <!-- File upload form -->
          <div class="col-md-12" id="importFrm" style="display: none;">
              <form action="<?php echo base_url(); ?>index.php?admin/import" method="post" enctype="multipart/form-data">
                  <div class="col-md-6"><input type="file" name="file" /></div>
                  <div class="float-right"><input type="submit" class="btn btn-primary" name="importSubmit" value="IMPORT"></div>
              </form>
          </div>
          
          <!-- Data list table -->
          <table class="table table-striped table-bordered" id="tbl-escrow-refinance-rates">
              <thead class="thead-dark">
                  <tr>
                      <th>#ID</th>
                      <th>Min Range</th>
                      <th>Max Range</th>
                      <th>Rate</th>
                  </tr>
              </thead>
              <tbody>
                  <?php
                    if(!empty($rates)){ foreach($rates as $row){ ?>
                  <tr>
                      <td><?php echo $row['escrow_ref_id_pk']; ?></td>
                      <td><?php echo "$".number_format($row['min_range']); ?></td>
                      <td><?php echo "$".number_format($row['max_range']); ?></td>
                      <td><?php echo "$".number_format($row['escrow_rate']); ?></td>
                  </tr>
                  <?php } }else{ 
                   ?>
                  <tr><td colspan="4">No rate(s) deatils found...</td></tr>
                  <?php } ?>
              </tbody>
          </table>
      <!-- </div> -->



     </div>
  </section>
</div>
<script>

$( document ).ready(function() {
   var error = "<?php echo $error_msg; ?>";

   if(error)
   {
      $('#importFrm').css('display','block');
   }

   $('.alert').delay(5000).fadeOut(300);

    if($('#tbl-escrow-refinance-rates').length)
    {
        $('#tbl-escrow-refinance-rates').DataTable({
           "pageLength": 20,
           "lengthChange": false,
           "searching": false,
           "language": {
              paginate: {
                next: '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                previous: '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
            }
          },
          "ordering": false,
        });
    }
    
});
</script>