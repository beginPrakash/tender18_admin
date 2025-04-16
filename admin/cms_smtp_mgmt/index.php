<?php



include "../includes/authentication.php";
$pages = 'cms_smtp_mgmt';
?>

<?php include '../includes/header.php'; ?>

<?php // include '../includes/connection.php';



?>

<?php

if (isset($_POST['btnInsert'])) {

    $host = $_POST['host'];
    $user_name = $_POST['user_name'];
    $password = $_POST['password'];
    $port = $_POST['port'];
    $from_email = $_POST['from_email'];
    $from_name = $_POST['from_name'];

    $q1 = "UPDATE `cms_smtp_management` SET `host`='$host', `user_name`='$user_name', `password`='$password', `port`='$port', `from_email`='$from_email', `from_name`='$from_name' WHERE `id`=1";

    $sql1 = mysqli_query($con, $q1);



    if ($sql1) {

        $_SESSION['success'] = 'Updated successfully.';

    } else {

        $_SESSION['error'] = 'Something went wrong.';

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

                        window.location.href='" . ADMIN_URL . "/cms_smtp_mgmt/index.php';

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

                        window.location.href='" . ADMIN_URL . "/cms_smtp_mgmt/index.php';

                         document.querySelector('.msg_box').remove();

                     }, 3000);

                 

             </script>";

}



?>

<div class="row">

    <div class="col-12">

        <div class="page-title-box d-sm-flex align-items-center justify-content-between">

            <h4 class="mb-sm-0">CMS SMTP management</h4>

        </div>

    </div>

</div>

<?php

$host = "";

$user_name = "";

$password = "";

$port = "";

$from_email = "";

$from_name = "";

$header_data = mysqli_query($con, "SELECT * FROM `cms_smtp_management`");

$header_result = mysqli_num_rows($header_data);

if ($header_result == 1) {

    while ($row = mysqli_fetch_assoc($header_data)) {

        $host = $row['host'];

        $user_name = $row['user_name'];

        $password = $row['password'];

        $port = $row['port'];

        $from_email = $row['from_email'];

        $from_name = $row['from_name'];

    }

}



?>

<div class="row">

    <div class="col-lg-12">

        <div class="card">

            <form action="" method="post" id="smtp_form">

                <div class="card-header">

                    <h4 class="card-title mb-0">CMS SMTP Management</h4>

                </div>

                <div class="card-body">

                    <div class="row gy-4">

                        <div class="col-xxl-12 col-md-12">

                            <div class="col-md-6">

                                <label for="host" class="form-label">Host </label>

                                <input class="form-control" type="text" name="host" id="host" value="<?php echo $host; ?>">


                            </div>

                        </div>

                        <div class="col-xxl-12 col-md-12">

                            <div class="col-md-6">

                                <label for="user_name" class="form-label">User Name : <span class="text-danger">*</span></label>

                                <input type="text" name="user_name" class="form-control" id="user_name" value="<?php echo htmlspecialcode_generator($user_name); ?>">

                            </div>

                        </div>

                        <div class="col-xxl-12 col-md-12">

                            <div class="col-md-6">

                                <label for="password" class="form-label">Password : <span class="text-danger">*</span></label>

                                <input type="text" name="password" class="form-control" id="password" value="<?php echo $password; ?>">

                            </div>

                        </div>

                        <div class="col-xxl-12 col-md-12">

                            <div class="col-md-6">

                                <label for="port" class="form-label">Port : <span class="text-danger">*</span></label>

                                <input type="text" name="port" class="form-control" id="port" value="<?php echo htmlspecialcode_generator($port); ?>">

                            </div>

                        </div>

                        <div class="col-xxl-12 col-md-12">

                            <div class="col-md-6">

                                <label for="from_email" class="form-label">From Email : <span class="text-danger">*</span></label>

                                <input type="text" name="from_email" class="form-control" id="from_email" value="<?php echo $from_email; ?>">

                            </div>

                        </div>
                        <div class="col-xxl-12 col-md-12">

                            <div class="col-md-6">

                                <label for="from_name" class="form-label">From Name : <span class="text-danger">*</span></label>

                                <input type="text" name="from_name" class="form-control" id="from_name" value="<?php echo $from_name; ?>">

                            </div>

                        </div>

                    </div>

                </div>

                <div class="card-body">

                    <div class="row gy-4">

                        <div class="col-lg-12">

                            <div class="text-start">

                                <button type="submit" class="btn btn-primary" name="btnInsert">Submit</button>

                            </div>

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </div>

</div>

<?php include "../includes/footer.php" ?>