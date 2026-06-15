<?php
require_once 'config/database.php';
require_once 'models/Bike.php';

header('Content-Type: application/json');

$bike = new Bike();
$stmt = $bike->readAvailable();
$num = $stmt->rowCount();

$bikes_arr = array();
$bikes_arr["records"] = array();

if($num > 0){
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $bike_item = array(
            "id" => $id,
            "kode_sepeda" => $kode_sepeda,
            "merk" => $merk,
            "tarif_per_jam" => $tarif_per_jam,
            "status" => $status
        );
        array_push($bikes_arr["records"], $bike_item);
    }
    http_response_code(200);
    echo json_encode($bikes_arr);
} else {
    http_response_code(404);
    echo json_encode(array("message" => "Tidak ada sepeda yang tersedia saat ini."));
}
?>
