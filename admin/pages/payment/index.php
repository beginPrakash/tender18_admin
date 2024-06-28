<?php include '../../includes/authentication.php';
?>
<?php $pages = 'pages'; ?>
<?php include '../../includes/header.php' ?>

<?php
if (isset($_POST['submit'])) {
    $payment_main_title = mysqli_real_escape_string($con, $_POST['payment_main_title']);
    $payment_link = $_POST['payment_link'];
    $payment_title = mysqli_real_escape_string($con, $_POST['payment_title']);
    $payment_bank_title = mysqli_real_escape_string($con, $_POST['payment_bank_title']);
    $payment_upi_title = mysqli_real_escape_string($con, $_POST['payment_upi_title']);
    $hidden_payment_image = mysqli_real_escape_string($con, $_POST['hidden_payment_image']);

    $file = $_FILES['payment_image'];
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
        $filevalue = $hidden_payment_image;
    }

    $query_delete1 = mysqli_query($con, "DELETE FROM `payment`");
    $q1 = "INSERT INTO payment(`main_title`, `payment_link`, `title`, `image`, `bank_title`, `upi_title`) VALUES ('$payment_main_title', '$payment_link', '$payment_title', '$filevalue', '$payment_bank_title', '$payment_upi_title')";
    $sql1 = mysqli_query($con, $q1);

    $query_delete2 = mysqli_query($con, "DELETE FROM `payment_bank_details`");
    $bank_details_bank_name = $_POST['bank_details_bank_name'];
    $bank_details_acc_no = $_POST['bank_details_acc_no'];
    $bank_details_benf_name = $_POST['bank_details_benf_name'];
    $bank_details_ifsc_code = $_POST['bank_details_ifsc_code'];
    foreach ($bank_details_bank_name as $key => $bank_name) {
        if (!empty($bank_name)) {
            $bank_name = mysqli_real_escape_string($con, $bank_name);
            $acc_no = $bank_details_acc_no[$key];
            $benf_name = mysqli_real_escape_string($con, $bank_details_benf_name[$key]);
            $ifsc_code = $bank_details_ifsc_code[$key];
            $q = "INSERT INTO payment_bank_details(`bank_name`, `acc_no`, `benf_name`, `ifsc_code`) VALUES ('$bank_name', '$acc_no', '$benf_name', '$ifsc_code')";
            $sql = mysqli_query($con, $q);
        }
    }

    $query_delete3 = mysqli_query($con, "DELETE FROM `payment_upi`");
    $upi_payments_title = $_POST['upi_payments_title'];
    $upi_payments_upi_no = $_POST['upi_payments_upi_no'];
    foreach ($upi_payments_title as $key => $title) {
        if (!empty($title)) {
            $title = mysqli_real_escape_string($con, $title);
            $upi_no = $upi_payments_upi_no[$key];
            $q = "INSERT INTO payment_upi(`title`, `upi_no`) VALUES ('$title', '$upi_no')";
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
                        window.location.href='" . ADMIN_URL . "/pages/payment';
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
                        window.location.href='" . ADMIN_URL . "/pages/payment';
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
            <h4 class="mb-sm-0">Payment</h4>
        </div>
    </div>
</div>
<!-- end page title -->

<?php
//payment section
$payment_main_title = "";
$payment_link = "";
$payment_title = "";
$payment_title = "";
$payment_image = "";
$payment_bank_title = "";
$payment_upi_title = "";

$payment_data = mysqli_query($con, "SELECT * FROM `payment`");
$payment_result = mysqli_num_rows($payment_data);
if ($payment_result == 1) {
    while ($row = mysqli_fetch_assoc($payment_data)) {
        $payment_main_title = $row['main_title'];
        $payment_link = $row['payment_link'];
        $payment_title = $row['title'];
        $payment_image = $row['image'];
        $payment_bank_title = $row['bank_title'];
        $payment_upi_title = $row['upi_title'];
    }
}

$bank_details_data = mysqli_query($con, "SELECT * FROM `payment_bank_details`");
$bank_details_result = mysqli_num_rows($bank_details_data);

$payment_upi_data = mysqli_query($con, "SELECT * FROM `payment_upi`");
$payment_upi_result = mysqli_num_rows($payment_upi_data);
?>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <form action="" method="post" <?php if ($payment_result == 1) {
                                                echo 'id="homepage"';
                                            } else {
                                                echo 'id="homepages"';
                                            } ?> enctype="multipart/form-data">
                <div class="card-header">
                    <h4 class="card-title mb-0">Payment Section</h4>
                </div>
                <div class="card-body">
                    <div class="row gy-4">
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="payment_main_title" class="form-label">Main Title : <span class="text-danger">*</span></label>
                                <input type="text" name="payment_main_title" class="form-control" value="<?php echo htmlspecialcode_generator($payment_main_title); ?>" id="payment_main_title">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="payment_link" class="form-label">Payment Link : <span class="text-danger">*</span></label>
                                <input type="text" name="payment_link" class="form-control" value="<?php echo $payment_link; ?>" id="payment_link">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="payment_title" class="form-label">Title : <span class="text-danger">*</span></label>
                                <input type="text" name="payment_title" class="form-control" value="<?php echo htmlspecialcode_generator($payment_title); ?>" id="payment_title">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="payment_image" class="form-label">Image: <?php if ($payment_result < 1) {
                                                                                            echo '<span class="text-danger">*</span>';
                                                                                        } ?></label>
                                <input class="form-control" type="file" name="payment_image" id="payment_image">
                                <input type="hidden" name="hidden_payment_image" value="<?php echo $payment_image; ?>">
                                <?php
                                if (!empty($payment_image)) {
                                    echo '<img src="../../uploads/images/' . $payment_image . '" class="img-thumbnail mt-2" width="100" height="100">';
                                }
                                ?>
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="payment_bank_title" class="form-label">Bank Details Title : <span class="text-danger">*</span></label>
                                <input type="text" name="payment_bank_title" class="form-control" value="<?php echo htmlspecialcode_generator($payment_bank_title); ?>" id="payment_bank_title">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="payment_upi_title" class="form-label">UPI Payments Title : <span class="text-danger">*</span></label>
                                <input type="text" name="payment_upi_title" class="form-control" value="<?php echo htmlspecialcode_generator($payment_upi_title); ?>" id="payment_upi_title">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-header">
                    <h4 class="card-title mb-0">Bank Details</h4>
                </div>
                <div class="ps-3">
                    <button class="btn btn-success mt-3" id="add_bank_details" type="button">Add more</button>
                </div>
                <div class="card-body details_banner_section bank_details">
                    <div class="row gy-4">
                        <?php
                        if ($bank_details_result > 0) {
                            while ($row = mysqli_fetch_assoc($bank_details_data)) {
                                $bank_details_bank_name = $row['bank_name'];
                                $bank_details_acc_no = $row['acc_no'];
                                $bank_details_benf_name = $row['benf_name'];
                                $bank_details_ifsc_code = $row['ifsc_code'];
                        ?>
                                <div class="details-block bank_details col-xxl-12 col-md-12 mt-4">
                                    <div class="col-xxl-6 col-md-6 bg-light pt-3 pb-4 ps-2 pe-2">
                                        <i class="ri-delete-bin-5-fill remove"></i>
                                        <div class="col-md-6 mt-3">
                                            <label for="bank_details_bank_name" class="form-label">Bank Name : <span class="text-danger">*</span></label>
                                            <input type="text" name="bank_details_bank_name[]" class="form-control" value="<?php echo htmlspecialcode_generator($bank_details_bank_name); ?>" id="bank_details_bank_name">
                                        </div>
                                        <div class="col-md-6 mt-3">
                                            <label for="bank_details_acc_no" class="form-label">Bank Account Number : <span class="text-danger">*</span></label>
                                            <input type="text" name="bank_details_acc_no[]" class="form-control" value="<?php echo $bank_details_acc_no; ?>" id="bank_details_acc_no">
                                        </div>
                                        <div class="col-md-6 mt-3">
                                            <label for="bank_details_benf_name" class="form-label">Benificiery Name : <span class="text-danger">*</span></label>
                                            <input type="text" name="bank_details_benf_name[]" class="form-control" value="<?php echo htmlspecialcode_generator($bank_details_benf_name); ?>" id="bank_details_benf_name">
                                        </div>
                                        <div class="col-md-6 mt-3">
                                            <label for="bank_details_ifsc_code" class="form-label">IFSC Code : <span class="text-danger">*</span></label>
                                            <input type="text" name="bank_details_ifsc_code[]" class="form-control" value="<?php echo $bank_details_ifsc_code; ?>" id="bank_details_ifsc_code">
                                        </div>
                                    </div>
                                </div>
                        <?php }
                        } ?>
                    </div>
                </div>

                <div class="card-header">
                    <h4 class="card-title mb-0">UPI Payments</h4>
                </div>
                <div class="ps-3">
                    <button class="btn btn-success mt-3" id="add_upi_payments" type="button">Add more</button>
                </div>
                <div class="card-body details_banner_section upi_payments">
                    <div class="row gy-4">
                        <?php
                        if ($payment_upi_result > 0) {
                            while ($row = mysqli_fetch_assoc($payment_upi_data)) {
                                $upi_payments_title = $row['title'];
                                $upi_payments_upi_no = $row['upi_no'];
                        ?>
                                <div class="details-block upi_payments col-xxl-12 col-md-12 mt-4">
                                    <div class="col-xxl-6 col-md-6 bg-light pt-3 pb-4 ps-2 pe-2">
                                        <i class="ri-delete-bin-5-fill remove"></i>
                                        <div class="col-md-6 mt-3">
                                            <label for="upi_payments_title" class="form-label">UPI Payement App : <span class="text-danger">*</span></label>
                                            <input type="text" name="upi_payments_title[]" class="form-control" value="<?php echo htmlspecialcode_generator($upi_payments_title); ?>" id="upi_payments_title">
                                        </div>
                                        <div class="col-md-6 mt-3">
                                            <label for="upi_payments_upi_no" class="form-label">UPI Number : <span class="text-danger">*</span></label>
                                            <input type="text" name="upi_payments_upi_no[]" class="form-control" value="<?php echo $upi_payments_upi_no; ?>" id="upi_payments_upi_no">
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
                'payment_main_title': "required",
                'payment_link': "required",
                'payment_title': "required",
                'payment_image': "required",
                'payment_bank_title': "required",
                'payment_upi_title': "required"
            },
        });

        $('#homepage').validate({
            rules: {
                'payment_main_title': "required",
                'payment_link': "required",
                'payment_title': "required",
                'payment_bank_title': "required",
                'payment_upi_title': "required"
            },
        });
    });
