<?php
session_start();
$_SESSION['login'] = false;
include "../includes/connection.php";

if (empty($_GET['token'])) {
    echo "<script>
            window.location.href='../forgot_password';
        </script>";
}

$token = $_GET['token'];

if (isset($_POST['change_pass'])) {
    if (!empty($_GET['token'])) {
        $check = mysqli_query($con, "SELECT * from users where token='$token'");
        $result = mysqli_num_rows($check);
        if ($result == 1) {
            $row = mysqli_fetch_assoc($check);
            $id = $row['user_id'];
            $dbtoken = $row['token'];
            if ($token == $dbtoken) {
                $password = md5($_POST['password']);
                $update = mysqli_query($con, "UPDATE users SET users_password='$password' WHERE `user_id`='$id'");
                $empty = "";
                $remove = mysqli_query($con, "UPDATE users SET token='$empty' where `user_id` = '$id'");
                if ($update) {
                    $_SESSION['success'] = 'Password changed successfully';
                    // echo "<script>
                    //             window.location.href='../login/index.php';
                    //         </script>";
                } else {
                    $_SESSION['error'] = 'Failed';
                    // echo "<script>
                    //             window.location.href='../login/index.php';
                    //         </script>";
                }
            } else {
                $_SESSION['error'] = 'Token invalid';
            }
        } else {
            $_SESSION['error'] = "Token Expired";
            // echo "
            // <script>
            //     window.location.href='../login/index.php';
            // </script>";
        }
    } else {
        $_SESSION['error'] = "Token Expired";
        // echo "
        //     <script>
        //         window.location.href='../login/index.php';
        //     </script>";
    }
}
?>

<!doctype html>
<html lang="en" data-layout="vertical" data-sidebar="dark" data-sidebar-size="lg" data-preloader="disable" data-bs-theme="light">

<head>

    <meta charset="utf-8">
    <title>Change Password | Tender18</title>
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
                                                <h5 class="fs-3xl">Change Password</h5>
                                                <p class="text-muted">Enter new password to change password.</p>
                                            </div>
                                            <div class="p-2 mt-5">
                                                <form action="" method="post" id="login">
                                                    <div class="mb-3">
                                                        <div class="position-relative auth-pass-inputgroup overflow-hidden">
                                                            <div class="input-group">
                                                                <span class="input-group-text" id="basic-addon1"><i class="ri-lock-2-line"></i></span>
                                                                <input type="password" name="password" class="form-control pe-5 password-input" placeholder="Enter your new password" id="password">
                                                            </div>
                                                            <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon" type="button" id="password-addon"><i class="ri-eye-fill align-middle"></i></button>
                                                        </div>
                                                    </div>

                                                    <div class="mb-3">
                                                        <div class="position-relative auth-pass-inputgroup overflow-hidden">
                                                            <div class="input-group">
                                                                <span class="input-group-text" id="basic-addon2"><i class="ri-lock-2-line"></i></span>
                                                                <input type="password" name="confirm_password" class="form-control pe-5 password-input" placeholder="Enter your confirm password" id="confirm_password">
                                                            </div>
                                                            <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon" type="button" id="password-addon1"><i class="ri-eye-fill align-middle"></i></button>
                                                        </div>
                                                    </div>
                                                    <div class="mt-4">
                                                        <button class="btn btn-primary w-100" type="submit" name="change_pass">Update</button>
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
                                                                            window.location.href='../login/';
                                                                        }, 3000);
                                                                    
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
                    'password': {
                        required: true
                    },
                    'confirm_password': {
                        required: true,
                        equalTo: '#password'
                    }
                },
            });
        });
    </script>
</body>

</html>