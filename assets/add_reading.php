<?php
if($_SERVER["REQUEST_METHOD"] == "POST"){
    
// Connect to MySQL
include("connect.php");

// Prepare the SQL statement
date_default_timezone_set('Europe/London');
$date = date("Y-m-d", time());
$time = date("H:i", time());

$node = $_POST["ID"] / 100.0 - 100.0;
$soilTemp = $_POST["stemp"] / 100.0 - 100.0;
$soilMoist= $_POST["smoist"] / 100.0 - 100.0;
$airTemp = $_POST["airtemp"] / 100.0 - 100.0;
$airHum = $_POST["airhum"] / 100.0 - 100.0;
$batt = $_POST["batt"] / 100.0 - 100.0;


//H = (log10(RH)-2)/0.4343 + (17.62*T)/(243.12+T);
//Dp = 243.12*H/(17.62-H); // this is the dew point in Celsius
$H = ((log10($airHum) - 2.0) / 0.4343) + (17.62 * $airTemp) / (243.12 + $airTemp);
$dewPoint = (243.12 * $H) / (17.62 - $H);

$SQL = "INSERT INTO readings (`Node`,`Soil Temperature`,`Soil Moisture`,`Air Temperature`,`Air Humidity`,`Dew Point`,Battery,Date,Time) VALUES ('".$node."','".$soilTemp."','".$soilMoist."','".$airTemp."','".$airHum."','".$dewPoint."','".$batt."','".$date."','".$time."')";


// Execute SQL statement
$result = mysqli_query($con, $SQL);

if(!$result)
{
    echo "\nERROR on QUERY";
}
else
{
    echo "SUCCESS";
}

mysqli_close($con);

}
?>
