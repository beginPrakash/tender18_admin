<?php include '../includes/authentication.php';
?>
<?php $pages = 'pages'; ?>
<?php include '../includes/header.php' ?>
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
    $title = $_POST['title'];
    $description = $_POST['description'];

    $details_title = $_POST['details_title'];
    $details_sub_title = $_POST['details_sub_title'];
    $details_link = $_POST['details_link'];
    $hidden_details_image = $_POST['hidden_details_image'];

    $query_delete = mysqli_query($con, "DELETE FROM `homepage_services`");
    $query_delete = mysqli_query($con, "DELETE FROM `homepage_services_details`");

    $q = "INSERT INTO homepage_services(`title`, `description`) VALUES ('$title', '$description')";
    $sql1 = mysqli_query($con, $q);
    // print_r($details_title);

    foreach ($details_title as $key => $title) {
        if (!empty($title)) {
            $file = $_FILES['details_image'];
            $filename = $file['name'][$key];
            $filepath = $file['tmp_name'][$key];
            $fileerror = $file['error'][$key];

            if (!empty($filename)) {
                if ($fileerror == 0) {
                    $destfile = '../images/' . $filename;
                    move_uploaded_file($filepath, $destfile);
                }
            }

            if (!empty($filename)) {
                $filevalue1 =  $filename;
            } else {
                $filevalue1 = $hidden_details_image[$key];
            }

            $q = "INSERT INTO homepage_services_details(`title`, `description` ,`image` ,`link`) VALUES ('$title', '$details_sub_title[$key]', '$filevalue1', '$details_link[$key]')";
            $sql2 = mysqli_query($con, $q);
        }
    }

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
                         //window.location.reload();
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
                         //window.location.reload();
                         document.querySelector('.msg_box').remove();
                     }, 3000);
                 
             </script>";
}

?>

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Homepage</h4>
        </div>
    </div>
</div>
<!-- end page title -->

<?php
$title = "";
$description = "";
$details_title = "";
$details_sub_title = "";
$details_link = "";
$details_image = "";


