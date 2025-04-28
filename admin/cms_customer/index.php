<?php include '../includes/authentication.php';
?>
<?php $pages = 'cms_customer'; ?>
<?php include '../includes/header.php';?>

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
                        window.location.href='" . ADMIN_URL . "/cms_customer/index.php';
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
<?php
 if (isset($_GET['upload_date']) && !empty($_GET['upload_date'])) {
    $upload_date = $_GET['upload_date'];
    $arr = explode(' to ', $upload_date);
    $from = date('Y-m-d 00:00:00', strtotime($arr[0]));
    $to = date('Y-m-d 23:59:59', strtotime($arr[1]));
    $condition = "where created_at BETWEEN '" . $from . "' AND '" . $to . "'";
} else {
    $upload_date = "";
    $condition = "";
}
?>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            
            <div class="card-header">
                
              
                <button type="button" onclick="dataSelected()" class="card-title float-start btn bg-success text-white mb-0">Send Email</button>
                <div class="col col-8 filter_upload" style="float:right">
                    <form class="d-flex align-items-center">
                        <label for="upload_date" class="form-label text-nowrap mx-2">Select Date :</label>
                        <input type="text" name="upload_date" class="form-control flatpickr-input" data-provider="flatpickr" data-date-format="d M, Y" data-range-date="true"  data-default-date="<?php echo $upload_date; ?>" readonly="readonly" id="upload_date">
                        <button type="submit" class="btn btn-primary mx-2">Filter</button>
                    </form>
                </div>    
            </div>
            <div class="card-body">
            <form id="deleteForm" method="POST" action="send_email.php">
                <input type="hidden" name="ids" id="ids">
            </form>
                <table id="example_table" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width: 100%">
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
                        $query = '';
                        if (!empty($ftype)) {
                            $query = "where type='$ftype'";
                        }
                       
                        $tenders_data = mysqli_query($con, "SELECT * FROM `cms_customer` $condition order by `customer_id` ASC");
                      
                        $tenders_result = mysqli_num_rows($tenders_data);
                        if ($tenders_result > 0) {
                            $i = 1;
                            foreach ($tenders_data as $data) {
                            ?>
                                <tr>
                                    <th scope="row"><input type="checkbox" name="row-check" class="row-check" value="<?php echo $data['id']; ?>"></th>
                                    <td><?php echo $data['customer_id']; ?></td>
                                    <td><?php echo htmlspecialcode_generator($data['customer_name']); ?></td>
                                    <td><?php echo $data['company_name']; ?></td>
                                    <td><?php echo $data['email_ids']; ?></td>
                                    <td><?php echo date('M d,Y',strtotime($data['created_at'])); ?></td>
                                    <td class="action_element">
                                       
                                            <a href="<?php echo ADMIN_URL; ?>cms_customer/view.php?id=<?php echo $data['id']; ?>"><i style="font-size: 20px;" class="ri-eye-fill text-success"></i></a>
                                            &nbsp | &nbsp<a href="<?php echo ADMIN_URL; ?>cms_customer/index.php?id=<?php echo $data['id']; ?>" class="delete remove-item-btn" data-bs-toggle="modal" data-bs-target=".bs-example-modal-center"><i style="font-size: 20px;" class="ri-delete-bin-fill text-danger delete_btn" data-url="<?php echo ADMIN_URL; ?>cms_customer/index.php?id=<?php echo $data['id']; ?>"></i></a>
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
    $(document).ready(function () {

    // Get saved page and page length
    const savedPage = parseInt(localStorage.getItem('dt-page') || 0);
    const savedLength = parseInt(localStorage.getItem('dt-length') || 30);

    // Initialize DataTable with saved options
    const table = $('#example_table').DataTable({
      pageLength: savedLength,
      displayStart: savedPage * savedLength,
      columnDefs: [
            { orderable: false, targets: 0 } // Disable ordering on the first column (index 0)
        ],
    });

    // Store page number on page change
    $('#example_table').on('page.dt', function () {
      const info = table.page.info();
      localStorage.setItem('dt-page', info.page);
    });

    // Store selected length on change
    $('#example_table').on('length.dt', function (e, settings, len) {
      localStorage.setItem('dt-length', len);
      localStorage.setItem('dt-page', 0); // Reset page to 0 when length changes
    });
});
    
</script>

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