<?php include '../includes/authentication.php';
?>
<?php $pages = 'agencies'; ?>
<?php include '../includes/header.php' ?>
<?php
// Include the PHPExcel library
require '../PHPExcel/Classes/PHPExcel.php';
error_reporting(0);
?>
<?php
$error_message = "";
$success_message = "";
if (isset($_POST['submit_excel'])) {
    // Check if the file was uploaded without errors
    if (isset($_FILES['excel_file']) && $_FILES['excel_file']['error'] === UPLOAD_ERR_OK) {

        // Define the directory where you want to save the uploaded file
        $uploadDirectory = '../uploads/agency_uploads/';

        // Get the uploaded file's information
        $uploadedFile = $_FILES['excel_file'];
        $fileName = $uploadedFile['name'];
        $fileTmpName = $uploadedFile['tmp_name'];

        // Check if the file is an Excel file (XLS or XLSX)
        $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);
        if (in_array($fileExt, ['xls', 'xlsx'])) {
            // Create a unique name for the uploaded file
            $newFileName = $uploadDirectory . uniqid() . '.' . $fileExt;

            // Move the uploaded file to the desired location
            if (move_uploaded_file($fileTmpName, $newFileName)) {
                // echo 'File uploaded successfully.';

                // Now you can process the Excel file using PHPExcel
                $objPHPExcel = PHPExcel_IOFactory::load($newFileName);
                $worksheet = $objPHPExcel->getActiveSheet();

                // $cellValue = $worksheet->getCell('A2')->getValue();
                // echo 'Data in cell A2: ' . $cellValue;

                // Get the highest row and column in the worksheet
                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                // Initialize an array to store the results
                $results = array();

                // Loop through the rows and columns to get the data
                for ($row = 1; $row <= $highestRow; $row++) {
                    $rowData = array();
                    for ($col = 'A'; $col <= $highestColumn; $col++) {
                        $cellValue = $worksheet->getCell($col . $row)->getValue();
                        $rowData[] = $cellValue;
                    }
                    $results[] = $rowData;
                }

                // echo "<pre>";
                // print_r($results);
                // echo "</pre>";

                echo '<script>var counter = 0; var total_count = ' . (count($results) - 1) . ';</script>';
                for ($i = 1; $i < count($results); $i++) {
                    // for ($j = 0; $j <= 11; $j++) {
                    //     echo $results[$i][$j] . '<br>';
                    // }

                    $title = $results[$i][0];
                    $ref_no = $results[$i][1];

                    echo '<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
                    <script>
                        $(document).ready(function () {
                            var title = "' . $title . '";
                            var ref_no = "' . $ref_no . '";
                            
                            $.ajax({
                                type: "POST",
                                url: "agencies_ajax.php",
                                data: { 
                                    title: title,
                                    ref_no: ref_no,
                                },
                                success: function (response) {
                                    counter = counter + 1;
                                    // console.log("Data inserted: " + response);
                                    $(".ajax_records ul").append("<li>"+response+"</li>");
                                    if(counter == total_count){
                                        $(".ajax_records ul").append("<li class=\"text-success\">Uploaded successfully</li>");
                                    }
                                    var curr_per = ( counter * 100 ) / total_count;
                                    $("#progress_percentage").html(curr_per.toFixed(2)+"%");
                                    $(".progress .progress-bar").css("width",curr_per.toFixed(2)+"%");
                                }
                            });
                        });
                    </script>';
                }

                $success_message = 'Agencies uploading please check below results.';
            } else {
                $error_message = 'Failed to move the uploaded file.';
            }
        } else {
            $error_message = 'Only Excel files (XLS or XLSX) are allowed.';
        }
    } else {
        $error_message = 'Error during file upload.';
    }
    // echo "<script>setTimeout(function(){ window.location.href='" . ADMIN_URL . "/tenders'; }, 2000);</script>";
}
?>
<style>
    #example .action_element {
        display: flex;
        align-items: center;
    }

    .ajax_records p {
        margin-bottom: 5px;
    }

    .ajax_records ul {
        margin-bottom: 0;
        padding-left: 8px;
    }

    .ajax_records ul li {
        font-size: 14px;
    }
</style>

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Upload Agencies</h4>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col float-start d-flex text-start">
                        <form action="" method="POST" enctype="multipart/form-data" class="col-4 d-flex gap-3">
                            <input type="file" name="excel_file" class="form-control" accept=".xls, .xlsx">
                            <input type="submit" value="Upload Excel File" class="btn btn-primary" name="submit_excel">
                        </form>
                    </div>
                    <?php
                    if (!empty($error_message))
                        echo '<p class="text-danger mt-2 mb-0">' . $error_message . '</p>';
                    if (!empty($success_message))
                        echo '<p class="text-success mt-2 mb-0">' . $success_message . '</p>';
                    ?>
                    <div class="ajax_records mt-4 p-3" style="height: 400px;border: 1px solid gray;border-radius: 10px;overflow-y: scroll;">
                        <p><b>Current Logs:</b></p>
                        <ul class="list-unstyled">
                            <li><?php if (isset($_POST['submit_excel'])) {
                                    echo "Uploading data...";
                                } else {
                                    echo "No data found.";
                                } ?></li>
                        </ul>
                    </div>
                    <p class="mt-3"><b>Progress: </b><span id="progress_percentage"><?php if (!isset($_POST['submit_excel'])) {
                                                                                        echo "0.00%";
                                                                                    } ?></span></p>
                    <div class="progress mb-4 p-0">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end col-->
</div>

<?php include '../includes/footer.php';  ?>