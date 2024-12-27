<?php include '../includes/authentication.php';
?>
<?php $pages = 'agencies'; ?>
<?php include '../includes/header.php' ?>
<?php
if (isset($_GET['id'])) {
    $del = mysqli_query($con, "DELETE FROM `tender_agencies` where id='" . $_GET['id'] . "'");
    $status = true;
    if ($status) {
        $_SESSION['success'] = 'Deleted successfully.';
    } else {
        $_SESSION['error'] = 'Something went wrong.';
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
        $uploadDirectory = '../uploads/agency_uploads/';

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
                            url: "agencies_ajax.php",
                            data: { filename: filename },
                            success: function (response) {
                                // console.log(response);
                                $(".ajax_running").text("Uploaded successfully");
                                setTimeout(function(){ window.location.href="' . ADMIN_URL . '/agencies/index.php"; }, 2000);
                            }
                        });
                    });
                </script>';

                $success_message = 'Agencies uploaded successfully';
            } else {
                $error_message = 'Failed to move the uploaded file.';
            }
        } else {
            $error_message = 'Only Excel files (XLS or XLSX) are allowed.';
        }
    } else {
        $error_message = 'Error during file upload.';
    }
    // echo "<script>setTimeout(function(){ window.location.href='" . ADMIN_URL . "/agencies'; }, 2000);</script>";
}
?>
<?php
if (isset($_GET['page-limit']) && !empty($_GET['page-limit'])) {
    $_SESSION['page_limit'] = $_GET['page-limit'];
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
                        window.location.href='" . ADMIN_URL . "/agencies/index.php';
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
                        window.location.href='" . ADMIN_URL . "/agencies/index.php';
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
</style>

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">All Agencies</h4>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body dataTables_wrapper pt-0">
                <div class="card-header">
                    <div class="row">
                        <div class="col col-4 float-start text-start">
                            <form action="" method="POST" enctype="multipart/form-data" class="d-flex gap-3">
                                <input type="file" name="excel_file" class="form-control" accept=".xls, .xlsx">
                                <input type="submit" value="Upload Excel File" class="btn btn-primary" name="submit_excel">
                            </form>
                            <?php
                            if (!empty($error_message))
                                echo '<p class="text-danger mt-2">' . $error_message . '</p>';
                            if (!empty($success_message))
                                echo '<p class="text-success ajax_running mt-2">Please wait...</p>';
                            ?>
                        </div>
                        <div class="col float-end text-end">
                            <a href="<?php echo ADMIN_URL; ?>agencies/add-agency.php">
                                <h5 class="card-title btn bg-success text-white">Add New Agency</h5>
                            </a>
                        </div>
                    </div>
                </div>
                <?php
                if (isset($_GET['search_term']) && !empty($_GET['search_term'])) {
                    $search = $_GET['search_term'];
                    $condition = "where pseudo_name like '%$search%' or agency_name like '%$search%'";
                } else {
                    $condition = "";
                    $search = "";
                }
                ?>
                <div id="example_filter" class="dataTables_filter mt-2">
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
                                <label>
                                    Search:
                                    <input type="search" name="search_term" value="<?php echo $search; ?>" class="form-control" placeholder="">
                                </label>
                                <button type="submit" class="btn btn-primary">Search</button>
                            </form>
                        </div>
                    </div>
                </div>
                <table class="table table-bordered dt-responsive dataTable nowrap table-striped align-middle" style="width: 100%">
                    <thead>
                        <tr>
                            <th>SR No.</th>
                            <th>Pseudo Name</th>
                            <th>Agency Name</th>
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
                        $total_query = mysqli_fetch_array(mysqli_query($con, "SELECT COUNT(*) FROM `tender_agencies` $condition order by `id` DESC "))[0];
                        // print_r($total_query);
                        $total = ceil($total_query / $limit);
                        $page = isset($_GET['page_no']) ? abs((int) $_GET['page_no']) : 1;
                        $offset = ($page * $limit) - $limit;

                        $tenders_data = mysqli_query($con, "SELECT * FROM `tender_agencies` $condition order by `id` DESC LIMIT $offset, $limit");

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
                                    <th scope="row"><?php echo $i; ?></th>
                                    <td><?php echo $data['pseudo_name']; ?></td>
                                    <td><?php echo $data['agency_name']; ?></td>
                                    <td class="action_element">
                                        <a href="<?php echo ADMIN_URL; ?>agencies/edit-agency.php?id=<?php echo $data['id']; ?>"><i style="font-size: 20px;" class="ri-pencil-fill text-success"></i></a> &nbsp | &nbsp
                                        <a href="<?php echo ADMIN_URL; ?>agencies/index.php?id=<?php echo $data['id']; ?>" class="delete" data-bs-toggle="modal" data-bs-target=".bs-example-modal-center"><i style="font-size: 20px;" class="ri-delete-bin-fill text-danger"></i></a>
                                    </td>
                                </tr>
                            <?php $i++;
                            }
                        } else { ?>
                            <tr class="odd">
                                <td colspan="4">No agency found.</td>
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
                                $data .= '<li class="paginate_button page-item previous"><a class="page-link" href="' . ADMIN_URL . 'tenders' . '?page_no=' . ($page - 1) . '">Previous</a></li>';
                            }
                            // display the "previous" link
                            if ($page == 2) {
                                $data .= '<li class="paginate_button page-item"><a href="' . ADMIN_URL . 'tenders' . '?page_no=1" class="page-link">' . ($page - 1) . '</a></li>';
                            }
                            // display the first page link
                            if ($page > 2) {
                                $data .= '<li class="paginate_button page-item"><a href="' . ADMIN_URL . 'tenders' . '?page_no=1" class="page-link">1</a></li>';
                                // add an ellipsis to indicate skipped pages
                                if ($page > 3) {
                                    $data .= '<li class="paginate_button page-item"><a class="ellipsis page-link" style="pointer-events: none;">...</a></li>';
                                }
                            }
                            // display up to 3 pages before the current page
                            for ($i = max(2, $page - 2); $i < $page; $i++) {
                                $data .= '<li class="paginate_button page-item"><a href="' . ADMIN_URL . 'tenders' . '?page_no=' . $i . '" class="page-link">' . $i . '</a></li>';
                            }
                            // display the current page number
                            $data .= '<li class="paginate_button page-item active"><a class="page-link" style="pointer-events: none;">' . $page . '</a></li>';
                            // display up to 3 pages after the current page
                            for ($i = $page + 1; $i <= min($total - 1, $page + 2); $i++) {
                                $data .= '<li class="paginate_button page-item"><a href="' . ADMIN_URL . 'tenders' . '?page_no=' . $i . '" class="page-link">' . $i . '</a></li>';
                            }
                            // display the last page link
                            if ($page < $total - 1) {
                                // add an ellipsis to indicate skipped pages
                                if ($page < $total - 2) {
                                    $data .= '<li class="paginate_button page-item"><a class="ellipsis page-link" style="pointer-events: none;">...</a></li>';
                                }
                                $data .= '<li class="paginate_button page-item"><a href="' . ADMIN_URL . 'tenders' . '?page_no=' . $total . '" class="page-link">' . $total . '</a></li>';
                            }
                            // display the "next" link
                            if ($page == $total - 1) {
                                $data .= '<li class="paginate_button page-item"><a href="' . ADMIN_URL . 'tenders' . '?page_no=' . ($page + 1) . '" class="page-link">' . ($total) . '</a></li>';
                            }
                            // display the "next" link
                            if ($page < $total) {
                                $data .= '<li class="paginate_button page-item next"><a class="page-link" href="' . ADMIN_URL . 'tenders' . '?page_no=' . ($page + 1) . '">Next</i></a></li>';
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

<?php include '../includes/footer.php';  ?>

<script>
    $('.action_element a.delete').click(function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        $('.bs-example-modal-center a.btn.btn-danger').prop('href', url);
    });
</script>

<script>
    $('.pagination li.paginate_button.page-item').click(function(e) {
        e.preventDefault();
        var url = window.location.href.split('?')[0];
        var search = $("form input[name=search_term]").val();
        var curr_page = $(this).text();
        if (search) {
            if ($(this).hasClass('previous')) {
                url += '?search_term=' + search + '&page_no=' + (parseInt($('.pagination li.paginate_button.page-item.active a').text()) - 1);
            } else if ($(this).hasClass('next')) {
                url += '?search_term=' + search + '&page_no=' + (parseInt($('.pagination li.paginate_button.page-item.active a').text()) + 1);
            } else {
                url += '?search_term=' + search + '&page_no=' + curr_page;
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