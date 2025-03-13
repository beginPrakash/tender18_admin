<?php

include "../../includes/authentication.php";
?>
<?php $pages = 'city_content_individual'; ?>
<?php include '../../includes/header.php'; ?>
<?php // include '../includes/connection.php';

?>
<?php

if($_SESSION['role']!='admin' && $_SESSION['role']!='employee'){ 
    // echo "not admin ------>" . $_SESSION['role'];
    echo "<script>
            window.location.href='../index.php';
            </script>";
}
if (isset($_GET['id']) && isset($_GET['unique_code'])) {
    if (empty($_GET['id'])) {
        echo "<script>
            window.location.href='" . ADMIN_URL . "meta-content/city-content-individual/index.php';
            </script>";
    }
}
?>
<?php
if (isset($_POST['submit'])) {
    $city_id = $_POST['city_id']; 
    $title = mysqli_real_escape_string($con, $_POST['title']);
    $description = mysqli_real_escape_string($con, $_POST['description']);
    $content = mysqli_real_escape_string($con, $_POST['content']);
    $keywords = mysqli_real_escape_string($con, $_POST['keywords']);
    $h1 = mysqli_real_escape_string($con, $_POST['h1']);

    $select = mysqli_query($con, "SELECT * FROM `city_meta_content_individual` WHERE  city_id='$city_id'");
    $result_data = mysqli_num_rows($select);
    $pass = mysqli_fetch_assoc($select);
    if ($result_data == 1) {
        $q1 = "UPDATE `city_meta_content_individual` SET `title`='$title', `description`='$description', `keywords`='$keywords', `h1`='$h1', `content`='$content' WHERE `city_id`=$city_id";
    }else{
        $q1 = "INSERT INTO city_meta_content_individual(`title`, `description`, `keywords`, `h1`, `content`, `city_id`) VALUES ('$title', '$description', '$keywords','$h1','$content',$city_id)";
    }
    
        // var_dump($q);
        $sql = mysqli_query($con, $q1);
        
        if ($sql) {
            $_SESSION['success'] = 'Data Saved successfully.';
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
                         window.location.href='" . ADMIN_URL . "/meta-content/city-content-individual/edit.php?id=".$_POST['city_id']."';
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
            <?php 
            $title = "";

            $content = "";
            
            $description = "";
            
            $h1 = "";
            
            $keywords = "";

            $city_name = "";
            
            
            $header_data = mysqli_query($con, "SELECT * FROM `city_meta_content_individual` where city_id={$_GET['id']} ");
            
            $header_result = mysqli_num_rows($header_data);
            
            if ($header_result == 1) {
            
                while ($row = mysqli_fetch_assoc($header_data)) {
                    $title = $row['title'];
                    $description = $row['description'];
                    $content = $row['content'];
                    $keywords = $row['keywords'];
                    $h1 = $row['h1'];
            
                }
            
            }else{

            $header_data = mysqli_query($con, "SELECT * FROM `city_meta_content`");

            $header_result = mysqli_num_rows($header_data);

            if ($header_result == 1) {

                while ($row = mysqli_fetch_assoc($header_data)) {
                    $title = $row['title'];
                    $description = $row['description'];
                    $content = $row['content'];
                    $keywords = $row['keywords'];
                    $h1 = $row['h1'];

                }

            }

            }

            $select = mysqli_query($con, "SELECT * FROM `tender_zipcodes` WHERE  `id`={$_GET['id']}");
            $result_data = mysqli_num_rows($select);
            $pass = mysqli_fetch_assoc($select);
            if ($result_data == 1) {
                $city_name = $pass['city'];
            }
            
            ?>
            <form action="" method="post" id="city_form" enctype="multipart/form-data">
                <input type="hidden" name="city_id" value=<?php echo $_GET['id']; ?>>
                <div class="card-body">
                    <div class="row gy-4">
                    <h3><?php echo ucfirst($city_name); ?></h3>
                    <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="title" class="form-label">Title : <span class="text-danger">*</span></label>
                                <input type="text" name="title" placeholder="Enter Title" class="form-control" id="title" value="<?php echo $title; ?>">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="description" class="form-label">Meta Description : <span class="text-danger">*</span></label>
                                <textarea rows="5" name="description" class="form-control" id="description"><?php echo $description; ?></textarea>
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="keywords" class="form-label">Meta Keywords : <span class="text-danger">*</span></label>
                                <textarea rows="3" name="keywords" class="form-control" id="keywords"><?php echo $keywords; ?></textarea>
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="h1" class="form-label">H1 : <span class="text-danger">*</span></label>
                                <textarea rows="3" name="h1" class="form-control" id="h1"><?php echo $h1; ?></textarea>
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="content" class="form-label">Content : <span class="text-danger">*</span></label>
                                <textarea rows="5" name="content" class="form-control d-none" id="content"><?php echo $content; ?></textarea>
                                <div class="ckeditor-classic-total ckeditor-classic-job-profile"><?php echo $content; ?></div>
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
<?php include "../../includes/footer.php" ?>
<script>
    $(document).ready(function() {
        $('#city_form').validate({
            rules: {
                'title': "required",
                'description': "required",
                'h1': "required",
                'content': "required",
            },
            
        });

        ClassicEditor.create(document.querySelector(".ckeditor-classic-job-profile"))
        .then(function(c) {
            c.ui.view.editable.element.style.height = "200px";
        })
        .catch(function(c) {
            console.error(c);
        });

        $('#city_form').submit(function(event) {
            $('.ckeditor-classic-total').each(function(i, obj) {
                var data = $(this).parent().find(".ck-editor .ck-editor__main .ck-editor__editable").html();
                if (data != "")
                    $(this).parent().find('textarea').val(data);
            });
        });
    });
    
</script>