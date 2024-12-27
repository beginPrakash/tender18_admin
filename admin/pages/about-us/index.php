<?php include '../../includes/authentication.php';
?>
<?php $pages = 'pages'; ?>
<?php include '../../includes/header.php' ?>
<?php
if (isset($_POST['submit'])) {
    $banner_main_title = mysqli_real_escape_string($con, $_POST['banner_main_title']);
    $banner_title = mysqli_real_escape_string($con, $_POST['banner_title']);
    $banner_description = mysqli_real_escape_string($con, $_POST['banner_description']);
    $hidden_banner_image = $_POST['hidden_banner_image'];

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

    $query_delete1 = mysqli_query($con, "DELETE FROM `about_us_banner`");
    $q1 = "INSERT INTO about_us_banner(`title`, `description`, `image`, `main_title`) VALUES ('$banner_title', '$banner_description', '$filevalue', '$banner_main_title')";
    $sql1 = mysqli_query($con, $q1);

    $why_title = mysqli_real_escape_string($con, $_POST['why_title']);
    $why_description = mysqli_real_escape_string($con, $_POST['why_description']);
    $hidden_why_image = $_POST['hidden_why_image'];

    $file1 = $_FILES['why_image'];
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
        $filevalue1 = $hidden_why_image;
    }

    $query_delete2 = mysqli_query($con, "DELETE FROM `about_us_why`");
    $q2 = "INSERT INTO about_us_why(`title`, `description`, `image`) VALUES ('$why_title', '$why_description', '$filevalue1')";
    $sql2 = mysqli_query($con, $q2);

    $query_delete3 = mysqli_query($con, "DELETE FROM `about_us_why_details`");
    $our_title = $_POST['our_title'];
    $our_description = $_POST['our_description'];
    foreach ($our_title as $key => $title) {
        if (!empty($title)) {
            $title = mysqli_real_escape_string($con, $title);
            $description = mysqli_real_escape_string($con, $our_description[$key]);
            $q = "INSERT INTO about_us_why_details(`title`, `description`) VALUES ('$title', '$description')";
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
                        window.location.href='" . ADMIN_URL . "/pages/about-us/index.php';
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
                        window.location.href='" . ADMIN_URL . "/pages/about-us/index.php';
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
            <h4 class="mb-sm-0">About Us</h4>
        </div>
    </div>
</div>
<!-- end page title -->

<?php
//banner section
$banner_main_title = "";
$banner_title = "";
$banner_description = "";
$banner_image = "";

$banner_data = mysqli_query($con, "SELECT * FROM `about_us_banner`");
$banner_result = mysqli_num_rows($banner_data);
if ($banner_result == 1) {
    while ($row = mysqli_fetch_assoc($banner_data)) {
        $banner_main_title = $row['main_title'];
        $banner_title = $row['title'];
        $banner_description = $row['description'];
        $banner_image = $row['image'];
    }
}

$why_title = "";
$why_description = "";
$why_image = "";

$why_data = mysqli_query($con, "SELECT * FROM `about_us_why`");
$why_result = mysqli_num_rows($why_data);
if ($why_result == 1) {
    while ($row = mysqli_fetch_assoc($why_data)) {
        $why_title = $row['title'];
        $why_image = $row['image'];
        $why_description = $row['description'];
    }
}

$why_details_data = mysqli_query($con, "SELECT * FROM `about_us_why_details`");
$why_details_result = mysqli_num_rows($why_details_data);
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
                                <label for="banner_description" class="form-label">Description : <span class="text-danger">*</span></label>
                                <textarea rows="7" name="banner_description" class="form-control d-none" id="banner_description"><?php echo htmlspecialcode_generator($banner_description); ?></textarea>
                                <div class="ckeditor-classic-one"><?php echo htmlspecialcode_generator($banner_description); ?></div>
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
                                <textarea rows="5" name="why_description" class="form-control" id="why_description"><?php echo htmlspecialcode_generator($why_description); ?></textarea>
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="why_image" class="form-label">Image: <?php if ($why_result < 1) {
                                                                                        echo '<span class="text-danger">*</span>';
                                                                                    } ?></label>
                                <input class="form-control" type="file" name="why_image" id="why_image">
                                <input type="hidden" name="hidden_why_image" value="<?php echo $why_image; ?>">
                                <?php
                                if (!empty($why_image)) {
                                    echo '<img src="../../uploads/images/' . $why_image . '" class="img-thumbnail mt-2" width="100" height="100">';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-header">
                    <h4 class="card-title mb-0">Our Section</h4>
                </div>
                <div class="ps-3">
                    <button class="btn btn-success mt-3" id="add_more_ours" type="button">Add more</button>
                </div>
                <div class="card-body details_banner_section ours">
                    <div class="row gy-4">
                        <?php
                        if ($why_details_result > 0) {
                            while ($row = mysqli_fetch_assoc($why_details_data)) {
                                $our_title = $row['title'];
                                $our_description = $row['description'];
                        ?>
                                <div class="details-block ours col-xxl-12 col-md-12 mt-4">
                                    <div class="col-xxl-6 col-md-6 bg-light pt-3 pb-4 ps-2 pe-2">
                                        <i class="ri-delete-bin-5-fill remove"></i>
                                        <div class="col-md-6 mt-3">
                                            <label for="our_title" class="form-label">Title : <span class="text-danger">*</span></label>
                                            <input type="text" name="our_title[]" class="form-control" value="<?php echo htmlspecialcode_generator($our_title); ?>" id="our_title">
                                        </div>
                                        <div class="col-md-6 mt-3">
                                            <label for="our_description" class="form-label">Description : <span class="text-danger">*</span></label>
                                            <textarea rows="5" name="our_description[]" class="form-control" id="our_description"><?php echo htmlspecialcode_generator($our_description); ?></textarea>
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
        $('#homepages').validate({
            rules: {
                'banner_title': "required",
                'banner_description': "required",
                'banner_image': "required",
                'banner_main_title': "required",
                'why_title': "required",
                'why_description': "required",
                'why_image': "required"
            },
        });

        $('#homepage').validate({
            rules: {
                'banner_title': "required",
                'banner_description': "required",
                'banner_main_title': "required",
                'why_title': "required",
                'why_description': "required"
            },
        });
    });
</script>

<script>
    $("#add_more_ours").click(function() {
        if ($(".details-block.ours").length < 100) {
            var html = '<div class="details-block ours col-xxl-12 col-md-12 mt-4"><div class="col-xxl-6 col-md-6 bg-light pt-3 pb-4 ps-2 pe-2"><i class="ri-delete-bin-5-fill remove"></i><div class="col-md-6 mt-3"><label for="our_title" class="form-label">Title : <span class="text-danger">*</span></label><input type="text" name="our_title[]" class="form-control" id="our_title"></div><div class="col-md-6 mt-3"><label for="our_description" class="form-label">Description : <span class="text-danger">*</span></label><textarea rows="5" name="our_description[]" class="form-control" id="our_description"></textarea></div></div></div>';

            $(".details_banner_section.ours .row.gy-4").append(html);
        }
    });
    $(document).on('click', ".details-block.ours .remove", function() {
        $(this).parent().parent().remove();
    });
</script>
<script>
    ClassicEditor.create(document.querySelector(".ckeditor-classic-one"))
        .then(function(c) {
            c.ui.view.editable.element.style.height = "200px";
        })
        .catch(function(c) {
            console.error(c);
        });
</script>
<script>
    $('#homepage, #homepages').submit(function(event) {
        $("#banner_description").val($("#banner_description ~ .ck-editor .ck-editor__main .ck-editor__editable").html());
    });
</script>