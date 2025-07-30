(function($) {



    var form = $("#signup-form");
    form.validate({
        errorPlacement: function errorPlacement(error, element) {
            element.before(error);
        },
        rules: {
            email: {
                email: true
            }
        },
        onfocusout: function(element) {
            $(element).valid();
        },
    });
    form.children("div").steps({
        headerTag: "h3",
        bodyTag: "fieldset",
        transitionEffect: "fade",
        stepsOrientation: "vertical",
        titleTemplate: '<div class="title"><span class="step-number">#index#</span><span class="step-text">#title#</span></div>',
        labels: {
            previous: 'Previous',
            next: 'Next',
            finish: 'Finish',
            current: ''
        },
        onStepChanging: function(event, currentIndex, newIndex) {
            if (currentIndex === 0) {
                form.parent().parent().parent().append('<div class="footer footer-' + currentIndex + '"></div>');
            }
            if (currentIndex === 1) {
                form.parent().parent().parent().find('.footer').removeClass('footer-0').addClass('footer-' + currentIndex + '');
            }
            if (currentIndex === 2) {
                form.parent().parent().parent().find('.footer').removeClass('footer-1').addClass('footer-' + currentIndex + '');
            }
            if (currentIndex === 3) {
                form.parent().parent().parent().find('.footer').removeClass('footer-2').addClass('footer-' + currentIndex + '');
            }
            // if(currentIndex === 4) {
            //     form.parent().parent().parent().append('<div class="footer" style="height:752px;"></div>');
            // }
            form.validate().settings.ignore = ":disabled,:hidden";
            return form.valid();
        },
        onFinishing: function(event, currentIndex) {
            form.validate().settings.ignore = ":disabled,:hidden";
            return form.valid();
        },
        onFinished: function(event, currentIndex) {
            $('#page-preloader').css('background-color', 'rgba(0,0,0,.5)');
		    $('#page-preloader').css('display', 'block');
            $("#signup-form").submit();
        },
        onStepChanged: function(event, currentIndex, priorIndex) {

            return true;
        }
    });

    jQuery.extend(jQuery.validator.messages, {
        required: "",
        remote: "",
        email: "",
        url: "",
        date: "",
        dateISO: "",
        number: "",
        digits: "",
        creditcard: "",
        equalTo: ""
    });

    $('.dob_date_picker_div').each(function(){
		var date_id = $(this).find('.dob_birth_date').attr('id');
		var month_id = $(this).find('.dob_birth_month').attr('id');
		var year_id = $(this).find('.dob_birth_year').attr('id');
		
		$.dobPicker({
			daySelector: '#'+date_id,
			monthSelector: '#'+month_id,
			yearSelector: '#'+year_id,
			dayDefault: '',
			monthDefault: '',
			yearDefault: '',
			minimumAge: 0,
			maximumAge: 120
		});

		if($('#'+date_id).data('val')) {
			$('#'+date_id).val($('#'+date_id).data('val'));
			$('#'+date_id).change();
		}
		if($('#'+month_id).data('val')) {
			$('#'+month_id).val($('#'+month_id).data('val'));
			$('#'+month_id).change();
		}
		if($('#'+year_id).data('val')) {
			$('#'+year_id).val($('#'+year_id).data('val'));
		}
	});
    var marginSlider = document.getElementById('slider-margin');
    if (marginSlider != undefined) {
        noUiSlider.create(marginSlider, {
              start: [1100],
              step: 100,
              connect: [true, false],
              tooltips: [true],
              range: {
                  'min': 100,
                  'max': 2000
              },
              pips: {
                    mode: 'values',
                    values: [100, 2000],
                    density: 4
                    },
                format: wNumb({
                    decimals: 0,
                    thousand: '',
                    prefix: '$ ',
                })
        });
        var marginMin = document.getElementById('value-lower'),
	    marginMax = document.getElementById('value-upper');

        marginSlider.noUiSlider.on('update', function ( values, handle ) {
            if ( handle ) {
                marginMax.innerHTML = values[handle];
            } else {
                marginMin.innerHTML = values[handle];
            }
        });
    }

    $('#is_another_seller').change(function(){
        var $this = $(this);
        var selectedvalue = $this.val();
        if(selectedvalue == 'Yes'){
            $("#second_seller").removeClass("d-none");
            $('#second_first_name').prop('required',true);
            $('#second_last_name').prop('required',true);
            $('#second_email').prop('required',true);
            $('#second_phone').prop('required',true);
            $('#second_birth_month').prop('required',true);
            $('#second_birth_date').prop('required',true);
            $('#second_birth_year').prop('required',true);
            $('#second_ssn1').prop('required',true);
            $('#second_ssn2').prop('required',true);
            $('#second_ssn3').prop('required',true);
            $('#second_current_mailing_address').prop('required',true);
            $('#second_mailing_address_port_closing').prop('required',true);
        } else {
            $("#second_seller").addClass("d-none");
            $('#second_first_name').prop('required',false);
            $('#second_last_name').prop('required',false);
            $('#second_email').prop('required',false);
            $('#second_phone').prop('required',false);
            $('#second_birth_month').prop('required',false);
            $('#second_birth_date').prop('required',false);
            $('#second_birth_year').prop('required',false);
            $('#second_ssn1').prop('required',false);
            $('#second_ssn2').prop('required',false);
            $('#second_ssn3').prop('required',false);
            $('#second_current_mailing_address').prop('required',false);
            $('#second_mailing_address_port_closing').prop('required',false);
        }
    });

    $('#is_trustee').change(function(){
        var $this = $(this);
        var selectedvalue = $this.val();
        if(selectedvalue == 'Yes'){
            $("#trustee_container").removeClass("d-none");
            $('#current_trustees').prop('required',true);
            $('#is_original_trustees').prop('required',true);
        } else {
            $("#trustee_container").addClass("d-none");
            $('#current_trustees').prop('required',false);
            $('#is_original_trustees').prop('required',false);
        }
    });

    $('#is_used_another_last_name').change(function(){
        var $this = $(this);
        var selectedvalue = $this.val();
        if(selectedvalue == 'Yes'){
            $("#another_last_name_container").removeClass("d-none");
            $('#another_last_name').prop('required',true);
        } else {
            $("#another_last_name_container").addClass("d-none");
            $('#another_last_name').prop('required',false);
        }
    });

    $('#is_married_or_domestic_partner').change(function(){
        var $this = $(this);
        var selectedvalue = $this.val();
        if(selectedvalue == 'Yes'){
            $("#married_or_domestic_partner").removeClass("d-none");
            $('#marriage_or_domestic_day').prop('required',true);
            $('#marriage_or_domestic_month').prop('required',true);
            $('#marriage_or_domestic_year').prop('required',true);
            $('#spouse_first_name').prop('required',true);
            $('#spouse_last_name').prop('required',true);
            $('#spouse_email').prop('required',true);
            $('#spouse_phone').prop('required',true);
            $('#spouse_birth_day').prop('required',true);
            $('#spouse_birth_month').prop('required',true);
            $('#spouse_birth_year').prop('required',true);
            $('#spouse_ssn').prop('required',true);
        } else {
            $("#married_or_domestic_partner").addClass("d-none");
            $('#marriage_or_domestic_day').prop('required',false);
            $('#marriage_or_domestic_month').prop('required',false);
            $('#marriage_or_domestic_year').prop('required',false);
            $('#spouse_first_name').prop('required',false);
            $('#spouse_last_name').prop('required',false);
            $('#spouse_email').prop('required',false);
            $('#spouse_phone').prop('required',false);
            $('#spouse_birth_day').prop('required',false);
            $('#spouse_birth_month').prop('required',false);
            $('#spouse_birth_year').prop('required',false);
            $('#spouse_ssn').prop('required',false);
        }
    });

    $('#is_currently_employed').change(function(){
        var $this = $(this);
        var selectedvalue = $this.val();
        if(selectedvalue == 'Yes'){
            $("#currently_employed_container").removeClass("d-none");
            $('#employee_company_name').prop('required',true);
            $('#from_employee_date').prop('required',true);
            $('#to_employee_date').prop('required',true);
        } else {
            $("#currently_employed_container").addClass("d-none");
            $('#employee_company_name').prop('required',false);
            $('#from_employee_date').prop('required',false);
            $('#to_employee_date').prop('required',false);
        }
    });

    $('#is_spouse_domestic_partner_employed').change(function(){
        var $this = $(this);
        var selectedvalue = $this.val();
        if(selectedvalue == 'Yes'){
            $("#spouse_domestic_partner_employed_container").removeClass("d-none");
            $('#spouse_company_name').prop('required',true);
            $('#from_spouse_date').prop('required',true);
            $('#to_spouse_date').prop('required',true);
        } else {
            $("#spouse_domestic_partner_employed_container").addClass("d-none");
            $('#spouse_company_name').prop('required',false);
            $('#from_spouse_date').prop('required',false);
            $('#to_spouse_date').prop('required',false);
        }
    });

    $('#is_another_occupation_spouse_domestic').change(function(){
        var $this = $(this);
        var selectedvalue = $this.val();
        if(selectedvalue == 'Yes'){
            $("#another_spouse_domestic_partner_employed_container").removeClass("d-none");
            $('#another_spouse_company_name').prop('required',true);
            $('#another_from_spouse_date').prop('required',true);
            $('#another_to_spouse_date').prop('required',true);
        } else {
            $("#another_spouse_domestic_partner_employed_container").addClass("d-none");
            $('#another_spouse_company_name').prop('required',false);
            $('#another_from_spouse_date').prop('required',false);
            $('#another_to_spouse_date').prop('required',false);
        }
    });

    $('#is_property_sell_2').change(function(){
        var $this = $(this);
        var selectedvalue = $this.val();
        if(selectedvalue == 'No'){
            $("#is_another_property_sell").removeClass("d-none");
            $('#another_property_sell').prop('required',true);
        } else {
            $("#is_another_property_sell").addClass("d-none");
            $('#another_property_sell').prop('required',false);
        }
    });

    $('#is_another_residence').change(function(){
        var $this = $(this);
        var selectedvalue = $this.val();
        if(selectedvalue == 'Yes'){
            $("#another_residence_container").removeClass("d-none");
            $('#another_residence').prop('required',true);
            $('#another_from_date').prop('required',true);
            $('#another_to_date').prop('required',true);
        } else {
            $("#another_residence_container").addClass("d-none");
            $('#another_residence').prop('required',false);
            $('#another_from_date').prop('required',false);
            $('#another_to_date').prop('required',false);
        }
    });

    $('#is_married').change(function(){
        var $this = $(this);
        var selectedvalue = $this.val();
        if(selectedvalue == 'married'){
            $("#married_employed_container").removeClass("d-none");
            $("#married_occupation_container").removeClass("d-none");
            $('#is_spouse_domestic_partner_employed').prop('required',true);
            $('#is_another_occupation_spouse_domestic').prop('required',true);
        } else {
            $("#married_employed_container").addClass("d-none");
            $("#married_occupation_container").addClass("d-none");
            $('#is_spouse_domestic_partner_employed').prop('required',false);
            $('#is_another_occupation_spouse_domestic').prop('required',false);
        }
    });

    $('#is_married_or_domestic_partner').change(function(){
        var $this = $(this);
        var selectedvalue = $this.val();
        if(selectedvalue == 'married'){
            $("#married_employed_container").removeClass("d-none");
            $("#married_occupation_container").removeClass("d-none");
            $('#is_spouse_domestic_partner_employed').prop('required',true);
            $('#is_another_occupation_spouse_domestic').prop('required',true);
        } else {
            $("#married_employed_container").addClass("d-none");
            $("#married_occupation_container").addClass("d-none");
            $('#is_spouse_domestic_partner_employed').prop('required',false);
            $('#is_another_occupation_spouse_domestic').prop('required',false);
        }
    });

    $('#is_add_another_occupation').change(function(){
        var $this = $(this);
        var selectedvalue = $this.val();
        if (selectedvalue == 'Yes') {
            $("#another_occupation_container").removeClass("d-none");
            $('#employee_another_company_name').prop('required',true);
            $('#another_from_employee_date').prop('required',true);
            $('#another_to_employee_date').prop('required',true);
        } else {
            $("#another_occupation_container").addClass("d-none");
            $('#employee_another_company_name').prop('required',false);
            $('#another_from_employee_date').prop('required',false);
            $('#another_to_employee_date').prop('required',false);
        }
    });

    $('#is_another_hoa').change(function(){
        var $this = $(this);
        var selectedvalue = $this.val();
        if (selectedvalue == 'Yes') {
            $("#another_hoa_container").removeClass("d-none");
            $('#second_hoa_company').prop('required',true);
            $('#another_from_employee_date').prop('required',true);
            $('#another_to_employee_date').prop('required',true);
        } else {
            $("#another_hoa_container").addClass("d-none");
            $('#second_hoa_company').prop('required',false);
            $('#another_from_employee_date').prop('required',false);
            $('#another_to_employee_date').prop('required',false);
        }
    });

    $('#is_property_owned_free_clear').change(function(){
        var $this = $(this);
        var selectedvalue = $this.val();
        if(selectedvalue == 'No'){
            $("#property_owned_free_clear_no").removeClass("d-none");
            $('#lender_name').prop('required',true);
            $('#lender_address').prop('required',true);
            $('#loan_number').prop('required',true);
            $('#lender_phone_number').prop('required',true);
            $('#unpaid_balance').prop('required',true);
            $('#payment_due_date').prop('required',true);
            $('#loan_type').prop('required',true);
            $('#is_impound_account').prop('required',true);
            $('#tax_status').prop('required',true);
            $('#is_paid_impound').prop('required',true);
        } else {
            $("#property_owned_free_clear_no").addClass("d-none");
            $('#lender_name').prop('required',false);
            $('#lender_address').prop('required',false);
            $('#loan_number').prop('required',false);
            $('#lender_phone_number').prop('required',false);
            $('#unpaid_balance').prop('required',false);
            $('#payment_due_date').prop('required',false);
            $('#loan_type').prop('required',false);
            $('#is_impound_account').prop('required',false);
            $('#tax_status').prop('required',false);
            $('#is_paid_impound').prop('required',false);
        }
    });

    $('#is_another_loan').change(function(){
        var $this = $(this);
        var selectedvalue = $this.val();
        if(selectedvalue == 'Yes'){
            $("#another_loan_option").removeClass("d-none");
            $('#second_lender_name').prop('required',true);
            $('#second_lender_address').prop('required',true);
            $('#second_loan_number').prop('required',true);
            $('#second_lender_phone_number').prop('required',true);
            $('#second_unpaid_balance').prop('required',true);
            $('#second_payment_due_date').prop('required',true);
            $('#second_loan_type').prop('required',true);
            $('#second_is_impound_account').prop('required',true);
            $('#second_tax_status').prop('required',true);
            $('#second_is_paid_impound').prop('required',true);
        } else {
            $("#another_loan_option").addClass("d-none");
            $('#second_lender_name').prop('required',false);
            $('#second_lender_address').prop('required',false);
            $('#second_loan_number').prop('required',false);
            $('#second_lender_phone_number').prop('required',false);
            $('#second_unpaid_balance').prop('required',false);
            $('#second_payment_due_date').prop('required',false);
            $('#second_loan_type').prop('required',false);
            $('#second_is_impound_account').prop('required',false);
            $('#second_tax_status').prop('required',false);
            $('#second_is_paid_impound').prop('required',false);
        }
    });

    $('#is_private_water_company').change(function(){
        var $this = $(this);
        var selectedvalue = $this.val();
        if(selectedvalue == 'Yes'){
            $("#water_company_container").removeClass("d-none");
            $('#water_company').prop('required',true);
            $('#water_company_address').prop('required',true);
            $('#water_account_number').prop('required',true);
            $('#water_phone_number').prop('required',true);
        } else {
            $("#water_company_container").addClass("d-none");
            $('#water_company').prop('required',false);
            $('#water_company_address').prop('required',false);
            $('#water_account_number').prop('required',false);
            $('#water_phone_number').prop('required',false);
        }
    });

    $('#is_hoa').change(function(){
        var $this = $(this);
        var selectedvalue = $this.val();
        if(selectedvalue == 'Yes'){
            $("#hoa_container").removeClass("d-none");
            $('#hoa_company').prop('required',true);
            $('#hoa_company_address').prop('required',true);
            $('#hoa_contact_person').prop('required',true);
            $('#hoa_contact_number').prop('required',true);
        } else {
            $("#hoa_container").addClass("d-none");
            $('#hoa_company').prop('required',false);
            $('#hoa_company_address').prop('required',false);
            $('#hoa_contact_person').prop('required',false);
            $('#hoa_contact_number').prop('required',false);
        }
    });

    $('#is_married').change(function(){
        var $this = $(this);
        var selectedvalue = $this.val();
        if (selectedvalue == 'married') {
            $("select[name='is_married_or_domestic_partner']").val("Yes");
            $(".is_married_or_domestic_partner_wrapper").removeClass("d-none");
            $("#married_or_domestic_partner").removeClass("d-none");
            $('#marriage_or_domestic_day').prop('required',true);
            $('#marriage_or_domestic_month').prop('required',true);
            $('#marriage_or_domestic_year').prop('required',true);
            $('#spouse_first_name').prop('required',true);
            $('#spouse_last_name').prop('required',true);
            $('#spouse_email').prop('required',true);
            $('#spouse_phone').prop('required',true);
            $('#spouse_birth_day').prop('required',true);
            $('#spouse_birth_month').prop('required',true);
            $('#spouse_birth_year').prop('required',true);
            $('#spouse_ssn').prop('required',true);
        } else {
            $("select[name='is_married_or_domestic_partner']").val("No");
            $(".is_married_or_domestic_partner_wrapper").addClass("d-none");
            $("#married_or_domestic_partner").addClass("d-none");
            $('#marriage_or_domestic_day').prop('required',false);
            $('#marriage_or_domestic_month').prop('required',false);
            $('#marriage_or_domestic_year').prop('required',false);
            $('#spouse_first_name').prop('required',false);
            $('#spouse_last_name').prop('required',false);
            $('#spouse_email').prop('required',false);
            $('#spouse_phone').prop('required',false);
            $('#spouse_birth_day').prop('required',false);
            $('#spouse_birth_month').prop('required',false);
            $('#spouse_birth_year').prop('required',false);
            $('#spouse_ssn').prop('required',false);
            $('#marriage_or_domestic_day, #marriage_or_domestic_month, #marriage_or_domestic_year, #spouse_first_name, #spouse_last_name, #spouse_email, #spouse_phone, #spouse_birth_day, #spouse_birth_month, #spouse_birth_year, #spouse_ssn').val('');
        }
    });

    $(".phone_mask").mask('(000) 000-0000');
    $(".ssn").mask('000-00-0000');
    $(".amount_mask").mask("#,##0", {reverse: true});
    $( ".datepicker" ).datepicker();
   
})(jQuery);

$(document).ready(function(){
    var $preloader = $('#page-preloader'),
    $spinner   = $preloader.find('.spinner-loader');
    $spinner.fadeOut();
    $preloader.delay(50).fadeOut('slow');
});