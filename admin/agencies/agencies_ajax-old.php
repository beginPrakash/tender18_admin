<?php
include '../includes/authentication.php';

if (isset($_POST['title'])) {

    $title = $_POST['title'];
    $ref_no = $_POST['ref_no'];

    $q1 = "INSERT INTO tender_agencies(`pseudo_name`, `agency_name`) VALUES ('" . $title . "', '" . $ref_no . "')";
    $sql1 = mysqli_query($con, $q1);

    echo $title;
    die();
}
