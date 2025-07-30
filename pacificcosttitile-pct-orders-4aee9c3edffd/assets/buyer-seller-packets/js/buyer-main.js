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
                if ($('#is_another_buyer').val() == '1') {
                    $('#new_buyer_vesting_container').removeClass('d-none');
                    $(".married option[value='new']").each(function() {
                        $(this).remove();
                    });
                    var new_buyer = $('#buyer_new_first_name').val()+" "+$('#buyer_new_last_name').val();
                    $('#new_buyer_name').val(new_buyer);
                    $('.married').append($("<option></option>")
                    .attr("value", "new")
                    .text(new_buyer)); 
                } else {
                    $('#new_buyer_vesting_container').addClass('d-none');
                }
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
            // alert('Submited');
			form.submit();
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

	// $.dobPicker({
    //     daySelector: '#birth_date1',
    //     monthSelector: '#birth_month1',
    //     yearSelector: '#birth_year1',
    //     dayDefault: '',
    //     monthDefault: '',
    //     yearDefault: '',
    //     minimumAge: 0,
    //     maximumAge: 120
    // });
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

	$('.buyer__show_hide_action').change(function(){
		var show_hide_div = $(this).data('action');
		if(show_hide_div) {
			if($(this).hasClass('has__data_val')) {
				if($(this).find(':selected').data('val') == '1') {
					$('.'+show_hide_div).show();
					$('.'+show_hide_div).find('select').val('').trigger('change');
				}
				else {
					$('.'+show_hide_div).hide();
				}
			}
			else{

				if($(this).val()=='1') {
					$('.'+show_hide_div).show();
				}
				else {
					$('.'+show_hide_div).hide();
				}
			}
		}
		
	});
	$(".married_option_change").change(function(){
		
		var marital_status = $(this).parents('.vesting-buyer-div').find('.marital_status_select').val();
		var select_new = '';
		if(marital_status == 'husband_and_wife') {
			select_new = 'wife_and_husband';
		}
		else if(marital_status == 'wife_and_husband') {
			select_new = 'husband_and_wife';
		}
		else if(marital_status == 'a_married_couple' || marital_status == 'registered_domestic_partners') {
			select_new = marital_status;
		}
		var related_id = $(this).data('related');
		if(related_id) {
			var select_id = "#buyer_"+related_id+"_marital_status";
			$(select_id).val("").trigger('change');
			select_id = "#buyer_"+related_id+"_married_to";
			$(select_id).val("");
		}
		var married_to = $(this).val();
		if(select_new && married_to) {
			var current_buyer = $(this).parents('.vesting-buyer-div').data('id');
			var select_id = "#buyer_"+married_to+"_marital_status";
			$(select_id).val(select_new).trigger('change');
			select_id = "#buyer_"+married_to+"_married_to";
			$(select_id).val(current_buyer);
			$(this).data('related',married_to);
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

    $('#is_improvement').change(function(){
        var $this = $(this);
        var selectedvalue = $this.val();
        if (selectedvalue == 'Yes') {
            $("#is_improvement_container").removeClass("d-none");
            $('#is_full_paid').prop('required',true);
        } else {
            $("#is_improvement_container").addClass("d-none");
            $('#is_full_paid').prop('required',false);
        }
    });

    $('#is_loan').change(function(){
        var $this = $(this);
        var selectedvalue = $this.val();
        if (selectedvalue == 'Yes') {
            $("#is_loan_container").removeClass("d-none");
            $('#lender_name_2').prop('required',true);
            $('#lender_loan_amount').prop('required',true);
            $('#lender_acct_no').prop('required',true);
            $("select[name='is_loan_processor']").val("1");
            $('#lpanofficer').prop('required',true);
            $('#lpemail').prop('required',true);
            $('#lpphone').prop('required',true);
            $('.loan_processor_div').show();
        } else {
            $("#is_loan_container").addClass("d-none");
            $('#lender_name_2').prop('required',false);
            $('#lender_loan_amount').prop('required',false);
            $('#lender_acct_no').prop('required',false);
            $("#lender_name_2, #lender_loan_amount, #lender_acct_no").val('');
            $("select[name='is_loan_processor']").val("0");

            $('#lpanofficer').prop('required',false);
            $('#lpemail').prop('required',false);
            $('#lpphone').prop('required',false);

            $('#lpanofficer, #lpemail, #lpphone').val('');
            $('.loan_processor_div').hide();
        }
    });

    $('#proceeds_refund').change(function(){
        var $this = $(this);
        var selectedvalue = $this.val();
        if (selectedvalue == 'transfer_all_proceeds') {
            $("#transfer_all_proceeds_cont").removeClass("d-none");
            $("#transfer_portion_cont").addClass("d-none");
            $("#fed_Ex_check_address_cont").addClass("d-none");
            $("#wire_proceeds_cont").addClass("d-none");

            $('#transfer_all_proceeds_att').prop('required',true);
            $('#transfer_all_proceeds_esc').prop('required',true);
            $('#transfer_portion_att').prop('required', false);
            $('#transfer_portion_att_esc').prop('required', false);
            $('#fed_Ex_check_address').prop('required', false);
            $('#bank_name').prop('required', false);
            $('#account_name').prop('required', false);
            $('#wire_proceed_phone').prop('required', false);
            $('#routing_number').prop('required', false);
            $('#account_number').prop('required', false);
        } else if (selectedvalue == 'transfer_portion') {
            $("#transfer_all_proceeds_cont").addClass("d-none");
            $("#transfer_portion_cont").removeClass("d-none");
            $("#fed_Ex_check_address_cont").addClass("d-none");
            $("#wire_proceeds_cont").addClass("d-none");

            $('#transfer_all_proceeds_att').prop('required',false);
            $('#transfer_all_proceeds_esc').prop('required',false);
            $('#transfer_portion_att').prop('required', true);
            $('#transfer_portion_att_esc').prop('required', true);
            $('#fed_Ex_check_address').prop('required', false);
            $('#bank_name').prop('required', false);
            $('#account_name').prop('required', false);
            $('#wire_proceed_phone').prop('required', false);
            $('#routing_number').prop('required', false);
            $('#account_number').prop('required', false);
        } else if (selectedvalue == 'fed_Ex_check_address') {
            $("#transfer_all_proceeds_cont").addClass("d-none");
            $("#transfer_portion_cont").addClass("d-none");
            $("#fed_Ex_check_address_cont").removeClass("d-none");
            $("#wire_proceeds_cont").addClass("d-none");

            $('#transfer_all_proceeds_att').prop('required',false);
            $('#transfer_all_proceeds_esc').prop('required',false);
            $('#transfer_portion_att').prop('required', false);
            $('#transfer_portion_att_esc').prop('required', false);
            $('#fed_Ex_check_address').prop('required', true);
            $('#bank_name').prop('required', false);
            $('#account_name').prop('required', false);
            $('#wire_proceed_phone').prop('required', false);
            $('#routing_number').prop('required', false);
            $('#account_number').prop('required', false);
        } else if (selectedvalue == 'wire_proceeds') {
            $("#transfer_all_proceeds_cont").addClass("d-none");
            $("#transfer_portion_cont").addClass("d-none");
            $("#fed_Ex_check_address_cont").addClass("d-none");
            $("#wire_proceeds_cont").removeClass("d-none");

            $('#transfer_all_proceeds_att').prop('required',false);
            $('#transfer_all_proceeds_esc').prop('required',false);
            $('#transfer_portion_att').prop('required', false);
            $('#transfer_portion_att_esc').prop('required', false);
            $('#fed_Ex_check_address').prop('required', false);
            $('#bank_name').prop('required', true);
            $('#account_name').prop('required', true);
            $('#wire_proceed_phone').prop('required', true);
            $('#routing_number').prop('required', true);
            $('#account_number').prop('required', true);
        } else {
            $("#transfer_all_proceeds_cont").addClass("d-none");
            $("#transfer_portion_cont").addClass("d-none");
            $("#fed_Ex_check_address_cont").addClass("d-none");
            $("#wire_proceeds_cont").addClass("d-none");

            $('#transfer_all_proceeds_att').prop('required',false);
            $('#transfer_all_proceeds_esc').prop('required',false);
            $('#transfer_portion_att').prop('required', false);
            $('#transfer_portion_att_esc').prop('required', false);
            $('#fed_Ex_check_address').prop('required', false);
            $('#bank_name').prop('required', false);
            $('#account_name').prop('required', false);
            $('#wire_proceed_phone').prop('required', false);
            $('#routing_number').prop('required', false);
            $('#account_number').prop('required', false);
        }
    });

	$(".phone_mask").mask('(000) 000-0000');
	$(".amount_mask").mask("#,##0", {reverse: true});
	$(".ssn").mask('000-00-0000');
    $( ".datepicker" ).datepicker();
})(jQuery);
