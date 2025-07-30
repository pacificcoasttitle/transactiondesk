<style>
th {
    text-align: center;
}

.smart-forms p {
	margin-bottom: 0px;
}
</style>

<section class="section-type-4a section-defaulta" style="padding-bottom:0px;">
    <div class="container">
        <div class="row">
            <div class="row">
                <div class="col-xs-12">
                    <div class="typography-section__inner">
                        <h2 class="ui-title-block ui-title-block_light">Trainings,</h2>
                        <div class="ui-decor-1a bg-accent"></div>
                        <h3 class="ui-title-block_light">Below is a detail of all your trainings.</h3>
                        <?php if(!empty($success)) {?>
                            <div id="time_card_success_msg" class="w-100 alert alert-success alert-dismissible">
                                <?php foreach($success as $sucess) {
                                    echo $sucess."<br \>";	
                                }?>
                            </div>
                        <?php } 
                        if(!empty($errors)) {?>
                            <div id="time_card_error_msg" class="w-100 alert alert-danger alert-dismissible">
                                <?php foreach($errors as $error) {
                                    echo $error."<br \>";	
                                }?>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="typography-sectiona">
                        <div class="table-container">
                            <table class="table table-type-3 typography-last-elem" id="trainings_listing" width="100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th>Status</th>
                                        <th>Completed At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                </tbody>
                            </table>
                            <div class="typography-sectionab"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>



