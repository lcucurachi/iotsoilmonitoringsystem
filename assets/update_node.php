<?php
// Connect to MySQL
include("connect.php");

$id = $_GET["id"];
$lat = $_GET["lat"];
$lng = $_GET["lng"];

//Query the database
$sql="UPDATE nodes SET Latitude = '".$lat."', Longitude = '".$lng."' WHERE ID = '".$id."';";

// Retrieve result
$result = mysqli_query($con, $sql);
if(!$result)
{
    //echo "\nERROR on QUERY";
}
else
{
    //echo "\nSUCCESS";
}

//Close connection to db
mysqli_close($con);

header('Location: /manage_nodes.php'); 
?>