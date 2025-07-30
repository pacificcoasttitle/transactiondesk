<style type="text/css">
    th {
        text-align: center;
    }
	.table-container {
		overflow-y: initial !important;
	}

    .btnFile {
        display: inline-block;
        margin-bottom: 0;
        font-weight: 400;
        text-align: center;
        vertical-align: middle;
        cursor: pointer;
        background-image: none;
        border: 1px solid transparent;
        white-space: nowrap;
        padding: 6px 12px;
        font-size: 14px;
        line-height: 1.42857143;
        border-radius: 4px;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    .glyphicon {
        position: relative;
        top: 1px;
        display: inline-block;
        font-family: 'Glyphicons Halflings';
        font-style: normal;
        font-weight: 400;
        line-height: 1;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }

    .glyphicon-plus:before {
        content: "\2b";
    }

    .btnFile-success {
        color: #fff;
        font-weight: bold;
        text-align: center;
        padding: 5em 22em;
        margin: 10px 0px 0px 0px;
        color: #555;
        border: 2px dashed #cbd2d6;
        border-radius: 7px;
        margin-bottom: 20px;
    }

    .fileinput-button input {
        position: absolute;
        top: 0;
        right: 0;
        margin: 0;
        opacity: 0;
        -ms-filter: 'alpha(opacity=0)';
        font-size: 200px;
        direction: ltr;
        cursor: pointer;
    }
    input[type=file] {
        display: block;
    }
    input, button, select, textarea {
        font-family: inherit;
        font-size: inherit;
        line-height: inherit;
    }
    button, input, optgroup, select, textarea {
        color: inherit;
        font: inherit;
        margin: 0;
    }
    input[type="file"] {
        align-items: baseline;
        color: inherit;
        text-align: start;
    }
    input[type="hidden"], input[type="image"], input[type="file"] {
        -webkit-appearance: initial;
        padding: initial;
        background-color: initial;
        border: initial;
    }
    input[type="password"], input[type="search"] {
        -webkit-appearance: textfield;
        padding: 1px;
        background-color: white;
        border: 2px inset;
        border-image-source: initial;
        border-image-slice: initial;
        border-image-width: initial;
        border-image-outset: initial;
        border-image-repeat: initial;
        -webkit-rtl-ordering: logical;
        -webkit-user-select: text;
        cursor: auto;
    }
    input, textarea, keygen, select, button {
        margin: 0em;
        font: -webkit-small-control;
        color: initial;
        letter-spacing: normal;
        word-spacing: normal;
        text-transform: none;
        text-indent: 0px;
        text-shadow: none;
        display: inline-block;
        text-align: start;
    }
    .custom__task_actions .btn {
        border-bottom: 2px solid #666;
        margin-bottom: 50px;
        
    }

    user agent stylesheetinput, textarea, keygen, select, button, meter, progress {
        -webkit-writing-mode: horizontal-tb;
    }

    .text {
        font-weight: normal;
    }

    .ui-form-1 .form-control {
        border: 1px solid #cbd2d6;
        border-radius: 25px;
        color: #555;
    }

    .bttn-icon-split {
        padding: 0;
        overflow: hidden;
        display: inline-flex;
        align-items: stretch;
        justify-content: center;
    }
    .bttn-info {
        color: #fff !important;
        background-color: #36b9cc !important;
        border-color: #36b9cc !important;
    }
    .bttn {
        display: inline-block;
        font-weight: 400;
        color: #858796;
        text-align: center;
        vertical-align: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        background-color: transparent;
        border: 1px solid transparent;
        
        font-size: 1rem;
        line-height: 1.5;
        border-radius: 0.35rem;
        transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;
    }

    .bttn-icon-split .icon {
        background: rgba(0,0,0,.15);
        display: inline-block;
        padding: 0.775rem 1.75rem;
        font-size: 17px;
    }
    .text-white-50 {
        color: rgba(255,255,255,.5)!important;
    }
    .bttn-icon-split .text {
        display: inline-block;
        padding: 0.775rem 0.75rem;
        font-size: 17px;
    }
    
</style>

<body>
	<?php
        $this->load->view('layout/header_dashboard');
    ?>
    <section class="section-type-4a section-defaulta" style="padding-bottom:0px;">
        <div class="container">
            <div class="row">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="typography-section__inner" style="margin-bottom: 20px;">
                            <h2 class="ui-title-block ui-title-block_light">Upload a Document</h2>
                            <div class="ui-decor-1a bg-primary"></div>
                            <h3 class="ui-title-block_light">File Number <?php echo $orderDetails['file_number'];?></h3>
                            <h3 class="ui-title-block_light"><?php echo $orderDetails['full_address'];?></h3>
                        </div>
                        <?php if(!empty($success)) {?>
                            <div id="agent_success_msg" class="w-100 alert alert-success alert-dismissible" >
                                <?php foreach($success as $sucess) {
                                    echo $sucess."<br \>";	
                                }?>
                            </div>
                        <?php } 
                            if(!empty($errors)) {?>
                            <div id="agent_error_msg" class="w-100 alert alert-danger alert-dismissible" >
                                <?php foreach($errors as $error) {
                                    echo $error."<br \>";	
                                }?>
                            </div>
                        <?php } ?>
                        <div class="loader"></div>
                        <div class="" style="margin-top: 50px;">
                            <div class="col-md-12">
                                <div class="table-container">
                                    <div class="typography-section-border">
                                        <div class="container">
                                            <div class="row">
                                                <section class="section-reply-form" id="section-reply-form">
                                                    <form class="form-reply ui-form-1" id="files_upload" action="<?php echo base_url();?>borrower-document-upload" method="POST" enctype="multipart/form-data">
                                                        
                                                        <input type="hidden" id="file_id" name="file_id" value="<?php echo $orderDetails['file_id'];?>">
                                                        <input type="hidden" id="order_id" name="order_id" value="<?php echo $orderDetails['order_id'];?>">
                                                        <input type="hidden" id="task_name" name="task_name" value="<?php echo $task_name;?>">
                                                        
                                                        <div class="row">
                                                            <div class="col-xs-8">
                                                                <span class="btnFile btnFile-success fileinput-button" style="margin-top:10px;">
                                                                    <i class="glyphicon glyphicon-plus"></i>
                                                                    <span>Drag and Drop files...</span>
                                                                    <input type="file" name="document_files[]" id="ufile" multiple>
                                                                </span>
                                                                <div id="output">
                                                                    <ul></ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    
                                                        <?php if (!empty($tasks)) { ?>
                                                            <div class="row">
                                                                <div class="col-xs-8">
                                                                    <select class="form-control" id="task_id" name="task_id" required <?php echo ($task_name == 'request_docs') ? 'disabled' : '';?>>
                                                                        <option value="">Select Task</option>
                                                                        <?php foreach($tasks as $task) {?>
                                                                            <option <?php echo ($task_name == 'request_docs') ? 'selected' : '';?> value="<?php echo $task->id;?>"><?php echo $task->name;?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                        
                                                        <div class="col-md-3 custom__task_actions" style="padding: 0px !important;margin-bottom: 50px;">
                                                            
                                                            <button type="submit" class="bttn bttn-info bttn-icon-split">
																<span class="icon text-white-50">
																	<i class="fa fa-upload"></i>
																</span>
																<span class="text">Upload Documents</span>
															</button>
                                                        </div>
                                                    </form>
                                                    <div id="result"></div>
                                                </section>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="section-type-4a section-defaulta">
        <div class="container">
            <div class="row">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="typography-section__inner">
                            <h2 class="ui-title-block ui-title-block_light">Documents</h2>
                            <div class="ui-decor-1a bg-accent"></div>
                            <h3 class="ui-title-block_light">Below is list of all your documents.</h3>
                        </div>
                        <div class="typography-sectiona">
                            <div class="col-md-12">
                                <div class="table-container">
                                    <table class="table table_primary" id="document_listing">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Document Name</th>
                                                <th>Created</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if(!empty($borrowerDocuments)) { 
                                                $i = 1;
                                                foreach($borrowerDocuments as $document) { ?>
                                                    <tr role="row" class="odd">
                                                        <td><?php echo $i;?></td>
                                                        <td><?php echo $document['original_document_name'];?></td>
                                                        <td><?php echo  date("m/d/Y", strtotime($document['created']));?></td>
                                                        <td>
                                                            <a style="border-bottom: 2px solid #666;" target="_blank" href="<?php echo env('AWS_PATH').'borrower/'.$document['document_name'];?>" class="btn button btn-primary">
                                                                <span class="text">View</span>
                                                            </a>
                                                        </td> 
                                                    </tr>
                                                <?php $i++;} ?>
											<?php } else { ?>
												<tr role="row" class="odd"><td colspan="4" class="text-center">No record found</td></tr>
											<?php }  ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php
        $this->load->view('layout/footer');
    ?>
</body>

<script>
	$(document).ready(function () {
		$("input#ufile").change(function () {
			$("#output ul").empty();
			var ele = document.getElementById($(this).attr('id'));
			var result = ele.files;
			for (var x = 0; x < result.length; x++) {
				var fle = result[x];
				$("#output ul").append("<li>" + fle.name + "(TYPE: " + fle.type + ", SIZE: " + fle.size +
					")</li>");
			}
		});
	});

</script>

</html>


