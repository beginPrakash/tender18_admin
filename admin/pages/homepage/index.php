<?php include '../../includes/authentication.php';
?>
<?php $pages = 'pages'; ?>
<?php include '../../includes/header.php' ?>
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
    //banner section
    $banner_title = mysqli_real_escape_string($con, $_POST['banner_title']);
    $banner_description = mysqli_real_escape_string($con, $_POST['banner_description']);
    $hidden_banner_image = $_POST['hidden_banner_image'];

    $banner_details_title = $_POST['banner_details_title'];
    $banner_details_sub_title = $_POST['banner_details_sub_title'];

    $file = $_FILES['banner_image'];
    $filename = $file['name'];
    $filepath = $file['tmp_name'];
    $fileerror = $file['error'];

    if (!empty($filename)) {
        if ($fileerror == 0) {
            $string = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
            $files =  substr(str_shuffle($string), 0, 8);
            $temp = explode(".", $filename);
            $newfilename = time() . $files . '.' . end($temp);
            $destfile = '../../uploads/images/' . $newfilename;
            move_uploaded_file($filepath, $destfile);
        }
    }

    if (!empty($filename)) {
        $filevalue =  $newfilename;
    } else {
        $filevalue = $hidden_banner_image;
    }

    $query_delete = mysqli_query($con, "DELETE FROM `homepage_banner`");
    $query_delete = mysqli_query($con, "DELETE FROM `homepage_banner_details`");

    $q = "INSERT INTO homepage_banner(`title`, `description` ,`image`) VALUES ('$banner_title', '$banner_description', '$filevalue')";
    $sql1 = mysqli_query($con, $q);
    // print_r($banner_details_title);

    foreach ($banner_details_title as $key => $banner_title) {
        if (!empty($banner_title)) {
            $banner_title = mysqli_real_escape_string($con, $banner_title);
            $banner_subtitle = mysqli_real_escape_string($con, $banner_details_sub_title[$key]);
            $q = "INSERT INTO homepage_banner_details(`title`, `sub_title`) VALUES ('$banner_title', '$banner_subtitle')";
            $sql2 = mysqli_query($con, $q);
        }
    }


    //service section
    $service_title = mysqli_real_escape_string($con, $_POST['service_title']);
    $service_description = mysqli_real_escape_string($con, $_POST['service_description']);

    $service_details_title = $_POST['service_details_title'];
    $service_details_sub_title = $_POST['service_details_sub_title'];
    $service_details_link = $_POST['service_details_link'];
    $hidden_service_details_image = $_POST['hidden_service_details_image'];

    $query_delete = mysqli_query($con, "DELETE FROM `homepage_services`");
    $query_delete = mysqli_query($con, "DELETE FROM `homepage_services_details`");

    $q = "INSERT INTO homepage_services(`title`, `description`) VALUES ('$service_title', '$service_description')";
    $sql1 = mysqli_query($con, $q);
    // print_r($service_details_title);

    foreach ($service_details_title as $key => $service_title) {
        if (!empty($service_title)) {
            $file = $_FILES['service_details_image'];
            $filename = $file['name'][$key];
            $filepath = $file['tmp_name'][$key];
            $fileerror = $file['error'][$key];

            if (!empty($filename)) {
                if ($fileerror == 0) {
                    $string = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
                    $files =  substr(str_shuffle($string), 0, 8);
                    $temp = explode(".", $filename);
                    $newfilename = time() . $files . '.' . end($temp);
                    $destfile = '../../uploads/images/' . $newfilename;
                    move_uploaded_file($filepath, $destfile);
                }
            }

            if (!empty($filename)) {
                $filevalue1 =  $newfilename;
            } else {
                $filevalue1 = $hidden_service_details_image[$key];
            }
            $service_title = mysqli_real_escape_string($con, $service_title);
            $service_desc = mysqli_real_escape_string($con, $service_details_sub_title[$key]);

            $q = "INSERT INTO homepage_services_details(`title`, `description` ,`image` ,`link`) VALUES ('$service_title', '$service_desc', '$filevalue1', '$service_details_link[$key]')";
            $sql2 = mysqli_query($con, $q);
        }
    }

    //tender section
    $tender_title = mysqli_real_escape_string($con, $_POST['tender_title']);
    $tender_description = mysqli_real_escape_string($con, $_POST['tender_description']);

    $query_delete = mysqli_query($con, "DELETE FROM `homepage_tenders`");

    $q = "INSERT INTO homepage_tenders(`title`, `description`) VALUES ('$tender_title', '$tender_description')";
    $sql1 = mysqli_query($con, $q);

    //about section
    $about_title = mysqli_real_escape_string($con, $_POST['about_title']);
    $about_description = mysqli_real_escape_string($con, $_POST['about_description']);
    $about_link = $_POST['about_link'];
    $about_title1 = mysqli_real_escape_string($con, $_POST['about_title1']);
    $about_description1 = mysqli_real_escape_string($con, $_POST['about_description1']);

    $query_delete = mysqli_query($con, "DELETE FROM `homepage_about`");

    $q = "INSERT INTO homepage_about(`title`, `description`, `link`, `title1`, `description1`) VALUES ('$about_title', '$about_description', '$about_link', '$about_title1', '$about_description1')";
    $sql1 = mysqli_query($con, $q);

    //gem section
    $service_state_title  = mysqli_real_escape_string($con, $_POST['service_state_title']);
    $service_city_title = mysqli_real_escape_string($con, $_POST['service_city_title']);
    $service_keyword_title = mysqli_real_escape_string($con, $_POST['service_keyword_title']);
    $service_gem_title = mysqli_real_escape_string($con, $_POST['service_gem_title']);
    $service_gem_button_text = mysqli_real_escape_string($con, $_POST['service_gem_button_text']);
    $service_gem_button_link = $_POST['service_gem_button_link'];

    $query_delete = mysqli_query($con, "DELETE FROM `homepage_gem_services`");

    $q = "INSERT INTO homepage_gem_services(`state_title`, `city_title`, `keyword_title`, `gem_title`, `service_gem_button_text`, `service_gem_button_link`) VALUES ('$service_state_title', '$service_city_title', '$service_keyword_title', '$service_gem_title', '$service_gem_button_text', '$service_gem_button_link')";
    $sql1 = mysqli_query($con, $q);

    $state_details_title = $_POST['state_details_title'];
    $state_details_link = $_POST['state_details_link'];

    $query_delete = mysqli_query($con, "DELETE FROM `homepage_services_state`");

    foreach ($state_details_title as $key => $state_title) {
        if (!empty($state_title)) {
            $state_title = mysqli_real_escape_string($con, $state_title);
            $state_description = $state_details_link[$key];
            $q = "INSERT INTO homepage_services_state(`title`, `link`) VALUES ('$state_title', '$state_description')";
            $sql2 = mysqli_query($con, $q);
        }
    }

    $city_details_title = $_POST['city_details_title'];
    $city_details_link = $_POST['city_details_link'];

    $query_delete = mysqli_query($con, "DELETE FROM `homepage_services_city`");

    foreach ($city_details_title as $key => $city_title) {
        if (!empty($city_title)) {
            $city_title = mysqli_real_escape_string($con, $city_title);
            $city_description = $city_details_link[$key];
            $q = "INSERT INTO homepage_services_city(`title`, `link`) VALUES ('$city_title', '$city_description')";
            $sql2 = mysqli_query($con, $q);
        }
    }

    $keyword_details_title = $_POST['keyword_details_title'];
    $keyword_details_link = $_POST['keyword_details_link'];

    $query_delete = mysqli_query($con, "DELETE FROM `homepage_services_keyword`");

    foreach ($keyword_details_title as $key => $keyword_title) {
        if (!empty($keyword_title)) {
            $keyword_title = mysqli_real_escape_string($con, $keyword_title);
            $keyword_description = $keyword_details_link[$key];
            $q = "INSERT INTO homepage_services_keyword(`title`, `link`) VALUES ('$keyword_title', '$keyword_description')";
            $sql2 = mysqli_query($con, $q);
        }
    }

    $gem_details_title = $_POST['gem_details_title'];

    $query_delete = mysqli_query($con, "DELETE FROM `homepage_services_gem`");

    foreach ($gem_details_title as $key => $gem_title) {
        if (!empty($gem_title)) {
            $gem_title = mysqli_real_escape_string($con, $gem_title);
            $q = "INSERT INTO homepage_services_gem(`title`) VALUES ('$gem_title')";
            $sql2 = mysqli_query($con, $q);
        }
    }

    //why section
    $why_title = mysqli_real_escape_string($con, $_POST['why_title']);
    $why_description = mysqli_real_escape_string($con, $_POST['why_description']);

    $why_details_title = $_POST['why_details_title'];
    $why_details_sub_title = $_POST['why_details_sub_title'];

    $query_delete = mysqli_query($con, "DELETE FROM `homepage_why_section`");
    $query_delete = mysqli_query($con, "DELETE FROM `homepage_why_details`");

    $q = "INSERT INTO homepage_why_section(`title`, `description`) VALUES ('$why_title', '$why_description')";
    $sql1 = mysqli_query($con, $q);
    // print_r($why_details_title);

    foreach ($why_details_title as $key => $why_title) {
        if (!empty($why_title)) {
            $why_title = mysqli_real_escape_string($con, $why_title);
            $why_description = mysqli_real_escape_string($con, $why_details_sub_title[$key]);
            $q = "INSERT INTO homepage_why_details(`title`, `icon`) VALUES ('$why_title', '$why_description')";
            // var_dump($q);
            $sql2 = mysqli_query($con, $q);
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
                        window.location.href='" . ADMIN_URL . "/pages/homepage';
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
                        window.location.href='" . ADMIN_URL . "/pages/homepage';
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
//banner section
$banner_title = "";
$banner_description = "";
$banner_image = "";
$banner_details_title = "";
$banner_details_sub_title = "";
$details_image = "";

$banner_data = mysqli_query($con, "SELECT * FROM `homepage_banner`");
$banner_result = mysqli_num_rows($banner_data);
if ($banner_result == 1) {
    while ($row = mysqli_fetch_assoc($banner_data)) {
        $banner_title = $row['title'];
        $banner_description = $row['description'];
        $banner_image = $row['image'];
    }
}
$banner_details_data = mysqli_query($con, "SELECT * FROM `homepage_banner_details`");
$banner_details_result = mysqli_num_rows($banner_details_data);

//service section
$service_title = "";
$service_description = "";
$service_details_title = "";
$service_details_sub_title = "";
$service_details_link = "";
$service_details_image = "";

$service_data = mysqli_query($con, "SELECT * FROM `homepage_services`");
$service_result = mysqli_num_rows($service_data);
if ($service_result == 1) {
    while ($row = mysqli_fetch_assoc($service_data)) {
        $service_title = $row['title'];
        $service_description = $row['description'];
    }
}
$service_details_data = mysqli_query($con, "SELECT * FROM `homepage_services_details`");
$service_details_result = mysqli_num_rows($service_details_data);

//tender section
$tender_title = "";
$tender_description = "";

$tender_data = mysqli_query($con, "SELECT * FROM `homepage_tenders`");
$tender_result = mysqli_num_rows($tender_data);
if ($tender_result == 1) {
    while ($row = mysqli_fetch_assoc($tender_data)) {
        $tender_title = $row['title'];
        $tender_description = $row['description'];
    }
}

//about section
$about_title = "";
$about_description = "";
$about_link = "";
$about_title1 = "";
$about_description1 = "";


$about_data = mysqli_query($con, "SELECT * FROM `homepage_about`");
$about_result = mysqli_num_rows($about_data);
if ($about_result == 1) {
    while ($row = mysqli_fetch_assoc($about_data)) {
        $about_title = $row['title'];
        $about_description = $row['description'];
        $about_link = $row['link'];
        $about_title1 = $row['title1'];
        $about_description1 = $row['description1'];
    }
}

//about section
$service_state_title  = "";
$service_city_title = "";
$service_keyword_title = "";
$service_gem_title = "";
$service_gem_button_text = "";
$service_gem_button_link = "";


$gem_services_data = mysqli_query($con, "SELECT * FROM `homepage_gem_services`");
$gem_services_result = mysqli_num_rows($gem_services_data);
if ($gem_services_result == 1) {
    while ($row = mysqli_fetch_assoc($gem_services_data)) {
        $service_state_title = $row['state_title'];
        $service_city_title = $row['city_title'];
        $service_keyword_title = $row['keyword_title'];
        $service_gem_title = $row['gem_title'];
        $service_gem_button_text = $row['service_gem_button_text'];
        $service_gem_button_link = $row['service_gem_button_link'];
    }
}

$state_services_details_data = mysqli_query($con, "SELECT * FROM `homepage_services_state`");
$state_services_details_result = mysqli_num_rows($state_services_details_data);

$city_services_details_data = mysqli_query($con, "SELECT * FROM `homepage_services_city`");
$city_services_details_result = mysqli_num_rows($city_services_details_data);

$keyword_services_details_data = mysqli_query($con, "SELECT * FROM `homepage_services_keyword`");
$keyword_services_details_result = mysqli_num_rows($keyword_services_details_data);

$gem_services_details_data = mysqli_query($con, "SELECT * FROM `homepage_services_gem`");
$gem_services_details_result = mysqli_num_rows($gem_services_details_data);

//why section
$why_title = "";
$why_description = "";
$why_details_title = "";
$why_details_sub_title = "";


$why_data = mysqli_query($con, "SELECT * FROM `homepage_why_section`");
$why_result = mysqli_num_rows($why_data);
if ($why_result == 1) {
    while ($row = mysqli_fetch_assoc($why_data)) {
        $why_title = $row['title'];
        $why_description = $row['description'];
    }
}
$why_details_data = mysqli_query($con, "SELECT * FROM `homepage_why_details`");
$why_details_result = mysqli_num_rows($why_details_data);
?>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <form action="" method="post" <?php if ($banner_result == 1) {
                                                echo 'id="homepage"';
                                            } else {
                                                echo 'id="homepages"';
                                            } ?> enctype="multipart/form-data">
                <div class="card-header">
                    <h4 class="card-title mb-0">Banner Section</h4>
                </div>
                <div class="card-body">
                    <div class="row gy-4">
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="banner_title" class="form-label">Title : <span class="text-danger">*</span></label>
                                <input type="text" name="banner_title" class="form-control" value="<?php echo htmlspecialcode_generator($banner_title); ?>" id="banner_title">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="banner_description" class="form-label">Description : <span class="text-danger">*</span></label>
                                <textarea rows="3" name="banner_description" class="form-control" id="banner_description"><?php echo htmlspecialcode_generator($banner_description); ?></textarea>
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="banner_image" class="form-label">Banner Image: <?php if ($banner_result < 1) {
                                                                                                echo '<span class="text-danger">*</span>';
                                                                                            } ?></label>
                                <input class="form-control" type="file" name="banner_image" id="banner_image">
                                <input type="hidden" name="hidden_banner_image" value="<?php echo $banner_image; ?>">
                                <?php
                                if (!empty($banner_image)) {
                                    echo '<img src="../../uploads/images/' . $banner_image . '" class="img-thumbnail mt-2" width="100" height="100">';
                                }
                                ?>
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
                    <h4 class="card-title mb-0">Banner Details Section</h4>
                </div>
                <div class="ps-3">
                    <button class="btn btn-success mt-3" id="add_more_banner" type="button">Add more</button>
                </div>
                <div class="card-body details_banner_section banner">
                    <div class="row gy-4">
                        <?php
                        if ($banner_details_result > 0) {
                            while ($row = mysqli_fetch_assoc($banner_details_data)) {
                                $banner_details_title = $row['title'];
                                $banner_details_sub_title = $row['sub_title'];
                        ?>
                                <div class="details-block banner banner col-xxl-12 col-md-12 mt-4">
                                    <div class="col-xxl-6 col-md-6 bg-light pt-3 pb-4 ps-2 pe-2">
                                        <i class="ri-delete-bin-5-fill remove"></i>
                                        <div class="col-md-6 mt-3">
                                            <label for="banner_details_title" class="form-label">Title : <span class="text-danger">*</span></label>
                                            <input type="text" name="banner_details_title[]" class="form-control" value="<?php echo htmlspecialcode_generator($banner_details_title); ?>" id="banner_details_title">
                                        </div>
                                        <div class="col-md-6 mt-3">
                                            <label for="banner_details_sub_title" class="form-label">Sub Title : <span class="text-danger">*</span></label>
                                            <textarea rows="3" name="banner_details_sub_title[]" class="form-control" id="banner_details_sub_title"><?php echo htmlspecialcode_generator($banner_details_sub_title); ?></textarea>
                                        </div>
                                    </div>
                                </div>
                        <?php }
                        } ?>
                    </div>
                </div>
                <div class="card-header">
                    <h4 class="card-title mb-0">Our Services Section</h4>
                </div>
                <div class="card-body">
                    <div class="row gy-4">
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="service_title" class="form-label">Title : <span class="text-danger">*</span></label>
                                <input type="text" name="service_title" class="form-control" value="<?php echo htmlspecialcode_generator($service_title); ?>" id="service_title">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="service_description" class="form-label">Description : <span class="text-danger">*</span></label>
                                <textarea rows="3" name="service_description" class="form-control" id="service_description"><?php echo htmlspecialcode_generator($service_description); ?></textarea>
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
                    <h4 class="card-title mb-0">Our Services Details Section</h4>
                </div>
                <div class="ps-3">
                    <button class="btn btn-success mt-3" id="add_more_service" type="button">Add more</button>
                </div>
                <div class="card-body details_banner_section service">
                    <div class="row gy-4">
                        <?php
                        if ($service_details_result > 0) {
                            while ($row = mysqli_fetch_assoc($service_details_data)) {
                                $service_details_title = $row['title'];
                                $service_details_sub_title = $row['description'];
                                $service_details_link = $row['link'];
                                $service_details_image = $row['image'];
                        ?>
                                <div class="details-block service col-xxl-12 col-md-12 mt-4">
                                    <div class="col-xxl-6 col-md-6 bg-light pt-3 pb-4 ps-2 pe-2">
                                        <i class="ri-delete-bin-5-fill remove"></i>
                                        <div class="col-md-6 mt-3">
                                            <label for="service_details_title" class="form-label">Title : <span class="text-danger">*</span></label>
                                            <input type="text" name="service_details_title[]" class="form-control" value="<?php echo htmlspecialcode_generator($service_details_title); ?>" id="service_details_title">
                                        </div>
                                        <div class="col-md-6 mt-3">
                                            <label for="service_details_sub_title" class="form-label">Sub Title : <span class="text-danger">*</span></label>
                                            <textarea rows="3" name="service_details_sub_title[]" class="form-control" id="service_details_sub_title"><?php echo htmlspecialcode_generator($service_details_sub_title); ?></textarea>
                                        </div>
                                        <div class="col-md-6 mt-3">
                                            <label for="service_details_link" class="form-label">Link : <span class="text-danger">*</span></label>
                                            <input type="text" name="service_details_link[]" class="form-control" value="<?php echo $service_details_link; ?>" id="service_details_link">
                                        </div>
                                        <div class="col-md-6 mt-3">
                                            <label for="service_details_image" class="form-label">Image: <?php if ($service_details_result < 1) {
                                                                                                                echo '<span class="text-danger">*</span>';
                                                                                                            } ?></label>
                                            <input class="form-control" type="file" name="service_details_image[]" id="service_details_image">
                                            <input type="hidden" name="hidden_service_details_image[]" value="<?php echo $service_details_image; ?>">
                                            <?php
                                            if (!empty($service_details_image)) {
                                                echo '<img src="../../uploads/images/' . $service_details_image . '" class="img-thumbnail mt-2" width="100" height="100">';
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                        <?php }
                        } ?>
                    </div>
                </div>
                <div class="card-header">
                    <h4 class="card-title mb-0">Live Tenders Section</h4>
                </div>
                <div class="card-body">
                    <div class="row gy-4">
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="tender_title" class="form-label">Title : <span class="text-danger">*</span></label>
                                <input type="text" name="tender_title" class="form-control" value="<?php echo htmlspecialcode_generator($tender_title); ?>" id="tender_title">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="tender_description" class="form-label">Description : <span class="text-danger">*</span></label>
                                <textarea rows="3" name="tender_description" class="form-control" id="tender_description"><?php echo htmlspecialcode_generator($tender_description); ?></textarea>
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
                    <h4 class="card-title mb-0">About Us Section</h4>
                </div>
                <div class="card-body">
                    <div class="row gy-4">
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="about_title" class="form-label">Title : <span class="text-danger">*</span></label>
                                <input type="text" name="about_title" class="form-control" value="<?php echo htmlspecialcode_generator($about_title); ?>" id="about_title">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="about_description" class="form-label">Description : <span class="text-danger">*</span></label>
                                <textarea rows="3" name="about_description" class="form-control mb-4 d-none" id="about_description"><?php echo htmlspecialcode_generator($about_description); ?></textarea>
                                <div class="ckeditor-classic-one"><?php echo htmlspecialcode_generator($about_description); ?></div>
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="about_link" class="form-label">Link : <span class="text-danger">*</span></label>
                                <input type="text" name="about_link" class="form-control" value="<?php echo $about_link; ?>" id="about_link">
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
                    <h4 class="card-title mb-0">GeM Services Section</h4>
                </div>
                <div class="card-body">
                    <div class="row gy-4">
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="service_state_title" class="form-label">State Title : <span class="text-danger">*</span></label>
                                <input type="text" name="service_state_title" class="form-control" value="<?php echo htmlspecialcode_generator($service_state_title); ?>" id="service_state_title">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="service_city_title" class="form-label">City Title : <span class="text-danger">*</span></label>
                                <input type="text" name="service_city_title" class="form-control" value="<?php echo htmlspecialcode_generator($service_city_title); ?>" id="service_city_title">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="service_keyword_title" class="form-label">Keyword Title : <span class="text-danger">*</span></label>
                                <input type="text" name="service_keyword_title" class="form-control" value="<?php echo htmlspecialcode_generator($service_keyword_title); ?>" id="service_keyword_title">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="service_gem_title" class="form-label">GeM Title : <span class="text-danger">*</span></label>
                                <input type="text" name="service_gem_title" class="form-control" value="<?php echo htmlspecialcode_generator($service_gem_title); ?>" id="service_gem_title">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="service_gem_button_text" class="form-label">GeM Button Title : <span class="text-danger">*</span></label>
                                <input type="text" name="service_gem_button_text" class="form-control" value="<?php echo htmlspecialcode_generator($service_gem_button_text); ?>" id="service_gem_button_text">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="service_gem_button_link" class="form-label">GeM Button Link : <span class="text-danger">*</span></label>
                                <input type="text" name="service_gem_button_link" class="form-control" value="<?php echo $service_gem_button_link; ?>" id="service_gem_button_link">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-header">
                    <h4 class="card-title mb-0">State Details Section</h4>
                </div>
                <div class="ps-3">
                    <button class="btn btn-success mt-3" id="add_more_state" type="button">Add more</button>
                </div>
                <div class="card-body details_banner_section state">
                    <div class="row gy-4">
                        <?php
                        if ($state_services_details_result > 0) {
                            while ($row = mysqli_fetch_assoc($state_services_details_data)) {
                                $state_details_title = $row['title'];
                                $state_details_link = $row['link'];
                        ?>
                                <div class="details-block state col-xxl-12 col-md-12 mt-4">
                                    <div class="col-xxl-6 col-md-6 bg-light pt-3 pb-4 ps-2 pe-2">
                                        <i class="ri-delete-bin-5-fill remove"></i>
                                        <div class="col-md-6 mt-3">
                                            <label for="state_details_title" class="form-label">Title : <span class="text-danger">*</span></label>
                                            <input type="text" name="state_details_title[]" class="form-control" value="<?php echo htmlspecialcode_generator($state_details_title); ?>" id="state_details_title">
                                        </div>
                                        <div class="col-md-6 mt-3">
                                            <label for="state_details_link" class="form-label">Link : <span class="text-danger">*</span></label>
                                            <input type="text" name="state_details_link[]" class="form-control" id="state_details_link" value='<?php echo $state_details_link; ?>'>
                                        </div>
                                    </div>
                                </div>
                        <?php }
                        } ?>
                    </div>
                </div>
                <div class="card-header">
                    <h4 class="card-title mb-0">City Details Section</h4>
                </div>
                <div class="ps-3">
                    <button class="btn btn-success mt-3" id="add_more_city" type="button">Add more</button>
                </div>
                <div class="card-body details_banner_section city">
                    <div class="row gy-4">
                        <?php
                        if ($city_services_details_result > 0) {
                            while ($row = mysqli_fetch_assoc($city_services_details_data)) {
                                $city_details_title = $row['title'];
                                $city_details_link = $row['link'];
                        ?>
                                <div class="details-block city col-xxl-12 col-md-12 mt-4">
                                    <div class="col-xxl-6 col-md-6 bg-light pt-3 pb-4 ps-2 pe-2">
                                        <i class="ri-delete-bin-5-fill remove"></i>
                                        <div class="col-md-6 mt-3">
                                            <label for="city_details_title" class="form-label">Title : <span class="text-danger">*</span></label>
                                            <input type="text" name="city_details_title[]" class="form-control" value="<?php echo htmlspecialcode_generator($city_details_title); ?>" id="city_details_title">
                                        </div>
                                        <div class="col-md-6 mt-3">
                                            <label for="city_details_link" class="form-label">Link : <span class="text-danger">*</span></label>
                                            <input type="text" name="city_details_link[]" class="form-control" id="city_details_link" value='<?php echo $city_details_link; ?>'>
                                        </div>
                                    </div>
                                </div>
                        <?php }
                        } ?>
                    </div>
                </div>
                <div class="card-header">
                    <h4 class="card-title mb-0">Keyword Details Section</h4>
                </div>
                <div class="ps-3">
                    <button class="btn btn-success mt-3" id="add_more_keyword" type="button">Add more</button>
                </div>
                <div class="card-body details_banner_section keyword">
                    <div class="row gy-4">
                        <?php
                        if ($keyword_services_details_result > 0) {
                            while ($row = mysqli_fetch_assoc($keyword_services_details_data)) {
                                $keyword_details_title = $row['title'];
                                $keyword_details_link = $row['link'];
                        ?>
                                <div class="details-block keyword col-xxl-12 col-md-12 mt-4">
                                    <div class="col-xxl-6 col-md-6 bg-light pt-3 pb-4 ps-2 pe-2">
                                        <i class="ri-delete-bin-5-fill remove"></i>
                                        <div class="col-md-6 mt-3">
                                            <label for="keyword_details_title" class="form-label">Title : <span class="text-danger">*</span></label>
                                            <input type="text" name="keyword_details_title[]" class="form-control" value="<?php echo htmlspecialcode_generator($keyword_details_title); ?>" id="keyword_details_title">
                                        </div>
                                        <div class="col-md-6 mt-3">
                                            <label for="keyword_details_link" class="form-label">Link : <span class="text-danger">*</span></label>
                                            <input type="text" name="keyword_details_link[]" class="form-control" id="keyword_details_link" value='<?php echo $keyword_details_link; ?>'>
                                        </div>
                                    </div>
                                </div>
                        <?php }
                        } ?>
                    </div>
                </div>
                <div class="card-header">
                    <h4 class="card-title mb-0">GeM Details Section</h4>
                </div>
                <div class="ps-3">
                    <button class="btn btn-success mt-3" id="add_more_gem" type="button">Add more</button>
                </div>
                <div class="card-body details_banner_section gem">
                    <div class="row gy-4">
                        <?php
                        if ($gem_services_details_result > 0) {
                            while ($row = mysqli_fetch_assoc($gem_services_details_data)) {
                                $gem_details_title = $row['title'];
                        ?>
                                <div class="details-block gem col-xxl-12 col-md-12 mt-4">
                                    <div class="col-xxl-6 col-md-6 bg-light pt-3 pb-4 ps-2 pe-2">
                                        <i class="ri-delete-bin-5-fill remove"></i>
                                        <div class="col-md-6 mt-3">
                                            <label for="gem_details_title" class="form-label">Title : <span class="text-danger">*</span></label>
                                            <input type="text" name="gem_details_title[]" class="form-control" value="<?php echo htmlspecialcode_generator($gem_details_title); ?>" id="gem_details_title">
                                        </div>
                                    </div>
                                </div>
                        <?php }
                        } ?>
                    </div>
                </div>
                <div class="card-header">
                    <h4 class="card-title mb-0">About Tender18 Section</h4>
                </div>
                <div class="card-body">
                    <div class="row gy-4">
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="about_title1" class="form-label">Title : <span class="text-danger">*</span></label>
                                <input type="text" name="about_title1" class="form-control" value="<?php echo htmlspecialcode_generator($about_title1); ?>" id="about_title1">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="about_description1" class="form-label">Description : <span class="text-danger">*</span></label>
                                <textarea rows="3" name="about_description1" class="form-control mb-4 d-none" id="about_description1"><?php echo htmlspecialcode_generator($about_description1); ?></textarea>
                                <div class="ckeditor-classic-two"><?php echo htmlspecialcode_generator($about_description1); ?></div>
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
                    <h4 class="card-title mb-0">Why Section</h4>
                </div>
                <div class="card-body">
                    <div class="row gy-4">
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="why_title" class="form-label">Title : <span class="text-danger">*</span></label>
                                <input type="text" name="why_title" class="form-control" value="<?php echo htmlspecialcode_generator($why_title); ?>" id="why_title">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="why_description" class="form-label">Description : <span class="text-danger">*</span></label>
                                <textarea rows="3" name="why_description" class="form-control" id="why_description"><?php echo htmlspecialcode_generator($why_description); ?></textarea>
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
                    <h4 class="card-title mb-0">Why Details Section</h4>
                </div>
                <div class="ps-3">
                    <button class="btn btn-success mt-3" id="add_more_why" type="button">Add more</button>
                </div>
                <div class="card-body details_banner_section why">
                    <div class="row gy-4">
                        <?php
                        if ($why_details_result > 0) {
                            while ($row = mysqli_fetch_assoc($why_details_data)) {
                                $why_details_title = $row['title'];
                                $why_details_sub_title = $row['icon'];
                        ?>
                                <div class="details-block why col-xxl-12 col-md-12 mt-4">
                                    <div class="col-xxl-6 col-md-6 bg-light pt-3 pb-4 ps-2 pe-2">
                                        <i class="ri-delete-bin-5-fill remove"></i>
                                        <div class="col-md-6 mt-3">
                                            <label for="why_details_title" class="form-label">Title : <span class="text-danger">*</span></label>
                                            <input type="text" name="why_details_title[]" class="form-control" value="<?php echo htmlspecialcode_generator($why_details_title); ?>" id="why_details_title">
                                        </div>
                                        <div class="col-md-6 mt-3">
                                            <label for="why_details_sub_title" class="form-label">Icon : <span class="text-danger">*</span></label>
                                            <input type="text" name="why_details_sub_title[]" class="form-control" id="why_details_sub_title" value='<?php echo htmlspecialcode_generator($why_details_sub_title); ?>'>
                                        </div>
                                    </div>
                                </div>
                        <?php }
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
                'service_title': "required",
                'service_description': "required",
                'tender_title': "required",
                'tender_description': "required",
                'about_title': "required",
                'about_description': "required",
                'about_title1': "required",
                'about_description1': "required",
                'service_state_title': "required",
                'service_city_title': "required",
                'service_keyword_title': "required",
                'service_gem_title': "required",
                'service_gem_button_text': "required",
                'service_gem_button_link': "required",
                'why_title': "required",
                'why_description': "required"
            },
        });

        $('#homepage').validate({
            rules: {
                'banner_title': "required",
                'banner_description': "required",
                'service_title': "required",
                'service_description': "required",
                'tender_title': "required",
                'tender_description': "required",
                'about_title': "required",
                'about_description': "required",
                'about_title1': "required",
                'about_description1': "required",
                'service_state_title': "required",
                'service_city_title': "required",
                'service_keyword_title': "required",
                'service_gem_title': "required",
                'service_gem_button_text': "required",
                'service_gem_button_link': "required",
                'why_title': "required",
                'why_description': "required"
            },
        });
    });
