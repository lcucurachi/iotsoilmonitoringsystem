<?php
include("assets/session.php");
include("assets/getLast24hReadings.php");
include("assets/getNodes.php");
include("assets/weather.php");
include("assets/alerts.php");
?>

<html>

<head>
    <title>IoT Soil Monitoring System</title>
    
    <!--LOADING CHARTS JS-->
    <script src="https://github.com/chartjs/Chart.js/releases/download/v2.7.3/Chart.min.js"></script>
    
    <!--LOADING GOOGLE MAPS JS-->
    <script src="https://maps.googleapis.com/maps/api/js?key=???????????????????-c&libraries=visualization"></script>
    <script src="assets/heatmap.js"></script>
    <script src="assets/gmaps-heatmap.js"></script>
    
    <!--LOADING GOOGLE MAPS INIT FUNCTION-->
    <script>
        document.addEventListener("DOMContentLoaded", function(event) {
        initMap();
        });
        
    </script>    
    
    <!-- Theme CSS -->
    <link id="theme-style" rel="stylesheet" href="css/style.css">
    <style>
        /* Set the size of the div element that contains the map */
        #map {
            height: 400px;
            /* The height is 400 pixels */
            width: 100%;
            /* The width is the width of the web page */
        }

        #floating-panel {
            position: absolute;
            top: 10px;
            left: 25%;
            z-index: 5;
            background-color: #fff;
            padding: 5px;
            border: 1px solid #999;
            text-align: center;
            font-family: 'Roboto', 'sans-serif';
            line-height: 30px;
            padding-left: 10px;
        }

        #floating-panel {
            background-color: #fff;
            border: 1px solid #999;
            left: 25%;
            padding: 5px;
            position: absolute;
            top: 10px;
            z-index: 5;
        }

    </style>
</head>

