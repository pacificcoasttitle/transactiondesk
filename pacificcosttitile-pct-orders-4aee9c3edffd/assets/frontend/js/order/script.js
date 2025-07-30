$(document).on('change','input[type="radio"]',function(){
    var $this = $(this);
    var selectedvalue = $this.val();
    if ($this.attr('id') == 'coSellerYes') {
        $('#co_seller_first_name').prop('required',true);
        $('#co_seller_middle_name').prop('required',true);
        $('#co_seller_last_name').prop('required',true);
        $('#co_seller_expiration_date').prop('required',true);
        $('input:radio[name=co_seller_marital_status]').prop('required',true);
        $('#co_seller_ssn').prop('required',true);
        $('#co_seller_email').prop('required',true);
        $('#co_seller_phone_number').prop('required',true);
        $('input:radio[name=co_seller_phone_number_type]').prop('required',true);
        $('input:radio[name=co_seller_foreign_resident]').prop('required',true);
    } else if($this.attr('id') == 'coSellerNo') {
        $('#co_seller_first_name').prop('required',false);
        $('#co_seller_middle_name').prop('required',false);
        $('#co_seller_last_name').prop('required',false);
        $('#co_seller_expiration_date').prop('required',false);
        $('input:radio[name=co_seller_marital_status]').prop('required',false);
        $('#co_seller_ssn').prop('required',false);
        $('#co_seller_email').prop('required',false);
        $('#co_seller_phone_number').prop('required',false);
        $('input:radio[name=co_seller_phone_number_type]').prop('required',false);
        $('input:radio[name=co_seller_foreign_resident]').prop('required',false);
    }

    if ($this.attr('id') == 'noCorrectPropertyAddress') {
        $('#property_street_address').prop('required',true);
        $('#property_city').prop('required',true);
        $('#property_state').prop('required',true);
        $('#property_zip_code').prop('required',true);
    } else if($this.attr('id') == 'yesCorrectPropertyAddress') {
        $('#property_street_address').prop('required',false);
        $('#property_city').prop('required',false);
        $('#property_state').prop('required',false);
        $('#property_zip_code').prop('required',false);
    }

    if ($this.attr('id') == 'noCorrectPropertyAddressAsCurrentAddress') {
        $('#current_street_address').prop('required',true);
        $('#current_city').prop('required',true);
        $('#current_state').prop('required',true);
        $('#current_zip_code').prop('required',true);
    } else if($this.attr('id') == 'yesCorrectPropertyAddressAsCurrentAddress') {
        $('#current_street_address').prop('required',false);
        $('#current_city').prop('required',false);
        $('#current_state').prop('required',false);
        $('#current_zip_code').prop('required',false);
    }

    if ($this.attr('id') == 'yesForwarding') {
        $('#forwarding_street_address').prop('required',true);
        $('#forwarding_city').prop('required',true);
        $('#forwarding_state').prop('required',true);
        $('#forwarding_zip_code').prop('required',true);
    } else if($this.attr('id') == 'noForwarding') {
        $('#forwarding_street_address').prop('required',false);
        $('#forwarding_city').prop('required',false);
        $('#forwarding_state').prop('required',false);
        $('#forwarding_zip_code').prop('required',false);
    }

    if ($this.attr('id') == 'yesinsurance') {
        $('#insurance_policy_file_name').prop('required',false);
    } else if($this.attr('id') == 'noinsurance') {
        $('#insurance_policy_file_name').prop('required',false);
    }

    if ($this.attr('id') == 'yesmortgage') {
        $('input:radio[name=is_mortgage_credit]').prop('required',true);
        $('#mortgage_holder').prop('required',true);
        $('#loan_amount').prop('required',true);
        $('#mortgage_phone').prop('required',true);
        $('#loan_number').prop('required',true);
        $('#loan_balance').prop('required',true);
        $('#account_holder_name').prop('required',true);
        $('input:radio[name=is_second_mortgage]').prop('required',true);
    } else if($this.attr('id') == 'nomortgage') {
        $('input:radio[name=is_mortgage_credit]').prop('required',false);
        $('#mortgage_holder').prop('required',false);
        $('#loan_amount').prop('required',false);
        $('#mortgage_phone').prop('required',false);
        $('#loan_number').prop('required',false);
        $('#loan_balance').prop('required',false);
        $('#account_holder_name').prop('required',false);
        $('input:radio[name=is_second_mortgage]').prop('required',false);
    }

    if ($this.attr('id') == 'yesRealEstate') {
        $('#agent_first_name').prop('required',true);
        $('#agent_middle_name').prop('required',true);
        $('#agent_last_name').prop('required',true);
        $('#agent_company').prop('required',true);
        $('#agent_company_address').prop('required',true);
        $('#agent_company_city').prop('required',true);
        $('#agent_company_state').prop('required',true);
        $('#agent_company_zip_code').prop('required',true);
        $('#amount_percent_commission').prop('required',true);
        $('#amount_deduction').prop('required',true);
        $('#agent_phone').prop('required',true);
        $('#agent_email').prop('required',true);
    } else if($this.attr('id') == 'noRealEstate') {
        $('#agent_first_name').prop('required',false);
        $('#agent_middle_name').prop('required',false);
        $('#agent_last_name').prop('required',false);
        $('#agent_company').prop('required',false);
        $('#agent_company_address').prop('required',false);
        $('#agent_company_city').prop('required',false);
        $('#agent_company_state').prop('required',false);
        $('#agent_company_zip_code').prop('required',false);
        $('#amount_percent_commission').prop('required',false);
        $('#amount_deduction').prop('required',false);
        $('#agent_phone').prop('required',false);
        $('#agent_email').prop('required',false);
    }

    if ($this.attr('id') == 'yesCreditCard') {
        $('input:radio[name=CreditCardLock]').prop('required',true);
    } else if($this.attr('id') == 'noCreditCard') {
        $('input:radio[name=CreditCardLock]').prop('required',false);
    }

    if ($this.attr('id') == 'yesmortgage2') {
        $('input:radio[name=is_second_mortgage_credit]').prop('required',true);
        $('#second_mortgage_holder').prop('required',true);
        $('#second_loan_amount').prop('required',true);
        $('#second_mortgage_phone').prop('required',true);
        $('#second_loan_number').prop('required',true);
        $('#second_loan_balance').prop('required',true);
        $('#second_account_holder_name').prop('required',true);
        
    } else if($this.attr('id') == 'nomortgage2') {
        $('input:radio[name=is_second_mortgage_credit]').prop('required',false);
        $('#second_mortgage_holder').prop('required',false);
        $('#second_loan_amount').prop('required',false);
        $('#second_mortgage_phone').prop('required',false);
        $('#second_loan_number').prop('required',false);
        $('#second_loan_balance').prop('required',false);
        $('#second_account_holder_name').prop('required',false);
    }

    if ($this.attr('id') == 'yesCreditCard2') {
        $('input:radio[name=is_second_creditcard_lock]').prop('required',true);
    } else if($this.attr('id') == 'noCreditCard2') {
        $('input:radio[name=is_second_creditcard_lock]').prop('required',false);
    }

    if ($this.attr('id') == 'otherAttorney') {
        $('#firm_name').prop('required',true);
        $('#firm_phone_number').prop('required',true);
        $('#attorney_name').prop('required',true);
        $('#attorney_phone_number').prop('required',true);
        $('#attorney_email').prop('required',true);
    } else if($this.attr('id') == 'attorney') {
        $('#firm_name').prop('required',false);
        $('#firm_phone_number').prop('required',false);
        $('#attorney_name').prop('required',false);
        $('#attorney_phone_number').prop('required',false);
        $('#attorney_email').prop('required',false);
    }

    if ($this.attr('id') == 'yesHOA') {
        $('#hoa_management_company_name').prop('required',true);
        $('#hoa_contact_person').prop('required',true);
        $('#hoa_email').prop('required',true);
        $('#hoa_phone').prop('required',true);
        $('#hoa_dues').prop('required',true);
        $('input:radio[name=hoa_dues_per]').prop('required',true);
        $('#hoa_notes').prop('required',true);
        $('input:radio[name=is_property_second_hoa]').prop('required',true);
    } else if($this.attr('id') == 'noHOA') {
        $('#hoa_management_company_name').prop('required',false);
        $('#hoa_contact_person').prop('required',false);
        $('#hoa_email').prop('required',false);
        $('#hoa_phone').prop('required',false);
        $('#hoa_dues').prop('required',false);
        $('input:radio[name=hoa_dues_per]').prop('required',false);
        $('#hoa_notes').prop('required',false);
        $('input:radio[name=is_property_second_hoa]').prop('required',false);
    }

    if ($this.attr('id') == 'yesHOA2') {
        $('#second_hoa_management_company_name').prop('required',true);
        $('#second_hoa_contact_person').prop('required',true);
        $('#second_hoa_email').prop('required',true);
        $('#second_hoa_phone').prop('required',true);
        $('#second_hoa_dues').prop('required',true);
        $('input:radio[name=second_hoa_dues_per]').prop('required',true);
        $('#second_hoa_notes').prop('required',true);
    } else if($this.attr('id') == 'noHOA2') {
        $('#second_hoa_management_company_name').prop('required',false);
        $('#second_hoa_contact_person').prop('required',false);
        $('#second_hoa_email').prop('required',false);
        $('#second_hoa_phone').prop('required',false);
        $('#second_hoa_dues').prop('required',false);
        $('input:radio[name=second_hoa_dues_per]').prop('required',false);
        $('#second_hoa_notes').prop('required',false);
    }

    if ($this.attr('id') == 'otherAddress') {    
        $('#forwarding_street_address').prop('required',true);
        $('#forwarding_street_address2').prop('required',true);
        $('#forwarding_city').prop('required',true);
        $('#forwarding_state').prop('required',true);
        $('#forwarding_zip_code').prop('required',true);
    } else if($this.attr('id') == 'sameAddress') {
        $('#forwarding_street_address').prop('required',false);
        $('#forwarding_street_address2').prop('required',false);
        $('#forwarding_city').prop('required',false);
        $('#forwarding_state').prop('required',false);
        $('#forwarding_zip_code').prop('required',false);
    }

    if(selectedvalue == 'marriedToOther'){
        $this.parents('.col-md-4').siblings().find(".spouseName").removeClass("d-none")
    }else{
        $this.parents('.col-md-4').siblings().find(".spouseName").addClass("d-none")
    }
});

