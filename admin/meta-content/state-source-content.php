<?php



include "../includes/authentication.php";
$pages = 'state-source-content';
?>

<?php include '../includes/header.php'; ?>

<?php // include '../includes/connection.php';



?>

<?php

if (isset($_POST['btnInsert'])) {

    $title = mysqli_real_escape_string($con, $_POST['title']);
    $description = mysqli_real_escape_string($con, $_POST['description']);
    $content = mysqli_real_escape_string($con, $_POST['content']);
    $keywords = mysqli_real_escape_string($con, $_POST['keywords']);
    $h1 = mysqli_real_escape_string($con, $_POST['h1']);
    $tab_title = mysqli_real_escape_string($con, $_POST['tab_title']);
    $tab_description = mysqli_real_escape_string($con, $_POST['tab_description']);
    $tab_title2 = mysqli_real_escape_string($con, $_POST['tab_title2']);
    $tab_description2 = mysqli_real_escape_string($con, $_POST['tab_description2']);
    $tab_title3 = mysqli_real_escape_string($con, $_POST['tab_title3']);
    $tab_description3 = mysqli_real_escape_string($con, $_POST['tab_description3']);
    $tab_title4 = mysqli_real_escape_string($con, $_POST['tab_title4']);
    $tab_description4 = mysqli_real_escape_string($con, $_POST['tab_description4']);
    $faq_main_title = mysqli_real_escape_string($con, $_POST['faq_main_title']);

    $q1 = "UPDATE `multiurl_meta_content` SET `title`='$title',`type`='state-source', `description`='$description', `keywords`='$keywords', `h1`='$h1', `content`='$content' WHERE `id`=9";
    
    $sql1 = mysqli_query($con, $q1);

    $q2 = "UPDATE `tenderdetail_page_meta_content` SET  `tab_title`='$tab_title', `tab_description`='$tab_description', `tab_title2`='$tab_title2', `tab_description2`='$tab_description2', `tab_title3`='$tab_title3', `tab_description3`='$tab_description3', `tab_title4`='$tab_title4', `tab_description4`='$tab_description4', `faq_main_title` = '$faq_main_title' WHERE `type`='state-department'";

    $sql2 = mysqli_query($con, $q2);

    $query_delete2 = mysqli_query($con, "DELETE FROM `faq_meta` where type='state-department'");
    $terms_details_title = $_POST['terms_details_title'];
    $terms_details_description = $_POST['terms_details_description'];
    foreach ($terms_details_title as $key => $title) {
        if (!empty($title)) {
            $title = mysqli_real_escape_string($con, $title);
            $desc = mysqli_real_escape_string($con, $terms_details_description[$key]);
            $q = "INSERT INTO faq_meta(`title`, `description`,`type`) VALUES ('$title', '$desc','state-department')";
            $sql = mysqli_query($con, $q);
        }
    }



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

                        window.location.href='" . ADMIN_URL . "/meta-content/state-source-content.php';

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

                        window.location.href='" . ADMIN_URL . "/meta-content/state-source-content.php';

                         document.querySelector('.msg_box').remove();

                     }, 3000);

                 

             </script>";

}



?>

<div class="row">

    <div class="col-12">

        <div class="page-title-box d-sm-flex align-items-center justify-content-between">

            <h4 class="mb-sm-0">State Source Tenders Meta Content</h4>

        </div>

    </div>

</div>

<?php

$title = "";

$content = "";

$description = "";

$h1 = "";

$keywords = "";


$header_data = mysqli_query($con, "SELECT * FROM `multiurl_meta_content` where `id`= 9");

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

$content_data = mysqli_query($con, "SELECT * FROM `tenderdetail_page_meta_content` where `type` = 'state-department'");

$content_result = mysqli_num_rows($content_data);

if ($content_result == 1) {

    while ($row = mysqli_fetch_assoc($content_data)) {
        $tab_title = $row['tab_title'];
        $tab_description = $row['tab_description'];
        $tab_title2 = $row['tab_title2'];
        $tab_description2 = $row['tab_description2'];
        $tab_title3 = $row['tab_title3'];
        $tab_description3 = $row['tab_description3'];
        $tab_title4 = $row['tab_title4'];
        $tab_description4 = $row['tab_description4'];
        $faq_main_title  = $row['faq_main_title'];

    }

}



?>

