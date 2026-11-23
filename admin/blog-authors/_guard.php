<?php
// Same access rule as the Blogs module.
$blogs_per = _get_user_perby_role($_SESSION['user_id'], 'blogs', $con);
if ($_SESSION['role'] != 'admin' && !($_SESSION['role'] == 'employee' && $blogs_per == 1)) {
    echo "<script>window.location.href='../index.php';</script>";
    exit;
}