$(document).ready(function() {
    $('#is_married').change(function() {
        if (this.checked) {
            $('#date_and_place_marriage').prop('required',true);
            $('#spouse_first_name').prop('required',true);
            $('#spouse_middle_name').prop('required',true);
            $('#spouse_last_name').prop('required',true);
            $('#spouse_maiden_name').prop('required',true);
            $('#spouse_date_of_birth').prop('required',true); 
            $('#spouse_home_phone_number').prop('required',true);
            $('#spouse_business_phone_number').prop('required',true);
            $('#spouse_birthplace').prop('required',true);
            $('#spouse_ssn').prop('required',true);
            $('#spouse_driver_license_no').prop('required',true); 
            $('#spouse_another_name_that_used').prop('required',true);
            $('#spouse_state_residence').prop('required',true);
            $('#spouse_lived_year').prop('required',true);
        } else {
            $('#date_and_place_marriage').prop('required',false);
            $('#spouse_first_name').prop('required',false);
            $('#spouse_middle_name').prop('required',false);
            $('#spouse_last_name').prop('required',false);
            $('#spouse_maiden_name').prop('required',false);
            $('#spouse_date_of_birth').prop('required',false);
            $('#spouse_home_phone_number').prop('required',false);
            $('#spouse_business_phone_number').prop('required',false);
            $('#spouse_birthplace').prop('required',false);
            $('#spouse_ssn').prop('required',false);
            $('#spouse_driver_license_no').prop('required',false);
            $('#spouse_another_name_that_used').prop('required',false);
            $('#spouse_state_residence').prop('required',false);
        }      
    });

    $('#is_domestic_partner').change(function() {
        if (this.checked) {
            $('#domestic_first_name').prop('required',true);
            $('#domestic_middle_name').prop('required',true);
            $('#domestic_last_name').prop('required',true);
            $('#domestic_date_of_birth').prop('required',true);
            $('#domestic_maiden_name').prop('required',true);
            $('#domestic_home_phone_number').prop('required',true); 
            $('#domestic_business_phone_number').prop('required',true);
            $('#domestic_birthplace').prop('required',true);
            $('#domestic_ssn').prop('required',true);
            $('#domestic_driver_license_no').prop('required',true);
            $('#domestic_another_name_that_used').prop('required',true); 
            $('#domestic_state_residence').prop('required',true);
            $('#domestic_lived_year').prop('required',true);
            $('#spouse_lived_year').prop('required',true);
        } else {
            $('#domestic_first_name').prop('required',false);
            $('#domestic_middle_name').prop('required',false);
            $('#domestic_last_name').prop('required',false);
            $('#domestic_date_of_birth').prop('required',false);
            $('#domestic_maiden_name').prop('required',false);
            $('#domestic_home_phone_number').prop('required',false);
            $('#domestic_business_phone_number').prop('required',false);
            $('#domestic_birthplace').prop('required',false);
            $('#domestic_ssn').prop('required',false);
            $('#domestic_driver_license_no').prop('required',false);
            $('#domestic_another_name_that_used').prop('required',false);
            $('#domestic_state_residence').prop('required',false);
            $('#domestic_lived_year').prop('required',false);
        }      
    });

    $('#is_loan').change(function() {
        if (this.checked) {
            $('#lender').prop('required',true);
            $('#loan_amount').prop('required',true);
            $('#loan_account').prop('required',true);
        } else {
            $('#lender').prop('required',false);
            $('#loan_amount').prop('required',false);
            $('#loan_account').prop('required',false);
        }      
    });
	
});

