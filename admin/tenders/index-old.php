<?php include '../includes/authentication.php';
?>
<?php $pages = 'tenders'; ?>
<?php include '../includes/header.php' ?>
<?php
if (isset($_GET['id'])) {
    $del = mysqli_query($con, "DELETE FROM `tenders_posts` where id='" . $_GET['id'] . "'");
    $status = true;
    if ($status) {
        $_SESSION['success'] = 'Deleted successfully.';
    } else {
        $_SESSION['error'] = 'Something went wrong.';
    }
}
if (isset($_POST['multi_selection_ids'])) {
    // print_r($_POST['multi_selection_ids']);
    $deleteID = explode(",", $_POST['multi_selection_ids']);
    foreach ($deleteID as $delID) {
        $del = mysqli_query($con, "DELETE FROM `tenders_posts` where id='" . $delID . "'");
        $status = true;
        if ($status) {
            $_SESSION['success'] = 'Deleted successfully.';
        } else {
            $_SESSION['error'] = 'Something went wrong.';
        }
    }
}
?>

<?php
$error_message = "";
$success_message = "";
if (isset($_POST['submit_excel'])) {
    // Check if the file was uploaded without errors
    if (isset($_FILES['excel_file']) && $_FILES['excel_file']['error'] === UPLOAD_ERR_OK) {

        // Define the directory where you want to save the uploaded file
        $uploadDirectory = '../uploads/tender_uploads/';

        // Get the uploaded file's information
        $uploadedFile = $_FILES['excel_file'];
        $fileName = $uploadedFile['name'];
        $fileTmpName = $uploadedFile['tmp_name'];

        // Check if the file is an Excel file (XLS or XLSX)
        $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);
        if (in_array($fileExt, ['xls', 'xlsx'])) {
            // Create a unique name for the uploaded file
            $newFileName = $uploadDirectory . uniqid() . '.' . $fileExt;

            // Move the uploaded file to the desired location
            if (move_uploaded_file($fileTmpName, $newFileName)) {
                // echo 'File uploaded successfully.';

                echo '<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
                <script>
                    $(document).ready(function () {
                        var filename = "' . $newFileName . '"; 
                        $.ajax({
                            type: "POST",
                            url: "tender_ajax.php",
                            data: { filename: filename },
                            success: function (response) {
                                // console.log(response);
                                $(".ajax_running").text("Uploaded successfully");
                                setTimeout(function(){ window.location.href="' . ADMIN_URL . '/tenders"; }, 2000);
                            }
                        });
                    });
                </script>';

                $success_message = 'Tenders uploaded successfully';
            } else {
                $error_message = 'Failed to move the uploaded file.';
            }
        } else {
            $error_message = 'Only Excel files (XLS or XLSX) are allowed.';
        }
    } else {
        $error_message = 'Error during file upload.';
    }
    // echo "<script>setTimeout(function(){ window.location.href='" . ADMIN_URL . "/tenders'; }, 2000);</script>";
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
                        window.location.href='" . ADMIN_URL . "/tenders';
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
                        window.location.href='" . ADMIN_URL . "/tenders';
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
            <h4 class="mb-sm-0">All Tenders</h4>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="row mb-4">
                    <div class="col col-2 float-end">
                        <a href="<?php echo ADMIN_URL; ?>agencies">
                            <h5 class="card-title btn w-100 bg-primary text-white">Add Agencies</h5>
                        </a>
                    </div>
                    <div class="col col-2 float-end">
                        <a href="<?php echo ADMIN_URL; ?>zipcodes">
                            <h5 class="card-title btn w-100 bg-primary text-white">Add Zipcodes</h5>
                        </a>
                    </div>
                </div>
                <div class="row">
                    <div class="col col-6 float-start text-start">
                        <form action="" method="POST" enctype="multipart/form-data" class="d-flex gap-3">
                            <input type="file" name="excel_file" class="form-control" accept=".xls, .xlsx">
                            <input type="submit" value="Upload Excel File" class="btn btn-primary" name="submit_excel">
                            <a href="error-logs.txt" class="text-danger w-75 d-flex align-items-center" download>Last uploaded error logs</a>
                        </form>
                        <?php
                        if (!empty($error_message))
                            echo '<p class="text-danger mt-2">' . $error_message . '</p>';
                        if (!empty($success_message))
                            echo '<p class="text-success ajax_running mt-2">Please wait...</p>';
                        ?>
                    </div>
                    <div class="col float-end text-end">
                        <a href="<?php echo ADMIN_URL; ?>tenders/add-tender.php">
                            <h5 class="card-title btn bg-success text-white">Add New Tender</h5>
                        </a>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col col-2 float-end">
                        <h5 class="card-title btn w-100 bg-danger text-white" id="multiple_delete">Multiple Delete</h5>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table id="example" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width: 100%">
                    <thead>
                        <tr>
                            <th class="table_head">SR No.</th>
                            <th>Title</th>
                            <th>Tender ID</th>
                            <!-- <th>Agency</th> -->
                            <th>City</th>
                            <th>State</th>
                            <th>Pincode</th>
                            <th>Due Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $tenders_data = mysqli_query($con, "SELECT * FROM `tenders_posts` order by `id` DESC");
                        $tenders_result = mysqli_num_rows($tenders_data);
                        if ($tenders_result > 0) {
                            $i = 1;
                            foreach ($tenders_data as $data) {
                        ?>
                                <tr>
                                    <th scope="row" class="table_body" data-id="<?php echo $data['id']; ?>"><?php echo $i; ?></th>
                                    <td width="100"><?php echo (strlen($data['title']) > 30 ? substr($data['title'], 0, 30) . "..." : $data['title']); ?></td>
                                    <td><?php echo $data['tender_id']; ?></td>
                                    <!-- <td><?php echo $data['agency_type']; ?></td> -->
                                    <td><?php echo $data['city']; ?></td>
                                    <td><?php echo $data['state']; ?></td>
                                    <td><?php echo $data['pincode']; ?></td>
                                    <td><?php echo date("M d, Y", strtotime($data['due_date'])); ?></td>
                                    <td class="action_element">
                                        <a href="<?php echo ADMIN_URL; ?>tenders/edit-tender.php?id=<?php echo $data['id']; ?>"><i style="font-size: 20px;" class="ri-pencil-fill text-success"></i></a> &nbsp | &nbsp
                                        <a href="<?php echo ADMIN_URL; ?>tenders?id=<?php echo $data['id']; ?>" class="delete" data-bs-toggle="modal" data-bs-target=".bs-example-modal-center"><i style="font-size: 20px;" class="ri-delete-bin-fill text-danger"></i></a>
                                    </td>
                                </tr>
                            <?php $i++;
                            }
                        } else { ?>
                            <tr>
                                <td colspan="9">No tenders found.</td>
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

<div class="modal fade bs-example-modal-center" tabindex="-1" id="multi_delete_model" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center p-5">
                <form method="post" action="">
                    <input type="hidden" name="multi_selection_ids" class="multi_selection_ids" value="">
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
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<?php include '../includes/footer.php';  ?>

<script>
    $('.action_element a.delete').click(function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        $('.bs-example-modal-center a.btn.btn-danger').prop('href', url);
    });
