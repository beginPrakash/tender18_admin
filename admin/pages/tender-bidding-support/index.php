<?php include '../../includes/authentication.php';
?>
<?php $pages = 'pages'; ?>
<?php include '../../includes/header.php' ?>

<?php
if (isset($_POST['submit'])) {
    $banner_title = mysqli_real_escape_string($con, $_POST['banner_title']);
    $banner_description = mysqli_real_escape_string($con, $_POST['banner_description']);
    $hidden_banner_image = $_POST['hidden_banner_image'];

    $feature_title = mysqli_real_escape_string($con, $_POST['feature_title']);
    $hidden_feature_image = $_POST['hidden_feature_image'];

    $file = $_FILES['banner_image'];
    $filename = $file['name'];
    $filepath = $file['tmp_name'];
    $fileerror = $file['error'];
    if (!empty($filename)) {
        if ($fileerror == 0) {
            $string = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
            $files =  substr(str_shuffle($string), 0, 8);
            $temp = explode(".", $filename);
            $newfilename = time() . $files . '.' . end($temp);
            $destfile = '../../uploads/images/' . $newfilename;
            move_uploaded_file($filepath, $destfile);
        }
    }
    if (!empty($filename)) {
        $filevalue =  $newfilename;
    } else {
        $filevalue = $hidden_banner_image;
    }

    $file1 = $_FILES['feature_image'];
    $filename1 = $file1['name'];
    $filepath1 = $file1['tmp_name'];
    $fileerror1 = $file1['error'];
    if (!empty($filename1)) {
        if ($fileerror1 == 0) {
            $string1 = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
            $files1 =  substr(str_shuffle($string1), 0, 8);
            $temp1 = explode(".", $filename1);
            $newfilename1 = time() . $files1 . '.' . end($temp1);
            $destfile1 = '../../uploads/images/' . $newfilename1;
            move_uploaded_file($filepath1, $destfile1);
        }
    }
    if (!empty($filename1)) {
        $filevalue1 =  $newfilename1;
    } else {
        $filevalue1 = $hidden_feature_image;
    }

    $query_delete1 = mysqli_query($con, "DELETE FROM `tender_support_banner`");
    $q1 = "INSERT INTO tender_support_banner(`title`, `description`, `image`, `below_title`, `below_image`) VALUES ('$banner_title', '$banner_description', '$filevalue', '$feature_title', '$filevalue1')";
    $sql1 = mysqli_query($con, $q1);

    $other_get_title = mysqli_real_escape_string($con, $_POST['other_get_title']);
    $other_get_description = mysqli_real_escape_string($con, $_POST['other_get_description']);
    $other_get_subtitle = mysqli_real_escape_string($con, $_POST['other_get_subtitle']);

    $other_service_title = mysqli_real_escape_string($con, $_POST['other_service_title']);
    $other_service_description = mysqli_real_escape_string($con, $_POST['other_service_description']);

    $other_why_title = mysqli_real_escape_string($con, $_POST['other_why_title']);
    $other_why_description = mysqli_real_escape_string($con, $_POST['other_why_description']);

    $query_delete2 = mysqli_query($con, "DELETE FROM `tender_support_other`");
    $q2 = "INSERT INTO tender_support_other(`get_title`, `get_description`, `get_subtitle`, `service_title`, `service_description`, `why_title`, `why_description`) VALUES ('$other_get_title', '$other_get_description', '$other_get_subtitle', '$other_service_title', '$other_service_description', '$other_why_title', '$other_why_description')";
    $sql2 = mysqli_query($con, $q2);

    $query_delete3 = mysqli_query($con, "DELETE FROM `tender_support_experts`");
    $support_experts_title = $_POST['support_experts_title'];
    foreach ($support_experts_title as $key => $title) {
        if (!empty($title)) {
            $title = mysqli_real_escape_string($con, $title);
            $q = "INSERT INTO tender_support_experts(`title`) VALUES ('$title')";
            $sql = mysqli_query($con, $q);
        }
    }

    $query_delete4 = mysqli_query($con, "DELETE FROM `tender_support_features`");
    $feature_detail_title = $_POST['feature_detail_title'];
    foreach ($feature_detail_title as $key => $title) {
        if (!empty($title)) {
            $title = mysqli_real_escape_string($con, $title);
            $q = "INSERT INTO tender_support_features(`title`) VALUES ('$title')";
            $sql = mysqli_query($con, $q);
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
                        window.location.href='" . ADMIN_URL . "/pages/tender-bidding-support';
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
                        window.location.href='" . ADMIN_URL . "/pages/tender-bidding-support';
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
            <h4 class="mb-sm-0">Tender Bidding Support</h4>
        </div>
    </div>
</div>
<!-- end page title -->

<?php
//banner section
$banner_title = "";
$banner_description = "";
$banner_image = "";
$feature_title = "";
$feature_image = "";

$banner_data = mysqli_query($con, "SELECT * FROM `tender_support_banner`");
$banner_result = mysqli_num_rows($banner_data);
if ($banner_result == 1) {
    while ($row = mysqli_fetch_assoc($banner_data)) {
        $banner_title = $row['title'];
        $banner_description = $row['description'];
        $banner_image = $row['image'];
        $feature_title = $row['below_title'];
        $feature_image = $row['below_image'];
    }
}
$support_features_data = mysqli_query($con, "SELECT * FROM `tender_support_features`");
$support_features_result = mysqli_num_rows($support_features_data);

$support_experts_data = mysqli_query($con, "SELECT * FROM `tender_support_experts`");
$support_experts_result = mysqli_num_rows($support_experts_data);

//other section
$other_get_title = "";
$other_get_description = "";
$other_get_subtitle = "";
$other_service_title = "";
$other_service_description = "";
$other_why_title = "";
$other_title = "";
$other_why_description = "";

$other_data = mysqli_query($con, "SELECT * FROM `tender_support_other`");
$other_result = mysqli_num_rows($other_data);
if ($other_result == 1) {
    while ($row = mysqli_fetch_assoc($other_data)) {
        $other_get_title = $row['get_title'];
        $other_get_description = $row['get_description'];
        $other_get_subtitle = $row['get_subtitle'];
        $other_service_title = $row['service_title'];
        $other_service_description = $row['service_description'];
        $other_why_title = $row['why_title'];
        $other_why_description = $row['why_description'];
    }
}
?>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <form action="" method="post" <?php if ($banner_result == 1) {
                                                echo 'id="homepage"';
                                            } else {
                                                echo 'id="homepages"';
                                            } ?> enctype="multipart/form-data">
                <div class="card-header">
                    <h4 class="card-title mb-0">Banner Section</h4>
                </div>
                <div class="card-body">
                    <div class="row gy-4">
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="banner_title" class="form-label">Title : <span class="text-danger">*</span></label>
                                <input type="text" name="banner_title" class="form-control" value="<?php echo htmlspecialcode_generator($banner_title); ?>" id="banner_title">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="banner_description" class="form-label">Description : <span class="text-danger">*</span></label>
                                <textarea rows="7" name="banner_description" class="form-control" id="banner_description"><?php echo htmlspecialcode_generator($banner_description); ?></textarea>
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="banner_image" class="form-label">Banner Image: <?php if ($banner_result < 1) {
                                                                                                echo '<span class="text-danger">*</span>';
                                                                                            } ?></label>
                                <input class="form-control" type="file" name="banner_image" id="banner_image">
                                <input type="hidden" name="hidden_banner_image" value="<?php echo $banner_image; ?>">
                                <?php
                                if (!empty($banner_image)) {
                                    echo '<img src="../../uploads/images/' . $banner_image . '" class="img-thumbnail mt-2" width="100" height="100">';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-header">
                    <h4 class="card-title mb-0">Features Section</h4>
                </div>
                <div class="card-body">
                    <div class="row gy-4">
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="feature_title" class="form-label">Title : <span class="text-danger">*</span></label>
                                <input type="text" name="feature_title" class="form-control" value="<?php echo htmlspecialcode_generator($feature_title); ?>" id="feature_title">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="feature_image" class="form-label">Feature Image: <?php if ($banner_result < 1) {
                                                                                                    echo '<span class="text-danger">*</span>';
                                                                                                } ?></label>
                                <input class="form-control" type="file" name="feature_image" id="feature_image">
                                <input type="hidden" name="hidden_feature_image" value="<?php echo $feature_image; ?>">
                                <?php
                                if (!empty($feature_image)) {
                                    echo '<img src="../../uploads/images/' . $feature_image . '" class="img-thumbnail mt-2" width="100" height="100">';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-header">
                    <h4 class="card-title mb-0">Features</h4>
                </div>
                <div class="ps-3">
                    <button class="btn btn-success mt-3" id="add_more_features" type="button">Add more</button>
                </div>
                <div class="card-body details_banner_section features">
                    <div class="row gy-4">
                        <?php
                        if ($support_features_result > 0) {
                            while ($row = mysqli_fetch_assoc($support_features_data)) {
                                $feature_detail_title = $row['title'];
                        ?>
                                <div class="details-block features col-xxl-12 col-md-12 mt-4">
                                    <div class="col-xxl-6 col-md-6 bg-light pt-3 pb-4 ps-2 pe-2">
                                        <i class="ri-delete-bin-5-fill remove"></i>
                                        <div class="col-md-6 mt-3">
                                            <label for="feature_detail_title" class="form-label">Title : <span class="text-danger">*</span></label>
                                            <input type="text" name="feature_detail_title[]" class="form-control" value="<?php echo htmlspecialcode_generator($feature_detail_title); ?>" id="feature_detail_title">
                                        </div>
                                    </div>
                                </div>
                        <?php }
                        } ?>
                    </div>
                </div>

                <div class="card-header">
                    <h4 class="card-title mb-0">Get Section</h4>
                </div>
                <div class="card-body">
                    <div class="row gy-4">
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="other_get_title" class="form-label">Title : <span class="text-danger">*</span></label>
                                <input type="text" name="other_get_title" class="form-control" value="<?php echo htmlspecialcode_generator($other_get_title); ?>" id="other_get_title">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="other_get_description" class="form-label">Description : <span class="text-danger">*</span></label>
                                <textarea rows="5" name="other_get_description" class="form-control" id="other_get_description"><?php echo htmlspecialcode_generator($other_get_description); ?></textarea>
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="other_get_subtitle" class="form-label">Subtitle : <span class="text-danger">*</span></label>
                                <input type="text" name="other_get_subtitle" class="form-control" value="<?php echo htmlspecialcode_generator($other_get_subtitle); ?>" id="other_get_subtitle">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-header">
                    <h4 class="card-title mb-0">Cover Section</h4>
                </div>
                <div class="ps-3">
                    <button class="btn btn-success mt-3" id="add_more_covers" type="button">Add more</button>
                </div>
                <div class="card-body details_banner_section covers">
                    <div class="row gy-4">
                        <?php
                        if ($support_experts_result > 0) {
                            while ($row = mysqli_fetch_assoc($support_experts_data)) {
                                $support_experts_title = $row['title'];
                        ?>
                                <div class="details-block covers col-xxl-12 col-md-12 mt-4">
                                    <div class="col-xxl-6 col-md-6 bg-light pt-3 pb-4 ps-2 pe-2">
                                        <i class="ri-delete-bin-5-fill remove"></i>
                                        <div class="col-md-6 mt-3">
                                            <label for="support_experts_title" class="form-label">Title : <span class="text-danger">*</span></label>
                                            <input type="text" name="support_experts_title[]" class="form-control" value="<?php echo htmlspecialcode_generator($support_experts_title); ?>" id="support_experts_title">
                                        </div>
                                    </div>
                                </div>
                        <?php }
                        } ?>
                    </div>
                </div>

                <div class="card-header">
                    <h4 class="card-title mb-0">Userful Section</h4>
                </div>
                <div class="card-body">
                    <div class="row gy-4">
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="other_service_title" class="form-label">Title : <span class="text-danger">*</span></label>
                                <input type="text" name="other_service_title" class="form-control" value="<?php echo htmlspecialcode_generator($other_service_title); ?>" id="other_service_title">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="other_service_description" class="form-label">Description : <span class="text-danger">*</span></label>
                                <textarea name="other_service_description" rows="5" class="form-control" id="other_service_description"><?php echo htmlspecialcode_generator($other_service_description); ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-header">
                    <h4 class="card-title mb-0">Why Section</h4>
                </div>
                <div class="card-body">
                    <div class="row gy-4">
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="other_why_title" class="form-label">Title : <span class="text-danger">*</span></label>
                                <input type="text" name="other_why_title" class="form-control" value="<?php echo htmlspecialcode_generator($other_why_title); ?>" id="other_why_title">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="other_why_description" class="form-label">Description : <span class="text-danger">*</span></label>
                                <textarea rows="5" name="other_why_description" class="form-control" id="other_why_description"><?php echo htmlspecialcode_generator($other_why_description); ?></textarea>
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

<?php include '../../includes/footer.php';  ?>

<script>
    $(document).ready(function() {
        $('#homepages').validate({
            rules: {
                'banner_title': "required",
                'banner_description': "required",
                'banner_image': "required",
                'feature_title': "required",
                'feature_image': "required",
                'other_get_title': "required",
                'other_get_description': "required",
                'other_get_subtitle': "required",
                'other_service_title': "required",
                'other_service_description': "required",
                'other_why_title': "required",
                'other_why_description': "required"
            },
        });

        $('#homepage').validate({
            rules: {
                'banner_title': "required",
                'banner_description': "required",
                'feature_title': "required",
                'other_get_title': "required",
                'other_get_description': "required",
                'other_get_subtitle': "required",
                'other_service_title': "required",
                'other_service_description': "required",
                'other_why_title': "required",
                'other_why_description': "required"
            },
        });
    });
</script>

<script>
    $("#add_more_covers").click(function() {
        if ($(".details-block.covers").length < 100) {
            var html = '<div class="details-block covers col-xxl-12 col-md-12 mt-4"><div class="col-xxl-6 col-md-6 bg-light pt-3 pb-4 ps-2 pe-2"><i class="ri-delete-bin-5-fill remove"></i><div class="col-md-6 mt-3"><label for="support_experts_title" class="form-label">Title : <span class="text-danger">*</span></label><input type="text" name="support_experts_title[]" class="form-control" id="support_experts_title"></div></div></div>';

            $(".details_banner_section.covers .row.gy-4").append(html);
        }
    });
    $(document).on('click', ".details-block.covers .remove", function() {
        $(this).parent().parent().remove();
    });
</script>

<script>
    $("#add_more_features").click(function() {
        if ($(".details-block.features").length < 100) {
            var html = '<div class="details-block features col-xxl-12 col-md-12 mt-4"><div class="col-xxl-6 col-md-6 bg-light pt-3 pb-4 ps-2 pe-2"><i class="ri-delete-bin-5-fill remove"></i><div class="col-md-6 mt-3"><label for="feature_detail_title" class="form-label">Title : <span class="text-danger">*</span></label><input type="text" name="feature_detail_title[]" class="form-control" id="feature_detail_title"></div></div></div>';

            $(".details_banner_section.features .row.gy-4").append(html);
        }
    });
    $(document).on('click', ".details-block.features .remove", function() {
        $(this).parent().parent().remove();
    });
</script>