$("#borrower_seller_form").validate({
    ignore: false,
    errorClass: "error text-danger",
    validClass: "success text-success",
    highlight: function (element, errorClass) {
        
    },
    rules: {
        types_of_transfer: {
            required: true,
            minlength: 1,
        }
    },
    submitHandler: function (form) {
        document.forms["borrower_seller_form"].submit();
    },
    invalidHandler: function(e,validator) {
		for (var i=0;i<validator.errorList.length;i++){   
            $(validator.errorList[i].element).closest('.accordion-collapse').addClass('show');
            return false;
        }
    },
    errorPlacement: function(error, element) {
        var placement = $(element).data('error');
        if (placement) {
          $(placement).append(error)
        } else {
          error.insertAfter(element.parent());
        }
    }
});

$("#borrower_buyer_form").validate({
    ignore: false,
    errorClass: "error text-danger",
    validClass: "success text-success",
    highlight: function (element, errorClass) {
       
    },
    rules: {
        name: "required",
        email: {
            required: true,
            email: true,
        },
    },
    submitHandler: function (form) {
        document.forms["borrower_buyer_form"].submit();
    },
    invalidHandler: function(e,validator) {
        for (var i=0;i<validator.errorList.length;i++){   
            $(validator.errorList[i].element).closest('.accordion-collapse').addClass('show');
            return false;
        }
    },
    errorPlacement: function(error, element) {
        var placement = $(element).data('error');
        if (placement) {
          $(placement).append(error)
        } else {
          error.insertAfter(element);
        }
    }
});



