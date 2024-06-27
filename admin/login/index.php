<?php
session_start();
$_SESSION['login'] = false;
include "../includes/connection.php";
if (isset($_POST['login'])) {
    $name = $_POST['name'];
    $password = md5($_POST['password']);
    $select = mysqli_query($con, "SELECT * FROM `users` WHERE  users_name='$name' OR users_email='$name'");
    $result = mysqli_num_rows($select);
    $pass = mysqli_fetch_assoc($select);
    $role = ['admin', 'employee'];
    // print_r($pass['users_password']);
    // die();
    if ($result == 1 && $pass['users_password'] == $password) {
        if (in_array($pass['user_role'], $role)) {
            $_SESSION['success'] = "Welcome !";
            $fetch = mysqli_query($con, "SELECT * FROM users WHERE  users_name='$name' OR users_email='$name'");
            while ($row = mysqli_fetch_assoc($fetch)) {
                $user_name = $row['users_name'];
                $user_email = $row['users_email'];
                $user_role = $row['user_role'];
                $user_id = $row['user_id'];
            }
            $_SESSION['login'] = true;
            $_SESSION['user_name'] = $user_name;
            $_SESSION['role'] = $user_role;
            $_SESSION['user_id'] = $user_id;
            echo "<script>
            window.location.href='../index.php';
            </script>";
        } else {
            $_SESSION['error'] = "Unauthorized role";
        }
    } else {
        $_SESSION['error'] = "Invalid Credential";
    }
}
?>

<!doctype html>
<html lang="en" data-layout="vertical" data-sidebar="dark" data-sidebar-size="lg" data-preloader="disable" data-bs-theme="light">

<head>

    <meta charset="utf-8">
    <title>Sign In | Tender18</title>
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
                                                <img src="<?php echo ADMIN_URL; ?>assets/images/tender18-logo.webp" alt="" height="22" />
                                                <h5 class="fs-3xl mt-3">Welcome Back</h5>
                                                <p class="text-muted">Sign in to continue to Tender18.</p>
                                            </div>
                                            <div class="p-2 mt-5">
                                                <form action="" method="post" id="login">
                                                    <div class="mb-3">
                                                        <div class="input-group">
                                                            <span class="input-group-text" id="basic-addon"><i class="ri-user-3-line"></i></span>
                                                            <input type="text" name="name" class="form-control" id="username" placeholder="Enter username">
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <div class="position-relative auth-pass-inputgroup overflow-hidden">
                                                            <div class="input-group">
                                                                <span class="input-group-text" id="basic-addon1"><i class="ri-lock-2-line"></i></span>
                                                                <input type="password" name="password" class="form-control pe-5 password-input" placeholder="Enter password" id="password-input">
                                                            </div>
                                                            <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon" type="button" id="password-addon"><i class="ri-eye-fill align-middle"></i></button>
                                                        </div>
                                                    </div>
                                                    <div class="float-end">
                                                        <a href="../forgot_password/" class="text-muted">Forgot password?</a>
                                                    </div>
                                                    <div class="mt-4">
                                                        <button class="btn btn-primary w-100" type="submit" name="login">Sign In</button>
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
                                                        if (!empty($_SESSION['token'])) {
                                                            echo '<div class="alert alert-danger alert-dismissible fade show mb-xl-0 mt-4 msg_box" role="alert">
                                                                <strong> ' . $_SESSION['token'] . ' </strong>
                                                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                            </div>';
                                                            $_SESSION['token'] = "";
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
                    'password': "required",
                    'name': "required"
                },
            });
        });
    </script>
</body>

</html>