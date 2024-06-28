<?php

include "../includes/authentication.php";
?>
<?php $pages = 'clients'; ?>
<?php
if (isset($_POST['get_choices_suggestions']) and !empty($_POST['get_choices_suggestions'])) {
    // $suggestions = $_POST['get_choices_suggestions'];
    $curr_val = $_POST['curr_val'];
    $curr_ajax_val = $_POST['curr_ajax_val'];
    // echo $suggestions . " " . $curr_val . " " . $curr_ajax_val;

    if ($curr_ajax_val == "filter_city") {
        $sql_acyear = mysqli_query($con, "SELECT * FROM tender_zipcodes WHERE city like '%" . $curr_val . "%' group by city");
        if ($sql_acyear) {
            if (mysqli_num_rows($sql_acyear) > 0) {
                while ($row_acyear = mysqli_fetch_assoc($sql_acyear)) {
                    echo '<p>' . $row_acyear['city'] . '</p>';
                }
            }
        }
    } else if ($curr_ajax_val == "filter_agency") {
        $sql_acyear = mysqli_query($con, "SELECT * FROM tender_agencies WHERE agency_name like '%" . $curr_val . "%' group by agency_name");
        if ($sql_acyear) {
            if (mysqli_num_rows($sql_acyear) > 0) {
                while ($row_acyear = mysqli_fetch_assoc($sql_acyear)) {
                    echo '<p>' . $row_acyear['agency_name'] . '</p>';
                }
            }
        }
    } else if ($curr_ajax_val == "filter_state") {
        $sql_acyear = mysqli_query($con, "SELECT * FROM tender_zipcodes WHERE `state` like '%" . $curr_val . "%' group by `state`");
        if ($sql_acyear) {
            if (mysqli_num_rows($sql_acyear) > 0) {
                while ($row_acyear = mysqli_fetch_assoc($sql_acyear)) {
                    echo '<p>' . $row_acyear['state'] . '</p>';
                }
            }
        }
    }

    die();
}
?>
<?php include '../includes/header.php'; ?>
<?php // include '../includes/connection.php';

?>
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
?>
<?php
if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $pass = md5($_POST['pass']);
    // $user_role = $_POST['user_role'];
    $user_role = "user";
    $string = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
    $files =  substr(str_shuffle($string), 0, 8);
    $unique_code = time() . $files;
    $fetch = mysqli_num_rows(mysqli_query($con, "SELECT * FROM users WHERE  users_name='$username' OR users_email='$email'"));

    if (isset($_POST['company_name'])) {
        $company_name = mysqli_real_escape_string($con, $_POST['company_name']);
        $customer_name = $_POST['customer_name'];
        $alt_email = $_POST['alt_email'];
        $mobile_number = $_POST['mobile_number'];
        $alt_mobile = $_POST['alt_mobile'];
        $whatsapp_alert_no = $_POST['whatsapp_alert_no'];
        $address = mysqli_real_escape_string($con, $_POST['address']);
        $state = $_POST['state'];
        $status = $_POST['status'];
        $keywords = mysqli_real_escape_string($con, $_POST['keywords']);
        $words = mysqli_real_escape_string($con, $_POST['words']);
        $not_used_keywords = mysqli_real_escape_string($con, $_POST['not_used_keywords']);
        $all_filters = implode(",", $_POST['all_filters']);
        $filter_city = mysqli_real_escape_string($con, $_POST['filter_city']);
        $filter_state = mysqli_real_escape_string($con, $_POST['filter_state']);
        $filter_tender_value = mysqli_real_escape_string($con, $_POST['filter_tender_value']);
        $filter_agency = mysqli_real_escape_string($con, $_POST['filter_agency']);
        $filter_department = mysqli_real_escape_string($con, $_POST['filter_department']);
        $filter_type = mysqli_real_escape_string($con, $_POST['filter_type']);
        $start_date = $_POST['start_date'];
        $duration = $_POST['duration'];
        $expired_date = $_POST['expired_date'];

        $timestamp1 = strtotime($start_date);
        $start_date = date("Y-m-d", $timestamp1);

        $timestamp2 = strtotime($expired_date);
        $expired_date = date("Y-m-d", $timestamp2);

        $newDataCol = ", company_name, customer_name, alt_email, mobile_number, alt_mobile, whatsapp_alert_no, address, state, status, keywords, words, not_used_keywords, all_filters, filter_city, filter_state, filter_tender_value, filter_agency, filter_department, filter_type, start_date, duration, expired_date";
        $newDataVal = ", '$company_name', '$customer_name', '$alt_email', '$mobile_number', '$alt_mobile', '$whatsapp_alert_no', '$address', '$state', '$status', '$keywords', '$words', '$not_used_keywords', '$all_filters', '$filter_city', '$filter_state', '$filter_tender_value', '$filter_agency', '$filter_department', '$filter_type', '$start_date', '$duration', '$expired_date'";
    } else {
        $newDataCol = "";
        $newDataVal = "";
    }

    if ($fetch < 1) {
        $q = "INSERT INTO users(users_name, users_email ,users_password, user_role, user_unique_id" . $newDataCol . ") VALUES ('$username', '$email', '$pass', '$user_role', '$unique_code'" . $newDataVal . ")";
        $sql = mysqli_query($con, $q);
        if ($sql) {
            $_SESSION['success'] = 'Registration successfully.';
        } else {
            $_SESSION['error'] = 'Something went wrong.';
        }
    } else {
        $_SESSION['error'] = 'Username or Email already exists.';
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
    /* .hidden_fields {
        display: none;
    } */
    .col-md-6:has(label#all_filters\[\]-error)>label[for=all_filters] {
        color: red;
    }

    label#all_filters\[\]-error {
        display: none !important;
    }

    .choices__list--dropdown .choices__item--choice p {
        margin-bottom: 0;
        font-weight: bold;
    }

    .choices__list--dropdown .choices__item:has(p) {
        height: 200px;
        overflow-y: scroll;
    }
