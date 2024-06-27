<?php
include '../includes/authentication.php';

if (isset($_POST['title'])) {

    $title = $_POST['title'];
    $tender_id = $_POST['tender_id'];
    $agency_type = $_POST['agency_type'];
    $location = $_POST['location'];
    $tender_value = $_POST['tender_value'];
    $description = $_POST['description'];
    $pincode = $_POST['pincode'];
    $tender_fee = $_POST['tender_fee'];
    $tender_emd = $_POST['tender_emd'];
    $documents = $_POST['documents'];
    $publish_date = $_POST['publish_date'];
    $due_date = $_POST['due_date'];

    $q1 = "INSERT INTO tenders_posts(`title`, `tender_id`, `agency_type`, `location`, `due_date`, `tender_value`, `description`, `pincode`, `publish_date`, `tender_fee`, `tender_emd`, `documents`) VALUES ('" . $title . "', '" . $tender_id . "', '" . $agency_type . "', '" . $location . "', '" . $due_date . "', '" . $tender_value . "', '" . $description . "', '" . $pincode . "', '" . $publish_date . "', '" . $tender_fee . "', '" . $tender_emd . "', '" . $documents . "')";
    $sql1 = mysqli_query($con, $q1);

    echo $title;
    die();
}
