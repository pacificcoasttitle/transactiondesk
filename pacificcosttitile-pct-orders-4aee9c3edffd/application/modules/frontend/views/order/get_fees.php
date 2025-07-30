<div class="row mb-3">
    <div class="col-sm-12">
        <a style="float:right" class="btn-success btn-icon-split btn-sm " href="<?php echo base_url(); ?>fees">
            <span class="icon text-white-50">
                <i class="fa fa-arrow-left"></i>
            </span>
            <span class="text">Back</span>
        </a>
    </div>
</div>
<section class="content-wrapper" style="margin-bottom:50px;">
    <div class="row">

    </div>
    <div class="row">
        <div class="recipt-body" id="artcle_main">
            <div id="editor"></div>
            <div class="article" id="artcle_div">
                <?php if (empty($calcResult)) {?>
                    <span style='font-size: 20px;color: red;'>Fees estimation does not exist.</span>
                <?php } else {?>
                    <table class="table" style="max-width:100%">
                        <tbody>
                            <tr>
                                <td style="border-top:none;" colspan="4">
                                    <h3><strong>Pacific Coast Title</strong> - Fee Estimate</h3>
                                </td>
                            </tr>
                            <tr>
                                <td><b>Order Number</b></td>
                                <td><?php echo isset($order_number) && !empty($order_number) ? $order_number : '-'; ?></td>
                                <td><b>Transaction Type</b></td>
                                <td>
                                    <?php echo isset($calcResult['transactionType']) && !empty($calcResult['transactionType']) ? $calcResult['transactionType'] : '-'; ?>
                                </td>
                            </tr>
                            <tr>
                                <td><b>Property Location</b></td>
                                <td>
                                    <?php echo isset($full_address) && !empty($full_address) ? $full_address : '-'; ?>
                                </td>
                                <?php if (isset($loan_amount) && !empty($loan_amount)) {
    $loan_amount = str_replace(",", "", $loan_amount);?>
                                        <td><b>Loan Amount </b></td>
                                        <td>$<?php echo number_format($loan_amount); ?></td>
                                <?php
} else {?>
                                        <td></td>
                                        <td></td>
                                <?php }?>
                            </tr>
                            <tr>
                                <?php if (isset($sales_amount) && !empty($sales_amount)) {?>
                                    <td><b>Sales Amount </b></td>
                                    <td>$<?php echo number_format($sales_amount); ?> </td>
                                    <td></td>
                                    <td></td>
                                <?php }?>
                            </tr>
                        </tbody>
                    </table>
                    <div class="clearfix">
                        <p></p>
                    </div>
                    <table class="table-null" cellspacing="5" cellpadding="5">
                        <tbody>
                            <tr>
                                <td>
                                    <table class="table-data">
                                        <tbody>
                                            <tr class="bg-gray">
                                                <td class="bg-gray" colspan="2" style="width:60%"><b>Title Fees</b></td>
                                            </tr>

                                            <?php if ($calcResult['transactionType'] == 'Re-Finance') {?>
                                                <tr>
                                                    <td>ALTA Residential Loan Policy</td>
                                                    <td class="aright">
                                                        <?php echo $calcResult['purchase_rate']; ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><b>Total</b></td>
                                                    <td class="aright">
                                                        <b><?php echo $calcResult['title_total']; ?></b>
                                                    </td>
                                                </tr>
                                            <?php } else {?>
                                                <tr>
                                                    <td>Alta Homeowners Policy</td>
                                                    <td class="aright">
                                                        <?php echo $calcResult['purchase_rate']; ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Alta Lenders Concurrent Loan Rate</td>
                                                    <td class="aright">
                                                        <?php echo $calcResult['lender_rate']; ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><b>Total</b></td>
                                                    <td class="aright">
                                                        <b><?php echo $calcResult['title_total']; ?></b>
                                                    </td>
                                                </tr>
                                            <?php }?>

                                            <?php if ($is_escrow_flag == '1') {?>
                                                <tr class="bg-gray">
                                                    <td class="bg-gray" colspan="2" style="width:60%"><b>Escrow Fees</b></td>
                                                </tr>

                                                <?php if (!empty($calcResult['escrowInitalFee'])) {?>
                                                    <tr>
                                                        <td>Escrow Initial Fees</td>
                                                        <td class="aright">
                                                            <?php echo $calcResult['escrowInitalFee']; ?>
                                                        </td>
                                                    </tr>
                                                <?php }?>

                                                <?php if (isset($calcResult['escrowAdditionalFees']) && !empty($calcResult['escrowAdditionalFees'])) {
    foreach ($calcResult['escrowAdditionalFees'] as $fee) {?>
                                                        <tr>
                                                            <td><?php echo $fee['name']; ?></td>
                                                            <td class="aright">
                                                                $<?php echo number_format($fee['value'], 2); ?>
                                                            </td>
                                                        </tr>
                                                    <?php }
}?>

                                                <?php if (!empty($calcResult['escrowTotal'])) {?>
                                                    <tr>
                                                        <td><b>Total</b></td>
                                                        <td class="aright">
                                                            <b><?php echo $calcResult['escrowTotal']; ?></b>
                                                        </td>
                                                    </tr>
                                                <?php }?>
                                            <?php }?>

                                            <tr class="bg-gray">
                                                <td class="bg-gray" colspan="2" style="width:60%"><b>Recording Fees</b></td>
                                            </tr>

                                            <?php if (isset($calcResult['recordingAdditionalFees']) && !empty($calcResult['recordingAdditionalFees'])) {
    foreach ($calcResult['recordingAdditionalFees'] as $fee) {?>
                                                    <tr>
                                                        <td><?php echo $fee['name']; ?></td>
                                                        <td class="aright">
                                                            $<?php echo number_format($fee['value'], 2); ?>
                                                        </td>
                                                    </tr>
                                                <?php }?>
                                                <tr>
                                                    <td><b>Total</b></td>
                                                    <td class="aright">
                                                        <b>$<?php echo $calcResult['recordingTotal']; ?></b>
                                                    </td>
                                                </tr>

                                            <?php
}?>

                                            <tr class="bg-gray">
                                                <td class="bg-gray" colspan="2" style="width:60%"><b>Transfer Tax</b></td>
                                            </tr>

                                            <tr>
                                                <td>County Transfer Tax</td>
                                                <td class="aright">
                                                    <?php echo $calcResult['transferTaxesFees']['county_tax_fee'] ? $calcResult['transferTaxesFees']['county_tax_fee'] : '0.00'; ?>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>City Transfer Tax</td>
                                                <td class="aright">
                                                    <?php echo $calcResult['transferTaxesFees']['city_tax_fee'] ? $calcResult['transferTaxesFees']['city_tax_fee'] : '0.00'; ?>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td><b>Total</b></td>
                                                <td class="aright">
                                                    <b>$<?php echo $calcResult['transferTaxesFees']['transfer_tax_total'] ? $calcResult['transferTaxesFees']['transfer_tax_total'] : '0.00' ?></b>
                                                </td>
                                            </tr>

                                            <?php if (!empty($calcResult['other_additional_fees_total'])) {?>

                                                <tr class="bg-gray">
                                                    <td class="bg-gray" colspan="2" style="width:60%"><b>Other Fees</b></td>
                                                </tr>

                                                <?php if (isset($calcResult['other_additional_fees']) && !empty($calcResult['other_additional_fees'])) {
    foreach ($calcResult['other_additional_fees'] as $fee) {?>
                                                        <tr>
                                                            <td><?php echo $fee['name']; ?></td>
                                                            <td class="aright">
                                                                $<?php echo number_format($fee['value'], 2); ?>
                                                            </td>
                                                        </tr>
                                                    <?php }?>
                                                    <tr>
                                                        <td><b>Total</b></td>
                                                        <td class="aright">
                                                            <b>$<?php echo $calcResult['other_additional_fees_total']; ?></b>
                                                        </td>
                                                    </tr>
                                                <?php
}?>

                                            <?php }?>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                <?php }?>
            </div>

        </div>
        <div class="clearfix" id="act_btns">
                <br />
                <a class="button small orange" id="download_estimate" data-closing-fee-id="<?php echo $closing_fee_estimate_id; ?>" href="javascript:void(0);">Download Fee Estimate</a>
            </div>
    </div>
</section>