</style>
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Register</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <!-- <div class="card-header">
                <h4 class="card-title mb-0">Change Password</h4>
            </div> -->
            <form action="" method="post" id="register" enctype="multipart/form-data">
                <div class="card-body">
                    <div class="row gy-4">
                        <!-- <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="user_role" class="form-label">User Role : <span class="text-danger">*</span></label>
                                <select class="form-select" name="user_role" id="user_role">
                                    <option value="">Select User Role </option>
                                    <option value="admin">Admin</option>
                                    <option value="employee">Employee</option>
                                    <option value="user">User</option>
                                </select>
                            </div>
                        </div> -->
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="username" class="form-label">Username : <span class="text-danger">*</span></label>
                                <input type="text" name="username" placeholder="Enter Username " class="form-control" id="username">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email : <span class="text-danger">*</span></label>
                                <input type="email" name="email" placeholder="Enter Email " class="form-control" id="email">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="pass" class="form-label">Password : <span class="text-danger">*</span></label>
                                <input type="password" name="pass" placeholder="Enter Password " class="form-control" id="pass">
                            </div>
                        </div>

                        <div class="col-xxl-12 col-md-12 hidden_fields">
                            <div class="col-md-6">
                                <label for="company_name" class="form-label">Company Name : <span class="text-danger">*</span></label>
                                <input type="text" name="company_name" placeholder="Enter Company Name " class="form-control" id="company_name">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12 hidden_fields">
                            <div class="col-md-6">
                                <label for="customer_name" class="form-label">Customer Name : <span class="text-danger">*</span></label>
                                <input type="text" name="customer_name" placeholder="Enter Customer Name " class="form-control" id="customer_name">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12 hidden_fields">
                            <div class="col-md-6">
                                <label for="alt_email" class="form-label">Alternate Email : </label>
                                <input type="text" name="alt_email" placeholder="Enter Alternate Email " class="form-control" id="alt_email">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12 hidden_fields">
                            <div class="col-md-6">
                                <label for="mobile_number" class="form-label">Mobile Number : <span class="text-danger">*</span></label>
                                <input type="text" name="mobile_number" placeholder="Enter Mobile Number " class="form-control" id="mobile_number">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12 hidden_fields">
                            <div class="col-md-6">
                                <label for="alt_mobile" class="form-label">Alternate Mobile : </label>
                                <input type="text" name="alt_mobile" placeholder="Enter Alternate Mobile " class="form-control" id="alt_mobile">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12 hidden_fields">
                            <div class="col-md-6">
                                <label for="whatsapp_alert_no" class="form-label">Whatsapp Alert Number : <span class="text-danger">*</span></label>
                                <input type="text" name="whatsapp_alert_no" placeholder="Enter Whatsapp Alert Number " class="form-control" id="whatsapp_alert_no">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12 hidden_fields">
                            <div class="col-md-6">
                                <label for="address" class="form-label">Address : <span class="text-danger">*</span></label>
                                <textarea name="address" rows="3" placeholder="Enter Address " class="form-control" id="address"></textarea>
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12 hidden_fields">
                            <div class="col-md-6">
                                <label for="state" class="form-label">State : <span class="text-danger">*</span></label>
                                <select id="state" name="state" class="form-select">
                                    <option value="">Select State</option>
                                    <option value="Andhra Pradesh">Andhra Pradesh</option>
                                    <option value="Arunachal Pradesh">Arunachal Pradesh</option>
                                    <option value="Assam">Assam</option>
                                    <option value="Bihar">Bihar</option>
                                    <option value="Chhattisgarh">Chhattisgarh</option>
                                    <option value="Goa">Goa</option>
                                    <option value="Gujarat">Gujarat</option>
                                    <option value="Haryana">Haryana</option>
                                    <option value="Himachal Pradesh">Himachal Pradesh</option>
                                    <option value="Jharkhand">Jharkhand</option>
                                    <option value="Karnataka">Karnataka</option>
                                    <option value="Kerala">Kerala</option>
                                    <option value="Madhya Pradesh">Madhya Pradesh</option>
                                    <option value="Maharashtra">Maharashtra</option>
                                    <option value="Manipur">Manipur</option>
                                    <option value="Meghalaya">Meghalaya</option>
                                    <option value="Mizoram">Mizoram</option>
                                    <option value="Nagaland">Nagaland</option>
                                    <option value="Odisha">Odisha</option>
                                    <option value="Punjab">Punjab</option>
                                    <option value="Rajasthan">Rajasthan</option>
                                    <option value="Sikkim">Sikkim</option>
                                    <option value="Tamil Nadu">Tamil Nadu</option>
                                    <option value="Telangana">Telangana</option>
                                    <option value="Tripura">Tripura</option>
                                    <option value="Uttar Pradesh">Uttar Pradesh</option>
                                    <option value="Uttarakhand">Uttarakhand</option>
                                    <option value="West Bengal">West Bengal</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="start_date" class="form-label">Start Date : <span class="text-danger">*</span></label>
                                <input type="text" name="start_date" class="form-control flatpickr-input" data-provider="flatpickr" data-date-format="M d, Y" data-default-date="" readonly="readonly" id="start_date">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12 hidden_fields">
                            <div class="col-md-6">
                                <label for="duration" class="form-label">Duration : <span class="text-danger">*</span></label>
                                <input type="number" name="duration" placeholder="Enter Duration " class="form-control" id="duration">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="expired_date" class="form-label">Expired Date : <span class="text-danger">*</span></label>
                                <input type="text" name="expired_date" class="form-control flatpickr-input" data-provider="flatpickr" data-date-format="M d, Y" data-default-date="" readonly="readonly" id="expired_date">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12 hidden_fields">
                            <div class="col-md-6">
                                <label for="customer_status" class="form-label">Customer Status : <span class="text-danger">*</span></label>
                                <select id="customer_status" name="status" class="form-select">
                                    <option value="">Select Status</option>
                                    <option value="Active">Active</option>
                                    <option value="Expired">Expired</option>
                                    <option value="Renew">Renew</option>
                                    <option value="Upgrade">Upgrade</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12 hidden_fields">
                            <div class="col-md-6">
                                <label for="keywords" class="form-label">Keywords : <span class="text-danger">*</span></label>
                                <input type="text" name="keywords" placeholder="Enter Keywords " class="form-control" id="keywords">
                                <!-- <input type="text" name="keywords" placeholder="Enter Keywords " data-choices data-choices-text-unique-true data-choices-removeItem class="form-control" id="keywords"> -->
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12 hidden_fields">
                            <div class="col-md-6">
                                <label for="words" class="form-label">Words : <span class="text-danger">*</span></label>
                                <input type="text" name="words" data-choices data-choices-text-unique-true data-choices-removeItem placeholder="Enter Words " class="form-control" id="words">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12 hidden_fields">
                            <div class="col-md-6">
                                <label for="not_used_keywords" class="form-label">Not Used Keywords: <span class="text-danger">*</span></label>
                                <input type="text" name="not_used_keywords" data-choices data-choices-text-unique-true data-choices-removeItem placeholder="Enter Not Used  Keywords " class="form-control" id="not_used_keywords">
                            </div>
                        </div>

                        <div class="col-xxl-12 col-md-12 hidden_fields">
                            <div class="col-md-6">
                                <label for="filter_city" class="form-label">Filter City : <span class="text-danger">*</span></label>
                                <input type="text" name="filter_city" placeholder=" " data-choices data-choices-text-unique-true data-choices-removeItem class="form-control" id="filter_city">
                            </div>
                        </div>

                        <div class="col-xxl-12 col-md-12 hidden_fields">
                            <div class="col-md-6">
                                <label for="filter_state" class="form-label">Filter State : <span class="text-danger">*</span></label>
                                <input type="text" name="filter_state" placeholder=" " data-choices data-choices-text-unique-true data-choices-removeItem class="form-control" id="filter_state">
                            </div>
                        </div>

                        <div class="col-xxl-12 col-md-12 hidden_fields">
                            <div class="col-md-6">
                                <label for="filter_tender_value" class="form-label">Filter Tender Value : <span class="text-danger">*</span></label>
                                <input type="text" name="filter_tender_value" placeholder=" " class="form-control" id="filter_tender_value">
                            </div>
                        </div>

                        <div class="col-xxl-12 col-md-12 hidden_fields">
                            <div class="col-md-6">
                                <label for="filter_agency" class="form-label">Filter Agency : <span class="text-danger">*</span></label>
                                <input type="text" name="filter_agency" placeholder=" " data-choices data-choices-text-unique-true data-choices-removeItem class="form-control" id="filter_agency">
                            </div>
                        </div>

                        <div class="col-xxl-12 col-md-12 hidden_fields">
                            <div class="col-md-6">
                                <label for="filter_department" class="form-label">Filter Department Type : <span class="text-danger">*</span></label>
                                <input type="text" name="filter_department" placeholder=" " data-choices data-choices-text-unique-true data-choices-removeItem class="form-control" id="filter_department">
                            </div>
                        </div>

                        <div class="col-xxl-12 col-md-12 hidden_fields">
                            <div class="col-md-6">
                                <label for="filter_type" class="form-label">Filter Type : <span class="text-danger">*</span></label>
                                <input type="text" name="filter_type" placeholder=" " data-choices data-choices-text-unique-true data-choices-removeItem class="form-control" id="filter_type">
                            </div>
                        </div>

                        <div class="col-xxl-12 col-md-12 hidden_fields">
                            <div class="col-md-6">
                                <label for="all_filters" class="form-label w-100 mb-2">All filters: <span class="text-danger">*</span></label>

                                <div class="w-100">
                                    <input class="form-check-input" type="checkbox" value="ref_no" name="all_filters[]" id="formCheck1">
                                    <label for="formCheck1" class="form-check-label">Ref No </label>
                                </div>

                                <div class="w-100">
                                    <input class="form-check-input" type="checkbox" value="keywords" name="all_filters[]" id="formCheck3">
                                    <label for="formCheck3" class="form-check-label">keywords </label>
                                </div>

                                <div class="w-100">
                                    <input class="form-check-input" type="checkbox" value="state" name="all_filters[]" id="formCheck2">
                                    <label for="formCheck2" class="form-check-label">State </label>
                                </div>

                                <div class="w-100">
                                    <input class="form-check-input" type="checkbox" value="city" name="all_filters[]" id="formCheck4">
                                    <label for="formCheck4" class="form-check-label">City </label>
                                </div>

                                <div class="w-100">
                                    <input class="form-check-input" type="checkbox" value="agency_department" name="all_filters[]" id="formCheck6">
                                    <label for="formCheck6" class="form-check-label">Agency </label>
                                </div>

                                <div class="w-100">
                                    <input class="form-check-input" type="checkbox" value="tender_id" name="all_filters[]" id="formCheck7">
                                    <label for="formCheck7" class="form-check-label">Tender ID </label>
                                </div>

                                <!-- <div class="w-100">
                                    <input class="form-check-input" type="checkbox" value="publish_date" name="all_filters[]" id="formCheck9">
                                    <label for="formCheck9" class="form-check-label">Publish Date </label>
                                </div> -->

                                <div class="w-100">
                                    <input class="form-check-input" type="checkbox" value="due_date" name="all_filters[]" id="formCheck8">
                                    <label for="formCheck8" class="form-check-label">Due Date </label>
                                </div>

                                <div class="w-100">
                                    <input class="form-check-input" type="checkbox" value="tender_value" name="all_filters[]" id="formCheck10">
                                    <label for="formCheck10" class="form-check-label">Tender Value </label>
                                </div>

                                <div class="w-100">
                                    <input class="form-check-input" type="checkbox" value="tender_department" name="all_filters[]" id="formCheck11">
                                    <label for="formCheck11" class="form-check-label">Department Type</label>
                                </div>

                                <div class="w-100">
                                    <input class="form-check-input" type="checkbox" value="tender_type" name="all_filters[]" id="formCheck12">
                                    <label for="formCheck12" class="form-check-label">Type </label>
                                </div>
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
    $('#user_role').change(function() {
        if ($(this).val() == "user") {
            $('.hidden_fields').css("display", "block");
        } else {
            $('.hidden_fields').css("display", "none");
        }
    });
