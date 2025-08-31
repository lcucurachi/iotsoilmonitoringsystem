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

/**
* Store records in a new array.
* Basically just push all of the values in an array.
**/
$readings = array();
array_push($readings, $row);
while($row = mysqli_fetch_array($result))
{
    array_push($readings, $row);
}

/**
* Create Labels array for graphs
* This part is converting the time into a label using a character approach 
* Basically the graph won't visualise unless the labes are correctly formated
* so it is necessary to format the time to make sure is using 4 digits total 
* for each data point
**/
$labels = array();
$number = count($readings);
for($i = 0; $i < $number; $i++)
{
    //NOTE: LAST INDEX IS FOR CHARACTER BECAUSE HERE I'M CONSTRUCTING THE STRING FROM THE TIME CHARACTERS
    $current = $readings[$i]["Time"][0];
    $current = $current.$readings[$i]["Time"][1];
    $current = $current.".";
    $current = $current.$readings[$i]["Time"][3];
    $current = $current.$readings[$i]["Time"][4];
    $current = $current.",";
    array_push($labels,number_format(floatval($current),2).",");
}
?>
