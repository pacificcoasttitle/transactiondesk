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
<script src="<?=base_url()?>assets/front/js/jquery-2.1.4.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"
	integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous">
</script>
<!-- forms hide/show script -->
<script>
	$(document).ready(function () {

		$("div.signup-form").hide();
		$("div.forget-form").hide();

		$("#open-signin-form").click(function () {
			$("div.signin-form").show();
			$("div.signup-form").hide();
			$("div.forget-form").hide();
			$("#output").hide();
		});
		$("#open-signup-form").click(function () {
			$("div.signup-form").show();
			$("div.forget-form").hide();
			$("div.signin-form").hide();
			$("#output").hide();
		});

		$("#open-signin").click(function () {
			$("div.signin-form").show();
			$("div.signup-form").hide();
			$("div.forget-form").hide();
			$("#output").hide();
		});
		$("#open-signup").click(function () {
			$("div.signup-form").show();
			$("div.forget-form").hide();
			$("div.signin-form").hide();
			$("#output").hide();
		});
		$("#forget-pass").click(function () {
			$("div.forget-form").show();
			$("div.signin-form").hide();
			$("div.signup-form").hide();
			$("#output").hide();
		});

	});

</script>

<!-- bottom content start here -->

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
						<h1 class="b-title-page">Lender Portal</h1>
						<div class="b-title-page__info">get your rate instantly</div>
						<!-- end breadcrumb-->
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- pagetitle end here -->
	<!-- content section start here -->

	<section id="content-wrapper" style="padding-top:100px;">

		<div class="container">
			<div class="row">

				<div class="col-md-4">

				</div>

				<div class="col-md-4">

					<div id="output" style="display:none;">
						<div id="output_div" class="alert alert-danger">
							<span class="text-danger" id="output_body"></span>
						</div>
					</div>

					<div class="row signup-form">
						<form action='' method="post" onsubmit="return validate()">
							<h2 class="column bold-title"><b>Sign Up</b></h2>
							<div class="column twelve">
								<input type="email" placeholder="Email Id" class="" onblur="check_email()" id="email"
									name="email" required>
							</div>
							<div class="column six">
								<input type="text" placeholder="First Name" class="" name="fname" required>
							</div>
							<div class="column six">
								<input type="text" placeholder="Last Name" class="" name="lname">
							</div>
							<div class="column six">
								<input type="password" placeholder="Password" name="password" id="password" class=""
									required>
							</div>
							<div class="column six">
								<select name="rep" id="input" class="form-control" required="required">
									<option value="">Select Representative</option>
									<?php foreach ($rep as $key): ?>
									<option><?=$key->rep_key?></option>

									<?php endforeach ?>
								</select>
							</div>
							<img src="<?=base_url()?>assets/front/images/loading.gif" class="img-responsive" alt="Image"
								id="loading1" style="display:none;">
							<div class="column">
								<button class="button orange small submit" type="submit" id="submit_btn">Sign
									Up</button>&nbsp;|&nbsp;
								<button class="button grey small" style="border:none;" id="open-signin-form">Already
									Registered</button>
							</div>
						</form>
					</div>
					<div class="row signin-form">
						<form action='' id="login_form" method="post" onsubmit="return do_login()">
							<h2 class="column bold-title"><b>Sign In</b></h2>
							<div class="column twelve">
								<input type="email" placeholder="Email Id" class="" id="email_id" name="email" required>
							</div>
							<div class="column twelve">
								<input type="password" placeholder="Password" class="" name="password"
									id="login_password" required>
							</div>
							<div class="column twelve">
								<button type="submit" class="button orange small submit"
									style="margin-bottom:initial">Sign In</button>&nbsp;|&nbsp;
								<button type="button" class="button grey small" id="forget-pass"
									style="border:none;">Forget Password</button>
								<hr style="border-color:#a1a1a1;">
								<button class="button blue small" style="border:none;" id="open-signup-form">Sign Up For
									Access</button>
							</div>
						</form>
					</div>
					<div class="row forget-form">
						<form action='' id="forget_login" method="post" onsubmit="return reset_password()">
							<h2 class="column bold-title"><b>Reset Password</b></h2>
							<div class="column seven">
								<input type="email" name="email" placeholder="Email Id" class="" id="for_email_id">
							</div>
							<div class="column five">
								<a type="button" placeholder="send reset" id="forget_btn"
									class="button orange small">Get Reset Code</a>
							</div>
							<div class="column twelve">
								<input type="text" placeholder="Reset Code" class="" name="verification">
							</div>
							<div class="column six">
								<input type="password" placeholder="Password" class="" id="password1" name="password">
							</div>
							<div class="column six">
								<input type="password" placeholder="Confirm Password" id="password2" class="">
							</div>
							<div class="column twelve">
								<button class="button orange small submit" type="submit">Sign Up</button>
								<button class="button grey small reset" value="Reset Form"
									type="reset">Reset</button>&nbsp;|&nbsp;
								<a id="open-signin">Back To Sign In</a>&nbsp;|&nbsp;
								<a id="open-signup">Back To Sign Up</a>
							</div>
						</form>
					</div>
				</div>

				<div class="col-md-4">

				</div>
			</div>
		</div>
	</section>
</body>