$('#collapseTwo input[type="radio"]').change(function(){
    var $this = $(this);
    var selectedvalue = $this.val();
    if(selectedvalue == 'yes'){
        $this.parents("ul").siblings('.errorMsg').removeClass('d-none')
    }
    else{
        $this.parents("ul").siblings('.errorMsg').addClass('d-none')
    }
})

$('input[name="is_mortgage"]').change(function(){
    var $this = $(this);
    var selectedvalue = $this.val();
    if(selectedvalue == 'yes'){
        $("#mortgage").removeClass("d-none");
        $('#first_mortgage_company').prop('required',true);
        $('#first_mortgage_loan_number').prop('required',true);
        $('#first_mortgage_area_code').prop('required',true);
        $('#first_mortgage_phone_number').prop('required',true);
    } else {
        $("#mortgage").addClass("d-none");
        $('#first_mortgage_company').prop('required',false);
        $('#first_mortgage_loan_number').prop('required',false);
        $('#first_mortgage_area_code').prop('required',false);
        $('#first_mortgage_phone_number').prop('required',false);
    }
})

$('input[name="is_liens"]').change(function(){
    var $this = $(this);
    var selectedvalue = $this.val();
    if(selectedvalue == 'yes'){
        $("#lien").removeClass("d-none");
        $('#first_lien_holder_name').prop('required',true);
        $('#first_amount_owed').prop('required',true);
    } else {
        $("#lien").addClass("d-none");
        $('#first_lien_holder_name').prop('required',false);
        $('#first_amount_owed').prop('required',false);
    }
})

