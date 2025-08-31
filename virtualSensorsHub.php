<?php
// Connect to MySQL
include("assets/connect.php");

$currentNode = "1";
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $currentNode = $_POST["currentNode"];
}

// Get current date
date_default_timezone_set('Europe/London');
$date = date("Y-m-d", time());
$time = date("H:i", time());


$found = false;
$row;
$newDate = new DateTime($date);
while(!$found)
{
    //Query the database
    $sql="SELECT * FROM readings WHERE `Node` = ".$currentNode." AND Date LIKE '".$newDate->format('Y-m-d')."'";

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
$readings = array();
array_push($readings, $row);
while($row = mysqli_fetch_array($result))
{
    array_push($readings, $row);
}



//RETRIEVE ALL DIFFERENT VALUES OF TIME BASED ON THAT DATE
$sql = "SELECT DISTINCT `Time`,`Date` FROM `readings` WHERE `Node` = ".$currentNode." AND `Date` LIKE '".$newDate->format('Y-m-d')."'";
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



//RETRIEVE ALL FIELDS
$number = count($times);
$soil_temp = array();
for($i = 0; $i < $number; $i++)
{
    $sql = "SELECT `Soil Temperature` FROM `readings` WHERE `Date` LIKE '".$newDate->format('Y-m-d')."'"." AND `Time`LIKE '".$times[$i]."'"." AND `Node` = ".$currentNode;
    $result = mysqli_query($con, $sql);
    if(empty($result))
    {
        echo "\nERROR on QUERY for NodeReadings";
    }
    while($row = mysqli_fetch_array($result))
    {
        array_push($soil_temp, $row);
    }
}

/**
* Create Labels array for graphs
* This part is converting the time into a label using a character approach 
* Basically the graph won't visualise unless the labes are correctly formated
* so it is necessary to format the time to make sure is using 4 digits total 
* for each data point
**/
$labels = array();
$number = count($times);
for($i = 0; $i < $number; $i++)
{
    //NOTE: LAST INDEX IS FOR CHARACTER BECAUSE HERE I'M CONSTRUCTING THE STRING FROM THE TIME CHARACTERS
    $current = $times[$i][0];
    $current = $current.$times[$i][1];
    $current = $current.".";
    $current = $current.$times[$i][3];
    $current = $current.$times[$i][4];
    $current = $current.",";
    array_push($labels,number_format(floatval($current),2).",");
}

include("assets/getNodes.php");
?>










<html>

<head>
    <title>IoT Virtual Sensors Hub</title>

    <!-- Theme CSS -->
    <link id="theme-style" rel="stylesheet" href="css/style.css">

    <!--LOADING CHARTS JS-->
    <script src="https://github.com/chartjs/Chart.js/releases/download/v2.7.3/Chart.min.js"></script>

    <!--LOADING NN AND JQUERY JS-->
    <script src="assets/jquery-3.3.1.js"></script>

</head>

<body>
    <div id="bg">
        <img src="images/back (2).jpg" alt="">
    </div>

    <!--MAIN TITLE-->
    <h1 style="position: absolute; left: 780px;top: 10px;">Virtual Sensors Hub</h1>
    <h2 style="position: absolute; left: 890px;top: 50px;">Node
        <?php echo $currentNode ?>
    </h2>

    <!--GRPAHS-->
    <h2 style="position: absolute; left: 450px; top: 250px;">Magic Bean Node</h2>
    <div class="chart-container" style="position: absolute; left: 200px; top: 300px;">
        <canvas id="chartNode" height="400" width="700"></canvas>
    </div>

    <h2 style="position: absolute; left: 1250px; top: 250px;">Virtual Bean Node</h2>
    <div class="chart-container" style="position: absolute; left: 1000px; top: 300px;">
        <canvas id="chartVirtualNode" height="400" width="700"></canvas>
    </div>
    
    <h2 id="error" style="position: absolute; left: 890px;top: 200px;">
    </h2>

    <!--BUTTONS-->
    <form id="form_node_select" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <input type="submit" name="action" value="LOAD NODE ID" style="position: absolute; left: 810px;top: 120px;width: 120px;" />
        <select name="currentNode" form="form_node_select" style="position: absolute; left: 940px;top: 120px;width: 50; text-align:center;">
            <?php
                $nodes = count($nodesPositions);
                for($i = 0; $i < $nodes; $i++)
                {
                    echo "<option value=".'"'.$nodesPositions[$i][0].'"'.">".$nodesPositions[$i][0]."</option>";
                }
            ?>
        </select>
        <!--<input type="text" value="<?php echo $currentNode ?>" name="currentNode" style="position: absolute; left: 940px;top: 120px;width: 50; text-align:center;" />-->
    </form>




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
        var nodeDataLabels = [<?php
                        $number = count($labels);
                        for($i = 0; $i < $number; $i++)
                        {
                            echo $labels[$i];
                        }
                    ?>];
        var virtualNodeDataLabels = [<?php
                        $number = count($labels);
                        for($i = 1; $i < $number; $i++)
                        {
                            echo $labels[$i];
                        }
                    ?>];

        var ctx1 = document.getElementById("chartNode").getContext('2d');
        var chart1 = new Chart(ctx1, {
            type: 'line',
            data: {
                "labels": nodeDataLabels,
                "datasets": [{
                    "label": "C° ",
                    "data": [
                        <?php
                        $number = count($soil_temp);
                        for($i = 0; $i < $number; $i++)
                        {
                            echo $soil_temp[$i][0];
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

        var ctx2 = document.getElementById("chartVirtualNode").getContext('2d');
        var chart2 = new Chart(ctx2, {
            type: 'line',
            data: {
                "labels": virtualNodeDataLabels,
                "datasets": [{
                    "label": "C° ",
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


        function addData(chart, label, data) {
            //chart.data.labels.push(label);
            chart.data.datasets.forEach((dataset) => {
                dataset.data.push(data);
            });
            chart.update();
        }




        function getVirtualData() {
            var nodeID = "<?php echo $currentNode ?>";
            var date = "<?php echo $newDate->format('Y-m-d'); ?>";
            return jQuery.ajax({
                type: "POST",
                url: "assets/virtual_data.php",
                dataType: "json",
                data: {
                    //arguments: [1, 2],
                    function: "getPredictions",
                    date: date,
                    nodeID: nodeID
                },
                success: function(obj, statusText) {
                    if (!('error' in obj)) {
                        //document.getElementById("out").innerHTML = obj.result["lat"];
                        //console.log("SUCCESS");
                        //return obj;
                    } else {
                        //console.log(obj.error);
                        //console.log("PHP ERROR: \n" + JSON.stringify(obj.error));
                        //document.getElementById("out").innerHTML = "PHP ERROR: \n" + JSON.stringify(obj.error);
                        //return null;
                    }
                },
                error: function(error) {
                    //console.log(error);
                    //console.log("JQUERY ERROR: \n" + JSON.stringify(error));
                    //document.getElementById("out").innerHTML = "JQUERY ERROR: \n" + JSON.stringify(error);
                    //return null;
                }
            });
        }

        $.when(getVirtualData()).done(function(query) {
            // the code here will be executed when all four ajax requests resolve.
            // a1, a2, a3 and a4 are lists of length 3 containing the response text,
            // status, and jqXHR object for each of the four ajax calls respectively.
            //console.log(query);

            if (!(query["result"] === undefined) && query["result"] != null) {
                console.log("RECEIVED DATA FROM QUERY");
                //ADDING MAKERS
                var total = query["result"]["size"];
                for (var i = 0; i < total-1; i++) {
                    var soil_temp = query["result"][i.toString()];
                    addData(chart2, null, soil_temp);
                }
                var error = query["result"][(total-1).toString()];
                document.getElementById("error").innerHTML = "MSE: " + error;
                //virtualNodeDataLabels = 09;
                //virtualNodeData = 10;
                //addData(chart2, virtualNodeDataLabels, virtualNodeData);
            } else
                console.log("VIRTUAL DATA: Error retrieving virtual sensors data...");
        });

    </script>

</body>

</html>
