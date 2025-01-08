<?php 

    $dashboards_per = _get_user_perby_role($_SESSION['user_id'],'dashboards',$con);
    $archive_tenders_per = _get_user_perby_role($_SESSION['user_id'],'archive_tenders',$con);
    $live_tenders_per = _get_user_perby_role($_SESSION['user_id'],'live_tenders',$con);
    $tenders_per = _get_user_perby_role($_SESSION['user_id'],'tenders',$con);
    $menus_per = _get_user_perby_role($_SESSION['user_id'],'menus',$con);
    $clients_per = _get_user_perby_role($_SESSION['user_id'],'clients',$con);
    $users_per = _get_user_perby_role($_SESSION['user_id'],'users',$con);
    $testimonials_per = _get_user_perby_role($_SESSION['user_id'],'testimonials',$con);
    $pages_per = _get_user_perby_role($_SESSION['user_id'],'pages',$con);
    $free_quote_form_per = _get_user_perby_role($_SESSION['user_id'],'free_quote_form',$con);
    $tender_inquiry_per = _get_user_perby_role($_SESSION['user_id'],'tender_inquiry',$con);
    $feedback_inquiry_per = _get_user_perby_role($_SESSION['user_id'],'feedback_inquiry',$con);
    $complain_inquiry_per = _get_user_perby_role($_SESSION['user_id'],'complain_inquiry',$con);
    $registration_form_per = _get_user_perby_role($_SESSION['user_id'],'registration_form',$con);
    $blogs_per = _get_user_perby_role($_SESSION['user_id'],'blogs',$con);
    $states_per = _get_user_perby_role($_SESSION['user_id'],'states',$con);
    $departments_per = _get_user_perby_role($_SESSION['user_id'],'departments',$con);
    $keywords_per = _get_user_perby_role($_SESSION['user_id'],'keywords',$con);
    $smtp_mgmt_per = _get_user_perby_role($_SESSION['user_id'],'smtp_mgmt',$con);
    $city_content_per = _get_user_perby_role($_SESSION['user_id'],'city_content',$con);
    $agency_content_per = _get_user_perby_role($_SESSION['user_id'],'agency_content',$con);
    $keyword_content_per = _get_user_perby_role($_SESSION['user_id'],'keyword_content',$con);
    $meta_content_per = _get_user_perby_role($_SESSION['user_id'],'meta_content',$con);