$('input[name="is_condominium"]').change(function(){
    var $this = $(this);
    var selectedvalue = $this.val();
    if(selectedvalue == 'yes'){
        $("#association").removeClass("d-none");
        $('#first_homeowners_association').prop('required',true);
        $('#first_property_management_company').prop('required',true);
        $('#first_property_management_number').prop('required',true);
    }
    else{
        $("#association").addClass("d-none");
        $('#first_homeowners_association').prop('required',false);
        $('#first_property_management_company').prop('required',false);
        $('#first_property_management_number').prop('required',false);
    }
})

$('input[name="sale_proceeds"]').change(function(){
    var $this = $(this);
    var selectedvalue = $this.val();
           
    if(selectedvalue == 'wire'){
        $('#wireInstructions').removeClass('d-none');
        $('#name_on_account').prop('required',true);
        $('#bank_name').prop('required',true);
        $('#bank_city').prop('required',true);
        $('#bank_state').prop('required',true);
        $('#account_number').prop('required',true);
        $('#routing_number').prop('required',true);
        $('#street_address_for_account').prop('required',true);
        $('#street_address2_for_account').prop('required',true);
        $('#city_for_account').prop('required',true);
        $('#state_for_account').prop('required',true);
        $('#zipcode_for_account').prop('required',true);
    } else {
        $('#wireInstructions').addClass('d-none');
        $('#name_on_account').prop('required',false);
        $('#bank_name').prop('required',false);
        $('#bank_city').prop('required',false);
        $('#bank_state').prop('required',false);
        $('#account_number').prop('required',false);
        $('#routing_number').prop('required',false);
        $('#street_address_for_account').prop('required',false);
        $('#street_address2_for_account').prop('required',false);
        $('#city_for_account').prop('required',false);
        $('#state_for_account').prop('required',false);
        $('#zipcode_for_account').prop('required',false);
    }

    if (selectedvalue == 'mailCheck'){
        $this.parents('.accordion-body').find('.mailAddress').removeClass('d-none');
        $('#closing_proceeds_street_address').prop('required',true);
        $('#closing_proceeds_street_address2').prop('required',true);
        $('#closing_proceeds_city').prop('required',true);
        $('#closing_proceeds_state').prop('required',true);
        $('#closing_proceeds_zipcode').prop('required',true);
    } else {
        $this.parents('.accordion-body').find('.mailAddress').addClass('d-none');
        $('#closing_proceeds_street_address').prop('required',false);
        $('#closing_proceeds_street_address2').prop('required',false);
        $('#closing_proceeds_city').prop('required',false);
        $('#closing_proceeds_state').prop('required',false);
        $('#closing_proceeds_zipcode').prop('required',false);
    }
})

$('input[name="co_seller"]').change(function(){
    var $this = $(this);
    var selectedvalue = $this.val();
    if(selectedvalue == 'yes'){
        $(".coSellerInfo").removeClass("d-none")
    }
    else{
        $(".coSellerInfo").addClass("d-none")
    }
})

$('input[name="is_correct_property_address"]').change(function(){
    var $this = $(this);
    var selectedvalue = $this.val();
    if (selectedvalue == 'no'){
        $(".otherPropertyAddress").removeClass("d-none")
    } else {
        $(".otherPropertyAddress").addClass("d-none")
    }
})

$('input[name="is_property_address_as_current_address"]').change(function(){
    var $this = $(this);
    var selectedvalue = $this.val();
    console.log(selectedvalue);
    if(selectedvalue == 'no'){
        console.log(selectedvalue);
        $(".otherCurrentAddress").removeClass("d-none")
    } else {
        $(".otherCurrentAddress").addClass("d-none")
    }
})

$('input[name="is_forwarding_address_different_from_current_address"]').change(function(){
    var $this = $(this);
    var selectedvalue = $this.val();
    if (selectedvalue == 'yes'){
        $(".otherForwardAddress").removeClass("d-none")
    } else {
        $(".otherForwardAddress").addClass("d-none")
    }
})

$('input[name="is_same_property_address_as_forwarding_address"]').change(function(){
    var $this = $(this);
    var selectedvalue = $this.val();
    if (selectedvalue == 'other'){
        $(".otherFowardingAddressClose").removeClass("d-none")
    } else {
        $(".otherFowardingAddressClose").addClass("d-none")
    }
})

