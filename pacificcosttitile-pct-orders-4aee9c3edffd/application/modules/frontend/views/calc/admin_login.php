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

<body>
<?php
   $this->load->view('layout/header');
?>
    <!-- pagetitle start here -->
    <div class="section-title-page7f area-bg area-bg_blue area-bg_op_60 parallax">
          <div class="area-bg__inner">
            <div class="container">
              <div class="row">
                <div class="col-xs-12">
                  <h1 class="b-title-page">Admin Login</h1>
                  <div class="b-title-page__info">Team Members Only</div>
                  <!-- end breadcrumb-->
                </div>
              </div>
            </div>
          </div>
        </div>
    <!-- pagetitle end here -->
	
    <!-- bottom content start here -->


    <section id="content-wrapper" style="padding-top:100px;">
	<div class="container">
        <div class="row">
            <div class="col-md-4"></div>
             
			 <div class="col-md-4">
              <div id="output" style="display:none;">
                    <div id="output_div" class="alert alert-danger">
                      <span class = "text-danger" id="output_body"></span>
                    </div>
                  </div>
              <form action ='' id="login_form" method="post" onsubmit ="return do_login()" >
                <div class="row signin-form">
                    <h2 class="column bold-title"><b>Login</b></h2>
                    <div class="column twelve">
                        <input type="email" placeholder="Email Id" class="" id="email_id" name="email" required>
                    </div>
                   <div class="column twelve">
                        <input type="password" placeholder="Password" class="" name="password"  id="login_password" required>
                    </div>
                      <img src="<?=base_url()?>assets/front/images/loading.gif" class="img-responsive" alt="Image" id="loading1" style="display:none;">
                    <div class="column twelve">
                        <button type="submit" class="button orange small submit" style="margin-bottom:initial">Sign In</button>
                       <!--  <button class="button grey small reset" value="Reset Form" type="reset">Reset</button> -->
                       
                    </div>
                </div>
                </form>
            </div>
		 <div class="col-md-4"></div>	
			
        </div>
		</div>
    </section>
</body>
</html>
    <!-- bottom content end here -->


<script src="<?=base_url()?>assets/front/js/jquery-2.1.4.min.js"></script>
    <script>

 function do_login()
    {
        $("#loading1").show();
        $("#output").hide();
        
        var password = $("#login_password").val();
        if(password == "")
        {
            $("#output_div").attr("class","alert alert-danger");
            $("#output_body").html("Please fill password.!!");
            $("#output").show();
            return false;
        }
        var form_data = $("#login_form").serialize();
        $.ajax({
            url     : "<?=base_url()?>admin/calc/admin/admin_login_submit",
            type    : "POST",
            data    : form_data,
            success : function( data )
            {
                var ex = data.split(",");
                data = ex[0];
                type1 = ex[1];
                if(data == "1")
                {
                    $("#loading1").hide();
                    window.location.assign('<?=base_url()?>calculator/admin_dashboard');
                }
                else if(data == "0")
                {
                    $("#output_body").html("Email ID or Password is Incorrect!");
                    $("#output").show();
                    $("#loading1").hide();
                }
            },
            error   : function( xhr, err )
            {
                alert('Connection Problem !!');
                return false;
            }
        });
        return false;
    }

    </script>

<?php
   $data['calculator'] = 1;
    $this->load->view('layout/footer', $data);
?>
    <!-- Main slider-->
<script src="<?=base_url()?>assets/plugins/slider-pro/jquery.sliderPro.min.js"></script>
	
<script type="text/javascript" src="<?=base_url()?>assets/front/js/modal.min.js"></script>