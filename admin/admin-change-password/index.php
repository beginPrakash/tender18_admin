<?php

include "../includes/authentication.php";
?>
<?php include '../includes/header.php'; ?>
<?php // include '../includes/connection.php';

?>
<?php
if (isset($_POST['change_pass'])) {
    $user = $_SESSION['user_name'];
    $opass = md5($_POST['opass']);
    $npass = md5($_POST['npass']);
    $cpass = md5($_POST['cpass']);
    $check = mysqli_query($con, "SELECT users_password FROM users WHERE users_name='$user' and users_password='$opass'");
    $result = mysqli_num_rows($check);
    if ($result == 0) {
        $_SESSION['error'] = 'Old Password is wrong';
    } else {
        if ($opass == $npass) {
            $_SESSION['error'] = 'Old Password and New Password should not be similar';
        } else {
            $update = mysqli_query($con, "UPDATE users SET users_password='$npass' WHERE users_name = '$user'");
            if (!$update) {
                $_SESSION['error'] = 'Failed to Change Password ';
            } else {
                $_SESSION['success'] = 'Password successfully chaged';
            }
        }
    }
}

?>
<?php
if (!empty($_SESSION['success'])) {
    echo '<div class="alert alert-success alert-dismissible fade show mb-4 show msg_box" role="alert">
            <strong>' . $_SESSION['success'] . '</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
    $_SESSION['success'] = "";
    echo "
             <script>
                     setTimeout(function(){
                         //window.location.reload();
                         document.querySelector('.msg_box').remove();
                     }, 3000);
                 
             </script>";
}
if (!empty($_SESSION['error'])) {
    echo '<div class="alert alert-danger alert-dismissible fade show mb-4 msg_box" role="alert">
            <strong> ' . $_SESSION['error'] . ' </strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
    $_SESSION['error'] = "";
    echo "
             <script>
                     setTimeout(function(){
                         //window.location.reload();
                         document.querySelector('.msg_box').remove();
                     }, 3000);
                 
             </script>";
}

?>
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Change Password</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <!-- <div class="card-header">
                <h4 class="card-title mb-0">Change Password</h4>
            </div> -->
            <form action="" method="post" id="pass_check" enctype="multipart/form-data">
                <div class="card-body">
                    <div class="row gy-4">
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="opass" class="form-label">Old Password : <span class="text-danger">*</span></label>
                                <input type="password" name="opass" placeholder="Enter Old Password " class="form-control" id="opass">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="npass" class="form-label">New Password : <span class="text-danger">*</span></label>
                                <input type="password" name="npass" placeholder="Enter New Password " class="form-control" id="npass">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="cpass" class="form-label">Confirm Password : <span class="text-danger">*</span></label>
                                <input type="password" name="cpass" placeholder="Enter Confirm Password " class="form-control" id="cpass">
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="text-start">
                                <button type="submit" class="btn btn-primary" name="change_pass">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php include "../includes/footer.php" ?>