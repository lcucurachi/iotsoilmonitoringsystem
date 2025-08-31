<?php
// Connect to MySQL
include("assets/connect.php");
include("assets/session.php");
include("assets/getThresholds.php");

date_default_timezone_set('Europe/London');

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $currentDate = $_POST["currentDate"];    
    $newDate = new DateTime($currentDate);
    if($_POST["action"] == "PREVIOUS") {
        $newDate->modify('-1 day');
    }
    else if($_POST["action"] == "NEXT") {
        $newDate->modify('+1 day');
    }
    
    //Query the database
    $sql="SELECT * FROM readings WHERE Date LIKE '".$newDate->format('Y-m-d')."'";

    // Retrieve all records
    $result = mysqli_query($con, $sql);
    if(!empty($result))
    {
        //echo "\nERROR on QUERY for 24HReadings";
    }
    $row = mysqli_fetch_array($result);
    
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
    $currentDate = $newDate->format('Y-m-d');
}
else
{
    // Get current date
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
    $currentDate = $newDate->format('Y-m-d');
}

mysqli_close($con);
?>

<!DOCTYPE html>
<html>

<head>
    <title>IoT Readings Table</title>
</head>

<body>

    <div id="bg">
        <img src="images/back (2).jpg" alt="">
    </div>
    <!-- Theme CSS -->
    <link id="theme-style" rel="stylesheet" href="css/style.css">

    <h2 style="position: absolute; left: 20px;top: 0px;">Readings <?php echo $currentDate; ?></h2>
    
    
    
    <!--BUTTONS-->
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <input type="submit" name="action" value="PREVIOUS" style="position: absolute; left: 300px;top: 25px;width: 100px;" />
        <input type="hidden" value="<?php echo $currentDate ?>" name="currentDate"/>
    </form>
    <h2 style="position: absolute; left: 420px;top: 0px;"> - </h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <input type="submit" name="action" value="NEXT" style="position: absolute; left: 450px;top: 25px;width: 100px;" />
        <input type="hidden" value="<?php echo $currentDate ?>" name="currentDate"/>
    </form>
    <h2 style="position: absolute; left: 600px;top: 0px;">GO TO </h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <input type="submit" name="action" value="GO" style="position: absolute; left: 800px;top: 25px;width: 50px;" />
        <input type="text" value="<?php echo $currentDate ?>" name="currentDate" style="position: absolute; left: 680px;top: 25px;width: 100px;"/>
    </form>
    <h2 style="position: absolute; left: 900px;top: 0px;">TODAY</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <input type="submit" name="action" value="TODAY" style="position: absolute; left: 980px;top: 25px;width: 70px;" />
    </form>
    
    

    <table border="0" cellspacing="0" cellpadding="4" style="position: absolute; left: 20px;top: 60px;">
        <tr>
            <td class="table_titles">ID</td>
            <td class="table_titles">Node</td>
            <td class="table_titles">Soil Temperature</td>
            <td class="table_titles">Soil Moisture</td>
            <td class="table_titles">Air Temperature</td>
            <td class="table_titles">Air Humidity</td>
            <td class="table_titles">Dew Point</td>
            <td class="table_titles">Battery Level</td>
            <td class="table_titles">Date</td>
            <td class="table_titles">Time</td>
        </tr>
        <?php
        // Used for row color toggle
        $oddrow = true;

        // process every record
        $number = count($readings);
        //echo "<h2 style='position: absolute; left: 500px;top: 0px;'>".$number."</h2>";
        for($i = 0; $i < $number; $i++)
        {
            if ($oddrow) 
            { 
                $css_class=' class="table_cells_odd"'; 
            }
            else
            { 
                $css_class=' class="table_cells_even"'; 
            }

            $oddrow = !$oddrow;

            echo '<tr>';        
            echo '   <td'.$css_class.'>'.$readings[$i]["ID"].'</td>';
            echo '   <td'.$css_class.'>'.$readings[$i]["Node"].'</td>';
            
            if($readings[$i]["Soil Temperature"] > $thresholds_up[0] || $readings[$i]["Soil Temperature"] < $thresholds_down[0])
                echo '   <td'.$css_class.'>'."<font color=".'"'."red".'"'.">".$readings[$i]["Soil Temperature"].'</font></td>';
            else
                echo '   <td'.$css_class.'>'.$readings[$i]["Soil Temperature"].'</td>';
            
            if($readings[$i]["Soil Moisture"] > $thresholds_up[1] || $readings[$i]["Soil Moisture"] < $thresholds_down[1])
                echo '   <td'.$css_class.'>'."<font color=".'"'."red".'"'.">".$readings[$i]["Soil Moisture"].'</font></td>';
            else
                echo '   <td'.$css_class.'>'.$readings[$i]["Soil Moisture"].'</td>';
            
            if($readings[$i]["Air Temperature"] > $thresholds_up[2] || $readings[$i]["Air Temperature"] < $thresholds_down[2])
                echo '   <td'.$css_class.'>'."<font color=".'"'."red".'"'.">".$readings[$i]["Air Temperature"].'</font></td>';
            else
                echo '   <td'.$css_class.'>'.$readings[$i]["Air Temperature"].'</td>';
            
            if($readings[$i]["Air Humidity"] > $thresholds_up[3] || $readings[$i]["Air Humidity"] < $thresholds_down[3])
                echo '   <td'.$css_class.'>'."<font color=".'"'."red".'"'.">".$readings[$i]["Air Humidity"].'</font></td>';
            else
                echo '   <td'.$css_class.'>'.$readings[$i]["Air Humidity"].'</td>';
            
            if($readings[$i]["Dew Point"] > $thresholds_up[4] || $readings[$i]["Dew Point"] < $thresholds_down[4])
                echo '   <td'.$css_class.'>'."<font color=".'"'."red".'"'.">".$readings[$i]["Dew Point"].'</font></td>';
            else
                echo '   <td'.$css_class.'>'.$readings[$i]["Dew Point"].'</td>';
            
            if($readings[$i]["Battery"] > $thresholds_up[5] || $readings[$i]["Battery"] < $thresholds_down[5])
                echo '   <td'.$css_class.'>'."<font color=".'"'."red".'"'.">".$readings[$i]["Battery"].'</font></td>';
            else
                echo '   <td'.$css_class.'>'.$readings[$i]["Battery"].'</td>';
            
            echo '   <td'.$css_class.'>'.$readings[$i]["Date"].'</td>';
            echo '   <td'.$css_class.'>'.$readings[$i]["Time"].'</td>';
            echo '</tr>';
        }
    ?>
    </table>
</body>

</html>