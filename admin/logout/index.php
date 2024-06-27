<?php
include "../includes/authentication.php";

if (!empty($_SESSION['login'])) {
    unset($_SESSION['user_name']);
    unset($_SESSION['login']);
    unset($_SESSION['success']);
    unset($_SESSION['error']);
    session_destroy();
    echo "<script>
    window.location.href='../login/index.php';
    </script>";
} else {
    echo "<script>
    window.location.href='../login/index.php';
    </script>";
}
