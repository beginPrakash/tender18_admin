<?php



include "../includes/authentication.php";
$pages = 'blog_list_links';
?>

<?php include '../includes/header.php'; ?>

<?php // include '../includes/connection.php';



?>
<style>
    .row.link_table_data {
    margin: 0px;
}
</style>
<?php

if (isset($_POST['btnInsert'])) {
    $main_title = $_POST['main_title'];
    $link_arr = $_POST['link_arr'];

    $q1 = "UPDATE `blog_links` SET `main_title`='$main_title', `title_url`='$link_arr' WHERE `id`=1";

    $sql1 = mysqli_query($con, $q1);



    if ($sql1) {

        $_SESSION['success'] = 'Updated successfully.';

    } else {

        $_SESSION['error'] = 'Something went wrong.';

    }

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

                        window.location.href='" . ADMIN_URL . "/blog_list_links/index.php';

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

                        window.location.href='" . ADMIN_URL . "/blog_list_links/index.php';

                         document.querySelector('.msg_box').remove();

                     }, 3000);

                 

             </script>";

}



?>

<div class="row">

    <div class="col-12">

        <div class="page-title-box d-sm-flex align-items-center justify-content-between">

            <h4 class="mb-sm-0">Blog List Links</h4>

        </div>

    </div>

</div>

<?php

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

?>

<div class="row">

    <div class="col-lg-12">

        <div class="card">

            <form action="" method="post" id="smtp_form">

                <div class="card-header">

                    <h4 class="card-title mb-0">Blog List Links</h4>

                </div>

                <div class="card-body">

                    <div class="row gy-4">

                        <div class="col-xxl-12 col-md-12">

                            <div class="col-md-6">

                                <label for="main_title" class="form-label">Main Title </label>

                                <input class="form-control" type="text" name="main_title" id="main_title" value="<?php echo $main_title; ?>">


                            </div>

                        </div>

                        <div class="col-xxl-12 col-md-12">
                            <h2>Links</h2>
                            <div class="row add_payment_div">
                                <input type="hidden" name="link_arr" class="link_arr">
                                
                                <div class="col-md-2">
                                    <div class="form-group">
                                    <label class="form-label">Link Title</label>
                                    <input type="text" class="form-control link_title" id="link_title">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                    <label class="form-label">Link URL</label>
                                    <input type="text" class="form-control link_url" id="link_url">
                                    </div>
                                </div>
                                
                                <div class="col-md-2 add_btn_div">
                                    <button type="button" class="btn btn-success add_more_link_btn add_more_pay_btn">Add</button>
                                    <button type="button" class="btn btn-success update_pay_btn" style="display:none">Update</button>
                                </div>
                            </div>
                            <div class="row link_table_data">
                                <table class="link_table">
                                    <?php $i = 0; ?>
                                    <?php if(!empty($title_url) && count($title_url) > 0) { ?>
                                            <tr>
                                                <th>Link Title</th>
                                                <th>Link URL</th>
                                            </tr>
                                    <?php } ?>
                                    <?php if(!empty($title_url) && count($title_url) > 0) { ?>
                                    <?php foreach($title_url as $key => $val) { ?>
                                                
                                                
                                        <tr class="create_td" data-ind="<?Php echo $i; ?>">
                                            <td class="tsd_linktitle_<?Php echo $i; ?>"><?php echo $val->link_title; ?></td>
                                            <td class="tsd_linkurl_<?Php echo $i; ?>"><?Php echo $val->link_url; ?></td>
                                            <td>
                                                <a href="javascript:void(0);" class="remove_btn" data-indexid="<?Php echo $i; ?>"><i class="ri-delete-bin-5-fill remove"></i></a> 
                                                <a href="javascript:void(0);" class="edit_btn" data-indexid="<?Php echo $i; ?>"><i class="ri-pencil-fill"></i></a>
                                            </td>
                                        </tr>
                                    <?php  $i++; } } ?>
                                </table>
                            </div>
                        </div>
                        
                        

                    </div>

                </div>

                <div class="card-body">

                    <div class="row gy-4">

                        <div class="col-lg-12">

                            <div class="text-start">

                                <button type="submit" class="btn btn-primary" name="btnInsert">Submit</button>

                            </div>

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </div>

</div>

<?php include "../includes/footer.php" ?>

<script>
    // Add More Payment detail
    var myArray = [];

    $('.create_td').each(function(){
        var indid = $(this).attr('data-ind');
        var link_title = $('.tsd_linktitle_'+indid).html();
        var link_url = $('.tsd_linkurl_'+indid).html();

        var pieces = {                              
           "link_title" :link_title,
           "link_url" :link_url
        };

        myArray.push(pieces);
        $('.link_arr').val(JSON.stringify(myArray));
    })

    $('.update_pay_btn').click(function() {
        var indexid = $(this).attr('data-inde');
        var plink_title = $('.link_title').val();
        var plink_url = $('.link_url').val();
       
        myArray[indexid].link_title = plink_title;
        myArray[indexid].link_url = plink_url;

        $('.link_arr').val(JSON.stringify(myArray));

        $('.tsd_linktitle_'+indexid).html(plink_title);
        $('.tsd_linkurl_'+indexid).html(plink_url);
       
        $('.link_title').val('');
        $('.link_url').val('');
        $('.add_more_link_btn').show();
        $('.update_pay_btn').removeAttr('data-inde');
        $('.update_pay_btn').hide();
    });

    $('.add_more_link_btn').click(function() {
    
        var total_tr_length = $('.link_table_data tr').length;
        if(total_tr_length == 0){           
            $('.link_table').append('<tr><th>Link Title</th><th>Link URL</th></tr>');
        }
        var link_title = $('.link_title').val();
        var link_url = $('.link_url').val();
        
        var pieces = {                              
           "link_title" :link_title,
           "link_url" :link_url
        };

        myArray.push(pieces);

        var create_td_cnt = $('.create_td').length;
        $('.link_arr').val(JSON.stringify(myArray));
        
        $('.link_table tr:last').after('<tr class="create_td"><td class="tsd_linktitle_'+create_td_cnt+'">'+link_title+'</td><td class="tsd_linkurl_'+create_td_cnt+'">'+link_url+'</td><td><a href="javascript:void(0);" class="remove_btn" data-indexid="'+create_td_cnt+'"><i class="ri-delete-bin-5-fill remove"></i></a> <a href="javascript:void(0);" class="edit_btn" data-indexid="'+create_td_cnt+'"><i class="ri-pencil-fill"></i></a></td></tr>');
        $('.link_title').val('');
        $('.link_url').val('');

    });

    //remove row when click remove button
    $(document).on('click','.remove_btn',function(){
        var indexid = $(this).attr('data-indexid');
        myArray.splice(indexid, 1);
        $('.link_arr').val(JSON.stringify(myArray));
        $(this).parent().parent().remove();
    });

    //get row data when click edit button
    $(document).on('click','.edit_btn',function(){
        console.log(myArray);
        var indexid = $(this).attr('data-indexid');
        var link_ti=  myArray[indexid].link_title;
        var link_ur=  myArray[indexid].link_url;
        $('.link_title').val(link_ti);
        $('.link_url').val(link_ur);

        $('.update_pay_btn').show();
        $('.update_pay_btn').attr('data-inde',indexid);
        $('.add_more_link_btn').hide();
    });
</script>