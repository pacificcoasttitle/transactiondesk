< !DOCTYPE html>
	<html lang="en">

	<head>
		<title></title>
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/frontend/css/payoff/style.css">
		<style>
			* {
				-webkit-box-sizing: border-box;
				-moz-box-sizing: border-box;
				box-sizing: border-box;
			}

			@page {
				sheet-size: A4;
			}

			@page bigger {
				sheet-size: 215.9mm 279.4mm;
			}

			@page toc {
				sheet-size: A4;
			}

			body {
				margin: 0;
				padding: 0;
				font-family: 'Open Sans', sans-serif;
				font-size: 13px;
				color: #252525;
			}

			@page .payoff_form {
				max-width: 816px;
				min-height: 1056px;
				box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
				margin: 0 auto;
				padding: 20px 30px;
			}

			:root {
				--primary-color: #573BFF;
			}

			

			.w80 {
				width: 80px;
			}

			.w50 {
				width: 50px;
			}

			.w130 {
				width: 130px;
			}

			.w180 {
				width: 180px;
			}

			.w120 {
				width: 123px;
			}

			.width49 {
				width: 48%;
			}

			.width50 {
				width: 50%;
			}

			.mr-20 {
				margin-right: 20px;
			}

			.mr-10 {
				margin-right: 10px;
			}

			.mt-20 {
				margin-top: 20px;
			}

			.mt-10 {
				margin-top: 10px;
			}

			hr {
				margin: 10px 0;
				border: 0;
				border-bottom: 1px dashed #c7c2c2;
			}

			.d-flex {
                display: -webkit-box;
				display: flex;
			}

			.justify-space-between {
                -webkit-box-pack: center; 
				justify-content: space-between;
			}

			.payoff_title {
				font-weight: 700;
				text-align: center;
				margin: 0 0 15px;
				font-size: 20px;
				padding: 0 0 15px;
				border-bottom: 1px solid rgba(0, 0, 0, .1);
			}

			.form-control {
				border: 0;
				border-bottom: 1px solid #252525;
				color: #252525;
				outline: none;
			}

			.form-control:focus {
				border-color: var(--primary-color)
			}

			.form_box {
				margin-top: 20px;
				padding: 15px;
				border: 1px solid rgba(0, 0, 0, .1);
			}

			label {
				display: inline-block;
			}

			.full_width50 {
				width: calc(100% - 50px);
			}

			.full_width130 {
				width: calc(100% - 130px);
			}

			.full_width180 {
				width: calc(100% - 180px);
			}

			textarea {
				resize: none;
				font-family: 'Open Sans', sans-serif;
			}

			h2.mini_title {
				text-align: center;
				font-size: 14px;
				text-transform: uppercase;
			}

			.radio input[type="radio"],
			.checkbox input[type="checkbox"] {
				display: none;
			}

			.radio label,
			.checkbox label {
				position: relative;
				padding-left: 20px;
			}

			.checkbox label::before,
			.radio label::before {
				content: "\2713";
				width: 13px;
				height: 13px;
				border-radius: 2px;
				position: absolute;
				left: 0;
				border: 1px solid #252525;
				color: #fff;
				font-size: 12px;
				text-align: center;
				line-height: 13px;
				top: 2px
			}

			.radio label::before {
				border-radius: 50%;
			}

			.checkbox input:checked+label::before,
			.radio input:checked+label::before {
				border-color: var(--primary-color);
				background-color: var(--primary-color);
				;
			}

			.btn,
			.fileupload-buttonbar .toggle {
				margin-bottom: 5px;
			}

			.btn-success:hover,
			.btn-success:focus,
			.btn-success:active,
			.btn-success.active,
			.open .dropdown-toggle.btn-success {
				/*color: #fff;
  background-color: #47a447;
  border-color: #398439;*/
			}

			.btn:hover,
			.btn:focus {
				color: #333;
				text-decoration: none;
			}

			.fileinput-button {
				position: relative;
				overflow: hidden;
			}

			.btn {
				display: inline-block;
				margin-bottom: 0;
				font-weight: 400;
				text-align: center;
				vertical-align: middle;
				cursor: pointer;
				background-image: none;
				border: 1px solid transparent;
				white-space: nowrap;
				padding: 6px 12px;
				font-size: 14px;
				line-height: 1.42857143;
				border-radius: 4px;
				-webkit-user-select: none;
				-moz-user-select: none;
				-ms-user-select: none;
				user-select: none;
			}

			.glyphicon {
				position: relative;
				top: 1px;
				display: inline-block;
				font-family: 'Glyphicons Halflings';
				font-style: normal;
				font-weight: 400;
				line-height: 1;
				-webkit-font-smoothing: antialiased;
				-moz-osx-font-smoothing: grayscale;
			}

			.glyphicon-plus:before {
				content: "\2b";
			}

			.btn-success {
				color: #fff;
				/*background-color: #5cb85c;
  border-color: #4cae4c;*/
				font-weight: bold;
				text-align: center;
				padding: 5em 22em;
				margin: 10px 0px 0px 0px;
				color: #555;
				border: 2px dashed #555;
				border-radius: 7px;
				margin-bottom: 20px;
			}

			.fileinput-button input {
				position: absolute;
				top: 0;
				right: 0;
				margin: 0;
				opacity: 0;
				-ms-filter: 'alpha(opacity=0)';
				font-size: 200px;
				direction: ltr;
				cursor: pointer;
			}

			input[type=file] {
				display: block;
			}

			input,
			button,
			select,
			textarea {
				font-family: inherit;
				font-size: inherit;
				line-height: inherit;
			}

			button,
			input,
			optgroup,
			select,
			textarea {
				color: inherit;
				font: inherit;
				margin: 0;
			}

			input[type="file"] {
				align-items: baseline;
				color: inherit;
				text-align: start;
			}

			input[type="hidden"],
			input[type="image"],
			input[type="file"] {
				-webkit-appearance: initial;
				padding: initial;
				background-color: initial;
				border: initial;
			}

			input[type="password"],
			input[type="search"] {
				-webkit-appearance: textfield;
				padding: 1px;
				background-color: white;
				border: 2px inset;
				border-image-source: initial;
				border-image-slice: initial;
				border-image-width: initial;
				border-image-outset: initial;
				border-image-repeat: initial;
				-webkit-rtl-ordering: logical;
				-webkit-user-select: text;
				cursor: auto;
			}

			input,
			textarea,
			keygen,
			select,
			button {
				margin: 0em;
				font: -webkit-small-control;
				color: initial;
				letter-spacing: normal;
				word-spacing: normal;
				text-transform: none;
				text-indent: 0px;
				text-shadow: none;
				display: inline-block;
				text-align: start;
			}

			user agent stylesheetinput,
			textarea,
			keygen,
			select,
			button,
			meter,
			progress {
				-webkit-writing-mode: horizontal-tb;
			}

		</style>
	</head>

	<body>
		<div class="payoff_form">
			<h1 class="payoff_title">PAYOFF INSTRUCTIONS FOR</h1>
			<form method="post" action="<?php echo base_url();?>generate-payoff" id="create_payoff" name="create_payoff"
				enctype="multipart/form-data">
				<div class="d-flex justify-space-between">
					<div><label for="to">T.O.:</label><input type="text" id="to" name="to"
							value="<?php echo !empty($data['to']) && isset($data['to']) ? $data['to']: '';?>"
							class="form-control"></div>
					<div><label for="recording_date">Recording Date:</label><input type="text" id="recording_date"
							name="recording_date"
							value="<?php echo !empty($data['recording_date']) && isset($data['recording_date']) ? $data['recording_date']: '';?>"
							class="form-control"></div>
				</div><input type="hidden" id="file_id" name="file_id"
					value="<?php echo $orderDetails['file_id'];?>"><input type="hidden" id="file_number"
					name="file_number" value="<?php echo $orderDetails['file_number'];?>">
				<div class="form_box">
					<div class="d-flex justify-space-between">
						<div class="d-flex">
							<div class="mr-20"><input type="text" id="8am" name="8am"
									value="<?php echo !empty($data['8am']) && isset($data['8am']) ? $data['8am']: '';?>"
									class="form-control w50"><label for="8am"><strong>8AM</strong></label></div>
							<div><input type="text" id="am_special" name="am_special"
									value="<?php echo !empty($data['8am']) && isset($data['am_special']) ? $data['am_special']: '';?>"
									class="form-control"><label for="am_special">AM Special</label></div>
						</div>
						<div><input type="text" id="need_prefigures_by" name="need_prefigures_by"
								value="<?php echo !empty($data['need_prefigures_by']) && isset($data['need_prefigures_by']) ? $data['need_prefigures_by']: '';?>"
								class="form-control"><label for="need_prefigures_by">Need Prefigures by</label></div>
					</div>
					<hr>
					<div class="d-flex justify-space-between">
						<div class="width49">
							<div class="d-flex"><label for="customer" class="w130">Customer:</label><textarea
									name="customer" rows="3" class="form-control full_width130"><?php echo !empty($data['customer']) && isset($data['customer']) ? $data['customer']: '';
