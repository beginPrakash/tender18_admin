<?php include '../includes/authentication.php';
?>
<?php $pages = 'zipcodes'; ?>
<?php include '../includes/header.php' ?>

<?php
if (isset($_POST['submit'])) {
    $zipcode = $_POST['zipcode'];
    $city = $_POST['city'];
    $state = $_POST['state'];

    $q1 = "INSERT INTO tender_zipcodes(`zipcode`, `city`, `state`) VALUES ('$zipcode', '$city', '$state')";

    // var_dump($q1);
    // die();
    $sql1 = mysqli_query($con, $q1);

    $status = true;
    if ($status) {
        $_SESSION['success'] = 'Added successfully.';
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
                        window.location.href='" . ADMIN_URL . "/zipcodes';
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
                        window.location.href='" . ADMIN_URL . "/zipcodes';
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
            <h4 class="mb-sm-0">Add New Zipcode</h4>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <form action="" method="post" id="homepage" enctype="multipart/form-data">
                <div class="card-header">
                    <h4 class="card-title mb-0">Zipcodes Details</h4>
                </div>
                <div class="card-body">
                    <div class="row gy-4">
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="zipcode" class="form-label">Zipcode : <span class="text-danger">*</span></label>
                                <input type="text" name="zipcode" class="form-control" id="zipcode">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="city" class="form-label">City : <span class="text-danger">*</span></label>
                                <input type="text" name="city" class="form-control" id="city">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="state" class="form-label">State : <span class="text-danger">*</span></label>
                                <input type="text" name="state" class="form-control" id="state">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row gy-4">
                        <div class="col-lg-12">
                            <div class="text-start">
                                <button type="submit" class="btn btn-primary" name="submit">Add</button>
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