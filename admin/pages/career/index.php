<?php include '../../includes/authentication.php';
?>
<?php $pages = 'pages'; ?>
<?php include '../../includes/header.php' ?>

<?php
if (isset($_POST['submit'])) {

    $terms_title = $_POST['terms_title'];
    $about_title = $_POST['about_title'];
    $about_description = $_POST['about_description'];
    $work_title = str_replace('"', '\"', $_POST['work_title']);
    $work_description = str_replace('"', '\"', $_POST['work_description']);

    $query_delete1 = mysqli_query($con, "DELETE FROM `career_page`");
    $q1 = "INSERT INTO career_page(`title`,`about_title`,`about_description`,`work_title`,`work_description`) VALUES ('$terms_title','$about_title','$about_description','$work_title','$work_description')";
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
                        window.location.href='" . ADMIN_URL . "/pages/career';
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
                        window.location.href='" . ADMIN_URL . "/pages/career';
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
            <h4 class="mb-sm-0">Career</h4>
        </div>
    </div>
</div>
<!-- end page title -->

<?php
//terms section
$terms_title = "";
$work_title = "";
$work_description = "";
$about_title = "";
$about_description = "";

$terms_data = mysqli_query($con, "SELECT * FROM `career_page`");
$terms_result = mysqli_num_rows($terms_data);
if ($terms_result == 1) {
    while ($row = mysqli_fetch_assoc($terms_data)) {
        $terms_title = $row['title'];
        $work_title = $row['work_title'];
        $work_description = $row['work_description'];
        $about_title = $row['about_title'];
        $about_description = $row['about_description'];
    }
}
?>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <form action="" method="post" id="homepages" enctype="multipart/form-data">
                <div class="card-header">
                    <h4 class="card-title mb-0">Main Section</h4>
                </div>
                <div class="card-body">
                    <div class="row gy-4">
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="terms_title" class="form-label">Title : <span class="text-danger">*</span></label>
                                <input type="text" name="terms_title" class="form-control" value="<?php echo $terms_title; ?>" id="terms_title">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-header">
                    <h4 class="card-title mb-0">Work Section</h4>
                </div>
                <div class="card-body">
                    <div class="row gy-4">
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="work_title" class="form-label">Title : <span class="text-danger">*</span></label>
                                <input type="text" name="work_title" class="form-control" value="<?php echo $work_title; ?>" id="work_title">
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="work_description" class="form-label">Description : <span class="text-danger">*</span></label>
                                <textarea name="work_description" rows="5" class="form-control d-none" id="work_description"><?php echo $work_description; ?></textarea>
                                <div class="ckeditor-classic-total ckeditor-classic-work"><?php echo $work_description; ?></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-header">
                    <h4 class="card-title mb-0">About Section</h4>
                </div>
                <div class="card-body">
                    <div class="row gy-4">
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="about_title" class="form-label">Title : <span class="text-danger">*</span></label>
                                <input type="text" name="about_title" class="form-control" value="<?php echo $about_title; ?>" id="about_title">
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="about_description" class="form-label">Description : <span class="text-danger">*</span></label>
                                <textarea name="about_description" rows="5" class="form-control d-none" id="about_description"><?php echo $about_description; ?></textarea>
                                <div class="ckeditor-classic-total ckeditor-classic-about"><?php echo $about_description; ?></div>
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
                'terms_title': "required",
                'work_title': "required",
                'work_description': "required",
                'about_title': "required",
                'about_description': "required"
            },
        });
    });
</script>
<script>
    ClassicEditor.create(document.querySelector(".ckeditor-classic-work"))
        .then(function(c) {
            c.ui.view.editable.element.style.height = "200px";
        })
        .catch(function(c) {
            console.error(c);
        });
    ClassicEditor.create(document.querySelector(".ckeditor-classic-about"))
        .then(function(c) {
            c.ui.view.editable.element.style.height = "200px";
        })
        .catch(function(c) {
            console.error(c);
        });
</script>
<script>
    $('#homepages').submit(function(event) {
        $('.ckeditor-classic-total').each(function(i, obj) {
            var data = $(this).parent().find(".ck-editor .ck-editor__main .ck-editor__editable").html();
            if (data != "")
                $(this).parent().find('textarea').val(data);
        });
    });
</script>