<?php
include("connect.php");

// Prepare the SQL statement
$SQL = "SELECT * FROM thresholds_up";

// Execute SQL statement
$result = mysqli_query($con, $SQL);
$thresholds_up = array();

if(!$result)
{
    echo "\nERROR on QUERY";
}
else
{
    //echo "SUCCESS";
    $thresholds_up = mysqli_fetch_array($result);
}

$SQL = "SELECT * FROM thresholds_down";

// Execute SQL statement
$result = mysqli_query($con, $SQL);

$thresholds_down = array();

if(!$result)
{
    echo "\nERROR on QUERY";
}
else
{
    //echo "SUCCESS";
    $thresholds_down = mysqli_fetch_array($result);
}
?>