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
if (isset($_GET['id']) && isset($_GET['unique_code'])) {
    if (empty($_GET['id'])) {
        echo "<script>
            window.location.href='" . ADMIN_URL . "blogs/index.php';
            </script>";
    }
}
?>
<?php
if (isset($_POST['submit'])) {
        $hidden_blog_image = $_POST['hidden_blog_image'];
        $blog_id = $_POST['blog_id']; 
        $title = mysqli_real_escape_string($con, $_POST['title']);
        $description = $_POST['description'];
        $file = $_FILES['blog_image'];
        $filename = $file['name'];
        $filepath = $file['tmp_name'];
        $fileerror = $file['error'];

        $con_desc = preg_replace('/(<a\s+[^>]*)(\w+)="([^"]*)"/', '$1$2=$3', $description);
        $con_desc =mysqli_real_escape_string($con, $con_desc);
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
            $filevalue = $hidden_blog_image;
        }  
        $q = "UPDATE `blogs` SET title='$title', description='$con_desc', blog_image='$filevalue' where id='$blog_id'";
        // var_dump($q);
        $sql = mysqli_query($con, $q);
        
        if ($sql) {
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
                         window.location.href='" . ADMIN_URL . "/blogs/index.php';
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
            <h4 class="mb-sm-0">Edit</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <!-- <div class="card-header">
                <h4 class="card-title mb-0">Change Password</h4>
            </div> -->
            <?php $fetch_blogs = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM `blogs` where id={$_GET['id']}"));
            
            ?>
            <form action="" method="post" id="blog_form" enctype="multipart/form-data">
                <input type="hidden" name="blog_id" value=<?php echo $_GET['id']; ?>>
                <div class="card-body">
                    <div class="row gy-4">

                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="title" class="form-label">Title : <span class="text-danger">*</span></label>
                                <input type="text" name="title" placeholder="Enter Title " class="form-control" id="title" value="<?php echo $fetch_blogs['title']; ?>">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="description" class="form-label">Description : <span class="text-danger">*</span></label>
                                <textarea rows="5" name="description" class="form-control d-none" id="description"><?php echo $fetch_blogs['description']; ?></textarea>
                                <div class="ckeditor-classic-total ckeditor-classic-job-profile"><?php echo $fetch_blogs['description']; ?></div>
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="blog_image" class="form-label">Blog Image:</label>
                                <input class="form-control" type="file" name="blog_image" id="blog_image">
                                <?php $blog_image = $fetch_blogs['blog_image']; ?>
                                <input type="hidden" name="hidden_blog_image" value="<?php echo $blog_image; ?>">
                                <?php
                                
                                if (!empty($blog_image)) {
                                    echo '<img src="../uploads/images/' . $blog_image . '" class="img-thumbnail mt-2" width="100" height="100">';
                                }
                                ?>
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
            },
            
        });

        ClassicEditor.create(document.querySelector(".ckeditor-classic-job-profile"))
        .then(function(c) {
            c.ui.view.editable.element.style.height = "200px";
        })
        .catch(function(c) {
            console.error(c);
        });

        $('#blog_form').submit(function(event) {
            $('.ckeditor-classic-total').each(function(i, obj) {
                var data = $(this).parent().find(".ck-editor .ck-editor__main .ck-editor__editable").html();
                if (data != "")
                    $(this).parent().find('textarea').val(data);
            });
        });
    });
    
</script>