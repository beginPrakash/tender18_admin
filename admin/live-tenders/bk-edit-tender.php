<?php include '../includes/authentication.php';
?>
<?php $pages = 'live-tenders'; ?>
<?php include '../includes/header.php' ?>

<?php
if (!empty($_GET['id'])) {
    $id = $_GET['id'];
} else {
    echo "<script>window.location.href='" . ADMIN_URL . "/live-tenders/index.php';</script>";
    die();
}
?>

<?php
if (isset($_POST['submit'])) {
    $tenderID = $_POST['tenderID'];
    $title = mysqli_real_escape_string($con, $_POST['title']);
    $tender_id = $_POST['tender_id'];
    $agency_type = mysqli_real_escape_string($con, $_POST['agency_type']);
    $due_date = $_POST['due_date'];
    $tender_value = $_POST['tender_value'];
    $description = mysqli_real_escape_string($con, $_POST['description']);
    $pincode = $_POST['pincode'];
    $publish_date = $_POST['publish_date'];
    $tender_fee = $_POST['tender_fee'];
    $tender_emd = $_POST['tender_emd'];
    $documents = $_POST['documents'];
    $opening_date = $_POST['opening_date'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $department = mysqli_real_escape_string($con, $_POST['department']);
    $tender_type = mysqli_real_escape_string($con, $_POST['tender_type']);

    $timestamp = strtotime($publish_date);
    $publish_date = date("Y-m-d", $timestamp);

    $timestamp1 = strtotime($due_date);
    $due_date = date("Y-m-d", $timestamp1);

    $timestamp2 = strtotime($opening_date);
    $opening_date = date("Y-m-d", $timestamp2);


    $q1 = "UPDATE `tenders_live` SET `title`='$title', `tender_id`='$tender_id', `agency_type`='$agency_type', `due_date`='$due_date', `tender_value`='$tender_value', `description`='$description', `pincode`='$pincode', `publish_date`='$publish_date', `tender_fee`='$tender_fee', `tender_emd`='$tender_emd', `documents`='$documents', `opening_date`='$opening_date', `city`='$city', `state`='$state', `department`='$department', `tender_type`='$tender_type' WHERE `id`='$tenderID'";

    // var_dump($q1);
    // die();
    $sql1 = mysqli_query($con, $q1);

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
                        window.location.href='" . ADMIN_URL . "/live-tenders/index.php';
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
                        window.location.href='" . ADMIN_URL . "/live-tenders/index.php';
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
            <h4 class="mb-sm-0">Edit Tender</h4>
        </div>
    </div>
</div>
<!-- end page title -->

<?php
$tenderID = "";
$title = "";
$tender_id = "";
$agency_type = "";
$due_date = "";
$tender_value = "";
$description = "";
$pincode = "";
$publish_date = "";
$tender_fee = "";
$tender_emd = "";
$documents = "";
$opening_date = "";
$city = "";
$state = "";
$department = "";
$tender_type = "";

$banner_data = mysqli_query($con, "SELECT * FROM `tenders_live` where id='" . $id . "'");
$banner_result = mysqli_num_rows($banner_data);
if ($banner_result == 1) {
    while ($row = mysqli_fetch_assoc($banner_data)) {
        $tenderID = $row['id'];
        $title = $row['title'];
        $tender_id = $row['tender_id'];
        $agency_type = $row['agency_type'];
        $due_date = $row['due_date'];
        $tender_value = $row['tender_value'];
        $description = $row['description'];
        $pincode = $row['pincode'];
        $publish_date = $row['publish_date'];
        $tender_fee = $row['tender_fee'];
        $tender_emd = $row['tender_emd'];
        $documents = $row['documents'];
        $opening_date = $row['opening_date'];
        $city = $row['city'];
        $state = $row['state'];
        $department = $row['department'];
        $tender_type = $row['tender_type'];
    }
}
?>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <form action="" method="post" id="homepage" enctype="multipart/form-data">
                <input type="hidden" name="tenderID" value="<?php echo $tenderID; ?>">
                <div class="card-header">
                    <h4 class="card-title mb-0">Tender Details</h4>
                </div>
                <div class="card-body">
                    <div class="row gy-4">
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="title" class="form-label">Title : <span class="text-danger">*</span></label>
                                <input type="text" name="title" value="<?php echo htmlspecialcode_generator($title); ?>" class="form-control" id="title">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="tender_id" class="form-label">Tender ID : <span class="text-danger">*</span></label>
                                <input type="text" name="tender_id" value="<?php echo $tender_id; ?>" class="form-control" id="tender_id">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="agency_type" class="form-label">Agency : <span class="text-danger">*</span></label>
                                <input type="text" name="agency_type" value="<?php echo htmlspecialcode_generator($agency_type); ?>" class="form-control" id="agency_type">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="description" class="form-label">BOQ Title : <span class="text-danger">*</span></label>
                                <textarea rows="5" name="description" class="form-control" id="description"><?php echo htmlspecialcode_generator($description); ?></textarea>
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="city" class="form-label">City : <span class="text-danger">*</span></label>
                                <input type="text" name="city" value="<?php echo $city; ?>" class="form-control" id="city">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="state" class="form-label">State : <span class="text-danger">*</span></label>
                                <input type="text" name="state" value="<?php echo $state; ?>" class="form-control" id="state">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="pincode" class="form-label">Pin code : <span class="text-danger">*</span></label>
                                <input type="text" name="pincode" value="<?php echo $pincode; ?>" class="form-control" id="pincode">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="tender_value" class="form-label">Tender Value : <span class="text-danger">*</span></label>
                                <input type="text" name="tender_value" value="<?php echo $tender_value; ?>" class="form-control" id="tender_value">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="publish_date" class="form-label">Publish Date : <span class="text-danger">*</span></label>
                                <input type="text" name="publish_date" class="form-control flatpickr-input" data-provider="flatpickr" data-date-format="M d, Y" data-default-date="<?php echo date('M d, Y', strtotime($publish_date)); ?>" readonly="readonly" id="publish_date">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="opening_date" class="form-label">Opening Date : <span class="text-danger">*</span></label>
                                <input type="text" name="opening_date" class="form-control flatpickr-input" data-provider="flatpickr" data-date-format="M d, Y" data-default-date="<?php echo date('M d, Y', strtotime($opening_date)); ?>" readonly="readonly" id="opening_date">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="due_date" class="form-label">Due Date : <span class="text-danger">*</span></label>
                                <input type="text" name="due_date" class="form-control flatpickr-input" data-provider="flatpickr" data-date-format="M d, Y" data-default-date="<?php echo date('M d, Y', strtotime($due_date)); ?>" readonly="readonly" id="due_date">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="tender_fee" class="form-label">Tender Fee : <span class="text-danger">*</span></label>
                                <input type="text" name="tender_fee" value="<?php echo $tender_fee; ?>" class="form-control" id="tender_fee">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="tender_emd" class="form-label">Tender EMD : <span class="text-danger">*</span></label>
                                <input type="text" name="tender_emd" class="form-control" value="<?php echo $tender_emd; ?>" id="tender_emd">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="documents" class="form-label">Documents: <span class="text-danger">*</span></label>
                                <textarea rows="5" name="documents" class="form-control" id="documents"><?php echo $documents; ?></textarea>
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="department" class="form-label">Department: <span class="text-danger">*</span></label>
                                <input type="text" name="department" class="form-control" id="department" value="<?php echo htmlspecialcode_generator($department); ?>">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="tender_type" class="form-label">Tender Type: </label>
                                <input type="text" name="tender_type" class="form-control" id="tender_type" value="<?php echo htmlspecialcode_generator($tender_type); ?>">
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
                'tender_id': "required",
                'agency_type': "required",
                'location': "required",
                'due_date': "required",
                'tender_value': "required",
                // 'description': "required",
                // 'pincode': "required",
                'publish_date': "required",
                'tender_fee': "required",
                'tender_emd': "required",
                'opening_date': "required",
                // 'city': "required",
                'state': "required",
                'department': "required",
                // 'tender_type': "required",
                'documents': "required"
            },
        });
    });
</script>