$(document).ready(function () {
	$('#supplemental_report_date').datepicker().datepicker("setDate", new Date());
	$('#edit_supplemental_report_date').datepicker().datepicker("setDate", new Date());

	if ($('#orders_listing').length) {
		order_list = $('#orders_listing').DataTable({
			// "pageLength": 2,
			"paging": true,
			"lengthChange": false,
			"language": {
				searchPlaceholder: "Search File# or Address",
				paginate: {
					next: '<span class="fa fa-angle-right"></span>',
					previous: '<span class="fa fa-angle-left"></span>',
				},
				"emptyTable": "Record(s) not found.",
				"search": "",
			},
			/*"searching": false,*/
			initComplete: function () {


			},
			dom: 'Bfrtip',
			buttons: [],
			"drawCallback": function () {

			},
			"ordering": false,
			"serverSide": true,
			"ajax": {
				url: base_url + "get-proposed-orders",
				type: "post",
				beforeSend: function () {
					$('#page-list-loader').css('background-color', 'rgba(0,0,0,.5)');
					$('#page-list-loader').css('display', 'block');
				},
				error: function (XMLHttpRequest, textStatus, errorThrown) {
					if (parseInt(XMLHttpRequest.status) == 419) {
						alert("You are logged out. Please login.");
					}
					if (parseInt(XMLHttpRequest.status) == 419) {
						setTimeout(function () {
							location.reload();
						}, 1000);
					}
					$("#orders_listing tbody").append(
						'<tr><td colspan="4" class="text-center">No records found</td></tr>');
					$("#orders_listing_processing").css("display", "none");

				},
				complete: function () {
					$("#page-list-loader").hide();
					$('#page-list-loader').css('display', 'none');
				}
			}
		});
	}

	if (jQuery('#add-order-details').length) {
		jQuery('#add-order-details').validate({
			ignore: ":not(:visible)",
			rules: {
				LenderCompany: "required",
				LenderEmailAddress: "required",
				// LenderName:"required",
				TitleOfficer: "required",
				// loan_amount:"required",
				loan_number: "required",
				primary_first_name: "required",
				// primary_last_name:"required",
				supplemental_report_date: "required",
				preliminary_report_date: "required",
				branch: "required",
			},
			messages: {
				TitleOfficer: "Please select title officer",
				loan_number: "Please enter loan number",
				borrower: "Please enter borrower",
				lender: "Please enter lender",
				supplemental_report_date: "Please select date",
				preliminary_report_date: "Please select date",
				branch: "Please select branch",
			},
			submitHandler: function (form) {
				$('#page-preloader').css('background-color', 'rgba(0,0,0,.5)');
				$('#page-preloader').css('display', 'block');
				var LenderCompany = $('#LenderCompany').val();
				var assignment_clause = $('#assignment_clause').val();
				var LenderEmailAddress = $('#LenderEmailAddress').val();
				var LenderState = $('#LenderState').val();
				var LenderName = $('#LenderName').val();
				var LenderAddress = $('#LenderAddress').val();
				var LenderCity = $('#LenderCity').val();
				var LenderZipcode = $('#LenderZipcode').val();
				var TitleOfficer = $('#TitleOfficer').val();
				var loan_amount = $('#loan_amount').val();
				var loan_number = $('#loan_number').val();
				// var primary_first_name = $('#primary_first_name').val();
				// var primary_last_name = $('#primary_last_name').val();
				// var secondary_first_name = $('#first_name').val();
				// var secondary_last_name = $('#last_name').val();
				// var vesting = $('#vesting').val();
				var borrowers_vesting = $('#borrowers_vesting').val();
				var LenderId = $('#LenderId').val();
				var orderId = $('#orderId').val();
				var transaction_id = $('#transaction_id').val();
				var property_id = $('#property_id').val();
				var property_address = $('#property_address').val();
				var property_city = $('#property_city').val();
				var property_state = $('#property_state').val();
				var property_zipcode = $('#property_zipcode').val();
				var fileId = $('#fileId').val();
				var supplemental_report_date = $('#supplemental_report_date').val();
				var preliminary_report_date = $('#preliminary_report_date').val();
				var new_existing_lender = $('input[name="new_existing_lender"]:checked').val();
				var branch = $('#branch').val();

				$.ajax({
					url: base_url + "add-order-details",
					type: "post",
					data: {
						TitleOfficer: TitleOfficer,
						loan_amount: loan_amount,
						loan_number: loan_number,
						borrowers_vesting: borrowers_vesting,
						property_address: property_address,
						property_city: property_city,
						property_state: property_state,
						property_zipcode: property_zipcode,
						LenderId: LenderId,
						LenderCompany: LenderCompany,
						assignment_clause: assignment_clause,
						LenderEmailAddress: LenderEmailAddress,
						LenderState: LenderState,
						LenderName: LenderName,
						LenderAddress: LenderAddress,
						LenderCity: LenderCity,
						LenderZipcode: LenderZipcode,
						orderId: orderId,
						transaction_id: transaction_id,
						property_id: property_id,
						fileId: fileId,
						s_report_date: supplemental_report_date,
						p_report_date: preliminary_report_date,
						new_existing_lender: new_existing_lender,
						branch: branch
					},
					success: function (response) {
						$('#page-preloader').css('display', 'none');
						var res = JSON.parse(response);
						if (res.status == 'success') {
							$('#lender_information').modal('hide');
							if (res.data) {
								var binaryData = res.data;
								downloadFile(binaryData);
							}
							location.reload(true);
						} else if (res.status == 'error') {
							$('.modal-body.search-result').append('<div class="error">Something went wrong. Please try again.</div>');
							$('#lender_information').modal('hide');
						}
					}
				});
			}
		});
	}

	/* Lender autocomplete */
	$("#LenderCompany").focusin(function () {
		if ($('input[name="new_existing_lender"]:checked').val() == 'existing_lender') {
			if ($('.ui-widget.ui-autocomplete').length > 0) {
				$('#LenderCompany').autocomplete("enable");
			}
			$("#LenderCompany").autocomplete({
				source: function (request, response) {
					$.ajax({
						url: base_url + 'getDetailsByName',
						data: {
							term: request.term, //the value of the input is here
							is_escrow: 0
						},
						type: "POST",
						dataType: "json",
						success: function (data) {
							if (data.length > 0) {
								response($.map(data, function (item) {
									return item;
								}))
							} else {
								response([{
									label: 'No results found.',
									val: -1
								}]);
							}
						}
					});
				},
				delay: 0,
				minLength: 3,
				select: function (event, ui) {
					event.preventDefault();
					$("#LenderCompany").val(ui.item.company).parent().addClass('state-success');

					if (ui.item.email_address) {
						$("#LenderEmailAddress").val(ui.item.email_address).parent().addClass('state-success');

					} else {
						$("#LenderEmailAddress").val('').removeAttr('readonly').parent().removeClass('state-success').addClass('state-error');
					}

					if (ui.item.state) {
						$("#LenderState").val(ui.item.state).parent().addClass('state-success');
					} else {
						$("#LenderState").val('').removeAttr('readonly').parent().removeClass('state-success').addClass('state-error');
					}

					if (ui.item.name) {
						$("#LenderName").val(ui.item.name).parent().addClass('state-success');
					} else {
						$("#LenderName").val('').removeAttr('readonly').parent().removeClass('state-success').addClass('state-error');
					}

					if (ui.item.address) {
						$("#LenderAddress").val(ui.item.address).parent().addClass('state-success');
					} else {
						$("#LenderAddress").val('').removeAttr('readonly').parent().removeClass('state-success').addClass('state-error');
					}

					if (ui.item.city) {
						$("#LenderCity").val(ui.item.city).parent().addClass('state-success');
					} else {
						$("#LenderCity").val('').removeAttr('readonly').parent().removeClass('state-success').addClass('state-error');
					}

					if (ui.item.zip_code) {
						$("#LenderZipcode").val(ui.item.zip_code).parent().addClass('state-success');
					} else {
						$("#LenderZipcode").val('').removeAttr('readonly').parent().removeClass('state-success').addClass('state-error');
					}

					if (ui.item.assignment_clause) {
						$("#assignment_clause").val(ui.item.assignment_clause);
					} else {
						$("#assignment_clause").val('');
					}

					$("#LenderId").val(ui.item.id);
				},
				change: function (event, ui) {
					if (ui.item == null) {
						$("#LenderEmailAddress").val('').removeAttr('readonly').parent().removeClass('state-success').addClass('state-error');
						$("#LenderState").val('').removeAttr('readonly').parent().removeClass('state-success').addClass('state-error');
						$("#LenderCompany").val('').removeAttr('readonly').parent().removeClass('state-success').addClass('state-error');
						$("#LenderAddress").val('').removeAttr('readonly').parent().removeClass('state-success').addClass('state-error');
						$("#LenderCity").val('').removeAttr('readonly').parent().removeClass('state-success').addClass('state-error');
						$("#LenderZipcode").val('').removeAttr('readonly').parent().removeClass('state-success').addClass('state-error');
						$("#assignment_clause").val('');
						$("#LenderId").val('');
					}
				}
			});
		} else {
			if ($('.ui-widget.ui-autocomplete').length > 0) {
				$('#LenderCompany').autocomplete("disable");
			}
		}
	});

	$("#edit_LenderCompany").focusin(function () {
		if ($('input[name="edit_new_existing_lender"]:checked').val() == 'existing_lender') {
			if ($('.ui-widget.ui-autocomplete').length > 0) {
				$('#edit_LenderCompany').autocomplete("enable");
			}
			$("#edit_LenderCompany").autocomplete({
				source: function (request, response) {
					$.ajax({
						url: base_url + 'getDetailsByName',
						data: {
							term: request.term, //the value of the input is here
							is_escrow: 0
						},
						type: "POST",
						dataType: "json",
						success: function (data) {
							if (data.length > 0) {
								response($.map(data, function (item) {
									return item;
								}))
							} else {
								response([{
									label: 'No results found.',
									val: -1
								}]);
							}
						}
					});
				},
				delay: 0,
				minLength: 3,
				select: function (event, ui) {
					event.preventDefault();

					$("#edit_LenderCompany").val(ui.item.company).parent().addClass('state-success');

					if (ui.item.email_address) {
						$("#edit_LenderEmailAddress").val(ui.item.email_address).parent().addClass('state-success');

					} else {
						$("#edit_LenderEmailAddress").val('').removeAttr('readonly').parent().removeClass('state-success').addClass('state-error');
					}

					if (ui.item.state) {
						$("#edit_LenderState").val(ui.item.state).parent().addClass('state-success');
					} else {
						$("#edit_LenderState").val('').removeAttr('readonly').parent().removeClass('state-success').addClass('state-error');
					}

					if (ui.item.name) {
						$("#edit_LenderName").val(ui.item.name).parent().addClass('state-success');
					} else {
						$("#edit_LenderName").val('').removeAttr('readonly').parent().removeClass('state-success').addClass('state-error');
					}

					if (ui.item.address) {
						$("#edit_LenderAddress").val(ui.item.address).parent().addClass('state-success');
					} else {
						$("#edit_LenderAddress").val('').removeAttr('readonly').parent().removeClass('state-success').addClass('state-error');
					}

					if (ui.item.city) {
						$("#edit_LenderCity").val(ui.item.city).parent().addClass('state-success');
					} else {
						$("#edit_LenderCity").val('').removeAttr('readonly').parent().removeClass('state-success').addClass('state-error');
					}

					if (ui.item.zip_code) {
						$("#edit_LenderZipcode").val(ui.item.zip_code).parent().addClass('state-success');
					} else {
						$("#edit_LenderZipcode").val('').removeAttr('readonly').parent().removeClass('state-success').addClass('state-error');
					}
					if (ui.item.assignment_clause) {
						$("#edit_assignment_clause").val(ui.item.assignment_clause);
					} else {
						$("#edit_assignment_clause").val('');
					}

					$("#edit_LenderId").val(ui.item.id);
				},
				change: function (event, ui) {
					if (ui.item == null) {
						$("#edit_LenderEmailAddress").val('').removeAttr('readonly').parent().removeClass('state-success').addClass('state-error');
						$("#edit_LenderState").val('').removeAttr('readonly').parent().removeClass('state-success').addClass('state-error');
						$("#edit_LenderCompany").val('').removeAttr('readonly').parent().removeClass('state-success').addClass('state-error');
						$("#edit_LenderAddress").val('').removeAttr('readonly').parent().removeClass('state-success').addClass('state-error');
						$("#edit_LenderCity").val('').removeAttr('readonly').parent().removeClass('state-success').addClass('state-error');
						$("#edit_LenderZipcode").val('').removeAttr('readonly').parent().removeClass('state-success').addClass('state-error');
						$("#edit_assignment_clause").val('');
						$("#edit_LenderId").val('');
					}
				}
			});
		} else {
			if ($('.ui-widget.ui-autocomplete').length > 0) {
				$('#edit_LenderCompany').autocomplete("disable");
			}
		}
	});

	/* Lender autocomplete */
	/* Edit modal validations */
	if (jQuery('#edit-order-details').length) {
		jQuery('#edit-order-details').validate({
			ignore: ":not(:visible)",
			rules: {
				LenderCompany: "required",
				LenderEmailAddress: "required",
				// LenderName:"required",
				TitleOfficer: "required",
				// loan_amount:"required",
				loan_number: "required",
				primary_first_name: "required",
				// primary_last_name:"required",
				supplemental_report_date: "required",
				preliminary_report_date: "required",
				edit_branch: "required",
			},
			messages: {
				TitleOfficer: "Please select title officer",
				loan_number: "Please enter loan number",
				borrower: "Please enter borrower",
				lender: "Please enter lender",
				supplemental_report_date: "Please select date",
				preliminary_report_date: "Please select date",
				edit_branch: "Please select branch",
			},
			submitHandler: function (form) {
				$('#page-preloader').css('background-color', 'rgba(0,0,0,.5)');
				$('#page-preloader').css('display', 'block');
				var LenderCompany = $('#edit_LenderCompany').val();
				var LenderEmailAddress = $('#edit_LenderEmailAddress').val();
				var LenderState = $('#edit_LenderState').val();
				var LenderName = $('#edit_LenderName').val();
				var LenderAddress = $('#edit_LenderAddress').val();
				var assignment_clause = $('#edit_assignment_clause').val();
				var LenderCity = $('#edit_LenderCity').val();
				var LenderZipcode = $('#edit_LenderZipcode').val();
				var TitleOfficer = $('#edit_TitleOfficer').val();
				var loan_amount = $('#edit_loan_amount').val();
				var loan_number = $('#edit_loan_number').val();
				var LenderId = $('#edit_LenderId').val();
				var orderId = $('#edit_orderId').val();
				var transaction_id = $('#edit_transaction_id').val();
				var property_id = $('#edit_property_id').val();
				var borrowers_vesting = $('#edit_borrowers_vesting').val();
				var property_address = $('#edit_property_address').val();
				var property_city = $('#edit_property_city').val();
				var property_state = $('#edit_property_state').val();
				var property_zipcode = $('#edit_property_zipcode').val();
				var fileId = $('#edit_fileId').val();
				var supplemental_report_date = $('#edit_supplemental_report_date').val();
				var preliminary_report_date = $('#edit_preliminary_report_date').val();
				var new_existing_lender = $('input[name="edit_new_existing_lender"]:checked').val();
				var branch = $('#edit_branch').val();

				$.ajax({
					url: base_url + "add-order-details",
					type: "post",
					data: {
						TitleOfficer: TitleOfficer,
						loan_amount: loan_amount,
						loan_number: loan_number,
						LenderId: LenderId,
						LenderCompany: LenderCompany,
						assignment_clause: assignment_clause,
						LenderEmailAddress: LenderEmailAddress,
						LenderState: LenderState,
						LenderName: LenderName,
						LenderAddress: LenderAddress,
						LenderCity: LenderCity,
						LenderZipcode: LenderZipcode,
						orderId: orderId,
						transaction_id: transaction_id,
						property_id: property_id,
						borrowers_vesting: borrowers_vesting,
						property_address: property_address,
						property_city: property_city,
						property_state: property_state,
						property_zipcode: property_zipcode,
						fileId: fileId,
						s_report_date: supplemental_report_date,
						p_report_date: preliminary_report_date,
						new_existing_lender: new_existing_lender,
						branch: branch
					},
					success: function (response) {
						$('#page-preloader').css('display', 'none');
						var res = JSON.parse(response);
						if (res.status == 'success') {
							$('#edit-data-result').html('<div class="alert alert-success">Data updated successfully</div>');
							if (res.data) {
								var binaryData = res.data;
								downloadFile(binaryData);
							}

							location.reload(true);
						} else if (res.status == 'error') {
							$('#edit-data-result').html('<div class="alert alert-error">Something went wrong. Please try again.</div>');
						}
						$('#edit-data-result').fadeOut(5000, function () {
							$('#edit_information').modal('hide');
						});
					}
				});
			}
		});
	}
	/* Edit modal validations */

	$("input[name=new_existing_lender]").change(function () {
		$("#LenderEmailAddress").val('');
		$("#LenderName").val('');
		$("#LenderState").val('');
		$("#LenderCompany").val('');
		$("#LenderAddress").val('');
		$("#LenderCity").val('');
		$("#LenderZipcode").val('');
		$("#assignment_clause").val('');
		$("#LenderId").val('');
	});

	$("input[name=edit_new_existing_lender]").change(function () {
		$("#edit_LenderEmailAddress").val('');
		$("#edit_LenderName").val('');
		$("#edit_LenderState").val('');
		$("#edit_LenderCompany").val('');
		$("#edit_LenderAddress").val('');
		$("#edit_LenderCity").val('');
		$("#edit_LenderZipcode").val('');
		$("#edit_assignment_clause").val('');
		$("#edit_LenderId").val('');
	});
});

