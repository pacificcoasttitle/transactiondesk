$(document).ready(function () {
	getDashboardCountBasedOnFilter(0, 0, 0);
});

$(document).on("click", function() { 
    $("#month").unbind().on("change", function(){
		var month = $(this).val();
		var user = 0;
		var manager = 0;
		getDashboardCountBasedOnFilter(manager, user, month);
	});
});

function getDashboardCountBasedOnFilter(manager_id, user_id, month)
{
	$('#page-preloader').css('background-color', 'rgba(0,0,0,.5)');
	$('#page-preloader').css('display', 'block');
	$.ajax({
		url: base_url + "escrow/admin/get-dashboard-count", 
		type: "post",
		data: {
			manager_id: manager_id,
			user_id: user_id,
			month: month
		},
		dataType: "html",
		success: function (response) {
			var results = JSON.parse(response);
			$('#dashboard_count').html(results);
			$('#page-preloader').css('display', 'none');
		}
	});
}


