<?php

include "../includes/authentication.php";
?>
<?php $pages = 'blogs'; ?>
<?php include '../includes/header.php'; ?>
<?php // include '../includes/connection.php';

?>
<?php
$blogs_per = _get_user_perby_role($_SESSION['user_id'],'blogs',$con);

if($_SESSION['role']!='admin' && $_SESSION['role']!='employee'){ 
    // echo "not admin ------>" . $_SESSION['role'];
    echo "<script>
            window.location.href='../index.php';
            </script>";
}elseif($_SESSION['role']=='employee' && $blogs_per!=1){ 
    // echo "not admin ------>" . $_SESSION['role'];
    echo "<script>
            window.location.href='../index.php';
            </script>";
}
if (isset($_GET['id']) && isset($_GET['unique_code'])) {
    if (empty($_GET['id'])) {
        echo "<script>
            window.location.href='" . ADMIN_URL . "blogs/index.php';
            </script>";
    }
}
?>
<?php
if (isset($_POST['submit'])) {
        $hidden_blog_image = $_POST['hidden_blog_image'];
        $blog_id = $_POST['blog_id']; 
        $title = mysqli_real_escape_string($con, $_POST['title']);
        $description = $_POST['description'];
        $file = $_FILES['blog_image'];
        $filename = $file['name'];
        $main_title = $_POST['main_title'];
        $link_arr = $_POST['link_arr'];
        $main_title1 = $_POST['main_title1'];
        $link_arr1 = $_POST['link_arr1'];
        $main_title2 = $_POST['main_title2'];
        $link_arr2 = $_POST['link_arr2'];
        $filepath = $file['tmp_name'];
        $fileerror = $file['error'];

        $con_desc = preg_replace('/(<a\s+[^>]*)(\w+)="([^"]*)"/', '$1$2=$3', $description);
        $con_desc =mysqli_real_escape_string($con, $con_desc);
        if (!empty($filename)) {
            if ($fileerror == 0) {
                $string = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
                $files =  substr(str_shuffle($string), 0, 8);
                $temp = explode(".", $filename);
                $newfilename = time() . $files . '.' . end($temp);
                $destfile = '../uploads/images/' . $newfilename;
                move_uploaded_file($filepath, $destfile);
            }
        }

        if (!empty($filename)) {
            $filevalue =  $newfilename;
        } else {
            $filevalue = $hidden_blog_image;
        }  
        $q = "UPDATE `blogs` SET title='$title', description='$con_desc', blog_image='$filevalue', main_title='$main_title', title_urls='$link_arr', main_title1='$main_title1', title_urls1='$link_arr1', main_title2='$main_title2', title_urls2='$link_arr2' where id='$blog_id'";
        // var_dump($q);
        $sql = mysqli_query($con, $q);
        
        if ($sql) {
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
                         window.location.href='" . ADMIN_URL . "/blogs/index.php';
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

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Edit</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <!-- <div class="card-header">
                <h4 class="card-title mb-0">Change Password</h4>
            </div> -->
            <?php $fetch_blogs = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM `blogs` where id={$_GET['id']}"));
            
            ?>
            <form action="" method="post" id="blog_form" enctype="multipart/form-data">
                <input type="hidden" name="blog_id" value=<?php echo $_GET['id']; ?>>
                <div class="card-body">
                    <div class="row gy-4">

                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="title" class="form-label">Title : <span class="text-danger">*</span></label>
                                <input type="text" name="title" placeholder="Enter Title " class="form-control" id="title" value="<?php echo $fetch_blogs['title']; ?>">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="description" class="form-label">Description : <span class="text-danger">*</span></label>
                                <textarea rows="5" name="description" class="form-control d-none" id="description"><?php echo $fetch_blogs['description']; ?></textarea>
                                <div class="ckeditor-classic-total ckeditor-classic-job-profile"><?php echo $fetch_blogs['description']; ?></div>
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="blog_image" class="form-label">Blog Image:</label>
                                <input class="form-control" type="file" name="blog_image" id="blog_image">
                                <?php $blog_image = $fetch_blogs['blog_image']; ?>
                                <input type="hidden" name="hidden_blog_image" value="<?php echo $blog_image; ?>">
                                <?php
                                
                                if (!empty($blog_image)) {
                                    echo '<img src="../uploads/images/' . $blog_image . '" class="img-thumbnail mt-2" width="100" height="100">';
                                }
                                ?>
                            </div>
                        </div>

                        <div class="col-xxl-12 col-md-12">

                            <div class="col-md-6">

                                <label for="main_title" class="form-label">Main Title </label>

                                <input class="form-control" type="text" name="main_title" id="main_title" value="<?php echo $fetch_blogs['main_title']; ?>">


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
                                    <button type="button" class="btn btn-success update_pay_btn update_pay_btn_main" style="display:none">Update</button>
                                </div>
                            </div>
                            <div class="row link_table_data">
                                <table class="link_table">
                                    <?php $title_url = json_decode($fetch_blogs['title_urls']); ?>
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
                                                <a href="javascript:void(0);" class="remove_btn remove_link_btn" data-indexid="<?Php echo $i; ?>"><i class="ri-delete-bin-5-fill remove"></i></a> 
                                                <a href="javascript:void(0);" class="edit_btn" data-indexid="<?Php echo $i; ?>"><i class="ri-pencil-fill"></i></a>
                                            </td>
                                        </tr>
                                    <?php  $i++; } } ?>
                                </table>
                            </div>
                        </div>

                        <div class="col-xxl-12 col-md-12">

                            <div class="col-md-6">

                                <label for="main_title1" class="form-label">Main Title </label>

                                <input class="form-control" type="text" name="main_title1" id="main_title1" value="<?php echo $fetch_blogs['main_title1']; ?>">


                            </div>

                        </div>

                        <div class="col-xxl-12 col-md-12">
                            <h2>Links</h2>
                            <div class="row add_payment_div">
                                <input type="hidden" name="link_arr1" class="link_arr1">
                                
                                <div class="col-md-2">
                                    <div class="form-group">
                                    <label class="form-label">Link Title</label>
                                    <input type="text" class="form-control link_title1" id="link_title1">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                    <label class="form-label">Link URL</label>
                                    <input type="text" class="form-control link_url1" id="link_url1">
                                    </div>
                                </div>
                                
                                <div class="col-md-2 add_btn_div">
                                    <button type="button" class="btn btn-success add_more_link_btn1 add_more_pay_btn">Add</button>
                                    <button type="button" class="btn btn-success update_pay_btn1 update_pay_btn_main" style="display:none">Update</button>
                                </div>
                            </div>
                            <div class="row link_table_data1">
                                <table class="link_table1">
                                    <?php $title_url1 = json_decode($fetch_blogs['title_urls1']); ?>
                                    <?php $i = 0; ?>
                                    <?php if(!empty($title_url1) && count($title_url1) > 0) { ?>
                                            <tr>
                                                <th>Link Title</th>
                                                <th>Link URL</th>
                                            </tr>
                                    <?php } ?>
                                    <?php if(!empty($title_url1) && count($title_url1) > 0) { ?>
                                    <?php foreach($title_url1 as $key => $val) { ?>
                                                
                                                
                                        <tr class="create_td1" data-ind="<?Php echo $i; ?>">
                                            <td class="tsd_linktitle1_<?Php echo $i; ?>"><?php echo $val->link_title; ?></td>
                                            <td class="tsd_linkurl1_<?Php echo $i; ?>"><?Php echo $val->link_url; ?></td>
                                            <td>
                                                <a href="javascript:void(0);" class="remove_btn remove_link_btn1" data-indexid="<?Php echo $i; ?>"><i class="ri-delete-bin-5-fill remove"></i></a> 
                                                <a href="javascript:void(0);" class="edit_btn1" data-indexid="<?Php echo $i; ?>"><i class="ri-pencil-fill"></i></a>
                                            </td>
                                        </tr>
                                    <?php  $i++; } } ?>
                                </table>
                            </div>
                        </div>

                        <div class="col-xxl-12 col-md-12">

                            <div class="col-md-6">

                                <label for="main_title2" class="form-label">Main Title </label>

                                <input class="form-control" type="text" name="main_title2" id="main_title2" value="<?php echo $fetch_blogs['main_title2']; ?>">


                            </div>

                        </div>

                        <div class="col-xxl-12 col-md-12">
                            <h2>Links</h2>
                            <div class="row add_payment_div">
                                <input type="hidden" name="link_arr2" class="link_arr2">
                                
                                <div class="col-md-2">
                                    <div class="form-group">
                                    <label class="form-label">Link Title</label>
                                    <input type="text" class="form-control link_title2" id="link_title2">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                    <label class="form-label">Link URL</label>
                                    <input type="text" class="form-control link_url2" id="link_url2">
                                    </div>
                                </div>
                                
                                <div class="col-md-2 add_btn_div">
                                    <button type="button" class="btn btn-success add_more_link_btn2 add_more_pay_btn">Add</button>
                                    <button type="button" class="btn btn-success update_pay_btn2 update_pay_btn_main" style="display:none">Update</button>
                                </div>
                            </div>
                            <div class="row link_table_data2">
                                <table class="link_table2">
                                    <?php $title_url2 = json_decode($fetch_blogs['title_urls2']); ?>
                                    <?php $i = 0; ?>
                                    <?php if(!empty($title_url2) && count($title_url2) > 0) { ?>
                                            <tr>
                                                <th>Link Title</th>
                                                <th>Link URL</th>
                                            </tr>
                                    <?php } ?>
                                    <?php if(!empty($title_url2) && count($title_url2) > 0) { ?>
                                    <?php foreach($title_url2 as $key => $val) { ?>
                                                
                                                
                                        <tr class="create_td2" data-ind="<?Php echo $i; ?>">
                                            <td class="tsd_linktitle2_<?Php echo $i; ?>"><?php echo $val->link_title; ?></td>
                                            <td class="tsd_linkurl2_<?Php echo $i; ?>"><?Php echo $val->link_url; ?></td>
                                            <td>
                                                <a href="javascript:void(0);" class="remove_btn remove_link_btn2" data-indexid="<?Php echo $i; ?>"><i class="ri-delete-bin-5-fill remove"></i></a> 
                                                <a href="javascript:void(0);" class="edit_btn2" data-indexid="<?Php echo $i; ?>"><i class="ri-pencil-fill"></i></a>
                                            </td>
                                        </tr>
                                    <?php  $i++; } } ?>
                                </table>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="text-start">
                                <button type="submit" class="btn btn-primary" name="submit">Submit</button>
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
    $(document).ready(function() {
        $('#blog_form').validate({
            rules: {
                'title': "required",
                'description': "required",
            },
            
        });

        ClassicEditor.create(document.querySelector(".ckeditor-classic-job-profile"))
        .then(function(c) {
            c.ui.view.editable.element.style.height = "200px";
        })
        .catch(function(c) {
            console.error(c);
        });

        $('#blog_form').submit(function(event) {
            $('.ckeditor-classic-total').each(function(i, obj) {
                var data = $(this).parent().find(".ck-editor .ck-editor__main .ck-editor__editable").html();
                if (data != "")
                    $(this).parent().find('textarea').val(data);
            });
        });

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
            
            $('.link_table tr:last').after('<tr class="create_td"><td class="tsd_linktitle_'+create_td_cnt+'">'+link_title+'</td><td class="tsd_linkurl_'+create_td_cnt+'">'+link_url+'</td><td><a href="javascript:void(0);" class="remove_btn remove_link_btn" data-indexid="'+create_td_cnt+'"><i class="ri-delete-bin-5-fill remove"></i></a> <a href="javascript:void(0);" class="edit_btn" data-indexid="'+create_td_cnt+'"><i class="ri-pencil-fill"></i></a></td></tr>');
            $('.link_title').val('');
            $('.link_url').val('');

        });

        //remove row when click remove button
        $(document).on('click','.remove_link_btn',function(){
            var indexid = $(this).attr('data-indexid');
            myArray.splice(indexid, 1);
            $('.link_arr').val(JSON.stringify(myArray));
            $(this).parent().parent().remove();
        });

        // Add More Link1 detail
        var myArray1 = [];

        $('.create_td1').each(function(){
            var indid = $(this).attr('data-ind');
            var link_title = $('.tsd_linktitle1_'+indid).html();
            var link_url = $('.tsd_linkurl1_'+indid).html();

            var pieces = {                              
            "link_title" :link_title,
            "link_url" :link_url
            };

            myArray1.push(pieces);
            $('.link_arr1').val(JSON.stringify(myArray1));
        })

        $('.update_pay_btn1').click(function() {
            var indexid = $(this).attr('data-inde');
            var plink_title = $('.link_title1').val();
            var plink_url = $('.link_url1').val();

            myArray1[indexid].link_title = plink_title;
            myArray1[indexid].link_url = plink_url;

            $('.link_arr1').val(JSON.stringify(myArray1));

            $('.tsd_linktitle1_'+indexid).html(plink_title);
            $('.tsd_linkurl1_'+indexid).html(plink_url);

            $('.link_title1').val('');
            $('.link_url1').val('');
            $('.add_more_link_btn1').show();
            $('.update_pay_btn1').removeAttr('data-inde');
            $('.update_pay_btn1').hide();
        });

        //get row data when click edit button
        $(document).on('click','.edit_btn1',function(){
            var indexid = $(this).attr('data-indexid');
            var link_ti=  myArray1[indexid].link_title;
            var link_ur=  myArray1[indexid].link_url;
            $('.link_title1').val(link_ti);
            $('.link_url1').val(link_ur);

            $('.update_pay_btn1').show();
            $('.update_pay_btn1').attr('data-inde',indexid);
            $('.add_more_link_btn1').hide();
        });


        $('.add_more_link_btn1').click(function() {

            var total_tr_length = $('.link_table_data1 tr').length;
            if(total_tr_length == 0){           
                $('.link_table1').append('<tr><th>Link Title</th><th>Link URL</th></tr>');
            }
            var link_title = $('.link_title1').val();
            var link_url = $('.link_url1').val();
            
            var pieces = {                              
            "link_title" :link_title,
            "link_url" :link_url
            };

            myArray1.push(pieces);

            var create_td_cnt = $('.create_td1').length;
            $('.link_arr1').val(JSON.stringify(myArray1));
            
            $('.link_table1 tr:last').after('<tr class="create_td1"><td class="tsd_linktitle1_'+create_td_cnt+'">'+link_title+'</td><td class="tsd_linkurl1_'+create_td_cnt+'">'+link_url+'</td><td><a href="javascript:void(0);" class="remove_btn remove_link_btn1" data-indexid="'+create_td_cnt+'"><i class="ri-delete-bin-5-fill remove"></i></a> <a href="javascript:void(0);" class="edit_btn1" data-indexid="'+create_td_cnt+'"><i class="ri-pencil-fill"></i></a></td></tr>');
            $('.link_title1').val('');
            $('.link_url1').val('');

        });

        //remove row when click remove button
        $(document).on('click','.remove_link_btn1',function(){
            var indexid = $(this).attr('data-indexid');
            myArray1.splice(indexid, 1);
            $('.link_arr1').val(JSON.stringify(myArray1));
            $(this).parent().parent().remove();
        });


        // Add More Link2 detail
        var myArray2 = [];

        $('.create_td2').each(function(){
            var indid = $(this).attr('data-ind');
            var link_title = $('.tsd_linktitle2_'+indid).html();
            var link_url = $('.tsd_linkurl2_'+indid).html();

            var pieces = {                              
            "link_title" :link_title,
            "link_url" :link_url
            };

            myArray2.push(pieces);
            $('.link_arr2').val(JSON.stringify(myArray2));
        })

        $('.update_pay_btn2').click(function() {
            var indexid = $(this).attr('data-inde');
            var plink_title = $('.link_title2').val();
            var plink_url = $('.link_url2').val();

            myArray2[indexid].link_title = plink_title;
            myArray2[indexid].link_url = plink_url;

            $('.link_arr2').val(JSON.stringify(myArray2));

            $('.tsd_linktitle2_'+indexid).html(plink_title);
            $('.tsd_linkurl2_'+indexid).html(plink_url);

            $('.link_title2').val('');
            $('.link_url2').val('');
            $('.add_more_link_btn2').show();
            $('.update_pay_btn2').removeAttr('data-inde');
            $('.update_pay_btn2').hide();
        });

        //get row data when click edit button
        $(document).on('click','.edit_btn2',function(){
            var indexid = $(this).attr('data-indexid');
            var link_ti=  myArray2[indexid].link_title;
            var link_ur=  myArray2[indexid].link_url;
            $('.link_title2').val(link_ti);
            $('.link_url2').val(link_ur);

            $('.update_pay_btn2').show();
            $('.update_pay_btn2').attr('data-inde',indexid);
            $('.add_more_link_btn2').hide();
        });


        $('.add_more_link_btn2').click(function() {

            var total_tr_length = $('.link_table_data2 tr').length;
            if(total_tr_length == 0){           
                $('.link_table2').append('<tr><th>Link Title</th><th>Link URL</th></tr>');
            }
            var link_title = $('.link_title2').val();
            var link_url = $('.link_url2').val();
            
            var pieces = {                              
            "link_title" :link_title,
            "link_url" :link_url
            };

            myArray2.push(pieces);

            var create_td_cnt = $('.create_td2').length;
            $('.link_arr2').val(JSON.stringify(myArray2));
            
            $('.link_table2 tr:last').after('<tr class="create_td2"><td class="tsd_linktitle2_'+create_td_cnt+'">'+link_title+'</td><td class="tsd_linkurl2_'+create_td_cnt+'">'+link_url+'</td><td><a href="javascript:void(0);" class="remove_btn remove_link_btn2" data-indexid="'+create_td_cnt+'"><i class="ri-delete-bin-5-fill remove"></i></a> <a href="javascript:void(0);" class="edit_btn2" data-indexid="'+create_td_cnt+'"><i class="ri-pencil-fill"></i></a></td></tr>');
            $('.link_title2').val('');
            $('.link_url2').val('');

        });

        //remove row when click remove button
        $(document).on('click','.remove_link_btn2',function(){
            var indexid = $(this).attr('data-indexid');
            myArray2.splice(indexid, 1);
            $('.link_arr2').val(JSON.stringify(myArray2));
            $(this).parent().parent().remove();
        });
    });
    
</script>