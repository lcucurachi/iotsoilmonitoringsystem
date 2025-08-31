<?php
include("getThresholds.php");
include("connect.php");

// Prepare the SQL statement
date_default_timezone_set('Europe/London');
$date = date("Y-m-d", time());
$time = date("H:i", time());

$operator = $_GET["operator"];
$SQL = "";

//Query the database
if($operator == ">")
    $SQL="TRUNCATE TABLE thresholds_up";
else if($operator == "<")
    $SQL="TRUNCATE TABLE thresholds_down";

// Execute SQL statement
$result = mysqli_query($con, $SQL);
if(!$result)
{
    echo "\nERROR on QUERY TRUNCATE";
}
else
{
    echo "\nSUCCESS TRUNCATING";
}

$type = $_GET["type"];

if($type == "air")
{
    $air_temp =  $_GET["air_temp"];
    $air_hum =  $_GET["air_hum"];
    if($operator == ">")
        $SQL = "INSERT INTO thresholds_up (`Soil Temperature`, `Soil Moisture`, `Air Temperature`, `Air Humidity`, `Dew Point`, `Battery`) VALUES ('".$thresholds_up[0]."', '".$thresholds_up[1]."', '".$air_temp."', '".$air_hum."', '".$thresholds_up[4]."', '".$thresholds_up[5]."');";
    else if($operator == "<")
        $SQL = "INSERT INTO thresholds_down (`Soil Temperature`, `Soil Moisture`, `Air Temperature`, `Air Humidity`, `Dew Point`, `Battery`) VALUES ('".$thresholds_down[0]."', '".$thresholds_down[1]."', '".$air_temp."', '".$air_hum."', '".$thresholds_down[4]."', '".$thresholds_down[5]."');";
}
else if($type == "soil")
{
    $soil_temp =  $_GET["soil_temp"];
    $soil_moist =  $_GET["soil_moist"];
    if($operator == ">")
        $SQL = "INSERT INTO thresholds_up (`Soil Temperature`, `Soil Moisture`, `Air Temperature`, `Air Humidity`, `Dew Point`, `Battery`) VALUES ('".$soil_temp."', '".$soil_moist."', '".$thresholds_up[2]."', '".$thresholds_up[3]."', '".$thresholds_up[4]."', '".$thresholds_up[5]."');";
    else if($operator == "<")
        $SQL = "INSERT INTO thresholds_down (`Soil Temperature`, `Soil Moisture`, `Air Temperature`, `Air Humidity`, `Dew Point`, `Battery`) VALUES ('".$soil_temp."', '".$soil_moist."', '".$thresholds_down[2]."', '".$thresholds_down[3]."', '".$thresholds_down[4]."', '".$thresholds_down[5]."');";
}
else if($type == "other")
{
    $dew_point =  $_GET["dew_point"];
    $batt_lev =  $_GET["batt_lev"];
    if($operator == ">")
        $SQL = "INSERT INTO thresholds_up (`Soil Temperature`, `Soil Moisture`, `Air Temperature`, `Air Humidity`, `Dew Point`, `Battery`) VALUES ('".$thresholds_up[0]."', '".$thresholds_up[1]."', '".$thresholds_up[2]."', '".$thresholds_up[3]."', '".$dew_point."', '".$batt_lev."');";
    else if($operator == "<")
        $SQL = "INSERT INTO thresholds_down (`Soil Temperature`, `Soil Moisture`, `Air Temperature`, `Air Humidity`, `Dew Point`, `Battery`) VALUES ('".$thresholds_down[0]."', '".$thresholds_down[1]."', '".$thresholds_down[2]."', '".$thresholds_down[3]."', '".$dew_point."', '".$batt_lev."');";
}

// Execute SQL statement
$result = mysqli_query($con, $SQL);

if(!$result)
{
    echo "\nERROR on QUERY INSERT";
}
else
{
    echo "\nSUCCESS INSERTING";
}

mysqli_close($con);
header('Location: /manageThresholds.php');
?>