$('input[name="is_insurance_policy"]').change(function(){
    var $this = $(this);
    var selectedvalue = $this.val();
    if(selectedvalue == 'yes') {
        $(".insurance_policy_file_name").removeClass("d-none")
    } else {
        $(".insurance_policy_file_name").addClass("d-none")
    }
})

$('input[name="is_real_estate"]').change(function(){
    var $this = $(this);
    var selectedvalue = $this.val();
    if(selectedvalue == 'yes'){
        $(".RealEstateInfo").removeClass("d-none")
    }
    else{
        $(".RealEstateInfo").addClass("d-none")
    }
})

$('input[name="seller_invoices[]"]').change(function(){
    var $this = $(this);
    var selectedvalue = $this.val();
    console.log(selectedvalue);
    if ($this.is(":checked") && selectedvalue !== 'none') {
        $(".seller_invoices_files").removeClass("d-none")
    } else {
        $(".seller_invoices_files").addClass("d-none")
    }
})

$('input[name="is_mortgage_credit"],input[name="is_second_mortgage_credit"]').change(function(){
    var $this = $(this);
    var selectedvalue = $this.val();
    if(selectedvalue == 'yes'){
        $this.parents('ul').siblings(".CreditCardLock").removeClass("d-none")
    }
    else{
        $this.parents('ul').siblings(".CreditCardLock").addClass("d-none")
    }
})

$('input[name="is_second_mortgage"]').change(function(){
    var $this = $(this);
    var selectedvalue = $this.val();
    if(selectedvalue == 'yes'){
        $(".secondMortgage").removeClass("d-none")
    }
    else{
        $(".secondMortgage").addClass("d-none")
    }
})

$('input[name="is_property_hoa"]').change(function(){
    var $this = $(this);
    var selectedvalue = $this.val();
    if(selectedvalue == 'yes'){
        $(".homeOwner").removeClass("d-none")
    }
    else{
        $(".homeOwner").addClass("d-none")
    }
})

$('input[name="is_property_second_hoa"]').change(function(){
    var $this = $(this);
    var selectedvalue = $this.val();
    if(selectedvalue == 'yes'){
        $(".homeOwner2").removeClass("d-none")
    }
    else{
        $(".homeOwner2").addClass("d-none")
    }
})

$('input[name="is_attorney"]').change(function(){
    var $this = $(this);
    var selectedvalue = $this.val();
    if(selectedvalue == 'yes'){
        $(".attorneyInfo").removeClass("d-none")
    }
    else{
        $(".attorneyInfo").addClass("d-none")
    }
})

$(function () {
    if($("#canvas1").length > 0) {
        window.signaturePad1 = new SignaturePad($('#canvas1').get(0), {
        });
    }
})
  
var clear1 = function () {
    window.signaturePad1.clear()
}  
function condtionalRequired(checkbox_element,elements_paernt) {
	if($(checkbox_element).is(":checked")) {
		$(elements_paernt+" input[type=text]").each(function(){
			if(!($(this).hasClass('optional-input'))) {
				$(this).prop('required',true);
			}
		});
	}
	else {
		$(elements_paernt+" input[type=text]").each(function(){
			$(this).prop('required',false);
		});
	}
}
$('#checkEleven').change(function(){
	condtionalRequired('#checkEleven','#borrower_seller_form #collapseTwenty .part_5_parent');
});
$('#checkThree').change(function(){
	condtionalRequired('#checkThree','#borrower_seller_form #collapseTwenty .part_6_parent');
});
$(document).ready(function(){
	$("#borrower_seller_form #collapseTwenty input[type=text]").each(function(){
		if(!($(this).hasClass('optional-input'))) {
			$(this).prop('required',true);
		}
	});
	$("#borrower_seller_form #collapseFourteen input[type=text]").each(function(){
		if(!($(this).hasClass('optional-input'))) {
			$(this).prop('required',true);
		}
	});
	$("#borrower_seller_form #collapse22 input[type=text]").each(function(){
		if(!($(this).hasClass('optional-input'))) {
			$(this).prop('required',true);
		}
	});
	condtionalRequired('#checkEleven','#borrower_seller_form #collapseTwenty .part_5_parent');
	condtionalRequired('#checkThree','#borrower_seller_form #collapseTwenty .part_6_parent');
});

















