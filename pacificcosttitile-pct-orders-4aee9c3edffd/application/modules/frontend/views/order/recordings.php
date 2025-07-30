

	

	<div class="rtd typography-page">
		<div class="typography-section typography-section-border">
			<div class="container">
				<div class="row">
					<div class="col-xs-12">
						<!--  <h2 class="typography-title">Tables</h2> -->
						<div class="table-responsive">
							<table class="table table-type-3 typography-last-elem no-footer" id="table-recordings" width="100%" cellspacing="0">
								<thead>
									<tr>
										<th>Date</th>
										<th>Instrument #</th>
										<th>Order #</th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>

	</div>
	

<script>
	$(document).ready(function () {
		
		if ($('#table-recordings').length) {
			customer_list = $('#table-recordings').DataTable({
				// "pageLength": 1,
				"paging": true,
				"lengthChange": false,
				"language": {
					paginate: {
						next: '<span class="fa fa-angle-right"></span>',
						previous: '<span class="fa fa-angle-left"></span>',
					},
					"emptyTable": "Record(s) not found.",
                },
                "searching": false,
				initComplete: function () {
					
					
                },
				dom: 'Bfrtip',
				buttons: [],
				"drawCallback": function () {
					
				},
				"ordering": false,
				"serverSide": true,
				"ajax": {
					url: base_url + "order/get-recordings", // json datasource
					type: "post", // method  , by default get
					error: function (XMLHttpRequest, textStatus, errorThrown) {
						if (parseInt(XMLHttpRequest.status) == 419) {
							alert("You are logged out. Please login.");
						}
						if (parseInt(XMLHttpRequest.status) == 419) {
							setTimeout(function () {
								location.reload();
							}, 1000);
						}
						$("#table-recordings tbody").append(
							'<tr><td colspan="3" class="text-center">No records found</td></tr>');
						$("#table-recordings_processing").css("display", "none");

					}
				}
			});
		}
	});

</script>
