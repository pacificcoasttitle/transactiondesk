<nav id="sidebar">
    <ul class="list-unstyled components">
        <li class="active">
            <a href="<?php echo site_url('welcome/admin_dashboard'); ?>">
                <i class="fa fa-tachometer" aria-hidden="true"></i>Dashboard
            </a>
        </li>
        <li>
            <a href="#importSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                <i class="fa fa-upload" aria-hidden="true"></i>
                Import
            </a>
            <ul class="collapse list-unstyled" id="importSubmenu">
                <li>
                    <a href="<?php echo site_url('admin/title_rates'); ?>">Title Rates</a>
                </li>
                <li>
                    <a href="<?php echo site_url('admin/escrow_resale'); ?>">Escrow Resale Rates</a>
                </li>
                <li>
                    <a href="<?php echo site_url('admin/escrow_refinance'); ?>">Escrow Refinance Rates</a>
                </li>
            </ul>
            <a href="<?php echo site_url('admin/fees'); ?>">
                <i class="fa fa-plus" aria-hidden="true"></i>
                Fees
            </a>
            <a href="<?php echo site_url('welcome/admin_logout'); ?>">
                <i class="fa fa-sign-out" aria-hidden="true"></i>
                Logout
            </a>
        </li>
    </ul>
</nav>

<a href="javascript:void(0);" id="sidebarCollapse" class="sidebar-toggle" data-toggle="offcanvas" role="button">
      <i class="fa fa-bars fa-3x" aria-hidden="true"></i>
</a> 