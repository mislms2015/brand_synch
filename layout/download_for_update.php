<?php
include_once "../config/conn.php";

// $filename = $_GET['file_name'];
// $pasa_type = $_GET['pasa_type'];

$date = date('Y-m-d', time());
$dateTime = $date. " 00:00:00";

$filename = "brand_synch_update_" .$date. ".csv";

$query = mysqli_query($conn, "SELECT * FROM bca_file WHERE is_match = '1'"); 

if($query->num_rows > 0){ 
    $delimiter = ","; 
    
    // Create a file pointer 
    $f = fopen('php://memory', 'w'); 
    // Set column headers 
    // $fields = array('sequence_number', 'transaction_id', 'date_registered', 'primary_min', 'brand', 'mran', 'recipient_min', 'amount', 'date_initiated', 'date_failed', 'date_requested', 'date_debit_confirmed', 'date_credit_confirmed', 'denomination_id', 'pasa_type', 'BRAND2', 'STATUS'); 
    // fputcsv($f, $fields, $delimiter); 
    
    // Output each row of the data, format line as csv and write to file pointer 
    while($row = $query->fetch_assoc()){

        //$lineData = array($row['file_id'], $row['number_series'], $row['brand_id'], $row['brand_name'], $row['brand_description'], 1, 1, $dateTime, $dateTime);

        $number_series = substr($row['number_series'], 2);
        $brand_id = $row['brand_id'];
        $brand_description = $row['brand_description'];
        $brand_name = $row['brand_name'];

        //db.min_metadata.updateOne({"min":"9282130970"}, {$set:{"brand_description":"Smart Prepaid213", "brand_id":"TNT", "brand_name":"Smart Prepaid", "is_active":true}})
        $lineData = array('db.min_metadata.updateOne({"min":"'.$number_series.'"}, {$set:{"brand_description":"'.$brand_description.'", "brand_id":"'.$brand_id.'", "brand_name":"'.$brand_name.'", "is_active":true}})');
        //$lineData = array("db.min_metadata.updateOne({min:$number_series}, {".'$set'.":{brand_description:$brand_description, brand_id:$brand_id, brand_name:$brand_name, is_active:true}})");

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