?></textarea></div>
							<div class="mt-10 d-flex"><label for="reference_number" class="w130">Reference Number:
								</label><input type="text" id="reference_number" name="reference_number"
									value="<?php echo !empty($data['reference_number']) && isset($data['reference_number']) ? $data['reference_number']: '';?>"
									class="form-control full_width130"></div>
						</div>
						<div class="width49">
							<div class="d-flex"><label class="w50">Phone:</label><input type="text" id="phone"
									name="phone"
									value="<?php echo !empty($data['phone']) && isset($data['phone']) ? $data['phone']: '';?>"
									class="form-control full_width50"></div>
							<div class="mt-10 d-flex"><label class="w50">Fax: </label><input type="text" id="fax"
									name="fax"
									value="<?php echo !empty($data['fax']) && isset($data['fax']) ? $data['fax']: '';?>"
									class="form-control full_width50"></div>
							<div class="mt-10 d-flex"><label class="w50">Email: </label><input type="text" id="email"
									name="email"
									value="<?php echo !empty($data['email']) && isset($data['email']) ? $data['email']: '';?>"
									class="form-control full_width50"></div>
							<div class="mt-10 d-flex"><label id="" name="" class="w50">Prefers:EMAIL </label></div>
						</div>
					</div>
					<hr>
					<div>
						<div class="d-flex"><label for="property_address" class="w130">Property:</label><input
								type="text" id="property_address" name="property_address"
								value="<?php echo !empty($data['property_address']) && isset($data['property_address']) ? $data['property_address']: '';?>"
								class="form-control full_width130"></div>
						<div class="d-flex mt-10">
							<div class="mr-20"><label>Order Type:</label><input type="text" id="order_type"
									name="order_type"
									value="<?php echo !empty($data['order_type']) && isset($data['order_type']) ? $data['order_type']: '';?>"
									class="form-control w120"><label for="order_type">: Title Only (Outside
									Escrow)</label></div>
							<div class="mr-20"><label for="apn"><strong>APN:</strong></label><input type="text" id="apn"
									name="apn"
									value="<?php echo !empty($data['apn']) && isset($data['apn']) ? $data['apn']: '';?>"
									class="form-control w120"></div>
							<div><label>Country:</label><input type="text" id="country" name="country"
									value="<?php echo !empty($data['country']) && isset($data['country']) ? $data['country']: '';?>"
									class="form-control w120"></div>
						</div>
					</div>
					<hr>
					<div>
						<div class="d-flex justify-space-between">
							<div class="width49">
								<div class="d-flex"><label for="seller" class="w50">Seller: </label><input type="text"
										id="seller" name="seller"
										value="<?php echo !empty($data['seller']) && isset($data['seller']) ? $data['seller']: '';?>"
										class="form-control full_width50"></div>
							</div>
							<div class="width49">
								<div class="d-flex"><label for="ssn" class="w50"><strong>SSN: </strong></label><input
										type="text" id="ssn" name="ssn"
										value="<?php echo !empty($data['ssn']) && isset($data['ssn']) ? $data['ssn']: '';?>"
										class="form-control full_width50"></div>
							</div>
						</div>
						<div class="d-flex mt-10">
							<div class="d-flex mr-20"><label class="w50">Buyer: </label><input type="text" id="buyer"
									name="buyer"
									value="<?php echo !empty($data['buyer']) && isset($data['buyer']) ? $data['buyer']: '';?>"
									class="form-control full_width50"></div>
							<div class="d-flex"><label class="w50"><strong>SSN: </strong></label><input type="text"
									id="ssn_buyer" name="ssn_buyer"
									value="<?php echo !empty($data['ssn_buyer']) && isset($data['ssn_buyer']) ? $data['ssn_buyer']: '';?>"
									class="form-control full_width50"></div>
						</div>
					</div>
					<hr>
					<div>
						<h2 class="mini_title">Funding</h2>
						<div class="d-flex justify-space-between">
							<div class="width49">
								<div class="d-flex"><label for="fund_expected" class="w130">Fund Expected: $
									</label><input type="text" id="fund_expected" name="fund_expected"
										value="<?php echo !empty($data['fund_expected']) && isset($data['fund_expected']) ? $data['fund_expected']: '';?>"
										class="form-control full_width130"></div>
							</div>
							<div class="width49">
								<div class="d-flex">
									<div class="d-flex"><label for="from" class="w50">From</label><input type="text"
											id="funding_from" name="funding_from"
											value="<?php echo !empty($data['funding_from']) && isset($data['funding_from']) ? $data['funding_from']: '';?>"
											class="form-control full_width50"></div>
									<div class="d-flex">
										<div class="mr-10">via</div>
										<div class="radio mr-10"><input type="radio" name="payment_method" value="wire"
												id="wire" class="form-control" <?php echo !empty($data['payment_method']) && $data['payment_method']=='wire'? 'checked="checked"' : '';
