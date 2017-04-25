<?php
$servername = "www.franklinpracticum.com";
$username = "frank73_s16maps";
$password = "Maps.;s16";
$dbname = "frank73_s16maps";

$conn = new mysqli($servername, $username, $password);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->select_db($dbname);

$sql = "SELECT JSONCode FROM states WHERE State_Abb ='" . filter_input(INPUT_POST, "state") . "'";
$result = $conn->query($sql);
$code;
if ($result) {
    $code = $result->fetch_assoc()["JSONCode"];
} else {
    die($conn->error);
}
$conn->close();

$code = $code . filter_input(INPUT_POST, "districtid");
$json = json_decode(file_get_contents("../view/cd_114.json"));
foreach ($json->features as $row) {
    $geoid = $row->properties->GEOID;
    if ($geoid === $code) {
        echo json_encode($row);
    }
}