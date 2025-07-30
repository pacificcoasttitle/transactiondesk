<style>
.dataTables_length {
    width: 250px !important;
    float: left;
}
.FilterOrderListing {
    width: 100%;
    display: flex;
}
.form-footer {
    color: #fff;
    display: flex;
    justify-content: flex-start;
}

.form-footer .btn {
    color: #fff;
}

.dropdown-menu {
    margin-top: 5px !important;
}
</style>
<div class="container-fluid">
    <?php if(!empty($this->session->userdata('success'))){ ?>
        <div class="col-xs-12">
            <div class="alert alert-success"><?php echo $this->session->userdata('success'); ?></div>
        </div>
    <?php } ?>

    <?php if(!empty($error_msg)){ ?>
        <div class="col-xs-12">
            <div class="alert alert-danger"><?php echo $error_msg; ?></div>
        </div>
    <?php } ?>
    <div id="surveys_success_msg" class="w-100 alert alert-success alert-dismissible" style="display:none;"></div>
    <div id="surveys_error_msg" class="w-100 alert alert-danger alert-dismissible" style="display:none;"></div>
    <div class="row mb-3">
		<div class="col-sm-6">
			<h1 class="h3 text-gray-800">Surveys</h1>
		</div>
		<div class="col-sm-6">
            <a href="javascript:void(0)" class="btn btn-success btn-icon-split float-right mr-2" onclick="$('#send_sample_email').modal('show')"> 
                <span class="icon text-white-50"><i class="fas fa-plus"></i></span><span class="text">Send Sample Email</span> </a>
		</div>
	</div>
    <div class="card shadow mb-4">
        <div class="card-header datatable-header py-3">
            <div class="datatable-header-titles" > 
                <span>
                    <i class="fas fa-table"></i>
                </span>
                <h6 class="m-0 font-weight-bold text-primary pl-10">Surveys Result</h6> 
            </div>
        </div>
                
        <div class="card-body">
            <div id="surveys_success_msg" class="w-100 alert alert-success alert-dismissible" style="display:none;"></div>
            <div id="surveys_error_msg" class="w-100 alert alert-danger alert-dismissible" style="display:none;"></div>
            <div class="table-responsive">
                <table class="table table-bordered" id="tbl-surveys-listing" width="100%" cellspacing="0">
                    <thead>
                        <tr align="center">
                            <th width="10%">Sr No</th>
                            <th width="25%">Title Officer</th>
                            <th width="9%">Q1</th>
                            <th width="9%">Q2</th>
                            <th width="9%">Q3</th>
                            <th width="9%">Q4</th>
                            <th width="9%">Q5</th>
                            <th width="20%">Action</th>
                        </tr>
                    </thead>                
                    <tbody>
                        <?php 
                        if (!empty($survey)) {
                        foreach ($survey as $key => $value) { ?>
                            <tr align="center">
                                <td><?php echo $key + 1; ?></td>
                                <td><?php echo $value['title']; ?></td>
                                <?php if (!empty($value['avg'])) { ?>
                                    <td><?php echo ($value['avg']['Q1']); ?></td>
                                    <td><?php echo ($value['avg']['Q2']); ?></td>
                                    <td><?php echo ($value['avg']['Q3']); ?></td>
                                    <td><?php echo ($value['avg']['Q4']); ?></td>
                                    <td><?php echo ($value['avg']['Q5']); ?></td>
                                <?php } else {?>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                <?php }?>
                                <td>
                                    <div class="dropdown">
                                        <a class="btn dropdown-toggle click-action-type" type="button" data-toggle="dropdown" href="#">Click Action Type
                                            <span class="caret"></span>
                                        </a>
                                        <ul class="dropdown-menu" style="width:210px !important;max-width:none !important; margin-top: 0px;">
                                            <li>
                                                <a href="javascript:void(0)" onclick='displayComment(<?php echo json_encode($value["textComment"], JSON_HEX_APOS | JSON_HEX_QUOT); ?>);' title="View Comment">
                                                    <button class="btn btn-grad-2a button-color" type="button">
                                                        <i class="fas fa-eye" aria-hidden="true" style="margin-right:5px;"></i>
                                                        View Comment
                                                    </button>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0)" onclick="changeClient(90001679);" title="View Response">
                                                    <button class="btn btn-grad-2a button-color" type="button">
                                                        <i class="fas fa-eye" aria-hidden="true" style="margin-right:5px;" 1=""></i>
                                                        View Response
                                                    </button>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        <?php } } else { ?>
                            <tr>
                                <td colspan="8" class="text-center">No record found</td>
                            </tr>
                        <?php }?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="send_sample_email" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document" style="width: 40%;">
        <div class="modal-content">
            <!-- <form method="post" id="instrument-file-upload-form" name="instrument-file-upload-form" enctype="multipart/form-data" action="<?php echo base_url(); ?>order/admin/change-client"> -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card shadow">
                            <div class="card-header py-3">
                                <div class="w-100 alert alert-danger alert-dismissible surveys_error_msg" style="display:none;"></div>
                                <h6 class="m-0 font-weight-bold text-primary">Send Sample Email</h6>
                            </div>
                            <div class="card-body">
                                <div class="smart-forms smart-container">
                                    <div class="modal-body search-result">

                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <label for="email_address" class="col-form-label">Email Address</label>
                                                    <input name="email_address" required="" type="text"
                                                        class="form-control" id="email_address">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-footer" style="padding: 0px 1rem !important;">
                                        <button type="button" id="send_sample_mail_btn" data-btntext-sending="Sending..." class="btn btn-success btn-icon-split btn-sm">
                                            <span class="icon text-white-50">
                                                <i class="fas fa-check"></i>
                                            </span>
                                            <span class="text">Send</span>
                                        </button>
                                        <button type="reset" data-dismiss="modal" aria-label="Close" class="btn btn-danger btn-icon-split btn-sm">
                                            <span class="icon text-white-50">
                                                <i class="fas fa-ban"></i>
                                            </span>
                                            <span class="text">Cancel</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <!-- </form> -->
        </div>
    </div>
