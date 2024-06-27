<?php
include 'connection.php';
include 'functions.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (empty($_SESSION['login'])) {
    // echo "Do Login";
    echo "<script>
        window.location.href='" . ADMIN_URL . "login/index.php';
        </script>";
}
