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
                        window.location.href='" . ADMIN_URL . "/cms_tender/archive.php?id=".$_GET['id'].";
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
function highlightSearchTerm($text, $searchTerm)
{
    // $highlightedTerm = "<b>$searchTerm</b>";
    // return str_ireplace($searchTerm, $highlightedTerm, $text);

    $highlightMarkup = '<strong style=color:#cb192d;margin-right:3px;>';
    $closingHighlightMarkup = '</strong>';
    $highlightedText = preg_replace("/({$searchTerm})/i", $highlightMarkup . '$1' . $closingHighlightMarkup, $text);
    return $highlightedText;
}

?>
<style>
    td {
        white-space: pre-wrap;
    }
   .tend_btn {
        color: #5a58eb !important;
        font-family: DMSans !important;
        border: 2px solid #5a58eb !important;
        background:none;
    }
    table.dataTable td {
        word-wrap: break-word;
        white-space: normal !important;
    }
   
</style>
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Archive Tenders</h4>
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
                        <a class="dropdown-item fs-sm" href="<?php echo ADMIN_URL; ?>cms_tender/index.php?id=<?php echo $_GET['id']; ?>">
                            <h5 class="card-title btn w-100  text-white tend_btn">New Tenders</h5>
                        </a>
                    </div>
                    <div class="col-2">
                        <a class="dropdown-item fs-sm" href="<?php echo ADMIN_URL; ?>cms_tender/live.php?id=<?php echo $_GET['id']; ?>">
                            <h5 class="card-title btn w-100 text-white tend_btn">Live Tenders</h5>
                        </a>
                    </div>
                    <div class="col-2">
                        <a class="dropdown-item fs-sm" href="<?php echo ADMIN_URL; ?>cms_tender/archive.php?id=<?php echo $_GET['id']; ?>">
                            <h5 class="card-title btn w-100 text-white bg-primary">Archive Tenders</h5>
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
            <form id="deleteForm" method="POST" action="archivesend_email.php">
                <input type="hidden" name="ids" id="ids">
                <input type="hidden" name="cust_from_email" value="<?php echo $cust_from_email; ?>">
                <input type="hidden" name="cust_reply_email" value="<?php echo $cust_reply_email; ?>">
                <input type="hidden" name="cms_id" value="<?php echo $_GET['id']; ?>">
            </form>
                <table id="example" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width: 100%">
                    <thead>
                        <tr>
                            <th><input type="checkbox" name="check_all" class="check_all"></th>
                            <th>Tender Title</th>
                            <th>Location</th>
                            <th>Source</th>
                            <th>Agency</th>
                            <th>Due Date</th>
                            <th>Tender Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                       
        $sql="";
        $usersData = mysqli_query($con, "SELECT `mail_type`,`email_ids`,`company_name`,`not_used_keywords`,`words`,`keywords`,`filter_city`,`filter_state`,`filter_tender_value`,`filter_agency`,`filter_department`,`filter_type` FROM `cms_customer` WHERE customer_id = $customer_id");
        $usersResult = mysqli_num_rows($usersData);
        $company_name = "";
        if ($usersResult > 0) {
            while ($row = mysqli_fetch_assoc($usersData)) {
        
                $uemail_ids = $row['email_ids'];
                    $mail_type= $row['mail_type'];
                    $company_name= $row['company_name'];
                    $city = $row['filter_city'];
                    $state = $row['filter_state'];
                    $tenderValue = $row['filter_tender_value'];
                    $agency = $row['filter_agency'];
                    $department = $row['filter_department'];
                    $type = $row['filter_type'];
                    $keywords = $row['keywords'];
                    $not_used_keywords = $row['not_used_keywords'];
                    $words = $row['words'];
                    $exp_uemail_ids = explode(',',$uemail_ids);


                        $condition_new = "";
                        $condition_city = "";
                        if (!empty($city)) {
                            $city = explode(",", $city);
                            if (!empty($city)) {
                                
                                foreach ($city as $key => $value) {
                                    if ($key > 0) {
                                        $condition_city .= " or city='$value'";
                                    } else {
                                        $condition_city .= " city='$value'";
                                    }
                                }
                                $condition_new .= " and (" . $condition_city . " )";
                            }
                        }
                        $condition_state = "";
                        if (!empty($state)) {
                            $state = explode(",", $state);
                            if (!empty($state)) {
                                
                                foreach ($state as $key => $value) {
                                    if ($key > 0) {
                                        $condition_state .= " or state='$value'";
                                    } else {
                                        $condition_state .= " state='$value'";
                                    }
                                }
                                $condition_new .= " and (" . $condition_state . " )";
                            }
                        }
                
                        if (!empty($tenderValue)) {
                            $condition_new .= " and tender_value between 0 and $tenderValue";
                        }
                        $condition_agency = "";
                        if (!empty($agency)) {
                            $agency = explode(",", $agency);
                            if (!empty($agency)) {
                                
                                foreach ($agency as $key => $value) {
                                    if ($key > 0) {
                                        $condition_agency .= " or agency_type LIKE '%$value%'";
                                    } else {
                                        $condition_agency .= " agency_type LIKE '%$value%'";
                                    }
                                }
                                $condition_new .= " and (" . $condition_agency . " )";
                            }
                        }
                        $condition_department = "";
                        if (!empty($department)) {
                            $department = explode(",", $department);
                            if (!empty($department)) {
                                
                                foreach ($department as $key => $value) {
                                    if ($key > 0) {
                                        $condition_department .= " or department LIKE '%$value%'";
                                    } else {
                                        $condition_department .= " department LIKE '%$value%'";
                                    }
                                }
                                $condition_new .= " and (" . $condition_department . " )";
                            }
                        }
                        $condition_type = "";
                        if (!empty($type)) {
                            $type = explode(",", $type);
                            if (!empty($type)) {
                                
                                foreach ($type as $key => $value) {
                                    if ($key > 0) {
                                        $condition_type .= " or tender_type LIKE '%$value%'";
                                    } else {
                                        $condition_type .= " tender_type LIKE '%$value%'";
                                    }
                                }
                                $condition_new .= " and (" . $condition_type . " )";
                            }
                        }
                        $whereClauses = [];
                        $whereClauses1 = [];
                        $whereClauses2 = [];
                        if (!empty($keywords)) {
                            $keywords = explode(',', $keywords);
                            foreach ($keywords as $keyword) {
                                $keyword_arr = explode(' ', $keyword);
                                $arr_keyword = "";
                                $cnt_in = 0;
                                // echo count($keyword_arr);
                                foreach ($keyword_arr as $key) {
                                    if ($cnt_in > 0) {
                                        $arr_keyword .= " and ";
                                    }
                                    $arr_keyword .= "title LIKE '%$key%'";
                                    $cnt_in++;
                                }
                                if (count($keyword_arr) > 1 && !empty($keyword_arr)) {
                                    $arr_keyword = " ( " . $arr_keyword . " ) ";
                                } else {
                                    $arr_keyword = " ( " . $arr_keyword . " ) ";
                                }
                                $whereClauses[] = $arr_keyword;

                                $arr_keyword1 = "";
                                $cnt_in = 0;
                                foreach ($keyword_arr as $key) {
                                    if ($cnt_in > 0) {
                                        $arr_keyword1 .= " and ";
                                    }
                                    $arr_keyword1 .= "description LIKE '%$key%'";
                                    $cnt_in++;
                                }
                                if (count($keyword_arr) > 1 && !empty($keyword_arr)) {
                                    $arr_keyword1 = " ( " . $arr_keyword1 . " ) ";
                                } else {
                                    $arr_keyword1 = " ( " . $arr_keyword1 . " ) ";
                                }
                                $whereClauses[] = $arr_keyword1;
                            }
                        }

                        if (!empty($not_used_keywords)) {
                            $not_used_keywords = explode(',', $not_used_keywords);
                            foreach ($not_used_keywords as $not_keyword) {
                                $not_keyword_arr = explode(' ', $not_keyword);
                                $arr_not_keyword = "";
                                $cnt_in = 0;
                                // echo count($keyword_arr);
                                foreach ($not_keyword_arr as $key) {
                                    if ($cnt_in > 0) {
                                        $arr_not_keyword .= " and ";
                                    }
                                    $arr_not_keyword .= "title NOT LIKE '%$key%'";
                                    $cnt_in++;
                                }
                                if (count($not_keyword_arr) > 1 && !empty($not_keyword_arr)) {
                                    $arr_not_keyword = " ( " . $arr_not_keyword . " ) ";
                                } else {
                                    $arr_not_keyword = " ( " . $arr_not_keyword . " ) ";
                                }
                                $whereClauses1[] = $arr_not_keyword;

                                $arr_not_keyword1 = "";
                                $cnt_in = 0;
                                foreach ($not_keyword_arr as $key) {
                                    if ($cnt_in > 0) {
                                        $arr_not_keyword1 .= " and ";
                                    }
                                    $arr_not_keyword1 .= "description NOT LIKE '%$key%'";
                                    $cnt_in++;
                                }
                                if (count($not_keyword_arr) > 1 && !empty($not_keyword_arr)) {
                                    $arr_not_keyword1 = " ( " . $arr_not_keyword1 . " ) ";
                                } else {
                                    $arr_not_keyword1 = " ( " . $arr_not_keyword1 . " ) ";
                                }
                                $whereClauses1[] = $arr_not_keyword1;
                            }
                        }

                        if (!empty($words)) {
                            $words = explode(',', $words);
                            foreach ($words as $word) {
                                $whereClauses2[] = "( title LIKE '%$word%' )";
                                $whereClauses2[] = "( description LIKE '%$word%' )";
                            }
                        }

                        if (!empty($whereClauses) && !empty($whereClauses1) && !empty($whereClauses2)) {
                            $whereCondition = implode(' or ', $whereClauses);
                            $whereCondition1 = implode(' and ', $whereClauses1);
                            $whereCondition2 = implode(' or ', $whereClauses2);
                            $condition = "WHERE (" . $whereCondition . " or " . $whereCondition2 . ") AND (" . $whereCondition1 . ")";
                        }

                        if (!empty($whereClauses) && empty($whereClauses1) && !empty($whereClauses2)) {
                            $whereCondition = implode(' or ', $whereClauses);
                            $whereCondition2 = implode(' or ', $whereClauses2);
                            $condition = "WHERE (" . $whereCondition .  " or " . $whereCondition2 . ")";
                        }

                        if (empty($whereClauses) && !empty($whereClauses1) && !empty($whereClauses2)) {
                            $whereCondition1 = implode(' and ', $whereClauses1);
                            $whereCondition2 = implode(' or ', $whereClauses2);
                            $condition = "WHERE (" . $whereCondition2 . ") AND (" . $whereCondition1 . ")";
                        }

                        if (!empty($whereClauses) && !empty($whereClauses1) && empty($whereClauses2)) {
                            $whereCondition = implode(' or ', $whereClauses);
                            $whereCondition1 = implode(' and ', $whereClauses1);
                            $condition = "WHERE (" . $whereCondition . ") AND (" . $whereCondition1 . ")";
                        }

                        if (!empty($whereClauses) && empty($whereClauses1)  && empty($whereClauses2)) {
                            $whereCondition = implode(' or ', $whereClauses);
                            $condition = "WHERE (" . $whereCondition . ")";
                        }

                        if (empty($whereClauses) && empty($whereClauses1)  && !empty($whereClauses2)) {
                            $whereCondition2 = implode(' or ', $whereClauses2);
                            $condition = "WHERE (" . $whereCondition2 . ")";
                        }

                        if (!empty($whereClauses1) && empty($whereClauses)  && empty($whereClauses2)) {
                            $whereCondition1 = implode(' and ', $whereClauses1);
                            $condition = "WHERE (" . $whereCondition1 . ")";
                        }

                        if (empty($condition) && !empty($condition_new)) {
                            $condition = preg_replace('/and/', 'where', $condition_new, 1);
                        } else {
                            $condition .= $condition_new;
                        }
                        if(!empty($row['keywords'])):
                            $sql = "SELECT * FROM `tenders_archive` $condition $condition_orderque_key";
                        endif;
                        //echo "SELECT * FROM `tenders_posts` $condition order by id desc";exit;

                        $kcounter = 0;
                        $ks=0;
                        $keyword_key_val = '';
                        $condition_orderque_key = '';
                        if(!empty($keywords)):
                        foreach ($keywords as $key => $value) {
                                    if ($kcounter == 0 && $key <= 0) {
                                        $keyword_key_val .= " ORDER BY CASE";
                                    } 
                                        $keyword_key_val .= " WHEN title LIKE '%$value%' THEN $ks";
                                    
                                    $kcounter++;
                                    $ks++;
                                }
                            endif;

                        if($keyword_key_val != ''){
                            $condition_orderque_key .= " " . $keyword_key_val;
                        }
                        if(!empty($keywords)):
                            $keys_count = count($keywords);
                            $condition_orderque_key .= " ELSE " . $keys_count . " END, title ASC";
                        endif;
     
                
            }

        }
        
                        $i = 1;
                       


                        $tender_data = mysqli_query($con, $sql);
                        $tender_result = mysqli_num_rows($tender_data);
                        if ($tender_result > 0) {
                            while ($row = mysqli_fetch_assoc($tender_data)) {
                                if (!empty($row['city'])) {
                                    $location = $row['city'];
                                }
                                if (!empty($row['state'])) {
                                    if (!empty($location)) {
                                        $location .= ", " . $row['state'];
                                    } else {
                                        $location = $row['state'];
                                    }
                                }
                                $tender_value = "";
                                if (empty($row['tender_value']) && $row['tender_value'] > 0) {
                                    $tender_value = 'Refer Document';
                                } else {
                                    $tender_value = $row['tender_value'];
                                }
                                $c_keywords = explode(',',$cust_keywords);
                                if (count($c_keywords) > 0) {
                    
                                    $highlightedResult = $row['title'];
                                    foreach ($c_keywords as $keyword) {
                                        $keyword_arr = explode(' ', $keyword);
                                        foreach ($keyword_arr as $key) {
                                          
                                            $highlightedResult = highlightSearchTerm($highlightedResult, $key);
                                        }
                                    }
                                    $result_title = htmlspecialcode_generator($highlightedResult);
                                }
                                $highlightedResult = $result_title;
                                
                        ?>
                            <tr>
                                <th scope="row"><input type="checkbox" name="row-check" class="row-check" value="<?php echo $row['id']; ?>"></th>
                                <td><?php echo htmlspecialcode_generator($highlightedResult); ?></td>
                                <td><?php echo $location; ?></td>
                                <td><?php echo $row['department']; ?></td>
                                <td><?php echo $row['agency_type']; ?></td>
                                <td><?php echo date('M d, Y', strtotime($row['due_date'])); ?></td>
                                <td><?php echo $tender_value; ?></td>
                            </tr>
                        <?php $i++;
                        } }?>
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
             // Disable ordering on the first column (index 0)
            { orderable: false, targets: 0 }, 
        ],
        scrollX: true,
        columns: [
            { width: '5%' }, // Column 1
            { width: '45%' }, // Column 2
            { width: '10%' }, // Column 3
            { width: '10%' }, // Column 3
            { width: '10%' }, // Column 3
            { width: '10%' }, // Column 3
            { width: '10%' }, // Column 3
        ]
        
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