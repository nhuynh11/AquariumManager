<?php 
echo "<body style='background-color: white'>";
if ($_SERVER['REQUEST_URI']=='/favicon.ico') exit('');

$connection = new SQLite3('/home/pi/aquarium.db');
if($connection) {
    echo "Connected";
    echo "<br>";
}

$dataPoints = array();

$currentTemps = $connection->query("SELECT time_recorded, temperature FROM aquarium_temps");

while($row=$currentTemps->fetchArray(SQLITE3_ASSOC)) {
    array_push($dataPoints, array("x" => $row['time_recorded']*1000, "y" => $row['temperature']));
}

?>
<!doctype html>
<html>
<head>
<script>
var dataArr = <?php echo json_encode($dataPoints) ?>;
window.onload = function () {
var chart = new CanvasJS.Chart("chartContainer", {
    theme: "light1",
    backgroundColor: "white",
    animationEnabled: true,
    zoomEnabled: true,
    title: {
        text: "Aquarium Temperatures"
    },
    axisX: {
        title: "Time",
        /*labelFormatter: function (e) {
            return CanvasJS.formatDate(e.value, "DD-MM-YY HH:MM");
        }*/
    },
    axisY: {
        title: "Fahrenheit Temperature"
    },
    data: [{
	lineColor: "blue",
        type: "area",
        xValueType: "dateTime",
        xValueFormatString: "DD-MM-YY HH:MM",
        // xValueFormatString: "hh:MM TT",
        dataPoints: dataArr
    }]
});
chart.render();
}
</script>

</head>
<body>
<div>
<?php
    echo '<br>';
    /*
    $datapoints = $connection->query("SELECT time_recorded, temperature FROM aquarium_temps where DATE(time_recorded) = '2019-06-12'");
    while($row=$datapoints->fetchArray(SQLITE3_ASSOC)){
        // echo 'id = ' . $row['temp_id'] . '<br>';
        echo 'time recorded = ' . $row['time_recorded'] . '<br>';
        echo 'temperature = ' . $row['temperature'] . '<br>';
    }
    */
    
?>
</div>
<div id="chartContainer" style="height: 370px; width: 100%;"></div>
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
</body>
</html>
