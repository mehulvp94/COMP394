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
$geoid = filter_input(INPUT_POST, "geoid");
$arr = str_split($geoid);

$sqlCommand = "SELECT State_Abb FROM states WHERE JSONCode = '" . $arr[0] . $arr[1] . "';";

$result = $conn->query($sqlCommand);

$row = $result->fetch_assoc();

$json = "{\"state\":\"" . $row["State_Abb"] . "\", \"district\":\"" . $arr[2] . $arr[3] . "\"}";

echo $json;


