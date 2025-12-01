<?php include '../includes/authentication.php';
?>
<?php $pages = 'live-tenders'; ?>
<?php include '../includes/header.php' ?>
<?php
if (isset($_POST['te_id'])) {
    $tes_id = $_POST['te_id'];
    $tend_data = mysqli_query($con, "SELECT * FROM `tenders_live` where id='" . $tes_id . "'");
    $tend_result = mysqli_num_rows($tend_data);
    
    if ($tend_result == 1) {
        while ($row = mysqli_fetch_assoc($tend_data)) {
            $ref_no = $row['ref_no'];
            $del = mysqli_query($con, "DELETE FROM `tenders_all` where ref_no='" . $ref_no . "'");
        }
    }
    $del = mysqli_query($con, "DELETE FROM `tenders_live` where id='" . $tes_id . "'");
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
        $tend_data = mysqli_query($con, "SELECT * FROM `tenders_live` where id='" . $delID . "'");
        $tend_result = mysqli_num_rows($tend_data);
        
        if ($tend_result == 1) {
            while ($row = mysqli_fetch_assoc($tend_data)) {
                $ref_no = $row['ref_no'];
                $del = mysqli_query($con, "DELETE FROM `tenders_all` where ref_no='" . $ref_no . "'");
            }
        }
        $del = mysqli_query($con, "DELETE FROM `tenders_live` where id='" . $delID . "'");
        $status = true;
        if ($status) {
            $_SESSION['success'] = 'Deleted successfully.';
        } else {
            $_SESSION['error'] = 'Something went wrong.';
        }
    }
}
if (isset($_POST['move'])) {
    //echo "come";die();
    $move = mysqli_query($con, "INSERT INTO `tenders_archive` (title, tender_id, ref_no, agency_type, due_date, tender_value, description, pincode, publish_date, tender_fee, tender_emd, documents, city, state, department, tender_type, opening_date, created_at, updated_at)
    SELECT title, tender_id, ref_no, agency_type, due_date, tender_value, description, pincode, publish_date, tender_fee, tender_emd, documents, city, state, department, tender_type, opening_date, created_at, updated_at 
    FROM `tenders_live` where due_date < CURDATE();");

    $status = true;
    if ($status) {
        if ($move) {
            $affected_rows = mysqli_affected_rows($con);
            if ($affected_rows > 0) {
                $_SESSION['success'] = 'Moved successfully.';
            } else {
                $_SESSION['error'] = 'No records moved.';
            }
        }
    } else {
        $_SESSION['error'] = 'Something went wrong.';
    }
    $trun = mysqli_query($con, "DELETE FROM `tenders_live` WHERE due_date < CURDATE()");
}
?>
<?php
if (isset($_GET['page-limit']) && !empty($_GET['page-limit'])) {
    $_SESSION['page_limit'] = $_GET['page-limit'];
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
                        window.location.href='" . ADMIN_URL . "/live-tenders/index.php';
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
                        window.location.href='" . ADMIN_URL . "/live-tenders/index.php';
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

    td {
        white-space: pre-wrap;
    }

    .filter_upload {
        width: 30%;
    }
</style>

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">All Live Tenders</h4>
        </div>
    </div>
</div>
<!-- end page title -->
<form id="postForm" method="POST" action="" style="display:none;">
    <input type="hidden" name="te_id" id="te_id" value="">
</form>

<form id="movepostForm" method="POST" action="" style="display:none;">
    <input type="hidden" name="move"value="">
</form>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <?php
            if (isset($_GET['search_term']) && !empty($_GET['search_term']) && isset($_GET['upload_date']) && !empty($_GET['upload_date'])) {
                $search = $_GET['search_term'];
                $upload_date = $_GET['upload_date'];
                $arr = explode(' to ', $upload_date);
                $from = date('Y-m-d 00:00:00', strtotime($arr[0]));
                $to = date('Y-m-d 00:00:00', strtotime($arr[1]));
                $condition = "where (title like '%$search%' or ref_no like '%$search%' or tender_id like '%$search%' or city like '%$search%' or state like '%$search%') and created_at BETWEEN '" . $from . "' AND '" . $to . "'";
            } else if (isset($_GET['search_term']) && !empty($_GET['search_term'])) {
                $search = $_GET['search_term'];
                $upload_date = "";
                $condition = "where (title like '%$search%' or ref_no like '%$search%' or tender_id like '%$search%' or city like '%$search%' or state like '%$search%')";
            } else if (isset($_GET['upload_date']) && !empty($_GET['upload_date'])) {
                $search = "";
                $upload_date = $_GET['upload_date'];
                $arr = explode(' to ', $upload_date);
                $from = date('Y-m-d 00:00:00', strtotime($arr[0]));
                $to = date('Y-m-d 00:00:00', strtotime($arr[1]));
                $condition = "where created_at BETWEEN '" . $from . "' AND '" . $to . "'";
            } else {
                $search = "";
                $upload_date = "";
                $condition = "";
            }
            ?>
            <div class="card-header">
                <!--<div class="row mb-4">
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
                </div>-->
                <div class="row mt-2">
                    <div class="col col-2">
                        <h5 class="card-title btn w-100 bg-danger text-white" id="multiple_delete">Multiple Delete</h5>
                    </div>
                    <div class="col col-2 float-end">
                        <a href="#" class="move" data-bs-toggle="modal" data-bs-target=".bs-example-modal-center-01">
                            <h5 class="card-title btn w-100 bg-primary text-white">Move to Archive Tenders</h5>
                        </a>
                    </div>
                    <!--<div class="col col-3 filter_upload">
                        <form class="d-flex align-items-center">
                            <?php if (isset($_GET['search_term']) && !empty($_GET['search_term'])) { ?>
                                <input type="hidden" name="search_term" value="<?php echo $_GET['search_term']; ?>">
                            <?php } ?>
                            <label for="upload_date" class="form-label text-nowrap mx-2">Upload Date :</label>
                            <input type="text" name="upload_date" class="form-control flatpickr-input" data-provider="flatpickr" data-date-format="d M, Y" data-range-date="true" data-default-date="<?php echo $upload_date; ?>" readonly="readonly" id="upload_date">
                            <button type="submit" class="btn btn-primary mx-2">Filter</button>
                        </form>
                    </div>-->
                </div>
            </div>
            <div class="card-body dataTables_wrapper pt-0">
                <div id="example_filter" class="dataTables_filter">
                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <div class="dataTables_length d-flex align-items-center h-100" id="example_length">
                                <form>
                                    <label>
                                        Show
                                        <select name="page-limit" aria-controls="example" onchange="this.form.submit()" class="form-select form-select-sm">
                                            <option value="10" <?php if (isset($_SESSION['page_limit']) && !empty($_SESSION['page_limit']) && $_SESSION['page_limit'] == 10) {
                                                                    echo "selected";
                                                                } ?>>10</option>
                                            <option value="25" <?php if (isset($_SESSION['page_limit']) && !empty($_SESSION['page_limit']) && $_SESSION['page_limit'] == 25) {
                                                                    echo "selected";
                                                                } ?>>25</option>
                                            <option value="50" <?php if (isset($_SESSION['page_limit']) && !empty($_SESSION['page_limit']) && $_SESSION['page_limit'] == 50) {
                                                                    echo "selected";
                                                                } ?>>50</option>
                                            <option value="100" <?php if (isset($_SESSION['page_limit']) && !empty($_SESSION['page_limit']) && $_SESSION['page_limit'] == 100) {
                                                                    echo "selected";
                                                                } ?>>100</option>
                                        </select>
                                        entries
                                    </label>
                                </form>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <form class="mb-3 d-flex gap-2 justify-content-end">
                                <?php if (isset($_GET['upload_date']) && !empty($_GET['upload_date'])) { ?>
                                    <input type="hidden" name="upload_date" value="<?php echo $_GET['upload_date']; ?>">
                                <?php } ?>
                                <label>
                                    Search:
                                    <input type="search" name="search_term" value="<?php echo $search; ?>" class="form-control form-control" placeholder="" aria-controls="example">
                                </label>
                                <button type="submit" class="btn btn-primary">Search</button>
                            </form>
                        </div>
                    </div>
                </div>
                <table class="table table-bordered dt-responsive dataTable nowrap table-striped align-middle" style="width: 100%">
                    <thead>
                        <tr>
                            <th class="table_head">SR No.</th>
                            <th>Title</th>
                            <th>Ref No.</th>
                            <th>Tender ID</th>
                            <!-- <th>Agency</th> -->
                            <th>City</th>
                            <!-- <th>State</th> -->
                            <th>Due Date</th>
                            <th>Upload Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (isset($_SESSION['page_limit']) && !empty($_SESSION['page_limit'])) {
                            $limit = $_SESSION['page_limit'];
                        } else {
                            $limit = 10;
                        }
                        $total_query = mysqli_fetch_array(mysqli_query($con, "SELECT COUNT(*) FROM `tenders_live` $condition order by `id` DESC "))[0];
                        // print_r($total_query);
                        $total = ceil($total_query / $limit);
                        $page = isset($_GET['page_no']) ? abs((int) $_GET['page_no']) : 1;
                        $offset = ($page * $limit) - $limit;

                        $tenders_data = mysqli_query($con, "SELECT * FROM `tenders_live` $condition order by `id` DESC LIMIT $offset, $limit");

                        $tenders_result = mysqli_num_rows($tenders_data);

                        if ($limit > $total_query) {
                            $limit = $total_query;
                        }

                        if ($tenders_result > 0) {
                            $i = ($offset + 1);
                            foreach ($tenders_data as $data) {
                        ?>
                                <tr class="<?php if ($i % 2 == 0) {
                                                echo "even";
                                            } else {
                                                echo "odd";
                                            } ?>">
                                    <th scope="row" class="table_body" data-id="<?php echo $data['id']; ?>"><?php echo $i; ?></th>
                                    <td width="300"><?php echo (strlen(htmlspecialcode_generator($data['title'])) > 30 ? substr(htmlspecialcode_generator($data['title']), 0, 30) . "..." : htmlspecialcode_generator($data['title'])); ?></td>
                                    <td><?php echo $data['ref_no']; ?></td>
                                    <td><?php echo $data['tender_id']; ?></td>
                                    <!-- <td><?php echo htmlspecialcode_generator($data['agency_type']); ?></td> -->
                                    <td width="200"><?php echo $data['city']; ?></td>
                                    <!-- <td width="200"><?php echo $data['state']; ?></td> -->
                                    <td width="200"><?php echo date("M d, Y", strtotime($data['due_date'])); ?></td>
                                    <td width="200"><?php echo date("M d, Y h:i:s A", strtotime($data['created_at'])); ?></td>
                                    <td class="action_element">
                                        <div class="d-flex align-items-center">
                                            <a href="<?php echo ADMIN_URL; ?>live-tenders/edit-tender.php?id=<?php echo $data['id']; ?>"><i style="font-size: 20px;" class="ri-pencil-fill text-success"></i></a> &nbsp | &nbsp
                                            <a href="#"  data-teid="<?php echo $data['id']; ?>" class="delete" data-bs-toggle="modal" data-bs-target=".bs-example-modal-center"><i style="font-size: 20px;" class="ri-delete-bin-fill text-danger"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            <?php $i++;
                            }
                        } else { ?>
                            <tr class="odd">
                                <td colspan="9" class="dataTables_empty">No live tenders found.</td>
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
                                                <a href="javascript:void(0);" class="btn btn-danger" onclick="document.getElementById('postForm').submit(); return false;">Delete</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.modal-content -->
                            </div>
                            <!-- /.modal-dialog -->
                        </div>

                        <div class="modal fade bs-example-modal-center-01" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-body text-center p-5">
                                        <i class="bi bi-exclamation-triangle text-warning display-5"></i>
                                        <div class="mt-4">
                                            <h4 class="mb-3">Are you sure you want to move tenders to archive tenders?</h4>
                                            <p class="text-muted mb-4">
                                                If you click the move button, the data will be moved from the live tenders to archive tenders and you can not retrieve this data again.
                                            </p>
                                            <div class="hstack gap-2 justify-content-center">
                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                                    Close
                                                </button>
                                                <a href="javascript:void(0);" class="btn btn-danger" onclick="document.getElementById('movepostForm').submit(); return false;">Move</a>
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
                <div class="row">
                    <div class="col-sm-12 col-md-5">
                        <div class="dataTables_info" id="example_info" role="status" aria-live="polite">Showing <?php if ($tenders_result > 0) {
                                                                                                                    echo ($offset + 1);
                                                                                                                } else {
                                                                                                                    echo "0";
                                                                                                                } ?> to <?php echo ($page * $limit); ?> of <?php echo $total_query; ?> entries</div>
                    </div>
                    <div class="col-sm-12 col-md-7">
                        <?php
                        if ($total > 1) {
                            // display the "previous" link
                            $data = '<div class="dataTables_paginate paging_simple_numbers">';
                            $data .= '<ul class="pagination">';
                            if ($page > 1) {
                                $data .= '<li class="paginate_button page-item previous"><a class="page-link" href="' . ADMIN_URL . 'live-tenders/index.php' . '?page_no=' . ($page - 1) . '">Previous</a></li>';
                            }
                            // display the "previous" link
                            if ($page == 2) {
                                $data .= '<li class="paginate_button page-item"><a href="' . ADMIN_URL . 'live-tenders/index.php' . '?page_no=1" class="page-link">' . ($page - 1) . '</a></li>';
                            }
                            // display the first page link
                            if ($page > 2) {
                                $data .= '<li class="paginate_button page-item"><a href="' . ADMIN_URL . 'live-tenders/index.php' . '?page_no=1" class="page-link">1</a></li>';
                                // add an ellipsis to indicate skipped pages
                                if ($page > 3) {
                                    $data .= '<li class="paginate_button page-item"><a class="ellipsis page-link" style="pointer-events: none;">...</a></li>';
                                }
                            }
                            // display up to 3 pages before the current page
                            for ($i = max(2, $page - 2); $i < $page; $i++) {
                                $data .= '<li class="paginate_button page-item"><a href="' . ADMIN_URL . 'live-tenders/index.php' . '?page_no=' . $i . '" class="page-link">' . $i . '</a></li>';
                            }
                            // display the current page number
                            $data .= '<li class="paginate_button page-item active"><a class="page-link" style="pointer-events: none;">' . $page . '</a></li>';
                            // display up to 3 pages after the current page
                            for ($i = $page + 1; $i <= min($total - 1, $page + 2); $i++) {
                                $data .= '<li class="paginate_button page-item"><a href="' . ADMIN_URL . 'live-tenders/index.php' . '?page_no=' . $i . '" class="page-link">' . $i . '</a></li>';
                            }
                            // display the last page link
                            if ($page < $total - 1) {
                                // add an ellipsis to indicate skipped pages
                                if ($page < $total - 2) {
                                    $data .= '<li class="paginate_button page-item"><a class="ellipsis page-link" style="pointer-events: none;">...</a></li>';
                                }
                                $data .= '<li class="paginate_button page-item"><a href="' . ADMIN_URL . 'live-tenders/index.php' . '?page_no=' . $total . '" class="page-link">' . $total . '</a></li>';
                            }
                            // display the "next" link
                            if ($page == $total - 1) {
                                $data .= '<li class="paginate_button page-item"><a href="' . ADMIN_URL . 'live-tenders/index.php' . '?page_no=' . ($page + 1) . '" class="page-link">' . ($total) . '</a></li>';
                            }
                            // display the "next" link
                            if ($page < $total) {
                                $data .= '<li class="paginate_button page-item next"><a class="page-link" href="' . ADMIN_URL . 'live-tenders/index.php' . '?page_no=' . ($page + 1) . '">Next</i></a></li>';
                            }
                            $data .= '</ul></div>';
                            echo $data;
                        }
                        ?>
                    </div>
                </div>
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
    var te_url = "<?php echo ADMIN_URL; ?>live-tenders/index.php";
    $('.action_element a.delete').click(function(e) {
        e.preventDefault();
        var te_id=$(this).attr('data-teid');
        $('#te_id').val(te_id);
    });

    $('.card-header a.move').click(function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        $('.bs-example-modal-center-01 a.btn.btn-danger').prop('href', url);
    });
</script>

<script>
    $("#multiple_delete").click(function() {

        if ($(this).hasClass('active')) {

            var numberOfChecked = 0;
            numberOfChecked = $('input[name="multi_selection[]"]:checked').length;

            if (numberOfChecked === 0) {
                console.log('null');
            } else {
                $('#multi_delete_model').modal('show');
                $('#multi_delete_model').addClass('show');
                var count = $('input[name="multi_selection[]"]:checked').length;
                var i = 1;
                var var_passout = "";
                $('input[name="multi_selection[]"]:checked').each(function() {
                    console.log(this.value);
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
            $('<th class="table_head_select"><input type="checkbox" name="multi_selection_main" class="multi_selection_main" value="yes"></th>').insertBefore('.table_head');
            $('<td class="table_body_select"><input type="checkbox" name="multi_selection[]" class="multi_selection" value="yes"></td>').insertBefore('.table_body');
            $(".table_body").each(function() {
                $(this).parent().find('.multi_selection').val($(this).attr('data-id'));
            });
            $(".user-profile-nav li:last-child").hide();
        }
        $(this).toggleClass("active");
    });
</script>

<script>
    $(document).on('click', ".multi_selection_main", function() {
        if ($(this).prop("checked") == true) {
            $('input[name="multi_selection[]"]').each(function() {
                $(this).prop("checked", true);
            });
        } else {
            $('input[name="multi_selection[]"]').each(function() {
                $(this).prop("checked", false);
            });
        }
    });
</script>

<script>
    $('.pagination li.paginate_button.page-item').click(function(e) {
        e.preventDefault();
        var url = window.location.href.split('?')[0];
        var search = $("form input[name=search_term]").val();
        var upload_date = $("form input[name=upload_date]").val();
        var curr_page = $(this).text();
        if (search && upload_date) {
            if ($(this).hasClass('previous')) {
                url += '?search_term=' + search + '&upload_date=' + upload_date + '&page_no=' + (parseInt($('.pagination li.paginate_button.page-item.active a').text()) - 1);
            } else if ($(this).hasClass('next')) {
                url += '?search_term=' + search + '&upload_date=' + upload_date + '&page_no=' + (parseInt($('.pagination li.paginate_button.page-item.active a').text()) + 1);
            } else {
                url += '?search_term=' + search + '&upload_date=' + upload_date + '&page_no=' + curr_page;
            }
        } else if (search) {
            if ($(this).hasClass('previous')) {
                url += '?search_term=' + search + '&page_no=' + (parseInt($('.pagination li.paginate_button.page-item.active a').text()) - 1);
            } else if ($(this).hasClass('next')) {
                url += '?search_term=' + search + '&page_no=' + (parseInt($('.pagination li.paginate_button.page-item.active a').text()) + 1);
            } else {
                url += '?search_term=' + search + '&page_no=' + curr_page;
            }
        } else if (upload_date) {
            if ($(this).hasClass('previous')) {
                url += '?upload_date=' + upload_date + '&page_no=' + (parseInt($('.pagination li.paginate_button.page-item.active a').text()) - 1);
            } else if ($(this).hasClass('next')) {
                url += '?upload_date=' + upload_date + '&page_no=' + (parseInt($('.pagination li.paginate_button.page-item.active a').text()) + 1);
            } else {
                url += '?upload_date=' + upload_date + '&page_no=' + curr_page;
            }
        } else {
            if ($(this).hasClass('previous')) {
                url += '?page_no=' + (parseInt($('.pagination li.paginate_button.page-item.active a').text()) - 1);
            } else if ($(this).hasClass('next')) {
                url += '?page_no=' + (parseInt($('.pagination li.paginate_button.page-item.active a').text()) + 1);
            } else {
                url += '?page_no=' + curr_page;
            }
        }
        window.location.href = url;
        // console.log(url);
    });
</script>