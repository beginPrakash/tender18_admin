<?php include '../../includes/authentication.php';
?>
<?php $pages = 'city_content_individual'; ?>
<?php include '../../includes/header.php' ?>
<?php
$content_per = _get_user_perby_role($_SESSION['user_id'],'city_content_individual',$con);

if($_SESSION['role']!='admin' && $_SESSION['role']!='employee'){ 
    // echo "not admin ------>" . $_SESSION['role'];
    echo "<script>
            window.location.href='../index.php';
            </script>";
}elseif($_SESSION['role']=='employee' && $content_per!=1){ 
    // echo "not admin ------>" . $_SESSION['role'];
    echo "<script>
            window.location.href='../index.php';
            </script>";
}
?>

<style>
    td {
        white-space: pre-wrap;
    }
</style>
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">City Content Individual</h4>
        </div>
        
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <a class="dropdown-item fs-sm" href="<?php echo ADMIN_URL; ?>meta-content/index.php">
                    <h5 class="card-title float-end btn bg-success text-white">Back To Meta Content List</h5>
                </a>
                <!-- <h5 class="card-title mb-0">Basic Datatables</h5> -->
            </div>
            <div class="card-body">
                <table id="example" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width: 100%">
                    <thead>
                        <tr>
                            <th>SR No.</th>
                            <th>City Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        $fetching_data = mysqli_query($con, "SELECT * FROM tender_zipcodes group by city order by city asc");
                        while ($row = mysqli_fetch_assoc($fetching_data)) {
                        ?>
                            <tr>
                                <th scope="row"><?php echo $i; ?></th>
                                <td><?php echo $row['city']; ?></td>
                                <td class="action_element">
                                    <a href="<?php echo ADMIN_URL; ?>meta-content/city-content-individual/edit.php?id='<?php echo $row['id']; ?>'"><i style="font-size: 20px;" class="ri-pencil-fill text-success"></i>
                                    </a>
                                           
                                </td>
                            </tr>
                        <?php $i++;
                        } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!--end col-->
</div>

<?php include '../../includes/footer.php';  ?>