</script>
<script>
    $("#add_more_banner").click(function() {
        if ($(".details-block.banner").length < 5) {
            var i = $(".details-block.banner:last-child").attr('data-id');
            if ($(".details-block.banner:last-child").length > 0) {
                i = parseInt(i);
            } else {
                i = 0;
            }
            i = i + 1;
            var html = '<div class="details-block banner col-xxl-12 col-md-12 mt-4" data-id="' + i + '"><div class="col-xxl-6 col-md-6 bg-light pt-3 pb-4 ps-2 pe-2"><i class="ri-delete-bin-5-fill remove"></i><div class="col-md-6 mt-3"><label for="banner_details_title" class="form-label">Title : <span class="text-danger">*</span></label><input type="text" name="banner_details_title[]" class="form-control" id="banner_details_title"></div><div class="col-md-6 mt-3"><label for="banner_details_sub_title" class="form-label">Sub Title : <span class="text-danger">*</span></label><textarea rows="3" name="banner_details_sub_title[]" class="form-control" id="banner_details_sub_title"></textarea></div></div></div>';

            $(".details_banner_section.banner .row.gy-4").append(html);
        }
    });
    $(document).on('click', ".details-block.banner .remove", function() {
        $(this).parent().parent().remove();
    });
</script>
<script>
    $("#add_more_service").click(function() {
        if ($(".details-block.service").length < 4) {
            var i = $(".details-block.service:last-child").attr('data-id');
            if ($(".details-block.service:last-child").length > 0) {
                i = parseInt(i);
            } else {
                i = 0;
            }
            i = i + 1;
            var html = '<div class="details-block service col-xxl-12 col-md-12 mt-4" data-id="' + i + '"><div class="col-xxl-6 col-md-6 bg-light pt-3 pb-4 ps-2 pe-2"><i class="ri-delete-bin-5-fill remove"></i><div class="col-md-6 mt-3"><label for="service_details_title" class="form-label">Title : <span class="text-danger">*</span></label><input type="text" name="service_details_title[]" class="form-control" id="service_details_title"></div><div class="col-md-6 mt-3"><label for="service_details_sub_title" class="form-label">Sub Title : <span class="text-danger">*</span></label><textarea rows="3" name="service_details_sub_title[]" class="form-control" id="service_details_sub_title"></textarea></div><div class="col-md-6 mt-3"><label for="service_details_link" class="form-label">Link : <span class="text-danger">*</span></label><input type="text" name="service_details_link[]" class="form-control" id="service_details_link"></div><div class="col-md-6 mt-3"><label for="service_details_image" class="form-label">Image: <span class="text-danger">*</span></label><input class="form-control" type="file" name="service_details_image[]" id="service_details_image"></div></div></div>';

            $(".details_banner_section.service .row.gy-4").append(html);
        }
    });
    $(document).on('click', ".details-block.service .remove", function() {
        $(this).parent().parent().remove();
    });
