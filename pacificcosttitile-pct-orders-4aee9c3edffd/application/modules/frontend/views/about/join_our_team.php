<body>
    <?php
        $this->load->view('layout/header');
    ?>

<div class="section-title-page7j area-bg area-bg_blue area-bg_op_60 parallax">
          <div class="area-bg__inner">
            <div class="container">
              <div class="row">
                <div class="col-xs-12">
                  <h1 class="b-title-page">contact us</h1>
                  <div class="b-title-page__info">get in touch</div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- end .b-title-page-->
    <div class="map" id="map"></div>
      <div class="container">
        <div class="row">
          <div class="col-md-6">
            <section class="section-default">
              <h2 class="ui-title-block-3 ui-title-block-3_sm">send message</h2>
              <div class="ui-decor-2 ui-decor-2_sm bg-primary"></div>
                <div id="success"></div>
                <form class="b-form-contacts ui-form ui-form-3" id="contactForm" action="#" method="post">
                  <div class="row">
                    <div class="col-md-6">
                      <input class="form-control" id="user-name" type="text" name="user-name" placeholder="Name" required>
                    </div>
                    <div class="col-md-6">
                      <input class="form-control" id="user-email" type="email" name="user-email" placeholder="Email">
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-xs-12">
                      <input class="form-control last-block_mrg-btn_0" id="user-subject" type="text" name="user-subject" placeholder="Subject">
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-xs-12">
                      <textarea class="form-control" id="user-message" rows="5" placeholder="Comments" required></textarea>
                      <button class="btn btn-grad-1 btn-round">send message</button>
                    </div>
                  </div>
                </form>
                <!-- end .b-form-contact-->
            </section>
            <!-- end .section-default-->
          </div>
          <div class="col-md-6">
              <section class="section-default b-contact">
                <h2 class="ui-title-block-3 ui-title-block-3_sm">corporate contact info</h2>
                <div class="ui-decor-2 ui-decor-2_sm bg-primary"></div>
                <p>Below you will find all of our contact information. You can also use our online form to submit your question. We look forward to hearing from you.</p>
                <div class="b-contact-desc">
                  <div class="b-contact-desc__item">
                    <div class="b-contact-desc__name">address</div>
                    <div class="b-contact-desc__info">1111 E. Katella Ave. Ste 120 Orange, CA 92867</div>
                  </div>
                  <div class="b-contact-desc__item">
                    <div class="b-contact-desc__name">phone</div>
                    <div class="b-contact-desc__info">(714) 516-6700</div>
                  </div>
                  <div class="b-contact-desc__item">
                    <div class="b-contact-desc__name">email</div>
                    <div class="b-contact-desc__info">hr@pct.com</div>
                  </div>
                  <div class="b-contact-desc__item">
                    <div class="b-contact-desc__name">Web</div>
                    <div class="b-contact-desc__info">www.pct.com</div>
                  </div>
                </div>
                  <ul class="social-net list-inline social-net-colors">
                   
                   
                   
                  </ul>
                  <!-- end .social-list-->
              </section>
              <!-- end .b-contact-->
          </div>
        </div>
      </div>
    <!-- end .section-type-14-->
    <br><br>
    
    

</body>

</html>
<?php
    $this->load->view('layout/footer');
?>

<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCoMfJn9Q37LUYQucbUdgWF8JGWRuTZlt4&libraries=places&sensor=false"></script>
<script src="<?php echo base_url(); ?>assets/js/map-custom.js"></script>