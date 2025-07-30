    <script type="text/javascript">
        var base_url = '<?php echo base_url(); ?>';
    </script>
    <!-- Start main scripts -->
    <?php 
        $url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];


        if (strpos($url,'calculator') !== false) {
            echo 'Car exists.';
        } else { ?>
            <script src="<?php echo base_url(); ?>assets/libs/jquery-1.12.4.min.js"></script>
        <?php }
    ?>
    
    <script src="<?php echo base_url(); ?>assets/libs/jquery-migrate-1.2.1.js"></script>
    <!-- Bootstrap-->
    <script src="<?php echo base_url(); ?>assets/libs/bootstrap/bootstrap.min.js"></script>  
    <!-- Select customization & Color scheme-->
    <script src="<?php echo base_url(); ?>assets/plugins/bootstrap-select/js/bootstrap-select.js"></script>
    <!-- Slider-->
    <script src="<?php echo base_url(); ?>assets/plugins/owl-carousel/owl.carousel.min.js"></script>
    <!-- Pop-up window-->
    <script src="<?php echo base_url(); ?>assets/plugins/magnific-popup/jquery.magnific-popup.min.js"></script>
    <!-- Headers scripts-->
    <script src="<?php echo base_url(); ?>assets/plugins/headers/slidebar.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/headers/header.js"></script>
    <!-- Mail scripts-->
    <script src="<?php echo base_url(); ?>assets/plugins/jqBootstrapValidation.js"></script>
    <!-- <script src="<?php echo base_url(); ?>assets/plugins/contact_me.js"></script> -->

    <!-- Video player-->
    <script src="<?php echo base_url(); ?>assets/plugins/flowplayer/flowplayer.min.js"></script>

    <!-- Filter and sorting images-->
    <script src="<?php echo base_url(); ?>assets/plugins/isotope/isotope.pkgd.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/isotope/imagesLoaded.js"></script>
    <!-- Progress numbers-->
    <script src="<?php echo base_url(); ?>assets/plugins/rendro-easy-pie-chart/jquery.easypiechart.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/rendro-easy-pie-chart/waypoints.min.js"></script>
        
    <!-- Animations-->
    <script src="<?php echo base_url(); ?>assets/plugins/scrollreveal/scrollreveal.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/revealer/js/anime.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/revealer/js/scrollMonitor.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/revealer/js/main.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/animate/wow.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/animate/jquery.shuffleLetters.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/animate/jquery.scrollme.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/backend/vendor/datatables/jquery.dataTables.js"></script>
    <script src="<?php echo base_url(); ?>assets/backend/vendor/datatables/dataTables.bootstrap4.js"></script>
    <script src="<?php echo base_url(); ?>assets/backend/vendor/datatables/dataTables.bootstrap4.js"></script>
    <script src="<?php echo base_url(); ?>assets/backend/vendor/datatables/dataTables.buttons.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/backend/vendor/datatables/buttons.html5.min.js"></script>

    

    <!-- User customization-->
    <script src="<?php echo base_url(); ?>assets/js/custom.js"></script>

    

    <!-- Facebook Pixel Code -->
    <script>
        !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
        n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
        n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
        t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
        document,'script','https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '307916942954456', {
        em: 'insert_email_variable'
        });
        fbq('track', 'PageView');
    </script>
    <noscript>
        <img height="1" width="1" style="display:none"
        src="https://www.facebook.com/tr?id=307916942954456&ev=PageView&noscript=1"
        />
    </noscript>