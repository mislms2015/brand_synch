<?php include_once '../layout/sub-head.php'; ?>
<style>
<?php include_once '../metro/css/style.css'; ?>
</style>
<?php
include_once "./nav.php";
include_once '../config/conn.php';
include_once '../config/__utils.php';
?>

<title>Import: Brand Series</title>

<div class="container-fluid">
    <div class="grid">
        <div class="row mt-10">
            <div class="stub">
                <?= nav($conn, 'import_bca_file', 'active'); ?>
            </div>

            <div class="cell">

                <!-- Query logic: Start -->
                <?php
                $header = checkHeaderBcaFile();

                if (isset($_POST['submit'])) {
                
                    // Allowed mime types
                    $fileMimes = fileMimes();
                    $files = $_FILES['file'];
                
                    // Validate whether selected file is a CSV file
                    if (!empty($files)) {

                        for ($i = 0; $i < count($files['name']); $i++) {
                            $bca__logs = array();
                            $filename = $files['name'][$i];
                            if (in_array($files['type'][$i], $fileMimes)) {
                                $err_msg = '';

                                // Open uploaded CSV file with read-only mode
                                $csvFile = fopen($files['tmp_name'][$i], 'r');

                                // Skip the first line
                                $numcols = fgetcsv($csvFile);

                                $temp_header = array();
                                for ($header_column = 0; $header_column < count($numcols); $header_column++) {
                                    array_push($temp_header, $numcols[$header_column]);
                                }
                                
                                $header_compare = array_diff($header,$temp_header);

                                //check file if uploaded
                                $check_file = mysqli_query($conn, "SELECT * FROM file_uploaded WHERE file_name = '". $filename ."'");

                                if (count($header_compare) > 0) {
                                    $err_msg = "<b><i>$filename header not match.</i></b>";
                                } else if (mysqli_num_rows($check_file) > 0) {
                                    $err_msg = "<b><i>$filename file already uploaded.</i></b>";
                                } else {
                                    $err_msg = '';
                                }
                    
                                if (count($header_compare) == 0 && mysqli_num_rows($check_file) == 0) {
                                    // insert filename to table for additional validation
                                    $file_upload_banner = $filename;

                                    $upload_file = "INSERT INTO file_uploaded (file_type, file_name, banner) VALUES ('bca_file', '".$files['name'][$i]. "', '" .$file_upload_banner. "')";
                                    
                                    $file_upload_id = '';
                                    if ($conn->query($upload_file) === TRUE) {
                                        $last_id = $conn->insert_id;
                                        $file_upload_id = $last_id;
                                    } else {
                                        $file_upload_id = '999999999';
                                    }

                                    // Parse data from CSV file line by line
                                    $counter = 0;
                                    while (($getData = fgetcsv($csvFile, 100000000, ",")) !== FALSE) {

                                        array_push($bca__logs, array($file_upload_id, $getData[0], $getData[1], $getData[2], $getData[3], $getData[4], $getData[5], $getData[6]));

                                        $counter++;
                                    }

                                    // Close opened CSV file
                                    fclose($csvFile);
                                    
                                    //insert gigapay raw logs here:start
                                    $query = "INSERT INTO bca_file (id, file_id, number_series, brand_id, brand_name, brand_description, is_active, is_supported) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                                    $stmt = $conn->prepare($query);
                                    $stmt->bind_param("ssssssss", $upload_id, $id, $number_series, $brand_id, $brand_name, $brand_description, $is_active, $is_supported);

                                    $conn->query("START TRANSACTION");
                                    foreach ($bca__logs as $res) {
                                        if ($res[3] == 'EXSUNPRE') {
                                            $temp_brand_id = 'BUDDY';
                                            $temp_brand_name = 'Smart Prepaid';
                                            $temp_brand_description = 'Smart Prepaid';
                                        } else if ($res[3] == 'SUNPOS') {
                                            $temp_brand_id = 'POSTPD';
                                            $temp_brand_name = 'Smart Postpaid';
                                            $temp_brand_description = 'Smart Postpaid';
                                        } else {
                                            $temp_brand_id = $res[3];
                                            $temp_brand_name = $res[4];
                                            $temp_brand_description = $res[5];
                                        }

                                        $upload_id = $res[0];
                                        $id = $res[1];
                                        $number_series = $res[2];
                                        $brand_id = $temp_brand_id;
                                        $brand_name = $temp_brand_name;
                                        $brand_description = $temp_brand_description;
                                        $is_active = $res[6];
                                        $is_supported = $res[7];
                                        $stmt->execute();
                                    }
                                    $stmt->close();
                                    $conn->query("COMMIT");
                                    //insert gigapay raw logs here: end

                                    echo "
                                        <div class='remark info'>
                                            <pre class='fg-green'><b><i>$filename</i></b> successfully imported. $counter rows inserted.</pre>
                                        </div>

                                        <audio autoplay>
                                            <source src='../asset/sound/chime.mp3'>
                                        </audio>
                                    ";
                                } else {
                                    echo "
                                        <div class='remark warning'>
                                            <pre class='fg-red'>$err_msg</pre>
                                        </div>
                                    ";
                                }
                            } else {
                                echo "
                                    <div class='remark warning'>
                                        <pre class='fg-red'>$filename invalid file.</pre>
                                    </div>
                                ";
                            }
                            
                        }
                    }
                    else {
                        echo "
                            <div class='remark warning'>
                                <pre class='fg-red'>No file selected.</pre>
                            </div>
                        ";
                    }
                }
                ?>
                <!-- Query logic: End -->
            </div>
            
        </div>
    </div>

</div>

<?php include_once '../layout/sub-footer.php'; ?>
<script src="../metro/js/script.js"></script>