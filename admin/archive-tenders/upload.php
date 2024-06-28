<?php include '../includes/authentication.php';
?>
<?php $pages = 'tenders'; ?>
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
        $uploadDirectory = '../uploads/tender_uploads/';

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
                    $due_timestap = PHPExcel_Shared_Date::ExcelToPHP($results[$i][8]);
                    $publish_timestap = PHPExcel_Shared_Date::ExcelToPHP($results[$i][7]);

                    $due_date = date('Y-m-d', $due_timestap);
                    $publish_date = date('Y-m-d', $publish_timestap);

                    $title = $results[$i][0];
                    $tender_id = $results[$i][1];
                    $agency_type = $results[$i][2];
                    $location = $results[$i][4];
                    $tender_value = $results[$i][6];
                    $description = $results[$i][3];
                    $pincode = $results[$i][5];
                    $tender_fee = $results[$i][9];
                    $tender_emd = $results[$i][10];
                    $documents = $results[$i][11];


                    echo '<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
                        <script>
                            $(document).ready(function () {
                                var title = "' . $title . '";
                                var tender_id = "' . $tender_id . '";
                                var agency_type = "' . $agency_type . '";
                                var location = "' . $location . '";
                                var tender_value = "' . $tender_value . '";
                                var description = "' . $description . '";
                                var pincode = "' . $pincode . '";
                                var tender_fee = "' . $tender_fee . '";
                                var tender_emd = "' . $tender_emd . '";
                                var documents = "' . $documents . '";
                                var due_date = "' . $due_date . '";
                                var publish_date = "' . $publish_date . '";

                                $.ajax({
                                    type: "POST",
                                    url: "tender_ajax.php",
                                    data: { 
                                        title: title,
                                        tender_id: tender_id,
                                        agency_type: agency_type,
                                        location: location,
                                        tender_value: tender_value,
                                        description: description,
                                        pincode: pincode,
                                        tender_fee: tender_fee,
                                        tender_emd: tender_emd,
                                        documents: documents,
                                        due_date: due_date,
                                        publish_date: publish_date,
                                    },
                                    success: function (response) {
                                        counter = counter + 1;
                                        // console.log("Data inserted: " + response);
                                        $(".ajax_records ul").append("<li>"+response+"</li>");
                                        if(counter == total_count){
                                            $(".ajax_records ul").append("<li class=\"text-success\">Uploaded successfully</li>");
                                        }
                                    }
                                });
                            });
                        </script>';
                }
                $success_message = 'Tenders uploading please check below results.';
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
            <h4 class="mb-sm-0">Upload Tenders</h4>
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
                                    echo "Uploading data";
                                } else {
                                    echo "No data found.";
                                } ?></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end col-->
</div>

<?php include '../includes/footer.php';  ?>