$banner_data = mysqli_query($con, "SELECT * FROM `homepage_services`");
$banner_result = mysqli_num_rows($banner_data);
if ($banner_result == 1) {
    while ($row = mysqli_fetch_assoc($banner_data)) {
        $title = $row['title'];
        $description = $row['description'];
    }
}
$banner_details_data = mysqli_query($con, "SELECT * FROM `homepage_services_details`");
$banner_details_result = mysqli_num_rows($banner_details_data);
?>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <form action="" method="post" <?php if ($banner_result == 1) {
                                                echo 'id="homepage_service"';
                                            } else {
                                                echo 'id="homepage_service"';
                                            } ?> enctype="multipart/form-data">
                <div class="card-header">
                    <h4 class="card-title mb-0">Our Services Section</h4>
                </div>
                <div class="card-body">
                    <div class="row gy-4">
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="title" class="form-label">Title : <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control" value="<?php echo $title; ?>" id="title">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="description" class="form-label">Description : <span class="text-danger">*</span></label>
                                <textarea rows="3" name="description" class="form-control" id="description"><?php echo $description; ?></textarea>
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
                    <h4 class="card-title mb-0">Our Services Details Section</h4>
                </div>
                <div class="ps-3">
                    <button class="btn btn-success mt-3" id="add_more" type="button">Add more</button>
                </div>
                <div class="card-body details_banner_section">
                    <div class="row gy-4">
                        <?php
                        if ($banner_details_result > 0) {
                            while ($row = mysqli_fetch_assoc($banner_details_data)) {
                                $details_title = $row['title'];
                                $details_sub_title = $row['description'];
                                $details_link = $row['link'];
                                $details_image = $row['image'];
                        ?>
                                <div class="details-block col-xxl-12 col-md-12 mt-4">
                                    <div class="col-xxl-6 col-md-6 bg-light pt-3 pb-4 ps-2 pe-2">
                                        <i class="ri-delete-bin-5-fill remove"></i>
                                        <div class="col-md-6 mt-3">
                                            <label for="details_title" class="form-label">Title : <span class="text-danger">*</span></label>
                                            <input type="text" name="details_title[]" class="form-control" value="<?php echo $details_title; ?>" id="details_title">
                                        </div>
                                        <div class="col-md-6 mt-3">
                                            <label for="details_sub_title" class="form-label">Sub Title : <span class="text-danger">*</span></label>
                                            <textarea rows="3" name="details_sub_title[]" class="form-control" id="details_sub_title"><?php echo $details_sub_title; ?></textarea>
                                        </div>
                                        <div class="col-md-6 mt-3">
                                            <label for="details_link" class="form-label">Link : <span class="text-danger">*</span></label>
                                            <input type="text" name="details_link[]" class="form-control" value="<?php echo $details_link; ?>" id="details_link">
                                        </div>
                                        <div class="col-md-6 mt-3">
                                            <label for="details_image" class="form-label">Image: <?php if ($banner_details_result < 1) {
                                                                                                        echo '<span class="text-danger">*</span>';
                                                                                                    } ?></label>
                                            <input class="form-control" type="file" name="details_image[]" id="details_image">
                                            <input type="hidden" name="hidden_details_image[]" value="<?php echo $details_image; ?>">
                                            <?php
                                            if (!empty($details_image)) {
                                                echo '<img src="../images/' . $details_image . '" class="img-thumbnail mt-2" width="100" height="100">';
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                        <?php }
                        } ?>
                        <!-- <div class="details-block col-xxl-12 col-md-12 mt-4" data-id="1">
                            <div class="col-xxl-6 col-md-6 bg-light pt-3 pb-4 ps-2 pe-2">
                                <i class="ri-delete-bin-5-fill remove"></i>
                                <div class="col-md-6 mt-3">
                                    <label for="details_title" class="form-label">Title : <span class="text-danger">*</span></label>
                                    <input type="text" name="details_title[]" class="form-control" id="details_title">
                                </div>
                                <div class="col-md-6 mt-3">
                                    <label for="details_sub_title" class="form-label">Sub Title : <span class="text-danger">*</span></label>
                                    <textarea rows="3" name="details_sub_title[]" class="form-control" id="details_sub_title"></textarea>
                                </div>
                                <div class="col-md-6 mt-3">
                                    <label for="details_link" class="form-label">Link : <span class="text-danger">*</span></label>
                                    <input type="text" name="details_link[]" class="form-control" id="details_link">
                                </div>
                                <div class="col-md-6 mt-3">
                                    <label for="details_image" class="form-label">Image: <span class="text-danger">*</span></label>
                                    <input class="form-control" type="file" name="details_image[]" id="details_image">
                                </div>
                            </div>
                        </div> -->
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

<?php include '../includes/footer.php';  ?>
<script>
    $(document).ready(function() {
        $('#homepage_service').validate({
            rules: {
                'title': "required",
                'description': "required"
            },
        });
    });
</script>
<script>
    $("#add_more").click(function() {
        if ($(".details-block").length < 4) {
            var i = $(".details-block:last-child").attr('data-id');
            if ($(".details-block:last-child").length > 0) {
                i = parseInt(i);
            } else {
                i = 0;
            }
            i = i + 1;
            var html = '<div class="details-block col-xxl-12 col-md-12 mt-4" data-id="' + i + '"><div class="col-xxl-6 col-md-6 bg-light pt-3 pb-4 ps-2 pe-2"><i class="ri-delete-bin-5-fill remove"></i><div class="col-md-6 mt-3"><label for="details_title" class="form-label">Title : <span class="text-danger">*</span></label><input type="text" name="details_title[]" class="form-control" id="details_title"></div><div class="col-md-6 mt-3"><label for="details_sub_title" class="form-label">Sub Title : <span class="text-danger">*</span></label><textarea rows="3" name="details_sub_title[]" class="form-control" id="details_sub_title"></textarea></div><div class="col-md-6 mt-3"><label for="details_link" class="form-label">Link : <span class="text-danger">*</span></label><input type="text" name="details_link[]" class="form-control" id="details_link"></div><div class="col-md-6 mt-3"><label for="details_image" class="form-label">Image: <span class="text-danger">*</span></label><input class="form-control" type="file" name="details_image[]" id="details_image"></div></div></div>';

            $(".details_banner_section .row.gy-4").append(html);
        }
    });
    $(document).on('click', ".details-block .remove", function() {
        $(this).parent().parent().remove();
    });
</script>