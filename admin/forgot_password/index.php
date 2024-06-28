<?php

session_start();
$_SESSION['login'] = false;
require_once('../MailConfig.php');
include "../includes/connection.php";
if (isset($_POST['check'])) {
    $email = $_POST['uemail'];
    $select = mysqli_query($con, "SELECT users_email FROM users WHERE users_email='$email'");
    $row = mysqli_fetch_assoc($select);
    $result = mysqli_num_rows($select);
    if ($result == 1) {
        $dbemail = $row['users_email'];
        $string = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $token =  substr(str_shuffle($string), 0, 8);
        $unique = substr(str_shuffle($string), 0, 8);
        //   echo $unique."<br>";
        // echo $token;
        $update = mysqli_query($con, "UPDATE users SET `user_unique_id`='$unique', `token`='$token' WHERE users_email='$dbemail'");
        if (!$update) {
            //echo "Failed";
        } else {
            // echo "updated";
            // echo $unique. "<br>";
            // echo $token. "<br>";
        }

        // $fetch = mysqli_query($con,"SELECT * FROM `users`");
        // while($row = mysqli_fetch_assoc($fetch))
        // {
        //     $unique_id = $row['user_unique_id'];
        //     $token_id = $row['token'];
        // }
        // echo "<h1>".$unique_id."</h1>";
        // echo "<h1>".$token_id."</h1>";

        $name = 'Tender18';
        $to = $dbemail;
        $message = '<p>Click here to change your password : </p><input type="hidden" name="unique" value="' . $unique . '"><input type="hidden" name="token" value="' . $token . '"><a href="' . ADMIN_URL . 'change_password?unique=' . $unique . '&token=' . $token . '">Reset Password</a> ';
        $subject = 'Forgot Password';

        email($name, $to, $message, $subject);
        $_SESSION['success'] = "Reset Password link has been sent to your email address";
    } else {
        $_SESSION['error'] = "Invalid Email";
    }
}
?>

<!doctype html>
<html lang="en" data-layout="vertical" data-sidebar="dark" data-sidebar-size="lg" data-preloader="disable" data-bs-theme="light">

<head>

    <meta charset="utf-8">
    <title>Forgot Password | Tender18</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Tender18" name="author">
    <!-- App favicon -->
    <link rel="shortcut icon" href="../assets/images/tender18-favicon.webp">

    <!-- Layout config Js -->
    <script src="../assets/js/layout.js"></script>
    <!-- Bootstrap Css -->
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <!-- Icons Css -->
    <link href="../assets/css/icons.min.css" rel="stylesheet" type="text/css">
    <!-- App Css-->
    <link href="../assets/css/app.min.css" rel="stylesheet" type="text/css">
    <!-- custom Css-->
    <link href="../assets/css/custom.min.css" rel="stylesheet" type="text/css">
    <style>
        .error {
            color: red;
            width: 100%;
        }
    </style>
</head>

<body>
    <input type="hidden" value="<?php echo ADMIN_URL; ?>" id="admin_url">
    <section class="auth-page-wrapper py-5 position-relative bg-light d-flex align-items-center justify-content-center min-vh-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-11">
                    <div class="card mb-0">
                        <div class="card-body">
                            <div class="row g-0 align-items-center">
                                <div class="col-xxl-6 mx-auto">
                                    <div class="card mb-0 border-0 shadow-none mb-0">
                                        <div class="card-body p-sm-5 m-lg-4">
                                            <div class="text-center mt-5">
                                                <h5 class="fs-3xl">Forgot Password</h5>
                                                <p class="text-muted">Enter your email to forgot password.</p>
                                            </div>
                                            <div class="p-2 mt-5">
                                                <form action="" method="post" id="login">
                                                    <div class="mb-3">
                                                        <div class="input-group">
                                                            <span class="input-group-text" id="basic-addon"><i class="ri-user-3-line"></i></span>
                                                            <input type="email" class="form-control" name="uemail" placeholder="Enter your Email">
                                                        </div>
                                                    </div>
                                                    <div class="mt-4">
                                                        <button class="btn btn-primary w-100" type="submit" name="check">Forgot</button>
                                                        <a class="text-center d-block mt-3 w-100" href="../login/">Back to Login</a>
                                                    </div>
                                                    <div class="tx-dark tx-center mg-b-60">
                                                        <?php
                                                        if (!empty($_SESSION['error'])) {
                                                            echo '<div class="alert alert-danger alert-dismissible fade show mb-xl-0 mt-4 msg_box" role="alert">
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
                                                        if (!empty($_SESSION['success'])) {
                                                            echo '<div class="alert alert-success alert-dismissible fade show mb-xl-0 mt-4 show msg_box" role="alert">
                                                                <strong>' . $_SESSION['success'] . '</strong>
                                                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                            </div>';
                                                            $_SESSION['success'] = "";
                                                            echo "
                                                                <script>
                                                                        setTimeout(function(){
                                                                            //window.location.reload();
                                                                            document.querySelector('.msg_box').remove();
                                                                        }, 6000);
                                                                    
                                                                </script>";
                                                        }

                                                        ?></div>
                                                </form>
                                            </div>
                                        </div><!-- end card body -->
                                    </div><!-- end card -->
                                </div>
                                <!--end col-->

                            </div>
                            <!--end row-->
                        </div>
                    </div>
                </div>
                <!--end col-->
            </div>
            <!--end row-->
        </div>
        <!--end container-->
    </section>

    <!-- JAVASCRIPT -->
    <script src="../assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/libs/simplebar/simplebar.min.js"></script>
    <script src="../assets/js/plugins.js"></script>



    <script src="../assets/js/pages/password-addon.init.js"></script>

    <!--Swiper slider js-->
    <script src="../assets/libs/swiper/swiper-bundle.min.js"></script>

    <!-- swiper.init js -->
    <script src="../assets/js/pages/swiper.init.js"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#login').validate({
                rules: {
                    'uemail': "required"
                },
            });
        });
    </script>
</body>

</html>