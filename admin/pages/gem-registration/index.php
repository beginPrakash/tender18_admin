<?php include '../../includes/authentication.php';
?>
<?php $pages = 'pages'; ?>
<?php include '../../includes/header.php' ?>

<?php
if (isset($_POST['submit'])) {
    $banner_main_title = mysqli_real_escape_string($con, $_POST['banner_main_title']);
    $banner_title = mysqli_real_escape_string($con, $_POST['banner_title']);
    $banner_subtitle = mysqli_real_escape_string($con, $_POST['banner_subtitle']);
    $banner_description = mysqli_real_escape_string($con, $_POST['banner_description']);
    $banner_get_title = mysqli_real_escape_string($con, $_POST['banner_get_title']);
    $banner_description = mysqli_real_escape_string($con, $banner_description);

    $query_delete1 = mysqli_query($con, "DELETE FROM `gem_reg_banner_section`");
    $q1 = "INSERT INTO gem_reg_banner_section(`main_title`, `title`, `subtitle`, `description`, `get_title`) VALUES ('$banner_main_title', '$banner_title', '$banner_subtitle', '$banner_description', '$banner_get_title')";
    $sql1 = mysqli_query($con, $q1);

    $query_delete2 = mysqli_query($con, "DELETE FROM `gem_reg_get_section`");
    $get_title = $_POST['get_title'];
    $hidden_get_image = $_POST['hidden_get_image'];
    foreach ($get_title as $key => $title) {
        if (!empty($title)) {
            $title = mysqli_real_escape_string($con, $title);
            $file = $_FILES['get_image'];
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
                $filevalue1 =  $newfilename;
            } else {
                $filevalue1 = $hidden_get_image[$key];
            }

            $q2 = "INSERT INTO gem_reg_get_section(`title`, `image`) VALUES ('$title', '$filevalue1')";
            $sql2 = mysqli_query($con, $q2);
        }
    }

    $elevate_title = mysqli_real_escape_string($con, $_POST['elevate_title']);
    $elevate_description = mysqli_real_escape_string($con, $_POST['elevate_description']);
    $elevate_image_description = mysqli_real_escape_string($con, $_POST['elevate_image_description']);
    $elevate_image_title = mysqli_real_escape_string($con, $_POST['elevate_image_title']);
    $hidden_elevate_image = $_POST['hidden_elevate_image'];

    $file = $_FILES['elevate_image'];
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
        $filevalue = $hidden_elevate_image;
    }

    $query_delete3 = mysqli_query($con, "DELETE FROM `gem_reg_elevate_section`");
    $q1 = "INSERT INTO gem_reg_elevate_section(`title`, `description`, `image`, `image_title`, `image_description`) VALUES ('$elevate_title', '$elevate_description', '$filevalue', '$elevate_image_title', '$elevate_image_description')";
    $sql1 = mysqli_query($con, $q1);

    $looking_title = mysqli_real_escape_string($con, $_POST['looking_title']);
    $looking_description = mysqli_real_escape_string($con, $_POST['looking_description']);
    $hidden_looking_image = $_POST['hidden_looking_image'];

    $file = $_FILES['looking_image'];
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
        $filevalue = $hidden_looking_image;
    }

    $query_delete4 = mysqli_query($con, "DELETE FROM `gem_reg_looking_section`");
    $q1 = "INSERT INTO gem_reg_looking_section(`title`, `description`, `image`) VALUES ('$looking_title', '$looking_description', '$filevalue')";
    $sql1 = mysqli_query($con, $q1);

    $cons_title = mysqli_real_escape_string($con, $_POST['cons_title']);
    $cons_subtitle = mysqli_real_escape_string($con, $_POST['cons_subtitle']);
    $cons_description = mysqli_real_escape_string($con, $_POST['cons_description']);
    $cons_description = mysqli_real_escape_string($con, $cons_description);

    $query_delete5 = mysqli_query($con, "DELETE FROM `gem_reg_consultants_section`");
    $q1 = "INSERT INTO gem_reg_consultants_section(`title`, `description`, `subtitle`) VALUES ('$cons_title', '$cons_description', '$cons_subtitle')";
    $sql1 = mysqli_query($con, $q1);

    $faq_title = $_POST['faq_title'];
    $faq_description = $_POST['faq_description'];

    $query_delete6 = mysqli_query($con, "DELETE FROM `gem_reg_faq_section`");
    foreach ($faq_title as $key => $title) {
        if (!empty($title)) {
            $title = mysqli_real_escape_string($con, $title);
            $desc = mysqli_real_escape_string($con, $faq_description[$key]);
            $q = "INSERT INTO gem_reg_faq_section(`title`, `description`) VALUES ('$title', '$desc')";
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
                        window.location.href='" . ADMIN_URL . "/pages/gem-registration/index.php';
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
                        window.location.href='" . ADMIN_URL . "/pages/gem-registration/index.php';
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
            <h4 class="mb-sm-0">GEM Registration</h4>
        </div>
    </div>
</div>
<!-- end page title -->

<?php
//banner section
$banner_main_title = "";
$banner_title = "";
$banner_subtitle = "";
$banner_description = "";
$banner_get_title = "";

$banner_data = mysqli_query($con, "SELECT * FROM `gem_reg_banner_section`");
$banner_result = mysqli_num_rows($banner_data);
if ($banner_result == 1) {
    while ($row = mysqli_fetch_assoc($banner_data)) {
        $banner_main_title = $row['main_title'];
        $banner_title = $row['title'];
        $banner_subtitle = $row['subtitle'];
        $banner_description = $row['description'];
        $banner_get_title = $row['get_title'];
    }
}

//consultants section
$cons_title = "";
$cons_subtitle = "";
$cons_description = "";

$cons_data = mysqli_query($con, "SELECT * FROM `gem_reg_consultants_section`");
$cons_result = mysqli_num_rows($cons_data);
if ($cons_result == 1) {
    while ($row = mysqli_fetch_assoc($cons_data)) {
        $cons_title = $row['title'];
        $cons_subtitle = $row['subtitle'];
        $cons_description = $row['description'];
    }
}

//elevate section
$elevate_title = "";
$elevate_description = "";
$elevate_image = "";
$elevate_image_title = "";
$elevate_image_description = "";

$elevate_data = mysqli_query($con, "SELECT * FROM `gem_reg_elevate_section`");
$elevate_result = mysqli_num_rows($elevate_data);
if ($elevate_result == 1) {
    while ($row = mysqli_fetch_assoc($elevate_data)) {
        $elevate_title = $row['title'];
        $elevate_description = $row['description'];
        $elevate_image = $row['image'];
        $elevate_image_title = $row['image_title'];
        $elevate_image_description = $row['image_description'];
    }
}

//faq section
$faq_title = "";
$faq_description = "";

$faq_data = mysqli_query($con, "SELECT * FROM `gem_reg_faq_section`");
$faq_result = mysqli_num_rows($faq_data);

//looking section
$looking_title = "";
$looking_image = "";
$looking_description = "";

$looking_data = mysqli_query($con, "SELECT * FROM `gem_reg_looking_section`");
$looking_result = mysqli_num_rows($looking_data);
if ($looking_result == 1) {
    while ($row = mysqli_fetch_assoc($looking_data)) {
        $looking_title = $row['title'];
        $looking_image = $row['image'];
        $looking_description = $row['description'];
    }
}

//get section
$get_title = "";
$get_image = "";

$get_data = mysqli_query($con, "SELECT * FROM `gem_reg_get_section`");
$get_result = mysqli_num_rows($get_data);
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
                                <label for="banner_main_title" class="form-label">Main Title : <span class="text-danger">*</span></label>
                                <input type="text" name="banner_main_title" class="form-control" value="<?php echo htmlspecialcode_generator($banner_main_title); ?>" id="banner_main_title">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="banner_title" class="form-label">Title : <span class="text-danger">*</span></label>
                                <input type="text" name="banner_title" class="form-control" value="<?php echo htmlspecialcode_generator($banner_title); ?>" id="banner_title">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="banner_subtitle" class="form-label">Subtitle : <span class="text-danger">*</span></label>
                                <input type="text" name="banner_subtitle" class="form-control" value="<?php echo htmlspecialcode_generator($banner_subtitle); ?>" id="banner_subtitle">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="banner_description" class="form-label">Description : <span class="text-danger">*</span></label>
                                <textarea name="banner_description" rows="5" class="form-control d-none" id="banner_description"><?php echo htmlspecialcode_generator($banner_description); ?></textarea>
                                <div class="ckeditor-classic-total ckeditor-classic-banner-description"><?php echo htmlspecialcode_generator($banner_description); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-header">
                    <h4 class="card-title mb-0">Get GEM Registration Section</h4>
                </div>
                <div class="card-body">
                    <div class="row gy-4">
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="banner_get_title" class="form-label">Title : <span class="text-danger">*</span></label>
                                <input type="text" name="banner_get_title" class="form-control" value="<?php echo htmlspecialcode_generator($banner_get_title); ?>" id="banner_get_title">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="ps-3">
                    <button class="btn btn-success mt-3" id="add_more_banner" type="button">Add more</button>
                </div>
                <div class="card-body details_banner_section banner">
                    <div class="row gy-4">
                        <?php
                        if ($get_result > 0) {
                            while ($row = mysqli_fetch_assoc($get_data)) {
                                $get_title = $row['title'];
                                $get_image = $row['image'];
                        ?>
                                <div class="details-block banner banner col-xxl-12 col-md-12 mt-4">
                                    <div class="col-xxl-6 col-md-6 bg-light pt-3 pb-4 ps-2 pe-2">
                                        <i class="ri-delete-bin-5-fill remove"></i>
                                        <div class="col-md-6 mt-3">
                                            <label for="get_title" class="form-label">Title : <span class="text-danger">*</span></label>
                                            <input type="text" name="get_title[]" class="form-control" value="<?php echo htmlspecialcode_generator($get_title); ?>" id="get_title">
                                        </div>
                                        <div class="col-md-6 mt-3">
                                            <label for="get_image" class="form-label">Image: </label>
                                            <input class="form-control" type="file" name="get_image[]" id="get_image">
                                            <input type="hidden" name="hidden_get_image[]" value="<?php echo $get_image; ?>">
                                            <?php
                                            if (!empty($get_image)) {
                                                echo '<img src="../../uploads/images/' . $get_image . '" class="img-thumbnail mt-2" width="100" height="100">';
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                        <?php }
                        } ?>
                    </div>
                </div>
                <div class="card-header">
                    <h4 class="card-title mb-0">Elevate Section</h4>
                </div>
                <div class="card-body">
                    <div class="row gy-4">
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="elevate_title" class="form-label">Title : <span class="text-danger">*</span></label>
                                <input type="text" name="elevate_title" class="form-control" value="<?php echo htmlspecialcode_generator($elevate_title); ?>" id="elevate_title">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="elevate_description" class="form-label">Description : <span class="text-danger">*</span></label>
                                <textarea name="elevate_description" rows="5" class="form-control" id="elevate_description"><?php echo htmlspecialcode_generator($elevate_description); ?></textarea>
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="elevate_image_title" class="form-label">Image Title : <span class="text-danger">*</span></label>
                                <input type="text" name="elevate_image_title" class="form-control" value="<?php echo htmlspecialcode_generator($elevate_image_title); ?>" id="elevate_image_title">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="elevate_image_description" class="form-label">Image Description : <span class="text-danger">*</span></label>
                                <textarea name="elevate_image_description" rows="5" class="form-control d-none" id="elevate_image_description"><?php echo htmlspecialcode_generator($elevate_image_description); ?></textarea>
                                <div class="ckeditor-classic-total ckeditor-classic-elevate-description"><?php echo htmlspecialcode_generator($elevate_image_description); ?></div>
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="elevate_image" class="form-label">Image: <?php if ($banner_result < 1) {
                                                                                            echo '<span class="text-danger">*</span>';
                                                                                        } ?></label>
                                <input class="form-control" type="file" name="elevate_image" id="elevate_image">
                                <input type="hidden" name="hidden_elevate_image" value="<?php echo $elevate_image; ?>">
                                <?php
                                if (!empty($elevate_image)) {
                                    echo '<img src="../../uploads/images/' . $elevate_image . '" class="img-thumbnail mt-2" width="100" height="100">';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-header">
                    <h4 class="card-title mb-0">Looking Section</h4>
                </div>
                <div class="card-body">
                    <div class="row gy-4">
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="looking_title" class="form-label">Title : <span class="text-danger">*</span></label>
                                <input type="text" name="looking_title" class="form-control" value="<?php echo htmlspecialcode_generator($looking_title); ?>" id="looking_title">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="looking_description" class="form-label">Description : <span class="text-danger">*</span></label>
                                <textarea name="looking_description" rows="5" class="form-control d-none" id="looking_description"><?php echo htmlspecialcode_generator($looking_description); ?></textarea>
                                <div class="ckeditor-classic-total ckeditor-classic-looking-description"><?php echo htmlspecialcode_generator($looking_description); ?></div>
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="looking_image" class="form-label">Image: <?php if ($banner_result < 1) {
                                                                                            echo '<span class="text-danger">*</span>';
                                                                                        } ?></label>
                                <input class="form-control" type="file" name="looking_image" id="looking_image">
                                <input type="hidden" name="hidden_looking_image" value="<?php echo $looking_image; ?>">
                                <?php
                                if (!empty($looking_image)) {
                                    echo '<img src="../../uploads/images/' . $looking_image . '" class="img-thumbnail mt-2" width="100" height="100">';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-header">
                    <h4 class="card-title mb-0">Consultants Section</h4>
                </div>
                <div class="card-body">
                    <div class="row gy-4">
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="cons_title" class="form-label">Title : <span class="text-danger">*</span></label>
                                <input type="text" name="cons_title" class="form-control" value="<?php echo htmlspecialcode_generator($cons_title); ?>" id="cons_title">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="cons_subtitle" class="form-label">Subtitle : <span class="text-danger">*</span></label>
                                <input type="text" name="cons_subtitle" class="form-control" value="<?php echo htmlspecialcode_generator($cons_subtitle); ?>" id="cons_subtitle">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="cons_description" class="form-label">Description : <span class="text-danger">*</span></label>
                                <textarea name="cons_description" rows="5" class="form-control d-none" id="cons_description"><?php echo htmlspecialcode_generator($cons_description); ?></textarea>
                                <div class="ckeditor-classic-total ckeditor-classic-consultants-description"><?php echo htmlspecialcode_generator($cons_description); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-header">
                    <h4 class="card-title mb-0">FAQ</h4>
                </div>
                <div class="ps-3">
                    <button class="btn btn-success mt-3" id="add_terms_details" type="button">Add more</button>
                </div>
                <div class="card-body details_banner_section terms_details">
                    <div class="row gy-4">
                        <?php
                        if ($faq_result > 0) {
                            $count = 1;
                            while ($row = mysqli_fetch_assoc($faq_data)) {
                                $faq_title = $row['title'];
                                $faq_description = $row['description'];
                        ?>
                                <div class="details-block terms_details col-xxl-12 col-md-12 mt-4" data-id="<?php echo $count; ?>">
                                    <div class="col-xxl-6 col-md-6 bg-light pt-3 pb-4 ps-2 pe-2">
                                        <i class="ri-delete-bin-5-fill remove"></i>
                                        <div class="col-md-6 mt-3">
                                            <label for="faq_title" class="form-label">Title : <span class="text-danger">*</span></label>
                                            <input type="text" name="faq_title[]" class="form-control" value="<?php echo htmlspecialcode_generator($faq_title); ?>" id="faq_title">
                                        </div>
                                        <div class="col-md-6 mt-3">
                                            <label for="faq_description" class="form-label">Description : <span class="text-danger">*</span></label>
                                            <textarea name="faq_description[]" rows="5" class="form-control d-none" id="faq_description"><?php echo htmlspecialcode_generator($faq_description); ?></textarea>
                                            <div class="ckeditor-classic-total ckeditor-classic-<?php echo $count; ?>"><?php echo htmlspecialcode_generator($faq_description); ?></div>
                                        </div>
                                    </div>
                                </div>
                        <?php $count++;
                            }
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
        $('#homepages').validate({
            rules: {
                'banner_main_title': "required",
                'banner_title': "required",
                'banner_subtitle': "required",
                'banner_description': "required",
                'banner_get_title': "required",
                'elevate_title': "required",
                'elevate_description': "required",
                'elevate_image_title': "required",
                'elevate_image_description': "required",
                'elevate_image': "required",
                'looking_title': "required",
                'looking_image': "required",
                'looking_description': "required",
                'cons_title': "required",
                'cons_subtitle': "required",
                'cons_description': "required",
            },
        });

        $('#homepage').validate({
            rules: {
                'banner_main_title': "required",
                'banner_title': "required",
                'banner_subtitle': "required",
                'banner_description': "required",
                'banner_get_title': "required",
                'elevate_title': "required",
                'elevate_description': "required",
                'elevate_image_title': "required",
                'elevate_image_description': "required",
                'looking_title': "required",
                'looking_description': "required",
                'cons_title': "required",
                'cons_subtitle': "required",
                'cons_description': "required",
            },
        });
    });
</script>

<script>
    ClassicEditor.create(document.querySelector(".ckeditor-classic-banner-description"))
        .then(function(c) {
            c.ui.view.editable.element.style.height = "200px";
        })
        .catch(function(c) {
            console.error(c);
        });
    ClassicEditor.create(document.querySelector(".ckeditor-classic-elevate-description"))
        .then(function(c) {
            c.ui.view.editable.element.style.height = "200px";
        })
        .catch(function(c) {
            console.error(c);
        });
    ClassicEditor.create(document.querySelector(".ckeditor-classic-looking-description"))
        .then(function(c) {
            c.ui.view.editable.element.style.height = "200px";
        })
        .catch(function(c) {
            console.error(c);
        });
    ClassicEditor.create(document.querySelector(".ckeditor-classic-consultants-description"))
        .then(function(c) {
            c.ui.view.editable.element.style.height = "200px";
        })
        .catch(function(c) {
            console.error(c);
        });
</script>

<script>
    $("#add_more_banner").click(function() {
        if ($(".details-block.banner").length < 5) {
            var i = $(".details-block.banner:last-child").attr('data-id');
            if ($(".details-block.banner:last-child").length > 0) {
                i = parseInt(i);
            } else {
                i = 0;
            }
            i = i + 1;
            var html = '<div class="details-block banner col-xxl-12 col-md-12 mt-4" data-id="' + i + '"><div class="col-xxl-6 col-md-6 bg-light pt-3 pb-4 ps-2 pe-2"><i class="ri-delete-bin-5-fill remove"></i><div class="col-md-6 mt-3"><label for="get_title" class="form-label">Title : <span class="text-danger">*</span></label><input type="text" name="get_title[]" class="form-control" id="get_title"></div><div class="col-md-6 mt-3"><label for="get_image" class="form-label">Image: <span class="text-danger">*</span></label><input class="form-control" type="file" name="get_image[]" id="get_image"></div></div></div>';

            $(".details_banner_section.banner .row.gy-4").append(html);
        }
    });
    $(document).on('click', ".details-block.banner .remove", function() {
        $(this).parent().parent().remove();
    });
</script>

<script>
    $('#homepages, #homepage').submit(function(event) {
        $('.ckeditor-classic-total').each(function(i, obj) {
            var data = $(this).parent().find(".ck-editor .ck-editor__main .ck-editor__editable").html();
            if (data != "")
                $(this).parent().find('textarea').val(data);
        });
    });
</script>

<script>
    $("#add_terms_details").click(function() {
        if ($(".details-block.terms_details").length < 100) {
            var i = $(".details-block.terms_details:last-child").attr('data-id');
            if ($(".details-block.terms_details:last-child").length > 0) {
                i = parseInt(i);
            } else {
                i = 0;
            }
            i = i + 1;
            var html = '<div class="details-block terms_details col-xxl-12 col-md-12 mt-4" data-id="' + i + '"><div class="col-xxl-6 col-md-6 bg-light pt-3 pb-4 ps-2 pe-2"><i class="ri-delete-bin-5-fill remove"></i><div class="col-md-6 mt-3"><label for="faq_title" class="form-label">Title : <span class="text-danger">*</span></label><input type="text" name="faq_title[]" class="form-control" id="faq_title"></div><div class="col-md-6 mt-3"><label for="faq_description" class="form-label">Description : <span class="text-danger">*</span></label><textarea name="faq_description[]" class="form-control d-none" rows="5" id="faq_description"></textarea><div class="ckeditor-classic-total ckeditor-classic-' + i + '"></div></div></div></div>';

            $(".details_banner_section.terms_details .row.gy-4").append(html);

            ClassicEditor.create(document.querySelector(".ckeditor-classic-" + i))
                .then(function(c) {
                    c.ui.view.editable.element.style.height = "200px";
                })
                .catch(function(c) {
                    console.error(c);
                });
        }
    });
    $(document).on('click', ".details-block.terms_details .remove", function() {
        $(this).parent().parent().remove();
    });
</script>

<script>
    $('.details-block.terms_details').each(function(i, obj) {
        var count = $(this).attr("data-id");
        ClassicEditor.create(document.querySelector(".ckeditor-classic-" + count))
            .then(function(c) {
                c.ui.view.editable.element.style.height = "200px";
            })
            .catch(function(c) {
                console.error(c);
            });
    });
</script>