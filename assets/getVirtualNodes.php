<?php
// Connect to MySQL
include("connect.php");

//Query the database
$sql="SELECT * FROM virtualnodes";

// Retrieve all records
$result = mysqli_query($con, $sql);
if(!$result)
{
    echo "\nERROR on QUERY for virtualnodes";
}

//Store records in a new array
$virtualnodes = array();
while( $row = mysqli_fetch_array($result) )
{
    array_push($virtualnodes, $row);
}

//Close connection to db
mysqli_close($con);
?>