<?php $userdata = $this->session->userdata('hr_admin');?>
<!-- Sidebar -->
<div class="pc__sidebar">
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion toggled" id="accordionSidebar">
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
		<img style="" class="payOffLogo w-70" src="<?php echo base_url(); ?>assets/backend/hr/img/pct_payoff_dashboard_logo.png">
	</a>
	<hr class="sidebar-divider my-0">


		<li class="nav-item <?php if ($this->uri->segment(1) == 'pay-off-dashboard') {echo 'active';}?>">
			<a class="nav-link" href="<?php echo $dashboardUrl; ?>">
				<i class="fas fa fa-dashboard"></i>
				<span>Payoff Home</span>
			</a>
		</li>
        <hr class="sidebar-divider my-0">
		<li class="nav-item <?php if ($this->uri->uri_string(1) == 'add-transactee') {echo 'active';}?>">
			<a class="nav-link" href="<?php echo base_url(); ?>add-transactee">
				<i class="fas fa fa-dashboard"></i>
				<span>Add New Transactee</span>
			</a>
		</li>

	<!-- Divider -->
	<hr class="sidebar-divider d-none d-md-block">

	<!-- Sidebar Toggler (Sidebar) -->
	<div class="text-center d-none d-md-inline">
		<button class="rounded-circle border-0" id="sidebarToggle"></button>
	</div>

</ul>
</div>

<!-- End of Sidebar -->
