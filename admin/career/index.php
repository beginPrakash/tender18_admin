<?php include '../includes/authentication.php';
?>
<?php $pages = 'career'; ?>
<?php include '../includes/header.php' ?>

<?php
if (isset($_GET['id'])) {
    $del = mysqli_query($con, "DELETE FROM `career_posts` where id='" . $_GET['id'] . "'");
    $status = true;
    if ($status) {
        $_SESSION['success'] = 'Deleted successfully.';
    } else {
        $_SESSION['error'] = 'Something went wrong.';
    }
}
?>
<?php
if (!empty($_SESSION['success'])) {
    echo '<div class="alert alert-success alert-dismissible fade show mb-4 show msg_box" role="alert">
            <strong>' . $_SESSION['success'] . '</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
    $_SESSION['success'] = "";
    echo "
             <script>
                     setTimeout(function(){
                        window.location.href='" . ADMIN_URL . "/career';
                         document.querySelector('.msg_box').remove();
                     }, 3000);
                 
             </script>";
}
if (!empty($_SESSION['error'])) {
    echo '<div class="alert alert-danger alert-dismissible fade show mb-4 msg_box" role="alert">
            <strong> ' . $_SESSION['error'] . ' </strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
    $_SESSION['error'] = "";
    echo "
             <script>
                     setTimeout(function(){
                        window.location.href='" . ADMIN_URL . "/career';
                         document.querySelector('.msg_box').remove();
                     }, 3000);
                 
             </script>";
}

?>
<style>
    #example .action_element {
        display: flex;
        align-items: center;
    }
</style>

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">All Jobs</h4>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <a href="<?php echo ADMIN_URL; ?>career/add-job.php">
                    <h5 class="card-title float-end btn bg-success text-white">Add New Job</h5>
                </a>
            </div>
            <div class="card-body">
                <table id="example" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width: 100%">
                    <thead>
                        <tr>
                            <th>SR No.</th>
                            <th>Title</th>
                            <th>Job Status</th>
                            <th>Experience</th>
                            <th>Location</th>
                            <th>Anual Package</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $tenders_data = mysqli_query($con, "SELECT * FROM `career_posts` order by `id` DESC");
                        $tenders_result = mysqli_num_rows($tenders_data);
                        if ($tenders_result > 0) {
                            $i = 1;
                            foreach ($tenders_data as $data) {
                        ?>
                                <tr>
                                    <th scope="row"><?php echo $i; ?></th>
                                    <td><?php echo $data['title']; ?></td>
                                    <td><?php echo $data['job_status']; ?></td>
                                    <td><?php echo $data['experience']; ?></td>
                                    <td><?php echo $data['location']; ?></td>
                                    <td><?php echo $data['anual_package']; ?></td>
                                    <td class="action_element">
                                        <a href="<?php echo ADMIN_URL; ?>career/edit-job.php?id=<?php echo $data['id']; ?>"><i style="font-size: 20px;" class="ri-pencil-fill text-success"></i></a> &nbsp | &nbsp
                                        <a href="<?php echo ADMIN_URL; ?>career?id=<?php echo $data['id']; ?>" class="delete" data-bs-toggle="modal" data-bs-target=".bs-example-modal-center"><i style="font-size: 20px;" class="ri-delete-bin-fill text-danger"></i></a>
                                    </td>
                                </tr>
                            <?php $i++;
                            }
                        } else { ?>
                            <tr>
                                <td colspan="7">No jobs found.</td>
                            </tr>
                        <?php } ?>
                        <div class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-body text-center p-5">
                                        <i class="bi bi-exclamation-triangle text-warning display-5"></i>
                                        <div class="mt-4">
                                            <h4 class="mb-3">Are you sure you want to delete?</h4>
                                            <p class="text-muted mb-4">
                                                If you click the delete button, the data will be deleted from the site and you can not retrieve this data again.
                                            </p>
                                            <div class="hstack gap-2 justify-content-center">
                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                                    Close
                                                </button>
                                                <a href="javascript:void(0);" class="btn btn-danger">Delete</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.modal-content -->
                            </div>
                            <!-- /.modal-dialog -->
                        </div>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!--end col-->
</div>

<?php include '../includes/footer.php';  ?>

<script>
    $('.action_element a.delete').click(function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        $('.bs-example-modal-center a.btn.btn-danger').prop('href', url);
    });
</script>