?>><label for="wire">wire</label></div>
										<div class="radio"><input type="radio" name="payment_method" value="check"
												id="check" class="form-control" <?php echo !empty($data['payment_method']) && $data['payment_method']=='check'? 'checked="checked' : '';
?>><label for="check">check</label></div>
									</div>
								</div>
							</div>
						</div>
						<div class="d-flex justify-space-between mt-10">
							<div class="width49">
								<div class="d-flex"><label for="fund_expected1" class="w130">Fund Expected: $
									</label><input type="text" id="fund_expected1" name="fund_expected1"
										value="<?php echo !empty($data['fund_expected1']) && isset($data['fund_expected1']) ? $data['fund_expected1']: '';?>"
										class="form-control full_width130"></div>
							</div>
							<div class="width49">
								<div class="d-flex">
									<div class="d-flex"><label for="" class="w50">From</label><input type="text"
											id="funding_from1" name="funding_from1"
											value="<?php echo !empty($data['funding_from1']) && isset($data['funding_from1']) ? $data['funding_from1']: '';?>"
											class="form-control full_width50"></div>
									<div class="d-flex">
										<div class="mr-10">via</div>
										<div class="radio mr-10"><input type="radio" name="payment_method1" id="wire1"
												value="wire" <?php echo !empty($data['payment_method1']) && $data['payment_method1']=='wire'? 'checked' : '';
