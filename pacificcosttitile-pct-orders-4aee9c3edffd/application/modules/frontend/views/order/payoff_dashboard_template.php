<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title><?php echo $title; ?></title>

    <link href="<?php echo base_url(); ?>assets/backend/hr/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"/>
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet" />
    <link href="<?php echo base_url(); ?>assets/backend/hr/css/sb-admin-2.min.css?v=02" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url(); ?>assets/backend/css/jquery-ui.css" rel="stylesheet" type="text/css">

    <!-- Page level plugin CSS-->
    <link href="<?php echo base_url(); ?>assets/backend/vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?php echo base_url(); ?>assets/backend/css/jquery-ui.css" rel="stylesheet">
    <!-- <link href="<?php echo base_url(); ?>assets/backend/css/sb-admin.css" rel="stylesheet"> -->
    <link href="<?php echo base_url(); ?>assets/backend/css/custom.css?v=1" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/backend/css/daterangepicker.css" rel="stylesheet">
    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css"> -->
    <link href="<?php echo base_url(); ?>assets/backend/hr/css/sb-admin-2.min.css?v=02" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
    <script src="<?php echo base_url(); ?>assets/backend/hr/vendor/jquery/jquery.min.js"></script>
    <?php echo $css_files; ?>
    <script>
        var base_url = "<?php echo base_url(); ?>";
		document.cookie = "user_timezone="+Intl.DateTimeFormat().resolvedOptions().timeZone+";path=/";
    </script>
</head>
<body id="page-top sidebar-toggled">
    <div id="page-preloader" style="background-color: rgba(0, 0, 0, 0.5); display: none;"><span class="spinner border-t_second_b border-t_prim_a"></span></div>
    <div id="page-list-loader" style="background-color: rgba(0, 0, 0, 0.5); display: none;"><span class="spinner border-t_second_b border-t_prim_a"></span></div>
    <div id="wrapper">
        <?php echo $sidebar; ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content" class="dashboard-wrap">
                <?php echo $header; ?>
                <?php echo $content; ?>
            </div>
            <?php echo $footer; ?>
        </div>
    </div>

    <!-- <script src="<?php echo base_url(); ?>assets/backend/hr/vendor/jquery/jquery.min.js"></script> -->
    <script src="<?php echo base_url(); ?>assets/backend/js/jquery.validate.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/backend/js/jquery-ui.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/backend/js/moment.js"></script>
    <script src="<?php echo base_url(); ?>assets/backend/js/daterangepicker.js"></script>
    <script src="<?php echo base_url(); ?>assets/backend/hr/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <script src="<?php echo base_url(); ?>assets/backend/hr/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/backend/hr/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/backend/vendor/datatables/jquery.dataTables.js"></script>
    <script src="<?php echo base_url(); ?>assets/backend/vendor/datatables/dataTables.bootstrap4.js"></script>
    <script src="<?php echo base_url(); ?>assets/backend/vendor/datatables/dataTables.buttons.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/backend/vendor/datatables/buttons.html5.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/backend/hr/js/sb-admin-2.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/frontend/js/jquery.form.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/frontend/js/jquery.validate.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/custom.js?random=<?php echo uniqid(); ?>"></script>
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <?php echo $js_files; ?>
    <script>
		! function (f, b, e, v, n, t, s) {
			if (f.fbq) return;
			n = f.fbq = function () {
				n.callMethod ?
					n.callMethod.apply(n, arguments) : n.queue.push(arguments)
			};
			if (!f._fbq) f._fbq = n;
			n.push = n;
			n.loaded = !0;
			n.version = '2.0';
			n.queue = [];
			t = b.createElement(e);
			t.async = !0;
			t.src = v;
			s = b.getElementsByTagName(e)[0];
			s.parentNode.insertBefore(t, s)
		}(window,
			document, 'script', 'https://connect.facebook.net/en_US/fbevents.js');
		fbq('init', '307916942954456', {
			em: 'insert_email_variable'
		});
		fbq('track', 'PageView');
	</script>

	<noscript>
		<img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=307916942954456&ev=PageView&noscript=1" />
	</noscript>

    <?php $userdata = $this->session->userdata('user');
if (!empty($userdata)) {?>
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

                var channel = pusher.subscribe('admin-channel-'+'<?php echo $userdata['id']; ?>');
                channel.bind('admin-event-'+'<?php echo $userdata['id']; ?>', function(data) {
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
                                url: base_url + "mark-as-read",
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
