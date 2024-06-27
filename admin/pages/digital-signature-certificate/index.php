<?php include '../../includes/authentication.php';
?>
<?php $pages = 'pages'; ?>
<?php include '../../includes/header.php' ?>

<?php
if (isset($_POST['submit'])) {
    $digital_cert_title = mysqli_real_escape_string($con, $_POST['digital_cert_title']);
    $digital_cert_description = mysqli_real_escape_string($con, $_POST['digital_cert_description']);
    $digital_cert_feature_title = mysqli_real_escape_string($con, $_POST['digital_cert_feature_title']);
    $hidden_digital_cert_feature_image = $_POST['hidden_digital_cert_feature_image'];
    $hidden_digital_cert_image = $_POST['hidden_digital_cert_image'];

    $file = $_FILES['digital_cert_image'];
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
        $filevalue = $hidden_digital_cert_image;
    }

    $file1 = $_FILES['digital_cert_feature_image'];
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
        $filevalue1 = $hidden_digital_cert_feature_image;
    }

    $query_delete1 = mysqli_query($con, "DELETE FROM `digital_cert_banner`");
    $q1 = "INSERT INTO digital_cert_banner(`title`, `description`, `image`, `feature_title`, `feature_image`) VALUES ('$digital_cert_title', '$digital_cert_description', '$filevalue', '$digital_cert_feature_title', '$filevalue1')";
    $sql1 = mysqli_query($con, $q1);

    $query_delete2 = mysqli_query($con, "DELETE FROM `digital_cert_feature`");
    $digital_feature_title = $_POST['digital_feature_title'];
    $hidden_digital_feature_image = $_POST['hidden_digital_feature_image'];
    $digital_feature_image = $_FILES['digital_feature_image'];
    foreach ($digital_feature_title as $key => $title) {
        $file = $digital_feature_image;
        $filename = $file['name'][$key];
        $filepath = $file['tmp_name'][$key];
        $fileerror = $file['error'][$key];
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
            if (!empty($hidden_digital_feature_image[$key])) {
                $filevalue = $hidden_digital_feature_image[$key];
            } else {
                $filevalue = "";
            }
        }
        if (!empty($title) && !empty($filevalue)) {
            $title = mysqli_real_escape_string($con, $title);
            $q = "INSERT INTO digital_cert_feature(`title`, `image`) VALUES ('$title', '$filevalue')";
            $sql = mysqli_query($con, $q);
        }
    }

    $digital_service_get_title = mysqli_real_escape_string($con, $_POST['digital_service_get_title']);
    $digital_service_get_description = mysqli_real_escape_string($con, $_POST['digital_service_get_description']);
    $digital_service_get_subtitle = mysqli_real_escape_string($con, $_POST['digital_service_get_subtitle']);
    $digital_service_service_title = mysqli_real_escape_string($con, $_POST['digital_service_service_title']);
    $digital_service_service_description = mysqli_real_escape_string($con, $_POST['digital_service_service_description']);
    $digital_service_why_title = mysqli_real_escape_string($con, $_POST['digital_service_why_title']);
    $digital_service_why_description = mysqli_real_escape_string($con, $_POST['digital_service_why_description']);

    $query_delete3 = mysqli_query($con, "DELETE FROM `digital_cert_service`");
    $q2 = "INSERT INTO digital_cert_service(`get_title`, `get_subtitle`, `get_description`, `service_title`, `service_description`, `why_title`, `why_description`) VALUES ('$digital_service_get_title', '$digital_service_get_subtitle', '$digital_service_get_description', '$digital_service_service_title', '$digital_service_service_description', '$digital_service_why_title', '$digital_service_why_description')";
    $sql2 = mysqli_query($con, $q2);

    $query_delete4 = mysqli_query($con, "DELETE FROM `digital_cert_will_get`");
    $digital_will_get_title = $_POST['digital_will_get_title'];
    foreach ($digital_will_get_title as $key => $title) {
        if (!empty($title)) {
            $title = mysqli_real_escape_string($con, $title);
            $q = "INSERT INTO digital_cert_will_get(`title`) VALUES ('$title')";
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
                        window.location.href='" . ADMIN_URL . "/pages/digital-signature-certificate';
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
                        window.location.href='" . ADMIN_URL . "/pages/digital-signature-certificate';
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
            <h4 class="mb-sm-0">Digital Signature Certificate</h4>
        </div>
    </div>
</div>
<!-- end page title -->

<?php
//digital_cert_banner section
$digital_cert_title = "";
$digital_cert_description = "";
$digital_cert_image = "";
$digital_cert_feature_title = "";
$digital_cert_feature_image = "";

$digital_cert_data = mysqli_query($con, "SELECT * FROM `digital_cert_banner`");
$digital_cert_result = mysqli_num_rows($digital_cert_data);
if ($digital_cert_result == 1) {
    while ($row = mysqli_fetch_assoc($digital_cert_data)) {
        $digital_cert_title = $row['title'];
        $digital_cert_description = $row['description'];
        $digital_cert_image = $row['image'];
        $digital_cert_feature_title = $row['feature_title'];
        $digital_cert_feature_image = $row['feature_image'];
    }
}

//digital_cert_service section
$digital_service_get_title = "";
$digital_service_get_subtitle = "";
$digital_service_get_description = "";
$digital_service_service_title = "";
$digital_service_service_description = "";
$digital_service_why_title = "";
$digital_service_why_description = "";

$digital_service_data = mysqli_query($con, "SELECT * FROM `digital_cert_service`");
$digital_service_result = mysqli_num_rows($digital_service_data);
if ($digital_service_result == 1) {
    while ($row = mysqli_fetch_assoc($digital_service_data)) {
        $digital_service_get_title = $row['get_title'];
        $digital_service_get_subtitle = $row['get_subtitle'];
        $digital_service_get_description = $row['get_description'];
        $digital_service_service_title = $row['service_title'];
        $digital_service_service_description = $row['service_description'];
        $digital_service_why_title = $row['why_title'];
        $digital_service_why_description = $row['why_description'];
    }
}

$digital_feature_data = mysqli_query($con, "SELECT * FROM `digital_cert_feature`");
$digital_feature_result = mysqli_num_rows($digital_feature_data);

$digital_will_get_data = mysqli_query($con, "SELECT * FROM `digital_cert_will_get`");
$digital_will_get_result = mysqli_num_rows($digital_will_get_data);
?>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <form action="" method="post" <?php if ($digital_cert_result == 1) {
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
                                <label for="digital_cert_title" class="form-label">Title : <span class="text-danger">*</span></label>
                                <input type="text" name="digital_cert_title" class="form-control" value="<?php echo htmlspecialcode_generator($digital_cert_title); ?>" id="digital_cert_title">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="digital_cert_description" class="form-label">Description : <span class="text-danger">*</span></label>
                                <textarea name="digital_cert_description" rows="5" class="form-control" id="digital_cert_description"><?php echo htmlspecialcode_generator($digital_cert_description); ?></textarea>
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="digital_cert_image" class="form-label">Image: <?php if ($digital_cert_result < 1) {
                                                                                                echo '<span class="text-danger">*</span>';
                                                                                            } ?></label>
                                <input class="form-control" type="file" name="digital_cert_image" id="digital_cert_image">
                                <input type="hidden" name="hidden_digital_cert_image" value="<?php echo $digital_cert_image; ?>">
                                <?php
                                if (!empty($digital_cert_image)) {
                                    echo '<img src="../../uploads/images/' . $digital_cert_image . '" class="img-thumbnail mt-2" width="100" height="100">';
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
                                <label for="digital_cert_feature_title" class="form-label">Title : <span class="text-danger">*</span></label>
                                <input type="text" name="digital_cert_feature_title" class="form-control" value="<?php echo htmlspecialcode_generator($digital_cert_feature_title); ?>" id="digital_cert_feature_title">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="digital_cert_feature_image" class="form-label">Image: <?php if ($digital_cert_result < 1) {
                                                                                                        echo '<span class="text-danger">*</span>';
                                                                                                    } ?></label>
                                <input class="form-control" type="file" name="digital_cert_feature_image" id="digital_cert_feature_image">
                                <input type="hidden" name="hidden_digital_cert_feature_image" value="<?php echo $digital_cert_feature_image; ?>">
                                <?php
                                if (!empty($digital_cert_feature_image)) {
                                    echo '<img src="../../uploads/images/' . $digital_cert_feature_image . '" class="img-thumbnail mt-2" width="100" height="100">';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-header">
                    <h4 class="card-title mb-0">Features Certificate</h4>
                </div>
                <div class="ps-3">
                    <button class="btn btn-success mt-3" id="add_features_cert" type="button">Add more</button>
                </div>
                <div class="card-body details_banner_section features_cert">
                    <div class="row gy-4">
                        <?php
                        if ($digital_feature_result > 0) {
                            while ($row = mysqli_fetch_assoc($digital_feature_data)) {
                                $digital_feature_title = $row['title'];
                                $digital_feature_image = $row['image'];
                        ?>
                                <div class="details-block features_cert col-xxl-12 col-md-12 mt-4">
                                    <div class="col-xxl-6 col-md-6 bg-light pt-3 pb-4 ps-2 pe-2">
                                        <i class="ri-delete-bin-5-fill remove"></i>
                                        <div class="col-md-6 mt-3">
                                            <label for="digital_feature_title" class="form-label">Title : <span class="text-danger">*</span></label>
                                            <input type="text" name="digital_feature_title[]" class="form-control" value="<?php echo htmlspecialcode_generator($digital_feature_title); ?>" id="digital_feature_title">
                                        </div>
                                        <div class="col-xxl-12 col-md-12">
                                            <div class="col-md-6 mt-3">
                                                <label for="digital_feature_image" class="form-label">Image: <?php if ($digital_feature_result < 1) {
                                                                                                                    echo '<span class="text-danger">*</span>';
                                                                                                                } ?></label>
                                                <input class="form-control" type="file" name="digital_feature_image[]" id="digital_feature_image">
                                                <input type="hidden" name="hidden_digital_feature_image[]" value="<?php echo $digital_feature_image; ?>">
                                                <?php
                                                if (!empty($digital_feature_image)) {
                                                    echo '<img src="../../uploads/images/' . $digital_feature_image . '" class="img-thumbnail mt-2" width="100" height="100">';
                                                }
                                                ?>
                                            </div>
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
                                <label for="digital_service_get_title" class="form-label">Title : <span class="text-danger">*</span></label>
                                <input type="text" name="digital_service_get_title" class="form-control" value="<?php echo htmlspecialcode_generator($digital_service_get_title); ?>" id="digital_service_get_title">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="digital_service_get_description" class="form-label">Description : <span class="text-danger">*</span></label>
                                <textarea name="digital_service_get_description" rows="3" class="form-control" id="digital_service_get_description"><?php echo htmlspecialcode_generator($digital_service_get_description); ?></textarea>
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="digital_service_get_subtitle" class="form-label">Point Title : <span class="text-danger">*</span></label>
                                <input type="text" name="digital_service_get_subtitle" class="form-control" value="<?php echo htmlspecialcode_generator($digital_service_get_subtitle); ?>" id="digital_service_get_subtitle">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-header">
                    <h4 class="card-title mb-0">You Will Get</h4>
                </div>
                <div class="ps-3">
                    <button class="btn btn-success mt-3" id="add_you_will_get" type="button">Add more</button>
                </div>
                <div class="card-body details_banner_section you_will_get">
                    <div class="row gy-4">
                        <?php
                        if ($digital_will_get_result > 0) {
                            while ($row = mysqli_fetch_assoc($digital_will_get_data)) {
                                $digital_will_get_title = $row['title'];
                        ?>
                                <div class="details-block you_will_get col-xxl-12 col-md-12 mt-4">
                                    <div class="col-xxl-6 col-md-6 bg-light pt-3 pb-4 ps-2 pe-2">
                                        <i class="ri-delete-bin-5-fill remove"></i>
                                        <div class="col-md-6 mt-3">
                                            <label for="digital_will_get_title" class="form-label">Title : <span class="text-danger">*</span></label>
                                            <input type="text" name="digital_will_get_title[]" class="form-control" value="<?php echo htmlspecialcode_generator($digital_will_get_title); ?>" id="digital_will_get_title">
                                        </div>
                                    </div>
                                </div>
                        <?php }
                        } ?>
                    </div>
                </div>

                <div class="card-header">
                    <h4 class="card-title mb-0">Useful Section</h4>
                </div>
                <div class="card-body">
                    <div class="row gy-4">
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="digital_service_service_title" class="form-label">Title : <span class="text-danger">*</span></label>
                                <input type="text" name="digital_service_service_title" class="form-control" value="<?php echo htmlspecialcode_generator($digital_service_service_title); ?>" id="digital_service_service_title">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="digital_service_service_description" class="form-label">Description : <span class="text-danger">*</span></label>
                                <textarea name="digital_service_service_description" rows="3" class="form-control" id="digital_service_service_description"><?php echo htmlspecialcode_generator($digital_service_service_description); ?></textarea>
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
                                <label for="digital_service_why_title" class="form-label">Title : <span class="text-danger">*</span></label>
                                <input type="text" name="digital_service_why_title" class="form-control" value="<?php echo htmlspecialcode_generator($digital_service_why_title); ?>" id="digital_service_why_title">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="digital_service_why_description" class="form-label">Description : <span class="text-danger">*</span></label>
                                <textarea name="digital_service_why_description" rows="3" class="form-control" id="digital_service_why_description"><?php echo htmlspecialcode_generator($digital_service_why_description); ?></textarea>
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
                'digital_cert_title': "required",
                'digital_cert_description': "required",
                'digital_cert_image': "required",
                'digital_cert_feature_title': "required",
                'digital_cert_feature_image': "required",
                'digital_service_get_title': "required",
                'digital_service_get_description': "required",
                'digital_service_get_subtitle': "required",
                'digital_service_service_title': "required",
                'digital_service_service_description': "required",
                'digital_service_why_title': "required",
                'digital_service_why_description': "required"
            },
        });

        $('#homepage').validate({
            rules: {
                'digital_cert_title': "required",
                'digital_cert_description': "required",
                'digital_cert_feature_title': "required",
                'digital_service_get_title': "required",
                'digital_service_get_description': "required",
                'digital_service_get_subtitle': "required",
                'digital_service_service_title': "required",
                'digital_service_service_description': "required",
                'digital_service_why_title': "required",
                'digital_service_why_description': "required"
            },
        });
    });
</script>

<script>
    $("#add_features_cert").click(function() {
        if ($(".details-block.features_cert").length < 100) {
            var html = '<div class="details-block features_cert col-xxl-12 col-md-12 mt-4"><div class="col-xxl-6 col-md-6 bg-light pt-3 pb-4 ps-2 pe-2"><i class="ri-delete-bin-5-fill remove"></i><div class="col-md-6 mt-3"><label for="digital_feature_title" class="form-label">Title : <span class="text-danger">*</span></label><input type="text" name="digital_feature_title[]" class="form-control" id="digital_feature_title"></div><div class="col-xxl-12 mt-3 col-md-12"><div class="col-md-6"><label for="digital_feature_image" class="form-label">Image: <span class="text-danger">*</span></label><input class="form-control" type="file" name="digital_feature_image[]" id="digital_feature_image"></div></div></div></div>';

            $(".details_banner_section.features_cert .row.gy-4").append(html);
        }
    });
    $(document).on('click', ".details-block.features_cert .remove", function() {
        $(this).parent().parent().remove();
    });
</script>

<script>
    $("#add_you_will_get").click(function() {
        if ($(".details-block.you_will_get").length < 100) {
            var html = '<div class="details-block you_will_get col-xxl-12 col-md-12 mt-4"><div class="col-xxl-6 col-md-6 bg-light pt-3 pb-4 ps-2 pe-2"><i class="ri-delete-bin-5-fill remove"></i><div class="col-md-6 mt-3"><label for="digital_will_get_title" class="form-label">Title : <span class="text-danger">*</span></label><input type="text" name="digital_will_get_title[]" class="form-control" id="digital_will_get_title"></div></div></div>';

            $(".details_banner_section.you_will_get .row.gy-4").append(html);
        }
    });
    $(document).on('click', ".details-block.you_will_get .remove", function() {
        $(this).parent().parent().remove();
    });
</script>