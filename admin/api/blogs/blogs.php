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

    // Optional tag filter: blogs.php?endpoint=getBlogsData&tag=<tag-slug>
    $tag_join = "";
    $result['tag'] = null;
    $tag_slug = make_slug($_GET['tag'] ?? '');
    if ($tag_slug !== '') {
        $tag_stmt = mysqli_prepare($con, "SELECT id, name, slug FROM `blog_tags` WHERE slug = ? LIMIT 1");
        mysqli_stmt_bind_param($tag_stmt, 's', $tag_slug);
        mysqli_stmt_execute($tag_stmt);
        $tag_row = mysqli_fetch_assoc(mysqli_stmt_get_result($tag_stmt));
        $tag_id = $tag_row ? (int) $tag_row['id'] : 0;
        $tag_join = " JOIN `blog_tag_relations` btr ON btr.blog_id = b.id AND btr.tag_id = $tag_id ";
        $result['tag'] = $tag_row ? ['name' => $tag_row['name'], 'slug' => $tag_row['slug']] : ['name' => $tag_slug, 'slug' => $tag_slug];
    }
    $sql_query = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) as total FROM `blogs` b $tag_join"));

    $total_query = $sql_query['total'];

    $total = ceil($total_query / $limit);

    $page = isset($_GET['page_no']) ? max(1, (int) $_GET['page_no']) : 1;

    if (empty($page) || $page < 1) {

        $page = 1;

    }

    $offset = ($page * $limit) - $limit;

    $tender_data = mysqli_query($con, "SELECT b.*, a.name AS author_name FROM `blogs` b $tag_join
        LEFT JOIN `blog_authors` a ON a.id = b.author_id
        ORDER BY b.publish_date DESC, b.id DESC LIMIT $offset, $limit");

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

            $url_title = !empty($row['slug']) ? $row['slug'] : make_slug($row['title']);

            $result['blogs'][$count]['blog_id'] = $row['id'];

            $result['blogs'][$count]['title'] = htmlspecialcode_generator($row['title']);

            $result['blogs'][$count]['url_title'] = $url_title;

            $result['blogs'][$count]['author_name'] = !empty($row['author_name']) ? $row['author_name'] : 'Tender18 Team';

            $result['blogs'][$count]['publish_date'] = $row['publish_date'] ?: date('Y-m-d', strtotime($row['created_at']));

            $result['blogs'][$count]['publish_date_display'] = format_blog_date($result['blogs'][$count]['publish_date']);

            $result['blogs'][$count]['description'] = htmlspecialcode_generator($row['description']);

            $result['blogs'][$count]['blog_image'] = htmlspecialcode_generator($blog_image);

            $count++;

        }

    } else {

        $result['blogs'] = [];

    }

    //get blog links
    $blog_links = "";

    $title_url = [];


    $header_data = mysqli_query($con, "SELECT * FROM `blog_links` where `id`=1");

    $header_result = mysqli_num_rows($header_data);

    if ($header_result == 1) {

        while ($row = mysqli_fetch_assoc($header_data)) {

            $main_title = $row['main_title'];

            $title_url = json_decode($row['title_url']);

        }

    }

    if(!empty($title_url) && count($title_url) > 0){
        $result['main_title'] = $main_title;
        $titlearr = [];
        foreach($title_url as $key => $val){
         
            $titlearr[$key]['link_title'] = $val->link_title;
            $titlearr[$key]['link_url'] = $val->link_url;
        }
      
        $result['title_urls'] = $titlearr;

    }else{
        $result['main_title'] = "";
        $result['title_urls'] = "";
    }

    if ($total > 1) {

        if ($page == 2) {

            $result['links'][] = ($page - 1);

        }

        if ($page > 2) {

            $result['links'][] = 1;

            if ($page > 3) {

                $result['links'][] = '...';

            }

        }

        for ($i = max(2, $page - 2); $i < $page; $i++) {

            $result['links'][] = $i;

        }

        $result['links'][$page] = "<b>" . $page . "</b>";

        for ($i = $page + 1; $i <= min($total - 1, $page + 2); $i++) {

            $result['links'][] = $i;

        }

        if ($page < $total - 1) {

            if ($page < $total - 2) {

                $result['links'][] = '...';

            }

            $result['links'][] = $total;

        }

        if ($page == $total - 1) {

            $result['links'][] = ($page + 1);

        }

    } else {

        $result['links'] = [];

    }

    return $result;

}



if ($result === null) {



    echo json_encode(array("status" => "error"));



} else {



    echo json_encode(array("status" => " success", "data" => $result),JSON_PARTIAL_OUTPUT_ON_ERROR);



}



die();