?>class="form-control"><label for="wire1">wire</label></div>
										<div class="radio"><input type="radio" name="payment_method1" id="check1"
												value="check" <?php echo !empty($data['payment_method1']) && $data['payment_method1']=='check'? 'checked' : '';
?>class="form-control"><label for="check1">check</label></div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<hr>
					<div>
						<h2 class="mini_title">Payoffs</h2>
						<div class="d-flex justify-space-between">
							<div class="width49">
								<div class="d-flex"><label for="pay" class="w50">Pay</label><input type="text" id="pay"
										name="pay"
										value="<?php echo !empty($data['payoff']) && isset($data['payoff']) ? $data['payoff']: '';?>"
										class="form-control full_width50"></div>
							</div>
							<div class="width49">
								<div class="d-flex">
									<div class="checkbox mr-10"><input type="checkbox" id="FHA0" name="FHA0"
											value="FHA0" <?php echo !empty($data['FHA0']) && $data['FHA0']=='FHA0'? 'checked' : '';
?>class="form-control"><label for="FHA0">FHA PAYOFF</label></div>
									<div class="mr-10">via</div>
									<div class="radio mr-10"><input type="radio" id="wire2" name="payment_method2"
											value="wire" <?php echo !empty($data['payment_method2']) && $data['payment_method2']=='wire'? 'checked' : '';
