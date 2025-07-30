<style>

.date-range-control {
    cursor: pointer;
    padding: 5px 10px;
    border-radius: 0.2rem;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #ced4da;
    width: 230px;
    display: inline-block;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out, -webkit-box-shadow 0.15s ease-in-out;
}

div.dataTables_wrapper div.dataTables_filter {
    text-align: left;
}
</style>

<div class="container-fluid">
    <div class="row mb-3">
		<div class="col-sm-6">
			<h1 class="h3 text-gray-800">Tax Data</h1>
		</div>
	</div>
    <!-- DataTables Example -->
    <div class="card shadow mb-4">
        <div class="card-header datatable-header py-3">
            <div class="datatable-header-titles" > 
                <span>
                    <i class="fas fa-history"></i>
                </span>
                <h6 class="m-0 font-weight-bold text-primary pl-10">Tax Data</h6> 
            </div>
        </div>

                
        <div class="card-body">
            <div id="customer_success_msg" class="w-100 alert alert-success alert-dismissible" style="display:none;"></div>
            <div id="customer_error_msg" class="w-100 alert alert-danger alert-dismissible" style="display:none;"></div>
            <div class="table-responsive">
                <table class="table table-bordered" id="tbl-tax-data-listing" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Sr No</th>
                            <th>Order No</th>
                            <th>Property Address</th>
                            <th>APN</th>
                            <th>Message</th>
                            <th>Created at</th>
                            <th>Action</th>
                        </tr>
                    </thead>                
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div><!-- /.container-fluid -->

<script>
    function downloadDocumentFromAws(url, documentType)
    {
        $('#page-preloader').css('background-color', 'rgba(0,0,0,.5)');
        $('#page-preloader').css('display', 'block');
        var fileNameIndex = url.lastIndexOf("/") + 1;
        var filename = url.substr(fileNameIndex);
        $.ajax({
            url: base_url + "download-aws-document-admin",
            type: "post",
            data: {
                url : url
            },
            async: false,
            success: function (response) {
                if (response) {
                    if (navigator.msSaveBlob) {
                        var csvData = base64toBlob(response, 'application/octet-stream');
                        var csvURL = navigator.msSaveBlob(csvData, filename);
                        var element = document.createElement('a');
                        element.setAttribute('href', csvURL);
                        element.setAttribute('download', documentType+"_"+filename);
                        element.style.display = 'none';
                        document.body.appendChild(element);
                        document.body.removeChild(element);
                    } else {
                        var csvURL = 'data:application/octet-stream;base64,' + response;
                        var element = document.createElement('a');
                        element.setAttribute('href', csvURL);
                        element.setAttribute('download', documentType+"_"+filename);
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
</script>

