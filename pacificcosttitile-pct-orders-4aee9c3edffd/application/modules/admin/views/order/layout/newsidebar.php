<?php
$userdata = $this->session->userdata('admin');
$roleList = $this->common->getRoleList();
$role_id = isset($userdata['role_id']) ? $userdata['role_id'] : 0;
$roleName = $roleList[$role_id];
$settingLinks = $orderTabLinks = $usersTabLinks = $clientTabLinks = $logTabLinks = $documentTabLinks = $branchTabLinks = $commisionTabLinks = $payoffSectionLink = false;

if (
    $this->uri->uri_string() == 'order/admin/roles' ||
    $this->uri->uri_string() == 'order/admin/credentials-check' ||
    $this->uri->uri_string() == 'order/admin/resware-admin-credential' ||
    $this->uri->uri_string() == 'order/admin/send-password' ||
    $this->uri->uri_string() == 'order/admin/primary-check' ||
    $this->uri->uri_string() == 'order/admin/fees-types' ||
    $this->uri->uri_string() == 'order/admin/fees' ||
    $this->uri->uri_string() == 'order/admin/code-book' ||
    $this->uri->uri_string() == 'order/admin/rules-manager' ||
    $this->uri->uri_string() == 'order/admin/notifications' ||
    $this->uri->uri_string() == 'order/admin/holidays' ||
    $this->uri->uri_string() == 'order/admin/settings' ||
    $this->uri->uri_string() == 'order/admin/surveys' ||
    $this->uri->uri_string() == 'order/admin/manual-report' ||
    preg_match('/order\/admin\/([a-z\-])*lp-document-type*/', $this->uri->uri_string()) ||
    preg_match('/order\/admin\/([a-z\-])*lp-alert*/', $this->uri->uri_string()) ||
    preg_match('/order\/admin\/([a-z\-])*daily-email*/', $this->uri->uri_string())
) {
    $settingLinks = true;
}

if (
    $this->uri->uri_string() == 'order/admin/lv-log' ||
    $this->uri->uri_string() == 'order/admin/pre-listing' ||
    $this->uri->uri_string() == 'order/admin/grant-deed-log' ||
    $this->uri->uri_string() == 'order/admin/tax-data' ||
    $this->uri->uri_string() == 'order/admin/tax-log' ||
    $this->uri->uri_string() == 'order/admin/partner-api-log' ||
    $this->uri->uri_string() == 'order/admin/cpl-error-logs' ||
    $this->uri->uri_string() == 'order/admin/resware-logs' ||
    $this->uri->uri_string() == 'order/admin/lp-xml-logs' ||
    $this->uri->uri_string() == 'order/admin/ion-fraud' ||
    $this->uri->uri_string() == 'order/admin/admin-user-logs'
) {
    $logTabLinks = true;
}

if (
    $this->uri->uri_string() == 'order/admin/cpl-documents' ||
    $this->uri->uri_string() == 'order/admin/ion-fraud-documents' ||
    $this->uri->uri_string() == 'order/admin/grant-deed-documents' ||
    $this->uri->uri_string() == 'order/admin/lv-documents' ||
    $this->uri->uri_string() == 'order/admin/tax-documents' ||
    $this->uri->uri_string() == 'order/admin/curative-documents' ||
    $this->uri->uri_string() == 'order/admin/file-documents' ||
    $this->uri->uri_string() == 'order/admin/pre-listing-documents' ||
    $this->uri->uri_string() == 'order/admin/lp-listing-documents'
) {
    $documentTabLinks = true;
}

if (
    $this->uri->uri_string() == 'order/admin/doma-branches' ||
    $this->uri->uri_string() == 'order/admin/north-american-branches' ||
    $this->uri->uri_string() == 'order/admin/westcor-branches' ||
    $this->uri->uri_string() == 'order/admin/commonwealth-branches' ||
    $this->uri->uri_string() == 'order/admin/proposed-branches'
) {
    $branchTabLinks = true;
}

if (
    preg_match('/order\/admin\/([a-z\-])*underwriter-tier*/', $this->uri->uri_string()) ||
    preg_match('/order\/admin\/([a-z\-])*commission-range*/', $this->uri->uri_string()) ||
    preg_match('/order\/admin\/([a-z\-])*commission-file*/', $this->uri->uri_string()) ||
    preg_match('/order\/admin\/([a-z\-])*commission-config*/', $this->uri->uri_string()) ||
    preg_match('/order\/admin\/([a-z\-])*commission-bonus*/', $this->uri->uri_string())
) {
    $commisionTabLinks = true;
}