?>class="form-control"><label for="wire2">wire</label></div>
									<div class="radio"><input type="radio" id="check2" name="payment_method2"
											value="check" <?php echo !empty($data['payment_method2']) && $data['payment_method2']=='check'? 'checked' : '';
?>class="form-control"><label for="check2">check</label></div>
								</div>
							</div>
						</div>
						<div class="d-flex justify-space-between mt-10">
							<div class="width49">
								<div class="d-flex"><label for="pay1" class="w50">Pay</label><input type="text"
										id="pay1" name="pay1"
										value="<?php echo !empty($data['pay1']) && isset($data['pay1']) ? $data['pay1']: '';?>"
										class="form-control full_width50"></div>
							</div>
							<div class="width49">
								<div class="d-flex">
									<div class="checkbox mr-10"><input type="checkbox" id="FHA1" name="FHA1"
											value="FHA1" <?php echo !empty($data['FHA1']) && $data['FHA1']=='FHA1'? 'checked' : '';
?>class="form-control"><label for="FHA1">FHA PAYOFF</label></div>
									<div class="mr-10">via</div>
									<div class="radio mr-10"><input type="radio" id="wire3" name="payment_method3"
											value="wire" <?php echo !empty($data['payment_method3']) && $data['payment_method3']=='wire'? 'checked' : '';
?>class="form-control"><label for="wire3">wire</label></div>
									<div class="radio"><input type="radio" id="check3" name="payment_method3"
											value="check" <?php echo !empty($data['payment_method3']) && $data['payment_method3']=='check'? 'checked' : '';
?>class="form-control"><label for="check3">check</label></div>
								</div>
							</div>
						</div>
						<div class="d-flex justify-space-between mt-10">
							<div class="width49">
								<div class="d-flex"><label for="pay2" class="w50">Pay</label><input type="text"
										id="pay2" name="pay2"
										value="<?php echo !empty($data['pay2']) && isset($data['pay2']) ? $data['pay2']: '';?>"
										class="form-control full_width50"></div>
							</div>
							<div class="width49">
								<div class="d-flex">
									<div class="checkbox mr-10"><input type="checkbox" id="FHA2" name="FHA2"
											value="FHA2" <?php echo !empty($data['FHA2']) && $data['FHA2']=='FHA2'? 'checked' : '';
?>class="form-control"><label for="FHA2">FHA PAYOFF</label></div>
									<div class="mr-10">via</div>
									<div class="radio mr-10"><input type="radio" id="wire4" name="payment_method4"
											value="wire" <?php echo !empty($data['payment_method4']) && $data['payment_method4']=='wire'? 'checked' : '';
?>class="form-control"><label for="wire4">wire</label></div>
									<div class="radio"><input type="radio" id="check4" name="payment_method4"
											value="check" <?php echo !empty($data['payment_method4']) && $data['payment_method4']=='check'? 'checked' : '';
?>class="form-control"><label for="check4">check</label></div>
								</div>
							</div>
						</div>
						<div class="d-flex justify-space-between mt-10">
							<div class="width49">
								<div class="d-flex"><label for="pay3" class="w50">Pay</label><input type="text"
										id="pay3" name="pay3"
										value="<?php echo !empty($data['pay3']) && isset($data['pay3']) ? $data['pay3']: '';?>"
										class="form-control full_width50"></div>
							</div>
							<div class="width49">
								<div class="d-flex">
									<div class="checkbox mr-10"><input type="checkbox" id="FHA3" name="FHA3"
											value="FHA3" <?php echo !empty($data['FHA3']) && $data['FHA3']=='FHA3'? 'checked' : '';
