<?php



include '../../includes/connection.php';



include '../../includes/functions.php';



header("Content-Type: application/json");



header('Access-Control-Allow-Origin: *');







$endpoint = isset($_GET['endpoint']) ? $_GET['endpoint'] : '';



switch ($endpoint) {



    case 'getBlogsData':



        $result = get_results($con);



        break;



    default:



        $result = null;



}







function get_results($con)

{

    

    $limit = 10;

    $sql_query = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) as total FROM `blogs` order by id desc "));

    $total_query = $sql_query['total'];

    $total = ceil($total_query / $limit);

    $page = isset($_GET['page_no']) ? abs((int) $_GET['page_no']) : 1;

    if (empty($page) || $page < 1) {

        $page = 1;

    }

    $offset = ($page * $limit) - $limit;

    $tender_data = mysqli_query($con, "SELECT * FROM `blogs` order by id desc");

    $tender_result = mysqli_num_rows($tender_data);

    if ($limit > $total_query) {

        $limit = $total_query;

    }

    if ($tender_result > 0) {

        $count = 1;

        while ($row = mysqli_fetch_assoc($tender_data)) {

            $image = $row['blog_image'];

            $blog_image = '';

            if (!empty($image)) {

                $blog_image =  ADMIN_URL . 'uploads/images/' . $image;

            }

            $url_title = str_replace(" ","-",$row['title']);

            $url_title = rtrim($url_title, "-");

            $result['blogs'][$count]['blog_id'] = $row['id'];

            $result['blogs'][$count]['title'] = htmlspecialcode_generator($row['title']);

            $result['blogs'][$count]['url_title'] = $url_title;

            $result['blogs'][$count]['description'] = htmlspecialcode_generator($row['description']);

            $result['blogs'][$count]['blog_image'] = htmlspecialcode_generator($blog_image);

            $count++;

        }

    } else {

        $result['blogs'] = [];

    }

    // if ($total > 1) {

    //     if ($page == 2) {

    //         $result['links'][] = ($page - 1);

    //     }

    //     if ($page > 2) {

    //         $result['links'][] = 1;

    //         if ($page > 3) {

    //             $result['links'][] = '...';

    //         }

    //     }

    //     for ($i = max(2, $page - 2); $i < $page; $i++) {

    //         $result['links'][] = $i;

    //     }

    //     $result['links'][$page] = "<b>" . $page . "</b>";

    //     for ($i = $page + 1; $i <= min($total - 1, $page + 2); $i++) {

    //         $result['links'][] = $i;

    //     }

    //     if ($page < $total - 1) {

    //         if ($page < $total - 2) {

    //             $result['links'][] = '...';

    //         }

    //         $result['links'][] = $total;

    //     }

    //     if ($page == $total - 1) {

    //         $result['links'][] = ($page + 1);

    //     }

    // } else {

    //     $result['links'] = [];

    // }

    return $result;

}







if ($result === null) {



    echo json_encode(array("status" => "error"));



} else {



    echo json_encode(array("status" => " success", "data" => $result),JSON_PARTIAL_OUTPUT_ON_ERROR);



}



die();



