<?php

include "../includes/authentication.php";
?>
<?php $pages = 'states'; ?>
<?php include '../includes/header.php'; ?>
<?php // include '../includes/connection.php';

?>
<?php
$states_per = _get_user_perby_role($_SESSION['user_id'],'states',$con);

if($_SESSION['role']!='admin' && $_SESSION['role']!='employee'){ 
    // echo "not admin ------>" . $_SESSION['role'];
    echo "<script>
            window.location.href='../index.php';
            </script>";
}elseif($_SESSION['role']=='employee' && $states_per!=1){ 
    // echo "not admin ------>" . $_SESSION['role'];
    echo "<script>
            window.location.href='../index.php';
            </script>";
}
if (isset($_GET['id']) && isset($_GET['unique_code'])) {
    if (empty($_GET['id'])) {
        echo "<script>
            window.location.href='" . ADMIN_URL . "states/index.php';
            </script>";
    }
}
?>
<?php
if (isset($_POST['submit'])) {
        $state_id = $_POST['state_id']; 
        $title = mysqli_real_escape_string($con, $_POST['title']);
        $name = mysqli_real_escape_string($con, $_POST['name']);
        $description = mysqli_real_escape_string($con, $_POST['description']);
        $content = mysqli_real_escape_string($con, $_POST['content']);
        $keywords = mysqli_real_escape_string($con, $_POST['keywords']);
        $h1 = mysqli_real_escape_string($con, $_POST['h1']);
        $state_code = mysqli_real_escape_string($con, $_POST['state_code']);
 
        $q = "UPDATE `states` SET title='$title',name='$name', description='$description',content='$content',keywords='$keywords',h1='$h1',state_code='$state_code' where id='$state_id'";
        // var_dump($q);
        $sql = mysqli_query($con, $q);
        
        if ($sql) {
            $_SESSION['success'] = 'Data Updated successfully.';
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
                         window.location.href='" . ADMIN_URL . "/states/index.php';
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
            <?php $fetch_states = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM `states` where id={$_GET['id']}"));
            
            ?>
            <form action="" method="post" id="state_form" enctype="multipart/form-data">
                <input type="hidden" name="state_id" value=<?php echo $_GET['id']; ?>>
                <div class="card-body">
                    <div class="row gy-4">

                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Name : <span class="text-danger">*</span></label>
                                <input type="text" name="name" placeholder="Enter Name " class="form-control" id="name" value="<?php echo $fetch_states['name']; ?>">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="state_code" class="form-label">State Code : <span class="text-danger">*</span></label>
                                <input type="text" name="state_code" placeholder="Enter State Code " class="form-control" id="state_code" value="<?php echo $fetch_states['state_code']; ?>">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="title" class="form-label">Title : <span class="text-danger">*</span></label>
                                <input type="text" name="title" placeholder="Enter Title" class="form-control" id="title" value="<?php echo $fetch_states['title']; ?>">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="description" class="form-label">Meta Description : <span class="text-danger">*</span></label>
                                <textarea rows="5" name="description" class="form-control" id="description"><?php echo $fetch_states['description']; ?></textarea>
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="keywords" class="form-label">Meta Keywords : <span class="text-danger">*</span></label>
                                <textarea rows="3" name="keywords" class="form-control" id="keywords"><?php echo $fetch_states['keywords']; ?></textarea>
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="h1" class="form-label">H1 : <span class="text-danger">*</span></label>
                                <textarea rows="3" name="h1" class="form-control" id="h1"><?php echo $fetch_states['h1']; ?></textarea>
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="content" class="form-label">Content : <span class="text-danger">*</span></label>
                                <textarea rows="5" name="content" class="form-control d-none" id="content"><?php echo $fetch_states['content']; ?></textarea>
                                <div class="ckeditor-classic-total ckeditor-classic-job-profile"><?php echo $fetch_states['content']; ?></div>
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
        $('#state_form').validate({
            rules: {
                'name': "required",
                'state_code': "required",
                'title': "required",
                'description': "required",
                'keywords': "required",
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

        $('#state_form').submit(function(event) {
            $('.ckeditor-classic-total').each(function(i, obj) {
                var data = $(this).parent().find(".ck-editor .ck-editor__main .ck-editor__editable").html();
                if (data != "")
                    $(this).parent().find('textarea').val(data);
            });
        });
    });
    
</script>