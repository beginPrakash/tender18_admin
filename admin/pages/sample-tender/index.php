<?php include '../../includes/authentication.php';
?>
<?php $pages = 'pages'; ?>
<?php include '../../includes/header.php' ?>
<?php
if (isset($_POST['submit'])) {
    $stender_main_title = mysqli_real_escape_string($con, $_POST['stender_main_title']);
    $stender_title = mysqli_real_escape_string($con, $_POST['stender_title']);
    $stender_sub_title = mysqli_real_escape_string($con, $_POST['stender_sub_title']);

    $query_delete1 = mysqli_query($con, "DELETE FROM `sample_tender`");
    $q1 = "INSERT INTO sample_tender(`title`, `sub_title`, `main_title`) VALUES ('$stender_title', '$stender_sub_title', '$stender_main_title')";
    $sql1 = mysqli_query($con, $q1);

    $query_delete3 = mysqli_query($con, "DELETE FROM `sample_tender_details`");
    $our_description = $_POST['our_description'];

    foreach ($our_description as $key => $our_description) {
        if (!empty($our_description)) {
            $description = mysqli_real_escape_string($con, $our_description);
            $q = "INSERT INTO sample_tender_details(`description`) VALUES ('$description')";
            $sql = mysqli_query($con, $q);
        }
    }

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
                        window.location.href='" . ADMIN_URL . "/pages/sample-tender/index.php';
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
                        window.location.href='" . ADMIN_URL . "/pages/sample-tender/index.php';
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
            <h4 class="mb-sm-0">Sample Tender</h4>
        </div>
    </div>
</div>
<!-- end page title -->

<?php
//banner section
$stender_main_title = "";
$stender_title = "";
$stender_sub_title = "";

$stender_data = mysqli_query($con, "SELECT * FROM `sample_tender`");
$stender_result = mysqli_num_rows($stender_data);
if ($stender_result == 1) {
    while ($row = mysqli_fetch_assoc($stender_data)) {
        $stender_main_title = $row['main_title'];
        $stender_title = $row['title'];
        $stender_sub_title = $row['sub_title'];
    }
}

$stenderd_data = mysqli_query($con, "SELECT * FROM `sample_tender_details`");
$sample_tender_details = mysqli_num_rows($stenderd_data);
?>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <form action="" method="post" id="homepages" enctype="multipart/form-data">
                <div class="card-body">
                    <div class="row gy-4">
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="stender_main_title" class="form-label">Main Title : <span class="text-danger">*</span></label>
                                <input type="text" name="stender_main_title" class="form-control" value="<?php echo htmlspecialcode_generator($stender_main_title); ?>" id="stender_main_title">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="stender_title" class="form-label">Title : <span class="text-danger">*</span></label>
                                <input type="text" name="stender_title" class="form-control" value="<?php echo htmlspecialcode_generator($stender_title); ?>" id="stender_title">
                            </div>
                        </div>

                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="sub_title" class="form-label">Sub Title : <span class="text-danger">*</span></label>
                                <input type="text" name="stender_sub_title" class="form-control" value="<?php echo htmlspecialcode_generator($stender_sub_title); ?>" id="stender_title">
                            </div>
                        </div>

                    </div>
                </div>


                <div class="card-header">
                    <h4 class="card-title mb-0">Our Section</h4>
                </div>
                <div class="ps-3">
                    <button class="btn btn-success mt-3" id="add_terms_details" type="button">Add more</button>
                </div>
                <div class="card-body details_banner_section terms_details">
                    <div class="row gy-4">
                        <?php
                        if ($sample_tender_details > 0) {
                            $count = 1;
                            while ($row = mysqli_fetch_assoc($stenderd_data)) {
                                $our_description = $row['description'];
                        ?>
                                <div class="details-block terms_details col-xxl-12 col-md-12 mt-4" data-id="<?php echo $count; ?>">
                                    <div class="col-xxl-6 col-md-6 bg-light pt-3 pb-4 ps-2 pe-2">
                                        <i class="ri-delete-bin-5-fill remove"></i>
                                        <div class="col-md-6 mt-3">
                                            <label for="our_description" class="form-label">Description : <span class="text-danger">*</span></label>
                                            <textarea name="our_description[]" rows="5" class="form-control d-none" id="our_description"><?php echo htmlspecialcode_generator($our_description); ?></textarea>
                                            <div class="ckeditor-classic-total ckeditor-classic-<?php echo $count; ?>"><?php echo htmlspecialcode_generator($our_description); ?></div>
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
                'banner_title': "required",
                'banner_description': "required",
                'banner_image': "required",
                'banner_main_title': "required",
                'why_title': "required",
                'why_description': "required",
                'why_image': "required"
            },
        });

        $('#homepage').validate({
            rules: {
                'banner_title': "required",
                'banner_description': "required",
                'banner_main_title': "required",
                'why_title': "required",
                'why_description': "required"
            },
        });
    });


</script>

<script>
    $("#add_terms_details").click(function() {
        if ($(".details-block.terms_details").length < 100) {
            var i = $(".details-block.terms_details:last-child").attr('data-id');
            if ($(".details-block.terms_details:last-child").length > 0) {
                i = parseInt(i);
            } else {
                i = 0;
            }
            i = i + 1;
            var html = '<div class="details-block terms_details col-xxl-12 col-md-12 mt-4" data-id="' + i + '"><div class="col-xxl-6 col-md-6 bg-light pt-3 pb-4 ps-2 pe-2"><i class="ri-delete-bin-5-fill remove"></i><div class="col-md-6 mt-3"><label for="our_description" class="form-label">Description : <span class="text-danger">*</span></label><textarea name="our_description[]" class="form-control d-none" rows="5" id="our_description"></textarea><div class="ckeditor-classic-total ckeditor-classic-' + i + '"></div></div></div></div>';

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
</script>

<script>
    $('#homepage, #homepages').submit(function(event) {
       $('.ckeditor-classic-total').each(function(i, obj) {
            var data = $(this).parent().find(".ck-editor .ck-editor__main .ck-editor__editable").html();
            if (data != "")
                $(this).parent().find('textarea').val(data);
        });
    });
</script>