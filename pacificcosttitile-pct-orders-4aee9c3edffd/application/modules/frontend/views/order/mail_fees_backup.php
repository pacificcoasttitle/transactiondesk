<body>

  <script src="<?php echo base_url(); ?>assets/frontend/js/jspdf.debug.js">
  </script>
  <script src="<?php echo base_url(); ?>assets/frontend/js/html2canvas.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/frontend/js/html2pdf.bundle.js"></script>

  <link rel="stylesheet" href="<?php echo base_url() ?>assets/front/css/style.css" media="screen" type="text/css" />

  <?php
  $this->load->view('layout/header_dashboard');
  ?>
  <section class="content-wrapper" id="content-wrapper">
    <div class="row"></div>
    <div class="row">
      <div class="recipt-body" id="artcle_main">
        <div class="article" id="artcle_div">
          <?php
          if (empty($calcResult)) {
          ?>
            <span style='font-size: 20px;color: red;'>Fees estimation does not exist.</span>
          <?php
          } else {
          ?>
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
                  <?php
                  if (isset($loan_amount) && !empty($loan_amount)) {
                    $loan_amount = str_replace(",", "", $loan_amount);
                  ?>
                    <td><b>Loan Amount </b></td>
                    <td>$<?php echo number_format($loan_amount); ?></td>
                  <?php
                  } else {
                  ?>
                    <td></td>
                    <td></td>
                  <?php
                  }
                  ?>
                </tr>
                <tr>
                  <?php
                  if (isset($sales_amount) && !empty($sales_amount)) {
                    $sales_amount = str_replace(",", "", $sales_amount);
                  ?>
                    <td><b>Sales Amount </b></td>
                    <td>$<?php echo number_format($sales_amount); ?> </td>
                    <td></td>
                    <td></td>
                  <?php
                  }
                  ?>

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

                        <?php  if ($calcResult['transactionType'] == 'Re-Finance') { ?>
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
                        <?php } else { ?>
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
                        <?php } ?>

                        <tr class="bg-gray">
                            <td class="bg-gray" colspan="2" style="width:60%"><b>Escrow Fees</b></td>
                        </tr>

                        <?php if (!empty($calcResult['escrowInitalFee'])) { ?>
                            <tr>
                                <td>Escrow Initial Fees</td>
                                <td class="aright">
                                    <?php echo $calcResult['escrowInitalFee']; ?>
                                </td>
                            </tr>
                        <?php } ?>   

                        <?php if (isset($calcResult['escrowAdditionalFees']) && !empty($calcResult['escrowAdditionalFees'])) { 
                            foreach($calcResult['escrowAdditionalFees'] as $fee)  { ?>
                                 <tr>
                                    <td><?php echo $fee['name'];?></td>
                                    <td class="aright">
                                        $<?php echo number_format($fee['value'], 2); ?>
                                    </td>
                                </tr>
                            <?php } 
                        } ?>

                        <?php if (!empty($calcResult['escrowTotal'])) { ?>
                            <tr>
                                <td><b>Total</b></td>
                                <td class="aright">
                                    <b><?php echo $calcResult['escrowTotal']; ?></b>
                                </td>
                            </tr>
                        <?php } ?>   

                        <tr class="bg-gray">
                            <td class="bg-gray" colspan="2" style="width:60%"><b>Recording Fees</b></td>
                        </tr>

                        <?php if (isset($calcResult['recordingAdditionalFees']) && !empty($calcResult['recordingAdditionalFees'])) { 
                            foreach($calcResult['recordingAdditionalFees'] as $fee)  { ?>
                                 <tr>
                                    <td><?php echo $fee['name'];?></td>
                                    <td class="aright">
                                        $<?php echo number_format($fee['value'], 2); ?>
                                    </td>
                                </tr>
                            <?php } ?>
                            <tr>
                                <td><b>Total</b></td>
                                <td class="aright">
                                    <b><?php echo $calcResult['recordingTotal']; ?></b>
                                </td>
                            </tr>
                         
                        <?php } ?>

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
                                <b><?php echo $calcResult['transferTaxesFees']['transfer_tax_total'] ? $calcResult['transferTaxesFees']['transfer_tax_total'] : '0.00' ?></b>
                            </td>
                        </tr>

                        <?php if (!empty($calcResult['other_additional_fees_total'])) { ?>

                            <tr class="bg-gray">
                                <td class="bg-gray" colspan="2" style="width:60%"><b>Other Fees</b></td>
                            </tr>

                            <?php if (isset($calcResult['other_additional_fees']) && !empty($calcResult['other_additional_fees'])) { 
                                foreach($calcResult['other_additional_fees'] as $fee)  { ?>
                                    <tr>
                                        <td><?php echo $fee['name'];?></td>
                                        <td class="aright">
                                            $<?php echo number_format($fee['value'], 2); ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                                <tr>
                                    <td><b>Total</b></td>
                                    <td class="aright">
                                        <b><?php echo $calcResult['other_additional_fees_total']; ?></b>
                                    </td>
                                </tr>       
                            <?php } ?>

                        <?php } ?>
                      </tbody>
                    </table>
                  </td>
                </tr>
              </tbody>
            </table>
        </div>
        <div class="clearfix" id="act_btns" data-html2canvas-ignore="true">
          <br />
          <a class="button small orange" id="download_estimate" data-closing-fee-id="<?php echo $closing_fee_estimate_id; ?>" href="javascript:void(0);">Download Fee Estimate</a>
        </div>
      <?php
          }
      ?>


      </div>
      <!--   </div> -->
  </section>
  <?php
  $this->load->view('layout/footer');
  ?>
</body>

</html>
<script>
  $(document).ready(function() {

    $('#download_estimate').click(function() {

      // $('#act_btns').hide();
      var pdf = new jsPDF('', 'pt', 'a4');
      /*pdf.addHTML($('.content-wrapper'), function() {
        pdf.save('web.pdf');
        $('#act_btns').show();
      });*/

      /*pdf.html(document.getElementById('artcle_main'), {
	            callback: function () {
	                //pdf.save('test.pdf');
	                window.open(pdf.output('bloburl')); // to debug
	            }
	        });*/

      var element = document.getElementById('artcle_main');
      html2pdf(element, {
        margin: 1,
        filename: 'feeEstimation.pdf',
        // image: {type: 'jpeg', quality: 1},
        html2canvas: {
          scale: 4,
          logging: false
        },
        // jsPDF: {unit: 'mm', format: 'a4', orientation: 'p'}

      });
      // $('#act_btns').show();
    });
  });

  function base64toBlob(base64Data, contentType) {
    contentType = contentType || '';
    var sliceSize = 1024;
    var byteCharacters = atob(base64Data);
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
</script>