<?php $userdata = $this->session->userdata('hr_admin');?>
<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    <?php
$userdata = $this->session->userdata('user');
// echo "<pre>";
// print_r($userdata);die;
if ($userdata['is_sales_rep'] == 1) {
    $dashboardUrl = base_url() . 'sales-dashboard/' . $userdata['id'];
} else if ($userdata['is_title_officer'] == 1) {
    $dashboardUrl = base_url() . 'title-officer-dashboard';
} else if ($userdata['is_escrow_officer'] == 1) {
    $dashboardUrl = base_url() . 'escrow-dashboard';
} else if ($userdata['is_payoff_user'] == 1) {
    $dashboardUrl = base_url() . 'pay-off-dashboard';
} else if ($userdata['is_special_lender'] == 1) {
    $dashboardUrl = base_url() . 'special-lender-dashboard';
} else {
    $dashboardUrl = base_url() . 'dashboard';
}

?>

	<!-- Sidebar - Brand -->
	<a class="sidebar-brand d-flex align-items-center justify-content-center"
		href="<?php echo $dashboardUrl; ?>">
		<img style="width:200px;" src="<?php echo base_url(); ?>assets/backend/hr/img/logo2.png">
	</a>
	<hr class="sidebar-divider my-0">

	<?php if (($userdata['is_sales_rep'] == 1) && ($sidebar)) {?>

		<!-- Divider -->
		<li class="nav-item <?php if ($this->uri->segment(1) == 'sales-dashboard') {echo 'active';}?>">
			<a class="nav-link" href="<?php echo base_url(); ?>sales-dashboard/<?php echo $userdata['id']; ?>">
				<i class="fas fa fa-dashboard"></i>
				<span>Dashboard</span>
			</a>
		</li>
		<li class="nav-item <?php if ($this->uri->uri_string(1) == 'sales-current-month-history') {echo 'active';}?>">
			<a class="nav-link" href="<?php echo base_url(); ?>sales-current-month-history">
				<i class="fas fa fa-calendar  "></i>
				<span>Daily</span>
			</a>
		</li>
		<li class="nav-item <?php if ($this->uri->uri_string(1) == 'sales-reports') {echo 'active';}?>">
			<a class="nav-link" href="<?php echo base_url(); ?>sales-reports/<?php echo $userdata['id']; ?>">
				<i class="fas fa fa-file  "></i>
				<span>Report</span>
			</a>
		</li>
		<li class="nav-item <?php if ($this->uri->segment(1) == 'sales-production-history') {echo 'active';}?>">
			<a class="nav-link" href="<?php echo base_url(); ?>sales-production-history/<?php echo $userdata['id']; ?>">
				<i class="fas fa fa-history"></i>
				<span>Production History</span>
			</a>
		</li>
		<li class="nav-item <?php if ($this->uri->segment(1) == 'trends') {echo 'active';}?>">
			<a class="nav-link" href="<?php echo base_url(); ?>trends/<?php echo $userdata['id']; ?>">
				<i class="fa fa-line-chart"></i>
				<span>Trends</span>
			</a>
		</li>
		<li class="nav-item <?php if ($this->uri->segment(1) == 'sales-summary') {echo 'active';}?>">
			<a class="nav-link" href="<?php echo base_url(); ?>sales-summary/<?php echo $userdata['id']; ?>">
				<i class="fa fa-list-alt"></i>
				<span>Summary</span>
			</a>
		</li>
		<?php if (($userdata['is_sales_rep_manager'] == 1)) {?>
		<li class="nav-item <?php if ($this->uri->segment(1) == 'survey-result') {echo 'active';}?>">
			<a class="nav-link" href="<?php echo base_url(); ?>survey-result">
				<i class="fas fa-poll-h"></i>
				<span>Survey Result</span>
			</a>
		</li>
		<?php }?>
		<li class="nav-item <?php if ($this->uri->segment(1) == 'logout') {echo 'active';}?>">
			<a class="nav-link" href="<?php echo base_url() . 'logout'; ?>">
				<i class="fa fa-sign-out"></i>
				<span>Logout</span>
			</a>
		</li>

	<?php } else if (($userdata['is_escrow_officer'] == 1 || $userdata['is_payoff_user'] == 1 || $userdata['is_special_lender'] == 1) && ($sidebar)) {?>
		<li class="nav-item <?php if ($this->uri->segment(1) == 'escrow-dashboard' || $this->uri->segment(1) == 'pay-off-dashboard' || $this->uri->segment(1) == 'special-lender-dashboard') {echo 'active';}?>">
			<a class="nav-link" href="<?php echo $dashboardUrl; ?>">
				<i class="fas fa fa-dashboard"></i>
				<span>Dashboard Home</span>
			</a>
		</li>
		<?php if ($userdata['is_escrow_officer'] == 1 || $userdata['is_payoff_user'] == 1) {?>
		<li class="nav-item <?php if ($this->uri->segment(1) == 'pay-off-dashboard') {echo 'active';}?>">
			<a class="nav-link" href="<?php echo base_url(); ?>pay-off-dashboard">
				<i class="fas fa fa-dashboard"></i>
				<span>Payoff Home</span>
			</a>
		</li>
		<?php }?>
	<?php } else if ($sidebar) {?>
		<li class="nav-item <?php if ($this->uri->segment(1) == 'dashboard' || $this->uri->segment(1) == 'title-officer-dashboard') {echo 'active';}?>">
			<a class="nav-link" href="<?php echo $dashboardUrl; ?>">
				<i class="fas fa fa-dashboard"></i>
				<span>Dashboard Home</span>
			</a>
		</li>
		<li class="nav-item <?php if ($this->uri->uri_string(1) == 'order') {echo 'active';}?>">
			<a class="nav-link" href="<?php echo base_url(); ?>order">
				<i class="fas fa fa-calendar  "></i>
				<span>Open Order</span>
			</a>
		</li>
		<li class="nav-item <?php if ($this->uri->segment(1) == 'cpl-dashboard') {echo 'active';}?>">
			<a class="nav-link" href="<?php echo base_url(); ?>cpl-dashboard">
				<i class="fas fa-seedling"></i>
				<span>CPL Dashboard</span>
			</a>
		</li>
		<li class="nav-item <?php if ($this->uri->segment(1) == 'proposed-insured') {echo 'active';}?>">
			<a class="nav-link" href="<?php echo base_url(); ?>proposed-insured">
				<i class="fa fa-line-chart"></i>
				<span>Proposed Insured</span>
			</a>
		</li>
		<?php if ($userdata['is_master'] == 1) {?>
		<li class="nav-item <?php if ($this->uri->segment(1) == 'reports' || $this->uri->segment(1) == 'labels' || $this->uri->segment(1) == 'pmas' || $this->uri->segment(1) == 'sales-snap-shot' || $this->uri->segment(1) == 'sales-activity-report') {echo 'active';}?>">
			<a class="nav-link" href="<?php echo base_url(); ?>reports">
				<i class="fa fa-file"></i>
				<span>Reports</span>
			</a>
		</li>
		<li class="nav-item <?php if ($this->uri->segment(1) == 'prelim-files' || $this->uri->segment(1) == 'review-file') {echo 'active';}?>">
			<a class="nav-link" href="<?php echo base_url() . 'prelim-files'; ?>">
				<i class="fa fa-comments"></i>
				<span>Review Prelim</span>
			</a>
		</li>
		<li class="nav-item <?php if ($this->uri->segment(1) == 'fees' || $this->uri->segment(1) == 'get-fees') {echo 'active';}?>">
			<a class="nav-link" href="<?php echo base_url() . 'fees'; ?>">
				<i class="fa fa-money"></i>
				<span>Fee Estimate</span>
			</a>
		</li>
		<li class="nav-item <?php if ($this->uri->segment(1) == 'policy-orders' || $this->uri->segment(1) == 'policy-order') {echo 'active';}?>">
			<a class="nav-link" href="<?php echo base_url() . 'policy-orders'; ?>">
				<i class="fa fa-shield "></i>
				<span>Get Policy</span>
			</a>
		</li>
		<?php }?>
		<li class="nav-item <?php if ($this->uri->segment(1) == 'logout') {echo 'active';}?>">
			<a class="nav-link" href="<?php echo base_url() . 'logout'; ?>">
				<i class="fa fa-sign-out"></i>
				<span>Logout</span>
			</a>
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
