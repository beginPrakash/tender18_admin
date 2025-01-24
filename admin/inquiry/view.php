<?php include '../includes/authentication.php';
?>
<?php $pages = 'registration-form'; ?>
<?php include '../includes/header.php' ?>

<?php
if (!empty($_GET['id'])) {
    $id = $_GET['id'];
} else {
    echo "<script>window.location.href='" . ADMIN_URL . "/registration-form';</script>";
    die();
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
            <h4 class="mb-sm-0">Registration Single Data</h4>
        </div>
    </div>
</div>
<!-- end page title -->

<?php
$name = "";
$company_name = "";
$email = "";
$mobile = "";
$state = "";
$description = "";

$banner_data = mysqli_query($con, "SELECT * FROM `inquiries` where id='" . $id . "'");
$banner_result = mysqli_num_rows($banner_data);
if ($banner_result == 1) {
    while ($row = mysqli_fetch_assoc($banner_data)) {
        $name = $row['name'];
        $company_name = $row['company_name'];
        $email = $row['email'];
        $mobile = $row['mobile'];
        $state = $row['state'];
        $description = $row['description'];
        $created_at = $row['created_at'];
    }
}
?>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="row gy-4">
                    <div class="col-xxl-12 col-md-12">
                        <div class="col-md-6">
                            <label for="name" class="form-label"><b>Name :</b></label> <?php echo $name; ?>
                        </div>
                    </div>
                    <div class="col-xxl-12 col-md-12">
                        <div class="col-md-6">
                            <label for="company_name" class="form-label"><b>Company Name :</b></label> <?php echo htmlspecialcode_generator($company_name); ?>
                        </div>
                    </div>
                    <div class="col-xxl-12 col-md-12">
                        <div class="col-md-6">
                            <label for="email" class="form-label"><b>Email :</b></label> <?php echo $email; ?>
                        </div>
                    </div>
                    <div class="col-xxl-12 col-md-12">
                        <div class="col-md-6">
                            <label for="mobile" class="form-label"><b>Mobile :</b></label> <?php echo $mobile; ?>
                        </div>
                    </div>
                    <div class="col-xxl-12 col-md-12">
                        <div class="col-md-6">
                            <label for="state" class="form-label"><b>State :</b></label> <?php echo $state; ?>
                        </div>
                    </div>
                    <div class="col-xxl-12 col-md-12">
                        <div class="col-md-6">
                            <label for="description" class="form-label"><b>Description :</b></label> <?php echo htmlspecialcode_generator($description); ?>
                        </div>
                    </div>
                    <div class="col-xxl-12 col-md-12">
                        <div class="col-md-6">
                            <label for="description" class="form-label"><b>Created On :</b></label> <?php echo (new DateTime($created_at))->format('d-m-Y H:i:s'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include "../includes/footer.php" ?>