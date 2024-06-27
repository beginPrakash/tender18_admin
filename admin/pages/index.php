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
                            <td>Homepage</td>
                            <td><a href="<?php echo ADMIN_URL; ?>pages/homepage"><i style="font-size: 20px;" class="ri-eye-fill text-success"></i></a></td>
                        </tr>
                        <!-- <tr>
                            <th scope="row">2</th>
                            <td>Testimonials</td>
                            <td><a href="<?php echo ADMIN_URL; ?>testimonials"><i style="font-size: 20px;" class="ri-eye-fill text-success"></i></a></td>
                        </tr> -->
                        <tr>
                            <th scope="row">2</th>
                            <td>Partners</td>
                            <td><a href="<?php echo ADMIN_URL; ?>pages/partners"><i style="font-size: 20px;" class="ri-eye-fill text-success"></i></a></td>
                        </tr>
                        <tr>
                            <th scope="row">3</th>
                            <td>Tender Bidding Support</td>
                            <td><a href="<?php echo ADMIN_URL; ?>pages/tender-bidding-support"><i style="font-size: 20px;" class="ri-eye-fill text-success"></i></a></td>
                        </tr>
                        <tr>
                            <th scope="row">4</th>
                            <td>About Us</td>
                            <td><a href="<?php echo ADMIN_URL; ?>pages/about-us"><i style="font-size: 20px;" class="ri-eye-fill text-success"></i></a></td>
                        </tr>
                        <tr>
                            <th scope="row">5</th>
                            <td>Payment</td>
                            <td><a href="<?php echo ADMIN_URL; ?>pages/payment"><i style="font-size: 20px;" class="ri-eye-fill text-success"></i></a></td>
                        </tr>
                        <tr>
                            <th scope="row">6</th>
                            <td>Digital Signature Certificate</td>
                            <td><a href="<?php echo ADMIN_URL; ?>pages/digital-signature-certificate"><i style="font-size: 20px;" class="ri-eye-fill text-success"></i></a></td>
                        </tr>
                        <tr>
                            <th scope="row">7</th>
                            <td>Terms & Conditions</td>
                            <td><a href="<?php echo ADMIN_URL; ?>pages/terms-and-conditions"><i style="font-size: 20px;" class="ri-eye-fill text-success"></i></a></td>
                        </tr>
                        <tr>
                            <th scope="row">8</th>
                            <td>All Tenders</td>
                            <td><a href="<?php echo ADMIN_URL; ?>pages/all-tenders"><i style="font-size: 20px;" class="ri-eye-fill text-success"></i></a></td>
                        </tr>
                        <tr>
                            <th scope="row">9</th>
                            <td>New Tenders</td>
                            <td><a href="<?php echo ADMIN_URL; ?>pages/new-tenders"><i style="font-size: 20px;" class="ri-eye-fill text-success"></i></a></td>
                        </tr>
                        <tr>
                            <th scope="row">10</th>
                            <td>Live Tenders</td>
                            <td><a href="<?php echo ADMIN_URL; ?>pages/live-tenders"><i style="font-size: 20px;" class="ri-eye-fill text-success"></i></a></td>
                        </tr>
                        <tr>
                            <th scope="row">11</th>
                            <td>Archive Tenders</td>
                            <td><a href="<?php echo ADMIN_URL; ?>pages/archive-tenders"><i style="font-size: 20px;" class="ri-eye-fill text-success"></i></a></td>
                        </tr>
                        <tr>
                            <th scope="row">12</th>
                            <td>FAQs</td>
                            <td><a href="<?php echo ADMIN_URL; ?>pages/faqs"><i style="font-size: 20px;" class="ri-eye-fill text-success"></i></a></td>
                        </tr>
                        <tr>
                            <th scope="row">13</th>
                            <td>Login / Register</td>
                            <td><a href="<?php echo ADMIN_URL; ?>pages/login-register"><i style="font-size: 20px;" class="ri-eye-fill text-success"></i></a></td>
                        </tr>
                        <!-- <tr>
                            <th scope="row">14</th>
                            <td>Career</td>
                            <td><a href="<?php echo ADMIN_URL; ?>pages/career"><i style="font-size: 20px;" class="ri-eye-fill text-success"></i></a></td>
                        </tr> -->
                        <tr>
                            <th scope="row">14</th>
                            <td>GEM Registration</td>
                            <td><a href="<?php echo ADMIN_URL; ?>pages/gem-registration"><i style="font-size: 20px;" class="ri-eye-fill text-success"></i></a></td>
                        </tr>
                        <tr>
                            <th scope="row">15</th>
                            <td>Tender Information Service</td>
                            <td><a href="<?php echo ADMIN_URL; ?>pages/tender-information-service"><i style="font-size: 20px;" class="ri-eye-fill text-success"></i></a></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!--end col-->
</div>

<?php include '../includes/footer.php';  ?>