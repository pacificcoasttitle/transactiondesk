<body>
<?php
   $this->load->view('layout/header');
?>
<div class="section-title-page7e area-bg area-bg_blue area-bg_op_60 parallax">
	<div class="area-bg__inner">
		<div class="container">
			<div class="row">
				<div class="col-xs-12">
					<h1 class="b-title-page">Rate Calculator</h1>
					<div class="b-title-page__info">get your rate instantly</div>
					<!-- end breadcrumb-->
				</div>
			</div>
		</div>
	</div>
</div>
<!-- pagetitle end here -->
<!-- content section start here -->
<section class="content-wrapper">
	<div class="row">
		<div class="buttons">
			<div class="column six">
				<?php if($this->session->userdata('mpuserid') !=""){?>
				Hi, <?php echo $this->session->userdata('mpusername');?> <a
					href="<?=base_url()?>calculator/logout">Logout</a>
				<?php } else{?>
				<a class="button orange create-repo-btn small" href="<?=base_url()?>calculator/signup"> Lender
					Partner Login</a>
				<?php } ?>
			</div>
			<div class="column six">
				<form id="retrive_quote" action="<?=base_url()?>frontend/calc/welcome/view_quote" method="POST"
					class="form-horizontal" role="form">
					<div class="row" style="float:right;">
						<div class="column seven">
							<input type="text" placeholder="Quote ID" id="rtselect" name="quote_id" class="sarinput">
						</div>
						<div class="column five">
							<input type="submit" value="Retrieve Quote" class="button  blue create-repo-btn">
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	<div class="row">
		<form id="get_quote" action="<?=base_url()?>frontend/calc/welcome/receipt" method="POST" role="form">
			<div class="row mil">
				<h2 class="ui-title-block-3 ui-title-block-3_sm" style="color:#888;margin-top: 15px;">Property Location</h2>
				<hr class="w">
				<div class="column four first">
					<select name="region" class="sarinput" id="repcounty" onchange="get_county_list(this.value)">
						<option value="">Select State</option>
						<?php foreach ($state_list as $state): ?>
						<option value="<?=$state->region?>"><?=$state->region?></option>
						<?php endforeach ?>
					</select>
				</div>
				<div class="column four">
					<select name="county" class="sarinput" onchange="countryOnChnage(this.value);" id="select_county"
						style="display:none">
						<option value="" class="city_null">Select County</option>
						<?php foreach ($county_list as $county): ?>
						<option value="<?=$county->zone_name?>__<?=$county->transaction_type?>"
							class="region<?php $ss = str_replace(' ','_',$county->region); echo $ss;?>"><?=$county->zone_name?>
						</option>
						<?php endforeach ?>
					</select>
				</div>
				<!-- <div class="column four">
               <select type="text" placeholder="Select Rep" name="city" class="sarinput" id="select_city" style="display:none">
                   <option value="" class="city_null">Select City</option>
                   <?php foreach ($city_list as $county): ?>
            <option value="<?=$county->county_id_pk?>" class="zone<?php $ss = str_replace(' ','_',$county->zone_name); echo $ss;?>"><?=$county->county_name?></option>
            <?php endforeach ?>
            </select> 
         </div> -->

			</div>
			<?php /*<div class="row">
      <div class="column nine first">
        <select type="text" placeholder="Select Rep" name="same_county" class="sarinput" onchange="check_same_county(this.value)">
        <option value="">Is the closing office in the same county?</option>
      				<option value="1" selected="true">Yes</option>
      				<option value="0">No</option>
      			  </select>   
      </div>
        
       
      </div>*/?>

			<div class="row" id="close_county_row" style="display:none;">
				<h2 class="ui-title-block-3 ui-title-block-3_sm" style="color:#888;">Closing Property Location</h2>
				<hr class="w">
				<div class="column three first">
					<select type="text" placeholder="Select Rep" name="closing_zone" class="sarinput"
						onchange="get_close_city_list(this.value);" id="select_close_county">
						<option value="" class="city_null">Select County</option>
						<?php foreach ($county_list as $county): ?>
						<option value="<?=$county->zone_name?>"
							class="region<?php $ss = str_replace(' ','_',$county->region); echo $ss;?>"><?=$county->zone_name?>
						</option>
						<?php endforeach ?>
					</select>
				</div>
				<div class="column three ">
					<select type="text" placeholder="Select Rep" name="closing_county" class="sarinput"
						id="select_close_city">
						<option value="" class="city_null">Select City</option>
						<?php foreach ($city_list as $county): ?>
						<option value="<?=$county->county_id_pk?>"
							class="zone<?php $ss = str_replace(' ','_',$county->zone_name); echo $ss;?>">
							<?=$county->county_name?></option>
						<?php endforeach ?>
					</select>
				</div>
			</div>

			<div class="row">
				<h2 class="ui-title-block-3 ui-title-block-3_sm" style="color:#888; margin-top: 15px;">Rates Option</h2>
				<hr class="w">
				<!--	<div class="checkbox-inline">
          <input id="all-fees"  class="checkbox-custom" name="all-fees" type="checkbox" value="1" onclick="check_radio(1)">
          <label for="all-fees" class="checkbox-custom-label">All Fees</label>
          </div> -->
				<div class="checkbox-inline">
					<input id="title-rates" class="checkbox-custom" name="title-rates" type="checkbox" value="1">
					<label for="title-rates" class="checkbox-custom-label">Title Rates</label>
				</div>

				<div class="checkbox-inline">
					<input id="escrow-rates" class="checkbox-custom" name="escrow-rates" type="checkbox" value="1">
					<label for="escrow-rates" class="checkbox-custom-label">Escrow Rates</label>
				</div>
				<!--	<div class="checkbox-inline">
      <input id="endorsements" class="checkbox-custom"  name="endorsements" type="checkbox" value="1">
      <label for="endorsements" class="checkbox-custom-label">Endorsements</label>
      </div> -->
				<div class="checkbox-inline">
					<input id="recording-fees" class="checkbox-custom" name="recording-fees" type="checkbox" value="1">
					<label for="recording-fees" class="checkbox-custom-label">Recording Fees</label>
				</div>
				<!-- <input type="hidden" name="recording-fees" value="0" id="input" class="form-control" value=""> -->

				<div class="clearfix"></div>
			</div>

			<p>&nbsp;</p>
			<div class="row ">
				<h2 class="ui-title-block-3 ui-title-block-3_sm" style="color:#888; margin-top: 15px;">Transaction Details
				</h2>
				<hr class="w" />
				<div class="column four first">
					<select type="text" placeholder="Select Rep" name="transaction_type" class="sarinput" id="transacttype"
						onchange="change_transaction_type()">
						<option value="">Transaction Type</option>
						<option value="Resale">Purchase</option>
						<option value="Re-Finance">Refinance</option>
					</select>
				</div>
				<div class="column four">
					<span style='margin-left: -15px;'>$</span>
					<input type="text" placeholder="Sale Amount" id="saleamount" onkeypress="return isNumber(event,this)"
						name="transaction_amount" class="sarinput" autocomplete="off">
					<small style="color:#f33;display:none">Please enter correct value.</small>
				</div>
				<div class="column four">
					<span style='margin-left: -15px;'>$</span>
					<input type="text" placeholder="Loan Amount" id="loanamount" onkeypress="return isNumber(event,this)"
						name="loanamount" class="sarinput" autocomplete="off">
					<small style="color:#f33;display:none">Please enter correct value.</small>
				</div>
			</div>

			<div class="row">
				<div class="column six first">
					<select type="text" placeholder="Select Rep" name="policy_type" class="sarinput" id="reppolicy">
						<option value="" selected>Policy Type</option>
					</select>
				</div>
				<div class="column three " style="display:none;">
					<select type="text" placeholder="Select Rep" name="is_lender_policy" class="sarinput" id="lenpolicy">
						<option value=" ">Does lender require policy?</option>
						<option value="1">Yes</option>
						<option value="0">No</option>
					</select>
				</div>
			</div>

			<p>&nbsp;</p>
			<!-- <div class="row">
      <h2 class="ui-title-block-3 ui-title-block-3_sm" style="color:#888;" >Endorsement Details</h2>
      <hr class="w">
      <div id="endorsement_selection"></div>
   </div> -->
			<div class="row">
				<!-- <h2 class="ui-title-block-3 ui-title-block-3_sm" style="color:#888;">Escrow/Settlement Service</h2>
      <hr class="w">
      <div class="clearfix" onclick="select_transaction_type(this);">
         <input id="eight" class="checkbox-custom" name="eight" type="checkbox" value="1" >
         <label for="eight" class="checkbox-custom-label">Residential Loan Escrow Service </label>
      </div>

      <div class="clearfix" onclick="select_transaction_type(this);" id="mob_sig">
         <input id="nine" class="checkbox-custom" name="nine" type="checkbox" value="1" >
         <label for="nine" class="checkbox-custom-label">Mobile Sign in Free</label>
      </div>

      <div class="clearfix" id="mobile_count_div" style="padding-left: 55px;display:none;">
         <label class="sr-only" for="">No. Of Mobile Signin</label>
         <input type="text"  name="no_of_mobile_signin" onkeypress="return isNumber(event,this)" class="form-control column four" id="mobile_count" placeholder="1-2-3..">
         <div class="clearfix">
            <p style="margin-top:5px;">&nbsp;</p>
         </div>
      </div>
      
      <div class="clearfix" onclick="select_transaction_type(this);">
         <input id="ten" class="checkbox-custom" name="ten" type="checkbox" value="1" checked="true">
         <label for="ten" class="checkbox-custom-label">New Loan Fee</label>
      </div>
      
      <div class="clearfix" onclick="select_transaction_type(this);">
         <input id="eleven" class="checkbox-custom" name="eleven" type="checkbox" value="1" checked="true">
         <label for="eleven" class="checkbox-custom-label">Notary Fees</label>
      </div>
      
      <div class="clearfix" onclick="select_transaction_type(this);">
         <input id="twelve" class="checkbox-custom" name="twelve" type="checkbox" checked="true" value="1" >
         <label for="twelve" class="checkbox-custom-label">Recording Service Fee</label>
      </div>
      <p>&nbsp;</p> -->

				<div class="clearfix">
					<input type="submit" value="Get Quote" class="button orange create-repo-btn">
					<input type="reset" value="Reset Form" class="button  grey create-repo-btn"
						onclick="javascript:(location.reload());">
				</div>
			</div>
		</form>

		<div class="row">
			<div class="twelve column">
				<div class="panel">
					<p>The charges quoted on this web site are estimates only, and should not be relied on as accurately
						reflecting the charges for a specific transaction. The actual charges may vary, depending on the
						availability of discounts, requests for special coverages or services, or other matters specific to
						the transaction. Please contact your local Pacific Coast Title office or agent for charges associated
						with a specific transaction. Contact information for Pacific Coast Title Company offices in your area
						is available at www.pct.com/branches</p>
				</div>
			</div>
		</div>
