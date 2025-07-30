<!DOCTYPE html>
<html>
<head>
	<title><?php echo $title; ?></title>
	<meta charset="utf-8">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<meta content="We specialize in Residential, Commercial Title & Escrow Services" name="description">
	<meta content="" name="keywords">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta content="telephone=no" name="format-detection">
	<meta name="HandheldFriendly" content="true">
	<link rel="icon" href="<?php echo base_url(); ?>assets/frontend/images/favicon.ico" type="image/x-icon">
	<?php echo $css_files; ?>
    <style>
        .pagination {
            overflow: hidden;
        }
        .pagination > li > a {
            width: 42px;
            height: 42px;
            margin-right: 8px;
            padding-top: 14px;
            border: 1px solid rgba(221, 221, 221, 0.5);
        }
        .pagination > .active > a, .pagination > .active > span, .pagination > .active > a:hover, .pagination > .active > span:hover, .pagination > .active > a:focus, .pagination > .active > span:focus {
            background-color: #6533d7;
            background-image: -webkit-linear-gradient(305deg, #6533d7 0%, #339bd7 100%);
        }
        .pagination > li > a:hover, .pagination > li > span:hover, .pagination > li > a:focus, .pagination > li > span:focus {
            background-color: #6533d7;
            background-image: -webkit-linear-gradient(305deg, #6533d7 0%, #339bd7 100%);
        }
        .dataTables_paginate {
            padding-top: 50px;
            padding-bottom: 100px;
            text-align: right;
        }
        .typography-section {
            padding-bottom: 0px;
        }

        .dataTables_filter input {
            height: calc(1.5em + 0.5rem + 2px); 
            background: #fff;
            position: relative;
            vertical-align: top;
            border: 1px solid #cbd2d6;
            display: -moz-inline-stack;
            display: inline-block;
            color: #34495E;
            outline: none;
            height: 42px;
            width: 96%;
            zoom: 1;
            border-radius: 3px;
            margin: 0;
            font-size: 14px;
            font-family: "Roboto", Arial, Helvetica, sans-serif;
            font-weight: 400;
        }

        .button-color {
            color: #888888;
        }
        .button-color-green {
            background: rgb(0, 102, 68);
        }
    
        th {
            text-align: center;
        }
		.ui-widget.ui-widget-content {
			z-index: 10000000;
		}
    </style>
	
	<script>
		var base_url = "<?php echo base_url(); ?>";
		document.cookie = "user_timezone="+Intl.DateTimeFormat().resolvedOptions().timeZone+";path=/";
	</script>
</head>

<body>
	<?php echo $header; ?>
	<?php echo $content; ?>
	<?php echo $footer; ?>

	<!-- Facebook Pixel Code -->
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

	<script src="https://js.pusher.com/7.0/pusher.min.js"></script>
	<?php $userdata = $this->session->userdata('user');
        if(!empty($userdata)) { ?>
            <script>
                var notificationsWrapper = $('.user-notifications');
                var notificationsToggle = notificationsWrapper.find('a[data-toggle]');
                var notificationsCountElem = notificationsToggle.find('span[data-count]');
                var notificationsCount = parseInt(notificationsCountElem.data('count'));
                var notifications = notificationsWrapper.find('div.notification-item-list');
                var notificationClickFlag = 0;
                var newNotificationFlag = 0;

                // Enable pusher logging - don't include this in production
                Pusher.logToConsole = true;

                var pusher = new Pusher('<?php echo env("PUSHER_KEY"); ?>', {
                    cluster: '<?php echo env("PUSHER_CLUSTER"); ?>'
                });

                var channel = pusher.subscribe('user-channel-' + '<?php echo $userdata['id'];?>');
                channel.bind('user-event-' + '<?php echo $userdata['id'];?>',
                    function (data) {
                        console.log(data);
                        var notification = data;
                        var alertClass = '';
                        var iconClass = '';
                        if (notification.type == 'approved' || notification.type == 'completed') {
                            alertClass = 'bg-success';
                            iconClass = 'fa-check';
                        } else if (notification.type == 'denied') {
                            alertClass = 'bg-danger';
                            iconClass = 'fa-ban';
                        } else if (notification.type == 'added' || notification.type == 'assigned' || notification.type ==
                            'created') {
                            alertClass = 'bg-warning';
                            iconClass = 'fa-exclamation-triangle';
                        }

                        var existingNotifications = notifications.html();

                        var newNotificationHtml = `<a class="dropdown-item d-flex align-items-center" href="#">
                                            <div class="mr-3">
                                                <div class="icon-circle ` + alertClass + `">
                                                    <i class="fa ` + iconClass + ` text-white"></i>
                                                </div>
                                            </div>
                                            <div style="width: max-content;color:#333 !important;">
                                                <div class="small text-gray-500">` + notification.date + `</div>
                                                ` + notification.message + `
                                            </div>
                                        </a>`;

                        if (notificationsCount > 0) {
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

                $('#notificationDropdown').click(function (e) {
                    if (notificationClickFlag == 0 || newNotificationFlag == 1) {
                        if (notificationsCount > 0) {
                            $.ajax({
                                type: "POST",
                                url: base_url + "mark-as-read",
                                async: false,
                                success: function (response) {
                                    notificationClickFlag = 1;
                                    notificationsCountElem.attr('data-count', 0);
                                    notificationsCount = 0;
                                    notificationsWrapper.find('.badge-counter').addClass('d-none').text(0);
                                    newNotificationFlag = 0;
                                },
                                error: function (response) {
                                    notificationClickFlag = 0;
                                }
                            });
                        }
                    } else {
                        var newNotificationHtml = `
                                        <a class="dropdown-item d-flex align-items-center" href="#">
                                            <div style="width: 44rem!important;">
                                                <span style="color:#333 !important;" class="font-weight-bold">No new notifications found</span>
                                            </div>
                                        </a>`;
                        notifications.html(newNotificationHtml);
                        notificationsCountElem.attr('data-count', 0);
                        notificationsCount = 0;
                        notificationsWrapper.find('.badge-counter').addClass('d-none').text(0);
                    }
                });

            </script>
	<?php } ?>
</body>

</html>
