<?php include '../includes/authentication.php';
?>
<?php $pages = 'cms_customer'; ?>
<?php include '../includes/header.php' ?>

<?php
if (!empty($_GET['id'])) {
    $id = $_GET['id'];
} else {
    echo "<script>window.location.href='" . ADMIN_URL . "/cms_customer';</script>";
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
            <h4 class="mb-sm-0">CMS Customer Detail</h4>
        </div>
    </div>
</div>
<!-- end page title -->

<?php
$customer_id = "";
$company_name = "";
$customer_name = "";
$email_ids = "";
$mail_type = "";
$keywords = "";
$words = "";
$not_used_keywords = "";
$filter_city = "";
$filter_state = "";
$filter_tender_value = "";
$filter_agency = "";
$filter_type = "";
$sender_email_id = "";
$reply_email_id = "";

$banner_data = mysqli_query($con, "SELECT * FROM `cms_customer` where id='" . $id . "'");
$banner_result = mysqli_num_rows($banner_data);
if ($banner_result == 1) {
    while ($row = mysqli_fetch_assoc($banner_data)) {
        $customer_id = $row['customer_id'];
        $company_name = $row['company_name'];
        $customer_name = $row['customer_name'];
        $email_ids = $row['email_ids'];
        $mail_type = $row['mail_type'];
        $keywords = $row['keywords'];
        $words = $row['words'];
        $not_used_keywords = $row['not_used_keywords'];
        $filter_city = $row['filter_city'];
        $filter_state = $row['filter_state'];
        $filter_tender_value = $row['filter_tender_value'];
        $filter_agency = $row['filter_agency'];
        $filter_department = $row['filter_department'];
        $filter_type = $row['filter_type'];
        $sender_email_id = $row['sender_email_id'];
        $reply_email_id = $row['reply_email_id'];
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
                            <label for="name" class="form-label"><b>CMS Customer ID :</b></label> <?php echo $customer_id; ?>
                        </div>
                    </div>
                    <div class="col-xxl-12 col-md-12">
                        <div class="col-md-6">
                            <label for="name" class="form-label"><b>Customer Name :</b></label> <?php echo $customer_name; ?>
                        </div>
                    </div>
                    <div class="col-xxl-12 col-md-12">
                        <div class="col-md-6">
                            <label for="company_name" class="form-label"><b>Company Name :</b></label> <?php echo htmlspecialcode_generator($company_name); ?>
                        </div>
                    </div>
                    <div class="col-xxl-12 col-md-12">
                        <div class="col-md-6">
                            <label for="email" class="form-label"><b>Email Ids:</b></label> <?php echo $email_ids; ?>
                        </div>
                    </div>
                    <div class="col-xxl-12 col-md-12">
                        <div class="col-md-6">
                            <label for="mobile" class="form-label"><b>Mail Type :</b></label> <?php echo $mail_type; ?>
                        </div>
                    </div>
                    <div class="col-xxl-12 col-md-12">
                        <div class="col-md-6">
                            <label for="state" class="form-label"><b>Keywords :</b></label> <?php echo $keywords; ?>
                        </div>
                    </div>
                    <div class="col-xxl-12 col-md-12">
                        <div class="col-md-6">
                            <label for="description" class="form-label"><b>Words :</b></label> <?php echo $words; ?>
                        </div>
                    </div>
                    <div class="col-xxl-12 col-md-12">
                        <div class="col-md-6">
                            <label for="description" class="form-label"><b>Not Used Keywords :</b></label> <?php echo $not_used_keywords; ?>
                        </div>
                    </div>
                    <div class="col-xxl-12 col-md-12">
                        <div class="col-md-6">
                            <label for="description" class="form-label"><b>Filter City :</b></label> <?php echo $filter_city; ?>
                        </div>
                    </div>
                    <div class="col-xxl-12 col-md-12">
                        <div class="col-md-6">
                            <label for="description" class="form-label"><b>Filter State :</b></label> <?php echo $filter_state; ?>
                        </div>
                    </div>
                    <div class="col-xxl-12 col-md-12">
                        <div class="col-md-6">
                            <label for="description" class="form-label"><b>Filter Tender Value :</b></label> <?php echo $filter_tender_value; ?>
                        </div>
                    </div>
                    <div class="col-xxl-12 col-md-12">
                        <div class="col-md-6">
                            <label for="description" class="form-label"><b>Filter Agency :</b></label> <?php echo $filter_agency; ?>
                        </div>
                    </div>
                    <div class="col-xxl-12 col-md-12">
                        <div class="col-md-6">
                            <label for="description" class="form-label"><b>Filter Department :</b></label> <?php echo $filter_department; ?>
                        </div>
                    </div>
                    <div class="col-xxl-12 col-md-12">
                        <div class="col-md-6">
                            <label for="description" class="form-label"><b>Filter Type :</b></label> <?php echo $filter_type; ?>
                        </div>
                    </div>
                    <div class="col-xxl-12 col-md-12">
                        <div class="col-md-6">
                            <label for="description" class="form-label"><b>Sender Email Id :</b></label> <?php echo $sender_email_id; ?>
                        </div>
                    </div>
                    <div class="col-xxl-12 col-md-12">
                        <div class="col-md-6">
                            <label for="description" class="form-label"><b>Reply Email Id :</b></label> <?php echo $reply_email_id; ?>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>

<?php include "../includes/footer.php" ?>