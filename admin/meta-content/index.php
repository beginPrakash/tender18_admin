<?php include '../includes/authentication.php';
?>
<?php $pages = 'pages'; ?>
<?php include '../includes/header.php' ?>
<?php
$meta_per = _get_user_perby_role($_SESSION['user_id'],'meta_content',$con);

if($_SESSION['role']!='admin' && $_SESSION['role']!='employee'){ 
    // echo "not admin ------>" . $_SESSION['role'];
    echo "<script>
            window.location.href='../index.php';
            </script>";
}elseif($_SESSION['role']=='employee' && $meta_per!=1){ 
    // echo "not admin ------>" . $_SESSION['role'];
    echo "<script>
            window.location.href='../index.php';
            </script>";
}
if (isset($_GET['id'])) {
    if (!empty($_GET['id'])) {
        mysqli_query($con, "DELETE FROM `blogs` where id={$_GET['id']}");
        echo "<script>
            window.location.href='" . ADMIN_URL . "meta-content/index.php';
            </script>";
    }
}
?>
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
                        
                        <tr>
                            <th scope="row">11</th>
                            <td>Agency- State Tenders Meta Content</td>
                            <td><a href="<?php echo ADMIN_URL; ?>meta-content/agency-state-content.php"><i style="font-size: 20px;" class="ri-eye-fill text-success"></i></a></td>
                        </tr>

                        <tr>
                            <th scope="row">12</th>
                            <td>Agency- City Tenders Meta Content</td>
                            <td><a href="<?php echo ADMIN_URL; ?>meta-content/agency-city-content.php"><i style="font-size: 20px;" class="ri-eye-fill text-success"></i></a></td>
                        </tr>

                        <tr>
                            <th scope="row">13</th>
                            <td>Agency- Department (Source) Tenders Meta Content</td>
                            <td><a href="<?php echo ADMIN_URL; ?>meta-content/agency-source-content.php"><i style="font-size: 20px;" class="ri-eye-fill text-success"></i></a></td>
                        </tr>

                        <tr>
                            <th scope="row">14</th>
                            <td>Keyword- State Tenders Meta Content</td>
                            <td><a href="<?php echo ADMIN_URL; ?>meta-content/keyword-state-content.php"><i style="font-size: 20px;" class="ri-eye-fill text-success"></i></a></td>
                        </tr>

                        <tr>
                            <th scope="row">15</th>
                            <td>Keyword- City Tenders Meta Content</td>
                            <td><a href="<?php echo ADMIN_URL; ?>meta-content/keyword-city-content.php"><i style="font-size: 20px;" class="ri-eye-fill text-success"></i></a></td>
                        </tr>

                        <tr>
                            <th scope="row">16</th>
                            <td>Keyword- Agency Tenders Meta Content</td>
                            <td><a href="<?php echo ADMIN_URL; ?>meta-content/keyword-agency-content.php"><i style="font-size: 20px;" class="ri-eye-fill text-success"></i></a></td>
                        </tr>

                        <tr>
                            <th scope="row">17</th>
                            <td>Keyword- Department (Source) Tenders Meta Content</td>
                            <td><a href="<?php echo ADMIN_URL; ?>meta-content/keyword-source-content.php"><i style="font-size: 20px;" class="ri-eye-fill text-success"></i></a></td>
                        </tr>

                        <tr>
                            <th scope="row">18</th>
                            <td>City - Department (Source) Tenders Meta Content</td>
                            <td><a href="<?php echo ADMIN_URL; ?>meta-content/city-source-content.php"><i style="font-size: 20px;" class="ri-eye-fill text-success"></i></a></td>
                        </tr>

                        <tr>
                            <th scope="row">19</th>
                            <td>State - Department (Source) Tenders Meta Content</td>
                            <td><a href="<?php echo ADMIN_URL; ?>meta-content/state-source-content.php"><i style="font-size: 20px;" class="ri-eye-fill text-success"></i></a></td>
                        </tr>

                        <tr>
                            <th scope="row">20</th>
                            <td>Tender Type - State Tenders Meta Content</td>
                            <td><a href="<?php echo ADMIN_URL; ?>meta-content/tendertype-state-content.php"><i style="font-size: 20px;" class="ri-eye-fill text-success"></i></a></td>
                        </tr>

                        <tr>
                            <th scope="row">21</th>
                            <td>Tender Type - City Tenders Meta Content</td>
                            <td><a href="<?php echo ADMIN_URL; ?>meta-content/tendertype-city-content.php"><i style="font-size: 20px;" class="ri-eye-fill text-success"></i></a></td>
                        </tr>

                        <tr>
                            <th scope="row">22</th>
                            <td>Tender Type - Agency Tenders Meta Content</td>
                            <td><a href="<?php echo ADMIN_URL; ?>meta-content/tendertype-agency-content.php"><i style="font-size: 20px;" class="ri-eye-fill text-success"></i></a></td>
                        </tr>

                        <tr>
                            <th scope="row">23</th>
                            <td>Tender Type – Department (Souce) Tenders Meta Content</td>
                            <td><a href="<?php echo ADMIN_URL; ?>meta-content/tendertype-source-content.php"><i style="font-size: 20px;" class="ri-eye-fill text-success"></i></a></td>
                        </tr>
                        
                        <tr>
                            <th scope="row">24</th>
                            <td>Tender Type – Keyword Tenders Meta Content</td>
                            <td><a href="<?php echo ADMIN_URL; ?>meta-content/tendertype-keyword-content.php"><i style="font-size: 20px;" class="ri-eye-fill text-success"></i></a></td>
                        </tr>

                        <tr>
                            <th scope="row">25</th>
                            <td>Tender Detail City Meta Content</td>
                            <td><a href="<?php echo ADMIN_URL; ?>meta-content/tenderdetail-city-content.php"><i style="font-size: 20px;" class="ri-eye-fill text-success"></i></a></td>
                        </tr>

                        <tr>
                            <th scope="row">26</th>
                            <td>Tender Detail State Meta Content</td>
                            <td><a href="<?php echo ADMIN_URL; ?>meta-content/tenderdetail-state-content.php"><i style="font-size: 20px;" class="ri-eye-fill text-success"></i></a></td>
                        </tr>

                        <tr>
                            <th scope="row">27</th>
                            <td>Tender Detail Agency Meta Content</td>
                            <td><a href="<?php echo ADMIN_URL; ?>meta-content/tenderdetail-agency-content.php"><i style="font-size: 20px;" class="ri-eye-fill text-success"></i></a></td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!--end col-->
</div>

<?php include '../includes/footer.php';  ?>