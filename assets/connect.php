<?php
$MyUsername = "root";
$MyPassword = "ubuntu";
$MyHostname = "localhost";
$MyDatabase = "iotsms";

$con = mysqli_connect($MyHostname, $MyUsername, $MyPassword);

if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }

$selected = mysqli_select_db($con, $MyDatabase);

if(!$selected)
	{
		echo "\n\nERROR on selected database not found.";
	}
?>