<?php include '../includes/authentication.php';
?>
<?php $pages = 'cms_customer'; ?>
<?php include '../includes/header.php';?>

<?php
// Pagination Utils START
function pageUrl($baseUrl, $params, $page) {
    $params['page_no'] = $page;
    return $baseUrl . '?' . http_build_query($params);
}

if (!empty($_GET['upload_date'])) {
    $baseParams['upload_date'] = $_GET['upload_date'];
}

if (!empty($_GET['search_term'])) {
    $baseParams['search_term'] = $_GET['search_term'];
}

$baseUrl = ADMIN_URL . 'cms_customer/index.php';
// Pagination Utils END

$cmscust_per = _get_user_perby_role($_SESSION['user_id'],'cms_customer',$con);

if($_SESSION['role']!='admin' && $_SESSION['role']!='employee'){ 
    // echo "not admin ------>" . $_SESSION['role'];
    echo "<script>
            window.location.href='../index.php';
            </script>";
}elseif($_SESSION['role']=='employee' && $cmscust_per!=1){ 
    // echo "not admin ------>" . $_SESSION['role'];
    echo "<script>
            window.location.href='../index.php';
            </script>";
}

if (isset($_GET['page-limit']) && !empty($_GET['page-limit'])) {
    $_SESSION['page_limit'] = $_GET['page-limit'];
}

if (isset($_GET['search_term']) && !empty($_GET['search_term']) && isset($_GET['upload_date']) && !empty($_GET['upload_date'])) {
    $search = $_GET['search_term'];
    $upload_date = urldecode($_GET['upload_date']);
    $arr = explode(' to ', $upload_date);
    if (count($arr) === 2) {

        $fromDate = DateTime::createFromFormat('d M, Y', trim($arr[0]));
        $toDate   = DateTime::createFromFormat('d M, Y', trim($arr[1]));

        if ($fromDate && $toDate) {
            $from = $fromDate->format('Y-m-d 00:00:00');
            $to   = $toDate->format('Y-m-d 23:59:59');
            
            $condition = "where (customer_id like '%$search%' or customer_name like '%$search%' or company_name like '%$search%' or email_ids like '%$search%' or created_at like '%$search%') and created_at BETWEEN '" . $from . "' AND '" . $to . "'";
        }
    }
} else if (isset($_GET['search_term']) && !empty($_GET['search_term'])) {
    $search = $_GET['search_term'];
    $upload_date = "";
    $condition = "where (customer_id like '%$search%' or customer_name like '%$search%' or company_name like '%$search%' or email_ids like '%$search%' or created_at like '%$search%')";
} else if (!empty($_GET['upload_date'])) {

    $upload_date = urldecode($_GET['upload_date']);
    $arr = explode(' to ', $upload_date);

    if (count($arr) === 2) {

        $fromDate = DateTime::createFromFormat('d M, Y', trim($arr[0]));
        $toDate   = DateTime::createFromFormat('d M, Y', trim($arr[1]));

        if ($fromDate && $toDate) {
            $from = $fromDate->format('Y-m-d 00:00:00');
            $to   = $toDate->format('Y-m-d 23:59:59');

            $condition = "WHERE created_at BETWEEN '$from' AND '$to'";
        }
    }
} else {
    $search = "";
    $upload_date = "";
    $condition = "";
}

if (isset($_GET['id'])) {
    $del = mysqli_query($con, "DELETE FROM `cms_customer` where id='" . $_GET['id'] . "'");
    $status = true;
    if ($status) {
        $_SESSION['success'] = 'Deleted successfully.';
    } else {
        $_SESSION['error'] = 'Something went wrong.';
    }
}

if (isset($_GET['st'])) {
    if ($_GET['st'] == 1) {
        $_SESSION['success'] = 'Mail sent successfully.';
    }elseif ($_GET['st'] == 0) {
        $_SESSION['error'] = 'Email Ids not found.';
    }
}

