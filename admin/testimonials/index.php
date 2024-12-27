<?php include '../includes/authentication.php';
?>
<?php $pages = 'testimonials'; ?>
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
    $details_title = $_POST['details_title'];
    $details_sub_title = $_POST['details_sub_title'];
    $details_description = $_POST['details_description'];

    $query_delete = mysqli_query($con, "DELETE FROM `testimonials`");

    foreach ($details_title as $key => $title) {
        if (!empty($title)) {
            $title = mysqli_real_escape_string($con, $title);
            $subtitle = mysqli_real_escape_string($con, $details_sub_title[$key]);
            $description = mysqli_real_escape_string($con, $details_description[$key]);
            $q = "INSERT INTO testimonials(`title`, `name`, `description`) VALUES ('$title', '$subtitle','$description')";
            // var_dump($q);
            $sql2 = mysqli_query($con, $q);
        }
    }

    if ($sql2) {
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
                        window.location.href='" . ADMIN_URL . "/testimonials/index.php';
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
                        window.location.href='" . ADMIN_URL . "/testimonials/index.php';
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
$details_title = "";
$details_sub_title = "";
$details_description = "";

$banner_details_data = mysqli_query($con, "SELECT * FROM `testimonials`");
$banner_details_result = mysqli_num_rows($banner_details_data);
?>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <form action="" method="post" id="homepage_service" enctype="multipart/form-data">
                <div class="card-header">
                    <h4 class="card-title mb-0">Testimonials Section</h4>
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
                                $details_sub_title = $row['name'];
                                $details_description = $row['description'];
                        ?>
                                <div class="details-block col-xxl-12 col-md-12 mt-4">
                                    <div class="col-xxl-6 col-md-6 bg-light pt-3 pb-4 ps-2 pe-2">
                                        <i class="ri-delete-bin-5-fill remove"></i>
                                        <div class="col-md-6 mt-3">
                                            <label for="details_title" class="form-label">Title : <span class="text-danger">*</span></label>
                                            <input type="text" name="details_title[]" class="form-control" value="<?php echo htmlspecialcode_generator($details_title); ?>" id="details_title">
                                        </div>
                                        <div class="col-md-6 mt-3">
                                            <label for="details_description" class="form-label">Description : <span class="text-danger">*</span></label>
                                            <textarea name="details_description[]" rows="5" class="form-control" id="details_description"><?php echo htmlspecialcode_generator($details_description); ?></textarea>
                                        </div>
                                        <div class="col-md-6 mt-3">
                                            <label for="details_sub_title" class="form-label">Name : <span class="text-danger">*</span></label>
                                            <input type="text" name="details_sub_title[]" class="form-control" id="details_sub_title" value='<?php echo htmlspecialcode_generator($details_sub_title); ?>'>
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
                                    <label for="details_description" class="form-label">Description : <span class="text-danger">*</span></label>
                                    <textarea name="details_description[]" rows="5" class="form-control" id="details_description"></textarea>
                                </div>
                                <div class="col-md-6 mt-3">
                                    <label for="details_sub_title" class="form-label">Sub Title : <span class="text-danger">*</span></label>
                                    <input type="text" name="details_sub_title[]" class="form-control" id="details_sub_title">
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
    $("#add_more").click(function() {
        if ($(".details-block").length < 100) {
            var i = $(".details-block:last-child").attr('data-id');
            if ($(".details-block:last-child").length > 0) {
                i = parseInt(i);
            } else {
                i = 0;
            }
            i = i + 1;
            var html = '<div class="details-block col-xxl-12 col-md-12 mt-4" data-id="' + i + '"><div class="col-xxl-6 col-md-6 bg-light pt-3 pb-4 ps-2 pe-2"><i class="ri-delete-bin-5-fill remove"></i><div class="col-md-6 mt-3"><label for="details_title" class="form-label">Title : <span class="text-danger">*</span></label><input type="text" name="details_title[]" class="form-control" id="details_title"></div><div class="col-md-6 mt-3"><label for="details_description" class="form-label">Description : <span class="text-danger">*</span></label><textarea name="details_description[]" rows="5" class="form-control" id="details_description"></textarea></div><div class="col-md-6 mt-3"><label for="details_sub_title" class="form-label">Name : <span class="text-danger">*</span></label><input type="text" name="details_sub_title[]" class="form-control" id="details_sub_title"></div></div></div>';

            $(".details_banner_section .row.gy-4").append(html);
        }
    });
    $(document).on('click', ".details-block .remove", function() {
        $(this).parent().parent().remove();
    });
</script>