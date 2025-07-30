<style>
.ui-subtitle-block {
    font-family: Merriweather;
    font-style: italic;
    line-height: 1;
}
.ui-title-block + .ui-decor-1 {
    margin-top: 22px;
    margin-bottom: 34px;
}

.ui-decor-1 {
    display: inline-block;
    width: 100px;
    height: 2px;
}

span.orderinfo1 {
    color: #04415D;
    font-weight: bold;
    font-size: 15px;
    line-height: 1.25px;
    margin-top: 15px;
}

h3, .h3 {
    font-size: 20px;
}
.b-advantages-group {
    margin-right: -80px;
    margin-bottom: -26px;
    margin-left: -40px;
}
    
.b-advantages {
    position: relative;
}
.b-advantages_3-col {
    display: inline-block;
    margin-right: 0;
    vertical-align: top;
    width: 32.33%;
}
.b-advantages-2_mod-a {
    margin-bottom: 110px;
}
.stroke {
    font-family: 'Stroke-Gap-Icons';
    speak: none;
    font-style: normal;
    font-weight: normal;
    font-variant: normal;
    text-transform: none;
    line-height: 1;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}
.b-advantages-2 .b-advantages__icon {
    position: absolute;
    z-index: 10;
    top: 30px;
    right: 80px;
    display: block;
    padding: 0 30px;
    font-size: 44px;
    color: #777;
    background-color: #f8f8f8;
}
.b-advantages-2_mod-a .b-advantages__icon {
    top: -5px;
    right: 66px;
    background-color: #fff;
}
.b-advantages-2 .b-advantages__inner {
    margin-top: 54px;
    margin-right: 38px;
    margin-bottom: 26px;
    padding: 42px 40px 45px 50px;
}
.b-advantages-2_mod-a .b-advantages__inner {
    margin-top: 12px;
    padding: 48px 40px 45px 51px;
    box-shadow: inset 0 0 0 5px whitesmoke;
}
.b-advantages__title {
    display: block;
    margin-bottom: 19px;
    font-size: 16px;
    font-weight: 700;
    letter-spacing: .02em;
    text-transform: uppercase;
    color: #333;
    padding: 5px 0;
}
.b-advantages-2 .b-advantages__title {
    position: relative;
    z-index: 200;
}
.b-advantages__title a {
    -webkit-transition: all .3s;
    transition: all .3s;
    color: #333;
}
.b-advantages-2 .b-advantages__title a {
    color: #333;
}
.btn_mrg-top_30 {
    margin-top: 30px;
}
.b-advantages:before, .b-advantages:after {
    display: table;
    content: "";
}
.b-advantages:after {
    clear: both;
}
.b-advantages-2_mod-a .b-advantages__icon:before {
    background-image: -webkit-linear-gradient(135deg, #6533d7 0%, #339bd7 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}
.parallax {
    -webkit-transform: none;
    transform: none;
    background-repeat: no-repeat;
    background-attachment: fixed;
}
.section-type-1 {
    color: #fff;
    background-image: url(../media/content/bg/bg-1.jpg);
}
.area-bg {
    position: relative;
}
.section-sm {
    padding-top: 55px;
    padding-bottom: 65px;
}
.area-bg__inner {
    position: relative;
    z-index: 110;
}
.container:before {
    content: " ";
    display: table;
}
.parallax {
    -webkit-transform: none;
    transform: none;
    background-repeat: no-repeat;
    background-attachment: fixed;
}
.section-type-1 {
    color: #fff;
    background-image: url('../assets/media/content/bg/bg-1.jpg');
}
.area-bg {
    position: relative;
}
.section-type-1 .ui-title-block-3 {
    color: #fff;
}
.ui-title-block-3 {
    font-size: 30px;
    font-weight: 300;
    letter-spacing: .02em;
    text-transform: uppercase;
}
.ui-subtitle-block-2 {
    letter-spacing: .37em;
    text-transform: uppercase;
}
.section-type-1 .btn {
    margin-top: 20px;
    margin-bottom: 10px;
    margin-left: 20px;
    padding-right: 35px;
    padding-left: 35px;
    color: #fff;
    border-color: #fff;
    background-color: transparent;
}

.container:after {
    clear: both;
    content: " ";
    display: table;
}

.area-bg:after {
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    content: '';
    opacity: .85;
}
.area-bg_op_70:after {
    opacity: .7;
}
.area-bg_grad-2:after {
    background-color: #d35410;
}
</style>
<body>
    <?php
       // $this->load->view('layout/header');
        // $this->load->view('layout/header_dashboard');
    ?>

        <!-- end .b-title-page-->
        <article class="b-about section-default">
          <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="ui-subtitle-block mb-4">Important Details Below</div>
                    <h2 class="ui-title-block ui-title-block_light">Your Order Info...</h2>
                    <div class="ui-decor-1 bg-primary"></div>
                    <p>We will be sending you an email confirmation shortly. Below you can find your order number, the full legal description, and the vesting information for your recently submitted order.</p>
                </div>
              <div class="col-md-6">
                <footer class="b-about__footer">
				<ul class="list list-mark-2">
                    <?php
                        if(isset($tp_data['file_number']) && !empty($tp_data['file_number']))
                        {
                    ?>
                            <li>
                                <h3>Order Number:</h3><br>
                                <span class="orderinfo1" id="orderNumber"><strong><?php echo $tp_data['file_number']; ?></strong></span>
                            </li><br>
                            <input type="hidden" name="id" id="CustomerId" value="<?php echo $customer_id;?>">
                            <input type="hidden" name="property-full-address" id="property-full-address" value="<?php echo $property;?>">
                    <?php
                        }
                    ?>
                    
                    <li>
                        <h3>Brief Legal Description:</h3><br>
                        <span class="orderinfo1">
                            <span id="legalDescription">
                                <?php 
                                    if(isset($tp_data['legal_description']) && !empty($tp_data['legal_description']))
                                    {
                                        echo $tp_data['legal_description'];
                                    }
                                    else
                                    {
                                        echo 'Refer to grant deed below.';
                                    }
                                ?>
                            </span>
                        </span>
                    </li><br>
                    <li>
                        <h3>Vesting Information:</h3><br>
                        <span class="orderinfo1">
                            <span id="vestingInformation">
                                <?php 
                                    if(isset($tp_data['vesting_information']) && !empty($tp_data['vesting_information']))
                                    {
                                        echo $tp_data['vesting_information'];
                                    }
                                    else
                                    {
                                        echo 'Refer to grant deed below.';
                                    }
                                ?>
                            </span>
                                                     
                        </span>
                    </li><br>                                         
                </ul>
				</footer>
              </div>
              <div class="col-md-6">
                <div class="b-sm-about-group">
                  <div class="row" id="taxInformation">
                    <div class="col-md-6">
                        <h3>1st Installment</h3>
                                <div id="firstInstallment" style="border:1px solid #000000;padding:15px;">
                                <?php
                                    if(isset($tp_data['first_installment']) && !empty($tp_data['first_installment']))
                                    {
                                        $firstInstallment = json_decode($tp_data['first_installment'],TRUE)
                                ?>
                                        <p>Balance: <?php echo isset($firstInstallment['Balance']) && !empty($firstInstallment['Balance']) ? $firstInstallment['Balance'] : '-'?></p>
                                        <p>Amount: <?php echo isset($firstInstallment['Amount']) && !empty($firstInstallment['Amount']) ? $firstInstallment['Amount'] : '-'?></p>
                                        <p>DueDate: <?php echo isset($firstInstallment['DueDate']) && !empty($firstInstallment['DueDate']) ? $firstInstallment['DueDate'] : '-'?></p>
                                        <p>Number: <?php echo isset($firstInstallment['Number']) && !empty($firstInstallment['Number']) ? $firstInstallment['Number'] : '-'?></p>
                                        <p>PaymentDate: <?php echo isset($firstInstallment['PaymentDate']) && !empty($firstInstallment['PaymentDate']) ? $firstInstallment['PaymentDate'] : '-'?></p>
                                        <p>Penalty: <?php echo isset($firstInstallment['Penalty']) && !empty($firstInstallment['Penalty']) ? $firstInstallment['Penalty'] : '-'?></p>
                                        <p>Status: <?php echo isset($firstInstallment['Status']) && !empty($firstInstallment['Status']) ? $firstInstallment['Status'] : '-'?></p>
                                        <p>AmountPaid: <?php echo isset($firstInstallment['AmountPaid']) && !empty($firstInstallment['AmountPaid']) ? $firstInstallment['AmountPaid'] : '-'?></p>
                                        <p>TaxYear: <?php echo isset($firstInstallment['TaxYear']) && !empty($firstInstallment['TaxYear']) ? $firstInstallment['TaxYear'] : '-'?></p>
                                <?php
                                    }
                                    else
                                    {
                                ?>
                                        <span class="orderinfo1">No data found.</span>
                                <?php
                                    }
                                ?>
                            </div>
                        
                        
                    </div>
                    <div class="col-md-6">                      
                        <h3>2nd Installment</h3>                    
                        <div id="secondInstallment" style="border:1px solid #000000;padding:15px;">
                        <?php
                            if(isset($tp_data['second_installment']) && !empty($tp_data['second_installment']))
                            {
                                $secondInstallment = json_decode($tp_data['second_installment'],TRUE);
                        ?>
                                <p>Balance: <?php echo isset($secondInstallment['Balance']) && !empty($secondInstallment['Balance']) ? $secondInstallment['Balance'] : '-'?></p>
                                <p>Amount: <?php echo isset($secondInstallment['Amount']) && !empty($secondInstallment['Amount']) ? $secondInstallment['Amount'] : '-'?></p>
                                <p>DueDate: <?php echo isset($secondInstallment['DueDate']) && !empty($secondInstallment['DueDate']) ? $secondInstallment['DueDate'] : '-'?></p>
                                <p>Number: <?php echo isset($secondInstallment['Number']) && !empty($secondInstallment['Number']) ? $secondInstallment['Number'] : '-'?></p>
                                <p>PaymentDate: <?php echo isset($secondInstallment['PaymentDate']) && !empty($secondInstallment['PaymentDate']) ? $secondInstallment['PaymentDate'] : '-'?></p>
                                <p>Penalty: <?php echo isset($secondInstallment['Penalty']) && !empty($secondInstallment['Penalty']) ? $secondInstallment['Penalty'] : '-'?></p>
                                <p>Status: <?php echo isset($secondInstallment['Status']) && !empty($secondInstallment['Status']) ? $secondInstallment['Status'] : '-'?></p>
                                <p>AmountPaid: <?php echo isset($secondInstallment['AmountPaid']) && !empty($secondInstallment['AmountPaid']) ? $secondInstallment['AmountPaid'] : '-'?></p>
                                <p>TaxYear: <?php echo isset($secondInstallment['TaxYear']) && !empty($secondInstallment['TaxYear']) ? $secondInstallment['TaxYear'] : '-'?></p>
                        <?php
                            }
                            else
                            {
                        ?>
                                <span class="orderinfo1">No data found.</span>
                        <?php
                            }
                        ?>
                    </div>                           
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row mt-5 mb-5" id="grantDeedInfo">
                <div class="col-md-12">
                    <h3>Grant Deed Information:</h3>
                </div>
                <?php 
                    $cs4_result_id_status = isset($tp_data['cs4_message']) && !empty($tp_data['cs4_message']) ? $tp_data['cs4_message'] : '';
                    
                    /*if($cs4_result_id_status == 'Success')
                    {*/
                ?>
                        <div class="col-md-3"> 
                            <div id="grantDeedInfoFile">
                                <?php 
                                    $L_V_serviceId = isset($tp_data['cs4_service_id']) && !empty($tp_data['cs4_service_id']) ? $tp_data['cs4_service_id'] : '';
                                    $instrumentNumber = isset($tp_data['cs4_instrument_no']) && !empty($tp_data['cs4_instrument_no']) ? $tp_data['cs4_instrument_no'] : '';
                                    $state = isset($state) && !empty($state) ? $state : '';
                                    $county = isset($county) && !empty($county) ? $county : '';
                                    $recordedDate = isset($tp_data['cs4_recorded_date']) && !empty($tp_data['cs4_recorded_date']) ? $tp_data['cs4_recorded_date'] : '';
    
                                    if(isset($instrumentNumber) && !empty($instrumentNumber))
                                    {
                                        if(isset($recordedDate) && !empty($recordedDate))
                                        {
                                            $time = strtotime($recordedDate);
                                            $year = date('Y',$time);
                                        }

                                        $count = substr_count($instrumentNumber, '-');

                                        if(isset($count) && !empty($count))
                                        {
                                            $detailDocInfo = explode('-', $instrumentNumber);
                                            
                                            $docId = isset($detailDocInfo['1']) && !empty($detailDocInfo['1']) ? $detailDocInfo['1'] : '';   
                                        }
                                        else
                                        {
                                            $docId = str_replace($year, '', $instrumentNumber);
                                        }
                                        $docId = (string)((int)($docId));
                                    }
                                    
                                    $file_number = isset($tp_data['file_number']) && !empty($tp_data['file_number']) ? $tp_data['file_number'] : ''; 
                                    $fips = isset($tp_data['fips']) && !empty($tp_data['fips']) ? $tp_data['fips'] : '';
                                ?>
                                <?php
                                    if(isset($lv_file_url) && !empty($lv_file_url))
                                    {
                                        if (env('AWS_ENABLE_FLAG') == 1) { ?>
                                            <a href="javascript:void(0);" class="btn btn-success btn-icon-split btn_mrg-top_30" onclick="downloadDocumentFromAws('<?php echo $lv_file_url;?>', 'legal_vesting');">
                                                <span class="icon text-white-50">
                                                    <i class="fas fa-download"></i>
                                                </span>
                                                <span class="text">Download L&V</span>
                                                
                                            </a>
                                        <?php } else { ?>
                                            <a href="<?php echo $lv_file_url; ?>" class="btn btn-default btn-sm btn_mrg-top_30" download="L&V.pdf">Download L&V</a>
                                        <?php } ?>

                                <?php
                                    } else if ($lpFileStatus == 'processing') { ?>
                                        <div class="legal-vesting-no-data processing-wrapper">
                                            <span class="orderinfo1">Document generation in under processing please refresh the page after some time or check your email.</span>
                                            <br />
                                            <a href="javascript:void(0);" class="btn btn-info btn-icon-split btn_mrg-top_30" onclick="fetchLvDoc(this, '<?php echo $file_number;?>', 'lv');">
                                                <span class="icon text-white-50">
                                                    <i class="fas fa-download"></i>
                                                </span>
                                                <span class="text">Click to fetch</span>
                                            </a>
                                        </div>
                                    <?php }
                                    else
                                    {
                                ?>
                                        <!-- <a href="javascript:void(0);" class="btn btn-default btn-sm btn_mrg-top_30" id="btn-download-L-V" onclick='imageCreateRequest("<?php // echo $L_V_serviceId; ?>",4,"<?php // echo $file_number; ?>");'>Download L&V</a> -->
                                        <div class="legal-vesting-no-data">
                                            <span class="orderinfo1">No legal vesting available. Our customer service will look for it and contact you within X minutes.</span>
                                        </div>
                                <?php
                                    }
                                ?>
                                
                            </div>
                            <div class="loader" style="display: none;"></div>
                        </div>
                    <?php
                        /*if(isset($docId) && !empty($docId))
                        {*/
                    ?>
                            <div class="col-md-3">
                                <div id="instrumentInfoFile">
                                    <?php
                                        if(isset($deed_file_url) && !empty($deed_file_url))
                                        { 
                                            if (env('AWS_ENABLE_FLAG') == 1) { ?>
                                                <a href="javascript:void(0)" class="btn btn-success btn-icon-split btn_mrg-top_30" onclick="downloadDocumentFromAws('<?php echo $deed_file_url;?>', 'grant_deed');">
                                                    <span class="icon text-white-50">
                                                        <i class="fas fa-download"></i>
                                                    </span>
                                                    <span class="text">Download Grant Deed</span>
                                                </a>
                                            <?php } else { ?>
                                                <a href="<?php echo $deed_file_url; ?>" class="btn btn-default btn-sm btn_mrg-top_30" download="GrantDeed.pdf">Download Grant Deed</a>
                                            <?php } ?>
                                    <?php
                                        }
                                        else
                                        {
                                    ?>
                                            <!-- <a href="javascript:void(0);" onclick='generateGrantDeed("<?php // echo $fips; ?>","<?php // echo $year; ?>","<?php // echo $docId; ?>","<?php // echo $file_number; ?>");' class="btn btn-default btn-sm btn_mrg-top_30" id="btn-download-grant-deed">Download Grant Deed</a> -->
                                            <div class="grant-deed-no-data">
                                                <span class="orderinfo1">No grant deed available. Our customer service will look for it and contact you within X minutes.</span>
                                            </div>
                                    <?php  
                                        }
                                    ?>
                                    
                                </div>
                                <div class="loader" style="display: none;"></div>
                            </div>
                            <!-- <div class="col-md-6 grant-deed-no-data">
                                <span class="orderinfo1">No grant deed available. Our customer service will look for it and contact you within X minutes.</span>
                            </div> -->
                            <!-- <div class="col-md-6 grant-deed-no-data">
                            <span class="orderinfo1">No grant deed available. Our customer service will look for it and contact you within X minutes.</span>
                        </div> -->
                
                <?php
                        $cs3_message = isset($tp_data['cs3_message']) && !empty($tp_data['cs3_message']) ? $tp_data['cs3_message'] : '';
                        
                        /*if($cs3_message == 'Success')
                        {*/
                            $apn = str_replace('0000', '0-000', $apn);
                    ?>
                             <div class="col-md-3">
                                <div id="taxDocumentInfo">
                                    <?php
                                        if(isset($tax_file_url) && !empty($tax_file_url))
                                        {
                                            if (env('AWS_ENABLE_FLAG') == 1) { ?>
                                                <a href="javascript:void(0)" class="btn btn-success btn-icon-split btn_mrg-top_30"  onclick="downloadDocumentFromAws('<?php echo $tax_file_url;?>', 'tax');"> 
                                                    <span class="icon text-white-50">
                                                        <i class="fas fa-download"></i>
                                                    </span>
                                                    <span class="text">Download Tax Document</span>
                                                </a>
                                            <?php } else { ?>
                                                <a href="<?php echo $tax_file_url; ?>" class="btn btn-success btn-icon-split btn_mrg-top_30" download="Tax.pdf">
                                                    <span class="icon text-white-50">
                                                        <i class="fas fa-download"></i>
                                                    </span>
                                                    <span class="text">Download Tax Document</span>
                                                </a>
                                            <?php } ?>
                                        <?php } else if ($taxFileStatus == 'processing') { ?>
                                            <div class="tax-no-data  processing-wrapper">
                                                <span class="orderinfo1">Document generation in under processing please refresh the page after some time or check your email.</span>
                                                <br />
                                                <button class="btn"  onClick="fetchLvDoc(this, '<?php echo $file_number;?>', 'tax')" > Click to fetch</button>
                                            </div>
                                    <?php } else {
                                            $tax_serviceId = isset($tp_data['cs3_service_id']) && !empty($tp_data['cs3_service_id']) ? $tp_data['cs3_service_id'] : '';
                                    ?>
                                            
                                            <div class="tax-no-data">
                                                <span class="orderinfo1">No tax document available. Our customer service will look for it and contact you within X minutes.</span>
                                            </div>
                                    <?php  
                                        }
                                    ?>
                                    
                                </div>
                                <div class="loader" style="display: none;"></div>
                            </div>
                    <?php
                        /*}*/
                    ?>
            </div>
          </div>
        </article>
        <!-- end .b-about-->
      <div class="section-area">
        <div class="container">
          <div class="row">
            <div class="col-xs-12">
              <div class="b-advantages-group">
                    <section class="b-advantages b-advantages-2 b-advantages-2_mod-a b-advantages_3-col"><i class="b-advantages__icon stroke flaticon-screen"></i>
                            <div class="b-advantages__inner">
                            <h3 class="b-advantages__title ui-title-inner"><a href="<?php echo base_url().'cpl-dashboard'; ?>">Generate CPL</a></h3>
                            <div class="b-advantages__info">Our customer service team is ready to help create a farm package to help you alert the neighbors about your new listing.</div>
                            <a class="btn btn-success btn-icon-split btn_mrg-top_30" href="<?php echo base_url().'cpl-dashboard'; ?>">
                                <span class="icon text-white-50">
                                    <i class="fas fa-seedling"></i>
                                </span>
                                <span class="text">Generate CPL</span>
                            </a>
                        </div>
                    </section>
                  <!-- end .b-advantages-->
                    <section class="b-advantages b-advantages-2 b-advantages-2_mod-a b-advantages_3-col"><i class="b-advantages__icon stroke flaticon-worldwide"></i>
                        <div class="b-advantages__inner">
                            <h3 class="b-advantages__title ui-title-inner"><a href="<?php echo base_url().'proposed-insured'; ?>">Proposed</a></h3>
                            <div class="b-advantages__info">Login in to our PCT Title Toolbox program and create your own farm package consisting of the various types of owners.</div>
                            <a class="btn btn-success btn-icon-split btn_mrg-top_30" href="<?php echo base_url().'proposed-insured'; ?>">
                                <span class="icon text-white-50">
                                    <i class="fas fa-seedling"></i>
                                </span>
                                <span class="text">Generate Proposed</span>
                            </a>
                        </div>
                    </section>
                  <!-- end .b-advantages-->
                    <section class="b-advantages b-advantages-2 b-advantages-2_mod-a b-advantages_3-col"><i class="b-advantages__icon stroke flaticon-analytics"></i>
                        <div class="b-advantages__inner">
                            <h3 class="b-advantages__title ui-title-inner"><a href="<?php echo base_url().'order'; ?>">Open New Order</a></h3>
                            <div class="b-advantages__info">Need to open another order? That's fantastic. The link below will redirect you back to our Open Order form.</div>
                            <a class="btn btn-success btn-icon-split btn_mrg-top_30" href="<?php echo base_url().'order'; ?>">
                                <span class="icon text-white-50">
                                    <i class="fas fa-seedling"></i>
                                </span>
                                <span class="text">Create</span>
                            </a>
                        </div>
                    </section>
                  <!-- end .b-advantages-->
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- end .section-area-->
      
      <!-- end .section-default-->
    <section class="section-type-1 section-sm parallax area-bg area-bg_grad-2 area-bg_op_70">
        <div class="area-bg__inner">
            <div class="container">
                <div class="row">
                    <div class="col-md-7">
                        <h2 class="ui-title-block-3">we provide higher quality services</h2>
                        <div class="ui-subtitle-block-2">and you’ll get solutions for everything</div>
                    </div>
                    <div class="col-md-5"><a class="btn btn-default btn-round pull-right" href="https://clients.pacificcoasttitle.com/login.aspx?ReturnUrl=/&amp;officeid=1">OPEN ORDERS</a><a class="btn btn-default btn-round pull-right" href="rate-book.html">GET RATES</a></div>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
<script type="text/javascript">
    var base_url = '<?php echo base_url(); ?>';
</script>

<?php
    // $this->load->view('layout/footer');
?>
<script src="<?php echo base_url(); ?>assets/libs/jquery-1.12.4.min.js"></script>
<!-- <script type="text/javascript" src="<?php echo base_url(); ?>assets/frontend/js/jquery-1.9.1.min.js"></script> -->
<!-- <script type="text/javascript" src="<?php echo base_url(); ?>assets/frontend/js/jquery-cloneya.min.js?random=<?php echo uniqid(); ?>"></script> -->
<!-- <script type="text/javascript" src="<?php echo base_url(); ?>assets/frontend/js/order.js?random=<?php echo uniqid(); ?>"></script> -->
<script>
    $(document).ready(function($){
        // $('#page-preloader').css('display', 'none');
        let data = {};
        data.state = "<?php echo $state ?>";
        data.county = "<?php echo $county ?>";
        data.property = "<?php echo $address ?>";
        data.order_id = "<?php echo $order_id ?>";
        data.file_number = "<?php echo $lpFileNumber ?>";
        data.escrow_id = "<?php echo $escrow_id ?>";
        
        if (data.file_number != '') {
            // $(document).ajaxStop(function() {
                // place code to be executed on completion of last outstanding ajax call here
                // if (!executeCall) {
                //     executeCall = true;
                $.ajax({
                    url: base_url + "pre-listing-doc",
                    type: "post",
                    data: data,
                    async: false,
                    success: function (response) {
                        if (response) {
                            console.log('response ==', response);
                        }
                        // $.ajax({
                        //     url: base_url + "generate-all-document-from-title-point",
                        //     type: "post",
                        //     data: data,
                        //     async: true,
                        //     success: function (response) {
                                
                        //     }
                        // });
                    }
                });
                // }
            // });
        }
    });

    function fetchLvDoc(obj,fileNumber, docType) {
        // $(obj).text('Fetching ...');
        $(obj).html('<span class="text">Fetching</span>');
        
        $.ajax({
			url: base_url + "check-document",
			type: "post",
			data: {
				file_number : fileNumber,
                doc_type: docType
			},
            // async: false,
			success: function (response) {
                response = JSON.parse(response);
				if (response && response.url) {
                    var buttonText = '';
                    var downloadButton = '';
                    var doc_type = '';
                    if (docType == 'tax') {
                        buttonText = 'Download Tax Document';
                        doc_type = 'tax';
                    } else {
                        buttonText = 'Download L & V';
                        doc_type = 'legal_vesting';
                    }
                    <?php if (env('AWS_ENABLE_FLAG') == 1) { ?>
                        let url = response.url;
                        downloadButton = "<a href='#' class='btn btn-default btn-sm btn_mrg-top_30' onclick='downloadDocumentFromAws("+ '"' +  url + '"' + ", " + '"' + doc_type +  '"' + ");'>" + buttonText + "</a>";
                    <?php } else { ?>
                        downloadButton = '<a href="' + response.url + '" target="_blank" class="btn btn-default btn-sm btn_mrg-top_30" >' + buttonText + '</a>';
                    <?php } ?>
					$(obj).closest('.processing-wrapper').replaceWith(downloadButton)
				} else {
                    $(obj).html('<span class="text">Click to Fetch</span>');
                    alert('Document generation still in process, Please try afte sometime');
                    return;
                }
			},
            complete: function (data) {
                if (data.status != 200) {
                    $(obj).html('<span class="text">Click to Fetch</span>');
                    alert('Document generation still in process, Please try afte sometime');
                    return;
                }
            }
        });
    }

    function downloadDocumentFromAws(url, documentType)
    {
        $('#page-preloader').css('background-color', 'rgba(0,0,0,.5)');
		$('#page-preloader').css('display', 'block');
        var fileNameIndex = url.lastIndexOf("/") + 1;
        var filename = url.substr(fileNameIndex);
        $.ajax({
			url: base_url + "download-aws-document",
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
						console.log(response);
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