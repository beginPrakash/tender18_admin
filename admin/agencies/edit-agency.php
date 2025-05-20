<?php include '../includes/authentication.php';
?>
<?php $pages = 'agencies'; ?>
<?php include '../includes/header.php' ?>

<?php
if (!empty($_GET['id'])) {
    $id = $_GET['id'];
} else {
    echo "<script>window.location.href='" . ADMIN_URL . "/agencies/index.php';</script>";
    die();
}
?>

<?php
if (isset($_POST['submit'])) {
    $tenderID = $_POST['tenderID'];
    $title = mysqli_real_escape_string($con, $_POST['title']);
    $ref_no = mysqli_real_escape_string($con, $_POST['ref_no']);

    $q1 = "UPDATE `tender_agencies` SET `pseudo_name`='$title', `agency_name`='$ref_no' WHERE `id`='$tenderID'";

    // var_dump($q1);
    // die();
    $sql1 = mysqli_query($con, $q1);

    $log_qu = "INSERT INTO agency_log(`pseudo_name`, `agency_name`,`agency_id`,`action_type`) VALUES ('$title', '$ref_no',$tenderID,'update')";
    mysqli_query($con, $log_qu);

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
                        window.location.href='" . ADMIN_URL . "/agencies/index.php';
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
                        window.location.href='" . ADMIN_URL . "/agencies/index.php';
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
            <h4 class="mb-sm-0">Edit Agency</h4>
        </div>
    </div>
</div>
<!-- end page title -->

<?php
$tenderID = "";
$title = "";
$ref_no = "";

$banner_data = mysqli_query($con, "SELECT * FROM `tender_agencies` where id='" . $id . "'");
$banner_result = mysqli_num_rows($banner_data);
if ($banner_result == 1) {
    while ($row = mysqli_fetch_assoc($banner_data)) {
        $tenderID = $row['id'];
        $title = $row['pseudo_name'];
        $ref_no = $row['agency_name'];
    }
}
?>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <form action="" method="post" id="homepage" enctype="multipart/form-data">
                <input type="hidden" name="tenderID" value="<?php echo $tenderID; ?>">
                <div class="card-header">
                    <h4 class="card-title mb-0">Agency Details</h4>
                </div>
                <div class="card-body">
                    <div class="row gy-4">
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="title" class="form-label">Pseudo Name : <span class="text-danger">*</span></label>
                                <input type="text" name="title" value="<?php echo htmlspecialcode_generator($title); ?>" class="form-control" id="title">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="ref_no" class="form-label">Agency Name : <span class="text-danger">*</span></label>
                                <input type="text" name="ref_no" value="<?php echo htmlspecialcode_generator($ref_no); ?>" class="form-control" id="ref_no">
                            </div>
                        </div>
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

<?php include "../includes/footer.php" ?>

<script>
    $(document).ready(function() {
        $('#homepage').validate({
            rules: {
                'title': "required",
                'ref_no': "required"
            },
        });
    });
</script>