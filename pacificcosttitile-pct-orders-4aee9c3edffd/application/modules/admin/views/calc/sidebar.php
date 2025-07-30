<ul class="sidebar navbar-nav">
      <li class="nav-item <?php if($this->uri->uri_string() == 'calculator/admin_dashboard') { echo 'active'; } ?>">
        <a class="nav-link" href="<?php echo site_url('calculator/admin_dashboard'); ?>">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Dashboard</span>
        </a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-fw fa-folder"></i>
          <span>Rates</span>
        </a>
        <div class="dropdown-menu" aria-labelledby="pagesDropdown" id="rates-sub-menu">

          <a class="dropdown-item <?php if($this->uri->uri_string() == 'calculator/admin/title_rates' || $this->uri->uri_string() == 'calculator/admin/import_title_rates' || $this->uri->segment(3) == 'edit_title_rates') { echo 'active'; } ?>" href="<?php echo site_url('calculator/admin/title_rates'); ?>">Title Rates</a>

          <a class="dropdown-item <?php if($this->uri->uri_string() == 'calculator/admin/resale_rates' || $this->uri->uri_string() == 'calculator/admin/add_resale_rates' || $this->uri->segment(3) == 'edit_resale_rates') { echo 'active'; } ?>" href="<?php echo site_url('calculator/admin/resale_rates'); ?>">Resale</a>
          
          <a class="dropdown-item <?php if($this->uri->uri_string() == 'calculator/admin/refinance_rates' || $this->uri->uri_string() == 'calculator/admin/add_refinance_rates' || $this->uri->segment(3) == 'edit_refinance_rates') { echo 'active'; } ?>" href="<?php echo site_url('calculator/admin/refinance_rates'); ?>">Refinance</a>
        </div>
      </li>
      <li class="nav-item <?php if($this->uri->uri_string() == 'calculator/admin/fees' || $this->uri->uri_string() == 'calculator/admin/add_fees' || $this->uri->segment(3) == 'edit_fees') { echo 'active'; } ?>">
        <a class="nav-link" href="<?php echo site_url('calculator/admin/fees'); ?>">
          <i class="fas fa-fw fa-chart-area"></i>
          <span>Fees</span></a>
      </li>
    </ul>