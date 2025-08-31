<?php
// Connect to MySQL
include("getThresholds.php");
include("getLast24hReadings.php");

$ids = array();

$alerts="";
$alertsInit = "None";
$count = count($readings);
for($i=0; $i<$count; $i++)
{
    
    if($readings[$i]["Soil Temperature"] > $thresholds_up[0] || $readings[$i]["Soil Moisture"] > $thresholds_up[1] || $readings[$i]["Air Temperature"] > $thresholds_up[2] || $readings[$i]["Air Humidity"] > $thresholds_up[3] || $readings[$i]["Dew Point"] > $thresholds_up[4] || $readings[$i]["Battery"] > $thresholds_up[5] || $readings[$i]["Soil Temperature"] < $thresholds_down[0] || $readings[$i]["Soil Moisture"] < $thresholds_down[1] || $readings[$i]["Air Temperature"] < $thresholds_down[2] || $readings[$i]["Air Humidity"] < $thresholds_down[3] || $readings[$i]["Dew Point"] < $thresholds_down[4] || $readings[$i]["Battery"] < $thresholds_down[5])
    {
        $count2 = count($ids);
        $found = false;
        for($j=0; $j<$count2; $j++)
        {
            if($ids[$j] == $readings[$i]["Node"])
                $found = true;
        }
        if(!$found)
        {
            array_push($ids, $readings[$i]["Node"]);
        }
    }
}

$count2 = count($ids);
for($j=0; $j<$count2; $j++)
{
    $alerts = $alerts.$ids[$j]."<br>";
    $alertsInit="Check Nodes:<br>";
}

//echo $alerts;
?>