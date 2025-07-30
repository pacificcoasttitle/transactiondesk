<?php
function evalmath($equation) {
  $result = 0;
  // sanitize imput
  $equation = preg_replace("/[^a-z0-9+\-.*\/()%]/", "", $equation);
  // convert alphabet to $variabel
  $equation = preg_replace("/([a-z])+/i", "\$$0", $equation);
  // convert percentages to decimal
  $equation = preg_replace("/([+-])([0-9]{1})(%)/", "*(1\$1.0\$2)", $equation);
  $equation = preg_replace("/([+-])([0-9]+)(%)/", "*(1\$1.\$2)", $equation);
  $equation = preg_replace("/([0-9]{1})(%)/", ".0\$1", $equation);
  $equation = preg_replace("/([0-9]+)(%)/", ".\$1", $equation);

  return $equation;
}
?>

<body>
<?php
   $this->load->view('layout/header');
?>
<!-- pagetitle start here -->
<div class="section-title-page7p area-bg area-bg_blue area-bg_op_90 parallax">
  <div class="area-bg__inner">
    <div class="container">
      <div class="row">
        <div class="col-xs-12">
            <h1 class="b-title-page">Your Quote is Below</h1>
            <div class="b-title-page__info">get your rate instantly</div>
            <!-- end breadcrumb-->
        </div>
      </div>
    </div>
  </div>
</div>
<!-- pagetitle end here -->

