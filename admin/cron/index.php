<?php include '../includes/connection.php' ?>
<?php
$date = date('Y-m-d');
$fetching_users = mysqli_query($con, "SELECT * FROM users WHERE Date(expired_date) <= '$date'");
while ($row = mysqli_fetch_assoc($fetching_users)) {
    $user_id  = $row['user_id'];
    $q = "UPDATE `users` SET status='Expired' where user_id='$user_id'";
        // var_dump($q);
        $sql = mysqli_query($con, $q);
        //echo $sql;
}
?>