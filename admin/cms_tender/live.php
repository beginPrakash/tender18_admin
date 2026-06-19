<?php include '../includes/without_authentication.php';
include '../../elasticsearch/elastic_client.php';
include '../../elasticsearch/elastic_utils.php';
$index = ES_INDEXES['LIVE'];

if (isset($_GET['page-limit']) && !empty($_GET['page-limit'])) {
    $_SESSION['page_limit'] = $_GET['page-limit'];
}

$customer_id = base64_decode($_GET['id']);

// Pagination Utils START
function pageUrl($baseUrl, $params, $page) {
    $params['page_no'] = $page;
    return $baseUrl . '?' . http_build_query($params);
}

$baseParams = [
    'id' => $_GET['id'] ?? null,
];

if (!empty($_GET['search_term'])) {
    $baseParams['search_term'] = $_GET['search_term'];
}

$baseUrl = ADMIN_URL . 'cms_tender/live.php';
// Pagination Utils END

$page = $_GET['page_no'] ?? 1;
$size = $_SESSION['page_limit'] ?? 35;

$result = get_results($con, $index, $page, $size, $customer_id);

function get_results($con, $index, $page, $size, $customer_id)
{
    // START Customer
    $sql = "SELECT mail_type, email_ids, company_name, keywords, words, not_used_keywords, filter_city, filter_state, filter_tender_min_value, filter_tender_value, filter_agency, filter_department, filter_type FROM cms_customer WHERE customer_id = ? LIMIT 1";

    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 's', $customer_id);
    mysqli_stmt_execute($stmt);

    $user_result = mysqli_stmt_get_result($stmt);

    $userFilters = [
        'keywords'          => [],
        'words'             => [],
        'not_used_keywords' => [],
        'city'              => [],
        'state'             => [],
        'tender_value_from' => 0,
        'tender_value_to'   => null,
        'agency'            => [],
        'department'        => [],
        'type'              => [],
    ];

    if ($row = mysqli_fetch_assoc($user_result)) {
        $userFilters = [
            'keywords'          => normalize_filters_array($row['keywords']),
            'words'             => normalize_filters_array($row['words']),
            'not_used_keywords' => normalize_filters_array($row['not_used_keywords']),
            'city'              => normalize_filters_array($row['filter_city']),
            'state'             => normalize_filters_array($row['filter_state']),
            'tender_value_from' => $row['filter_tender_min_value'],
            'tender_value_to'   => $row['filter_tender_value'],
            'agency'            => normalize_filters_array($row['filter_agency'], false),
            'department'        => normalize_filters_array($row['filter_department']),
            'type'              => normalize_filters_array($row['filter_type']),
        ];
    }

    mysqli_stmt_close($stmt);
    // END Customer

    $filter_keyword = array_values(array_unique(array_filter(array_merge(
        is_string($_GET['search_term'] ?? null)
            ? [trim($_GET['search_term'])]
            : [],
        is_array($userFilters['keywords'] ?? null)
            ? $userFilters['keywords']
            : [],
        is_array($userFilters['words'] ?? null)
            ? $userFilters['words']
            : []
    ))));
    
    $filters = [
        'tender_value_from' => prefer(null, $userFilters['tender_value_from'] ?? null, 0),
        'tender_value_to'   => prefer(null, $userFilters['tender_value_to'] ?? null, 0),
        'keyword' => prefer(null, $userFilters['keywords']),
        'words' => prefer(null, $userFilters['words']),
        'search_keyword' => $_GET['search_term'] ?? null,
        'not_used_keywords' => $userFilters['not_used_keywords'] ?? [],
        'state' => prefer([], $userFilters['state'], []),
        'city' => prefer([], $userFilters['city'], []),
        'agency' => prefer([], $userFilters['agency'], []),
        'department' => prefer([], $userFilters['department'], []),
        'tender_type' => prefer([], $userFilters['type'], [])
    ];

    $body = build_elastic_cms_query($filters, $page, $size);

    $resp = es_search($index, $body);

    $total_query = isset($resp['body']['hits']['total']['value']) ? (int)$resp['body']['hits']['total']['value'] : 0; 
    $total = ceil($total_query / $size);
    $data = isset($resp['body']['hits']['hits']) ? $resp['body']['hits']['hits'] : array();

    if (isset($data) && count($data) > 0) {
        $count = 1;
        foreach ($data as $source) {
            $row = $source['_source'];
            
            $result['tenders'][$count]['id'] = $source['_id'];
            $result['tenders'][$count]['ref_no'] = $row['ref_no'];
            $location = "";
            $city = "";
            $state="";
            if (!empty($row['city'])) {
                $location = $row['city'];
                $city = $row['city'];
            }
            if (!empty($row['state'])) {
                if (!empty($location)) {
                    $location .= ", " . $row['state'];
                    $state = $row['state'];
                } else {
                    $location = $row['state'];
                    $state = $row['state'];
                }
            }
            $result['tenders'][$count]['location'] = $location;
            $pincode = "";
            if (empty($row['pincode'])) {
                $pincode = 'Refer Document';
            } else {
                $pincode = $row['pincode'];
            }
            $result['tenders'][$count]['pincode'] = $pincode;

            $highlightedResult = $row['title'];
             // Ensure array
            if (!is_array($filter_keyword)) {
                $filter_keyword = [$filter_keyword];
            }
           
            $highlightedResult = highlight_all_keywords($highlightedResult, $filter_keyword, '<strong style=color:#cb192d;margin-right:0px;>', '</strong>');

            $result['tenders'][$count]['title'] = htmlspecialcode_generator($highlightedResult);
            $result['tenders'][$count]['city'] = $city;
            $result['tenders'][$count]['tend_city'] = str_replace(' ', '-', $city).'-tenders';
            $result['tenders'][$count]['state'] = $state;
            $result['tenders'][$count]['tend_state'] = str_replace(' ', '-', $state).'-tenders';
            $result['tenders'][$count]['agency'] = htmlspecialcode_generator($row['agency_type']);
            $result['tenders'][$count]['tend_agency'] = str_replace(' ', '-', htmlspecialcode_generator($row['agency_type'])).'-tenders';
            $result['tenders'][$count]['publish_date'] = date('M d, Y', strtotime($row['publish_date']));
            $result['tenders'][$count]['due_date'] = date('M d, Y', strtotime($row['due_date']));
            $tender_value = "";
            if (empty($row['tender_value']) && $row['tender_value'] > 0) {
                $tender_value = 'Refer Document';
            } else {
                $tender_value = $row['tender_value'];
            }
            $result['tenders'][$count]['tender_value'] = $tender_value;
            $tender_fee = "";
            if (empty($row['tender_fee']) && $row['tender_fee'] > 0) {
                $tender_fee = 'Refer Document';
            } else {
                $tender_fee = $row['tender_fee'];
            }
            $result['tenders'][$count]['tender_fee'] = $tender_fee;
            $tender_emd = "";
            if (empty($row['tender_emd']) && $row['tender_emd'] > 0) {
                $tender_emd = 'Refer Document';
            } else {
                $tender_emd = $row['tender_emd'];
            }
            $result['tenders'][$count]['tender_emd'] = $tender_emd;
            $result['tenders'][$count]['documents'] = "#";

            $result['tenders'][$count]['whatsapp_no'] = $whatsapp_no;
            $result['tenders'][$count]['dep_type'] = $row['department'];
            $count++;
        }
        $result = ['total' => $total_query, 'data' => $result ];
    } else {
        $result = [];
    }

    return $result;
}
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
                        window.location.href='" . ADMIN_URL . "/cms_tender/live.php?id=".$_GET['id'].";
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

    $highlightMarkup = '<strong style=color:#cb192d;margin-right:0px;>';
    $closingHighlightMarkup = '</strong>';
    $highlightedText = preg_replace("/({$searchTerm})/i", $highlightMarkup . '$1' .$closingHighlightMarkup, $text);
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
            <h4 class="mb-sm-0">Live Tenders</h4>
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
                            <h5 class="card-title btn w-100 tend_btn text-white">New Tenders</h5>
                        </a>
                    </div>
                    <div class="col-2">
                        <a class="dropdown-item fs-sm" href="<?php echo ADMIN_URL; ?>cms_tender/live.php?id=<?php echo $_GET['id']; ?>">
                            <h5 class="card-title btn w-100 text-white bg-primary">Live Tenders</h5>
                        </a>
                    </div>
                    <div class="col-2">
                        <a class="dropdown-item fs-sm" href="<?php echo ADMIN_URL; ?>cms_tender/archive.php?id=<?php echo $_GET['id']; ?>">
                            <h5 class="card-title btn w-100 tend_btn text-white">Archive Tenders</h5>
                        </a>
                    </div>
                </div>
                <p>View Tender Link : <span class="copy" style="cursor: copy;color:#5a58eb" data-id="<?php echo HOME_URL . "cms-user/new-tenders?id=" . $_GET['id']; ?>"><?php echo HOME_URL . "cms-user/new-tenders?id=" . $_GET['id']; ?><span><p>
            </div>
            <div class="card-header">               
                <button type="button" onclick="dataSelected()" class="card-title float-start btn bg-success text-white mb-0">Send Email</button>
            </div>
            <div class="card-body dataTables_wrapper pt-0">
                <div id="example_filter" class="dataTables_filter">
                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <div class="dataTables_length d-flex align-items-center h-100" id="example_length">
                                <form>
                                    <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
                                    <?php if (isset($_GET['search_term']) && !empty($_GET['search_term'])) { ?>
                                        <input type="hidden" name="search_term" value="<?php echo $_GET['search_term']; ?>">
                                    <?php } ?>
                                    <label>
                                        Show
                                        <select name="page-limit" aria-controls="example" onchange="this.form.submit()" class="form-select form-select-sm">
                                            <option value="10" <?= (isset($_SESSION['page_limit']) && $_SESSION['page_limit'] == 10) ? 'selected' : '' ?>>10</option>
                                            <option value="25" <?= (isset($_SESSION['page_limit']) && $_SESSION['page_limit'] == 25) ? 'selected' : '' ?>>25</option>
                                            <option value="50" <?= (isset($_SESSION['page_limit']) && $_SESSION['page_limit'] == 50) ? 'selected' : '' ?>>50</option>
                                            <option value="100" <?= (isset($_SESSION['page_limit']) && $_SESSION['page_limit'] == 100) ? 'selected' : '' ?>>100</option>
                                        </select>
                                        entries
                                    </label>
                                </form>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <form class="mb-3 d-flex gap-2 justify-content-end">
                                <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
                                <label>
                                    <input type="search" name="search_term" value="<?php echo $_GET['search_term'] ?? ''; ?>" class="form-control" placeholder="" aria-controls="example">
                                </label>
                                <button type="submit" class="btn btn-primary">Search</button>
                            </form>
                        </div>
                    </div>
                </div>
                <?php
                $cms_cust_data = mysqli_query($con, "SELECT * FROM `cms_customer` where customer_id='" . $customer_id . "'");
                $cms_cust_result = mysqli_num_rows($cms_cust_data);
                if ($cms_cust_result == 1) {
                    while ($row = mysqli_fetch_assoc($cms_cust_data)) {
                        $cust_from_email = $row['sender_email_id'];
                        $cust_reply_email = $row['reply_email_id'];
                    }
                }
                ?>
                <form id="deleteForm" method="POST" action="send_email.php">
                    <input type="hidden" name="ids" id="ids">
                    <input type="hidden" name="cust_from_email" value="<?php echo $cust_from_email; ?>">
                    <input type="hidden" name="cust_reply_email" value="<?php echo $cust_reply_email; ?>">
                    <input type="hidden" name="cms_id" value="<?php echo $_GET['id']; ?>">
                </form>
                <table class="table table-bordered dt-responsive dataTable nowrap table-striped align-middle" style="width: 100%">
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
                            $page = isset($page) ? abs((int) $page) : 1;
                            $offset = ($page * $size) - $size;
                            if (isset($result['total']) && $size > $result['total']) {
                                $size = $result['total'];
                            }
                            $total = ceil($result['total'] / $size);
                            $tenders = $result['data']['tenders'] ?? [];
                            
                            if (!empty($tenders) && $tenders > 0) {
                                $i = ($offset + 1);
                                foreach ($tenders as $row) {
                        ?>
                            <tr class="<?php if ($i % 2 == 0) {
                                    echo "even";
                                } else {
                                    echo "odd";
                                } ?>">
                                <th scope="row"><input type="checkbox" name="row-check" class="row-check" value="<?php echo $row['id']; ?>"></th>
                                <td style="width: 514px;"><?php echo $row['title']; ?></td>
                                <td style="width: 128px;"><?php echo $row['location']; ?></td>
                                <td style="width: 118px;"><?php echo $row['dep_type']; ?></td>
                                <td style="width: 118px;"><?php echo $row['agency']; ?></td>
                                <td style="width: 118px;"><?php echo $row['due_date']; ?></td>
                                <td style="width: 129px;"><?php echo $row['tender_value']; ?></td>
                            </tr>
                         <?php $i++;
                            }
                        } else { ?>
                            <tr class="odd">
                                <td colspan="9" class="dataTables_empty">No tenders found.</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <div class="row">
                    <div class="col-sm-12 col-md-5">
                        <div class="dataTables_info" id="example_info" role="status" aria-live="polite">Showing <?php if (isset($tenders) && $tenders > 0) { echo ($offset + 1); } else { echo "0"; } ?> to <?php echo ($page * $size); ?> of <?php echo $result['total']; ?> entries</div>
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