function generateProposedInsured(fileId) {
	if (fileId) {
		$('#page-preloader').css('background-color', 'rgba(0,0,0,.5)');
		$('#page-preloader').css('display', 'block');
		$.ajax({
			url: base_url + "generate-proposed-insured",
			type: "post",
			data: {
				fileId: fileId,
			},
			success: function (response) {
				var res = JSON.parse(response);
				var dataRequired = 0;
				if (res.status == 'success') {
					$("#LenderName").val(res.orderDetails['lender_name']);
					$("#LenderEmailAddress").val(res.orderDetails['lender_email']);
					$("#LenderState").val(res.orderDetails['lender_state']);
					$("#LenderCompany").val(res.orderDetails['lender_company_name']);
					$("#assignment_clause").val(res.orderDetails['lender_assignment_clause']);
					$("#LenderAddress").val(res.orderDetails['lender_address']);
					$("#LenderCity").val(res.orderDetails['lender_city']);
					$("#LenderZipcode").val(res.orderDetails['lender_zipcode']);
					$("#LenderId").val(res.orderDetails['lender_id']);
					$("#property_address").val(res.orderDetails['street_address']);
					$("#property_city").val(res.orderDetails['property_city']);
					$("#property_state").val(res.orderDetails['property_state']);
					$("#property_zipcode").val(res.orderDetails['property_zip']);
					$("#loan_amount").val(res.orderDetails['loan_amount']);
					$("#loan_number").val(res.orderDetails['loan_number']);


					$("#TitleOfficer").val(res.orderDetails['title_officer']);


					if (res.orderDetails['supplemental_report_date'] == null || res.orderDetails['supplemental_report_date'] == undefined || res.orderDetails['supplemental_report_date'].length == 0) {

						// $('#edit_preliminary_report_date').val(res.orderDetails['preliminary_report_date']);
					} else {
						$('#supplemental_report_date').val(res.orderDetails['supplemental_report_date']);
					}
					/*$('#supplemental_report_date').val(res.orderDetails['supplemental_report_date']);
					$('#preliminary_report_date').val(res.orderDetails['preliminary_report_date']);*/
					$("#borrowers_vesting").val(res.orderDetails['borrowers_vesting']);
					if (res.orderDetails['lender_id'] != '') {
						$("#existing_lender").prop("checked", true);
						// $('input[name=new_existing_lender]').attr("disabled",true);
					} else {
						// $('input[name=new_existing_lender]').attr("disabled",false);
						$("#add_lender").prop("checked", true)
					}

				}

				$('#page-preloader').css('display', 'none');

				$('#LenderId').val(res.orderDetails.lender_id);
				$('#orderId').val(res.orderDetails.orderId);
				$('#transaction_id').val(res.orderDetails.transaction_id);
				$('#property_id').val(res.orderDetails.property_id);
				$('#fileId').val(res.orderDetails.fileId);

				$('#lender_information').modal('show');
			}
		});
	} else {
		alert("File ID required.");
	}
}