?>
<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
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
        <button type="button" class="btn btn-sm p-0 fs-3xl header-item float-end btn-vertical-sm-hover shadow-none" id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar" data-simplebar="init" class="h-100">
        <div class="container-fluid">
            <div id="two-column-menu"></div>
            <ul class="navbar-nav" id="navbar-nav">
                <li class="menu-title"><span data-key="t-menu">Menu</span></li>
                <?php if($_SESSION['role']=='admin' || $dashboards_per == 1) { ?>
                    <li class="nav-item active">
                        <a class="nav-link menu-link <?php if ($pages == 'index') {
                                                            echo 'active';
                                                        } ?>" href="<?php echo ADMIN_URL; ?>index.php">
                            <i class="ti ti-brand-google-home"></i>
                            <span data-key="t-dashboards">Dashboards</span>
                        </a>
                    </li>
                <?php } ?>
                <?php if($_SESSION['role']=='admin' || $pages_per == 1) { ?>
                    <li class="nav-item active">
                        <a class="nav-link menu-link <?php if ($pages == 'pages') {
                                                            echo 'active';
                                                        } ?>" href="<?php echo ADMIN_URL; ?>/pages/index.php">
                            <i class="ti ti-brand-google-home"></i>
                            <span data-key="t-dashboards">Pages</span>
                        </a>
                    </li>
                <?php } ?>
                <?php if($_SESSION['role']=='admin' || $testimonials_per == 1) { ?>
                    <li class="nav-item active">
                        <a class="nav-link menu-link <?php if ($pages == 'testimonials') {
                                                            echo 'active';
                                                        } ?>" href="<?php echo ADMIN_URL; ?>/testimonials/index.php">
                            <i class="ti ti-brand-google-home"></i>
                            <span data-key="t-dashboards">Testimonials</span>
                        </a>
                    </li>
                <?php } ?>
                <?php if($_SESSION['role']=='admin' || $users_per == 1) { ?>
                    <li class="nav-item active">
                        <a class="nav-link menu-link <?php if ($pages == 'users') {
                                                            echo 'active';
                                                        } ?>" href="<?php echo ADMIN_URL; ?>/users/index.php">
                            <i class="ti ti-brand-google-home"></i>
                            <span data-key="t-dashboards">Users</span>
                        </a>
                    </li>
                <?php } ?>
                <?php if($_SESSION['role']=='admin' || $keywords_per == 1) { ?>
                    <li class="nav-item active">
                        <a class="nav-link menu-link <?php if ($pages == 'keywords') {
                                                            echo 'active';
                                                        } ?>" href="<?php echo ADMIN_URL; ?>/keywords/index.php">
                            <i class="ti ti-brand-google-home"></i>
                            <span data-key="t-dashboards">Keywords</span>
                        </a>
                    </li>
                <?php } ?>
                <?php if($_SESSION['role']=='admin' || $clients_per == 1) { ?>
                    <li class="nav-item active">
                        <a class="nav-link menu-link <?php if ($pages == 'clients') {
                                                            echo 'active';
                                                        } ?>" href="<?php echo ADMIN_URL; ?>/clients/index.php">
                            <i class="ti ti-brand-google-home"></i>
                            <span data-key="t-dashboards">Clients</span>
                        </a>
                    </li>
                <?php } ?>
                <?php if($_SESSION['role']=='admin' || $blogs_per == 1) { ?>
                    <li class="nav-item active">
                        <a class="nav-link menu-link <?php if ($pages == 'blogs') {
                                                            echo 'active';
                                                        } ?>" href="<?php echo ADMIN_URL; ?>/blogs/index.php">
                            <i class="ti ti-brand-google-home"></i>
                            <span data-key="t-dashboards">Blogs</span>
                        </a>
                    </li>
                <?php } ?>
                <?php if($_SESSION['role']=='admin' || $states_per == 1) { ?>
                    <li class="nav-item active">
                        <a class="nav-link menu-link <?php if ($pages == 'states') {
                                                            echo 'active';
                                                        } ?>" href="<?php echo ADMIN_URL; ?>/states/index.php">
                            <i class="ti ti-brand-google-home"></i>
                            <span data-key="t-dashboards">States</span>
                        </a>
                    </li>
                <?php } ?>
                <?php if($_SESSION['role']=='admin' || $departments_per == 1) { ?>
                    <li class="nav-item active">
                        <a class="nav-link menu-link <?php if ($pages == 'departments') {
                                                            echo 'active';
                                                        } ?>" href="<?php echo ADMIN_URL; ?>/departments/index.php">
                            <i class="ti ti-brand-google-home"></i>
                            <span data-key="t-dashboards">Departments</span>
                        </a>
                    </li>
                <?php } ?>
                <?php if($_SESSION['role']=='admin' || $menus_per == 1) { ?>
                    <li class="nav-item active">
                        <a class="nav-link menu-link <?php if ($pages == 'menus') {
                                                            echo 'active';
                                                        } ?>" href="<?php echo ADMIN_URL; ?>/menus/index.php">
                            <i class="ti ti-brand-google-home"></i>
                            <span data-key="t-dashboards">Menus</span>
                        </a>
                    </li>
                <?php } ?>
                <!-- <li class="nav-item active">
                    <a class="nav-link menu-link <?php if ($pages == 'agencies') {
                                                        echo 'active';
                                                    } ?>" href="<?php echo ADMIN_URL; ?>/agencies">
                        <i class="ti ti-brand-google-home"></i>
                        <span data-key="t-dashboards">Agencies</span>
                    </a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link menu-link <?php if ($pages == 'zipcodes') {
                                                        echo 'active';
                                                    } ?>" href="<?php echo ADMIN_URL; ?>/zipcodes">
                        <i class="ti ti-brand-google-home"></i>
                        <span data-key="t-dashboards">Zipcodes</span>
                    </a>
                </li> -->

                <?php if($_SESSION['role']=='admin' || $tenders_per == 1) { ?>
                    <li class="nav-item active">
                        <a class="nav-link menu-link <?php if ($pages == 'tenders' || $pages == 'zipcodes' || $pages == 'agencies') {
                                                            echo 'active';
                                                        } ?>" href="<?php echo ADMIN_URL; ?>/tenders/index.php">
                            <i class="ti ti-brand-google-home"></i>
                            <span data-key="t-dashboards">Tenders</span>
                        </a>
                    </li>
                <?php } ?>
                <?php if($_SESSION['role']=='admin' || $live_tenders_per == 1) { ?>
                    <li class="nav-item active">
                        <a class="nav-link menu-link <?php if ($pages == 'live-tenders') {
                                                            echo 'active';
                                                        } ?>" href="<?php echo ADMIN_URL; ?>live-tenders/index.php">
                            <i class="ti ti-brand-google-home"></i>
                            <span data-key="t-dashboards">Live Tenders</span>
                        </a>
                    </li>
                <?php } ?>
                <?php if($_SESSION['role']=='admin' || $archive_tenders_per == 1) { ?>
                    <li class="nav-item active">
                        <a class="nav-link menu-link <?php if ($pages == 'archive-tenders') {
                                                            echo 'active';
                                                        } ?>" href="<?php echo ADMIN_URL; ?>archive-tenders/index.php">
                            <i class="ti ti-brand-google-home"></i>
                            <span data-key="t-dashboards">Archive Tenders</span>
                        </a>
                    </li>
                <?php } ?>
                <!-- <li class="nav-item active">
                    <a class="nav-link menu-link <?php if ($pages == 'career') {
                                                        echo 'active';
                                                    } ?>" href="<?php echo ADMIN_URL; ?>/career">
                        <i class="ti ti-brand-google-home"></i>
                        <span data-key="t-dashboards">Career</span>
                    </a>
                </li> -->
                <?php if($_SESSION['role']=='admin' || $free_quote_form_per == 1) { ?>
                    <li class="nav-item active">
                        <a class="nav-link menu-link <?php if ($pages == 'free-quote') {
                                                            echo 'active';
                                                        } ?>" href="<?php echo ADMIN_URL; ?>/free-quote/index.php">
                            <i class="ti ti-brand-google-home"></i>
                            <span data-key="t-dashboards">Get A Free Quote Form</span>
                        </a>
                    </li>
                <?php } ?>
                <?php if($_SESSION['role']=='admin' || $tender_inquiry_per == 1) { ?>
                    <li class="nav-item active">
                        <a class="nav-link menu-link <?php if ($pages == 'tender-inquiry') {
                                                            echo 'active';
                                                        } ?>" href="<?php echo ADMIN_URL; ?>/tender-inquiry/index.php">
                            <i class="ti ti-brand-google-home"></i>
                            <span data-key="t-dashboards">Tender Inquiry</span>
                        </a>
                    </li>
                <?php } ?>
                <?php if($_SESSION['role']=='admin' || $feedback_inquiry_per == 1) { ?>
                    <li class="nav-item active">
                        <a class="nav-link menu-link <?php if ($pages == 'feedback-inquiry') {
                                                            echo 'active';
                                                        } ?>" href="<?php echo ADMIN_URL; ?>/feedback-inquiry/index.php">
                            <i class="ti ti-brand-google-home"></i>
                            <span data-key="t-dashboards">Feedback Inquiry</span>
                        </a>
                    </li>
                <?php } ?>
                <?php if($_SESSION['role']=='admin' || $complain_inquiry_per == 1) { ?>
                    <li class="nav-item active">
                        <a class="nav-link menu-link <?php if ($pages == 'complain-inquiry') {
                                                            echo 'active';
                                                        } ?>" href="<?php echo ADMIN_URL; ?>/complain-inquiry/index.php">
                            <i class="ti ti-brand-google-home"></i>
                            <span data-key="t-dashboards">Complain Inquiry</span>
                        </a>
                    </li>
                <?php } ?>
                <?php if($_SESSION['role']=='admin' || $registration_form_per == 1) { ?>
                    <li class="nav-item active">
                        <a class="nav-link menu-link <?php if ($pages == 'registration-form') {
                                                            echo 'active';
                                                        } ?>" href="<?php echo ADMIN_URL; ?>/registration-form/index.php">
                            <i class="ti ti-brand-google-home"></i>
                            <span data-key="t-dashboards">Registration Form</span>
                        </a>
                    </li>
                <?php } ?>
                <?php if($_SESSION['role']=='admin' || $smtp_mgmt_per == 1) { ?>
                    <li class="nav-item active">
                        <a class="nav-link menu-link <?php if ($pages == 'smtp_mgmt') {
                                                            echo 'active';
                                                        } ?>" href="<?php echo ADMIN_URL; ?>/smtp_mgmt/index.php">
                            <i class="ti ti-brand-google-home"></i>
                            <span data-key="t-dashboards">SMTP Management</span>
                        </a>
                    </li>
                <?php } ?>
                <?php if($_SESSION['role']=='admin' || $meta_content_per == 1) { ?>
                    <li class="nav-item active">
                        <a class="nav-link menu-link <?php if ($pages == 'meta-content') {
                                                            echo 'active';
                                                        } ?>" href="<?php echo ADMIN_URL; ?>/meta-content/index.php">
                            <i class="ti ti-brand-google-home"></i>
                            <span data-key="t-dashboards">Meta Content</span>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </div>
        <!-- Sidebar -->
    </div>

    <div class="sidebar-background"></div>
</div>
<!-- Left Sidebar End -->