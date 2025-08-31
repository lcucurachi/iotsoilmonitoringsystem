<?php
header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');

// Connect to MySQL
include("connect.php");

// Prepare the SQL statement
date_default_timezone_set('Europe/London');
$date = date("Y-m-d", time());
$time = date("H:i", time());


// Execute SQL statement
$result = mysqli_query($con, $_GET["query"]);

/*if(!$result)
{
    echo "\nERROR on QUERY";
}
else
{
    echo "SUCCESS";
}*/

mysqli_close($con);
$data = array();
while($row = mysqli_fetch_assoc($result))
{
    $data[] = $row;
}

$myJSON = json_encode($data);
//$myJSON = json_encode(array('result' => $data));
echo $myJSON;

?>