<div class="row">

    <div class="col-lg-12">

        <div class="card">

            <form action="" method="post" id="keyword_content_form">

                <div class="card-header">

                    <h4 class="card-title mb-0">State Source Tenders Meta Content</h4>

                </div>

                <div class="card-body">

                    <div class="row gy-4">

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

                    </div>

                </div>

                <div class="card-header">

                    <h4 class="card-title mb-0">Tab Content Section</h4>

                </div>

                <div class="card-body">

                    <div class="row gy-4">

                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="tab_title" class="form-label">Tab 1 Title : <span class="text-danger">*</span></label>
                                <input type="text" name="tab_title" placeholder="Enter Title" class="form-control" id="tab_title" value="<?php echo $tab_title; ?>">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6 meta-blocks" data-id="2">
                                <label for="tab_description" class="form-label">Tab 1 Description : <span class="text-danger">*</span></label>
                                <textarea rows="5" name="tab_description" class="form-control d-none" id="tab_description"><?php echo html_entity_decode($tab_description, ENT_QUOTES, 'UTF-8');?></textarea>
                                <div class="ckeditor-classic-total ckeditor-meta-2"><?php echo html_entity_decode($tab_description, ENT_QUOTES, 'UTF-8');?></div>
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="tab_title2" class="form-label">Tab 2 Title : <span class="text-danger">*</span></label>
                                <input type="text" name="tab_title2" placeholder="Enter Title" class="form-control" id="tab_title2" value="<?php echo $tab_title2; ?>">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6 meta-blocks" data-id="3">
                                <label for="tab_description2" class="form-label">Tab 2 Description : <span class="text-danger">*</span></label>
                                <textarea rows="5" name="tab_description2" class="form-control d-none" id="tab_description2"><?php echo html_entity_decode($tab_description2, ENT_QUOTES, 'UTF-8');?></textarea>
                                <div class="ckeditor-classic-total ckeditor-meta-3"><?php echo html_entity_decode($tab_description2, ENT_QUOTES, 'UTF-8');?></div>
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="tab_title3" class="form-label">Tab 3 Title : <span class="text-danger">*</span></label>
                                <input type="text" name="tab_title3" placeholder="Enter Title" class="form-control" id="tab_title3" value="<?php echo $tab_title3; ?>">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6 meta-blocks" data-id="4">
                                <label for="tab_description3" class="form-label">Tab 3 Description : <span class="text-danger">*</span></label>
                                <textarea rows="5" name="tab_description3" class="form-control d-none" id="tab_description3"><?php echo html_entity_decode($tab_description3, ENT_QUOTES, 'UTF-8');?></textarea>
                                <div class="ckeditor-classic-total ckeditor-meta-4"><?php echo html_entity_decode($tab_description3, ENT_QUOTES, 'UTF-8');?></div>
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="tab_title4" class="form-label">Tab 4 Title : <span class="text-danger">*</span></label>
                                <input type="text" name="tab_title4" placeholder="Enter Title" class="form-control" id="tab_title4" value="<?php echo $tab_title4; ?>">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6 meta-blocks" data-id="5">
                                <label for="tab_description4" class="form-label">Tab 4 Description : <span class="text-danger">*</span></label>
                                <textarea rows="5" name="tab_description4" class="form-control d-none" id="tab_description4"><?php echo html_entity_decode($tab_description4, ENT_QUOTES, 'UTF-8');?></textarea>
                                <div class="ckeditor-classic-total ckeditor-meta-5"><?php echo html_entity_decode($tab_description4, ENT_QUOTES, 'UTF-8');?></div>
                            </div>
                        </div>

                      
                        
                    </div>

                </div>

                <div class="card-header">

                    <h4 class="card-title mb-0">FAQ's Section</h4>

                </div>
                <div class="card-body">
                    <div class="row gy-4">
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="faq_main_title" class="form-label">FAQ Section Main Title : <span class="text-danger">*</span></label>
                                <input type="text" name="faq_main_title" placeholder="Enter Title" class="form-control" id="faq_main_title" value="<?php echo $faq_main_title; ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="ps-3">
                    <button class="btn btn-success mt-3" id="add_terms_details" type="button">Add more</button>
                </div>
                <div class="card-body details_banner_section terms_details">
                    <div class="row gy-4">
                        <?php
                        $terms_details_data = mysqli_query($con, "SELECT * FROM `faq_meta` where `type`='state-department'");
                        $terms_details_result = mysqli_num_rows($terms_details_data);
                        if ($terms_details_result > 0) {
                            $count = 1;
                            while ($row = mysqli_fetch_assoc($terms_details_data)) {
                                $terms_details_title = $row['title'];
                                $terms_details_description = $row['description'];
                        ?>
                                <div class="details-block terms_details col-xxl-12 col-md-12 mt-4" data-id="<?php echo $count; ?>">
                                    <div class="row">    
                                        <div class="col-xxl-12 col-md-12 bg-light pt-3 pb-4 ps-2 pe-2">
                                            
                                            <i class="ri-delete-bin-5-fill remove"></i>
                                            <div class="row">    
                                                <div class="col-md-6 mt-3">
                                                    <label for="terms_details_title" class="form-label">Title : <span class="text-danger">*</span></label>
                                                    <input type="text" name="terms_details_title[]" class="form-control" value="<?php echo htmlspecialcode_generator($terms_details_title); ?>" id="terms_details_title">
                                                </div>
                                                <div class="col-md-6 mt-3">
                                                    <label for="terms_details_description" class="form-label">Description : <span class="text-danger">*</span></label>
                                                    <textarea name="terms_details_description[]" rows="5" class="form-control d-none" id="terms_details_description">                                                    <textarea name="terms_details_description[]" rows="5" class="form-control d-none" id="terms_details_description"></textarea><?php echo html_entity_decode($terms_details_description, ENT_QUOTES);?></textarea>
                                                    <div class="ckeditor-classic-total ckeditor-classic-<?php echo $count; ?>">                                                    <div class="ckeditor-classic-total ckeditor-classic-<?php echo $count; ?>"></div><?php echo html_entity_decode($terms_details_description, ENT_QUOTES);?></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        <?php $count++;
                            }
                        } ?>
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

        $('#keyword_content_form').submit(function(event) {
            $('.ckeditor-classic-total').each(function(i, obj) {
                var data = $(this).parent().find(".ck-editor .ck-editor__main .ck-editor__editable").html();
                if (data != "")
                    $(this).parent().find('textarea').val(data);
            });
        });

        $("#add_terms_details").click(function() {
            if ($(".details-block.terms_details").length < 100) {
                var i = $(".details-block.terms_details:last-child").attr('data-id');
                if ($(".details-block.terms_details:last-child").length > 0) {
                    i = parseInt(i);
                } else {
                    i = 0;
                }
                i = i + 1;
                var html = '<div class="details-block terms_details col-xxl-12 col-md-12 mt-4" data-id="' + i + '"><div class="row"><div class="col-xxl-12 col-md-12 bg-light pt-3 pb-4 ps-2 pe-2"><i class="ri-delete-bin-5-fill remove"></i><div class="row"><div class="col-md-6 mt-3"><label for="terms_details_title" class="form-label">Title : <span class="text-danger">*</span></label><input type="text" name="terms_details_title[]" class="form-control" id="terms_details_title"></div><div class="col-md-6 mt-3"><label for="terms_details_description" class="form-label">Description : <span class="text-danger">*</span></label><textarea name="terms_details_description[]" class="form-control d-none" rows="5" id="terms_details_description"></textarea><div class="ckeditor-classic-total ckeditor-classic-' + i + '"></div></div></div></div></div></div>';

                $(".details_banner_section.terms_details .row.gy-4").append(html);

                ClassicEditor.create(document.querySelector(".ckeditor-classic-" + i))
                    .then(function(c) {
                        c.ui.view.editable.element.style.height = "200px";
                    })
                    .catch(function(c) {
                        console.error(c);
                    });
            }
        });
        

        $(document).on('click', ".details-block.terms_details .remove", function() {
            $(this).parent().parent().remove();
        });

        $('.details-block.terms_details').each(function(i, obj) {
            var count = $(this).attr("data-id");
            ClassicEditor.create(document.querySelector(".ckeditor-classic-" + count))
                .then(function(c) {
                    c.ui.view.editable.element.style.height = "200px";
                })
                .catch(function(c) {
                    console.error(c);
                });
        });

        $('.meta-blocks').each(function(i, obj) {
            var count = $(this).attr("data-id");
            ClassicEditor.create(document.querySelector(".ckeditor-meta-" + count))
                .then(function(c) {
                    c.ui.view.editable.element.style.height = "200px";
                })
                .catch(function(c) {
                    console.error(c);
                });
        });
    });
</script>