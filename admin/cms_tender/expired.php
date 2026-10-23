<?php include '../includes/without_authentication.php';
?>
<?php $pages = 'cms_tender'; ?>
<?php include '../includes/without_login_header.php';?>

<?php

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
                        window.location.href='" . ADMIN_URL . "/cms_tender/index.php?id=".$_GET['id'].";
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
            <h4 class="mb-sm-0">Tenders</h4>
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
                        <a class="dropdown-item fs-sm" href="<?php echo ADMIN_URL; ?>cms_tender/active.php?id=<?php echo $_GET['id']; ?>">
                            <h5 class="card-title btn w-100 bg-primary text-white">Active</h5>
                        </a>
                    </div>
                    <div class="col-2">
                        <a class="dropdown-item fs-sm" href="<?php echo ADMIN_URL; ?>cms_tender/expired.php?id=<?php echo $_GET['id']; ?>">
                            <h5 class="card-title btn w-100 bg-primary text-white">Expired</h5>
                        </a>
                    </div>
                    <div class="col-2">
                        <a class="dropdown-item fs-sm" href="<?php echo ADMIN_URL; ?>cms_tender/renew.php?id=<?php echo $_GET['id']; ?>">
                            <h5 class="card-title btn w-100 bg-primary text-white">Renew</h5>
                        </a>
                    </div>
                    <div class="col-2">
                        <a class="dropdown-item fs-sm" href="<?php echo ADMIN_URL; ?>cms_tender/upgrade.php?id=<?php echo $_GET['id']; ?>">
                            <h5 class="card-title btn w-100 bg-primary text-white">Upgrade</h5>
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-header">               
                <button type="button" onclick="dataSelected()" class="card-title float-start btn bg-success text-white mb-0">Send Email</button>
            </div>
            <div class="card-body">
                <?php
                $customer_id = base64_decode($_GET['id']);
                //find customer keywords
                $cms_cust_data = mysqli_query($con, "SELECT * FROM `cms_customer` where customer_id='" . $customer_id . "'");
                $cms_cust_result = mysqli_num_rows($cms_cust_data);
                if ($cms_cust_result == 1) {
                    while ($row = mysqli_fetch_assoc($cms_cust_data)) {
                        $cust_keywords = $row['keywords'];
                        $cust_from_email = $row['sender_email_id'];
                        $cust_reply_email = $row['reply_email_id'];
                    }
                }
                ?>
            <form id="deleteForm" method="POST" action="expiredsend_email.php">
                <input type="hidden" name="ids" id="ids">
                <input type="hidden" name="cust_from_email" value="<?php echo $cust_from_email; ?>">
                <input type="hidden" name="cust_reply_email" value="<?php echo $cust_reply_email; ?>">
                <input type="hidden" name="cms_id" value="<?php echo $_GET['id']; ?>">
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
                         
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        
                        if (!empty($cust_keywords)) {
                            $cust_keywords = trim($cust_keywords);
                            $boolean_mode_keyword = "+" . str_replace(" ", " +", $cust_keywords); // Convert to Boolean Mode format
                            $condition_key = "";
                            $ucondition_key = "";
                            
                            // Build the MATCH AGAINST condition
                            $condition_key = "MATCH(keywords) AGAINST('$boolean_mode_keyword' IN BOOLEAN MODE)";
                            
                            // Final WHERE clauses
                            $condition_filter .= "and $condition_key";
                            $cust_keywords = explode(",", $cust_keywords);
                        }
                        function truncate_and_append($text, $length = 20)
                        {
                            if (strlen($text) <= $length) {
                                return $text;
                            } else {
                                return substr($text, 0, $length) . "...";
                            }
                        }
                        $i = 1;
                       
                        $conditions = [];
                        $conditionStr = '';
                        if(count($cust_keywords) > 0){
                            foreach ($cust_keywords as $skill) {
                                //$skill = mysqli_real_escape_string($conn, $skill);
                                $conditions[] = "(FIND_IN_SET('$skill', u.keywords) )";
                            }
                            $conditionStr = implode(" OR ", $conditions);
                            $sql = "SELECT DISTINCT u.company_name, u.users_email, u.user_unique_id,u.mobile_number,u.user_id,u.status,u.user_role FROM users u JOIN cms_customer p ON $conditionStr  where u.user_role='user' and u.status='Expired' order by u.user_id desc";
                        }

                        $fetching_users = mysqli_query($con, $sql);
                        while ($row = mysqli_fetch_assoc($fetching_users)) {
                        ?>
                            <tr>
                                <th scope="row"><input type="checkbox" name="row-check" class="row-check" value="<?php echo $row['user_id']; ?>"></th>
                                <td><?php echo $i; ?></td>
                                <td><?php echo $row['company_name']; ?></td>
                                <td><?php echo $row['users_email']; ?></td>
                                <td><?php echo $row['mobile_number']; ?></td>
                                <td class="copy" style="cursor: copy;" data-id="<?php echo HOME_URL . "user/new-tenders?id=" . $row['user_unique_id']; ?>"><?php echo truncate_and_append(HOME_URL . "user/new-tenders/?id=" . $row['user_unique_id']); ?></td>
                                <td class="copy" style="cursor: copy;" data-id="<?php echo HOME_URL . "user/live-tenders?id=" . $row['user_unique_id']; ?>"><?php echo HOME_URL . "user/live-tenders/?id=" . $row['user_unique_id']; ?></td>
                                
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


<?php include '../includes/footer.php';  ?>

<script>

    $('#example').DataTable({
        pageLength: 35,
        columnDefs: [
            { orderable: false, targets: 0 } // Disable ordering on the first column (index 0)
        ],
        // Other options
    });
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
        console.log(ids);

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