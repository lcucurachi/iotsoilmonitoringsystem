<?php
// Connect to MySQL
include("getNodes.php");
include("connect.php");
$numNodes = count($nodesPositions);

$sql = "SELECT MIN(Date) as EarliestDate FROM readings";
$result = mysqli_query($con, $sql);
if(empty($result))
{
    echo "\nERROR on QUERY for node date on fullcleaner.php";
}
$oldestDate = mysqli_fetch_array($result)[0];
date_default_timezone_set('Europe/London');
$newDate = new DateTime($oldestDate);

$today = false;

while($today == false)
{
    echo "EXECUTING DATE ".$newDate->format('Y-m-d')."<br>";
for($i=0; $i < $numNodes; $i++)
{
    echo "EXECUTING NODE ".$nodesPositions[$i][0]."<br>";
    //Query the database
    $sql="SELECT * FROM readings WHERE Node = ".$nodesPositions[$i][0]." AND Date LIKE '".$newDate->format('Y-m-d')."'";

    // Retrieve all records
    $result = mysqli_query($con, $sql);
    if(empty($result))
    {
        echo "\nERROR on QUERY for node data on cleaner.php";
    }
    $readings = array();
    while($row = mysqli_fetch_array($result))
    {
        array_push($readings, $row);
    }
    $numReadings = count($readings);
    
    /*
    //RETRIEVE ALL DIFFERENT VALUES OF TIME BASED ON THAT DATE
    $sql = "SELECT DISTINCT `Time`,`Date` FROM `readings` WHERE Node = ".$nodesPositions[$i][0]." AND `Date` LIKE '".$newDate->format('Y-m-d')."'";
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
    
    $numTimes = count($times);
    for($j=0; $j < $numTimes; $j++)
    {
        echo "ID ".$readings[$j]["ID"]." - ".$times[$j]."<br>";
    }
    echo "<br><br>";*/
    
    $toDelete = array();
    for($j=0; $j < $numReadings; $j++)
    {
        $current = $readings[$j];
        if($j != ($numReadings-1))
            for($k=($j+1); $k < $numReadings; $k++)
            {
                $time = $current["Time"][0].$current["Time"][1];
                $time2 = $readings[$k]["Time"][0].$readings[$k]["Time"][1];
                if($time == $time2)
                    array_push($toDelete, $readings[$k]);
                else
                    $j = $k-1;
                    break;
            }
    }
    
    //DELETE RECORDS
    for($j=0; $j < count($toDelete); $j++)
    {
        //echo $toDelete[$j]["ID"]."<br>";
        $sql="DELETE FROM readings WHERE ID = ".$toDelete[$j]["ID"];

        // Retrieve all records
        $result = mysqli_query($con, $sql);
        if(empty($result))
        {
            echo "\nERROR on QUERY for node data on cleaner.php";
        }
    }
    echo "FOUND ".count($toDelete)."<br>";
}
    
    date_default_timezone_set('Europe/London');
    $date = date("Y-m-d", time());
    $time = date("H:i", time());
    $todayDate = new DateTime($date);
    if($todayDate->format('Y-m-d') == $newDate->format('Y-m-d'))
        $today = true;
    
    $newDate->modify('+1 day');
}

//Close connection to db
mysqli_close($con);
?>