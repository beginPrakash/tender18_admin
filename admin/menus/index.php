<?php include '../includes/authentication.php';

?>

<?php $pages = 'menus'; ?>

<?php include '../includes/header.php' ?>

<style>

    .menu-icon {

        position: relative;

    }



    .menu-icon i.ri-delete-bin-5-fill {

        position: absolute;

        top: 50%;

        transform: translate(-25px, -50%);

        color: red;

        cursor: pointer;

    }

</style>

<?php

if (isset($_GET['location']) && !empty($_GET['location'])) {

    $location = $_GET['location'];

} else {

    $location = "";

}



if (!empty($location)) {

    $url = "?location=" . $location;

} else {

    $url = "";

}

?>

<?php

if (isset($_POST['submit'])) {

    $menu_title = $_POST['menu_title'];

    $menu_link = $_POST['menu_link'];

    $location = $_POST['location'];

    // print_r($location);



    $query_delete = mysqli_query($con, "DELETE FROM `menus` where `location`='$location'");



    $status = true;

    foreach ($menu_title as $key => $title) {

        $title = mysqli_real_escape_string($con, $title);

        $link = mysqli_real_escape_string($con, $menu_link[$key]);

        $query = "INSERT INTO menus (`menu_title`, `menu_link`, `location`) VALUES('$title', '$link', '$location')";

        $sql = mysqli_query($con, $query);

        if (!$sql)

            $status = $sql;

    }



    if ($status) {

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

                        window.location.href='" . ADMIN_URL . "/menus/index.php" . $url . "';

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

                        window.location.href='" . ADMIN_URL . "/menus/index.php" . $url . "';

                         document.querySelector('.msg_box').remove();

                     }, 3000);

                 

             </script>";

}

?>

<div class="row">

    <div class="col-12">

        <div class="page-title-box d-sm-flex align-items-center justify-content-between">

            <h4 class="mb-sm-0">Users</h4>

        </div>

        <div class="row mb-3">

            <div class="col-lg-2">

                <label for="location" class="form-label">Menus :</label>

                <select class="form-select" name="location" id="location">

                    <option value="">Select Menu </option>

                    <option value="header" <?php if ($location == "header") {

                                                echo "selected";

                                            } ?>>Header</option>

                    <option value="quick_links" <?php if ($location == "quick_links") {

                                                    echo "selected";

                                                } ?>>Quick Links</option>

                    <option value="tenders_by_product" <?php if ($location == "tenders_by_product") {

                                                            echo "selected";

                                                        } ?>>TENDERS BY PRODUCT</option>

                </select>

            </div>

        </div>

    </div>

</div>



<?php

if (!empty($location)) {

?>

    <div class="row">

        <div class="col-lg-12">

            <div class="card">

                <div class="card-header">

                    <h5 class="card-title mb-0">Menus</h5>

                </div>

                <div class="card-body">

                    <form action="" method="post">

                        <input type="hidden" name="location" value="<?php echo $location ?>">

                        <div class="row gy-4">

                            <div class="col-xxl-12 col-md-12">

                                <div class="col-md-6">

                                    <button class="btn btn-success mb-3" id="add_more" type="button">Add more</button>

                                    <div class="accordion accordion-icon-none" id="accordionWithouticon">

                                        <?php

                                        $menus_data = mysqli_query($con, "SELECT * FROM `menus` where `location`='$location'");

                                        // var_dump("SELECT * FROM `menus` where `location`='$location'");

                                        $menus_result = mysqli_num_rows($menus_data);

                                        if ($menus_result > 0) {

                                            $count = 1;

                                            while ($row = mysqli_fetch_assoc($menus_data)) {

                                        ?>

                                                <div class="accordion-item" data-id="<?php echo $count; ?>">

                                                    <div class="p-2 menu-icon">

                                                        <input class="collapsed w-100 accordion-header pe-4 border border-light p-2" name="menu_title[]" type="text" data-bs-toggle="collapse" data-bs-target="#accor_withouticoncollapse<?php echo $count; ?>" aria-expanded="false" aria-controls="accor_withouticoncollapse<?php echo $count; ?>" value="<?php echo htmlspecialcode_generator($row['menu_title']); ?>">

                                                        <i class="ri-delete-bin-5-fill remove"></i>

                                                    </div>

                                                    <div id="accor_withouticoncollapse<?php echo $count; ?>" class="accordion-collapse collapse" aria-labelledby="Menu" data-bs-parent="#accordionWithouticon">

                                                        <div class="p-2">

                                                            <label class="w-100 h6 mb-0">Menu Link :</label>

                                                            <input type="text" class="w-100 mb-0 border border-light p-2" value="<?php echo htmlspecialcode_generator($row['menu_link']); ?>" name="menu_link[]">

                                                        </div>

                                                    </div>

                                                </div>

                                        <?php $count++;

                                            }

                                        } ?>

                                    </div>

                                </div>

                            </div>

                            <div class="col-lg-12">

                                <div class="text-start">

                                    <button type="submit" class="btn btn-primary" name="submit">Save</button>

                                </div>

                            </div>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

<?php } ?>



<?php include '../includes/footer.php';  ?>



<script>

    $('#location').change(function() {

        var location = $(this).val();

        if (location)

            window.location.href = '<?php echo ADMIN_URL; ?>menus/index.php?location=' + location;

        else

            window.location.href = '<?php echo ADMIN_URL; ?>menus/index.php';

    });

    $("#add_more").click(function() {

        var i = $(".accordion-item:last-child").attr('data-id');

        if ($(".accordion-item:last-child").length > 0) {

            i = parseInt(i);

        } else {

            i = 0;

        }

        i = i + 1;

        var html = '<div class="accordion-item" data-id="' + i + '"><div class="p-2 menu-icon"><input class="collapsed w-100 accordion-header border pe-4 border-light p-2" name="menu_title[]" type="text" data-bs-toggle="collapse" data-bs-target="#accor_withouticoncollapse' + i + '" aria-expanded="false" aria-controls="accor_withouticoncollapse' + i + '" value="Menu ' + i + '"><i class="ri-delete-bin-5-fill remove"></i></div><div id="accor_withouticoncollapse' + i + '" class="accordion-collapse collapse" aria-labelledby="Menu" data-bs-parent="#accordionWithouticon"><div class="p-2"><label class="w-100 h6 mb-0">Menu Link :</label><input type="text" value="#" class="w-100 mb-0 border border-light p-2" name="menu_link[]"></div></div></div>';



        $("#accordionWithouticon").append(html);

    });

    $(document).on('click', ".menu-icon .remove", function() {

        $(this).parent().parent().remove();

    });

</script>