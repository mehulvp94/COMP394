<?php

$servername = "www.franklinpracticum.com";
$username = "frank73_s16maps";
$password = "Maps.;s16";
$dbname = "frank73_s16maps";
$i = 0;

$json = "{\"DistrictIds\":[";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
	die("Connection failed: " . $conn->connect_error);
}

mysqli_select_db($conn, $dbname);

$sql = "SELECT DistrictID, State FROM Representative WHERE
State = '" . filter_input(INPUT_POST, "state") . "' ORDER BY DistrictID";

$result = $conn->query($sql);

while($row = $result->fetch_assoc()) {
	$json .= "{\"district\":\"" . $row["DistrictID"] . "\", \"state\":\"" 
                . $row["State"] . "\"}";
	$i = $i + 1;
        
	if ($i != $result->num_rows) {
		$json .= ",";
	}
}

$json .= "]}";

$conn->close();
echo $json;
?>
