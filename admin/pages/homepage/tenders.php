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

    $query_delete = mysqli_query($con, "DELETE FROM `homepage_tenders`");

    $q = "INSERT INTO homepage_tenders(`title`, `description`) VALUES ('$title', '$description')";
    $sql1 = mysqli_query($con, $q);

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


$banner_data = mysqli_query($con, "SELECT * FROM `homepage_tenders`");
$banner_result = mysqli_num_rows($banner_data);
if ($banner_result == 1) {
    while ($row = mysqli_fetch_assoc($banner_data)) {
        $title = $row['title'];
        $description = $row['description'];
    }
}
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
                    <h4 class="card-title mb-0">Live Tenders Section</h4>
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