function base64toBlob(base64Data, contentType) {
	contentType = contentType || '';
	var sliceSize = 1024;
	var byteCharacters = (base64Data);
	var bytesLength = byteCharacters.length;
	var slicesCount = Math.ceil(bytesLength / sliceSize);
	var byteArrays = new Array(slicesCount);

	for (var sliceIndex = 0; sliceIndex < slicesCount; ++sliceIndex) {
		var begin = sliceIndex * sliceSize;
		var end = Math.min(begin + sliceSize, bytesLength);

		var bytes = new Array(end - begin);
		for (var offset = begin, i = 0; offset < end; ++i, ++offset) {
			bytes[i] = byteCharacters[offset].charCodeAt(0);
		}
		byteArrays[sliceIndex] = new Uint8Array(bytes);
	}
	return new Blob(byteArrays, {
		type: contentType
	});
}

function editInformation(fileId) {
	if (fileId) {
		$('#page-preloader').css('background-color', 'rgba(0,0,0,.5)');
		$('#page-preloader').css('display', 'block');

		$.ajax({
			// url: base_url + "get-order-details",
			url: base_url + "generate-proposed-insured",
			type: "post",
			data: {
				fileId: fileId,
			},
			success: function (response) {
				$('#page-preloader').css('display', 'none');

				var res = JSON.parse(response);
				if (res.status == 'success') {
					if (res.status == 'success') {
						/*if(res.orderDetails['is_escrow'] == 1)
						{
							$('#edit-order-details #lender-details-fields').show();
						}
						else
						{
							$('#edit-order-details #lender-details-fields').hide();
						}*/
						$("#edit_LenderName").val(res.orderDetails['lender_name']);
						// $("#edit_LenderEmailAddress").val(res.orderDetails['lender_email']);
						$("#edit_LenderState").val(res.orderDetails['lender_state']);
						$("#edit_LenderCompany").val(res.orderDetails['lender_company_name']);
						$("#edit_assignment_clause").val(res.orderDetails['lender_assignment_clause']);
						$("#edit_LenderAddress").val(res.orderDetails['lender_address']);
						$("#edit_LenderCity").val(res.orderDetails['lender_city']);
						$("#edit_LenderZipcode").val(res.orderDetails['lender_zipcode']);
						$("#edit_LenderId").val(res.orderDetails['lender_id']);


						$("#edit_property_address").val(res.orderDetails['street_address']);
						$("#edit_property_city").val(res.orderDetails['property_city']);
						$("#edit_property_state").val(res.orderDetails['property_state']);
						$("#edit_property_zipcode").val(res.orderDetails['property_zip']);
						/*$("#edit_primary_first_name").val(res.orderDetails['primary_owner_first_name']);
						$("#edit_primary_last_name").val(res.orderDetails['primary_owner_last_name']);
						$("#edit_vesting").val(res.orderDetails['vesting']);
						

						$("#edit_first_name").val(res.orderDetails['secondary_owner_first_name']);
						$("#edit_last_name").val(res.orderDetails['secondary_owner_last_name']);*/

						$("#edit_borrowers_vesting").val(res.orderDetails['borrowers_vesting']);
						$("#edit_loan_amount").val(res.orderDetails['loan_amount']);
						$("#edit_loan_number").val(res.orderDetails['loan_number']);


						$("#edit_TitleOfficer").val(res.orderDetails['title_officer']);
						$("#edit_branch").val(res.orderDetails['proposed_branch_id']);


						if (res.orderDetails['supplemental_report_date'] == null || res.orderDetails['supplemental_report_date'] == undefined || res.orderDetails['supplemental_report_date'].length == 0) {

							// $('#edit_preliminary_report_date').val(res.orderDetails['preliminary_report_date']);
						} else {
							$('#edit_supplemental_report_date').val(res.orderDetails['supplemental_report_date']);
						}
					}
					$('#edit_LenderId').val(res.orderDetails.lender_id);
					if (res.orderDetails['lender_id'] != '') {
						$("#edit_existing_lender").prop("checked", true);
						// $('input[name=new_existing_lender]').attr("disabled",true);	
					} else {
						// $('input[name=new_existing_lender]').attr("disabled",false);	
						$("#edit_add_lender").prop("checked", true);
					}
					$('#edit_orderId').val(res.orderDetails.orderId);
					$('#edit_transaction_id').val(res.orderDetails.transaction_id);
					$('#edit_property_id').val(res.orderDetails.property_id);
					$('#edit_fileId').val(res.orderDetails.fileId);
					$('#edit_information').modal('show');
				} else {
					alert("Something went wrong. Please try again.");
				}
			}
		});
	} else {
		alert("File ID required.");
	}
}