<body>
    
    <!--BACKGROUND IMAGE-->
    <div id="bg">
        <img src="images/back (2).jpg" alt="">
    </div>

    <!--MAIN TITLE-->
    <h1 style="position: absolute; left: 750px;top: 10px;">IoT Soil Monitoring System</h1>

    <!--MENU-->
    <h2 style="position: absolute; left: 45px;top: 0px;">MENU</h2>
    <!--<form action="/manage_nodes.php" method="get" style="position: absolute; left: 15px;top: 60px;">
    <input type="submit" value="MANAGE NODES"/>
    </form>-->
    <input type="button" value="MANAGE NODES" onclick="window.open('/manage_nodes.php')" style="position: absolute; left: 15px;top: 60px;width: 130px;" />
    <!--<form action="/displayLast24hReadings.php" method="get" style="position: absolute; left: 15px;top: 90px;">
    <input type="submit" value="24h READINGS"/>
    </form>-->
    <input type="button" value="24h READINGS" onclick="window.open('/displayLast24hReadings.php')" style="position: absolute; left: 15px;top: 90px;width: 130px;" />
    <input type="button" value="THRESHOLDS" onclick="window.open('/manageThresholds.php')" style="position: absolute; left: 15px;top: 120px; width: 130px;" />
    <input type="button" value="VIRTUAL HUB" onclick="window.open('/virtualSensorsHub.php')" style="position: absolute; left: 15px;top: 150px; width: 130px;" />
    <input type="button" value="SIGN OUT" onclick="window.open('/logout.php','_top')" style="position: absolute; left: 15px;top: 180px; width: 130px;" />
    
    <!--THRESHOLDS-->
    <h2 style="position: absolute; left: 30px;top: 250px;">ALERTS</h2>
    <h4 style="position: absolute; left: 30px;top: 290px;"><?php echo $alertsInit; ?></h4>
    <?php echo "<h4 style=".'"'."position: absolute; left: 70px;top: 310px;".'"'.">".$alerts."</h4>" ?>


    <!--WHEATHER-->
    <h2 style="position: absolute; left: 900px;top: 90px;">WEATHER</h2>
    <h2 style="position: absolute; left: 880px;top: 130px;">(
        <?php
        echo $weatherData->{'name'};
    ?>,
        <?php
        echo $weatherData->{'sys'}->{"country"};
    ?>)</h2>

    <h3 style="position: absolute; left: 450px;top: 210px;">Current:
        <?php
        echo $weatherData->{'weather'}[0]->{"main"};
    ?>
    </h3>
    <h3 style="position: absolute; left: 640px;top: 210px;">
        <?php
        echo $weatherData->{'main'}->{'temp'}."°C";
    ?>
    </h3>
    <img src="https://openweathermap.org/img/w/<?php echo $weatherData->{'weather'}[0]->{"icon"}; ?>.png" style="position: absolute; left: 730px;top: 210px;">

    <h3 style="position: absolute; left: 820;top: 190px;">
        Range:
        <?php
        echo $weatherData->{'main'}->{'temp_min'}."°C";
    ?> -
        <?php
        echo $weatherData->{'main'}->{'temp_max'}."°C";
    ?>&nbsp&nbsp&nbsp&nbspSunrise:
        <?php
        $sunrise = (int)$weatherData->{'sys'}->{"sunrise"};
        date_default_timezone_set('Europe/London');
        $riseTime = date("h:i", $sunrise);
        echo $riseTime;
    ?> AM &nbsp&nbsp&nbsp&nbsp Sunset:
        <?php
        $sunset = (int)$weatherData->{'sys'}->{"sunset"};
        date_default_timezone_set('Europe/London');
        $setTime = date("h:i", $sunset);
        echo $setTime;
    ?> PM</h3>

    <h3 style="position: absolute; left: 820px;top: 240px;">
        <?php
        echo "Humidity: ".$weatherData->{'main'}->{'humidity'}."% &nbsp&nbsp;";
        echo "Pressure: ".$weatherData->{'main'}->{'pressure'}."hPa &nbsp&nbsp;";
        //echo "Visibility: ".$weatherData->{'visibility'}." &nbsp&nbsp;";
        echo "Wind Speed: ".$weatherData->{'wind'}->{'speed'}."m/s &nbsp&nbsp;";
    ?>
    </h3>

    <!--MAP-->
    <h2 style="position: absolute; left: 770px;top: 320px;">Soil Temperature and Nodes Map</h2>
    <h3 style="position: absolute; left: 890px;top: 350px;">(<?php echo $readings[0]["Date"];?>)</h3>
    <div id="floating-panel" style="position: absolute; left: 700px; top: 410px;">
        <button onclick="toggleNodes()">Toggle Nodes</button>
        <!--<button onclick="toggleVirtualNodes()">Toggle Virtual Nodes</button>-->
        <button onclick="toggleHeatmap()">Toggle Heatmap</button>
    </div>
    <div id="map" style="position: absolute; left: 450px; top: 400px; width: 1000px;">
    </div>



    <!--READINGS-->
    <h2 style="position: absolute; left: 830px;top: 810px;">24h Readings Averages</h2>
    <h3 style="position: absolute; left: 890px;top: 850px;">(Of all Nodes)</h3>
    <h3 style="position: absolute; left: 890px;top: 880px;">(<?php echo $readings[0]["Date"];?>)</h3>



    <!--GRPAHS-->
    <div class="chart-container" style="position: absolute; left: 200px; top: 950px;">
        <canvas id="chartSoilTemp" height="400" width="700"></canvas>
    </div>
    <div class="chart-container" style="position: absolute; left: 1000px; top: 950px;">
        <canvas id="chartAirTemp" height="400" width="700"></canvas>
    </div>
    <div class="chart-container" style="position: absolute; left: 200px; top: 1400px;">
        <canvas id="chartSoilMoist" height="400" width="700"></canvas>
    </div>
    <div class="chart-container" style="position: absolute; left: 1000px; top: 1400px;">
        <canvas id="chartAirHum" height="400" width="700"></canvas>
    </div>

    <div class="chart-container" style="position: absolute; left: 600px; top: 1850px;">
        <canvas id="chartDewPoint" height="400" width="700"></canvas>
    </div>
    
    <div style="position: absolute; left: 600px; top: 2300px;">
        <canvas id="chartNull" height="50"></canvas>
    </div>

    
    
    
    
    
    
    <!------------------------------------------------>
    <!------------------------------------------------>
    <!------------------------------------------------>
    <!------------------------------------------------>
    <!------------------------------------------------>
    <!------------------------------------------------>
    <!------------------------------------------------>
    <!------------------------------------------------>
    <!------------------------------------------------>
    <!--GOOGLE MAPS SCRIPT FOR LOADING NODES AND VISUALISING THEM AS HEATMAPS-->
    <script>
        var map, heatmap, heatDataNodes, heatDataVirtualNodes, nodesShown, heatShown, virtualNodesShown;
        var markers = [];
        //var virtualMarkers = [];

        // Initialize and add the map
        function initMap() {
        // map center
        var myLatlng = new google.maps.LatLng(<?php echo $nodesPositions[0][1]; ?>, <?php echo $nodesPositions[0][2]; ?>);
        
        // map options,
        var myOptions = {
          zoom: 17,
          center: myLatlng,
          mapTypeId: 'satellite'
        };
        
        // standard map
        map = new google.maps.Map(document.getElementById("map"), myOptions);
            
        // heatmap layer
            heatmap = new HeatmapOverlay(map, 
            {
                // radius should be small ONLY if scaleRadius is true (or small radius is intended)
                //"radius": 0.0001,
                "radius": 40,
                "maxOpacity": 1, 
                // scales the radius based on map zoom
                "scaleRadius": false, 
                // if set to false the heatmap uses the global maximum for colorization
                // if activated: uses the data maximum within the current map boundaries 
                //   (there will always be a red spot with useLocalExtremas true)
                "useLocalExtrema": true,
                // which field name in your data represents the latitude - default "lat"
                latField: 'lat',
                // which field name in your data represents the longitude - default "lng"
                lngField: 'lng',
                // which field name in your data represents the data value - default "value"
                valueField: 'count'
            });
            
            //HEATMAP POINTS
            heatDataNodes = getNodesHeatPoints();
            heatmap.setData(heatDataNodes);
            
            heatShown = true;
            nodesShown = true;
            
            //Load NODEs MARKERS
            loadNodesMarkers();
            
            //virtualNodesShown = true;
            //loadVirtualNodesMarkers();
        }

        function getAllNodesHeatPoints(virtualNodesData)
        {
            //[{lat: 51.5408,lng:-0.476797,count: 1.1666666666667}];
            var arrayLocations = [];
            /*for (var i = 0; i < virtualNodesData.length; i++) {            
                arrayLocations.push({lat: virtualMarkers[i].getPosition().lat(), lng: virtualMarkers[i].getPosition().lng(), count: virtualNodesData[i]});
            }*/
            var temp = getNodesHeatPoints_raw();
            for (var i = 0; i < temp["size"]; i++) {            
                arrayLocations.push({lat: temp["lat"+i.toString()], lng: temp["lng"+i.toString()], count: temp["count"+i.toString()]});
            }
            
            return {max:2, data: arrayLocations};
        }
        
        /*function loadVirtualNodesMarkers()
        {
            //Place markers for each node
            <?php
            /*$number = count($virtualnodes);
            for($i=0; $i<$number;$i++)
            {
                echo "addVirtualMarker(new google.maps.Marker({position: {lat: ".$virtualnodes[$i][1].",lng: ".$virtualnodes[$i][2]."}, map: map,title: 'Virtual Node ID:  ".$virtualnodes[$i][0]."'}));";
            }*/?>
        }*/
        
        function loadNodesMarkers()
        {
            //Place markers for each node
            <?php
            $number = count($nodesPositions);
            for($i=0; $i<$number;$i++)
            {
                echo "addMarker(new google.maps.Marker({position: {lat: ".$nodesPositions[$i][1].",lng: ".$nodesPositions[$i][2]."}, map: map,title: 'Node ID:  ".$nodesPositions[$i][0]."'}));";
            }?>
        }
        
        
        // Sets the map on all markers in the array.
        function setMapOnAllNodes(map) {
            for (var i = 0; i < markers.length; i++) {
                markers[i].setMap(map);
            }
        }
        
        // Sets the map on all markers in the array.
        function setMapOnAllVirtualNodes(map) {
            for (var i = 0; i < virtualMarkers.length; i++) {
                virtualMarkers[i].setMap(map);
            }
        }

        // Adds a marker to the map and push to the array.
        function addMarker(marker) {
            markers.push(marker);
        }
        
        // Adds a marker to the map and push to the array.
        function addVirtualMarker(marker) {
            virtualMarkers.push(marker);
        }

        /* Data points defined as a mixture of WeightedLocation and LatLng objects */
        function getNodesHeatPoints() {
            return {max:2,data: [
                <?php
                //NORMALISE DATA
                $max = $readings[0]["Soil Temperature"];
                $min = $readings[0]["Soil Temperature"];
                $num_reads = count($readings);
                if($num_reads != 1)
                {
                for($j = 0; $j < $num_reads; $j++)
                    {
                        if($readings[$j]["Soil Temperature"] > $max)
                            $max = $readings[$j]["Soil Temperature"];
                        if($readings[$j]["Soil Temperature"] < $min)
                            $min = $readings[$j]["Soil Temperature"];
                    }
                }
                
                //OUTPUT DATA
                $number = count($nodesPositions);
                for($i=0; $i<$number;$i++)
                {
                    $ID = $nodesPositions[$i][0];
                    $weight = 0;
                    //FIND CORRESPONDING TEMPERATURE
                    $num_reads = count($readings);
                    $found = false;
                        for($j = 0; $j < $num_reads; $j++)
                        {
                            if($readings[$j]["Node"] == $ID)
                            {
                                $weight = $readings[$j]["Soil Temperature"];
                                $j = $num_reads;
                                $found = true;
                            }
                        }
                    if($found = true)
                    {
                        //$weight = ($weight - $min)/($max - $min);
                        if($num_reads > 1 & $max != $min)
                            $weight = 1 + ($weight - $min)/($max - $min);
                        echo "{lat: ".$nodesPositions[$i][1].",lng:".$nodesPositions[$i][2].",count: ".$weight."},";
                    }
                }?>
            ]};
        }
        
        /* Data points defined as a mixture of WeightedLocation and LatLng objects */
        function getNodesHeatPoints_raw() {
            var raw = {
                <?php
                //OUTPUT DATA
                $number = count($nodesPositions);
                for($i=0; $i<$number;$i++)
                {
                    $ID = $nodesPositions[$i][0];
                    $weight = 0;
                    //FIND CORRESPONDING TEMPERATURE
                    $num_reads = count($readings);
                    $found = false;
                        for($j = 0; $j < $num_reads; $j++)
                        {
                            if($readings[$j]["Node"] == $ID)
                            {
                                $weight = $readings[$j]["Soil Temperature"];
                                $j = $num_reads;
                                $found = true;
                            }
                        }
                    if($found)
                        echo "lat".$i.": ".$nodesPositions[$i][1].",lng".$i.":".$nodesPositions[$i][2].",count".$i.": ".$weight.",";
                }
                echo '"'."size".'"'.':'.$number;
                ?>
            };
            return raw;
        }
        
        function toggleNodes()
        {
            if(nodesShown)
            {
                setMapOnAllNodes(null);
                nodesShown = false;
            }
            else
            {
                setMapOnAllNodes(map);
                nodesShown = true;
            }
        }
        
        function toggleVirtualNodes()
        {
            if(virtualNodesShown)
            {
                setMapOnAllVirtualNodes(null);
                virtualNodesShown = false;
            }
            else
            {
                setMapOnAllVirtualNodes(map);
                virtualNodesShown = true;
            }
        }

        function toggleHeatmap() {
            if(heatShown)
            {
                var data2 = {data: []};
                heatmap.setData(data2);
                heatShown = false;
            }
            else
            {
                heatmap.setData(heatDataNodes);
                heatShown = true;
            }
        }

    </script>

    
    
    
    
    
    <!------------------------------------------------>
    <!------------------------------------------------>
    <!------------------------------------------------>
    <!------------------------------------------------>
    <!------------------------------------------------>
    <!------------------------------------------------>
    <!------------------------------------------------>
    <!------------------------------------------------>
    <!------------------------------------------------>    
    <!------------------------------------------------>
    <!--GRAPHS CODE FOR DATA LOADING AND FORMATTING -->
    <script>
        var ctx = document.getElementById("chartSoilTemp").getContext('2d');
        var chart = new Chart(ctx, {
            type: 'line',
            data: {
                "labels": [
                    <?php
                        $number = count($labels);
                        for($i = 0; $i < $number; $i++)
                        {
                            echo $labels[$i];
                        }
                    ?>
                ],
                "datasets": [{
                    "label": "C° ",
                    "data": [
                        <?php
                        $number = count($average_soil_temp);
                        for($i = 0; $i < $number; $i++)
                        {
                            echo $average_soil_temp[$i][0];
                            echo ",";
                        }
                        ?>
                    ],
                    "fill": false,
                    "borderColor": "rgb(75, 192, 192)",
                    "lineTension": 0.1
                }]
            },
            options: {
                responsive: false,
                title: {
                    display: true,
                    text: 'Soil Temperature',
                    fontSize: 26,
                },
                scales: {
                    yAxes: [{
                        scaleLabel: {
                            display: true,
                            labelString: 'Temperature'
                        },
                        ticks: {
                            beginAtZero: false
                        }
                    }],
                    xAxes: [{
                        scaleLabel: {
                            display: true,
                            labelString: 'Time'
                        },
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
        var ctx = document.getElementById("chartAirTemp").getContext('2d');
        var chart = new Chart(ctx, {
            type: 'line',
            data: {
                "labels": [
                    <?php
                        $number = count($labels);
                        for($i = 0; $i < $number; $i++)
                        {
                            echo $labels[$i];
                        }
                    ?>
                ],
                "datasets": [{
                    "label": "C° ",
                    "data": [
                        <?php
                        $number = count($average_air_temp);
                        for($i = 0; $i < $number; $i++)
                        {
                            echo $average_air_temp[$i][0];
                            echo ",";
                        }
                        ?>
                    ],
                    "fill": false,
                    "borderColor": "rgb(75, 192, 192)",
                    "lineTension": 0.1
                }]
            },
            options: {
                responsive: false,
                title: {
                    display: true,
                    text: 'Air Temperature',
                    fontSize: 26,
                },
                scales: {
                    yAxes: [{
                        scaleLabel: {
                            display: true,
                            labelString: 'Temperature'
                        },
                        ticks: {
                            beginAtZero: false
                        }
                    }],
                    xAxes: [{
                        scaleLabel: {
                            display: true,
                            labelString: 'Time'
                        },
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
        var ctx = document.getElementById("chartSoilMoist").getContext('2d');
        var chart = new Chart(ctx, {
            type: 'line',
            data: {
                "labels": [
                    <?php
                        $number = count($labels);
                        for($i = 0; $i < $number; $i++)
                        {
                            echo $labels[$i];
                        }
                    ?>
                ],
                "datasets": [{
                    "label": "RH %",
                    "data": [
                        <?php
                        $number = count($average_soil_moist);
                        for($i = 0; $i < $number; $i++)
                        {
                            echo $average_soil_moist[$i][0];
                            echo ",";
                        }
                        ?>
                    ],
                    "fill": false,
                    "borderColor": "rgb(75, 192, 192)",
                    "lineTension": 0.1
                }]
            },
            options: {
                responsive: false,
                title: {
                    display: true,
                    text: 'Soil Moisture',
                    fontSize: 26,
                },
                scales: {
                    yAxes: [{
                        scaleLabel: {
                            display: true,
                            labelString: 'Moisture'
                        },
                        ticks: {
                            beginAtZero: false
                        }
                    }],
                    xAxes: [{
                        scaleLabel: {
                            display: true,
                            labelString: 'Time'
                        },
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
        var ctx = document.getElementById("chartAirHum").getContext('2d');
        var chart = new Chart(ctx, {
            type: 'line',
            data: {
                "labels": [
                    <?php
                        $number = count($labels);
                        for($i = 0; $i < $number; $i++)
                        {
                            echo $labels[$i];
                        }
                    ?>
                ],
                "datasets": [{
                    "label": "RH %",
                    "data": [
                        <?php
                        $number = count($average_air_hum);
                        for($i = 0; $i < $number; $i++)
                        {
                            echo $average_air_hum[$i][0];
                            echo ",";
                        }
                        ?>
                    ],
                    "fill": false,
                    "borderColor": "rgb(75, 192, 192)",
                    "lineTension": 0.1
                }]
            },
            options: {
                responsive: false,
                title: {
                    display: true,
                    text: 'Air Humidity',
                    fontSize: 26,
                },
                scales: {
                    yAxes: [{
                        scaleLabel: {
                            display: true,
                            labelString: 'Humidity'
                        },
                        ticks: {
                            beginAtZero: false
                        }
                    }],
                    xAxes: [{
                        scaleLabel: {
                            display: true,
                            labelString: 'Time'
                        },
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });


        var ctx = document.getElementById("chartDewPoint").getContext('2d');
        var chart = new Chart(ctx, {
            type: 'line',
            data: {
                "labels": [
                    <?php
                        $number = count($labels);
                        for($i = 0; $i < $number; $i++)
                        {
                            echo $labels[$i];
                        }
                    ?>
                ],
                "datasets": [{
                    "label": "C° ",
                    "data": [
                        <?php
                        $number = count($average_dew_point);
                        for($i = 0; $i < $number; $i++)
                        {
                            echo $average_dew_point[$i][0];
                            echo ",";
                        }
                        ?>
                    ],
                    "fill": false,
                    "borderColor": "rgb(75, 192, 192)",
                    "lineTension": 0.1
                }]
            },
            options: {
                responsive: false,
                title: {
                    display: true,
                    text: 'Dew Point',
                    fontSize: 26,
                },
                scales: {
                    yAxes: [{
                        scaleLabel: {
                            display: true,
                            labelString: 'Dew Point'
                        },
                        ticks: {
                            beginAtZero: false
                        }
                    }],
                    xAxes: [{
                        scaleLabel: {
                            display: true,
                            labelString: 'Time'
                        },
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });

    </script>
</body>

</html>