<!-- content section start here -->
<section class="content-wrapper">
  <div class="row"></div>
  <div class="row">
    <div class="recipt-body" id="artcle_main">
      <div class="article" id="artcle_div">
        <?php
          if(empty($quote_detail))
          {
        ?>
            <span style='font-size: 20px;color: red;'>Quote does not exist.</span>
        <?php
          }
          else
          {
        ?>
            <table class="table" style="max-width:100%">
              <tbody>
                <tr>
                  <td style="border-top:none;" colspan="4">
                    <h3><strong>Pacific Coast Title</strong> - Title Quote</h3>
                  </td>
                </tr>
                <tr>
                  <td><b>Quote ID</b></td>
                  <td><?php echo $quote_detail->quote_id_pk; ?></td>
                  <td><b>Transaction Type</b></td>
                  <td>
                    <?php echo ($quote_detail->txn_type == 'Resale') ? "Purchase" : "Refinance"; ?>                      
                  </td>
                </tr>
                <tr>
                  <td><b>Quote Date</b></td>
                  <td>
                    <?php echo date("m/d/Y h:i A", strtotime($quote_detail->quote_date)); ?>                      
                  </td>
                  <?php 
                    if($quote_detail->txn_type == 'Resale')
                    {
                  ?>
                      <td><b>Sale Amount </b></td>
                      <td>$ <?php echo number_format($quote_detail->sale_amount); ?> </td>
                  <?php
                    }
                    else
                    {
                  ?>
                      <td><b>Loan Amount </b></td>
                      <td>$ <?php echo number_format($quote_detail->loan_amount); ?></td>
                  <?php
                    }
                  ?>
                </tr>
                <tr>
                  <td><b>Property Location</b></td>
                  <td>
                    <?php echo $quote_detail->region ."/".$quote_detail->zone_name."/".$quote_detail->county_name; ?> 
                  </td>
                  <?php 
                    if($quote_detail->txn_type == 'Resale')
                    {
                  ?>
                      <td><b>Loan Amount </b></td>
                      <td>$ <?php echo number_format($quote_detail->loan_amount); ?></td>
                  <?php
                    }
                  ?>
                </tr>
              </tbody>
            </table>
            <div class="clearfix"><p></p></div>
            <table class="table-null" cellspacing="5" cellpadding="5">
              <tbody>
                <tr>
                  <td>
                    <table class="table-data">                      
                      <?php

                      // Title Rate Calculation
                        $row = $this->welcome_model->get_title_rate($quote_detail->quote_id_pk);
                        // echo "<pre>row"; print_r($row); exit;
                        $residential_owner_rate = $row->owner_rate;
                        $home_owner_rate = $row->home_owner_rate;
                        $alta_lenders_rate = $row->con_loan_rate;
                        $residential_loan = ($row->resi_loan_rate) ? $row->resi_loan_rate : $row->refi_rate;

                        $row2 = $this->welcome_model->get_title_rate_loan($quote_detail->quote_id_pk);
                        // echo "<pre>"; print_r($row2); exit;
                        $con_full_loan_rate = $row2->con_full_loan_rate;
                        $purchase_rate = $lender = $title_tot = $lender_rate = 0;
                        // echo "<pre>"; print_r($quote_detail); exit;
                        $policy_type = "";
                        if ($quote_detail->txn_type == 'Re-Finance') 
                        {
                          $purchase_rate = $residential_loan;
                          $policy_type = "ALTA Residential Loan Policy";
                        }
                        else if($quote_detail->txn_type == 'Resale')
                        {
                            if ($quote_detail->policy_type == 'Extended') 
                            {
                                $purchase_rate = $residential_owner_rate;
                                $policy_type = "Alta Extended Policy";
                            }
                            else if ($quote_detail->policy_type == 'Regular') 
                            {
                                $purchase_rate = $home_owner_rate;
                                $policy_type = "Alta Homeowners Policy";
                            }
                            else if ($quote_detail->policy_type == 'Standard') 
                            {
                              $purchase_rate = $residential_owner_rate;
                              $policy_type = "CLTA Standard Policy";
                            }
                            if ($quote_detail->is_lender_policy == '1') {
                                $cfpb_row = $this->welcome_model->get_cfpb_title_rate($quote_detail->quote_id_pk);
                                $alta_lenders_rate = $cfpb_row->con_loan_rate;
                                $lender_rate+= $alta_lenders_rate;
                            }
                        }
                        $title_tot+= $purchase_rate + $lender_rate;
                      // Title Rate Calculation

                      //Escrow fees calculation
                        if ($quote_detail->txn_type == 'Re-Finance')
                        {
                          $escrow_fees =  $this->welcome_model->get_escrow_refinance($quote_detail->zone_name,$quote_detail->loan_amount);
                        
                          $escrow_inital_fee = isset($escrow_fees['escrow_rate']) && !empty($escrow_fees['escrow_rate']) ? $escrow_fees['escrow_rate'] : 0 ;

                          //Fetch additional escrow fees
                            $escrow_additional_fees =  $this->welcome_model->get_additional_fees('refinance','escrow');

                            if(isset($escrow_additional_fees) && !empty($escrow_additional_fees))
                            {
                              foreach ($escrow_additional_fees as $key => $value) 
                              {
                                $escrow_additional_fees_total += $value['value'];
                              }
                            }

                            $escro_total = $escrow_inital_fee + $escrow_additional_fees_total;
                          //Fetch additional escrow fees
                        }
                        else if ($quote_detail->txn_type == 'Resale') 
                        {
                            $escrow_fees =  $this->welcome_model->get_escrow_resale($quote_detail->zone_name, $quote_detail->sale_amount);
                            // echo "<pre>"; print_r($escrow_fees); exit;
                            $base_amount = isset($escrow_fees['base_amount']) && !empty($escrow_fees['base_amount']) ? $escrow_fees['base_amount'] : 0;
                            $per_thousand_price = isset($escrow_fees['per_thousand_price']) && !empty($escrow_fees['per_thousand_price']) ? $escrow_fees['per_thousand_price'] : 0;

                            $sale_amount = $quote_detail->sale_amount;

                            $minimum_rate =isset($escrow_fees['minimum_rate']) && !empty($escrow_fees['minimum_rate']) ? $escrow_fees['minimum_rate'] : 0;

                            $base_rate =isset($escrow_fees['base_rate']) && !empty($escrow_fees['base_rate']) ? $escrow_fees['base_rate'] : 0; 

                            $escrow_inital_fee = $base_amount + (($sale_amount * $per_thousand_price) / 1000);
                            // echo "<pre>"; print_r($escrow_inital_fee); exit;

                            if($escrow_inital_fee < $minimum_rate)
                            {
                                $escrow_inital_fee = $minimum_rate;
                            }

                            if((isset($base_rate) && !empty($base_rate)) && empty($escrow_inital_fee))
                            {
                                $escrow_inital_fee = $base_rate;
                            }
                            //Fetch additional escrow fees
                            $escrow_additional_fees =  $this->welcome_model->get_additional_fees('resale','escrow');

                            if(isset($escrow_additional_fees) && !empty($escrow_additional_fees))
                            {
                              foreach ($escrow_additional_fees as $key => $value) 
                              {
                                $escrow_additional_fees_total += $value['value'];
                              }
                            }

                            $escro_total = $escrow_inital_fee + $escrow_additional_fees_total;
                        }
                      //Escrow fees calculation

                        // Recording Fees calculation
                        if ($quote_detail->txn_type == 'Re-Finance')
                        {
                          $recording_additional_fees =  $this->welcome_model->get_additional_fees('refinance','recording');
                        }
                        else if($quote_detail->txn_type == 'Resale')
                        {
                            $recording_additional_fees =  $this->welcome_model->get_additional_fees('resale','recording');
                        }
                        if(isset($recording_additional_fees) && !empty($recording_additional_fees))
                        {
                          foreach ($recording_additional_fees as $record_key => $record_value) 
                          {
                            $recording_additional_fees_total += $record_value['value'];
                          }
                        }                     
                        // Recording Fees calculation

                        //CFPB Calculation
                        // $cfpb_row = $this->welcome_model->get_cfpb_title_rate($quote_detail->quote_id_pk);

                        $residential_owner_rate = $cfpb_row->owner_rate;
                        $home_owner_rate = $cfpb_row->home_owner_rate;
                        $alta_lenders_rate = $cfpb_row->con_loan_rate;
                        $residential_loan = $cfpb_row->resi_loan_rate;
                        //CFPB Calculation

                        // Other Fee calculation
                        if ($quote_detail->txn_type == 'Re-Finance')
                        {
                          $other_additional_fees =  $this->welcome_model->get_additional_fees('refinance','other');
                        }
                        else if ($quote_detail->txn_type == 'Resale')
                        {
                          $other_additional_fees =  $this->welcome_model->get_additional_fees('resale','other');
                        }

                        if(isset($other_additional_fees) && !empty($other_additional_fees))
                        {
                          foreach ($other_additional_fees as $o_key => $o_value) 
                          {
                            $other_additional_fees_total += $o_value['value'];
                          }
                        }                   
                        // Other Fee calculation  
                      ?>
                      <tbody>
                        <tr class="bg-gray">
                          <td class="bg-gray" style="width:60%"><b>Title</b></td>
                          <td class="aright"><b>$ <?=number_format($title_tot, 2) ?></b></td>
                        </tr>
                        <tr>
                          <td><?php echo $policy_type; ?></td>
                          <td class="aright">
                            $ <?php echo number_format($purchase_rate, 2); ?>
                          </td>
                        </tr>
                        <?php 
                          if($quote_detail->is_lender_policy == '1')
                          {
                        ?>
                            <tr>
                              <td>Alta Lenders Concurrent  Loan Rate</td>
                              <td class="aright">$ <?=number_format($alta_lenders_rate, 2) ?></td>
                            </tr>
                        <?php
                          }
                        ?>
                        <tr><td colspan="2">&nbsp;</td></tr>
                        <?php
                          if($quote_detail->is_escrow_rate == '1')
                          {
                        ?>
                            <tr class="bg-gray">
                              <td ><b>Escrow Fees</b></td>
                              <td class="aright">
                                <b>
                                  <?php 
                                    echo ($escro_total !==0) ? '$'. number_format($escro_total, 2) : "" ;
                                  ?>
                                </b>
                              </td>
                            </tr>
                            <tr>
                              <?php 
                                if ($escro_inital !==0)
                                {
                              ?>
                                  <td>Escrow Initial Fees</td>
                                  <td class="aright">
                                    $<?php echo number_format($escrow_inital_fee, 2); ?>
                                  </td>
                              <?php
                                }

                                if(isset($escrow_additional_fees) && !empty($escrow_additional_fees))
                                {
                                  foreach ($escrow_additional_fees as $key => $value) 
                                  {
                              ?>  
                                  <tr>
                                    <td><?php echo $value['name']; ?></td>
                                    <td class="aright">
                                      $<?php echo number_format($value['value'], 2); ?>
                                    </td>
                                  </tr>
                              <?php
                                  }
                                }
                              ?>
                            </tr>
                        <?php
                          }
                        ?>
                      </tbody>
                    </table>
                  </td>
                  <td>
                    <table class="table-data">
                      <tbody>
                        <?php
                          if($quote_detail->is_recording == 1)
                          {
                        ?>
                                <tr class="bg-gray">
                                  <td class="bg-gray" style="width:80%"><b>Recording Fees</b></td>
                                  <td class="aright"><b>
                                    <?php
                                      echo ($recording_additional_fees_total !==0) ? '$'. number_format($recording_additional_fees_total, 2) : "" ; 
                                    ?>
                                  </b></td>
                                </tr>
                        <?php
                              if(isset($recording_additional_fees) && !empty($recording_additional_fees))
                              {
                                foreach ($recording_additional_fees as $r_key => $r_value) 
                                {
                        ?>
                                  <tr>
                                    <td><?php echo $r_value['name']; ?></td>
                                    <td class="aright">
                                      $<?php echo number_format($r_value['value'], 2); ?>
                                    </td>
                                  </tr>
                        <?php
                                }
                              }
                            }
                        ?>
                        <tr><td colspan="2">&nbsp;</td></tr>
                        <?php 
                            if($quote_detail->txn_type == 'Resale')
                            {
                        ?>
                                <tr class="bg-gray">
                          <td class="bg-gray"><b>CFPB Calculation</b></td>
                          <td class="aright"></td>
                        </tr>
                        <tr>
                            <td>Owners Policy (Actual Premium)</td>
                            <td class="aright"><?php echo "$". number_format($purchase_rate, 2); ?></td>
                        </tr>
                        <tr>
                            <td>Simultaneous Loan Policy (Actual Premium)</td>
                            <td class="aright"><?php echo "$".number_format($alta_lenders_rate, 2); ?></td>
                        </tr>
                        <tr>
                            <td>Stand-Alone Loan Policy (Actual Premium)</td>
                            <td class="aright"><?php echo "$".number_format($con_full_loan_rate, 2); ?></td>
                        </tr>
                        <tr>
                            <td>CFPB - Owner’s Policy Disclosed Amount</td>
                            <td class="aright"><?php echo "$".number_format(($purchase_rate + $alta_lenders_rate - $con_full_loan_rate), 2); ?></td>
                        </tr>
            
                        <tr>
                            <td style="color:#f22;">Title Insurance Premium Adjustment</td>
                            <td class="aright" style="color:#f22;">(<?php echo "$".number_format(($purchase_rate - ($purchase_rate + $alta_lenders_rate - $con_full_loan_rate)), 2); ?>)</td>
                        </tr>
                        <tr>
                            <td colspan="2">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="2">&nbsp;</td>
                        </tr>
                        <?php 
                            if(isset($other_additional_fees) && !empty($other_additional_fees))
                            {
                        ?>
                                <tr class="bg-gray">
                                  <td class="bg-gray"><b>Other</b></td>
                                  <td class="aright"><b>
                                    <?php
                                      echo ($other_additional_fees_total !==0) ? '$'. number_format($other_additional_fees_total, 2) : "" ; 
                                    ?>
                                  </b></td>
                                </tr>
                        <?php
                              foreach ($other_additional_fees as $other_key => $other_value) 
                              {
                        ?>
                                <tr>
                                    <td><?php echo $other_value['name']; ?></td>
                                    <td class="aright">
                                      $<?php echo number_format($other_value['value'], 2); ?>
                                    </td>
                                </tr>
                        <?php
                              }
                            }
                        ?>
                        
                        <?php
                            }
                        ?>
                        
                      </tbody>
                    </table>
                  </td>
                </tr>
              </tbody>              
            </table>
            <div class="clearfix">
                <p class=" p-text ntoss"><b>Quote Disclosure</b><br>
                    <small>
                        The charges quoted on this web site are estimates only, and should not be relied on as accurately reflecting the charges for a specific transaction. The actual charges may vary, depending on the availability of discounts, requests for special coverages or services, or other matters specific to the transaction. Please contact your local Pacific Coast Title office or agent for charges associated with a specific transaction. Contact information for Pacific Coast Title Company offices in your area is available at www.pct.com/branches
                    </small>
                </p>
                <br/>            
                <div class="clearfix">
                  <p class=" p-text"><b>CFPB Disclosure</b><br>
                    <small>Note: Amounts shown for Items noted with an asterisk (*) below are disclosed as required by CFPB Rule. Actual charges for such services are shown in the box above</small>
                  </p>
                </div>
            </div>
            </div>
            <div class="clearfix" id="act_btns">  
                <br/>
                <a class="button small orange" id="downld_pdf" href="javascript:;"  onclick="" target="_blank">Download Quote</a>   
                <a class="button small gray"  href="javascript:;" onclick="send_email()">Email Quote</a>
        <?php
          }
        ?>
                <a class="button small blue" href="<?=base_url() ?>calculator" target="_blank">Start New Quote</a>
            </div>

            <div class="clearfix">
                <form  method="POST" class="form-inline" role="form" id="send_email_form" style="display:none;" onsubmit="return false;">
                  <div class="form-group">
                    <label class="sr-only" for="">Email</label>
                    <input type="email" class="form-control" id="email" placeholder="Email">
                    <div id="output" style="display:none;">
                        <div id="output_div" >
                          <span class = "text-danger" id="output_body"></span>
                        </div>
                    </div>
                  </div>
                  <button class="button small" onclick="send_email_id()">Send</button>
                </form>
            </div>
      </div>
    </div>
