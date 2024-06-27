<?php include '../includes/authentication.php';
?>
<?php $pages = 'career'; ?>
<?php include '../includes/header.php' ?>

<?php
if (!empty($_GET['id'])) {
    $id = $_GET['id'];
} else {
    echo "<script>window.location.href='" . ADMIN_URL . "/career';</script>";
    die();
}
?>

<?php
if (isset($_POST['submit'])) {
    $jobID = $_POST['jobID'];
    $title = $_POST['title'];
    $job_status = $_POST['job_status'];
    $description = $_POST['description'];
    $job_profile = $_POST['job_profile'];
    $experience = $_POST['experience'];
    $anual_package = $_POST['anual_package'];
    $location = $_POST['location'];

    $q1 = "UPDATE `career_posts` SET `title`='$title', `job_status`='$job_status', `description`='$description', `job_profile`='$job_profile', `experience`='$experience', `anual_package`='$anual_package', `location`='$location' WHERE `id`='$jobID'";

    // var_dump($q1);
    // die();
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
                        window.location.href='" . ADMIN_URL . "/career';
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
                        window.location.href='" . ADMIN_URL . "/career';
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
            <h4 class="mb-sm-0">Edit Job</h4>
        </div>
    </div>
</div>
<!-- end page title -->

<?php
$jobID = "";
$title = "";
$job_status = "";
$description = "";
$job_profile = "";
$experience = "";
$anual_package = "";
$location = "";

$banner_data = mysqli_query($con, "SELECT * FROM `career_posts` where id='" . $id . "'");
$banner_result = mysqli_num_rows($banner_data);
if ($banner_result == 1) {
    while ($row = mysqli_fetch_assoc($banner_data)) {
        $jobID = $row['id'];
        $title = $row['title'];
        $job_status = $row['job_status'];
        $description = $row['description'];
        $job_profile = $row['job_profile'];
        $experience = $row['experience'];
        $anual_package = $row['anual_package'];
        $location = $row['location'];
    }
}
?>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <form action="" method="post" id="homepage" enctype="multipart/form-data">
                <input type="hidden" name="jobID" value="<?php echo $jobID; ?>">
                <div class="card-header">
                    <h4 class="card-title mb-0">Job Details</h4>
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
                                <label for="job_status" class="form-label">Job Status : <span class="text-danger">*</span></label>
                                <input type="text" name="job_status" class="form-control" value="<?php echo $job_status; ?>" id="job_status">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="description" class="form-label">Description : <span class="text-danger">*</span></label>
                                <textarea rows="5" name="description" class="form-control" id="description"><?php echo $description; ?></textarea>
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="job_profile" class="form-label">Job Profile : <span class="text-danger">*</span></label>
                                <textarea rows="5" name="job_profile" class="form-control d-none" id="job_profile"><?php echo $job_profile; ?></textarea>
                                <div class="ckeditor-classic-total ckeditor-classic-job-profile"><?php echo $job_profile; ?></div>
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="experience" class="form-label">Experience : <span class="text-danger">*</span></label>
                                <input type="text" name="experience" class="form-control" value="<?php echo $experience; ?>" id="experience">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="anual_package" class="form-label">Annual Package : <span class="text-danger">*</span></label>
                                <input type="text" name="anual_package" class="form-control" value="<?php echo $anual_package; ?>" id="anual_package">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="location" class="form-label">Location : <span class="text-danger">*</span></label>
                                <input type="text" name="location" class="form-control" value="<?php echo $location; ?>" id="location">
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
                'title': "required",
                'job_status': "required",
                'description': "required",
                'job_profile': "required",
                'experience': "required",
                'anual_package': "required",
                'location': "required"
            },
        });
    });
</script>
<script>
    ClassicEditor.create(document.querySelector(".ckeditor-classic-job-profile"))
        .then(function(c) {
            c.ui.view.editable.element.style.height = "200px";
        })
        .catch(function(c) {
            console.error(c);
        });
</script>
<script>
    $('#homepage').submit(function(event) {
        $('.ckeditor-classic-total').each(function(i, obj) {
            var data = $(this).parent().find(".ck-editor .ck-editor__main .ck-editor__editable").html();
            if (data != "")
                $(this).parent().find('textarea').val(data);
        });
    });
</script>