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
if (isset($_GET['id']) && isset($_GET['unique_code'])) {
    if (empty($_GET['id']) && empty($_GET['unique_code'])) {
        // mysqli_query($con, "DELETE FROM `users` where user_id={$_GET['id']} AND user_unique_id={$_GET['unique_code']}");
        echo "<script>
            window.location.href='" . ADMIN_URL . "users';
            </script>";
    }
}
?>
<?php
if (isset($_POST['submit'])) {
    //print_r($_POST);exit;
    $username = $_POST['username'];
    $email = $_POST['email'];
    $user_id = $_POST['user_id'];
    $pass = md5($_POST['password']);
    $user_role = $_POST['user_role'];
    $unique_code = $_POST['unique_code'];
    $fetch = mysqli_num_rows(mysqli_query($con, "SELECT * FROM users WHERE  (users_name='$username' OR users_email='$email') and user_id!='$user_id'"));
    if ($fetch < 1) {
        // $q = "INSERT INTO users(users_name, users_email ,users_password, user_role, user_unique_id) VALUES ('$username', '$email', '$pass', '$user_role', '$unique_code')";
        if (!empty($_POST['password']))
            $passw = ", users_password='$pass'";
        else
            $passw = "";
        $q = "UPDATE `users` SET users_name='$username', users_email='$email', user_role='$user_role' $passw where user_id='$user_id' and user_unique_id='$unique_code'";
        // var_dump($q);
        $sql = mysqli_query($con, $q);
        mysqli_query($con, "DELETE FROM `user_permission` where user_id={$_GET['id']}");
        if($user_role == 'employee'):
            if(!empty($_POST['user_permis']) && count($_POST['user_permis']) > 0):
                foreach($_POST['user_permis'] as $key => $val):  
                    $per_que = "INSERT INTO user_permission(user_id, key_name ,key_value) VALUES ('$user_id', $key, $val)";
                    $per_sql = mysqli_query($con, $per_que);
                endforeach;
            endif;
        endif;
        if ($sql) {
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
                $q1 = "UPDATE `users` SET company_name='$company_name', customer_name='$customer_name', alt_email='$alt_email', mobile_number='$mobile_number', alt_mobile='$alt_mobile', whatsapp_alert_no='$whatsapp_alert_no', service_duration='$service_duration', `address`='$address', `state`='$state', `status`='$status', keywords='$keywords', words='$words' where user_id='$user_id' and user_unique_id='$unique_code'";
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
                         window.location.href='" . ADMIN_URL . "/users/index.php';
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
            <?php $fetch_users = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM `users` where user_id={$_GET['id']} AND user_unique_id={$_GET['unique_code']}"));
            
            ?>
            <form action="" method="post" id="register" enctype="multipart/form-data">
                <input type="hidden" name="user_id" value=<?php echo $_GET['id']; ?>>
                <input type="hidden" name="unique_code" value=<?php echo $_GET['unique_code']; ?>>
                <div class="card-body">
                    <div class="row gy-4">
                        <div class="col-xxl-12 col-md-12">
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
                                    <!-- <option value="user" <?php if ($fetch_users['user_role'] == "user") {
                                                                    echo "selected";
                                                                } ?>>User</option> -->
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12 employee_perm_div d-none">
                            <label for="user_role" class="form-label">User Permission:</label>
                             
                            <div class="row">
                                <div class="col-lg-3">
                                    <?php $dashboards_per = _get_user_perby_role($_GET['id'],'dashboards',$con); ?>
                                    <input class="form-check-input multi_check" type="checkbox" value="1" <?php echo ($dashboards_per == 1) ? 'checked' : '';?> name="user_permis['dashboards']">
                                    <label class="form-check-label">Dashboards</label>
                                </div>
                                <div class="col-lg-3">
                                    <?php $pages_per = _get_user_perby_role($_GET['id'],'pages',$con); ?>
                                    <input class="form-check-input multi_check" type="checkbox" value="1" <?php echo ($pages_per == '1') ? 'checked' : '';?> name="user_permis['pages']">
                                    <label class="form-check-label">Pages</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3">
                                    <?php $testimonials_per = _get_user_perby_role($_GET['id'],'testimonials',$con); ?>
                                    <input class="form-check-input multi_check" type="checkbox" value="1" <?php echo ($testimonials_per == '1') ? 'checked' : '';?> name="user_permis['testimonials']">
                                    <label class="form-check-label">Testimonials</label>
                                </div>
                                <div class="col-lg-3">
                                    <?php $users_per = _get_user_perby_role($_GET['id'],'users',$con); ?>
                                    <input class="form-check-input multi_check" type="checkbox" value="1" <?php echo ($users_per == '1') ? 'checked' : '';?> name="user_permis['users']">
                                    <label class="form-check-label">Users</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3">
                                    <?php $clients_per = _get_user_perby_role($_GET['id'],'clients',$con); ?>
                                    <input class="form-check-input multi_check" type="checkbox" value="1" <?php echo ($clients_per == '1') ? 'checked' : '';?> name="user_permis['clients']">
                                    <label class="form-check-label">Clients</label>
                                    </div>
                                <div class="col-lg-3">
                                    <?php $menus_per = _get_user_perby_role($_GET['id'],'menus',$con); ?>
                                    <input class="form-check-input multi_check" type="checkbox" value="1" <?php echo ($menus_per == '1') ? 'checked' : '';?> name="user_permis['menus']">
                                    <label class="form-check-label">Menus</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3">
                                    <?php $tenders_per = _get_user_perby_role($_GET['id'],'tenders',$con); ?>
                                    <input class="form-check-input multi_check" type="checkbox" value="1" <?php echo ($tenders_per == '1') ? 'checked' : '';?> name="user_permis['tenders']">
                                    <label class="form-check-label">Tenders</label>    
                                </div>
                                <div class="col-lg-3">
                                    <?php $live_tenders_per = _get_user_perby_role($_GET['id'],'live_tenders',$con); ?>
                                    <input class="form-check-input multi_check" type="checkbox" value="1" <?php echo ($live_tenders_per == '1') ? 'checked' : '';?> name="user_permis['live_tenders']">
                                    <label class="form-check-label">Live Tenders</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3">
                                    <?php $archive_tenders_per = _get_user_perby_role($_GET['id'],'archive_tenders',$con); ?>
                                    <input class="form-check-input multi_check" type="checkbox" value="1" <?php echo ($archive_tenders_per == '1') ? 'checked' : '';?> name="user_permis['archive_tenders']">
                                    <label class="form-check-label">Archive Tenders</label>
                                </div>
                                <div class="col-lg-3">
                                    <?php $inquiry_per = _get_user_perby_role($_GET['id'],'inquiries',$con); ?>
                                    <input class="form-check-input multi_check" type="checkbox" value="1" <?php echo ($inquiry_per == '1') ? 'checked' : '';?> name="user_permis['inquiries']">
                                    <label class="form-check-label">Inquiries</label>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-lg-3">
                                    <?php $feedback_per = _get_user_perby_role($_GET['id'],'feedback_inquiry',$con); ?>
                                    <input class="form-check-input multi_check" type="checkbox" value="1" <?php echo ($feedback_per == '1') ? 'checked' : '';?> name="user_permis['feedback_inquiry']">
                                    <label class="form-check-label">Feedback Inquiry</label>
                                </div>
                                <div class="col-lg-3">
                                    <?php $complain_per = _get_user_perby_role($_GET['id'],'complain_inquiry',$con); ?>
                                    <input class="form-check-input multi_check" type="checkbox" value="1" <?php echo ($complain_per == '1') ? 'checked' : '';?> name="user_permis['complain_inquiry']">
                                    <label class="form-check-label"> Complain Inquiry</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3">
                                <?php $blog_per = _get_user_perby_role($_GET['id'],'blogs',$con); ?>
                                    <input class="form-check-input multi_check" type="checkbox" value="1" <?php echo ($blog_per == '1') ? 'checked' : '';?> name="user_permis['blogs']">
                                    <label class="form-check-label">Blogs</label>
                                </div>
                                <div class="col-lg-3">
                                    <?php $state_per = _get_user_perby_role($_GET['id'],'states',$con); ?>
                                    <input class="form-check-input multi_check" type="checkbox" value="1" <?php echo ($state_per == '1') ? 'checked' : '';?> name="user_permis['states']">
                                    <label class="form-check-label">States</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3">
                                <?php $dept_per = _get_user_perby_role($_GET['id'],'departments',$con); ?>
                                    <input class="form-check-input multi_check" type="checkbox" value="1" <?php echo ($dept_per == '1') ? 'checked' : '';?> name="user_permis['departments']">
                                    <label class="form-check-label">Departments</label>
                                </div>
                                <div class="col-lg-3">
                                <?php $keys_per = _get_user_perby_role($_GET['id'],'keywords',$con); ?>
                                    <input class="form-check-input multi_check" type="checkbox" value="1" <?php echo ($keys_per == '1') ? 'checked' : '';?> name="user_permis['keywords']">
                                    <label class="form-check-label">Keywords</label>
                                </div>
                            </div>
                            <div class="row">
                                
                                <div class="col-lg-3">
                                <?php $smtp_per = _get_user_perby_role($_GET['id'],'smtp_mgmt',$con); ?>
                                    <input class="form-check-input multi_check" type="checkbox" value="1" <?php echo ($smtp_per == '1') ? 'checked' : '';?> name="user_permis['smtp_mgmt']">
                                    <label class="form-check-label">SMTP Management</label>
                                </div>
                                <div class="col-lg-3">
                                <?php $metacon_per = _get_user_perby_role($_GET['id'],'meta_content',$con); ?>
                                    <input class="form-check-input multi_check" type="checkbox" value="1" <?php echo ($metacon_per == '1') ? 'checked' : '';?> name="user_permis['meta_content']">
                                    <label class="form-check-label">Meta Content</label>
                                </div>
                                
                            </div>
                            
                            
                        </div>  
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
                                <label for="password" class="form-label">Password : </label>
                                <input type="password" name="password" placeholder="Enter Password " class="form-control" id="password">
                            </div>
                        </div>

                        <div class="col-xxl-12 col-md-12 hidden_fields">
                            <div class="col-md-6">
                                <label for="company_name" class="form-label">Company Name : <span class="text-danger">*</span></label>
                                <input type="text" name="company_name" placeholder="Enter Company Name " value="<?php echo $fetch_users['company_name']; ?>" class="form-control" id="company_name">
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
                                <label for="alt_email" class="form-label">Alternate Email : <span class="text-danger">*</span></label>
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
                                <label for="alt_mobile" class="form-label">Alternate Mobile : <span class="text-danger">*</span></label>
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
                                <label for="service_duration" class="form-label">Service Duration : <span class="text-danger">*</span></label>
                                <input type="text" name="service_duration" placeholder="Enter Service Duration " value="<?php echo $fetch_users['service_duration']; ?>" class="form-control" id="service_duration">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12 hidden_fields">
                            <div class="col-md-6">
                                <label for="address" class="form-label">Address : <span class="text-danger">*</span></label>
                                <textarea name="address" rows="3" placeholder="Enter Address " class="form-control" id="address"><?php echo $fetch_users['address']; ?></textarea>
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
                                <input type="text" name="keywords" placeholder="Enter Keywords " data-choices data-choices-text-unique-true data-choices-removeItem class="form-control" id="keywords" value="<?php echo $fetch_users['keywords']; ?>">
                            </div>
                        </div>
                        <div class="col-xxl-12 col-md-12 hidden_fields">
                            <div class="col-md-6">
                                <label for="words" class="form-label">Words : <span class="text-danger">*</span></label>
                                <input type="text" name="words" data-choices data-choices-text-unique-true data-choices-removeItem placeholder="Enter Words " class="form-control" id="words" value="<?php echo $fetch_users['words']; ?>">
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
    if ($('#user_role').val() == "employee") {
        $('.employee_perm_div').removeClass('d-none');
    } else {
        $('.employee_perm_div').addClass('d-none');
    }
    $('#user_role').change(function() {
        if ($(this).val() == "user") {
            $('.hidden_fields').css("display", "block");
        } else {
            $('.hidden_fields').css("display", "none");
        }

        if ($(this).val() == "employee") {
            $('.employee_perm_div').removeClass('d-none');
        } else {
            $('.employee_perm_div').addClass('d-none');
           
        }

    });
    
</script>