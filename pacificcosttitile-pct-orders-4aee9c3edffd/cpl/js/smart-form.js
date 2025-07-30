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
							OrderNumber: {
								required: true,
								digits: true
							},
							LoanNumber: {
								required: true,
								customloanno: true,
							},
							LenderName: {
								required: true,
							},
							LenderAddress: {
								required: true,
							},
							LenderCity: {
								required: true,
							},
							LenderSt: {
								required: true,
							},
							LenderZip: {
								required: true,
								minlength: 5,
                    			customzip: true
							},
							BorrowerNames: {
								required: true
							},
							PropertyAddress: {
								required: true
							},
							PropertyCity: {
								required: true
							},
							PropertySt: {
								required: true
							},
							PropertyZip: {
								required: true,
								minlength: 5,
                    			customzip: true
							},		
							EmailTo: {
								required: true,
								email: true
							},
						},
						
						/* @validation error messages 
						---------------------------------------------- */
						messages:{
							OrderNumber: {
								required: 'Enter order number',
								digits: 'Please enter only numeric value',
							},
							LoanNumber: {
								required: 'Enter loan number',
								customloanno: 'Please enter only alphanumeric value'
							},
							LenderName: {
								required: 'Enter lender\'s name',
							},
							LenderAddress: {
								required: 'Enter lender address',
							},
							LenderCity: {
								required: 'Enter lender city',
							},
							LenderSt: {
								required: 'Enter lender state',
							},
							LenderZip: {
								required: 'Enter lender zip',
								minlength: "Lender zip must be 5 characters long",
                        		customzip: 'Please enter valid lender zip'
							},
							BorrowerNames: {
								required: 'Enter borrower name'
							},
							PropertyAddress: {
								required: 'Enter borrower address',
							},
							PropertyCity: {
								required: 'Enter borrower city',
							},
							PropertySt: {
								required: 'Enter borrower state',
							},
							PropertyZip: {
								required: 'Enter borrower zip',
								minlength: "Borrower zip must be 5 characters long",
                        		customzip: 'Please enter valid borrower zip'
							},		
							EmailTo: {
								required: 'Enter email cpl to',
								email: 'Please enter valid email cpl to'
							},
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
										var percentVal = '0%';
										bar.width(percentVal);
										percent.html(percentVal);
										$( ".progress-section" ).show();
										$('.form-footer').addClass('progress');
									},
									uploadProgress: function(event, position, total, percentComplete) {
										var percentVal = percentComplete + '%';
										bar.width(percentVal);
										percent.html(percentVal);
									},								
									error:function(){
										swapButton();
										$( ".progress-section" ).hide(500);
										$('.form-footer').removeClass('progress');
									},
									 success:function(){
										swapButton(); 
										var percentVal = '100%';
										bar.width(percentVal);
										percent.html(percentVal);
										$('.progress-section').show().delay(5000).fadeOut();											
										$('.form-footer').removeClass('progress');
										$('.alert-success').show().delay(7000).fadeOut();
										$('.field').removeClass("state-error, state-success");
										if( $('.alert-error').length == 0){
											$('#smart-form').resetForm();
											reloadCaptcha();
										}
											
									 }
							  });
						}
						
				});

			//custom validation rule
            $.validator.addMethod("customzip", 
                function(value, element) {
                    return /^(?!0{5})(\d{5})(?!-?0{4})(|-\d{4})?$/i.test(value);
                }, 
                "Please enter valid ZipCode."
            );

            $.validator.addMethod("lettersonly", function(value, element) {
			  return this.optional(element) || /^[a-z]+$/i.test(value);
			}, "Letters only please");

			$.validator.addMethod("customloanno", function(value, element) {
		        return this.optional(element) || /^[a-z0-9\\]+$/i.test(value);
		    }, "Alphanumeric only please");
	});				
    