</section>
</div>
</body>
</html>

<!-- content section end here -->
<!-- bottom content start here -->
<!-- bottom content end here -->
<style>
	input.error {
		/* border:solid 1px red !important;*/
	}

	textarea.error {
		border: solid 1px red !important;
	}

	#retrive_quote label.error {
		width: auto display: inline;
		color: red;
		font-size: 12px;
	}

	#get_quote label.error {
		width: auto display: inline;
		color: red;
		font-size: 12px;
	}

</style>

<script>
	$(document).ready(function () {
		$('#repcounty').val('California');
		get_county_list('California');
		$('#retrive_quote').validate({
			rules: {
				//quote_id: "required",
				quote_id: {
					required: true,
					digits: true
				}
			},
			messages: {
				//quote_id: "Quote ID is required.",
				quote_id: {
					required: "Quote ID is required.",
					digits: "Enter numaric value."
				}
			}
		});

		$('#get_quote').validate({
			rules: {
				region: "required",
				county: "required",
				city: "required",
				transaction_type: "required",
				transaction_amount: "required",
				loanamount: "required",
				policy_type: "required",
			},
			messages: {
				region: "State is required.",
				county: "County is required.",
				city: "City is required.",
				transaction_type: "Transaction type is required.",
				transaction_amount: "Sale amount is required.",
				loanamount: "Loan amount is required.",
				policy_type: "Policy type is required.",
			}
		});
	});

	function countryOnChnage(value) {
		console.log(value)
		var transactionType = value.split('__');
		console.log(transactionType[1])
		switch (transactionType[1]) {
			case 'Re-Finance':
				$("#transacttype option[value='Resale']").hide();
				break;
			case 'Resale':
				$("#transacttype option[value='Re-Finance']").hide();
				break;
			default:
				$("#transacttype option").show();
				break;
		}
		// var sel = zone_id.split(' ').join('_');
		// $("#select_city").hide();
		// $("#select_city .city_null").show();
		// if(sel.trim().length <=0){
		//   $("#select_city").hide();
		//   return false;
		// }    
		// $.ajax({
		//   url     : "<?=base_url()?>welcome/get_cities",
		//   type    : "post",
		//   data    : {zone_id : zone_id },
		//   success : function( data )
		//   {
		//     $("#select_city").html(data); 
		//     $("#select_city").css("display", "block");
		//     return false;
		//   },
		//   error: function(){
		//     $("#select_city").css("display", "none");
		//   }
		// });
	}

	function get_close_city_list(zone_id) {
		var sel = zone_id.replace(" ", '_');
		$("#select_close_city option").hide();
		$("#select_close_city .city_null").show();
		$("#select_close_city .zone" + sel).show();
		$("#select_close_city").val("");
	}



	function get_county_list(region_id) {
		var sel = region_id.replace(" ", '_');
		$("#select_county option").hide();
		$("#select_close_county option").hide();
		$("#select_county .city_null").show();
		$("#select_city option").hide();
		$("#select_city .city_null").show();
		$("#select_city").val("");
		$("#select_county .region" + sel).show();
		$("#select_county").val("");
		$("#select_close_county .region" + sel).show();
		$("#select_close_county").val("");
		$("#select_county").css("display", "block");
		check_radio(0);
	}


	function check_radio(flag) {
		var region = $('#repcounty').val();
		var cc = region.split(" ");
		var sel = cc[0];
		if ($('#all-fees').is(":checked")) {
			$('[name="title-rates"]').prop("checked", true);
			$('[name="escrow-rates"]').prop("checked", true);
			$('[name="endorsements"]').prop('checked', true);
			$('[name="recording-fees"]').prop('checked', true);
		} else if ($('#all-fees').is(":unchecked")) {
			$('[name="title-rates"]').prop("checked", false);
			$('[name="escrow-rates"]').prop("checked", false);
			$('[name="endorsements"]').prop('checked', false);
			$('[name="recording-fees"]').prop('checked', false);
		}
	}

	function check_same_county(check) {
		if (check == 0) {
			$('#close_county_row').show();
			$('[name="closing_county"]').attr("required", "required");
			$('[name="closing_zone"]').attr("required", "required");
		} else {
			$('#close_county_row').hide();
			$('[name="closing_county"]').val("");
			$('[name="closing_county"]').removeAttr("required");
			$('[name="closing_zone"]').val("");
			$('[name="closing_zone"]').removeAttr("required");
		}
	}


	function change_transaction_type() {
		var selected_value = $('#transacttype').val();
		$.ajax({
			url: '<?=base_url()?>frontend/calc/welcome/endorsement_options/' + selected_value,
		}).done(function (data) {
			console.log("success");
			$("#endorsement_selection").html(data);
		}).fail(function () {
			console.log("error");
		}).always(function () {
			console.log("complete");
		});

		if (selected_value == "Resale") {
			$('[name="eight"]').attr("checked", "true");
			$('[name="eight"]').addClass('default');
			$('[name="eleven"]').attr("checked", "true");
			$('[name="eleven"]').addClass('default');
			$('[name="transaction_amount"]').parent().show();
			$('[name="ten"]').addClass('default');
			$('[name="eleven"]').removeClass('default');
			$('#reppolicy').html("");
			$('#reppolicy').html(
				'<option value="">Policy Type</option><option value="Regular">Alta Homeowners Policy </option>');
			$('#reppolicy').val('Regular');
			$('#lenpolicy').parent().show();
		} else if (selected_value == "Re-Finance") {
			$('[name="eight"]').attr("checked", "true");
			$('[name="ten"]').attr("checked", "true");
			$('[name="eleven"]').attr("checked", "true");
			$('[name="eight"]').addClass('default');
			$('[name="ten"]').removeClass('default');
			$('[name="ten"]').removeAttr("checked");;
			$('[name="ten"]').parent().hide();

			$('[name="eleven"]').addClass('default');
			$('[name="transaction_amount"]').val('0');
			$('[name="transaction_amount"]').parent().hide();
			$('#reppolicy').html("");
			$('#lenpolicy').parent().hide();
			$('#lenpolicy').val("0");
			$('#reppolicy').html(
				'<option value="">Policy Type</option><option selected="true" value="Residential">ALTA Residential Loan Policy</option>'
				);
		}
	}

	function select_transaction_type(check) {
		var selected_value = $('#transacttype').val();
		if ($(check).find('input[type="checkbox"]').attr("checked")) {
			$(check).find('input[type="checkbox"]').removeAttr("checked");
		} else {
			$(check).find('input[type="checkbox"]').attr("checked", "true");
		}
		$(check).find('.default').attr("checked", "true");
	}


	$('body').on('click', ".endo_Resale", function () {
		select_transaction_type(this);
	});

	$('body').on('click', ".endo_Re-finance", function () {
		select_transaction_type(this);
	});

	$('body').on('click', "#mob_sig", function () {
		if ($('[name="nine"]').is(":checked")) {
			$("#mobile_count_div").slideDown('fast');
			$("#mobile_count").attr("required", "required");
			$("#mobile_count").val("");
		} else {
			$("#mobile_count_div").slideUp('fast');
			$("#mobile_count").removeAttr("required");
			$("#mobile_count").val("0");
		}
	});

	function isNumber(evt, box) {
		evt = (evt) ? evt : window.event;
		var charCode = (evt.which) ? evt.which : evt.keyCode;
		if (charCode == 46) {
			return true;
		}
		if (charCode > 31 && (charCode < 48 || charCode > 57)) {
			$(box).siblings('small').show();
			setTimeout(function () {
				$(box).siblings('small').hide();
			}, 1000);
			return false;
		}
		return true;
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

