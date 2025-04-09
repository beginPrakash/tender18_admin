<?php include '../includes/authentication.php';
?>
<?php $pages = 'inquiries'; ?>
<?php include '../includes/header.php' ?>

<?php
$inquiries_per = _get_user_perby_role($_SESSION['user_id'],'inquiries',$con);

if($_SESSION['role']!='admin' && $_SESSION['role']!='employee'){ 
    // echo "not admin ------>" . $_SESSION['role'];
    echo "<script>
            window.location.href='../index.php';
            </script>";
}elseif($_SESSION['role']=='employee' && $inquiries_per!=1){ 
    // echo "not admin ------>" . $_SESSION['role'];
    echo "<script>
            window.location.href='../index.php';
            </script>";
}

if (isset($_GET['id'])) {
    $del = mysqli_query($con, "DELETE FROM `inquiries` where id='" . $_GET['id'] . "'");
    $status = true;
    if ($status) {
        $_SESSION['success'] = 'Deleted successfully.';
    } else {
        $_SESSION['error'] = 'Something went wrong.';
    }
}
?>

<style>
    #example .action_element {
        display: flex;
        align-items: center;
    }
</style>
<?php
if (isset($_GET['ftype'])) {
    $ftype = $_GET['ftype'];
} else {
    $ftype = "";
}
?>
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">CMS Customer Data</h4>
        </div>
      
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <table id="example" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width: 100%">
                    <thead>
                        <tr>
                            <th>CMS Customer Id</th>
                            <th>Name</th>
                            <th>Company Name</th>
                            <th>Email</th>                           
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = '';
                        if (!empty($ftype)) {
                            $query = "where type='$ftype'";
                        }
                        $tenders_data = mysqli_query($con, "SELECT * FROM `cms_customer` $query order by `customer_id` ASC");
                        $tenders_result = mysqli_num_rows($tenders_data);
                        if ($tenders_result > 0) {
                            $i = 1;
                            foreach ($tenders_data as $data) {
                            ?>
                                <tr>
                                    <th scope="row"><?php echo $data['customer_id']; ?></th>
                                    <td><?php echo htmlspecialcode_generator($data['customer_name']); ?></td>
                                    <td><?php echo $data['company_name']; ?></td>
                                    <td><?php echo $data['email_ids']; ?></td>
                                    <td class="action_element">
                                       
                                            <a href="<?php echo ADMIN_URL; ?>cms_customer/view.php?id=<?php echo $data['id']; ?>"><i style="font-size: 20px;" class="ri-eye-fill text-success"></i></a>
                                        
                                    </td>
                                </tr>
                            <?php $i++;
                            }
                        } else { ?>
                            <tr>
                                <td colspan="8">No data found.</td>
                            </tr>
                        <?php } ?>
                       
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!--end col-->
</div>

<?php include '../includes/footer.php';  ?>
 