?>class="form-control"><label for="FHA3">FHA PAYOFF</label></div>
									<div class="mr-10">via</div>
									<div class="radio mr-10"><input type="radio" id="wire5" name="payment_method5"
											value="wire" <?php echo !empty($data['payment_method5']) && $data['payment_method5']=='wire'? 'checked' : '';
?>class="form-control"><label for="wire5">wire</label></div>
									<div class="radio"><input type="radio" id="check5" name="payment_method5"
											value="check" <?php echo !empty($data['payment_method5']) && $data['payment_method5']=='check'? 'checked' : '';
?>class="form-control"><label for="check5">check</label></div>
								</div>
							</div>
						</div>
					</div>
					<hr>
					<div>
						<h2 class="mini_title">Taxes</h2>
						<div class="d-flex">
							<div class="width49 d-flex">
								<div class="mr-10">PROPERTY TAXES</div>
								<div class="radio mr-10"><input type="radio" name="pay_or_not" value="pay" <?php echo !empty($data['pay_or_not']) && $data['pay_or_not']=='pay'? 'checked' : '';
?>class="form-control"><label for="pay">Pay</label></div>
								<div class="radio"><input type="radio" name="pay_or_not" value="dont_pay" <?php echo !empty($data['pay_or_not']) && $data['pay_or_not']=='dont_pay'? 'checked' : '';
?>class="form-control"><label for="dont_pay">Do Not Pay</label></div>
							</div>
							<div class="width49">
								<div class="d-flex"><label for="" class="w130">Hold $ until</label><input type="text"
										id="hold_until" name="hold_until"
										value="<?php echo !empty($data['hold_until']) && isset($data['hold_until']) ? $data['hold_until']: '';?>"
										class="form-control full_width130"></div>
							</div>
						</div>
						<div class="d-flex mt-10">
							<div class="mr-10">PROPERTY TAXES are for:</div>
							<div class="checkbox mr-10"><input type="checkbox" id="sd" name="taxes" value="sd" <?php echo !empty($data['taxes']) && $data['taxes']=='sd'? 'checked' : '';
?>class="form-control"><label for="sd">SD</label></div>
							<div class="checkbox mr-10"><input type="checkbox" id="la" name="taxes" value="la" <?php echo !empty($data['taxes']) && $data['taxes']=='la'? 'checked' : '';
?>class="form-control"><label for="la">LA</label></div>
							<div class="checkbox mr-10"><input type="checkbox" id="sb" name="taxes" value="sb" <?php echo !empty($data['taxes']) && $data['taxes']=='sb'? 'checked' : '';
?>class="form-control"><label for="sb">SB</label></div>
							<div class="checkbox mr-10"><input type="checkbox" id="riv" name="taxes" value="riv" <?php echo !empty($data['taxes']) && $data['taxes']=='riv'? 'checked' : '';
?>class="form-control"><label for="riv">RIV</label></div>
							<div class="checkbox mr-10"><input type="checkbox" id="orange" name="taxes" value="orange" <?php echo !empty($data['taxes']) && $data['taxes']=='orange'? 'checked' : '';
?>class="form-control"><label for="orange">Orange</label></div>
							<div class="checkbox mr-10"><input type="checkbox" id="kern" name="taxes" value="kern" <?php echo !empty($data['taxes']) && $data['taxes']=='kern'? 'checked' : '';
?>class="form-control"><label for="kern">Kern</label></div>
							<div class="checkbox mr-10"><input type="checkbox" id="ventura" name="taxes" value="ventura" <?php echo !empty($data['taxes']) && $data['taxes']=='ventura'? 'checked' : '';
?>class="form-control"><label for="ventura">Ventura</label></div>
							<div class="checkbox d-flex"><input type="checkbox" id="other" name="taxes" value="other" <?php echo !empty($data['taxes']) && $data['taxes']=='other'? 'checked' : '';
