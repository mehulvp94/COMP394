<?php
$servername = "www.franklinpracticum.com";
$username = "frank73_s16maps";
$password = "Maps.;s16";
$dbname = "frank73_s16maps";
$i = 0;

$json = "{\"Representatives\":[";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
	die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT FirstName, LastName, State, DistrictID FROM Representative WHERE
State = '" . filter_input(INPUT_POST, "state") . "' ORDER BY LastName";

$result = $conn->query($sql);

while($row = $result->fetch_assoc()) {
	$json .= "{\"lastName\":\"" . $row["LastName"] . "\", \"firstName\":\"" . $row["FirstName"]
	. "\", \"state\":\"" . $row["State"] . "\", \"district\":\"" . $row["DistrictID"] . "\" }";
	$i = $i + 1;

	if ($i != $result->num_rows) {
		$json .= ",";
	}
}

$json .= "]}";

$conn->close();

echo $json;
?>
