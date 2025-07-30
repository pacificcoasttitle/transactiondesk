<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title><?php echo $title; ?></title>
  
  <!-- Custom fonts for this template-->
  <link href="<?php echo base_url(); ?>assets/backend/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"/>

  <!-- Page level plugin CSS-->
  <link href="<?php echo base_url(); ?>assets/backend/vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="<?php echo base_url(); ?>assets/backend/css/sb-admin.css" rel="stylesheet">
  <link href="<?php echo base_url(); ?>assets/backend/css/custom.css" rel="stylesheet">
  <link href="<?php echo base_url(); ?>assets/backend/css/daterangepicker.css" rel="stylesheet">
  <link href="<?php echo base_url(); ?>assets/backend/css/jquery-ui.css" rel="stylesheet">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
  <script>
        var base_url = "<?php echo base_url(); ?>";
				document.cookie = "user_timezone="+Intl.DateTimeFormat().resolvedOptions().timeZone+";path=/";
  </script>
</head>

<body id="page-top">

  <nav class="navbar navbar-expand navbar-dark bg-dark static-top">

    <a class="navbar-brand mr-1" href="<?php echo site_url().'admin/dashboard'; ?>">
      <img src="http://pct.com/assets/media/general/logo2.png">
    </a>

    <button class="btn btn-link btn-sm text-white order-1 order-sm-0" id="sidebarToggle" href="#">
      <i class="fas fa-bars"></i>
    </button>

    <!-- Navbar -->
    <ul class="navbar-nav ml-auto">
      <li class="nav-item dropdown no-arrow">
        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-user-circle fa-fw"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
          <a class="dropdown-item" href="<?php echo base_url().'order/admin/logout'; ?>">Logout</a>
        </div>
      </li>
    </ul>

  </nav>

  <div id="wrapper">
  <!-- Sidebar -->
  <?php $this->load->view('order/layout/sidebar'); ?>

  <div id="content-wrapper">
