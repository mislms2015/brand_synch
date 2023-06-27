<?php
include_once "../config/conn.php";

$batch_1 = array();
$batch_2 = array();
$series_to_bca = array();

####get all brand_series and bash to bca file
$investigate_series_bca = mysqli_query($conn5, "SELECT brand_id, series FROM brand_series");

while ($row = mysqli_fetch_object($investigate_series_bca)) {
    array_push($series_to_bca, array($row->brand_id, rtrim($row->series, "~"). '%'));
}

$query_series = "UPDATE bca_file SET is_match = '2', brand = ? WHERE number_series LIKE ? AND brand_id = ? AND CHAR_LENGTH(number_series) = 12";
$stmt_update = $conn5->prepare($query_series);
$stmt_update->bind_param("sss", $brand_id, $series, $brand_id);

foreach($series_to_bca as $bsi):
        
        $conn5->query("START TRANSACTION");
        $brand_id = $bsi[0];
        $series = $bsi[1];
        $stmt_update->execute();
    
endforeach;

$stmt_update->close();
$conn5->query("COMMIT");

//------------------------------------------------------------------------------------------------------

####bash first with the brand_series
$bca_brand_series = mysqli_query($conn, "SELECT number_series, brand_id FROM bca_file");

while ($row = mysqli_fetch_object($bca_brand_series)) {
    array_push($batch_1, array($row->number_series, $row->brand_id));
}

$stmt_series_bch1 = $conn->prepare("SELECT
series, brand_id
FROM
brand_series
    WHERE
    series = ?");
$stmt_series_bch1->bind_param('s', $number_series);

$query_series_bch1 = "UPDATE bca_file SET is_match = ?, brand = ? WHERE number_series = ?";
$stmt_update_bch1 = $conn2->prepare($query_series_bch1);
$stmt_update_bch1->bind_param("sss", $is_match, $brand_id, $series);

foreach($batch_1 as $bch1):
    $number_series = $bch1[0];
    $brand_series = $bch1[1];

    $stmt_series_bch1->execute();

    $stmt_series_bch1->bind_result($col_series, $col_brand);

    while ($stmt_series_bch1->fetch()) {
        $is_match_temp = "";
        $brand_id_temp = "";
        $series_temp = "";

        if ($col_series == $number_series && $col_brand == $brand_series) {
            $is_match_temp = "2";
            $brand_id_temp = $col_brand;
            $series_temp = $number_series;
        } else if ($col_series == $number_series && $col_brand != $brand_series) {
            $is_match_temp = "1";
            $brand_id_temp = $col_brand;
            $series_temp = $number_series;
        } else {
            $is_match_temp = "0";
            $brand_id_temp = null;
            $series_temp = $number_series;
        }

        $conn2->query("START TRANSACTION");
        $is_match = $is_match_temp;
        $brand_id = $brand_id_temp;
        $series = $series_temp;
        $stmt_update_bch1->execute();
    }

    
endforeach;

$stmt_series_bch1->close();
$conn->query("COMMIT");
$stmt_update_bch1->close();
$conn2->query("COMMIT");

//------------------------------------------------------------------------------------------------------

####bash to min_metadata
$investigate = mysqli_query($conn3, "SELECT number_series, brand_id FROM bca_file");

while ($row = mysqli_fetch_object($investigate)) {
    array_push($batch_2, array($row->number_series, $row->brand_id));
}

$stmt_series_bch2 = $conn3->prepare("SELECT
min, brand_id
FROM
min_metadata
    WHERE
    min = ?");
$stmt_series_bch2->bind_param('s', $number_series);

$query_series_bch2 = "UPDATE bca_file SET is_match = ?, brand = ? WHERE number_series = ?";
$stmt_update_bch2 = $conn4->prepare($query_series_bch2);
$stmt_update_bch2->bind_param("sss", $is_match, $brand_id, $series);

foreach($batch_2 as $bch2):
    $number_series = $bch2[0];
    $brand_series = $bch2[1];

    $stmt_series_bch2->execute();

    $stmt_series_bch2->bind_result($col_series, $col_brand);

    while ($stmt_series_bch2->fetch()) {
        $is_match_temp = "";
        $brand_id_temp = "";
        $series_temp = "";

        if ($col_series == $number_series && $col_brand == $brand_series) {
            $is_match_temp = "2";
            $brand_id_temp = $col_brand;
            $series_temp = $number_series;
        } else if ($col_series == $number_series && $col_brand != $brand_series) {
            $is_match_temp = "1";
            $brand_id_temp = $col_brand;
            $series_temp = $number_series;
        } else {
            $is_match_temp = "0";
            $brand_id_temp = null;
            $series_temp = $number_series;
        }

        $conn4->query("START TRANSACTION");
        $is_match = $is_match_temp;
        $brand_id = $brand_id_temp;
        $series = $series_temp;
        $stmt_update_bch2->execute();
    }

    
endforeach;

$stmt_series_bch2->close();
$conn3->query("COMMIT");
$stmt_update_bch2->close();
$conn4->query("COMMIT");

echo 'success';
?>