<?php

include "../includes/authentication.php";
?>
<?php $pages = 'blogs'; ?>
<?php include '../includes/header.php'; ?>
<?php // include '../includes/connection.php';

?>
<?php
$blogs_per = _get_user_perby_role($_SESSION['user_id'],'blogs',$con);

if($_SESSION['role']!='admin' && $_SESSION['role']!='employee'){ 
    // echo "not admin ------>" . $_SESSION['role'];
    echo "<script>
            window.location.href='../index.php';
            </script>";
}elseif($_SESSION['role']=='employee' && $blogs_per!=1){ 
    // echo "not admin ------>" . $_SESSION['role'];
    echo "<script>
            window.location.href='../index.php';
            </script>";
}
?>
<?php
if (isset($_POST['submit'])) {
    $title = mysqli_real_escape_string($con, $_POST['title']);
    $description = mysqli_real_escape_string($con, $_POST['description']);
    $file = $_FILES['blog_image'];
    $filename = $file['name'];
    $filepath = $file['tmp_name'];
    $fileerror = $file['error'];

    if (!empty($filename)) {
        if ($fileerror == 0) {
            $string = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
            $files =  substr(str_shuffle($string), 0, 8);
            $temp = explode(".", $filename);
            $newfilename = time() . $files . '.' . end($temp);
            $destfile = '../uploads/images/' . $newfilename;
            move_uploaded_file($filepath, $destfile);
        }
    }

    if (!empty($filename)) {
        $filevalue =  $newfilename;
    } else {
        $filevalue = '';
    }
    $created_by = $_SESSION['user_id']; 
        $q = "INSERT INTO blogs(`title`, `description`, `blog_image`, `created_by`) VALUES ('$title', '$description', '$filevalue',$created_by)";

        $sql = mysqli_query($con, $q);

        if ($sql) {
            $_SESSION['success'] = 'Blog created successfully.';
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
                        window.location.href='" . ADMIN_URL . "/blogs';
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

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Blogs</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <!-- <div class="card-header">
                <h4 class="card-title mb-0">Change Password</h4>
            </div> -->
            <form action="" method="post" id="blog_form" enctype="multipart/form-data">
                <div class="card-body">
                    <div class="row gy-4">
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="title" class="form-label">Title : <span class="text-danger">*</span></label>
                                <input type="text" name="title" placeholder="Enter Title " class="form-control" id="title">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="description" class="form-label">Description : <span class="text-danger">*</span></label>
                                <textarea name="description" placeholder="Enter Description" class="form-control" id="description"></textarea>
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="blog_image" class="form-label">Blog Image: <span class="text-danger">*</span></label>
                                <input class="form-control" type="file" name="blog_image" id="blog_image">
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="text-start">
                                <button type="submit" class="btn btn-primary" name="submit">Submit</button>
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
        $('#blog_form').validate({
            rules: {
                'title': "required",
                'description': "required",
                'blog_image': "required",
            },
            
        });
});
</script>