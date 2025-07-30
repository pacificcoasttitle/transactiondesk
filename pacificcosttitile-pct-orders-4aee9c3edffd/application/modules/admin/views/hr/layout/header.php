<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
	
	<ul class="navbar-nav ml-auto">
		
	<li class="nav-item dropdown no-arrow mx-1 admin-notifications">
			<a class="nav-link dropdown-toggle" href="#" id="adminNotificationDropdown" role="button"
				data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				<i class="fas fa-bell fa-fw" style="font-size:26px;"></i>
				<!-- Counter - Alerts -->
				<?php if ($unreadNotificationCount['total_unread_count'] > 0 ) { ?>
					<span class="badge badge-danger badge-counter" data-count="<?php echo $unreadNotificationCount['total_unread_count'];?>"><?php echo $unreadNotificationCount['total_unread_count'];?></span>
				<?php } else { ?>
					<span class="badge badge-danger badge-counter d-none" data-count="<?php echo $unreadNotificationCount['total_unread_count'];?>"><?php echo $unreadNotificationCount['total_unread_count'];?></span>
				<?php } ?>
			</a>
			<!-- Dropdown - Alerts -->
			<div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="adminNotificationDropdown">
				<h6 class="dropdown-header">
					Notification Center
				</h6>
				<div class="slimscroll notification-item-list" style="overflow-y: auto;overflow-x:hidden; max-height:318px;">
				<?Php if(!empty($notifications)) {
						foreach($notifications as $notification) {
							$alertClass = '';
							$iconClass = '';
							if ($notification['type'] == 'approved' || $notification['type'] == 'completed') {
								$alertClass = 'bg-success';
								$iconClass = 'fa-check';
							} else if ($notification['type'] == 'denied') {
								$alertClass = 'bg-danger';
								$iconClass = 'fa-ban';
							} else if ($notification['type'] == 'accepted' || $notification['type'] == 'assigned' || $notification['type'] == 'submitted') {
								$alertClass = 'bg-warning';
								$iconClass = 'fa-exclamation-triangle';
							}
							?>
							<a class="dropdown-item d-flex align-items-center" href="#">
								<div class="mr-3">
									<div class="icon-circle <?php echo $alertClass;?>">
										<i class="fas <?php echo $iconClass;?> text-white"></i>
									</div>
								</div>
								<div>
									<div class="small text-gray-500"><?php echo date('F d, Y', strtotime($notification['created_at']));?></div>
									<?php echo $notification['message'];?>
								</div>
							</a>
						<?php } ?>
					<?php } else { ?>
						<a class="dropdown-item d-flex align-items-center" href="#">
							
							<div>
								<span class="font-weight-bold">No new notification found</span>
							</div>
						</a>
					<?php } ?>	
				</div>
				<a class="dropdown-item text-center small text-gray-500" href="<?php echo base_url().'hr/admin/notifications'; ?>">Show All Notification</a>
			</div>
		</li>

		<li class="nav-item dropdown no-arrow">
			<a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
				aria-haspopup="true" aria-expanded="false">
				<?php $admin = $this->session->userdata('hr_admin');?>
				<span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo strtoupper($admin['name']);?></span>
				<img class="img-profile rounded-circle" src="<?php echo base_url()?>assets/backend/hr/img/undraw_profile.svg">
			</a>
			
			<div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
				<a class="dropdown-item" href="<?php echo base_url().'hr/admin/logout'; ?>">
					<i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
					Logout
				</a>
			</div>
		</li>

		
	</ul>
</nav>

