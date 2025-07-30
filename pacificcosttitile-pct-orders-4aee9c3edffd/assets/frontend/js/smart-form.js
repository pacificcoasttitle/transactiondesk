jQuery(document).ready(function ($) {

	var bar = $('.bar');
	var percent = $('.percent');

	function reloadCaptcha() { $("#captchax").attr("src", "php/captcha/captcha.php?r=" + Math.random()); }
	$('.captcode').click(function (e) {
		e.preventDefault();
		reloadCaptcha();
	});

	function swapButton() {
		var txtswap = $(".form-footer button[type='submit']");
		if (txtswap.text() == txtswap.data("btntext-sending")) {
			txtswap.text(txtswap.data("btntext-original"));
		} else {
			txtswap.data("btntext-original", txtswap.text());
			txtswap.text(txtswap.data("btntext-sending"));
		}
	}

	$("#smart-form").validate({

		/* @validation states + elements 
		------------------------------------------- */
		errorClass: "state-error",
		validClass: "state-success",
		errorElement: "em",
		onkeyup: false,
		onclick: false,

		/* @validation rules 
		------------------------------------------ */
		rules: {
			OpenName: {
				required: true
			},
			OpenLastName: {
				required: true
			},

			OpenEmail: {
				required: true
			},

			emailaddress: {
				required: true,
				email: true
			},
			captcha: {
				required: true,
				remote: 'php/captcha/process.php'
			},
			LenderName: {
				required: {
					depends: function (element) {
						return ($("input[name=add-lender-details]").val() != "");
					},
				},
			},
			LenderEmailAddress: {
				required: {
					depends: function (element) {
						return ($("input[name=add-lender-details]").val() != "");
					},
				},
				email: true,
			},
			/*LenderTelephone:{
				required: {
					depends: function(element) {
						return ($("input[name=add-lender-details]").val() != "");
					},
				},
			},*/
			LenderCompany: {
				required: {
					depends: function (element) {
						return ($("input[name=add-lender-details]").val() != "");
					},
				},
			},
			BuyerAgentName: {
				required: {
					depends: function (element) {
						return ($("input[name=BuyerAgentEmailAddress]").val() != "" || $("input[name=BuyerAgentTelephone]").val() != "" || $("input[name=BuyerAgentCompany]").val() != "");
					},
				},
			},
			BuyerAgentEmailAddress: {
				required: {
					depends: function (element) {
						return ($("input[name=BuyerAgentName]").val() != "" || $("input[name=BuyerAgentTelephone]").val() != "" || $("input[name=BuyerAgentCompany]").val() != "");
					},
				},
				email: true,
			},
			// BuyerAgentTelephone:{
			//     required: {
			//         depends: function(element) {
			//             return ($("input[name=BuyerAgentName]").val() != "" || $("input[name=BuyerAgentEmailAddress]").val() != "" || $("input[name=BuyerAgentCompany]").val() != "");
			//         },
			//     },
			// },
			BuyerAgentCompany: {
				required: {
					depends: function (element) {
						return ($("input[name=BuyerAgentName]").val() != "" || $("input[name=BuyerAgentEmailAddress]").val() != "" || $("input[name=BuyerAgentTelephone]").val() != "");
					},
				},
			},
			ListingAgentName: {
				required: {
					depends: function (element) {
						return ($("input[name=ListingAgentEmailAddress]").val() != "" || $("input[name=ListingAgentTelephone]").val() != "" || $("input[name=ListingAgentCompany]").val() != "");
					},
				},
			},
			ListingAgentEmailAddress: {
				required: {
					depends: function (element) {
						return ($("input[name=ListingAgentName]").val() != "" || $("input[name=ListingAgentTelephone]").val() != "" || $("input[name=ListingAgentCompany]").val() != "");
					},
				},
				email: true,
			},
			// ListingAgentTelephone:{
			//     required: {
			//         depends: function(element) {
			//             return ($("input[name=ListingAgentName]").val() != "" || $("input[name=ListingAgentEmailAddress]").val() != "" || $("input[name=ListingAgentCompany]").val() != "");
			//         },
			//     },
			// },
			ListingAgentCompany: {
				required: {
					depends: function (element) {
						return ($("input[name=ListingAgentName]").val() != "" || $("input[name=ListingAgentEmailAddress]").val() != "" || $("input[name=ListingAgentTelephone]").val() != "");
					},
				},
			},
			ProductTypeID: {
				required: true,
			},
			salesAmount: {
				required: {
					depends: function (element) {
						return ($("select[name=ProductTypeID]").val() == "20" || $("select[name=ProductTypeID]").val() == "32");
					},
				},
			},
			primaryBorrower: {
				required: {
					depends: function (element) {
						return ($("select[name=ProductTypeID]").val() == "20" || $("select[name=ProductTypeID]").val() == "32");
					},
				},
			},
			loanAmount: {
				required: {
					depends: function (element) {
						return ($("select[name=ProductTypeID]").val() == "19" || $("select[name=ProductTypeID]").val() == "33");
					},
				},
			}
		},

		/* @validation error messages 
		---------------------------------------------- */
		messages: {
			OpenName: {
				required: 'Enter your first name',
			},
			OpenLastName: {
				required: 'Enter your last name',
			},
			Opentelephone: {
				required: 'Enter your telephone number',
			},
			OpenEmail: {
				required: 'Enter your email address',
			},
			sendername: {
				required: 'Enter your name',
				minlength: 'Name must be at least 2 characters'
			},
			emailaddress: {
				required: 'Enter your email address',
				email: 'Enter a VALID email address'
			},
			orderservices: {
				required: 'Please select a service'
			},
			orderbudget: {
				required: 'Choose your budget range'
			},
			orderfiles: {
				required: 'Browse to add some order files',
				extension: 'Sorry, file format not supported'
			},
			/*sendermessage: {
					required: 'Oops you forgot your message',
					minlength: 'Message must be at least 10 characters'
			},*/
			captcha: {
				required: 'You must enter the captcha code',
				remote: 'Captcha code is incorrect'
			},
			LenderName: {
				required: 'Enter lender\'s name',
			},
			LenderEmailAddress: {
				required: 'Enter lender\'s email address',
				email: 'Enter vaild lender\'s email address',
			},
			/*LenderTelephone: {
				required: 'Enter lender\'s telephone',
			},*/
			LenderCompany: {
				required: 'Enter lender\'s company',
			},
			BuyerAgentName: {
				required: 'Enter agent\'s name',
			},
			BuyerAgentEmailAddress: {
				required: 'Enter agent\'s email address',
				email: 'Enter vaild agent\'s email address',
			},
			/*BuyerAgentTelephone: {
				required: 'Enter agent\'s telephone',
			},*/
			BuyerAgentCompany: {
				required: 'Enter agent\'s company',
			},
			ListingAgentName: {
				required: 'Enter agent\'s name',
			},
			ListingAgentEmailAddress: {
				required: 'Enter agent\'s email address',
				email: 'Enter vaild agent\'s email address',
			},
			/*ListingAgentTelephone: {
				required: 'Enter agent\'s telephone',
			},*/
			ListingAgentCompany: {
				required: 'Enter agent\'s company',
			},
			ProductTypeID: {
				required: 'Select Product',
			},
			salesAmount: {
				required: 'Enter sales amount',
			},
			primaryBorrower: {
				required: 'Enter primary borrower',
			},
			// secondaryBorrower: {
			// 	required: 'Enter secondary borrower',
			// },
			loanAmount: {
				required: 'Enter loan amount',
			}
		},

		/* @validation highlighting + error placement  
		---------------------------------------------------- */
		highlight: function (element, errorClass, validClass) {
			$(element).closest('.field').addClass(errorClass).removeClass(validClass);
		},
		unhighlight: function (element, errorClass, validClass) {
			$(element).closest('.field').removeClass(errorClass).addClass(validClass);
		},
		errorPlacement: function (error, element) {
			if (element.is(":radio") || element.is(":checkbox")) {
				element.closest('.option-group').after(error);
			} else {
				error.insertAfter(element.parent());
			}
		},

		/* @ajax form submition 
		---------------------------------------------------- */
		submitHandler: function (form) {
			$(form).ajaxSubmit({
				target: '.result',
				beforeSubmit: function () {
					swapButton();
					/*var percentVal = '0%';
					bar.width(percentVal);
					percent.html(percentVal);
					$( ".progress-section" ).show(); */
					$('.form-footer').addClass('progress');

					$("#progressDivId").css("display", "block");
					var percentValue = '0%';

					$('#progressBar').width(percentValue);
					$('#percent').html(percentValue);

				},
				uploadProgress: function (event, position, total, percentComplete) {

					/*var percentVal = percentComplete + '%';
					bar.width(percentVal);
					percent.html(percentVal);*/

					var percentValue = percentComplete + '%';
					$("#progressBar").animate({
						width: '' + percentValue + ''
					}, {
						duration: 8000,
						easing: "linear",
						step: function (x) {
							percentText = Math.round(x * 100 / percentComplete);
							$("#percent").text(percentText + "%");
							if (percentText == "100") {
								$("#outputImage").show();
								$('#page-preloader').show();
							}
						}
					});
				},
				error: function () {
					swapButton();
					$(".progress-section").hide(500);
					$('.form-footer').removeClass('progress');
					$('#page-preloader').hide();
				},
				success: function (data) {
					var res = jQuery.parseJSON(data);
					if (res.status == 'error') {
						$('.result').html('<div class="alert notification state-error alert-error">' + res.message + '</div>');
					}
					else if (res.status == 'success') {
						$('.result').html('<div class="alert alert-success">' + res.message + '</div>');
						/*setTimeout(function () { */
						window.location.replace(base_url + 'order-submit/' + res.file_id)
						/*}, 8000);*/
					}
					swapButton();

					$('#progressDivId').show().delay(5000).fadeOut();
					$('.form-footer').removeClass('progress');
					$("#progressBar").stop();
					$('.alert-success').show().delay(7000).fadeOut();
					$('#page-preloader').hide();
					$('.field').removeClass("state-error, state-success");
					if ($('.alert-error').length == 0) {
						$('#smart-form').resetForm();
						reloadCaptcha();
					}
				}
			});
		}

	});

	$("#login-form").validate({

		/* @validation states + elements 
		------------------------------------------- */
		errorClass: "state-error",
		validClass: "state-success",
		errorElement: "em",
		onkeyup: false,
		onclick: false,

		/* @validation rules 
		------------------------------------------ */
		rules: {
			email_address: {
				required: true,
				email: true
			},
			pwd: {
				required: true
			},
		},

		/* @validation error messages 
		---------------------------------------------- */
		messages: {
			email_address: {
				required: 'Enter your email address',
				email: 'Enter a Valid email address'
			},
			pwd: {
				required: 'Enter your password'
			},
		},

		/* @validation highlighting + error placement  
		---------------------------------------------------- */
		highlight: function (element, errorClass, validClass) {
			$(element).closest('.field').addClass(errorClass).removeClass(validClass);
		},
		unhighlight: function (element, errorClass, validClass) {
			$(element).closest('.field').removeClass(errorClass).addClass(validClass);
		},
		errorPlacement: function (error, element) {
			if (element.is(":radio") || element.is(":checkbox")) {
				element.closest('.option-group').after(error);
			} else {
				error.insertAfter(element.parent());
			}
			$('#email_address_php_error').hide();
			$('#password_php_error').hide();
		},

		/* @ajax form submition 
		---------------------------------------------------- */
		submitHandler: function (form) {
			$(form).ajaxSubmit({
				target: '#email_address',
				beforeSubmit: function () {

				},
				uploadProgress: function (event, position, total, percentComplete) {


				},
				error: function () {

				},
				success: function (data) {
					var res = jQuery.parseJSON(data);
					if (res.status == 'error') {
						if (res.email_err_msg) {
							$('#email_address_php_error').html(res.email_err_msg);
							$('#email_address_php_error').show();
						} else {
							$('#email_address_php_error').hide();
						}

						if (res.password_err_msg) {
							$('#password_php_error').html(res.password_err_msg);
							$('#password_php_error').show();

							if (res.is_password_field_show == '1') {
								$('#password_container').css('display', 'block');
								$('#is_password_field_show').val(1);
							}
						} else {
							$('#password_php_error').hide();
						}
					} else {
						window.location.replace(base_url + res.url);
					}
				}
			});
		}
	});

	$("#change-password-form").validate({

		/* @validation states + elements 
		------------------------------------------- */
		errorClass: "state-error",
		validClass: "state-success",
		errorElement: "em",
		onkeyup: false,
		onclick: false,

		/* @validation rules 
		------------------------------------------ */
		rules: {
			password: {
				required: true
			},
			confirm_password: {
				required: true,
				equalTo: "#password"
			},
		},

		/* @validation error messages 
		---------------------------------------------- */
		messages: {
			password: {
				required: 'Enter your password',
			},
			confirm_password: {
				required: 'Enter your confirm password',
				equalTo: 'Confirm password should match with password'
			},
		},

		/* @validation highlighting + error placement  
		---------------------------------------------------- */
		highlight: function (element, errorClass, validClass) {
			$(element).closest('.field').addClass(errorClass).removeClass(validClass);
		},
		unhighlight: function (element, errorClass, validClass) {
			$(element).closest('.field').removeClass(errorClass).addClass(validClass);
		},
		errorPlacement: function (error, element) {
			if (element.is(":radio") || element.is(":checkbox")) {
				element.closest('.option-group').after(error);
			} else {
				error.insertAfter(element.parent());
			}
			$('#confirm_password_php_error').hide();
			$('#password_php_error').hide();
		},

		/* @ajax form submition 
		---------------------------------------------------- */
		submitHandler: function (form) {
			$(form).ajaxSubmit({
				target: '#password',
				beforeSubmit: function () {

				},
				uploadProgress: function (event, position, total, percentComplete) {


				},
				error: function () {

				},
				success: function (data) {
					var res = jQuery.parseJSON(data);
					if (res.status == 'error') {
						if (res.pwd_err_msg) {
							$('#password_php_error').html(res.pwd_err_msg);
							$('#password_php_error').show();
						} else {
							$('#password_php_error').hide();
						}

						if (res.confirm_pwd_err_msg) {
							$('#confirm_password_php_error').html(res.confirm_pwd_err_msg);
							$('#confirm_password_php_error').show();
						} else {
							$('#confirm_password_php_error').hide();
						}
					} else {
						window.location.replace(base_url + res.url);
					}
				}
			});
		}
	});

});
