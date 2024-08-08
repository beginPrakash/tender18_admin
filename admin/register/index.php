<?php

include "../includes/authentication.php";
?>
<?php $pages = 'users'; ?>
<?php include '../includes/header.php'; ?>
<?php // include '../includes/connection.php';

?>
<?php
$users_per = _get_user_perby_role($_SESSION['user_id'],'users',$con);

if($_SESSION['role']!='admin' && $_SESSION['role']!='employee'){ 
    // echo "not admin ------>" . $_SESSION['role'];
    echo "<script>
            window.location.href='../index.php';
            </script>";
}elseif($_SESSION['role']=='employee' && $users_per!=1){ 
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
    $user_role = $_POST['user_role'];
    $string = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
    $files =  substr(str_shuffle($string), 0, 8);
    $unique_code = time() . $files;
    $fetch = mysqli_num_rows(mysqli_query($con, "SELECT * FROM users WHERE  users_name='$username' OR users_email='$email'"));

    if (isset($_POST['company_name'])) {
        $company_name = $_POST['company_name'];
        $customer_name = $_POST['customer_name'];
        $alt_email = $_POST['alt_email'];
        $mobile_number = $_POST['mobile_number'];
        $alt_mobile = $_POST['alt_mobile'];
        $whatsapp_alert_no = $_POST['whatsapp_alert_no'];
        $service_duration = $_POST['service_duration'];
        $address = $_POST['address'];
        $state = $_POST['state'];
        $status = $_POST['status'];
        $keywords = $_POST['keywords'];
        $words = $_POST['words'];
        $newDataCol = ", company_name, customer_name, alt_email, mobile_number, alt_mobile, whatsapp_alert_no, service_duration, address, state, status, keywords, words";
        $newDataVal = ", '$company_name', '$customer_name', '$alt_email', '$mobile_number', '$alt_mobile', '$whatsapp_alert_no', '$service_duration', '$address', '$state', '$status', '$keywords', '$words'";
    } else {
        $newDataCol = "";
        $newDataVal = "";
    }

    if ($fetch < 1) {
       
        $q = "INSERT INTO users(users_name, users_email ,users_password, user_role, user_unique_id" . $newDataCol . ") VALUES ('$username', '$email', '$pass', '$user_role', '$unique_code'" . $newDataVal . ")";
        $sql = mysqli_query($con, $q);
        $last_insert_id = mysqli_insert_id($con);
        //save user role permission
        if(!empty($_POST['user_permis']) && count($_POST['user_permis']) > 0):
            foreach($_POST['user_permis'] as $key => $val):  
                $per_que = "INSERT INTO user_permission(user_id, key_name ,key_value) VALUES ('$last_insert_id', $key, $val)";
                $per_sql = mysqli_query($con, $per_que);
            endforeach;
        endif;
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
                        window.location.href='" . ADMIN_URL . "/users';
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
    .hidden_fields {
        display: none;
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
                        <div class="col-xxl-12 col-md-12">
                            <div class="col-md-6">
                                <label for="user_role" class="form-label">User Role : <span class="text-danger">*</span></label>
                                <select class="form-select" name="user_role" id="user_role">
                                    <option value="">Select User Role </option>
                                    <option value="admin">Admin</option>
                                    <option value="employee">Employee</option>
                                    <!-- <option value="user">User</option> -->
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12 employee_perm_div d-none">
                            <label for="user_role" class="form-label">User Permission:</label>
                            <div class="row">
                                <div class="col-lg-3">
                                    <input class="form-check-input multi_check" type="checkbox" value="1" checked="" name="user_permis['dashboards']">
                                    <label class="form-check-label">Dashboards</label>
                                </div>
                                <div class="col-lg-3">
                                    <input class="form-check-input multi_check" type="checkbox" value="1" checked="" name="user_permis['pages']">
                                    <label class="form-check-label">Pages</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3">
                                    <input class="form-check-input multi_check" type="checkbox" value="1" checked="" name="user_permis['testimonials']">
                                    <label class="form-check-label">Testimonials</label>
                                </div>
                                <div class="col-lg-3">
                                    <input class="form-check-input multi_check" type="checkbox" value="1" checked="" name="user_permis['users']">
                                    <label class="form-check-label">Users</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3">
                                    <input class="form-check-input multi_check" type="checkbox" value="1" checked="" name="user_permis['clients']">
                                    <label class="form-check-label">Clients</label>
                                    </div>
                                <div class="col-lg-3">
                                    <input class="form-check-input multi_check" type="checkbox" value="1" checked="" name="user_permis['menus']">
                                    <label class="form-check-label">Menus</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3">
                                    <input class="form-check-input multi_check" type="checkbox" value="1" checked="" name="user_permis['tenders']">
                                    <label class="form-check-label">Tenders</label>    
                                </div>
                                <div class="col-lg-3">
                                    <input class="form-check-input multi_check" type="checkbox" value="1" checked="" name="user_permis['live_tenders']">
                                    <label class="form-check-label">Live Tenders</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3">
                                    <input class="form-check-input multi_check" type="checkbox" value="1" checked="" name="user_permis['archive_tenders']">
                                    <label class="form-check-label">Archive Tenders</label>
                                </div>
                                <div class="col-lg-3">
                                    <input class="form-check-input multi_check" type="checkbox" value="1" checked="" name="user_permis['free_quote_form']">
                                    <label class="form-check-label">Free Quote Form</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3">
                                    <input class="form-check-input multi_check" type="checkbox" value="1" checked="" name="user_permis['tender_inquiry']">
                                    <label class="form-check-label">Tender Inquiry</label>
                                </div>
                                <div class="col-lg-3">
                                    <input class="form-check-input multi_check" type="checkbox" value="1" checked="" name="user_permis['registration_form']">
                                    <label class="form-check-label"> Registration Form</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3">
                                    <input class="form-check-input multi_check" type="checkbox" value="1" checked="" name="user_permis['feedback_inquiry']">
                                    <label class="form-check-label">Feedback Inquiry</label>
                                </div>
                                <div class="col-lg-3">
                                    <input class="form-check-input multi_check" type="checkbox" value="1" checked="" name="user_permis['complain_inquiry']">
                                    <label class="form-check-label"> Complain Inquiry</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3">
                                    <input class="form-check-input multi_check" type="checkbox" value="1" checked="" name="user_permis['blogs']">
                                    <label class="form-check-label">Blogs</label>
                                </div>
                                <div class="col-lg-3">
                                    <input class="form-check-input multi_check" type="checkbox" value="1" checked="" name="user_permis['states']">
                                    <label class="form-check-label">States</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3">
                                    <input class="form-check-input multi_check" type="checkbox" value="1" checked="" name="user_permis['departments']">
                                    <label class="form-check-label">Departments</label>
                                </div>
                            </div>
                        </div>    
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
                                <label for="alt_email" class="form-label">Alternate Email : <span class="text-danger">*</span></label>
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
                                <label for="alt_mobile" class="form-label">Alternate Mobile : <span class="text-danger">*</span></label>
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
                                <label for="service_duration" class="form-label">Service Duration : <span class="text-danger">*</span></label>
                                <input type="text" name="service_duration" placeholder="Enter Service Duration " class="form-control" id="service_duration">
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
                                <input type="text" name="keywords" placeholder="Enter Keywords " data-choices data-choices-text-unique-true data-choices-removeItem class="form-control" id="keywords">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12 hidden_fields">
                            <div class="col-md-6">
                                <label for="words" class="form-label">Words : <span class="text-danger">*</span></label>
                                <input type="text" name="words" data-choices data-choices-text-unique-true data-choices-removeItem placeholder="Enter Words " class="form-control" id="words">
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

        if ($(this).val() == "employee") {
            $('.employee_perm_div').removeClass('d-none');
            $('.multi_check').prop('checked', true);
        } else {
            $('.employee_perm_div').addClass('d-none');
            $('.multi_check').prop('checked', false);
        }
    });
</script>