</div>

<div class="modal fade" id="commentModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document" style="width: 40%;">
            <div class="modal-content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card shadow">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Comments List</h6>
                            </div>
                            <div class="card-body">
                                <div class="smart-forms smart-container">
                                    <div class="modal-body search-result">

                                    <ul id="commentList" class="list-group">
                                        <!-- List items will be added dynamically here -->
                                    </ul>
                                    </div>
                                    <div class="form-footer" style="padding: 0px 1rem !important;">
                                        <button type="reset" data-dismiss="modal" aria-label="Close" class="btn btn-secondary btn-icon-split btn-sm">
                                            <span class="text">Close</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- <div class="modal-header">
                    <h5 class="modal-title">Comments List</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul id="commentList" class="list-group">
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div> -->
            </div>
        </div>
    </div>

<script>
    $(document).on('click', '#send_sample_mail_btn', function() {
        console.log('send_sample_mail_btn');
        $('#surveys_error_msg').hide();
        var email_address = $('#email_address').val();
        if (!email_address) {
            $('#surveys_error_msg, .surveys_error_msg').html('Please enter email address.').show();
            setTimeout(function () {
                $('#surveys_error_msg, .surveys_error_msg').html('').hide();
            }, 5000);
            return;
        }
        $.ajax({
            url: base_url + "send-survey-sample-email",
            method: "POST",
            data: {
                email_address: $('#email_address').val()
            },
            success: function (data) {
                var result = jQuery.parseJSON(data);
                $('#send_sample_email').modal('hide');
                $('#surveys_success_msg').html(result.message).show();
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                $('#send_sample_email').modal('hide');
                $('#surveys_error_msg').html('Something went wrong. Please try it again.').show();
                $([document.documentElement, document.body]).animate({
                    scrollTop: $("#surveys_success_msg").offset().top
                }, 1000);

                setTimeout(function () {
                    $('#surveys_error_msg').html('').hide();
                }, 5000);
            }
        });
    });
    $(document).ready(function() {
    });
    function displayComment(commentsArray) {
        $("#commentList").html("");
        if (commentsArray.length > 0) {
            let listHtml = "";
            commentsArray.forEach(function (comment) {
                listHtml += `<li class="list-group-item">${comment}</li>`;
            });
            $("#commentList").html(listHtml);
            $("#commentModal").modal("show");
        }
    }
</script>
