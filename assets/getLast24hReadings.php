<?php
// Connect to MySQL
include("connect.php");

// Get current date
date_default_timezone_set('Europe/London');
$date = date("Y-m-d", time());
$time = date("H:i", time());


$found = false;
$row;
$newDate = new DateTime($date);
while(!$found)
{
    //Query the database
    $sql="SELECT * FROM readings WHERE Date LIKE '".$newDate->format('Y-m-d')."'";

    // Retrieve all records
    $result = mysqli_query($con, $sql);
    if(empty($result))
    {
        echo "\nERROR on QUERY for 24HReadings";
    }
    $row = mysqli_fetch_array($result);
    if(!empty($row))
        $found = true;
    else
        $newDate->modify('-1 day');
}
$readings = array();
array_push($readings, $row);
while($row = mysqli_fetch_array($result))
{
    array_push($readings, $row);
}



//RETRIEVE ALL DIFFERENT VALUES OF TIME BASED ON THAT DATE
$sql = "SELECT DISTINCT `Time`,`Date` FROM `readings` WHERE `Date` LIKE '".$newDate->format('Y-m-d')."'";
// Retrieve all records
$result = mysqli_query($con, $sql);
if(empty($result))
{
    echo "\nERROR on 2nd QUERY for 24HReadings";
}
$times = array();
while($row = mysqli_fetch_array($result))
{
    array_push($times, $row["Time"]);
}



//RETRIEVE ALL AVERAGES OF FIELDS
$number = count($times);
$average_soil_temp = array();
$average_soil_moist = array();
$average_air_temp = array();
$average_air_hum = array();
$average_dew_point = array();
for($i = 0; $i < $number; $i++)
{
    $sql = "SELECT AVG(`Soil Temperature`) FROM `readings` WHERE `Date` LIKE '".$newDate->format('Y-m-d')."'"." AND `Time`LIKE '".$times[$i]."'";
    $result = mysqli_query($con, $sql);
    if(empty($result))
    {
        echo "\nERROR on AVERAGES QUERY for 24HReadings";
    }
    while($row = mysqli_fetch_array($result))
    {
        array_push($average_soil_temp, $row);
    }
}
for($i = 0; $i < $number; $i++)
{
    $sql = "SELECT AVG(`Soil Moisture`) FROM `readings` WHERE `Date` LIKE '".$newDate->format('Y-m-d')."'"." AND `Time`LIKE '".$times[$i]."'";
    $result = mysqli_query($con, $sql);
    if(empty($result))
    {
        echo "\nERROR on AVERAGES QUERY for 24HReadings";
    }
    while($row = mysqli_fetch_array($result))
    {
        array_push($average_soil_moist, $row);
    }
}
for($i = 0; $i < $number; $i++)
{
    $sql = "SELECT AVG(`Air Temperature`) FROM `readings` WHERE `Date` LIKE '".$newDate->format('Y-m-d')."'"." AND `Time`LIKE '".$times[$i]."'";
    $result = mysqli_query($con, $sql);
    if(empty($result))
    {
        echo "\nERROR on AVERAGES QUERY for 24HReadings";
    }
    while($row = mysqli_fetch_array($result))
    {
        array_push($average_air_temp, $row);
    }
}
for($i = 0; $i < $number; $i++)
{
    $sql = "SELECT AVG(`Air Humidity`) FROM `readings` WHERE `Date` LIKE '".$newDate->format('Y-m-d')."'"." AND `Time`LIKE '".$times[$i]."'";
    $result = mysqli_query($con, $sql);
    if(empty($result))
    {
        echo "\nERROR on AVERAGES QUERY for 24HReadings";
    }
    while($row = mysqli_fetch_array($result))
    {
        array_push($average_air_hum, $row);
    }
}

for($i = 0; $i < $number; $i++)
{
    $sql = "SELECT AVG(`Dew Point`) FROM `readings` WHERE `Date` LIKE '".$newDate->format('Y-m-d')."'"." AND `Time`LIKE '".$times[$i]."'";
    $result = mysqli_query($con, $sql);
    if(empty($result))
    {
        echo "\nERROR on AVERAGES QUERY for 24HReadings";
    }
    while($row = mysqli_fetch_array($result))
    {
        array_push($average_dew_point, $row);
    }
}


/**
* Create Labels array for graphs
* This part is converting the time into a label using a character approach 
* Basically the graph won't visualise unless the labes are correctly formated
* so it is necessary to format the time to make sure is using 4 digits total 
* for each data point
**/
$labels = array();
$number = count($times);
for($i = 0; $i < $number; $i++)
{
    //NOTE: LAST INDEX IS FOR CHARACTER BECAUSE HERE I'M CONSTRUCTING THE STRING FROM THE TIME CHARACTERS
    $current = $times[$i][0];
    $current = $current.$times[$i][1];
    $current = $current.".";
    $current = $current.$times[$i][3];
    $current = $current.$times[$i][4];
    $current = $current.",";
    array_push($labels,number_format(floatval($current),2).",");
}
?>