</script>

<script>
    $("#add_bank_details").click(function() {
        if ($(".details-block.bank_details").length < 100) {
            var html = '<div class="details-block bank_details col-xxl-12 col-md-12 mt-4"><div class="col-xxl-6 col-md-6 bg-light pt-3 pb-4 ps-2 pe-2"><i class="ri-delete-bin-5-fill remove"></i><div class="col-md-6 mt-3"><label for="bank_details_bank_name" class="form-label">Bank Name : <span class="text-danger">*</span></label><input type="text" name="bank_details_bank_name[]" class="form-control" id="bank_details_bank_name"></div><div class="col-md-6 mt-3"><label for="bank_details_acc_no" class="form-label">Bank Account Number : <span class="text-danger">*</span></label><input type="text" name="bank_details_acc_no[]" class="form-control" id="bank_details_acc_no"></div><div class="col-md-6 mt-3"><label for="bank_details_benf_name" class="form-label">Benificiery Name : <span class="text-danger">*</span></label><input type="text" name="bank_details_benf_name[]" class="form-control" id="bank_details_benf_name"></div><div class="col-md-6 mt-3"><label for="bank_details_ifsc_code" class="form-label">IFSC Code : <span class="text-danger">*</span></label><input type="text" name="bank_details_ifsc_code[]" class="form-control" id="bank_details_ifsc_code"></div></div></div>';

            $(".details_banner_section.bank_details .row.gy-4").append(html);
        }
    });
    $(document).on('click', ".details-block.bank_details .remove", function() {
        $(this).parent().parent().remove();
    });
</script>

<script>
    $("#add_upi_payments").click(function() {
        if ($(".details-block.upi_payments").length < 100) {
            var html = '<div class="details-block upi_payments col-xxl-12 col-md-12 mt-4"><div class="col-xxl-6 col-md-6 bg-light pt-3 pb-4 ps-2 pe-2"><i class="ri-delete-bin-5-fill remove"></i><div class="col-md-6 mt-3"><label for="upi_payments_title" class="form-label">UPI Payement App : <span class="text-danger">*</span></label><input type="text" name="upi_payments_title[]" class="form-control" id="upi_payments_title"></div><div class="col-md-6 mt-3"><label for="upi_payments_upi_no" class="form-label">UPI Number : <span class="text-danger">*</span></label><input type="text" name="upi_payments_upi_no[]" class="form-control" id="upi_payments_upi_no"></div></div></div>';

            $(".details_banner_section.upi_payments .row.gy-4").append(html);
        }
    });
    $(document).on('click', ".details-block.upi_payments .remove", function() {
        $(this).parent().parent().remove();
    });
</script>