</script>
<script>
    $('#homepage, #homepages').submit(function(event) {
        $("#about_description").val($("#about_description ~ .ck-editor .ck-editor__main .ck-editor__editable").html());
        $("#about_description1").val($("#about_description1 ~ .ck-editor .ck-editor__main .ck-editor__editable").html());
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
<script>
    $("#add_more_why").click(function() {
        if ($(".details-block.why").length < 8) {
            var i = $(".details-block.why:last-child").attr('data-id');
            if ($(".details-block.why:last-child").length > 0) {
                i = parseInt(i);
            } else {
                i = 0;
            }
            i = i + 1;
            var html = '<div class="details-block why col-xxl-12 col-md-12 mt-4" data-id="' + i + '"><div class="col-xxl-6 col-md-6 bg-light pt-3 pb-4 ps-2 pe-2"><i class="ri-delete-bin-5-fill remove"></i><div class="col-md-6 mt-3"><label for="why_details_title" class="form-label">Title : <span class="text-danger">*</span></label><input type="text" name="why_details_title[]" class="form-control" id="why_details_title"></div><div class="col-md-6 mt-3"><label for="why_details_sub_title" class="form-label">Icon : <span class="text-danger">*</span></label><input type="text" name="why_details_sub_title[]" class="form-control" id="why_details_sub_title"></div></div></div>';

            $(".details_banner_section.why .row.gy-4").append(html);
        }
    });
    $(document).on('click', ".details-block.why .remove", function() {
        $(this).parent().parent().remove();
    });
</script>
<script>
    $("#add_more_state").click(function() {
        // if ($(".details-block.state").length < 8) {
        var i = $(".details-block.state:last-child").attr('data-id');
        if ($(".details-block.state:last-child").length > 0) {
            i = parseInt(i);
        } else {
            i = 0;
        }
        i = i + 1;
        var html = '<div class="details-block state col-xxl-12 col-md-12 mt-4" data-id="' + i + '"><div class="col-xxl-6 col-md-6 bg-light pt-3 pb-4 ps-2 pe-2"><i class="ri-delete-bin-5-fill remove"></i><div class="col-md-6 mt-3"><label for="state_details_title" class="form-label">Title : <span class="text-danger">*</span></label><input type="text" name="state_details_title[]" class="form-control" id="state_details_title"></div><div class="col-md-6 mt-3"><label for="state_details_link" class="form-label">Link : <span class="text-danger">*</span></label><input type="text" name="state_details_link[]" class="form-control" id="state_details_link"></div></div></div>';

        $(".details_banner_section.state .row.gy-4").append(html);
        // }
    });
    $(document).on('click', ".details-block.state .remove", function() {
        $(this).parent().parent().remove();
    });
</script>
<script>
    $("#add_more_city").click(function() {
        // if ($(".details-block.city").length < 8) {
        var i = $(".details-block.city:last-child").attr('data-id');
        if ($(".details-block.city:last-child").length > 0) {
            i = parseInt(i);
        } else {
            i = 0;
        }
        i = i + 1;
        var html = '<div class="details-block city col-xxl-12 col-md-12 mt-4" data-id="' + i + '"><div class="col-xxl-6 col-md-6 bg-light pt-3 pb-4 ps-2 pe-2"><i class="ri-delete-bin-5-fill remove"></i><div class="col-md-6 mt-3"><label for="city_details_title" class="form-label">Title : <span class="text-danger">*</span></label><input type="text" name="city_details_title[]" class="form-control" id="city_details_title"></div><div class="col-md-6 mt-3"><label for="city_details_link" class="form-label">Link : <span class="text-danger">*</span></label><input type="text" name="city_details_link[]" class="form-control" id="city_details_link"></div></div></div>';

        $(".details_banner_section.city .row.gy-4").append(html);
        // }
    });
    $(document).on('click', ".details-block.city .remove", function() {
        $(this).parent().parent().remove();
    });
</script>
<script>
    $("#add_more_keyword").click(function() {
        // if ($(".details-block.keyword").length < 8) {
        var i = $(".details-block.keyword:last-child").attr('data-id');
        if ($(".details-block.keyword:last-child").length > 0) {
            i = parseInt(i);
        } else {
            i = 0;
        }
        i = i + 1;
        var html = '<div class="details-block keyword col-xxl-12 col-md-12 mt-4" data-id="' + i + '"><div class="col-xxl-6 col-md-6 bg-light pt-3 pb-4 ps-2 pe-2"><i class="ri-delete-bin-5-fill remove"></i><div class="col-md-6 mt-3"><label for="keyword_details_title" class="form-label">Title : <span class="text-danger">*</span></label><input type="text" name="keyword_details_title[]" class="form-control" id="keyword_details_title"></div><div class="col-md-6 mt-3"><label for="keyword_details_link" class="form-label">Link : <span class="text-danger">*</span></label><input type="text" name="keyword_details_link[]" class="form-control" id="keyword_details_link"></div></div></div>';

        $(".details_banner_section.keyword .row.gy-4").append(html);
        // }
    });
    $(document).on('click', ".details-block.keyword .remove", function() {
        $(this).parent().parent().remove();
    });
</script>
<script>
    $("#add_more_gem").click(function() {
        // if ($(".details-block.gem").length < 8) {
        var i = $(".details-block.gem:last-child").attr('data-id');
        if ($(".details-block.gem:last-child").length > 0) {
            i = parseInt(i);
        } else {
            i = 0;
        }
        i = i + 1;
        var html = '<div class="details-block gem col-xxl-12 col-md-12 mt-4" data-id="' + i + '"><div class="col-xxl-6 col-md-6 bg-light pt-3 pb-4 ps-2 pe-2"><i class="ri-delete-bin-5-fill remove"></i><div class="col-md-6 mt-3"><label for="gem_details_title" class="form-label">Title : <span class="text-danger">*</span></label><input type="text" name="gem_details_title[]" class="form-control" id="gem_details_title"></div></div></div>';

        $(".details_banner_section.gem .row.gy-4").append(html);
        // }
    });
    $(document).on('click', ".details-block.gem .remove", function() {
        $(this).parent().parent().remove();
    });
</script>