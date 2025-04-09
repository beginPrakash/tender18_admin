<?php
include 'connection.php';
include 'functions.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
