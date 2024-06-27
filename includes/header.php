<!DOCTYPE html>
<html lang="en" data-layout="vertical" data-sidebar="dark" data-sidebar-size="sm-hover-active" data-preloader="disable" data-bs-theme="light">

<head>
  <meta charset="utf-8" />
  <title>Admin | Tender18</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta content="Tender18" name="author" />
  <!-- App favicon -->
  <!-- <link rel="shortcut icon" href="<?php echo ADMIN_URL; ?>assets/images/favicon.ico" /> -->
  <link rel="shortcut icon" href="<?php echo ADMIN_URL; ?>assets/images/tender18-favicon.webp" />

  <!-- Layout config Js -->
  <!--datatable css-->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables.net-bs5/1.13.6/dataTables.bootstrap5.min.css" />
  <!--datatable responsive css-->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables.net-responsive-bs/2.2.9/responsive.bootstrap.css" />

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables.net-buttons-dt/2.2.2/buttons.dataTables.min.css" />
  <script src="<?php echo ADMIN_URL; ?>assets/js/layout.js"></script>
  <!-- Bootstrap Css -->
  <link href="<?php echo ADMIN_URL; ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
  <!-- Icons Css -->
  <link href="<?php echo ADMIN_URL; ?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />
  <!-- App Css-->
  <link href="<?php echo ADMIN_URL; ?>assets/css/app.min.css" rel="stylesheet" type="text/css" />
  <!-- custom Css-->
  <link href="<?php echo ADMIN_URL; ?>assets/css/custom.min.css" rel="stylesheet" type="text/css" />
  <link href="<?php echo ADMIN_URL; ?>assets/css/style.css" rel="stylesheet" type="text/css" />
</head>

<body class="dashboard-topbar-wrapper">
  <input type="hidden" value="<?php echo ADMIN_URL; ?>" id="admin_url">
  <!-- Begin page -->
  <div id="layout-wrapper">
    <?php include 'sidebar.php' ?>
    <!-- Vertical Overlay-->
    <div class="vertical-overlay"></div>
    <header id="page-topbar">
      <div class="layout-width">
        <div class="navbar-header">
          <div class="d-flex">
            <!-- LOGO -->
            <div class="navbar-brand-box horizontal-logo">
              <a href="<?php echo ADMIN_URL; ?>index.php" class="logo logo-dark">
                <span class="logo-sm">
                  <img src="<?php echo ADMIN_URL; ?>assets/images/tender18-logo-small.webp" alt="" height="22" />
                </span>
                <span class="logo-lg">
                  <img src="<?php echo ADMIN_URL; ?>assets/images/tender18-logo.webp" alt="" height="22" />
                </span>
              </a>

              <a href="<?php echo ADMIN_URL; ?>index.php" class="logo logo-light">
                <span class="logo-sm">
                  <img src="<?php echo ADMIN_URL; ?>assets/images/tender18-logo-small.webp" alt="" height="22" />
                </span>
                <span class="logo-lg">
                  <img src="<?php echo ADMIN_URL; ?>assets/images/tender18-logo.webp" alt="" height="22" />
                </span>
              </a>
            </div>

            <button type="button" class="btn btn-sm px-3 fs-16 header-item vertical-menu-btn topnav-hamburger shadow-none" id="topnav-hamburger-icon">
              <span class="hamburger-icon">
                <span></span>
                <span></span>
                <span></span>
              </span>
            </button>

          </div>

          <div class="d-flex align-items-center">
            <div class="ms-1 header-item d-none d-sm-flex">
              <button type="button" class="btn btn-icon btn-topbar btn-ghost-light rounded-circle user-name-text" data-toggle="fullscreen">
                <i class="ti ti-arrows-maximize fs-3xl"></i>
              </button>
            </div>

            <div class="dropdown topbar-head-dropdown ms-1 header-item">
              <button type="button" class="btn btn-icon btn-topbar btn-ghost-light rounded-circle user-name-text mode-layout" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="ti ti-sun align-middle fs-3xl"></i>
              </button>
              <div class="dropdown-menu p-2 dropdown-menu-end" id="light-dark-mode">
                <a href="#!" class="dropdown-item" data-mode="light"><i class="bi bi-sun align-middle me-2"></i> Default (light
                  mode)</a>
                <a href="#!" class="dropdown-item" data-mode="dark"><i class="bi bi-moon align-middle me-2"></i> Dark</a>
                <a href="#!" class="dropdown-item" data-mode="auto"><i class="bi bi-moon-stars align-middle me-2"></i> Auto
                  (system default)</a>
              </div>
            </div>

            <div class="dropdown ms-sm-3 topbar-head-dropdown dropdown-hover-end header-item topbar-user">
              <button type="button" class="btn shadow-none btn-icon" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="d-flex align-items-center">
                  <img class="rounded-circle header-profile-user" src="<?php echo ADMIN_URL; ?>assets/images/users/avatar-1.jpg" alt="Header Avatar" />
                </span>
              </button>
              <div class="dropdown-menu dropdown-menu-end">
                <!-- item-->
                <h6 class="dropdown-header">Welcome <b><?php echo $_SESSION['user_name']; ?></b></h6>
                <!-- <a class="dropdown-item fs-sm" href="pages-profile.html"><i class="bi bi-person-circle text-muted align-middle me-1"></i>
                  <span class="align-middle">Profile</span></a> -->
                <div class="dropdown-divider"></div>
                <a class="dropdown-item fs-sm" href="<?php echo ADMIN_URL; ?>settings"><i class="bi bi-gear text-muted align-middle me-1"></i>
                  <span class="align-middle">Settings</span></a>
                <a class="dropdown-item fs-sm" href="<?php echo ADMIN_URL; ?>admin-change-password"><i class="bi bi-gear text-muted align-middle me-1"></i>
                  <span class="align-middle">Change Password</span></a>
                <a class="dropdown-item fs-sm" href="<?php echo ADMIN_URL; ?>logout"><i class="bi bi-box-arrow-right text-muted align-middle me-1"></i>
                  <span class="align-middle" data-key="t-logout">Logout</span></a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </header>
    <div class="wrapper"></div>

    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->
    <div class="main-content">
      <div class="page-content">
        <div class="container-fluid">