if ($this->uri->uri_string() == 'order/admin/transactees-list' ||
    $this->uri->uri_string() == 'order/admin/payoff-users' ||
    $this->uri->segment(3) == 'payoff-users' ||
    $this->uri->uri_string() == 'order/admin/add-payoff-user' ||
    $this->uri->uri_string(3) == 'order/admin/edit-payoff-user'
) {
    $payoffSectionLink = true;
}

if ($this->uri->uri_string() == 'order/admin/orders' || $this->uri->uri_string() == 'order/admin/lp-orders' || $this->uri->segment(3) == 'order-details') {
    $orderTabLinks = true;
}

if (
    $this->uri->uri_string() == 'order/admin/admin_users' ||
    $this->uri->uri_string() == 'order/admin/sales-rep' ||
    $this->uri->uri_string() == 'order/admin/add-sales-rep' ||
    $this->uri->segment(3) == 'edit-sales-rep' ||
    $this->uri->segment(3) == 'export-sales-rep-client' ||
	$this->uri->uri_string() == 'order/admin/title-officers' ||
    $this->uri->uri_string() == 'order/admin/add-title-officer' ||
    $this->uri->segment(3) == 'edit-title-officer' ||
    $this->uri->uri_string() == 'order/admin/master-users' ||
    $this->uri->uri_string() == 'order/admin/add-new-master-user' ||
    $this->uri->segment(3) == 'edit-master-user' ||
    $this->uri->uri_string() == 'order/admin/cpl-proposed-users' ||
    $this->uri->segment(3) == 'edit-cpl-proposed-user' ||
    $this->uri->uri_string() == 'order/admin/escrow-officers' ||
    $this->uri->segment(3) == 'edit-escrow-officer' ||
    $this->uri->segment(3) == 'add-escrow-officer'
) {
    $usersTabLinks = true;
}

if (
    $this->uri->uri_string() == 'order/admin/escrow' ||
    $this->uri->uri_string() == 'order/admin/agents' ||
    $this->uri->uri_string() == 'order/admin/import-agents' ||
    $this->uri->segment(3) == 'edit-agent' ||
    $this->uri->uri_string() == 'order/admin/lenders' ||
    $this->uri->uri_string() == 'order/admin/import-lenders' ||
    $this->uri->segment(3) == 'edit-lender' ||
    $this->uri->uri_string() == 'order/admin/mortgage-brokers' ||
    $this->uri->uri_string() == 'order/admin/companies' ||
    $this->uri->uri_string() == 'order/admin/add-company' ||
    $this->uri->uri_string() == 'order/admin/incorrect-users' ||
    $this->uri->uri_string() == 'order/admin/new-users' ||
    $this->uri->uri_string() == 'order/admin/add-new-user' ||
    $this->uri->uri_string() == 'order/admin/client-users-list'
) {
    $clientTabLinks = true;
}

?>