</script>

<script>
    $("#start_date").change(function() {
        selectedDate = $(this).val();
        durationMonth = $("#duration").val();
        if (!durationMonth) {
            durationMonth = 0;
        }
        // console.log(selectedDate + " " + durationMonth);
        var currentDate = new Date(selectedDate);
        currentDate.setMonth(currentDate.getMonth() + parseInt(durationMonth));
        var formattedDate = getMonthShortForm(currentDate.getMonth()) + ' ' + currentDate.getDate() + ', ' + currentDate.getFullYear();
        // console.log("Selected Date + 1 Month: " + formattedDate);
        const fp = flatpickr("#expired_date", {
            dateFormat: "M d, Y",
        });
        fp.setDate(new Date(formattedDate));
    });
    $("#duration").keyup(function() {
        selectedDate = $("#start_date").val();
        durationMonth = $(this).val();
        if (!durationMonth) {
            durationMonth = 0;
        }
        if (selectedDate) {
            // console.log(selectedDate + " " + durationMonth);
            var currentDate = new Date(selectedDate);
            currentDate.setMonth(currentDate.getMonth() + parseInt(durationMonth));
            var formattedDate = getMonthShortForm(currentDate.getMonth()) + ' ' + currentDate.getDate() + ', ' + currentDate.getFullYear();
            // console.log("Selected Date + 1 Month: " + formattedDate);
            const fp = flatpickr("#expired_date", {
                dateFormat: "M d, Y",
            });
            fp.setDate(new Date(formattedDate));
        }
    });

    function getMonthShortForm(monthIndex) {
        var monthsShortForm = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
        return monthsShortForm[monthIndex];
    }
