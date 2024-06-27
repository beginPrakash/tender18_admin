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
    $link = $_POST['link'];
    $title1 = $_POST['title1'];
    $description1 = $_POST['description1'];

    $query_delete = mysqli_query($con, "DELETE FROM `homepage_about`");

    $q = "INSERT INTO homepage_about(`title`, `description`, `link`, `title1`, `description1`) VALUES ('$title', '$description', '$link', '$title1', '$description1')";
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
$link = "";
$title1 = "";
$description1 = "";


$banner_data = mysqli_query($con, "SELECT * FROM `homepage_about`");
$banner_result = mysqli_num_rows($banner_data);
if ($banner_result == 1) {
    while ($row = mysqli_fetch_assoc($banner_data)) {
        $title = $row['title'];
        $description = $row['description'];
        $link = $row['link'];
        $title1 = $row['title1'];
        $description1 = $row['description1'];
    }
}
?>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <form action="" method="post" <?php if ($banner_result == 1) {
                                                echo 'id="homepage _service"';
                                            } else {
                                                echo 'id="homepage_service"';
                                            } ?> enctype="multipart/form-data">
                <div class="card-header">
                    <h4 class="card-title mb-0">About Us Section</h4>
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
                                <textarea rows="3" name="description" class="form-control mb-4 d-none" id="description"><?php echo $description; ?></textarea>
                                <div class="ckeditor-classic-one"><?php echo $description; ?></div>
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="link" class="form-label">Link : <span class="text-danger">*</span></label>
                                <input type="text" name="link" class="form-control" value="<?php echo $link; ?>" id="link">
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
                    <h4 class="card-title mb-0">About Tender18 Section</h4>
                </div>
                <div class="card-body">
                    <div class="row gy-4">
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="title1" class="form-label">Title : <span class="text-danger">*</span></label>
                                <input type="text" name="title1" class="form-control" value="<?php echo $title1; ?>" id="title1">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="description1" class="form-label">Description : <span class="text-danger">*</span></label>
                                <textarea rows="3" name="description1" class="form-control mb-4 d-none" id="description1"><?php echo $description1; ?></textarea>
                                <div class="ckeditor-classic-two"><?php echo $description1; ?></div>
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
                'description': "required",
                'title1': "required",
                'description1': "required"
            },
        });
    });
</script>
<script>
    $('#homepage_service').submit(function(event) {
        $("#description").val($("#description ~ .ck-editor .ck-editor__main .ck-editor__editable").html());
        $("#description1").val($("#description1 ~ .ck-editor .ck-editor__main .ck-editor__editable").html());
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
    ClassicEditor.create(document.querySelector(".ckeditor-classic-two"))
        .then(function(c) {
            c.ui.view.editable.element.style.height = "200px";
        })
        .catch(function(c) {
            console.error(c);
        });
</script>