if (!empty($_SESSION['success'])) {
    echo '<div class="alert alert-success alert-dismissible fade show mb-4 show msg_box" role="alert">
            <strong>' . $_SESSION['success'] . '</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
    $_SESSION['success'] = "";
    echo "
             <script>
                     setTimeout(function(){
                        //window.location.href='" . ADMIN_URL . "/cms_customer/index.php';
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
                         //window.location.reload();
                         document.querySelector('.msg_box').remove();
                     }, 3000);
                 
             </script>";
}
?>
<style>
    td {
        white-space: pre-wrap;
    }
    #example_table .action_element {
        display: flex;
        align-items: center;
    }
</style>
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
            
            <div class="card-header">
                <button type="button" onclick="dataSelected()" class="card-title float-start btn bg-success text-white mb-0">Send Email</button>
                <div class="col col-8 filter_upload" style="float:right">
                    <form class="d-flex align-items-center">
                        <label for="upload_date" class="form-label text-nowrap mx-2">Select Date :</label>
                        <?php if (isset($_GET['search_term']) && !empty($_GET['search_term'])) { ?>
                            <input type="hidden" name="search_term" value="<?php echo $_GET['search_term']; ?>">
                        <?php } ?>
                        <input type="text" name="upload_date" class="form-control flatpickr-input" data-provider="flatpickr" data-date-format="d M, Y" data-range-date="true"  data-default-date="<?php echo $upload_date; ?>" readonly="readonly" id="upload_date">
                        <button type="submit" class="btn btn-primary mx-2">Filter</button>
                    </form>
                </div>    
            </div>
            <div class="card-body dataTables_wrapper pt-0">
            <form id="deleteForm" method="POST" action="send_email.php">
                <input type="hidden" name="ids" id="ids">
                <input type="hidden" name="upload_date" id="upload_date" value="<?php echo $upload_date; ?>">
                <input type="hidden" name="search_term" id="search_term" value="<?php echo $_GET['search_term']; ?>">
                <input type="hidden" name="page_no" id="page_no" value="<?php echo $_GET['page_no']; ?>">
            </form>

            <div id="example_filter" class="dataTables_filter">
                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <div class="dataTables_length d-flex align-items-center h-100" id="example_length">
                                <form>
                                    <label>Show <select name="page-limit" aria-controls="example" onchange="this.form.submit()" class="form-select form-select-sm">
                                            <option value="10" <?php if (isset($_SESSION['page_limit']) && !empty($_SESSION['page_limit']) && $_SESSION['page_limit'] == 10) { echo "selected"; } ?>>10</option>
                                            <option value="25" <?php if (isset($_SESSION['page_limit']) && !empty($_SESSION['page_limit']) && $_SESSION['page_limit'] == 25) { echo "selected"; } ?>>25</option>
                                            <option value="50" <?php if (isset($_SESSION['page_limit']) && !empty($_SESSION['page_limit']) && $_SESSION['page_limit'] == 50) { echo "selected"; } ?>>50</option>
                                            <option value="100" <?php if (isset($_SESSION['page_limit']) && !empty($_SESSION['page_limit']) && $_SESSION['page_limit'] == 100) { echo "selected"; } ?>>100</option>
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
                                <label> Search: <input type="search" name="search_term" value="<?php echo $search; ?>" class="form-control form-control" placeholder="" aria-controls="example">
                                </label>
                                <button type="submit" class="btn btn-primary">Search</button>
                            </form>
                        </div>

                    </div>
                </div>

                <table id="example_table" class="table table-bordered dt-responsive dataTable nowrap table-striped align-middle" style="width: 100%;">
                    <thead>
                        <tr>
                            <th><input type="checkbox" name="check_all" class="check_all"></th>
                            <th>CMS Customer Id</th>
                            <th>Name</th>
                            <th>Company Name</th>
                            <th>Email</th>
                            <th>Created Date</th>                           
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

                        $total_query = mysqli_fetch_array(mysqli_query($con, "SELECT COUNT(*) FROM `cms_customer` $condition order by `customer_id` ASC "))[0];
                        $total = ceil($total_query / $limit);
                        $page = isset($_GET['page_no']) ? abs((int) $_GET['page_no']) : 1;
                        $offset = ($page * $limit) - $limit;

                        $tenders_data = mysqli_query($con, "SELECT * FROM `cms_customer` $condition order by `customer_id` ASC LIMIT $offset, $limit");
                        $tenders_result = mysqli_num_rows($tenders_data);

                        if ($limit > $total_query) {
                            $limit = $total_query;
                        }


                        // $query = '';
                        // if (!empty($ftype)) {
                        //     $query = "where type='$ftype'";
                        // }
                       
                        // $tenders_data = mysqli_query($con, "SELECT * FROM `cms_customer` $condition order by `customer_id` ASC");
                      
                        // $tenders_result = mysqli_num_rows($tenders_data);
                        if ($tenders_result > 0) {
                            $i = ($offset + 1);
                            foreach ($tenders_data as $data) {
                            ?>
                                <tr class="<?php if ($i % 2 == 0) { echo "even";} else { echo "odd"; } ?>">
                                    <th scope="row"><input type="checkbox" name="row-check" class="row-check" value="<?php echo $data['id']; ?>"></th>
                                    <td><?php echo $data['customer_id']; ?></td>
                                    <td><?php echo htmlspecialcode_generator($data['customer_name']); ?></td>
                                    <td style="word-break: break-word; overflow-wrap: break-word; white-space: normal; max-width: 300px;"><?php echo $data['company_name']; ?></td>
                                    <td style="word-break: break-word; overflow-wrap: break-word; white-space: normal; max-width: 300px;"><?php echo $data['email_ids']; ?></td>
                                    <td><?php echo date('M d,Y',strtotime($data['created_at'])); ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <a href="<?php echo ADMIN_URL; ?>cms_customer/view.php?id=<?php echo $data['id']; ?>"><i style="font-size: 20px;" class="ri-eye-fill text-success"></i></a>
                                            &nbsp | &nbsp<a href="<?php echo ADMIN_URL; ?>cms_customer/index.php?id=<?php echo $data['id']; ?>" class="delete remove-item-btn" data-bs-toggle="modal" data-bs-target=".bs-example-modal-center"><i style="font-size: 20px;" class="ri-delete-bin-fill text-danger delete_btn" data-url="<?php echo ADMIN_URL; ?>cms_customer/index.php?id=<?php echo $data['id']; ?>"></i></a>
                                        </div>    
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
                <div class="row">
                    <div class="col-sm-12 col-md-5">
                        <div class="dataTables_info" id="example_info" role="status" aria-live="polite">Showing 
                            <?php if ($tenders_result > 0) { echo ($offset + 1); } else { echo "0"; } ?> to <?php echo ($page * $limit); ?> of <?php echo $total_query; ?> entries</div>
                    </div>
                    <div class="col-sm-12 col-md-7">
                       <?php
                        if ($total > 1) {
                            // display the "previous" link
                            $customer_id_params = $_GET['id'];
                            $data = '<div class="dataTables_paginate paging_simple_numbers">';
                            $data .= '<ul class="pagination">';
                            if ($page > 1) {
                                $data .= '<li class="paginate_button page-item previous"><a class="page-link" href="'. pageUrl($baseUrl, $baseParams, $page - 1) .'">Previous</a></li>';
                            }
                            // display the "previous" link
                            if ($page == 2) {
                                $data .= '<li class="paginate_button page-item"><a href="'. pageUrl($baseUrl, $baseParams, 1) .'" class="page-link">' . ($page - 1) . '</a></li>';
                            }
                            // display the first page link
                            if ($page > 2) {
                                $data .= '<li class="paginate_button page-item"><a href="'. pageUrl($baseUrl, $baseParams, 1) .'" class="page-link">1</a></li>';
                                // add an ellipsis to indicate skipped pages
                                if ($page > 3) {
                                    $data .= '<li class="paginate_button page-item"><a class="ellipsis page-link" style="pointer-events: none;">...</a></li>';
                                }
                            }
                            // display up to 3 pages before the current page
                            for ($i = max(2, $page - 2); $i < $page; $i++) {
                                $data .= '<li class="paginate_button page-item"><a href="'. pageUrl($baseUrl, $baseParams, $i) .'" class="page-link">' . $i . '</a></li>';
                            }
                            // display the current page number
                            $data .= '<li class="paginate_button page-item active"><a class="page-link" style="pointer-events: none;">' . $page . '</a></li>';
                            // display up to 3 pages after the current page
                            for ($i = $page + 1; $i <= min($total - 1, $page + 2); $i++) {
                                $data .= '<li class="paginate_button page-item"><a href="'. pageUrl($baseUrl, $baseParams, $i) .'" class="page-link">' . $i . '</a></li>';
                            }
                            // display the last page link
                            if ($page < $total - 1) {
                                // add an ellipsis to indicate skipped pages
                                if ($page < $total - 2) {
                                    $data .= '<li class="paginate_button page-item"><a class="ellipsis page-link" style="pointer-events: none;">...</a></li>';
                                }
                                $data .= '<li class="paginate_button page-item"><a href="'. pageUrl($baseUrl, $baseParams, $total) .'" class="page-link">' . $total . '</a></li>';
                            }
                            // display the "next" link
                            if ($page == $total - 1) {
                                $data .= '<li class="paginate_button page-item"><a href="'. pageUrl($baseUrl, $baseParams, $page + 1) .'" class="page-link">' . ($total) . '</a></li>';
                            }
                            // display the "next" link
                            if ($page < $total) {
                                $data .= '<li class="paginate_button page-item next"><a class="page-link" href="'. pageUrl($baseUrl, $baseParams, $page + 1) .'">Next</i></a></li>';
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

<?php include '../includes/footer.php';  ?>

<script>
    //copy text
    var copy = document.querySelectorAll(".copy");
    for (const copied of copy) {
        copied.onclick = function() {
            document.execCommand("copy");
        }
        copied.addEventListener("copy", function(event) {
            event.preventDefault();
            if (event.clipboardData) {
                // event.clipboardData.setData("text/plain", copied.textContent);
                event.clipboardData.setData("text/plain", copied.getAttribute("data-id"));
            }
        });
    }

    $(document).on('click','.delete_btn',function(e){
        e.preventDefault();
        var url = $(this).attr('data-url');
        $('.bs-example-modal-center a.btn.btn-danger').prop('href', url);
    });

    function dataSelected() {
        const checkboxes = document.querySelectorAll('input[name="row-check"]:checked');
        const ids = Array.from(checkboxes).map(cb => cb.value);

        if (ids.length > 0) {
            if (confirm("Are you sure you want to send email to the selected records?")) {
                const form = document.getElementById('deleteForm');
                document.getElementById('ids').value = ids.join(',');
                form.submit();
            }
        } else {
            alert("Please select at least one record.");
        }
    }

    $(document).on('click','.check_all',function(){
        if($(this).prop('checked') == true){
            $('.row-check').prop('checked',true);
        }else{
            $('.row-check').prop('checked',false);
        }
    })
    

    $(document).on('click', '.row-check', function() {
        var $row = $(this).closest('tr');

        // Check if the row has the class 'dt-hasChild parent'
        if ($row.hasClass('dt-hasChild parent')) {
            // Remove the class
            $row.removeClass('dt-hasChild parent');
            var $nextRow = $row.next('tr');
                if ($nextRow.hasClass('child')) {
                    $nextRow.remove();
                }
        }
        
        
    });
</script>