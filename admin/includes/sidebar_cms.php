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
    $inquiries_content_per = _get_user_perby_role($_SESSION['user_id'],'inquiries',$con);
    $cms_customer_per = _get_user_perby_role($_SESSION['user_id'],'cms_customer',$con);
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
              
            </ul>
        </div>
        <!-- Sidebar -->
    </div>

    <div class="sidebar-background"></div>
</div>
<!-- Left Sidebar End -->