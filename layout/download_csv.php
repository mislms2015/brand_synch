<?php
include_once "../config/conn.php";

// $filename = $_GET['file_name'];
// $pasa_type = $_GET['pasa_type'];

$date = date('Y-m-d', time());
$dateTime = $date. " 00:00:00";

$filename = "brand_synch_" .$date. ".csv";

$query = mysqli_query($conn, "SELECT * FROM bca_file WHERE is_match IS NULL"); 

if($query->num_rows > 0){ 
    $delimiter = ","; 

    $today = date("F j, Y, g:i a");
    
    // Create a file pointer 
    $f = fopen('php://memory', 'w'); 
    // Set column headers 
    // $fields = array('sequence_number', 'transaction_id', 'date_registered', 'primary_min', 'brand', 'mran', 'recipient_min', 'amount', 'date_initiated', 'date_failed', 'date_requested', 'date_debit_confirmed', 'date_credit_confirmed', 'denomination_id', 'pasa_type', 'BRAND2', 'STATUS'); 
    // fputcsv($f, $fields, $delimiter); 

    $fields = array('_id', 'min', 'brand_id', 'brand_name', 'brand_description', 'is_active', 'creation_timestamp', 'last_update_timestamp', 'last_updated_timestamp');
    fputcsv($f, $fields, $delimiter); 
    
    // Output each row of the data, format line as csv and write to file pointer 
    while($row = $query->fetch_assoc()){
        if ($row['brand_id'] == 'DITO' || $row['brand_id'] == 'GLOBE') {
            $status = 'false';
        } else {
            $status = 'true';
        }

        $lineData = array($row['file_id'], $row['number_series'], $row['brand_id'], $row['brand_name'], $row['brand_description'], $status, strtotime($today), strtotime($today));

        fputcsv($f, $lineData, $delimiter);
    }
    
    // Move back to beginning of file 
    fseek($f, 0);
    
    // Set headers to download file rather than displayed 
    header('Content-Type: text/csv'); 
    header('Content-Disposition: attachment; filename="' . $filename . '";'); 
    
    //output all remaining data on a file pointer 
    fpassthru($f);
    
} 
exit;


?>