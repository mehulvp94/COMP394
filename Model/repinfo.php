<?php
$servername = "www.franklinpracticum.com";
$username = "frank73_s16maps";
$password = "Maps.;s16";
$dbname = "frank73_s16maps";
$i = 0;

$repjson = "{\"RepInfo\":[";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
	die("Connection failed: " . $conn->connect_error);
}

mysqli_select_db($conn, $dbname);

$sql = "SELECT FirstName, LastName, Role, Party, OfficeAdd, DCPhone, DistrictPhone, ElectContact, WebAddress, DistrictID, State 
    FROM Representative WHERE
    DistrictID = '" . filter_input(INPUT_POST, "districtid") . "' AND State = '". filter_input(INPUT_POST, "state") . "'"; 

$result = $conn->query($sql);

//Add representative to the JSON array
while($row = $result->fetch_assoc()) {
	$repjson .= "{\"firstName\":\"" . $row["FirstName"]. "\", \"lastName\":\"". $row["LastName"] .
                "\", \"role\":\"". $row["Role"] . "\", \"party\":\"" . $row["Party"] . "\", \"officeAdd\":\"" . $row["OfficeAdd"] .
                "\", \"dcPhone\":\"". $row["DCPhone"] . "\", \"districtPhone\":\"" . $row["DistrictPhone"] .
                "\", \"electContact\":\"". $row["ElectContact"] . "\", \"webAdd\":\"" . $row["WebAddress"] .
                "\", \"district\":\"" . $row["DistrictID"] . "\", \"state\":\"" 
                . $row["State"] . "\"},";
	}


//Create Senator SQL Command
$senSQL = "SELECT FirstName, LastName, Role, Party, OfficeAdd, DCPhone, DistrictPhone, ElectContact, WebAddress, DistrictID, State 
    FROM Senator WHERE
    State = '". filter_input(INPUT_POST, "state") . "'";

$resultSen = $conn->query($senSQL);

//Populate the Senators
while($row = $resultSen->fetch_assoc()) {
	$repjson .= "{\"firstName\":\"" . $row["FirstName"]. "\", \"lastName\":\"". $row["LastName"] .
                "\", \"role\":\"". $row["Role"] . "\", \"party\":\"" . $row["Party"] . "\", \"officeAdd\":\"" . $row["OfficeAdd"] .
                "\", \"dcPhone\":\"". $row["DCPhone"] . "\", \"districtPhone\":\"" . $row["DistrictPhone"] .
                "\", \"electContact\":\"". $row["ElectContact"] . "\", \"webAdd\":\"" . $row["WebAddress"] .
                "\", \"district\":\"" . $row["DistrictID"] . "\", \"state\":\"" 
                . $row["State"] . "\"}";
        
        $i = $i + 1;
        if ($i != 2)
        {
            $repjson .= ",";
        }
}


$repjson .= "]}";

$conn->close();
echo $repjson;
?>


