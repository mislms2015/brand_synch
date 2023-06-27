<?php
include_once "../config/conn.php";

$pasa_initial = array();
$brand_series_initial = array();


####bash first with the brand_series
$bca_brand_series = mysqli_query($conn, "SELECT number_series, brand_id FROM bca_file");

while ($row = mysqli_fetch_object($bca_brand_series)) {
    array_push($brand_series_initial, array($row->number_series, $row->brand_id));
}

$stmt_series = $conn->prepare("SELECT
series, brand_id
FROM
brand_series
    WHERE
    series = ?");
$stmt_series->bind_param('s', $number_series);

$query_series = "UPDATE bca_file SET is_match = ?, brand = ? WHERE number_series = ?";
$stmt_update = $conn2->prepare($query_series);
$stmt_update->bind_param("sss", $is_match, $brand_id, $series);

foreach($brand_series_initial as $bsi):
    $number_series = $bsi[0];
    $brand_series = $bsi[1];

    $stmt_series->execute();

    $stmt_series->bind_result($col_series, $col_brand);

    while ($stmt_series->fetch()) {
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
        $stmt_update->execute();
    }

    
endforeach;

$stmt_series->close();
$conn->query("COMMIT");
$stmt_update->close();
$conn2->query("COMMIT");

//------------------------------------------------------------------------------------------------------

####bash to min_metadata
$investigate = mysqli_query($conn, "SELECT number_series, brand_id FROM bca_file");

while ($row = mysqli_fetch_object($bca_brand_series)) {
    array_push($pasa_initial, array($row->number_series, $row->brand_id));
}

$stmt_series = $conn->prepare("SELECT
min, brand_id
FROM
min_metadata
    WHERE
    min = ?");
$stmt_series->bind_param('s', $number_series);

$query_series = "UPDATE bca_file SET is_match = ?, brand = ? WHERE number_series = ?";
$stmt_update = $conn2->prepare($query_series);
$stmt_update->bind_param("sss", $is_match, $brand_id, $series);

foreach($brand_series_initial as $bsi):
    $number_series = $bsi[0];
    $brand_series = $bsi[1];

    $stmt_series->execute();

    $stmt_series->bind_result($col_series, $col_brand);

    while ($stmt_series->fetch()) {
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
        $stmt_update->execute();
    }

    
endforeach;

$stmt_series->close();
$conn->query("COMMIT");
$stmt_update->close();
$conn2->query("COMMIT");

echo 'success';
?>