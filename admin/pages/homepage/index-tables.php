<?php include '../includes/authentication.php';
?>
<?php $pages = 'pages'; ?>
<?php include '../includes/header.php' ?>

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Homepage</h4>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <!-- <div class="card-header">
                <h5 class="card-title mb-0">Basic Datatables</h5>
            </div> -->
            <div class="card-body">
                <table id="example" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width: 100%">
                    <thead>
                        <tr>
                            <th>SR No.</th>
                            <th>Section Name</th>
                            <th>View</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">1</th>
                            <td>Banner</td>
                            <td><a href="<?php echo ADMIN_URL; ?>homepage/banner.php"><i style="font-size: 20px;" class="ri-eye-fill text-success"></i></a></td>
                        </tr>
                        <tr>
                            <th scope="row">2</th>
                            <td>Our Services</td>
                            <td><a href="<?php echo ADMIN_URL; ?>homepage/services.php"><i style="font-size: 20px;" class="ri-eye-fill text-success"></i></a></td>
                        </tr>
                        <tr>
                            <th scope="row">3</th>
                            <td>Live Tenders</td>
                            <td><a href="<?php echo ADMIN_URL; ?>homepage/tenders.php"><i style="font-size: 20px;" class="ri-eye-fill text-success"></i></a></td>
                        </tr>
                        <tr>
                            <th scope="row">4</th>
                            <td>About</td>
                            <td><a href="<?php echo ADMIN_URL; ?>homepage/about.php"><i style="font-size: 20px;" class="ri-eye-fill text-success"></i></a></td>
                        </tr>
                        <tr>
                            <th scope="row">5</th>
                            <td>Why Tender18?</td>
                            <td><a href="<?php echo ADMIN_URL; ?>homepage/why_section.php"><i style="font-size: 20px;" class="ri-eye-fill text-success"></i></a></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!--end col-->
</div>

<?php include '../includes/footer.php';  ?>