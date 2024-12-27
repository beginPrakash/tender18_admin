<?php include '../includes/authentication.php';
?>
<?php $pages = 'departments'; ?>
<?php include '../includes/header.php' ?>
<?php
$dept_per = _get_user_perby_role($_SESSION['user_id'],'departments',$con);

if($_SESSION['role']!='admin' && $_SESSION['role']!='employee'){ 
    // echo "not admin ------>" . $_SESSION['role'];
    echo "<script>
            window.location.href='../index.php';
            </script>";
}elseif($_SESSION['role']=='employee' && $dept_per!=1){ 
    // echo "not admin ------>" . $_SESSION['role'];
    echo "<script>
            window.location.href='../index.php';
            </script>";
}
if (isset($_GET['id'])) {
    if (!empty($_GET['id'])) {
        mysqli_query($con, "DELETE FROM `departments` where id={$_GET['id']}");
        echo "<script>
            window.location.href='" . ADMIN_URL . "departments/index.php';
            </script>";
    }
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
            <h4 class="mb-sm-0">Departments</h4>
        </div>
        
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <a class="dropdown-item fs-sm" href="<?php echo ADMIN_URL; ?>departments/add.php">
                    <h5 class="card-title float-end btn bg-success text-white">Add New departments</h5>
                </a>
                <!-- <h5 class="card-title mb-0">Basic Datatables</h5> -->
            </div>
            <div class="card-body">
                <table id="example" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width: 100%">
                    <thead>
                        <tr>
                            <th>SR No.</th>
                            <th>Department Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        $fetching_data = mysqli_query($con, "SELECT * FROM departments order by name asc");
                        while ($row = mysqli_fetch_assoc($fetching_data)) {
                        ?>
                            <tr>
                                <th scope="row"><?php echo $i; ?></th>
                                <td><?php echo $row['name']; ?></td>
                                <td>
                                    <div class="dropdown d-inline-block">
                                        <button class="btn btn-subtle-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ri-more-fill align-middle"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a class="dropdown-item edit-item-btn" href="<?php echo ADMIN_URL; ?>departments/edit.php?id='<?php echo $row['id']; ?>'"><i class="ri-pencil-fill align-bottom me-2 text-muted"></i>
                                                    Edit</a>
                                            </li>
                                            <li>
                                                <input type="hidden" value="<?php echo ADMIN_URL; ?>departments/index.php?id='<?php echo $row['id']; ?>'" id="delete_id">
                                                <a class="dropdown-item remove-item-btn" data-bs-toggle="modal" data-bs-target=".bs-example-modal-center">
                                                    <i class=" ri-delete-bin-fill align-bottom me-2 text-muted"></i>
                                                    Delete
                                                </a>

                                            </li>
                                        </ul>
                                    </div>
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
<div class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center p-5">
                <i class="bi bi-exclamation-triangle text-warning display-5"></i>
                <div class="mt-4">
                    <h4 class="mb-3">Are you sure you want to delete!</h4>
                    <div class="hstack gap-2 justify-content-center">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <a href="" id="delete_id_modal" class="btn btn-danger">Yes</a>
                    </div>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<?php include '../includes/footer.php';  ?>

<script>
    $('.remove-item-btn').click(function() {
        var url = $(this).parent().find('#delete_id').val();
        $("#delete_id_modal").attr('href', url);
    });
</script>