</script>

<script>
    $("#multiple_delete").click(function() {

        if ($(this).hasClass('active')) {

            var numberOfChecked = 0;
            numberOfChecked = $('input[name="multi_selection[]"]:checked').length;

            if (numberOfChecked === 0) {
                //console.log('null');
            } else {
                $('#multi_delete_model').modal('show');
                $('#multi_delete_model').addClass('show');
                var count = $('input[name="multi_selection[]"]:checked').length;
                var i = 1;
                var var_passout = "";
                $('input[name="multi_selection[]"]:checked').each(function() {
                    //console.log(this.value); 
                    if (i == count) {
                        var_passout += this.value;
                    } else {
                        var_passout += this.value + ",";
                    }
                    i++;
                });
                //console.log(var_passout);
                $('.multi_selection_ids').val(var_passout);
            }
            $('.table_head_select').remove();
            $('.table_body_select').remove();
            $(".user-profile-nav li:last-child").show();
        } else {
            $('<th class="table_head_select"></th>').insertBefore('.table_head');
            $('<td class="table_body_select"><input type="checkbox" name="multi_selection[]" class="multi_selection" value="yes"></td>').insertBefore('.table_body');
            $(".table_body").each(function() {
                $(this).parent().find('.multi_selection').val($(this).attr('data-id'));
            });
            $(".user-profile-nav li:last-child").hide();
        }
        $(this).toggleClass("active");
    });
</script>