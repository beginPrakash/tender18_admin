<?php include '../includes/authentication.php';
?>
<?php $pages = 'pages'; ?>
<?php include '../includes/header.php' ?>

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Pages</h4>
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
                            <th>Page Title</th>
                            <th>View</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">1</th>
                            <td>All Tenders Meta Content</td>
                            <td><a href="<?php echo ADMIN_URL; ?>meta-content/all-tenders-content.php"><i style="font-size: 20px;" class="ri-eye-fill text-success"></i></a></td>
                        </tr>
                        <tr>
                            <th scope="row">2</th>
                            <td>Live Tenders Meta Content</td>
                            <td><a href="<?php echo ADMIN_URL; ?>meta-content/live-tenders-content.php"><i style="font-size: 20px;" class="ri-eye-fill text-success"></i></a></td>
                        </tr>
                        <tr>
                            <th scope="row">3</th>
                            <td>New Tenders Meta Content</td>
                            <td><a href="<?php echo ADMIN_URL; ?>meta-content/new-tenders-content.php"><i style="font-size: 20px;" class="ri-eye-fill text-success"></i></a></td>
                        </tr>
                        <tr>
                            <th scope="row">4</th>
                            <td>Archive Tenders Meta Content</td>
                            <td><a href="<?php echo ADMIN_URL; ?>meta-content/archieve-tenders-content.php"><i style="font-size: 20px;" class="ri-eye-fill text-success"></i></a></td>
                        </tr>
                        <tr>
                            <th scope="row">5</th>
                            <td>City Meta Content</td>
                            <td><a href="<?php echo ADMIN_URL; ?>meta-content/city-content.php"><i style="font-size: 20px;" class="ri-eye-fill text-success"></i></a></td>
                        </tr>
                        <tr>
                            <th scope="row">6</th>
                            <td>Agency Meta Content</td>
                            <td><a href="<?php echo ADMIN_URL; ?>meta-content/agency-content.php"><i style="font-size: 20px;" class="ri-eye-fill text-success"></i></a></td>
                        </tr>
                        <tr>
                            <th scope="row">7</th>
                            <td>Keywords Meta Content</td>
                            <td><a href="<?php echo ADMIN_URL; ?>meta-content/keyword-content.php"><i style="font-size: 20px;" class="ri-eye-fill text-success"></i></a></td>
                        </tr>
                        <tr>
                            <th scope="row">8</th>
                            <td>City Meta Content Individual</td>
                            <td><a href="<?php echo ADMIN_URL; ?>meta-content/city-content-individual/index.php"><i style="font-size: 20px;" class="ri-eye-fill text-success"></i></a></td>
                        </tr>
                        <tr>
                            <th scope="row">9</th>
                            <td>Agency Meta Content Individual</td>
                            <td><a href="<?php echo ADMIN_URL; ?>meta-content/agency-content-individual/index.php"><i style="font-size: 20px;" class="ri-eye-fill text-success"></i></a></td>
                        </tr>
                        <tr>
                            <th scope="row">10</th>
                            <td>Keywords Meta Content Individual</td>
                            <td><a href="<?php echo ADMIN_URL; ?>meta-content/keywords-content-individual/index.php"><i style="font-size: 20px;" class="ri-eye-fill text-success"></i></a></td>
                        </tr>
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!--end col-->
</div>

<?php include '../includes/footer.php';  ?>