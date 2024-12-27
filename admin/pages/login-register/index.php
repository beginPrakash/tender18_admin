<?php include '../../includes/authentication.php';
?>
<?php $pages = 'pages'; ?>
<?php include '../../includes/header.php' ?>
<style>
    .details_banner_section i.ri-delete-bin-5-fill {
        color: red;
        float: right;
        font-size: 20px;
        cursor: pointer;
    }
</style>
<?php
if (isset($_POST['submit'])) {
    //why section
    $why_title = mysqli_real_escape_string($con, $_POST['why_title']);
    $why_description = mysqli_real_escape_string($con, $_POST['why_description']);

    $why_details_title = $_POST['why_details_title'];
    $why_details_sub_title = $_POST['why_details_sub_title'];

    $query_delete = mysqli_query($con, "DELETE FROM `login_register_why_section`");
    $query_delete = mysqli_query($con, "DELETE FROM `login_register_why_details`");

    $q = "INSERT INTO login_register_why_section(`title`, `description`) VALUES ('$why_title', '$why_description')";
    $sql1 = mysqli_query($con, $q);
    // print_r($why_details_title);

    foreach ($why_details_title as $key => $why_title) {
        if (!empty($why_title)) {
            $why_title = mysqli_real_escape_string($con, $why_title);
            $why_description = mysqli_real_escape_string($con, $why_details_sub_title[$key]);
            $q = "INSERT INTO login_register_why_details(`title`, `icon`) VALUES ('$why_title', '$why_description')";
            // var_dump($q);
            $sql2 = mysqli_query($con, $q);
        }
    }


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
                        window.location.href='" . ADMIN_URL . "/pages/login-register/index.php';
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
                        window.location.href='" . ADMIN_URL . "/pages/login-register/index.php';
                         document.querySelector('.msg_box').remove();
                     }, 3000);
                 
             </script>";
}

?>

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Login / Register</h4>
        </div>
    </div>
</div>
<!-- end page title -->

<?php

//why section
$why_title = "";
$why_description = "";
$why_details_title = "";
$why_details_sub_title = "";


$why_data = mysqli_query($con, "SELECT * FROM `login_register_why_section`");
$why_result = mysqli_num_rows($why_data);
if ($why_result == 1) {
    while ($row = mysqli_fetch_assoc($why_data)) {
        $why_title = $row['title'];
        $why_description = $row['description'];
    }
}
$why_details_data = mysqli_query($con, "SELECT * FROM `login_register_why_details`");
$why_details_result = mysqli_num_rows($why_details_data);
?>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <form action="" method="post" id="homepages" enctype="multipart/form-data">
                <div class="card-header">
                    <h4 class="card-title mb-0">Why Section</h4>
                </div>
                <div class="card-body">
                    <div class="row gy-4">
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="why_title" class="form-label">Title : <span class="text-danger">*</span></label>
                                <input type="text" name="why_title" class="form-control" value="<?php echo htmlspecialcode_generator($why_title); ?>" id="why_title">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="why_description" class="form-label">Description : <span class="text-danger">*</span></label>
                                <textarea rows="3" name="why_description" class="form-control" id="why_description"><?php echo htmlspecialcode_generator($why_description); ?></textarea>
                            </div>
                        </div>
                        <!-- <div class="col-lg-12">
                            <div class="text-start">
                                <button type="submit" class="btn btn-primary" name="submit">Update</button>
                            </div>
                        </div> -->
                    </div>
                </div>
                <div class="card-header">
                    <h4 class="card-title mb-0">Why Details Section</h4>
                </div>
                <div class="ps-3">
                    <button class="btn btn-success mt-3" id="add_more_why" type="button">Add more</button>
                </div>
                <div class="card-body details_banner_section why">
                    <div class="row gy-4">
                        <?php
                        if ($why_details_result > 0) {
                            while ($row = mysqli_fetch_assoc($why_details_data)) {
                                $why_details_title = $row['title'];
                                $why_details_sub_title = $row['icon'];
                        ?>
                                <div class="details-block why col-xxl-12 col-md-12 mt-4">
                                    <div class="col-xxl-6 col-md-6 bg-light pt-3 pb-4 ps-2 pe-2">
                                        <i class="ri-delete-bin-5-fill remove"></i>
                                        <div class="col-md-6 mt-3">
                                            <label for="why_details_title" class="form-label">Title : <span class="text-danger">*</span></label>
                                            <input type="text" name="why_details_title[]" class="form-control" value="<?php echo htmlspecialcode_generator($why_details_title); ?>" id="why_details_title">
                                        </div>
                                        <div class="col-md-6 mt-3">
                                            <label for="why_details_sub_title" class="form-label">Icon : <span class="text-danger">*</span></label>
                                            <input type="text" name="why_details_sub_title[]" class="form-control" id="why_details_sub_title" value='<?php echo htmlspecialcode_generator($why_details_sub_title); ?>'>
                                        </div>
                                    </div>
                                </div>
                        <?php }
                        } ?>
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

<?php include '../../includes/footer.php';  ?>
<script>
    $(document).ready(function() {
        $('#homepage').validate({
            rules: {
                'why_title': "required",
                'why_description': "required"
            },
        });
    });
</script>
<script>
    $("#add_more_why").click(function() {
        if ($(".details-block.why").length < 8) {
            var i = $(".details-block.why:last-child").attr('data-id');
            if ($(".details-block.why:last-child").length > 0) {
                i = parseInt(i);
            } else {
                i = 0;
            }
            i = i + 1;
            var html = '<div class="details-block why col-xxl-12 col-md-12 mt-4" data-id="' + i + '"><div class="col-xxl-6 col-md-6 bg-light pt-3 pb-4 ps-2 pe-2"><i class="ri-delete-bin-5-fill remove"></i><div class="col-md-6 mt-3"><label for="why_details_title" class="form-label">Title : <span class="text-danger">*</span></label><input type="text" name="why_details_title[]" class="form-control" id="why_details_title"></div><div class="col-md-6 mt-3"><label for="why_details_sub_title" class="form-label">Icon : <span class="text-danger">*</span></label><input type="text" name="why_details_sub_title[]" class="form-control" id="why_details_sub_title"></div></div></div>';

            $(".details_banner_section.why .row.gy-4").append(html);
        }
    });
    $(document).on('click', ".details-block.why .remove", function() {
        $(this).parent().parent().remove();
    });
</script>