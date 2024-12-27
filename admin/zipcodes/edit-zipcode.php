<?php include '../includes/authentication.php';

?>

<?php $pages = 'zipcodes'; ?>

<?php include '../includes/header.php' ?>



<?php

if (!empty($_GET['id'])) {

    $id = $_GET['id'];

} else {

    echo "<script>window.location.href='" . ADMIN_URL . "/zipcodes/index.php';</script>";

    die();

}

?>



<?php

if (isset($_POST['submit'])) {

    $tenderID = $_POST['tenderID'];

    $zipcode = $_POST['zipcode'];

    $city = $_POST['city'];

    $state = $_POST['state'];



    $q1 = "UPDATE `tender_zipcodes` SET `zipcode`='$zipcode', `city`='$city', `state`='$state' WHERE `id`='$tenderID'";



    // var_dump($q1);

    // die();

    $sql1 = mysqli_query($con, $q1);



    $status = true;

    if ($status) {

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

                        window.location.href='" . ADMIN_URL . "/zipcodes/index.php';

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

                        window.location.href='" . ADMIN_URL . "/zipcodes/index.php';

                         document.querySelector('.msg_box').remove();

                     }, 3000);

                 

             </script>";

}



?>



<style>

    .details_banner_section i.ri-delete-bin-5-fill {

        color: red;

        float: right;

        font-size: 20px;

        cursor: pointer;

    }

</style>



<!-- start page title -->

<div class="row">

    <div class="col-12">

        <div class="page-title-box d-sm-flex align-items-center justify-content-between">

            <h4 class="mb-sm-0">Edit Zipcode</h4>

        </div>

    </div>

</div>

<!-- end page title -->



<?php

$tenderID = "";

$zipcode = "";

$city = "";

$state = "";



$banner_data = mysqli_query($con, "SELECT * FROM `tender_zipcodes` where id='" . $id . "'");

$banner_result = mysqli_num_rows($banner_data);

if ($banner_result == 1) {

    while ($row = mysqli_fetch_assoc($banner_data)) {

        $tenderID = $row['id'];

        $zipcode = $row['zipcode'];

        $city = $row['city'];

        $state = $row['state'];

    }

}

?>



<div class="row">

    <div class="col-lg-12">

        <div class="card">

            <form action="" method="post" id="homepage" enctype="multipart/form-data">

                <input type="hidden" name="tenderID" value="<?php echo $tenderID; ?>">

                <div class="card-header">

                    <h4 class="card-title mb-0">Zipcode Details</h4>

                </div>

                <div class="card-body">

                    <div class="row gy-4">

                        <div class="col-xxl-12 col-md-12">

                            <div class="col-md-6">

                                <label for="zipcode" class="form-label">Zipcode : <span class="text-danger">*</span></label>

                                <input type="text" name="zipcode" value="<?php echo $zipcode; ?>" class="form-control" id="zipcode">

                            </div>

                        </div>

                        <div class="col-xxl-12 col-md-12">

                            <div class="col-md-6">

                                <label for="city" class="form-label">City : <span class="text-danger">*</span></label>

                                <input type="text" name="city" value="<?php echo $city; ?>" class="form-control" id="city">

                            </div>

                        </div>

                        <div class="col-xxl-12 col-md-12">

                            <div class="col-md-6">

                                <label for="state" class="form-label">State : <span class="text-danger">*</span></label>

                                <input type="text" name="state" value="<?php echo $state; ?>" class="form-control" id="state">

                            </div>

                        </div>

                    </div>

                </div>



                <div class="card-body">

                    <div class="row gy-4">

                        <div class="col-lg-12">

                            <div class="text-start">

                                <button type="submit" class="btn btn-primary" name="submit">Update</button>

                            </div>

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </div>

</div>



<?php include "../includes/footer.php" ?>



<script>

    $(document).ready(function() {

        $('#homepage').validate({

            rules: {

                'zipcode': "required",

                'city': "required",

                'state': "required"

            },

        });

    });

</script>