<?php if ($userdata['email_address'] == 'upwork@pct.com') {?>
	<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

		<!-- Sidebar - Brand -->
		<a class="sidebar-brand d-flex align-items-center justify-content-center"
			href="<?php echo base_url() . 'order/admin/dashboard'; ?>">
			<img style="width:200px;" src="<?php echo base_url(); ?>assets/backend/hr/img/logo2.png">
		</a>

		<!-- Divider -->
		<hr class="sidebar-divider my-0">

		<li class="nav-item <?php if ($settingLinks) {echo 'active';}?>">
			<a class="nav-link <?php if (!$settingLinks) {echo 'collapsed';}?>" href="#" id="li_settings" role="button" data-toggle="collapse" aria-haspopup="true" data-target="#li_settings_list" aria-expanded="false">
				<i class="fas fa-fw fa-gear"></i>
				<span>Settings</span>
			</a>
			<div class="collapse <?php if ($settingLinks) {echo 'show';}?> " aria-labelledby="li_settings" id="li_settings_list">
				<div class="bg-white py-2 collapse-inner rounded">
					<a class="collapse-item <?php if ($this->uri->uri_string() == 'order/admin/credentials-check') {echo 'active';}?>" href="<?php echo base_url() . 'order/admin/credentials-check'; ?>">
						Credentials Check
					</a>
				</div>
			</div>
		</li>

		<!-- Divider -->
		<hr class="sidebar-divider d-none d-md-block">

		<!-- Sidebar Toggler (Sidebar) -->
		<div class="text-center d-none d-md-inline">
			<button class="rounded-circle border-0" id="sidebarToggle"></button>
		</div>
	</ul>
	<!-- End of Sidebar -->

<?php } else {?>
	<!-- Sidebar -->
	<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

		<!-- Sidebar - Brand -->
		<a class="sidebar-brand d-flex align-items-center justify-content-center"
			href="<?php echo base_url() . 'order/admin/dashboard'; ?>">
			<img style="width:200px;" src="<?php echo base_url(); ?>assets/backend/hr/img/logo2.png">
		</a>

		<!-- Divider -->
		<hr class="sidebar-divider my-0">
		<?php if ($role_id != 3 && $role_id != 5): ?>
			<li class="nav-item <?php if ($this->uri->uri_string() == 'order/admin/dashboard' ||
    $this->uri->segment(3) == 'order-details') {echo 'active';}?>">
				<a class="nav-link" href="<?php echo base_url() . 'order/admin/dashboard'; ?>">
					<i class="fas fa fa-dashboard"></i>
					<span>Dashboard</span>
				</a>
			</li>
		<?php endif;?>

		<?php if ($role_id != 5): ?>

			<li class="nav-item <?php if ($orderTabLinks) {echo 'active';}?>">
				<a class="nav-link <?php if (!$orderTabLinks) {echo 'collapsed';}?>" href="#" id="ordersDropdown" role="button" data-toggle="collapse" data-target="#ordersDropdown_list" aria-haspopup="true" aria-expanded="false">
					<i class="fas fa-fw fa-list"></i>
					<span>Orders</span>
				</a>
				<div class="collapse <?php if ($orderTabLinks) {echo 'show';}?>" aria-labelledby="ordersDropdown" id="ordersDropdown_list">
					<div class="bg-white py-2 collapse-inner rounded">
						<a class="collapse-item <?php if ($this->uri->uri_string() == 'order/admin/orders' ||
    $this->uri->segment(3) == 'order-details' || $this->uri->segment(4) == 'loan' || $this->uri->segment(4) == 'sale') {echo 'active';}?>" href="<?php echo base_url() . 'order/admin/orders'; ?>">
							Orders
						</a>
						<?php if ($role_id != 3): ?>
						<a class="collapse-item <?php if ($this->uri->uri_string() == 'order/admin/lp-orders') {echo 'active';}?>" href="<?php echo base_url() . 'order/admin/lp-orders'; ?>">
							LP Orders
						</a>
						<?php endif;?>
					</div>
				</div>
			</li>
		<?php endif;?>

		<?php if ($role_id != 3 && $role_id != 5): ?>
			<li class="nav-item <?php if ($clientTabLinks) {echo 'active';}?>">
				<a class="nav-link <?php if (!$clientTabLinks) {echo 'collapsed';}?>" href="#" id="clientsDropdown" role="button" data-toggle="collapse" data-target="#clients" aria-haspopup="true" aria-expanded="false">
					<i class="fas fa-fw fa-users"></i>
					<span>Clients</span>
				</a>
				<div class="collapse <?php if ($clientTabLinks) {echo 'show';}?>" aria-labelledby="clientsDropdown" id="clients">
					<div class="bg-white py-2 collapse-inner rounded">
						<?php if ($role_id == 1): ?>
						<?php endif;?>
						<a class="collapse-item <?php if ($this->uri->uri_string() == 'order/admin/agents' || $this->uri->uri_string() == 'order/admin/import-agents' || $this->uri->segment(3) == 'edit-agent') {echo 'active';}?>" href="<?php echo base_url() . 'order/admin/agents'; ?>">Agents</a>
						<a class="collapse-item <?php if ($this->uri->uri_string() == 'order/admin/escrow' || $this->uri->uri_string() == 'order/admin/import') {echo 'active';}?>" href="<?php echo base_url() . 'order/admin/escrow'; ?>">Escrow</a>
						<a class="collapse-item <?php if ($this->uri->uri_string() == 'order/admin/lenders' || $this->uri->uri_string() == 'order/admin/import-lenders' || $this->uri->segment(3) == 'edit-lender') {echo 'active';}?>" href="<?php echo base_url() . 'order/admin/lenders'; ?>">Lenders</a>
						<a class="collapse-item <?php if ($this->uri->uri_string() == 'order/admin/mortgage-brokers') {echo 'active';}?>" href="<?php echo base_url() . 'order/admin/mortgage-brokers'; ?>">Mortgage Brokers</a>
						<a class="collapse-item <?php if ($this->uri->uri_string() == 'order/admin/companies' || $this->uri->uri_string() == 'order/admin/add-company') {echo 'active';}?>" href="<?php echo base_url() . 'order/admin/companies'; ?>">Companies</a>
						<a class="collapse-item <?php if ($this->uri->uri_string() == 'order/admin/incorrect-users') {echo 'active';}?>" href="<?php echo base_url() . 'order/admin/incorrect-users'; ?>">Incorrect Users</a>
						<a class="collapse-item <?php if ($this->uri->uri_string() == 'order/admin/client-users-list') {echo 'active';}?>" href="<?php echo base_url() . 'order/admin/client-users-list'; ?>">Edit Client Type</a>
						<a class="collapse-item <?php if ($this->uri->uri_string() == 'order/admin/new-users' || $this->uri->uri_string() == 'order/admin/add-new-user') {echo 'active';}?>" href="<?php echo base_url() . 'order/admin/new-users'; ?>">New Clients</a>


					</div>
				</div>
			</li>

			<li class="nav-item <?php if ($usersTabLinks) {echo 'active';}?>">
				<a class="nav-link <?php if (!$usersTabLinks) {echo 'collapsed';}?>" href="#" id="usersDropdown" role="button" data-toggle="collapse" data-target="#users" aria-haspopup="true" aria-expanded="false">
					<i class="fas fa-fw fa-users"></i>
					<span>PCT Users</span>
				</a>
				<div class="collapse <?php if ($usersTabLinks) {echo 'show';}?>" aria-labelledby="usersDropdown" id="users">
					<div class="bg-white py-2 collapse-inner rounded">
						<?php if ($role_id == 1): ?>
							<a class="collapse-item <?php if ($this->uri->uri_string() == 'order/admin/admin_users') {echo 'active';}?>" href="<?php echo base_url() . 'order/admin/admin_users'; ?>">Admin</a>
							<?php endif;?>
							<?php if (!in_array($roleName, ['CS Admin'])): ?>
								<a class="collapse-item <?php if ($this->uri->uri_string() == 'order/admin/sales-rep' || $this->uri->uri_string() == 'order/admin/add-sales-rep' || $this->uri->segment(3) == 'edit-sales-rep' || $this->uri->segment(3) == 'export-sales-rep-client') {echo 'active';}?>" href="<?php echo base_url() . 'order/admin/sales-rep'; ?>">Sales Rep.</a>
							<?php endif;?>

							<a class="collapse-item <?php if ($this->uri->uri_string() == 'order/admin/title-officers' || $this->uri->uri_string() == 'order/admin/add-title-officer' || $this->uri->segment(3) == 'edit-title-officer') {echo 'active';}?>" href="<?php echo base_url() . 'order/admin/title-officers'; ?>">Title Officer</a>
							<a class="collapse-item <?php if ($this->uri->uri_string() == 'order/admin/master-users' || $this->uri->uri_string() == 'order/admin/add-new-master-user' || $this->uri->segment(3) == 'edit-master-user') {echo 'active';}?>" href="<?php echo base_url() . 'order/admin/master-users'; ?>">Master Users</a>
							<a class="collapse-item <?php if ($this->uri->uri_string() == 'order/admin/cpl-proposed-users' || $this->uri->segment(3) == 'edit-cpl-proposed-user') {echo 'active';}?>" href="<?php echo base_url() . 'order/admin/cpl-proposed-users'; ?>">CPL/Proposed Users</a>
							<a class="collapse-item <?php if ($this->uri->uri_string() == 'order/admin/escrow-officers' || $this->uri->segment(3) == 'edit-escrow-officer' || $this->uri->segment(3) == 'add-escrow-officer') {echo 'active';}?>" href="<?php echo base_url() . 'order/admin/escrow-officers'; ?>">Escrow Officers</a>
					</div>
				</div>
			</li>

			<li class="nav-item <?php if ($logTabLinks) {echo 'active';}?>">
				<a class="nav-link <?php if (!$logTabLinks) {echo 'collapsed';}?>" href="#" id="logsDropDown" role="button" data-toggle="collapse" data-target="#logs" aria-haspopup="true" aria-expanded="false">
					<i class="fas fa-fw fa-book"></i>
					<span>Logs</span>
				</a>
				<div class="collapse <?php if ($logTabLinks) {echo 'show';}?>" aria-labelledby="logsDropDown" id="logs">
					<div class="bg-white py-2 collapse-inner rounded">
						<a class="collapse-item <?php if ($this->uri->uri_string() == 'order/admin/lv-log') {echo 'active';}?>" href="<?php echo base_url() . 'order/admin/lv-log'; ?>">Legal Vesting</a>
						<a class="collapse-item <?php if ($this->uri->uri_string() == 'order/admin/pre-listing') {echo 'active';}?>" href="<?php echo base_url() . 'order/admin/pre-listing'; ?>">Pre Listing</a>
						<a class="collapse-item <?php if ($this->uri->uri_string() == 'order/admin/grant-deed-log') {echo 'active';}?>" href="<?php echo base_url() . 'order/admin/grant-deed-log'; ?>">Grant Deed</a>
						<a class="collapse-item <?php if ($this->uri->uri_string() == 'order/admin/tax-data') {echo 'active';}?>" href="<?php echo base_url() . 'order/admin/tax-data'; ?>">Tax Data</a>
						<a class="collapse-item <?php if ($this->uri->uri_string() == 'order/admin/tax-log') {echo 'active';}?>" href="<?php echo base_url() . 'order/admin/tax-log'; ?>">Tax Document</a>
						<a class="collapse-item <?php if ($this->uri->uri_string() == 'order/admin/partner-api-log') {echo 'active';}?>" href="<?php echo base_url() . 'order/admin/partner-api-log'; ?>">Partner Api</a>
						<a class="collapse-item <?php if ($this->uri->uri_string() == 'order/admin/cpl-error-logs') {echo 'active';}?>" href="<?php echo base_url() . 'order/admin/cpl-error-logs'; ?>">CPL Error</a>
						<a class="collapse-item <?php if ($this->uri->uri_string() == 'order/admin/resware-logs') {echo 'active';}?>" href="<?php echo base_url() . 'order/admin/resware-logs'; ?>">ResWare Log</a>
						<a class="collapse-item <?php if ($this->uri->uri_string() == 'order/admin/lp-xml-logs') {echo 'active';}?>" href="<?php echo base_url() . 'order/admin/lp-xml-logs'; ?>">LP Xml</a>
						<a class="collapse-item <?php if ($this->uri->uri_string() == 'order/admin/ion-fraud') {echo 'active';}?>" href="<?php echo base_url() . 'order/admin/ion-fraud'; ?>">ION Fraud</a>
						<?php if ($role_id == 1): ?>
							<a class="collapse-item <?php if ($this->uri->uri_string() == 'order/admin/admin-user-logs') {echo 'active';}?>" href="<?php echo base_url() . 'order/admin/admin-user-logs'; ?>">Admin Activity</a>
						<?php endif;?>
					</div>
				</div>
			</li>

			<li class="nav-item <?php if ($documentTabLinks) {echo 'active';}?>">
				<a class="nav-link <?php if (!$documentTabLinks) {echo 'collapsed';}?>" href="#" id="documentDropDown" role="button" data-toggle="collapse" data-target="#documents" aria-haspopup="true" aria-expanded="false">
					<i class="fas fa-fw fa-file"></i>
					<span>Documents</span>
				</a>
				<div class="collapse <?php if ($documentTabLinks) {echo 'show';}?>" aria-labelledby="documentDropDown" id="documents">
					<div class="bg-white py-2 collapse-inner rounded">
						<a class="collapse-item <?php if ($this->uri->uri_string() == 'order/admin/cpl-documents') {echo 'active';}?>" href="<?php echo base_url() . 'order/admin/cpl-documents'; ?>">CPL</a>
						<a class="collapse-item <?php if ($this->uri->uri_string() == 'order/admin/ion-fraud-documents') {echo 'active';}?>" href="<?php echo base_url() . 'order/admin/ion-fraud-documents'; ?>">ION Fraud</a>
						<a class="collapse-item <?php if ($this->uri->uri_string() == 'order/admin/grant-deed-documents') {echo 'active';}?>" href="<?php echo base_url() . 'order/admin/grant-deed-documents'; ?>">Grant Deed</a>
						<a class="collapse-item <?php if ($this->uri->uri_string() == 'order/admin/lv-documents') {echo 'active';}?>" href="<?php echo base_url() . 'order/admin/lv-documents'; ?>">Legal & Vesting</a>
						<a class="collapse-item <?php if ($this->uri->uri_string() == 'order/admin/tax-documents') {echo 'active';}?>" href="<?php echo base_url() . 'order/admin/tax-documents'; ?>">Tax</a>
						<a class="collapse-item <?php if ($this->uri->uri_string() == 'order/admin/curative-documents') {echo 'active';}?>" href="<?php echo base_url() . 'order/admin/curative-documents'; ?>">Curative</a>
						<a class="collapse-item <?php if ($this->uri->uri_string() == 'order/admin/file-documents') {echo 'active';}?>" href="<?php echo base_url() . 'order/admin/file-documents'; ?>">Forms</a>
						<!-- <a class="collapse-item <?php if ($this->uri->uri_string() == 'order/admin/pre-listing-documents') {echo 'active';}?>" href="<?php echo base_url() . 'order/admin/pre-listing-documents'; ?>">Pre Listing</a>
						<a class="collapse-item <?php if ($this->uri->uri_string() == 'order/admin/lp-listing-documents') {echo 'active';}?>" href="<?php echo base_url() . 'order/admin/lp-listing-documents'; ?>">LP Listing</a> -->
					</div>
				</div>
			</li>

			<li class="nav-item <?php if ($branchTabLinks) {echo 'active';}?>">
				<a class="nav-link <?php if (!$branchTabLinks) {echo 'collapsed';}?>" href="#" id="cpl_branches" role="button" data-toggle="collapse" data-target="#cpl_branches_section" aria-haspopup="true" aria-expanded="false">
					<i class="fas fa-fw fa-sitemap"></i>
					<span>Branches</span>
				</a>
				<div class="collapse <?php if ($branchTabLinks) {echo 'show';}?>" aria-labelledby="cpl_branches" id="cpl_branches_section">
					<div class="bg-white py-2 collapse-inner rounded">
						<a class="collapse-item <?php if ($this->uri->uri_string() == 'order/admin/north-american-branches') {echo 'active';}?>" href="<?php echo base_url() . 'order/admin/north-american-branches'; ?>">CPL - North American</a>
						<!-- <a class="collapse-item <?php if ($this->uri->uri_string() == 'order/admin/north-american-branches') {echo 'active';}?>" href="<?php echo base_url() . 'order/admin/north-american-branches'; ?>">CPL - North American</a> -->
						<a class="collapse-item <?php if ($this->uri->uri_string() == 'order/admin/westcor-branches') {echo 'active';}?>" href="<?php echo base_url() . 'order/admin/westcor-branches'; ?>">CPL - Westcor</a>
						<a class="collapse-item <?php if ($this->uri->uri_string() == 'order/admin/commonwealth-branches') {echo 'active';}?>" href="<?php echo base_url() . 'order/admin/commonwealth-branches'; ?>">CPL - Commonwealth</a>
						<a class="collapse-item <?php if ($this->uri->uri_string() == 'order/admin/proposed-branches') {echo 'active';}?>" href="<?php echo base_url() . 'order/admin/proposed-branches'; ?>">Proposed Insured</a>
					</div>
				</div>
			</li>

			<li class="nav-item <?php if ($settingLinks) {echo 'active';}?>">
				<a class="nav-link <?php if (!$settingLinks) {echo 'collapsed';}?>" href="#" id="li_settings" role="button" data-toggle="collapse" aria-haspopup="true" data-target="#li_settings_list" aria-expanded="false">
					<i class="fas fa-fw fa-gear"></i>
					<span>Settings</span>
				</a>
				<div class="collapse <?php if ($settingLinks) {echo 'show';}?>" aria-labelledby="li_settings" id="li_settings_list">
					<div class="bg-white py-2 collapse-inner rounded">
						<?php if ($role_id == 1): ?>
							<a class="collapse-item <?php if ($this->uri->uri_string() == 'order/admin/roles') {echo 'active';}?>" href="<?php echo base_url() . 'order/admin/roles'; ?>">
								User Roles
							</a>
						<?php endif;?>

						<a class="collapse-item <?php if ($this->uri->uri_string() == 'order/admin/credentials-check') {echo 'active';}?>" href="<?php echo base_url() . 'order/admin/credentials-check'; ?>">
							Credentials Check
						</a>

						<?php if (!in_array($roleName, ['CS Admin'])): ?>

							<a class="collapse-item <?php if ($this->uri->uri_string() == 'order/admin/resware-admin-credential') {echo 'active';}?>" href="<?php echo base_url() . 'order/admin/resware-admin-credential'; ?>">
								Resware Admin
							</a>

							<a class="collapse-item <?php if ($this->uri->uri_string() == 'order/admin/send-password') {echo 'active';}?>" href="<?php echo base_url() . 'order/admin/send-password'; ?>">
								Send Password
							</a>

						<?php endif;?>

						<a class="collapse-item <?php if ($this->uri->uri_string() == 'order/admin/primary-check') {echo 'active';}?>" href="<?php echo base_url() . 'order/admin/primary-check'; ?>">
							Primary Accounts
						</a>

						<?php if (!in_array($roleName, ['CS Admin'])): ?>

							<a class="collapse-item <?php if ($this->uri->uri_string() == 'order/admin/fees-types' || $this->uri->segment(3) == 'add-fee-type' || $this->uri->segment(3) == 'edit-fee-type') {echo 'active';}?>" href="<?php echo base_url() . 'order/admin/fees-types'; ?>">
								Fees Types
							</a>

							<a class="collapse-item <?php if ($this->uri->uri_string() == 'order/admin/fees' || $this->uri->segment(3) == 'add-fee' || $this->uri->segment(3) == 'edit-fee') {echo 'active';}?>" href="<?php echo base_url() . 'order/admin/fees'; ?>">
								Fees
							</a>

							<a class="collapse-item <?php if ($this->uri->uri_string() == 'order/admin/code-book') {echo 'active';}?>" href="<?php echo base_url() . 'order/admin/code-book'; ?>">
								Code Book
							</a>

							<a class="collapse-item <?php if ($this->uri->uri_string() == 'order/admin/rules-manager') {echo 'active';}?>" href="<?php echo base_url() . 'order/admin/rules-manager'; ?>">
								Rules Manager
							</a>

							<a class="collapse-item <?php if ($this->uri->uri_string() == 'order/admin/notifications') {echo 'active';}?>" href="<?php echo base_url() . 'order/admin/notifications'; ?>">
								Notifications
							</a>

							<a class="collapse-item <?php if ($this->uri->uri_string() == 'order/admin/holidays' || $this->uri->segment(3) == 'add-holiday' || $this->uri->segment(3) == 'edit-holiday') {echo 'active';}?>" href="<?php echo base_url() . 'order/admin/holidays'; ?>">
								Holidays
							</a>

							<a class="collapse-item <?php if (preg_match('/order\/admin\/([a-z\-])*lp-document-type*/', $this->uri->uri_string())) {echo 'active';}?>" href="<?php echo base_url() . 'order/admin/lp-document-types'; ?>">
								LP Document Types
							</a>

							<a class="collapse-item <?php if (preg_match('/order\/admin\/([a-z\-])*lp-alert*/', $this->uri->uri_string())) {echo 'active';}?>" href="<?php echo base_url() . 'order/admin/lp-alert'; ?>">
								LP Alert
							</a>

							<a class="collapse-item <?php if ($this->uri->uri_string() == 'order/admin/daily-email-control' || $this->uri->segment(3) == 'add-daily-emailer' || $this->uri->segment(3) == 'edit-daily-emailer') {echo 'active';}?>" href="<?php echo base_url() . 'order/admin/daily-email-control'; ?>">
								Daily Email Control
							</a>

							<a class="collapse-item <?php if ($this->uri->uri_string() == 'order/admin/settings') {echo 'active';}?>" href="<?php echo base_url() . 'order/admin/settings'; ?>">
								Settings
							</a>

							<a class="collapse-item <?php if (preg_match('/order\/admin\/([a-z\-])*manual-report*/', $this->uri->uri_string())) {echo 'active';}?>" href="<?php echo base_url() . 'order/admin/manual-report'; ?>">
								Manual Report
							</a>
							<a class="collapse-item <?php if ($this->uri->uri_string() == 'order/admin/surveys') {echo 'active';}?>" href="<?php echo base_url() . 'order/admin/surveys'; ?>">
								Surveys
							</a>
						<?php endif;?>
					</div>
				</div>
			</li>
			<?php if ($role_id == 1): ?>
			<li class="nav-item <?php if ($commisionTabLinks) {echo 'active';}?>">
				<a class="nav-link <?php if (!$commisionTabLinks) {echo 'collapsed';}?>" href="#" id="li_commissions" role="button" data-toggle="collapse" data-target="#li_commissons_list"  aria-haspopup="true" aria-expanded="false">
					<i class="fas fa-fw fa-gear"></i>
					<span>Commissions</span>
				</a>
				<div class="collapse <?php if ($commisionTabLinks) {echo 'show';}?>" aria-labelledby="li_commissions" id="li_commissons_list">
					<div class="bg-white py-2 collapse-inner rounded">
						<a class="collapse-item <?php if (preg_match('/order\/admin\/([a-z\-])*underwriter-tier*/', $this->uri->uri_string())) {echo 'active';}?>" href="<?php echo base_url() . 'order/admin/underwriter-tier'; ?>">
							Underwriter Tier
						</a>
						<a class="collapse-item <?php if (preg_match('/order\/admin\/([a-z\-])*commission-range*/', $this->uri->uri_string())) {echo 'active';}?>" href="<?php echo base_url() . 'order/admin/commission-range'; ?>">
							Commission Range
						</a>
						<a class="collapse-item <?php if (preg_match('/order\/admin\/([a-z\-])*commission-file*/', $this->uri->uri_string())) {echo 'active';}?>" href="<?php echo base_url() . 'order/admin/commission-files'; ?>">
							Commission Files
						</a>
						<a class="collapse-item <?php if (preg_match('/order\/admin\/([a-z\-])*commission-config*/', $this->uri->uri_string())) {echo 'active';}?>" href="<?php echo base_url() . 'order/admin/commission-config'; ?>">
							Escrow Commisison
						</a>
						<a class="collapse-item <?php if (preg_match('/order\/admin\/([a-z\-])*commission-bonus*/', $this->uri->uri_string())) {echo 'active';}?>" href="<?php echo base_url() . 'order/admin/commission-bonus'; ?>">
							Bonus
						</a>
					</div>
				</div>
			</li>
			<?php endif;?>
		<?php endif;?>

		<?php if ($role_id == 1 || $role_id == 5) {?>
			<li class="nav-item <?php if ($payoffSectionLink) {echo 'active';}?>">
				<a class="nav-link <?php if (!$payoffSectionLink) {echo 'collapsed';}?>" href="#" id="li_transactees" role="button" data-toggle="collapse" data-target="#li_transactees_list"  aria-haspopup="true" aria-expanded="false">
					<i class="fas fa-fw fa-gear"></i>
					<span>Payoffs</span>
				</a>
				<div class="collapse <?php if ($payoffSectionLink) {echo 'show';}?>" aria-labelledby="li_transactees" id="li_transactees_list">
					<div class="bg-white py-2 collapse-inner rounded">
						<a class="collapse-item <?php if ($this->uri->uri_string() == 'order/admin/payoff-users' || $this->uri->segment(3) == 'payoff-users' || $this->uri->uri_string() == 'order/admin/add-payoff-user' || $this->uri->uri_string(3) == 'order/admin/edit-payoff-user') {echo 'active';}?>" href="<?php echo base_url() . 'order/admin/payoff-users'; ?>">Payoff Team</a>

						<a class="collapse-item <?php if ($this->uri->uri_string() == 'order/admin/transactees-list') {echo 'active';}?>" href="<?php echo base_url() . 'order/admin/transactees-list'; ?>">
							Transactee List
						</a>

					</div>
				</div>
			</li>

		<?php }?>


		<!-- Divider -->
		<hr class="sidebar-divider d-none d-md-block">

		<!-- Sidebar Toggler (Sidebar) -->
		<div class="text-center d-none d-md-inline">
			<button class="rounded-circle border-0" id="sidebarToggle"></button>
		</div>

	</ul>
	<!-- End of Sidebar -->
<?php }?>