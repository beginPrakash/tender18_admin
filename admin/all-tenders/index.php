<?php include '../includes/authentication.php';
include '../../elasticsearch/elastic_client.php';
include '../../elasticsearch/elastic_utils.php';
$index = ES_INDEXES['ALL'];


function get_month_year_list(){
    $start    = '2022-01-01';
    $end      = date('Y-m-d',strtotime('+1 year'));
    $getRangeYear   = range(gmdate('Y', strtotime($start)), gmdate('Y', strtotime($end)));
    $month = date('n');
    $cyear = date('Y');
    if (count($getRangeYear) > 0) {
        rsort($getRangeYear);
        $count = 1;
        $scount = 100;
        foreach($getRangeYear as $key => $val){
            if((($month >= 1 && $month <= 12) && $cyear==$val) || ($val<=$cyear)):
                if($val==$cyear && ($month >= 1 && $month <= 12)):
                    $result[$count]['label'] = 'Jan to June '.$val;
                    $result[$count]['value'] = '01-01-'.$val.'/30-06-'.$val;
                elseif($val!=$cyear || ($month >= 1 && $month <= 12)):
                    $result[$count]['label'] = 'Jan to June '.$val;
                    $result[$count]['value'] = '01-01-'.$val.'/30-06-'.$val;
                endif;
            endif;
            if((($month >= 7 && $month <= 12) && $cyear==$val) || ($val<=$cyear)):
                if($val==$cyear && ($month >= 7 && $month <= 12)):
                    $result[$scount]['label'] = 'July to Dec '.$val;
                    $result[$scount]['value'] = '01-07-'.$val.'/31-12-'.$val;
                elseif($val!=$cyear || ($month >= 7 && $month <= 12)):
                    $result[$scount]['label'] = 'July to Dec '.$val;
                    $result[$scount]['value'] = '01-07-'.$val.'/31-12-'.$val;
                endif;
            endif;
            $count++;
            $scount++;
            
        }
        $result = array_values($result);
    }else {
        $result = [];
    }
    return $result;
}

$get_month_list = get_month_year_list();
$latest_date = $get_month_list[0]['value'];

if (isset($_GET['page-limit']) && !empty($_GET['page-limit'])) {
    $_SESSION['page_limit'] = $_GET['page-limit'];
}

$page = $_GET['page_no'] ?? 1;
$size = $_SESSION['page_limit'] ?? 10;


$result = get_results($con, $index, $page, $size, $latest_date);

