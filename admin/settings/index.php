<?php

include "../includes/authentication.php";
?>
<?php include '../includes/header.php'; ?>
<?php // include '../includes/connection.php';

?>
<?php
if (isset($_POST['btnInsert'])) {

    $query_delete = mysqli_query($con, "DELETE FROM `header`");
    $query_delete = mysqli_query($con, "DELETE FROM `footer`");

    $button_text = mysqli_real_escape_string($con, $_POST['button_text']);
    $button_link = $_POST['button_link'];
    $button_text1 = mysqli_real_escape_string($con, $_POST['button_text1']);
    $button_link1 = $_POST['button_link1'];
    $copyright_text = mysqli_real_escape_string($con, $_POST['copyright_text']);
    $quick_menu_title = mysqli_real_escape_string($con, $_POST['quick_menu_title']);
    $contact_menu_title = mysqli_real_escape_string($con, $_POST['contact_menu_title']);
    $tender_menu_title = mysqli_real_escape_string($con, $_POST['tender_menu_title']);
    $terms_text = mysqli_real_escape_string($con, $_POST['terms_text']);
    $terms_link = $_POST['terms_link'];
    $address = mysqli_real_escape_string($con, $_POST['address']);
    $first_email = $_POST['first_email'];
    $second_email = $_POST['second_email'];
    $contact_no = $_POST['contact_no'];
    $facebook_link = $_POST['facebook_link'];
    $twitter_link = $_POST['twitter_link'];
    $linked_link = $_POST['linked_link'];
    $youtube_link = $_POST['youtube_link'];
    $instagram_link = $_POST['instagram_link'];
    $whatsapp_num = $_POST['whatsapp_num'];
    $hidden_desktop_logo = $_POST['hidden_desktop_logo'];
    $hidden_mobile_logo = $_POST['hidden_mobile_logo'];

    $file = $_FILES['desktop_logo'];
    $filename = $file['name'];
    $filepath = $file['tmp_name'];
    $fileerror = $file['error'];

    if (!empty($filename)) {
        if ($fileerror == 0) {
            $destfile = '../uploads/images/' . $filename;
            move_uploaded_file($filepath, $destfile);
        }
    }

    $file_2 = $_FILES['mobile_logo'];
    $filename_2 = $file_2['name'];
    $filepath = $file_2['tmp_name'];
    $fileerror = $file_2['error'];

    if (!empty($filename_2)) {
        if ($fileerror == 0) {
            $destfile_2 = '../uploads/images/' . $filename_2;
            move_uploaded_file($filepath, $destfile_2);
        }
    }

    if (!empty($filename)) {
        $filevalue1 =  $filename;
    } else {
        $filevalue1 = $hidden_desktop_logo;
    }

    if (!empty($filename_2)) {
        $filevalue2 =  $filename_2;
    } else {
        $filevalue2 =  $hidden_mobile_logo;
    }

    $query = "INSERT INTO header (`desktop_logo`, `mobile_logo`, `button_link`, `button_text`, `button_link1`, `button_text1`, `whatsapp_num`) VALUES('$filevalue1', '$filevalue2', '$button_link', '$button_text', '$button_link1', '$button_text1', '$whatsapp_num')";
    $sql1 = mysqli_query($con, $query);

    $query_2 = "INSERT INTO footer (`copyright_text`,`quick_menu_title`,`contact_menu_title`,`tender_menu_title`,`terms_text`,`terms_link`, `first_email`, `second_email`, `address`, `contact_no`, `facebook_link`, `twitter_link`, `linked_link`, `youtube_link`, `instagram_link`) VALUES('$copyright_text', '$quick_menu_title', '$contact_menu_title', '$tender_menu_title', '$terms_text', '$terms_link', '$first_email', '$second_email', '$address', '$contact_no', '$facebook_link', '$twitter_link', '$linked_link', '$youtube_link', '$instagram_link')";
    $sql2 = mysqli_query($con, $query_2);

    if ($sql1 && $sql2) {
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
                        window.location.href='" . ADMIN_URL . "/settings';
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
                        window.location.href='" . ADMIN_URL . "/settings';
                         document.querySelector('.msg_box').remove();
                     }, 3000);
                 
             </script>";
}

