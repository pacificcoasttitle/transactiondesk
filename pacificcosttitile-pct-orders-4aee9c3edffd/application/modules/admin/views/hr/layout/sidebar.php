<?php $userdata = $this->session->userdata('hr_admin');?>
<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

	<!-- Sidebar - Brand -->
	<a class="sidebar-brand d-flex align-items-center justify-content-center"
		href="<?php echo base_url().'hr/admin/dashboard'; ?>">
		<img style="width:200px;" src="<?php echo base_url();?>assets/backend/hr/img/logo2.png">
	</a>

	<!-- Divider -->
	<hr class="sidebar-divider my-0">

	<li class="nav-item <?php if($this->uri->uri_string() == 'hr/admin/dashboard') { echo 'active'; } ?>">
		<a class="nav-link" href="<?php echo base_url().'hr/admin/dashboard'; ?>">
			<i class="fas fa-fw fa-tachometer-alt"></i>
			<span>Dashboard</span>
		</a>
	</li>

	<?php if($userdata['user_type_id'] == 4 && $userdata['department_id'] == 4) : ?>
		<li class="nav-item <?php if($this->uri->uri_string() == 'hr/admin/orders' || $this->uri->segment(3) == 'order-tasks') { echo 'active'; } ?>">
			<a class="nav-link" href="<?php echo base_url().'hr/admin/orders'; ?>">
				<i class="fas fa-list"></i>
				<span>Orders</span>
			</a>
		</li>
	<?php endif; ?>

	<li
		class="nav-item <?php if($this->uri->uri_string() == 'hr/admin/time-sheets' || $this->uri->uri_string() == 'hr/admin/time-cards' || $this->uri->uri_string() == 'hr/admin/add-time-card' || $this->uri->segment(3) == 'edit-time-card') { echo 'active'; } ?>">
		<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#time_card_menu" aria-expanded="true"
			aria-controls="time_card_menu">
			<i class="fas fa-fw fa-users"></i>
			<span>Time Cards</span>
		</a>
		
		<div id="time_card_menu" class="collapse <?php if($this->uri->uri_string() == 'hr/admin/time-sheets' || $this->uri->uri_string() == 'hr/admin/time-cards' || $this->uri->uri_string() == 'hr/admin/add-time-card' || $this->uri->segment(3) == 'edit-time-card') { echo 'show'; } ?>" aria-labelledby="users_menu" data-parent="#accordionSidebar">
			<div class="bg-white py-2 collapse-inner rounded">
				<a class="collapse-item <?php if($this->uri->uri_string() == 'hr/admin/time-cards' || $this->uri->uri_string() == 'hr/admin/add-time-card' || $this->uri->segment(3) == 'edit-time-card') { echo 'active'; } ?>"
					href="<?php echo base_url().'hr/admin/time-cards'; ?>">Exceptions</a>
					<a class="collapse-item <?php if($this->uri->uri_string() == 'hr/admin/time-sheets') { echo 'active'; } ?>"
					href="<?php echo base_url().'hr/admin/time-sheets'; ?>">Time Sheets</a>
				
			</div>
		</div>
	</li>

	<li
		class="nav-item <?php if($this->uri->uri_string() == 'hr/admin/vacation-requests' || $this->uri->uri_string() == 'hr/admin/add-vacation-request' || $this->uri->segment(3) == 'edit-vacation-request') { echo 'active'; } ?>">
		<a class="nav-link" href="<?php echo base_url().'hr/admin/vacation-requests'; ?>">
			<i class="fas fa-fw fa-table"></i>
			<span>Vacation Requests</span>
		</a>
	</li>

	<li
		class="nav-item <?php if($this->uri->uri_string() == 'hr/admin/incident-reports' || $this->uri->uri_string() == 'hr/admin/add-incident-report' || $this->uri->segment(3) == 'edit-incident-report') { echo 'active'; } ?>">
		<a class="nav-link" href="<?php echo base_url().'hr/admin/incident-reports'; ?>">
			<i class="fas fa-fw fa-file"></i>
			<span>Report Incident</span>
		</a>
	</li>

	<?php if($userdata['user_type_id'] == 4 && $userdata['department_id'] == 4) : ?>
		<li
			class="nav-item <?php if($this->uri->uri_string() == 'hr/admin/tasks') { echo 'active'; } ?>">
			<a class="nav-link" href="<?php echo base_url().'hr/admin/tasks'; ?>">
				<i class="fas fa-tasks fa-fw"></i>
				<span>Task List</span>
			</a>
		</li>
	<?php endif; ?>
	
	<?php if($userdata['user_type_id'] == 1 || $userdata['user_type_id'] == 2) : ?>
	<li class="nav-item <?php if($this->uri->uri_string() == 'hr/admin/ot-hours') { echo 'active'; } ?>">
		<a class="nav-link" href="<?php echo base_url().'hr/admin/ot-hours'; ?>">
			<i class="fas fa-clock"></i>
			<span>OT Hours</span>
		</a>
	</li>
	<?php endif; ?>

	<li
		class="nav-item <?php if($this->uri->uri_string() == 'hr/admin/users' || $this->uri->uri_string() == 'hr/admin/add-user' || $this->uri->segment(3) == 'edit-user' || $this->uri->uri_string() == 'hr/admin/admin-users' || $this->uri->uri_string() == 'hr/admin/add-admin-user' || $this->uri->segment(3) == 'edit-admin-user') { echo 'active'; } ?>">
		<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#users_menu" aria-expanded="true"
			aria-controls="users_menu">
			<i class="fas fa-fw fa-users"></i>
			<span>Users</span>
		</a>
		
		<div id="users_menu" class="collapse <?php if($this->uri->uri_string() == 'hr/admin/users' || $this->uri->uri_string() == 'hr/admin/add-user' || $this->uri->segment(3) == 'edit-user' || $this->uri->uri_string() == 'hr/admin/admin-users' || $this->uri->uri_string() == 'hr/admin/add-admin-user' || $this->uri->segment(3) == 'edit-admin-user') { echo 'show'; } ?>" aria-labelledby="users_menu" data-parent="#accordionSidebar">
			<div class="bg-white py-2 collapse-inner rounded">
				<a class="collapse-item <?php if($this->uri->uri_string() == 'hr/admin/users' || $this->uri->uri_string() == 'hr/admin/add-user' || $this->uri->segment(3) == 'edit-user') { echo 'active'; } ?>"
					href="<?php echo base_url().'hr/admin/users'; ?>">Employees</a>
				<?php if($userdata['user_type_id'] == 1 || $userdata['user_type_id'] == 2) { ?>
					<a class="collapse-item <?php if($this->uri->uri_string() == 'hr/admin/admin-users' || $this->uri->uri_string() == 'hr/admin/add-admin-user' || $this->uri->segment(3) == 'edit-admin-user') { echo 'active'; } ?>"
					href="<?php echo base_url().'hr/admin/admin-users'; ?>">Admin Users</a>
				<?php } ?>
			</div>
		</div>
	</li>
		
	<?php if($userdata['user_type_id'] == 1 || $userdata['user_type_id'] == 2) { ?>
		<li
			class="nav-item <?php if($this->uri->uri_string() == 'hr/admin/user-types' || $this->uri->uri_string() == 'hr/admin/add-user-type' || $this->uri->segment(3) == 'edit-user-type') { echo 'active'; } ?>">
			<a class="nav-link" href="<?php echo base_url().'hr/admin/user-types'; ?>">
				<i class="fas fa-fw fa-table"></i>
				<span>User Types</span>
			</a>
		</li>

		<li
			class="nav-item <?php if($this->uri->uri_string() == 'hr/admin/departments' || $this->uri->uri_string() == 'hr/admin/add-department' || $this->uri->segment(3) == 'edit-department') { echo 'active'; } ?>">
			<a class="nav-link" href="<?php echo base_url().'hr/admin/departments'; ?>">
				<i class="fas fa-fw fa-building"></i>
				<span>Departments</span>
			</a>
		</li>

		<li
			class="nav-item <?php if($this->uri->uri_string() == 'hr/admin/positions' || $this->uri->uri_string() == 'hr/admin/add-position' || $this->uri->segment(3) == 'edit-position') { echo 'active'; } ?>">
			<a class="nav-link" href="<?php echo base_url().'hr/admin/positions'; ?>">
				<i class="fas fa-fw fa-globe"></i>
				<span>Positions</span>
			</a>
		</li>

		<li
			class="nav-item <?php if(preg_match('/hr\/admin\/([a-z\-])*branch/',$this->uri->uri_string())) { echo 'active'; } ?>">
			<a class="nav-link" href="<?php echo base_url().'hr/admin/branches'; ?>">
				<i class="fas fa-fw fa-code-branch"></i>
				<span>Branches</span>
			</a>
		</li>

	<?php } ?>

	<li
		class="nav-item <?php if($this->uri->uri_string() == 'hr/admin/memos' || $this->uri->uri_string() == 'hr/admin/add-memo' || $this->uri->segment(3) == 'edit-memo' || $this->uri->uri_string() == 'hr/admin/memos-status') { echo 'active'; } ?>">
		<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#memos_menu" aria-expanded="true" aria-controls="memos_menu">
			<i class="fas fa-fw fa-sticky-note"></i>
			<span>Memos</span>
		</a>
		<div id="memos_menu" class="collapse <?php if($this->uri->uri_string() == 'hr/admin/memos' || $this->uri->uri_string() == 'hr/admin/add-memo' || $this->uri->segment(3) == 'edit-memo' || $this->uri->uri_string() == 'hr/admin/memos-status') { echo 'show'; } ?>" aria-labelledby="memos_menu" data-parent="#accordionSidebar">
			<div class="bg-white py-2 collapse-inner rounded">
				<a class="collapse-item <?php if($this->uri->uri_string() == 'hr/admin/memos' || $this->uri->uri_string() == 'hr/admin/add-memo' || $this->uri->segment(3) == 'edit-memo') { echo 'active'; } ?>"
					href="<?php echo base_url().'hr/admin/memos'; ?>">Memos List</a>
				<a class="collapse-item <?php if($this->uri->uri_string() == 'hr/admin/memos-status') { echo 'active'; } ?>"
					href="<?php echo base_url().'hr/admin/memos-status'; ?>">Memo Status</a>
			</div>
		</div>
	</li>
	
	<?php if($userdata['user_type_id'] == 1 || $userdata['user_type_id'] == 2) { ?>
		<li
			class="nav-item <?php if(preg_match('/hr\/admin\/([a-z\-])*task-/',$this->uri->uri_string())) { echo 'active'; } ?>" >
			<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#tasks_menu" aria-expanded="true"
				aria-controls="tasks_menu">
				<i class="fas fa-fw fa-check"></i>
				<span>Onboarding</span>
			</a>
			<div id="tasks_menu" class="collapse <?php if(preg_match('/hr\/admin\/([a-z\-])*task-/',$this->uri->uri_string())) { echo 'show'; }?>" aria-labelledby="tasks_menu" data-parent="#accordionSidebar">
				<div class="bg-white py-2 collapse-inner rounded">
					<a class="collapse-item <?php if(preg_match('/hr\/admin\/([a-z\-])*task-category/',$this->uri->uri_string())) { echo 'active'; } ?> ?>"
						href="<?php echo base_url().'hr/admin/task-category'; ?>">Task Category</a>
					<a class="collapse-item <?php if(preg_match('/hr\/admin\/([a-z\-])*task-list/',$this->uri->uri_string())) { echo 'active'; } ?>"
						href="<?php echo base_url().'hr/admin/task-list'; ?>">Task List</a>

				</div>
			</div>
		</li>
	<?php } ?>

	<li
		class="nav-item <?php if($this->uri->uri_string() == 'hr/admin/notifications') { echo 'active'; } ?>">
		<a class="nav-link" href="<?php echo base_url().'hr/admin/notifications'; ?>">
			<i class="fas fa-bell fa-fw"></i>
			<span>Notifications</span>
		</a>
	</li>
	
	<li
		class="nav-item <?php if(preg_match('/hr\/admin\/([a-z\-])*training*([a-z\-])/',$this->uri->uri_string())) { echo 'active'; } ?>">
		<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#training_menu" aria-expanded="true" aria-controls="training_menu">
			<i class="fas fa-fw fa-sticky-note"></i>
			<span>Trainings</span>
		</a>
		<div id="training_menu" class="collapse <?php if(preg_match('/hr\/admin\/([a-z\-])*training*([-\a-z])/',$this->uri->uri_string())) { echo 'show'; } ?>" aria-labelledby="memos_menu" data-parent="#accordionSidebar">
			<div class="bg-white py-2 collapse-inner rounded">
				<?php if($userdata['user_type_id'] == 1 || $userdata['user_type_id'] == 2) { ?>
					<a class="collapse-item <?php if($this->uri->uri_string() == 'hr/admin/training' || $this->uri->uri_string() == 'hr/admin/add-training' || $this->uri->segment(3) == 'edit-training') { echo 'active'; } ?>"
					href="<?php echo base_url().'hr/admin/training'; ?>">Training List</a>
				<?php } else { ?>
					<a class="collapse-item <?php if($this->uri->uri_string() == 'hr/admin/trainings-branch-manager') { echo 'active'; } ?>" 
					href="<?php echo base_url().'hr/admin/trainings-branch-manager'; ?>">Training List</a>
				<?php } ?>
				<a class="collapse-item <?php if($this->uri->uri_string() == 'hr/admin/training-status') { echo 'active'; } ?>"
					href="<?php echo base_url().'hr/admin/training-status'; ?>">Training Status</a>
			</div>
		</div>
	</li>

	<li
		class="nav-item <?php if($this->uri->uri_string() == 'hr/admin/escrow-instruction') { echo 'active'; } ?>">
		<a class="nav-link" href="<?php echo base_url().'hr/admin/escrow-instruction'; ?>">
			<i class="fas fa-bell fa-fw"></i>
			<span>Escrow instruction</span>
		</a>
	</li>

	<!-- Divider -->
	<hr class="sidebar-divider d-none d-md-block">

	<!-- Sidebar Toggler (Sidebar) -->
	<div class="text-center d-none d-md-inline">
		<button class="rounded-circle border-0" id="sidebarToggle"></button>
	</div>

</ul>
<!-- End of Sidebar -->
