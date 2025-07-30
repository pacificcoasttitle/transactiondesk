<style type="text/css" media="screen">
   .alert-danger {
   background-color: #f2dede;
   border-color: #ebccd1;
   color: #a94442;
   }
   .alert-success {
   background-color: #dff0d8;
   border-color: #d6e9c6;
   color: #3c763d;
   }
</style>

<!-- content section start here -->
<!-- bottom content start here -->

<div class="container-fluid">
    <div class="card mb-3">
        <div class="card-header">
            <i class="fas fa-table"></i>
            Users
        </div>
        <div class="card-body">
            <div id="output" style="display:none;">
               <div id="output_div" class="alert alert-danger">
                  <span class = "text-danger" id="output_body"></span>
               </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered" id="tbl-users-listing" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Full Name</th>
                            <th>Email Id</th>
                            <th>Rep Name</th>
                            <th>Rate Schedule</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            if(!empty($users)){ foreach($users as $user){ ?>
                          <tr>
                              <td><?php echo $user->first_name." ". $user->last_name; ?></td>
                              <td><?php echo $user->email; ?></td>
                              <td><?php echo $user->rep_key; ?></td>
                              <td>
                                <form action="<?php echo base_url(); ?>calculator/admin_dashboard_submit" method="post" accept-charset="utf-8" id="user_role_<?php echo $user->user_id_pk; ?>">
                                  <select type="text" placeholder="Select Rep" name="membership_id_fk" class="sarinput">
                                     <option value="">Select Membership</option>
                                     <?php foreach ($roles as $role): ?>
                                     <option value="<?php echo $role->membership_id_pk; ?>" <?php echo ($role->membership_id_pk == $user->membership_id_fk)?'selected="true"':NULL; ?>><?php echo $role->membership; ?></option>
                                     <?php endforeach ?>
                                  </select>
                                  <input type="hidden" name="user_id_pk" value="<?php echo $user->user_id_pk; ?>">
                               </form>
                              </td>
                              <td>
                                <a href="javascript:void(0);" style=""  class="grey button small2"><i onclick="form_submit(<?php echo $user->user_id_pk; ?>);" class="fa fa-floppy-o" aria-hidden="true"></i></a>
                                 <a class="grey button small2" data-toggle="modal" href='#reset-modal<?php echo $user->user_id_pk; ?>'><i class="fa fa-refresh" aria-hidden="true"></i></a>
                                 <div class="modal" id="reset-modal<?php echo $user->user_id_pk; ?>">
                                    <div class="modal-dialog">
                                       <div class="modal-content">
                                          <form action="<?php echo base_url(); ?>admin/calc/admin/reset_user_pass/<?php echo $user->user_id_pk; ?>" method="POST" role="form">
                                             <div class="modal-header">
                                                <h4 class="modal-title">Reset User Password</h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>                   
                                             </div>
                                             <div class="modal-body">
                                                <input type="text" name="pass" id="reset-usr-pwd" placeholder="Enter New Password" class="form-control" value="" required="required" title="">
                                             </div>
                                             <div class="modal-footer">
                                                <button type="submit" class="btn btn-secondary" style="border:none;">Submit</button>
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal" style="border:none;">Close</button>
                                             </div>
                                          </form>
                                       </div>
                                    </div>
                                 </div>
                              </td>
                              
                          </tr>
                          <?php } } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- bottom content end here -->
<script>
   function form_submit (form_id) {
     var form_data =  $("#user_role_"+form_id).serialize();
     $.ajax({
      url     : "<?php echo base_url(); ?>calculator/admin_dashboard_submit",
      type    : "POST",
      data    : form_data,
      success : function( data )
      {
          if(data == "1")
          {
            $("#output_div").attr("class","alert alert-success");
              $("#output_body").attr("class","text-success");
              $("#output_body").html("User details Updated.");
              $("#output").show();
              $("#output_div").show();
              setTimeout(explode, 3000);
              
          }
          else if(data == "login_fail")
          {
            window.location.assign('<?php echo base_url(); ?>calculator/admin_login');
          }
      },
      error   : function( xhr, err )
      {
          alert('Connection Problem !!');
          return false;
      }
  });
   }
   
   function explode(){
    $("#output").fadeOut();
   }
</script>