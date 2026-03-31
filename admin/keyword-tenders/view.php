<?php include '../includes/authentication.php';
?>
<?php $pages = 'all-tenders'; ?>
<?php include '../includes/header.php' ?>

<?php
if (!empty($_GET['id'])) {
    $id = $_GET['id'];
} else {
    echo "<script>window.location.href='" . ADMIN_URL . "/all-tenders/index.php';</script>";
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
            <h4 class="mb-sm-0">View Tender</h4>
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

$banner_data = mysqli_query($con, "SELECT * FROM `tenders_all` where id='" . $id . "'");
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
        
                <div class="card-header">
                    <h4 class="card-title mb-0">Tender Details</h4>
                </div>
                <div class="card-body">
                    <div class="row gy-4">
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-12">
                                <label for="title" class="form-label">Title : </label> <?php echo htmlspecialcode_generator(ucfirst($title)); ?>
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <label for="tender_id" class="form-label">Tender ID : </label> <?php echo $tender_id; ?>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <label for="agency_type" class="form-label">Agency : </label> <?php echo htmlspecialcode_generator(ucfirst($agency_type)); ?>
                        </div>
                        <?php if(!empty($description)){ ?>
                            <div class="col-xxl-12 col-md-12">
                                <label for="description" class="form-label">BOQ Title : </label> <?php echo htmlspecialcode_generator($description); ?>
                            </div>
                        <?php } ?>
                        <div class="col-xxl-12 col-md-12">
                            <label for="city" class="form-label">City : </label> <?php echo ucfirst($city); ?>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <label for="state" class="form-label">State : </label> <?php echo ucfirst($state); ?>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                                <label for="pincode" class="form-label">Pin code : </label> <?php echo $pincode; ?>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <label for="tender_value" class="form-label">Tender Value : </label> <?php echo $tender_value; ?>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <label for="publish_date" class="form-label">Publish Date : </label>  <?php echo date('M d, Y', strtotime($publish_date)); ?>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <label for="opening_date" class="form-label">Opening Date : </label>  <?php echo date('M d, Y', strtotime($opening_date)); ?>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <label for="due_date" class="form-label">Due Date : </label> <?php echo date('M d, Y', strtotime($due_date)); ?>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <label for="tender_fee" class="form-label">Tender Fee : </label>  <?php echo $tender_fee; ?>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <label for="tender_emd" class="form-label">Tender EMD :</label>  <?php echo $tender_emd; ?>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <?php $document_list = htmlspecialcode_generator($documents); 
                                if(!empty($document_list)){
                                    $explode_docs = explode(',',$document_list);
                                }
                            ?>
                            <label for="documents" class="form-label">Documents: </label> <br>
                            <?php 
                            if(count($explode_docs) > 0){
                                foreach($explode_docs as $key => $val){
                                    echo $val.'<br>';
                                }
                            }?>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="department" class="form-label">Department: </label>  <?php echo htmlspecialcode_generator($department); ?>
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="tender_type" class="form-label">Tender Type: </label> <?php echo htmlspecialcode_generator($tender_type); ?>
                            </div>
                        </div>
                    </div>
                </div>
           
        </div>
    </div>
</div>

<?php include "../includes/footer.php" ?>
