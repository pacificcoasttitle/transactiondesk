	jQuery(document).ready(function($){
			   
				var bar = $('.bar');
				var percent = $('.percent');
				
				function reloadCaptcha(){ $("#captchax").attr("src","php/captcha/captcha.php?r=" + Math.random()); }
				$('.captcode').click(function(e){
					e.preventDefault();
					reloadCaptcha();
				});
				
				function swapButton(){
					var txtswap = $(".form-footer button[type='submit']");
					if (txtswap.text() == txtswap.data("btntext-sending")) {
						txtswap.text(txtswap.data("btntext-original"));
					} else {
						txtswap.data("btntext-original", txtswap.text());
						txtswap.text(txtswap.data("btntext-sending"));
					}
				}

				$( "#smart-form" ).validate({
				
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
								/*Opentelephone: {
									required: true
								},*/
								OpenEmail: {
									required: true
								},
								sendername: {
										required: true,
										minlength: 2
								},		
								emailaddress: {
										required: true,
										email: true
								},
								orderservices: {
										required: true
								},
								orderbudget: {
										required: true
								},
								orderfiles:{
									required:true,
									extension:"jpeg|jpg|png"
								},								
								sendermessage: {
										required: true,
										minlength: 10
								},
								captcha:{
									required:true,
									remote:'php/captcha/process.php'
								},
								/*BuyerAgentName:{
				                    required: {
				                        depends: function(element) {
				                            return ($("input[name=add-agent-details]").val() != "");
				                        },
				                    },
				                },
				                ListingAgentName:{
				                    required: {
				                        depends: function(element) {
				                            return ($("input[name=add-agent-details]").val() != "");
				                        },
				                    },
				                },*/
				                salesAmount:{
				                    required: {
				                        depends: function(element) {
				                            return ($("select[name=ProductTypeID]").val() == "20" || $("select[name=ProductTypeID]").val() == "32");
				                        },
				                    },
				                },
				                loanAmount:{
				                    required: {
				                        depends: function(element) {
				                            return ($("select[name=ProductTypeID]").val() == "19" || $("select[name=ProductTypeID]").val() == "33");
				                        },
				                    },
				                }
						},
						
						/* @validation error messages 
						---------------------------------------------- */
						messages:{
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
								orderfiles:{
									required:'Browse to add some order files',
									extension:'Sorry, file format not supported'
								},								
								sendermessage: {
										required: 'Oops you forgot your message',
										minlength: 'Message must be at least 10 characters'
								},															
								captcha:{
										required: 'You must enter the captcha code',
										remote:'Captcha code is incorrect'
								},
								/*BuyerAgentName: {
									required: 'Enter agent\'s name',
								},
								ListingAgentName: {
									required: 'Enter agent\'s name',
								},*/
								salesAmount: {
									required: 'Enter sales amount',
								},
								loanAmount: {
									required: 'Enter loan amount',
								}
						},

						/* @validation highlighting + error placement  
						---------------------------------------------------- */	
						highlight: function(element, errorClass, validClass) {
								$(element).closest('.field').addClass(errorClass).removeClass(validClass);
						},
						unhighlight: function(element, errorClass, validClass) {
								$(element).closest('.field').removeClass(errorClass).addClass(validClass);
						},
						errorPlacement: function(error, element) {
						   if (element.is(":radio") || element.is(":checkbox")) {
									element.closest('.option-group').after(error);
						   } else {
									error.insertAfter(element.parent());
						   }
						},
						
						/* @ajax form submition 
						---------------------------------------------------- */
						submitHandler:function(form) {
							$(form).ajaxSubmit({
									target:'.result',			   
									beforeSubmit:function(){
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
									uploadProgress: function(event, position, total, percentComplete) {
										
										/*var percentVal = percentComplete + '%';
										bar.width(percentVal);
										percent.html(percentVal);*/

										var percentValue = percentComplete + '%';
					    	            $("#progressBar").animate({
					    	                width: '' + percentValue + ''
					    	            }, {
					    	                duration: 5000,
					    	                easing: "linear",
					    	                step: function (x) {
					                        percentText = Math.round(x * 100 / percentComplete);
					    	                    $("#percent").text(percentText + "%");
					                        if(percentText == "100") {
					                        	   $("#outputImage").show();
					                        }
					    	                }
					    	            });
									},								
									error:function(){
										swapButton();
										$( ".progress-section" ).hide(500);
										$('.form-footer').removeClass('progress');
									},
									success:function(data){
										/*var CustomerId = $('#CustomerId').val();
										var res = jQuery.parseJSON(data);*/
										/*if(customer_number)
										{
											if(customer_number)
						                    {
						                        var content = "<h3>Your Customer Number is:"+customer_number+"</h3>";
						                    }

						                    $("#showCustomerNumber").html(content);
						                    $("#showCustomernumberModal").modal('show');
										}*/
										
										swapButton(); 
										$("#progressBar").stop();
										/*var percentVal = '100%';
										bar.width(percentVal);
										percent.html(percentVal);
										$('.progress-section').show().delay(5000).fadeOut(); */								
										$('#progressDivId').show().delay(5000).fadeOut();
										$('.form-footer').removeClass('progress');
										$('.alert-success').show().delay(7000).fadeOut();
										/*$('#clone-min-max').replaceWith($stored_elem);*/
										$('.field').removeClass("state-error, state-success");
										if( $('.alert-error').length == 0){
											$('#smart-form').resetForm();
											reloadCaptcha();	
										}

											$("#OpenName").val('').removeAttr('readonly').parent();
					                        $("#OpenLastName").val('').removeAttr('readonly').parent();
					                        // $("#Opentelephone").val('').removeAttr('readonly').parent();
					                        $("#OpenEmail").val('').removeAttr('readonly').parent();
					                        $("#CompanyName").val('').removeAttr('readonly').parent();
					                        $("#StreetAddress").val('').removeAttr('readonly').parent();
					                        $("#City").val('').removeAttr('readonly').parent();
					                        $("#Zipcode").val('').removeAttr('readonly').parent();
					                        $("#CustomerNumber").val('').removeAttr('readonly').parent();
					                        $("#CustomerId").val('');
					                        $('#agent-details-fields').hide();											
									}
							  });
						}
						
				});
				
				
		
	});				
    