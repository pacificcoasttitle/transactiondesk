<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title><?php echo $title; ?></title>
    <meta content="We specialize in Residential, Commercial Title & Escrow Services" name="description">
    <meta content="" name="keywords">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="telephone=no" name="format-detection">
    <meta name="HandheldFriendly" content="true">
    <meta charset="utf-8" />
    <link rel="icon" href="<?php echo base_url(); ?>assets/frontend/images/favicon.ico" type="image/x-icon">
    <style>
        th {
            text-align: center;
        }
    </style>
    <?php echo $css_files; ?>
    <?php echo $js_files; ?>
    <script>
        var base_url = "<?php echo base_url(); ?>";
		document.cookie = "user_timezone="+Intl.DateTimeFormat().resolvedOptions().timeZone+";path=/";
    </script>
</head>
<body>
    <div id="page-preloader">
        <span class="spinner border-t_second_b border-t_prim_a"></span>
    </div>
    <div class="l-theme animated-css" style="height:auto;" data-header="sticky" data-header-top="200" data-canvas="container">
        <?php echo $header; ?>
    </div>
    <?php echo $content; ?>
    <?php echo $footer; ?>      
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <?php $userdata = $this->session->userdata('hr_user');
        if(!empty($userdata)) { ?>
            <script>
                var notificationsWrapper   = $('.user-notifications');
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

                var channel = pusher.subscribe('user-channel-'+'<?php echo $userdata['id'];?>');
                channel.bind('user-event-'+'<?php echo $userdata['id'];?>', function(data) {
                    var notification = data;
                    var alertClass = '';
                    var iconClass = '';
                    if (notification.type == 'approved') {
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
                                            <i class="fa `+iconClass+` text-white"></i>
                                        </div>
                                    </div>
                                    <div style="width: max-content;color:#333 !important;">
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

                $('#notificationDropdown').click(function(e) {
                    if(notificationClickFlag == 0 || newNotificationFlag == 1) {
                        if( notificationsCount > 0 ) {                            
                            $.ajax({
                                type: "POST",
                                url: base_url+"hr/mark-as-read",  
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
				<?php if(isset($time_tracking) && isset($clock_event)) : ?>
				var start_time = <?php echo $time_tracking ?>;
				var storeTimeInterval = 0;
				var call_request = 0;
				<?php if($clock_event == 'OUT') : ?>
					storeTimeInterval = setInterval(myTimer, 1000);
				<?php else : ?>
					secondsToHms(start_time);
				<?php endif; ?>
				function myTimer() {
					
					start_time++;
					secondsToHms(start_time)
				}
				function secondsToHms(d) {
					d = Number(d);
					var h = Math.floor(d / 3600);
					var m = Math.floor(d % 3600 / 60);
					var s = Math.floor(d % 3600 % 60);

					


					var hDisplay = h > 0 ? h  : "00";
					var mDisplay = m > 0 ? m  : "00";
					var sDisplay = s > 0 ? s  : "00";
					res = String(hDisplay).padStart(2, '0') +' : '+ String(mDisplay).padStart(2, '0') +' : '+ String(sDisplay).padStart(2, '0'); 
					$("#timeClock").html(res);
				}

				$(".track-time-btn,.track-time-confirm-btn").click(function(e) {
					$(this).attr("disabled", true);

					if($(this).hasClass('time-start')) {
						var data = {clock_event : 'IN',is_break:0};
						storeTimeInterval = setInterval(myTimer, 1000);
						call_request = 1;
					}
					else {
						call_request = 0;
						$("#timeTrackingModal").modal("show");
						$(".track-time-btn").attr("disabled", false);
						if($(this).hasClass('track-time-confirm-btn')) {

							var is_break = $('#timeTrackingModal input[name="break_reason"]:checked').val();

							if(storeTimeInterval) {
								clearInterval(storeTimeInterval);
							}
							var data = {clock_event : 'OUT',is_break:is_break};
							call_request = 1;
							$("#timeTrackingModal").modal("hide");
						}
					}
					if(call_request) {

						$.ajax({
								type: "POST",
								url: base_url+"hr/record-time",  
								data : data,    
								dataType: 'json',                                  
								success: function(response){   
									if(response.status)  {
										$(".track-time-btn").toggleClass('hide');
										$(".track-time-btn").attr("disabled", false);
										$(".track-time-confirm-btn").attr("disabled", false);
									}
									else {
										location.reload();
									}
								},
								error: function(response){	
									$(".track-time-btn").attr("disabled", false);
									$(".track-time-confirm-btn").attr("disabled", false);
								}                                        
							});
					}
				});
				

				<?php endif; ?>
				
            </script>
        <?php }
    ?>
</body>

</html>
