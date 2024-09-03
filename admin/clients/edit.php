<?php

include "../includes/authentication.php";
?>
<?php $pages = 'clients'; ?>
<?php
if (isset($_POST['get_choices_suggestions']) and !empty($_POST['get_choices_suggestions'])) {
    // $suggestions = $_POST['get_choices_suggestions'];
    $curr_val = $_POST['curr_val'];
    $curr_ajax_val = $_POST['curr_ajax_val'];
    // echo $curr_val . " " . $curr_ajax_val;

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
if (isset($_GET['id']) && isset($_GET['unique_code'])) {
    if (empty($_GET['id']) && empty($_GET['unique_code'])) {
        echo "<script>
            window.location.href='" . ADMIN_URL . "clients';
            </script>";
    }
}
?>
<?php
if (isset($_POST['submit'])) {
    $payment_arr = json_decode($_POST['payment_arr']);
    $plan_arr = json_decode($_POST['plan_arr']);
    //echo'<pre>';print_r($plan_arr);exit;
    $username = $_POST['username'];
    $email = $_POST['email'];
    $user_id = $_POST['user_id'];
    $pass = md5($_POST['password']);
    $user_role = $_POST['user_role'];
    $unique_code = $_POST['unique_code'];
    $fetch = mysqli_num_rows(mysqli_query($con, "SELECT * FROM users WHERE  (users_name='$username' OR users_email='$email') and user_id!='$user_id'"));
    if ($fetch < 1) {
        if (!empty($_POST['password']))
            $passw = ", users_password='$pass'";
        else
            $passw = "";

        $q = "UPDATE `users` SET users_name='$username', users_email='$email', user_role='$user_role' $passw where user_id='$user_id' and user_unique_id='$unique_code'";
        $sql = mysqli_query($con, $q);
        //save payment details
        mysqli_query($con, "DELETE FROM `client_payment_details` where user_id={$_GET['id']}");
        //save payment details
        if(!empty($payment_arr) && count($payment_arr)):
            foreach($payment_arr as $key => $val):
                if(!empty($val->payment_date)):
                    $timestamp2 = strtotime($val->payment_date);
                    $payment_date = date("Y-m-d", $timestamp2);
                    $payment_amount = (isset($val->payment_amount) && !empty($val->payment_amount)) ? $val->payment_amount : '';
                    $payment_type = (isset($val->payment_type) && !empty($val->payment_type)) ? $val->payment_type : '';
                    $payment_notes = (isset($val->payment_notes) && !empty($val->payment_notes)) ? $val->payment_notes : '';
                    $q1 = "INSERT INTO client_payment_details(user_id, payment_date ,payment_type, payment_amount, payment_notes) VALUES ($user_id, '$payment_date', '$payment_type', '$payment_amount', '$payment_notes')";
                    $sql1 = mysqli_query($con, $q1);
                   
                endif;
                
            endforeach;
        endif;

        //save plan details
        mysqli_query($con, "DELETE FROM `client_plan_details` where user_id={$_GET['id']}");
        //save plan details
        if(!empty($plan_arr) && count($plan_arr)):
            foreach($plan_arr as $key => $val):
                if(!empty($val->completion_date)):
                    $timestamp2 = strtotime($val->completion_date);
                    $completion_date = date("Y-m-d", $timestamp2);
                    $comp_count = (isset($val->comp_count) && !empty($val->comp_count)) ? $val->comp_count : '';
                    $completation_type = (isset($val->completation_type) && !empty($val->completation_type)) ? $val->completation_type : '';
                    $comp_notes = (isset($val->comp_notes) && !empty($val->comp_notes)) ? $val->comp_notes : '';
                    $q = "INSERT INTO client_plan_details(user_id, completion_date ,completation_type, comp_count, comp_notes) VALUES ($user_id, '$completion_date', '$completation_type', '$comp_count', '$comp_notes')";
                    $sql = mysqli_query($con, $q);
                endif;
                
            endforeach;
        endif;
        if ($sql) {
            if (isset($_POST['company_name'])) {
                $company_name = mysqli_real_escape_string($con, $_POST['company_name']);
                $customer_name = $_POST['customer_name'];
                $alt_email = $_POST['alt_email'];
                $mobile_number = $_POST['mobile_number'];
                $alt_mobile = $_POST['alt_mobile'];
                $whatsapp_alert_no = $_POST['whatsapp_alert_no'];
                $custom_care_number = $_POST['custom_care_number'];
                $tech_person_name = $_POST['tech_person_name'];
                $tech_person_number = $_POST['tech_person_number'];
                $address = mysqli_real_escape_string($con, $_POST['address']);
                $state = $_POST['state'];
                $status = $_POST['status'];
                $email_ids = $_POST['email_ids'];
                $mail_type = $_POST['mail_type'];
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

                $q1 = "UPDATE `users` SET company_name='$company_name', customer_name='$customer_name', alt_email='$alt_email', mobile_number='$mobile_number', alt_mobile='$alt_mobile', whatsapp_alert_no='$whatsapp_alert_no', `address`='$address', `state`='$state', `status`='$status', keywords='$keywords', words='$words', not_used_keywords='$not_used_keywords', all_filters='$all_filters', filter_city='$filter_city', filter_state='$filter_state', filter_tender_value='$filter_tender_value', filter_agency='$filter_agency', filter_department='$filter_department', filter_type='$filter_type', `start_date`='$start_date', duration='$duration', expired_date='$expired_date', custom_care_number='$custom_care_number', tech_person_name='$tech_person_name', tech_person_number='$tech_person_number', email_ids='$email_ids', mail_type='$mail_type' where user_id='$user_id' and user_unique_id='$unique_code'";
                $sql1 = mysqli_query($con, $q1);
            }
            $_SESSION['success'] = 'Updated successfully.';
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
                        // window.location.href='" . ADMIN_URL . "/clients';
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
            <?php $fetch_users = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM `users` where user_id={$_GET['id']} AND user_unique_id={$_GET['unique_code']}")); ?>
            <form action="" method="post" id="register" enctype="multipart/form-data">
                <input type="hidden" name="user_id" value=<?php echo $_GET['id']; ?>>
                <input type="hidden" name="unique_code" value=<?php echo $_GET['unique_code']; ?>>
                <input type="hidden" name="user_role" id="user_role" value="user">
                <div class="card-body">
                    <div class="row gy-4">
                        <!-- <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="user_role" class="form-label">User Role : <span class="text-danger">*</span></label>
                                <select class="form-select" name="user_role" id="user_role">
                                    <option value="">Select User Role </option>
                                    <option value="admin" <?php if ($fetch_users['user_role'] == "admin") {
                                                                echo "selected";
                                                            } ?>>Admin</option>
                                    <option value="employee" <?php if ($fetch_users['user_role'] == "employee") {
                                                                    echo "selected";
                                                                } ?>>Employee</option>
                                    <option value="user" <?php if ($fetch_users['user_role'] == "user") {
                                                                echo "selected";
                                                            } ?>>User</option>
                                </select>
                            </div>
                        </div> -->
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="username" class="form-label">Username : <span class="text-danger">*</span></label>
                                <input type="text" name="username" placeholder="Enter Username " value="<?php echo $fetch_users['users_name']; ?>" class="form-control" id="username">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email : <span class="text-danger">*</span></label>
                                <input type="email" name="email" placeholder="Enter Email " value="<?php echo $fetch_users['users_email']; ?>" class="form-control" id="email">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="email_ids" class="form-label">Email Ids: </label>
                                <textarea name="email_ids" row="3" placeholder="Enter Comma Seprated Emails " class="form-control" id="email_ids"><?php echo $fetch_users['email_ids']; ?></textarea>
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="row">
                                <label for="mail_type" class="form-label">Mail Type : </label>
                                <div class="col-lg-3">
                                    <input class="form-check-input" type="radio" value="list" name="mail_type" <?php echo ($fetch_users['mail_type'] == 'list') ? 'checked' : '' ?>>
                                    <label class="form-check-label">List</label>
                                </div>
                                <div class="col-lg-3">
                                    <input class="form-check-input" type="radio" value="link" name="mail_type" <?php echo ($fetch_users['mail_type'] == 'link') ? 'checked' : '' ?>>
                                    <label class="form-check-label">Link</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="password" class="form-label">Password : </label>
                                <input type="password" name="password" placeholder="Enter Password " class="form-control" id="password">
                            </div>
                        </div>

                        <div class="col-xxl-12 col-md-12 hidden_fields">
                            <div class="col-md-6">
                                <label for="company_name" class="form-label">Company Name : <span class="text-danger">*</span></label>
                                <input type="text" name="company_name" placeholder="Enter Company Name " value="<?php echo htmlspecialcode_generator($fetch_users['company_name']); ?>" class="form-control" id="company_name">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12 hidden_fields">
                            <div class="col-md-6">
                                <label for="customer_name" class="form-label">Customer Name : <span class="text-danger">*</span></label>
                                <input type="text" name="customer_name" placeholder="Enter Customer Name " value="<?php echo $fetch_users['customer_name']; ?>" class="form-control" id="customer_name">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12 hidden_fields">
                            <div class="col-md-6">
                                <label for="alt_email" class="form-label">Alternate Email : </label>
                                <input type="text" name="alt_email" placeholder="Enter Alternate Email " value="<?php echo $fetch_users['alt_email']; ?>" class="form-control" id="alt_email">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12 hidden_fields">
                            <div class="col-md-6">
                                <label for="mobile_number" class="form-label">Mobile Number : <span class="text-danger">*</span></label>
                                <input type="text" name="mobile_number" placeholder="Enter Mobile Number " value="<?php echo $fetch_users['mobile_number']; ?>" class="form-control" id="mobile_number">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12 hidden_fields">
                            <div class="col-md-6">
                                <label for="alt_mobile" class="form-label">Alternate Mobile : </label>
                                <input type="text" name="alt_mobile" placeholder="Enter Alternate Mobile " value="<?php echo $fetch_users['alt_mobile']; ?>" class="form-control" id="alt_mobile">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12 hidden_fields">
                            <div class="col-md-6">
                                <label for="whatsapp_alert_no" class="form-label">Whatsapp Alert Number : <span class="text-danger">*</span></label>
                                <input type="text" name="whatsapp_alert_no" placeholder="Enter Whatsapp Alert Number " value="<?php echo $fetch_users['whatsapp_alert_no']; ?>" class="form-control" id="whatsapp_alert_no">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12 hidden_fields">
                            <div class="col-md-6">
                                <label for="address" class="form-label">Address : <span class="text-danger">*</span></label>
                                <textarea name="address" rows="3" placeholder="Enter Address " class="form-control" id="address"><?php echo htmlspecialcode_generator($fetch_users['address']); ?></textarea>
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12 hidden_fields">
                            <div class="col-md-6">
                                <label for="state" class="form-label">State : <span class="text-danger">*</span></label>
                                <select id="state" name="state" class="form-select">
                                    <option value="">Select State</option>
                                    <option value="Andhra Pradesh" <?php if ($fetch_users['state'] == "Andhra Pradesh") {
                                                                        echo "selected";
                                                                    } ?>>Andhra Pradesh</option>
                                    <option value="Arunachal Pradesh" <?php if ($fetch_users['state'] == "Arunachal Pradesh") {
                                                                            echo "selected";
                                                                        } ?>>Arunachal Pradesh</option>
                                    <option value="Assam" <?php if ($fetch_users['state'] == "Assam") {
                                                                echo "selected";
                                                            } ?>>Assam</option>
                                    <option value="Bihar" <?php if ($fetch_users['state'] == "Bihar") {
                                                                echo "selected";
                                                            } ?>>Bihar</option>
                                    <option value="Chhattisgarh" <?php if ($fetch_users['state'] == "Chhattisgarh") {
                                                                        echo "selected";
                                                                    } ?>>Chhattisgarh</option>
                                    <option value="Goa" <?php if ($fetch_users['state'] == "Goa") {
                                                            echo "selected";
                                                        } ?>>Goa</option>
                                    <option value="Gujarat" <?php if ($fetch_users['state'] == "Gujarat") {
                                                                echo "selected";
                                                            } ?>>Gujarat</option>
                                    <option value="Haryana" <?php if ($fetch_users['state'] == "Haryana") {
                                                                echo "selected";
                                                            } ?>>Haryana</option>
                                    <option value="Himachal Pradesh" <?php if ($fetch_users['state'] == "Himachal Pradesh") {
                                                                            echo "selected";
                                                                        } ?>>Himachal Pradesh</option>
                                    <option value="Jharkhand" <?php if ($fetch_users['state'] == "Jharkhand") {
                                                                    echo "selected";
                                                                } ?>>Jharkhand</option>
                                    <option value="Karnataka" <?php if ($fetch_users['state'] == "Karnataka") {
                                                                    echo "selected";
                                                                } ?>>Karnataka</option>
                                    <option value="Kerala" <?php if ($fetch_users['state'] == "Kerala") {
                                                                echo "selected";
                                                            } ?>>Kerala</option>
                                    <option value="Madhya Pradesh" <?php if ($fetch_users['state'] == "Madhya Pradesh") {
                                                                        echo "selected";
                                                                    } ?>>Madhya Pradesh</option>
                                    <option value="Maharashtra" <?php if ($fetch_users['state'] == "Maharashtra") {
                                                                    echo "selected";
                                                                } ?>>Maharashtra</option>
                                    <option value="Manipur" <?php if ($fetch_users['state'] == "Manipur") {
                                                                echo "selected";
                                                            } ?>>Manipur</option>
                                    <option value="Meghalaya" <?php if ($fetch_users['state'] == "Meghalaya") {
                                                                    echo "selected";
                                                                } ?>>Meghalaya</option>
                                    <option value="Mizoram" <?php if ($fetch_users['state'] == "Mizoram") {
                                                                echo "selected";
                                                            } ?>>Mizoram</option>
                                    <option value="Nagaland" <?php if ($fetch_users['state'] == "Nagaland") {
                                                                    echo "selected";
                                                                } ?>>Nagaland</option>
                                    <option value="Odisha" <?php if ($fetch_users['state'] == "Odisha") {
                                                                echo "selected";
                                                            } ?>>Odisha</option>
                                    <option value="Punjab" <?php if ($fetch_users['state'] == "Punjab") {
                                                                echo "selected";
                                                            } ?>>Punjab</option>
                                    <option value="Rajasthan" <?php if ($fetch_users['state'] == "Rajasthan") {
                                                                    echo "selected";
                                                                } ?>>Rajasthan</option>
                                    <option value="Sikkim" <?php if ($fetch_users['state'] == "Sikkim") {
                                                                echo "selected";
                                                            } ?>>Sikkim</option>
                                    <option value="Tamil Nadu" <?php if ($fetch_users['state'] == "Tamil Nadu") {
                                                                    echo "selected";
                                                                } ?>>Tamil Nadu</option>
                                    <option value="Telangana" <?php if ($fetch_users['state'] == "Telangana") {
                                                                    echo "selected";
                                                                } ?>>Telangana</option>
                                    <option value="Tripura" <?php if ($fetch_users['state'] == "Tripura") {
                                                                echo "selected";
                                                            } ?>>Tripura</option>
                                    <option value="Uttar Pradesh" <?php if ($fetch_users['state'] == "Uttar Pradesh") {
                                                                        echo "selected";
                                                                    } ?>>Uttar Pradesh</option>
                                    <option value="Uttarakhand" <?php if ($fetch_users['state'] == "Uttarakhand") {
                                                                    echo "selected";
                                                                } ?>>Uttarakhand</option>
                                    <option value="West Bengal" <?php if ($fetch_users['state'] == "West Bengal") {
                                                                    echo "selected";
                                                                } ?>>West Bengal</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="start_date" class="form-label">Start Date : <span class="text-danger">*</span></label>
                                <input type="text" name="start_date" class="form-control flatpickr-input" data-provider="flatpickr" data-date-format="M d, Y" data-default-date="<?php echo date('M d, Y', strtotime($fetch_users['start_date'])); ?>" readonly="readonly" id="start_date">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12 hidden_fields">
                            <div class="col-md-6">
                                <label for="duration" class="form-label">Duration : <span class="text-danger">*</span></label>
                                <input type="number" name="duration" placeholder="Enter Duration " value="<?php echo $fetch_users['duration']; ?>" class="form-control" id="duration">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="expired_date" class="form-label">Expired Date : <span class="text-danger">*</span></label>
                                <input type="text" name="expired_date" class="form-control flatpickr-input" data-provider="flatpickr" data-date-format="M d, Y" data-default-date="<?php echo date('M d, Y', strtotime($fetch_users['expired_date'])); ?>" readonly="readonly" id="expired_date">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12 hidden_fields">
                            <div class="col-md-6">
                                <label for="customer_status" class="form-label">Customer Status : <span class="text-danger">*</span></label>
                                <select id="customer_status" name="status" class="form-select">
                                    <option value="">Select Status</option>
                                    <option value="Active" <?php if ($fetch_users['status'] == "Active") {
                                                                echo "selected";
                                                            } ?>>Active</option>
                                    <option value="Expired" <?php if ($fetch_users['status'] == "Expired") {
                                                                echo "selected";
                                                            } ?>>Expired</option>
                                    <option value="Renew" <?php if ($fetch_users['status'] == "Renew") {
                                                                echo "selected";
                                                            } ?>>Renew</option>
                                    <option value="Upgrade" <?php if ($fetch_users['status'] == "Upgrade") {
                                                                echo "selected";
                                                            } ?>>Upgrade</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12 hidden_fields">
                            <div class="col-md-6">
                                <label for="keywords" class="form-label">Keywords : <span class="text-danger">*</span></label>
                                <textarea name="keywords" rows="3" placeholder="Enter Keywords " class="form-control" id="keywords"><?php echo htmlspecialcode_generator($fetch_users['keywords']); ?></textarea>
                                <!-- <input type="text" name="keywords" placeholder="Enter Keywords " data-choices data-choices-text-unique-true data-choices-removeItem class="form-control" id="keywords" value="<?php echo htmlspecialcode_generator($fetch_users['keywords']); ?>"> -->
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12 hidden_fields">
                            <div class="col-md-6">
                                <label for="words" class="form-label">Words : <span class="text-danger">*</span></label>
                                <input type="text" name="words" data-choices data-choices-text-unique-true data-choices-removeItem placeholder="Enter Words " class="form-control" id="words" value="<?php echo htmlspecialcode_generator($fetch_users['words']); ?>">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12 hidden_fields">
                            <div class="col-md-6">
                                <label for="not_used_keywords" class="form-label">Not Used Keywords: <span class="text-danger">*</span></label>
                                <input type="text" name="not_used_keywords" data-choices data-choices-text-unique-true data-choices-removeItem placeholder="Enter Not Used  Keywords " class="form-control" id="not_used_keywords" value="<?php echo htmlspecialcode_generator($fetch_users['not_used_keywords']); ?>">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12 hidden_fields">
                            <div class="col-md-6">
                                <label for="custom_care_number" class="form-label">Custom Care Number : <span class="text-danger">*</span></label>
                                <input type="text" name="custom_care_number" placeholder="Enter Custom Care Number " class="form-control" id="custom_care_number"  value="<?php echo $fetch_users['custom_care_number']; ?>">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12 hidden_fields">
                            <div class="col-md-6">
                                <label for="tech_person_name" class="form-label">Technical Person Name : <span class="text-danger">*</span></label>
                                <input type="text" name="tech_person_name" placeholder="Enter Technical Person Name " class="form-control" id="tech_person_name"  value="<?php echo $fetch_users['tech_person_name']; ?>">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12 hidden_fields">
                            <div class="col-md-6">
                                <label for="tech_person_number" class="form-label">Technical Person Number : <span class="text-danger">*</span></label>
                                <input type="text" name="tech_person_number" placeholder="Enter Technical Person Number " class="form-control" id="tech_person_number"  value="<?php echo $fetch_users['tech_person_number']; ?>">
                            </div>
                        </div>
                        <?php $fetching_payments = mysqli_query($con, "SELECT * FROM client_payment_details where user_id={$_GET['id']} order by id asc");
                            $num_rows = mysqli_num_rows($fetching_payments); ?>
                                    
                            
                                <div class="col-xxl-12 col-md-12">
                                    <h2>Payment Details</h2>
                                    <input type="hidden" name="payment_arr" class="payment_arr">
                                    <div class="row add_payment_div">
                                        <div class="col-md-2">
                                            <div class="form-group">
                                            <label class="form-label">Payment Date</label>
                                            <input type="text" class="form-control flatpickr-input payment_date" data-provider="flatpickr" data-date-format="M d, Y" data-default-date=""  id="payment_date">
                                            </div>
                                        </div>
                                        <div class="col-md-2 department_div">
                                            <div class="form-group">
                                            <label class="form-label">Payment Type</label><br>
                                            <input type="radio" name="payment_type[0]" class="radio_btn payment_type phalf_payment" value="half_payment" checked>Half Payment
                                            <br><input type="radio" name="payment_type[0]" class="radio_btn payment_type pfull_payment" value="full_payment">Full Payment
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                            <label class="form-label">Amount</label>
                                            <input type="text" class="form-control payment_amount" id="payment_amount">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                            <label class="form-label">Notes</label>
                                            <textarea class="form-control payment_notes" id="payment_notes"></textarea>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-2 add_btn_div">
                                            <button type="button" class="btn btn-success add_more_pay_btn">Add</button>
                                            <button type="button" class="btn btn-success update_pay_btn" style="display:none">Update</button>
                                        </div>
                                    </div>

                                   
                                        <div class="row payment_table_data">
                                            <table class="payment_table">
                                            <?php $i = 0; ?>
                                            <?php if($num_rows > 0) { ?>
                                               <tr>
                                                    <th>Payment Date</th>
                                                    <th>Payment Type</th>
                                                    <th>Amount</th>
                                                    <th>Notes</th>
                                                    <th>Action</th>
                                                </tr>
                                                <?php } ?>
                                    <?php while ($row = mysqli_fetch_assoc($fetching_payments)) { ?>
                                                
                                                <?php 
                                                if($row['payment_type'] == 'half_payment'){
                                                    $ptype= 'Half';
                                                }
                                                else{
                                                    $ptype= 'Full';
                                                }
                                                ?>
                                                <tr class="create_td" data-ind="<?Php echo $i; ?>">
                                                    <td class="tsd_date_<?Php echo $i; ?>"><?php echo date('M d, Y', strtotime($row['payment_date'])); ?></td>
                                                    <td class="tsd_ptype_<?Php echo $i; ?>"><?Php echo $ptype; ?></td>
                                                    <td class="tsd_amount_<?Php echo $i; ?>"><?php echo $row['payment_amount']; ?></td>
                                                    <td class="tsd_notes_<?Php echo $i; ?>"><?php echo $row['payment_notes']; ?></td>
                                                    <td>
                                                        <a href="javascript:void(0);" class="remove_btn" data-indexid="<?Php echo $i; ?>"><i class="ri-delete-bin-5-fill remove"></i></a> 
                                                        <a href="javascript:void(0);" class="edit_btn" data-indexid="<?Php echo $i; ?>"><i class="ri-pencil-fill"></i></a>
                                                    </td>
                                                </tr>
                                                <?php  $i++; } ?>
                                            </table>
                                        </div>
                                  
                                </div>


                                <?php $fetching_plans = mysqli_query($con, "SELECT * FROM client_plan_details where user_id={$_GET['id']} order by id asc");
                                $num_rowsq = mysqli_num_rows($fetching_plans); ?>
                                        
                                
                                    <div class="col-xxl-12 col-md-12">
                                        <h2>Plan Details</h2>
                                        <input type="hidden" name="plan_arr" class="plan_arr">
                                        <div class="row add_plan_div">
                                    <input type="hidden" name="plan_arr" class="plan_arr">
                                        <div class="col-md-2">
                                            <div class="form-group">
                                            <label class="form-label">Completion Date</label>
                                            <input type="text" class="form-control flatpickr-input completion_date" data-provider="flatpickr" data-date-format="M d, Y" data-default-date=""  id="completion_date">
                                            </div>
                                        </div>
                                        <div class="col-md-3 completion_div">
                                            <div class="form-group">
                                            <label class="form-label">Completation Type</label><br>
                                            <input type="radio" name="completation_type[0]" class="radio_btn completation_type pe_bidding" value="e_bidding" checked>Radio - E-tender bidding
                                            <br><input type="radio" name="completation_type[0]" class="radio_btn completation_type ps_uploading" value="s_uploading">Product / Service Uploading
                                            <br><input type="radio" name="completation_type[0]" class="radio_btn completation_type pgem_bidding" value="gem_bidding">Gem Tender Bidding
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                            <label class="form-label">Count</label>
                                            <select id="comp_count" class="comp_count form-control">
                                                <option value="0">Select Count</option>
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="3">4</option>
                                                <option value="5">5</option>
                                                <option value="6">6</option>
                                                <option value="7">7</option>
                                                <option value="8">8</option>
                                                <option value="9">9</option>
                                                <option value="10">10</option>
                                                <option value="11">11</option>
                                                <option value="12">12</option>
                                                <option value="13">13</option>
                                                <option value="14">14</option>
                                                <option value="15">15</option>
                                                <option value="16">16</option>
                                                <option value="17">17</option>
                                                <option value="18">18</option>
                                                <option value="19">19</option>
                                                <option value="20">20</option>
                                            </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                            <label class="form-label">Notes</label>
                                            <textarea class="form-control comp_notes" id="comp_notes"></textarea>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-2 add_btn_div">
                                            <button type="button" class="btn btn-success add_more_plan_btn">Add</button>
                                            <button type="button" class="btn btn-success update_plan_btn" style="display:none">Update</button>
                                        </div>
                                    </div>
                                

                                   
                                        <div class="row plan_table_data">
                                            <table class="plan_table">
                                            <?php $is = 0; ?>
                                            <?php if($num_rowsq > 0) { ?>
                                               <tr>
                                                    <th>Completion Date</th>
                                                    <th>Completion Type</th>
                                                    <th>Count</th>
                                                    <th>Notes</th>
                                                    <th>Action</th>
                                                </tr>
                                                <?php } ?>
                                    <?php while ($row = mysqli_fetch_assoc($fetching_plans)) { ?>
                                                
                                                <?php 
                    
                                                if($row['completation_type'] == 'e_bidding'){
                                                    $ptype= 'Radio - E-tender bidding';
                                                }else if($row['completation_type'] == 's_uploading'){
                                                    $ptype= 'Product / Service Uploading';
                                                }else{
                                                    $ptype= 'Gem Tender Bidding';
                                                }
                                                ?>
                                                <tr class="pcreate_td p_<?php echo $row['completation_type']; ?>" data-ind="<?Php echo $is; ?>">
                                                    <td class="ptsd_date_<?Php echo $is; ?>"><?php echo date('M d, Y', strtotime($row['completion_date'])); ?></td>
                                                    <td class="ptsd_ptype_<?Php echo $is; ?>"><?Php echo $ptype; ?></td>
                                                    <td class="ptsd_count ptsd_amount_<?Php echo $is; ?>"><?php echo $row['comp_count']; ?></td>
                                                    <td class="ptsd_notes_<?Php echo $is; ?>"><?php echo $row['comp_notes']; ?></td>
                                                    <td>
                                                        <a href="javascript:void(0);" class="remove_plan_btn" data-indexid="<?Php echo $is; ?>"><i class="ri-delete-bin-5-fill remove"></i></a> 
                                                        <a href="javascript:void(0);" class="edit_plan_btn" data-indexid="<?Php echo $is; ?>"><i class="ri-pencil-fill"></i></a>
                                                    </td>
                                                </tr>
                                                <?php  $is++; } ?>
                                            </table>
                                        </div>

                                </div>
                                <div class="row col-md-6 plancount_table" style="display:none">
                                <table class="table table-bordered dt-responsive nowrap table-striped align-middle">
                                    <tr>
                                        <th>Type</th>
                                        <th>Total</th>
                                    </tr>
                                    <tr>
                                        <td>Radio - E-tender bidding</td>
                                        <td class="p_e_bidding_count">0</td>
                                    </tr>
                                    <tr>
                                        <td>Product / Service Uploading</td>
                                        <td class="p_s_uploading_count">0</td>
                                    </tr>
                                    <tr>
                                        <td>Gem Tender Bidding</td>
                                        <td class="p_gem_bidding_count">0</td>
                                    </tr>
                                </table>
                            </div>   
                        <div class="col-xxl-12 col-md-12 hidden_fields">
                            <div class="col-md-6">
                                <label for="filter_city" class="form-label">Filter City : <span class="text-danger">*</span></label>
                                <input type="text" name="filter_city" value="<?php echo htmlspecialcode_generator($fetch_users['filter_city']); ?>" placeholder=" " data-choices data-choices-text-unique-true data-choices-removeItem class="form-control" id="filter_city">
                            </div>
                        </div>

                        <div class="col-xxl-12 col-md-12 hidden_fields">
                            <div class="col-md-6">
                                <label for="filter_state" class="form-label">Filter State : <span class="text-danger">*</span></label>
                                <input type="text" name="filter_state" value="<?php echo htmlspecialcode_generator($fetch_users['filter_state']); ?>" placeholder=" " data-choices data-choices-text-unique-true data-choices-removeItem class="form-control" id="filter_state">
                            </div>
                        </div>

                        <div class="col-xxl-12 col-md-12 hidden_fields">
                            <div class="col-md-6">
                                <label for="filter_tender_value" class="form-label">Filter Tender Value : <span class="text-danger">*</span></label>
                                <input type="text" name="filter_tender_value" value="<?php echo htmlspecialcode_generator($fetch_users['filter_tender_value']); ?>" placeholder=" " class="form-control" id="filter_tender_value">
                            </div>
                        </div>

                        <div class="col-xxl-12 col-md-12 hidden_fields">
                            <div class="col-md-6">
                                <label for="filter_agency" class="form-label">Filter Agency : <span class="text-danger">*</span></label>
                                <input type="text" name="filter_agency" value="<?php echo htmlspecialcode_generator($fetch_users['filter_agency']); ?>" placeholder=" " data-choices data-choices-text-unique-true data-choices-removeItem class="form-control" id="filter_agency">
                            </div>
                        </div>

                        <div class="col-xxl-12 col-md-12 hidden_fields">
                            <div class="col-md-6">
                                <label for="filter_department" class="form-label">Filter Department Type : <span class="text-danger">*</span></label>
                                <input type="text" name="filter_department" value="<?php echo htmlspecialcode_generator($fetch_users['filter_department']); ?>" placeholder=" " data-choices data-choices-text-unique-true data-choices-removeItem class="form-control" id="filter_department">
                            </div>
                        </div>

                        <div class="col-xxl-12 col-md-12 hidden_fields">
                            <div class="col-md-6">
                                <label for="filter_type" class="form-label">Filter Type : <span class="text-danger">*</span></label>
                                <input type="text" name="filter_type" value="<?php echo htmlspecialcode_generator($fetch_users['filter_type']); ?>" placeholder=" " data-choices data-choices-text-unique-true data-choices-removeItem class="form-control" id="filter_type">
                            </div>
                        </div>

                        <div class="col-xxl-12 col-md-12 hidden_fields">
                            <div class="col-md-6">
                                <label for="all_filters" class="form-label w-100 mb-2">All filters: <span class="text-danger">*</span></label>
                                <?php $all_filters_array = explode(",", $fetch_users['all_filters']); ?>
                                <div class="w-100">
                                    <input class="form-check-input" type="checkbox" value="ref_no" <?php if (in_array("ref_no", $all_filters_array)) {
                                                                                                        echo "checked";
                                                                                                    } ?> name="all_filters[]" id="formCheck1">
                                    <label for="formCheck1" class="form-check-label">Ref No </label>
                                </div>

                                <div class="w-100">
                                    <input class="form-check-input" type="checkbox" value="keywords" <?php if (in_array("keywords", $all_filters_array)) {
                                                                                                            echo "checked";
                                                                                                        } ?> name="all_filters[]" id="formCheck3">
                                    <label for="formCheck3" class="form-check-label">keywords </label>
                                </div>

                                <div class="w-100">
                                    <input class="form-check-input" type="checkbox" value="state" <?php if (in_array("state", $all_filters_array)) {
                                                                                                        echo "checked";
                                                                                                    } ?> name="all_filters[]" id="formCheck2">
                                    <label for="formCheck2" class="form-check-label">State </label>
                                </div>

                                <div class="w-100">
                                    <input class="form-check-input" type="checkbox" value="city" <?php if (in_array("city", $all_filters_array)) {
                                                                                                        echo "checked";
                                                                                                    } ?> name="all_filters[]" id="formCheck4">
                                    <label for="formCheck4" class="form-check-label">City </label>
                                </div>

                                <div class="w-100">
                                    <input class="form-check-input" type="checkbox" value="agency_department" <?php if (in_array("agency_department", $all_filters_array)) {
                                                                                                                    echo "checked";
                                                                                                                } ?> name="all_filters[]" id="formCheck6">
                                    <label for="formCheck6" class="form-check-label">Agency </label>
                                </div>

                                <div class="w-100">
                                    <input class="form-check-input" type="checkbox" value="tender_id" <?php if (in_array("tender_id", $all_filters_array)) {
                                                                                                            echo "checked";
                                                                                                        } ?> name="all_filters[]" id="formCheck7">
                                    <label for="formCheck7" class="form-check-label">Tender ID </label>
                                </div>

                                <!-- <div class="w-100">
                                    <input class="form-check-input" type="checkbox" value="publish_date" <?php if (in_array("publish_date", $all_filters_array)) {
                                                                                                                echo "checked";
                                                                                                            } ?> name="all_filters[]" id="formCheck9">
                                    <label for="formCheck9" class="form-check-label">Publish Date </label>
                                </div> -->

                                <div class="w-100">
                                    <input class="form-check-input" type="checkbox" value="due_date" <?php if (in_array("due_date", $all_filters_array)) {
                                                                                                            echo "checked";
                                                                                                        } ?> name="all_filters[]" id="formCheck8">
                                    <label for="formCheck8" class="form-check-label">Due Date </label>
                                </div>

                                <div class="w-100">
                                    <input class="form-check-input" type="checkbox" value="tender_value" <?php if (in_array("tender_value", $all_filters_array)) {
                                                                                                                echo "checked";
                                                                                                            } ?> name="all_filters[]" id="formCheck10">
                                    <label for="formCheck10" class="form-check-label">Tender Value </label>
                                </div>

                                <div class="w-100">
                                    <input class="form-check-input" type="checkbox" value="tender_department" <?php if (in_array("tender_department", $all_filters_array)) {
                                                                                                                    echo "checked";
                                                                                                                } ?> name="all_filters[]" id="formCheck11">
                                    <label for="formCheck11" class="form-check-label">Department Type </label>
                                </div>

                                <div class="w-100">
                                    <input class="form-check-input" type="checkbox" value="tender_type" <?php if (in_array("tender_type", $all_filters_array)) {
                                                                                                            echo "checked";
                                                                                                        } ?> name="all_filters[]" id="formCheck12">
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
    if ($('#user_role').val() == "user") {
        $('.hidden_fields').css("display", "block");
    }
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
            url: "edit.php",
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

    // Add More Payment detail
    // Add More Payment detail

    function _get_total_count(clname){
        console.log(clname);
        var s= 0;
        $(clname).each(function() {
            var value = parseInt($(this).find('.ptsd_count').text());
            s = value + s;
        });
        return s;
    }
    var p_e_bidding_count = _get_total_count('.p_e_bidding');
        var p_s_uploading_count = _get_total_count('.p_s_uploading');
        var p_gem_bidding_count = _get_total_count('.p_gem_bidding');
        $('.p_e_bidding_count').text(p_e_bidding_count);
        $('.p_s_uploading_count').text(p_s_uploading_count);
        $('.p_gem_bidding_count').text(p_gem_bidding_count);
        $('.plancount_table').show();

    var myArray = [];
    $('.create_td').each(function(){
        var indid = $(this).attr('data-ind');
        var pa_date = $('.tsd_date_'+indid).html();
        var payment_type = $('.tsd_ptype_'+indid).html();
        var payment_notes = $('.tsd_notes_'+indid).html();
        var payment_amount = $('.tsd_amount_'+indid).html();
        
        if(payment_type == 'Half'){
            payment_type= 'half_payment';
        }else{
            payment_type= 'full_payment';
        }

        var pieces = {                              
           "payment_date" :pa_date,
           "payment_type" :payment_type,
           "payment_notes" :payment_notes,
           "payment_amount" :payment_amount
        };

       
        

        myArray.push(pieces);
        $('.payment_arr').val(JSON.stringify(myArray));
    })

    $('.add_more_pay_btn').click(function() {
    
        var total_tr_length = $('.payment_table_data tr').length;
        if(total_tr_length == 0){           
            $('.payment_table').append('<tr><th>Payment Date</th><th>Payment Type</th><th>Amount</th><th>Notes</th><th>Action</th></tr>');
        }
        var pa_date = $('.payment_date').val();
        var payment_type = $('.payment_type:checked').val();
        var payment_notes = $('.payment_notes').val();
        var payment_amount = $('.payment_amount').val();
        
        var pieces = {                              
           "payment_date" :pa_date,
           "payment_type" :payment_type,
           "payment_notes" :payment_notes,
           "payment_amount" :payment_amount
        };

        if(payment_type == 'half_payment'){
            payment_type= 'Half';
        }else{
            payment_type= 'Full';
        }
        

        myArray.push(pieces);

        var create_td_cnt = $('.create_td').length;
        //console.log(create_td_cnt);
        $('.payment_arr').val(JSON.stringify(myArray));
        
        $('.payment_table tr:last').after('<tr class="create_td"><td class="tsd_date_'+create_td_cnt+'">'+pa_date+'</td><td class="tsd_ptype_'+create_td_cnt+'">'+payment_type+'</td><td class="tsd_amount_'+create_td_cnt+'">'+payment_amount+'</td><td class="tsd_notes_'+create_td_cnt+'">'+payment_notes+'</td><td><a href="javascript:void(0);" class="remove_btn" data-indexid="'+create_td_cnt+'"><i class="ri-delete-bin-5-fill remove"></i></a> <a href="javascript:void(0);" class="edit_btn" data-indexid="'+create_td_cnt+'"><i class="ri-pencil-fill"></i></a></td></tr>');
        $('.payment_date').val('');
        $('.payment_notes').val('');
        $('.payment_amount').val('');

    });

    //remove row when click remove button
    $(document).on('click','.remove_btn',function(){
        var indexid = $(this).attr('data-indexid');
        myArray.splice(indexid, 1);
        $('.payment_arr').val(JSON.stringify(myArray));
        $(this).parent().parent().remove();
    });

    //get row data when click edit button
    $(document).on('click','.edit_btn',function(){
        var indexid = $(this).attr('data-indexid');
        var edit_date=  myArray[indexid].payment_date;
        var edit_amount=  myArray[indexid].payment_amount;
        var edit_ptype=  myArray[indexid].payment_type;
        var edit_notes=  myArray[indexid].payment_notes;
        $('.payment_date').val(edit_date);
        $('.payment_notes').val(edit_notes);
        $('.payment_amount').val(edit_amount);
        console.log(edit_ptype);
        if(edit_ptype == 'half_payment'){
            $('.phalf_payment').prop('checked',true);
            $('.pfull_payment').prop('checked',false);
        }else{
            $('.pfull_payment').prop('checked',true);
            $('.phalf_payment').prop('checked',false);
        }
        $('.update_pay_btn').show();
        $('.update_pay_btn').attr('data-inde',indexid);
        $('.add_more_pay_btn').hide();
    });

    $('.update_pay_btn').click(function() {
        var indexid = $(this).attr('data-inde');
        var edit_date = $('.payment_date').val();
        var edit_ptype = $('.payment_type:checked').val();
        var edit_notes = $('.payment_notes').val();
        var edit_amount = $('.payment_amount').val();

        myArray[indexid].payment_date = edit_date;
        myArray[indexid].payment_amount = edit_amount;
        myArray[indexid].payment_type = edit_ptype;
        myArray[indexid].payment_notes = edit_notes;

        $('.payment_arr').val(JSON.stringify(myArray));
        if(edit_ptype == 'half_payment'){
            edit_ptype= 'Half';
        }else{
            edit_ptype= 'Full';
        }

        $('.tsd_date_'+indexid).html(edit_date);
        $('.tsd_ptype_'+indexid).html(edit_ptype);
        $('.tsd_amount_'+indexid).html(edit_amount);
        $('.tsd_notes_'+indexid).html(edit_notes);
       
        $('.payment_date').val('');
        $('.payment_notes').val('');
        $('.payment_amount').val('');
        $('.add_more_pay_btn').show();
        $('.update_pay_btn').removeAttr('data-inde');
        $('.update_pay_btn').hide();
    });

    var pmyArray = [];

    $('.pcreate_td').each(function(){
        var indid = $(this).attr('data-ind');
        var pa_date = $('.ptsd_date_'+indid).html();
        var completation_type = $('.ptsd_ptype_'+indid).html();
        var comp_notes = $('.ptsd_notes_'+indid).html();
        var comp_count = $('.ptsd_amount_'+indid).html();

        if(completation_type == 'Radio - E-tender bidding'){
            completation_type= 'e_bidding';
        }else if(completation_type == 'Product / Service Uploading'){
            completation_type= 's_uploading';
        }else{
            completation_type= 'gem_bidding';
        }

        var pieces = {                              
           "completion_date" :pa_date,
           "completation_type" :completation_type,
           "comp_notes" :comp_notes,
           "comp_count" :comp_count
        };

        pmyArray.push(pieces);
        $('.plan_arr').val(JSON.stringify(pmyArray));
    })

    $('.add_more_plan_btn').click(function() {
        
        var total_ptr_length = $('.plan_table_data tr').length;
        if(total_ptr_length == 0){           
            $('.plan_table').append('<tr><th>Completion Date</th><th>Completion Type</th><th>Count</th><th>Notes</th><th>Action</th></tr>');
        }
        var pa_date = $('.completion_date').val();
        var completation_type = $('.completation_type:checked').val();
        var completation_types = $('.completation_type:checked').val();
        var comp_notes = $('.comp_notes').val();
        var comp_count = $('.comp_count').val();
        
        var pieces = {                              
           "completion_date" :pa_date,
           "completation_type" :completation_type,
           "comp_notes" :comp_notes,
           "comp_count" :comp_count
        };

        if(completation_type == 'e_bidding'){
            completation_type= 'Radio - E-tender bidding';
        }else if(completation_type == 's_uploading'){
            completation_type= 'Product / Service Uploading';
        }else{
            completation_type= 'Gem Tender Bidding';
        }
        

        pmyArray.push(pieces);

        var create_td_cnt = $('.pcreate_td').length;

        $('.plan_arr').val(JSON.stringify(pmyArray));
        
        $('.plan_table tr:last').after('<tr class="pcreate_td p_'+completation_types+'"><td class="ptsd_date_'+create_td_cnt+'">'+pa_date+'</td><td class="ptsd_ptype_'+create_td_cnt+'">'+completation_type+'</td><td class="ptsd_count ptsd_amount_'+create_td_cnt+'">'+comp_count+'</td><td class="ptsd_notes_'+create_td_cnt+'">'+comp_notes+'</td><td><a href="javascript:void(0);" class="remove_plan_btn" data-indexid="'+create_td_cnt+'"><i class="ri-delete-bin-5-fill remove"></i></a> <a href="javascript:void(0);" class="edit_plan_btn" data-indexid="'+create_td_cnt+'"><i class="ri-pencil-fill"></i></a></td></tr>');
        $('.completion_date').val('');
        $('.comp_count').val('0').trigger('change');
        $('.comp_notes').val('');
        var p_e_bidding_count = _get_total_count('.p_e_bidding');
        var p_s_uploading_count = _get_total_count('.p_s_uploading');
        var p_gem_bidding_count = _get_total_count('.p_gem_bidding');
        $('.p_e_bidding_count').text(p_e_bidding_count);
        $('.p_s_uploading_count').text(p_s_uploading_count);
        $('.p_gem_bidding_count').text(p_gem_bidding_count);
        $('.plancount_table').show();

    });

    //remove row when click remove button
    $(document).on('click','.remove_plan_btn',function(){
        var indexid = $(this).attr('data-indexid');
        pmyArray.splice(indexid, 1);
        $('.plan_arr').val(JSON.stringify(pmyArray));
        $(this).parent().parent().remove();
        var p_e_bidding_count = _get_total_count('.p_e_bidding');
        var p_s_uploading_count = _get_total_count('.p_s_uploading');
        var p_gem_bidding_count = _get_total_count('.p_gem_bidding');
        $('.p_e_bidding_count').text(p_e_bidding_count);
        $('.p_s_uploading_count').text(p_s_uploading_count);
        $('.p_gem_bidding_count').text(p_gem_bidding_count);
        $('.plancount_table').show();
    });

    //get row data when click edit button
    $(document).on('click','.edit_plan_btn',function(){
        var indexid = $(this).attr('data-indexid');
        var edit_cdate=  pmyArray[indexid].completion_date;
        var edit_count=  pmyArray[indexid].comp_count;
        var edit_ctype=  pmyArray[indexid].completation_type;
        var edit_cnotes=  pmyArray[indexid].comp_notes;
        $('.completion_date').val(edit_cdate);
        $('.comp_notes').val(edit_cnotes);
        $('.comp_count').val(edit_count);
        if(edit_ctype == 'e_bidding'){
            $('.pe_bidding').prop('checked',true);
            $('.ps_uploading').prop('checked',false);
            $('.pgem_bidding').prop('checked',false);
        }else if(edit_ctype == 's_uploading'){
            $('.pe_bidding').prop('checked',false);
            $('.ps_uploading').prop('checked',true);
            $('.pgem_bidding').prop('checked',false);
        }else{
            $('.pe_bidding').prop('checked',false);
            $('.ps_uploading').prop('checked',false);
            $('.pgem_bidding').prop('checked',true);
        }
        $('.update_plan_btn').show();
        $('.update_plan_btn').attr('data-inde',indexid);
        $('.add_more_plan_btn').hide();
    });

    $('.update_plan_btn').click(function() {
        var indexid = $(this).attr('data-inde');
        var edit_cdate = $('.completion_date').val();
        var edit_ctype = $('.completation_type:checked').val();
        var edit_cnotes = $('.comp_notes').val();
        var edit_count = $('.comp_count').val();

        pmyArray[indexid].completion_date = edit_cdate;
        pmyArray[indexid].comp_count = edit_count;
        pmyArray[indexid].completation_type = edit_ctype;
        pmyArray[indexid].comp_notes = edit_cnotes;

        $('.plan_arr').val(JSON.stringify(pmyArray));

        if(edit_ctype == 'e_bidding'){
            edit_ctype= 'Radio - E-tender bidding';
        }else if(edit_ctype == 's_uploading'){
            edit_ctype= 'Product / Service Uploading';
        }else{
            edit_ctype= 'Gem Tender Bidding';
        }
        

        $('.ptsd_date_'+indexid).html(edit_cdate);
        $('.ptsd_ptype_'+indexid).html(edit_ctype);
        $('.ptsd_amount_'+indexid).html(edit_count);
        $('.ptsd_notes_'+indexid).html(edit_cnotes);
       
        $('.completion_date').val('');
        $('.comp_count').val('');
        $('.comp_notes').val('');
        $('.update_plan_btn').removeAttr('data-inde');
        $('.update_plan_btn').hide();
        $('.add_more_plan_btn').show();

        var p_e_bidding_count = _get_total_count('.p_e_bidding');
        var p_s_uploading_count = _get_total_count('.p_s_uploading');
        var p_gem_bidding_count = _get_total_count('.p_gem_bidding');
        $('.p_e_bidding_count').text(p_e_bidding_count);
        $('.p_s_uploading_count').text(p_s_uploading_count);
        $('.p_gem_bidding_count').text(p_gem_bidding_count);
        $('.plancount_table').show();
    });
</script>