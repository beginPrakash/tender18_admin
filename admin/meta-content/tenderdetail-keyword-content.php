<?php



include "../includes/authentication.php";
$pages = 'tenderdetail-keyword-content';
?>

<?php include '../includes/header.php'; ?>

<?php // include '../includes/connection.php';



?>

<?php

if (isset($_POST['btnInsert'])) {

    $content = mysqli_real_escape_string($con, $_POST['content']);

    $q1 = "UPDATE `tender_detail_content` SET `type`='keyword', `content`='$content' WHERE `id`=4";
    
    $sql1 = mysqli_query($con, $q1);



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

                        window.location.href='" . ADMIN_URL . "/meta-content/tenderdetail-keyword-content.php';

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

                        window.location.href='" . ADMIN_URL . "/meta-content/tenderdetail-keyword-content.php';

                         document.querySelector('.msg_box').remove();

                     }, 3000);

                 

             </script>";

}



?>

<div class="row">

    <div class="col-12">

        <div class="page-title-box d-sm-flex align-items-center justify-content-between">

            <h4 class="mb-sm-0">Tender Detail Keyword Content</h4>

        </div>

    </div>

</div>

<?php

$content = "";



$header_data = mysqli_query($con, "SELECT * FROM `tender_detail_content` where `id`= 4");

$header_result = mysqli_num_rows($header_data);

if ($header_result == 1) {

    while ($row = mysqli_fetch_assoc($header_data)) {
        $content = $row['content'];

    }

}



?>

<div class="row">

    <div class="col-lg-12">

        <div class="card">

            <form action="" method="post" id="keyword_content_form">

                <div class="card-header">

                    <h4 class="card-title mb-0">Tender Detail Keyword Content</h4>

                </div>

                <div class="card-body">

                    <div class="row gy-4">

                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="content" class="form-label">Content : <span class="text-danger">*</span></label>
                                <textarea rows="5" name="content" class="form-control" id="content"><?php echo $content; ?></textarea>
                            </div>
                        </div>
                        
                    </div>

                </div>

                <div class="card-body">

                    <div class="row gy-4">

                        <div class="col-lg-12">

                            <div class="text-start">

                                <button type="submit" class="btn btn-primary" name="btnInsert">Submit</button>

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
        $('#keyword_content_form').validate({
            rules: {
                'content': "required",
            },
            
        });

    });
</script>