?>
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Settigns</h4>
        </div>
    </div>
</div>
<?php
$desktop_logo = "";
$mobile_logo = "";
$button_link = "";
$button_text = "";
$button_link1 = "";
$button_text1 = "";
$copyright_text = "";
$first_email = "";
$second_email = "";
$address = "";
$contact_no = "";
$facebook_link = "";
$twitter_link = "";
$linked_link = "";
$youtube_link = "";
$instagram_link = "";
$whatsapp_num = "";
$terms_link = "";
$terms_text = "";
$quick_menu_title = "";
$contact_menu_title = "";
$tender_menu_title = "";


$header_data = mysqli_query($con, "SELECT * FROM `header`");
$header_result = mysqli_num_rows($header_data);
if ($header_result == 1) {
    while ($row = mysqli_fetch_assoc($header_data)) {
        $desktop_logo = $row['desktop_logo'];
        $mobile_logo = $row['mobile_logo'];
        $button_link = $row['button_link'];
        $button_text = $row['button_text'];
        $button_link1 = $row['button_link1'];
        $button_text1 = $row['button_text1'];
        $whatsapp_num = $row['whatsapp_num'];
    }
}

$footer_data = mysqli_query($con, "SELECT * FROM `footer`");
$footer_result = mysqli_num_rows($footer_data);
if ($footer_result == 1) {
    while ($row = mysqli_fetch_assoc($footer_data)) {
        $copyright_text = $row['copyright_text'];
        $quick_menu_title = $row['quick_menu_title'];
        $contact_menu_title = $row['contact_menu_title'];
        $tender_menu_title = $row['tender_menu_title'];
        $terms_link = $row['terms_link'];
        $terms_text = $row['terms_text'];
        $first_email = $row['first_email'];
        $second_email = $row['second_email'];
        $address = $row['address'];
        $contact_no = $row['contact_no'];
        $facebook_link = $row['facebook_link'];
        $twitter_link = $row['twitter_link'];
        $linked_link = $row['linked_link'];
        $youtube_link = $row['youtube_link'];
        $instagram_link = $row['instagram_link'];
    }
}
?>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <form action="" method="post" <?php if ($header_result == 1) {
                                                echo 'id="setting"';
                                            } else {
                                                echo 'id="settings"';
                                            } ?> enctype="multipart/form-data">
                <div class="card-header">
                    <h4 class="card-title mb-0">Header</h4>
                </div>
                <div class="card-body">
                    <div class="row gy-4">
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="desktop_logo" class="form-label">Desktop Logo: <?php if ($header_result < 1) {
                                                                                                echo '<span class="text-danger">*</span>';
                                                                                            } ?></label>
                                <input class="form-control" type="file" name="desktop_logo" id="desktop_logo">
                                <input type="hidden" name="hidden_desktop_logo" value="<?php echo $desktop_logo; ?>">
                                <?php
                                if (!empty($desktop_logo)) {
                                    echo '<img src="../uploads/images/' . $desktop_logo . '" class="img-thumbnail mt-2" width="100" height="100">';
                                }
                                ?>
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="mobile_logo" class="form-label">Mobile Logo: <?php if ($header_result < 1) {
                                                                                                echo '<span class="text-danger">*</span>';
                                                                                            } ?></label>
                                <input class="form-control" type="file" name="mobile_logo" id="mobile_logo">
                                <input type="hidden" name="hidden_mobile_logo" value="<?php echo $mobile_logo; ?>">
                                <?php
                                if (!empty($mobile_logo)) {
                                    echo '<img src="../uploads/images/' . $mobile_logo . '" class="img-thumbnail mt-2" width="100" height="100">';
                                }
                                ?>
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="button_text" class="form-label">Button Text : <span class="text-danger">*</span></label>
                                <input type="text" name="button_text" class="form-control" id="button_text" value="<?php echo htmlspecialcode_generator($button_text); ?>">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="button_link" class="form-label">Button Link : <span class="text-danger">*</span></label>
                                <input type="text" name="button_link" class="form-control" id="button_link" value="<?php echo $button_link; ?>">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="button_text1" class="form-label">Button Text1 : <span class="text-danger">*</span></label>
                                <input type="text" name="button_text1" class="form-control" id="button_text1" value="<?php echo htmlspecialcode_generator($button_text1); ?>">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="button_link1" class="form-label">Button Link1 : <span class="text-danger">*</span></label>
                                <input type="text" name="button_link1" class="form-control" id="button_link1" value="<?php echo $button_link1; ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-header">
                    <h4 class="card-title mb-0">Tenders Whatsapp Number</h4>
                </div>
                <div class="card-body">
                    <div class="row gy-4">
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="whatsapp_num" class="form-label">Whatsapp No. : <span class="text-danger">*</span></label>
                                <input type="text" name="whatsapp_num" class="form-control" id="whatsapp_num" value="<?php echo $whatsapp_num; ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-header">
                    <h4 class="card-title mb-0">Footer</h4>
                </div>
                <div class="card-body">
                    <div class="row gy-4">
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="address" class="form-label">Address : <span class="text-danger">*</span></label>
                                <textarea name="address" class="form-control" id="address" rows="3"><?php echo htmlspecialcode_generator($address); ?></textarea>
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="first_email" class="form-label">First Email : <span class="text-danger">*</span></label>
                                <input type="text" name="first_email" class="form-control" id="first_email" value="<?php echo $first_email; ?>">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="second_email" class="form-label">Second Email : <span class="text-danger">*</span></label>
                                <input type="text" name="second_email" class="form-control" id="second_email" value="<?php echo $second_email; ?>">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="contact_no" class="form-label">Contact No. : <span class="text-danger">*</span></label>
                                <input type="text" name="contact_no" class="form-control" id="contact_no" value="<?php echo $contact_no; ?>">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="facebook_link" class="form-label">Facebook Link : <span class="text-danger">*</span></label>
                                <input type="text" name="facebook_link" class="form-control" id="facebook_link" value="<?php echo $facebook_link; ?>">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="twitter_link" class="form-label">Twitter Link : <span class="text-danger">*</span></label>
                                <input type="text" name="twitter_link" class="form-control" id="twitter_link" value="<?php echo $twitter_link; ?>">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="linked_link" class="form-label">LinkedIn Link : <span class="text-danger">*</span></label>
                                <input type="text" name="linked_link" class="form-control" id="linked_link" value="<?php echo $linked_link; ?>">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="youtube_link" class="form-label">YouTube Link : <span class="text-danger">*</span></label>
                                <input type="text" name="youtube_link" class="form-control" id="youtube_link" value="<?php echo $youtube_link; ?>">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="instagram_link" class="form-label">Instagram Link : <span class="text-danger">*</span></label>
                                <input type="text" name="instagram_link" class="form-control" id="instagram_link" value="<?php echo $youtube_link; ?>">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="copyright_text" class="form-label">Copyright Text : <span class="text-danger">*</span></label>
                                <input type="text" name="copyright_text" class="form-control" id="copyright_text" value="<?php echo htmlspecialcode_generator($copyright_text); ?>">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="quick_menu_title" class="form-label">Quick Menu Title : <span class="text-danger">*</span></label>
                                <input type="text" name="quick_menu_title" class="form-control" id="quick_menu_title" value="<?php echo htmlspecialcode_generator($quick_menu_title); ?>">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="contact_menu_title" class="form-label">Contact Menu Title : <span class="text-danger">*</span></label>
                                <input type="text" name="contact_menu_title" class="form-control" id="contact_menu_title" value="<?php echo htmlspecialcode_generator($contact_menu_title); ?>">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="tender_menu_title" class="form-label">Tender Menu Title : <span class="text-danger">*</span></label>
                                <input type="text" name="tender_menu_title" class="form-control" id="tender_menu_title" value="<?php echo htmlspecialcode_generator($tender_menu_title); ?>">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="terms_text" class="form-label">Terms Text : <span class="text-danger">*</span></label>
                                <input type="text" name="terms_text" class="form-control" id="terms_text" value="<?php echo htmlspecialcode_generator($terms_text); ?>">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="terms_link" class="form-label">Terms link : <span class="text-danger">*</span></label>
                                <input type="text" name="terms_link" class="form-control" id="terms_link" value="<?php echo $terms_link; ?>">
                            </div>
                        </div>
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