</html>
<!-- bottom content end here -->
<script>
	function check_email() {
		var email_id = $("#email").val();
		//alert(email_id);
		if (email_id == "") {
			$("#output_div").attr("class", "alert alert-danger");
			$("#output_body").attr("class", " text-danger");
			$("#output_body").html("please fill email first.!!");
			$("#output").show();
			$("#email_id").focus();
		} else {
			$("#output_body").html("");
			$("#output").hide();
			$("#output_div").attr("class", "alert alert-danger");
			$("#output_body").attr("class", "text-danger");
			var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
			if (!regex.test(email_id)) {
				$("#output_body").html("Email id is not correct.!!");
				$("#output").show();
				$("#email_id").focus();
				return false;
			}
			$.ajax({
				url: "<?=base_url()?>frontend/calc/welcome/check_availability_email",
				type: "post",
				data: {
					email: email_id
				},
				success: function (data) {
					if (data == 0) {
						$("#output_body").html("");
						$("#output_body").html("This email-id is not available. !!");
						$("#output").show();
						//$("#submit_btn").prop("type",'button');
						$("#submit_btn").attr("disabled", "disabled");

						return false;
					} else {
						$("#output").hide();
						$("#output_div").attr("class", "alert alert-success");
						$("#output_body").attr("class", "text-success");
						$("#output_body").html("This email-id is available. !!");
						$("#output").show();
						setTimeout(explode, 2000);
						$("#submit_btn").prop("type", 'submit');
						$("#submit_btn").removeAttr("disabled");
						return true;
					}

				},
			});
		}
	}

	function explode() {
		$("#output").fadeOut();
	}

	function validate() {


		var new1 = $("#password").val();
		var new2 = $("#password").val();

		if (new1 === new2) {
			$("#output").hide();
			return true;
		} else {

			$("#output_div").attr("class", "alert alert-danger");
			$("#output_body").attr("class", "text-danger");
			$("#output_body").html("Confirm password  is not same.!!");
			$("#output").show();
			return false;
		}

		return false;
	}


	function do_login() {
		$("#loading1").show();
		$("#output").hide();

		var password = $("#login_password").val();
		if (password == "") {
			$("#output_div").attr("class", "alert alert-danger");
			$("#output_body").html("Please fill password.!!");
			$("#output").show();
			return false;
		}
		var form_data = $("#login_form").serialize();
		$.ajax({
			url: "<?=base_url()?>frontend/calc/welcome/login_user",
			type: "POST",
			data: form_data,
			success: function (data) {
				var ex = data.split(",");
				data = ex[0];
				type1 = ex[1];
				if (data == "1") {
					$("#loading1").hide();
					window.location.assign('<?=base_url()?>calculator');
				} else if (data == "0") {
					$("#output_body").html("Email ID or Password is Incorrect!");
					$("#output").show();
					$("#loading1").hide();
				} else if (data == "#") {
					$("#output_body").html("Please check your email and verify your account.");
					$("#output").show();
					$("#loading1").hide();
				} else if (data == "##") {
					$("#output_body").html(
						"Your Email id is verified. But Your Profile is not Activated By Admin panel.");
					$("#output").show();
					$("#loading1").hide();
				}
			},
			error: function (xhr, err) {
				alert('Connection Problem !!');
				return false;
			}
		});
		return false;
	}


	$("#forget_btn").click(function () {
		var email_id = document.getElementById("for_email_id").value;
		$("#loading2").show();
		if (email_id == "") {

			$("#output_div").attr("class", "alert alert-danger");
			$("#output_body").attr("class", "text-danger");
			$("#output_body").html("Please provide your email id.");
			$("#output").show();
			return false;
		} else {
			$("#msg_txt").html("");
			$("#output_div").attr("class", "alert alert-danger");

			var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
			if (!regex.test(email_id)) {
				$("#output_div").attr("class", "alert alert-danger");
				$("#output_body").attr("class", "text-danger");
				$("#output_body").html("Email id is not correct.!!");
				$("#output").show();
				$("#loading2").hide();
				return false;
			}
		}
		$.post(
			"<?=base_url()?>frontend/calc/welcome/forget_password",
			$("#forget_login").serialize(),
			function (data) {

				if (data == '0') {
					$("#output_div").attr("class", "alert alert-success");
					$("#output_body").attr("class", "text-success");
					$("#output_body").html("A Verification Code has been sent to this email id !!");
					$("#output").show();
					$("#loading2").hide();
				}
				if (data == '1') {
					$("#output_div").attr("class", "alert alert-danger");
					$("#output_body").attr("class", "text-danger");
					$("#output_body").html("This email id is not registered !!");
					$("#output").show();
					$("#loading2").hide();
				}
			}
		);
	});


	function reset_password() {
		var new1 = $("#password1").val();
		var new2 = $("#password2").val();

		if (new1 === new2) {
			$("#output").hide();
			$.post(
				"<?=base_url()?>frontend/calc/welcome/reset_password",
				$("#forget_login").serialize(),
				function (data) {

					if (data == '0') {
						$("#output_div").attr("class", "alert alert-success");
						$("#output_body").attr("class", "text-success");
						$("#output_body").html("Your Password has been changed successfully.");
						$("#output").show();
						$("#loading2").hide();
					}
					if (data == '1') {
						$("#output_div").attr("class", "alert alert-danger");
						$("#output_body").attr("class", "text-danger");
						$("#output_body").html("This email id is not registered !!");
						$("#output").show();
						$("#loading2").hide();
					}
					if (data == '2') {
						$("#output_div").attr("class", "alert alert-danger");
						$("#output_body").attr("class", "text-danger");
						$("#output_body").html("Verification Code is not Correct.");
						$("#output").show();
						$("#loading2").hide();
					}
				}
			);
		} else {

			$("#output_div").attr("class", "alert alert-danger");
			$("#output_body").attr("class", "text-danger");
			$("#output_body").html("Confirm password  is not same.!!");
			$("#output").show();
			return false;
		}

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
    <!-- Facebook Pixel Code -->
