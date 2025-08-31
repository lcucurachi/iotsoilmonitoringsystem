<?php
// Connect to MySQL
include("connect.php");

//Query the database
$sql="SELECT * FROM nodes";

// Retrieve all records
$result = mysqli_query($con, $sql);
if(!$result)
{
    echo "\nERROR on QUERY for nodes";
}

//Store records in a new array
$nodesPositions = array();
while( $row = mysqli_fetch_array($result) )
{
    array_push($nodesPositions, $row);
}

//Close connection to db
mysqli_close($con);
?>