<!--   </div> -->
</section>
</body>
</html>
<!-- content section end here -->
<script src='<?php echo base_url(); ?>assets/front/js/jspdf.min.js'></script>
<script src='<?php echo base_url(); ?>assets/front/js/html2canvas.min.js'></script>
<script src='<?php echo base_url(); ?>assets/front/js/html2pdf.js'></script>
<script type="text/javascript">

jQuery("body").on("click","#downld_pdf",function(){ 
    jQuery('#act_btns').hide(); 
    var element = document.getElementById('artcle_main'); 
    html2pdf(element, {
        filename: 'Quote.pdf',
        margin: 0.10, 
        image: {
            type: 'jpeg',
            quality: 1
        },
        html2canvas: {
            dpi: 200,
            letterRendering: true
        },
        jsPDF: {
            unit: 'in',
            format: 'letter',
            orientation: 'Portrait'
        }
    });
    jQuery('#act_btns').show();
});

function send_email () 
{
   jQuery("#send_email_form").slideDown();
}


function send_email_id () 
{
 
  var email_id = jQuery("#email").val();
  //alert(email_id);
  if(email_id == "")
  {
    jQuery("#output_body").attr("class","text-danger");
    jQuery("#output_body").html("please fill email first.!!");
    jQuery("#output").show();
    jQuery("#email").focus();
  }
  else
  {
    jQuery("#output_body").html("");
    jQuery("#output").hide();
    jQuery("#output_div").attr("class","text-danger");
    jQuery("#output_body").attr("class","text-danger");
    var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    if(!regex.test(email_id))
    {
      jQuery("#output_body").html("INCORRECT EMAIL ID! ");
      jQuery("#output").show();
      jQuery("#email").focus();
      return false;
    }

    var quote_data = jQuery(".article").html();
    jQuery.ajax({
      url     : "<?=base_url() ?>frontend/calc/welcome/email_quote",
      type    : "post",
      data    : {quote : quote_data,email : email_id},
      success : function( data )
      {
        jQuery("#output_body").html("");
        jQuery("#output_body").html("Your Quote has been emailed. Please check your spam folder in case you dont instantly receive it.");
        jQuery("#output").show();
        return false;
      },
    });
  }
}

function PrintElem(elem)
{
    // Popup(  $(elem).html() );
}

function Popup(data) 
{
    var printWindow = window.open('', '', 'height=400,width=600');
    printWindow.document.write(data);
    printWindow.document.close();

    printWindow.focus();                                         
    printWindow.print();
    printWindow.close(); 
}

</script>

<?php
   $data['calculator'] = 1;
    $this->load->view('layout/footer', $data);
?>
    <!-- Main slider-->
<script src="<?=base_url()?>assets/plugins/slider-pro/jquery.sliderPro.min.js"></script>
	
<script type="text/javascript" src="<?=base_url()?>assets/front/js/modal.min.js"></script>
	<!-- Facebook Pixel Code -->