function downloadFile(binaryData) {
	if (navigator.msSaveBlob) {
		var csvData = base64toBlob(binaryData, 'application/octet-stream');
		var csvURL = navigator.msSaveBlob(csvData, 'ProposedInsured.pdf');
		var element = document.createElement('a');
		element.setAttribute('href', csvURL);
		element.setAttribute('download', 'ProposedInsured.pdf');
		element.style.display = 'none';
		document.body.appendChild(element);
		document.body.removeChild(element);
	} else {

		var csvURL = 'data:application/octet-stream;base64,' + binaryData;
		var element = document.createElement('a');
		element.setAttribute('href', csvURL);
		element.setAttribute('download', 'ProposedInsured.pdf');
		element.style.display = 'none';
		document.body.appendChild(element);
		element.click();
		document.body.removeChild(element);
	}
}

function downloadDocumentFromAws(url, documentType) {
	$('#page-preloader').css('background-color', 'rgba(0,0,0,.5)');
	$('#page-preloader').css('display', 'block');
	var fileNameIndex = url.lastIndexOf("/") + 1;
	var filename = url.substr(fileNameIndex);
	$.ajax({
		url: base_url + "download-aws-document",
		type: "post",
		data: {
			url: url
		},
		async: false,
		success: function (response) {
			if (response) {
				if (navigator.msSaveBlob) {
					var csvData = base64toBlob(response, 'application/octet-stream');
					var csvURL = navigator.msSaveBlob(csvData, filename);
					var element = document.createElement('a');
					element.setAttribute('href', csvURL);
					element.setAttribute('download', documentType + "_" + filename);
					element.style.display = 'none';
					document.body.appendChild(element);
					document.body.removeChild(element);
				} else {
					console.log(response);
					var csvURL = 'data:application/octet-stream;base64,' + response;
					var element = document.createElement('a');
					element.setAttribute('href', csvURL);
					element.setAttribute('download', documentType + "_" + filename);
					element.style.display = 'none';
					document.body.appendChild(element);
					element.click();
					document.body.removeChild(element);
				}
			}
			$('#page-preloader').css('display', 'none');
		}
	});
}
