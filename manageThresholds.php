<?php
include("assets/getThresholds.php");
include("assets/session.php");
?>

<html>

<head>
    <title>IoT Thresholds Settings</title>
    <!-- Theme CSS -->
    <link id="theme-style" rel="stylesheet" href="css/style.css">

</head>

<body>
    <div id="bg">
        <img src="images/back (2).jpg" alt="">
    </div>

    <h1 style="position: absolute; left: 100px;top: 0px;">Thresholds Settings</h1>

    <h2 style="position: absolute; left: 100px;top: 60px;">Soil Thresholds</h2>
    <form id="add_node" action="assets/updateThresholds.php" style="position: absolute; left: 100px;top: 110px;">
        Temperature: <input type="text" name="soil_temp"><br>
        Moisture: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="soil_moist"><br>
        Operator: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="operator"><br>
        <input type="hidden" value="soil" name="type"/>
        <input type="submit" value="Submit">
    </form>
    <div id="error" style="position: absolute; left: 290px;top: 145px;"></div>

    <h2 style="position: absolute; left: 100px;top: 190px;">Air Thresholds</h2>
    <form id="update_node" action="assets/updateThresholds.php" style="position: absolute; left: 100px;top: 240px;">
        Temperature: <input type="text" name="air_temp"><br>
        Humidity: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="air_hum"><br>
        Operator: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="operator"><br>
        <input type="hidden" value="air" name="type"/>
        <input type="submit" value="Submit">
    </form>

    <h2 style="position: absolute; left: 100px;top: 320px;">Others</h2>
    <form id="remove_node" action="assets/updateThresholds.php" style="position: absolute; left: 100px;top: 370px;">
        Dew Point:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="dew_point"><br>
        Battery Level: <input type="text" name="batt_lev"><br>
        Operator: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="operator"><br>
        <input type="hidden" value="other" name="type"/>
        <input type="submit" value="Submit">
    </form>

    <h2 style="position: absolute; left: 100px;top: 490px;">Current Thresholds (>)</h2>
    <table border="0" cellspacing="0" cellpadding="4" style="position: absolute; left: 100px;top: 550px;">
        <tr>
            <td class="table_titles">Soil Temperature</td>
            <td class="table_titles">Soil Moisture</td>
            <td class="table_titles">Air Temperature</td>
            <td class="table_titles">Air Moisture</td>
            <td class="table_titles">Dew Point</td>
            <td class="table_titles">Battery Level</td>
        </tr>

        <?php
        $css_class=' class="table_cells_odd"'; 
        echo '<tr>';
        echo '   <td'.$css_class.'>'.$thresholds_up[0].'</td>';
        echo '   <td'.$css_class.'>'.$thresholds_up[1].'</td>';
        echo '   <td'.$css_class.'>'.$thresholds_up[2].'</td>';
        echo '   <td'.$css_class.'>'.$thresholds_up[3].'</td>';
        echo '   <td'.$css_class.'>'.$thresholds_up[4].'</td>';
        echo '   <td'.$css_class.'>'.$thresholds_up[5].'</td>';
        echo '</tr>';
        ?>

    </table>
    
    <h2 style="position: absolute; left: 100px;top: 630px;">Current Thresholds (<)</h2>
    <table border="0" cellspacing="0" cellpadding="4" style="position: absolute; left: 100px;top: 690px;">
        <tr>
            <td class="table_titles">Soil Temperature</td>
            <td class="table_titles">Soil Moisture</td>
            <td class="table_titles">Air Temperature</td>
            <td class="table_titles">Air Moisture</td>
            <td class="table_titles">Dew Point</td>
            <td class="table_titles">Battery Level</td>
        </tr>

        <?php
        $css_class=' class="table_cells_odd"'; 
        echo '<tr>';
        echo '   <td'.$css_class.'>'.$thresholds_down[0].'</td>';
        echo '   <td'.$css_class.'>'.$thresholds_down[1].'</td>';
        echo '   <td'.$css_class.'>'.$thresholds_down[2].'</td>';
        echo '   <td'.$css_class.'>'.$thresholds_down[3].'</td>';
        echo '   <td'.$css_class.'>'.$thresholds_down[4].'</td>';
        echo '   <td'.$css_class.'>'.$thresholds_down[5].'</td>';
        echo '</tr>';
        ?>

    </table>
</body>

</html>
