<?php include '../includes/authentication.php';
?>
<?php $pages = 'users'; ?>
<?php include '../includes/header.php' ?>
<?php
if ($_SESSION['role'] != 'admin') {
    // echo "not admin ------>" . $_SESSION['role'];
    echo "<script>
            window.location.href='../index.php';
            </script>";
}
if (isset($_GET['id']) && isset($_GET['unique_code'])) {
    if (!empty($_GET['id']) && !empty($_GET['unique_code'])) {
        mysqli_query($con, "DELETE FROM `users` where user_id={$_GET['id']} AND user_unique_id={$_GET['unique_code']}");
        echo "<script>
            window.location.href='" . ADMIN_URL . "users';
            </script>";
    }
}
?>
<?php
if (isset($_GET['role'])) {
    $role = $_GET['role'];
} else {
    $role = "";
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
            <h4 class="mb-sm-0">Users</h4>
        </div>
        <div class="row mb-3">
            <div class="col-lg-2">
                <label for="user_role" class="form-label">Filters :</label>
                <select class="form-select" name="user_role" id="user_role">
                    <option value="">Select User Role </option>
                    <option value="admin" <?php if ($role == "admin") {
                                                echo "selected";
                                            } ?>>Admin</option>
                    <option value="employee" <?php if ($role == "employee") {
                                                    echo "selected";
                                                } ?>>Employee</option>
                    <!-- <option value="user" <?php if ($role == "user") {
                                                    echo "selected";
                                                } ?>>User</option> -->
                </select>
            </div>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <a class="dropdown-item fs-sm" href="<?php echo ADMIN_URL; ?>register">
                    <h5 class="card-title float-end btn bg-success text-white">Register New Users</h5>
                </a>
                <!-- <h5 class="card-title mb-0">Basic Datatables</h5> -->
            </div>
            <div class="card-body">
                <table id="example" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width: 100%">
                    <thead>
                        <tr>
                            <th>SR No.</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        if (!empty($role)) {
                            $query = "where user_role='$role' and user_role!='user'";
                        } else {
                            $query = "where user_role!='user'";
                        }
                        $fetching_users = mysqli_query($con, "SELECT * FROM users $query order by user_id desc");
                        while ($row = mysqli_fetch_assoc($fetching_users)) {
                        ?>
                            <tr>
                                <th scope="row"><?php echo $i; ?></th>
                                <td><?php echo $row['users_name']; ?></td>
                                <td><?php echo $row['users_email']; ?></td>
                                <td><?php echo ucfirst($row['user_role']); ?></td>
                                <td>
                                    <div class="dropdown d-inline-block">
                                        <button class="btn btn-subtle-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ri-more-fill align-middle"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a class="dropdown-item edit-item-btn" href="<?php echo ADMIN_URL; ?>users/edit.php?id='<?php echo $row['user_id']; ?>'&unique_code='<?php echo $row['user_unique_id']; ?>'"><i class="ri-pencil-fill align-bottom me-2 text-muted"></i>
                                                    Edit</a>
                                            </li>
                                            <li>
                                                <input type="hidden" value="<?php echo ADMIN_URL; ?>users?id='<?php echo $row['user_id']; ?>'&unique_code='<?php echo $row['user_unique_id']; ?>'" id="delete_id">
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
    $('#user_role').change(function() {
        var user_role = $(this).val();
        if (user_role)
            window.location.href = '<?php echo ADMIN_URL; ?>users?role=' + user_role;
        else
            window.location.href = '<?php echo ADMIN_URL; ?>users';
    });
</script>