</script>

<script>
    $("input.choices__input").keyup(function(e) {
        // var min_width = parseInt($(this).css("min-width"));
        // var width = parseInt($(this).css("width"));
        var curr_val = $(this).val();
        $(this).css("min-width", (curr_val.length + 40) + "px");
        // $(this).css("width", (width + 1) + "px");

        var curr_ele = $(this);
        var curr_ajax_val = $(this).parent().find('input').attr('id');
        // console.log(curr_ajax_val);
        $.ajax({
            method: "POST",
            url: "add.php",
            data: {
                get_choices_suggestions: 'true',
                curr_val: curr_val,
                curr_ajax_val: curr_ajax_val
            },
            success: function(data) {
                if (data) {
                    $(curr_ele).parent().parent().find('.choices__list--dropdown .choices__item--choice p').remove();
                    $(curr_ele).parent().parent().find('.choices__list--dropdown .choices__item--choice').append(data);
                }
            }
        });
    });
    $(document).on('click', '.choices__list--dropdown .choices__item--choice p', function(e) {
        var curr_val = $(this).text();
        // console.log(curr_val.length);
        $(this).parent().parent().parent().find('input.choices__input.choices__input--cloned').val(curr_val);
        $(this).parent().parent().parent().find('input.choices__input.choices__input--cloned').css("width", (curr_val.length * 12) + "px");
        $(this).parent().parent().removeClass('is-active');
        $(this).parent().parent().parent().attr('aria-expanded', 'false');
        $(this).parent().parent().parent().removeClass('is-focused');
        $(this).parent().parent().parent().removeClass('is-open');
    });
</script>