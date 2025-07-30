<?php 
    // Extract dynamic question keys
    $questionKeys = [];
    foreach ($rating as $entry) {
        foreach ($entry as $key => $value) {
            if (is_numeric($key)) { // Only include numeric keys (questions)
                $questionKeys[$key] = $key;
            }
        }
    }
    
    // Sort keys to maintain order
    ksort($questionKeys);
?>
<table class="table table-bordered" id="tbl-surveys-listing" width="100%" cellspacing="0">
    <thead>
        <tr align="center">
            <th width="10%">Recipiente</th>
            <th width="25%">Sales rep</th>
            <th width="9%">Service</th>
            <th width="9%">Experience</th>
            <th width="9%">Comminication</th>
            <th width="9%">Helpful</th>
            <th width="9%">Refer</th>
            <th width="20%">Action</th>
        </tr>
    </thead>
    <?php 
    //if (!empty($survey)) {
    //foreach ($survey as $key => $value) { ?>              
    <tbody>
        <?php 
        // echo "<pre>";
        // print_r($rating);die;
        if (!empty($rating)) {
        foreach ($rating as $k => $val) { ?>
            <tr align="center">
                <td><?php echo $titleOfficer; ?></td>
                <td><?php echo $val['sales_rep']; ?></td>
                <?php foreach ($questionKeys as $key) { ?>
                    <td><?php echo isset($val[$key]) ? $val[$key] : '-'; ?></td>
                <?php } ?>
                <!-- <td><?php echo ($val['Q1']); ?></td>
                <td><?php echo ($val['Q2']); ?></td>
                <td><?php echo ($val['Q3']); ?></td>
                <td><?php echo ($val['Q4']); ?></td>
                <td><?php echo ($val['Q5']); ?></td> -->
                <!-- <td>-</td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
                <td>-</td> -->
                <td>
                    <div class="dropdown">
                        <a class="btn dropdown-toggle click-action-type" type="button" data-toggle="dropdown" href="#">Click Action Type
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" style="width:210px !important;max-width:none !important; margin-top: 0px;">
                            <li>
                                <a href="javascript:void(0)" onclick='displayComment("<?php echo $val["comment"]; ?>");' title="View Comment">
                                    <button class="btn btn-grad-2a button-color" type="button">
                                        <i class="fas fa-eye" aria-hidden="true" style="margin-right:5px;"></i>
                                        View Comment
                                    </button>
                                </a>
                                <!-- <a href="javascript:void(0)" onclick='displayComment(<?php echo json_encode($value["textComment"], JSON_HEX_APOS | JSON_HEX_QUOT); ?>);' title="View Comment">
                                    <button class="btn btn-grad-2a button-color" type="button">
                                        <i class="fas fa-eye" aria-hidden="true" style="margin-right:5px;"></i>
                                        View Comment
                                    </button>
                                </a> -->
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
    <?php //}?>
</table>