function get_results($con, $index, $page, $size, $latest_date)
{
    $search = $_GET['search_term'];
    if(isset($_GET['due_date']) && !empty($_GET['due_date'])) {
        $due_date = $_GET['due_date'];    
    } else {
        $due_date = $latest_date;
    }
    
    $arr = explode('/', $due_date);
    $from = date('Y-m-d', strtotime($arr[0]));
    $to = date('Y-m-d', strtotime($arr[1]));

    // return $con;
    $start_date = $from;
    $end_date = $to;

    $filter_keyword = $search ?? null;
    
    $filters = [
        'keyword' => $search ?? null,
        'start_date' => $start_date,
        'end_date' => $end_date
    ];

    $body = build_elastic_admin_query($filters, $page, $size);
    // echo "<pre>"; print_r($body); die;

    $resp = es_search($index, $body);
    $total_query = isset($resp['body']['hits']['total']['value']) ? (int)$resp['body']['hits']['total']['value'] : 0; 
    $total = ceil($total_query / $size);
    $data = isset($resp['body']['hits']['hits']) ? $resp['body']['hits']['hits'] : array();

    if (isset($data) && count($data) > 0) {
        $result = [
            'total' => $total_query,
            'data'  => $data
        ];
    } else {
        $result = [];
    }

    return $result;
}
?>
<?php $pages = 'all-tenders'; ?>
<?php include '../includes/header.php' ?>

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
                        window.location.href='" . ADMIN_URL . "/all-tenders/index.php';
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
                        window.location.href='" . ADMIN_URL . "/all-tenders/index.php';
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
            <h4 class="mb-sm-0">All Tenders</h4>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <?php
            ?>
            <div class="card-header">
               
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
                                <?php if (isset($_GET['due_date']) && !empty($_GET['due_date'])) { ?>
                                    <input type="hidden" name="due_date" value="<?php echo $_GET['due_date']; ?>">
                                <?php } ?>
                                <label>
                                    <select class="form-select form-control" name="due_date" id="due_date">
                                        <?php if(count($get_month_list) > 0) {
                                            $fdue_date = $_GET['due_date'] ?? '';
                                            foreach($get_month_list as $key => $val){ ?>
                                                <option value="<?php echo $val['value']; ?>" <?php if($val['value'] == $fdue_date) { echo 'selected'; } ?>><?php echo $val['label']; ?></option>
                                        <?php    }
                                        } ?>
                                    </select>
                                </label>
                                <label>
                                    <input type="search" name="search_term" value="<?php echo $_GET['search_term'] ?? ''; ?>" class="form-control" placeholder="" aria-controls="example">
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
                            $page = isset($page) ? abs((int) $page) : 1;
                            $offset = ($page * $size) - $size;
                            if (isset($result['total']) && $size > $result['total']) {
                                $size = $result['total'];
                            }
                            $total = ceil($result['total'] / $size);


                            if (!empty($result['data']) && $result['data'] > 0) {
                                $i = ($offset + 1);
                                foreach ($result['data'] as $source) {
                                $data = $source['_source'];
                            ?>
                                <tr class="<?php if ($i % 2 == 0) {
                                                echo "even";
                                            } else {
                                                echo "odd";
                                            } ?>">
                                    <th scope="row" class="table_body" data-id="<?php echo $source['_id']; ?>"><?php echo $i; ?></th>
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
                                            <a href="<?php echo ADMIN_URL; ?>all-tenders/view.php?id=<?php echo $source['_id']; ?>"><i style="font-size: 20px;" class="ri-eye-fill text-success"></i></a>
                                        </div>
                                    </td>
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
                        <div class="dataTables_info" id="example_info" role="status" aria-live="polite">Showing <?php if (isset($result['data']) && $result['data'] > 0) { echo ($offset + 1); } else { echo "0"; } ?> to <?php echo ($page * $size); ?> of <?php echo $result['total']; ?> entries</div>
                    </div>
                    <div class="col-sm-12 col-md-7">
                        <?php
                        if ($total > 1) {
                            // display the "previous" link
                            $data = '<div class="dataTables_paginate paging_simple_numbers">';
                            $data .= '<ul class="pagination">';
                            if ($page > 1) {
                                $data .= '<li class="paginate_button page-item previous"><a class="page-link" href="' . ADMIN_URL . 'all-tenders/index.php' . '?page_no=' . ($page - 1) . '">Previous</a></li>';
                            }
                            // display the "previous" link
                            if ($page == 2) {
                                $data .= '<li class="paginate_button page-item"><a href="' . ADMIN_URL . 'all-tenders/index.php' . '?page_no=1" class="page-link">' . ($page - 1) . '</a></li>';
                            }
                            // display the first page link
                            if ($page > 2) {
                                $data .= '<li class="paginate_button page-item"><a href="' . ADMIN_URL . 'all-tenders/index.php' . '?page_no=1" class="page-link">1</a></li>';
                                // add an ellipsis to indicate skipped pages
                                if ($page > 3) {
                                    $data .= '<li class="paginate_button page-item"><a class="ellipsis page-link" style="pointer-events: none;">...</a></li>';
                                }
                            }
                            // display up to 3 pages before the current page
                            for ($i = max(2, $page - 2); $i < $page; $i++) {
                                $data .= '<li class="paginate_button page-item"><a href="' . ADMIN_URL . 'all-tenders/index.php' . '?page_no=' . $i . '" class="page-link">' . $i . '</a></li>';
                            }
                            // display the current page number
                            $data .= '<li class="paginate_button page-item active"><a class="page-link" style="pointer-events: none;">' . $page . '</a></li>';
                            // display up to 3 pages after the current page
                            for ($i = $page + 1; $i <= min($total - 1, $page + 2); $i++) {
                                $data .= '<li class="paginate_button page-item"><a href="' . ADMIN_URL . 'all-tenders/index.php' . '?page_no=' . $i . '" class="page-link">' . $i . '</a></li>';
                            }
                            // display the last page link
                            if ($page < $total - 1) {
                                // add an ellipsis to indicate skipped pages
                                if ($page < $total - 2) {
                                    $data .= '<li class="paginate_button page-item"><a class="ellipsis page-link" style="pointer-events: none;">...</a></li>';
                                }
                                $data .= '<li class="paginate_button page-item"><a href="' . ADMIN_URL . 'all-tenders/index.php' . '?page_no=' . $total . '" class="page-link">' . $total . '</a></li>';
                            }
                            // display the "next" link
                            if ($page == $total - 1) {
                                $data .= '<li class="paginate_button page-item"><a href="' . ADMIN_URL . 'all-tenders/index.php' . '?page_no=' . ($page + 1) . '" class="page-link">' . ($total) . '</a></li>';
                            }
                            // display the "next" link
                            if ($page < $total) {
                                $data .= '<li class="paginate_button page-item next"><a class="page-link" href="' . ADMIN_URL . 'all-tenders/index.php' . '?page_no=' . ($page + 1) . '">Next</i></a></li>';
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
    $('.pagination li.paginate_button.page-item').click(function(e) {
        e.preventDefault();
        var url = window.location.href.split('?')[0];
        var search = $("form input[name=search_term]").val();
        var due_date = $("form input[name=due_date]").val();
        var curr_page = $(this).text();
        if (search && due_date) {
            if ($(this).hasClass('previous')) {
                url += '?search_term=' + search + '&due_date=' + due_date + '&page_no=' + (parseInt($('.pagination li.paginate_button.page-item.active a').text()) - 1);
            } else if ($(this).hasClass('next')) {
                url += '?search_term=' + search + '&due_date=' + due_date + '&page_no=' + (parseInt($('.pagination li.paginate_button.page-item.active a').text()) + 1);
            } else {
                url += '?search_term=' + search + '&due_date=' + due_date + '&page_no=' + curr_page;
            }
        } else if (search) {
            if ($(this).hasClass('previous')) {
                url += '?search_term=' + search + '&page_no=' + (parseInt($('.pagination li.paginate_button.page-item.active a').text()) - 1);
            } else if ($(this).hasClass('next')) {
                url += '?search_term=' + search + '&page_no=' + (parseInt($('.pagination li.paginate_button.page-item.active a').text()) + 1);
            } else {
                url += '?search_term=' + search + '&page_no=' + curr_page;
            }
        } else if (due_date) {
            if ($(this).hasClass('previous')) {
                url += '?due_date=' + due_date + '&page_no=' + (parseInt($('.pagination li.paginate_button.page-item.active a').text()) - 1);
            } else if ($(this).hasClass('next')) {
                url += '?due_date=' + due_date + '&page_no=' + (parseInt($('.pagination li.paginate_button.page-item.active a').text()) + 1);
            } else {
                url += '?due_date=' + due_date + '&page_no=' + curr_page;
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