?>class="form-control"><label for="other">Other</label><input type="text" class="form-control w80" id="other"
									name="other"
									value="<?php echo !empty($data['other']) && isset($data['other']) ? $data['other']: '';?>">
							</div>
						</div>
						<div class="d-flex mt-10"><label for="" class="w130">Current Taxes
								<strong>APN:</strong></label><input type="text" id="current_taxes" name="current_taxes"
								value="<?php echo !empty($data['current_taxes']) && isset($data['current_taxes']) ? $data['hold_until']: '';?>"
								class="form-control full_width130"></div>
						<div class="d-flex justify-space-between mt-10">
							<div class="checkbox"><input type="checkbox" id="first" name="first" value="first" <?php echo !empty($data['first']) && $data['first']=='first'? 'checked' : '';
?>class="form-control"><label for="first">1st ½ $</label><input type="text" class="form-control w130" id="first_value"
									name="first_value"
									value="<?php echo !empty($data['first_value']) && isset($data['first_value']) ? $data['first_value']: '';?>">
							</div>
							<div class="checkbox"><input type="checkbox" id="penalty1" name="penalty1" value="penalty1" <?php echo !empty($data['penalty1']) && $data['penalty1']=='penalty1'? 'checked' : '';
?>class="form-control"><label for="penalty1">Penalty</label><input type="text" class="form-control w130"
									id="penalty1_value" name="penalty1_value"
									value="<?php echo !empty($data['penalty1_value']) && isset($data['penalty1_value']) ? $data['penalty1_value']: '';?>">
							</div>
							<div><label for="total1">=Total: $</label><input type="text" id="total1" name="total1"
									value="<?php echo !empty($data['total1']) && isset($data['total1']) ? $data['total1']: '';?>"
									class="form-control w130"></div>
						</div>
						<div class="d-flex justify-space-between mt-10">
							<div class="checkbox"><input type="checkbox" id="second" name="second" value="second" <?php echo !empty($data['second']) && $data['second']=='second'? 'checked' : '';
?>class="form-control"><label for="second">2nd ½ $</label><input type="text" class="form-control w130"
									id="second_value" name="second_value"
									value="<?php echo !empty($data['second_value']) && isset($data['second_value']) ? $data['second_value']: '';?>">
							</div>
							<div class="checkbox"><input type="checkbox" id="penalty2" name="penalty2" value="penalty2" <?php echo !empty($data['penalty2']) && $data['penalty2']=='penalty2'? 'checked' : '';
?>class="form-control"><label for="penalty2">Penalty</label><input type="text" class="form-control w130"
									id="penalty2_value" name="penalty2_value"
									value="<?php echo !empty($data['penalty2_value']) && isset($data['penalty2_value']) ? $data['penalty2_value']: '';?>">
							</div>
							<div><label for="total2">=Total: $</label><input type="text" id="total2" name="total2"
									value="<?php echo !empty($data['total2']) && isset($data['total2']) ? $data['total2']: '';?>"
									class="form-control w130"></div>
						</div>
						<div class="d-flex mt-20"><label for="" class="w180">Supplemental Taxes APN:</label><input
								type="text" class="form-control full_width180" id="" name=""
								value="<?php echo !empty($data['hold_until']) && isset($data['hold_until']) ? $data['hold_until']: '';?>">
						</div>
						<div class="d-flex justify-space-between mt-10">
							<div class="checkbox"><input type="checkbox" id="first1" name="first1" value="first1" <?php echo !empty($data['first1']) && $data['first1']=='first1'? 'checked' : '';
?>class="form-control"><label for="first1">1st ½ $</label><input type="text" class="form-control w130"
									id="first1_value" name="first1_value"
									value="<?php echo !empty($data['first1_value']) && isset($data['first1_value']) ? $data['first1_value']: '';?>">
							</div>
							<div class="checkbox"><input type="checkbox" id="penalty3" name="penalty3" value="penalty3" <?php echo !empty($data['penalty3']) && $data['penalty3']=='penalty3'? 'checked' : '';
