<?php
include 'connection.php';
include 'functions.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (empty($_SESSION['login'])) {
    // echo "Do Login";
    if (!headers_sent()) {
        header('Location: ' . ADMIN_URL . 'login/index.php');
    }
    echo "<script>
        window.location.href='" . ADMIN_URL . "login/index.php';
        </script>";
    // Stop here: without this the protected page kept rendering for logged-out visitors
    exit;
}
