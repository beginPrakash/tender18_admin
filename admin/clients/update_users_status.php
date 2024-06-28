<?php
include '../includes/connection.php';
include '../includes/functions.php';

$usersData = mysqli_query($con, "SELECT * FROM `users` WHERE user_role='user' AND DATE(expired_date) >= DATE(NOW())");
$usersResult = mysqli_num_rows($usersData);
if ($usersResult > 0) {
    while ($row = mysqli_fetch_assoc($usersData)) {
        $q = "UPDATE `users` SET `status`='Expired' where user_id='" . $row['user_id'] . "' and user_unique_id='" . $row['user_unique_id'] . "' and `status`!='Expired'";
        $sql = mysqli_query($con, $q);
    }
}
