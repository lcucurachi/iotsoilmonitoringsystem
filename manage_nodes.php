<?php
include("assets/connect.php");
include("assets/getNodes.php");
include("assets/session.php");
?>

<html>

<head>
    <title>IoT Manage Nodes</title>
    <!-- Theme CSS -->
    <link id="theme-style" rel="stylesheet" href="css/style.css">
</head>

<body>
    <div id="bg">
        <img src="images/back (2).jpg" alt="">
    </div>

    <h1 style="position: absolute; left: 100px;top: 0px;">Manage Nodes</h1>
    
    <h2 style="position: absolute; left: 100px;top: 60px;">Insert Node</h2>
    <form id="add_node" action="assets/add_node.php" style="position: absolute; left: 100px;top: 110px;">
        Node ID: <input id="add_id" type="text" name="id"><br>
        Node Latitude: <input id="add_lat" type="text" name="lat"><br>
        Node Longitude: <input id="add_long" type="text" name="lng"><br>
        <input type="submit" value="Submit">
        <button onclick="getLocation()" type="button">Auto Coordinates</button>
    </form>
    <div id="error" style="position: absolute; left: 290px;top: 145px;"></div>

    <h2 style="position: absolute; left: 100px;top: 210px;">Update Node</h2>
    <form id="update_node" action="assets/update_node.php" style="position: absolute; left: 100px;top: 260px;">
        Node ID: <input type="text" name="id"><br>
        Node Latitude: <input type="text" name="lat"><br>
        Node Longitude: <input type="text" name="lng"><br>
        <input type="submit" value="Submit">
    </form>

    <h2 style="position: absolute; left: 100px;top: 360px;">Remove Node</h2>
    <form id="remove_node" action="assets/remove_node.php" style="position: absolute; left: 100px;top: 410px;">
        Node ID: <input type="text" name="id"><br>
        <input type="submit" value="Submit">
    </form>

    <h2 style="position: absolute; left: 100px;top: 480px;">Existing Nodes</h2>

    <table border="0" cellspacing="0" cellpadding="4" style="position: absolute; left: 100px;top: 530px;">
        <tr>
            <td class="table_titles">ID</td>
            <td class="table_titles">Latitude</td>
            <td class="table_titles">Longitude</td>
        </tr>

    <?php
    // Used for row color toggle
    $oddrow = true;

    $number = count($nodesPositions);
      for($i=0; $i<$number;$i++)
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
        echo '   <td'.$css_class.'>'.$nodesPositions[$i][0].'</td>';
        echo '   <td'.$css_class.'>'.$nodesPositions[$i][1].'</td>';
        echo '   <td'.$css_class.'>'.$nodesPositions[$i][2].'</td>';
        echo '</tr>';
    }	
    ?>
        
    </table>



    <script>
        x = document.getElementById("error");

        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition, showError);
            } else {
                x.innerHTML = "Error: Geolocation is not supported by this browser.";
            }
        }

        function showPosition(position) {
            document.getElementById("add_lat").value = position.coords.latitude;
            document.getElementById("add_long").value = position.coords.longitude;
        }

        function showError(error) {
            switch (error.code) {
                case error.PERMISSION_DENIED:
                    x.innerHTML = "Error: User denied the request for Geolocation."
                    break;
                case error.POSITION_UNAVAILABLE:
                    x.innerHTML = "Error: Location information is unavailable."
                    break;
                case error.TIMEOUT:
                    x.innerHTML = "Error: The request to get user location timed out."
                    break;
                case error.UNKNOWN_ERROR:
                    x.innerHTML = "Error: An unknown error occurred."
                    break;
            }
        }

    </script>
</body>

</html>
