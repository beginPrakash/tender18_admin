<?php include '../../includes/authentication.php';
?>
<?php $pages = 'pages'; ?>
<?php include '../../includes/header.php' ?>

<?php
if (isset($_POST['submit'])) {
    $main_title = mysqli_real_escape_string($con, $_POST['main_title']);
    $banner_title = mysqli_real_escape_string($con, $_POST['banner_title']);
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

    $query_delete1 = mysqli_query($con, "DELETE FROM `live_tenders_banner`");
    $q1 = "INSERT INTO live_tenders_banner(`title`, `image`, `main_title`) VALUES ('$banner_title', '$filevalue', '$main_title')";
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
                        window.location.href='" . ADMIN_URL . "/pages/live-tenders/index.php';
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
                        window.location.href='" . ADMIN_URL . "/pages/live-tenders/index.php';
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
            <h4 class="mb-sm-0">Live Tenders</h4>
        </div>
    </div>
</div>
<!-- end page title -->

<?php
//banner section
$main_title = "";
$banner_title = "";
$banner_image = "";

$banner_data = mysqli_query($con, "SELECT * FROM `live_tenders_banner`");
$banner_result = mysqli_num_rows($banner_data);
if ($banner_result == 1) {
    while ($row = mysqli_fetch_assoc($banner_data)) {
        $main_title = $row['main_title'];
        $banner_title = $row['title'];
        $banner_image = $row['image'];
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
                    <h4 class="card-title mb-0">Main Section</h4>
                </div>
                <div class="card-body">
                    <div class="row gy-4">
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="main_title" class="form-label">Title : <span class="text-danger">*</span></label>
                                <input type="text" name="main_title" class="form-control" value="<?php echo htmlspecialcode_generator($main_title); ?>" id="main_title">
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
                'banner_image': "required",
                'main_title': "required"
            },
        });

        $('#homepage').validate({
            rules: {
                'banner_title': "required",
                'main_title': "required"
            },
        });
    });
</script>