?>class="form-control"><label for="penalty3">Penalty</label><input type="text" class="form-control w130"
									id="penalty3_value" name="penalty3_value"
									value="<?php echo !empty($data['penalty3_value']) && isset($data['penalty3_value']) ? $data['penalty3_value']: '';?>">
							</div>
							<div><label for="total3">=Total: $</label><input type="text" id="total3" name="total3"
									value="<?php echo !empty($data['total3']) && isset($data['total3']) ? $data['total3']: '';?>"
									class="form-control w130"></div>
						</div>
						<div class="d-flex justify-space-between mt-10">
							<div class="checkbox"><input type="checkbox" id="second1" name="second1" value="second1" <?php echo !empty($data['second1']) && $data['second1']=='second1'? 'checked' : '';
?>class="form-control"><label for="second1">2nd ½ $</label><input type="text" class="form-control w130"
									id="second1_value" name="second1_value"
									value="<?php echo !empty($data['second1_value']) && isset($data['second1_value']) ? $data['second1_value']: '';?>">
							</div>
							<div class="checkbox"><input type="checkbox" id="penalty4" name="penalty4" value="penalty4" <?php echo !empty($data['penalty4']) && $data['penalty4']=='penalty4'? 'checked' : '';
?>class="form-control"><label for="penalty4">Penalty</label><input type="text" class="form-control w130"
									id="penalty4_value" name="penalty4_value"
									value="<?php echo !empty($data['penalty4_value']) && isset($data['penalty4_value']) ? $data['penalty4_value']: '';?>">
							</div>
							<div><label for="total4">=Total: $</label><input type="text" id="total4" name="total4"
									value="<?php echo !empty($data['total4']) && isset($data['total4']) ? $data['total4']: '';?>"
									class="form-control w130"></div>
						</div>
						<div class="d-flex mt-10">
							<div><label>Delinquent Taxes for the month of</label><input type="text"
									id="delinquent_taxes" name="delinquent_taxes"
									value="<?php echo !empty($data['delinquent_taxes']) && isset($data['delinquent_taxes']) ? $data['delinquent_taxes']: '';?>"
									class="form-control w130"></div>
							<div><label>Amount $</label><input type="text" id="amount" name="amount"
									value="<?php echo !empty($data['amount']) && isset($data['amount']) ? $data['amount']: '';?>"
									class="form-control w130"></div>
							<div><label><strong>APN:</strong></label><input type="text" id="apn_2" name="apn_2"
									value="<?php echo !empty($data['apn_2']) && isset($data['apn_2']) ? $data['apn_2']: '';?>"
									class="form-control w130"></div>
						</div>
					</div>
					<hr>
					<div>
						<h2 class="mini_title">Proceeds</h2>
						<div class="d-flex">
							<div class="mr-10">Send Proceeds to Escrow via: </div>
							<div class="radio mr-10"><input type="radio" id="wire6" name="payment_method6" value="wire6" <?php echo !empty($data['payment_method6']) && $data['payment_method6']=='wire6'? 'checked' : '';
?>class="form-control"><label for="wire6">wire</label></div>
							<div class="radio mr-10"><input type="radio" id="check6" name="payment_method6"
									value="check6" <?php echo !empty($data['payment_method6']) && $data['payment_method6']=='check6'? 'checked' : '';
?>class="form-control"><label for="check6">check</label></div>
							<div class="radio"><input type="radio" id="draft" name="payment_method6" value="draft" <?php echo !empty($data['payment_method6']) && $data['payment_method6']=='draft'? 'checked' : '';
?>class="form-control"><label for="draft">draft</label></div>
						</div>
					</div>
					<hr>
					<div>
						<h2 class="mini_title">SPECIAL INSTRUCTIONS</h2>
					</div>
					<hr>
					<div>
						<h2 class="mini_title">Upload Documents</h2>
						<center><span class="btn btn-success fileinput-button"><i
									class="glyphicon glyphicon-plus"></i><span>Drag and Drop files...</span><input
									type="file" name="payoff_files[]" id="ufile" multiple></span></center>
						<div id="output">
							<ul></ul>
						</div>
					</div>
					<hr>
					<div>
						<div class="form-footer" style="margin: 0px 20px;text-align:center;"><input type="submit"
								value="Submit" style="padding: 0.5em 5em;"></div>
					</div>
				</div>
			</form>
		</div>
	</body>

	</html>
