<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <link rel="icon" type="image/png" href="/favicon.ico">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title><?php echo $title; ?></title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />

    <link href="<?php echo base_url(); ?>assets/backend/hr/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet" />
    <link href="<?php echo base_url(); ?>assets/backend/hr/css/sb-admin-2.min.css?v=02" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url(); ?>assets/backend/css/jquery-ui.css" rel="stylesheet" type="text/css">

    <?php echo $css_files; ?>
    <script>
        var base_url = "<?php echo base_url(); ?>";
		document.cookie = "user_timezone="+Intl.DateTimeFormat().resolvedOptions().timeZone+";path=/";
    </script>
</head>
<body id="page-top">
    <div id="page-preloader" style="background-color: rgba(0, 0, 0, 0.5); display: none;"><span class="spinner border-t_second_b border-t_prim_a"></span></div>
    <div id="wrapper">
        <?php echo $sidebar; ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php echo $header; ?>
                <?php echo $content; ?>
            </div>
            <?php echo $footer; ?>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="<?php echo base_url(); ?>assets/backend/hr/vendor/jquery/jquery.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/backend/js/jquery-ui.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/backend/hr/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?php echo base_url(); ?>assets/backend/hr/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?php echo base_url(); ?>assets/backend/hr/js/sb-admin-2.min.js"></script>
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <?php echo $js_files; ?>

    <?php $userdata = $this->session->userdata('hr_admin');
        if(!empty($userdata)) { ?>
            <script type="text/javascript">
                $(document).ready(function() {
                    "use strict";
                    var $preloader = $('#page-preloader'),
                    $spinner   = $preloader.find('.spinner-loader');
                    $spinner.fadeOut();
                    $preloader.delay(50).fadeOut('slow');
                });

                var notificationsWrapper   = $('.admin-notifications');
                var notificationsToggle    = notificationsWrapper.find('a[data-toggle]');
                var notificationsCountElem = notificationsToggle.find('span[data-count]');
                var notificationsCount     = parseInt(notificationsCountElem.data('count'));
                var notifications          = notificationsWrapper.find('div.notification-item-list');
                var notificationClickFlag  = 0;
                var newNotificationFlag    = 0;

                // Enable pusher logging - don't include this in production
                Pusher.logToConsole = true;

                var pusher = new Pusher('<?php echo env("PUSHER_KEY"); ?>', {
                    cluster: '<?php echo env("PUSHER_CLUSTER"); ?>'
                });

                var channel = pusher.subscribe('admin-channel-'+'<?php echo $userdata['id'];?>');
                channel.bind('admin-event-'+'<?php echo $userdata['id'];?>', function(data) {
                    var notification = data;
                    var alertClass = '';
                    var iconClass = '';
                    if (notification.type == 'approved' || notification.type == 'completed') {
                        alertClass = 'bg-success';
                        iconClass = 'fa-check';
                    } else if (notification.type == 'denied') {
                        alertClass = 'bg-danger';
                        iconClass = 'fa-ban';
                    } else if (notification.type == 'accepted' || notification.type == 'assigned' || notification.type == 'submitted') {
                        alertClass = 'bg-warning';
                        iconClass = 'fa-exclamation-triangle';
                    }
                     
                    var existingNotifications = notifications.html();
                    
                    var newNotificationHtml = `<a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="mr-3">
                                        <div class="icon-circle `+alertClass+`">
                                            <i class="fas `+iconClass+` text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-gray-500">`+notification.date+`</div>
                                        `+notification.message+`
                                    </div>
                                </a>`; 
 
                    if (notificationsCount > 0 ){
                        notifications.html(newNotificationHtml + existingNotifications);
                    } else {
                        notifications.html(newNotificationHtml);
                    }     
                    notificationsCount += 1;
                    notificationsCountElem.attr('data-count', notificationsCount);
                    notificationsWrapper.find('.badge-counter').removeClass('d-none').text(notificationsCount);
                    notificationsWrapper.show();
                    newNotificationFlag = 1;
                });

                $('#adminNotificationDropdown').click(function(e) {
                    if(notificationClickFlag == 0 || newNotificationFlag == 1) {
                        if( notificationsCount > 0 ) {                            
                            $.ajax({
                                type: "POST",
                                url: base_url+"hr/admin/mark-as-read",  
                                async: false,                                          
                                success: function(response){     
                                    notificationClickFlag = 1; 
                                    notificationsCountElem.attr('data-count', 0);
                                    notificationsCount = 0;
                                    notificationsWrapper.find('.badge-counter').addClass('d-none').text(0);
                                    newNotificationFlag = 0;
                                },
                                error: function(response){	
                                    notificationClickFlag = 0;  
                                }                                        
                            });
                        }
                    } else {
                        var newNotificationHtml = `
                            <a class="dropdown-item d-flex align-items-center" href="#">
                                <div>
                                    <span class="font-weight-bold">No new notification found</span>
                                </div>
                            </a>`;
                        notifications.html(newNotificationHtml); 
                        notificationsCountElem.attr('data-count', 0);
                        notificationsCount = 0;
                        notificationsWrapper.find('.badge-counter').addClass('d-none').text(0); 
                    }  
                }); 
            </script>
        <?php }
    ?>
</body>

</html>
