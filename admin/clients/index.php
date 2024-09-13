<?php include '../includes/authentication.php';
?>
<?php $pages = 'clients'; ?>
<?php include '../includes/header.php';?>

<?php
$clients_per = _get_user_perby_role($_SESSION['user_id'],'clients',$con);

if($_SESSION['role']!='admin' && $_SESSION['role']!='employee'){ 
    // echo "not admin ------>" . $_SESSION['role'];
    echo "<script>
            window.location.href='../index.php';
            </script>";
}elseif($_SESSION['role']=='employee' && $clients_per!=1){ 
    // echo "not admin ------>" . $_SESSION['role'];
    echo "<script>
            window.location.href='../index.php';
            </script>";
}
if (isset($_GET['id']) && isset($_GET['unique_code'])) {
    if (!empty($_GET['id']) && !empty($_GET['unique_code'])) {
        mysqli_query($con, "DELETE FROM `users` where user_id={$_GET['id']} AND user_unique_id={$_GET['unique_code']}");
        echo "<script>
            window.location.href='" . ADMIN_URL . "clients';
            </script>";
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
                        window.location.href='" . ADMIN_URL . "/clients';
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
</style>
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Clients</h4>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-2">
                        <a class="dropdown-item fs-sm" href="<?php echo ADMIN_URL; ?>clients/active.php">
                            <h5 class="card-title btn w-100 bg-primary text-white">Active</h5>
                        </a>
                    </div>
                    <div class="col-2">
                        <a class="dropdown-item fs-sm" href="<?php echo ADMIN_URL; ?>clients/expired.php">
                            <h5 class="card-title btn w-100 bg-primary text-white">Expired</h5>
                        </a>
                    </div>
                    <div class="col-2">
                        <a class="dropdown-item fs-sm" href="<?php echo ADMIN_URL; ?>clients/renew.php">
                            <h5 class="card-title btn w-100 bg-primary text-white">Renew</h5>
                        </a>
                    </div>
                    <div class="col-2">
                        <a class="dropdown-item fs-sm" href="<?php echo ADMIN_URL; ?>clients/upgrade.php">
                            <h5 class="card-title btn w-100 bg-primary text-white">Upgrade</h5>
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-header">
                
                <a class="dropdown-item fs-sm" href="<?php echo ADMIN_URL; ?>clients/add.php">
                    <h5 class="card-title float-end btn bg-success text-white">Register New Users</h5>
                </a>
                <button type="button" onclick="dataSelected()" class="card-title float-start btn bg-success text-white mb-0">Send Email</button>
            </div>
            <div class="card-body">
            <form id="deleteForm" method="POST" action="send_email.php">
                <input type="hidden" name="ids" id="ids">
            </form>
                <table id="example" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width: 100%">
                    <thead>
                        <tr>
                            <th><input type="checkbox" name="check_all" class="check_all"></th>
                            <th>SR No.</th>
                            <th>Company Name</th>
                            <th>Email</th>
                            <th>Mobile</th>
                            <th>New Tenders Link</th>
                            <th>Live Tenders Link</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        function truncate_and_append($text, $length = 20)
                        {
                            if (strlen($text) <= $length) {
                                return $text;
                            } else {
                                return substr($text, 0, $length) . "...";
                            }
                        }
                        $i = 1;
                        $fetching_users = mysqli_query($con, "SELECT * FROM users where user_role='user' order by user_id desc");
                        while ($row = mysqli_fetch_assoc($fetching_users)) {
                        ?>
                            <tr>
                                <th scope="row"><input type="checkbox" name="row-check" class="row-check" value="<?php echo $row['user_id']; ?>"></th>
                                <td><?php echo $i; ?></td>
                                <td><?php echo $row['company_name']; ?></td>
                                <td><?php echo $row['users_email']; ?></td>
                                <td><?php echo $row['mobile_number']; ?></td>
                                <td class="copy" style="cursor: copy;" data-id="<?php echo HOME_URL . "user/new-tenders?id=" . $row['user_unique_id']; ?>"><?php echo truncate_and_append(HOME_URL . "user/new-tenders/" . $row['user_unique_id']); ?></td>
                                <td class="copy" style="cursor: copy;" data-id="<?php echo HOME_URL . "user/live-tenders?id=" . $row['user_unique_id']; ?>"><?php echo truncate_and_append(HOME_URL . "user/live-tenders/" . $row['user_unique_id']); ?></td>
                                <td>
                                    <div class="dropdown d-inline-block">
                                        <button class="btn btn-subtle-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ri-more-fill align-middle"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a class="dropdown-item edit-item-btn" href="<?php echo ADMIN_URL; ?>clients/edit.php?id='<?php echo $row['user_id']; ?>'&unique_code='<?php echo $row['user_unique_id']; ?>'"><i class="ri-pencil-fill align-bottom me-2 text-muted"></i>
                                                    Edit</a>
                                            </li>
                                            <li>
                                                <input type="hidden" value="<?php echo ADMIN_URL; ?>clients?id='<?php echo $row['user_id']; ?>'&unique_code='<?php echo $row['user_unique_id']; ?>'" id="delete_id">
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