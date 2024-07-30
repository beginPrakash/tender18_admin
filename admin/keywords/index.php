<?php include '../includes/authentication.php';
?>
<?php $pages = 'keywords'; ?>
<?php include '../includes/header.php' ?>

<?php
if (isset($_GET['id'])) {
    $del = mysqli_query($con, "DELETE FROM `keywords` where id='" . $_GET['id'] . "'");
    $status = true;
    if ($status) {
        $_SESSION['success'] = 'Deleted successfully.';
    } else {
        $_SESSION['error'] = 'Something went wrong.';
    }
}
if (isset($_POST['importSubmit'])) {

    // Allowed mime types
    $csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');
    // Validate whether selected file is a CSV file
    if(!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'], $csvMimes)){
        
        // If the file is uploaded
        if(is_uploaded_file($_FILES['file']['tmp_name'])){
            
            // Open uploaded CSV file with read-only mode
            $csvFile = fopen($_FILES['file']['tmp_name'], 'r');
            
            // Skip the first line
            fgetcsv($csvFile);
            
            // Parse data from CSV file line by line
            while(($line = fgetcsv($csvFile)) !== FALSE){      
                // Get row data
                $name   = $line[0];
                $q = "INSERT INTO keywords(`name`) VALUES ('$name')";

                $sql = mysqli_query($con, $q);
            }
            // Close opened CSV file
            fclose($csvFile);
            $_SESSION['success'] = 'Keywords imported successfully.';
        }else{
            $_SESSION['error'] = 'Something went wrong.';
        }
    }else{
        $_SESSION['error'] = 'Invalid file';
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
                        window.location.href='" . ADMIN_URL . "/keywords';
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
                        window.location.href='" . ADMIN_URL . "/keywords';
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
</style>

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">keywords List</h4>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <a href="<?php echo ADMIN_URL; ?>assets/images/sample_csv.csv" class="btn btn-primary">Sample CSV</a>
                    <button type="button" name="Import" class="btn btn-primary import_btn">Import Keywords</button>
                </form>
                <div class="col-md-12" id="importFrm" style="display: none;">
                  <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
                  <div class="col-md-6">
                    <label>Upload File</label>
                    <div class="input-group">
                      <input type="file" name="file" accept=".csv"/>
                          <?php if (isset($_GET['error'])) { ?>

                            <div class="invalid-feedback" style="display:block;"><?php echo $_GET['error']; ?></div>

                          <?php } ?>
                          <input type="submit" class="btn btn-primary importSubmit" name="importSubmit" value="IMPORT">
                    </div>
                    
                    <br />
                </div>
                  </form>
              </div>
            </div>
            <div class="card-body">
                <table id="example" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width: 100%">
                    <thead>
                        <tr>
                            <th>SR No.</th>
                            <th>Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $keyword_data = mysqli_query($con, "SELECT * FROM `keywords` order by `id` DESC");
                        $keyword_result = mysqli_num_rows($keyword_data);
                        if ($keyword_result > 0) {
                            $i = 1;
                            foreach ($keyword_data as $data) {
                            ?>
                                <tr>
                                    <th scope="row"><?php echo $i; ?></th>
                                    <td><?php echo $data['name']; ?></td>
                                    <td class="action_element">
                                        <a href="<?php echo ADMIN_URL; ?>keywords?id=<?php echo $data['id']; ?>" class="delete" data-bs-toggle="modal" data-bs-target=".bs-example-modal-center"><i style="font-size: 20px;" class="ri-delete-bin-fill text-danger"></i></a>
                                    </td>
                                </tr>
                            <?php $i++;
                            }
                        } else { ?>
                            <tr>
                                <td colspan="8">No data found.</td>
                            </tr>
                        <?php } ?>
                        <div class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-body text-center p-5">
                                        <i class="bi bi-exclamation-triangle text-warning display-5"></i>
                                        <div class="mt-4">
                                            <h4 class="mb-3">Are you sure you want to delete?</h4>
                                            <p class="text-muted mb-4">
                                                If you click the delete button, the data will be deleted from the site and you can not retrieve this data again.
                                            </p>
                                            <div class="hstack gap-2 justify-content-center">
                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                                    Close
                                                </button>
                                                <a href="javascript:void(0);" class="btn btn-danger">Delete</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.modal-content -->
                            </div>
                            <!-- /.modal-dialog -->
                        </div>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!--end col-->
</div>

<?php include '../includes/footer.php';  ?>

<script>
    $('.action_element a.delete').click(function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        $('.bs-example-modal-center a.btn.btn-danger').prop('href', url);
    });
    
    $(document).on('click','.import_btn',function(){
      $('#importFrm').show();
      $('.import_btn').hide();
    });
</script>